<?php
session_name('training_certificate_gen_db');
session_start();

require_once 'db.php';
require_once 'flash.php';

// ❌ Invalid request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setFlash('error', 'Invalid request.');
    header("Location: my_certificates.php?email=" . urlencode($_SESSION['user_email']));
    exit;
}


$user_id = (int) $_GET['id'];

// Fetch user
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ❌ User not found
if (!$user) {
    setFlash('error', 'User not found.');
    header("Location: my_certificates.php");
    exit;
}

// Fetch authority by batch
$stmt2 = $conn->prepare("
    SELECT * FROM authority_tbl 
    WHERE batch = ? AND active_status = 'active'
");
$stmt2->bind_param("s", $user['batch']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$authority = $result2->fetch_assoc();

// ❌ Training not completed
if (!$authority) {
    setFlash(
        'warning',
        'Training not completed yet. Batch: ' . $user['batch']
    );
    header("Location: my_certificates.php");
    exit;
}

// ✅ Certificate data
$certificate_data = [
    'participant_name' => $user['name'],
    'training_title'   => $authority['training_title'],
    'start_date'       => $authority['start_date'],
    'end_date'         => $authority['end_date'],
    'organized_by'     => $authority['organized_by'],
    'batch'            => $user['batch'],

    'name1' => $authority['name1'],
    'designation1' => $authority['designation1'],
    'office1' => $authority['office1'],
    'ministry1' => $authority['ministry1'],
    'signature1' => $authority['signature1'],

    'name2' => $authority['name2'],
    'designation2' => $authority['designation2'],
    'office2' => $authority['office2'],
    'ministry2' => $authority['ministry2'],
    'signature2' => $authority['signature2'],
];

require_once "includes/header.php"; 
?>

<style>
@font-face {
    font-family: 'Certificate';
    src: url('fonts/Certificate.ttf') format('truetype');
}



/* Golden Border Wrapper */
/*.certificate-wrapper {
    width: 297mm;
    height: 210mm;
    margin: 20px auto;
    padding: 15px;
    border: 12px solid gold;
    border-radius: 20px;
    box-sizing: border-box;
    background: #fff;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    position: relative;
}*/

/* Optional decorative corners */
/*.certificate-wrapper::before,
.certificate-wrapper::after {
    content: "";
    position: absolute;
    width: 40px;
    height: 40px;
    border: 5px solid gold;
    border-radius: 50%;
    z-index: 10;
}
.certificate-wrapper::before { top:0; left:0; border-right:none; border-bottom:none; }
.certificate-wrapper::after { bottom:0; right:0; border-left:none; border-top:none; }*/




/* Green Border Wrapper */
.certificate-wrapper {
    width: 297mm;
    height: 210mm;
    margin: 20px auto;
    padding: 15px;
    border: 12px solid #27ae60; 
    /*border-radius: 20px;*/
    border-radius: 0;
    box-sizing: border-box;
    background: #fff;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    position: relative;
}


.certificate-wrapper::before,
.certificate-wrapper::after {
    content: "";
    position: absolute;
    width: 40px;
    height: 40px;
    border: 5px solid #27ae60; 
    /*border-radius: 50%;*/
    border-radius: 0;
    z-index: 10;
}
.certificate-wrapper::before { top:0; left:0; border-right:none; border-bottom:none; }
.certificate-wrapper::after { bottom:0; right:0; border-left:none; border-top:none; }




/* Inner white content */
.certificate-inner {
    width: 100%;
    height: 100%;
    background: #fff;
    border-radius: 10px;
    padding: 15mm;
    position: relative;
    overflow: hidden;
}

/* Watermark */
.certificate-inner::before {
    content: "";
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-25deg);
    width: 380px; height: 380px;
    background: url('../logo/bcic_logo.png') no-repeat center center;
    background-size: contain;
    opacity: 0.05;
    z-index: 1;
    pointer-events: none;
}

.certificate-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }

