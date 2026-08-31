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
<title>BCIC Employees | Vibrant Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Bengali Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Noto Sans Bengali', 'Segoe UI', 'Roboto', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .dashboard-wrapper {
        max-width: 1600px;
        margin: 0 auto;
    }

    .main-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 28px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(2px);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .admin-header {
        background: linear-gradient(120deg, #1e3c72 0%, #2a5298 100%);
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        border-bottom: 4px solid #ffd966;
    }

    .admin-header h2 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 1.8rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        letter-spacing: -0.5px;
    }

    .admin-header h2 i {
        color: #ffd966;
        margin-right: 12px;
    }

    .header-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-solid {
        border-radius: 40px;
        padding: 8px 24px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn-solid-primary {
        background: #ffd966;
        color: #1e3c72;
    }

    .btn-solid-primary:hover {
        background: #ffcd38;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        color: #0a2a4a;
    }

    .btn-solid-outline {
        background: transparent;
        border: 2px solid #ffd966;
        color: #ffd966;
    }

    .btn-solid-outline:hover {
        background: #ffd966;
        color: #1e3c72;
        transform: translateY(-2px);
    }

    .btn-solid-danger {
        background: #dc3545;
        color: white;
    }

    .btn-solid-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    .filter-section {
        background: #f8f9fc;
        padding: 1.5rem 2rem;
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        border-bottom: 1px solid #e9ecef;
    }

    .filter-item {
        flex: 1;
        min-width: 180px;
    }

    .filter-item label {
        font-weight: 700;
        color: #2c3e66;
        margin-bottom: 8px;
        display: block;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-item select {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 10px 16px;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }

    .filter-item select:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42,82,152,0.2);
    }

    .table-container {
        padding: 0 1.5rem 1.5rem 1.5rem;
        background: white;
    }

    table.dataTable {
        border-radius: 20px;
        overflow: hidden;
        margin-top: 0 !important;
    }

    table.dataTable thead th {
        background: #2d3748;
        color: white;
        font-weight: 600;
        padding: 15px 12px;
        border-bottom: none;
        font-size: 0.9rem;
    }

    table.dataTable tbody tr:hover {
        background-color: #fef9e6;
        cursor: pointer;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-active {
        background: #10b981;
        color: white;
    }

    .badge-inactive {
        background: #ef4444;
        color: white;
    }

    .action-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-btn {
        border-radius: 30px;
        padding: 5px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .modal-content {
        border: none;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 30px 50px rgba(0,0,0,0.3);
    }

    .modal-header {
        background: linear-gradient(120deg, #1e3c72, #2a5298);
        color: white;
        border: none;
        padding: 1.2rem 1.8rem;
    }

    .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-body {
        padding: 2rem;
        background: #ffffff;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        background: #f1f5f9;
        border-top: 1px solid #e2e8f0;
        padding: 1.2rem 1.8rem;
    }

    .form-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        font-size: 0.85rem;
    }

    .required-field::after {
        content: "*";
        color: #ef4444;
        margin-left: 4px;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 10px 16px;
        transition: all 0.2s;
        font-size: 0.95rem;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42,82,152,0.2);
    }

    .image-preview {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #ffd966;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-modal-primary {
        background: #2a5298;
        border: none;
        border-radius: 40px;
        padding: 10px 24px;
        font-weight: 600;
        color: white;
    }

    .btn-modal-primary:hover {
        background: #1e3c72;
        color: white;
    }

    .btn-modal-secondary {
        background: #e2e8f0;
        color: #334155;
        border: none;
        border-radius: 40px;
        padding: 10px 24px;
        font-weight: 600;
    }

    .btn-modal-secondary:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    /* Status dropdown in action buttons */
    .status-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .status-dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 160px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .status-dropdown-content button {
        width: 100%;
        text-align: left;
        padding: 8px 16px;
        border: none;
        background: white;
        cursor: pointer;
    }
    
    .status-dropdown-content button:hover {
        background-color: #f1f5f9;
    }
    
    .status-dropdown:hover .status-dropdown-content {
        display: block;
    }

    @media (max-width: 768px) {
        body {
            padding: 12px;
        }
        .admin-header h2 {
            font-size: 1.2rem;
        }
        .modal-body {
            padding: 1.2rem;
        }
        .action-group {
            flex-direction: column;
        }
        table.dataTable thead th {
            font-size: 0.7rem;
            padding: 10px 6px;
        }
    }
    
    .modal-dialog {
        max-width: 900px;
        margin: 1.75rem auto;
    }
    
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
    }
