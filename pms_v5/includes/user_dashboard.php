<?php
session_name('pms_db');
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/db.php';

$required_role = 'user';
require_once __DIR__ . '/auth_guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Dashboard - BCIC PMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <div class="eyebrow">Employee Panel</div>
                <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            </div>
            <div class="date-badge"><?= date('l, F j, Y') ?></div>
        </div>

        <div class="section-title">Quick actions</div>
        <div class="action-grid">
            <a href="my_prescriptions.php" class="action-card">
                <div class="action-title">My Prescriptions</div>
                <div class="action-desc">View prescriptions issued to you by a doctor.</div>
            </a>
            <a href="my_profile.php" class="action-card">
                <div class="action-title">My Profile</div>
                <div class="action-desc">View and update your personal details.</div>
            </a>
        </div>
    </main>
</div>
</body>
</html>
