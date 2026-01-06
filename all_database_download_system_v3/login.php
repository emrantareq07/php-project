<?php
require_once 'config.php';
session_start();

if (isset($_GET['logout'])) {
    session_unset(); session_destroy();
    header('Location: login.php'); exit;
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header('Location: main_dashboard.php'); exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if ($u === ADMIN_USER && $p === ADMIN_PASS) {
        $_SESSION['logged'] = true;
        $_SESSION['user'] = $u;
        header('Location: main_dashboard.php'); exit;
    } else $err = 'Invalid username or password';
}
?>
<!doctype html>
<html><head>
<meta charset="utf-8"><title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#f4f6fb} .card{border-radius:12px}</style>
</head><body>
<div class="container vh-100 d-flex align-items-center justify-content-center">
  <div class="card p-4 shadow" style="max-width:420px;width:100%;">
    <h4 class="mb-3">Backup Admin Login</h4>
    <?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post" novalidate>
      <input name="username" class="form-control mb-2" placeholder="Username" required>
      <input name="password" type="password" class="form-control mb-3" placeholder="Password" required>
      <button class="btn btn-primary w-100">Login</button>
    </form>
    <div class="text-muted mt-2 small">Change credentials in config.php</div>
  </div>
</div>
</body></html>
