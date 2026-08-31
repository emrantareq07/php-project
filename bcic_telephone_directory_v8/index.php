<?php
require 'db/db.php';
?>
<?php include 'includes/header.php'; ?>

<style>
/* ===== SOLID COLOR SCHEME: GREEN, BLUE, WHITE ===== */
:root {
  --primary-green: #2e7d32;
  --primary-green-light: #4caf50;
  --primary-green-dark: #1b5e20;
  --primary-blue: #1976d2;
  --primary-blue-light: #42a5f5;
  --primary-blue-dark: #0d47a1;
  --pure-white: #ffffff;
  --off-white: #f8f9fa;
  --gray-light: #e9ecef;
  --gray-medium: #6c757d;
}

body {
  background: var(--pure-white);
  font-family: 'Segoe UI', 'Noto Sans Bengali', sans-serif;
}

/* Header Styling */
.header-section h2 {
  color: var(--primary-green);
  font-weight: 700;
  letter-spacing: -0.5px;
  border-left: 4px solid var(--primary-blue);
  padding-left: 15px;
}

/* Button Styles */
.btn-outline-primary {
  border-color: var(--primary-blue);
  color: var(--primary-blue);
  background: transparent;
  transition: all 0.3s ease;
}

.btn-outline-primary:hover {
  background: var(--primary-blue);
  border-color: var(--primary-blue);
  color: var(--pure-white);
}

.btn-success {
  background: var(--primary-green);
  border-color: var(--primary-green);
}

.btn-success:hover {
  background: var(--primary-green-dark);
  border-color: var(--primary-green-dark);
}

.btn-outline-success {
  border-color: var(--primary-green);
  color: var(--primary-green);
}

.btn-outline-success:hover {
  background: var(--primary-green);
  border-color: var(--primary-green);
}

/* Contact Card Styling */
.contact-card {
  transition: all 0.3s ease;
/*  border: none;*/
border-left: 5px solid #811ca3;
  border-radius: 16px;
  background: var(--pure-white);
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  margin-bottom: 1rem;
  cursor: default;
  border-bottom: 3px solid transparent;
}

/*.contact-card {
  transition: transform 0.18s, box-shadow 0.18s, background-color 1s;
  border-left: 4px solid #0d6efd;
  cursor: default;
}*/

.contact-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  border-bottom-color: var(--primary-blue);
}

/* Address Section Styling */
.address-section {
  background: var(--off-white);
  border-radius: 12px;
  padding: 0.75rem;
  margin-top: 0.75rem;
  border-left: 3px solid var(--primary-blue);
}

.address-section i {
  color: var(--primary-blue);
  width: 20px;
}

/* Contact Info Items */
.contact-info-item {
  background: var(--off-white);
  border-radius: 8px;
  padding: 0.5rem 0.75rem;
  margin-right: 0.75rem;
  margin-bottom: 0.5rem;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.contact-info-item i {
  color: var(--primary-blue);
  width: 20px;
}

.contact-info-item .text-muted {
  font-size: 0.7rem;
  text-transform: uppercase;
  font-weight: 600;
  color: var(--gray-medium) !important;
}

/* Department Tags */
.department-tag {
  background: linear-gradient(135deg, var(--primary-blue-light), var(--primary-blue));
  color: var(--pure-white);
  font-size: 0.75rem;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  display: inline-block;
  margin: 0.125rem;
  font-weight: 500;
}

/* Statistics Cards */
.stat-card {
  padding: 0.75rem 0.5rem;
  border-bottom: 1px solid var(--gray-light);
  background: var(--pure-white);
}

.stat-card small {
  color: var(--gray-medium);
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.7rem;
  letter-spacing: 0.5px;
}

.stat-card h5, .stat-card h6 {
  color: var(--primary-green);
  font-weight: 700;
}

/* Modal Styling - FIXED */
.modal-content {
  border: none;
  border-radius: 20px;
  overflow: hidden;
}

.modal-header {
  background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
  color: var(--pure-white);
  border: none;
  padding: 1.2rem 1.5rem;
}

.modal-header .modal-title {
  font-weight: 700;
  font-size: 1.25rem;
}

.modal-header .btn-close {
  filter: brightness(0) invert(1);
  opacity: 0.8;
}

.modal-body {
  padding: 1.5rem;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-footer {
  background: var(--off-white);
  border-top: 1px solid var(--gray-light);
  padding: 1rem 1.5rem;
}

/* Form Elements - IMPROVED */
.form-control, .form-select {
  border: 2px solid var(--gray-light);
  border-radius: 12px;
  padding: 0.6rem 1rem;
  transition: all 0.2s ease;
  width: 100%;
}

.form-control:focus, .form-select:focus {
  border-color: var(--primary-blue);
  box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
}

.form-label {
  font-weight: 600;
  color: var(--primary-green-dark);
  font-size: 0.85rem;
  margin-bottom: 0.4rem;
  display: block;
}

.required-field::after {
  content: "*";
  color: #dc3545;
  margin-left: 4px;
}

/* Badges */
.badge {
  padding: 0.35rem 0.75rem;
  border-radius: 20px;
  font-weight: 600;
}

.badge.bg-success {
  background: var(--primary-green) !important;
}

.badge.bg-info {
  background: var(--primary-blue) !important;
}

.badge.bg-warning {
  background: #ff9800 !important;
}

/* Employee Image */
.employee-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border: 3px solid var(--primary-blue);
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Pending Card Style */
.employee-pending {
  opacity: 0.85;
  background: #fff8e7 !important;
  border-left: 4px solid #ffc107 !important;
}

.employee-pending .btn {
  display: none !important;
}

/* Loading Spinner */
.spinner-border.text-primary {
  color: var(--primary-blue) !important;
}

/* Search Box */
.input-group-text {
  background: var(--pure-white);
  border: 2px solid var(--gray-light);
  border-right: none;
  color: var(--primary-blue);
}

#searchBox {
  border: 2px solid var(--gray-light);
  border-left: none;
  border-right: none;
}

#searchBox:focus {
  border-color: var(--primary-blue);
  box-shadow: none;
}

