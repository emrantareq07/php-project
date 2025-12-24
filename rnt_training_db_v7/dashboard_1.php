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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Management System</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #38b000;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --light-bg: #f8f9fa;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --gradient-warning: linear-gradient(135deg, #ff9e00 0%, #ff6d00 100%);
            --gradient-success: linear-gradient(135deg, #38b000 0%, #2d7d46 100%);
            --gradient-danger: linear-gradient(135deg, #e63946 0%, #d00000 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            margin: 10px auto;
            padding: 0;
            overflow: hidden;
            border: none;
        }
        
        .dashboard-header {
            background: var(--gradient-primary);
            color: white;
            padding: 25px 35px;
            margin-bottom: 0;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.1;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .main-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .sub-title {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 400;
            letter-spacing: 1px;
        }
        
        .action-section {
            padding: 30px 40px;
            background: var(--light-bg);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .btn-glow {
            transition: all 0.3s ease;
            border: none;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-glow::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-glow:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(50, 50);
                opacity: 0;
            }
        }
        
        .btn-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .btn-glow:active {
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }
        
        .btn-primary:hover {
            color: white;
            background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
        }
        
        .btn-warning {
            background: var(--gradient-warning);
            color: white;
        }
        
        .btn-warning:hover {
            color: white;
            background: linear-gradient(135deg, #ff6d00 0%, #ff9e00 100%);
        }
        
        .btn-danger {
            background: var(--gradient-danger);
            color: white;
        }
        
        .btn-danger:hover {
            color: white;
            background: linear-gradient(135deg, #d00000 0%, #e63946 100%);
        }
        
        .btn-success {
            background: var(--gradient-success);
            color: white;
        }
        
        .btn-success:hover {
            color: white;
            background: linear-gradient(135deg, #2d7d46 0%, #38b000 100%);
        }
        
        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            font-weight: 600;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
        }
        
        .table-section {
            padding: 30px 40px;
            background: white;
        }
        
        .data-table-wrapper {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .table-header {
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            color: white;
            border: none;
        }
        
        .table-header th {
            border: none;
            padding: 18px 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .table-hover tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .table-hover tbody tr:hover {
            background: linear-gradient(to right, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.03));
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .table-hover tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            border: none;
            color: #333;
            font-weight: 500;
        }
        
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .badge-active {
            background: rgba(56, 176, 0, 0.1);
            color: #38b000;
            border: 1px solid rgba(56, 176, 0, 0.3);
        }
        
        .badge-pending {
            background: rgba(255, 158, 0, 0.1);
            color: #ff9e00;
            border: 1px solid rgba(255, 158, 0, 0.3);
        }
        
        .badge-completed {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
            border: 1px solid rgba(67, 97, 238, 0.3);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-edit {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
        }
        
        .btn-edit:hover {
            background: #4361ee;
            color: white;
            transform: scale(1.1);
        }
        
        .btn-delete {
            background: rgba(230, 57, 70, 0.1);
            color: #e63946;
        }
        
        .btn-delete:hover {
            background: #e63946;
            color: white;
            transform: scale(1.1);
        }
        
        .btn-view {
            background: rgba(56, 176, 0, 0.1);
            color: #38b000;
        }
        
        .btn-view:hover {
            background: #38b000;
            color: white;
            transform: scale(1.1);
        }
        
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .modal-header {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 25px 30px;
        }
        
        .modal-title {
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-1px);
        }
        
        .form-control:hover, .form-select:hover {
            border-color: #b0b0b0;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 20px 30px;
            background: #f8f9fa;
            border-radius: 0 0 20px 20px;
        }
        
        .quick-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 10px;
            flex: 1;
            min-width: 150px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border-left: 5px solid;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card-1 { border-left-color: #4361ee; }
        .stat-card-2 { border-left-color: #3a0ca3; }
        .stat-card-3 { border-left-color: #4cc9f0; }
        .stat-card-4 { border-left-color: #38b000; }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 5px;
            opacity: 0.8;
        }
        
        .user-info {
            position: absolute;
            top: 10px;
            right: 40px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-avatar {
            width: 30px;
            height: 30px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .user-details {
            color: white;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .user-role {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 20px;
            }
            
            .main-title {
                font-size: 2rem;
            }
            
            .action-section {
                padding: 20px;
            }
            
            .table-section {
                padding: 20px;
            }
            
            .user-info {
                position: relative;
                top: 0;
                right: 0;
                margin-top: 20px;
                justify-content: center;
            }
            
            .quick-stats {
                flex-direction: column;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 15px;
            }
            
            .d-flex.justify-content-between > div {
                width: 100%;
            }
        }
        
        .attachment-badge {
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .attachment-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 201, 240, 0.3);
        }
        
        .date-cell {
            background: rgba(67, 97, 238, 0.05);
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 600;
            color: #4361ee;
            text-align: center;
            display: inline-block;
            min-width: 100px;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #4361ee, #3a0ca3);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #3a0ca3, #4361ee);
        }
        
        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .fab-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .fab-button:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 15px 40px rgba(67, 97, 238, 0.6);
        }
        
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 10px 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(67, 97, 238, 0.1);
            transition: all 0.3s ease;
            max-width: 400px;
            margin-left: auto;
        }
        
        .search-box:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 5px 25px rgba(67, 97, 238, 0.2);
        }
        
        .search-input {
            border: none;
            outline: none;
            width: 100%;
            padding: 5px;
            font-size: 1rem;
        }
        
        .search-icon {
            color: var(--primary-color);
            opacity: 0.7;
        }
    </style>
</head>
<body>

<!-- Floating Action Button -->
<div class="floating-action">
    <button class="fab-button" data-bs-toggle="modal" data-bs-target="#employeeModal">
        <i class="fa fa-plus"></i>
    </button>
</div>

<div class="container-fluid">
    <div class="main-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1 class="main-title">Employees Training Database</h1>
                <p class="sub-title">RNT, Personnel Division, BCIC.</p>
            </div>
            
            <!-- User Info -->
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($user_type); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats Section -->
        <div class="action-section">
            <?php
            // Fetch quick stats
            $total_employees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM employees"))['total'];
            $total_bcic_staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bcic_staff"))['total'];
            $total_trainings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM office_order"))['total'];
            $total_training_titles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM training_list"))['total'];
            ?>
            
            <div class="quick-stats">
                <div class="stat-card stat-card-1">
                    <div class="stat-icon text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_employees); ?></div>
                    <div class="stat-label">Total Employees</div>
                </div>
                
                <div class="stat-card stat-card-2">
                    <div class="stat-icon text-info">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_bcic_staff); ?></div>
                    <div class="stat-label">BCIC Staff</div>
                </div>
                
                <div class="stat-card stat-card-3">
                    <div class="stat-icon text-success">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_trainings); ?></div>
                    <div class="stat-label">Total Trainings</div>
                </div>
                
                <div class="stat-card stat-card-4">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($total_training_titles); ?></div>
                    <div class="stat-label">Training Programs</div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Left-aligned buttons -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="includes/add_designation.php" class="btn btn-primary btn-glow btn-sm">
                        <i class="fa fa-plus me-2"></i>Add Designation
                    </a>
                    <a href="includes/add_training_title.php" class="btn btn-success btn-glow btn-sm">
                        <i class="fa fa-plus me-2"></i>Add Training Title
                    </a>
                    <a href="includes/add_office_or_factory.php" class="btn btn-warning btn-glow btn-sm">
                        <i class="fa fa-plus me-2"></i>Add Office
                    </a>
                    <a href="add_office_order.php" class="btn btn-info btn-glow btn-sm">
                        <i class="fa fa-plus me-2"></i>Add Office Order
                    </a>
                </div>

                <!-- Right-aligned buttons -->
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <button class="btn btn-warning btn-glow btn-sm" data-bs-toggle="modal" data-bs-target="#employeeModal">
                        <i class="fa fa-plus me-2"></i>Add Training Info.
                    </button>
                    <a href="multi_searching.php" class="btn btn-primary btn-glow btn-sm">
                        <i class="fa fa-search me-2"></i>Multi-Search
                    </a>
                    <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                        <button class="btn btn-success btn-glow btn-sm" type="submit" name="submit">
                            <i class="fa fa-download me-2"></i>Download DB
                        </button>
                    </form>
                    <a href="includes/logout.php" class="btn btn-danger btn-glow btn-sm">
                        <i class="fa fa-sign-out me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Data Table Section -->
        <div class="table-section">
            <div class="data-table-wrapper">
                <table id="employeeTable" class="table table-hover mb-0">
                    <thead class="table-header">
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

<!-- Modal for Add/Edit -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add Employees Training
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="emp_id" class="form-label">
                                <i class="fas fa-id-card me-1"></i> EMP ID
                            </label>
                            <input list="emp_ids"
                                type="text" 
                                class="form-control" 
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

                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-1"></i> Name
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="name" 
                                name="name" 
                                placeholder="Enter full name" 
                                required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="designation" class="form-label">
                                <i class="fas fa-user-tie me-1"></i> Designation
                            </label>
                            <input list="designations" id="designation" name="designation" class="form-control" required>
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

                        <div class="col-md-6">
                            <label for="place_of_posting" class="form-label">
                                <i class="fas fa-building me-1"></i> Place of Posting
                            </label>
                            <select class="form-select" id="place_of_posting" name="place_of_posting" required>
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

                    <div class="mt-3">
                        <label for="ref_no" class="form-label">
                            <i class="fas fa-file-alt me-1"></i> Reference No.
                        </label>
                        <select class="form-select" id="ref_no" name="ref_no" readonly>
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
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="training_type" class="form-label">
                                <i class="fas fa-graduation-cap me-1"></i> Training Type
                            </label>
                            <input type="text" class="form-control" id="training_type" name="training_type" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="training_title" class="form-label">
                                <i class="fas fa-book me-1"></i> Title/Subject
                            </label>
                            <input type="text" class="form-control" id="training_title" name="training_title" readonly>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-start me-1"></i> Start Date
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-times me-1"></i> End Date
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date" readonly>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="t_institute" class="form-label">
                            <i class="fas fa-university me-1"></i> Institute
                        </label>
                        <input type="text" class="form-control" id="t_institute" name="t_institute" readonly>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-glow">
                        <i class="fas fa-save me-2"></i> Save Training
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Trigger AJAX request when the EMP ID field value changes
    $('#emp_id').on('change', function () {
        const empId = $(this).val().trim();

        // Do not proceed if the input is empty
        if (empId === "") {
            $('#name').val("");
            $('#designation').val("");
            $('#place_of_posting').val("");
            return;
        }

        // AJAX request to fetch employee details based on EMP ID
        $.ajax({
            url: "fetch_employee.php", // PHP file to handle the request
            type: "POST",
            data: { emp_id: empId },
            success: function (response) {
                // Parse the JSON response from PHP
                const data = JSON.parse(response);

                if (data.success) {
                    // Update fields with the fetched data
                    $('#name').val(data.name);
                    $('#designation').val(data.designation);
                    $('#place_of_posting').val(data.place_of_posting);
                } else {
                    // Clear the fields if no record is found
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

        // Trigger AJAX request when the Reference No. field value changes
        $('#ref_no').on('change', function () {
            const refNo = $(this).val().trim();
            // Do not proceed if the input is empty
            if (refNo === "") {
                $('#start_date').val("");
                $('#end_date').val("");
                $('#training_type').val("");
                $('#training_title').val("");
                $('#t_institute').val("");
                return;
            }

            // AJAX request to fetch office order details based on Reference No.
            $.ajax({
                url: "fetch_employee.php", // PHP file to handle the request
                type: "POST",
                data: { ref_no: refNo },
                success: function (response) {
                    // Parse the JSON response from PHP
                    const data = JSON.parse(response);

                    if (data.success) {
                        // Update fields with the fetched data
                        $('#start_date').val(data.start_date);
                        $('#end_date').val(data.end_date);
                        $('#training_type').val(data.training_type);
                        $('#training_title').val(data.training_title);
                        $('#t_institute').val(data.t_institute);
                    } else {
                        // Clear the fields if no record is found
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
        });
       

// Add ripple effect to buttons
document.querySelectorAll('.btn-glow').forEach(button => {
    button.addEventListener('click', function(e) {
        const x = e.clientX - e.target.getBoundingClientRect().left;
        const y = e.clientY - e.target.getBoundingClientRect().top;
        
        const ripple = document.createElement('span');
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 1000);
    });
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + N to open modal
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        $('#employeeModal').modal('show');
    }
    // Esc to close modal
    if (e.key === 'Escape') {
        $('.modal').modal('hide');
    }
});

// Add loading animation
function showLoading() {
    const loading = document.createElement('div');
    loading.id = 'loadingOverlay';
    loading.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loadingOverlay');
    if (loading) loading.remove();
}
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="script.js"></script>

<!-- Add ripple effect CSS -->
<style>
.ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.7);
    transform: scale(0);
    animation: ripple 0.6s linear;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.loading-spinner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.hover-row {
    transform: scale(1.01);
    transition: transform 0.3s ease;
}

/* Custom checkboxes */
.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Custom radio buttons */
.form-check-input[type="radio"]:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
</style>

</body>
</html>