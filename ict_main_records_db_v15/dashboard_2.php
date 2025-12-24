<?php
session_name('ict_main_records_db');
session_start();
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];
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
    <title>ICT Maintenance Dashboard</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --info-color: #7209b7;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            margin: 0;
            padding-top: 0;
        }
        
        .main-container {
            padding: 15px;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .dashboard-header {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stats-card.success {
            border-left-color: var(--success-color);
        }
        
        .stats-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stats-card.info {
            border-left-color: var(--info-color);
        }
        
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 10px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .icon-circle.success {
            background: linear-gradient(45deg, #4cc9f0, #00b4d8);
        }
        
        .icon-circle.warning {
            background: linear-gradient(45deg, #f72585, #b5179e);
        }
        
        .icon-circle.info {
            background: linear-gradient(45deg, #7209b7, #560bad);
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-color);
        }
        
        .stats-card.success .stat-number {
            color: var(--success-color);
        }
        
        .stats-card.warning .stat-number {
            color: var(--warning-color);
        }
        
        .stats-card.info .stat-number {
            color: var(--info-color);
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .btn-custom-success {
            background: linear-gradient(45deg, #4cc9f0, #00b4d8);
        }
        
        .btn-custom-warning {
            background: linear-gradient(45deg, #f72585, #b5179e);
        }
        
        .btn-custom-danger {
            background: linear-gradient(45deg, #ff595e, #ff6b6b);
        }
        
        .data-table-container {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-top: 15px;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            margin-bottom: 0;
            font-size: 0.85rem;
            width: 100% !important;
            min-width: 1000px;
        }
        
        .table thead th {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 8px;
            font-size: 0.8rem;
            white-space: nowrap;
            position: sticky;
            top: 0;
        }
        
        .table tbody td {
            padding: 8px;
            vertical-align: middle;
            font-size: 0.85rem;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.04);
        }
        
        .action-buttons .btn {
            margin: 1px;
            padding: 4px 8px;
            font-size: 0.8rem;
            border-radius: 5px;
        }
        
        .user-details-compact {
            line-height: 1.2;
        }
        
        .user-details-compact .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            display: block;
        }
        
        .user-details-compact .user-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .badge-sm {
            padding: 3px 8px;
            font-size: 0.75rem;
            border-radius: 10px;
        }
        
        .product-name-tooltip {
            cursor: help;
            border-bottom: 1px dotted #666;
            display: inline-block;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .main-container {
                padding: 10px;
            }
            
            .dashboard-header {
                padding: 15px;
            }
            
            .btn-custom {
                padding: 6px 12px;
                font-size: 0.8rem;
                margin-bottom: 5px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 6px 4px;
                font-size: 0.8rem;
            }
            
            .table {
                font-size: 0.8rem;
                min-width: 800px;
            }
        }
        
        @media print {
            .btn-custom, .action-buttons, .dataTables_filter, 
            .dataTables_length, .dataTables_paginate, .dt-buttons {
                display: none !important;
            }
            
            .table thead th {
                background: #333 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }
        }
        
        .form-control-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 5px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .dataTables_wrapper {
            font-size: 0.85rem;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            padding: 5px;
            font-size: 0.85rem;
        }
        
        .dt-buttons {
            margin-bottom: 10px;
        }
        
        .dt-buttons .btn {
            padding: 4px 8px;
            font-size: 0.8rem;
            margin-right: 3px;
            margin-bottom: 3px;
        }
        
        .text-truncate-custom {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="main-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-1"><i class="fas fa-tools me-2"></i>ICT Maintenance Records</h4>
                <p class="mb-0 opacity-75" style="font-size: 0.9rem;">ICT Division, BCIC - Maintenance Tracking System</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="bg-white text-dark rounded-pill px-3 py-1 d-inline-block">
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($username); ?></span>
                    <small class="text-muted ms-2">(<?php echo htmlspecialchars($user_type); ?>)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card fade-in">
                <div class="d-flex align-items-center">
                    <div class="icon-circle me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-number" id="totalEmployees">
                            <?php 
                            $empQuery = "SELECT COUNT(*) as total FROM employees";
                            $empResult = mysqli_query($conn, $empQuery);
                            echo mysqli_fetch_assoc($empResult)['total'] ?? 0;
                            ?>
                        </div>
                        <div class="stat-label">Total Employees</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card success fade-in">
                <div class="d-flex align-items-center">
                    <div class="icon-circle success me-3">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div>
                        <div class="stat-number" id="totalRecords">
                            <?php 
                            $recQuery = "SELECT COUNT(*) as total FROM records_tbl";
                            $recResult = mysqli_query($conn, $recQuery);
                            echo mysqli_fetch_assoc($recResult)['total'] ?? 0;
                            ?>
                        </div>
                        <div class="stat-label">Maintenance Records</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card warning fade-in">
                <div class="d-flex align-items-center">
                    <div class="icon-circle warning me-3">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="stat-number" id="totalDivisions">
                            <?php 
                            $divQuery = "SELECT COUNT(*) as total FROM division";
                            $divResult = mysqli_query($conn, $divQuery);
                            echo mysqli_fetch_assoc($divResult)['total'] ?? 0;
                            ?>
                        </div>
                        <div class="stat-label">Divisions/Departments</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card info fade-in">
                <div class="d-flex align-items-center">
                    <div class="icon-circle info me-3">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <div class="stat-number" id="totalVendors">
                            <?php 
                            $venQuery = "SELECT COUNT(*) as total FROM vendor_list";
                            $venResult = mysqli_query($conn, $venQuery);
                            echo mysqli_fetch_assoc($venResult)['total'] ?? 0;
                            ?>
                        </div>
                        <div class="stat-label">Vendors</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 mb-2">
                <!-- Left side buttons -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="includes/add_designation.php" class="btn btn-custom">
                        <i class="fa fa-plus me-1"></i> Designation
                    </a>
                    <a href="includes/add_employee.php" class="btn btn-custom">
                        <i class="fa fa-user-plus me-1"></i> Employee
                    </a>
                    <a href="includes/add_division.php" class="btn btn-custom">
                        <i class="fa fa-building me-1"></i> Division
                    </a>
                    <a href="includes/add_products.php" class="btn btn-custom">
                        <i class="fa fa-box me-1"></i> Products
                    </a>
                </div>
                
                <!-- Right side buttons -->
                <div class="d-flex flex-wrap gap-2 ms-auto">
                    <button class="btn btn-custom-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa fa-plus-circle me-1"></i> Add Record
                    </button>
                    <a href="dateRange_searching.php" class="btn btn-custom">
                        <i class="fa fa-search me-1"></i> Search
                    </a>
                    <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                        <button class="btn btn-custom-warning" type="submit" name="submit">
                            <i class="fa fa-download me-1"></i> Export DB
                        </button>
                    </form>
                    <a href="includes/logout.php" class="btn btn-custom-danger">
                        <i class="fa fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable Section -->
    <div class="data-table-container fade-in">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-dark fw-bold">
                <i class="fas fa-table me-2 text-primary"></i>Maintenance Records
            </h6>
            <span class="badge bg-primary badge-sm">
                <i class="fas fa-database me-1"></i> Total: <span id="recordCounter">
                    <?php 
                    $countQuery = "SELECT COUNT(*) as total FROM records_tbl";
                    $countResult = mysqli_query($conn, $countQuery);
                    echo mysqli_fetch_assoc($countResult)['total'] ?? 0;
                    ?>
                </span>
            </span>
        </div>
        
        <div class="table-responsive">
            <table id="recordsTable" class="table table-hover table-sm" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="8%">EMP ID</th> 
                        <th width="18%">User Details</th>
                        <th width="8%">Req No</th>
                        <th width="8%">Date</th>
                        <th width="15%">Product</th>
                        <th width="8%">SRM</th>
                        <th width="10%">SRM Ref</th>
                        <th width="10%">Bill/Challan</th>
                        <th width="10%">Remarks</th>
                        <th width="5%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Record Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); color: white;">
                <h6 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Record</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 15px;">
                <form id="addForm" method="POST">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Requisition No</label>
                            <input type="text" class="form-control form-control-sm" id="requisition_no" name="requisition_no" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Date</label>
                            <input type="date" class="form-control form-control-sm" id="date" name="date" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">EMP ID</label>
                            <input list="emp_ids" type="text" class="form-control form-control-sm" id="emp_id" name="emp_id" required>
                            <datalist id="emp_ids">
                                <?php
                                $sqlemp_id = "SELECT emp_id FROM employees";
                                $resultemp_id = mysqli_query($conn, $sqlemp_id);
                                while ($rowemp_id = mysqli_fetch_array($resultemp_id)) {
                                    echo "<option value='" . htmlspecialchars($rowemp_id['emp_id'], ENT_QUOTES, 'UTF-8') . "'>";
                                }
                                ?>
                            </datalist>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">User Name</label>
                            <input type="text" class="form-control form-control-sm" id="user_name" name="user_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Division/Dept</label>
                            <input list="division_depts" class="form-control form-control-sm" id="division_dept" name="division_dept" required>
                            <datalist id="division_depts">
                                <?php
                                $sql = "SELECT division FROM division ORDER BY division ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . htmlspecialchars($row['division'], ENT_QUOTES, 'UTF-8') . "'>";
                                }
                                ?>
                            </datalist>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Designation</label>
                            <input list="designations" class="form-control form-control-sm" id="designation" name="designation" required>
                            <datalist id="designations">
                                <?php
                                $sql = "SELECT designation FROM designation ORDER BY designation ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . htmlspecialchars($row['designation'], ENT_QUOTES, 'UTF-8') . "'>";
                                }
                                ?>
                            </datalist>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Product Name</label>
                            <input list="product_list" class="form-control" id="my-input" autocomplete="off">
                            <datalist id="product_list"></datalist>
                            <div id="selected-products" class="mt-2"></div>
                            <input type="hidden" id="selected_product_ids" name="selected_product_ids">
                            <input type="hidden" id="selected_amounts" name="selected_amounts">
                            <input type="hidden" id="selected_p_sn" name="selected_p_sn">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">SRM</label>
                            <input type="text" class="form-control form-control-sm" id="srm" name="srm" placeholder="Enter SRM">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">SRM Ref No</label>
                            <input type="text" class="form-control form-control-sm" id="srm_ref_no" name="srm_ref_no" placeholder="Enter SRM Ref No">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Bill/Challan No</label>
                            <input type="text" class="form-control form-control-sm" id="bill_or_challan_no" name="bill_or_challan_no" placeholder="Enter Bill/Challan No">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Remarks</label>
                            <select class="form-select form-select-sm" id="remarks" name="remarks" required onchange="toggleVendorField()">
                                <option value="Done By Vendor">Done By Vendor</option>
                                <option value="Done By ICT">Done By ICT</option>
                                <option value="Condemn">Condemn</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Vendor Name</label>
                            <select class="form-select form-select-sm" id="vendor_name" name="vendor_name">
                                <?php
                                $sql = "SELECT vendor_name FROM vendor_list ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['vendor_name'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['vendor_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-custom-success w-100">
                                <i class="fa fa-save me-1"></i> Save Record
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    setDefaultDateAndUpdate();

    // When modal is opened, reset and update fields
    document.getElementById("addModal").addEventListener("shown.bs.modal", function () {
        setDefaultDateAndUpdate();
    });

    // When date changes, update requisition number and product list
    document.getElementById("date").addEventListener("input", function () {
        updateData();
    });

    function setDefaultDateAndUpdate() {
        const dateField = document.getElementById('date');
        dateField.value = new Date().toISOString().split('T')[0]; // Set today's date
        updateData();
    }
});

// Function to update requisition number and product list
function updateData() {
    //datelist();
    productlist();
}

// Fetch requisition number
function datelist() {
    let selectedDate = document.getElementById('date').value;

    if (selectedDate) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get_requisition_id.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                document.getElementById('requisition_no').value = response.requisition_no || "";
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate));
    }
}

// Fetch product list
function productlist() {
    let selectedDate = document.getElementById('date').value;
    let productList = document.getElementById('product_list');

    if (selectedDate) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "get_products.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                productList.innerHTML = response.product_options;
            }
        };

        xhr.send("date=" + encodeURIComponent(selectedDate));
    }
}