</style>
</head>
<body>
<div class="dashboard-wrapper">
  <div class="main-card">
    
    <div class="admin-header">
      <h2>
        <i class="fa fa-users"></i> 
        BCIC EMPLOYEES • ADMIN DASHBOARD
      </h2>
      <div class="header-buttons">
        <a href="logout.php" class="btn btn-solid-danger btn-solid">
          <i class="fa fa-sign-out me-2"></i> Exit
        </a>
        <a href="../index.php" class="btn btn-solid-outline" target="_blank">
          <i class="fa fa-eye me-2"></i> Public View
        </a>
        <button class="btn btn-solid-primary" data-bs-toggle="modal" data-bs-target="#addModal">
          <i class="fa fa-plus-circle me-2"></i> Add Employee
        </button>
      </div>
    </div>

    <div class="filter-section">
      <div class="filter-item">
        <label><i class="fa fa-building me-1"></i> DEPARTMENT</label>
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
        <label><i class="fa fa-toggle-on me-1"></i> STATUS</label>
        <select id="statusFilter" class="form-select">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="filter-item">
        <label><i class="fa fa-check-circle me-1"></i> SYSTEM STATUS</label>
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

    <div class="table-container">
      <table id="employeesTable" class="display table table-hover" style="width:100%">
        <thead>
          32
            <th>ID</th>
            <th>EMP ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Division</th>
            <th>Department</th>
            <th>Mobile</th>
            <th>Office Phone</th>
            <th>Email</th>
            <th>Intercom</th>
            <th>Blood Group</th>
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

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <form id="addForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i>➕ Add New Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">EMP ID</label>
              <input type="text" name="emp_id" required class="form-control" placeholder="e.g., BCIC-101">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Full Name</label>
              <input type="text" name="name" required class="form-control" placeholder="Enter full name">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Designation</label>
              <input type="text" name="designation" required class="form-control" placeholder="Job title">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Division / Office</label>
              <input list="divisions" name="division" class="form-control" required placeholder="Select division">
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
            <div class="col-md-6 mb-3">
              <label class="form-label">Department</label>
              <input list="departments" name="department" class="form-control" placeholder="Select department">
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
            <div class="col-md-6 mb-3">
              <label class="form-label">Office Phone</label>
              <input type="tel" name="phone_office" class="form-control" placeholder="Office number">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">PABX / Intercom</label>
              <input type="text" name="intercom" class="form-control" placeholder="Intercom no">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Mobile Number</label>
              <input type="tel" name="mobile" class="form-control" required maxlength="11" placeholder="01XXXXXXXXX">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="example@bcic.gov.bd">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Fax</label>
              <input type="text" name="fax" class="form-control" placeholder="Fax number">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Blood Group</label>
              <select name="blood_group" class="form-select">
                <option value="">Select Blood Group</option>
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
            <div class="col-md-6 mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">System Status</label>
              <select name="system_status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="resignation">Resignation</option>
                <option value="dismissed">Dismissed</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Profile Image (Max 2MB)</label>
              <input type="file" name="image" accept="image/*" class="form-control">
              <small class="text-muted">JPG, PNG, GIF only</small>
            </div>
            <div class="col-md-6 mb-3">
            <label class="form-label">Address</label>
              <textarea autocomplete="on" name="address" class="form-control" rows="2" placeholder="Full residential address"></textarea>
          </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-modal-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-modal-primary"><i class="fa fa-save"></i> Save Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-pencil-square-o me-2"></i>✏️ Edit Employee Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">EMP ID</label>
              <input type="text" name="emp_id" id="edit_emp_id" required class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Name</label>
              <input type="text" name="name" id="edit_name" required class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Designation</label>
              <input type="text" name="designation" id="edit_designation" required class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Division/Office</label>
              <input type="text" name="division" id="edit_division" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Department</label>
              <input type="text" name="department" id="edit_department" class="form-control">
            </div>                        
            <div class="col-md-6 mb-3">
              <label class="form-label">Office Phone</label>
              <input type="tel" name="phone_office" id="edit_phone_office" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">PABX/Intercom</label>
              <input type="text" name="intercom" id="edit_intercom" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required-field">Mobile</label>
              <input type="tel" name="mobile" id="edit_mobile" required class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="edit_email" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Fax</label>
              <input type="text" name="fax" id="edit_fax" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Blood Group</label>
              <select name="blood_group" class="form-select" id="edit_blood_group">
                <option value="">Select Blood Group</option>
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
            <div class="col-md-6 mb-3">
              <label class="form-label">Status</label>
              <select name="status" id="edit_status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">System Status</label>
              <select name="system_status" class="form-select" id="edit_system_status" required>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="dismissed">Dismissed</option>
                <option value="resignation">Resignation</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Address</label>
              <textarea autocomplete="on" name="address" class="form-control" id="edit_address" rows="2" placeholder="Full residential address"></textarea>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Profile Image</label>
              <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
              <div class="mt-3">
                <img id="edit_preview" src="" alt="Preview" class="image-preview" style="display: none;">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-modal-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-modal-primary"><i class="fa fa-refresh"></i> Update Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- System Status Change Modal -->