#clearSearchBtn {
  border: 2px solid var(--gray-light);
  border-left: none;
  background: var(--pure-white);
  color: var(--gray-medium);
}

#clearSearchBtn:hover {
  background: var(--gray-light);
  color: var(--primary-green-dark);
}

/* Responsive Styles */
@media (max-width: 767.98px) {
  .contact-info-item {
    display: flex;
    width: 100%;
    margin-right: 0;
  }
  
  .employee-image {
    width: 70px;
    height: 70px;
  }
  
  .address-section {
    margin-top: 0.5rem;
  }
  
  .stat-card {
    padding: 0.5rem;
  }
  
  .modal-dialog {
    margin: 0.5rem;
  }
  
  .modal-body {
    padding: 1rem;
  }
  
  .row.g-3 > [class*="col-"] {
    margin-bottom: 0.75rem;
  }
}

@media (max-width: 575.98px) {
  .contact-info-item {
    font-size: 0.85rem;
  }
  
  .department-tag {
    font-size: 0.7rem;
    padding: 0.2rem 0.6rem;
  }
  
  .employee-image {
    width: 60px;
    height: 60px;
  }
}

/* Touch Optimization */
@media screen and (-webkit-min-device-pixel-ratio: 0) {
  select, textarea, input {
    font-size: 16px !important;
  }
}

.modal-body {
  -webkit-overflow-scrolling: touch;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: var(--gray-light);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: var(--primary-blue);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--primary-green);
}

/* Form Row Improvements */
.form-row-spacing {
  margin-bottom: 1rem;
}

/* Ensure all fields are visible */
.modal-body .row {
  margin-left: 0;
  margin-right: 0;
}

.modal-body .col-12,
.modal-body .col-md-6,
.modal-body .col-md-4 {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}
</style>

<div class="container my-4">
  <!-- Header Section -->
  <div class="row mb-4 header-section">
    <div class="col-12 col-md-8">
      <h2 class="mb-3 mb-md-0 fw-bold righteous-regular">
        <i class="fa fa-phone-square text-primary me-2 "></i>
        BCIC Telephone Directory
      </h2>
      <p class="text-muted mt-2 mb-0">
        <i class="fa fa-map-marker me-1"></i> Complete employee contact information with addresses
      </p>
    </div>
    <div class="col-12 col-md-4 text-md-end">
      <button class="btn btn-outline-primary mb-2 mb-md-0 me-md-2 w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#publicModal">
        <i class="fa fa-plus-circle me-2"></i> Add Contact
      </button>
      <a href="includes/login.php" class="btn btn-outline-primary w-100 w-md-auto" target="_blank">
        <i class="fa fa-lock me-2"></i> Admin Login
      </a>
    </div>
  </div>
 
