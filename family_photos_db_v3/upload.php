<?php
// upload.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$message = '';
$successCount = $duplicateCount = $skippedCount = 0;

if (isset($_POST['upload'])) {
    if (!isset($_FILES['photos'])) {
        $message = '<div class="alert alert-warning">No files uploaded.</div>';
    } else {
        $files = $_FILES['photos'];
        for ($i = 0; $i < count($files['name']); $i++) {
            $name = $files['name'][$i];
            $tmp  = $files['tmp_name'][$i];
            $error = $files['error'][$i];
            $size = $files['size'][$i];

            if ($error !== UPLOAD_ERR_OK) { $skippedCount++; continue; }
            if (!is_uploaded_file($tmp)) { $skippedCount++; continue; }

            // optional: security check — ensure uploaded file is an image
            $imginfo = @getimagesize($tmp);
            if ($imginfo === false) { $skippedCount++; continue; }

            // Limit file size (example: 12MB)
            if ($size > 12 * 1024 * 1024) { $skippedCount++; continue; }

            $data = file_get_contents($tmp);
            $hash = hash('sha256', $data);

            // duplicate check
            $check = $conn->prepare("SELECT id FROM photos WHERE filehash = ?");
            $check->bind_param("s", $hash);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $duplicateCount++;
            } else {
                // insert blob
                $fileNameVar = $name;
                $fileHashVar = $hash;
                $null = NULL;
                // $stmt = $conn->prepare("INSERT INTO photos (filename, filehash, photo) VALUES (?, ?, ?)");
                // $stmt->bind_param("ssb", $fileNameVar, $fileHashVar, $null);
                $stmt = $conn->prepare("INSERT INTO photos (filename, filehash, photo, user_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssbi", $fileNameVar, $fileHashVar, $null, $_SESSION['user_id']);

                $stmt->send_long_data(2, $data);
                if ($stmt->execute()) $successCount++;
                $stmt->close();
            }
        } // end loop

        $msgParts = [];
        if ($successCount) $msgParts[] = "<div class='alert alert-success'>✅ {$successCount} uploaded.</div>";
        if ($duplicateCount) $msgParts[] = "<div class='alert alert-warning'>⚠️ {$duplicateCount} duplicate(s) skipped.</div>";
        if ($skippedCount) $msgParts[] = "<div class='alert alert-info'>{$skippedCount} file(s) skipped (invalid or too large).</div>";
        $message = implode('', $msgParts);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Upload Photos</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="text-center mb-3">⬆ Upload Photos</h3>

          <?= $message ?>

          <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
              <label class="form-label">Choose photos (multiple)</label>
              <input type="file" name="photos[]" class="form-control" id="photos" multiple required accept="image/*">
              <div class="invalid-feedback">Please select one or more images.</div>
            </div>
            <div class="d-grid">
              <button name="upload" class="btn btn-primary btn-lg">Upload</button>
            </div>
          </form>

          <div class="text-center mt-3">
            <a href="list.php" class="btn btn-outline-secondary">📂 View Gallery</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
      form.addEventListener('submit', function (evt) {
        if (!form.checkValidity()) {
          evt.preventDefault()
          evt.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })();
</script>
</body>
</html>
