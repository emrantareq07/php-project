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
            header("location:urea_form.php");
        }
        exit;
    } else {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                title: "Invalid Credentials",
                text: "Username or Password is incorrect",
                icon: "error",
                confirmButtonColor: "#dc3545"
            }).then(()=>{window.location.href="dashboard.php";});
        </script>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>DFMS Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
<link rel="icon" type="image/png" href="../assets/img/bcic_logo.png">
<style>
    body {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card-login:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
    }
    .btn-gradient {
        background: linear-gradient(90deg,#667eea,#764ba2);
        border: none;
        color: #fff;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #764ba2;
        transition: all 0.3s;
    }
    .logo-container img {
        width: 100px;
        height: 100px;
        animation: bounce 1s ease-in-out;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
    .card-header {
        background-color: #764ba2;
        color: #fff;
        font-weight: bold;
        font-size: 1.1rem;
        text-align: center;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    .forgot-link {
        font-size: 0.9rem;
        text-decoration: none;
    }
    .forgot-link:hover {
        text-decoration: underline;
        color: #764ba2;
    }
    @media(max-width:576px){
        .card-login {
            margin: 15px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-login shadow-lg">
                <div class="card-header">
                    Bangladesh Chemical Industries Corporation
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3 logo-container">
                        <img src="bcic_logo.png" alt="BCIC Logo">
                    </div>
                    <h5 class="text-center text-muted mb-4">Welcome to DFMS Dashboard</h5>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                                <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                    <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                </span>
                            </div>
                            <div class="text-end mt-1">
                                <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="col-12 d-grid mt-3">
                            <button type="submit" name="login" class="btn btn-gradient btn-md">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center text-muted">
                    <small>© <?=date('Y')?> [--Design & Developed By ICT Division, BCIC.--]</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye-fill');
        eyeIcon.classList.toggle('bi-eye-slash-fill');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
