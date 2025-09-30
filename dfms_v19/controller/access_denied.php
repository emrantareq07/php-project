<?php
session_name('dfms');
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Escape username for safe output
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Access Denied</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }
    .denied-container {
      margin-top: 10%;
      text-align: center;
    }
    .icon-denied {
      font-size: 5rem;
      color: #dc3545;
    }
  </style>
</head>
<body>
<div class="container denied-container">
  <i class="fas fa-ban icon-denied"></i>
  <h1 class="text-danger mt-3">Access Denied</h1>
  <p class="lead">Sorry <strong><?= $username ?></strong>, you do not have permission to access this page.</p>

  <div class="mt-4">
    <a href="dashboard.php" class="btn btn-primary me-2">
      <i class="fas fa-home"></i> Back to Dashboard
    </a>
    <a href="logout.php" class="btn btn-danger">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
</div>

<script>
  // Optional: Show SweetAlert warning
  Swal.fire({
    icon: 'error',
    title: 'Access Denied',
    text: 'You do not have permission to view this page!',
    confirmButtonText: 'OK'
  });
</script>
</body>
</html>
