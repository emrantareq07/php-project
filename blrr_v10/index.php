<?php
// Set a unique session name for Project 2 and start the session
// session_name('PROJECT2SESSION');
// session_set_cookie_params([
//     'path' => 'assets/blrr_v6', // Set a unique path for Project 2
//     'secure' => true, // Use HTTPS if your site supports it
//     'httponly' => true
// ]);

// Set session cookie parameters before starting the session


// Start the session
session_name('blrr');
session_start();

include_once 'db/database.php';
require_once("backend/config.php");
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
        $_SESSION['office'] = $row['office'];
        $_SESSION['table_name'] = $row['table_name'];
        $_SESSION['office_title'] = $row['office_title'];
        
        if($username1==$username && $password1==$password){
        $_SESSION['username'] = $_POST['username'];

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
    if ($query_run) {     
       echo "<script>window.location.href='dashboard.php?code=" . $_SESSION['code'] . "'</script>";
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
    $query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time,code) 
              VALUES ('".$_SESSION['username']."', '$password', '".$_SESSION['user_type']."', '$Ip', '$login_date_time','$code')";
    // Run the query
    $query_run = mysqli_query($conn, $query);
    // For success
    if ($query_run) {     
       echo "<script>window.location.href='backend/sadmin_dashboard.php?code=" . $_SESSION['code'] . "'</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
          }     
        
    }
        // Redirect based on user type
        // if ($_SESSION['user_type'] === 'user') {
        //     header("Location: dashboard.php");
        //     exit();
        // } elseif ($_SESSION['user_type'] === 'sadmin') {
        //     header("Location: sadmin_dashboard.php");
        //     exit();
        // } else {
        //     header("Location: dashboard.php");
        //     exit();
        // }
       
        // if(($_SESSION['user_type']=='user')){
        //     header("location:dashboard.php");
        //  }
        //   elseif($_SESSION['user_type']=='sadmin'){
        //     header("location:backend/sadmin_dashboard.php");
        //  }        
        //  else{
        //      header("location:dashboard.php");
        //  }
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
                window.location.href = "index.php";
            });
          </script>';
    echo '</body></html>';
    }
}
?>
<?php include_once"backend/header.php";?>
<div class="container-fluid mt-4">
  <div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4">
  <!-- style="background-color: #751aff;" --><h2 class="text-center fw-bold text-muted">বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</h2>
  <hr class="bg-danger">
  <div class="card shadow-lg border border-1 border-primary">
  <!-- <div class="card-header  text-uppercase text-white bg-primary fw-bold" ><i class="fa fa-group" style="font-size:16px"></i> Login</div> -->
   <div class="card-header text-uppercase fw-bold text-white" style="background-color: #7e11bd;">
    <i class="fa fa-group" style="font-size:16px"></i> Login
</div>

  <div class="card-body">
  <div class="imgcontainer">      
     <img src="images/bcic_logo.png" alt="BCIC" class="avatar shadow-lg">
    </div>
  <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" class="">
   <!--  <div class="form-floating mb-3">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div> -->
    <!-- <div class="mb-3 mt-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div> -->
    <div class="form-floating mb-3 mt-3">      
      <input type="text" class="form-control" id="floatingInput" placeholder="Enter Username" name="username" required="">
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating mb-3">      
      <input type="password" class="form-control" id="floatingPassword" placeholder="Enter Password" name="password" required="">
      <label for="floatingPassword">Password</label>
    </div>
    <div class="form-check mb-3">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn  gap-2 col-12 mx-auto text-white" name="login" style="background-color: #7e11bd;"><i class="fa fa-sign-in" ></i> Sign In</button>
    <!-- <button type="submit" class="btn btn-primary d-grid gap-2 col-12 mx-auto" name="login" style="font-size:24px">Sign In <i class="fa fa-sign-out"></i></button> -->
  </form>
  </div>
  <div class="card-footer text-center text-muted"><b><i class="fa fa-copyright"></i><?php echo date("Y");
?> BCIC. [--Design & Developed by ICT Division, BCIC.--]</b></div>
</div>  
</div>
  <div class="col-sm-4"></div>
  
</div>
</div>
<?php
include('backend/footer.php');
?>