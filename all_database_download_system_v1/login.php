<?php
require_once 'config.php';
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php'); exit;
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header('Location: dashboard.php'); exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
        $_SESSION['logged'] = true;
        $_SESSION['user'] = $user;
        header('Location: dashboard.php'); exit;
    } else {
        $err = 'Invalid username or password';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container vh-100 d-flex align-items-center justify-content-center">
  <div class="card p-4 shadow" style="max-width:420px; width:100%;">
    <h4 class="mb-3">Backup Admin Login</h4>
    <?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post" novalidate>
      <div class="mb-2"><input required name="username" class="form-control" placeholder="Username"></div>
      <div class="mb-2"><input required type="password" name="password" class="form-control" placeholder="Password"></div>
      <button class="btn btn-primary w-100">Login</button>
    </form>
    <div class="text-muted mt-2 small">Protect this folder — put .htaccess if needed.</div>
  </div>
</div>
</body>
</html>
