<?php
// work_request.php
session_name('factory_work_request_db');
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$designation = $_SESSION['designation'];
$user_division = $_SESSION['division']; // Changed variable name to avoid conflict
$user_section = $_SESSION['section'];   // Changed variable name to avoid conflict

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $w_req_type = $_POST['w_req_type'] ?? '';
    $w_location = trim($_POST['w_location'] ?? '');
    $w_description = trim($_POST['w_description'] ?? '');
    $w_com_division = $_POST['w_com_division'] ?? '';
    $w_com_section = trim($_POST['w_com_section'] ?? '');
    $status = $_POST['status'] ?? 'normal';
    $remarks = trim($_POST['remarks'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($date)) {
        $errors[] = "Date is required";
    } else {
        // Check if date is not in the future
        $selected_date = strtotime($date);
        $today = strtotime(date('Y-m-d'));
        if ($selected_date > $today) {
            $errors[] = "Date cannot be in the future";
        }
    }
    
    if (empty($w_req_type)) {
        $errors[] = "Work request type is required";
    }
    
    if (empty($w_location)) {
        $errors[] = "Location is required";
    } elseif (strlen($w_location) < 3) {
        $errors[] = "Location must be at least 3 characters";
    }
    
    if (empty($w_description)) {
        $errors[] = "Description is required";
    } elseif (strlen($w_description) < 10) {
        $errors[] = "Description must be at least 10 characters";
    }
    
    if (empty($w_com_division)) {
        $errors[] = "Concerned division is required";
    }
    
    if (empty($w_com_section)) {
        $errors[] = "Concerned section is required";
    }
    
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Prepare SQL statement
        $sql = "INSERT INTO work_request_tbl (
            date, w_req_type, w_location, w_description, 
            w_com_division, w_com_section, status, remarks,
            requester_id, full_name, designation, division, section
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param(
                "ssssssssissss",
                $date, $w_req_type, $w_location, $w_description,
                $w_com_division, $w_com_section, $status, $remarks,
                $user_id, $full_name, $designation, $user_division, $user_section
            );
            
            if ($stmt->execute()) {
                $work_request_id = $stmt->insert_id;
                $message = "Work request submitted successfully! Request ID: WR-" . str_pad($work_request_id, 6, '0', STR_PAD_LEFT);
                
                // Clear form
                $_POST = array();
            } else {
                $error = "Failed to submit work request: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Get divisions for dropdown (moved before HTML output)
$divisions = [];
$sections = [];

$sql_div = "SELECT division FROM division";
$result_div = $conn->query($sql_div);
if ($result_div && $result_div->num_rows > 0) {
    while ($row = $result_div->fetch_assoc()) {
        $divisions[] = $row['division'];
    }
}

$sql_sec = "SELECT name FROM section";
$result_sec = $conn->query($sql_sec);
if ($result_sec && $result_sec->num_rows > 0) {
    while ($row = $result_sec->fetch_assoc()) {
        $sections[] = $row['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request Form</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --info-color: #7209b7;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .header-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.1;
        }
        
        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-1px);
        }
        
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .required::after {
            content: " *";
            color: var(--danger-color);
        }
        
        .urgency-badge {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        
        .urgency-badge:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        
        .urgency-badge.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1) 0%, rgba(58, 12, 163, 0.1) 100%);
        }
        
        .badge-normal {
            color: #4299e1;
            border-color: #4299e1;
        }
        
        .badge-urgent {
            color: #ed8936;
            border-color: #ed8936;
        }
        
        .badge-very-urgent {
            color: #f56565;
            border-color: #f56565;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 14px 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            background: linear-gradient(135deg, #3a56d4 0%, #2e0a8c 100%);
        }
        
        .btn-secondary {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            border-radius: 10px;
            padding: 14px 28px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
            transform: translateY(-2px);
        }
        
        .char-count {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        
        .char-count.warning {
            color: var(--danger-color);
        }
        
        .char-count.success {
            color: #38a169;
        }
        
        .floating-info {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .info-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s;
        }
        
        .info-btn:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
        
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 30px;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .instruction-list {
            list-style: none;
            padding: 0;
        }
        
        .instruction-list li {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .instruction-list li:last-child {
            border-bottom: none;
        }
        
        .instruction-list li i {
            color: var(--primary-color);
            font-size: 18px;
        }
        
        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(76, 201, 240, 0.1) 0%, rgba(67, 97, 238, 0.1) 100%);
            border-left: 4px solid var(--success-color);
            color: #2d3748;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(247, 37, 133, 0.1) 0%, rgba(242, 100, 104, 0.1) 100%);
            border-left: 4px solid var(--danger-color);
            color: #2d3748;
        }
        
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }
        
        @media (max-width: 768px) {
            .floating-info {
                bottom: 20px;
                right: 20px;
            }
            
            .info-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
        
        /* Floating animation for info button */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating-info .info-btn {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Hover effect for form sections */
        .form-section {
            transition: all 0.3s;
        }
        
        .form-section:hover {
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <!-- Info Modal -->
    <div class="modal fade" id="instructionsModal" tabindex="-1" aria-labelledby="instructionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instructionsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Work Request Form Instructions
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-check-circle me-2"></i>Required Fields</h6>
                            <ul class="instruction-list">
                                <li><i class="fas fa-asterisk"></i> All fields marked with * are mandatory</li>
                                <li><i class="fas fa-calendar-alt"></i> Select current or past date only</li>
                                <li><i class="fas fa-tasks"></i> Choose appropriate work type</li>
                                <li><i class="fas fa-map-marker-alt"></i> Provide specific location details</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-lightbulb me-2"></i>Best Practices</h6>
                            <ul class="instruction-list">
                                <li><i class="fas fa-align-left"></i> Write clear, detailed descriptions (min. 10 characters)</li>
                                <li><i class="fas fa-exclamation-triangle"></i> Select urgency level based on actual need</li>
                                <li><i class="fas fa-building"></i> Choose correct completion division/section</li>
                                <li><i class="fas fa-sticky-note"></i> Add remarks for special instructions if needed</li>
                            </ul>
                        </div>
                    </div>
                    <div class="section-divider"></div>
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <i class="fas fa-clock fa-2x me-3 text-info"></i>
                            <div>
                                <h6 class="alert-heading">Processing Time</h6>
                                <p class="mb-0">Requests are typically processed within 24-48 hours. Urgent requests receive priority attention.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-thumbs-up me-2"></i>Got It!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Info Button -->
    <div class="floating-info">
        <button type="button" class="info-btn" data-bs-toggle="modal" data-bs-target="#instructionsModal" title="View Instructions">
            <i class="fas fa-info"></i>
        </button>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Card -->
                <div class="card glass-card border-0 mb-4 overflow-hidden">
                    <div class="card-header header-gradient border-0 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h2 text-white mb-2">
                                    <i class="fas fa-file-alt me-3"></i>Work Request Form
                                </h1>
                                <p class="text-white-50 mb-0">Submit maintenance or service requests with detailed information</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="user-badge d-inline-block px-3 py-2 rounded-pill">
                                    <div class="text-white fw-semibold"><?php echo htmlspecialchars($full_name); ?></div>
                                    <small class="text-white-75 d-block">
                                        <?php echo htmlspecialchars($designation); ?> | 
                                        <?php echo htmlspecialchars($user_division); ?> - 
                                        <?php echo htmlspecialchars($user_section); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if ($message): ?>
                            <div class="alert alert-success alert-custom d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Request Submitted Successfully!</h5>
                                    <p class="mb-0"><?php echo $message; ?></p>
                                    <small class="text-muted">You can track your request in the dashboard.</small>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-custom d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Submission Error</h5>
                                    <p class="mb-0"><?php echo $error; ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Quick Tips -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-bullhorn me-2"></i>Before you begin
                                        </h6>
                                        <small class="text-muted">Ensure you have all necessary information ready</small>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#instructionsModal">
                                        <i class="fas fa-question-circle me-2"></i>View Instructions
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <form id="workRequestForm" method="POST">
                            <!-- Basic Information Section -->
                            <div class="form-section mb-5">
                                <h5 class="mb-4 border-bottom pb-2 text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="date" class="form-label required">
                                            <i class="fas fa-calendar-alt"></i> Date of Request
                                        </label>
                                        <input type="date" class="form-control" id="date" name="date" 
                                               value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>" 
                                               max="<?php echo date('Y-m-d'); ?>" required>
                                        <small class="text-muted mt-1 d-block">Select today's date or earlier</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="w_req_type" class="form-label required">
                                            <i class="fas fa-tasks"></i> Work Request Type
                                        </label>
                                        <select class="form-select" id="w_req_type" name="w_req_type" required>
                                            <option value="">Select Type</option>
                                            <option value="ICT" <?php echo ($_POST['w_req_type'] ?? '') == 'ICT' ? 'selected' : ''; ?>>ICT (Information & Communication Technology)</option>
                                            <option value="Civil" <?php echo ($_POST['w_req_type'] ?? '') == 'Civil' ? 'selected' : ''; ?>>Civil Works</option>
                                            <option value="Transport" <?php echo ($_POST['w_req_type'] ?? '') == 'Transport' ? 'selected' : ''; ?>>Transport</option>
                                            <option value="Electrical" <?php echo ($_POST['w_req_type'] ?? '') == 'Electrical' ? 'selected' : ''; ?>>Electrical</option>
                                            <option value="Mechanical" <?php echo ($_POST['w_req_type'] ?? '') == 'Mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Work Details Section -->
                            <div class="form-section mb-5">
                                <h5 class="mb-4 border-bottom pb-2 text-primary">
                                    <i class="fas fa-clipboard-list me-2"></i>Work Details
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="w_location" class="form-label required">
                                            <i class="fas fa-map-marker-alt"></i> Location of Work
                                        </label>
                                        <input type="text" class="form-control" id="w_location" name="w_location" 
                                               placeholder="e.g., Main Office Building, Floor 3, Room 302, Near Reception"
                                               value="<?php echo htmlspecialchars($_POST['w_location'] ?? ''); ?>" 
                                               required>
                                        <small class="text-muted mt-1 d-block">Be specific about the location</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="w_description" class="form-label required">
                                            <i class="fas fa-align-left"></i> Work Description
                                        </label>
                                        <textarea class="form-control" id="w_description" name="w_description" 
                                                  placeholder="Provide detailed description of the work required. Include specific issues, equipment involved, and any other relevant information..."
                                                  rows="5" required><?php echo htmlspecialchars($_POST['w_description'] ?? ''); ?></textarea>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">Minimum 10 characters required</small>
                                            <small class="char-count" id="charCount">0 characters</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Assignment Section -->
                            <div class="form-section mb-5">
                                <h5 class="mb-4 border-bottom pb-2 text-primary">
                                    <i class="fas fa-user-check me-2"></i>Assignment Details
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="w_com_division" class="form-label required">
                                            <i class="fas fa-building"></i> Work Completion Division
                                        </label>
                                        <select class="form-select" id="w_com_division" name="w_com_division" required>
                                            <option value="">Select Division</option>
                                            <?php 
                                            if (!empty($divisions)) {
                                                foreach ($divisions as $division) {
                                                    $selected = (($_POST['w_com_division'] ?? '') === $division) ? 'selected' : '';
                                                    echo "<option value=\"" . htmlspecialchars($division) . "\" $selected>" . htmlspecialchars($division) . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>No division found</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="w_com_section" class="form-label required">
                                            <i class="fas fa-users"></i> Work Completion Section
                                        </label>
                                        <select class="form-select" id="w_com_section" name="w_com_section" required>
                                            <option value="">Select Section</option>
                                            <?php 
                                            if (!empty($sections)) {
                                                foreach ($sections as $section) {
                                                    $selected = (($_POST['w_com_section'] ?? '') === $section) ? 'selected' : '';
                                                    echo "<option value=\"" . htmlspecialchars($section) . "\" $selected>" . htmlspecialchars($section) . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>No section found</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Urgency Section -->
                            <div class="form-section mb-5">
                                <h5 class="mb-4 border-bottom pb-2 text-primary">
                                    <i class="fas fa-exclamation-circle me-2"></i>Priority Level
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-warning border-warning bg-light mb-4">
                                            <div class="d-flex">
                                                <i class="fas fa-lightbulb text-warning fa-2x me-3"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1">Select Appropriate Urgency</h6>
                                                    <p class="mb-0">Choose based on the impact on operations and safety considerations.</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="urgency-badge badge-normal" for="status_normal">
                                                    <input type="radio" id="status_normal" name="status" value="normal" 
                                                           <?php echo ($_POST['status'] ?? 'normal') == 'normal' ? 'checked' : ''; ?> hidden>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-clock fa-2x mb-2"></i>
                                                        <strong class="mb-1">Normal</strong>
                                                        <small class="text-center text-muted">
                                                            Routine work, can be scheduled within regular timeline
                                                        </small>
                                                    </div>
                                                </label>
                                            </div>
                                            
                                            <div class="col-md-4 mb-3">
                                                <label class="urgency-badge badge-urgent" for="status_urgent">
                                                    <input type="radio" id="status_urgent" name="status" value="urgent" 
                                                           <?php echo ($_POST['status'] ?? '') == 'urgent' ? 'checked' : ''; ?> hidden>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                                        <strong class="mb-1">Urgent</strong>
                                                        <small class="text-center text-muted">
                                                            Affects operations, requires attention within 24 hours
                                                        </small>
                                                    </div>
                                                </label>
                                            </div>
                                            
                                            <div class="col-md-4 mb-3">
                                                <label class="urgency-badge badge-very-urgent" for="status_very_urgent">
                                                    <input type="radio" id="status_very_urgent" name="status" value="very urgent" 
                                                           <?php echo ($_POST['status'] ?? '') == 'very urgent' ? 'checked' : ''; ?> hidden>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-fire fa-2x mb-2"></i>
                                                        <strong class="mb-1">Very Urgent</strong>
                                                        <small class="text-center text-muted">
                                                            Critical issue, needs immediate attention (safety/security)
                                                        </small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="form-section mb-5">
                                <h5 class="mb-4 border-bottom pb-2 text-primary">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Information
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="remarks" class="form-label">
                                            <i class="fas fa-comment-dots"></i> Additional Remarks (Optional)
                                        </label>
                                        <textarea class="form-control" id="remarks" name="remarks" 
                                                  placeholder="Any special instructions, access requirements, preferred timing, or other relevant information..."
                                                  rows="3"><?php echo htmlspecialchars($_POST['remarks'] ?? ''); ?></textarea>
                                        <small class="text-muted mt-1 d-block">Add any extra details that might help with the request</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="form-section">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                            <div class="text-center text-md-start">
                                                <small class="text-muted">
                                                    <i class="fas fa-shield-alt me-1"></i>Your information is secure
                                                </small>
                                            </div>
                                            
                                            <div class="d-flex flex-wrap gap-3">
                                                <a href="dashboard.php" class="btn btn-secondary btn-lg">
                                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-paper-plane me-2"></i>Submit Work Request
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-4 pt-3 border-top">
                                    <a href="work_requests_list.php" class="text-decoration-none">
                                        <i class="fas fa-list me-2"></i>View My Previous Requests
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Character count for description
            const descriptionTextarea = document.getElementById('w_description');
            const charCount = document.getElementById('charCount');
            
            descriptionTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count + ' characters';
                
                // Update styling based on count
                if (count < 10) {
                    charCount.classList.add('warning');
                    charCount.classList.remove('success');
                    descriptionTextarea.classList.add('border-danger');
                    descriptionTextarea.classList.remove('border-success');
                } else {
                    charCount.classList.remove('warning');
                    charCount.classList.add('success');
                    descriptionTextarea.classList.remove('border-danger');
                    descriptionTextarea.classList.add('border-success');
                }
            });
            
            // Trigger input event on load to update count
            descriptionTextarea.dispatchEvent(new Event('input'));
            
            // Urgency badge selection
            document.querySelectorAll('.urgency-badge').forEach(badge => {
                badge.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    
                    // Update selected state
                    document.querySelectorAll('.urgency-badge').forEach(b => {
                        b.classList.remove('selected');
                    });
                    this.classList.add('selected');
                });
                
                // Set selected badge on load
                const radio = badge.querySelector('input[type="radio"]');
                if (radio.checked) {
                    badge.classList.add('selected');
                }
            });
            
            // Form validation
            document.getElementById('workRequestForm').addEventListener('submit', function(e) {
                let isValid = true;
                let errorMessage = '';
                let firstErrorField = null;
                
                // Check required fields
                const requiredFields = [
                    {id: 'date', name: 'Date of Request'},
                    {id: 'w_req_type', name: 'Work Request Type'},
                    {id: 'w_location', name: 'Location of Work'},
                    {id: 'w_description', name: 'Work Description'},
                    {id: 'w_com_division', name: 'Work Completion Division'},
                    {id: 'w_com_section', name: 'Work Completion Section'}
                ];
                
                requiredFields.forEach(field => {
                    const fieldElement = document.getElementById(field.id);
                    if (!fieldElement.value.trim()) {
                        isValid = false;
                        fieldElement.classList.add('border-danger');
                        if (!firstErrorField) firstErrorField = fieldElement;
                        errorMessage += `• ${field.name} is required\n`;
                    }
                });
                
                // Check description length
                if (descriptionTextarea.value.trim().length < 10) {
                    isValid = false;
                    errorMessage += '• Description must be at least 10 characters long\n';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll to first error field
                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstErrorField.focus();
                    }
                    
                    // Show error message
                    const errorHtml = `
                        <div class="alert alert-danger alert-custom d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Please fix the following errors:</h5>
                                <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">${errorMessage}</pre>
                            </div>
                        </div>
                    `;
                    
                    // Remove any existing error alerts
                    const existingAlerts = document.querySelectorAll('.alert-danger');
                    existingAlerts.forEach(alert => alert.remove());
                    
                    // Add new error alert at the top of form
                    const cardBody = document.querySelector('.card-body');
                    const firstChild = cardBody.firstChild;
                    if (firstChild.classList && firstChild.classList.contains('alert')) {
                        cardBody.insertBefore(document.createRange().createContextualFragment(errorHtml), firstChild.nextSibling);
                    } else {
                        cardBody.insertBefore(document.createRange().createContextualFragment(errorHtml), firstChild);
                    }
                } else {
                    // Show loading state
                    const submitBtn = document.querySelector('.btn-primary');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 5 seconds if still on page (form submission failed)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 5000);
                }
            });
            
            // Clear error styling on input
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.classList.remove('border-danger');
                    this.classList.remove('border-success');
                    
                    // Remove any error alerts
                    const errorAlerts = document.querySelectorAll('.alert-danger');
                    errorAlerts.forEach(alert => alert.remove());
                });
            });
            
            // Set today's date as default if not already set
            const dateField = document.getElementById('date');
            if (!dateField.value) {
                dateField.value = new Date().toISOString().split('T')[0];
            }
            
            // Focus on first field
            document.getElementById('w_req_type').focus();
            
            // Add subtle hover effect to form sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });
                
                section.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
            
            // Auto-show instructions modal on first visit (optional)
            // Uncomment if you want instructions to show automatically
            /*
            if (!localStorage.getItem('instructionsSeen')) {
                const modal = new bootstrap.Modal(document.getElementById('instructionsModal'));
                modal.show();
                localStorage.setItem('instructionsSeen', 'true');
            }
            */
        });
    </script>
</body>
</html>
<?php
// Close connection at the very end
$conn->close();
?>