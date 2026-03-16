<?php 
session_start();
include 'db.php';

// Get batches
// $batches = [];
// Fetch distinct batches from authority_tbl
$res = $conn->query("
    SELECT DISTINCT batch, training_title 
    FROM authority_tbl 
    ORDER BY batch DESC
");

$batches = [];
while ($row = $res->fetch_assoc()) {
    $batches[] = $row;
}
$selected_batch = $_GET['batch'] ?? '';

$certificates = [];
if ($selected_batch) {
    // Get active authority
    $stmt = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ? AND active_status='active'");
    $stmt->bind_param("s", $selected_batch);
    $stmt->execute();
    $authority = $stmt->get_result()->fetch_assoc();

    if ($authority) {
        // Get users in this batch
        $stmt2 = $conn->prepare("SELECT * FROM users_tbl WHERE batch = ?");
        $stmt2->bind_param("s", $selected_batch);
        $stmt2->execute();
        $users_result = $stmt2->get_result();

while ($user = $users_result->fetch_assoc()) {
    // Generate QR code for participant only
    $serial_no = "BCIC-ICT-DIVISION-B".$user['batch']."-".$user['id'];
    $start_date = date("d-m-Y", strtotime($authority['start_date']));
    $end_date   = date("d-m-Y", strtotime($authority['end_date']));
    $verification_link = "http://localhost/training_certificate_gen_v6/index.php";

    $qr_text = "Serial No: ".$serial_no
        . "\nName: ".$user['name']
        . "\nTraining: ".$authority['training_title']
        . "\nStart Date: ".$start_date
        . "\nEnd Date: ".$end_date
        . "\nBatch: ".$user['batch']
        . "\nVerify: ".$verification_link;

    $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($qr_text);

    $certificates[] = [
        'user_id'          => $user['id'],
        'participant_name' => $user['name'],
        'training_title'   => $authority['training_title'],
        'start_date'       => $authority['start_date'],
        'end_date'         => $authority['end_date'],
        'organized_by'     => $authority['organized_by'],
        'batch'            => $user['batch'],
        'qr_code'          => $qr_code_url, // ✅ QR code added
        'name1'            => $authority['name1'],
        'designation1'     => $authority['designation1'],
        'office1'          => $authority['office1'],
        'ministry1'        => $authority['ministry1'],
        'signature1'       => $authority['signature1'],
        'name2'            => $authority['name2'],
        'designation2'     => $authority['designation2'],
        'office2'          => $authority['office2'],
        'ministry2'        => $authority['ministry2'],
        'signature2'       => $authority['signature2']
    ];
}

    } else {
        echo "<div class='alert alert-warning'>Training for batch $selected_batch is not active. Please activate it.</div>";
    }
}

require_once "includes/header.php"; 
?>

<style>
@font-face {
    font-family: 'Certificate';
    src: url('fonts/Certificate.ttf') format('truetype');
}

/* ===== BCC Government Style Border ===== */
.certificate-wrapper {
    width: 297mm;
    height: 210mm;
    margin: 20px auto;
    padding: 18px;
    background: #ffffff;
    position: relative;

    /* Outer Dark Blue Border */
     /*border: 12px solid #0b3d91;*/
    border: 13px solid #0b3d91;

    /* Inner Gold Border Effect */
  
}

/* Decorative Corner Design */
.certificate-wrapper::before,
.certificate-wrapper::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    border: 6px solid #d4af37;
}

/* Top Left Corner */
.certificate-wrapper::before {
    top: -6px;
    left: -6px;
    border-right: none;
    border-bottom: none;
}

/* Bottom Right Corner */
.certificate-wrapper::after {
    bottom: -6px;
    right: -6px;
    border-left: none;
    border-top: none;
}

/* Inner Content Area */
.certificate-inner {
    width: 100%;
    height: 100%;
    background: #ffffff;
    padding: 15mm;
    position: relative;
}

/* Light Watermark Background */
.certificate-inner::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 380px;
    height: 380px;
    background: url('../logo/bcic_logo.png') no-repeat center;
    background-size: contain;
    opacity: 0.05;
}

/* Layout */
.certificate-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Header */
.header-logos {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    height: 80px;
}

.header-text {
    text-align: center;
    flex: 1;
}

.header-text h3 {
    font-size: 24px;
    color: #0b3d91;
    margin: 2px 0;
}

.header-text h4 {
    font-size: 18px;
    color: #0b3d91;
    margin: 2px 0;
}

/* Title */
h2 {
    font-family: 'Certificate';
    font-size: 56px;
    text-align: center;
    color: #0b3d91;
}

/* Participant Name */
.participant-name {
    font-size: 42px;
    font-weight: bold;
    text-align: center;
    color: #222;
}

/* Certificate Text */
.certificate-text {
    font-size: 19px;
    line-height: 1.8;
    text-align: center;
    color: #333;
}

/* Signatures */
.signatures {
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
}

