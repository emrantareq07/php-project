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
    $stmt = $conn->prepare("SELECT username, password,role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
       // $role=$user['role'];

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $status = 'success';

            // Insert login attempt into log_table
            $log = $conn->prepare("INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $log->bind_param("ssssss", $username, $event_type, $ip, $user_agent, $status, $login_time);
            $log->execute();
            $log->close();

           if (
                ($_SESSION['role'] !== 'admin' || $_SESSION['username'] !== 'admin') &&
                ($_SESSION['role'] !== 'sadmin' || $_SESSION['username'] !== 'sadmin')
            ) {
                echo "<script>window.location.href='includes/dashboard.php';</script>";
                exit;
            }
            // elseif($_SESSION['role'] !== 'sadmin' && $_SESSION['username'] !== 'sadmin') {
            //     echo "<script>window.location.href='includes/sadmin_dashboard.php';</script>";
            //     exit;
            //     }
            else {
                echo "<script>window.location.href='includes/admin_dashboard.php';</script>";
                exit;
            }

            // if((!$username==='admin') && ($_SESSION['role'] == 'admin')) {
            //    echo "<script>window.location.href='includes/dashboard.php';</script>";
            // exit; 
            // }else{
            //      echo "<script>window.location.href='includes/admin_dashboard.php';</script>";
            //     exit;
            // }
           
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

    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #00bcd4 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Open Sans', sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
            padding: 2rem;
        }
        .login-card h4 {
            font-weight: 600;
            color: #007bff;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        .toggle-password {
            cursor: pointer;
        }

        /* Toast container */
        #toastContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <img src="assets/bcic_logo.png" alt="Logo" width="80">
        <h4 class="mt-2">Man Power Management</h4>
        <p class="text-muted">Please sign in to continue</p>
    </div>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                <span class="input-group-text toggle-password" onclick="togglePassword()">
                    <i class="fa fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-sign-in-alt me-2"></i> Login
            </button>
        </div>

        <div class="text-center text-muted small">
            &copy; <?php echo date("Y"); ?> BCIC | All Rights Reserved
        </div>
    </form>
</div>

<!-- Toast container -->
<div id="toastContainer">
<?php if($login_failed): ?>
    <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <strong>Login Failed!</strong> Incorrect username or password.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Password Toggle -->
<script>
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

// Auto-hide toast after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function(toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    });
});
</script>

</body>
</html>
