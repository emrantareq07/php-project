<?php
// change_password.php
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
$emp_id = $_SESSION['emp_id'];
$message = '';
$error = '';
$success = '';

// Security: Check for too many failed attempts
$max_attempts = 5;
$lockout_time = 15; // minutes

// Get failed attempts in last lockout_time minutes
$attempt_sql = "SELECT COUNT(*) as attempts FROM password_attempts 
                WHERE user_id = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL ? MINUTE) 
                AND success = 0";
$stmt = $conn->prepare($attempt_sql);
$stmt->bind_param("ii", $user_id, $lockout_time);
$stmt->execute();
$result = $stmt->get_result();
$attempt_data = $result->fetch_assoc();
$failed_attempts = $attempt_data['attempts'] ?? 0;
$stmt->close();

if ($failed_attempts >= $max_attempts) {
    $error = "Too many failed attempts. Please try again after $lockout_time minutes.";
    $locked_out = true;
} else {
    $locked_out = false;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked_out) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Get IP address and user agent for logging
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    // Validation
    $validation_errors = [];
    
    if (empty($current_password)) {
        $validation_errors[] = "Current password is required";
    }
    
    if (empty($new_password)) {
        $validation_errors[] = "New password is required";
    } elseif (strlen($new_password) < 8) {
        $validation_errors[] = "New password must be at least 8 characters";
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $validation_errors[] = "New password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $validation_errors[] = "New password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $validation_errors[] = "New password must contain at least one number";
    }
    
    if (empty($confirm_password)) {
        $validation_errors[] = "Please confirm your new password";
    } elseif ($new_password !== $confirm_password) {
        $validation_errors[] = "New passwords do not match";
    }
    
    if (!empty($validation_errors)) {
        $error = implode('<br>', $validation_errors);
    } else {
        // Get current password from database
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify current password
            if (password_verify($current_password, $user['password'])) {
                // Check if new password is same as current
                if (password_verify($new_password, $user['password'])) {
                    $error = "New password cannot be the same as current password";
                } else {
                    // Hash new password
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
                    
                    // Update password
                    $update_sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("si", $hashed_password, $user_id);
                    
                    if ($update_stmt->execute()) {
                        $success = "Password changed successfully!";
                        
                        // Clear the form
                        $_POST = array();
                        
                        // Log successful password change
                        $log_sql = "INSERT INTO security_logs (user_id, action, ip_address, user_agent, details) 
                                   VALUES (?, 'password_change', ?, ?, ?)";
                        $log_stmt = $conn->prepare($log_sql);
                        $details = json_encode(['type' => 'password_change', 'status' => 'success']);
                        $log_stmt->bind_param("isss", $user_id, $ip_address, $user_agent, $details);
                        $log_stmt->execute();
                        $log_stmt->close();
                        
                        // Log successful attempt
                        $attempt_log = "INSERT INTO password_attempts (user_id, success, ip_address, user_agent) 
                                       VALUES (?, 1, ?, ?)";
                        $attempt_stmt = $conn->prepare($attempt_log);
                        $attempt_stmt->bind_param("iss", $user_id, $ip_address, $user_agent);
                        $attempt_stmt->execute();
                        $attempt_stmt->close();
                        
                        // Send email notification (optional)
                        sendPasswordChangeEmail($emp_id, $conn);
                        
                    } else {
                        $error = "Failed to update password. Please try again.";
                        
                        // Log failed update
                        $log_sql = "INSERT INTO security_logs (user_id, action, ip_address, user_agent, details) 
                                   VALUES (?, 'password_change', ?, ?, ?)";
                        $log_stmt = $conn->prepare($log_sql);
                        $details = json_encode(['type' => 'password_change', 'status' => 'failed', 'reason' => 'database_error']);
                        $log_stmt->bind_param("isss", $user_id, $ip_address, $user_agent, $details);
                        $log_stmt->execute();
                        $log_stmt->close();
                    }
                    $update_stmt->close();
                }
            } else {
                $error = "Current password is incorrect";
                
                // Log failed attempt
                $attempt_log = "INSERT INTO password_attempts (user_id, success, ip_address, user_agent) 
                               VALUES (?, 0, ?, ?)";
                $attempt_stmt = $conn->prepare($attempt_log);
                $attempt_stmt->bind_param("iss", $user_id, $ip_address, $user_agent);
                $attempt_stmt->execute();
                $attempt_stmt->close();
                
                // Log security event
                $log_sql = "INSERT INTO security_logs (user_id, action, ip_address, user_agent, details) 
                           VALUES (?, 'password_attempt', ?, ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $details = json_encode(['type' => 'password_change', 'status' => 'failed', 'reason' => 'incorrect_password']);
                $log_stmt->bind_param("isss", $user_id, $ip_address, $user_agent, $details);
                $log_stmt->execute();
                $log_stmt->close();
            }
        } else {
            $error = "User not found";
        }
        $stmt->close();
    }
}

