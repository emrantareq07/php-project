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
                }).then(()=>{window.location.href="login.php";});
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
<title>DFMS Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="../assets/img/bcic_logo.png">
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-login {
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
    }

    .card-login:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }

    .card-header {
        background: linear-gradient(90deg, #667eea, #764ba2);
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
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
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
        transition: left 0.5s;
    }

    .btn-gradient:hover::after {
        left: 100%;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s;
        background: #f8f9fa;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        border-color: #667eea;
        background: white;
        transform: translateY(-1px);
    }

    .input-group-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 10px 0 0 10px;
    }

    #togglePassword {
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        transition: all 0.3s;
    }

    #togglePassword:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .logo-container {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .logo-container img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        padding: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .forgot-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        position: relative;
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
        color: #764ba2;
    }

    .forgot-link:hover::after {
        width: 100%;
    }

    /* Toast Notification Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1055;
    }

    .custom-toast {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
        color: white;
        border: none;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        overflow: hidden;
    }

    .custom-toast .toast-body {
        padding: 15px 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-toast .toast-body i {
        font-size: 1.2rem;
    }

    /* Floating particles background */
    .particles {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }

    .particle {
        position: absolute;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        animation: floatParticle linear infinite;
    }

    @keyframes floatParticle {
        0% { transform: translateY(100vh) rotate(0deg); }
        100% { transform: translateY(-100px) rotate(360deg); }
    }

    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e0e0e0;
        font-size: 0.85rem;
        color: #6c757d;
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

<!-- Floating Particles Background -->
<div class="particles" id="particles"></div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-login shadow-lg">
                <div class="card-header">
                    <i class="fas fa-industry me-2"></i>Bangladesh Chemical Industries Corporation
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4 logo-container">
                        <img src="../images/bcic_logo.png" alt="BCIC Logo" class="img-fluid">
                    </div>
                    <h4 class="text-center text-gradient mb-4" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-tachometer-alt me-2"></i>DFMS Dashboard
                    </h4>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                            <div class="text-end mt-2">
                                <a href="forgot_password.php" class="forgot-link">
                                    <i class="fas fa-question-circle me-1"></i>Forgot Password?
                                </a>
                            </div>
                        </div>
                        <div class="col-12 d-grid mt-4">
                            <button type="submit" name="login" class="btn btn-gradient btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <small>© <?=date('Y')?> <span class="fw-bold" style="color: #667eea;">Design & Developed By ICT Division, BCIC.</span></small>
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

        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        const particleCount = 30;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Random properties
            const size = Math.random() * 10 + 5;
            const left = Math.random() * 100;
            const duration = Math.random() * 20 + 10;
            const delay = Math.random() * 5;
            
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${left}%`;
            particle.style.animationDuration = `${duration}s`;
            particle.style.animationDelay = `${delay}s`;
            particle.style.opacity = Math.random() * 0.5 + 0.2;
            
            particlesContainer.appendChild(particle);
        }

        // Add focus animation to form inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    });
</script>
</body>
</html>