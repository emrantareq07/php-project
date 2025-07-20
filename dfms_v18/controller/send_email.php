<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emails = explode(',', $_POST['emails']); // Get email addresses from POST data
    $pdfFile = $_FILES['pdf']['tmp_name']; // Get the uploaded PDF file

    // Include PHPMailer Autoload and Mysqldump
    require('smtp/PHPMailerAutoload.php'); 
    require('Mysqldump/Mysqldump.php'); // If you need this for other purposes

    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP
        $mail->SMTPAuth = true;
        $mail->Username = "pmisict@gmail.com"; // Your email address
        $mail->Password = "jhuimijoiytxcbrw"; // App password for Gmail
        $mail->SMTPSecure = 'tls'; // Use TLS encryption
        $mail->Port = 587; // TLS port

        // Email Sender
        $mail->setFrom("pmisict@gmail.com", "PMIS ICT"); // Update 'From' name if necessary

        // Add recipients
        foreach ($emails as $email) {
            $mail->addAddress(trim($email)); // Trim and add each email
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'PDF Preview';
        $mail->Body = 'Please find the attached PDF.';
        
        // Attach PDF
        $mail->addAttachment($pdfFile, 'Preview.pdf');

        // Send the email
        if ($mail->send()) {
            echo "Email(s) sent successfully.";
        } else {
            echo "Failed to send email(s): " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}
?>
