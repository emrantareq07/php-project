<?php
session_start();
include 'db.php'; // $conn should be your mysqli connection

// Get user ID
if (!isset($_GET['id'])) die("Invalid request.");
$user_id = intval($_GET['id']);

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// // Get email from URL (if passed)
// $email = $_GET['email'] ?? '';

// // For extra security, compare with session email
// if ($email !== $_SESSION['user_email']) {
//     die("Invalid request.");
// }

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
//$user_role=$user['role'];
if (!$user) die("User not found.");

// Fetch authority data by batch
$stmt2 = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ? AND active_status='active'");
$stmt2->bind_param("s", $user['batch']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$authority = $result2->fetch_assoc();
if (!$authority) die("Traning not completed Yet.Batch : " . htmlspecialchars($user['batch']));

// Build certificate data
$certificate_data = [
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Batch Certificates</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
body { font-family: 'Roboto', sans-serif; background: #f0f2f5; padding: 20px; }
.container { max-width: 100%; margin: auto; text-align: center; }

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
    text-align: center; 
}
.certificate-text { 
    font-size: 19px; 
    line-height: 1.8; 
    margin: 5px 0; 
    color: #333; 
    text-align: center; 
}

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }
button { margin-top: 20px; }
</style>
</head>
<body>
<div class="container">
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
    <button onclick="downloadCertificate()" class="btn btn-success">Download Certificate</button>

    <?php if ($user_role === 'sadmin'): ?>
    <a href="manage_users.php" class="btn btn-info mt-3">Back</a>
   
    <?php else: ?>
        <a href="my_certificates.php?email=<?= urlencode($_SESSION['user_email']); ?>" class="btn btn-info mt-3">Back</a>
    <?php endif; ?>
</div>

<script>
function downloadCertificate() {
    const element = document.querySelector('.certificate-wrapper');
    html2pdf().set({
        margin: 0,
        filename: 'certificate_<?= preg_replace("/[^a-zA-Z0-9]/","_",$certificate_data['participant_name']); ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
        pagebreak: { mode: ['css','legacy'] }
    }).from(element).save();
}
</script>
</body>
</html>
