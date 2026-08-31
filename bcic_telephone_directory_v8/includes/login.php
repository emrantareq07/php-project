<?php
// session_name('bcic_tel_db');
// login.php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../db/db.php';

// Initialize message variable
$message = '';

// Check if database connection is established
if (!$conn) {
    $message = '<div class="alert alert-danger">Database connection failed.</div>';
} elseif (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic input validation
    if (empty($username) || empty($password)) {
        $message = '<div class="alert alert-danger">Please fill in all fields.</div>';
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows) {
                $stmt->bind_result($id, $hash);
                $stmt->fetch();
                
                // Verify password against hash
                if (password_verify($password, $hash)) {
                    // Login success
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = htmlspecialchars($username); // XSS protection
                    $_SESSION['login_time'] = time(); // Track login time
                    
                    header('Location: dashboard.php');
                    exit;
                } else {
                    // Invalid password
                    $message = '<div class="alert alert-danger">Invalid username or password.</div>';
                }
            } else {
                // User not found
                $message = '<div class="alert alert-danger">Invalid username or password.</div>';
            }
            $stmt->close();
        } else {
            $message = '<div class="alert alert-danger">Database error.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - BCIC Telephone Directory</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
  <meta name="description" content="Admin Login for BCIC Telephone Directory">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #2c3e50; /* Solid dark blue-gray */
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }

    /* Main container */
    .login-container {
      width: 100%;
      padding: 1rem;
    }

    /* Card styles */
    .card {
      border: none;
      border-radius: 20px;
      background: #ffffff; /* Solid white */
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    /* Card body */
    .card-body {
      padding: 2.5rem 2rem;
    }

    /* Responsive padding */
    @media (max-width: 576px) {
      .card-body {
        padding: 1.5rem 1rem;
      }
    }

    /* Header with icon */
    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-icon {
      width: 80px;
      height: 80px;
      background-color: #3498db; /* Solid blue */
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      color: white;
      font-size: 2rem;
      box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    @media (max-width: 576px) {
      .login-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }
    }

    h3 {
      color: #2c3e50; /* Solid dark blue-gray */
      font-weight: 600;
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
    }

    @media (max-width: 576px) {
      h3 {
        font-size: 1.5rem;
      }
    }

    .subtitle {
      color: #7f8c8d; /* Solid gray */
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
    }

    /* Alert messages */
    .alert {
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      border: none;
      font-size: 0.95rem;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-danger {
      background-color: #f8d7da; /* Solid light red */
      color: #721c24; /* Solid dark red */
      border-left: 4px solid #dc3545; /* Solid red */
    }

    .alert-success {
      background-color: #d4edda; /* Solid light green */
      color: #155724; /* Solid dark green */
      border-left: 4px solid #28a745; /* Solid green */
    }

    /* Form groups */
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      color: #34495e; /* Solid dark blue */
      font-weight: 500;
      font-size: 0.95rem;
    }

    /* Input wrapper for icons */
    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      color: #95a5a6; /* Solid gray */
      font-size: 1.1rem;
      z-index: 1;
    }

    /* Form controls */
    .form-control {
      width: 100%;
      padding: 0.875rem 1rem 0.875rem 2.8rem;
      font-size: 1rem;
      border: 2px solid #e0e0e0; /* Solid light gray */
      border-radius: 12px;
      background: white;
      transition: all 0.3s ease;
      -webkit-appearance: none;
      appearance: none;
    }

    /* Prevent zoom on iOS */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      select,
      textarea,
      input {
        font-size: 16px !important;
      }
    }

    .form-control:focus {
      border-color: #3498db; /* Solid blue */
      outline: none;
      box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
    }

    .form-control:focus + .input-icon {
      color: #3498db; /* Solid blue */
    }

    /* Touch-friendly sizing */
    @media (max-width: 768px) {
      .form-control {
        padding: 1rem 1rem 1rem 3rem;
        min-height: 52px;
      }
      
      .input-icon {
        font-size: 1.2rem;
      }
    }

    /* Password field specific */
    .password-wrapper {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #95a5a6; /* Solid gray */
      cursor: pointer;
      padding: 0.5rem;
      font-size: 1.1rem;
      z-index: 2;
      transition: color 0.3s ease;
    }

    .toggle-password:hover {
      color: #3498db; /* Solid blue */
    }

    @media (max-width: 768px) {
      .toggle-password {
        padding: 0.75rem;
        font-size: 1.2rem;
      }
    }

    /* Button styles */
    .btn-login {
      width: 100%;
      padding: 1rem;
      background-color: #3498db; /* Solid blue */
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1rem;
      min-height: 52px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-login:hover {
      background-color: #2980b9; /* Darker solid blue */
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .btn-login:active {
      background-color: #21618c; /* Even darker blue */
      transform: translateY(0);
    }

    .btn-login:disabled {
      background-color: #bdc3c7; /* Solid gray */
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    @media (max-width: 768px) {
      .btn-login {
        padding: 1rem;
        font-size: 1rem;
        min-height: 56px;
      }
    }

    /* Loading spinner */
    .spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Footer links */
    .login-footer {
      text-align: center;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid #e0e0e0; /* Solid light gray */
    }

    .login-footer p {
      color: #7f8c8d; /* Solid gray */
      margin-bottom: 0;
    }

    .login-footer a {
      color: #3498db; /* Solid blue */
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
      padding: 0.5rem;
      display: inline-block;
    }

    .login-footer a:hover {
      color: #2980b9; /* Darker solid blue */
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .login-footer a {
        padding: 0.75rem;
      }
    }

    /* Back to home link */
    .back-home {
      text-align: center;
      margin-top: 1rem;
    }

    .back-home a {
      color: white;
      text-decoration: none;
      font-size: 0.95rem;
      opacity: 0.9;
      transition: opacity 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem;
    }

    .back-home a:hover {
      opacity: 1;
      text-decoration: underline;
    }

    /* Remember me checkbox */
    .remember-me {
      display: flex;
      align-items: center;
      margin: 1rem 0;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 0.5rem;
      cursor: pointer;
      accent-color: #3498db; /* Solid blue */
    }

    .remember-me label {
      color: #34495e; /* Solid dark blue */
      font-size: 0.95rem;
      cursor: pointer;
      user-select: none;
    }

    @media (max-width: 768px) {
      .remember-me input[type="checkbox"] {
        width: 20px;
        height: 20px;
      }
      
      .remember-me label {
        font-size: 1rem;
      }
    }

    /* Forgot password link */
    .forgot-password {
      text-align: right;
      margin-bottom: 0.5rem;
    }

    .forgot-password a {
      color: #7f8c8d; /* Solid gray */
      font-size: 0.9rem;
      text-decoration: none;
      transition: color 0.3s ease;
      padding: 0.25rem;
    }

    .forgot-password a:hover {
      color: #3498db; /* Solid blue */
      text-decoration: underline;
    }

    /* Error state */
    .form-control.error {
      border-color: #dc3545; /* Solid red */
    }

    .error-message {
      color: #dc3545; /* Solid red */
      font-size: 0.85rem;
      margin-top: 0.25rem;
      padding-left: 0.5rem;
    }

    /* Responsive breakpoints */
    @media (min-width: 768px) {
      .login-container {
        max-width: 500px;
        margin: 0 auto;
      }
    }

    @media (min-width: 992px) {
      .login-container {
        max-width: 450px;
      }
    }

    /* Landscape orientation on mobile */
    @media (max-height: 600px) and (orientation: landscape) {
      .login-container {
        padding: 0.5rem;
      }
      
      .card-body {
        padding: 1rem;
      }
      
      .login-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
      }
      
      h3 {
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
      }
      
      .form-group {
        margin-bottom: 0.75rem;
      }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
      .card {
        border: 2px solid #000;
      }
      
      .form-control {
        border: 2px solid #000;
      }
      
      .btn-login {
        border: 2px solid #000;
      }
    }

    /* Reduced motion preference */
    @media (prefers-reduced-motion: reduce) {
      .card,
      .btn-login,
      .form-control,
      .alert {
        animation: none;
        transition: none;
      }
    }

    /* Additional solid color utilities */
    .bg-primary-solid {
      background-color: #3498db;
    }
    
    .bg-secondary-solid {
      background-color: #2c3e50;
    }
    
    .text-primary-solid {
      color: #3498db;
    }
    
    .text-secondary-solid {
      color: #2c3e50;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card">
      <div class="card-body">
        <!-- Header with icon -->
        <div class="login-header">
          <div class="login-icon">
            <i class="fas fa-lock"></i>
          </div>
          <h3>Welcome Back!</h3>
          <div class="subtitle">Login to BCIC Telephone Directory Admin</div>
        </div>

        <!-- Display messages -->
        <?php echo $message; ?>

        <!-- Login Form -->
        <form method="post" novalidate id="">
          <!-- Username field -->
          <div class="form-group">
            <label class="form-label" for="username">
              <i class="fas fa-user"></i> Username
            </label>
            <div class="input-wrapper">
              <i class="fas fa-user input-icon"></i>
              <input 
                type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                placeholder="Enter your username" 
                required 
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                autocomplete="username"
                autofocus
              >
            </div>
          </div>

          <!-- Password field -->
          <div class="form-group">
            <label class="form-label" for="password">
              <i class="fas fa-key"></i> Password
            </label>
            <div class="input-wrapper password-wrapper">
              <i class="fas fa-lock input-icon"></i>
              <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="password" 
                placeholder="Enter your password" 
                required
                autocomplete="current-password"
              >
              <button 
                type="button" 
                class="toggle-password" 
                onclick="togglePasswordVisibility()"
                aria-label="Toggle password visibility"
              >
                <i class="fas fa-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>

          <!-- Remember me and forgot password -->
          <div class="row align-items-center">
            <div class="col-6">
              <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
              </div>
            </div>
            <div class="col-6">
              <div class="forgot-password">
                <a href="forgot_password.php">Forgot password?</a>
              </div>
            </div>
          </div>

          <!-- Submit button -->
          <button type="submit" name="login" class="btn-login" id="loginBtn">
            <span>Login</span>
            <i class="fas fa-arrow-right"></i>
          </button>
        </form>

        <!-- Footer links -->
        <div class="login-footer">
          <p>No account? <a href="includes/register.php">Create one here</a></p>
        </div>
      </div>
    </div>

    <!-- Back to main site link -->
    <div class="back-home">
      <a href="../index.php">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Telephone Directory</span>
      </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loginForm = document.getElementById('loginForm');
      const loginBtn = document.getElementById('loginBtn');
      
      // Form submission handling
      loginForm.addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        
        // Basic client-side validation
        if (!username || !password) {
          e.preventDefault();
          showError('Please fill in all fields');
          return;
        }
        
        // Show loading state
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<span class="spinner"></span> Logging in...';
      });

      // Real-time validation
      const usernameInput = document.getElementById('username');
      const passwordInput = document.getElementById('password');

      usernameInput.addEventListener('input', function() {
        this.classList.remove('error');
        removeErrorMessage(this);
      });

      passwordInput.addEventListener('input', function() {
        this.classList.remove('error');
        removeErrorMessage(this);
      });

      // Remove existing error message
      function removeErrorMessage(input) {
        const nextElement = input.parentElement.parentElement.nextElementSibling;
        if (nextElement && nextElement.classList.contains('error-message')) {
          nextElement.remove();
        }
      }

      // Show error message
      function showError(message) {
        // Remove any existing error
        const existingError = document.querySelector('.error-message');
        if (existingError) {
          existingError.remove();
        }
        
        // Create and insert error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        const formGroup = document.querySelector('.form-group:last-of-type');
        formGroup.appendChild(errorDiv);
        
        // Highlight empty fields
        if (!usernameInput.value.trim()) {
          usernameInput.classList.add('error');
        }
        if (!passwordInput.value) {
          passwordInput.classList.add('error');
        }
      }

      // Prevent form resubmission on page refresh
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    });

    // Toggle password visibility
    function togglePasswordVisibility() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    // Add touch optimization for mobile
    if ('ontouchstart' in window) {
      document.querySelectorAll('.btn-login, .toggle-password, a').forEach(element => {
        element.addEventListener('touchstart', function() {
          // Small delay to show touch feedback
          this.style.opacity = '0.8';
          setTimeout(() => {
            this.style.opacity = '';
          }, 100);
        });
      });
    }

    // Handle orientation change
    window.addEventListener('orientationchange', function() {
      // Force repaint for any layout issues
      document.body.style.display = 'none';
      setTimeout(() => {
        document.body.style.display = '';
      }, 20);
    });

    // Prevent zoom on double tap for iOS
    document.addEventListener('touchstart', function(e) {
      if (e.touches.length > 1) {
        e.preventDefault();
      }
    }, { passive: false });

    // Add loading state to forgot password link
    const forgotLink = document.querySelector('.forgot-password a');
    if (forgotLink) {
      forgotLink.addEventListener('click', function(e) {
        this.style.opacity = '0.7';
      });
    }

    // Handle back button
    window.addEventListener('pageshow', function(event) {
      if (event.persisted) {
        window.location.reload();
      }
    });
  </script>
</body>
</html>