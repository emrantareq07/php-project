<?php
session_name('dfms');
session_start();
error_reporting(0);

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

include('../db/db.php'); // Include your database connection

$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$message = '';
$message_type = '';
$show_alert_and_redirect = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
    $current_password_input = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($current_password_input) || empty($new_password) || empty($confirm_password)) {
        $message = 'All fields are required!';
        $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = 'New password and confirm password do not match!';
        $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
        $message = 'New password must be at least 6 characters long!';
        $message_type = 'error';
    } else {
        // Check current password
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $stored_password_hash = $row['password'];
            $current_password_hashed = sha1($current_password_input);
            
            // Verify current password using SHA1
            if ($current_password_hashed === $stored_password_hash) {
                // Update password with SHA1
                $new_password_hashed = sha1($new_password);
                $update_sql = "UPDATE users SET password = ? WHERE username = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "ss", $new_password_hashed, $username);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $message = 'Password changed successfully! You will be redirected to login page.';
                    $message_type = 'success';
                    $show_alert_and_redirect = true;
                    
                    // Clear form fields
                    $_POST = array();
                } else {
                    $message = 'Error updating password. Please try again.';
                    $message_type = 'error';
                }
                mysqli_stmt_close($update_stmt);
            } else {
                $message = 'Current password is incorrect!';
                $message_type = 'error';
            }
        } else {
            $message = 'User not found!';
            $message_type = 'error';
        }
        mysqli_stmt_close($stmt);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .password-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .message-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .message-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
        }
        .strength-weak { background-color: #dc3545; width: 25%; }
        .strength-medium { background-color: #ffc107; width: 50%; }
        .strength-strong { background-color: #28a745; width: 75%; }
        .strength-very-strong { background-color: #20c997; width: 100%; }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="urea_form.php">
                <i class="fas fa-industry"></i> BCIC-(DFMS)
            </a>
                   <?php 
                   $title= $_SESSION['username'];

                    if ($title == 'sfcl') {
                        $title = 'Shahjalal Fertilizer Company Ltd. (SFCL)';
                    } elseif ($title == 'jfcl') {
                        $title = 'Jamuna Fertilizer Company Ltd. (JFCL)';
                    } elseif ($title == 'afccl') {
                        $title = 'Ashuganj Fertilizer Company Ltd. (AFCCL)';
                    } elseif ($title == 'gpfplc') {
                        $title = 'Ghorashal Polash Fertilizer PLC (GPFPLC)';
                    } elseif ($title == 'cufl') {
                        $title = 'Chittagong Urea Fertilizer Ltd. (CUFL)';
                    } elseif ($title == 'tspcl') {
                        $title = 'TSP Complex Limited (TSPCL)';
                    } elseif ($title == 'dapfcl') {
                        $title = 'DAP Fertilizer Company Limited (DAPFCL)';
                    } elseif ($title == 'bisf') {
                        $title = 'Bangladesh Insulator & Sanitaryware Factory Ltd.(BISFL)';
                    } elseif ($title == 'cccl') {
                        $title = 'Chatak Cement Company Limited (CCCL)';
                    } elseif ($title == 'ugsf') {
                        $title = 'Osmania Glass Sheet Factory Limited (UGSFL)';
                    } elseif ($title == 'kpml') {
                        $title = 'Karnaphuli Paper Mills Limited (KPML)';
                    }
                   ?>
                    <div class="username-center text-uppercase "><b><h4 class="text-light poetsen-one-regular"> <?php echo $title; ?></h4></b></div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="urea_form.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- <li><a class="dropdown-item" href="pwd_change.php"><i class="fas fa-key"></i> Password Change</a></li> -->
                            <!-- <li><hr class="dropdown-divider"></li> -->
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="password-container">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="fas fa-key"></i> Change Password</h4>
                </div>
                <div class="card-body">
                    <?php if ($message && !$show_alert_and_redirect): ?>
                        <div class="<?php echo $message_type == 'success' ? 'message-success' : 'message-error'; ?>">
                            <i class="fas <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="passwordForm">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock"></i> Current Password
                            </label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key"></i> New Password
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                            <div class="password-strength mt-2" id="passwordStrength"></div>
                            <small class="form-text text-muted">
                                Password must be at least 6 characters long.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-key"></i> Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="form-text" id="confirmMessage"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-gradient btn-lg">
                                <i class="fas fa-sync-alt"></i> Change Password
                            </button>
                            <a href="urea_form.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Requirements Card -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Password Requirements</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success"></i> Minimum 6 characters long</li>
                        <li><i class="fas fa-check text-success"></i> Use a combination of letters and numbers</li>
                        <li><i class="fas fa-check text-success"></i> Avoid using common words or personal information</li>
                        <li><i class="fas fa-check text-success"></i> Consider using special characters for added security</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            let strength = 0;
            
            if (password.length >= 6) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/\d/)) strength += 25;
            if (password.match(/[^a-zA-Z\d]/)) strength += 25;
            
            strengthBar.className = 'password-strength';
            if (strength <= 25) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 50) {
                strengthBar.classList.add('strength-medium');
            } else if (strength <= 75) {
                strengthBar.classList.add('strength-strong');
            } else {
                strengthBar.classList.add('strength-very-strong');
            }
        });

        // Confirm password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            const message = document.getElementById('confirmMessage');
            
            if (confirmPassword === '') {
                message.innerHTML = '';
                this.classList.remove('is-valid', 'is-invalid');
            } else if (newPassword === confirmPassword) {
                message.innerHTML = '<i class="fas fa-check text-success"></i> Passwords match';
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                message.innerHTML = '<i class="fas fa-times text-danger"></i> Passwords do not match';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });

        // Form validation
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Please make sure passwords match!');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });

        // Success alert and redirect
        <?php if ($show_alert_and_redirect): ?>
        document.addEventListener('DOMContentLoaded', function() {
            alert('✅ Password changed successfully! Please login again with your new password.');
            setTimeout(function() {
                window.location.href = 'logout.php';
            }, 1000);
        });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
// Close database connection
if (isset($conn)) {
    mysqli_close($conn);
}
?>