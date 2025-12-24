<?php
session_name('rnt_training_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('db/db.php');
include('includes/header.php');

// Get quick stats
$total_employees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM employees"))['total'];
$total_bcic_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bcic_staff"))['total'];
$total_trainings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM office_order"))['total'];
$total_designations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM designation"))['total'];
$total_training_titles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM training_list"))['total'];
$total_postings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM place_of_posting"))['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Glow Effect -->
    <style>
        :root {
            --rainbow-1: linear-gradient(135deg, #FF6B6B, #FFE66D);
            --rainbow-2: linear-gradient(135deg, #1A936F, #88D498);
            --rainbow-3: linear-gradient(135deg, #4361EE, #3A0CA3);
            --rainbow-4: linear-gradient(135deg, #F72585, #7209B7);
            --rainbow-5: linear-gradient(135deg, #4CC9F0, #4895EF);
            --rainbow-6: linear-gradient(135deg, #F15BB5, #9B5DE5);
            --rainbow-7: linear-gradient(135deg, #00BBF9, #00F5D4);
            --rainbow-8: linear-gradient(135deg, #FB5607, #FF006E);
        }
        
        body {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }
        
        /* Glass Morphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Rainbow Gradient Cards */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
            color: white;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            z-index: 1;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .stat-card:hover::after {
            opacity: 1;
        }
        
        .stat-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
        }
        
        .stat-card-content {
            position: relative;
            z-index: 2;
            padding: 2rem;
        }
        
        /* Gradient Backgrounds for Cards */
        .card-rainbow-1 { background: var(--rainbow-1); }
        .card-rainbow-2 { background: var(--rainbow-2); }
        .card-rainbow-3 { background: var(--rainbow-3); }
        .card-rainbow-4 { background: var(--rainbow-4); }
        .card-rainbow-5 { background: var(--rainbow-5); }
        .card-rainbow-6 { background: var(--rainbow-6); }
        .card-rainbow-7 { background: var(--rainbow-7); }
        .card-rainbow-8 { background: var(--rainbow-8); }
        
        /* Animated Gradient Button */
        .btn-rainbow {
            background-size: 300% 300%;
            background-image: linear-gradient(45deg, 
                #FF6B6B, #FFE66D, #1A936F, #88D498,
                #4361EE, #3A0CA3, #F72585, #7209B7);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            animation: gradientShift 8s ease infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .btn-rainbow:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            color: white;
        }
        
        .btn-rainbow:active {
            transform: translateY(-1px);
        }
        
        /* Colorful Individual Buttons */
        .btn-gradient-1 {
            background: linear-gradient(45deg, #FF6B6B, #FFE66D);
            border: none;
            color: #333;
            font-weight: 600;
        }
        
        .btn-gradient-2 {
            background: linear-gradient(45deg, #1A936F, #88D498);
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-gradient-3 {
            background: linear-gradient(45deg, #4361EE, #3A0CA3);
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-gradient-4 {
            background: linear-gradient(45deg, #F72585, #7209B7);
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-gradient-5 {
            background: linear-gradient(45deg, #4CC9F0, #4895EF);
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-gradient-6 {
            background: linear-gradient(45deg, #F15BB5, #9B5DE5);
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-gradient-7 {
            background: linear-gradient(45deg, #00BBF9, #00F5D4);
            border: none;
            color: #333;
            font-weight: 600;
        }
        
        .btn-gradient-8 {
            background: linear-gradient(45deg, #FB5607, #FF006E);
            border: none;
            color: white;
            font-weight: 600;
        }
        
        .btn-custom {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        /* Colorful Table */
        .table-rainbow {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .table-rainbow thead {
            background: linear-gradient(90deg, 
                #FF6B6B 0%, #FFE66D 25%, #1A936F 50%, #4361EE 75%, #F72585 100%);
            color: white;
        }
        
        .table-rainbow thead th {
            border: none;
            padding: 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .table-rainbow tbody tr {
            transition: all 0.3s ease;
        }
        
        .table-rainbow tbody tr:hover {
            background: linear-gradient(90deg, 
                rgba(255, 107, 107, 0.1) 0%,
                rgba(255, 230, 109, 0.1) 25%,
                rgba(26, 147, 111, 0.1) 50%,
                rgba(67, 97, 238, 0.1) 75%,
                rgba(247, 37, 133, 0.1) 100%);
            transform: translateX(5px);
        }
        
        /* Colorful Modal */
        .modal-rainbow .modal-content {
            border-radius: 25px;
            border: none;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .modal-rainbow .modal-header {
            background: linear-gradient(90deg, 
                #FF6B6B 0%, #FFE66D 25%, #1A936F 50%, #4361EE 75%, #F72585 100%);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }
        
        .modal-rainbow .modal-title {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        /* Form Styling */
        .form-control-rainbow, .form-select-rainbow {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            background-clip: padding-box;
            position: relative;
        }
        
        .form-control-rainbow:focus, .form-select-rainbow:focus {
            border-image: linear-gradient(45deg, 
                #FF6B6B, #FFE66D, #1A936F, #88D498,
                #4361EE, #3A0CA3, #F72585, #7209B7) 1;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 107, 0.25);
            transform: translateY(-2px);
        }
        
        /* User Profile Card */
        .user-profile {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.3) 0%,
                rgba(255, 255, 255, 0.1) 100%);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #FF6B6B, #FFE66D, #1A936F, #4361EE);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Floating Action Button */
        .fab-rainbow {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(45deg, 
                #FF6B6B, #FFE66D, #1A936F, #4361EE, #F72585);
            background-size: 400% 400%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: all 0.4s ease;
            z-index: 1000;
            border: none;
            animation: rainbowRotate 4s linear infinite;
        }
        
        @keyframes rainbowRotate {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }
        
        .fab-rainbow:hover {
            animation: rainbowRotate 1s linear infinite;
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        
        /* Search Box with Gradient */
        .search-rainbow {
            border-radius: 50px;
            background: linear-gradient(90deg, 
                rgba(255, 107, 107, 0.1) 0%,
                rgba(255, 230, 109, 0.1) 25%,
                rgba(26, 147, 111, 0.1) 50%,
                rgba(67, 97, 238, 0.1) 75%,
                rgba(247, 37, 133, 0.1) 100%);
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
        }
        
        .search-rainbow:focus-within {
            border-image: linear-gradient(45deg, 
                #FF6B6B, #FFE66D, #1A936F, #4361EE, #F72585) 1;
        }
        
        /* Status Indicators */
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(26, 147, 111, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(26, 147, 111, 0); }
            100% { box-shadow: 0 0 0 0 rgba(26, 147, 111, 0); }
        }
        
        .status-active { background-color: #1A936F; }
        .status-pending { background-color: #FFE66D; }
        .status-completed { background-color: #4361EE; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: linear-gradient(180deg, 
                #FF6B6B 0%, #FFE66D 25%, #1A936F 50%, #4361EE 75%, #F72585 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        
        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }
        
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            animation: float 20s infinite linear;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(45deg, #FF6B6B, #FFE66D);
            top: -200px;
            left: -200px;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(45deg, #1A936F, #88D498);
            bottom: -150px;
            right: -150px;
            animation-delay: -5s;
        }
        
        .shape-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #4361EE, #3A0CA3);
            top: 50%;
            left: 10%;
            animation-delay: -10s;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(100px, 100px) rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .stat-card-content {
                padding: 1.5rem;
            }
            
            .fab-rainbow {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .table-rainbow thead th {
                padding: 1rem;
                font-size: 0.8rem;
            }
        }
        
        /* Glowing Text */
        .glow-text {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5),
                         0 0 20px rgba(255, 255, 255, 0.3),
                         0 0 30px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg">
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>
</div>

<!-- Floating Action Button -->
<button class="fab-rainbow animate__animated animate__bounceIn" data-bs-toggle="modal" data-bs-target="#employeeModal">
    <i class="fas fa-plus"></i>
</button>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4 p-md-5 animate__animated animate__fadeInDown">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3 glow-text" style="color: #333;">
                            <i class="fas fa-database me-3" style="background: linear-gradient(45deg, #FF6B6B, #FFE66D, #1A936F, #4361EE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            Employees Training Database
                        </h1>
                        <p class="lead mb-0 text-muted">
                            <i class="fas fa-building me-2"></i>RNT, Personnel Division, BCIC.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="user-profile d-inline-block animate__animated animate__fadeInRight">
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark"><?php echo htmlspecialchars($username); ?></h6>
                                    <p class="mb-0 text-muted small">
                                        <span class="status-dot status-active"></span>
                                        <?php echo htmlspecialchars($user_type); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="row mb-4 g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card-rainbow-1 shadow-lg animate__animated animate__fadeInUp">
                <div class="stat-card-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.9;">Total Employees</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo number_format($total_employees); ?></h2>
                        </div>
                        <div class="display-4 opacity-75">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <span class="status-dot status-active"></span> Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card-rainbow-2 shadow-lg animate__animated animate__fadeInUp animate__delay-1s">
                <div class="stat-card-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.9;">BCIC Staff</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo number_format($total_bcic_staff); ?></h2>
                        </div>
                        <div class="display-4 opacity-75">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <span class="status-dot status-completed"></span> Corporate
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card-rainbow-3 shadow-lg animate__animated animate__fadeInUp animate__delay-2s">
                <div class="stat-card-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.9;">Total Trainings</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo number_format($total_trainings); ?></h2>
                        </div>
                        <div class="display-4 opacity-75">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <span class="status-dot status-active"></span> Ongoing
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card card-rainbow-4 shadow-lg animate__animated animate__fadeInUp animate__delay-3s">
                <div class="stat-card-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.9;">Designations</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo number_format($total_designations); ?></h2>
                        </div>
                        <div class="display-4 opacity-75">
                            <i class="fas fa-sitemap"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <span class="status-dot status-pending"></span> Roles
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Stats Section -->
    <div class="row mb-4 g-4">
        <div class="col-xl-4 col-md-6">
            <div class="stat-card card-rainbow-5 shadow-lg animate__animated animate__fadeInLeft">
                <div class="stat-card-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.9;">Training Programs</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo number_format($total_training_titles); ?></h2>
                        </div>
                        <div class="display-4 opacity-75">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <i class="fas fa-list me-1"></i> Available
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <div class="stat-card card-rainbow-6 shadow-lg animate__animated animate__fadeInUp">
                <div class="stat-card-content">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase mb-2" style="opacity: 0.9;">Posting Locations</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo number_format($total_postings); ?></h2>
                        </div>
                        <div class="display-4 opacity-75">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <i class="fas fa-location-arrow me-1"></i> Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-12">
            <div class="stat-card card-rainbow-7 shadow-lg animate__animated animate__fadeInRight">
                <div class="stat-card-content">
                    <div class="text-center">
                        <div class="display-4 opacity-75 mb-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h6 class="text-uppercase mb-2" style="opacity: 0.9;">System Status</h6>
                        <div class="progress mb-3" style="height: 10px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i> Operational
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="includes/add_designation.php" class="btn btn-gradient-1 btn-custom">
                                <i class="fas fa-plus-circle me-2"></i> Add Designation
                            </a>
                            <a href="includes/add_training_title.php" class="btn btn-gradient-2 btn-custom">
                                <i class="fas fa-book-medical me-2"></i> Add Training Title
                            </a>
                            <a href="includes/add_office_or_factory.php" class="btn btn-gradient-3 btn-custom">
                                <i class="fas fa-building me-2"></i> Add Office
                            </a>
                            <a href="add_office_order.php" class="btn btn-gradient-4 btn-custom">
                                <i class="fas fa-file-alt me-2"></i> Add Office Order
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            <button class="btn btn-gradient-5 btn-custom" data-bs-toggle="modal" data-bs-target="#employeeModal">
                                <i class="fas fa-user-plus me-2"></i> Add Training
                            </button>
                            <a href="multi_searching.php" class="btn btn-gradient-6 btn-custom">
                                <i class="fas fa-search me-2"></i> Multi-Search
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Export Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="search-rainbow p-2">
                            <div class="input-group border-0">
                                <span class="input-group-text bg-transparent border-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-0 bg-transparent" placeholder="Search in database...">
                                <button class="btn btn-rainbow" type="button">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                            <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                                <button class="btn btn-gradient-7 btn-custom" type="submit" name="submit">
                                    <i class="fas fa-download me-2"></i> Download DB
                                </button>
                            </form>
                            <a href="includes/logout.php" class="btn btn-gradient-8 btn-custom">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="table-rainbow">
                <div class="table-responsive">
                    <table id="employeeTable" class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>SL No.</th>
                                <th>EMP ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Place of Posting</th>
                                <th>Training Type</th>
                                <th>Training Title</th>
                                <th>Ref. No.</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Institute</th>
                                <th>Attachment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit -->
<div class="modal fade modal-rainbow" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">
                    <i class="fas fa-user-graduate me-2"></i>Add Employees Training
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm">
                <div class="modal-body p-4">
                    <input type="hidden" id="id" name="id">
                    
                    <div class="row g-3">
                        <!-- EMP ID Field -->
                        <div class="col-md-6">
                            <label for="emp_id" class="form-label fw-bold">
                                <i class="fas fa-id-card me-1" style="color: #FF6B6B;"></i> EMP ID
                            </label>
                            <input list="emp_ids"
                                type="text" 
                                class="form-control form-control-rainbow" 
                                id="emp_id" 
                                name="emp_id" 
                                placeholder="Enter EMP ID" 
                                pattern="[0-9\-]+" 
                                title="EMP ID must contain only digits and the '-' symbol." 
                                required
                                onchange="fetchEmployeeDetails(this.value)">
                            <datalist id="emp_ids">
                                <?php
                                $sql_employees = "SELECT emp_id FROM employees";
                                $result_employees = mysqli_query($conn, $sql_employees);
                                $sql_bcic = "SELECT emp_id FROM bcic_staff";
                                $result_bcic = mysqli_query($conn, $sql_bcic);
                                $all_emp_ids = array();
                                
                                while ($row = mysqli_fetch_array($result_employees)) {
                                    $all_emp_ids[$row['emp_id']] = true;
                                }
                                
                                while ($row = mysqli_fetch_array($result_bcic)) {
                                    $all_emp_ids[$row['emp_id']] = true;
                                }
                                
                                foreach (array_keys($all_emp_ids) as $emp_id) {
                                    echo "<option value='" . htmlspecialchars($emp_id, ENT_QUOTES) . "'>" . htmlspecialchars($emp_id, ENT_QUOTES) . "</option>";
                                }
                                ?>
                            </datalist>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-user me-1" style="color: #FFE66D;"></i> Name
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-rainbow" 
                                id="name" 
                                name="name" 
                                placeholder="Enter full name" 
                                required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <!-- Designation Field -->
                        <div class="col-md-6">
                            <label for="designation" class="form-label fw-bold">
                                <i class="fas fa-user-tie me-1" style="color: #1A936F;"></i> Designation
                            </label>
                            <input list="designations" id="designation" name="designation" class="form-control form-control-rainbow" required>
                            <datalist id="designations">
                                <?php
                                $sql = "SELECT * FROM designation ORDER BY designation ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $row['designation'] . "'>";
                                }
                                ?>
                            </datalist>
                        </div>

                        <!-- Place of Posting Field -->
                        <div class="col-md-6">
                            <label for="place_of_posting" class="form-label fw-bold">
                                <i class="fas fa-building me-1" style="color: #4361EE;"></i> Place of Posting
                            </label>
                            <select class="form-select form-select-rainbow" id="place_of_posting" name="place_of_posting" required>
                                <option value="" disabled selected>--Select--</option>
                                <?php
                                $sql = "SELECT * FROM place_of_posting ORDER BY place_of_posting ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $row['place_of_posting'] . "'>" . $row['place_of_posting'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Reference No. Field -->
                    <div class="mt-3">
                        <label for="ref_no" class="form-label fw-bold">
                            <i class="fas fa-file-alt me-1" style="color: #F72585;"></i> Reference No.
                        </label>
                        <select class="form-select form-select-rainbow" id="ref_no" name="ref_no" readonly>
                            <option value="" disabled selected>--Select--</option>
                            <?php
                            $sql = "SELECT ref_no FROM office_order ORDER by id desc";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_array($result)) {
                                echo "<option value='".$row['ref_no']."'>".$row['ref_no']."</option>";
                            }
                            ?>   
                        </select>
                    </div>
                    
                    <!-- Training Type and Title -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="training_type" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap me-1" style="color: #4CC9F0;"></i> Training Type
                            </label>
                            <input type="text" class="form-control form-control-rainbow" id="training_type" name="training_type" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="training_title" class="form-label fw-bold">
                                <i class="fas fa-book me-1" style="color: #9B5DE5;"></i> Title/Subject
                            </label>
                            <input type="text" class="form-control form-control-rainbow" id="training_title" name="training_title" readonly>
                        </div>
                    </div>
                    
                    <!-- Dates Section -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1" style="color: #00BBF9;"></i> Start Date
                            </label>
                            <input type="date" class="form-control form-control-rainbow" id="start_date" name="start_date" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-times me-1" style="color: #FF006E;"></i> End Date
                            </label>
                            <input type="date" class="form-control form-control-rainbow" id="end_date" name="end_date" readonly>
                        </div>
                    </div>
                    
                    <!-- Institute Field -->
                    <div class="mt-3">
                        <label for="t_institute" class="form-label fw-bold">
                            <i class="fas fa-university me-1" style="color: #FB5607;"></i> Institute
                        </label>
                            <input type="text" class="form-control form-control-rainbow" id="t_institute" name="t_institute" readonly>
                    </div>
                </div>
                
                <div class="modal-footer border-0" style="background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);">
                    <button type="button" class="btn btn-outline-secondary btn-custom" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-rainbow">
                        <i class="fas fa-save me-2"></i> Save Training
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize DataTable with Bootstrap 5 styling
    $('#employeeTable').DataTable({
        "language": {
            "search": "<i class='fas fa-search'></i> Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "zeroRecords": "No matching records found",
            "paginate": {
                "first": "<i class='fas fa-angle-double-left'></i>",
                "last": "<i class='fas fa-angle-double-right'></i>",
                "next": "<i class='fas fa-angle-right'></i>",
                "previous": "<i class='fas fa-angle-left'></i>"
            }
        },
        "pageLength": 25,
        "order": [[0, 'asc']],
        "responsive": true,
        "dom": '<"row"<"col-md-6"l><"col-md-6"f>>tip',
        "initComplete": function() {
            $('.dataTables_length select').addClass('form-select-rainbow');
            $('.dataTables_filter input').addClass('form-control-rainbow');
        }
    });
    
    // Add rainbow effect to table rows on hover
    $('#employeeTable tbody').on('mouseenter', 'tr', function() {
        $(this).addClass('animate__animated animate__pulse');
    }).on('mouseleave', 'tr', function() {
        $(this).removeClass('animate__animated animate__pulse');
    });
    
    // AJAX functionality remains the same
    $('#emp_id').on('change', function () {
        const empId = $(this).val().trim();

        if (empId === "") {
            $('#name').val("");
            $('#designation').val("");
            $('#place_of_posting').val("");
            return;
        }

        $.ajax({
            url: "fetch_employee.php",
            type: "POST",
            data: { emp_id: empId },
            success: function (response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $('#name').val(data.name);
                    $('#designation').val(data.designation);
                    $('#place_of_posting').val(data.place_of_posting);
                } else {
                    $('#name').val("");
                    $('#designation').val("");
                    $('#place_of_posting').val("");
                }
            },
            error: function () {
                alert("An error occurred while fetching employee details.");
            }
        });
    });

    $('#ref_no').on('change', function () {
        const refNo = $(this).val().trim();
        if (refNo === "") {
            $('#start_date').val("");
            $('#end_date').val("");
            $('#training_type').val("");
            $('#training_title').val("");
            $('#t_institute').val("");
            return;
        }

        $.ajax({
            url: "fetch_employee.php",
            type: "POST",
            data: { ref_no: refNo },
            success: function (response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $('#start_date').val(data.start_date);
                    $('#end_date').val(data.end_date);
                    $('#training_type').val(data.training_type);
                    $('#training_title').val(data.training_title);
                    $('#t_institute').val(data.t_institute);
                } else {
                    $('#start_date').val("");
                    $('#end_date').val("");
                    $('#training_type').val("");
                    $('#training_title').val("");
                    $('#t_institute').val("");
                }
            },
            error: function () {
                alert("An error occurred while fetching office order details.");
            }
        });
    });
    
    // Form submission
    $('#employeeForm').on('submit', function(e) {
        e.preventDefault();
        // Add your form submission logic here
        setTimeout(() => {
            $('#employeeModal').modal('hide');
            showRainbowToast('Training information saved successfully!');
        }, 1500);
    });
    
    // Rainbow Toast notification
    function showRainbowToast(message) {
        const toast = $(`
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(45deg, #FF6B6B, #FFE66D, #1A936F, #4361EE, #F72585);">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        `);
        $('body').append(toast);
        const toastElement = new bootstrap.Toast(toast.find('.toast')[0]);
        toastElement.show();
        setTimeout(() => toast.remove(), 3000);
    }
    
    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // Ctrl + N to open modal
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            $('#employeeModal').modal('show');
        }
        // Escape to close modal
        if (e.key === 'Escape') {
            $('.modal').modal('hide');
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Add floating animation to stat cards
$(window).scroll(function() {
    $('.stat-card').each(function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        
        if (elementBottom > viewportTop && elementTop < viewportBottom) {
            $(this).addClass('animate__animated animate__fadeInUp');
        }
    });
});

// Add click ripple effect to buttons
document.querySelectorAll('.btn-custom, .btn-rainbow').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.7);
            transform: scale(0);
            animation: ripple 0.6s linear;
            width: ${size}px;
            height: ${size}px;
            top: ${y}px;
            left: ${x}px;
        `;
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});
</script>

<style>
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Add floating animation to the FAB */
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(180deg); }
}

.fab-rainbow {
    animation: float 6s ease-in-out infinite;
}

/* Add glow to active elements */
.form-control-rainbow:focus, .form-select-rainbow:focus {
    animation: glow 1.5s ease-in-out infinite alternate;
}

@keyframes glow {
    from {
        box-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #FF6B6B, 0 0 20px #FF6B6B;
    }
    to {
        box-shadow: 0 0 10px #fff, 0 0 20px #FFE66D, 0 0 30px #1A936F, 0 0 40px #4361EE;
    }
}
</style>

</body>
</html>