let selectedProducts = {};

//for reset modal
document.getElementById("addModal").addEventListener("hidden.bs.modal", function () {
    selectedProducts = {}; // Clear the selected products object
    document.getElementById("selected-products").innerHTML = ""; // Clear the display
    document.getElementById("selected_product_ids").value = ""; // Clear hidden input
    document.getElementById("selected_amounts").value = ""; // Clear hidden input
    document.getElementById("selected_p_sn").value = ""; // Clear hidden input
    document.getElementById("my-input").value = ""; // Clear the product input field
    document.getElementById("addForm").reset(); // Reset the entire form
    setDefaultDateAndUpdate(); // Reset the date field and update data
});
//close
document.getElementById("my-input").addEventListener("input", function () {
    let input = this.value.trim();
    let datalistOptions = document.getElementById("product_list").options;
    let selectedDisplay = document.getElementById("selected-products");

    for (let i = 0; i < datalistOptions.length; i++) {
        if (input === datalistOptions[i].value) {
            let productId = datalistOptions[i].getAttribute("data-id");
            let productSerial = datalistOptions[i].getAttribute("data-serial"); // Get p_sn value

                if (selectedProducts[productId]) {
                alert("This product is already selected!");
                this.value = ""; // Clear the input field
                return; // Stop further execution
            }

            if (!selectedProducts[productId]) {
                selectedProducts[productId] = { 
                    name: input, 
                    amount: "0", 
                    p_sn: "0" // Always default to "0"
                };

                let div = document.createElement("div");
                div.className = "d-flex align-items-center mb-2";
                div.setAttribute("data-id", productId);

                let span = document.createElement("span");
                span.className = "badge bg-primary p-2 me-2";
                span.textContent = input;

                let amountInput = document.createElement("input");
                amountInput.type = "number";
                amountInput.className = "form-control me-2";
                amountInput.style.width = "100px";
                amountInput.placeholder = "Amount";
                amountInput.value = "0"; // Start with 0

                let pSnInput = document.createElement("input");
                pSnInput.type = "text";
                pSnInput.className = "form-control me-2";
                pSnInput.style.width = "150px";
                pSnInput.placeholder = "Product Serial No";
                pSnInput.value = "0"; // Always default to 0

                // Event listeners for amount field
                amountInput.addEventListener("focus", function () {
                    if (this.value === "0") this.value = "";
                });

                amountInput.addEventListener("blur", function () {
                    if (this.value.trim() === "") this.value = "0";
                    selectedProducts[productId].amount = this.value;
                    updateHiddenInputs();
                });

                amountInput.addEventListener("input", function () {
                    selectedProducts[productId].amount = this.value.trim() === "" ? "0" : this.value;
                    updateHiddenInputs();
                });

                // Event listener for p_sn field
                pSnInput.addEventListener("focus", function () {
                    if (this.value === "0") this.value = "";
                });

                pSnInput.addEventListener("blur", function () {
                    if (this.value.trim() === "") this.value = "0";
                    selectedProducts[productId].p_sn = this.value;
                    updateHiddenInputs();
                });

                pSnInput.addEventListener("input", function () {
                    selectedProducts[productId].p_sn = this.value.trim() === "" ? "0" : this.value;
                    updateHiddenInputs();
                });

                                    // Restrict comma input dynamically
                    pSnInput.addEventListener("keypress", function (event) {
                        if (event.key === ",") {
                            event.preventDefault(); // Block the comma from being typed
                        }
                    });

                    pSnInput.addEventListener("input", function () {
                        this.value = this.value.replace(/,/g, ""); // Remove any pasted commas
                        selectedProducts[productId].p_sn = this.value.trim() || "0";
                        updateHiddenInputs();
                    });
                    //RETRICT COMMA END

                let removeBtn = document.createElement("button");
                removeBtn.className = "btn btn-danger btn-sm";
                removeBtn.textContent = "X";
                removeBtn.addEventListener("click", function () {
                    delete selectedProducts[productId];
                    div.remove();
                    updateHiddenInputs();
                });

                div.appendChild(span);
                div.appendChild(amountInput);
                div.appendChild(pSnInput);
                div.appendChild(removeBtn);
                selectedDisplay.appendChild(div);
            }

            this.value = ""; // Clear the input field
            break;
        }
    }

    updateHiddenInputs();
});

