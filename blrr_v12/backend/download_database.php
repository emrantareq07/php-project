<?php
function createDatabaseBackup($dbName, $outputFile) { 
    include_once('Mysqldump/Mysqldump.php');
    include('smtp/PHPMailerAutoload.php');

    try {
        // Dump specified database
        $dump = new Ifsnop\Mysqldump\Mysqldump("mysql:host=localhost;dbname=$dbName", 'root', '');
        $dump->start($outputFile);

        // Also save a copy in ../db/ directory
        $dump1 = new Ifsnop\Mysqldump\Mysqldump("mysql:host=localhost;dbname=$dbName", 'root', '');
        $f = date('d-m-Y');
        $backupFile = "../db/{$dbName}_$f.sql";
        $dump1->start($backupFile);

        // Send email with backup
        $mail = new PHPMailer(true);
        $mail->isSMTP();
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
        $mail->Subject = "$dbName Database Backup " . $f;
        $mail->Body = "Database Backup for $dbName";
        $mail->AddAttachment($backupFile);
        $mail->SMTPOptions = array('ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        ));
        
        if($mail->send()) {
            // Email sent successfully
        } else {
            // Email failed
        }

        return $backupFile;

    } catch (Exception $e) {
        die('Database backup failed: ' . $e->getMessage());
    }
}

// Handle NEW database download
if (isset($_POST['submit_new'])) {
    $dbName = 'blrr_db'; // Your new database name
    $f = date('d-m-Y');
    $backupFilename = "../db/{$dbName}_$f.sql";
    
    createDatabaseBackup($dbName, $backupFilename);
    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($backupFilename) . '"');
    header('Content-Length: ' . filesize($backupFilename));
    readfile($backupFilename);
    exit;
}

// Handle OLD database download
if (isset($_POST['submit_old'])) {
    $dbName = 'blrr_db_old'; // Your old database name - CHANGE THIS
    $f = date('d-m-Y');
    $backupFilename = "../db/{$dbName}_$f.sql";
    
    createDatabaseBackup($dbName, $backupFilename);
    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($backupFilename) . '"');
    header('Content-Length: ' . filesize($backupFilename));
    readfile($backupFilename);
    exit;
}

// If directly accessed, redirect
// header("Location: ../index.php");
// exit;
?>