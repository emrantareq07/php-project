<?php 
session_start();
include 'db.php';

// Get batches
$batches = [];
$res = $conn->query("SELECT DISTINCT batch FROM authority_tbl ORDER BY batch ASC");
while ($row = $res->fetch_assoc()) {
    $batches[] = $row['batch'];
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
            $certificates[] = [
                'user_id'          => $user['id'],
                'participant_name' => $user['name'],
                'training_title'   => $authority['training_title'],
                'start_date'       => $authority['start_date'],
                'end_date'         => $authority['end_date'],
                'organized_by'     => $authority['organized_by'],
                'batch'            => $user['batch'],
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
    <!-- Download Button -->
    <?php if ($certificates): ?>
        <div class="mb-3 text-end">
            <button onclick="downloadCertificates()" class="btn btn-success">
                Download All Certificates (<?= count($certificates); ?>)
            </button>
        </div>
    <?php endif; ?>

    <!-- Batch Select Form -->
    <form method="get" class="row g-3 mb-3">
        <div class="col-auto">
            <label for="batch" class="col-form-label fw-bold">Select Batch:</label>
        </div>
        <div class="col-auto">
            <select name="batch" id="batch" class="form-select" required>
                <option value="">-- Choose Batch --</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?= htmlspecialchars($batch) ?>" <?= ($selected_batch==$batch?'selected':'') ?>>
                        <?= htmlspecialchars($batch) ?>
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
        <div class="mb-3 fw-bold">
            Total Certificates for batch <span class="text-primary"><?= htmlspecialchars($selected_batch); ?></span>: 
            <span class="badge bg-success"><?= count($certificates); ?></span>
        </div>

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
                                <h2 class="text-center" style="font-family: 'Certificate', sans-serif; font-size: 56px; font-weight: bold; color: #333;">Certificate of Achievement</h2>
                                <h6 style="position: absolute; top: 5px; right: -10px; color: #6c757d;">
                                    Serial No: BCIC-ICT-DIVISION-B<?= htmlspecialchars($certificate_data['batch']) . '-' . htmlspecialchars($certificate_data['user_id']); ?>
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
                                    <?= htmlspecialchars($certificate_data['organized_by']); ?><br>
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
            <?php endforeach; ?>
        </div>
    <?php elseif ($selected_batch): ?>
        <div class="alert alert-danger">No certificates found for this batch.</div>
    <?php endif; ?>
</div>

<!-- JS for jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
async function downloadCertificates() {
    const { jsPDF } = window.jspdf;
    const certificates = document.querySelectorAll('.certificate-wrapper');

    if(certificates.length === 0){
        alert('No certificates to download!');
        return;
    }

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
