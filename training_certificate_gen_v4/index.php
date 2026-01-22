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
    $input = $_GET['serial_no'];
    
    // Extract batch and user_id from input like "2-2"
    $parts = explode('-', $input);
    
    if (count($parts) >= 2) {
        $batch = $parts[0];
        $user_id = $parts[1];
        
        // Construct the full serial number to search
        $full_serial_no = "BCIC-ICT-DIVISION-B{$batch}-{$user_id}";
        
        // Check if serial number exists in users_tbl
        $check_cert = $conn->prepare("
            SELECT u.*, a.training_title, a.start_date, a.end_date, a.organized_by
            FROM users_tbl u
            LEFT JOIN authority_tbl a ON u.batch = a.batch
            WHERE u.serial_no = ?
        ");
        $check_cert->bind_param("s", $full_serial_no);
        $check_cert->execute();
        $result = $check_cert->get_result();
        
        if ($result->num_rows > 0) {
            $cert_data = $result->fetch_assoc();
            echo json_encode([
                'valid' => true,
                'serial_no' => $cert_data['serial_no'],
                'short_serial' => $input,
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
    } else {
        echo json_encode([
            'valid' => false,
            'message' => 'Invalid format. Please enter like: 2-2'
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
    <title>BCIC Training System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --light-color: #ecf0f1;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .login-card {
            max-width: 450px;
            margin: 40px auto;
        }
        
        .register-card {
            max-width: 800px;
            margin: 20px auto;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
            border-bottom: none;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #219653);
            border: none;
            border-radius: 10px;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            border: none;
            border-radius: 10px;
        }
        
        .certificate-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
            transition: all 0.3s;
        }
        
        .certificate-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(243, 156, 18, 0.6);
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .logo {
            height: 50px;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .nav-tabs {
            border: none;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 5px;
            margin-bottom: 30px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }
        
        .tab-content {
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
        }
        
        .password-toggle {
            cursor: pointer;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-left: none;
        }
        
        .welcome-text {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        
        .welcome-text h1 {
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .welcome-text p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .feature-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 8px 20px;
            margin: 5px;
            color: white;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px 10px 0 0;
        }
        
        .modal-content {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>

<!-- Certificate Check Button -->
<button type="button" class="btn btn-warning certificate-btn" onclick="openCertificateCheck()" title="Verify Certificate">
    <i class="bi bi-award-fill text-white"></i>
</button>

<div class="main-container">
    
    <!-- Welcome Header -->
    <div class="welcome-text">
        <h1><i class="bi bi-mortarboard-fill me-2"></i>BCIC Training Certificate System</h1>
        <p>Professional Development & Certificate Management</p>
        <div class="d-flex flex-wrap justify-content-center mt-3">
            <span class="feature-badge"><i class="bi bi-check-circle me-1"></i> Digital Certificates</span>
            <span class="feature-badge"><i class="bi bi-check-circle me-1"></i> Easy Registration</span>
            <span class="feature-badge"><i class="bi bi-check-circle me-1"></i> Secure Verification</span>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (!empty($alert)): ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-<?= $alertType ?> alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <i class="bi <?= $alertType == 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-3 fs-5"></i>
                    <div>
                        <strong><?= $alertType == 'success' ? 'Success!' : 'Notice!' ?></strong>
                        <div class="mt-1"><?= $alert ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Logo -->
    <!-- <div class="logo-container">
        <img src="../logo/bdlogo.png" alt="Bangladesh Logo" class="logo">
        <img src="../logo/bcic_logo.png" alt="BCIC Logo" class="logo">
    </div> -->

    <!-- Main Tabs -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <ul class="nav nav-tabs justify-content-center" id="authTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                        <i class="bi bi-person-plus me-2"></i> Register
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="training-tab" data-bs-toggle="tab" data-bs-target="#training" type="button" role="tab">
                        <i class="bi bi-calendar-check me-2"></i> Trainings
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="authTabsContent">
                
                <!-- Login Tab -->
                <div class="tab-pane fade show active" id="login" role="tabpanel">
                    <div class="card login-card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="bi bi-person-check me-2"></i>User Login</h4>
                            <p class="mb-0 opacity-75">Sign in to your account</p>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email_id" required placeholder="Enter your email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="login_password" name="password" placeholder="Enter password" required>
                                        <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('login_password')">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                    <div class="form-text small">Default password: 1234 for new users</div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2" name="login">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Registration Tab -->
                <div class="tab-pane fade" id="register" role="tabpanel">
                    <div class="card register-card">
                        <div class="card-header">
                            <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>New Registration</h4>
                            <p class="mb-0 opacity-75" id="reg_training_info">Select training first from Trainings tab</p>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="registrationForm">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Batch Number</label>
                                        <input type="text" class="form-control" name="batch" id="reg_batch" readonly>
                                        <div class="form-text small">Auto-filled from selected training</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Enter Employee ID">
                                            <button type="button" class="btn btn-outline-primary" onclick="checkExistingEmployee()" id="searchBtn">
                                                <i class="bi bi-search"></i> Check
                                            </button>
                                        </div>
                                        <div class="form-text small">Enter existing ID to auto-fill</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" required placeholder="Enter full name">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="designation" id="designation" required placeholder="Enter designation">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Office/Organization <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="place_of_posting" id="place_of_posting" required placeholder="Enter office/organization">
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="mobile_no" id="mobile_no" required placeholder="Enter mobile number">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_id" id="email_id" required placeholder="Enter email address">
                                        <div class="form-text small" id="emailStatus"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" value="1234" readonly>
                                    <div class="form-text small">Default password is 1234</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 py-2" name="register">
                                    <i class="bi bi-person-plus me-2"></i> Complete Registration
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Training Tab -->
                <div class="tab-pane fade" id="training" role="tabpanel">
                    <div class="text-center mb-4">
                        <h4 class="text-primary"><i class="bi bi-calendar-check me-2"></i>Available Training Programs</h4>
                        <p class="text-muted">Select a training to register</p>
                    </div>
                    
                    <div id="trainingList" class="text-center p-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading trainings...</p>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="loadTrainingList()">
                            <i class="bi bi-arrow-clockwise me-2"></i> Refresh List
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Training List Modal -->
<div class="modal fade" id="trainingModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i>Available Trainings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="trainingModalBody"></div>
    </div>
  </div>
</div>

<!-- Certificate Check Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-award me-2"></i>Verify Certificate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Serial Number</label>
            <div class="input-group">
                <span class="input-group-text">BCIC-ICT-DIVISION-B</span>
                <input type="text" class="form-control" id="certificateSerial" placeholder="2-2">
                <button class="btn btn-primary" type="button" onclick="checkCertificate()">
                    <i class="bi bi-search"></i> Verify
                </button>
            </div>
            <div class="form-text small">Enter like: 2-2 (Batch-UserID)</div>
        </div>
        
        <div id="certificateResult"></div>
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

// Load training list
function loadTrainingList() {
    const target = document.getElementById('trainingList');
    target.innerHTML = `
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Loading trainings...</p>
    `;
    
    fetch("controller/training_list.php", { cache: "no-store" })
        .then(r => r.text())
        .then(html => {
            document.getElementById('trainingModalBody').innerHTML = html;
            // Switch to registration tab
            new bootstrap.Tab(document.getElementById('register-tab')).show();
            new bootstrap.Modal(document.getElementById('trainingModal')).show();
        })
        .catch(err => {
            console.error(err);
            document.getElementById('trainingModalBody').innerHTML = `<div class="alert alert-danger">Failed to load trainings.</div>`;
            new bootstrap.Modal(document.getElementById('trainingModal')).show();
        });
}

// Open certificate check
function openCertificateCheck() {
    document.getElementById('certificateResult').innerHTML = '';
    document.getElementById('certificateSerial').value = '';
    new bootstrap.Modal(document.getElementById('certificateModal')).show();
    setTimeout(() => document.getElementById('certificateSerial').focus(), 500);
}

// Check certificate
function checkCertificate() {
    const serialNo = document.getElementById('certificateSerial').value.trim();
    const resultDiv = document.getElementById('certificateResult');
    
    if (!serialNo) {
        resultDiv.innerHTML = `<div class="alert alert-warning">Please enter serial number.</div>`;
        return;
    }
    
    if (!/^\d+-\d+$/.test(serialNo)) {
        resultDiv.innerHTML = `<div class="alert alert-warning">Enter like: 2-2</div>`;
        return;
    }
    
    resultDiv.innerHTML = `<div class="text-center"><div class="spinner-border text-primary"></div><p>Verifying...</p></div>`;
    
    fetch(`?check_certificate=1&serial_no=${encodeURIComponent(serialNo)}`)
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h6><i class="bi bi-check-circle me-2"></i>Valid Certificate</h6>
                        <p class="mb-1"><strong>Name:</strong> ${data.participant_name}</p>
                        <p class="mb-1"><strong>Training:</strong> ${data.training_title}</p>
                        <p class="mb-1"><strong>Period:</strong> ${data.start_date} to ${data.end_date}</p>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-x-circle me-2"></i>Invalid Certificate</h6>
                        <p>${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error(error);
            resultDiv.innerHTML = `<div class="alert alert-danger">Verification failed.</div>`;
        });
}

// Check existing employee
function checkExistingEmployee() {
    const empId = document.getElementById('emp_id').value.trim();
    const searchBtn = document.getElementById('searchBtn');
    
    if (!empId) {
        alert('Please enter Employee ID');
        document.getElementById('emp_id').focus();
        return;
    }
    
    searchBtn.innerHTML = '<i class="bi bi-hourglass"></i>';
    searchBtn.disabled = true;
    
    fetch(`?check_emp=1&emp_id=${encodeURIComponent(empId)}`)
        .then(r => r.json())
        .then(data => {
            if (data.exists) {
                document.getElementById('name').value = data.name || '';
                document.getElementById('designation').value = data.designation || '';
                document.getElementById('place_of_posting').value = data.place_of_posting || '';
                document.getElementById('mobile_no').value = data.mobile_no || '';
                document.getElementById('email_id').value = data.email_id || '';
                document.getElementById('email_id').readOnly = true;
                document.getElementById('emailStatus').innerHTML = '<span class="text-success"><i class="bi bi-lock me-1"></i>Locked</span>';
                searchBtn.innerHTML = '<i class="bi bi-check-circle"></i>';
                searchBtn.classList.replace('btn-outline-primary', 'btn-success');
                document.getElementById('name').focus();
            } else {
                searchBtn.innerHTML = '<i class="bi bi-x-circle"></i>';
                searchBtn.classList.replace('btn-outline-primary', 'btn-danger');
                setTimeout(() => {
                    searchBtn.innerHTML = '<i class="bi bi-search"></i> Check';
                    searchBtn.classList.replace('btn-danger', 'btn-outline-primary');
                }, 2000);
            }
        })
        .catch(error => {
            console.error(error);
            searchBtn.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
            searchBtn.classList.replace('btn-outline-primary', 'btn-danger');
            setTimeout(() => {
                searchBtn.innerHTML = '<i class="bi bi-search"></i> Check';
                searchBtn.classList.replace('btn-danger', 'btn-outline-primary');
            }, 2000);
        })
        .finally(() => {
            searchBtn.disabled = false;
        });
}

// Called from training_list.php
function closeModalAndShowRegister(batch = '', title = '', start = '', end = '', organized = '') {
    const modal = bootstrap.Modal.getInstance(document.getElementById('trainingModal'));
    if (modal) modal.hide();
    
    new bootstrap.Tab(document.getElementById('register-tab')).show();
    
    const infoEl = document.getElementById('reg_training_info');
    if (infoEl) {
        infoEl.innerHTML = `<strong>${title}</strong><br>${start} to ${end}`;
    }
    
    document.getElementById('reg_batch').value = batch;
    
    // Clear form
    document.getElementById('emp_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('designation').value = '';
    document.getElementById('place_of_posting').value = '';
    document.getElementById('mobile_no').value = '';
    document.getElementById('email_id').value = '';
    document.getElementById('email_id').readOnly = false;
    document.getElementById('emailStatus').textContent = '';
}

// Auto-load training list
document.getElementById('training-tab').addEventListener('shown.bs.tab', loadTrainingList);

// Enter key for certificate
document.getElementById('certificateSerial')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') checkCertificate();
});

// Load on page load
document.addEventListener('DOMContentLoaded', loadTrainingList);
</script>
</body>
</html>