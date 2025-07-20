<?php
// Start the session
session_name('rnt_training_db');
session_start();
include_once 'db/db.php';
require_once("includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
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
        echo "<script>window.location.href='dashboard.php'</script>";
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
                        title: "Invalid Username or Password",                
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employees Training Database</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Font Awesome (optional, only include if needed) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #74b9ff, #0984e3);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
      animation: slideIn 0.8s ease-out;
    }
    @keyframes slideIn {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
    .form-control:focus {
      box-shadow: 0 0 5px rgba(41, 128, 185, 0.7);
      border-color: #3498db;
    }
    .btn-primary {
      background-color: #0984e3;
      border-color: #0984e3;
      transition: transform 0.2s;
    }
    .btn-primary:hover {
      transform: scale(1.05);
      background-color: #74b9ff;
    }
    .logo-container {
      text-align: center;
      margin-bottom: 20px;
    }
    .logo-container img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      border: 1px solid #7e42f5;
    }
  </style>
  <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
</head>
<body>
  <div class="login-container">
    <div class="logo-container">
      <h5 class="text-center m-0 fw-bold text-muted text-uppercase "> Employees Training Database</h5>
    <h6 class="text-center m-0 fw-bold text-muted text-uppercase mb-2"> RNT, Personnel Division, BCIC.</h6>
      <!-- Replace with your logo URL -->
      <img src="images/bcic_logo.png" alt="Logo">
    </div>
    <h3 class="text-center mb-3 text-uppercase text-muted text-uppercase mb-2 m-0 fw-bold">Login</h3>
     <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" class="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100" name="login"> <i class="fa fa-sign-in"></i> Login</button>
    </form>
    <div class="d-flex justify-content-center mt-2 mb-0">
        <span class="text-muted text-center small">[--Design & Developed by ICT Division, BCIC--]</span>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
