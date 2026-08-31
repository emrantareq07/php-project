<?php
session_name('training_certificate_gen_db');
session_start();
include 'controller/db.php'; // Adjust if needed

/* ===============================
   USER REGISTRATION
================================= */
if (isset($_POST['register'])) {

    $emp_id = $_POST['emp_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $batch = $_POST['batch'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $place_of_posting = $_POST['place_of_posting'] ?? '';
    $mobile_no = $_POST['mobile_no'] ?? '';
    $email_id = $_POST['email_id'] ?? '';

    $password = '1234';
    $status = 'active';
    $role = 'user';

    // Generate serial_no
    $sql = "SELECT MAX(id) AS maxid FROM users_tbl";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $maxid = $row['maxid'] ?? 0;
    $maxid = $maxid + 1;

    $serial_no = "BCIC-ICT-DIVISION-B{$batch}-{$maxid}";

    // Check duplicate for same batch (email or mobile)
    $check = $conn->prepare("
        SELECT * FROM users_tbl 
        WHERE batch = ? AND (email_id = ? OR mobile_no = ?)
    ");
    $check->bind_param("sss", $batch, $email_id, $mobile_no);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $alert = "User with this email or mobile already registered for this batch!";
        $alertType = "danger";
    } else {

        // Check if emp_id exists in other batches to auto-fill info
        if (!empty($emp_id)) {
            $existing_user = $conn->prepare("
                SELECT * FROM users_tbl 
                WHERE emp_id = ? 
                ORDER BY id DESC 
                LIMIT 1
            ");
            $existing_user->bind_param("s", $emp_id);
            $existing_user->execute();
            $existing_result = $existing_user->get_result();

            if ($existing_result->num_rows > 0) {
                $existing_data = $existing_result->fetch_assoc();

                $name = $existing_data['name'];
                $designation = $existing_data['designation'];
                $place_of_posting = $existing_data['place_of_posting'];
                $mobile_no = $existing_data['mobile_no'];
                $email_id = $existing_data['email_id'];
            }
        }

        // ✅ Correct insert (11 columns = 11 placeholders)
        $insert = $conn->prepare("
            INSERT INTO users_tbl 
            (emp_id, name, batch, designation, place_of_posting, mobile_no, email_id, password, status, role, serial_no) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $insert->bind_param(
            "sssssssssss",
            $emp_id,
            $name,
            $batch,
            $designation,
            $place_of_posting,
            $mobile_no,
            $email_id,
            $password,
            $status,
            $role,
            $serial_no
        );

        if ($insert->execute()) {
            $alert = "Registration successful! Default password is 1234";
            $alertType = "success";
        } else {
            $alert = "Registration failed!";
            $alertType = "danger";
        }
    }
}


/* ===============================
   USER LOGIN - COMPATIBLE WITH EXISTING CODE
================================= */
if (isset($_POST['login'])) {
    $email_id = $_POST['email_id'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users_tbl WHERE email_id = ?");
    $stmt->bind_param("s", $email_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check status (supports both 'active' string and 1 for active)
        $is_active = ($user['status'] == 'active' || $user['status'] == 1);
        
        if (!$is_active) {
            $alert = "Invalid email or password, or your account is not active!";
            $alertType = "danger";
        } else {
            $db_password = $user['password'];
            $login_success = false;
            
            // Try password_verify first (for hashed passwords)
            if (password_verify($password, $db_password)) {
                $login_success = true;
            }
            // Try direct comparison (for plain text passwords)
            elseif ($password === $db_password) {
                $login_success = true;
            }
            
            if ($login_success) {
                $_SESSION['user_id'] = $user['emp_id'];  // Using emp_id as user_id
                $_SESSION['emp_id'] = $user['emp_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email_id'];
                $_SESSION['user_role'] = $user['role'];
                
                header("Location: controller/dashboard.php");
                exit();
            } else {
                $alert = "Invalid email or password, or your account is not active!";
                $alertType = "danger";
            }
        }
    } else {
        $alert = "Invalid email or password, or your account is not active!";
        $alertType = "danger";
    }
    $stmt->close();
}

/* ===============================
   FAST CHECK EXISTING EMPLOYEE (OPTIMIZED)
================================= */
if (isset($_GET['check_emp']) && isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];
    
    // FAST QUERY: Only fetch necessary columns and use index if available
    $check_stmt = $conn->prepare("
        SELECT name, designation, place_of_posting, mobile_no, email_id 
        FROM users_tbl 
        WHERE emp_id = ? 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $check_stmt->bind_param("s", $emp_id);
    
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            'exists' => true,
            'name' => $user['name'],
            'designation' => $user['designation'],
            'place_of_posting' => $user['place_of_posting'],
            'mobile_no' => $user['mobile_no'],
            'email_id' => $user['email_id']
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }
    exit;
}

/* ===============================
   CHECK CERTIFICATE VALIDITY
================================= */
if (isset($_GET['check_certificate']) && isset($_GET['serial_no'])) {
    $serial_no = $_GET['serial_no'];
    
    // Check if serial number exists in users_tbl
    $check_cert = $conn->prepare("
        SELECT u.*, a.training_title, a.start_date, a.end_date, a.organized_by
        FROM users_tbl u
        LEFT JOIN authority_tbl a ON u.batch = a.batch
        WHERE u.serial_no = ?
    ");
    $check_cert->bind_param("s", $serial_no);
    $check_cert->execute();
    $result = $check_cert->get_result();
    
    if ($result->num_rows > 0) {
        $cert_data = $result->fetch_assoc();
        echo json_encode([
            'valid' => true,
            'serial_no' => $cert_data['serial_no'],
            'participant_name' => $cert_data['name'],
            'designation' => $cert_data['designation'],
            'place_of_posting' => $cert_data['place_of_posting'],
            'batch' => $cert_data['batch'],
            'training_title' => $cert_data['training_title'],
            'start_date' => date('d F Y', strtotime($cert_data['start_date'])),
            'end_date' => date('d F Y', strtotime($cert_data['end_date'])),
            'organized_by' => $cert_data['organized_by'],
            'issue_date' => date('d F Y', strtotime($cert_data['created_at'] ?? date('Y-m-d')))
        ]);
    } else {
        echo json_encode([
            'valid' => false,
            'message' => 'Certificate not found. Invalid serial number.'
        ]);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Training Certificate Generation System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body { background: #f8f9fa; }
.auth-container { max-width: 600px; margin: 30px auto; padding: 25px; border-radius: 10px; background: #fff; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
.auth-header { text-align: center; margin-bottom: 10px; }
.auth-icon { font-size: 3rem; color: #0d6efd; margin-bottom: 5px; }
.password-toggle { cursor: pointer; }
.smart-search-btn { transition: all 0.3s ease; }
.smart-search-btn:hover { transform: translateY(-2px); }
.email-readonly { background-color: #f8f9fa; cursor: not-allowed; }
.email-editable { background-color: #fff; }
.email-status { font-size: 0.8rem; margin-top: 2px; }
.cert-check-btn { position: fixed; bottom: 20px; right: 20px; z-index: 1000; }
</style>
</head>
<body>

<!-- Certificate Check Button (Fixed at bottom right) -->
<button type="button" class="btn btn-warning cert-check-btn rounded-circle" style="width: 60px; height: 60px;" onclick="openCertificateCheck()" title="Check Certificate Validity">
    <i class="bi bi-award-fill"></i>
</button>

<div class="container">

<!-- Alerts -->
<?php if (!empty($alert)): ?>
<div class="alert alert-<?= $alertType ?> alert-dismissible fade show mt-3">
    <?= $alert ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Login Form -->
<div class="auth-container border border-primary border-2 shadow rounded" id="login-form">
    <div class="auth-header">
        <i class="bi bi-person-check auth-icon"></i>
        <h3>User Login</h3>
        <p class="text-muted">Sign in to your account</p>
    </div>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control" name="email_id" required placeholder="Enter Email ID">
        </div>
        <div class="mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="password" class="form-control" id="login_password" name="password" placeholder="Enter Password" required>
                <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('login_password')">
                    <i class="bi bi-eye"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100" name="login"><i class="fa fa-sign-in"></i> Login</button>
        <div class="text-center mt-3">
            <p>Available Training
            <a href="#" onclick="event.preventDefault(); loadTrainingList(event)">Click Here</a></p>
        </div>
    </form>
</div>

<!-- Registration Form -->
<div class="auth-container border border-primary" id="registration-form" style="display:none;">
    <div class="auth-header">
        <i class="bi bi-person-plus auth-icon"></i>
        <h3>User Registration</h3>
        <p class="text-muted" id="reg_training_info">Please fill your personal info.</p>
    </div>

    <form method="POST" id="registrationForm">
        <div class="row mb-3">        
            <!-- Batch Field -->
            <div class="col-md-6">
                <label class="form-label">Batch</label>
                <input type="text" class="form-control" name="batch" id="reg_batch" readonly>
            </div> 
            <div class="col-md-6">
                <label class="form-label">Employee ID</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Enter Employee ID">
                    <button type="button" class="btn btn-outline-primary smart-search-btn" id="searchBtn" onclick="checkExistingEmployee()" title="Click to search for existing employee">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </div>           
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Designation <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="designation" id="designation" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Office/Organization <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="place_of_posting" id="place_of_posting" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="mobile_no" id="mobile_no" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control email-editable" name="email_id" id="email_id" required>
                <div class="email-status" id="emailStatus"></div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="text" class="form-control" value="1234" readonly>
            <div class="form-text">Default password is set to 1234</div>
        </div>
        
        <button type="submit" class="btn btn-primary w-100" name="register"><i class='fa fa-user-plus'></i> Register</button>
        
        <div class="text-center mt-3">
            <p>Already have an account? <a href="#" onclick="event.preventDefault(); showLoginForm()">Login here</a></p>
        </div>
    </form>
</div>

<!-- Training List Modal -->
<div class="modal fade" id="trainingModal" tabindex="-1" aria-labelledby="trainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase text-muted" id="trainingModalLabel">Available Trainings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="trainingList"></div>
    </div>
  </div>
</div>

<!-- Certificate Check Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase text-muted" id="certificateModalLabel">Certificate Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="certificateCheckBody">
        <div class="text-center mb-4">
          <i class="bi bi-award-fill text-warning" style="font-size: 4rem;"></i>
          <h4 class="mt-3">Verify Certificate Authenticity</h4>
          <p class="text-muted">Enter the certificate serial number to verify</p>
        </div>
        
        <div class="mb-4">
          <label for="certificateSerial" class="form-label fw-bold">Certificate Serial Number</label>
          <div class="input-group">
            <input type="text" class="form-control form-control-lg" id="certificateSerial" placeholder="Enter serial number (e.g., BCIC-ICT-DIVISION-B101-1)">
            <button class="btn btn-primary btn-lg" type="button" onclick="checkCertificate()">
              <i class="bi bi-search"></i> Verify
            </button>
          </div>
          <div class="form-text">Example: BCIC-ICT-DIVISION-B101-1</div>
        </div>
        
        <div id="certificateResult" class="mt-4">
          <!-- Results will be shown here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Password toggle
function togglePasswordVisibility(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye','bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash','bi-eye');
    }
}

// Show/hide forms
function showRegisterForm() {
    document.getElementById("login-form").style.display = "none";
    document.getElementById("registration-form").style.display = "block";
    resetFormToEditable();
    resetSearchButton();
}
function showLoginForm() {
    document.getElementById("registration-form").style.display = "none";
    document.getElementById("login-form").style.display = "block";
}

// Load training list via AJAX
function loadTrainingList(e) {
    if (e && e.preventDefault) e.preventDefault();
    const target = document.getElementById("trainingList");
    target.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border" role="status"></div>
            <div class="mt-2">Loading trainings…</div>
        </div>`;
    fetch("controller/training_list.php", { cache: "no-store" })
        .then(r => r.text())
        .then(html => {
            target.innerHTML = html;
            new bootstrap.Modal(document.getElementById('trainingModal')).show();
        })
        .catch(err => {
            console.error(err);
            target.innerHTML = `<div class="alert alert-danger">Failed to load trainings.</div>`;
            new bootstrap.Modal(document.getElementById('trainingModal')).show();
        });
}

// Open certificate check modal
function openCertificateCheck() {
    // Clear previous results
    document.getElementById('certificateResult').innerHTML = '';
    document.getElementById('certificateSerial').value = '';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('certificateModal'));
    modal.show();
    
    // Focus on input field
    setTimeout(() => {
        document.getElementById('certificateSerial').focus();
    }, 500);
}

// Check certificate validity
function checkCertificate() {
    const serialNo = document.getElementById('certificateSerial').value.trim();
    const resultDiv = document.getElementById('certificateResult');
    
    if (!serialNo) {
        resultDiv.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Please enter a certificate serial number.
            </div>
        `;
        return;
    }
    
    // Show loading
    resultDiv.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-2">Verifying certificate...</div>
        </div>
    `;
    
    // Make AJAX request
    fetch(`?check_certificate=1&serial_no=${encodeURIComponent(serialNo)}`, {
        cache: 'no-store'
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-3" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="mb-1"><i class="bi bi-shield-check"></i> Certificate Verified!</h5>
                            <p class="mb-0">This certificate is valid and authentic.</p>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Certificate Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Serial Number:</strong><br>${data.serial_no}</p>
                                <p><strong>Participant Name:</strong><br>${data.participant_name}</p>
                                <p><strong>Designation:</strong><br>${data.designation}</p>
                                <p><strong>Office/Organization:</strong><br>${data.place_of_posting}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Batch:</strong><br>${data.batch}</p>
                                <p><strong>Training Title:</strong><br>${data.training_title}</p>
                                <p><strong>Training Period:</strong><br>${data.start_date} to ${data.end_date}</p>
                                <p><strong>Issued By:</strong><br>${data.organized_by}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center text-muted small">
                            <i class="bi bi-clock"></i> Verified on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle-fill me-3" style="font-size: 2rem;"></i>
                        <div>
                            <h5 class="mb-1"><i class="bi bi-shield-exclamation"></i> Certificate Not Found</h5>
                            <p class="mb-0">${data.message}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Warning</h6>
                    </div>
                    <div class="card-body">
                        <p>The serial number <strong>"${serialNo}"</strong> does not exist in our database.</p>
                        <p class="text-danger mb-0"><i class="bi bi-info-circle"></i> This certificate may be forged or invalid.</p>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Certificate check error:', error);
        resultDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error verifying certificate. Please try again.
            </div>
        `;
    });
}

// Allow Enter key to trigger certificate check
document.getElementById('certificateSerial')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        checkCertificate();
    }
});

// Called from training_list.php "Register here"
function closeModalAndShowRegister(batch = '', title = '', start = '', end = '', organized = '') {
    const modalEl = document.getElementById('trainingModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    showRegisterForm();
    document.getElementById('registration-form').scrollIntoView({ behavior: 'smooth' });

    const infoEl = document.getElementById('reg_training_info');
    if (infoEl) {
        infoEl.innerHTML = `
            Title: ${title} <br>
            Date: ${start} to ${end} <br>
            Organized by: ${organized}
        `;
    }

    const batchInput = document.getElementById('reg_batch');
    if (batchInput) batchInput.value = batch;
}

// Reset search button to default state
function resetSearchButton() {
    const searchBtn = document.getElementById('searchBtn');
    searchBtn.innerHTML = '<i class="bi bi-search"></i> Search';
    searchBtn.classList.remove('btn-warning', 'btn-success', 'btn-danger');
    searchBtn.classList.add('btn-outline-primary');
    searchBtn.disabled = false;
}

// Reset form to editable state
function resetFormToEditable() {
    document.getElementById('name').value = '';
    document.getElementById('designation').value = '';
    document.getElementById('place_of_posting').value = '';
    document.getElementById('mobile_no').value = '';
    document.getElementById('email_id').value = '';
    
    const emailField = document.getElementById('email_id');
    emailField.readOnly = false;
    emailField.classList.remove('email-readonly');
    emailField.classList.add('email-editable');
    document.getElementById('emailStatus').textContent = '';
}

// Make email field read-only (only when auto-filled)
function makeEmailReadOnly() {
    const emailField = document.getElementById('email_id');
    emailField.readOnly = true;
    emailField.classList.remove('email-editable');
    emailField.classList.add('email-readonly');
    document.getElementById('emailStatus').innerHTML = '<span class="text-success"><i class="bi bi-lock-fill"></i> Email locked</span>';
}

// Make email field editable
function makeEmailEditable() {
    const emailField = document.getElementById('email_id');
    emailField.readOnly = false;
    emailField.classList.remove('email-readonly');
    emailField.classList.add('email-editable');
    document.getElementById('emailStatus').textContent = '';
}

// Check existing employee (MANUAL SEARCH ONLY)
function checkExistingEmployee() {
    const empId = document.getElementById('emp_id').value.trim();
    const searchBtn = document.getElementById('searchBtn');
    
    if (!empId) {
        alert('Please enter Employee ID');
        document.getElementById('emp_id').focus();
        return;
    }
    
    searchBtn.innerHTML = '<i class="bi bi-hourglass"></i> Searching...';
    searchBtn.classList.remove('btn-outline-primary', 'btn-success', 'btn-danger');
    searchBtn.classList.add('btn-warning');
    searchBtn.disabled = true;
    
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 5000);
    
    fetch(`?check_emp=1&emp_id=${encodeURIComponent(empId)}`, {
        signal: controller.signal,
        cache: 'no-store'
    })
    .then(response => {
        clearTimeout(timeoutId);
        return response.json();
    })
    .then(data => {
        if (data.exists) {
            document.getElementById('name').value = data.name || '';
            document.getElementById('designation').value = data.designation || '';
            document.getElementById('place_of_posting').value = data.place_of_posting || '';
            document.getElementById('mobile_no').value = data.mobile_no || '';
            document.getElementById('email_id').value = data.email_id || '';
            
            makeEmailReadOnly();
            
            searchBtn.innerHTML = '<i class="bi bi-check-circle"></i> Found';
            searchBtn.classList.remove('btn-warning');
            searchBtn.classList.add('btn-success');
            
            document.getElementById('name').focus();
            
        } else {
            searchBtn.innerHTML = '<i class="bi bi-x-circle"></i> Not Found';
            searchBtn.classList.remove('btn-warning');
            searchBtn.classList.add('btn-danger');
            
            makeEmailEditable();
            document.getElementById('name').focus();
            
            setTimeout(() => {
                resetSearchButton();
            }, 3000);
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Search error:', error);
        
        if (error.name === 'AbortError') {
            searchBtn.innerHTML = '<i class="bi bi-clock"></i> Timeout';
            alert('Search took too long. Please try again.');
        } else {
            searchBtn.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Error';
            alert('Error checking employee record. Please try again.');
        }
        
        searchBtn.classList.remove('btn-warning');
        searchBtn.classList.add('btn-danger');
        
        setTimeout(() => {
            resetSearchButton();
        }, 3000);
    })
    .finally(() => {
        searchBtn.disabled = false;
    });
}

// Clear form when switching to new registration
document.getElementById('registrationForm')?.addEventListener('reset', function() {
    resetFormToEditable();
});

// Clear form when starting new registration from training list
function clearFormFields() {
    document.getElementById('emp_id').value = '';
    resetFormToEditable();
    resetSearchButton();
}

// Update closeModalAndShowRegister to clear form
const originalCloseModalAndShowRegister = closeModalAndShowRegister;
closeModalAndShowRegister = function(batch = '', title = '', start = '', end = '', organized = '') {
    originalCloseModalAndShowRegister(batch, title, start, end, organized);
    clearFormFields();
};
</script>
</body>
</html>