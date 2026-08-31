<?php
session_name('man_power_db');
session_start();

date_default_timezone_set("Asia/Dhaka");

include('db/db.php');

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

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $ip = getUserIP();
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $status = 'failed';
    $event_type = 'login';
    $login_time = date('Y-m-d H:i:s');

    // Fetch hashed password from DB
    $stmt = $conn->prepare("SELECT id, username, password, factory_name, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $_SESSION['factory_name'] = $user['factory_name'];
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['last_activity'] = time(); // Track last activity
            
            $status = 'success';
            
            // ***** UPDATE LOGIN STATUS TO ONLINE (1) *****
            $update_status = $conn->prepare("UPDATE users SET login_status = 1 WHERE id = ?");
            $update_status->bind_param("i", $user['id']);
            $update_status->execute();
            $update_status->close();

            // Insert login attempt into log_table
            $log = $conn->prepare("INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $log->bind_param("ssssss", $username, $event_type, $ip, $user_agent, $status, $login_time);
            $log->execute();
            $log->close();
            
            if ($_SESSION['role'] === 'admin' || $_SESSION['username'] === 'admin') {
                 // if ($_SESSION['role'] === 'admin' && $_SESSION['username'] === 'admin') {
                echo "<script>window.location.href='includes/admin_dashboard.php';</script>";
                exit;
            } elseif ($_SESSION['role'] === 'sadmin' && $_SESSION['username'] === 'sadmin') {
                echo "<script>window.location.href='includes/sadmin_dashboard.php';</script>";
                exit;
            } else {
                echo "<script>window.location.href='includes/dashboard.php';</script>";
                exit;
            }
        }
    }

    // If login failed
    $login_failed = true;

    // Insert failed login attempt
    $log = $conn->prepare("INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $log->bind_param("ssssss", $username, $event_type, $ip, $user_agent, $status, $login_time);
    $log->execute();
    $log->close();

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Man Power Management | Login</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Particle.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
        :root {
            --primary-blue: #007bff;
            --secondary-blue: #00bcd4;
            --accent-green: #28a745;
            --dark-blue: #0056b3;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family:'Noto Sans Bengali',sans-serif;
            
        }
        
        body {

            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            min-height: 100vh;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            padding: 1rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        /* Particle.js background - fully responsive */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 0.75rem;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            padding: 2rem 2rem 2rem 2rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 45px rgba(0,0,0,0.3);
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-green));
        }
        
        .header-container {
            text-align: center;
            margin-bottom: 1.8rem;
            position: relative;
        }
        
        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .logo-ring {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: pulse 2s infinite;
            box-shadow: 0 0 20px rgba(0,123,255,0.3);
        }
        
        .logo-ring img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: contain;
        }
        
        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        
        .floating-icon {
            position: absolute;
            color: var(--primary-blue);
            opacity: 0.25;
            font-size: 1.3rem;
            animation: float 6s infinite ease-in-out;
        }
        
        .floating-icon:nth-child(1) { top: 5%; left: 5%; animation-delay: 0s; font-size: 1rem; }
        .floating-icon:nth-child(2) { top: 15%; right: 8%; animation-delay: 1s; font-size: 1.2rem; }
        .floating-icon:nth-child(3) { bottom: 20%; left: 8%; animation-delay: 2s; font-size: 1rem; }
        .floating-icon:nth-child(4) { bottom: 10%; right: 10%; animation-delay: 3s; font-size: 1.1rem; }
        
        .login-card h4 {
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
            font-size: 1.6rem;
        }
        
        .login-card h4::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 20%;
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent-green), transparent);
        }
        
        .subtitle {
            color: #666;
            font-size: 0.85rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .input-group {
            margin-bottom: 1.3rem;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
            background: #fff;
        }
        
        .input-group:focus-within {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: none;
            color: var(--primary-blue);
            min-width: 45px;
            justify-content: center;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: none;
            padding: 12px 14px;
            background: #fff;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            box-shadow: none;
            background: #fefefe;
        }
        
        .toggle-password {
            cursor: pointer;
            background: #f8f9fa;
            border-left: 1px solid #e9ecef;
            transition: background 0.3s ease;
        }
        
        .toggle-password:hover {
            background: #e9ecef;
        }
        
        .login-btn {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border: none;
            padding: 12px 12px;
            font-weight: 600;
            border-radius: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 1rem;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,123,255,0.35);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .login-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.15);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }
        
        .login-btn:hover::after {
            opacity: 1;
            transform: rotate(45deg) translate(10%, 10%);
        }
        
        .additional-links {
            margin-top: 1.2rem;
            text-align: center;
            font-size: 0.85rem;
        }
        
        .additional-links a {
            color: var(--primary-blue);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .additional-links a:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
            flex-wrap: wrap;
            gap: 0.8rem;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
            min-width: 70px;
        }
        
        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            display: block;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #666;
        }
        
        .footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.7rem;
            line-height: 1.4;
        }
        
        /* Toast container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 320px;
            width: auto;
        }
        
        .success-toast {
            background: linear-gradient(135deg, var(--accent-green), #20c997);
            color: white;
            border: none;
        }
        
        .error-toast {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: white;
            border: none;
        }
        
        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 20px rgba(0,123,255,0.3); }
            50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(0,123,255,0.5); }
            100% { transform: scale(1); box-shadow: 0 0 20px rgba(0,123,255,0.3); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .power-indicator {
            height: 5px;
            background: #e9ecef;
            border-radius: 4px;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .power-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #28a745, #007bff);
            transition: width 0.3s ease;
            border-radius: 4px;
        }
        
        /* ========== FULLY RESPONSIVE ENHANCEMENTS ========== */
        @media (max-width: 576px) {
            body {
                padding: 0.5rem;
                align-items: flex-start;
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
            
            .login-container {
                max-width: 100%;
                padding: 0 0.5rem;
            }
            
            .login-card {
                padding: 1.5rem 1.2rem;
                border-radius: 24px;
            }
            
            .logo-ring {
                width: 75px;
                height: 75px;
            }
            
            .logo-ring img {
                width: 48px;
                height: 48px;
            }
            
            .login-card h4 {
                font-size: 1.4rem;
            }
            
            .form-control {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
            
            .input-group-text {
                min-width: 40px;
                font-size: 0.85rem;
            }
            
            .login-btn {
                padding: 10px;
                font-size: 0.95rem;
            }
            
            .stats-container {
                flex-direction: row;
                gap: 0.5rem;
            }
            
            .stat-value {
                font-size: 1rem;
            }
            
            .stat-label {
                font-size: 0.7rem;
            }
            
            .floating-icon {
                display: none;
            }
            
            .toast-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: calc(100% - 20px);
            }
            
            .footer {
                font-size: 0.65rem;
            }
            
            .form-check-label {
                font-size: 0.85rem;
            }
        }
        
        @media (min-width: 577px) and (max-width: 768px) {
            .login-container {
                max-width: 450px;
            }
            
            .login-card {
                padding: 2rem 1.8rem;
            }
            
            .logo-ring {
                width: 85px;
                height: 85px;
            }
            
            .floating-icon {
                opacity: 0.2;
                font-size: 1rem;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .login-container {
                max-width: 480px;
            }
        }
        
        @media (min-width: 1200px) {
            .login-container {
                max-width: 520px;
            }
            
            .login-card {
                padding: 2.5rem;
            }
        }
        
        /* Ensure form elements don't overflow on very small devices */
        @media (max-width: 380px) {
            .login-card {
                padding: 1.2rem;
            }
            
            .form-label {
                font-size: 0.8rem;
            }
            
            .input-group-text {
                min-width: 35px;
                padding: 0 8px;
            }
            
            .stat-value {
                font-size: 0.9rem;
            }
        }
        
        /* Landscape mode for phones */
        @media (max-height: 550px) and (orientation: landscape) {
            body {
                align-items: flex-start;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .login-card {
                padding: 1rem 1.5rem;
            }
            
            .logo-ring {
                width: 55px;
                height: 55px;
                margin-bottom: 0.5rem;
            }
            
            .logo-ring img {
                width: 38px;
                height: 38px;
            }
            
            .header-container {
                margin-bottom: 0.8rem;
            }
            
            .login-card h4 {
                font-size: 1.2rem;
            }
            
            .input-group {
                margin-bottom: 0.8rem;
            }
            
            .stats-container {
                margin-top: 0.8rem;
                padding-top: 0.5rem;
            }
            
            .footer {
                margin-top: 0.8rem;
            }
        }
        
        /* Touch friendly adjustments */
        .login-btn, .toggle-password {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Ensure particles don't cause scroll issues */
        #particles-js canvas {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
        }
        
        /* Better spacing for remember me */
        .form-check {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<!-- Particle.js Background -->
<div id="particles-js"></div>

<div class="login-container animate__animated animate__fadeIn">
    <div class="login-card">
        <!-- Floating Icons -->
        <div class="floating-icons">
            <i class="fas fa-users floating-icon"></i>
            <i class="fas fa-chart-line floating-icon"></i>
            <i class="fas fa-cogs floating-icon"></i>
            <i class="fas fa-tachometer-alt floating-icon"></i>
        </div>
        
        <div class="header-container">
            <div class="logo-ring">
                <img src="assets/bcic_logo.png" alt="BCIC Logo" onerror="this.src='https://via.placeholder.com/60x60/007bff/ffffff?text=MPM'">
            </div>
            <h4 class="animate__animated animate__fadeInDown">বিসিআইসি জনবলের পরিসংখ্যানিক তথ্য সংগ্রহ সংক্রান্ত সফটওয়্যার </h4>
            <!-- <p class="subtitle">Optimize your workforce with intelligent management</p> -->
        </div>

        <form method="POST" action="" id="loginForm">
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required 
                           autocomplete="username">
                    <span class="input-group-text" style="background: #f8f9fa;">
                        <i class="fas fa-check text-success" style="display: none;" id="usernameValid"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Enter password" required autocomplete="current-password"
                           onkeyup="updatePasswordStrength()">
                    <span class="input-group-text toggle-password" onclick="togglePassword()">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <div class="power-indicator">
                    <div class="power-fill" id="passwordStrength"></div>
                </div>
                <small class="text-muted mt-1 d-block">Password strength: <span id="strengthText">None</span></small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn login-btn btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i> <span id="loginText">Sign In</span>
                    <span class="spinner-border spinner-border-sm ms-2" style="display: none;" id="loginSpinner"></span>
                </button>
            </div>

           <!--  <div class="additional-links">
                <a href="#" onclick="return false;" class="me-3"><i class="fas fa-key"></i> Forgot Password?</a>
                <a href="#" onclick="return false;"><i class="fas fa-user-plus"></i> Request Access</a>
            </div> -->

          <!--   <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-value" id="userCount">0</span>
                    <span class="stat-label">Active Users</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value" id="loginCount">0</span>
                    <span class="stat-label">Today's Logins</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">24/7</span>
                    <span class="stat-label">Availability</span>
                </div>
            </div> -->

            <div class="footer">
                &copy; <?php echo date("Y"); ?> Design & Developed ICT Division, BCIC | All Rights Reserved
               <!--  <div class="mt-1 small">
                    <i class="fas fa-shield-alt me-1"></i> Secure Login | 
                    <i class="fas fa-bolt ms-2 me-1"></i> Powered by MPM v2.0
                </div> -->
            </div>
        </form>
    </div>
</div>

<!-- Toast container -->
<div class="toast-container">
    <?php if($login_failed): ?>
    <div class="toast error-toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong class="me-auto">Login Failed</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Incorrect username or password. Please try again.
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Initialize Particle.js background
document.addEventListener('DOMContentLoaded', function() {
    // Particle.js configuration
    particlesJS('particles-js', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: "#ffffff" },
            shape: { type: "circle" },
            opacity: { value: 0.3, random: true },
            size: { value: 3, random: true },
            line_linked: {
                enable: true,
                distance: 150,
                color: "#ffffff",
                opacity: 0.2,
                width: 1
            },
            move: {
                enable: true,
                speed: 2,
                direction: "none",
                random: true,
                straight: false,
                out_mode: "out",
                bounce: false
            }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { enable: true, mode: "repulse" },
                onclick: { enable: true, mode: "push" }
            }
        }
    });

    // Animate stats
    animateCounters();
    
    // Add username validation
    const usernameInput = document.querySelector('input[name="username"]');
    if(usernameInput) {
        usernameInput.addEventListener('input', function(e) {
            const isValid = e.target.value.length >= 3;
            const icon = document.getElementById('usernameValid');
            if(icon) icon.style.display = isValid ? 'inline' : 'none';
        });
    }

    // Form submission animation
    const loginForm = document.getElementById('loginForm');
    if(loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const btn = document.querySelector('.login-btn');
            const text = document.getElementById('loginText');
            const spinner = document.getElementById('loginSpinner');
            
            if(btn && text && spinner) {
                text.textContent = 'Authenticating...';
                spinner.style.display = 'inline-block';
                btn.disabled = true;
                
                // Simulate processing (doesn't affect actual PHP submit)
                setTimeout(() => {
                    if(!this.checkValidity()) {
                        text.textContent = 'Sign In';
                        spinner.style.display = 'none';
                        btn.disabled = false;
                    }
                }, 1000);
            }
        });
    }
});

