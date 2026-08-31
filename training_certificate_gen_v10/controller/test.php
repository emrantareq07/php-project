<?php
session_start();

// Function to convert image to base64
function base64Image($path) {
    if(file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return '';
}

// Load logos as base64
$leftLogo = base64Image('logo/bdlogo.png');
$rightLogo = base64Image('logo/bcic_logo.png');
$watermarkLogo = base64Image('logo/bcic_logo.png');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['certificate_data'] = [
        'participant_name' => $_POST['participant_name'],
        'training_title' => $_POST['training_title'],
        'duration' => $_POST['duration'],
        'batch' => $_POST['batch']
    ];

    $upload_dir = __DIR__ . '/signatures/';
    $web_dir = 'signatures/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    // Signature 1
    if (isset($_FILES['signature1']) && $_FILES['signature1']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['signature1']['name'], PATHINFO_EXTENSION);
        $file_name = 'signature1_' . time() . '.' . $ext;
        $file_path = $upload_dir . $file_name;
        $web_path = $web_dir . $file_name;
        if (move_uploaded_file($_FILES['signature1']['tmp_name'], $file_path)) {
            $_SESSION['certificate_data']['signature1'] = $web_path;
        }
    }

    // Signature 2
    if (isset($_FILES['signature2']) && $_FILES['signature2']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['signature2']['name'], PATHINFO_EXTENSION);
        $file_name = 'signature2_' . time() . '.' . $ext;
        $file_path = $upload_dir . $file_name;
        $web_path = $web_dir . $file_name;
        if (move_uploaded_file($_FILES['signature2']['tmp_name'], $file_path)) {
            $_SESSION['certificate_data']['signature2'] = $web_path;
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . '?generate=1');
    exit();
}

