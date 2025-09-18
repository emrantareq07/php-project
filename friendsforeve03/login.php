<?php
// login.php
if (session_status() === PHP_SESSION_NONE) session_start();
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 10px;
    }
    .btn-success {
      background-color: #0d6efd;
      border: none;
    }
    .btn-success:hover {
      background-color: #0b5ed7;
    }
  </style>
</head>
<body>
<?php //include 'navbar.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="text-center mb-3">🔑 Login</h3>
          <?php echo $message; ?>
          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" type="text" class="form-control" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <div class="d-grid">
              <button name="login" type="submit" class="btn btn-success">Login</button>
            </div>
          </form>
          <p class="text-center mt-3">No account? <a href="register.php">Register</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>