// Update hidden inputs with selected product data
function updateHiddenInputs() {
    let productIds = Object.keys(selectedProducts);

    if (productIds.length === 0) {
        // If no products are selected, set default values
        document.getElementById("selected_product_ids").value = "0";
        document.getElementById("selected_amounts").value = "0";
        document.getElementById("selected_p_sn").value = "0";
    } else {
        // If products are selected, join their values
        let productAmounts = productIds.map(id => selectedProducts[id].amount.trim() || "0").join(",");
        let productSerials = productIds.map(id => selectedProducts[id].p_sn.trim() || "0").join(",");

        document.getElementById("selected_product_ids").value = productIds.join(",");
        document.getElementById("selected_amounts").value = productAmounts;
        document.getElementById("selected_p_sn").value = productSerials;
    }
}

// Ensure hidden inputs are updated before form submission
document.getElementById("addForm").addEventListener("submit", function (event) {
    updateHiddenInputs();
});

$(document).ready(function () {
    // Function to toggle vendor field based on remarks selection
    function toggleVendorField() {
        const remarksValue = $('#remarks').val();
        const vendorField = $('#vendor_name');

        if (remarksValue === 'Done By Vendor') {
            vendorField.prop('disabled', false); // Enable vendor select

            // Set the last vendor in the dropdown automatically
            const lastVendorOption = vendorField.find('option:last'); // Get the last option in the dropdown
            vendorField.val(lastVendorOption.val()); // Set the value of the vendor dropdown to the last vendor
        } else {
            vendorField.prop('disabled', true).val(''); // Disable and clear selection
        }
    }

    // Initialize on page load and attach the change event
    toggleVendorField();
    $('#remarks').on('change', toggleVendorField);
});


