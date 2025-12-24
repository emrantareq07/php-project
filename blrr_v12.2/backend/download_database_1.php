<?php
// download_database.php
date_default_timezone_set('Asia/Dhaka');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

function createDatabaseBackup($dbName, $outputFile) {
    // Attempt to load composer autoload first
    $baseDir = __DIR__;
    if (file_exists($baseDir . '/vendor/autoload.php')) {
        require_once $baseDir . '/vendor/autoload.php';
    } else {
        // fallback includes (adjust paths if your library is elsewhere)
        if (file_exists($baseDir . '/Mysqldump/Mysqldump.php')) {
            require_once $baseDir . '/Mysqldump/Mysqldump.php';
        } else {
            throw new Exception('Mysqldump library not found. Install via Composer or place Mysqldump in /Mysqldump.');
        }

        // try PHPMailer fallback (optional)
        if (file_exists($baseDir . '/smtp/PHPMailerAutoload.php')) {
            require_once $baseDir . '/smtp/PHPMailerAutoload.php';
        }
    }

    try {
        $dsn = "mysql:host=localhost;dbname={$dbName}";
        $dbUser = 'root';
        $dbPass = '';

        // Create dump to the requested outputFile (absolute path recommended)
        $dump = new Ifsnop\Mysqldump\Mysqldump($dsn, $dbUser, $dbPass);
        $dump->start($outputFile);

        // Also save a copy to ../db/
        $f = date('d-m-Y');
        $backupFile = dirname(__DIR__) . "/db/{$dbName}_{$f}.sql";
        $dump2 = new Ifsnop\Mysqldump\Mysqldump($dsn, $dbUser, $dbPass);
        $dump2->start($backupFile);

        // Try to send email if PHPMailer is available (non-fatal)
        if (class_exists('PHPMailer') || class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
            try {
                // Support both PHPMailer v5 (PHPMailer class) and v6 (namespace)
                if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->Port = 587;
                    $mail->SMTPSecure = 'tls';
                    $mail->SMTPAuth = true;
                    $mail->Username = "pmisict@gmail.com";    // consider moving credentials to env
                    $mail->Password = "jhuimijoiytxcbrw";    // use app password or env var
                    $mail->setFrom("pmisict@gmail.com", "DB Backup");
                    $mail->addAddress("emrantareq09@gmail.com");
                    $mail->addAddress("shanewornbhadra@gmail.com");
                    $mail->isHTML(true);
                    $mail->Subject = "{$dbName} Database Backup {$f}";
                    $mail->Body = "Database backup for {$dbName} (date: {$f})";
                    $mail->addAttachment($backupFile);
                    $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
                    $mail->send();
                } else {
                    // older PHPMailerAutoload path (v5)
                    $mail = new PHPMailer(true);
                    $mail->IsSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->Port = 587;
                    $mail->SMTPSecure = "tls";
                    $mail->SMTPAuth = true;
                    $mail->Username = "pmisict@gmail.com";
                    $mail->Password = "jhuimijoiytxcbrw";
                    $mail->SetFrom("pmisict@gmail.com");
                    $mail->addAddress("emrantareq09@gmail.com");
                    $mail->addAddress("shanewornbhadra@gmail.com");
                    $mail->IsHTML(true);
                    $mail->Subject = "{$dbName} Database Backup {$f}";
                    $mail->Body = "Database backup for {$dbName} (date: {$f})";
                    $mail->AddAttachment($backupFile);
                    $mail->SMTPOptions = array('ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ));
                    $mail->Send();
                }
            } catch (Exception $e) {
                // mail failed — non-fatal, continue
            }
        }

        return $backupFile;
    } catch (Exception $e) {
        throw new Exception("Database dump failed: " . $e->getMessage());
    }
}

// Decide which DB to dump
if (isset($_POST['submit_new']) || isset($_POST['submit_old'])) {
    $isOld = isset($_POST['submit_old']);
    $dbName = $isOld ? 'blrr_db_old' : 'blrr_db';
    $f = date('d-m-Y');
    $backupFilename = dirname(__DIR__) . "/db/{$dbName}_{$f}.sql";
    try {
        $createdFile = createDatabaseBackup($dbName, $backupFilename);
    } catch (Exception $ex) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 0, 'message' => $ex->getMessage()]);
        exit;
    }

    if (!file_exists($createdFile)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 0, 'message' => 'Backup file not found after dump.']);
        exit;
    }

    // Force file download (no extra output before these headers)
    if (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($createdFile) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($createdFile));
    readfile($createdFile);
    exit;
}

// If we reach here, redirect (or show JSON)
// header('Location: ../index.php');
// exit;
