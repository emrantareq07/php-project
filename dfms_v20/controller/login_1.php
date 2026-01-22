<?php
session_name('dfms');
session_start();
error_reporting(0);
include('../db/db.php');

// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    elseif (!empty($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
    return 'UNKNOWN';
}

$login_error = ''; // Variable to store login error message

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Check inactive
        if ($row['user_status'] != 'active') {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                Swal.fire({
                    title: "You are inactive",
                    text: "Please contact super admin",
                    icon: "warning",
                    confirmButtonColor: "#dc3545"
                }).then(()=>{window.location.href="dashboard.php";});
            </script>';
            exit;
        }

        // Set session
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $row['user_type'];

        // Log
        $login_date_time = date('Y-m-d H:i:s');
        $Ip = getClientIP();
        $code = rand(10000, 99999);
        $_SESSION['code'] = $code;

        $log_query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time, code) 
                      VALUES ('$username', '$password', '{$row['user_type']}', '$Ip', '$login_date_time', '$code')";
        mysqli_query($conn, $log_query);

        // Redirect
        if ($_SESSION['user_type'] == 'admin') {
            header("location:home.php");
        } else {
            header("location:dashboard.php");
        }
        exit;
    } else {
        // Set error message for toast
        $login_error = 'Incorrect username and password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>DFMS Login - Chemical Industries</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="../assets/img/bcic_logo.png">
<style>
    body {
        background: linear-gradient(rgba(20, 30, 48, 0.95), rgba(36, 59, 85, 0.95)), 
                    url('https://images.unsplash.com/photo-1538681105587-85640961bf8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow-x: hidden;
        position: relative;
    }

    /* Animated Factory Background Elements */
    .factory-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }

    .smoke {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: smokeFloat 20s linear infinite;
    }

    @keyframes smokeFloat {
        0% {
            transform: translateY(100vh) scale(0.5);
            opacity: 0;
        }
        10% {
            opacity: 0.3;
        }
        90% {
            opacity: 0.1;
        }
        100% {
            transform: translateY(-100px) scale(2);
            opacity: 0;
        }
    }

    .factory-silhouette {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 200px;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        z-index: -1;
    }

    .chemical-flask {
        position: absolute;
        font-size: 3rem;
        color: rgba(102, 126, 234, 0.4);
        animation: bubbleFloat 15s infinite linear;
    }

    @keyframes bubbleFloat {
        0% {
            transform: translateY(100vh) rotate(0deg);
        }
        100% {
            transform: translateY(-100px) rotate(360deg);
        }
    }

    .conveyor-belt {
        position: absolute;
        bottom: 50px;
        left: -100px;
        width: 100px;
        height: 20px;
        background: linear-gradient(90deg, #333, #666, #333);
        animation: conveyorMove 30s linear infinite;
        border-radius: 3px;
    }

    .conveyor-belt::before {
        content: '';
        position: absolute;
        top: -5px;
        width: 100%;
        height: 5px;
        background: #444;
    }

    .conveyor-belt::after {
        content: '';
        position: absolute;
        top: -10px;
        width: 100%;
        height: 2px;
        background: #555;
    }

    @keyframes conveyorMove {
        0% { left: -100px; }
        100% { left: 100%; }
    }

    .package {
        position: absolute;
        bottom: 65px;
        width: 40px;
        height: 30px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 5px;
        animation: packageMove 30s linear infinite;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
    }

    @keyframes packageMove {
        0% { left: -100px; }
        100% { left: 100%; }
    }

    .tank {
        position: absolute;
        bottom: 0;
        width: 80px;
        height: 120px;
        background: linear-gradient(180deg, #4a5568, #2d3748);
        border-radius: 5px 5px 0 0;
        animation: tankPulse 4s infinite alternate;
    }

    @keyframes tankPulse {
        0% { box-shadow: 0 0 20px rgba(74, 85, 104, 0.5); }
        100% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.8); }
    }

    .tank::before {
        content: '';
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        height: 80px;
        background: rgba(102, 126, 234, 0.3);
        border-radius: 3px;
        animation: liquidWave 8s infinite ease-in-out;
    }

    @keyframes liquidWave {
        0%, 100% { height: 80px; }
        50% { height: 90px; }
    }

    /* Main Content Styles */
    .card-login {
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 0 50px rgba(102, 126, 234, 0.1);
        transition: all 0.5s ease;
        overflow: hidden;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(102, 126, 234, 0.2);
        position: relative;
        z-index: 1;
    }

    .card-login:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 
                    0 0 0 1px rgba(102, 126, 234, 0.3),
                    inset 0 0 80px rgba(102, 126, 234, 0.2);
    }

    .card-header {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        text-align: center;
        padding: 1.5rem;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #ff6b6b);
        background-size: 200% 100%;
        animation: shimmer 3s infinite linear;
    }

    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }

    .card-header i {
        animation: spin 10s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 15px;
        border-radius: 12px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-gradient:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-gradient::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s;
    }

    .btn-gradient:hover::after {
        left: 100%;
    }

    .form-control {
        border-radius: 12px;
        padding: 15px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        transition: all 0.3s;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-weight: 500;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
        border-color: #667eea;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        transform: translateY(-2px);
    }

    .input-group-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 12px 0 0 12px;
        font-size: 1.1rem;
    }

    #togglePassword {
        border-radius: 0 12px 12px 0;
        cursor: pointer;
        transition: all 0.3s;
    }

    #togglePassword:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .logo-container {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-15px) rotate(2deg); }
        50% { transform: translateY(0) rotate(0deg); }
        75% { transform: translateY(-10px) rotate(-2deg); }
    }

    .logo-container img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        padding: 5px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4),
                    inset 0 0 60px rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .forgot-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .forgot-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transition: width 0.3s;
    }

    .forgot-link:hover {
        color: #a78bfa;
    }

    .forgot-link:hover::after {
        width: 100%;
    }

    /* Toast Notification Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .custom-toast {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
        color: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        overflow: hidden;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .custom-toast .toast-body {
        padding: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1rem;
    }

    .custom-toast .toast-body i {
        font-size: 1.4rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .card-footer {
        background: rgba(30, 41, 59, 0.8);
        border-top: 1px solid rgba(102, 126, 234, 0.2);
        font-size: 0.85rem;
        color: #cbd5e1;
        text-align: center;
        padding: 1rem;
    }

    .production-title {
        background: linear-gradient(135deg, #667eea, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
        display: inline-block;
    }

    .production-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 25%;
        width: 50%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
        border-radius: 2px;
    }

    /* Animated gears */
    .gear {
        position: absolute;
        color: rgba(102, 126, 234, 0.3);
        font-size: 2rem;
        animation: gearRotate 10s linear infinite;
        z-index: -1;
    }

    @keyframes gearRotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .pipeline {
        position: absolute;
        height: 5px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 5px;
        animation: flow 3s infinite linear;
    }

    @keyframes flow {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }
