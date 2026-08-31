<?php
session_name('pms_db');
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/db.php';

$required_role = 'sadmin';
require_once __DIR__ . '/auth_guard.php';

// --- Lightweight stats ---
$total_users = 0;
$total_employees = 0;
$logins_today = 0;

if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM user")) {
    $total_users = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM employees")) {
    $total_employees = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
if ($res = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM log_table WHERE DATE(login_date_time) = CURDATE()")) {
    $logins_today = (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>System Admin Dashboard - BCIC PMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>
  /* ----- colorful & lookrative overrides ----- */
  * {
    box-sizing: border-box;
    margin: 0;
  }

  body {
    background: #f4f8f6;
    font-family: 'Inter', sans-serif;
    color: #1a2d27;
  }

  .app-shell {
    display: flex;
    min-height: 100vh;
  }

  .main-content {
    flex: 1;
    padding: 32px 40px;
    background: #f4f8f6;
  }

  .topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    flex-wrap: wrap;
  }

  .topbar .eyebrow {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #5b756d;
    background: rgba(18, 79, 74, 0.08);
    display: inline-block;
    padding: 4px 14px;
    border-radius: 40px;
  }

  .topbar h1 {
    font-family: 'Fraunces', serif;
    font-weight: 600;
    font-size: 32px;
    color: #0b3d38;
    margin-top: 8px;
  }

  .topbar h1 span {
    background: linear-gradient(145deg, #e8573a, #c44a31);
    color: white;
    padding: 2px 14px;
    border-radius: 40px;
    font-size: 18px;
    font-family: 'Inter', sans-serif;
    margin-left: 10px;
  }

  .date-badge {
    background: white;
    padding: 8px 20px;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 500;
    color: #124f4a;
    border: 1px solid #d6e3df;
    box-shadow: 0 2px 8px rgba(18, 79, 74, 0.06);
  }

  /* ----- STAT GRID - colorful cards ----- */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
  }

  .stat-card {
    background: white;
    border-radius: 24px;
    padding: 24px 28px;
    box-shadow: 0 8px 24px rgba(18, 79, 74, 0.06);
    border: 1px solid #e2ebe7;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
  }

  .stat-card:nth-child(1)::before {
    background: linear-gradient(90deg, #124f4a, #1d8a7a);
  }
  .stat-card:nth-child(2)::before {
    background: linear-gradient(90deg, #e8573a, #f07a5a);
  }
  .stat-card:nth-child(3)::before {
    background: linear-gradient(90deg, #5b6abf, #8b9af0);
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(18, 79, 74, 0.10);
  }

  .stat-label {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #5d7a70;
    margin-bottom: 8px;
  }

  .stat-value {
    font-family: 'Fraunces', serif;
    font-size: 40px;
    font-weight: 700;
    color: #0b3d38;
    letter-spacing: -0.02em;
  }

  .stat-value.stat-accent {
    color: #5b6abf;
  }

  .stat-icon {
    float: right;
    font-size: 32px;
    opacity: 0.2;
    margin-top: -8px;
  }

  /* ----- SECTION TITLE ----- */
  .section-title {
    font-family: 'Fraunces', serif;
    font-size: 22px;
    font-weight: 600;
    color: #0b3d38;
    margin-bottom: 18px;
    letter-spacing: -0.3px;
  }

  .section-title small {
    font-family: 'Inter', sans-serif;
    font-weight: 400;
    font-size: 14px;
    color: #5d7a70;
    margin-left: 10px;
  }

  /* ----- ACTION GRID - colorful tiles ----- */
  .action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 24px;
  }

  .action-card {
    background: white;
    border-radius: 24px;
    padding: 28px 30px;
    text-decoration: none;
    color: #1a2d27;
    border: 1px solid #e2ebe7;
    box-shadow: 0 4px 12px rgba(18, 79, 74, 0.04);
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
    display: block;
  }

  .action-card::after {
    content: '→';
    position: absolute;
    bottom: 20px;
    right: 24px;
    font-size: 24px;
    color: #c4d4ce;
    transition: all 0.25s ease;
  }

  .action-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(18, 79, 74, 0.10);
    border-color: transparent;
  }

  .action-card:hover::after {
    color: #124f4a;
    transform: translateX(6px);
  }

  .action-card:nth-child(1) {
    background: linear-gradient(145deg, #ffffff, #f6fbf9);
    border-left: 6px solid #124f4a;
  }

  .action-card:nth-child(2) {
    background: linear-gradient(145deg, #ffffff, #fdf6f3);
    border-left: 6px solid #e8573a;
  }

  .action-card:nth-child(3) {
    background: linear-gradient(145deg, #ffffff, #f4f5fd);
    border-left: 6px solid #5b6abf;
  }

  .action-card:nth-child(1):hover {
    background: linear-gradient(145deg, #124f4a, #1a6b62);
    color: white;
    border-left-color: #124f4a;
  }
  .action-card:nth-child(2):hover {
    background: linear-gradient(145deg, #e8573a, #d14f33);
    color: white;
    border-left-color: #e8573a;
  }
  .action-card:nth-child(3):hover {
    background: linear-gradient(145deg, #5b6abf, #4a59b0);
    color: white;
    border-left-color: #5b6abf;
  }

  .action-card:hover .action-desc {
    color: rgba(255, 255, 255, 0.85);
  }
  .action-card:hover::after {
    color: rgba(255, 255, 255, 0.6);
  }

  .action-title {
    font-family: 'Fraunces', serif;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 6px;
    letter-spacing: -0.2px;
  }

  .action-desc {
    font-size: 14px;
    color: #5d7a70;
    line-height: 1.5;
    font-weight: 400;
    max-width: 90%;
  }

  /* responsive */
  @media (max-width: 700px) {
    .main-content {
      padding: 20px 16px;
    }
    .topbar h1 {
      font-size: 24px;
    }
    .stat-grid {
      gap: 16px;
    }
    .stat-value {
      font-size: 32px;
    }
    .action-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
</head>
<body>
<div class="app-shell">
    <?php include 'sidebar.php'; ?>
<?php //include __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <div class="topbar">
            <div>
                <div class="eyebrow">✨ System Admin Panel</div>
                <h1>
                    Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>
                    <span>👋</span>
                </h1>
            </div>
            <div class="date-badge">📅 <?= date('l, F j, Y') ?></div>
        </div>

        <!-- colorful stat cards -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-label">System Accounts</div>
                <div class="stat-value"><?= $total_users ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🧑‍💼</div>
                <div class="stat-label">Employees</div>
                <div class="stat-value"><?= $total_employees ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🔐</div>
                <div class="stat-label">Logins Today</div>
                <div class="stat-value stat-accent"><?= $logins_today ?></div>
            </div>
        </div>

        <!-- quick actions -->
        <div class="section-title">
            Quick actions
            <small>manage your system</small>
        </div>
        <div class="action-grid">
            <a href="user_management.php" class="action-card">
                <div class="action-title">👤 User Management</div>
                <div class="action-desc">Create, edit, or deactivate staff login accounts and roles.</div>
            </a>
            <a href="employee_management.php" class="action-card">
                <div class="action-title">📋 Employee Management</div>
                <div class="action-desc">Manage employee records used across the system.</div>
            </a>
            <a href="system_logs.php" class="action-card">
                <div class="action-title">📊 System Logs</div>
                <div class="action-desc">Review login activity and audit trails.</div>
            </a>
        </div>

        <!-- extra colorful touch: a tiny footer note -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px dashed #d6e3df; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <span style="font-size: 13px; color: #5d7a70;">
                🚀 BCIC PMS · System Admin Dashboard
            </span>
            <span style="font-size: 13px; color: #5d7a70; background: white; padding: 4px 16px; border-radius: 40px; border: 1px solid #e2ebe7;">
                ⚡ last login: <?= date('h:i A', strtotime($_SESSION['last_login'] ?? 'now')) ?>
            </span>
        </div>
    </main>
</div>
</body>
</html>