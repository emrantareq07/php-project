<?php
// login.php
//if (session_status() === PHP_SESSION_NONE) session_start();
include 'db.php';

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
                    
                    header('Location: index.php');
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