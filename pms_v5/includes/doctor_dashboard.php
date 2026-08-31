<?php
session_name('pms_db');
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/db.php';

$required_role = 'doctor';
require_once __DIR__ . '/auth_guard.php';

// --- Lightweight stats (safe fallbacks if a table/column doesn't exist yet) ---
$patient_count = 0;
$prescription_count = 0;
$today_prescription_count = 0;

if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM employees")) {
    $patient_count = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM special_med_tbl")) {
    $prescription_count = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM special_med_tbl WHERE date = CURDATE()")) {
    $today_prescription_count = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Dashboard - BCIC PMS</title>
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
                <div class="eyebrow">Doctor Panel</div>
                <h1>Welcome back, Dr. <?= htmlspecialchars($_SESSION['username']) ?></h1>
            </div>
            <div class="date-badge"><?= date('l, F j, Y') ?></div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label">Registered Patients</div>
                <div class="stat-value"><?= $patient_count ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Prescriptions</div>
                <div class="stat-value"><?= $prescription_count ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Prescribed Today</div>
                <div class="stat-value stat-accent"><?= $today_prescription_count ?></div>
            </div>
        </div>

        <div class="section-title">Quick actions</div>
        <div class="action-grid">
            <a href="patient_mgtm.php" class="action-card">
                <div class="action-title">Patients</div>
                <div class="action-desc">Search and manage patient records.</div>
            </a>
            <a href="booked_med_entry.php" class="action-card">
                <div class="action-title">New Prescription</div>
                <div class="action-desc">Create a new prescription for a patient.</div>
            </a>
            <a href="booked_med_list.php" class="action-card">
                <div class="action-title">Prescription History</div>
                <div class="action-desc">View and edit previously booked prescriptions.</div>
            </a>
            <a href="special_medicine.php" class="action-card">
                <div class="action-title">Special Medicine</div>
                <div class="action-desc">Manage special/long-term medicine bookings.</div>
            </a>
            <a href="reports.php" class="action-card">
                <div class="action-title">Medicine Reports</div>
                <div class="action-desc">Manage special/long-term medicine bookings.</div>
            </a>
            <a href="prescription_reports.php" class="action-card">
                <div class="action-title">Prescription Reports</div>
                <div class="action-desc">Manage special/long-term medicine bookings.</div>
            </a>
        </div>
    </main>
</div>
</body>
</html>