.signature {
    text-align: center;
    width: 250px;
}

.signature-img img {
    width: 120px;
}

.signature-name {
    margin-top: 10px;
    padding-top: 5px;
    border-top: 1px solid #000;
    font-weight: bold;
}

.signature-title {
    font-size: 12px;
}

/* QR */
/* QR CODE */
.qr-code {
    text-align: center;
}
.qr-code img {
    width: 100px;
    margin-top: 5px;
}
.qr-code span {
    display: block;
    font-size: 12px;
    margin-top: 3px;
}
</style>


<div class="container">
<?php if ($certificates): ?>
<div class="mb-3 text-end">
    <button onclick="downloadCertificates()" class="btn btn-success">
        Download All Certificates (<?= count($certificates); ?>)
    </button>
</div>
<?php endif; ?>

<form method="get" class="row g-3 mb-3">
<div class="col-auto">
<label for="batch" class="col-form-label fw-bold">Select Batch:</label>
</div>

<div class="col-auto">
    <select name="batch" id="batch" class="form-select" required>
        <option value="">-- Choose Batch --</option>

        <?php foreach ($batches as $row): ?>
            <option value="<?= htmlspecialchars($row['batch']) ?>"
                <?= ($selected_batch==$row['batch']?'selected':'') ?>>

          <?= htmlspecialchars('Batch '.$row['batch'].' - '.$row['training_title']) ?>

            </option>
        <?php endforeach; ?>

    </select>
</div>

<div class="col-auto">
<button type="submit" class="btn btn-primary">Show Certificates</button>
<a href="dashboard.php" class="btn btn-info">Back</a>
</div>
</form>

<?php if ($certificates): ?>
<div id="certificates">
<?php foreach ($certificates as $certificate_data): ?>
<div class="certificate-wrapper">
<div class="certificate-inner">
<div class="certificate-content">

<div class="header-logos">
<img src="../logo/bdlogo.png" class="logo">
<div class="header-text">
<h3 class="righteous-regular">Bangladesh Chemical Industries Corporation (BCIC)</h3>
<h4 class="text-primary fw-bold my-2 rowdies-regular ">Ministry of Industries</h4>
<h4 class="text-primary fw-bold rowdies-regular ">The People's Republic of Bangladesh</h4>
</div>
<img src="../logo/bcic_logo.png" class="logo">
</div>

<div style="position: relative;">
<h2 class="text-center" style="font-family: 'Certificate'; font-size: 56px;">Certificate of Achievement</h2>
<!-- <h6 style="position: absolute; top: 5px; right: -10px;">
    Serial No: 
    <span style="font-size: 9.5px;">
        BCIC-ICT-DIVISION-B<?= htmlspecialchars($certificate_data['batch']) . '-' . htmlspecialchars($certificate_data['user_id']); ?>
    </span>
</h6> -->
 <h6 style="position: absolute; top: 5px; right: -10px;">
    <span style="font-size: 11px;">Serial No: </span>
    <span style="font-size: 9.5px;">BCIC-ICT-DIVISION-B</span><span style="font-size: 9.5px;"><?= htmlspecialchars($certificate_data['batch']) . '-' . htmlspecialchars($certificate_data['user_id']);?></span>
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
held on <?= htmlspecialchars($start_date); ?>,
<?php else: ?>
has successfully completed the training on 
<strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong>
held on <?= htmlspecialchars($start_date); ?> to <?= htmlspecialchars($end_date); ?>
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

<!-- ✅ QR CODE for participant -->
<div class="qr-code">
<img src="<?= $certificate_data['qr_code']; ?>" alt="QR Code">
<span>Scan to Verify</span>
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
<?php endforeach; ?>
</div>
<?php elseif ($selected_batch): ?>
<div class="alert alert-danger">No certificates found for this batch.</div>
<?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
async function downloadCertificates() {
const { jsPDF } = window.jspdf;
const certificates = document.querySelectorAll('.certificate-wrapper');
if(certificates.length === 0){ alert('No certificates to download!'); return; }
const pdf = new jsPDF('landscape', 'mm', 'a4');

for(let i = 0; i < certificates.length; i++){
const canvas = await html2canvas(certificates[i], { scale: 2, useCORS: true });
const imgData = canvas.toDataURL('image/jpeg', 1.0);
const pdfWidth = pdf.internal.pageSize.getWidth();
const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
if(i > 0) pdf.addPage();
pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
}
pdf.save('batch_certificates_<?= preg_replace("/[^a-zA-Z0-9]/","_",$selected_batch); ?>.pdf');
}
</script>
</body>
</html>

<script>
// Disable Back & Forward
history.pushState(null, null, location.href);
window.onpopstate = function () {
   window.location.href = "reload_handler.php";
};


// If page reloads, destroy session and redirect
// Prevent reload
if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.location.href = "reload_handler.php";
}



window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        window.location.href = "../index.php";
    }
});
</script>