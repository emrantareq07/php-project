<?php
include 'db.php';

$message = '';

if (isset($_POST['upload'])) {
    $files = $_FILES['photos'];

    $successCount = 0;
    $duplicateCount = 0;

    for ($i = 0; $i < count($files['name']); $i++) {
        $fileName = $files['name'][$i];
        $fileTmp = $files['tmp_name'][$i];

        if (!is_uploaded_file($fileTmp)) continue;

        $fileData = file_get_contents($fileTmp);

        // Generate hash
        $fileHash = hash('sha256', $fileData);

        // Duplicate check
        $check = $conn->prepare("SELECT id FROM photos WHERE filehash = ?");
        $check->bind_param("s", $fileHash);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $duplicateCount++;
        } else {
            $stmt = $conn->prepare("INSERT INTO photos (filename, filehash, photo) VALUES (?, ?, ?)");
            $stmt->bind_param("ssb", $fileName, $fileHash, $null);
            $stmt->send_long_data(2, $fileData);
            $stmt->execute();
            $successCount++;
        }
    }

    if ($successCount > 0) {
        $message .= '<div class="alert alert-success text-center">✅ ' . $successCount . ' photo(s) uploaded successfully!</div>';
    }
    if ($duplicateCount > 0) {
        $message .= '<div class="alert alert-warning text-center">⚠️ ' . $duplicateCount . ' duplicate photo(s) skipped!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Photos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <h3 class="text-center mb-4">📸 Upload Photos</h3>

          <?php if (!empty($message)) echo $message; ?>

          <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="photos" class="form-label">Choose Photos</label>
              <input type="file" class="form-control" id="photos" name="photos[]" multiple required>
              <div class="invalid-feedback">Please select at least one photo.</div>
            </div>
            <div class="d-grid">
              <button type="submit" name="upload" class="btn btn-primary btn-lg">Upload</button>
            </div>
          </form>

          <div class="text-center mt-4">
            <a href="list.php" class="btn btn-outline-secondary">
              📂 View All Photos
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
</body>
</html>