$generate_certificate = isset($_GET['generate']) && $_GET['generate'] == 1;
$certificate_data = isset($_SESSION['certificate_data']) ? $_SESSION['certificate_data'] : null;
if ($generate_certificate) unset($_SESSION['certificate_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Certificate Generator</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
body { font-family: 'Roboto', sans-serif; background: #f0f2f5; padding: 20px; }
.container { max-width: 900px; margin: auto; }
header { text-align: center; margin-bottom: 30px; }
header h1 { font-family: 'Playfair Display', serif; font-size: 36px; color: #2c3e50; margin-bottom: 5px; }
header p { color: #4a6491; font-size: 16px; }

form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
form .full-width { grid-column: span 2; }
label { font-weight: 500; margin-bottom: 5px; display: block; }
input, textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
button { grid-column: span 2; padding: 12px; background: #2c3e50; color: #fff; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; transition: 0.3s; }
button:hover { background: #4a6491; }

.certificate-container { display: <?php echo $generate_certificate ? 'block' : 'none'; ?>; margin-top: 30px; text-align: center; }
.certificate {
    width: 800px; height: 600px; padding: 50px;
    background: linear-gradient(to bottom right, #fff, #f9f9f9);
    border: 15px solid;
    border-image: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491) 1;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3), inset 0 0 10px rgba(0,0,0,0.05);
    position: relative;
    margin: auto;
    text-align: center;
    font-family: 'Playfair Display', serif;
    overflow: hidden;
}
.certificate::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-25deg);
    width: 380px; height: 380px;
    background: url('logo/bcic_logo.png') no-repeat center center;
    background-size: contain;
    opacity: 0.05; z-index: 1; pointer-events: none;
}
.certificate-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }

.certificate-header { margin-bottom: 30px; }
.header-logos { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.header-text { text-align: center; flex: 1; }
.header-text h3 { margin: 2px 0; font-size: 18px; color:#4a6491; }
.header-text h2 { font-size: 40px; margin: 5px 0; }
.logo { height: 80px; width: auto; }

.participant-name { font-size: 42px; font-weight: 900; margin: 25px 0; color: #2c3e50; }
.certificate-text { font-size: 19px; line-height: 1.8; margin: 12px 0; color: #333; }

.signatures { display: flex; justify-content: space-around; margin-top: 50px; }
.signature { text-align: center; width: 40%; }
.signature-img img { max-width: 200px; max-height: 80px; object-fit: contain; border-bottom: 1px solid #ccc; }
.signature-name { font-weight: 700; margin-top: 5px; font-size: 16px; }
.signature-title { font-size: 14px; color: #666; margin: 2px 0; }

.actions { margin-top: 20px; display: flex; justify-content: center; gap: 15px; }
.download-btn { background: #27ae60; }
.download-btn:hover { background: #2ecc71; }
.new-certificate-btn { background: #e74c3c; }
.new-certificate-btn:hover { background: #e67e22; }

@media(max-width:900px){ form { grid-template-columns: 1fr; } .full-width{grid-column:span 1;} .certificate{width:100%;height:auto;padding:30px;} .signatures{flex-direction:column;gap:20px;} }
</style>
</head>
<body>
<div class="container">
<header>
<h1>Certificate Generator</h1>
<p>Create professional certificates for your training programs</p>
</header>

<form method="POST" action="" enctype="multipart/form-data">
<div>
<label for="participant_name">Participant Name:</label>
<input type="text" id="participant_name" name="participant_name" required value="<?php echo isset($_POST['participant_name']) ? htmlspecialchars($_POST['participant_name']) : ''; ?>">
</div>
<div>
<label for="batch">Batch:</label>
<input type="text" id="batch" name="batch" value="<?php echo isset($_POST['batch']) ? htmlspecialchars($_POST['batch']) : ''; ?>">
</div>
<div class="full-width">
<label for="training_title">Training Title:</label>
<textarea id="training_title" name="training_title" rows="2" required><?php echo isset($_POST['training_title']) ? htmlspecialchars($_POST['training_title']) : ''; ?></textarea>
</div>
<div class="full-width">
<label for="duration">Duration (Dates):</label>
<input type="text" id="duration" name="duration" required value="<?php echo isset($_POST['duration']) ? htmlspecialchars($_POST['duration']) : ''; ?>" placeholder="Example: 23 Aug 2025 to 25 Aug 2025">
</div>
<div class="full-width">
<label>Signature 1 (Project Director):</label>
<input type="file" name="signature1" accept="image/*">
</div>
<div class="full-width">
<label>Signature 2 (Executive Director):</label>
<input type="file" name="signature2" accept="image/*">
</div>
<button type="submit">Generate Certificate</button>
</form>

<?php if($generate_certificate && $certificate_data): ?>
<div class="certificate-container">
  <div class="certificate" id="certificate">
    <div class="certificate-content">
      <!-- Watermark -->
      <img src="<?php echo $watermarkLogo; ?>" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-25deg);width:350px;height:350px;opacity:0.06; z-index:1; pointer-events:none;">

      <!-- Header with logos -->
      <div class="header-logos" style="margin-bottom:25px;">
        <img src="<?php echo $leftLogo; ?>" class="logo" style="height:90px;">
        <div class="header-text">
          <h3 style="font-size:20px; color:#3a3a3a;">Bangladesh Chemical Industries Corporation (BCIC)</h3>
          <h2 style="font-size:42px; margin:8px 0;">Certificate of Achievement</h2>
          <h3 style="font-size:18px; color:#555;">This is to certify that</h3>
        </div>
        <img src="<?php echo $rightLogo; ?>" class="logo" style="height:90px;">
      </div>

      <!-- Participant Name -->
      <div class="participant-name" style="font-size:38px; font-weight:800; color:#2c3e50; margin:25px 0 15px 0;">
        <?php echo htmlspecialchars($certificate_data['participant_name']); ?>
      </div>

      <!-- Certificate Description -->
      <p class="certificate-text" style="font-size:18px; line-height:1.7; color:#444; margin:10px 0 20px 0;">
        has successfully completed the training on <strong>"<?php echo htmlspecialchars($certificate_data['training_title']); ?>"</strong>
        conducted from <?php echo htmlspecialchars($certificate_data['duration']); ?>
        <?php if(!empty($certificate_data['batch'])): ?> under batch <?php echo htmlspecialchars($certificate_data['batch']); ?><?php endif; ?> of the EDGE Project, Bangladesh Computer Council (BCC), ICT Division.
      </p>

      <p class="certificate-text" style="font-size:16px; color:#666; margin-bottom:35px;">
        The training program is managed by the AARC (Management Consultant) Ltd.
      </p>

      <!-- Signatures -->
      <div class="signatures" style="display:flex; justify-content:space-around; margin-top:50px;">
        <div class="signature">
          <?php if(isset($certificate_data['signature1'])): ?>
            <div class="signature-img" style="margin-bottom:5px;"><img src="<?php echo $certificate_data['signature1']; ?>" style="max-width:180px; max-height:70px;"></div>
          <?php endif; ?>
          <div class="signature-name" style="font-weight:700; font-size:16px;">Dr. Md. Taibur Rahman</div>
          <div class="signature-title" style="font-size:14px; color:#555;">Project Director, EDGE Project</div>
          <div class="signature-title" style="font-size:14px; color:#555;">Joint Secretary, ICT Division</div>
        </div>

        <div class="signature">
          <?php if(isset($certificate_data['signature2'])): ?>
            <div class="signature-img" style="margin-bottom:5px;"><img src="<?php echo $certificate_data['signature2']; ?>" style="max-width:180px; max-height:70px;"></div>
          <?php endif; ?>
          <div class="signature-name" style="font-weight:700; font-size:16px;">Md. Abu Sayed</div>
          <div class="signature-title" style="font-size:14px; color:#555;">Executive Director</div>
          <div class="signature-title" style="font-size:14px; color:#555;">Bangladesh Computer Council</div>
        </div>
      </div>

      <!-- Footer Border -->
      <div style="border-top:2px solid #2c3e50; margin-top:40px;"></div>

    </div>
  </div>
</div>


<div class="actions">
<button class="download-btn" onclick="downloadCertificate()">Download Certificate as PDF</button>
<button class="new-certificate-btn" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">Create New Certificate</button>
</div>

<script>
function downloadCertificate(){
    const certificate = document.getElementById('certificate');
    html2pdf().set({
        margin:10,
        filename:'certificate_<?php echo preg_replace("/[^a-zA-Z0-9]/","_",$certificate_data['participant_name']); ?>.pdf',
        image:{type:'jpeg',quality:1},
        html2canvas:{scale:3, logging:true, useCORS:true, allowTaint:true},
        jsPDF:{unit:'mm', format:'a4', orientation:'landscape'}
    }).from(certificate).save();
}
</script>
<?php endif; ?>
</div>
</body>
</html>
