<?php 
session_start();
include 'db.php';

$batches = [];
$res = $conn->query("SELECT DISTINCT batch FROM authority_tbl ORDER BY batch ASC");
while ($row = $res->fetch_assoc()) {
    $batches[] = $row['batch'];
}

$selected_batch = $_GET['batch'] ?? '';

$certificates = [];
if ($selected_batch) {
    $stmt = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ? AND active_status='active'");
    $stmt->bind_param("s", $selected_batch);
    $stmt->execute();
    $authority = $stmt->get_result()->fetch_assoc();

    if ($authority) {
        $stmt2 = $conn->prepare("SELECT * FROM users_tbl WHERE batch = ?");
        $stmt2->bind_param("s", $selected_batch);
        $stmt2->execute();
        $users_result = $stmt2->get_result();  // Changed variable name

        while ($user = $users_result->fetch_assoc()) {
            // Don't overwrite $users variable - use $user['id'] directly
            $certificates[] = [
                'user_id'          => $user['id'],  // Add user_id to certificate data
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
        echo "<div class='alert alert-warning'>Training for batch $selected_batch is not completed. Please set the training status to active.</div>";
    }
}
?>
<?php
require_once "includes/header.php"; 
?>
<div class="container">
    <!-- Download Button Top -->
    <?php if ($certificates): ?>
    <div class="mb-0 text-end">
        <button onclick="downloadCertificates()" class="btn btn-success">
                Download All Certificates (<?= count($certificates); ?>)
            </button>
    </div>
    <?php endif; ?>
    <!-- Batch Select Form -->
    <form method="get" class="row g-3 mb-0">
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
         <!-- Show total count -->
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
                            <img src="../logo/bdlogo.png" alt="Left Logo" class="logo">
                            <div class="header-text">
                                <h3 class="righteous-regular">Bangladesh Chemical Industries Corporation (BCIC)</h3>
                                <h4 class="text-primary fw-bold my-2 rowdies-regular ">Ministry of Industries</h4>
                                <h4 class="text-primary fw-bold rowdies-regular ">People's Republic of Bangladesh</h4>
                            </div>
                            <img src="../logo/bcic_logo.png" alt="Right Logo" class="logo">
                        </div>
                        <div style="position: relative;">
                        <h2 class=" text-center" style="font-family: 'Certificate', sans-serif; font-size: 56px; font-weight: bold; color: #333;">Certificate of Achievement</h2>
                        <h6 style="position: absolute; top: 5px; right: -10px; color: #6c757d; margin: 0;">
                        Serial No: 
                        <span>
                            BCIC-ICT-DIVISION-B<?= htmlspecialchars($certificate_data['batch']) . '-' . htmlspecialchars($certificate_data['user_id']); ?>
                        </span>
                    </h6>
                    </div>                        
                        <div class="participant-name "><h4 class="righteous-regular text-center ">This is to certify that</h4><?= htmlspecialchars($certificate_data['participant_name']); ?> 
                        <p class="certificate-text lobster-two-regular ">
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
                                held on <?= htmlspecialchars($start_date); ?> 
                                to <?= htmlspecialchars($end_date); ?>
                            <?php endif; ?>
                            <?= htmlspecialchars($certificate_data['organized_by']); ?><br>
                        </p>
                          </div>

                        <div class="signatures">
                            <div class="signature">
                                <div class="signature-img">
                                    <img src="<?= htmlspecialchars($certificate_data['signature1']); ?>" alt="Signature 1">
                                </div>
                                <div class="signature-name"><?= htmlspecialchars($certificate_data['name1']); ?></div>
                                <div class="signature-title"><?= htmlspecialchars($certificate_data['designation1']); ?></div>
                                <div class="signature-title"><?= htmlspecialchars($certificate_data['office1']); ?></div>
                                <div class="signature-title"><?= htmlspecialchars($certificate_data['ministry1']); ?></div>
                            </div>
                            <div class="signature">
                                <div class="signature-img">
                                    <img src="<?= htmlspecialchars($certificate_data['signature2']); ?>" alt="Signature 2">
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

<script>
function downloadCertificates() {
    const element = document.getElementById('certificates');
    html2pdf().set({
        margin: 0,
        filename: 'batch_certificates_<?= preg_replace("/[^a-zA-Z0-9]/","_",$selected_batch); ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
        pagebreak: { mode: ['css','legacy'] }
    }).from(element).save();
}
</script>
</body>
</html>