$(document).ready(function () {
    // Trigger AJAX when the EMP ID field changes
    $('#emp_id').on('change', function () {
        const empId = $(this).val().trim();

        if (empId === "") {
            // Clear fields if no EMP ID is provided
            $('#user_name').val("");
            $('#designation').val("");
            $('#division_dept').val("");
            $('#place_of_posting').val("");
            return;
        }

        // AJAX request to fetch employee details
        $.ajax({
            url: "fetch_employee.php", // PHP file to fetch data
            type: "POST",
            data: { emp_id: empId },
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Fill the form fields with fetched data
                        $('#user_name').val(data.user_name);
                        $('#designation').val(data.designation);
                        $('#division_dept').val(data.division_dept);
                        $('#place_of_posting').val(data.place_of_posting);
                    } else {
                        alert("Employee is New. Now Insert Details....");
                        // Clear fields if no data found
                        $('#user_name').val("");
                        $('#designation').val("");
                        $('#division_dept').val("");
                        $('#place_of_posting').val("");
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                   alert("Invalid server response.");
                }
            },
            error: function () {
                alert("An error occurred while fetching employee details.");
            }
        });
    });
});
</script>
<!-- //add complete -->
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(45deg, #f72585, #b5179e); color: white;">
                <h6 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Record</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 15px;">
                <form id="editForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Requisition No</label>
                            <input type="text" class="form-control form-control-sm" id="edit_requisition_no" name="requisition_no" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Date</label>
                            <input type="date" class="form-control form-control-sm" id="edit_date" name="date" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">EMP ID</label>
                            <input list="emp_ids" type="text" class="form-control form-control-sm" id="edit_emp_id" name="emp_id" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">User Name</label>
                            <input type="text" class="form-control form-control-sm" id="edit_user_name" name="user_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Division/Dept</label>
                            <input list="division_depts" class="form-control form-control-sm" id="edit_division_dept" name="division_dept" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Designation</label>
                            <input list="designations" class="form-control form-control-sm" id="edit_designation" name="designation" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Product Name</label>
                            <input list="edit_product_list" class="form-control form-control-sm" id="edit_product_input" autocomplete="off">
                            <datalist id="edit_product_list"></datalist>
                            <div id="edit_selected_products" class="mt-2"></div>
                            <input type="hidden" id="edit_selected_product_ids" name="selected_product_ids">
                            <input type="hidden" id="edit_product_amounts" name="product_amounts">
                            <input type="hidden" id="edit_selected_p_sn" name="selected_p_sn">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">SRM</label>
                            <input type="text" class="form-control form-control-sm" id="edit_srm" name="srm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">SRM Ref No</label>
                            <input type="text" class="form-control form-control-sm" id="edit_srm_ref_no" name="srm_ref_no">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Bill/Challan No</label>
                            <input type="text" class="form-control form-control-sm" id="edit_bill_or_challan_no" name="bill_or_challan_no">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Remarks</label>
                            <select class="form-select form-select-sm" id="edit_remarks" name="remarks" required onchange="toggleEditVendorField1()">
                                <option value="Done By Vendor">Done By Vendor</option>
                                <option value="Done By ICT">Done By ICT</option>
                                <option value="Condemn">Condemn</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Vendor Name</label>
                            <select class="form-select form-select-sm" id="edit_vendor_name" name="vendor_name">
                                <?php
                                $sql_vendor_name = "SELECT vendor_name FROM vendor_list";
                                $result_vendor_name = mysqli_query($conn, $sql_vendor_name);
                                while ($row_vendor_name = mysqli_fetch_array($result_vendor_name)) {
                                    echo "<option value='" . htmlspecialchars($row_vendor_name['vendor_name'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row_vendor_name['vendor_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-custom-warning w-100">
                                <i class="fa fa-save me-1"></i> Update Record
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewEmployeeModal" class="modal fade" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(45deg, #4cc9f0, #00b4d8); color: white;">
                <h6 class="modal-title"><i class="fas fa-eye me-2"></i>Record Details</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="printableArea">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th width="30%">Requisition No:</th>
                            <td id="view_requisition_no"></td>
                        </tr>
                        <tr>
                            <th>Employee ID:</th>
                            <td id="view_emp_id"></td>
                        </tr>
                        <tr>
                            <th>User Name:</th>
                            <td id="view_user_name"></td>
                        </tr>
                        <tr>
                            <th>Division/Department:</th>
                            <td id="view_division_dept"></td>
                        </tr>
                        <tr>
                            <th>Designation:</th>
                            <td id="view_designation"></td>
                        </tr>
                        <tr>
                            <th>Place of Posting:</th>
                            <td id="view_place_of_posting"></td>
                        </tr>
                        <tr>
                            <th>Requisition Date:</th>
                            <td id="view_date"></td>
                        </tr>
                        <tr>
                            <th>Product Name:</th>
                            <td id="view_product_name"></td>
                        </tr>
                        <tr>
                            <th>SRM:</th>
                            <td id="view_srm"></td>
                        </tr>
                        <tr>
                            <th>SRM Reference No:</th>
                            <td id="view_srm_ref_no"></td>
                        </tr>
                        <tr>
                            <th>Bill/Challan No:</th>
                            <td id="view_bill_or_challan_no"></td>
                        </tr>
                        <tr>
                            <th>Remarks:</th>
                            <td id="view_remarks"></td>
                        </tr>
                        <tr>
                            <th>Vendor Name:</th>
                            <td id="view_vendor_name"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="printButton">
                    <i class="fa fa-print me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>

<!-- Export buttons -->
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
// general.js - Other General Functions

// Initialize when document is ready
$(document).ready(function() {
    // Initialize DataTable
    initializeDataTable();
    
    // Initialize tooltips
    $('[title]').tooltip();
    
    // Initialize add modal
    initializeAddModal();
    
    // Edit form submission
    $('#editForm').on('submit', handleEditFormSubmit);
    
    // Table action buttons
    $('#recordsTable').on('click', '.view-btn', handleViewClick);
    $('#recordsTable').on('click', '.edit-btn', handleEditClick);
    $('#recordsTable').on('click', '.delete-btn', handleDeleteClick);
    
    // Edit modal event listeners
    $('#edit_remarks').on('change', toggleEditVendorField);
    $('#edit_emp_id').on('change', fetchEditEmployeeDetails);
    $('#edit_date').on('change', function() {
        fetchEditProductList($(this).val());
    });
    
    // Print functionality
    $('#printButton').on('click', handlePrint);
    
    // Update stats periodically
    setInterval(updateStats, 60000);
});

// Function to initialize DataTable
function initializeDataTable() {
    window.table = $('#recordsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'fetch_records.php',
            type: 'POST',
            dataSrc: function(json) {
                $('#recordCounter').text(json.recordsTotal || 0);
                return json.data;
            }
        },
        dom: '<"row mb-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row mt-2"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_",
            infoEmpty: "No records",
            zeroRecords: "No matching records found"
        },
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy me-1"></i> Copy',
                className: 'btn btn-secondary btn-sm me-1'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm me-1'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm me-1'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Print',
                className: 'btn btn-info btn-sm'
            }
        ],
        columns: [
            {
                data: 'emp_id',
                className: 'text-center'
            },
            {
                data: null,
                render: function(data, type, row) {
                    if (type === 'export') {
                        return row.user_name + ' | ' + row.designation + ' | ' + row.division_dept;
                    }
                    return `
                        <div class="user-details-compact">
                            <span class="user-name">${row.user_name || 'N/A'}</span>
                            <span class="user-meta d-block">${row.designation || ''}</span>
                            <span class="user-meta d-block text-primary">${row.division_dept || ''}</span>
                        </div>
                    `;
                }
            },
            {
                data: 'requisition_no',
                className: 'text-center fw-bold'
            },
            {
                data: 'date',
                className: 'text-center',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-GB') : '-';
                }
            },
            {
                data: 'product_name',
                className: 'text-center',
                render: function(data) {
                    if (!data) return '<span class="text-muted">-</span>';
                    if (data.length > 30) {
                        return `<span class="product-name-tooltip" title="${data}">${data.substring(0, 30)}...</span>`;
                    }
                    return data;
                }
            },
            {
                data: 'srm',
                className: 'text-center',
                render: function(data) {
                    return data || '-';
                }
            },
            {
                data: 'srm_ref_no',
                className: 'text-center',
                render: function(data) {
                    return data ? `<span class="text-success small">${data}</span>` : '-';
                }
            },
            {
                data: 'bill_or_challan_no',
                className: 'text-center',
                render: function(data) {
                    return data || '-';
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    let html = '';
                    if (row.remarks) {
                        html += `<div class="small fw-bold">${row.remarks}</div>`;
                    }
                    if (row.vendor_name) {
                        html += `<div class="small text-primary"><i class="fas fa-truck me-1"></i>${row.vendor_name}</div>`;
                    }
                    return html || '<span class="text-muted">-</span>';
                }
            },
            {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-primary btn-sm view-btn" data-id="${row.id}" title="View">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `;
                },
                orderable: false
            }
        ],
        autoWidth: false,
        scrollX: false,
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length select').addClass('form-select form-select-sm');
            
            // Add buttons container
            $('.dataTables_filter').before('<div class="col-12 mb-2" id="tableButtons"></div>');
            this.api().buttons().container().appendTo('#tableButtons');
        }
    });
}

