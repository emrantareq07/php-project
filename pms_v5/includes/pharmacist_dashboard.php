<?php
session_name('pms_db');
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/db.php';

$required_role = 'pharmacist';
require_once __DIR__ . '/auth_guard.php';

// --- Lightweight stats ---
$total_medicines = 0;
$low_stock_count = 0;
$prescriptions_today = 0;

if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM medicine_tbl")) {
    $total_medicines = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM medicine_tbl WHERE current_stock <= 10")) {
    $low_stock_count = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM special_med_tbl WHERE date = CURDATE()")) {
    $prescriptions_today = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pharmacist Dashboard - BCIC PMS</title>
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
                <div class="eyebrow">Pharmacist Panel</div>
                <h1>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            </div>
            <div class="date-badge"><?= date('l, F j, Y') ?></div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label">Medicines in Catalog</div>
                <div class="stat-value"><?= $total_medicines ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Low Stock Items</div>
                <div class="stat-value stat-accent"><?= $low_stock_count ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Prescriptions Today</div>
                <div class="stat-value"><?= $prescriptions_today ?></div>
            </div>
        </div>

        <div class="section-title">Quick actions</div>
        <div class="action-grid">
            <a href="dispense_list.php" class="action-card">
                <div class="action-title">Dispense Prescriptions</div>
                <div class="action-desc">View pending prescriptions and mark medicines as dispensed.</div>
            </a>
            <a href="medicine_stock_view.php" class="action-card">
                <div class="action-title">Medicine Stock</div>
                <div class="action-desc">Check current stock levels across all medicines.</div>
            </a>
            <a href="dispense_history.php" class="action-card">
                <div class="action-title">Dispensing History</div>
                <div class="action-desc">Review previously dispensed prescriptions.</div>
            </a>
        </div>
    </main>
</div>
</body>
</html>