function togglePassword() {
    const password = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");
    if (password && eyeIcon) {
        if (password.type === "password") {
            password.type = "text";
            eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            password.type = "password";
            eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
}

function updatePasswordStrength() {
    const password = document.getElementById("password");
    const strengthBar = document.getElementById("passwordStrength");
    const strengthText = document.getElementById("strengthText");
    
    if(!password || !strengthBar || !strengthText) return;
    
    const passValue = password.value;
    let strength = 0;
    let text = "None";
    let color = "#dc3545";
    
    if (passValue.length >= 8) strength += 25;
    if (/[A-Z]/.test(passValue)) strength += 25;
    if (/[0-9]/.test(passValue)) strength += 25;
    if (/[^A-Za-z0-9]/.test(passValue)) strength += 25;
    
    strengthBar.style.width = strength + "%";
    
    if (strength < 25) {
        text = "Weak";
        color = "#dc3545";
    } else if (strength < 50) {
        text = "Fair";
        color = "#ffc107";
    } else if (strength < 75) {
        text = "Good";
        color = "#28a745";
    } else {
        text = "Strong";
        color = "#007bff";
    }
    
    strengthText.textContent = text;
    strengthBar.style.background = `linear-gradient(90deg, ${color}, ${color})`;
}

function animateCounters() {
    // Simulate API call for stats
    const userCount = Math.floor(Math.random() * 50) + 100;
    const loginCount = Math.floor(Math.random() * 20) + 10;
    
    animateValue("userCount", 0, userCount, 2000);
    animateValue("loginCount", 0, loginCount, 1500);
}

function animateValue(id, start, end, duration) {
    const obj = document.getElementById(id);
    if (!obj) return;
    let startTimestamp = null;
    
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        obj.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    
    window.requestAnimationFrame(step);
}

// Auto-hide toast after 5 seconds
setTimeout(() => {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        if (typeof bootstrap !== 'undefined') {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        } else {
            toast.style.display = 'none';
        }
    });
}, 5000);

// Add floating animation to login card on load
window.addEventListener('load', () => {
    const card = document.querySelector('.login-card');
    if(card) {
        card.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            card.classList.remove('animate__pulse');
        }, 1000);
    }
});

// Ensure particles background resizes on window resize
window.addEventListener('resize', function() {
    if(window.pJSDom && window.pJSDom[0]) {
        window.pJSDom[0].pJS.fn.vendors.densityAutoParticles();
    }
});
</script>

</body>
</html>