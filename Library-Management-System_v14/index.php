<?php
session_name('bcic_e-library');
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
            echo "<script>alert('Your account has been blocked. Please contact the ICT Division, BCIC.'); window.location.href='index.php';</script>";
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Aladin&family=Arimo:ital,wght@0,400..700;1,400..700&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&family=Trochut:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

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
        .akaya-kanadaka-regular {
      font-family: "Akaya Kanadaka", system-ui;
      font-weight: 400;
      font-style: normal;
    }

    </style>
    <link rel="icon" type="image/gif/png" href="assets/img/bcic_logo.png">
</head>
<body>

<!-- LOGIN FORM UI -->
<div class="container my-3">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"> <h4 class="text-center  fw-bold text-success">  e-LIBRARY MANAGEMENT SYSTEM</h4>
          <img src="assets/img/bcic_logo.png" height="120" alt="Logo"
            class="d-block mx-auto my-0 rounded-circle border border-1 border-success shadow mb-2">
          <h3 class="text-center text-uppercase fw-bold text-muted mb-1 akaya-kanadaka-regular"> BCIC </h3>
          <!-- <center> <small class="text-center fw-bold text-muted ">(Zoo Road, Mirpur-1, Dhaka-1000)</small></center> -->
          
            <div class="card my-3 shadow border border-bg-success" >
                <div class="card-header bg-success text-white text-uppercase  fw-bold" style="background-color:#8c1572" >
                    <i class="fas fa-users"></i> LOGIN 
                    <i class="fa fa-info-circle float-end " style="font-size:16px;  cursor:pointer;" onclick="toggleRules()"></i>                    
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label ">Employee ID</label>
                            <input type="text" name="StudentId" class="form-control" placeholder="Enter EMP ID" required autocomplete="on">
                        </div>
                        <div class="mb-3">
                            <label class="form-label ">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter Password" required autocomplete="off">
                            <div class="form-text">
                                <!-- <a href="user-forgot-password.php">Forgot Password?</a> -->
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" name="login" class="btn btn-success fw-bold">
                                <i class="fa fa-sign-in"></i> LOGIN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-4 my-5 ">
           <!-- Hidden Card -->
                <div id="libraryRulesCard" class="card  d-none">
                  <div class="card-header bg-info text-white text-uppercase text-center fw-bold">
                    Library Rules
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item">The card is non-transferable.</li>
                      <li class="list-group-item">Books must be returned within 15 days.</li>
                      <li class="list-group-item">Only officers and employees of the head office are allowed to borrow books.</li>
                      <li class="list-group-item">Only one book, either in English or Bengali, can be borrowed at a time.</li>
                      <li class="list-group-item">Reference books will not be issued and must be read inside the library.</li>
                      <li class="list-group-item">If a book is not returned within 15 days, a notice will be issued. If the deadline is still not met, the price of the book will be deducted from the member's salary.</li>
                      <li class="list-group-item">The card will be issued for a period of one (1) year.</li>
                      <li class="list-group-item fw-bold fst-italic text-end text-muted">GM (PRD)</li>
                    </ul>
                  </div>
                </div>

                <!-- JavaScript -->
                <script>
                  function toggleRules() {
                    const card = document.getElementById('libraryRulesCard');
                    card.classList.toggle('d-none'); // show/hide the card
                  }
                </script>
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