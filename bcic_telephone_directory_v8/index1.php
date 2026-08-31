<?php
require 'db/db.php';
?>
<?php include 'includes/header.php'; ?>

<style>
/* small UI polish */
.contact-card {
  transition: transform 0.18s, box-shadow 0.18s, background-color 1s;
  border-left: 4px solid #0d6efd;
  cursor: default;
}
.contact-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }

/* highlight effect for new cards */
.contact-highlight {
  background-color: #fff3cd !important; /* soft yellow */
  animation: fadeHighlight 3s forwards;
}
@keyframes fadeHighlight {
  from { background-color: #fff3cd; }
  to { background-color: white; }
}

#contactsList .card { margin-bottom: .9rem; }
#loadingSpinner { display: none; }
.required-field::after { content: "*"; color: red; margin-left: 4px; }

/* fade-out for messages */
.fade-out {
  opacity: 1;
  transition: opacity 1s ease-out;
}
.fade-out.hide {
  opacity: 0;
}

.employee-badge {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
}

.department-tag {
  background: #e9ecef;
  color: #495057;
  font-size: 0.8rem;
  padding: 0.2rem 0.5rem;
  border-radius: 0.25rem;
  display: inline-block;
  margin: 0.125rem;
}

/* Validation styles */
.validation-feedback {
  font-size: 0.875rem;
  margin-top: 0.25rem;
  display: block;
}
.validation-feedback.error {
  color: #dc3545;
}
.validation-feedback.success {
  color: #198754;
}
.is-invalid {
  border-color: #dc3545 !important;
}
.is-valid {
  border-color: #198754 !important;
}

/* Responsive Statistics Cards */
.stat-card {
  padding: 0.75rem 0.5rem;
  border-bottom: 1px solid #dee2e6;
}
.stat-card:last-child {
  border-bottom: none;
}
@media (min-width: 768px) {
  .stat-card {
    border-bottom: none;
    border-right: 1px solid #dee2e6;
  }
  .stat-card:last-child {
    border-right: none;
  }
}

/* Responsive Employee Cards */
@media (max-width: 767.98px) {
  .employee-content {
    flex-direction: column !important;
    align-items: flex-start !important;
  }
  
  .employee-image {
    margin-bottom: 1rem;
    margin-right: 0 !important;
  }
  
  .employee-details {
    width: 100%;
  }
  
  .employee-actions {
    margin-top: 1rem;
    width: 100%;
  }
  
  .employee-actions .btn {
    width: 100%;
    margin-bottom: 0.5rem;
  }
  
  .employee-actions .btn:last-child {
    margin-bottom: 0;
  }
  
  .contact-info {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
  }
  
  .contact-info-item {
    width: 100%;
  }
}

@media (min-width: 768px) and (max-width: 991.98px) {
  .employee-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
  }
}

/* Improved touch targets for mobile */
@media (max-width: 767.98px) {
  .btn, 
  .form-control,
  .form-select,
  .input-group-text,
  .list-group-item {
    min-height: 44px;
  }
  
  .btn-sm {
    min-height: 38px;
  }
  
  .department-tag {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
  }
}

/* Responsive Modal */
@media (max-width: 767.98px) {
  .modal-dialog {
    margin: 0.5rem;
  }
  
  .modal-body {
    padding: 1rem;
  }
  
  .modal-footer {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .modal-footer .btn {
    width: 100%;
    margin: 0;
  }
}

/* Search and Filter responsive */
@media (max-width: 767.98px) {
  .search-filter-row {
    flex-direction: column;
    gap: 1rem;
  }
  
  .search-filter-row > div {
    width: 100%;
  }
  
  #clearSearchBtn {
    padding: 0.375rem 0.75rem;
  }
}

/* Header responsive */
@media (max-width: 767.98px) {
  .header-section {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 1rem;
  }
  
  .header-section .text-end {
    width: 100%;
    text-align: left !important;
  }
  
  .header-section .btn {
    width: 100%;
    margin: 0 !important;
  }
  
  .header-section .btn:first-child {
    margin-bottom: 0.5rem !important;
  }
  
  h2 {
    font-size: 1.5rem;
  }
}

/* Grid optimization */
.contact-card .row {
  --bs-gutter-x: 1rem;
}

@media (max-width: 767.98px) {
  .contact-card .row {
    --bs-gutter-x: 0.75rem;
  }
}

/* Image responsive */
.employee-image {
  width: 80px;
  height: 80px;
}

@media (max-width: 767.98px) {
  .employee-image {
    width: 70px;
    height: 70px;
  }
}

