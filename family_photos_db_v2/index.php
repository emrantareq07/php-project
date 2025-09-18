<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Family Photo Storage</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body text-center p-5">
          <h2 class="mb-4">📷 Welcome to <span class="text-primary">Family Photo Storage</span></h2>
          <p class="text-muted mb-5">
            Safely upload, store, and view your family memories in one place.
          </p>

          <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="upload.php" class="btn btn-primary btn-lg px-4">⬆ Upload Photos</a>
            <a href="list.php" class="btn btn-outline-secondary btn-lg px-4">📂 View Gallery</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
