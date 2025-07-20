<?php
// Set a unique session name for Project 1 and start the session and its for after hosting project
// session_name('PROJECT1SESSION');
// session_set_cookie_params([
//     'path' => 'assets/ajax_todo_list_v7', // Set a unique path for Project 1
//     'secure' => true, // Use HTTPS if your site supports it
//     'httponly' => true
// ]);

// Set session cookie parameters before starting the session
// session_set_cookie_params([
//     'path' => '/ajax_todo_list_v7',      // Ensure this matches your project directory
//     'secure' => false,          // False for local development without HTTPS
//     'httponly' => true,         // Prevent JavaScript access to the session cookie
//     'samesite' => 'Lax'         // Optional: Helps with CSRF protection
// ]);

// Start the session
session_name('emd_rent_db');
session_start();
include_once 'db/database.php';
require_once("backend/config.php");
if (isset($_POST['login'])) {
    // Sanitize user inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = sha1($_POST['password']); // Encrypt password

    // Use prepared statements to enhance security
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['office'] = $row['office'];
        $_SESSION['username'] = $username;

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

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
       // echo "<script>window.location.href='bill_form.php?code=" . $_SESSION['code'] . "'</script>";
        echo "<script>window.location.href='backend/dashboard.php'</script>";
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
       echo "<script>window.location.href='sadmin_dashboard.php?code=" . $_SESSION['code'] . "'</script>";
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


    } else {
        // Invalid login handling
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo '<script type="text/javascript">
                Swal.fire({
                    title: "Username and Password are Incorrect",                
                    icon: "warning",
                    confirmButtonColor: "#dc3545"
                }).then(function() {
                    window.location.href = "index.php";
                });
              </script>';
        echo '</body></html>';
    }

    // Close the statement
    $stmt->close();
}
?>
<?php include_once"backend/header.php";?>
<div class="container-fluid mt-5">
  <div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4">
  
  <div class="card shadow-lg border border-1 border-primary">
  <div class="card-header text-center text-uppercase bg-primary text-white fw-bold katibeh-regular"><b><h2>BCIC Rent Management System </h2></b></div>
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
      <input type="password" class="form-control" id="floatingPassword" placeholder="Enter password" name="password" required="">
      <label for="floatingPassword">Password</label>
    </div>
    <div class="form-check mb-3 ">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary gap-2 col-12 mx-auto" name="login"><i class="fa fa-sign-in" ></i> Sign In</button>
    <!-- <button type="submit" class="btn btn-primary d-grid gap-2 col-12 mx-auto" name="login" style="font-size:24px">Sign In <i class="fa fa-sign-out"></i></button> -->
  </form>
  </div>
  <div class="card-footer text-center text-muted katibeh-regular"><b>[--Design & Developed by ICT Division, BCIC.--]</b></div>
</div>  
</div>
  <div class="col-sm-4"></div>
  
</div>
</div>
<?php
include('backend/footer.php');
?>