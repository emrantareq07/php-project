
<?php
session_name('factory_work_request_db');
//session_start();
require_once 'db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = trim($_POST['emp_id'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate credentials
    $stmt = $conn->prepare("SELECT id, emp_id, password, full_name, role, status FROM users WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if account is active
        if ($user['status'] !== 'active') {
            $error = "Your account is inactive. Please contact administrator.";
        }
        // Verify password
        elseif (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['emp_id'] = $user['emp_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            // Redirect to dashboard
            header("Location: includes/dashboard.php");
            exit;
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Invalid credentials";
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        /* Add similar styles as registration page */
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f7fa; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        .login-container { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 400px; 
        }
        h2 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 30px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555; 
        }
        input[type="text"], 
        input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px; 
        }
        .btn-login { 
            background: #667eea; 
            color: white; 
            border: none; 
            padding: 12px; 
            width: 100%; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
        }
        .error { 
            color: #e74c3c; 
            margin-bottom: 15px; 
            text-align: center; 
        }
        .register-link { 
            text-align: center; 
            margin-top: 20px; 
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Employee ID</label>
                <input type="text" name="emp_id" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="includes/register.php">Register here</a>
        </div>
    </div>
</body>
</html>