// Function to handle edit form submission
function handleEditFormSubmit(e) {
    e.preventDefault();
    
    let formData = $(this).serialize();
    
    $.ajax({
        url: 'update_record.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                $('#editModal').modal('hide');
                if (window.table) {
                    window.table.ajax.reload(null, false);
                }
                updateStats();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr.responseText);
            alert('An error occurred while updating the record.');
        }
    });
}

// Function to handle view click
function handleViewClick() {
    var id = $(this).data('id');
    $.ajax({
        url: 'view_fetch_single_record.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(record) {
            if (record.status === 'success') {
                var data = record.data;
                $('#view_requisition_no').text(data.requisition_no);
                $('#view_emp_id').text(data.emp_id);
                $('#view_user_name').text(data.user_name);
                $('#view_division_dept').text(data.division_dept);
                $('#view_designation').text(data.designation);
                $('#view_place_of_posting').text(data.place_of_posting || 'N/A');
                $('#view_date').text(data.date);
                $('#view_product_name').html(record.product_names || 'N/A');
                $('#view_srm').text(data.srm || 'N/A');
                $('#view_srm_ref_no').text(data.srm_ref_no || 'N/A');
                $('#view_bill_or_challan_no').text(data.bill_or_challan_no || 'N/A');
                $('#view_remarks').text(data.remarks || 'N/A');
                $('#view_vendor_name').text(data.vendor_name || 'N/A');
                $('#viewEmployeeModal').modal('show');
            } else {
                alert(record.message);
            }
        },
        error: function() {
            alert('Failed to fetch the record. Please try again.');
        }
    });
}

