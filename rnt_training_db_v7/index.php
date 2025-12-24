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
        echo "<script>window.location.href='main_dashboard.php'</script>";
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
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENTIP'];
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
  <title>Training Management System | Login</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      --secondary-gradient: linear-gradient(135deg, #f72585 0%, #7209b7 100%);
      --success-gradient: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
      --warning-gradient: linear-gradient(135deg, #ff9e00 0%, #ff6d00 100%);
    }
    
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      overflow-x: hidden;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      background-attachment: fixed;
      position: relative;
    }
    
    /* Simplified Background Animation */
    .login-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
    }
    
    .bg-circle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.1;
      filter: blur(40px);
    }
    
    .circle-1 {
      width: 400px;
      height: 400px;
      background: #FF6B6B;
      top: -200px;
      left: -200px;
    }
    
    .circle-2 {
      width: 300px;
      height: 300px;
      background: #4361EE;
      bottom: -150px;
      right: -150px;
    }
    
    .circle-3 {
      width: 200px;
      height: 200px;
      background: #4CC9F0;
      top: 50%;
      left: 80%;
    }
    
    /* Login Container */
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 25px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1),
                  0 10px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      max-width: 480px;
      width: 100%;
      position: relative;
      z-index: 1;
      animation: slideUp 0.8s ease-out;
    }
    
    @keyframes slideUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
    
    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, 
        #FF6B6B 0%, #FFE66D 25%, #1A936F 50%, #4361EE 75%, #F72585 100%);
    }
    
    /* Header Section */
    .login-header {
      padding: 2.2rem 2rem 1rem;
      text-align: center;
      background: linear-gradient(135deg, rgba(67, 97, 238, 0.1) 0%, rgba(58, 12, 163, 0.05) 100%);
    }
    
    .logo-container {
      margin-bottom: 1.1rem;
    }
    
    .logo-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      padding: 10px;
      background: var(--primary-gradient);
      box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
      margin: 0 auto 1.2rem;
      border: 4px solid white;
      animation: pulse 2s infinite ease-in-out;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    .system-title {
      font-size: 2rem;
      font-weight: 800;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }
    
    .system-subtitle {
      color: #666;
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .system-org {
      color: #888;
      font-size: 0.9rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    /* Form Section */
    .login-form {
      padding: 2rem;
    }
    
    .form-label {
      font-weight: 600;
      color: #495057;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .form-label i {
      color: #4361ee;
      font-size: 1.1rem;
    }
    
    .form-control-custom {
      border: 2px solid #e0e0e0;
      border-radius: 15px;
      padding: 1rem 1.25rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control-custom:focus {
      border-color: #4361ee;
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
      transform: translateY(-2px);
    }
    
    .input-group-custom {
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .input-group-custom:focus-within {
      box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
      transform: translateY(-2px);
    }
    
    .input-group-text-custom {
      background: var(--primary-gradient);
      border: none;
      color: white;
      padding: 1rem 1.25rem;
    }
    
    /* Remember Me */
    .remember-me {
      margin: 1.5rem 0;
    }
    
    .form-check-input:checked {
      background-color: #4361ee;
      border-color: #4361ee;
    }
    
    /* Login Button */
    .btn-login {
      background: var(--primary-gradient);
      border: none;
      color: white;
      padding: 1rem 2rem;
      border-radius: 15px;
      font-weight: 600;
      font-size: 1.1rem;
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
      background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
    }
    
    .btn-login:active {
      transform: translateY(-1px);
    }
    
    .btn-login i {
      transition: transform 0.3s ease;
    }
    
    .btn-login:hover i {
      transform: translateX(5px);
    }
    
    /* Footer */
    .login-footer {
      padding: 1.2rem 1.8rem;
      text-align: center;
      background: linear-gradient(135deg, rgba(248, 249, 250, 0.5) 0%, rgba(233, 236, 239, 0.3) 100%);
      border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .developer-info {
      color: #6c757d;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .developer-info i {
      color: #4361ee;
      margin: 0 3px;
    }
    
    /* Training Icons */
    .training-icons {
      position: absolute;
      pointer-events: none;
      z-index: 0;
      width: 100%;
      height: 100%;
    }
    
    .training-icon {
      position: absolute;
      font-size: 2rem;
      opacity: 0.1;
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    .icon-1 { color: #FF6B6B; top: 20%; left: 10%; animation-delay: 0s; }
    .icon-2 { color: #4361EE; top: 30%; right: 15%; animation-delay: 1s; }
    .icon-3 { color: #4CC9F0; bottom: 20%; left: 15%; animation-delay: 2s; }
    .icon-4 { color: #F72585; bottom: 30%; right: 10%; animation-delay: 3s; }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .login-card {
        margin: 20px;
        max-width: 100%;
      }
      
      .system-title {
        font-size: 1.75rem;
      }
      
      .login-header, .login-form, .login-footer {
        padding: 1.5rem;
      }
      
      .logo-img {
        width: 80px;
        height: 80px;
      }
      
      .training-icons {
        display: none;
      }
    }
    
    /* Ripple Effect */
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.6);
      transform: scale(0);
      animation: ripple-animation 0.6s linear;
    }
    
    @keyframes ripple-animation {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
    
    /* Shake animation for invalid input */
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .shake {
      animation: shake 0.5s ease-in-out;
    }
  </style>
</head>
<body>
  <!-- Background -->
  <div class="login-bg">
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>
  </div>

  <!-- Training Icons -->
  <div class="training-icons">
    <i class="fas fa-graduation-cap training-icon icon-1"></i>
    <i class="fas fa-book training-icon icon-2"></i>
    <i class="fas fa-chart-line training-icon icon-3"></i>
    <i class="fas fa-users training-icon icon-4"></i>
  </div>

  <!-- Login Container -->
  <div class="login-container">
    <div class="login-card">
      <!-- Header -->
      <div class="login-header">
        <div class="logo-container">
          <img src="images/bcic_logo.png" alt="BCIC Logo" class="logo-img">
          <!-- <h1 class="system-title">Training Management System</h1> -->
          <h1 class="system-title">BCIC Employees Training Database</h1>
          <!-- <p class="system-subtitle">Employees Training Database</p> -->
          <p class="system-org">(RNT, Personnel Division, BCIC)</p>
        </div>
      </div>

      <!-- Login Form -->
      <div class="login-form">
         <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" class="">
          <!-- Username Field -->
          <div class="mb-4">
            <label for="username" class="form-label">
              <i class="fas fa-user"></i> Username
            </label>
            <div class="input-group input-group-custom">
              <span class="input-group-text input-group-text-custom">
                <i class="fas fa-user-circle"></i>
              </span>
              <input type="text" 
                     class="form-control form-control-custom" 
                     id="username" 
                     name="username" 
                     placeholder="Enter your username" 
                     required
                     autocomplete="username"
                     autofocus>
            </div>
          </div>

          <!-- Password Field -->
          <div class="mb-4">
            <label for="password" class="form-label">
              <i class="fas fa-lock"></i> Password
            </label>
            <div class="input-group input-group-custom">
              <span class="input-group-text input-group-text-custom">
                <i class="fas fa-key"></i>
              </span>
              <input type="password" 
                     class="form-control form-control-custom" 
                     id="password" 
                     name="password" 
                     placeholder="Enter your password" 
                     required
                     autocomplete="current-password">
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="mb-4 remember-me">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember">
              <label class="form-check-label" for="remember">
                <i class="fas fa-check-circle me-2"></i> Remember me
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" 
                  class="btn btn-login" 
                  name="login"
                  id="loginButton">
            <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
          </button>
        </form>
      </div>

      <!-- Footer -->
      <div class="login-footer">
        <div class="developer-info">
          <i class="fas fa-code"></i>
          Designed & Developed by ICT Division, BCIC
          <i class="fas fa-heart" style="color: #f72585;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      });

      // Form submission
      const loginForm = document.getElementById('loginForm');
      const loginButton = document.getElementById('loginButton');
      
      loginForm.addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!username || !password) {
          e.preventDefault();
          // Shake animation for empty fields
          if (!username) {
            document.getElementById('username').parentElement.classList.add('shake');
            setTimeout(() => {
              document.getElementById('username').parentElement.classList.remove('shake');
            }, 500);
          }
          if (!password) {
            document.getElementById('password').parentElement.classList.add('shake');
            setTimeout(() => {
              document.getElementById('password').parentElement.classList.remove('shake');
            }, 500);
          }
          return;
        }
        
        // Change button text
        loginButton.disabled = true;
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...';
      });

      // Auto-fill remember me credentials
      window.addEventListener('load', function() {
        const savedUsername = getCookie('user_login');
        const savedPassword = getCookie('userpassword');
        
        if (savedUsername && savedPassword) {
          document.getElementById('username').value = savedUsername;
          document.getElementById('password').value = savedPassword;
          document.getElementById('remember').checked = true;
        }
      });

      // Ripple effect on button click
      loginButton.addEventListener('click', function(e) {
        createRipple(e, this);
      });

      // Helper function to create ripple effect
      function createRipple(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
          position: absolute;
          border-radius: 50%;
          background: rgba(255, 255, 255, 0.6);
          transform: scale(0);
          animation: ripple-animation 0.6s linear;
          width: ${size}px;
          height: ${size}px;
          top: ${y}px;
          left: ${x}px;
          pointer-events: none;
        `;
        
        element.appendChild(ripple);
        
        setTimeout(() => {
          ripple.remove();
        }, 600);
      }

      // Helper function to get cookie
      function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
      }

      // Input focus effects
      const inputs = document.querySelectorAll('.form-control-custom');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.style.transform = 'translateY(-2px)';
          this.parentElement.style.boxShadow = '0 5px 20px rgba(67, 97, 238, 0.1)';
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.style.transform = 'translateY(0)';
          this.parentElement.style.boxShadow = 'none';
        });
      });

      // Add keyboard shortcut for login (Enter key in password field)
      document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.getElementById('loginButton').click();
        }
      });
    });

    // Add visual feedback on input
    document.querySelectorAll('.form-control-custom').forEach(input => {
      input.addEventListener('input', function() {
        if (this.value.trim() !== '') {
          this.style.background = 'linear-gradient(90deg, #ffffff 0%, #f8f9fa 100%)';
          this.style.borderColor = '#4361ee';
        } else {
          this.style.background = 'rgba(255, 255, 255, 0.9)';
          this.style.borderColor = '#e0e0e0';
        }
      });
    });
  </script>

  <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
</body>
</html>