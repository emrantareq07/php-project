<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header('Location: login.php'); exit;
}?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Download All Database</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
  <h2>Welcome!!! </h2>
  <a href="dashboard_send_mail.php" class="btn btn-success">Download All Database send to Email</a>
  <a href="dashboard.php" class="btn btn-success">Download All Database</a>
  
      
</div>

</body>
</html>
