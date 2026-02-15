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
    <title>DFMS - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap / Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="../assets/img/bcic_logo.png" type="image/png">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #007bff, #6610f2);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            border-radius: 1rem;
            overflow: hidden;
        }
        .login-header {
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 1rem;
        }
        .login-header img {
            width: 70px;
            margin-bottom: 10px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="card shadow-lg login-card">
        <div class="login-header">
             <img src="bcic_logo.png" alt="BCIC Logo">
            <h5 class="mb-0">Bangladesh Chemical Industries Corporation</h5>
            <small>DFMS Dashboard</small>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" name="login" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center text-muted small">
            © <?=date('Y')?> ICT Division, BCIC
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
