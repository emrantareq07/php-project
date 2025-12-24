<?php
session_name('transport_db');
session_start();

date_default_timezone_set("Asia/Dhaka");

include 'db/db_connection.php';

if (isset($_SESSION['username'])) {
    header("Location: includes/dashboard.php");
    exit;
}

// Helper: Get user IP
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
    else return $_SERVER['REMOTE_ADDR'];
}

$login_failed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $ip = getUserIP();
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $status = 'failed';
    $event_type = 'login';
    $login_time = date('Y-m-d H:i:s');

    // Fetch user using PDO
    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $status = 'success';

        // Insert login success log
        $log = $conn->prepare("
            INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $log->execute([$username, $event_type, $ip, $user_agent, $status, $login_time]);

        // Redirect
        if ($user['role'] === 'admin' && $username === 'admin') {
            header("Location: includes/admin_dashboard.php");
            exit;
        } elseif ($user['role'] === 'sadmin' && $username === 'sadmin') {
            header("Location: includes/sadmin_dashboard.php");
            exit;
        } else {
            header("Location: includes/dashboard.php");
            exit;
        }
    }

    // Login Failed → Log attempt
    $log = $conn->prepare("
        INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $log->execute([$username, $event_type, $ip, $user_agent, 'failed', $login_time]);

    $login_failed = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCIC Vehicle Database | Login</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #00bcd4;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Elements */
        .vehicle-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .car-animation {
            position: absolute;
            background-size: contain;
            background-repeat: no-repeat;
            width: 80px;
            height: 80px;
            animation: float 20s linear infinite;
            opacity: 0.1;
        }

        .car-1 { top: 15%; left: -100px; animation-delay: 0s; animation-duration: 25s; }
        .car-2 { top: 30%; left: -100px; animation-delay: 5s; animation-duration: 30s; }
        .car-3 { top: 50%; left: -100px; animation-delay: 10s; animation-duration: 35s; }
        .car-4 { top: 70%; left: -100px; animation-delay: 15s; animation-duration: 40s; }

        .road-line {
            position: absolute;
            bottom: 20%;
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
        }

        .road-line::before {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: rgba(255, 255, 255, 0.5);
            animation: roadLine 1.5s linear infinite;
        }

        @keyframes float {
            0% { transform: translateX(-100px) rotate(0deg); }
            100% { transform: translateX(calc(100vw + 100px)) rotate(0deg); }
        }

        @keyframes roadLine {
            0% { transform: translateX(-50px); }
            100% { transform: translateX(calc(100vw + 50px)); }
        }

        /* Pulse Animation for Logo */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Shake Animation for Failed Login */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
            animation: pulse 3s infinite;
        }

        .logo-container i {
            font-size: 3rem;
            color: white;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: none;
            color: var(--primary-color);
            width: 45px;
            justify-content: center;
        }

        .form-control {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .toggle-password {
            cursor: pointer;
            background-color: #f8f9fa;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            background-color: #e9ecef;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% { transform: scale(0, 0); opacity: 0.5; }
            100% { transform: scale(20, 20); opacity: 0; }
        }

        .footer-text {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }

        /* Toast container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: rgba(220, 53, 69, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.3);
        }

        /* Loading Animation - FIXED: Hidden by default */
        .loader {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            z-index: 100;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
        }

        .loader.show {
            display: flex !important;
            opacity: 1;
        }

        .loader.hide {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }
            
            .login-card {
                padding: 1.5rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
            
            .logo-container {
                width: 80px;
                height: 80px;
            }
            
            .logo-container i {
                font-size: 2.5rem;
            }
            
            .car-animation {
                width: 60px;
                height: 60px;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .login-card {
                background: rgba(33, 37, 41, 0.95);
                color: #f8f9fa;
            }
            
            .login-subtitle,
            .footer-text {
                color: #adb5bd;
            }
            
            .form-label {
                color: #dee2e6;
            }
            
            .input-group {
                border-color: #495057;
                background: #343a40;
            }
            
            .input-group-text {
                background-color: #495057;
                color: #dee2e6;
            }
            
            .form-control {
                background: #343a40;
                color: #f8f9fa;
            }
            
            .loader {
                background: rgba(33, 37, 41, 0.95);
            }
        }
    </style>
</head>
<body>

<!-- Animated Background Elements -->
<div class="vehicle-bg">
    <div class="car-animation car-1"></div>
    <div class="car-animation car-2"></div>
    <div class="car-animation car-3"></div>
    <div class="car-animation car-4"></div>
    <div class="road-line"></div>
</div>

<div class="login-container">
    <div class="login-card">
        <!-- Loading Overlay - Initially hidden -->
        <div class="loader" id="loader">
            <div class="spinner"></div>
            <div class="text-center mt-3">
                <p class="text-primary fw-semibold">Authenticating...</p>
                <p class="text-muted small">Please wait while we verify your credentials</p>
            </div>
        </div>

        <div class="login-header">
            <div class="logo-container">
                <i class="fas fa-car"></i>
            </div>
            <h1 class="login-title">BCIC Vehicle Database</h1>
            <p class="login-subtitle">Sign in to manage vehicle fleet</p>
        </div>

        <form method="POST" action="" id="loginForm">
            <div class="mb-4">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" 
                           name="username" 
                           class="form-control" 
                           placeholder="Enter your username" 
                           required
                           autocomplete="username"
                           autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control" 
                           placeholder="Enter your password" 
                           required
                           autocomplete="current-password">
                    <span class="input-group-text toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
                </button>
            </div>
        </form>

        <div class="footer-text">
            &copy; <?php echo date("Y"); ?> Bangladesh Chemical Industries Corporation | All Rights Reserved
            <div class="mt-2">
                <small>Secure Access • Vehicle Management System</small>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="toast-container">
<?php if($login_failed): ?>
    <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Login Failed!</strong> Invalid username or password.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Password Toggle
function togglePassword() {
    const password = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");
    
    if (password.type === "password") {
        password.type = "text";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        password.type = "password";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

// Show loader function
function showLoader() {
    const loader = document.getElementById('loader');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loader) {
        loader.classList.remove('hide');
        loader.classList.add('show');
    }
    
    if (loginBtn) {
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
    }
}

// Hide loader function
function hideLoader() {
    const loader = document.getElementById('loader');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loader) {
        loader.classList.remove('show');
        loader.classList.add('hide');
        
        // Hide completely after animation
        setTimeout(() => {
            loader.style.display = 'none';
        }, 300);
    }
    
    if (loginBtn) {
        loginBtn.disabled = false;
        loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard';
    }
}

// Auto-hide toast after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const errorToast = document.getElementById('errorToast');
    if (errorToast) {
        errorToast.style.animation = 'shake 0.5s ease-in-out';
        
        const toast = new bootstrap.Toast(errorToast, { 
            delay: 5000,
            autohide: true 
        });
        toast.show();
        
        setTimeout(() => {
            errorToast.style.animation = '';
        }, 500);
    }
    
    // Form submission handler
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    loginForm.addEventListener('submit', function(e) {
        // Basic form validation
        const username = document.querySelector('input[name="username"]').value.trim();
        const password = document.querySelector('input[name="password"]').value.trim();
        
        if (!username || !password) {
            e.preventDefault();
            alert('Please enter both username and password');
            return;
        }
        
        // Show loading animation
        showLoader();
        
        // Set timeout to hide loader if submission takes too long
        setTimeout(() => {
            hideLoader();
        }, 10000); // 10 seconds timeout
    });
    
    // Input focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+Enter to submit form
        if (e.ctrlKey && e.key === 'Enter') {
            loginForm.requestSubmit();
        }
        
        // Esc to clear form
        if (e.key === 'Escape') {
            loginForm.reset();
            document.querySelector('[autofocus]').focus();
        }
    });
    
    // Focus username field on page load
    document.querySelector('[autofocus]').focus();
    
    // Add hover effect to login button
    const loginButton = document.getElementById('loginBtn');
    if (loginButton) {
        loginButton.addEventListener('mouseenter', function() {
            if (!this.disabled) {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            }
        });
        
        loginButton.addEventListener('mouseleave', function() {
            if (!this.disabled) {
                this.style.transform = 'translateY(0) scale(1)';
            }
        });
    }
    
    // Prevent multiple submissions
    let isSubmitting = false;
    loginForm.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        isSubmitting = true;
    });
});

