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
    $stmt = $conn->prepare("SELECT username, password,factory_name,role FROM users WHERE username = ?");
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
            $status = 'success';

            // Insert login attempt into log_table
            $log = $conn->prepare("INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $log->bind_param("ssssss", $username, $event_type, $ip, $user_agent, $status, $login_time);
            $log->execute();
            $log->close();
            
            if ($_SESSION['role'] === 'admin' && $_SESSION['username'] === 'admin') {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        :root {
            --primary-blue: #007bff;
            --secondary-blue: #00bcd4;
            --accent-green: #28a745;
            --dark-blue: #0056b3;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            position: relative;
        }
        
        /* Particle.js background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 80%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
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
            margin-bottom: 2rem;
            position: relative;
        }
        
        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .logo-ring {
            width: 100px;
            height: 100px;
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: contain;
        }
        
        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        
        .floating-icon {
            position: absolute;
            color: var(--primary-blue);
            opacity: 0.3;
            font-size: 24px;
            animation: float 6s infinite ease-in-out;
        }
        
        .floating-icon:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 20%; right: 15%; animation-delay: 1s; }
        .floating-icon:nth-child(3) { bottom: 30%; left: 15%; animation-delay: 2s; }
        .floating-icon:nth-child(4) { bottom: 15%; right: 20%; animation-delay: 3s; }
        
        .login-card h4 {
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }
        
        .login-card h4::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent-green), transparent);
        }
        
        .subtitle {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        
        .input-group:focus-within {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: none;
            color: var(--primary-blue);
            min-width: 45px;
            justify-content: center;
        }
        
        .form-control {
            border: none;
            padding: 12px 15px;
            background: #fff;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            box-shadow: none;
            background: #f8f9fa;
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
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,123,255,0.3);
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
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }
        
        .login-btn:hover::after {
            opacity: 1;
            transform: rotate(45deg) translate(10%, 10%);
        }
        
        .additional-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
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
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            display: block;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.8rem;
        }
        
        /* Toast container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
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
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
            }
            
            .stats-container {
                flex-direction: column;
                gap: 10px;
            }
        }
        
        .power-indicator {
            height: 4px;
            background: linear-gradient(90deg, #e9ecef 30%, #28a745 70%, #007bff 100%);
            border-radius: 2px;
            margin-top: 5px;
            position: relative;
            overflow: hidden;
        }
        
        .power-fill {
            height: 80%;
            width: 0%;
            background: linear-gradient(90deg, #28a745, #007bff);
            transition: width 0.5s ease;
            border-radius: 2px;
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
            <h4 class="animate__animated animate__fadeInDown">Man Power Management</h4>
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
    document.querySelector('input[name="username"]').addEventListener('input', function(e) {
        const isValid = e.target.value.length >= 3;
        const icon = document.getElementById('usernameValid');
        icon.style.display = isValid ? 'inline' : 'none';
    });

    // Form submission animation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = document.querySelector('.login-btn');
        const text = document.getElementById('loginText');
        const spinner = document.getElementById('loginSpinner');
        
        text.textContent = 'Authenticating...';
        spinner.style.display = 'inline-block';
        btn.disabled = true;
        
        // Simulate processing
        setTimeout(() => {
            if(!this.checkValidity()) {
                text.textContent = 'Sign In';
                spinner.style.display = 'none';
                btn.disabled = false;
            }
        }, 1000);
    });
});

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

function updatePasswordStrength() {
    const password = document.getElementById("password").value;
    const strengthBar = document.getElementById("passwordStrength");
    const strengthText = document.getElementById("strengthText");
    
    let strength = 0;
    let text = "None";
    let color = "#dc3545";
    
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
    
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
        const bsToast = new bootstrap.Toast(toast);
        bsToast.hide();
    });
}, 5000);

// Add floating animation to login card on load
window.addEventListener('load', () => {
    const card = document.querySelector('.login-card');
    card.classList.add('animate__animated', 'animate__pulse');
});
</script>

</body>
</html>