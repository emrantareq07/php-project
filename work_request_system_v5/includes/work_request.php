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
$emp_id = $_SESSION['emp_id'];
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
    
    // Transport specific fields
    $transport_data = [];
    if ($w_req_type === 'Transport') {
        $transport_data = [
            'date' => $_POST['date'] ?? '',            
            'contact_no' => $_POST['contact_no'] ?? '',
            'departure_date' => $_POST['departure_date'] ?? '',
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
            'no_of_visitor' => $_POST['no_of_visitor'] ?? 0,
            'visiting_place' => trim($_POST['visiting_place'] ?? ''),
            'destination' => trim($_POST['destination'] ?? ''),
            'visit_purpose' => trim($_POST['visit_purpose'] ?? ''),
            'reporting_place' => trim($_POST['reporting_place'] ?? ''),
            'visiting_type' => $_POST['visiting_type'] ?? 'Official',
            'v_provide_status' => $_POST['v_provide_status'] ?? 'Pending'
        ];
    }
    
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
    
    // For non-transport requests, validate standard fields
    if ($w_req_type !== 'Transport') {
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
    }
    
    // Transport specific validation
    if ($w_req_type === 'Transport') {
        if (empty($transport_data['contact_no'])) {
            $errors[] = "Contact number is required for Transport request";
        }
        if (empty($transport_data['departure_date'])) {
            $errors[] = "Departure date is required for Transport request";
        }
        if (empty($transport_data['start_time'])) {
            $errors[] = "Start time is required for Transport request";
        }
        if (empty($transport_data['end_time'])) {
            $errors[] = "End time is required for Transport request";
        }
        if (empty($transport_data['visiting_place'])) {
            $errors[] = "Visiting place is required for Transport request";
        }
        if (empty($transport_data['destination'])) {
            $errors[] = "Destination is required for Transport request";
        }
        if (empty($transport_data['visit_purpose'])) {
            $errors[] = "Visit purpose is required for Transport request";
        }
        if ($transport_data['no_of_visitor'] <= 0) {
            $errors[] = "Number of visitors must be greater than 0";
        }
    }
    
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            if ($w_req_type === 'Transport') {
                // Save ONLY to transport_w_req_tbl for Transport requests
                $sql_transport = "INSERT INTO transport_w_req_tbl (
                    emp_id, full_name, designation, division, section,
                    contact_no, departure_date, start_time, end_time, no_of_visitor,
                    visiting_place, destination, visit_purpose, reporting_place,
                    visiting_type, v_provide_status, requester_id, date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt_transport = $conn->prepare($sql_transport);
                
                if ($stmt_transport === false) {
                    throw new Exception("Transport table error: " . $conn->error);
                }
                
                $stmt_transport->bind_param(
                    "sssssssssissssssis",
                    $emp_id,
                    $full_name,
                    $designation,
                    $user_division,
                    $user_section,
                    $transport_data['contact_no'],
                    $transport_data['departure_date'],
                    $transport_data['start_time'],
                    $transport_data['end_time'],
                    $transport_data['no_of_visitor'],
                    $transport_data['visiting_place'],
                    $transport_data['destination'],
                    $transport_data['visit_purpose'],
                    $transport_data['reporting_place'],
                    $transport_data['visiting_type'],
                    $transport_data['v_provide_status'],
                    $user_id,
                    $date
                );
                
                if (!$stmt_transport->execute()) {
                    throw new Exception("Failed to save transport details: " . $stmt_transport->error);
                }
                
                $transport_request_id = $stmt_transport->insert_id;
                $stmt_transport->close();
                
                $message = "Transport request submitted successfully! Request ID: TR-" . str_pad($transport_request_id, 6, '0', STR_PAD_LEFT);
                
            } else {
                // Save to work_request_tbl for non-Transport requests
                $sql = "INSERT INTO work_request_tbl (
                    emp_id, date, w_req_type, w_location, w_description, 
                    w_com_division, w_com_section, status, remarks,
                    requester_id, full_name, designation, division, section
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                
                if ($stmt === false) {
                    throw new Exception("Database error: " . $conn->error);
                }
                
                $stmt->bind_param(
                    "sssssssssissss",
                    $emp_id,
                    $date, 
                    $w_req_type, 
                    $w_location, 
                    $w_description,
                    $w_com_division, 
                    $w_com_section, 
                    $status, 
                    $remarks,
                    $user_id, 
                    $full_name, 
                    $designation, 
                    $user_division, 
                    $user_section
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to submit work request: " . $stmt->error);
                }
                
                $work_request_id = $stmt->insert_id;
                $stmt->close();
                
                $message = "Work request submitted successfully! Request ID: WR-" . str_pad($work_request_id, 6, '0', STR_PAD_LEFT);
            }
            
            // Commit transaction
            $conn->commit();
            
            // Clear form
            $_POST = array();
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = $e->getMessage();
        }
    }
}

