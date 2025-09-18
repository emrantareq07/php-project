<?php
session_start();
include 'db.php'; // $conn should be your mysqli connection

// Get user ID
if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$user_id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch authority data by batch
$stmt2 = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ?");
$stmt2->bind_param("s", $user['batch']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$authority = $result2->fetch_assoc();

if (!$authority) {
    die("Authority details not found for batch: " . htmlspecialchars($user['batch']));
}

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
<title>Certificate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body { 
    font-family: 'Roboto', sans-serif; 
    background: #f0f2f5; 
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container { 
    width: 100%;
    max-width: 297mm; /* A4 landscape width */
    margin: auto; 
}

.certificate-wrapper {
    width: 297mm;
    min-height: 210mm;
    padding: 15px; /* This creates the gradient border effect */
    background: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491);
    border-radius: 25px;
    margin: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.certificate {
    width: 100%;
    height: 100%;
    padding: 15mm;
    box-sizing: border-box;
    background: linear-gradient(to bottom right, #fff, #f9f9f9);
    border-radius: 10px;
    text-align: center;
    font-family: 'Playfair Display', serif;
    position: relative;
    overflow: hidden;
}

/* Watermark */
.certificate::before {
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

.certificate-content { 
    position: relative; 
    z-index: 2; 
    height: 100%; 
    display: flex; 
    flex-direction: column; 
    justify-content: space-between; 
}

.header-logos { 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    margin-bottom: 10px; 
    padding: 0 5mm;
}

.header-text { 
    text-align: center; 
    flex: 1; 
}

.header-text h3 { 
    margin: 2px 0; 
    font-size: 18px; 
    color:#4a6491; 
}

.header-text h2 { 
    font-size: 36px; 
    margin: 5px 0; 
}

.logo { 
    height: 80px; 
    width: auto;
    max-width: 120px;
    object-fit: contain;
}

.participant-name { 
    font-size: 42px; 
    font-weight: 900; 
    margin: 15px 0 10px; 
    color: #2c3e50; 
    padding: 0 10mm;
    word-break: break-word;
}

.certificate-text { 
    font-size: 19px; 
    line-height: 1.8; 
    margin: 5px 0; 
    color: #333; 
    padding: 0 10mm;
}

.signatures { 
    display: flex; 
    justify-content: space-around; 
    margin-top: 20px; 
    padding: 0 5mm;
}

.signature { 
    text-align: center; 
    width: 250px; 
}

.signature-img { 
    margin-bottom: 10px;
    position: relative;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.signature-img img { 
    max-width: 120px; 
    height: auto;
    max-height: 80px;
}

/* Add line under signature images */
.signature-img::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 10%;
    right: 10%;
    height: 1px;
    background: #000;
}

.signature-name { 
    margin-top: 15px;
    font-weight: bold; 
}

.signature-title { 
    font-size: 12px; 
    margin: 2px 0;
}

.download-btn {
    display: block;
    margin: 20px auto;
    padding: 12px 25px;
    background: linear-gradient(45deg, #2c3e50, #4a6491);
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.download-btn:hover {
    background: linear-gradient(45deg, #4a6491, #2c3e50);
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0,0,0,0.15);
}

/* Media query for print/PDF */
@media print {
    body {
        padding: 0;
        background: none;
    }
    
    .download-btn {
        display: none;
    }
}

/* Responsive adjustments */
@media screen and (max-width: 320mm) {
    body {
        padding: 10px;
    }
    
    .container {
        max-width: 100%;
    }
    
    .certificate-wrapper {
        width: 100%;
        min-height: auto;
        padding: 10px;
    }
    
    .certificate {
        padding: 10mm;
    }
    
    .header-text h2 {
        font-size: 28px;
    }
    
    .participant-name {
        font-size: 32px;
    }
    
    .certificate-text {
        font-size: 16px;
    }
    
    .signatures {
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="certificate-wrapper">
        <div class="certificate" id="certificate">
            <div class="certificate-content">
                <!-- Header with Logos -->
                <div class="header-logos">
                    <img src="logo/bdlogo.png" alt="Left Logo" class="logo">
                    <div class="header-text">
                        <h3>Bangladesh Chemical Industries Corporation (BCIC)</h3>
                        <h2>Certificate of Achievement</h2>
                        <h3>This is to certify that</h3>
                    </div>
                    <img src="logo/bcic_logo.png" alt="Right Logo" class="logo">
                </div>

                <!-- Participant Name -->
                <div class="participant-name"><?= htmlspecialchars($certificate_data['participant_name']); ?></div>

                <!-- Certificate Text -->
                <p class="certificate-text">
                    has successfully completed the training on <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong><br>
                    conducted from <?= htmlspecialchars($certificate_data['start_date']); ?> to <?= htmlspecialchars($certificate_data['end_date']); ?><br>
                    under batch <?= htmlspecialchars($certificate_data['batch']); ?>.
                </p>

                <!-- Signatures -->
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
    <button class="download-btn" onclick="downloadCertificate()">Download Certificate</button>
</div>

<script>
function downloadCertificate() {
    const element = document.getElementById('certificate');
    
    // Create a clone of the element to avoid modifying the original
    const clone = element.cloneNode(true);
    
    // Apply specific styles for PDF export
    clone.style.width = '297mm';
    clone.style.margin = '0';
    
    // Create a wrapper for the gradient border
    const wrapper = document.createElement('div');
    wrapper.className = 'certificate-wrapper';
    wrapper.style.width = '297mm';
    wrapper.style.minHeight = '210mm';
    wrapper.style.padding = '15px';
    wrapper.style.background = 'linear-gradient(45deg, #2c3e50, #27ae60, #4a6491)';
    wrapper.style.borderRadius = '25px';
    wrapper.appendChild(clone);
    
    // Temporarily append to body for rendering
    wrapper.style.position = 'absolute';
    wrapper.style.left = '-9999px';
    document.body.appendChild(wrapper);
    
    const opt = {
        margin: [0, 0, 0, 0],
        filename: 'certificate_<?= preg_replace("/[^a-zA-Z0-9]/","_",$certificate_data['participant_name']); ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { 
            scale: 2, 
            useCORS: true, 
            scrollY: 0,
            width: 1122, // 297mm in pixels at 96 DPI
            height: 794, // 210mm in pixels at 96 DPI
            logging: false,
            backgroundColor: '#ffffff'
        },
        jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'landscape',
            compress: true
        }
    };
    
    // Generate PDF from the wrapper
    html2pdf().set(opt).from(wrapper).save().then(() => {
        // Remove the wrapper after PDF generation
        document.body.removeChild(wrapper);
    });
}
</script>
</body>
</html>
///////////////////////////////


<?php
session_start();
include 'db.php'; // $conn should be your mysqli connection

// Get user ID
if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$user_id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch authority data by batch
$stmt2 = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ?");
$stmt2->bind_param("s", $user['batch']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$authority = $result2->fetch_assoc();

if (!$authority) {
    die("Authority details not found for batch: " . htmlspecialchars($user['batch']));
}

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
<title>Certificate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
body { font-family: 'Roboto', sans-serif; background: #f0f2f5; padding: 20px; }
.container { max-width: 100%; margin: auto; }
.certificate {
    width: 297mm;              /* A4 landscape width */
    min-height: 210mm;         /* A4 landscape height */
    padding: 15mm;
    box-sizing: border-box;
    background: linear-gradient(to bottom right, #fff, #f9f9f9);
    border: 15px solid;
    border-image: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491) 1;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    margin: auto;
    text-align: center;
    font-family: 'Playfair Display', serif;
    position: relative;
    overflow: hidden;
}

/* Watermark */
.certificate::before {
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

.participant-name { font-size: 42px; font-weight: 900; margin: 15px 0 10px; color: #2c3e50; }
.certificate-text { font-size: 19px; line-height: 1.8; margin: 5px 0; color: #333; }

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }
</style>
</head>
<body>
<div class="container">
    <div class="certificate" id="certificate">
        <div class="certificate-content">
            <!-- Header with Logos -->
            <div class="header-logos">
                <img src="logo/bdlogo.png" alt="Left Logo" class="logo">
                <div class="header-text">
                    <h3>Bangladesh Chemical Industries Corporation (BCIC)</h3>
                    <h2>Certificate of Achievement</h2>
                    <h3>This is to certify that</h3>
                </div>
                <img src="logo/bcic_logo.png" alt="Right Logo" class="logo">
            </div>

            <!-- Participant Name -->
            <div class="participant-name"><?= htmlspecialchars($certificate_data['participant_name']); ?></div>

            <!-- Certificate Text -->
            <p class="certificate-text">
                has successfully completed the training on <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong><br>
                conducted from <?= htmlspecialchars($certificate_data['start_date']); ?> to <?= htmlspecialchars($certificate_data['end_date']); ?><br>
                under batch <?= htmlspecialchars($certificate_data['batch']); ?>.
            </p>

            <!-- Signatures -->
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
    <br>
    <button onclick="downloadCertificate()">Download Certificate</button>
</div>

<script>
function downloadCertificate() {
    const element = document.getElementById('certificate');
    const opt = {
        margin:       0,
        filename:     'certificate_<?= preg_replace("/[^a-zA-Z0-9]/","_",$certificate_data['participant_name']); ?>.pdf',
        image:        { type: 'jpeg', quality: 1 },
        html2canvas:  { scale: 2, useCORS: true, scrollY: 0 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>
</body>
</html>





.certificate-wrapper {
    width: 297mm;
    min-height: 210mm;
    position: relative;
    background: #fff; /* certificate background */
    box-sizing: border-box;
    overflow: visible; /* ensure borders are visible */
}

/* Thick Top Border */
.certificate-wrapper::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 20px; /* border thickness */
    background: linear-gradient(to right, #27ae60, #3498db, #e74c3c);
    z-index: 2; /* bring above certificate */
}

/* Thick Bottom Border */
.certificate-wrapper::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px; /* border thickness */
    background: linear-gradient(to right, #27ae60, #3498db, #e74c3c);
    z-index: 2;
}

.certificate {
    width: 100%;
    height: 100%;
    background: #fff;
    padding: 20mm;
    box-sizing: border-box;
    position: relative;
    z-index: 1; /* keep content under borders */
}



////////////////////////////////



<?php
session_start();
include 'db.php'; // $conn should be your mysqli connection

// Get user ID
if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$user_id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch authority data by batch
$stmt2 = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ?");
$stmt2->bind_param("s", $user['batch']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$authority = $result2->fetch_assoc();

if (!$authority) {
    die("Authority details not found for batch: " . htmlspecialchars($user['batch']));
}

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
<title>Certificate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
body { font-family: 'Roboto', sans-serif; background: #f0f2f5; padding: 20px; }
.container { max-width: 100%; margin: auto; }
.certificate {
    width: 297mm;              /* A4 landscape width */
    min-height: 210mm;         /* A4 landscape height */
    padding: 15mm;
    box-sizing: border-box;
    background: linear-gradient(to bottom right, #fff, #f9f9f9);

    /* Different border widths: top, right, bottom, left */
    border-style: solid;
    border-width: 2px 10px 2px 2px;  
    border-image: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491) 1;

    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    margin: auto;
    text-align: center;
    font-family: 'Playfair Display', serif;
    position: relative;
    overflow: hidden;
}


/* Watermark */
.certificate::before {
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

.participant-name { font-size: 42px; font-weight: 900; margin: 15px 0 10px; color: #2c3e50; }
.certificate-text { font-size: 19px; line-height: 1.8; margin: 5px 0; color: #333; }

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }
</style>
</head>
<body>
<div class="container">
    <div class="certificate" id="certificate">
        <div class="certificate-content">
            <!-- Header with Logos -->
            <div class="header-logos">
                <img src="logo/bdlogo.png" alt="Left Logo" class="logo">
                <div class="header-text">
                    <h3>Bangladesh Chemical Industries Corporation (BCIC)</h3>
                    <h2>Certificate of Achievement</h2>
                    <h3>This is to certify that</h3>
                </div>
                <img src="logo/bcic_logo.png" alt="Right Logo" class="logo">
            </div>

            <!-- Participant Name -->
            <div class="participant-name"><?= htmlspecialchars($certificate_data['participant_name']); ?></div>

            <!-- Certificate Text -->
            <p class="certificate-text">
                has successfully completed the training on <strong>"<?= htmlspecialchars($certificate_data['training_title']); ?>"</strong><br>
                conducted from <?= htmlspecialchars($certificate_data['start_date']); ?> to <?= htmlspecialchars($certificate_data['end_date']); ?><br>
                under batch <?= htmlspecialchars($certificate_data['batch']); ?>.
            </p>

            <!-- Signatures -->
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
    <br>
    <button onclick="downloadCertificate()">Download Certificate</button>
</div>

<script>
function downloadCertificate() {
    const element = document.getElementById('certificate');
    const opt = {
        margin:       0,
        filename:     'certificate_<?= preg_replace("/[^a-zA-Z0-9]/","_",$certificate_data['participant_name']); ?>.pdf',
        image:        { type: 'jpeg', quality: 1 },
        html2canvas:  { scale: 2, useCORS: true, scrollY: 0 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>
</body>
</html>