// Function to handle edit click
function handleEditClick() {
    var id = $(this).data('id');
    
    $.ajax({
        url: 'fetch_single_record.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                var record = response.data;
                
                // Populate edit form
                $('#edit_id').val(record.id);
                $('#edit_requisition_no').val(record.requisition_no);
                $('#edit_emp_id').val(record.emp_id);
                $('#edit_user_name').val(record.user_name);
                $('#edit_division_dept').val(record.division_dept);
                $('#edit_designation').val(record.designation);
                $('#edit_date').val(record.date);
                $('#edit_srm').val(record.srm || '');
                $('#edit_srm_ref_no').val(record.srm_ref_no || '');
                $('#edit_bill_or_challan_no').val(record.bill_or_challan_no || '');
                $('#edit_remarks').val(record.remarks);
                $('#edit_vendor_name').val(record.vendor_name || '');
                
                // Load product list and populate products
                fetchEditProductList(record.date).then(function() {
                    // Clear existing products
                    $('#edit_selected_products').html('');
                    
                    // Add products if they exist
                    if (record.products) {
                        $.each(record.products, function(productId, data) {
                            addProductToEditList(productId, data.amount, data.p_sn);
                        });
                    }
                    
                    // Show modal
                    $('#editModal').modal('show');
                });
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(error) {
            console.error('AJAX Error:', error.responseText);
            alert('An error occurred while fetching the record.');
        }
    });
}