$conn->close();

// Function to send email notification
// function sendPasswordChangeEmail($emp_id, $conn) {
//     // Get user email (you need to add email field to users table)
//     $sql = "SELECT full_name, email FROM users WHERE emp_id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $emp_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($result->num_rows === 1) {
//         $user = $result->fetch_assoc();
//         $to = $user['email'];
//         $name = $user['full_name'];
        
//         if ($to) {
//             $subject = "Password Changed Successfully";
//             $message = "Hello $name,\n\n";
//             $message .= "Your password has been successfully changed.\n";
//             $message .= "If you did not make this change, please contact your system administrator immediately.\n\n";
//             $message .= "Date/Time: " . date('Y-m-d H:i:s') . "\n";
//             $message .= "Employee ID: $emp_id\n\n";
//             $message .= "Best regards,\n";
//             $message .= "System Administrator";
            
//             $headers = "From: noreply@yourcompany.com\r\n";
//             $headers .= "Reply-To: noreply@yourcompany.com\r\n";
//             $headers .= "X-Mailer: PHP/" . phpversion();
            
//             // Uncomment to send email
//             // mail($to, $subject, $message, $headers);
//         }
//     }
//     $stmt->close();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin: 0 auto;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 1.5rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .password-toggle {
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .password-requirements {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .requirement-item {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .requirement-item i {
            margin-right: 0.5rem;
            font-size: 0.8rem;
        }
        
        .requirement-item.valid {
            color: var(--success-color);
        }
        
        .requirement-item.invalid {
            color: var(--danger-color);
        }
        
        .strength-meter {
            height: 4px;
            background-color: #e9ecef;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .strength-weak {
            background-color: var(--danger-color);
            width: 33% !important;
        }
        
        .strength-medium {
            background-color: var(--warning-color);
            width: 66% !important;
        }
        
        .strength-strong {
            background-color: var(--success-color);
            width: 100% !important;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }
        
        .btn-primary:disabled {
            background: linear-gradient(135deg, #6c757d, #495057);
            transform: none;
            box-shadow: none;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #dee2e6;
        }
        
        .alert {
            border-radius: var(--border-radius);
            border: none;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: var(--success-color);
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: var(--danger-color);
        }
        
        .attempts-warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 0.75rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .card {
                margin: 0 15px;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2 class="mb-1">Change Password</h2>
                        <p class="mb-0">Update your account password securely</p>
                    </div>
                    
                    <div class="card-body">
                        <?php if ($locked_out): ?>
                            <div class="alert alert-danger">
                                <h5 class="alert-heading"><i class="bi bi-shield-lock-fill me-2"></i>Account Locked</h5>
                                <p><?php echo $error; ?></p>
                                <hr>
                                <p class="mb-0"><small>For security reasons, you cannot change your password at this time. Please try again later.</small></p>
                            </div>
                        <?php else: ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <?php echo $success; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <div class="mt-2">
                                        <small>For security reasons, please logout and login again with your new password.</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <?php if ($failed_attempts > 0): ?>
                                        <div class="mt-2">
                                            <small>Failed attempts in last <?php echo $lockout_time; ?> minutes: 
                                                <span class="badge bg-danger"><?php echo $failed_attempts; ?>/<?php echo $max_attempts; ?></span>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($failed_attempts > 0 && $failed_attempts < $max_attempts): ?>
                                <div class="attempts-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Warning:</strong> You have <?php echo $failed_attempts; ?> failed attempt(s). 
                                    After <?php echo $max_attempts; ?> failed attempts, you will be locked out for <?php echo $lockout_time; ?> minutes.
                                </div>
                            <?php endif; ?>
                            
                            <div class="password-requirements mb-4">
                                <h5 class="mb-3"><i class="bi bi-shield-check me-2"></i>Password Requirements</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="requirement-item">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            At least 8 characters long
                                        </div>
                                        <div class="requirement-item">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            Contains at least one uppercase letter
                                        </div>
                                        <div class="requirement-item">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            Contains at least one lowercase letter
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="requirement-item">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            Contains at least one number
                                        </div>
                                        <div class="requirement-item">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            Should not be same as current password
                                        </div>
                                        <div class="requirement-item">
                                            <i class="bi bi-shield-exclamation text-warning"></i>
                                            Max <?php echo $max_attempts; ?> attempts every <?php echo $lockout_time; ?> minutes
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <form id="changePasswordForm" method="POST">
                                <div class="mb-4">
                                    <label for="current_password" class="form-label fw-bold">
                                        <i class="bi bi-key me-1"></i>Current Password *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="current_password" 
                                               name="current_password" 
                                               required 
                                               placeholder="Enter your current password"
                                               <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                                        <button class="btn btn-outline-secondary password-toggle" type="button" id="toggleCurrentPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="new_password" class="form-label fw-bold">
                                        <i class="bi bi-lock me-1"></i>New Password *
                                    </label>
                                    <div class="input-group mb-2">
                                        <input type="password" 
                                               class="form-control" 
                                               id="new_password" 
                                               name="new_password" 
                                               required 
                                               placeholder="Enter new password"
                                               <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                                        <button class="btn btn-outline-secondary password-toggle" type="button" id="toggleNewPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="strength-meter">
                                        <div class="strength-meter-fill" id="strengthBar"></div>
                                    </div>
                                    <div class="row mt-2" id="passwordHints">
                                        <div class="col-6 col-md-3">
                                            <small class="requirement-item" id="hintLength">
                                                <i class="bi bi-circle me-1"></i>8+ chars
                                            </small>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <small class="requirement-item" id="hintUppercase">
                                                <i class="bi bi-circle me-1"></i>Uppercase
                                            </small>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <small class="requirement-item" id="hintLowercase">
                                                <i class="bi bi-circle me-1"></i>Lowercase
                                            </small>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <small class="requirement-item" id="hintNumber">
                                                <i class="bi bi-circle me-1"></i>Number
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label fw-bold">
                                        <i class="bi bi-lock-fill me-1"></i>Confirm New Password *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_password" 
                                               name="confirm_password" 
                                               required 
                                               placeholder="Confirm new password"
                                               <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                                        <button class="btn btn-outline-secondary password-toggle" type="button" id="toggleConfirmPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <small class="requirement-item" id="hintMatch">
                                            <i class="bi bi-circle me-1"></i>Passwords match
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" 
                                            class="btn btn-primary btn-lg" 
                                            id="submitBtn"
                                            <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                                        <i class="bi bi-key-fill me-2"></i>Change Password
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                        
                        <div class="back-link mt-4">
                            <a href="dashboard.php" class="text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
            const toggleNewPassword = document.getElementById('toggleNewPassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            
            function togglePassword(inputId, button) {
                const input = document.getElementById(inputId);
                const icon = button.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'bi bi-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'bi bi-eye';
                }
            }
            
            toggleCurrentPassword.addEventListener('click', function() {
                togglePassword('current_password', this);
            });
            
            toggleNewPassword.addEventListener('click', function() {
                togglePassword('new_password', this);
            });
            
            toggleConfirmPassword.addEventListener('click', function() {
                togglePassword('confirm_password', this);
            });
            
            // Password strength checker
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const strengthBar = document.getElementById('strengthBar');
            const submitBtn = document.getElementById('submitBtn');
            
            let passwordValid = false;
            let passwordsMatch = false;
            
            function checkPasswordStrength(password) {
                let strength = 0;
                const hints = {
                    length: document.getElementById('hintLength'),
                    uppercase: document.getElementById('hintUppercase'),
                    lowercase: document.getElementById('hintLowercase'),
                    number: document.getElementById('hintNumber')
                };
                
                // Check length
                if (password.length >= 8) {
                    strength++;
                    updateHint(hints.length, true);
                } else {
                    updateHint(hints.length, false);
                }
                
                // Check for uppercase
                if (/[A-Z]/.test(password)) {
                    strength++;
                    updateHint(hints.uppercase, true);
                } else {
                    updateHint(hints.uppercase, false);
                }
                
                // Check for lowercase
                if (/[a-z]/.test(password)) {
                    strength++;
                    updateHint(hints.lowercase, true);
                } else {
                    updateHint(hints.lowercase, false);
                }
                
                // Check for numbers
                if (/[0-9]/.test(password)) {
                    strength++;
                    updateHint(hints.number, true);
                } else {
                    updateHint(hints.number, false);
                }
                
                // Check for special characters (optional bonus)
                if (/[^A-Za-z0-9]/.test(password)) {
                    strength++;
                }
                
                // Update strength bar
                strengthBar.className = 'strength-meter-fill';
                if (password.length === 0) {
                    strengthBar.style.width = '0%';
                    strengthBar.style.backgroundColor = '';
                } else if (strength <= 2) {
                    strengthBar.className += ' strength-weak';
                } else if (strength <= 4) {
                    strengthBar.className += ' strength-medium';
                } else {
                    strengthBar.className += ' strength-strong';
                }
                
                // Check if password meets all requirements
                passwordValid = password.length >= 8 && 
                               /[A-Z]/.test(password) && 
                               /[a-z]/.test(password) && 
                               /[0-9]/.test(password);
                
                updateSubmitButton();
            }
            
            function updateHint(element, isValid) {
                const icon = element.querySelector('i');
                if (isValid) {
                    icon.className = 'bi bi-check-circle-fill text-success me-1';
                } else {
                    icon.className = 'bi bi-circle me-1';
                }
            }
            
            function checkPasswordMatch() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const hintMatch = document.getElementById('hintMatch');
                const icon = hintMatch.querySelector('i');
                
                if (confirmPassword.length === 0) {
                    passwordsMatch = false;
                    icon.className = 'bi bi-circle me-1';
                } else if (newPassword === confirmPassword) {
                    passwordsMatch = true;
                    icon.className = 'bi bi-check-circle-fill text-success me-1';
                } else {
                    passwordsMatch = false;
                    icon.className = 'bi bi-x-circle-fill text-danger me-1';
                }
                
                updateSubmitButton();
            }
            
            function updateSubmitButton() {
                const currentPassword = document.getElementById('current_password').value;
                const isFormValid = currentPassword && passwordValid && passwordsMatch;
                
                submitBtn.disabled = !isFormValid;
                if (isFormValid) {
                    submitBtn.innerHTML = '<i class="bi bi-key-fill me-2"></i>Change Password';
                }
            }
            
            // Event listeners
            newPasswordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
            
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            
            document.getElementById('current_password').addEventListener('input', updateSubmitButton);
            
            // Form submission
            document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
                if (!passwordValid) {
                    e.preventDefault();
                    showAlert('Please make sure your new password meets all requirements.', 'danger');
                    return;
                }
                
                if (!passwordsMatch) {
                    e.preventDefault();
                    showAlert('New passwords do not match. Please confirm your new password.', 'danger');
                    return;
                }
                
                // Check if new password is same as current
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                
                if (currentPassword === newPassword) {
                    e.preventDefault();
                    showAlert('New password cannot be the same as current password.', 'danger');
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Changing Password...';
            });
            
            // Initialize
            checkPasswordStrength('');
            checkPasswordMatch();
            
            // Helper function to show alerts
            function showAlert(message, type) {
                // Remove existing alerts
                const existingAlerts = document.querySelectorAll('.alert-dismissible:not(.alert-success):not(.alert-danger)');
                existingAlerts.forEach(alert => alert.remove());
                
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                        <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                const form = document.getElementById('changePasswordForm');
                form.insertAdjacentHTML('beforebegin', alertHtml);
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    const alert = document.querySelector('.alert-dismissible:not(.alert-success):not(.alert-danger)');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>