// Get divisions for dropdown
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
include "header_test.php"
?>

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
                                <div class="user-badge d-inline-block px-4 py-3 rounded-pill">
                                    <div class="text-white fw-semibold"><?php echo htmlspecialchars($full_name); ?></div>
                                    <small class="text-white-50 d-block">
                                        <?php echo htmlspecialchars($designation); ?> | 
                                        <?php echo htmlspecialchars($user_division); ?> - 
                                        <?php echo htmlspecialchars($user_section); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-5">
                        <?php if ($message): ?>
                            <div class="alert alert-success alert-custom d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">✨ Request Submitted Successfully!</h5>
                                    <p class="mb-0"><?php echo $message; ?></p>
                                    <small class="text-muted">You can track your request in the dashboard.</small>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-custom d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">⚠️ Submission Error</h5>
                                    <p class="mb-0"><?php echo $error; ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Quick Tips -->
                        <div class="quick-tips mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-primary mb-1">
                                        <i class="fas fa-bullhorn me-2"></i>Before you begin
                                    </h6>
                                    <small class="text-muted">Ensure you have all necessary information ready</small>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#instructionsModal">
                                    <i class="fas fa-question-circle me-2"></i>View Instructions
                                </button>
                            </div>
                        </div>
                        
                        <form id="workRequestForm" method="POST">
                            <!-- Basic Information Section -->
                            <div class="form-section mb-4">
                                <h5 class="mb-4 text-primary section-header">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="date" class="form-label required">
                                            <i class="fas fa-calendar-alt"></i> Date of Request
                                        </label>
                                        <input type="date" class="form-control" id="date" name="date" 
                                               value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>" 
                                               max="<?php echo date('Y-m-d'); ?>" required>
                                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Select today's date or earlier</small>
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
                            
                            <!-- Transport Details Section (Hidden by default) -->
                            <div id="transportSection" class="transport-section <?php echo ($_POST['w_req_type'] ?? '') === 'Transport' ? 'show' : 'hide'; ?>">
                                <div class="transport-header">
                                    <i class="fas fa-car"></i> Transport Request Details
                                </div>
                                
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label class="form-label required">
                                            <i class="fas fa-id-card"></i> Employee ID
                                        </label>
                                        <input type="text" class="form-control transport-field" name="emp_id" 
                                               value="<?php echo htmlspecialchars($emp_id ?? ''); ?>"
                                               placeholder="EMP-001">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label required">
                                            <i class="fas fa-phone"></i> Contact No
                                        </label>
                                        <input type="text" class="form-control transport-field" name="contact_no" 
                                               value="<?php echo htmlspecialchars($_POST['contact_no'] ?? ''); ?>"
                                               placeholder="01XXXXXXXXX">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label required">
                                            <i class="fas fa-calendar-day"></i> Departure Date
                                        </label>
                                        <input type="date" class="form-control transport-field" name="departure_date" 
                                               value="<?php echo htmlspecialchars($_POST['departure_date'] ?? ''); ?>"
                                               min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label required">
                                            <i class="fas fa-user-friends"></i> Number of Visitors
                                        </label>
                                        <input type="number" class="form-control transport-field" name="no_of_visitor" 
                                               value="<?php echo htmlspecialchars($_POST['no_of_visitor'] ?? 1); ?>"
                                               min="1" max="50">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label required">
                                            <i class="fas fa-clock"></i> Start Time
                                        </label>
                                        <input type="time" class="form-control transport-field" name="start_time" 
                                               value="<?php echo htmlspecialchars($_POST['start_time'] ?? '08:00'); ?>">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label required">
                                            <i class="fas fa-clock"></i> End Time
                                        </label>
                                        <input type="time" class="form-control transport-field" name="end_time" 
                                               value="<?php echo htmlspecialchars($_POST['end_time'] ?? '17:00'); ?>">
                                    </div>
                                    
                                   <!--  <div class="col-md-4">
                                        <label class="form-label required">
                                            <i class="fas fa-car"></i> Vehicle Status
                                        </label>
                                        <select class="form-select transport-field" name="v_provide_status">
                                            <option value="Pending" <?php echo ($_POST['v_provide_status'] ?? '') == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Yes" <?php echo ($_POST['v_provide_status'] ?? '') == 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                            <option value="No" <?php echo ($_POST['v_provide_status'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                                        </select>
                                    </div> -->
                                    
                                    <div class="col-md-6">
                                        <label class="form-label required">
                                            <i class="fas fa-map-marker-alt"></i> Visiting Place
                                        </label>
                                        <input type="text" class="form-control transport-field" name="visiting_place" 
                                               value="<?php echo htmlspecialchars($_POST['visiting_place'] ?? ''); ?>"
                                               placeholder="Office/Factory Name">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label required">
                                            <i class="fas fa-road"></i> Destination
                                        </label>
                                        <input type="text" class="form-control transport-field" name="destination" 
                                               value="<?php echo htmlspecialchars($_POST['destination'] ?? ''); ?>"
                                               placeholder="Street, City, District">
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <label class="form-label required">
                                            <i class="fas fa-bullseye"></i> Visit Purpose
                                        </label>
                                        <textarea class="form-control transport-field" name="visit_purpose" rows="2"
                                                  placeholder="Purpose of the visit..."><?php echo htmlspecialchars($_POST['visit_purpose'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="fas fa-map-pin"></i> Reporting Place
                                        </label>
                                        <input type="text" class="form-control transport-field" name="reporting_place" 
                                               value="<?php echo htmlspecialchars($_POST['reporting_place'] ?? ''); ?>"
                                               placeholder="Gate/Reception">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="fas fa-user-tag"></i> Visit Type
                                        </label>
                                        <select class="form-select transport-field" name="visiting_type">
                                            <option value="Official" <?php echo ($_POST['visiting_type'] ?? '') == 'Official' ? 'selected' : ''; ?>>Official</option>
                                            <option value="Personal" <?php echo ($_POST['visiting_type'] ?? '') == 'Personal' ? 'selected' : ''; ?>>Personal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Work Details Section (Hidden for Transport) -->
                            <div id="workDetailsSection" class="form-section mb-4 regular-section <?php echo ($_POST['w_req_type'] ?? '') === 'Transport' ? 'hide' : ''; ?>">
                                <h5 class="mb-4 text-primary section-header">
                                    <i class="fas fa-clipboard-list me-2"></i>Work Details
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="w_location" class="form-label required">
                                            <i class="fas fa-map-marker-alt"></i> Location of Work
                                        </label>
                                        <input type="text" class="form-control" id="w_location" name="w_location" 
                                               placeholder="e.g., Main Office Building, Floor 3, Room 302, Near Reception"
                                               value="<?php echo htmlspecialchars($_POST['w_location'] ?? ''); ?>" 
                                               required>
                                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Be specific about the location</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="w_description" class="form-label required">
                                            <i class="fas fa-align-left"></i> Work Description
                                        </label>
                                        <textarea class="form-control" id="w_description" name="w_description" 
                                                  placeholder="Provide detailed description of the work required. Include specific issues, equipment involved, and any other relevant information..."
                                                  rows="4" required><?php echo htmlspecialchars($_POST['w_description'] ?? ''); ?></textarea>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Minimum 10 characters required</small>
                                            <small class="char-count" id="charCount">0 characters</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Assignment Section (Hidden for Transport) -->
                            <div id="assignmentSection" class="form-section mb-4 regular-section <?php echo ($_POST['w_req_type'] ?? '') === 'Transport' ? 'hide' : ''; ?>">
                                <h5 class="mb-4 text-primary section-header">
                                    <i class="fas fa-user-check me-2"></i>Assignment Details
                                </h5>
                                
                                <div class="row g-4">
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
                            
                            <!-- Priority Level Section (Hidden for Transport) -->
                            <div id="prioritySection" class="form-section mb-4 regular-section <?php echo ($_POST['w_req_type'] ?? '') === 'Transport' ? 'hide' : ''; ?>">
                                <h5 class="mb-4 text-primary section-header">
                                    <i class="fas fa-exclamation-circle me-2"></i>Priority Level
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-warning border-warning bg-light mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-lightbulb text-warning fa-2x me-3"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1">🚨 Select Appropriate Urgency</h6>
                                                    <p class="mb-0">Choose based on the impact on operations and safety considerations.</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="urgency-badge badge-normal" for="status_normal">
                                                    <input type="radio" id="status_normal" name="status" value="normal" 
                                                           <?php echo ($_POST['status'] ?? 'normal') == 'normal' ? 'checked' : ''; ?> hidden>
                                                    <div class="d-flex flex-column align-items-center p-3">
                                                        <i class="fas fa-clock fa-3x mb-3"></i>
                                                        <strong class="h5 mb-2">Normal</strong>
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
                                                    <div class="d-flex flex-column align-items-center p-3">
                                                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                                        <strong class="h5 mb-2">Urgent</strong>
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
                                                    <div class="d-flex flex-column align-items-center p-3">
                                                        <i class="fas fa-fire fa-3x mb-3"></i>
                                                        <strong class="h5 mb-2">Very Urgent</strong>
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
                            
                            <!-- Additional Information (Hidden for Transport) -->
                            <div id="remarksSection" class="form-section mb-5 regular-section <?php echo ($_POST['w_req_type'] ?? '') === 'Transport' ? 'hide' : ''; ?>">
                                <h5 class="mb-4 text-primary section-header">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Information
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="remarks" class="form-label">
                                            <i class="fas fa-comment-dots"></i> Additional Remarks (Optional)
                                        </label>
                                        <textarea class="form-control" id="remarks" name="remarks" 
                                                  placeholder="Any special instructions, access requirements, preferred timing, or other relevant information..."
                                                  rows="3"><?php echo htmlspecialchars($_POST['remarks'] ?? ''); ?></textarea>
                                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Add any extra details that might help with the request</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="form-section">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                                            <div class="text-center text-md-start">
                                                <small class="text-muted">
                                                    <i class="fas fa-shield-alt me-1"></i>Your information is secure and encrypted
                                                </small>
                                            </div>
                                            
                                            <div class="d-flex flex-wrap gap-3">
                                                <a href="dashboard.php" class="btn btn-secondary btn-lg px-5">
                                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                                    <i class="fas fa-paper-plane me-2"></i>Submit Work Request
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-5 pt-4 border-top">
                                    <a href="work_requests_list.php" class="forgot-link">
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
            const transportSection = document.getElementById('transportSection');
            const workDetailsSection = document.getElementById('workDetailsSection');
            const assignmentSection = document.getElementById('assignmentSection');
            const prioritySection = document.getElementById('prioritySection');
            const remarksSection = document.getElementById('remarksSection');
            const workTypeSelect = document.getElementById('w_req_type');
            
            // Function to show/hide sections based on work type
            function toggleSections() {
                if (workTypeSelect.value === 'Transport') {
                    // Show transport section
                    transportSection.classList.remove('hide');
                    transportSection.classList.add('show');
                    
                    // Hide regular sections
                    workDetailsSection.classList.add('hide');
                    assignmentSection.classList.add('hide');
                    prioritySection.classList.add('hide');
                    remarksSection.classList.add('hide');
                    
                    // Make transport fields required
                    document.querySelectorAll('#transportSection input, #transportSection select, #transportSection textarea').forEach(field => {
                        if (field.name !== 'reporting_place' && field.name !== 'visiting_type') {
                            field.setAttribute('required', 'required');
                        }
                    });
                    
                    // Remove required from regular sections
                    document.getElementById('w_location').removeAttribute('required');
                    document.getElementById('w_description').removeAttribute('required');
                    document.getElementById('w_com_division').removeAttribute('required');
                    document.getElementById('w_com_section').removeAttribute('required');
                    
                } else {
                    // Hide transport section
                    transportSection.classList.remove('show');
                    transportSection.classList.add('hide');
                    
                    // Show regular sections
                    workDetailsSection.classList.remove('hide');
                    assignmentSection.classList.remove('hide');
                    prioritySection.classList.remove('hide');
                    remarksSection.classList.remove('hide');
                    
                    // Remove required from transport fields
                    document.querySelectorAll('#transportSection input, #transportSection select, #transportSection textarea').forEach(field => {
                        field.removeAttribute('required');
                    });
                    
                    // Add required to regular sections
                    document.getElementById('w_location').setAttribute('required', 'required');
                    document.getElementById('w_description').setAttribute('required', 'required');
                    document.getElementById('w_com_division').setAttribute('required', 'required');
                    document.getElementById('w_com_section').setAttribute('required', 'required');
                }
            }
            
            // Initial check
            toggleSections();
            
            // Add change event listener
            workTypeSelect.addEventListener('change', toggleSections);
            
            // Set default values for transport fields if not already set
            const transportFields = {
                'start_time': '08:00',
                'end_time': '17:00',
                'no_of_visitor': 1,
                'visiting_type': 'Official',
                'v_provide_status': 'Pending'
            };
            
            for (const [field, value] of Object.entries(transportFields)) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input && !input.value) {
                    input.value = value;
                }
            }
            
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
                
                // Always check these fields
                const requiredFields = [
                    {id: 'date', name: 'Date of Request'},
                    {id: 'w_req_type', name: 'Work Request Type'}
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
                
                // Check fields based on work type
                if (workTypeSelect.value === 'Transport') {
                    const transportRequired = [
                        {name: 'emp_id', label: 'Employee ID'},
                        {name: 'contact_no', label: 'Contact Number'},
                        {name: 'departure_date', label: 'Departure Date'},
                        {name: 'start_time', label: 'Start Time'},
                        {name: 'end_time', label: 'End Time'},
                        {name: 'visiting_place', label: 'Visiting Place'},
                        {name: 'destination', label: 'Destination'},
                        {name: 'visit_purpose', label: 'Visit Purpose'}
                    ];
                    
                    transportRequired.forEach(field => {
                        const fieldElement = document.querySelector(`[name="${field.name}"]`);
                        if (!fieldElement.value.trim()) {
                            isValid = false;
                            fieldElement.classList.add('border-danger');
                            if (!firstErrorField) firstErrorField = fieldElement;
                            errorMessage += `• ${field.label} is required for Transport request\n`;
                        }
                    });
                    
                    // Check number of visitors
                    const visitorCount = document.querySelector('[name="no_of_visitor"]');
                    if (visitorCount && visitorCount.value <= 0) {
                        isValid = false;
                        visitorCount.classList.add('border-danger');
                        if (!firstErrorField) firstErrorField = visitorCount;
                        errorMessage += '• Number of visitors must be greater than 0\n';
                    }
                } else {
                    // Check regular fields for non-transport requests
                    const regularRequired = [
                        {id: 'w_location', name: 'Location of Work'},
                        {id: 'w_description', name: 'Work Description'},
                        {id: 'w_com_division', name: 'Work Completion Division'},
                        {id: 'w_com_section', name: 'Work Completion Section'}
                    ];
                    
                    regularRequired.forEach(field => {
                        const fieldElement = document.getElementById(field.id);
                        if (!fieldElement.value.trim()) {
                            isValid = false;
                            fieldElement.classList.add('border-danger');
                            if (!firstErrorField) firstErrorField = fieldElement;
                            errorMessage += `• ${field.name} is required\n`;
                        }
                    });
                    
                    // Check description length for non-transport
                    if (descriptionTextarea.value.trim().length < 10) {
                        isValid = false;
                        errorMessage += '• Description must be at least 10 characters long\n';
                    }
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
                                <h5 class="alert-heading mb-1">⚠️ Please fix the following errors:</h5>
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
            
            // Set departure date to tomorrow if not set and transport is selected
            const departureDate = document.querySelector('[name="departure_date"]');
            if (departureDate && !departureDate.value && workTypeSelect.value === 'Transport') {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                departureDate.value = tomorrow.toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>
<?php
// Close connection at the very end
$conn->close();
?>