// Create floating vehicle icons with background images
function createFloatingVehicles() {
    const vehicleTypes = ['car', 'truck', 'bus', 'motorcycle'];
    
    // Define SVG data URLs for different vehicle types
    const svgData = {
        car: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z'/%3E%3C/svg%3E",
        truck: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z'/%3E%3C/svg%3E",
        bus: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z'/%3E%3C/svg%3E",
        motorcycle: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M19.44 9.03L15.41 5H11v2h3.59l2 2H5c-2.8 0-5 2.2-5 5s2.2 5 5 5c2.46 0 4.45-1.69 4.9-4h1.65l2.77-2.77c-.21.54-.32 1.14-.32 1.77 0 2.8 2.2 5 5 5s5-2.2 5-5c0-2.65-1.97-4.77-4.56-4.97zM7.82 15C7.4 16.15 6.28 17 5 17c-1.63 0-3-1.37-3-3s1.37-3 3-3c1.28 0 2.4.85 2.82 2H5v2h2.82zM19 17c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z'/%3E%3C/svg%3E"
    };
    
    const vehicleContainer = document.querySelector('.vehicle-bg');
    
    // Add initial cars with background images
    const cars = document.querySelectorAll('.car-animation');
    cars.forEach((car, index) => {
        const type = vehicleTypes[index % vehicleTypes.length];
        car.style.backgroundImage = `url("${svgData[type]}")`;
    });
    
    // Add more floating vehicles
    for (let i = 0; i < 6; i++) {
        const vehicle = document.createElement('div');
        vehicle.className = 'car-animation';
        vehicle.style.top = `${Math.random() * 80 + 10}%`;
        vehicle.style.left = '-100px';
        vehicle.style.animationDelay = `${Math.random() * 20}s`;
        vehicle.style.animationDuration = `${Math.random() * 20 + 20}s`;
        vehicle.style.opacity = Math.random() * 0.1 + 0.05;
        
        const type = vehicleTypes[Math.floor(Math.random() * vehicleTypes.length)];
        vehicle.style.backgroundImage = `url("${svgData[type]}")`;
        
        vehicleContainer.appendChild(vehicle);
    }
}

// Initialize floating vehicles when page loads
window.addEventListener('load', createFloatingVehicles);

// Add a subtle fade-in animation for the login card
window.addEventListener('load', function() {
    const loginCard = document.querySelector('.login-card');
    if (loginCard) {
        loginCard.style.opacity = '0';
        loginCard.style.transform = 'translateY(20px)';
        loginCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            loginCard.style.opacity = '1';
            loginCard.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>
</body>
</html>