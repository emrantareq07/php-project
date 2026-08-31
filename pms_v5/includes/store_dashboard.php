<?php
session_name('pms_db');
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/db.php';

$required_role = 'store_incharge';
require_once __DIR__ . '/auth_guard.php';

// --- Lightweight stats ---
$total_medicines = 0;
$out_of_stock = 0;
$low_stock_count = 0;

if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM medicine_tbl")) {
    $total_medicines = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM medicine_tbl WHERE current_stock = 0")) {
    $out_of_stock = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM medicine_tbl WHERE current_stock > 0 AND current_stock <= 10")) {
    $low_stock_count = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Store In-Charge Dashboard - BCIC PMS</title>
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
                <div class="eyebrow">Store In-Charge Panel</div>
                <h1>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?></h1>
            </div>
            <div class="date-badge"><?= date('l, F j, Y') ?></div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label">Total Medicines</div>
                <div class="stat-value"><?= $total_medicines ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Out of Stock</div>
                <div class="stat-value stat-accent"><?= $out_of_stock ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Low Stock (&le;10)</div>
                <div class="stat-value"><?= $low_stock_count ?></div>
            </div>
        </div>

        <div class="section-title">Quick actions</div>
        <div class="action-grid">
            <a href="medicine_manage.php" class="action-card">
                <div class="action-title">Manage Medicines</div>
                <div class="action-desc">Add, edit, or remove medicines from the catalog.</div>
            </a>
            <a href="stock_levels.php" class="action-card">
                <div class="action-title">Stock Levels</div>
                <div class="action-desc">Review current stock across all medicines.</div>
            </a>
            <a href="restock_entry.php" class="action-card">
                <div class="action-title">Restock Entry</div>
                <div class="action-desc">Log new stock received from suppliers.</div>
            </a>
        </div>
    </main>
</div>
</body>
</html>
