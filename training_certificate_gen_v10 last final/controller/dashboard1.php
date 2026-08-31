<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // go back to login
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h3>Welcome, <?= htmlspecialchars($user_name) ?> 👋</h3>
        <p>Your role: <strong class="text-primary"><?= ucfirst($user_role) ?></strong></p>
        
        <hr>

        <?php if ($user_role === 'admin'): ?>
            <!-- Admin Panel -->
            <h4 class="text-danger">Admin Panel</h4>
            <ul>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="reports.php">View Reports</a></li>
                <li><a href="settings.php">System Settings</a></li>
            </ul>
        <?php elseif ($user_role === 'user'): ?>
            <!-- User Panel -->
            <h4 class="text-success">User Dashboard</h4>
            <ul>
                <li><a href="my_profile.php">My Profile</a></li>
                <li><a href="my_certificates.php">My Certificates</a></li>
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
