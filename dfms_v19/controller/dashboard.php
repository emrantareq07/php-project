<?php
session_name('dfms');
session_start();
error_reporting(0);
include('../db/db.php');

// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

function getClientIP() {
    $ipaddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress == '::1' ? '127.0.0.1' : trim($ipaddress);
}

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Check if user is inactive
        if ($row['user_status'] != 'active') {
            echo '<html><head>';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '</head><body>';
            echo '<script type="text/javascript">
                    Swal.fire({
                        title: "You are inactive",
                        text: "Please contact super admin",
                        icon: "warning",
                        confirmButtonColor: "#dc3545"
                    }).then(function() {
                        window.location.href = "dashboard.php";
                    });
                  </script>';
            echo '</body></html>';
            exit;
        }

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $row['user_type'];

        // Log user login
        $login_date_time = date('Y-m-d H:i:s');
        $Ip = getClientIP();
        $code = rand(10000, 99999);
        $_SESSION['code'] = $code;

        $log_query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time, code) 
                      VALUES ('$username', '$password', '{$row['user_type']}', '$Ip', '$login_date_time', '$code')";
        mysqli_query($conn, $log_query);

        // Redirect based on user_type
        if($_SESSION['user_type']=='admin'){
            header("location:home.php");
            exit;
        } else {
            header("location:urea_form.php");
            exit;
        }

    } else {
        // Invalid username/password
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo '<script type="text/javascript">
                Swal.fire({
                    title: "Username and Password is Incorrect",                
                    icon: "warning",
                    confirmButtonColor: "#dc3545"
                }).then(function() {
                    window.location.href = "dashboard.php";
                });
              </script>';
        echo '</body></html>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>DFMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">       
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">    
    <link rel="icon" type="image/gif/png" href="../assets/img/bcic_logo.png">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="bg-white">
                    <div class="row">
                        <div class="col-md-3 pe-0"></div>
                        <div class="col-md-6 pe-0">
                            <div class="card shadow-lg border border-2 border-primary">
                                <div class="card-header text-uppercase text-success text-center">
                                    <b>Bangladesh Chemical Industries Corporation</b>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title text-muted text-center text-uppercase"><b>Welcome DFMS Dashboard</b></h6>
                                    <div style="text-align: center;">
                                        <img src="bcic_logo.png" alt="BCIC Logo" width="100" height="100">
                                    </div>
                                    <hr>
                                    <div class="form-left h-100 py-1 px-1">
                                        <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" class="row g-4">
                                            <div class="col-12">
                                                <label>Username<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="bi bi-person-fill"></i></div>
                                                    <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label>Password<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="bi bi-lock-fill"></i></div>
                                                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                                                </div>
                                            </div>

                                            <div class="col-12 d-grid gap-2 mt-3">
                                                <button type="submit" name="login" class="btn btn-primary btn-md">
                                                    <i class="fa fa-sign-in me-2"></i> Login
                                                </button>
                                            </div>                                             
                                        </form>
                                    </div>
                                    <hr style="border-color: #330066; border-width: 1px;border-style: solid;">
                                    <h6 class="fs-6 text-center text-muted">[--Design & Developed By ICT Division, BCIC.--]</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 pe-0"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
