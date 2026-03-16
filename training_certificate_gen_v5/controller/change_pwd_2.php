<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// $_SESSION['user_id'] already contains emp_id based on your comment
echo $emp_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Handle password change
$success = '';
$error = '';
$validation_errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($current_password)) {
        $validation_errors['current_password'] = 'Current password is required';
    }
    
    if (empty($new_password)) {
        $validation_errors['new_password'] = 'New password is required';
    } elseif (strlen($new_password) < 6) {
        $validation_errors['new_password'] = 'Password must be at least 6 characters long';
    }
    
    if (empty($confirm_password)) {
        $validation_errors['confirm_password'] = 'Please confirm your new password';
    } elseif ($new_password !== $confirm_password) {
        $validation_errors['confirm_password'] = 'New passwords do not match';
    }
    
    // If no validation errors, proceed with password change
    if (empty($validation_errors)) {
        // Get current password from database using emp_id
        $stmt = $conn->prepare("SELECT password FROM users_tbl WHERE emp_id = ?");
        $stmt->bind_param("s", $emp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user) {
            // Verify current password
            if (password_verify($current_password, $user['password'])) {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update password in database using emp_id
                $update_stmt = $conn->prepare("UPDATE users_tbl SET password = ?, updated_at = NOW() WHERE emp_id = ?");
                $update_stmt->bind_param("ss", $hashed_password, $emp_id);
                
                if ($update_stmt->execute()) {
                    $success = 'Password changed successfully! You will be redirected to login in 3 seconds.';
                    
                    // Destroy session and redirect after delay
                    session_destroy();
                    header("refresh:3;url=../index.php");
                    exit();
                } else {
                    $error = 'Failed to update password. Please try again.';
                }
                $update_stmt->close();
            } else {
                $validation_errors['current_password'] = 'Current password is incorrect';
            }
        } else {
            $error = 'User not found. Please log in again.';
            session_destroy();
            header("refresh:3;url=../index.php");
            exit();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password - Employee Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --card-bg: rgba(255, 255, 255, 0.9);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --light-bg: #f8fafc;
            --text-color: #1e293b;
            --text-muted: #64748b;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: var(--light-bg);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(100, 126, 234, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(118, 75, 162, 0.05) 0%, transparent 20%);
        }
        
        .password-container {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            transition: all 0.3s ease;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        
        .logo-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            border: 4px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 20px rgba(100, 126, 234, 0.2);
        }
        
        h2 {
            text-align: center;
            font-weight: 700;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 5px;
        }
        
        .subtitle {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.9rem;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #e2e8f0;
            color: var(--text-color);
            padding: 12px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: white;
            border-color: rgba(100, 126, 234, 0.5);
            color: var(--text-color);
            box-shadow: 0 0 0 0.25rem rgba(100, 126, 234, 0.15);
        }
        
        .form-label {
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        
        .input-group {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            z-index: 10;
        }
        
        .toggle-password:hover {
            color: #667eea;
        }
        
        .is-invalid {
            border-color: #ef4444 !important;
        }
        
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 500;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(100, 126, 234, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(100, 126, 234, 0.3);
        }
        
        .btn-outline-secondary {
            border: 1px solid #cbd5e1;
            color: #64748b;
            border-radius: 50px;
            padding: 10px 25px;
            margin-top: 15px;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #475569;
        }
        
        .password-strength {
            margin-top: 5px;
            font-size: 0.85rem;
        }
        
        .strength-meter {
            height: 5px;
            border-radius: 5px;
            background: rgba(0, 0, 0, 0.05);
            margin-top: 5px;
            overflow: hidden;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .strength-weak {
            background: #ef4444;
        }
        
        .strength-medium {
            background: #f59e0b;
        }
        
        .strength-strong {
            background: #10b981;
        }
        
        .requirements {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            font-size: 0.85rem;
            border: 1px solid #e2e8f0;
        }
        
        .requirements h6 {
            color: var(--text-muted);
            margin-bottom: 10px;
        }
        
        .requirements ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        
        .requirements li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .requirements i {
            margin-right: 8px;
            font-size: 0.8rem;
        }
        
        .text-valid {
            color: #10b981;
        }
        
        .text-invalid {
            color: #ef4444;
        }
        
        .back-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            border: 1px solid transparent;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #065f46;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #991b1b;
        }
        
        @media (max-width: 576px) {
            .password-container {
                padding: 15px;
            }
            
            .glass-card {
                padding: 30px 20px;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
                font-size: 30px;
            }
        }
    </style>
</head>
<body>

<div class="password-container">
    <div class="glass-card">
        <div class="logo-header">
            <div class="logo-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2>Change Password</h2>
            <div class="subtitle">
                Welcome, <span class="text-primary fw-semibold"><?php echo htmlspecialchars($user_name); ?></span>
                <div class="mt-1">
                    <small class="text-muted">Employee ID: <?php echo htmlspecialchars($emp_id); ?></small>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> <?php echo $success; ?>
                <div class="mt-2">
                    <div class="spinner-border spinner-border-sm text-success me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <small>Redirecting to login page...</small>
                </div>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="changePasswordForm" novalidate>
            <!-- Current Password -->
            <div class="mb-4">
                <label for="current_password" class="form-label">
                    <i class="bi bi-lock me-1"></i> Current Password
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control <?php echo isset($validation_errors['current_password']) ? 'is-invalid' : ''; ?>" 
                           id="current_password" 
                           name="current_password" 
                           placeholder="Enter your current password" 
                           required>
                    <button type="button" class="toggle-password" data-target="current_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <?php if (isset($validation_errors['current_password'])): ?>
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i> <?php echo $validation_errors['current_password']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label for="new_password" class="form-label">
                    <i class="bi bi-key me-1"></i> New Password
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control <?php echo isset($validation_errors['new_password']) ? 'is-invalid' : ''; ?>" 
                           id="new_password" 
                           name="new_password" 
                           placeholder="Enter your new password" 
                           required>
                    <button type="button" class="toggle-password" data-target="new_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <?php if (isset($validation_errors['new_password'])): ?>
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i> <?php echo $validation_errors['new_password']; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Password Strength Meter -->
                <div class="password-strength">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Password strength:</small>
                        <small id="strength-text" class="fw-medium">None</small>
                    </div>
                    <div class="strength-meter">
                        <div id="strength-fill" class="strength-fill"></div>
                    </div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="confirm_password" class="form-label">
                    <i class="bi bi-key-fill me-1"></i> Confirm New Password
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control <?php echo isset($validation_errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                           id="confirm_password" 
                           name="confirm_password" 
                           placeholder="Confirm your new password" 
                           required>
                    <button type="button" class="toggle-password" data-target="confirm_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <?php if (isset($validation_errors['confirm_password'])): ?>
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i> <?php echo $validation_errors['confirm_password']; ?>
                    </div>
                <?php endif; ?>
                <div id="password-match" class="mt-2" style="display: none;">
                    <small><i class="bi bi-check-circle text-success me-1"></i> <span class="text-success fw-medium">Passwords match</span></small>
                </div>
                <div id="password-mismatch" class="mt-2" style="display: none;">
                    <small><i class="bi bi-x-circle text-danger me-1"></i> <span class="text-danger fw-medium">Passwords do not match</span></small>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="requirements">
                <h6><i class="bi bi-info-circle me-1"></i> Password Requirements</h6>
                <ul>
                    <li>
                        <i id="req-length" class="bi bi-circle text-muted"></i>
                        <span class="text-muted">At least 6 characters</span>
                    </li>
                    <li>
                        <i id="req-uppercase" class="bi bi-circle text-muted"></i>
                        <span class="text-muted">Contains uppercase letter</span>
                    </li>
                    <li>
                        <i id="req-lowercase" class="bi bi-circle text-muted"></i>
                        <span class="text-muted">Contains lowercase letter</span>
                    </li>
                    <li>
                        <i id="req-number" class="bi bi-circle text-muted"></i>
                        <span class="text-muted">Contains number</span>
                    </li>
                </ul>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-key me-2"></i> Change Password
            </button>

            <!-- Cancel Button -->
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i> Cancel
            </a>
        </form>

        <div class="back-link">
            <a href="dashboard.php"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    });

    // Password strength checker
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    
    // Requirement icons
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    
    // Password match indicators
    const matchIndicator = document.getElementById('password-match');
    const mismatchIndicator = document.getElementById('password-mismatch');

    function checkPasswordStrength(password) {
        let strength = 0;
        
        // Check length
        if (password.length >= 6) {
            strength += 25;
            reqLength.className = 'bi bi-check-circle text-valid';
            reqLength.nextElementSibling.className = 'text-valid';
        } else {
            reqLength.className = 'bi bi-x-circle text-invalid';
            reqLength.nextElementSibling.className = 'text-invalid';
        }
        
        // Check uppercase
        if (/[A-Z]/.test(password)) {
            strength += 25;
            reqUppercase.className = 'bi bi-check-circle text-valid';
            reqUppercase.nextElementSibling.className = 'text-valid';
        } else {
            reqUppercase.className = 'bi bi-x-circle text-invalid';
            reqUppercase.nextElementSibling.className = 'text-invalid';
        }
        
        // Check lowercase
        if (/[a-z]/.test(password)) {
            strength += 25;
            reqLowercase.className = 'bi bi-check-circle text-valid';
            reqLowercase.nextElementSibling.className = 'text-valid';
        } else {
            reqLowercase.className = 'bi bi-x-circle text-invalid';
            reqLowercase.nextElementSibling.className = 'text-invalid';
        }
        
        // Check numbers
        if (/[0-9]/.test(password)) {
            strength += 25;
            reqNumber.className = 'bi bi-check-circle text-valid';
            reqNumber.nextElementSibling.className = 'text-valid';
        } else {
            reqNumber.className = 'bi bi-x-circle text-invalid';
            reqNumber.nextElementSibling.className = 'text-invalid';
        }
        
        // Update strength meter
        strengthFill.style.width = strength + '%';
        
        if (strength === 0) {
            strengthFill.className = 'strength-fill';
            strengthText.textContent = 'None';
            strengthText.className = 'fw-medium text-muted';
        } else if (strength < 50) {
            strengthFill.className = 'strength-fill strength-weak';
            strengthText.textContent = 'Weak';
            strengthText.className = 'fw-medium text-danger';
        } else if (strength < 75) {
            strengthFill.className = 'strength-fill strength-medium';
            strengthText.textContent = 'Medium';
            strengthText.className = 'fw-medium text-warning';
        } else {
            strengthFill.className = 'strength-fill strength-strong';
            strengthText.textContent = 'Strong';
            strengthText.className = 'fw-medium text-success';
        }
    }

    function checkPasswordMatch() {
        const password = newPasswordInput.value;
        const confirm = confirmPasswordInput.value;
        
        if (confirm === '') {
            matchIndicator.style.display = 'none';
            mismatchIndicator.style.display = 'none';
            return;
        }
        
        if (password === confirm) {
            matchIndicator.style.display = 'block';
            mismatchIndicator.style.display = 'none';
        } else {
            matchIndicator.style.display = 'none';
            mismatchIndicator.style.display = 'block';
        }
    }

    newPasswordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });
    
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // Form validation
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const password = newPasswordInput.value;
        const confirm = confirmPasswordInput.value;
        
        if (password !== confirm) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'New password and confirm password do not match.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Too Short',
                text: 'Password must be at least 6 characters long.',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
        
        // Check if form was submitted due to success redirect
        <?php if ($success): ?>
            e.preventDefault();
            return false;
        <?php endif; ?>
    });

    // Success message handling
    <?php if ($success): ?>
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Password Changed!',
                text: 'Please login with your new password.',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                background: '#f8fafc',
                color: '#1e293b'
            }).then(() => {
                window.location.href = '../index.php';
            });
        }, 1000);
    <?php endif; ?>
</script>

</body>
</html>