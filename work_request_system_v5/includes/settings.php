<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Get user data from session
$user_id = $_SESSION['user_id'];
$emp_id = $_SESSION['emp_id'];
$full_name = $_SESSION['full_name'];
$role = $_SESSION['role'];
 

?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">

<h4 class="mb-3">⚙ Settings</h4>

<div class="row g-3">
    <div class="col-md-4">
        <a href="add_division.php" class="card text-center shadow-sm p-4 text-decoration-none">
            <h5>Division</h5>
            <small>Manage divisions</small>
        </a>
    </div>

    <div class="col-md-4">
        <a href="add_section.php" class="card text-center shadow-sm p-4 text-decoration-none">
            <h5>Section</h5>
            <small>Manage sections</small>
        </a>
    </div>

    <div class="col-md-4">
        <a href="add_designation.php" class="card text-center shadow-sm p-4 text-decoration-none">
            <h5>Designation</h5>
            <small>Manage designations</small>
        </a>
    </div>
    <a href="dashboard.php" class="btn btn-primary float-end col-md-4"><i class="fas fa-arrow-left"></i>Back</a>
</div>

</div>

</body>
</html>


<?php //include('../includes/footer.php'); ?>
