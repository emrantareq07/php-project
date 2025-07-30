<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

// Clear any existing session login
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

// Function to get client IP address
function getClientIP() {
    $ipaddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return ($ipaddress === '::1') ? '127.0.0.1' : trim($ipaddress);
}

// Function to log login attempt
function logLogin($username, $role, $password, $conn) {
    $Ip = getClientIP();
    $login_date_time = date('Y-m-d H:i:s');
    $code = rand(10000, 99999);
    $_SESSION['code'] = $code;

    $query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time, code) 
              VALUES ('$username', '$password', '$role', '$Ip', '$login_date_time', '$code')";
    return mysqli_query($conn, $query);
}

if (isset($_POST['login'])) {
    $username = $_POST['StudentId'];
    $password_input = $_POST['password'];

    // Check admin login
    $sql_admin = "SELECT username, password, role FROM admin WHERE username = :username";
    $stmt_admin = $dbh->prepare($sql_admin);
    $stmt_admin->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt_admin->execute();

    if ($stmt_admin->rowCount() > 0) {
        $admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);
        $hashed_input = md5($password_input);

        if ($admin['password'] === $hashed_input) {
            $_SESSION['alogin'] = $username;
            $_SESSION['role'] = $admin['role'];
            $_SESSION['username'] = $username;

            if (logLogin($username, $admin['role'], $hashed_input, $conn)) {
                if ($admin['role'] === 'admin') {
                    echo "<script>window.location.href='admin/dashboard.php';</script>";
                } elseif ($admin['role'] === 'sadmin') {
                    echo "<script>window.location.href='admin/sadmin_dashboard.php';</script>";
                }
                exit();
            } else {
                echo "Error logging login attempt: " . mysqli_error($conn);
                exit();
            }
        }
    }

    // Check student login
    $sql_student = "SELECT StudentId, Password, Status, password_changed, FullName, EmailId FROM tblstudents WHERE StudentId = :StudentId";
    $stmt_student = $dbh->prepare($sql_student);
    $stmt_student->bindParam(':StudentId', $username, PDO::PARAM_STR);
    $stmt_student->execute();

    if ($stmt_student->rowCount() > 0) {
        $student = $stmt_student->fetch(PDO::FETCH_OBJ);

        if ($student->Status != 1) {
            echo "<script>alert('Your account has been blocked. Please contact the administrator.'); window.location.href='index.php';</script>";
            exit();
        }

        if (!password_verify($password_input, $student->Password)) {
            echo "<script>alert('Invalid password. Please try again.'); window.history.back();</script>";
            exit();
        }

        $_SESSION['login'] = $username;
        $_SESSION['stdid'] = $student->StudentId;
        $_SESSION['student_name'] = $student->FullName;
        $_SESSION['student_email'] = $student->EmailId;
        $_SESSION['username'] = $student->StudentId;
        $_SESSION['role'] = 'student';

        if (!$student->password_changed) {
            header("Location: user_change_password.php");
            exit();
        }

        if (logLogin($username, 'student', $password_input, $conn)) {
            echo "<script>window.location.href='user_dashboard.php';</script>";
            exit();
        } else {
            echo "Error logging login attempt: " . mysqli_error($conn);
            exit();
        }
    }

    // Invalid login
    echo "<script>alert('Invalid login details. Please try again.'); window.history.back();</script>";
    exit();
}
?>



<!-- MENU SECTION START -->
<?php //include('includes/header.php'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> e-Library Management System </title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Style -->
    <style>
        body {
            font-size: 16px;
        }
        .navbar-nav .nav-link {
            font-size: 16px;
        }
        .dropdown-item {
            font-size: 15px;
        }
    </style>
    <link rel="icon" type="image/gif/png" href="assets/img/bcic_logo.png">
</head>
<body>

<!-- LOGIN FORM UI -->
<div class="container my-3">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"> <h3 class="text-center  fw-bold text-success">  e-Library Management System</h3>
          <img src="assets/img/Seal_of_BCIC_College.png" height="120" alt="Logo" class="d-block mx-auto my-0  thumbnail-circle mb-2">
          <h3 class="text-center text-uppercase fw-bold text-muted mb-1"> BCIC College </h3>
          <center> <small class="text-center fw-bold text-muted ">(Zoo Road, Mirpur-1, Dhaka-1000)</small></center>
          
            <div class="card my-3 shadow ">
                <div class="card-header bg-primary text-white text-uppercase  fw-bold">
                    <i class="fas fa-users"></i> LOGIN 
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label ">Student ID / User Name</label>
                            <input type="text" name="StudentId" class="form-control" placeholder="Enter Student ID / User Name" required autocomplete="on">
                        </div>
                        <div class="mb-3">
                            <label class="form-label ">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter Password" required autocomplete="off">
                            <div class="form-text">
                                <!-- <a href="user-forgot-password.php">Forgot Password?</a> -->
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" name="login" class="btn btn-primary fw-bold">
                                <i class="fa fa-sign-in"></i> LOGIN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- FOOTER SECTION -->
<?php include('includes/footer.php'); ?>