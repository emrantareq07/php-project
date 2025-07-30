<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>e-Library Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom Style -->
    <style>
        body {
            font-size: 16px;
            padding-top: 70px;
        }
        .navbar-nav .nav-link {
            font-size: 16px;
            padding: 0.5rem 1rem;
        }
        .dropdown-item {
            font-size: 15px;
        }
        .carousel-inner > .carousel-item > img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }
        .slide-bdr {
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .input-wrapper {
            position: relative;
            width: 100%;
        }
        .with-clear {
            padding-right: 30px;
        }
        .clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #999;
            cursor: pointer;
            display: none;
        }
        .clear-btn:hover { 
            color: #555; 
        }
        textarea.with-clear + .clear-btn { 
            top: 20px; 
            right: 15px; 
        }
        .student-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        .student-img:hover {
            transform: scale(2);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .student-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .student-img-preview {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ccc;
            margin-top: 0px;
        }
        .alert-stat {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<?php include('navbar.php');?>