<div class="modal fade" id="systemStatusModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-exchange me-2"></i> Change System Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="status_change_id">
        <div class="mb-3">
          <label class="form-label">Select New System Status</label>
          <select id="new_system_status" class="form-select">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="resignation">Resignation</option>
            <option value="dismissed">Dismissed</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-modal-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-modal-primary" id="updateSystemStatusBtn">Update Status</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function(){
    // DataTable initialization
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
            error: function(){ 
                $('#employeesTable tbody').html('<tr><td colspan="14" class="text-center text-danger">⚠️ Failed to load data. Refresh?</td></tr>');
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
                data: 'blood_group',
                render: function(data){
                    return data && data !== '' ? '<span class="badge bg-info">' + data + '</span>' : '<span class="text-muted">Not Set</span>';
                }
            },
            { 
                data: 'status',
                render: function(data){
                    return data === 'active' ? '<span class="badge badge-active">✅ Active</span>' : '<span class="badge badge-inactive">❌ Inactive</span>';
                }
            },
            { 
                data: 'system_status',
                render: function(data){
                    let color = 'secondary';
                    let icon = '⚪';
                    if(data === 'approved') {
                        color = 'success';
                        icon = '✅';
                    } else if(data === 'pending') {
                        color = 'warning';
                        icon = '⏳';
                    } else if(data === 'rejected') {
                        color = 'danger';
                        icon = '❌';
                    } else if(data === 'dismissed') {
                        color = 'dark';
                        icon = '🚫';
                    } else if(data === 'resignation') {
                        color = 'secondary';
                        icon = '📝';
                    }
                    return `<span class="badge bg-${color} text-white">${icon} ${data.toUpperCase()}</span>`;
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <div class="action-group">
                            <button class="btn btn-sm btn-outline-primary action-btn editBtn" data-id="${row.id}" title="Edit"><i class="fa fa-edit"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-${row.status === 'active' ? 'danger' : 'success'} action-btn statusBtn" data-id="${row.id}" data-status="${row.status}"><i class="fa fa-exchange"></i> ${row.status === 'active' ? 'Deactivate' : 'Activate'}</button>
                            <button class="btn btn-sm btn-outline-info action-btn systemStatusBtn" data-id="${row.id}" data-system-status="${row.system_status}"><i class="fa fa-tag"></i> Change Status</button>
                            <a href="delete_employee.php?id=${row.id}" class="btn btn-sm btn-outline-danger action-btn" onclick="return confirm('Permanently delete?')"><i class="fa fa-trash"></i> Del</a>
                        </div>
                    `;
                }
            }
        ],
        pageLength: 10,
        language: { search: "🔍 Search:", info: "Showing _START_ to _END_ of _TOTAL_ entries" },
        order: [[0, 'desc']]
    });

    // Filters
    $('#departmentFilter, #statusFilter, #systemStatusFilter').on('change', function(){ table.ajax.reload(); });

    // Load edit data
    $('#employeesTable').on('click', '.editBtn', function(){
        var id = $(this).data('id');
        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
        
        $.get('get_employee.php', {id: id}, function(resp){
            btn.html('<i class="fa fa-edit"></i> Edit').prop('disabled', false);
            if(resp && resp.id){
                $('#edit_id').val(resp.id);
                $('#edit_emp_id').val(resp.emp_id);
                $('#edit_name').val(resp.name);
                $('#edit_designation').val(resp.designation);
                $('#edit_department').val(resp.department);
                $('#edit_division').val(resp.division);
                
                if(resp.blood_group) {
                    $('#edit_blood_group').val(resp.blood_group);
                } else {
                    $('#edit_blood_group').val('');
                }
                
                $('#edit_phone_office').val(resp.phone_office);
                $('#edit_intercom').val(resp.intercom);
                $('#edit_mobile').val(resp.mobile);
                $('#edit_email').val(resp.email);
                $('#edit_fax').val(resp.fax);
                $('#edit_address').val(resp.address);
                $('#edit_status').val(resp.status);
                $('#edit_system_status').val(resp.system_status);
                
                if(resp.image){
                    $('#edit_preview').attr('src', resp.image).show();
                } else { 
                    $('#edit_preview').hide(); 
                }
                new bootstrap.Modal(document.getElementById('editModal')).show();
            } else { 
                alert('Employee not found!'); 
            }
        }, 'json').fail(function(){
            btn.html('<i class="fa fa-edit"></i> Edit').prop('disabled', false);
            alert('Error loading employee details');
        });
    });

    // Status toggle (Active/Inactive)
    $('#employeesTable').on('click', '.statusBtn', function(){
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        var btn = $(this);
        
        if(confirm(`Change status to ${newStatus.toUpperCase()}?`)){
            btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            $.post('toggle_status.php', {id: id, status: newStatus}, function(res){ 
                alert(res); 
                table.ajax.reload();
                btn.html('<i class="fa fa-exchange"></i> ' + (newStatus === 'active' ? 'Activate' : 'Deactivate')).prop('disabled', false);
            }).fail(function(){
                alert('Error toggling status');
                btn.html('<i class="fa fa-exchange"></i> ' + (currentStatus === 'active' ? 'Deactivate' : 'Activate')).prop('disabled', false);
            });
        }
    });

    // Open System Status Change Modal
    $('#employeesTable').on('click', '.systemStatusBtn', function(){
        var id = $(this).data('id');
        var currentStatus = $(this).data('system-status');
        $('#status_change_id').val(id);
        $('#new_system_status').val(currentStatus);
        new bootstrap.Modal(document.getElementById('systemStatusModal')).show();
    });

    // Update System Status (Approved, Rejected, Pending, Resignation, Dismissed)
    $('#updateSystemStatusBtn').on('click', function(){
        var id = $('#status_change_id').val();
        var newStatus = $('#new_system_status').val();
        var btn = $(this);
        
        if(confirm(`Change system status to ${newStatus.toUpperCase()}?`)){
            btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.post('update_system_status.php', {id: id, system_status: newStatus}, function(res){ 
                alert(res); 
                table.ajax.reload();
                $('#systemStatusModal').modal('hide');
                btn.html('Update Status').prop('disabled', false);
            }).fail(function(){
                alert('Error updating system status');
                btn.html('Update Status').prop('disabled', false);
            });
        }
    });

    // ADD FORM submit
    $('#addForm').on('submit', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        
        $.ajax({
            url: 'save_employee.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){ 
                alert(res); 
                $('#addModal').modal('hide'); 
                $('#addForm')[0].reset(); 
                table.ajax.reload();
                submitBtn.html(originalText).prop('disabled', false);
            },
            error: function(){ 
                alert('Error saving employee');
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // EDIT FORM submit
    $('#editForm').on('submit', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
        
        $.ajax({
            url: 'update_employee.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){ 
                alert(res); 
                $('#editModal').modal('hide'); 
                table.ajax.reload();
                submitBtn.html(originalText).prop('disabled', false);
            },
            error: function(){ 
                alert('Update failed');
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Image preview for edit
    $('#edit_image').on('change', function(){
        const file = this.files[0];
        if(file && file.type.match('image.*')){
            if(file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            $('#edit_preview').attr('src', URL.createObjectURL(file)).show();
        }
    });
    
    // Image preview for add
    $('input[name="image"]').on('change', function(){
        const file = this.files[0];
        if(file && file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
        }
    });
});
</script>
</body>
</html>