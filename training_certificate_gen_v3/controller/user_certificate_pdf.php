<?php
session_start();
include 'db.php'; // $conn should be your mysqli connection

// Get user ID
if (!isset($_GET['id'])) die("Invalid request.");
$user_id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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
    'organized_by'         => $authority['organized_by'],

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Aladin&family=Arimo:ital,wght@0,400..700;1,400..700&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Lora:ital,wght@0,400..700;1,400..700&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&family=Trochut:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>
.montserrat-alternates-regular {
  font-family: "Montserrat Alternates", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.montserrat-alternates-bold {
  font-family: "Montserrat Alternates", sans-serif;
  font-weight: 700;
  font-style: normal;
}

.righteous-regular {
  font-family: "Righteous", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.lobster-two-regular {
  font-family: "Lobster Two", sans-serif;
  font-weight: 400;
  font-style: normal;
}

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
.header-text h5 { margin: 2px 0; font-size: 14px; color:#4a6491; }
.header-text h2 { font-size: 36px; margin: 5px 0; }
h6{ font-size: 11px; color:#4a6491;}
.logo { height: 80px; width: auto; }
span{ font-size: 10px; color:#4a6491;}
/* Center participant name and certificate text */
.participant-name { 
    font-size: 42px; 
    font-weight: 900; 
    margin: 5px 0 10px; 
    color: #2c3e50; 
    text-align: center; /* Centered */
}
.certificate-text {
    font-size: 19px;
    line-height: 1.8;
    margin: 0px 0;
    color: #333;
    text-align: justify;
    text-align-last: center; /* ✅ last line centered */
}

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }

/* Remove extra default margins to collapse gaps */
.header-logos h3,
.header-logos h4,
.header-logos h2,
.header-logos h6,
.certificate-section h2,
.certificate-section h6,
.participant-name h4,
.participant-name div,
.participant-name p {
    margin: 0;
}

/* Layout for logos and header text */
.header-logos {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-logos .header-text {
    flex-grow: 1;
    text-align: center;
    margin: 0 10px;
}
</style>
</head>
<body>
<div class="container">
<div class="certificate-wrapper">
<div class="certificate-inner">
    <div class="certificate-content">
<div class="certificate-header text-center">

    <!-- Logos + Organization Text -->
    <div class="header-logos mb-2">
        <img src="../logo/bdlogo.png" alt="Left Logo" class="logo">
        <div class="header-text">
            <h3 class="righteous-regular">Bangladesh Chemical Industries Corporation (BCIC)</h3>
            <h4 class="text-primary fw-bold mt-2">Ministry of Industries</h4>
            <h4 class="text-primary fw-bold mt-2" >People Republic of Bangladesh</h4>
        </div>
        <img src="../logo/bcic_logo.png" alt="Right Logo" class="logo">
    </div>

    <!-- Certificate Title + Serial No -->
<!--     <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <div class="flex-grow-1"></div>
        <h2 class="montserrat-alternates-bold text-center flex-grow-1">
            Certificate of Achievement
        </h2>
        <h6 class="text-muted">
            Serial No: <span>BCIC-ICT-DIVISION-<?= htmlspecialchars($certificate_data['batch']); ?></span>
        </h6>
    </div> -->

 <!-- Serial No at top-right -->
<div class="d-flex justify-content-end">
  <h6 style="color: #6c757d; margin: 0;">
    Serial No: <span>BCIC-ICT-DIVISION-<?= htmlspecialchars($certificate_data['batch']); ?></span>
  </h6>
</div>

<!-- Centered Title -->
<div class="text-center my-2">
  <h2 style="font-family: 'Certificate', sans-serif; font-size: 56px; font-weight: bold; color: #333; margin: 0;">
    Certificate of Achievement
  </h2>
</div>




<!-- <h6 class="float-end" style="position: relative; top: 0; right: -10px; color: #6c757d; margin: 0;">
        Serial No: <span>BCIC-ICT-DIVISION-<?= htmlspecialchars($certificate_data['batch']); ?></span>
    </h6>
    <div class=" text-center">

    <h2 class=" text-center" style="font-family: 'Certificate', sans-serif; font-size: 56px; font-weight: bold; color: #333;">Certificate of Achievement</h2>     
    
</div> -->
<!-- <h2 class="montserrat-alternates-bold mt-4 text-center">Certificate of Achievement</h2> -->

    <!-- Participant Section -->
    <div class="participant-name mt-4">
        <h4 class="righteous-regular">This is to certify that</h4>
        <div class="fw-bold mb-2 mt-3"><?= htmlspecialchars($certificate_data['participant_name']); ?></div>

        <p class="certificate-text lobster-two-regular mt-2">
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

</div>






                        <!-- <p class="certificate-text">
                            has successfully completed the training on 
                            <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong><br>
                            conducted from <?= htmlspecialchars($certificate_data['start_date']); ?> 
                            to <?= htmlspecialchars($certificate_data['end_date']); ?><br>
                            under batch <?= htmlspecialchars($certificate_data['batch']); ?>.
                        </p> -->
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

            <p class="text-center my-2">
                <button onclick="downloadCertificate()" class="btn btn-success"><i class="fa fa-download"></i> Download Certificate</button>
    <a href="my_certificates.php?email=<?= urlencode($_SESSION['user_email']); ?>" class="btn btn-info "><i class="fa fa-arrow-left"></i> Back</a>
            </p>

    
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
