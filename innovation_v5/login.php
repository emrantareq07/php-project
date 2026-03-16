<?php
session_name('innovation_db');
session_start();
require_once "db/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if already logged in
if (isset($_SESSION['emp_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emp_id   = trim($_POST['emp_id']);
    $password = trim($_POST['password']);

    if ($emp_id == "" || $password == "") {
        $error = "All fields are required!";
    } else {

        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE emp_id = ? LIMIT 1");
        $stmt->bind_param("s", $emp_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            // 🔐 Plain text password check
            if ($password === $user['password']) {

                $_SESSION['id']               = $user['id'];
                $_SESSION['emp_id']           = $user['emp_id'];
                $_SESSION['fullname']         = $user['fullname'];
                $_SESSION['designation']      = $user['designation'];
                $_SESSION['email']            = $user['email'];
                $_SESSION['mobile_no']        = $user['mobile_no'];
                $_SESSION['place_of_posting'] = $user['place_of_posting'];
                $_SESSION['role']             = $user['role'];

                // Remember Me
                if (!empty($_POST["remember"])) {
                    setcookie("user_login", $emp_id, time() + (86400 * 30), "/");
                } else {
                    setcookie("user_login", "", time() - 3600, "/");
                }

                header("Location: dashboard.php");
                exit();

            } else {
                $error = "Invalid Password!";
            }

        } else {
            $error = "Invalid Employee ID!";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .card-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 15px;
        }
        
        .card-header h2 {
            font-size: 24px;
            font-weight: 600;
            margin: 10px 0 5px;
        }
        
        .card-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .card-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .input-group {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .input-group:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: none;
            padding: 12px 15px;
            color: #6c757d;
        }
        
        .form-control {
            border: none;
            padding: 12px 15px;
            font-size: 15px;
        }
        
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        
        .remember-me input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #667eea;
        }
        
        .remember-me label {
            color: #6c757d;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .back-home {
            text-align: center;
            margin-top: 15px;
        }
        
        .back-home a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-home a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 576px) {
            .card-body {
                padding: 30px 20px;
            }
            
            .form-control {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card-header">
                <img src="images/bcic_logo.png" alt="BCIC Logo">
                <h2><?php echo SITE_NAME; ?></h2>
                <p>Welcome back! Please login to your account</p>
            </div>
            
            <div class="card-body">
              <?php if (!empty($error)) { ?>
    <div class="alert alert-danger text-center">
        <?php echo $error; ?>
    </div>
<?php } ?>
                <form method="post" id="loginForm">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   name="emp_id" 
                                   class="form-control" 
                                   placeholder="Employee ID"
                                   value=""
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Password"
                                   value=""
                                   required>
                        </div>
                    </div>
                    
                    <div class="remember-me">
                        <input type="checkbox" 
                               name="remember" 
                               id="remember"
                               <?php echo isset($_COOKIE['user_login']) ? 'checked' : ''; ?>>
                        <label for="remember">Remember me</label>
                    </div>
                    
                    <button type="submit" name="signin" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    
                    <div class="register-link">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="back-home">
            <a href="<?php echo base_url(); ?>index.php"><i class="fas fa-arrow-left me-2"></i>Back to Home</a>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function() {
                var btn = $('.btn-login');
                btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Logging in...').prop('disabled', true);
            });
        });
    </script>
</body>
</html>