@media (max-width: 575.98px) {
  .employee-image {
    width: 60px;
    height: 60px;
  }
}

/* Badge responsive */
.employee-badge {
  display: inline-block;
  margin: 0.125rem;
}

@media (max-width: 575.98px) {
  .employee-badge {
    display: block;
    margin: 0.25rem 0;
  }
}

/* Loading spinner */
#loadingSpinner {
  padding: 2rem 1rem;
}

/* Empty state */
.empty-state {
  padding: 3rem 1rem;
  text-align: center;
}

/* Responsive font sizes */
@media (max-width: 767.98px) {
  .card-title {
    font-size: 1.1rem;
  }
  
  .text-primary.fw-bold {
    font-size: 0.9rem;
  }
  
  .text-muted small {
    font-size: 0.75rem;
  }
}

/* Modal responsive improvements */
@media (max-width: 767.98px) {
  .modal-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .modal-header .btn-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
  }
  
  .modal-title {
    font-size: 1rem;
    padding-right: 2rem;
  }
}

/* Form responsive */
@media (max-width: 767.98px) {
  .form-label {
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
  }
  
  .row.g-3 > [class*="col-"] {
    margin-bottom: 0.5rem;
  }
}

/* Statistics card responsive */
.stats-container {
  display: flex;
  flex-wrap: wrap;
}

@media (max-width: 575.98px) {
  .stats-container > div {
    width: 50%;
  }
  
  .stats-container > div:nth-child(even) {
    border-right: none;
  }
  
  .stats-container > div:nth-child(1),
  .stats-container > div:nth-child(2) {
    border-bottom: 1px solid #dee2e6;
  }
}

/* Button groups responsive */
@media (max-width: 767.98px) {
  .btn-group-responsive {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .btn-group-responsive .btn {
    width: 100%;
    border-radius: 0.375rem !important;
  }
}

/* Table responsive (if any tables exist) */
@media (max-width: 767.98px) {
  .table-responsive-stack {
    display: block;
  }
  
  .table-responsive-stack thead {
    display: none;
  }
  
  .table-responsive-stack tbody,
  .table-responsive-stack tr,
  .table-responsive-stack td {
    display: block;
    width: 100%;
  }
  
  .table-responsive-stack tr {
    margin-bottom: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.5rem;
  }
  
  .table-responsive-stack td {
    text-align: right;
    padding: 0.5rem !important;
    border: none;
  }
  
  .table-responsive-stack td::before {
    content: attr(data-label);
    float: left;
    font-weight: bold;
  }
}

/* Improved scrolling on mobile */
.modal-body {
  max-height: 70vh;
  overflow-y: auto;
}

@media (max-width: 767.98px) {
  .modal-body {
    max-height: 60vh;
  }
}

/* Fix for Bootstrap modal scrolling */
.modal-open {
  overflow: hidden;
  padding-right: 0 !important;
}

/* Responsive container padding */
@media (max-width: 767.98px) {
  .container.my-4 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }
}

/* Datalist responsive */
datalist {
  max-width: 100%;
}

/* Image preview responsive */
#image_preview {
  max-width: 50px;
  max-height: 50px;
}

@media (max-width: 767.98px) {
  #image_preview {
    max-width: 40px;
    max-height: 40px;
  }
}

/* Footer responsive */
.modal-footer {
  flex-wrap: wrap;
}

@media (max-width: 767.98px) {
  .modal-footer {
    padding: 0.75rem;
  }
}
</style>

<div class="container my-4">
  <!-- Search Section -->
  <div class="row mb-4 header-section">
    <div class="col-12 col-md-8">
      <h2 class="text-success mb-3 mb-md-0 fw-bold text-uppercase righteous-regular">BCIC Telephone Directory</h2>
    </div>
    <div class="col-12 col-md-4 text-md-end">
      <button class="btn btn-outline-primary mb-2 mb-md-0 me-md-2 w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#publicModal">
        <i class="fa fa-comments-o"></i> Add Contact
      </button>
      <a href="includes/login.php" class="btn btn-outline-primary w-100 w-md-auto" target="_blank">
        <i class="fa fa-lock"></i> Admin Login
      </a>
    </div>
  </div>
 