<!-- Modal with Contact Submission Form - FIXED LAYOUT -->
<div class="modal fade" id="publicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="publicForm" autocomplete="off" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fa fa-user-plus me-2"></i> Add Employee Contact
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
          <!-- Message Area -->
          <div id="publicMsgWrap" class="mb-3">
            <span id="publicMsg" class="text-center d-block"></span>
          </div>
          
          <div class="row g-3">
            <!-- Basic Information Section -->
            <div class="col-12">
              <h6 class="text-primary mb-2"><i class="fa fa-info-circle me-1"></i> Basic Information</h6>
              <hr class="mt-0 mb-3">
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label required-field">EMP ID</label>
              <input type="text" name="emp_id" class="form-control" required placeholder="Enter EMP ID">
              <div class="emp_id_feedback validation-feedback" style="display: none;"></div>
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Full Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Designation</label>
              <input list="designations" id="designation" name="designation" class="form-control" placeholder="Select or type designation" required>
              <datalist id="designations">
                <?php
                  $sql = "SELECT * FROM designation ORDER BY designation_type ASC";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . htmlspecialchars($row['designation_type']) . "'>";
                  }
                ?>
              </datalist>
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Division / Office</label>
              <input list="divisions" id="division" name="division" class="form-control" placeholder="Select or type division" required>
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
            
            <!-- Professional Information Section -->
            <div class="col-12 mt-2">
              <h6 class="text-primary mb-2"><i class="fa fa-briefcase me-1"></i> Professional Information</h6>
              <hr class="mt-0 mb-3">
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">Department</label>
              <input list="departments" id="department" name="department" class="form-control" placeholder="Select or type department">
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
              <input type="tel" name="phone_office" class="form-control" placeholder="0XXXXXXXXX">
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">PABX / Intercom</label>
              <input name="intercom" class="form-control" placeholder="e.g., 0501">
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">Fax</label>
              <input name="fax" class="form-control" placeholder="Fax number">
            </div>
            
            <!-- Contact Information Section -->
            <div class="col-12 mt-2">
              <h6 class="text-primary mb-2"><i class="fa fa-phone me-1"></i> Contact Information</h6>
              <hr class="mt-0 mb-3">
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Mobile Number</label>
              <input name="mobile" class="form-control" required inputmode="tel" 
                     pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                     maxlength="11" placeholder="01XXXXXXXXX">
              <div class="mobile_feedback validation-feedback" style="display: none;"></div>
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">Email Address</label>
              <input name="email" type="email" class="form-control" placeholder="example@bcic.gov.bd">
              <div class="email_feedback validation-feedback" style="display: none;"></div>
            </div>
            
            <!-- Personal Details Section -->
            <div class="col-12 mt-2">
              <h6 class="text-primary mb-2"><i class="fa fa-user-circle me-1"></i> Personal Details</h6>
              <hr class="mt-0 mb-3">
            </div>
            
            <div class="col-12 col-md-6">
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
            
            <div class="col-12 col-md-6">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control" rows="2" placeholder="Full residential address"></textarea>
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">Profile Image</label>
              <input type="file" name="image" class="form-control" accept="image/*">
              <small class="text-muted">Max 2MB. JPG, PNG, GIF only</small>
            </div>
            
            <div class="col-12 col-md-6">
              <div class="mt-2">
                <img id="image_preview" src="" alt="Preview" class="rounded-circle border employee-image" style="display: none;">
              </div>
            </div>
            
            <!-- Hidden field -->
            <input type="hidden" name="submitted_by" value="public">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-close me-2"></i> Close
          </button>
          <button type="submit" class="btn btn-success" id="submitBtn">
            <i class="fa fa-save me-2"></i> Submit for Approval
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Search and Filters -->
  <div class="row mb-4 search-filter-row">
    <div class="col-12 col-md-8">
      <div class="input-group">
        <span class="input-group-text bg-white"><i class="fa fa-search text-primary"></i></span>
        <input type="text" id="searchBox" autofocus class="form-control" placeholder="Search by name, emp_id, designation, department, mobile, email...">
        <button class="btn btn-outline-secondary" id="clearSearchBtn" type="button">
          <i class="fa fa-times"></i>
        </button>
      </div>
    </div>
    <div class="col-12 col-md-4 mt-2 mt-md-0">
      <select id="departmentFilter" class="form-select">
        <option value="">All Departments</option>
        <option value="Administration">Administration</option>
        <option value="Commerce">Commerce</option>
        <option value="Finance">Finance</option>
        <option value="Technical">Technical</option>
        <option value="Medical">Medical</option>
        <option value="Production">Production</option>
      </select>
    </div>
  </div>

  <!-- Employee Statistics -->
