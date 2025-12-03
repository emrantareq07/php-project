<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    echo json_encode(['success'=>false,'message'=>'Not authenticated']); exit;
}
$input = json_decode(file_get_contents('php://input'), true);
$stamp = $input['stamp'] ?? null;
if (!$stamp) { echo json_encode(['success'=>false,'message'=>'Missing stamp']); exit; }

$files = glob(BACKUP_DIR . '*' . $stamp . '.sql');
if (empty($files)) { echo json_encode(['success'=>false,'message'=>'No dump files found']); exit; }

// create zip
$zipName = 'database_backup_' . date('Ymd_His') . '.zip';
$zipPath = rtrim(BACKUP_DIR,'/\\') . DIRECTORY_SEPARATOR . $zipName;
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE)!==true) {
    echo json_encode(['success'=>false,'message'=>'Cannot create zip']); exit;
}
foreach ($files as $f) $zip->addFile($f, basename($f));
$zip->close();

// delete sql files
foreach ($files as $f) @unlink($f);

// send email notification if enabled
if (defined('EMAIL_NOTIFY') && EMAIL_NOTIFY && !empty(NOTIFY_TO)) {
    $sub = "Database Backup Completed: $zipName";
    $body = "A database backup has been created.\n\nFile: $zipName\nSize: " . filesize($zipPath) . " bytes\nDownload: " . (isset($_SERVER['HTTP_HOST']) ? ('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/backups/' . $zipName) : $zipName);
    // try mail()
    @mail(NOTIFY_TO, $sub, $body, "From: backup@" . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
}

// auto-delete older backups (keep last KEEP_LAST)
$all = glob(BACKUP_DIR . '*.zip');
usort($all, function($a,$b){ return filemtime($b)-filemtime($a); });
if (count($all) > KEEP_LAST) {
    $toDel = array_slice($all, KEEP_LAST);
    foreach ($toDel as $d) @unlink($d);
}

echo json_encode([
    'success' => true,
    'filename' => basename($zipPath),
    'file' => 'backups/' . basename($zipPath),
    'size' => filesize($zipPath),
    'size_text' => round(filesize($zipPath)/1024,2) . ' KB'
]);