.header-logos { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.header-text { text-align: center; flex: 1; }
.header-text h3 { margin: 2px 0; font-size: 24px; color:#4a6491; }
.header-text h4 { margin: 1px 0; font-size: 18px; color:#4a6491; }
h6{ font-size: 11px; color:#4a6491;}
.logo { height: 80px; width: auto; }

.participant-name { 
    font-size: 42px; 
    font-weight: 900; 
    margin: 5px 0 10px; 
    color: #2c3e50; 
    text-align: center;
}
.certificate-text { font-size: 19px; line-height: 1.8; margin: 0px 0; color: #333; text-align: justify; text-align-last: center; }

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }

</style>

<div class="container">

    <div id="certificates">
        <div class="certificate-wrapper">
            <div class="certificate-inner">
                <div class="certificate-content">

                    <div class="header-logos">
                        <img src="../logo/bdlogo.png" class="logo">
                        <div class="header-text">
                            <h3 class="righteous-regular">Bangladesh Chemical Industries Corporation (BCIC)</h3>
                            <h4 class="text-primary fw-bold my-2 rowdies-regular">Ministry of Industries</h4>
                            <h4 class="text-primary fw-bold rowdies-regular">The People's Republic of Bangladesh</h4>
                        </div>
                        <img src="../logo/bcic_logo.png" class="logo">
                    </div>

                    <div style="position: relative;">
                        <h2 class="text-center"
                            style="font-family: 'Certificate', sans-serif; font-size: 56px; font-weight: bold;">
                            Certificate of Achievement
                        </h2>

                        <h6 style="position: absolute; top: 5px; right: -10px;">
                            Serial No: BCIC-ICT-DIVISION-B<?= htmlspecialchars($certificate_data['batch']); ?>-<?= $user_id; ?>
                        </h6>
                    </div>

                    <div class="participant-name">
                        <h4 class="righteous-regular text-center">This is to certify that</h4>
                        <?= htmlspecialchars($certificate_data['participant_name']); ?>

                        <p class="certificate-text lobster-two-regular">
                            <?php
                                $start_date = date("d F Y", strtotime($certificate_data['start_date']));
                                $end_date   = date("d F Y", strtotime($certificate_data['end_date']));
                            ?>

                            <?php if ($certificate_data['start_date'] == $certificate_data['end_date']): ?>
                                has successfully completed a Daylong In-house training on
                                <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong>
                                held on <?= $start_date; ?>,
                            <?php else: ?>
                                has successfully completed the training on
                                <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong>
                                held on <?= $start_date; ?> to <?= $end_date; ?>
                            <?php endif; ?>
                            <?= htmlspecialchars($certificate_data['organized_by']); ?>
                        </p>
                    </div>

                    <div class="signatures">
                        <div class="signature">
                            <div class="signature-img">
                                <img src="<?= htmlspecialchars($certificate_data['signature1']); ?>">
                            </div>
                            <div class="signature-name"><?= htmlspecialchars($certificate_data['name1']); ?></div>
                            <div class="signature-title"><?= htmlspecialchars($certificate_data['designation1']); ?></div>
                            <div class="signature-title"><?= htmlspecialchars($certificate_data['office1']); ?></div>
                            <div class="signature-title"><?= htmlspecialchars($certificate_data['ministry1']); ?></div>
                        </div>

                        <div class="signature">
                            <div class="signature-img">
                                <img src="<?= htmlspecialchars($certificate_data['signature2']); ?>">
                            </div>
                            <div class="signature-name"><?= htmlspecialchars($certificate_data['name2']); ?></div>
                            <div class="signature-title"><?= htmlspecialchars($certificate_data['designation2']); ?></div>
                            <div class="signature-title"><?= htmlspecialchars($certificate_data['office2']); ?></div>
                            <div class="signature-title"><?= htmlspecialchars($certificate_data['ministry2']); ?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <p class="text-center my-3">
        <button onclick="downloadCertificate()" class="btn btn-success">
            <i class="fa fa-download"></i> Download Certificate
        </button>
        <a href="my_certificates.php?email=<?= urlencode($_SESSION['user_email']); ?>" class="btn btn-info">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </p>

</div>

<!-- JS for jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
async function downloadCertificate() {
    const { jsPDF } = window.jspdf;
    const certificate = document.querySelector('.certificate-wrapper');

    const canvas = await html2canvas(certificate, { scale: 2, useCORS: true });
    const imgData = canvas.toDataURL('image/jpeg', 1.0);

    const pdf = new jsPDF('landscape', 'mm', 'a4');
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

    pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
    pdf.save('certificate_<?= $user_id; ?>.pdf');
}
</script>