<!--   <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-body py-2">
          <div class="stats-container">
            <div class="col-6 col-md-2 stat-card">
              <small class="text-muted d-block"><i class="fa fa-users me-1"></i>Total Employees</small>
              <h5 class="mb-0" id="totalEmployees">0</h5>
            </div>
            <div class="col-6 col-md-2 stat-card">
              <small class="text-muted d-block"><i class="fa fa-check-circle me-1"></i>Active</small>
              <h5 class="mb-0 text-success" id="activeEmployees">0</h5>
            </div>
            <div class="col-6 col-md-2 stat-card">
              <small class="text-muted d-block"><i class="fa fa-clock-o me-1"></i>Pending</small>
              <h5 class="mb-0 text-warning" id="pending_employees">0</h5>
            </div>
            <div class="col-6 col-md-3 stat-card">
              <small class="text-muted d-block"><i class="fa fa-building me-1"></i>Departments</small>
              <h5 class="mb-0" id="totalDepartments">0</h5>
            </div>
            <div class="col-12 col-md-3 stat-card">
              <small class="text-muted d-block"><i class="fa fa-calendar me-1"></i>Last Updated</small>
              <h6 class="mb-0 text-muted" id="lastUpdated">-</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
  <!-- Employee Statistics - INLINE STYLING -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-around align-items-center">
          <div class="stat-item text-center px-3 py-2">
            <small class="text-muted d-block"><i class="fa fa-users me-1"></i>Total Employees</small>
            <h5 class="mb-0 fw-bold" id="totalEmployees">0</h5>
          </div>
          <div class="stat-item text-center px-3 py-2 border-start border-end">
            <small class="text-muted d-block"><i class="fa fa-check-circle me-1"></i>Active</small>
            <h5 class="mb-0 fw-bold text-success" id="activeEmployees">0</h5>
          </div>
          <div class="stat-item text-center px-3 py-2">
            <small class="text-muted d-block"><i class="fa fa-clock-o me-1"></i>Pending</small>
            <h5 class="mb-0 fw-bold text-warning" id="pending_employees">0</h5>
          </div>
          <div class="stat-item text-center px-3 py-2 border-start border-end">
            <small class="text-muted d-block"><i class="fa fa-building me-1"></i>Departments</small>
            <h5 class="mb-0 fw-bold" id="totalDepartments">0</h5>
          </div>
          <div class="stat-item text-center px-3 py-2">
            <small class="text-muted d-block"><i class="fa fa-calendar me-1"></i>Last Updated</small>
            <h6 class="mb-0 fw-bold text-muted" id="lastUpdated">-</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Statistics Inline Styling */
.stat-item {
  flex: 1;
  min-width: 120px;
  transition: all 0.2s ease;
}

.stat-item:hover {
  transform: translateY(-2px);
}

.stat-item small {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 600;
}

.stat-item h5, .stat-item h6 {
  font-size: 1.25rem;
  margin-top: 0.25rem;
}

/* Responsive Design */
@media (max-width: 992px) {
  .stat-item {
    min-width: 100px;
  }
  
  .stat-item h5, .stat-item h6 {
    font-size: 1rem;
  }
  
  .stat-item small {
    font-size: 0.65rem;
  }
}

@media (max-width: 768px) {
  .d-flex.flex-wrap {
    flex-direction: row !important;
    gap: 0.5rem;
  }
  
  .stat-item {
    min-width: calc(50% - 1rem);
    padding: 0.5rem !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #e9ecef;
  }
  
  .stat-item:last-child {
    border-bottom: none;
  }
  
  .stat-item h5, .stat-item h6 {
    font-size: 1.1rem;
  }
  
  .border-start, .border-end {
    border-left: none !important;
    border-right: none !important;
  }
}

@media (max-width: 576px) {
  .stat-item {
    min-width: 100%;
    text-align: center;
    padding: 0.75rem !important;
  }
  
  .stat-item small {
    font-size: 0.7rem;
  }
  
  .stat-item h5, .stat-item h6 {
    font-size: 1rem;
  }
}

/* Desktop inline borders */
@media (min-width: 769px) {
  .border-start {
    border-left: 1px solid #e9ecef !important;
  }
  
  .border-end {
    border-right: 1px solid #e9ecef !important;
  }
}

/* Hover effect */
.stat-item:hover small {
  color: var(--primary-blue) !important;
}

