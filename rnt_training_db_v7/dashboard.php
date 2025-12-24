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
    
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-secondary-rgb: 58, 12, 163;
            --bs-success-rgb: 56, 176, 0;
            --bs-warning-rgb: 255, 158, 0;
            --bs-danger-rgb: 230, 57, 70;
            --bs-info-rgb: 76, 201, 240;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }
        
        /* Custom Card Design */
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        /* Custom Button Styles */
        .btn-custom {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-gradient-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }
        
        .btn-gradient-warning {
            background: linear-gradient(135deg, #ff9e00, #ff6d00);
            color: white;
        }
        
        .btn-gradient-success {
            background: linear-gradient(135deg, #38b000, #2d7d46);
            color: white;
        }
        
        .btn-gradient-danger {
            background: linear-gradient(135deg, #e63946, #d00000);
            color: white;
        }
        
        .btn-gradient-info {
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
        }
        
        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 10px;
        }
        
        .table-header-custom {
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            color: white;
        }
        
        .table-header-custom th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table-hover-custom tbody tr {
            transition: all 0.3s ease;
        }
        
        .table-hover-custom tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05) !important;
            transform: translateX(5px);
        }
        
        /* Modal Styling */
        .modal-header-custom {
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            color: white;
            border-radius: 0;
        }
        
        .modal-content-custom {
            border-radius: 15px;
            border: none;
            overflow: hidden;
        }
        
        /* Form Styling */
        .form-control-custom, .form-select-custom {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            transform: translateY(-1px);
        }
        
        .form-label-custom {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        /* Badge Styling */
        .badge-custom {
            padding: 0.5em 1em;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85em;
        }
        
        /* User Info Card */
        .user-card {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 5px solid #4361ee;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        /* Search Box */
        .search-box {
            max-width: 400px;
        }
        
        .search-box .input-group {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        
        /* Quick Stats Cards */
        .stat-icon-primary {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
        }
        
        .stat-icon-success {
            background: rgba(56, 176, 0, 0.1);
            color: #38b000;
        }
        
        .stat-icon-warning {
            background: rgba(255, 158, 0, 0.1);
            color: #ff9e00;
        }
        
        .stat-icon-info {
            background: rgba(76, 201, 240, 0.1);
            color: #4cc9f0;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .btn-custom {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .user-card {
                margin-top: 1rem;
            }
        }
        
        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            border: none;
        }
        
        .fab:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
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
        
        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }
        
        /* Status Indicators */
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        
        .status-active {
            background-color: #38b000;
            box-shadow: 0 0 10px rgba(56, 176, 0, 0.5);
        }
        
        .status-pending {
            background-color: #ff9e00;
            box-shadow: 0 0 10px rgba(255, 158, 0, 0.5);
        }
        
        .status-inactive {
            background-color: #6c757d;
            box-shadow: 0 0 10px rgba(108, 117, 125, 0.5);
        }
    </style>
</head>
<body class="bg-light">

<!-- Loading Overlay -->
<div class="loading-overlay">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Floating Action Button -->
<button class="fab animate__animated animate__bounceIn" data-bs-toggle="modal" data-bs-target="#employeeModal">
    <i class="fas fa-plus"></i>
</button>

