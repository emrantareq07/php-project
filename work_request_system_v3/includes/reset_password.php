<?php
// reset_password.php
session_name('factory_work_request_db');
session_start();

// Check if user is logged in and is admin/sadmin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Check if user has admin privileges
$user_role = $_SESSION['role'] ?? 'user';
if ($user_role !== 'admin' && $user_role !== 'sadmin') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: user_management.php");
    exit;
}

$user_id = intval($_GET['id']);

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details
$sql = "SELECT emp_id, full_name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: user_management.php");
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($new_password)) {
        $error = "New password is required";
    } elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters";
    } elseif (!preg_match('/[A-Z]/', $new_password) || 
              !preg_match('/[a-z]/', $new_password) || 
              !preg_match('/[0-9]/', $new_password)) {
        $error = "Password must contain uppercase, lowercase, and numbers";
    } elseif (empty($confirm_password)) {
        $error = "Please confirm your new password";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Update password
        $update_sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($update_stmt->execute()) {
            $message = "Password reset successfully for " . htmlspecialchars($user['full_name']);
            
            // Log the password reset
            $log_sql = "INSERT INTO password_reset_logs (admin_id, user_id, reset_at) 
                       VALUES (?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("ii", $_SESSION['user_id'], $user_id);
            $log_stmt->execute();
            $log_stmt->close();
        } else {
            $error = "Failed to reset password: " . $conn->error;
        }
        $update_stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        /* Similar styles to change_password.php */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Password</h1>
            <p>For: <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo htmlspecialchars($user['emp_id']); ?>)</p>
        </div>
        
        <div class="form-container">
            <?php if ($message): ?>
                <div class="message success">
                    <?php echo $message; ?>
                    <br><br>
                    <a href="user_management.php" class="btn">← Back to User Management</a>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="message error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                        <a href="user_management.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>