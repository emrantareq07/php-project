<?php
session_name('bcic_tel_db');
if(session_status() === PHP_SESSION_NONE) session_start();
require '../db/db.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<title>BCIC Employees - Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Bengali Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">

<style>
    /* Bengali Font Stack */
    .bn {
        font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', 'Kalpurush', 'Arial', sans-serif;
        line-height: 1.6;
    }
    
    /* English Font Stack */
    .en {
        font-family: 'Arial', 'Helvetica', sans-serif;
    }
    
    /* Apply to all elements by default */
    body {
        font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', 'Arial', sans-serif;
        background-color: #f8f9fa;
    }
    
    /* Header specific styles */
    .header-bangla {
        font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', sans-serif;
        font-weight: 600;
    }
    
    .header-english {
        font-family: 'Arial', 'Helvetica', sans-serif;
        font-weight: 500;
    }
    
    /* Navigation styles */
    .nav-bangla {
        font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', sans-serif;
        font-size: 1.1rem;
    }
    
    * {
        font-family: 'Open Sans', sans-serif;
        font-family: 'Tiro Bangla', serif;
        font-family: 'Noto Sans Bengali', sans-serif;
        font-family: 'Nikosh', sans-serif;
        font-family: 'Nikosh', serif;
    }
    
    .required-field::after { 
        content: "*"; 
        color: red; 
        margin-left: 4px; 
    }
    
    table.dataTable tbody tr { 
        cursor: default; 
    }
    
    .modal-lg { 
        max-width: 900px; 
    }
    
    /* ===== Responsive Styles ===== */
    
    /* Header Section */
    .admin-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .admin-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    
    .admin-header h2 {
        font-size: 1.5rem;
        margin-bottom: 0;
        color: #6c757d;
    }
    
    @media (min-width: 768px) {
        .admin-header h2 {
            font-size: 1.75rem;
        }
    }
    
    .header-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }
    
    @media (min-width: 576px) {
        .header-buttons {
            flex-direction: row;
            width: auto;
        }
    }
    
    .header-buttons .btn {
        width: 100%;
        white-space: nowrap;
    }
    
    @media (min-width: 576px) {
        .header-buttons .btn {
            width: auto;
        }
    }
    
    /* Filter Section */
    .filter-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .filter-section {
            flex-direction: row;
            gap: 1.5rem;
        }
    }
    
    .filter-item {
        width: 100%;
    }
    
    @media (min-width: 768px) {
        .filter-item {
            width: auto;
            min-width: 200px;
        }
    }
    
    .filter-item label {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    /* Table Responsive */
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1rem;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    /* DataTables Customization */
    .dataTables_wrapper {
        padding: 1rem;
        background: white;
        border-radius: 8px;
    }
    
    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    @media (max-width: 767px) {
        .dataTables_length,
        .dataTables_filter {
            text-align: left;
            width: 100%;
        }
        
        .dataTables_filter input {
            width: 100% !important;
            margin-left: 0 !important;
        }
        
        .dataTables_length select {
            width: auto;
            display: inline-block;
        }
    }
    
    /* Table Styles for Mobile */
    table.dataTable {
        width: 100% !important;
        border-collapse: collapse;
    }
    
    table.dataTable thead th {
        white-space: nowrap;
        padding: 12px 8px;
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    table.dataTable tbody td {
        padding: 10px 8px;
        vertical-align: middle;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
        justify-content: flex-start;
    }
    
    @media (max-width: 767px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        min-height: 38px;
    }
    
    @media (min-width: 768px) {
        .btn-sm {
            min-height: auto;
        }
    }
    
    /* Badge Styles */
    .badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }
    
    @media (max-width: 767px) {
        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
    }
    
    /* Modal Responsive */
    .modal-dialog {
        margin: 0.5rem;
    }
    
    @media (min-width: 576px) {
        .modal-dialog {
            margin: 1.75rem auto;
        }
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }
    
    @media (min-width: 768px) {
        .modal-header {
            padding: 1.5rem;
        }
    }
    
    .modal-body {
        padding: 1rem;
        max-height: 70vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    @media (min-width: 768px) {
        .modal-body {
            padding: 1.5rem;
        }
    }
    
    .modal-footer {
        padding: 1rem;
        border-top: 1px solid #dee2e6;
        background-color: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }
    
    @media (max-width: 767px) {
        .modal-footer {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .modal-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
    
    /* Form Responsive */
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.3rem;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 0.6rem 0.75rem;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        min-height: 44px; /* Better touch target */
    }
    
    @media (min-width: 768px) {
        .form-control, .form-select {
            min-height: auto;
        }
    }
    
    /* Prevent zoom on iOS */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        select, textarea, input {
            font-size: 16px !important;
        }
    }
    
    /* Image Preview */
    .image-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #dee2e6;
    }
    
    @media (min-width: 768px) {
        .image-preview {
            width: 80px;
            height: 80px;
        }
    }
    
    /* Grid System for Forms */
    .form-row {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    @media (min-width: 768px) {
        .form-row {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .form-row > div {
            flex: 1 1 calc(50% - 1.5rem);
        }
    }
    
    /* Loading States */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Touch Optimization */
    .btn, 
    .form-control,
    .form-select,
    .dropdown-item,
    .nav-link {
        -webkit-tap-highlight-color: transparent;
    }
    
    .btn:active {
        opacity: 0.8;
    }
    
    /* DataTables Pagination Responsive */
    .dataTables_paginate {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.25rem;
        margin-top: 1rem;
    }
    
    .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        margin: 0;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        min-width: 40px;
        text-align: center;
    }
    
    @media (max-width: 767px) {
        .dataTables_paginate .paginate_button {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    /* Info Text */
    .dataTables_info {
        text-align: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    @media (min-width: 768px) {
        .dataTables_info {
            text-align: left;
            margin-bottom: 0;
        }
    }
    
    /* Container Padding */
    .container-fluid {
        padding: 1rem;
    }
    
    @media (min-width: 768px) {
        .container-fluid {
            padding: 1.5rem;
        }
    }
    
    /* Card Shadow */
    .main-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 1rem;
    }
    
    @media (min-width: 768px) {
        .main-card {
            padding: 1.5rem;
        }
    }
    
    /* DataTable Column Visibility on Mobile */
    @media (max-width: 767px) {
        /* Hide less important columns on mobile */
        table.dataTable th:nth-child(5),  /* Division */
        table.dataTable td:nth-child(5),
        table.dataTable th:nth-child(8),  /* Office Phone */
        table.dataTable td:nth-child(8),
        table.dataTable th:nth-child(9),  /* Email */
        table.dataTable td:nth-child(9),
        table.dataTable th:nth-child(10), /* Intercom */
        table.dataTable td:nth-child(10) {
            display: none;
        }
    }
    
    @media (max-width: 575px) {
        /* Hide additional columns on very small screens */
        table.dataTable th:nth-child(4),  /* Designation */
        table.dataTable td:nth-child(4),
        table.dataTable th:nth-child(6),  /* Department */
        table.dataTable td:nth-child(6) {
            display: none;
        }
    }
    
    /* Custom scrollbar for better mobile experience */
    .modal-body::-webkit-scrollbar {
        width: 4px;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 4px;
    }
    
    /* Landscape optimization */
    @media (max-height: 600px) and (orientation: landscape) {
        .modal-body {
            max-height: 50vh;
        }
        
        .admin-header h2 {
            font-size: 1.25rem;
        }
        
        .header-buttons .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
    }
    
    /* High contrast mode */
    @media (prefers-contrast: high) {
        .badge {
            border: 1px solid currentColor;
        }
        
        .form-control, .form-select {
            border: 2px solid #000;
        }
    }
    
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation: none !important;
            transition: none !important;
        }
    }
</style>
</head>
<body>
<div class="container-fluid py-2">
  <div class="main-card">
    
    <!-- Admin Header -->
    <div class="admin-header">
      <h2 class="text-muted">
        <i class="fa fa-dashboard me-2"></i>
        BCIC Employees - Admin Panel
      </h2>
      <div class="header-buttons">
        <a href="logout.php" class="btn btn-outline-danger">
          <i class="fa fa-sign-out me-1"></i> 
          <span class="d-none d-sm-inline">Logout</span>
          <span class="d-sm-none">Exit</span>
        </a>
        <a href="../index.php" class="btn btn-outline-info" target="_blank">
          <i class="fa fa-eye me-1"></i> 
          <span class="d-none d-sm-inline">Public View</span>
          <span class="d-sm-none">Public</span>
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
          <i class="fa fa-plus me-1"></i> 
          <span class="d-none d-sm-inline">Add Employee</span>
          <span class="d-sm-none">Add</span>
        </button>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-item">
        <label for="departmentFilter" class="form-label fw-bold">
          <i class="fa fa-sitemap me-1"></i>Department:
        </label>
        <select id="departmentFilter" class="form-select">
          <option value="">All Departments</option>
          <option value="Administration">Administration</option>
          <option value="Commerce">Commerce</option>
          <option value="Finance">Finance</option>
          <option value="Technical">Technical</option>
          <option value="Medical">Medical</option>
        </select>
      </div>
      <div class="filter-item">
        <label for="statusFilter" class="form-label fw-bold">
          <i class="fa fa-circle me-1"></i>Status:
        </label>
        <select id="statusFilter" class="form-select">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="filter-item">
        <label for="systemStatusFilter" class="form-label fw-bold">
          <i class="fa fa-check-circle me-1"></i>System Status:
        </label>
        <select id="systemStatusFilter" class="form-select">
          <option value="">All Status</option>
          <option value="approved">Approved</option>
          <option value="pending">Pending</option>
          <option value="rejected">Rejected</option>
          <option value="resignation">Resignation</option>
          <option value="dismissed">Dismissed</option>
          
        </select>
      </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
      <table id="employeesTable" class="display table table-striped" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>EMP ID</th>
            <th>Name</th>
            <th class="desktop-col">Designation</th>
            <th class="desktop-col">Division/Office</th>
            <th class="desktop-col">Department</th>
            <th>Mobile</th>
            <th class="desktop-col">Office Phone</th>
            <th class="desktop-col">Email</th>
            <th class="desktop-col">PABX/Intercom</th>
            <th>Status</th>
            <th>System Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="addForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title text-uppercase text-muted">
            <i class="fa fa-user-plus text-success me-2"></i> Add Employee           
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label required-field">EMP ID</label>
              <input type="text" name="emp_id" required class="form-control" placeholder="Enter Employee ID">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Name</label>
              <input type="text" name="name" required class="form-control" placeholder="Enter Full Name">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Designation</label>
              <input type="text" name="designation" required class="form-control" placeholder="Enter Designation">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Division/Office</label>
              <input list="divisions" name="division" class="form-control" required placeholder="Select or type division">
              <datalist id="divisions">
                <?php
                    $sql = "SELECT * FROM division ORDER BY div_name ASC";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . htmlspecialchars($row['div_name']) . "'>";
                    }
                ?>
              </datalist>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Department</label>
              <input list="departments" name="department" class="form-control" placeholder="Select or type department">
              <datalist id="departments">
                <?php
                    $sql = "SELECT * FROM department ORDER BY name ASC";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . htmlspecialchars($row['name']) . "'>";
                    }
                ?>
              </datalist>
            </div>  
            <div class="col-12 col-md-6">
              <label class="form-label">Office Phone</label>
              <input type="tel" name="phone_office" class="form-control" inputmode="tel" placeholder="Enter office phone">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">PABX/Intercom</label>
              <input type="text" name="intercom" class="form-control" placeholder="Enter intercom number">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Mobile</label>
              <input type="tel" name="mobile" class="form-control" required inputmode="tel" 
                     pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                     maxlength="11" placeholder="01XXXXXXXXX">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Enter email address">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Fax</label>
              <input type="text" name="fax" class="form-control" placeholder="Enter fax number">
            </div>
            <!-- Personal Details -->
            <div class="col-12 col-md-6">
              <label class="form-label">Blood Group</label>
              <select name="blood_group" class="form-select">
                <option value="" selected disabled>Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Profile Image</label>
              <input type="file" name="image" accept="image/*" class="form-control">
              <small class="text-muted">Max file size: 2MB. Supported: JPG, PNG, GIF</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Close
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> Save Employee
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fa fa-edit text-warning me-2"></i> Edit Employee
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label required-field">EMP ID</label>
              <input type="text" name="emp_id" id="edit_emp_id" required class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Name</label>
              <input type="text" name="name" id="edit_name" required class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Designation</label>
              <input type="text" name="designation" id="edit_designation" required class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Division/Office</label>
              <input type="text" name="division" id="edit_division" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Department</label>
              <input type="text" name="department" id="edit_department" class="form-control">
            </div>                        
            <div class="col-12 col-md-6">
              <label class="form-label">Office Phone</label>
              <input type="tel" name="phone_office" id="edit_phone_office" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">PABX/Intercom</label>
              <input type="text" name="intercom" id="edit_intercom" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Mobile</label>
              <input type="tel" name="mobile" id="edit_mobile" required class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="edit_email" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Fax</label>
              <input type="text" name="fax" id="edit_fax" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Blood Group</label>
              <!-- <input type="text" name="blood_group" id="edit_blood_group" class="form-control"> -->
              <select name="blood_group" class="form-select" id="edit_blood_group">
                <option value="" selected disabled>Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Status</label>
              <select name="status" id="edit_status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">System Status</label>
              <select name="system_status" class="form-select" id="system_status" required>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="dismissed">Dismissed</option>
                <option value="resignation">Resignation</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Profile Image</label>
              <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
              <div class="mt-2">
                <img id="edit_preview" src="" alt="Preview" class="image-preview d-none">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Close
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-update me-1"></i> Update Employee
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function(){
    // Initialize DataTable with responsive options
    var table = $('#employeesTable').DataTable({
        ajax: {
            url: 'get_employees.php',
            type: 'POST',
            data: function(d){ 
                d.department = $('#departmentFilter').val(); 
                d.status = $('#statusFilter').val(); 
                d.system_status = $('#systemStatusFilter').val(); 
            },
            dataType: 'json',
            error: function(xhr){ 
                console.error(xhr.responseText); 
                // Show user-friendly error
                $('#employeesTable tbody').html('<tr><td colspan="13" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
            }
        },
        columns: [
            { data: 'id' },
            { data: 'emp_id' },
            { data: 'name' },
            { data: 'designation' },
            { data: 'division' },
            { data: 'department' },
            { data: 'mobile' },
            { data: 'phone_office' },
            { data: 'email' },
            { data: 'intercom' },
            { 
                data: 'status',
                render: function(data){
                    return data === 'active' ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                }
            },
            { 
                data: 'system_status',
                render: function(data){
                    if(data === 'approved') {
                        return '<span class="badge bg-success">Approved</span>';
                    } else if(data === 'pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    } else if(data === 'rejected') {
                        return '<span class="badge bg-danger">Rejected</span>';
                    }
                    else if(data === 'dismissed') {
                        return '<span class="badge bg-danger">Dismissed</span>';
                    }
                    else if(data === 'resignation') {
                        return '<span class="badge bg-danger">Resignation</span>';
                    }
                    return '<span class="badge bg-secondary">Unknown</span>';
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-outline-warning editBtn" data-id="${row.id}" title="Edit">
                                <i class="fa fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                            </button>
                            <button class="btn btn-sm btn-outline-${row.status === 'active' ? 'danger' : 'success'} statusBtn" 
                                    data-id="${row.id}" data-status="${row.status}" 
                                    title="${row.status === 'active' ? 'Deactivate' : 'Activate'}">
                                <i class="fa fa-${row.status === 'active' ? 'ban' : 'check'}"></i> 
                                <span class="d-none d-md-inline">${row.status === 'active' ? 'Deactivate' : 'Activate'}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-success approveBtn" data-id="${row.id}" title="Approve">
                                <i class="fa fa-check"></i> <span class="d-none d-md-inline">Approve</span>
                            </button>
                            <a href="delete_employee.php?id=${row.id}" class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Are you sure you want to delete this employee?')" title="Delete">
                                <i class="fa fa-trash"></i> <span class="d-none d-md-inline">Delete</span>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        pageLength: 10,
        responsive: false, // We handle responsiveness manually
        language: {
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ employees",
            infoEmpty: "No employees found",
            infoFiltered: "(filtered from _MAX_ total)",
            search: "Search:",
            zeroRecords: "No matching employees found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function() {
            // Add loading indicator
            $('#employeesTable').removeClass('no-data');
        }
    });

    // Filter handlers with debounce for better performance
    var filterTimeout;
    $('#departmentFilter, #statusFilter, #systemStatusFilter').on('change', function(){ 
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            table.ajax.reload();
        }, 300);
    });

    // Load data into edit modal
    $('#employeesTable').on('click', '.editBtn', function(){
        var id = $(this).data('id');
        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
        
        $.get('get_employee.php', {id: id}, function(resp){
            btn.html('<i class="fa fa-edit"></i>').prop('disabled', false);
            
            if(resp && resp.id){
                $('#edit_id').val(resp.id);
                $('#edit_emp_id').val(resp.emp_id);
                $('#edit_name').val(resp.name);
                $('#edit_designation').val(resp.designation);
                $('#edit_department').val(resp.department);
                $('#edit_division').val(resp.division);
                $('#edit_blood_group').val(resp.blood_group);
                $('#edit_phone_office').val(resp.phone_office);
                $('#edit_intercom').val(resp.intercom);
                $('#edit_mobile').val(resp.mobile);
                $('#edit_email').val(resp.email);
                $('#edit_fax').val(resp.fax);
                $('#edit_status').val(resp.status);
                $('#edit_system_status').val(resp.system_status);

                if(resp.image){
                    $('#edit_preview').attr('src', resp.image).removeClass('d-none');
                } else {
                    $('#edit_preview').addClass('d-none').attr('src', '');
                }

                new bootstrap.Modal(document.getElementById('editModal')).show();
            } else { 
                alert(resp.error || 'Employee not found'); 
            }
        }, 'json').fail(function() {
            btn.html('<i class="fa fa-edit"></i>').prop('disabled', false);
            alert('Error loading employee data. Please try again.');
        });
    });

    // Status toggle with loading state
    $('#employeesTable').on('click', '.statusBtn', function(){
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        var btn = $(this);
        
        if(confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this employee?`)) {
            btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.post('toggle_status.php', {id: id, status: newStatus}, function(res){ 
                alert(res); 
                table.ajax.reload(); 
                btn.html('<i class="fa fa-edit"></i>').prop('disabled', false);
            }).fail(function() {
                alert('Error toggling status. Please try again.');
                btn.html('<i class="fa fa-edit"></i>').prop('disabled', false);
            });
        }
    });

    // Preview new image before upload
    $('#edit_image').on('change', function(){
        const file = this.files[0];
        if(file){
            // Validate file size (2MB max)
            if(file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            
            // Validate file type
            if(!file.type.match('image.*')) {
                alert('Please select an image file');
                this.value = '';
                return;
            }
            
            $('#edit_preview').attr('src', URL.createObjectURL(file)).removeClass('d-none');
        }
    });

    // Add image preview for add form
    $('input[name="image"]').on('change', function(){
        const file = this.files[0];
        if(file){
            if(file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            
            if(!file.type.match('image.*')) {
                alert('Please select an image file');
                this.value = '';
                return;
            }
        }
    });

    // Unified add/edit form submit with loading states
    $('#addForm, #editForm').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
            url: form.attr('id') === 'addForm' ? 'save_employee.php' : 'update_employee.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                alert(res);
                table.ajax.reload();
                form[0].reset();
                
                // Reset preview
                if(form.attr('id') === 'editForm') {
                    $('#edit_preview').addClass('d-none').attr('src', '');
                }
                
                bootstrap.Modal.getInstance(form.closest('.modal')[0]).hide();
            },
            error: function(xhr){
                console.error(xhr.responseText);
                alert('Error while saving. Please check console and try again.');
            },
            complete: function() {
                // Restore button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Approve button with loading state
    $('#employeesTable').on('click', '.approveBtn', function(){
        var id = $(this).data('id');
        var btn = $(this);
        
        if(confirm('Approve this employee?')) {
            btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.post('approve.php', {id: id}, function(res){ 
                alert(res); 
                table.ajax.reload(); 
                btn.html('<i class="fa fa-check"></i>').prop('disabled', false);
            }).fail(function() {
                alert('Error approving employee. Please try again.');
                btn.html('<i class="fa fa-check"></i>').prop('disabled', false);
            });
        }
    });

    // Handle orientation change
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            table.columns.adjust().draw();
        }, 200);
    });

    // Handle window resize
    var resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            table.columns.adjust();
        }, 250);
    });

    // Prevent zoom on double tap for iOS
    document.addEventListener('touchstart', function(e) {
        if (e.touches.length > 1) {
            e.preventDefault();
        }
    }, { passive: false });

    // Add touch optimization
    if ('ontouchstart' in window) {
        $('.btn').on('touchstart', function() {
            $(this).css('opacity', '0.8');
        }).on('touchend', function() {
            $(this).css('opacity', '');
        });
    }

});
</script>
</body>
</html>