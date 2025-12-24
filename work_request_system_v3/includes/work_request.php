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
$division = $_SESSION['division'];
$section = $_SESSION['section'];

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
                $user_id, $full_name, $designation, $division, $section
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

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
        }

        .user-details {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .required:after {
            content: " *";
            color: #e74c3c;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .urgency-badges {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .urgency-badge {
            flex: 1;
            padding: 10px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .urgency-badge:hover {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }

        .urgency-badge.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }

        .badge-normal { color: #3498db; }
        .badge-urgent { color: #f39c12; }
        .badge-very-urgent { color: #e74c3c; }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-box ul {
            list-style: none;
            padding-left: 0;
        }

        .info-box li {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .info-box li:before {
            content: "•";
            color: #667eea;
            font-weight: bold;
            margin-right: 8px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .user-info {
                text-align: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>Work Request Form</h1>
                    <p>Submit maintenance or service requests</p>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($full_name); ?></div>
                    <div class="user-details">
                        <?php echo htmlspecialchars($designation); ?> | 
                        <?php echo htmlspecialchars($division); ?> - 
                        <?php echo htmlspecialchars($section); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-container">
            <?php if ($message): ?>
                <div class="message success">
                    <?php echo $message; ?>
                    <br>
                    <small>You can view your request in the dashboard.</small>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="info-box">
                <h3>📋 Instructions:</h3>
                <ul>
                    <li>Fill in all required fields marked with *</li>
                    <li>Provide clear and detailed description of the work needed</li>
                    <li>Select appropriate urgency level based on the situation</li>
                    <li>Review all information before submitting</li>
                    <li>You can track your request status in the dashboard</li>
                </ul>
            </div>
            
            <form id="workRequestForm" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="date" class="required">Date of Request</label>
                        <input type="date" id="date" name="date" 
                               value="<?php echo date('Y-m-d'); ?>" 
                               max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="w_req_type" class="required">Work Request Type</label>
                        <select id="w_req_type" name="w_req_type" required>
                            <option value="">Select Type</option>
                            <option value="ICT" <?php echo ($_POST['w_req_type'] ?? '') == 'ICT' ? 'selected' : ''; ?>>ICT (Information & Communication Technology)</option>
                            <option value="Civil" <?php echo ($_POST['w_req_type'] ?? '') == 'Civil' ? 'selected' : ''; ?>>Civil Works</option>
                            <option value="Transport" <?php echo ($_POST['w_req_type'] ?? '') == 'Transport' ? 'selected' : ''; ?>>Transport</option>
                            <option value="Electrical" <?php echo ($_POST['w_req_type'] ?? '') == 'Electrical' ? 'selected' : ''; ?>>Electrical</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="w_location" class="required">Location of Work</label>
                    <input type="text" id="w_location" name="w_location" 
                           placeholder="e.g., Main Office Building, Floor 3, Room 302"
                           value="<?php echo htmlspecialchars($_POST['w_location'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="w_description" class="required">Work Description</label>
                    <textarea id="w_description" name="w_description" 
                              placeholder="Provide detailed description of the work required..."
                              required><?php echo htmlspecialchars($_POST['w_description'] ?? ''); ?></textarea>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Character count: <span id="charCount">0</span>
                    </small>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="w_com_division" class="required">Work Completion Division</label>
                        <select id="w_com_division" name="w_com_division" required>
                            <option value="">Select Division</option>
                            <option value="IT" <?php echo ($_POST['w_com_division'] ?? '') == 'IT' ? 'selected' : ''; ?>>IT Department</option>
                            <option value="HR" <?php echo ($_POST['w_com_division'] ?? '') == 'HR' ? 'selected' : ''; ?>>Human Resources</option>
                            <option value="Finance" <?php echo ($_POST['w_com_division'] ?? '') == 'Finance' ? 'selected' : ''; ?>>Finance</option>
                            <option value="Operations" <?php echo ($_POST['w_com_division'] ?? '') == 'Operations' ? 'selected' : ''; ?>>Operations</option>
                            <option value="MTS" <?php echo ($_POST['w_com_division'] ?? '') == 'MTS' ? 'selected' : ''; ?>>MTS</option>
                            <option value="Procurement" <?php echo ($_POST['w_com_division'] ?? '') == 'Procurement' ? 'selected' : ''; ?>>Procurement</option>
                            <option value="Other" <?php echo ($_POST['w_com_division'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="w_com_section" class="required">Work Completion Section</label>
                        <input type="text" id="w_com_section" name="w_com_section" 
                               placeholder="e.g., Network Section, Electrical Maintenance"
                               value="<?php echo htmlspecialchars($_POST['w_com_section'] ?? ''); ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="required">Urgency Level</label>
                    <div class="urgency-badges">
                        <label class="urgency-badge badge-normal" for="status_normal">
                            <input type="radio" id="status_normal" name="status" value="normal" 
                                   <?php echo ($_POST['status'] ?? 'normal') == 'normal' ? 'checked' : ''; ?> hidden>
                            📋 Normal
                            <small style="display: block; font-weight: normal; font-size: 12px;">
                                Routine work, can be scheduled
                            </small>
                        </label>
                        
                        <label class="urgency-badge badge-urgent" for="status_urgent">
                            <input type="radio" id="status_urgent" name="status" value="urgent" 
                                   <?php echo ($_POST['status'] ?? '') == 'urgent' ? 'checked' : ''; ?> hidden>
                            ⚠️ Urgent
                            <small style="display: block; font-weight: normal; font-size: 12px;">
                                Requires attention soon
                            </small>
                        </label>
                        
                        <label class="urgency-badge badge-very-urgent" for="status_very_urgent">
                            <input type="radio" id="status_very_urgent" name="status" value="very urgent" 
                                   <?php echo ($_POST['status'] ?? '') == 'very urgent' ? 'checked' : ''; ?> hidden>
                            🚨 Very Urgent
                            <small style="display: block; font-weight: normal; font-size: 12px;">
                                Critical, needs immediate attention
                            </small>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="remarks">Additional Remarks (Optional)</label>
                    <textarea id="remarks" name="remarks" 
                              placeholder="Any additional information or special instructions..."
                              rows="3"><?php echo htmlspecialchars($_POST['remarks'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        📤 Submit Work Request
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Cancel
                    </a>
                </div>
            </form>
            
            <div class="back-link">
                <a href="work_requests_list.php">📋 View My Work Requests</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Character count for description
            const descriptionTextarea = document.getElementById('w_description');
            const charCount = document.getElementById('charCount');
            
            descriptionTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                
                // Highlight if description is too short
                if (this.value.length < 10) {
                    this.style.borderColor = '#e74c3c';
                    charCount.style.color = '#e74c3c';
                } else {
                    this.style.borderColor = '#2ecc71';
                    charCount.style.color = '#2ecc71';
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
                
                // Check required fields
                const requiredFields = [
                    'date', 'w_req_type', 'w_location', 'w_description',
                    'w_com_division', 'w_com_section'
                ];
                
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#e74c3c';
                    }
                });
                
                // Check description length
                if (descriptionTextarea.value.trim().length < 10) {
                    isValid = false;
                    errorMessage = 'Description must be at least 10 characters long.';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    if (errorMessage) {
                        alert('Please fix the following errors:\n\n' + errorMessage);
                    } else {
                        alert('Please fill in all required fields marked with *.');
                    }
                } else {
                    // Show loading state
                    const submitBtn = document.querySelector('.btn-primary');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '⏳ Submitting...';
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
                    this.style.borderColor = '#e1e5eb';
                });
            });
            
            // Set today's date as default if not already set
            const dateField = document.getElementById('date');
            if (!dateField.value) {
                dateField.value = new Date().toISOString().split('T')[0];
            }
            
            // Focus on first field
            document.getElementById('w_req_type').focus();
        });
    </script>
</body>
</html>