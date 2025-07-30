<?php
// --------------------------
session_name('dfms');
session_start();
error_reporting(0);
include('../db/db.php');
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = sha1($password);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $username1 = $row['username'];
        $password1 = $row['password'];
        $_SESSION['user_type'] = $row['user_type']; // Set user_type here
        if($username1==$username && $password1==$password){
         
    //log start
  if ($_SESSION['user_type'] == 'user') {
    // Get current date and time
    $login_date_time = date('Y-m-d H:i:s');
    function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Handle multiple IP addresses (comma-separated, common in proxy setups)
            $ipaddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        // Convert IPv6 localhost address to IPv4 localhost
        if ($ipaddress == '::1') {
            $ipaddress = '127.0.0.1';
        }
        return trim($ipaddress);
    }
      // $code=90;
      // $_SESSION['code']=$code;
    // Get the user's IP address
    $Ip = getClientIP();
    $code = rand(10000, 99999);
    $_SESSION['code']=$code;
    // Prepare the query
    $query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time,code) 
              VALUES ('".$_SESSION['username']."', '$password', '".$_SESSION['user_type']."', '$Ip', '$login_date_time','$code')";

    // Run the query
    $query_run = mysqli_query($conn, $query);
    // For success
    // if ($query_run) {     
    //    // echo "<script>window.location.href='bill_form.php?code=" . $_SESSION['code'] . "'</script>";
    //     echo "<script>window.location.href='dashboard.php'</script>";
    // } else {
    //     echo "Error: " . mysqli_error($conn);
    // }
}

  else{
    // Get current date and time
    $login_date_time = date('Y-m-d H:i:s');
    function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Handle multiple IP addresses (comma-separated, common in proxy setups)
            $ipaddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        // Convert IPv6 localhost address to IPv4 localhost
        if ($ipaddress == '::1') {
            $ipaddress = '127.0.0.1';
        }
        return trim($ipaddress);
    }
    // Get the user's IP address
    $Ip = getClientIP();
    $code = rand(10000, 99999);
    $_SESSION['code']=$code;
    // Prepare the query
    $query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time,code) 
              VALUES ('".$_SESSION['username']."', '$password', '".$_SESSION['user_type']."', '$Ip', '$login_date_time','$code')";
    // Run the query
    $query_run = mysqli_query($conn, $query);
    // For success
    // if ($query_run) {     
    //    echo "<script>window.location.href='sadmin_dashboard.php?code=" . $_SESSION['code'] . "'</script>";
    // } else {
    //     echo "Error: " . mysqli_error($conn);
    //       }     
        
    }    
         
         //log end    
        
        $_SESSION['username'] = $_POST['username'];
        // $_SESSION=$row['user_type'];
        //$_SESSION['usernametable'] = $username1 ;         

         if($_SESSION['user_type']=='admin'){
            header("location:home.php");
         }       
         else{
            header("location:urea_form.php");
         }

        }
    } 
    else {
        //$msg = "Username and Password is Incorrect";
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
     <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">     
 <!-- <link href="css/style.css" rel="stylesheet">  -->
</head>
<link rel="icon" type="image/gif/png" href="../assets/img/bcic_logo.png">
<body>

    <!-- <div class="login-page bg-light"> -->
        <div class="container my-5">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    
                  <h3 class="mb-0 page-header text-danger text-center"></h3>
                    <div class="bg-white  ">
                        <div class="row">
                            <div class = "page-header">
                               <h3 class="mb-6 page-header text-uppercase text-success text-center pt-3"></h3> 
                                </div>
                            <h4 class="mb-6 page-header text-danger text-center"><?=$msg; ?></h4>
                            
                             <div class="col-md-3 pe-0"></div>
                            <div class="col-md-6 pe-0">

                                <div class="card shadow-lg border border-2 border-primary">
                                  <div class="card-header text-uppercase text-success text-center">
                                   <b> Bangladesh Chemical Industries Corporation</b><!-- <hr class="bg-muted"> -->
                                  </div>
                                  <div class="card-body">
                                    <h6 class="card-title text-muted text-center text-uppercase"> <b> Welcome DFMS Dashboard </b></h6>
                                    <div style="text-align: center;">
                                      <img src="bcic_logo.png" alt="BCIC Logo" width="100" height="100">
                                    </div>
                                    <hr>
                                    <p class="card-text">
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
                                <hr class="" style="border-color: #330066; border-width: 1px;border-style: solid;">
                                    <h6 class="fs-6 text-center text-muted">[--Design & Developed By ICT Division, BCIC.--]</h6>
                                    
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-3 pe-0"></div>
<!--                             <div class="col-md-5 ps-0 d-none d-md-block">
                                <div class="form-right h-100 bg-primary text-white text-center pt-5">
                                    <i class="bi bi-bootstrap"></i>
                                    <h2 class="fs-1">Welcome Back!!!</h2>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!-- <p class="text-end text-secondary mt-3">Bootstrap 5 Login Page Design</p> -->
                </div>
            </div>
        </div>
    <!-- </div> -->

    <!-- Bootstrap JS -->
     
</body>
</html>