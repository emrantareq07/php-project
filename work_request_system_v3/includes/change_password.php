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
function sendPasswordChangeEmail($emp_id, $conn) {
    // Get user email (you need to add email field to users table)
    $sql = "SELECT full_name, email FROM users WHERE emp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $to = $user['email'];
        $name = $user['full_name'];
        
        if ($to) {
            $subject = "Password Changed Successfully";
            $message = "Hello $name,\n\n";
            $message .= "Your password has been successfully changed.\n";
            $message .= "If you did not make this change, please contact your system administrator immediately.\n\n";
            $message .= "Date/Time: " . date('Y-m-d H:i:s') . "\n";
            $message .= "Employee ID: $emp_id\n\n";
            $message .= "Best regards,\n";
            $message .= "System Administrator";
            
            $headers = "From: noreply@yourcompany.com\r\n";
            $headers .= "Reply-To: noreply@yourcompany.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Uncomment to send email
            // mail($to, $subject, $message, $headers);
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        /* Same CSS as above */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Change Password</h1>
            <p>Update your account password</p>
        </div>
        
        <div class="form-container">
            <?php if ($locked_out): ?>
                <div class="message error">
                    <strong>Account Locked:</strong><br>
                    <?php echo $error; ?>
                    <br><br>
                    <small>For security reasons, you cannot change your password at this time.</small>
                </div>
            <?php else: ?>
                <?php if ($success): ?>
                    <div class="message success">
                        <?php echo $success; ?>
                        <br><br>
                        <small>For security reasons, please logout and login again with your new password.</small>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="message error">
                        <?php echo $error; ?>
                        <?php if ($failed_attempts > 0): ?>
                            <br><br>
                            <small>Failed attempts in last <?php echo $lockout_time; ?> minutes: <?php echo $failed_attempts; ?>/<?php echo $max_attempts; ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="requirements">
                    <h3>Password Requirements:</h3>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains at least one uppercase letter</li>
                        <li>Contains at least one lowercase letter</li>
                        <li>Contains at least one number</li>
                        <li>Should not be the same as current password</li>
                        <li>Maximum <?php echo $max_attempts; ?> failed attempts allowed every <?php echo $lockout_time; ?> minutes</li>
                    </ul>
                </div>
                
                <form id="changePasswordForm" method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <div class="input-wrapper">
                            <input type="password" id="current_password" name="current_password" 
                                   required placeholder="Enter your current password"
                                   <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                            <button type="button" class="toggle-password" data-target="current_password">👁️</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password *</label>
                        <div class="input-wrapper">
                            <input type="password" id="new_password" name="new_password" 
                                   required placeholder="Enter new password"
                                   <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                            <button type="button" class="toggle-password" data-target="new_password">👁️</button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="password-hints" id="passwordHints">
                            <!-- Hints same as before -->
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password *</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   required placeholder="Confirm new password"
                                   <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                            <button type="button" class="toggle-password" data-target="confirm_password">👁️</button>
                        </div>
                        <div class="password-hints">
                            <div class="password-hint" id="hintMatch">
                                <span class="hint-icon">❌</span>
                                <span>Passwords match</span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit" id="submitBtn" 
                            <?php echo $failed_attempts >= $max_attempts ? 'disabled' : ''; ?>>
                        Change Password
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="back-link">
                <a href="dashboard.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    // Change icon
                    this.textContent = type === 'password' ? '👁️' : '🙈';
                });
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
                
                // Check length
                if (password.length >= 8) strength++;
                
                // Check for uppercase
                if (/[A-Z]/.test(password)) strength++;
                
                // Check for lowercase
                if (/[a-z]/.test(password)) strength++;
                
                // Check for numbers
                if (/[0-9]/.test(password)) strength++;
                
                // Check for special characters (optional)
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                // Update strength bar
                strengthBar.className = 'strength-bar';
                if (password.length === 0) {
                    strengthBar.style.width = '0%';
                } else if (strength <= 2) {
                    strengthBar.className += ' strength-weak';
                } else if (strength <= 4) {
                    strengthBar.className += ' strength-medium';
                } else {
                    strengthBar.className += ' strength-strong';
                }
                
                // Update hints
                updateHints(password);
                
                // Check if password meets all requirements
                passwordValid = password.length >= 8 && 
                               /[A-Z]/.test(password) && 
                               /[a-z]/.test(password) && 
                               /[0-9]/.test(password);
                
                updateSubmitButton();
            }
            
            function updateHints(password) {
                // Length hint
                const hintLength = document.getElementById('hintLength');
                const lengthValid = password.length >= 8;
                hintLength.querySelector('.hint-icon').textContent = lengthValid ? '✅' : '❌';
                hintLength.querySelector('.hint-icon').className = 'hint-icon ' + (lengthValid ? 'hint-valid' : 'hint-invalid');
                
                // Uppercase hint
                const hintUppercase = document.getElementById('hintUppercase');
                const uppercaseValid = /[A-Z]/.test(password);
                hintUppercase.querySelector('.hint-icon').textContent = uppercaseValid ? '✅' : '❌';
                hintUppercase.querySelector('.hint-icon').className = 'hint-icon ' + (uppercaseValid ? 'hint-valid' : 'hint-invalid');
                
                // Lowercase hint
                const hintLowercase = document.getElementById('hintLowercase');
                const lowercaseValid = /[a-z]/.test(password);
                hintLowercase.querySelector('.hint-icon').textContent = lowercaseValid ? '✅' : '❌';
                hintLowercase.querySelector('.hint-icon').className = 'hint-icon ' + (lowercaseValid ? 'hint-valid' : 'hint-invalid');
                
                // Number hint
                const hintNumber = document.getElementById('hintNumber');
                const numberValid = /[0-9]/.test(password);
                hintNumber.querySelector('.hint-icon').textContent = numberValid ? '✅' : '❌';
                hintNumber.querySelector('.hint-icon').className = 'hint-icon ' + (numberValid ? 'hint-valid' : 'hint-invalid');
            }
            
            function checkPasswordMatch() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const hintMatch = document.getElementById('hintMatch');
                
                if (confirmPassword.length === 0) {
                    passwordsMatch = false;
                    hintMatch.querySelector('.hint-icon').textContent = '❌';
                    hintMatch.querySelector('.hint-icon').className = 'hint-icon hint-invalid';
                } else if (newPassword === confirmPassword) {
                    passwordsMatch = true;
                    hintMatch.querySelector('.hint-icon').textContent = '✅';
                    hintMatch.querySelector('.hint-icon').className = 'hint-icon hint-valid';
                } else {
                    passwordsMatch = false;
                    hintMatch.querySelector('.hint-icon').textContent = '❌';
                    hintMatch.querySelector('.hint-icon').className = 'hint-icon hint-invalid';
                }
                
                updateSubmitButton();
            }
            
            function updateSubmitButton() {
                const currentPassword = document.getElementById('current_password').value;
                const isFormValid = currentPassword && passwordValid && passwordsMatch;
                submitBtn.disabled = !isFormValid;
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
                    alert('Please make sure your new password meets all requirements.');
                    return;
                }
                
                if (!passwordsMatch) {
                    e.preventDefault();
                    alert('New passwords do not match. Please confirm your new password.');
                    return;
                }
                
                // Optional: Check if new password is same as current
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                
                if (currentPassword === newPassword) {
                    e.preventDefault();
                    alert('New password cannot be the same as current password.');
                    return;
                }
                
                // Show loading state
                submitBtn.textContent = 'Changing Password...';
                submitBtn.disabled = true;
            });
            
            // Initialize
            checkPasswordStrength('');
            checkPasswordMatch();
        });
    </script>
</body>
</html>