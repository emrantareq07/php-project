<?php
session_start();

require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH.'/libs/function.php');
$usercredentials=new DB_con();

//fetching username from either session or cookies condition
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  }

  $query="SELECT * FROM tblusers  WHERE Username='$uname'";
      $result= $usercredentials->runBaseQuery($query);
      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
        $role = $result[$k]['role'];

      }
}
// login function start
if(isset($_POST['signin'])){
// Posted Values
$uname=$_POST['username'];
$pasword=md5($_POST['password']);
//Function Calling
$ret=$usercredentials->signin($uname,$pasword);
$num=mysqli_fetch_array($ret);
if($num>0){
  $_SESSION['uid']=$num['id'];
  $_SESSION['uname']=$uname;
  $_SESSION['role']=$num['role'];

      if(!empty($_POST["remember"])) {
        setcookie ("user_login",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60), "/");
        setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60), "/");
      } else {
        if(isset($_COOKIE["user_login"])) {
          setcookie ("user_login","");
        }

        if(isset($_COOKIE["userpassword"])) {
          setcookie ("userpassword","");
          setcookie ("userpassword","");
        }
      }

      // for logfile
  if ($_SESSION['role'] == 'user') {
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
    $query = "INSERT INTO log_table (username, password, role, Ip, login_date_time,code) 
              VALUES ('".$_SESSION['uname']."', '$pasword', '".$_SESSION['role']."', '$Ip', '$login_date_time','$code')";

    // Run the query
    $query_run = mysqli_query($conn, $query);
    // For success
    if ($query_run) {     
       echo "<script>window.location.href='user-dashboard.php?code=" . $_SESSION['code'] . "'</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
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
    $query = "INSERT INTO log_table (username, password, role, Ip, login_date_time,code) 
              VALUES ('".$_SESSION['uname']."', '$pasword', '".$_SESSION['role']."', '$Ip', '$login_date_time','$code')";
    // Run the query
    $query_run = mysqli_query($conn, $query);
    // For success
    if ($query_run) {     
       echo "<script>window.location.href='admin-dashboard.php?code=" . $_SESSION['code'] . "'</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
          }     
        
    }

      // if($_SESSION['role']=='user'){       
      //   echo "<script>window.location.href='user-dashboard.php'</script>";
      // }
      // else{        
      //   echo "<script>window.location.href='admin-dashboard.php'</script>";
      //   }
}
else{
// Message for unsuccessfull login
  // Error message
    echo '<html><head>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '</head><body>';
    echo '<script type="text/javascript">
            Swal.fire({
                title: "Invalid User details. Please try again",                
                icon: "warning",
                confirmButtonColor: "#dc3545"
            }).then(function() {
                window.location.href = "index.php";
            });
          </script>';
    echo '</body></html>';
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>BCIC Land DB</title>
  <!-- MDB icon -->
  <link rel="icon" href="images/bcic_logo.png" type="image/x-icon" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
  <!-- MDB -->
  <link rel="stylesheet" href="css/bootstrap-login-form.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Spicy+Rice&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=SUSE:wght@100..800&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Concert+One&family=Righteous&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Start your project here-->
  <style>
    .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }
    .h-custom {
      height: calc(100% - 73px);
    }
    @media (max-width: 450px) {
      .h-custom {
        height: 100%;
      }
    }
    .toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
  }

  .spicy-rice-regular {
    font-family: "Spicy Rice", serif;
    font-weight: 400;
    font-style: normal;
  }

  .suse-<uniquifier> {
    font-family: "SUSE", sans-serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
  }
  .concert-one-regular {
    font-family: "Concert One", sans-serif;
    font-weight: 400;
    font-style: normal;
  }

  .righteous-regular {
    font-family: "Righteous", sans-serif;
    font-weight: 400;
    font-style: normal;
  }


  </style>
  <section class="vh-100">
    <div class="container-fluid h-custom">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
          <center>
             <img src="images/bcic_logo.png" class="img-fluid shadow-lg rounded img-thumbnail " alt="Sample image" width="80" height="80" align="middle">
          </center>         
          <img src="images/draw2.webp" class="img-fluid" alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
          <form action="" method="post" id="login" autocomplete="off">
            <!-- <center>
             <img src="images/bcic_logo.png" class="img-fluid" alt="Sample image" width="80" height="80" align="middle"></center> -->
            <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg">

              <h3 class=" fw-bold mb-0  text-center text-uppercase concert-one-regular" style="color: #340f91;">BCIC Land Database</h3>
              <!-- <button type="button" class="btn btn-primary btn-floating mx-1">
                <i class="fab fa-facebook-f"></i>
              </button>

              <button type="button" class="btn btn-primary btn-floating mx-1">
                <i class="fab fa-twitter"></i>
              </button>

              <button type="button" class="btn btn-primary btn-floating mx-1">
                <i class="fab fa-linkedin-in"></i>
              </button> -->
            </div>

            <div class="divider d-flex align-items-center my-4">
              <p class="text-center fw-bold mx-3 mb-0">Estate Management Departmnet (EMD)</p>
            </div>

            <!-- Email input -->
            <!-- <div class="form-outline mb-4">
              <input type="email" id="form3Example3" class="form-control form-control-lg"
                placeholder="Enter a valid email address" />
              <label class="form-label" for="form3Example3">Email address</label>
            </div> -->
            <div class="form-outline mb-4">
              <input type="text" id="form3Example3" name="username" class="form-control form-control-lg"
                placeholder="Enter Username" value="<?php if(isset($_COOKIE['user_login'])) { echo $_COOKIE['user_login']; } ?>" required/>
              <label class="form-label" for="form3Example3">Username</label>
              <!-- <input name="username" type="text" value="<?php if(isset($_COOKIE['user_login'])) { echo $_COOKIE['user_login']; } ?>" required class="form-control" id="username" placeholder="Username" aria-label="Username" autofocus> -->
            </div>

            <!-- Password input -->
            <div class="form-outline mb-3">
              <input type="password" id="form3Example4" name="password" class="form-control form-control-lg" placeholder="Enter Password" />
              <label class="form-label" for="form3Example4">Password</label>
              <span class="toggle-password" onclick="togglePasswordVisibility()">
                <i class="fas fa-eye" id="toggleEyeIcon"></i>
              </span>
            </div>


            <div class="d-flex justify-content-between align-items-center">
              <!-- Checkbox -->
              <div class="form-check mb-0">
                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                <label class="form-check-label" for="form2Example3">
                  Remember me
                </label>
              </div>
              <a href="#!" class="text-body">Forgot password?</a>
            </div>

            <div class="text-center text-lg-start mt-4 pt-2">
              <button  class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;" type="submit" name="signin">Login</button>
              <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="#!"
                  class="link-danger">Register</a></p>
            </div>

          </form>
        </div>
      </div>
    </div>
    <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
      <!-- Copyright -->
      <div class="text-white mb-3 mb-md-0">
        Copyright © 2024. All rights reserved EMD, BCIC.
        
      </div>
      <div class="text-white mb-3 mb-md-0">
       
        <span class="text-center">[--Design & Developed by ICT Division, BCIC.--]</span>
      </div>
      <!-- Copyright -->

      <!-- Right -->
      <div>
        <a href="#!" class="text-white me-4">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#!" class="text-white me-4">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#!" class="text-white me-4">
          <i class="fab fa-google"></i>
        </a>
        <a href="#!" class="text-white">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>
      <!-- Right -->
    </div>
  </section>
  <!-- End your project here-->

  <!-- MDB -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Custom scripts -->
  <script type="text/javascript"></script>
      <script>
          function togglePasswordVisibility() {
            var passwordInput = document.getElementById("form3Example4");
            var toggleEyeIcon = document.getElementById("toggleEyeIcon");
            
            if (passwordInput.type === "password") {
              passwordInput.type = "text";
              toggleEyeIcon.classList.remove("fa-eye");
              toggleEyeIcon.classList.add("fa-eye-slash");
            } else {
              passwordInput.type = "password";
              toggleEyeIcon.classList.remove("fa-eye-slash");
              toggleEyeIcon.classList.add("fa-eye");
            }
          }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
