<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$message = '';
$error = '';

// Process password change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_pwd'] ?? '';
    $confirm_password = $_POST['confirm_new_pwd'] ?? '';
    
    // Validation
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Please fill in both password fields";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password in database
        $update_sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $hashed_password, $username);
        
        if ($stmt->execute()) {
            $message = "Password changed successfully! Redirecting to login page...";
            // Clear session and redirect after 2 seconds
            echo '<script>
                setTimeout(function() {
                    window.location.href = "logout.php";
                }, 2000);
            </script>';
        } else {
            $error = "Failed to update password. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - BCIC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .password-card {
            max-width: 500px;
            margin: 80px auto;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: none;
        }
        .card-header-custom h3 {
            margin: 0;
            font-weight: 600;
        }
        .card-header-custom p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .card-body-custom {
            padding: 40px;
            background: white;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: none;
        }
        .btn-change {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        .btn-change:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .alert-custom {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .password-requirements {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: transparent;
            border: none;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper input {
            padding-right: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="password-card">
            <div class="card-header-custom">
                <i class="fas fa-key fa-3x mb-3"></i>
                <h3>Change Password</h3>
                <p>Secure your account with a new password</p>
            </div>
            <div class="card-body-custom">
                <?php if ($message): ?>
                    <div class="alert alert-success alert-custom">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-custom">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" id="changePasswordForm">
                    <div class="form-group">
                        <label for="new_pwd">
                            <i class="fas fa-lock me-2"></i>New Password
                        </label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   class="form-control" 
                                   id="new_pwd" 
                                   name="new_pwd" 
                                   placeholder="Enter new password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword('new_pwd')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-requirements">
                            <i class="fas fa-info-circle"></i> Password must be at least 6 characters long
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_new_pwd">
                            <i class="fas fa-check-circle me-2"></i>Confirm New Password
                        </label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_new_pwd" 
                                   name="confirm_new_pwd" 
                                   placeholder="Confirm new password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_new_pwd')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-change text-white" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Change Password
                    </button>
                </form>

                <div class="back-link">
                    <a href="dashboard.php">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            
            // Change eye icon
            const button = field.parentElement.querySelector('.toggle-password i');
            if (type === 'text') {
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        // Form validation before submit
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPwd = document.getElementById('new_pwd').value;
            const confirmPwd = document.getElementById('confirm_new_pwd').value;
            
            if (newPwd.length < 6) {
                e.preventDefault();
                showAlert('Password must be at least 6 characters long', 'danger');
                return false;
            }
            
            if (newPwd !== confirmPwd) {
                e.preventDefault();
                showAlert('New password and confirm password do not match', 'danger');
                return false;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Changing Password...';
        });
        
        // Show alert function
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-custom`;
            alertDiv.innerHTML = `<i class="fas ${type === 'danger' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i> ${message}`;
            
            const container = document.querySelector('.card-body-custom');
            const form = document.querySelector('form');
            container.insertBefore(alertDiv, form);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
        
        // Real-time password match validation
        document.getElementById('confirm_new_pwd').addEventListener('input', function() {
            const newPwd = document.getElementById('new_pwd').value;
            const confirmPwd = this.value;
            
            if (confirmPwd.length > 0 && newPwd !== confirmPwd) {
                this.style.borderColor = '#dc3545';
                this.classList.add('is-invalid');
            } else {
                this.style.borderColor = '#dee2e6';
                this.classList.remove('is-invalid');
            }
        });
        
        document.getElementById('new_pwd').addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 6) {
                this.style.borderColor = '#ffc107';
            } else if (this.value.length >= 6) {
                this.style.borderColor = '#28a745';
            } else {
                this.style.borderColor = '#dee2e6';
            }
            
            // Also reset confirm password validation
            const confirmField = document.getElementById('confirm_new_pwd');
            if (confirmField.value.length > 0 && this.value !== confirmField.value) {
                confirmField.style.borderColor = '#dc3545';
            } else if (confirmField.value.length > 0 && this.value === confirmField.value) {
                confirmField.style.borderColor = '#28a745';
            }
        });
    </script>
</body>
</html>