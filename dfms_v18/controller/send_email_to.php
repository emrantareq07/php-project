<?php
// Uncomment these lines for debugging locally
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['pdf']) && isset($_POST['emails'])) {
        $emails = explode(',', $_POST['emails']); // Multiple emails separated by commas
        $pdfFile = $_FILES['pdf']['tmp_name'];
        $pdfName = $_FILES['pdf']['name'];

        // Validate uploaded PDF
        if (!is_uploaded_file($pdfFile)) {
            echo "No PDF file uploaded.";
            exit;
        }

        // Validate email addresses
        $validEmails = array_filter($emails, function ($email) {
            return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        });

        if (empty($validEmails)) {
            echo "No valid email addresses provided.";
            exit;
        }

        // Include PHPMailer
    require('smtp/PHPMailerAutoload.php'); 
    require('Mysqldump/Mysqldump.php'); // If you need this for other purposes

        $mail = new PHPMailer;

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "pmisict@gmail.com"; // Your Gmail address
            $mail->Password = "jhuimijoiytxcbrw"; // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->SMTPSecure = 'tls'; // Use TLS encryption
            $mail->Port = 587;

            // Email Configuration
            $mail->setFrom("pmisict@gmail.com", "PMIS ICT"); // Sender email and name
            foreach ($validEmails as $email) {
                $mail->addAddress(trim($email)); // Add recipients
            }
            $mail->Subject = 'Daily Production & Plant Status Report';
            $mail->Body = 'Please find the attached production report.';
            $mail->addAttachment($pdfFile, $pdfName); // Attach the PDF

            // Debugging output
            $mail->SMTPDebug = 2; // Set to 2 for detailed output during debugging
            $mail->Debugoutput = 'html';

            // Send Email
            if ($mail->send()) {
                echo "Emails sent successfully!";
            } else {
                echo "Failed to send emails. Error: " . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo "Mailer Error: " . $e->getMessage();
        }
    } else {
        echo "Required fields are missing.";
    }
}
?>
