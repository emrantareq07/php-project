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
}
</style>

<div class="container my-4">
  <!-- Search Section -->
  <div class="row mb-4">
    <div class="col-md-8">
      <h2 class="text-primary mb-3 fw-bold">BCIC Telephone Directory</h2>
      <p class="text-muted">Find contact information for BCIC employees</p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#publicModal">
    <i class="fa fa-comments-o"></i> Add Contact
  </button>
      <!-- Admin Login Button -->
      <a href="admin/login.php" class="btn btn-outline-primary">
        <i class="fa fa-lock"></i> Admin Login
      </a>
    </div>
  </div>

    <!-- Modal with Contact Submission Form -->
<!-- Modal with Contact Submission Form -->
<div class="modal fade" id="publicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="publicForm" class="row g-3 p-3" autocomplete="off" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title text-muted text-uppercase float-end">Add Employee Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="px-3 mt-2" id="publicMsgWrap"><span id="publicMsg" class="me-auto text-center d-block"></span></div>
        <div class="modal-body">
          <div class="row g-3">
            <!-- Basic Information -->
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Full Name</label>
              <input autocomplete="on" type="text" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Designation</label>
              <input autocomplete="on" type="text" name="designation" class="form-control" required>
            </div>
            
            <!-- Contact Information -->
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Mobile</label>
              <input autocomplete="on" name="mobile" class="form-control" required inputmode="tel" 
                     pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                     maxlength="11" placeholder="01XXXXXXXXX">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Office Phone</label>
              <input autocomplete="on" name="phone_office" class="form-control" inputmode="tel">
            </div>
            
            <!-- Professional Information -->
            <div class="col-12 col-md-6">
              <label class="form-label required-field">Department</label>
              <select name="department" class="form-select" required>
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
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Division</label>
              <input name="division" class="form-control" placeholder="e.g., Accounts, Marketing">
            </div>
            
            <!-- Additional Information -->
            <div class="col-12 col-md-6">
              <label class="form-label">Email</label>
              <input autocomplete="on" name="email" type="email" class="form-control">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Intercom</label>
              <input name="intercom" class="form-control" placeholder="e.g., 0501">
            </div>
            
            <div class="col-12 col-md-6">
              <label class="form-label">Office Location</label>
              <input name="office" class="form-control" placeholder="e.g., BCIC Head Office">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Fax</label>
              <input name="fax" class="form-control">
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
              <label class="form-label">Profile Image</label>
              <input type="file" name="image" class="form-control" accept="image/*">
              <div class="mt-2">
                <img id="image_preview" src="" alt="Preview" width="80" height="80" class="rounded-circle border d-none">
              </div>
            </div>
            
            <div class="col-12">
              <label class="form-label required-field">Address</label>
              <textarea autocomplete="on" name="address" class="form-control" rows="2" required placeholder="Full residential address"></textarea>
            </div>
            
            <!-- Hidden field for submitted_by -->
            <input type="hidden" name="submitted_by" value="public">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Submit for Approval</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Search and Filters -->
  <div class="row mb-4">
    <div class="col-md-8">
      <div class="input-group">
        <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
        <input type="text" id="searchBox" autofocus class="form-control" placeholder="Search by name, designation, department, mobile, email...">
        <button class="btn btn-outline-secondary" id="clearSearchBtn" type="button">
          <i class="fa fa-times"></i>
        </button>
      </div>
    </div>
    <div class="col-md-4">
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
          <div class="row text-center">
            <div class="col-md-3 border-end">
              <small class="text-muted">Total Employees</small>
              <h5 class="mb-0" id="totalEmployees">0</h5>
            </div>
            <div class="col-md-3 border-end">
              <small class="text-muted">Active</small>
              <h5 class="mb-0 text-success" id="activeEmployees">0</h5>
            </div>
            <div class="col-md-3 border-end">
              <small class="text-muted">Departments</small>
              <h5 class="mb-0" id="totalDepartments">0</h5>
            </div>
            <div class="col-md-3">
              <small class="text-muted">Last Updated</small>
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
    <div class="spinner-border text-primary" role="status"></div>
    <div class="mt-2 small">Loading employees...</div>
  </div>
  
  <!-- Load More Trigger -->
  <div id="loadMoreTrigger" style="height:1px;"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const contactsList = document.getElementById('contactsList');
  const spinner = document.getElementById('loadingSpinner');
  const searchBox = document.getElementById('searchBox');
  const clearBtn = document.getElementById('clearSearchBtn');
  const departmentFilter = document.getElementById('departmentFilter');
  
  // Statistics elements
  const totalEmployees = document.getElementById('totalEmployees');
  const activeEmployees = document.getElementById('activeEmployees');
  const totalDepartments = document.getElementById('totalDepartments');
  const lastUpdated = document.getElementById('lastUpdated');

  function debounce(fn, wait=300){ 
    let t; 
    return (...a)=>{ 
      clearTimeout(t); 
      t=setTimeout(()=>fn(...a),wait); 
    }; 
  }

  // Add this to your existing JavaScript
