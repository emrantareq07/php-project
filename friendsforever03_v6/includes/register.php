<?php
// register.php
if (session_status() === PHP_SESSION_NONE) session_start();
require '../db/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = '<div class="alert alert-danger">Please fill in all fields.</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div class="alert alert-danger">Passwords do not match.</div>';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = '<div class="alert alert-warning">Username already taken. Please choose another.</div>';
            } else {
                // Insert new user with hashed password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                if ($insert) {
                    $insert->bind_param("ss", $username, $hash);
                    if ($insert->execute()) {
                        $message = '<div class="alert alert-success">Registration successful. <a href="login.php">Login here</a>.</div>';
                    } else {
                        $message = '<div class="alert alert-danger">Error registering user. Please try again.</div>';
                    }
                    $insert->close();
                } else {
                    $message = '<div class="alert alert-danger">Database error.</div>';
                }
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
<title>Register</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f8f9fa; }
.card { border: none; border-radius: 10px; }
.btn-success { background-color: #0d6efd; border: none; }
.btn-success:hover { background-color: #0b5ed7; }
</style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-3">📝 Register</h3>
                    <?php echo $message; ?>
                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input name="username" type="text" class="form-control" required 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input name="confirm_password" type="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button name="register" type="submit" class="btn btn-success">Register</button>
                        </div>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="../index.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
