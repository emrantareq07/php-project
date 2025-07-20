<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:../index.php');
    exit;
}
// Reset session login value if set
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <style>
        body {
            font-size: 16px;
        }
        .navbar-nav .nav-link {
            font-size: 16px;
        }
        .dropdown-item {
            font-size: 15px;
        }
    </style>
</head>
<body>

<!-- LOGIN FORM UI -->
<div class="container my-5 ">
    <div class="row">
        <div class="col-sm-3 my-5 text-end"><a href="logtable.php" class="btn btn-success btn-sm"><i class="fa fa-eye" style="font-size:16px"></i> Show Log Table</a></div>
        <div class="col-sm-6 rounded shadow-lg p-4"> <h3 class="text-center  fw-bold text-primary"> Welcome Super Admin Dashsboard</h3>
        </div>
        <div class="col-sm-3 my-5">
             <a href="manage_user.php" class="btn btn-success btn-sm"><i class="fa fa-edit" style="font-size:16px"></i> Manage User</a>
        </div>
   </div>
</div>
</body>
</html>