const publicForm = document.getElementById('publicForm');
const publicMsg = document.getElementById('publicMsg');
const modalEl = document.getElementById('publicModal');
const imagePreview = document.getElementById('image_preview');
const submitBtn = publicForm ? publicForm.querySelector('button[type="submit"]') : null;

function showMsg(type, text){
    publicMsg.className='fade-out';
    publicMsg.innerHTML=`<div class="alert alert-${type} mb-0">${text}</div>`;
    setTimeout(()=>publicMsg.classList.add('hide'),3000);
    setTimeout(()=>{publicMsg.innerHTML=''; publicMsg.className='';},4000);
}

// Image preview
if (publicForm) {
    const imageInput = publicForm.querySelector('input[name="image"]');
    imageInput.addEventListener('change', function(){
        const file = this.files[0];
        if(file){
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.classList.remove('d-none');
        }
    });
}

/* Submit form */
if (publicForm) {
    publicForm.addEventListener('submit', async (e)=>{
        e.preventDefault();
        submitBtn.disabled=true;
        showMsg('info','Submitting for approval...');

        try{
            const fd = new FormData(publicForm);
            const res = await fetch('includes/request_save.php',{method:'POST',body:fd});
            const txt = await res.text();

            if(res.ok){
                // Reset form
                publicForm.reset();
                imagePreview.classList.add('d-none');
                showMsg('success',txt);

                // Add pending card to UI
                const name = fd.get('name');
                const designation = fd.get('designation');
                const mobile = fd.get('mobile');
                const department = fd.get('department');
                const imageFile = publicForm.querySelector('input[name="image"]').files[0];
                
                let imageURL = "https://via.placeholder.com/80x80?text=BCIC";
                if(imageFile){
                    imageURL = URL.createObjectURL(imageFile);
                }

                const tempId = `pending-${Date.now()}`;
                const pendingHtml = `
                    <div class="list-group-item card contact-card contact-highlight" data-temp-id="${tempId}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start">
                                        <img src="${imageURL}" class="rounded-circle me-3" width="80" height="80" style="object-fit:cover;">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">${name}</h5>
                                            <p class="mb-1 text-primary fw-bold">${designation}</p>
                                            <p class="mb-1">
                                                <span class="department-tag">${department}</span>
                                            </p>
                                            <div class="mt-2">
                                                <div class="d-inline-block me-3">
                                                    <small class="text-muted">Mobile:</small>
                                                    <div><i class="fa fa-mobile me-1"></i> ${mobile}</div>
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
                contactsList.insertAdjacentHTML('afterbegin',pendingHtml);

                // Auto close modal after 2 seconds
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if(modal) modal.hide();
                }, 2000);
            } else {
                showMsg('danger', txt);
            }

        }catch(e){
            showMsg('danger','Error submitting request.');
            console.error(e);
        }finally{ 
            if(submitBtn) submitBtn.disabled=false; 
        }
    });
}

// Reset form when modal is closed
if (modalEl) {
    modalEl.addEventListener('hidden.bs.modal',()=>{ 
        if(publicForm) publicForm.reset(); 
        if(imagePreview) imagePreview.classList.add('d-none');
        if(publicMsg) publicMsg.innerHTML=''; 
    });
}

  /* Search & Filter Functions */
  let searchQuery="", selectedDepartment="";
  let offset=0, limit=12, loading=false, allLoaded=false;

  const handleSearch = debounce((e) => { 
    searchQuery = e.target.value.trim(); 
    loadEmployees(searchQuery, selectedDepartment, true); 
  }, 250);

  searchBox.addEventListener('input', handleSearch);
  
  clearBtn.addEventListener('click', () => { 
    searchBox.value=""; 
    searchQuery=""; 
    loadEmployees("", selectedDepartment, true); 
    searchBox.focus(); 
  });

  departmentFilter.addEventListener('change', (e) => {
    selectedDepartment = e.target.value;
    loadEmployees(searchQuery, selectedDepartment, true);
  });

  /* Load Employees Function */
  async function loadEmployees(query="", department="", reset=false){
    if(loading || (allLoaded && !reset)) return;
    
    loading = true; 
    spinner.style.display = reset ? 'block' : 'block';
    
    if(reset){
      offset = 0; 
      allLoaded = false; 
      contactsList.innerHTML = '';
    }
    
    try{
      const params = new URLSearchParams({
        q: query,
        department: department,
        limit: limit,
        offset: offset
      });
      
      const res = await fetch(`includes/get_employees_public.php?${params}`);
      const data = await res.json();
      
      if(data.success){
        if(reset && data.employees.length === 0){
          contactsList.innerHTML = '<div class="alert alert-warning text-center">No employees found matching your criteria.</div>';
          allLoaded = true;
        } else if(data.employees.length > 0){
          renderEmployees(data.employees);
          offset += data.employees.length;
          
          // Update statistics
          updateStatistics(data.stats);
        } else {
          allLoaded = true;
        }
        
        // Check if we've loaded all available employees
        if(data.employees.length < limit){
          allLoaded = true;
        }
      } else {
        if(offset === 0){
          contactsList.innerHTML = `<div class="alert alert-danger text-center">${data.message || 'Error loading employees'}</div>`;
        }
        allLoaded = true;
      }
    } catch(e){
      console.error('Error loading employees:', e);
      if(offset === 0){
        contactsList.innerHTML = '<div class="alert alert-danger text-center">Error loading employees. Please try again.</div>';
      }
    } finally{ 
      spinner.style.display = 'none'; 
      loading = false; 
    }
  }

  /* Render Employees Function */
  function renderEmployees(employees){
    employees.forEach(employee => {
      const employeeHtml = `
        <div class="list-group-item card contact-card" data-emp-id="${employee.emp_id}">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-8">
                <div class="d-flex align-items-start">
                  <img src="${employee.image || 'https://via.placeholder.com/80x80?text=BCIC'}" 
                       class="rounded-circle me-3" width="80" height="80" style="object-fit:cover;">
                  <div class="flex-grow-1">
                    <h5 class="card-title mb-1">${employee.name}</h5>
                    <p class="mb-1 text-primary fw-bold">${employee.designation || 'Not specified'}</p>
                    <p class="mb-1">
                      <span class="department-tag">${employee.department || 'General'}</span>
                      ${employee.division ? `<span class="department-tag ms-1">${employee.division}</span>` : ''}
                    </p>
                    
                    <!-- Contact Information -->
                    <div class="mt-2">
                      ${employee.mobile ? `
                        <div class="d-inline-block me-3">
                          <small class="text-muted">Mobile:</small>
                          <div><i class="fa fa-mobile me-1"></i> ${employee.mobile}</div>
                        </div>
                      ` : ''}
                      
                      ${employee.phone_office ? `
                        <div class="d-inline-block me-3">
                          <small class="text-muted">Office:</small>
                          <div><i class="fa fa-phone me-1"></i> ${employee.phone_office}</div>
                        </div>
                      ` : ''}
                      
                      ${employee.intercom ? `
                        <div class="d-inline-block">
                          <small class="text-muted">Intercom:</small>
                          <div><i class="fa fa-hashtag me-1"></i> ${employee.intercom}</div>
                        </div>
                      ` : ''}
                    </div>
                    
                    ${employee.email ? `
                      <div class="d-inline-block">
                        <small class="text-muted">Email:</small>
                        <div><i class="fa fa-envelope me-1"></i> 
                          <a href="mailto:${employee.email}" class="text-decoration-none">${employee.email}</a>
                        </div>
                      </div>
                    ` : ''}
                    
                    ${employee.office ? `
                      <div class="d-inline-block">
                        <small class="text-muted">Office:</small>
                        <div><i class="fa fa-building me-1"></i> ${employee.office}</div>
                      </div>
                    ` : ''}
                  </div>
                </div>
              </div>
              
              <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <div class="d-flex flex-column gap-2">
                  ${employee.mobile ? `
                    <a href="tel:${employee.mobile}" class="btn btn-success btn-sm">
                      <i class="fa fa-phone"></i> Call
                    </a>
                  ` : ''}
                  
                  ${employee.email ? `
                    <a href="mailto:${employee.email}" class="btn btn-outline-primary btn-sm">
                      <i class="fa fa-envelope"></i> Email
                    </a>
                  ` : ''}
                  
                  <div class="mt-1">
                    <span class="badge ${employee.status === 'active' ? 'bg-success' : 'bg-secondary'} employee-badge">
                      ${employee.status === 'active' ? 'Active' : 'Inactive'}
                    </span>
                    ${employee.emp_id ? `<span class="badge bg-info employee-badge ms-1">${employee.emp_id}</span>` : ''}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
      
      contactsList.insertAdjacentHTML('beforeend', employeeHtml);
    });
  }

  /* Update Statistics */
  function updateStatistics(stats){
    if(stats){
      totalEmployees.textContent = stats.total_employees || '0';
      activeEmployees.textContent = stats.active_employees || '0';
      totalDepartments.textContent = stats.total_departments || '0';
      lastUpdated.textContent = stats.last_updated || '-';
    }
  }

  /* Infinite Scroll */
  const loadMoreTrigger = document.getElementById('loadMoreTrigger');
  const observer = new IntersectionObserver((entries) => {
    if(entries[0].isIntersecting && !loading && !allLoaded){
      loadEmployees(searchQuery, selectedDepartment);
    }
  });

  if(loadMoreTrigger){
    observer.observe(loadMoreTrigger);
  }

  // Initial load
  loadEmployees();

  /* Auto-refresh for new employees */
  setInterval(async () => {
    try {
      const params = new URLSearchParams({
        q: searchQuery,
        department: selectedDepartment,
        limit: 5,
        offset: 0,
        check_new: true
      });
      
      const res = await fetch(`includes/get_employees_public.php?${params}`);
      const data = await res.json();
      
      if(data.success && data.employees.length > 0){
        // Check for new employees and add them to the top
        data.employees.forEach(employee => {
          const existing = contactsList.querySelector(`[data-emp-id="${employee.emp_id}"]`);
          if(!existing){
            const temp = document.createElement('div');
            temp.innerHTML = renderEmployees([employee]);
            const newCard = temp.firstElementChild;
            newCard.classList.add('contact-highlight');
            contactsList.insertAdjacentElement('afterbegin', newCard);
            
            // Remove highlight after 3 seconds
            setTimeout(() => {
              newCard.classList.remove('contact-highlight');
            }, 3000);
          }
        });
        
        // Update statistics
        updateStatistics(data.stats);
      }
    } catch(e){
      console.error('Error checking for new employees:', e);
    }
  }, 10000); // Check every 10 seconds

});
</script>

<?php //include 'includes/footer.php'; ?>