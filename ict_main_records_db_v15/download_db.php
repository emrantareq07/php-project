<?php
function createDatabaseBackup($outputFile){ 
    include_once('Mysqldump/Mysqldump.php');
    include('smtp/PHPMailerAutoload.php');

    // Create DB dump
    $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=ict_main_records_db', 'root', '');
    $dump->start($outputFile);

    $f = date('d-m-Y');
    $dump1 = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=ict_main_records_db', 'root', '');
    $dump1->start("db/ict_main_records_db$f.sql");

    // Configure PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "pmisict@gmail.com";  
        $mail->Password   = "jhuimijoiytxcbrw"; // Gmail App Password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = 'tls';

        $mail->Port       = 587;

        $mail->setFrom("pmisict@gmail.com", "ICT Maintenance Record DB Backup");
        $mail->addAddress("emrantareq09@gmail.com");

        $mail->isHTML(true);
        $mail->Subject = "ICT Maintenance Database Backup " . $f;
        $mail->Body    = "Database Backup attached.";
        $mail->addAttachment("db/ict_main_records_db$f.sql");

        // Optional debugging
        // $mail->SMTPDebug  = 2;
        // $mail->Debugoutput = 'html';

        $mail->send();
        // echo "Backup email sent!";
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['submit'])) {
    $backupFilename = 'db/ict_main_records_db_' . date('d-m-Y') . '.sql';
    createDatabaseBackup($backupFilename);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $backupFilename . '"');
    readfile($backupFilename);
    exit;
}
?>
