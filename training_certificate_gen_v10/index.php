<?php
session_name('training_certificate_gen_db');
session_start();
include 'controller/db.php'; // Adjust if needed

// Add cache control headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Wed, 11 Jan 1984 05:00:00 GMT");

/* ===============================
   GET AVAILABLE TRAININGS
================================= */
function getAvailableTrainings($conn) {
    $current_date = date('Y-m-d');
    $trainings = [];
    
    $sql = "SELECT * FROM authority_tbl 
            WHERE end_date >= ? and tr_link_status='active' 
            ORDER BY start_date ASC ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $current_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $trainings[] = $row;
    }
    
    return $trainings;
}

// Get training count for badge
$available_trainings = getAvailableTrainings($conn);
$training_count = count($available_trainings);

/* ===============================
   USER REGISTRATION
================================= */
if (isset($_POST['register'])) {

    $emp_id = $_POST['emp_id'] ?? '';
 
     $name = strtoupper(trim($_POST['name'] ?? ''));

    $batch = $_POST['batch'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $place_of_posting = $_POST['place_of_posting'] ?? '';
    $mobile_no = $_POST['mobile_no'] ?? '';
    $email_id = $_POST['email_id'] ?? '';

    $password = password_hash('1234', PASSWORD_DEFAULT);
    $status = 'active';
    $role = 'user';

    // Generate serial_no
    $sql = "SELECT MAX(id) AS maxid FROM users_tbl";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $maxid = $row['maxid'] ?? 0;
    $maxid = $maxid + 1;

    $serial_no = "BCIC-ICT-DIVISION-B{$batch}-{$maxid}";
    
    //$serial_no = "BCIC-ICT-DIVISION-B{$batch-1}-{$maxid}";

    // Check duplicate for same batch (email or mobile)
    // $check = $conn->prepare("
    //     SELECT * FROM users_tbl 
    //     WHERE batch = ? AND (email_id = ? OR mobile_no = ?)
    // ");
    // $check->bind_param("sss", $batch, $email_id, $mobile_no);
    // $check->execute();
    // $result = $check->get_result();

    // Check duplicate for same batch (email or mobile or employee id)

    $check = $conn->prepare("
    SELECT * FROM users_tbl 
    WHERE batch = ? 
    AND (email_id = ? OR mobile_no = ? OR emp_id = ?)
    ");

    $check->bind_param("ssss", $batch, $email_id, $mobile_no, $emp_id);

    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $alert = "User with this email or mobile  already registered for this batch!";
        $alertType = "danger";
    } else {

        // // Check if emp_id exists in other batches to auto-fill info
        // if (!empty($emp_id)) {
        //     $existing_user = $conn->prepare("
        //         SELECT * FROM users_tbl 
        //         WHERE emp_id = ? 
        //         ORDER BY id DESC 
        //         LIMIT 1
        //     ");
        //     $existing_user->bind_param("s", $emp_id);
        //     $existing_user->execute();
        //     $existing_result = $existing_user->get_result();

        //     if ($existing_result->num_rows > 0) {
        //         $existing_data = $existing_result->fetch_assoc();

        //         $name = strtoupper($existing_data['name']);
        //         $designation = $existing_data['designation'];
        //         $place_of_posting = $existing_data['place_of_posting'];
        //         $mobile_no = $existing_data['mobile_no'];
        //         $email_id = $existing_data['email_id'];
        //     }
        // }

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
        $update = $conn->prepare("UPDATE users_tbl SET password=? WHERE email_id=?");
        $update->bind_param("ss", $password, $email_id);
        $update->execute();
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
    
    $stmt = $conn->prepare("SELECT * FROM users_tbl WHERE email_id = ? ");
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
               <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
        }
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
            margin: 10px auto;
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
        
        /* Training Button Badge */
        .training-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            animation: blink 1.5s infinite;
        }
        
        @keyframes blink {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        /* Training Card Styles */
        .training-card {
            border-radius: 10px;
            margin-bottom: 15px;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .training-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .training-card.current {
            border-left: 5px solid #28a745;
            animation: pulse 2s infinite;
        }
        
        .training-card.upcoming {
            border-left: 5px solid #17a2b8;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .status-current {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }
        
        .status-upcoming {
            background: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
    /* Add this to your existing CSS */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }

        /* Fix for modal stacking issue */
        .modal-backdrop.show {
            opacity: 0.5;
        }

        /* Ensure body doesn't get stuck with scrollbar */
        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
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
        <!-- <p>Professional Development & Certificate Management</p> -->
<!--         <div class="d-flex flex-wrap justify-content-center mt-3">
            <span class="feature-badge"><i class="bi bi-check-circle me-1"></i> Digital Certificates</span>
            <span class="feature-badge"><i class="bi bi-check-circle me-1"></i> Easy Registration</span>
            <span class="feature-badge"><i class="bi bi-check-circle me-1"></i> Secure Verification</span>
        </div> -->
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
                <!-- Removed Register Tab -->
                <li class="nav-item" role="presentation" style="position: relative;">
                    <button class="nav-link" id="training-tab" data-bs-toggle="tab" data-bs-target="#training" type="button" role="tab" onclick="openTrainingModal()">
                        <i class="bi bi-calendar-check me-2"></i> Available Trainings
                        <?php if ($training_count > 0): ?>
                        <span class="training-badge"><?= $training_count ?></span>
                        <?php endif; ?>
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="authTabsContent">
                
                <!-- Login Tab (Always Active) -->
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
                
                <!-- Registration Tab (Hidden - Only accessible via modal) -->
                <div class="tab-pane fade" id="register" role="tabpanel" style="display: none;">
                    <!-- Will be shown only via modal -->
                </div>
                
                <!-- Training Tab (Hidden - Only shows modal) -->
                <div class="tab-pane fade" id="training" role="tabpanel">
                    <!-- Empty - Modal will handle content -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Available Trainings Modal -->
<div class="modal fade" id="trainingModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i>Available Training Programs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetToLoginTab()"></button>
      </div>
      <div class="modal-body">
        <?php if ($training_count > 0): ?>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        Total <?= $training_count ?> training program<?= $training_count > 1 ? 's' : '' ?> available
                    </h6>
                    <span class="badge bg-primary"><?= date('d M Y') ?></span>
                </div>
                
                <div class="row" id="trainingCards">
                    <?php 
                    $current_date = date('Y-m-d');
                    foreach ($available_trainings as $training): 
                        $start_date = $training['start_date'];
                        $end_date = $training['end_date'];
                        $is_current = ($current_date >= $start_date && $current_date <= $end_date);
                        $status_class = $is_current ? 'current' : 'upcoming';
                        $status_text = $is_current ? 'Ongoing' : 'Upcoming';
                        $status_badge_class = $is_current ? 'status-current' : 'status-upcoming';
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card training-card <?= $status_class ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge <?= $status_badge_class ?> status-badge">
                                            <i class="bi <?= $is_current ? 'bi-play-circle' : 'bi-clock' ?> me-1"></i>
                                            <?= $status_text ?>
                                        </span>
                                    </div>
                                    <small class="text-muted">Batch: <?= $training['batch'] ?></small>
                                </div>
                                
                                <h6 class="card-title text-primary"><?= htmlspecialchars($training['training_title']) ?></h6>
                                
                                <div class="mb-2">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        <?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-building me-1"></i>
                                        <?= htmlspecialchars($training['organized_by']) ?>
                                    </small>
                                </div>
                                
                                <?php if ($is_current): ?>
                                <div class="alert alert-success py-1 px-3 mb-2" style="font-size: 0.85rem;">
                                    <i class="bi bi-lightning-charge me-1"></i>
                                    <strong>Registration Open!</strong> This training is currently running.
                                </div>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-register w-100" 
                                        onclick="openRegistrationModal('<?= $training['batch'] ?>', '<?= htmlspecialchars($training['training_title']) ?>', '<?= $start_date ?>', '<?= $end_date ?>')">
                                    <i class="bi bi-person-plus me-2"></i> Register Now
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">No Training Programs Available</h5>
                <p class="text-muted">Check back later for upcoming training programs.</p>
                <button class="btn btn-outline-primary" onclick="loadTrainingList()">
                    <i class="bi bi-arrow-clockwise me-2"></i> Refresh
                </button>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Registration Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeRegistrationAndBackToTraining()"></button>
      </div>
      <div class="modal-body">
        <form method="POST" id="registrationForm">
            <div class="alert alert-info" id="regTrainingInfo">
                <i class="bi bi-info-circle me-2"></i>
                <span id="selectedTrainingText">Please select a training first</span>
            </div>
            
            <div class="row mb-0">
                <div class="col-md-6 mb-0">
                    <label class="form-label">Batch Number</label>
                    <input type="text" class="form-control" name="batch" id="reg_batch" readonly>
                    <div class="form-text small">Auto-filled from selected training</div>
                </div>
                <div class="col-md-6 mb-0">
                    <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Enter Employee ID" required>
                        <button type="button" class="btn btn-outline-primary" onclick="checkExistingEmployee()" id="searchBtn">
                            <i class="bi bi-search"></i> Check
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="resetEmployee()" id="searchBtn">
                            <i class="bi bi-close"></i> Reset
                        </button>
                    </div>
                    <div class="form-text small">Enter existing ID to auto-fill</div>
                </div>
            </div>
            
            <div class="mb-0">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" required placeholder="Enter full name">
            
                <div class="form-text small" id="nameStatus"></div>
            </div>
            
            <div class="row mb-0">
                <div class="col-md-6 mb-0">
                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="designation" id="designation" required placeholder="Enter designation">
                </div>
                <div class="col-md-6 mb-0">
                    <label class="form-label">Organization <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="place_of_posting" id="place_of_posting" required placeholder="Enter office/organization">
                </div>
            </div>
            
            <div class="row mb-0">
                <div class="col-md-6 mb-0">
                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" name="mobile_no" id="mobile_no" required placeholder="Enter mobile number">

                    <div class="form-text small" id="mobileStatus"></div>
                </div>
                <div class="col-md-6 mb-0">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email_id" id="email_id" required placeholder="Enter email address">
                    <div class="form-text small" id="emailStatus"></div>
                </div>
            </div>
            
            <div class="mb-0">
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
</div>

<!-- Certificate Check Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase text-light" id="certificateModalLabel">Certificate Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="certificateCheckBody">
        <div class="text-center mb-1">
          <i class="bi bi-award-fill text-warning" style="font-size: 3rem;"></i>
          <h4 class="mt-1">Verify Certificate Authenticity</h4>
          <p class="text-muted">Enter the certificate serial number (Format: batch-userid)</p>
        </div>
        
        <div class="mb-2">
          <label for="certificateSerial" class="form-label fw-bold">Certificate Serial Number</label>
          <div class="input-group">
            <span class="input-group-text bg-light">BCIC-ICT-DIVISION-B</span>
            <input type="text" class="form-control form-control-lg" id="certificateSerial" placeholder="Enter like: 2-2">
            <button class="btn btn-primary btn-lg" type="button" onclick="checkCertificate()">
              <i class="bi bi-search"></i> Verify
            </button>
          </div>
          <div class="form-text">Example: <code>2-2</code> means Batch 2, User ID 2 (Full: BCIC-ICT-DIVISION-B2-2)</div>
        </div>
        
        <div id="certificateResult" class="mt-2">
          <!-- Results will be shown here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

// Replace the resetToLoginTab function with this:
function resetToLoginTab() {
    document.getElementById('login-tab').click();
    
    // Manually remove modal backdrop if it exists
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
    
    // Remove modal-open class from body
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Also update the closeRegistrationAndBackToTraining function:
function closeRegistrationAndBackToTraining() {
    const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
    if (registerModal) registerModal.hide();
    
    // Manually remove modal backdrop if it exists
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
    
    // Remove modal-open class from body
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Reopen training modal
    setTimeout(() => {
        openTrainingModal();
    }, 300);
}

// Add this function to handle modal hidden event
function setupModalCleanup() {
    const trainingModal = document.getElementById('trainingModal');
    const registerModal = document.getElementById('registerModal');
    
    if (trainingModal) {
        trainingModal.addEventListener('hidden.bs.modal', function () {
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Clean up body classes
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
    
    if (registerModal) {
        registerModal.addEventListener('hidden.bs.modal', function () {
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Clean up body classes
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
}

// Also update the openTrainingModal function:
function openTrainingModal() {
    // Always show login tab first
    document.getElementById('login-tab').click();
    
    // Clean any existing backdrop first
    const existingBackdrop = document.querySelector('.modal-backdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    // Open training modal
    const trainingModal = new bootstrap.Modal(document.getElementById('trainingModal'));
    trainingModal.show();
    
    // Load training list if needed
    loadTrainingList();
}

// Update the openRegistrationModal function:
function openRegistrationModal(batch, title, start, end) {
    // Close training modal
    const trainingModal = bootstrap.Modal.getInstance(document.getElementById('trainingModal'));
    if (trainingModal) {
        trainingModal.hide();
        
        // Clean up backdrop immediately
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
    
    // Small delay to ensure modal is closed
    setTimeout(() => {
        // Open registration modal
        const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        registerModal.show();
        
        // Set training info
        const startDate = new Date(start).toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
        const endDate = new Date(end).toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
        
        document.getElementById('selectedTrainingText').innerHTML = 
            `<strong>${title}</strong><br>${startDate} to ${endDate}`;
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
        
        // Reset search button
        const searchBtn = document.getElementById('searchBtn');
        searchBtn.innerHTML = '<i class="bi bi-search"></i> Check';
        searchBtn.classList.remove('btn-success', 'btn-danger');
        searchBtn.classList.add('btn-outline-primary');
    }, 300);
}

// Add this to your DOMContentLoaded event listener:
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-tab').click();
    setupModalCleanup(); // Initialize modal cleanup
});

// Close registration and go back to training modal
function closeRegistrationAndBackToTraining() {
    const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
    if (registerModal) registerModal.hide();
    
    // Reopen training modal
    setTimeout(() => {
        openTrainingModal();
    }, 300);
}

// Reset to login tab when training modal is closed
function resetToLoginTab() {
    document.getElementById('login-tab').click();
}

// Load training list
function loadTrainingList() {
    // This function is now handled by PHP directly
    // You can add AJAX refresh if needed
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
                <i class="bi bi-exclamation-triangle"></i> Please enter a certificate serial number (Format: batch-userid).
            </div>
        `;
        return;
    }
    
    // Validate format: should be like "2-2"
    if (!/^\d+-\d+$/.test(serialNo)) {
        resultDiv.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Invalid format. Please enter like: <code>2-2</code>
            </div>
        `;
        return;
    }
    
    // Show loading
    resultDiv.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-2">Verifying certificate BCIC-ICT-DIVISION-B${serialNo}...</div>
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
                                <p><strong>Short Code:</strong><br>${data.short_serial}</p>
                                <p><strong>Participant Name:</strong><br>${data.participant_name}</p>
                                <p><strong>Designation:</strong><br>${data.designation}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Office/Organization:</strong><br>${data.place_of_posting}</p>
                                <p><strong>Batch:</strong><br>${data.batch}</p>
                                <p><strong>Training Title:</strong><br>${data.training_title}</p>
                                <p><strong>Training Period:</strong><br>${data.start_date} to ${data.end_date}</p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <p><strong>Issued By:</strong><br>${data.organized_by}</p>
                                <p><strong>Issue Date:</strong><br>${data.issue_date}</p>
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
                        <p class="mb-0">Full serial checked: <code>BCIC-ICT-DIVISION-B${serialNo}</code></p>
                        <p class="text-danger mt-2 mb-0"><i class="bi bi-info-circle"></i> This certificate may be forged or invalid.</p>
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

                // Make fields readonly
                document.getElementById('email_id').readOnly = true;
                document.getElementById('name').readOnly = true;
                document.getElementById('mobile_no').readOnly = true;

                // Show lock icon/status for each
                document.getElementById('emailStatus').innerHTML = '<span class="text-success"><i class="bi bi-lock me-1"></i>Locked</span>';
                document.getElementById('nameStatus').innerHTML = '<span class="text-success"><i class="bi bi-lock me-1"></i>Locked</span>';
                document.getElementById('mobileStatus').innerHTML = '<span class="text-success"><i class="bi bi-lock me-1"></i>Locked</span>';

                searchBtn.innerHTML = '<i class="bi bi-check-circle"></i>';
                searchBtn.classList.replace('btn-outline-primary', 'btn-success');
                document.getElementById('name').focus();
            } else {
                searchBtn.innerHTML = '<i class="bi bi-x-circle"></i>';
                searchBtn.classList.replace('btn-outline-primary', 'btn-danger');
                setTimeout(() => {
                    searchBtn.innerHTML = '<i class="bi bi-search"></i> Check';
                    searchBtn.classList.replace('btn-danger', 'btn-outline-primary');

                    // Unlock fields
                    document.getElementById('email_id').readOnly = false;
                    document.getElementById('name').readOnly = false;
                    document.getElementById('mobile_no').readOnly = false;

                    // Clear lock icons/status
                    document.getElementById('emailStatus').innerHTML = '';
                    document.getElementById('nameStatus').innerHTML = '';
                    document.getElementById('mobileStatus').innerHTML = '';
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

                // Unlock fields
                document.getElementById('email_id').readOnly = false;
                document.getElementById('name').readOnly = false;
                document.getElementById('mobile_no').readOnly = false;

                // Clear lock icons/status
                document.getElementById('emailStatus').innerHTML = '';
                document.getElementById('nameStatus').innerHTML = '';
                document.getElementById('mobileStatus').innerHTML = '';
            }, 2000);
        })
        .finally(() => {
            searchBtn.disabled = false;
        });
}

// Reset employee form
function resetEmployee() {
    document.getElementById('emp_id').value = '';
    document.getElementById('name').value = '';
    document.getElementById('designation').value = '';
    document.getElementById('place_of_posting').value = '';
    document.getElementById('mobile_no').value = '';
    document.getElementById('email_id').value = '';

    // Unlock fields
    document.getElementById('email_id').readOnly = false;
    document.getElementById('name').readOnly = false;
    document.getElementById('mobile_no').readOnly = false;

    // Clear lock icons/status
    document.getElementById('emailStatus').innerHTML = '';
    document.getElementById('nameStatus').innerHTML = '';
    document.getElementById('mobileStatus').innerHTML = '';

    const searchBtn = document.getElementById('searchBtn');
    searchBtn.innerHTML = '<i class="bi bi-search"></i> Check';
    searchBtn.classList.remove('btn-success', 'btn-danger');
    searchBtn.classList.add('btn-outline-primary');
    searchBtn.disabled = false;

    document.getElementById('emp_id').focus();
}



// Prevent training tab from showing content
document.getElementById('training-tab').addEventListener('click', function(e) {
    e.preventDefault();
    openTrainingModal();
});

// Enter key for certificate
document.getElementById('certificateSerial')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') checkCertificate();
});

// On page load, ensure login tab is active
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-tab').click();
});
</script>
</body>
</html>