<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

$email_id = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    ul { list-style-type: none; padding: 0; }
    ul li { margin: 8px 0; }
    ul li a {
        text-decoration: none;
        color: #0d6efd;
        font-weight: 500;
    }
    ul li a:hover {
        text-decoration: underline;
        color: #0a58ca;
    }
  </style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h3>Welcome, <?= htmlspecialchars($user_name) ?> 👋</h3>
        <p>Your role: <strong class="text-primary"><?= ucfirst($user_role) ?></strong></p>
        
        <hr>

        <?php if ($user_role === 'sadmin'): ?>
            <!-- Admin Panel -->
            <h4 class="text-danger">Admin Panel</h4>
            <ul>
                <li><a href="manage_users.php" style="text-decoration: none;">👥 Manage Users</a></li>
                <li><a href="reports.php" style="text-decoration: none;">📊 View Reports</a></li>
                <li><a href="settings.php" style="text-decoration: none;">⚙️ System Settings</a></li>
                <li><a href="certificate_by_batch.php" style="text-decoration: none;">⚙️ Certificates</a></li>
                <li><a href="download_db.php" style="text-decoration: none;">⚙️ Download Database</a></li>
            </ul>
        <?php elseif ($user_role === 'user'): ?>
            <!-- User Panel -->
            <h4 class="text-success">User Dashboard</h4>
            <ul>
                <li><a href="my_profile.php" style="text-decoration: none;">🙍 My Profile</a></li>
                <li><a href="my_certificates.php?email=<?= urlencode($_SESSION['user_email']); ?>" style="text-decoration: none;">🎓 My Certificates</a>
                </li>
            </ul>
        <?php else: ?>
            <p class="text-muted">No dashboard available for this role.</p>
        <?php endif; ?>

        <hr>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</div>
</body>
</html>
