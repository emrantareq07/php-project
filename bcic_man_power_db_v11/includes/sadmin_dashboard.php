<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// NOTE: You previously had hard-coded admin session assignment here.
// If you want real login behavior, remove the next two lines in production.
// $_SESSION['username'] = 'admin';
// $_SESSION['role'] = 'admin';

$username = $_SESSION['username'];
$is_admin = ($username === 'sadmin'); // Check if user is admin
$role = $_SESSION['role'] ?? ''; // ensure role exists

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard | Man Power Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  
<div class="container">
  <h1>Welcome !!! <?php echo $_SESSION['username'];?></h1>
  <a href="manage_users.php" class="btn btn-primary"><i class="fa fa-shield-alt me-2"></i>  Manage User</a>

  <a href="logout.php" class="btn btn-danger"><i class="fa fa-shield-alt me-2"></i>  Logout</a>
</div>

</body>
</html>
 
    