</style>
</head>
<body>

<!-- Toast Container -->
<div class="toast-container">
    <?php if ($login_error): ?>
    <div class="toast custom-toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000" id="errorToast">
        <div class="toast-body">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($login_error); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Factory Background Elements -->
<div class="factory-bg" id="factoryBg">
    <!-- Smoke effects -->
    <div class="smoke" style="left: 10%; animation-delay: 0s;"></div>
    <div class="smoke" style="left: 30%; animation-delay: 2s;"></div>
    <div class="smoke" style="left: 50%; animation-delay: 4s;"></div>
    <div class="smoke" style="left: 70%; animation-delay: 6s;"></div>
    <div class="smoke" style="left: 90%; animation-delay: 8s;"></div>
    
    <!-- Chemical flasks -->
    <div class="chemical-flask" style="left: 15%; animation-delay: 1s;"><i class="fas fa-flask"></i></div>
    <div class="chemical-flask" style="left: 35%; animation-delay: 3s;"><i class="fas fa-vial"></i></div>
    <div class="chemical-flask" style="left: 65%; animation-delay: 5s;"><i class="fas fa-prescription-bottle"></i></div>
    <div class="chemical-flask" style="left: 85%; animation-delay: 7s;"><i class="fas fa-atom"></i></div>
    
    <!-- Conveyor belt with packages -->
    <div class="conveyor-belt"></div>
    <div class="package" style="animation-delay: 0s;"></div>
    <div class="package" style="animation-delay: 2s;"></div>
    <div class="package" style="animation-delay: 4s;"></div>
    
    <!-- Storage tanks -->
    <div class="tank" style="left: 5%;"></div>
    <div class="tank" style="left: 25%; animation-delay: 1s;"></div>
    <div class="tank" style="left: 75%; animation-delay: 2s;"></div>
    <div class="tank" style="left: 95%; animation-delay: 3s;"></div>
    
    <!-- Factory silhouette -->
    <div class="factory-silhouette"></div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-login shadow-lg">
                <div class="card-header">
                    <i class="fas fa-industry me-2"></i>Bangladesh Chemical Industries Corporation
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-0 logo-container">
                        <img src="../images/bcic_logo.png" alt="BCIC Logo" class="img-fluid">
                    </div>
                    
                    <div class="text-center mb-2">
                        <h4 class="production-title">
                            <i class="fas fa-cogs me-2"></i>DFMS Dashboard
                        </h4>
                        <!-- <p class="text-light mb-0" style="opacity: 0.8;">
                            <i class="fas fa-shield-alt me-1"></i>Secure Factory Management System
                        </p> -->
                    </div>
                    
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-light">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-light">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                            <div class="text-end mt-2">
                                <a href="forgot_password.php" class="forgot-link">
                                    <i class="fas fa-unlock-alt me-1"></i>Forgot Password?
                                </a>
                            </div>
                        </div>
                        <div class="col-12 d-grid mt-1">
                            <button type="submit" name="login" class="btn btn-gradient btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </button>
                        </div>
                    </form>
                    
                  <!--   <div class="mt-4 pt-3 border-top border-secondary">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-light">
                                    <i class="fas fa-chart-line fa-2x mb-2" style="color: #667eea;"></i>
                                    <p class="mb-0 small">Production Analytics</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-light">
                                    <i class="fas fa-tasks fa-2x mb-2" style="color: #764ba2;"></i>
                                    <p class="mb-0 small">Quality Control</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-light">
                                    <i class="fas fa-database fa-2x mb-2" style="color: #a78bfa;"></i>
                                    <p class="mb-0 small">Inventory Management</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="card-footer text-center py-1">
                    <small class="d-block mb-1">
                        <i class="fas fa-factory me-1"></i>Digital Fertilizer Management System
                    </small>
                    <small>© <?=date('Y')?> <span class="fw-bold" style="color: #667eea;">Design & Developed By ICT Division, BCIC</span></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Password toggle
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

    // Auto-hide toast after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const errorToast = document.getElementById('errorToast');
        if (errorToast) {
            setTimeout(() => {
                const toast = new bootstrap.Toast(errorToast);
                toast.hide();
            }, 5000);
        }

        // Create dynamic smoke effects
        const factoryBg = document.getElementById('factoryBg');
        
        for (let i = 0; i < 8; i++) {
            setTimeout(() => {
                const smoke = document.createElement('div');
                smoke.className = 'smoke';
                
                const left = Math.random() * 100;
                const size = Math.random() * 100 + 50;
                const duration = Math.random() * 15 + 15;
                
                smoke.style.left = `${left}%`;
                smoke.style.width = `${size}px`;
                smoke.style.height = `${size}px`;
                smoke.style.animationDuration = `${duration}s`;
                smoke.style.opacity = Math.random() * 0.2 + 0.1;
                
                factoryBg.appendChild(smoke);
                
                // Remove smoke after animation completes
                setTimeout(() => {
                    smoke.remove();
                }, duration * 1000);
            }, i * 2000);
        }

        // Create animated gears
        for (let i = 0; i < 5; i++) {
            const gear = document.createElement('div');
            gear.className = 'gear';
            gear.innerHTML = '<i class="fas fa-cog"></i>';
            
            const top = Math.random() * 80 + 10;
            const left = Math.random() * 90 + 5;
            const size = Math.random() * 30 + 20;
            const delay = Math.random() * 5;
            const direction = Math.random() > 0.5 ? 'normal' : 'reverse';
            
            gear.style.top = `${top}%`;
            gear.style.left = `${left}%`;
            gear.style.fontSize = `${size}px`;
            gear.style.animationDelay = `${delay}s`;
            gear.style.animationDirection = direction;
            
            document.body.appendChild(gear);
        }

        // Create animated pipelines
        for (let i = 0; i < 3; i++) {
            const pipeline = document.createElement('div');
            pipeline.className = 'pipeline';
            
            const width = Math.random() * 200 + 100;
            const top = Math.random() * 80 + 10;
            const left = Math.random() * 80 + 10;
            const angle = Math.random() * 360;
            const duration = Math.random() * 2 + 1;
            
            pipeline.style.width = `${width}px`;
            pipeline.style.top = `${top}%`;
            pipeline.style.left = `${left}%`;
            pipeline.style.transform = `rotate(${angle}deg)`;
            pipeline.style.animationDuration = `${duration}s`;
            pipeline.style.opacity = Math.random() * 0.4 + 0.2;
            
            factoryBg.appendChild(pipeline);
        }

        // Add interactive input effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.05)';
                this.parentElement.style.zIndex = '10';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
                this.parentElement.style.zIndex = '1';
            });
        });

        // Add pulsating effect to login button
        const loginBtn = document.querySelector('button[name="login"]');
        setInterval(() => {
            loginBtn.style.boxShadow = `0 10px 30px rgba(102, 126, 234, ${0.3 + Math.random() * 0.2})`;
        }, 1000);
    });
</script>
</body>
</html>