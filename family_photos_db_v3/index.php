<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'db.php';

$photo_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM photos WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($photo_count);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Family Photo Storage</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container py-5 text-center">
  <h2 class="fw-bold mb-3">📷 Welcome to <span class="text-primary">Family Photo Storage</span></h2>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <p class="lead text-muted">Login to upload and manage your family photos securely.</p>
    <div class="d-flex justify-content-center gap-3 mt-4">
      <a href="login.php" class="btn btn-success btn-lg">🔑 Login</a>
      <a href="register.php" class="btn btn-outline-primary btn-lg">📝 Register</a>
    </div>
  <?php else: ?>
    <p class="lead text-muted">
      Hello, <span class="fw-bold text-primary"><?= htmlspecialchars($_SESSION['username']); ?></span>! You have uploaded
      <span class="fw-bold text-success"><?= $photo_count; ?></span> photo<?= $photo_count != 1 ? 's' : ''; ?>.
    </p>
    <p class="lead text-muted">Hello, <span class="fw-bold text-primary"><?= htmlspecialchars($_SESSION['username']); ?></span>! You can now manage your family photos.</p>
    <div class="mt-4">
      <a href="upload.php" class="btn btn-primary btn-lg me-2">⬆ Upload Photos</a>
      <a href="list.php" class="btn btn-outline-secondary btn-lg">📂 View Photos</a>
    </div>
  <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date("Y"); ?> Family Photo Storage</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



