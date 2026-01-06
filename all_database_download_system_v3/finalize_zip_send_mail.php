<?php
ob_start(); // 🔥 VERY IMPORTANT

session_name('db_backup_session_v1');
session_start();

require_once __DIR__ . '/config_send_mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

/* ==========================
   AUTH CHECK
========================== */
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

/* ==========================
   INPUT
========================== */
$input = json_decode(file_get_contents('php://input'), true);
$stamp = $input['stamp'] ?? null;
if (!$stamp) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Missing stamp']);
    exit;
}

/* ==========================
   COLLECT SQL FILES
========================== */
$files = glob(BACKUP_DIR . '*' . $stamp . '.sql');
if (empty($files)) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'No dump files found']);
    exit;
}

/* ==========================
   CREATE ZIP
========================== */
$zipName = 'database_backup_' . date('Ymd_His') . '.zip';
$zipPath = BACKUP_DIR . $zipName;

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Cannot create zip']);
    exit;
}

foreach ($files as $f) {
    $zip->addFile($f, basename($f));
}
$zip->close();

/* ==========================
   DELETE SQL FILES
========================== */
foreach ($files as $f) {
    @unlink($f);
}

/* ==========================
   SEND EMAIL
========================== */
if (EMAIL_NOTIFY && MAIL_TO !== '') {
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_TO);

        $downloadUrl = 'http://' . $_SERVER['HTTP_HOST'] .
            dirname($_SERVER['SCRIPT_NAME']) . '/backups/' . $zipName;

        $mail->Subject = "Database Backup Completed: {$zipName}";
        $mail->Body =
            "Backup completed successfully.\n\n" .
            "File: {$zipName}\n" .
            "Size: " . round(filesize($zipPath) / 1024, 2) . " KB\n" .
            "Download: {$downloadUrl}";

        $mail->addAttachment($zipPath, $zipName);
        $mail->send();

    } catch (Exception $e) {
        error_log('Backup email failed: ' . $mail->ErrorInfo);
    }
}

/* ==========================
   CLEAN OLD BACKUPS
========================== */
$all = glob(BACKUP_DIR . '*.zip');
usort($all, fn($a, $b) => filemtime($b) - filemtime($a));

if (count($all) > KEEP_LAST) {
    foreach (array_slice($all, KEEP_LAST) as $d) {
        @unlink($d);
    }
}

/* ==========================
   JSON RESPONSE
========================== */
ob_clean();
echo json_encode([
    'success'   => true,
    'filename'  => $zipName,
    'file'      => 'backups/' . $zipName,
    'size'      => filesize($zipPath),
    'size_text' => round(filesize($zipPath) / 1024, 2) . ' KB'
]);
exit;