// Function to handle delete click
function handleDeleteClick() {
    var id = $(this).data('id');
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            url: 'delete_record.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (window.table) {
                    window.table.ajax.reload();
                }
                updateStats();
                alert('Record deleted successfully!');
            },
            error: function() {
                alert('Error deleting record!');
            }
        });
    }
}

// Function to fetch edit employee details
function fetchEditEmployeeDetails() {
    const empId = $(this).val().trim();
    
    if (empId === "") {
        $('#edit_user_name').val("");
        $('#edit_designation').val("");
        $('#edit_division_dept').val("");
        return;
    }

    $.ajax({
        url: "fetch_employee.php",
        type: "POST",
        data: { emp_id: empId },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    $('#edit_user_name').val(data.user_name);
                    $('#edit_designation').val(data.designation);
                    $('#edit_division_dept').val(data.division_dept);
                } else {
                    alert("Employee is New. Now Insert Details....");
                    $('#edit_user_name').val("");
                    $('#edit_designation').val("");
                    $('#edit_division_dept').val("");
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
                alert("Invalid server response.");
            }
        },
        error: function() {
            alert("An error occurred while fetching employee details.");
        }
    });
}

// Function to toggle edit vendor field
function toggleEditVendorField() {
    const remarksValue = $('#edit_remarks').val();
    const vendorField = $('#edit_vendor_name');

    if (remarksValue === 'Done By Vendor') {
        vendorField.prop('disabled', false);
        const lastVendorOption = vendorField.find('option:last');
        vendorField.val(lastVendorOption.val());
    } else {
        vendorField.prop('disabled', true).val('');
    }
}

