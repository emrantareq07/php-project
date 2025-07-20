<?php
// Start session and include configuration files
session_start();
require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH . '/libs/function.php');

// Initialize database connection
$usercredentials = new DB_con();

// Check for existing session or cookie
if (isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) {
    $uname = $uun = $uup = "";

    // Get username from session or cookie
    if (isset($_SESSION["uname"])) {
        $uname = $_SESSION['uname'];
    } elseif (isset($_COOKIE['user_login'])) {
        $uname = $_COOKIE['user_login'];
    }

    // Fetch user details if username exists
    if (!empty($uname)) {
        $query = "SELECT * FROM tblusers WHERE Username = '$uname'";
        $result = $usercredentials->runBaseQuery($query);

        if ($result && count($result) > 0) {
            $uun = $result[0]['Username'];
            $uup = $result[0]['Password'];
        }
    }
}

// Handle login form submission
if (isset($_POST['signin'])) {
    $uname = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validate credentials (password hashed using md5)
    $ret = $usercredentials->signin($uname, md5($password));
    $num = mysqli_fetch_array($ret);

    if ($num > 0) {
        $_SESSION['uid'] = $num['id'];
        $_SESSION['uname'] = $uname;

        if (!empty($_POST["remember"])) {
            $cookie_expiry = time() + (10 * 365 * 24 * 60 * 60); // 10 years
            setcookie("user_login", $uname, $cookie_expiry, "/");
        } else {
            setcookie("user_login", "", time() - 3600, "/");
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = 'Invalid details. Please try again';
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Morning Glory School - Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
        }
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card login-card p-4 border border-2 border-primary shadow-lg">
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <h4 class="text-center mb-4 text-muted text-uppercase">Admin Dashboard Login</h4>
                        
                        <form action="" method="post" id="loginForm" autocomplete="off">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo isset($_COOKIE['user_login']) ? htmlspecialchars($_COOKIE['user_login']) : ''; ?>" autocomplete="on"
                                           required placeholder="Enter your username">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           required placeholder="Enter your password">
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember"
                                    <?php echo isset($_COOKIE['user_login']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="remember_me">Remember me</label>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" name="signin" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>
                            
                            <div class="mt-3 text-center">
                                <p>Don't have an account? <a href="register.php">Register here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function() {
                const username = $('#username').val().trim();
                const password = $('#password').val().trim();
                if (username === '' || password === '') {
                    alert('Please fill in all fields');
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>