.stat-item:hover h5, .stat-item:hover h6 {
  color: var(--primary-green) !important;
}
</style>

  <!-- Contacts List -->
  <div id="contactsList" class="list-group mb-2"></div>
  
  <!-- Loading Spinner -->
  <div class="text-center py-3" id="loadingSpinner" aria-hidden="true">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-2 small text-muted">Loading employees...</div>
  </div>
  
  <!-- Load More Trigger -->
  <div id="loadMoreTrigger" style="height:1px;"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

  /* ======= Elements ======= */
  const contactsList = document.getElementById('contactsList');
  const spinner = document.getElementById('loadingSpinner');
  const searchBox = document.getElementById('searchBox');
  const clearBtn = document.getElementById('clearSearchBtn');
  const departmentFilter = document.getElementById('departmentFilter');
  const loadMoreTrigger = document.getElementById('loadMoreTrigger');
  const totalEmployees = document.getElementById('totalEmployees');
  const activeEmployees = document.getElementById('activeEmployees');
  const pending_employees = document.getElementById('pending_employees');
  const totalDepartments = document.getElementById('totalDepartments');
  const lastUpdated = document.getElementById('lastUpdated');

  const publicForm = document.getElementById('publicForm');
  const publicMsg = document.getElementById('publicMsg');
  const modalEl = document.getElementById('publicModal');
  const imagePreview = document.getElementById('image_preview');
  const submitBtn = publicForm ? publicForm.querySelector('button[type="submit"]') : null;

  let offset = 0, limit = 12, loading = false, allLoaded = false;
  let searchQuery = "", selectedDepartment = "";

  const loadedEmployeeIds = new Set();

  /* ======= Helper Functions ======= */
  const debounce = (fn, delay=300) => {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  };

  const showMsg = (type, text) => {
    if(!publicMsg) return;
    publicMsg.className = 'fade-out';
    publicMsg.innerHTML = `<div class="alert alert-${type} mb-0">${text}</div>`;
    setTimeout(()=>publicMsg.classList.add('hide'),3000);
    setTimeout(()=>{publicMsg.innerHTML=''; publicMsg.className='';},4000);
  };

  /* ======= Image Preview ======= */
  if(publicForm){
    const imageInput = publicForm.querySelector('input[name="image"]');
    imageInput.addEventListener('change', () => {
      const file = imageInput.files[0];
      if(file){
        if(file.size > 2 * 1024 * 1024) {
          alert('File size must be less than 2MB');
          imageInput.value = '';
          imagePreview.style.display = 'none';
          return;
        }
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.style.display = 'block';
      } else {
        imagePreview.style.display = 'none';
      }
    });
  }

  /* ======= Submit Form ======= */
  if(publicForm){
    publicForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      if(submitBtn) submitBtn.disabled = true;
      showMsg('info','Submitting for approval...');
      try {
        const fd = new FormData(publicForm);
        const res = await fetch('includes/request_save.php', {method:'POST', body:fd});
        const txt = await res.text();

        if(res.ok){
          publicForm.reset();
          imagePreview.style.display = 'none';
          showMsg('success', txt);

          // Add pending card
          const name = fd.get('name');
          const designation = fd.get('designation');
          const mobile = fd.get('mobile');
          const department = fd.get('department');
          const address = fd.get('address');
          const imageFile = publicForm.querySelector('input[name="image"]').files[0];

          const imageURL = imageFile ? URL.createObjectURL(imageFile) : "https://via.placeholder.com/80x80?text=BCIC";
          const tempId = `pending-${Date.now()}`;
          const pendingHtml = `
            <div class="list-group-item card contact-card contact-highlight employee-pending" data-temp-id="${tempId}" data-pending="true">
              <div class="card-body shadow">
                <div class="row align-items-center">
                  <div class="col-12">
                    <div class="d-flex flex-column flex-md-row align-items-start">
                      <img src="${imageURL}" class="rounded-circle me-md-3 mb-3 mb-md-0 employee-image" style="object-fit:cover;">
                      <div class="flex-grow-1 w-100">
                        <h5 class="card-title mb-1">${escapeHtml(name)}</h5>
                        <p class="mb-1 text-primary fw-bold">${escapeHtml(designation)}</p>
                        <p class="mb-1"><span class="department-tag">${escapeHtml(department || 'General')}</span></p>
                        <div class="mt-2">
                          <div class="contact-info">
                            <div class="contact-info-item">
                              <i class="fa fa-mobile"></i>
                              <span>${escapeHtml(mobile)}</span>
                            </div>
                          </div>
                        </div>
                        ${address ? `
                        <div class="address-section mt-2">
                          <i class="fa fa-map-marker me-2"></i>
                          <small>${escapeHtml(address)}</small>
                        </div>` : ''}
                        <div class="mt-2">
                          <span class="badge bg-warning"><i class="fa fa-clock-o me-1"></i> Pending Approval</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
          contactsList.insertAdjacentHTML('afterbegin', pendingHtml);

          // Auto close modal after 2 seconds
          setTimeout(()=>{ const modal = bootstrap.Modal.getInstance(modalEl); if(modal) modal.hide(); }, 2000);
        } else {
          showMsg('danger', txt);
        }
      } catch(e) {
        console.error(e);
        showMsg('danger','Error submitting request.');
      } finally {
        if(submitBtn) submitBtn.disabled = false;
      }
    });
  }

  // Helper function to escape HTML
  function escapeHtml(unsafe) {
    if(!unsafe) return '';
    return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  /* ======= Modal Reset ======= */
  if(modalEl){
    modalEl.addEventListener('hidden.bs.modal', () => {
      if(publicForm) publicForm.reset();
      if(imagePreview) imagePreview.style.display = 'none';
      if(publicMsg) publicMsg.innerHTML = '';
      
      // Reset validation states
      const invalidInputs = publicForm.querySelectorAll('.is-invalid, .is-valid');
      invalidInputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
      });
      
      const feedbacks = publicForm.querySelectorAll('.validation-feedback');
      feedbacks.forEach(feedback => {
        feedback.style.display = 'none';
        feedback.innerHTML = '';
      });
    });
  }

  /* ======= Search & Filter ======= */
  const handleSearch = debounce((e) => {
    searchQuery = e.target.value.trim();
    loadEmployees(searchQuery, selectedDepartment, true);
  }, 250);

  searchBox.addEventListener('input', handleSearch);
  clearBtn.addEventListener('click', () => {
    searchBox.value=""; searchQuery=""; loadEmployees("", selectedDepartment, true); searchBox.focus();
  });
  departmentFilter.addEventListener('change', e => {
    selectedDepartment = e.target.value;
    loadEmployees(searchQuery, selectedDepartment, true);
  });

  /* ======= Load Employees ======= */
  async function loadEmployees(query="", department="", reset=false){
    if(loading || (allLoaded && !reset)) return;
    loading = true;
    spinner.style.display = 'block';

    if(reset){ 
      offset = 0; 
      allLoaded = false; 
      contactsList.innerHTML = '';
      loadedEmployeeIds.clear();
    }

    try {
      const params = new URLSearchParams({q: query, department, limit, offset});
      const res = await fetch(`includes/get_employees_public.php?${params}`);
      const data = await res.json();

      if(data.success){
        if(reset && data.employees.length === 0){
          contactsList.innerHTML = '<div class="alert alert-info text-center empty-state"><i class="fa fa-info-circle me-2"></i>No employees found matching your criteria.</div>';
          allLoaded = true;
        } else if(data.employees.length > 0){
          renderEmployees(data.employees);
          offset += data.employees.length;
          if(data.stats) updateStatistics(data.stats);
        } else {
          allLoaded = true;
        }

        if(data.employees.length < limit) allLoaded = true;
      } else {
        if(offset===0) contactsList.innerHTML = `<div class="alert alert-danger text-center empty-state">${data.message || 'Error loading employees'}</div>`;
        allLoaded = true;
      }
    } catch(e){
      console.error(e);
      if(offset===0) contactsList.innerHTML = '<div class="alert alert-danger text-center empty-state">Error loading employees. Please try again.</div>';
    } finally {
      spinner.style.display='none';
      loading=false;
    }
  }

  /* ======= Render Employees with Address ======= */
  function renderEmployees(employees){
    employees.forEach(employee => {
      if(loadedEmployeeIds.has(employee.emp_id)) return;
      loadedEmployeeIds.add(employee.emp_id);
      
      const isActive = employee.status==='active';
      const isApproved = employee.system_status==='approved';
      const isPending = employee.system_status==='pending';
      const isFullyApproved = isActive && isApproved;
      const readonlyClass = !isFullyApproved ? 'employee-pending' : '';

      let badgeHTML = '';
      if(!isFullyApproved){
        badgeHTML = isPending
          ? `<span class="badge bg-warning"><i class="fa fa-clock-o me-1"></i> Pending Approval</span>`
          : `<span class="badge bg-secondary"><i class="fa fa-ban me-1"></i> Inactive</span>`;
      }

      const empHtml = `
      <div class="list-group-item card contact-card ${readonlyClass}" data-emp-id="${escapeHtml(employee.emp_id)}" data-status="${escapeHtml(employee.status)}" data-system-status="${escapeHtml(employee.system_status)}">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="d-flex flex-column flex-md-row align-items-start employee-content">
                <img src="${escapeHtml(employee.image || 'https://via.placeholder.com/80x80?text=BCIC')}" class="rounded-circle me-md-3 mb-3 mb-md-0 employee-image" style="object-fit:cover;">
                <div class="flex-grow-1 w-100 employee-details">
                  <h5 class="card-title mb-1">${escapeHtml(employee.name)}</h5>
                  <p class="mb-1 text-primary fw-bold">${escapeHtml(employee.designation || 'Not specified')}</p>
                  <p class="mb-1">
                    <span class="department-tag">${escapeHtml(employee.department || 'General')}</span>
                    ${employee.division ? `<span class="department-tag ms-1">${escapeHtml(employee.division)}</span>` : ''}
                  </p>
                  <div class="mt-2 contact-info d-flex flex-wrap">
                    ${employee.mobile ? `
                    <div class="contact-info-item">
                      <i class="fa fa-mobile"></i>
                      <div>
                        <small class="text-muted d-block">Mobile</small>
                        <strong>${escapeHtml(employee.mobile)}</strong>
                      </div>
                    </div>` : ''}
                    ${employee.phone_office ? `
                    <div class="contact-info-item">
                      <i class="fa fa-phone"></i>
                      <div>
                        <small class="text-muted d-block">Office</small>
                        <strong>${escapeHtml(employee.phone_office)}</strong>
                      </div>
                    </div>` : ''}
                    ${employee.intercom ? `
                    <div class="contact-info-item">
                      <i class="fa fa-hashtag"></i>
                      <div>
                        <small class="text-muted d-block">Intercom</small>
                        <strong>${escapeHtml(employee.intercom)}</strong>
                      </div>
                    </div>` : ''}
                  </div>
                  ${employee.email ? `
                  <div class="mt-1">
                    <i class="fa fa-envelope text-primary me-1"></i>
                    ${isFullyApproved ? `<a href="mailto:${escapeHtml(employee.email)}" class="text-decoration-none">${escapeHtml(employee.email)}</a>` : `<span class="text-muted">${escapeHtml(employee.email)}</span>`}
                  </div>` : ''}
                  ${employee.address ? `
                  <div class="address-section mt-2">
                    <i class="fa fa-map-marker me-2"></i>
                    <small class="text-muted">Address:</small>
                    <div class="mt-1">${escapeHtml(employee.address)}</div>
                  </div>` : ''}
                  ${!isFullyApproved ? `<div class="mt-2">${badgeHTML}</div>` : ''}
                </div>
                <div class="mt-3 mt-md-0 ms-md-auto employee-actions w-100 w-md-auto">
                  ${isFullyApproved
                    ? `<div class="d-flex flex-column flex-sm-row flex-md-column gap-2">
                        ${employee.mobile ? `
                        <a href="tel:${escapeHtml(employee.mobile)}" class="btn btn-success btn-sm"><i class="fa fa-phone"></i> <span class="d-sm-inline d-md-none d-lg-inline">Call</span></a>
                        <a href="sms:${escapeHtml(employee.mobile)}" class="btn btn-primary btn-sm"><i class="fa fa-comment"></i> <span class="d-sm-inline d-md-none d-lg-inline">SMS</span></a>
                        ` : ''}
                        ${employee.email ? `
                        <a href="mailto:${escapeHtml(employee.email)}" class="btn btn-outline-primary btn-sm"><i class="fa fa-envelope"></i> <span class="d-sm-inline d-md-none d-lg-inline">Email</span></a>
                        ` : ''}
                        <div class="mt-1 text-md-center">
                          <span class="badge bg-success employee-badge"><i class="fa fa-check-circle me-1"></i>Active</span>
                          ${employee.emp_id ? `<span class="badge bg-info employee-badge mt-1 mt-sm-0 mt-md-1 ms-sm-1 ms-md-0"><i class="fa fa-id-card me-1"></i>${escapeHtml(employee.emp_id)}</span>` : ''}
                        </div>
                      </div>`
                    : `<div class="text-muted text-md-center"><small>${isPending ? 'Awaiting Approval' : 'Not Active'}</small><div class="mt-1"><span class="badge ${isPending ? 'bg-warning' : 'bg-secondary'} employee-badge">${isPending ? 'Pending' : 'Inactive'}</span></div></div>`}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      `;
      contactsList.insertAdjacentHTML('beforeend', empHtml);
    });
  }

  /* ======= Update Statistics ======= */
  function updateStatistics(stats){
    if(!stats) return;
    totalEmployees.textContent = stats.total_employees || '0';
    activeEmployees.textContent = stats.active_employees || '0';
    pending_employees.textContent = stats.pending_employees || '0';
    totalDepartments.textContent = stats.total_departments || '0';
    lastUpdated.textContent = stats.last_updated || '-';
  }

  /* ======= Infinite Scroll ======= */
  if(loadMoreTrigger){
    new IntersectionObserver(entries => {
      if(entries[0].isIntersecting && !loading && !allLoaded) loadEmployees(searchQuery, selectedDepartment);
    }, { threshold: 0.1 }).observe(loadMoreTrigger);
  }

  /* ======= Auto-refresh New Employees ======= */
  setInterval(async () => {
    try {
      const params = new URLSearchParams({q: searchQuery, department: selectedDepartment, limit:5, offset:0, check_new:true});
      const res = await fetch(`includes/get_employees_public.php?${params}`);
      const data = await res.json();
      if(data.success && data.employees.length > 0){
        let hasNew = false;
        data.employees.forEach(employee => {
          if(!loadedEmployeeIds.has(employee.emp_id) && employee.system_status==='approved' && employee.status==='active'){
            hasNew = true;
            renderEmployees([employee]);
          }
        });
        if(hasNew && data.stats) updateStatistics(data.stats);
      }
    } catch(e){ console.error('Auto-refresh error:', e); }
  }, 10000);

  /* ======= Real-time Field Validation ======= */
  const validateField = debounce(async (input, fieldName) => {
    const value = input.value.trim();
    if(!value) return;
    try {
      const params = new URLSearchParams({ field: fieldName, value });
      const res = await fetch('includes/check_field.php?' + params);
      const data = await res.json();
      
      // Remove existing feedback
      const existingMsg = input.parentNode.querySelector('.field-alert');
      if(existingMsg) existingMsg.remove();
      
      if(data.exists){
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        const msgEl = document.createElement('div');
        msgEl.className = 'field-alert validation-feedback error';
        msgEl.textContent = `${fieldName.replace('_',' ')} already exists`;
        input.insertAdjacentElement('afterend', msgEl);
        if(submitBtn) submitBtn.disabled = true;
      } else {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        const msgEl = document.createElement('div');
        msgEl.className = 'field-alert validation-feedback success';
        msgEl.textContent = `${fieldName.replace('_',' ')} is available`;
        input.insertAdjacentElement('afterend', msgEl);
        if(submitBtn) submitBtn.disabled = false;
      }
    } catch(e){ console.error('Validation error:', e); }
  }, 500);

  if(publicForm){
    const empIdInput = publicForm.querySelector('input[name="emp_id"]');
    const mobileInput = publicForm.querySelector('input[name="mobile"]');
    const emailInput = publicForm.querySelector('input[name="email"]');
    
    if(empIdInput) {
      empIdInput.addEventListener('input', () => {
        const existingMsg = empIdInput.parentNode.querySelector('.field-alert');
        if(existingMsg) existingMsg.remove();
        empIdInput.classList.remove('is-invalid', 'is-valid');
        if(empIdInput.value.trim()) validateField(empIdInput,'emp_id');
      });
    }
    
    if(mobileInput) {
      mobileInput.addEventListener('input', () => {
        const existingMsg = mobileInput.parentNode.querySelector('.field-alert');
        if(existingMsg) existingMsg.remove();
        mobileInput.classList.remove('is-invalid', 'is-valid');
        if(mobileInput.value.trim()) validateField(mobileInput,'mobile');
      });
    }
    
    if(emailInput) {
      emailInput.addEventListener('input', () => {
        const existingMsg = emailInput.parentNode.querySelector('.field-alert');
        if(existingMsg) existingMsg.remove();
        emailInput.classList.remove('is-invalid', 'is-valid');
        if(emailInput.value.trim()) validateField(emailInput,'email');
      });
    }
  }

  /* ======= Pending Employees Style ======= */
  const style = document.createElement('style');
  style.textContent = `
    .employee-pending { 
      opacity:0.85; 
      background-color:#fff8e7 !important; 
      border-left:4px solid #ffc107 !important; 
    }
    .employee-pending .card-body { 
      cursor:not-allowed; 
    }
    .employee-pending a { 
      pointer-events:none; 
      text-decoration:none; 
      color:#6c757d !important; 
    }
    .employee-pending .btn { 
      display:none !important; 
    }
    
    /* Responsive touch improvements */
    @media (max-width: 767.98px) {
      .btn, .form-control, .form-select {
        font-size: 16px;
      }
      
      .contact-info-item {
        width: 100%;
        margin-bottom: 0.5rem;
      }
      
      .address-section {
        font-size: 0.85rem;
      }
      
      .modal-body {
        padding: 1rem;
      }
      
      .modal-body .row.g-3 > [class*="col-"] {
        margin-bottom: 1rem;
      }
    }
    
    /* Fix for iOS input zoom */
    @media screen and (-webkit-min-device-pixel-ratio:0) { 
      select, textarea, input {
        font-size: 16px !important;
      }
    }
    
    /* Smooth scrolling */
    .modal-body {
      -webkit-overflow-scrolling: touch;
    }
    
    /* Section headers in modal */
    .modal-body h6 {
      font-size: 1rem;
      font-weight: 600;
    }
    
    .modal-body hr {
      margin-top: 0;
      margin-bottom: 1rem;
    }
        /* Statistics responsive */
    @media (max-width: 575.98px) {
      .stat-card {
        padding: 0.5rem;
      }
      
      .stat-card small {
        font-size: 0.7rem;
      }
      
      .stat-card h5, .stat-card h6 {
        font-size: 1rem;
      }
    }
  `;
  document.head.appendChild(style);

  /* ======= Initial Load ======= */
  loadEmployees();

  /* ======= Handle orientation change ======= */
  window.addEventListener('orientationchange', () => {
    setTimeout(() => {
      document.body.style.display = 'none';
      document.body.offsetHeight;
      document.body.style.display = '';
    }, 200);
  });

  /* ======= Handle resize events ======= */
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      // Adjust any dynamic elements if needed
    }, 250);
  });

});
</script>