<div class="container-fluid py-2">
    <!-- Header Section -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white bg-primary">
                <div class="card-body p-2 p-md-3">
                    <div class="row align-items-center ">
                        <div class="col-md-8 ">
                            <h1 class="display-5 fw-bold mb-3 animate__animated animate__fadeInLeft">
                                <i class="fas fa-database me-3"></i>Employees Training Database
                            </h1>
                            <p class="lead mb-0 animate__animated animate__fadeInLeft animate__delay-1s">
                                <i class="fas fa-building me-2"></i>RNT, Personnel Division, BCIC.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="user-card d-inline-block animate__animated animate__fadeInRight">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($username, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($username); ?></h6>
                                        <p class="mb-0 text-muted small">
                                            <span class="status-indicator status-active"></span>
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
    </div>

    <!-- Quick Stats Section -->
    <div class="row mb-2 g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Total Employees</h6>
                            <h2 class="fw-bold mb-0"><?php echo number_format($total_employees); ?></h2>
                        </div>
                        <div class="card-icon stat-icon-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary">Active</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm animate__animated animate__fadeInUp animate__delay-1s">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">BCIC Staff</h6>
                            <h2 class="fw-bold mb-0"><?php echo number_format($total_bcic_staff); ?></h2>
                        </div>
                        <div class="card-icon stat-icon-info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-info bg-opacity-10 text-info">Corporate</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm animate__animated animate__fadeInUp animate__delay-2s">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Total Trainings</h6>
                            <h2 class="fw-bold mb-0"><?php echo number_format($total_trainings); ?></h2>
                        </div>
                        <div class="card-icon stat-icon-success">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success">Ongoing</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow-sm animate__animated animate__fadeInUp animate__delay-3s">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase mb-2">Designations</h6>
                            <h2 class="fw-bold mb-0"><?php echo number_format($total_designations); ?></h2>
                        </div>
                        <div class="card-icon stat-icon-warning">
                            <i class="fas fa-sitemap"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-warning bg-opacity-10 text-warning">Roles</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <div class="d-flex flex-wrap justify-content-between gap-3">

                    <!-- LEFT SIDE BUTTONS -->
                    <div class="d-flex flex-wrap gap-2">
                        <a href="includes/add_designation.php" class="btn btn-gradient-primary btn-sm">
                            <i class="fas fa-plus-circle me-2"></i> Add Designation
                        </a>
                        <a href="includes/add_training_title.php" class="btn btn-gradient-success btn-sm">
                            <i class="fas fa-book-medical me-2"></i> Add Training Title
                        </a>
                        <a href="includes/add_office_or_factory.php" class="btn btn-gradient-warning btn-sm">
                            <i class="fas fa-building me-2"></i> Add Office
                        </a>
                        <a href="add_office_order.php" class="btn btn-gradient-info btn-sm">
                            <i class="fas fa-file-alt me-2"></i> Add Office Order
                        </a>
                    </div>

                    <!-- RIGHT SIDE BUTTONS -->
                    <div class="d-flex flex-wrap gap-2">
                        <a href="main_dashboard.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-home me-2"></i> Home
                        </a>
                        <button class="btn btn-gradient-warning btn-sm" data-bs-toggle="modal" data-bs-target="#employeeModal">
                            <i class="fas fa-user-plus me-2"></i> Add Training
                        </button>

                        <a href="multi_searching.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-search me-2"></i> Multi-Search
                        </a>

                        <form action="dawnload_database.php" method="post" class="m-0">
                            <button class="btn btn-gradient-success btn-sm" type="submit" name="submit">
                                <i class="fas fa-download me-2"></i> Download DB
                            </button>
                        </form>

                        <a href="includes/logout.php" class="btn btn-gradient-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


    <!-- Search and Export Section -->
    <!-- <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="search-box">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-0" placeholder="Search in database...">
                                    <button class="btn btn-primary" type="button">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                                <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                                    <button class="btn btn-gradient-success btn-custom" type="submit" name="submit">
                                        <i class="fas fa-download me-2"></i> Download DB
                                    </button>
                                </form>
                                <a href="includes/logout.php" class="btn btn-gradient-danger btn-custom">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Data Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-responsive">
                    <table id="employeeTable" class="table table-hover-custom mb-0">
                        <thead class="table-header-custom">
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
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
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
                            <label for="emp_id" class="form-label-custom">
                                <i class="fas fa-id-card text-primary me-1"></i> EMP ID
                            </label>
                            <input list="emp_ids"
                                type="text" 
                                class="form-control form-control-custom" 
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
                            <label for="name" class="form-label-custom">
                                <i class="fas fa-user text-primary me-1"></i> Name
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-custom" 
                                id="name" 
                                name="name" 
                                placeholder="Enter full name" 
                                required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <!-- Designation Field -->
                        <div class="col-md-6">
                            <label for="designation" class="form-label-custom">
                                <i class="fas fa-user-tie text-primary me-1"></i> Designation
                            </label>
                            <input list="designations" id="designation" name="designation" class="form-control form-control-custom" required>
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
                            <label for="place_of_posting" class="form-label-custom">
                                <i class="fas fa-building text-primary me-1"></i> Place of Posting
                            </label>
                            <select class="form-select form-select-custom" id="place_of_posting" name="place_of_posting" required>
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
        </script>

                    <!-- Reference No. Field -->
                    <div class="mt-3">
                        <label for="ref_no" class="form-label-custom">
                            <i class="fas fa-file-alt text-primary me-1"></i> Reference No.
                        </label>
                        <select class="form-select form-select-custom" id="ref_no" name="ref_no" readonly>
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
                            <label for="training_type" class="form-label-custom">
                                <i class="fas fa-graduation-cap text-primary me-1"></i> Training Type
                            </label>
                            <input type="text" class="form-control form-control-custom" id="training_type" name="training_type" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="training_title" class="form-label-custom">
                                <i class="fas fa-book text-primary me-1"></i> Title/Subject
                            </label>
                            <input type="text" class="form-control form-control-custom" id="training_title" name="training_title" readonly>
                        </div>
                    </div>
                    
                    <!-- Dates Section -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label-custom">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> Start Date
                            </label>
                            <input type="date" class="form-control form-control-custom" id="start_date" name="start_date" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label-custom">
                                <i class="fas fa-calendar-times text-primary me-1"></i> End Date
                            </label>
                            <input type="date" class="form-control form-control-custom" id="end_date" name="end_date" readonly>
                        </div>
                    </div>
                    
                    <!-- Institute Field -->
                    <div class="mt-3">
                        <label for="t_institute" class="form-label-custom">
                            <i class="fas fa-university text-primary me-1"></i> Institute
                        </label>
                        <input type="text" class="form-control form-control-custom" id="t_institute" name="t_institute" readonly>
                    </div>
                </div>
                
                <div class="modal-footer border-0 bg-light p-4">
                    <button type="button" class="btn btn-outline-secondary btn-custom" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-gradient-primary btn-custom">
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

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="script.js"></script>
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
            // Add custom styling to DataTable elements
            $('.dataTables_length select').addClass('form-select-custom');
            $('.dataTables_filter input').addClass('form-control-custom');
        }
    });
    
    // Add animation to table rows on hover
    $('#employeeTable tbody').on('mouseenter', 'tr', function() {
        $(this).addClass('animate__animated animate__pulse');
    }).on('mouseleave', 'tr', function() {
        $(this).removeClass('animate__animated animate__pulse');
    });
    
    // Loading overlay functions
    function showLoading() {
        $('.loading-overlay').fadeIn();
    }
    
    function hideLoading() {
        $('.loading-overlay').fadeOut();
    }
    
    // AJAX functionality remains the same
    $('#emp_id').on('change', function () {
        const empId = $(this).val().trim();

        if (empId === "") {
            $('#name').val("");
            $('#designation').val("");
            $('#place_of_posting').val("");
            return;
        }

        showLoading();
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
    
    // Form submission
    $('#employeeForm').on('submit', function(e) {
        e.preventDefault();
        showLoading();
        // Add your form submission logic here
        setTimeout(() => {
            hideLoading();
            $('#employeeModal').modal('hide');
            showToast('Training information saved successfully!', 'success');
        }, 1500);
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = $(`
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
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

// Add some animation to cards on scroll
$(window).scroll(function() {
    $('.stat-card').each(function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        
        if (elementBottom > viewportTop && elementTop < viewportBottom) {
            $(this).addClass('animate__animated animate__fadeIn');
        }
    });
});
</script>

</body>
</html>