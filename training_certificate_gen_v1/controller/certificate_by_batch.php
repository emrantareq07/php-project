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
        $users = $stmt2->get_result();

        while ($user = $users->fetch_assoc()) {
            $certificates[] = [
                'participant_name' => $user['name'],
                'training_title'   => $authority['training_title'],
                'start_date'       => $authority['start_date'],
                'end_date'         => $authority['end_date'],
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
        // Training is not complete, you can set status active or show message
        echo "<div class='alert alert-warning'>Training for batch $selected_batch is not completed. Please set the training status to active.</div>";
        // Optionally, you can update the batch status in database
        // $conn->query("UPDATE authority_tbl SET active_status='active' WHERE batch='$selected_batch'");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Batch Certificates</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
body { font-family: 'Roboto', sans-serif; background: #ffff; padding: 20px; }
.container { max-width: 100%; margin: auto; }

/* Outer wrapper with gradient border */
.certificate-wrapper {
    width: 297mm; height: 210mm; /* A4 landscape */
    margin: auto;
    position: relative;
    
    padding-top: 25px;    /* top border width */
    padding-right: 15px;  /* right border width */
    padding-bottom: 20px; /* bottom border width */
    padding-left: 10px;   /* left border width */
    background: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491);
    border-radius: 20px;
    box-sizing: border-box;
}

/* Inner white content */
.certificate-inner {
    width: 100%; height: 100%;
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
    background: url('logo/bcic_logo.png') no-repeat center center;
    background-size: contain;
    opacity: 0.05;
    z-index: 1;
    pointer-events: none;
}

.certificate-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }

.header-logos { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.header-text { text-align: center; flex: 1; }
.header-text h3 { margin: 2px 0; font-size: 18px; color:#4a6491; }
.header-text h2 { font-size: 36px; margin: 5px 0; }
.logo { height: 80px; width: auto; }

/* Center participant name and certificate text */
.participant-name { 
    font-size: 42px; 
    font-weight: 900; 
    margin: 15px 0 10px; 
    color: #2c3e50; 
    text-align: center; /* Centered */
}
.certificate-text { 
    font-size: 19px; 
    line-height: 1.8; 
    margin: 5px 0; 
    color: #333; 
    text-align: center; /* Centered */
}

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }
</style>
</head>
<body>
<div class="container">
    <!-- Download Button Top -->
    <?php if ($certificates): ?>
    <div class="mb-3 text-end">
        <button onclick="downloadCertificates()" class="btn btn-success">
                Download All Certificates (<?= count($certificates); ?>)
            </button>
    </div>
    <?php endif; ?>

    <!-- Batch Select Form -->
    <form method="get" class="row g-3 mb-2">
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
                                <h3>Bangladesh Chemical Industries Corporation (BCIC)</h3>
                                <h2>Certificate of Achievement</h2>
                                <h3>This is to certify that</h3>
                            </div>
                            <img src="../logo/bcic_logo.png" alt="Right Logo" class="logo">
                        </div>
                        <div class="participant-name"><?= htmlspecialchars($certificate_data['participant_name']); ?></div>
                        <p class="certificate-text">
                            has successfully completed the training on 
                            <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong><br>
                            conducted from <?= htmlspecialchars($certificate_data['start_date']); ?> 
                            to <?= htmlspecialchars($certificate_data['end_date']); ?><br>
                            under batch <?= htmlspecialchars($certificate_data['batch']); ?>.
                        </p>
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
            <br>
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
