<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate and Send PDF</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <div id="print-content">
        <h3 class="text-center">Bangladesh Chemical Industries Corporation</h3>
        <h4 class="text-center text-success">Daily Production & Plant Status Report</h4>
        <p>Details about the production status...</p>
    </div>

    <button class="btn btn-warning mt-3" onclick="openEmailModal()">
        <i class="fa fa-paper-plane"></i> Send Email
    </button>
</div>

<!-- Email Input Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="emailModalLabel"><b>Enter Recipient Emails</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="emailInput" placeholder="Enter emails separated by commas">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processEmails()">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openEmailModal() {
        const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
        emailModal.show();
    }

    function validateEmails(emailList) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailList.every(email => emailRegex.test(email.trim()));
    }

    function processEmails() {
        const emailInput = document.getElementById("emailInput").value;
        const emails = emailInput.split(",").map(email => email.trim());

        if (!validateEmails(emails)) {
            alert("Please enter valid email addresses.");
            return;
        }

        // Hide the modal
        const emailModal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
        emailModal.hide();

        // Send the email request to the server
        const formData = new FormData();
        formData.append("emails", emails.join(","));
        formData.append("action", "sendEmail");

        fetch("", {
            method: "POST",
            body: formData,
        })
            .then(response => response.text())
            .then(result => {
                alert(result);
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while sending the email.");
            });
    }
</script>

<?php
    require('smtp/PHPMailerAutoload.php'); // Load Composer's autoload for PHPMailer and dompdf

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sendEmail') {
    $emails = explode(',', $_POST['emails']);

    // Validate emails
    $validEmails = array_filter($emails, function ($email) {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
    });

    if (empty($validEmails)) {
        echo "No valid email addresses provided.";
        exit;
    }

    // Generate PDF
    $dompdf = new Dompdf();
    $htmlContent = '
        <h3 class="text-center">Bangladesh Chemical Industries Corporation</h3>
        <h4 class="text-center text-success">Daily Production & Plant Status Report</h4>
        <p>Details about the production status...</p>
    ';
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();

    $pdfFilePath = 'report.pdf';
    file_put_contents($pdfFilePath, $output);

    // Send email with PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pmisict@gmail.com'; // Your Gmail address
        $mail->Password = 'jhuimijoiytxcbrw'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('pmisict@gmail.com', 'PMIS ICT');
        foreach ($validEmails as $email) {
            $mail->addAddress(trim($email));
        }

        $mail->Subject = 'Daily Production & Plant Status Report';
        $mail->Body = 'Please find the attached production report.';
        $mail->addAttachment($pdfFilePath);

        if ($mail->send()) {
            echo "Emails sent successfully!";
        } else {
            echo "Failed to send emails. Error: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $e->getMessage();
    } finally {
        if (file_exists($pdfFilePath)) {
            unlink($pdfFilePath); // Clean up the temporary file
        }
    }
}
?>
</body>
</html>

---------------------------------------
<style>
    /* Apply margin-top to the modal dialog */
    .modal-dialog {
        margin-top: 60px !important;
    }

    @media print {
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        #print-content {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        .hide-in-pdf,
        #print-btn,
        #search-btn,
        #login-btn,
        #date,
        #navbarDropdown,
        #reload-btn,
        #logout {
            display: none !important;
        }
    }
</style>

<!-- Button to open the modal -->
<button class="btn btn-warning hide-in-pdf" onclick="openEmailModal()">
    <i class="fa fa-paper-plane"></i> Send
</button>

<!-- Email Input Modal -->
<div class="modal fade my-4 mt-4" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="emailModalLabel"><b>Enter Email(s)</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email Address(es)</label>
                    <input type="text" class="form-control" id="emailInput" placeholder="Enter emails separated by commas">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processEmails()">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openEmailModal() {
        const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
        emailModal.show();
    }

    function validateEmails(emailList) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailList.every(email => emailRegex.test(email.trim()));
    }

    function processEmails() {
        const emailInput = document.getElementById("emailInput").value;
        const emails = emailInput.split(",").map(email => email.trim());

        // Validate email addresses
        if (!validateEmails(emails)) {
            alert("Please enter valid email address(es).");
            return;
        }

        // Hide modal and unnecessary elements
        const emailModal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
        emailModal.hide();
        const elementsToHide = document.querySelectorAll(
            '.hide-in-pdf, #print-btn, #search-btn, #login-btn, #date, #navbarDropdown, #reload-btn, #logout'
        );
        elementsToHide.forEach(element => element.style.display = 'none');

        // Generate PDF using jsPDF
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        pdf.html(document.getElementById("print-content"), {
            callback: function (doc) {
                const pdfBlob = doc.output('blob');  // Get the PDF as a Blob

                // Create FormData object to send email and PDF
                const formData = new FormData();
                formData.append("emails", emailInput);
                formData.append("pdf", pdfBlob, 'report.pdf');  // Append PDF as a file

                // Debugging: Log the formData content
                console.log("FormData prepared: ", formData);

                // Send data to backend via AJAX
                sendEmailToBackend(formData);
            }
        });
    }

    function sendEmailToBackend(formData) {
        fetch("send_email_to.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            console.log("Backend response:", result);
            alert(result);  // Alert success or error
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while sending the email.");
        });
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>