<!-- Modal with Contact Submission Form -->
<div class="modal fade" id="publicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="publicForm" class="row g-2 p-2" autocomplete="off" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title text-muted text-uppercase"><i class="fa fa-user-plus me-2"></i> Add Employee Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="px-3 mt-0" id="publicMsgWrap">
          <span id="publicMsg" class="me-auto text-center d-block"></span>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <!-- Basic Information -->
            <div class="col-12 col-md-6">
              <label class="form-label required-field">EMP ID</label>
              <input autocomplete="on" type="text" name="emp_id" class="form-control" required placeholder="Enter EMP ID">
              <div class="emp_id_feedback validation-feedback" style="display: none;"></div>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Full Name</label>
              <input autocomplete="on" type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Designation</label>
              <!-- <input autocomplete="on" type="text" name="designation" class="form-control" required> -->
              <input list="designations" id="designation" name="designation" class="form-control" placeholder="Select Designation" required>
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
            
            <!-- Professional Information -->
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Division/Office</label>
              <input list="divisions" id="division" name="division" class="form-control" placeholder="Select Division/ Office" required>
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
              <!-- <select name="department" class="form-select">
                <option value="" selected disabled>Select Department</option>
                <option value="Administration">Administration</option>
                <option value="Commerce">Commerce</option>
                <option value="Finance">Finance</option>
                <option value="Technical">Technical</option>
                <option value="Medical">Medical</option>
                <option value="Production">Production</option>
                <option value="Planning">Planning</option>
                <option value="Human Resources">Human Resources</option>
                <option value="Other">Other</option>
              </select> -->
              <input list="departments" id="department" name="department" class="form-control" placeholder="Select Department" required>
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

            <!-- Contact Information -->
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Mobile</label>
              <input autocomplete="on" name="mobile" class="form-control" required inputmode="tel" 
                     pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                     maxlength="11" placeholder="01XXXXXXXXX">
              <div class="mobile_feedback validation-feedback" style="display: none;"></div>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Office Phone</label>
              <input autocomplete="on" name="phone_office" class="form-control" inputmode="tel" placeholder="0XXXXXXXXX">
            </div>
            
            <!-- Additional Information -->
            <div class="col-12 col-md-6">
              <label class="form-label">Email</label>
              <input autocomplete="on" name="email" type="email" class="form-control" placeholder="Enter Email ID">
              <div class="email_feedback validation-feedback" style="display: none;"></div>
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label">PABX/Intercom</label>
              <input name="intercom" class="form-control" placeholder="e.g., 0501">
            </div>
            
            <div class="col-12 col-md-4">
              <label class="form-label">Fax</label>
              <input name="fax" class="form-control" placeholder="e.g., 0501">
            </div>
            
            <!-- Personal Details -->
            <div class="col-12 col-md-4">
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
              <label class="form-label">Profile Image</label>
              <input type="file" name="image" class="form-control" accept="image/*">
              <div class="mt-1">
                <img id="image_preview" src="" alt="Preview" class="rounded-circle border d-none employee-image">
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">Address</label>
              <textarea autocomplete="on" name="address" class="form-control" rows="2" placeholder="Full residential address"></textarea>
            </div>
            
            <!-- Hidden field for submitted_by -->
            <input type="hidden" name="submitted_by" value="public">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-close me-2"></i> Close</button>
          <button type="submit" class="btn btn-success" id="submitBtn"><i class="fa fa-save me-2"></i> Submit for Approval</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Search and Filters -->
  <div class="row mb-4 search-filter-row">
    <div class="col-12 col-md-8">
      <div class="input-group">
        <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
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
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body py-2">
          <div class="stats-container">
            <div class="col-6 col-md-2 stat-card">
              <small class="text-muted d-block">Total Employees</small>
              <h5 class="mb-0" id="totalEmployees">0</h5>
            </div>
            <div class="col-6 col-md-2 stat-card">
              <small class="text-muted d-block">Active</small>
              <h5 class="mb-0 text-success" id="activeEmployees">0</h5>
            </div>
            <div class="col-6 col-md-2 stat-card">
              <small class="text-muted d-block">Pending</small>
              <h5 class="mb-0 text-warning" id="pending_employees">0</h5>
            </div>
            <div class="col-6 col-md-3 stat-card">
              <small class="text-muted d-block">Departments</small>
              <h5 class="mb-0" id="totalDepartments">0</h5>
            </div>
            <div class="col-12 col-md-3 stat-card">
              <small class="text-muted d-block">Last Updated</small>
              <h6 class="mb-0 text-muted" id="lastUpdated">-</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contacts List -->
  <div id="contactsList" class="list-group mb-2"></div>
  
  <!-- Loading Spinner -->
  <div class="text-center py-3" id="loadingSpinner" aria-hidden="true">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-2 small">Loading employees...</div>
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
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.classList.remove('d-none');
      } else {
        imagePreview.classList.add('d-none');
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
          imagePreview.classList.add('d-none');
          showMsg('success', txt);

          // Add pending card
          const name = fd.get('name');
          const designation = fd.get('designation');
          const mobile = fd.get('mobile');
          const department = fd.get('department');
          const imageFile = publicForm.querySelector('input[name="image"]').files[0];

          const imageURL = imageFile ? URL.createObjectURL(imageFile) : "https://via.placeholder.com/80x80?text=BCIC";
          const tempId = `pending-${Date.now()}`;
          const pendingHtml = `
            <div class="list-group-item card contact-card contact-highlight employee-pending" data-temp-id="${tempId}" data-pending="true">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-12">
                    <div class="d-flex flex-column flex-md-row align-items-start">
                      <img src="${imageURL}" class="rounded-circle me-md-3 mb-3 mb-md-0 employee-image" style="object-fit:cover;">
                      <div class="flex-grow-1 w-100">
                        <h5 class="card-title mb-1">${escapeHtml(name)}</h5>
                        <p class="mb-1 text-primary fw-bold">${escapeHtml(designation)}</p>
                        <p class="mb-1"><span class="department-tag">${escapeHtml(department)}</span></p>
                        <div class="mt-2">
                          <div class="contact-info">
                            <div class="contact-info-item">
                              <small class="text-muted">Mobile:</small>
                              <div><i class="fa fa-mobile me-1"></i> ${escapeHtml(mobile)}</div>
                            </div>
                          </div>
                        </div>
                        <div class="mt-2">
                          <span class="badge bg-warning text-dark"><i class="fa fa-clock-o"></i> Pending Approval</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
          contactsList.insertAdjacentHTML('afterbegin', pendingHtml);

          // Auto close modal
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
      if(imagePreview) imagePreview.classList.add('d-none');
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
          contactsList.innerHTML = '<div class="alert alert-warning text-center empty-state">No employees found matching your criteria.</div>';
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

  /* ======= Render Employees ======= */
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
          ? `<span class="badge bg-warning text-dark"><i class="fa fa-clock-o"></i> Pending Approval</span>`
          : `<span class="badge bg-secondary"><i class="fa fa-ban"></i> Inactive</span>`;
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
                  <div class="mt-2 contact-info">
                    ${employee.mobile ? `
                    <div class="contact-info-item">
                      <small class="text-muted">Mobile:</small>
                      <div><i class="fa fa-mobile me-1"></i> ${escapeHtml(employee.mobile)}</div>
                    </div>` : ''}
                    ${employee.phone_office ? `
                    <div class="contact-info-item">
                      <small class="text-muted">Office:</small>
                      <div><i class="fa fa-phone me-1"></i> ${escapeHtml(employee.phone_office)}</div>
                    </div>` : ''}
                    ${employee.intercom ? `
                    <div class="contact-info-item">
                      <small class="text-muted">Intercom:</small>
                      <div><i class="fa fa-hashtag me-1"></i> ${escapeHtml(employee.intercom)}</div>
                    </div>` : ''}
                  </div>
                  ${employee.email ? `
                  <div class="mt-1 contact-info-item">
                    <small class="text-muted">Email:</small>
                    <div><i class="fa fa-envelope me-1"></i> ${isFullyApproved ? `<a href="mailto:${escapeHtml(employee.email)}" class="text-decoration-none">${escapeHtml(employee.email)}</a>` : `<span class="text-muted">${escapeHtml(employee.email)}</span>`}</div>
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
                          <span class="badge bg-success employee-badge">Active</span>
                          ${employee.emp_id ? `<span class="badge bg-info employee-badge mt-1 mt-sm-0 mt-md-1 ms-sm-1 ms-md-0">${escapeHtml(employee.emp_id)}</span>` : ''}
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
      opacity:0.7; 
      background-color:#fff3cd !important; 
      border-left:5px solid #ffc107 !important; 
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
        font-size: 16px; /* Prevents zoom on iOS */
      }
      
      .modal-dialog {
        margin: 0.5rem;
      }
      
      .modal-body {
        padding: 1rem;
      }
    }
    
    /* Fix for iOS input zoom */
    @media screen and (-webkit-min-device-pixel-ratio:0) { 
      select,
      textarea,
      input {
        font-size: 16px !important;
      }
    }
    
    /* Smooth scrolling on iOS */
    .modal-body {
      -webkit-overflow-scrolling: touch;
    }
    
    /* Better touch targets */
    .department-tag {
      padding: 0.375rem 0.75rem;
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
    // Recalculate any dynamic heights if needed
    setTimeout(() => {
      // Force reflow for any responsive elements
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