<?php
// Start the session
session_name('pms_db');
session_start();

include_once"includes/header_login.php";
require_once("config/config.php");
require_once("db/db.php");

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = sha1($password);

    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $username1 = $row['username'];
        $password1 = $row['password'];
        $_SESSION['user_type'] = $row['user_type']; // Set user_type here
        
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
       echo "<script>window.location.href='sadmin_dashboard.php?code=" . $_SESSION['code'] . "'</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
          }     
        
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
                window.location.href = "index.php";
            });
          </script>';
    echo '</body></html>';
    }
}
?>


<div class="container">
    <div class="login-container"> <h4 class="fw-bold text-muted katibeh-regular">BCIC Patient Management System (PMS)</h4>
      <div id="output"></div>
      <div class="avatar"> </div>
      <div class="form-box ">
        <form action="<?=$_SERVER['PHP_SELF']; ?>" method="post" id="login" >
          <input name="username" type="text" placeholder="Username" required>
          <input type="password" placeholder="Password" required name="password">
          <button class="btn btn-primary w-100 login text-white fw-bold" type="submit" name="login"><i class="fa fa-sign-in"></i> Login</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
//include('includes/footer.php');
?>