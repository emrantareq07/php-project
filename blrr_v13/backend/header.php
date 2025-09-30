<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            background: #f8f9fa;
            font-family: "Open Sans", Arial, sans-serif;
        }
        .navbar {
            background-color: #6610f2;
        }
        .navbar .navbar-brand, 
        .navbar .nav-link, 
        .navbar .navbar-text {
            color: #fff !important;
        }
        .border-custom-purple {
            border: 1px solid #6610f2 !important;
        }
        .btn-outline-success {
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="fa fa-database me-2"></i> Document Dashboard
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (!empty($_SESSION['username'])): ?>
                <li class="nav-item">
                    <span class="navbar-text me-3">
                        Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                    </span>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-sm btn-outline-light">
                        <i class="fa fa-sign-out-alt"></i> Logout
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a href="login.php" class="btn btn-sm btn-light">
                        <i class="fa fa-sign-in-alt"></i> Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid my-3">