// Function to fetch edit product list
function fetchEditProductList(date) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'get_products.php',
            type: 'POST',
            data: { date: date },
            dataType: 'json',
            success: function(response) {
                $('#edit_product_list').html(response.product_options || '');
                resolve();
            },
            error: function() {
                console.error("Error fetching product list.");
                reject();
            }
        });
    });
}

// Function to add product to edit list
function addProductToEditList(productId, amount, p_sn) {
    let productDisplay = $('#edit_selected_products');
    let productName = $('#edit_product_list option[data-id="' + productId + '"]').val();

    if (!productName) {
        console.error(`Product with ID ${productId} not found.`);
        return;
    }

    let row = $(`
        <div class="card mb-2 border-primary">
            <div class="card-body p-2">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <span class="badge bg-primary">${productName}</span>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control form-control-sm edit-amount-input" 
                               data-id="${productId}" placeholder="Amount" value="${amount || 0}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm edit-serial-input" 
                               data-id="${productId}" placeholder="Serial No" value="${p_sn || '0'}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger btn-sm edit-remove-btn" data-id="${productId}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);

    productDisplay.append(row);
    
    // Add event listeners
    row.find('.edit-amount-input').on('input', updateEditHiddenInputs);
    row.find('.edit-serial-input').on('input', function() {
        $(this).val($(this).val().replace(/,/g, ""));
        updateEditHiddenInputs();
    });
    row.find('.edit-remove-btn').on('click', function() {
        $(this).closest('.card').remove();
        updateEditHiddenInputs();
    });
    
    updateEditHiddenInputs();
}

// Function to update edit hidden inputs
function updateEditHiddenInputs() {
    let selectedProducts = {};
    let productAmounts = {};
    let productPSNs = {};

    $('#edit_selected_products .card').each(function() {
        let productId = $(this).find('.edit-amount-input').data('id');
        let amount = $(this).find('.edit-amount-input').val().trim() || "0";
        let p_sn = $(this).find('.edit-serial-input').val().trim() || "0";

        selectedProducts[productId] = productId;
        productAmounts[productId] = amount;
        productPSNs[productId] = p_sn;
    });

    $('#edit_selected_product_ids').val(Object.keys(selectedProducts).join(','));
    $('#edit_product_amounts').val(Object.values(productAmounts).join(','));
    $('#edit_selected_p_sn').val(Object.values(productPSNs).join(','));
}

// Function to handle print
function handlePrint() {
    var printContents = document.getElementById('printableArea').innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Record Details</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; }
                .table { font-size: 12px; }
                h4 { text-align: center; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h4>Record Details</h4>
            ${printContents}
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
}

// Function to update stats
function updateStats() {
    $.ajax({
        url: 'get_stats.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#totalEmployees').text(data.employees || 0);
            $('#totalRecords').text(data.records || 0);
            $('#totalDivisions').text(data.divisions || 0);
            $('#totalVendors').text(data.vendors || 0);
            $('#recordCounter').text(data.records || 0);
        },
        error: function() {
            console.error('Error updating stats');
        }
    });
}
</script>

</body>
</html>