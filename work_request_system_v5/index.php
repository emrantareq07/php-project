<?php
session_name('factory_work_request_db');
//session_start();
require_once 'db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = trim($_POST['emp_id'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate credentials
    $stmt = $conn->prepare("SELECT id, emp_id, password, full_name, role,emp_type, status FROM users WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if account is active
        if ($user['status'] !== 'active') {
            $error = "Your account is inactive. Please contact administrator.";
        }
        // Verify password
        elseif (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['emp_id'] = $user['emp_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['emp_type'] = $user['emp_type'];
            $_SESSION['logged_in'] = true;
            
            // Redirect to dashboard
            header("Location: includes/dashboard.php");
            exit;
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Invalid credentials";
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Request System - Login</title>
    
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
            --accent-color: #4cc9f0;
            --success-color: #38b000;
            --warning-color: #ff9e00;
            --danger-color: #f72585;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background elements */
        .bg-shape-1 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -150px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }
        
        .bg-shape-2 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            bottom: -100px;
            left: -50px;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        .bg-shape-3 {
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            background: rgba(255, 255, 255, 0.05);
            top: 50%;
            left: 10%;
            animation: morph 10s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
            100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.2;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: white;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .login-body {
            padding: 40px 35px;
        }
        
        .form-group-custom {
            position: relative;
            margin-bottom: 0.3rem;
        }
        
        .form-group-custom label {
            position: absolute;
            top: -10px;
            left: 50px;
            background: white;
            padding: 0 10px;
            font-size: 13px;
            color: var(--primary-color);
            font-weight: 600;
            z-index: 10;
            transform: translateY(50%);
            transition: all 0.3s;
        }
        
        .form-group-custom .input-group {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            background: white;
        }
        
        .form-group-custom .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }
        
        .form-group-custom .input-group-text {
            background: transparent;
            border: none;
            color: #a0aec0;
            padding: 1rem 1.25rem;
            font-size: 1.1rem;
        }
        
        .form-group-custom .form-control {
            border: none;
            border-left: 2px solid #e2e8f0;
            border-radius: 0;
            padding: 1rem 1.25rem;
            font-size: 16px;
            box-shadow: none !important;
        }
        
        .form-group-custom .form-control:focus {
            border-left-color: var(--primary-color);
        }
        
        .form-group-custom.error .input-group {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 3px rgba(247, 37, 133, 0.1);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-login:hover::after {
            left: 100%;
        }
        
        .alert-login {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .alert-danger-login {
            background: linear-gradient(135deg, rgba(247, 37, 133, 0.1) 0%, rgba(242, 100, 104, 0.1) 100%);
            border-left: 4px solid var(--danger-color);
            color: #2d3748;
        }
        
        .features-list {
            list-style: none;
            padding: 0;
            margin: 5px 0;
        }
        
        .features-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #4a5568;
            font-size: 14px;
        }
        
        .features-list li i {
            color: var(--primary-color);
        }
        
        .register-link {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        
        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .register-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .password-toggle {
            background: none;
            border: none;
            color: #a0aec0;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-card {
                max-width: 100%;
            }
            
            .login-body {
                padding: 30px 25px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .form-group-custom label {
                left: 45px;
            }
        }
        
        /* Loading animation for submit button */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Background shapes -->
    <div class="bg-shape-1"></div>
    <div class="bg-shape-2"></div>
    <div class="bg-shape-3"></div>
    
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-tools"></i>
            </div>
            <h2 class="text-white mb-1">Work Request System</h2>
            <p class="text-white-50 mb-0">Login to access your dashboard</p>
        </div>
        
        <!-- Body -->
        <div class="login-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger-login alert-login d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Login Failed</h6>
                        <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="loginForm">
                <!-- Employee ID Field -->
                <div class="form-group-custom mb-3">
                    <label for="emp_id">
                        <i class="fas fa-user me-1"></i> Employee ID
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" class="form-control" id="emp_id" name="emp_id" 
                               placeholder="Enter your employee ID" required autofocus>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group-custom mb-3">
                    <label for="password">
                        <i class="fas fa-key me-1"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" 
                               name="password" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- System Features -->
               <!--  <ul class="features-list mb-0">
                    <li><i class="fas fa-check-circle"></i> Secure authentication system</li>
                    <li><i class="fas fa-check-circle"></i> Role-based access control</li>
                    <li><i class="fas fa-check-circle"></i> Real-time work request tracking</li>
                    <li><i class="fas fa-check-circle"></i> Mobile-friendly interface</li>
                </ul> -->
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-login btn-lg mb-0 text-white" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-2"></i> Login to System
                </button>
                
                <!-- Register Link -->
                <div class="register-link">
                    <p class="mb-1">Don't have an account?</p>
                    <a href="includes/register.php" class="d-inline-flex align-items-center">
                        <i class="fas fa-user-plus me-2"></i> Request Account Registration
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
            
            // Form validation and loading animation
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            
            loginForm.addEventListener('submit', function(e) {
                const empId = document.getElementById('emp_id').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!empId || !password) {
                    e.preventDefault();
                    
                    // Add error styling to empty fields
                    if (!empId) {
                        document.querySelector('.form-group-custom:first-child').classList.add('error');
                        setTimeout(() => {
                            document.querySelector('.form-group-custom:first-child').classList.remove('error');
                        }, 500);
                    }
                    
                    if (!password) {
                        document.querySelectorAll('.form-group-custom')[1].classList.add('error');
                        setTimeout(() => {
                            document.querySelectorAll('.form-group-custom')[1].classList.remove('error');
                        }, 500);
                    }
                    
                    return false;
                }
                
                // Show loading state
                loginBtn.classList.add('btn-loading');
                loginBtn.disabled = true;
                
                // Re-enable after 5 seconds if still on page (form submission failed)
                setTimeout(() => {
                    loginBtn.classList.remove('btn-loading');
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i> Login to System';
                }, 5000);
            });
            
            // Clear error styling on input
            document.querySelectorAll('#emp_id, #password').forEach(field => {
                field.addEventListener('input', function() {
                    this.closest('.form-group-custom').classList.remove('error');
                });
            });
            
            // Auto-focus on employee ID field
            document.getElementById('emp_id').focus();
            
            // Add some interactive effects
            const inputGroups = document.querySelectorAll('.input-group');
            inputGroups.forEach(group => {
                const input = group.querySelector('.form-control');
                input.addEventListener('focus', function() {
                    this.closest('.input-group').style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.input-group').style.transform = 'translateY(0)';
                });
            });
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + / to focus on employee ID
                if (e.ctrlKey && e.key === '/') {
                    e.preventDefault();
                    document.getElementById('emp_id').focus();
                }
                
                // Ctrl + Enter to submit form
                if (e.ctrlKey && e.key === 'Enter') {
                    loginForm.requestSubmit();
                }
            });
        });
    </script>
</body>
</html>