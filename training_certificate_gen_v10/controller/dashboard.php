<?php
// Include your database connection
require_once "db.php"; 

session_name('training_certificate_gen_db');
session_start();

// Security Headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// User Session Data
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
$user_id   = $_SESSION['user_id'];
$email_id  = $_SESSION['user_email'];

// --- DYNAMIC DATA FETCHING ---

// 1. Total Counts
$total_users = $conn->query("SELECT COUNT(*) as c FROM users_tbl")->fetch_assoc()['c'];
$sadmin_count = $conn->query("SELECT COUNT(*) as c FROM users_tbl WHERE role='sadmin'")->fetch_assoc()['c'];
$admin_count  = $conn->query("SELECT COUNT(*) as c FROM users_tbl WHERE role='admin'")->fetch_assoc()['c'];
$user_count   = $conn->query("SELECT COUNT(*) as c FROM users_tbl WHERE role='user'")->fetch_assoc()['c'];

// 2. Batch-wise Monitoring Data
$batch_data = [];
$batch_res = $conn->query("SELECT batch, COUNT(*) as count FROM users_tbl WHERE batch != '' GROUP BY batch");
while($row = $batch_res->fetch_assoc()) {
    $batch_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interactive Admin Monitoring Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --sadmin-color: #dc3545;
            --admin-color: #fd7e14;
            --user-color: #198754;
            --bg-body: #f4f7fa;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', sans-serif; }
        
        .glass-card { 
            background: #ffffff; border: none; border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.05); transition: 0.3s;
        }
        .glass-card:hover { transform: translateY(-5px); }

        /* Role-specific headers */
        .panel-header-sadmin { border-left: 5px solid var(--sadmin-color); padding-left: 15px; margin-bottom: 20px; }
        .panel-header-admin { border-left: 5px solid var(--admin-color); padding-left: 15px; margin-bottom: 20px; }
        
        .nav-link-custom {
            display: flex; align-items: center; padding: 12px 15px;
            background: #fff; border: 1px solid #edf2f9; border-radius: 10px;
            text-decoration: none !important; color: #495057; font-weight: 500;
            margin-bottom: 10px; transition: all 0.2s;
        }
        .nav-link-custom:hover { background: #f8f9ff; color: #0d6efd; border-color: #d1d9e6; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .nav-link-custom i { width: 30px; font-size: 1.2rem; }

        .stat-circle {
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 24px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="glass-card p-4 mb-4 d-flex justify-content-between align-items-center bg-white">
        <div>
            <h3 class="fw-bold text-dark mb-0">Welcome, <?= htmlspecialchars($user_name) ?> 👋</h3>
            <p class="text-muted mb-0">
                <span class="badge bg-primary rounded-pill"><?= strtoupper($user_role) ?></span> 
                | Emp ID: <?= $user_id ?> | <?= $email_id ?>
            </p>
        </div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold">Logout</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="stat-circle bg-primary bg-opacity-10 text-primary me-3"><i class="fa fa-users"></i></div>
                <div><small class="text-muted d-block">Total Users</small><h4 class="fw-bold mb-0"><?= $total_users ?></h4></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="stat-circle bg-danger bg-opacity-10 text-danger me-3"><i class="fa fa-user-shield"></i></div>
                <div><small class="text-muted d-block">Super Admins</small><h4 class="fw-bold mb-0"><?= $sadmin_count ?></h4></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="stat-circle bg-warning bg-opacity-10 text-warning me-3"><i class="fa fa-user-cog"></i></div>
                <div><small class="text-muted d-block">Admins</small><h4 class="fw-bold mb-0"><?= $admin_count ?></h4></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-3 d-flex align-items-center">
                <div class="stat-circle bg-success bg-opacity-10 text-success me-3"><i class="fa fa-user-graduate"></i></div>
                <div><small class="text-muted d-block">Active Trainees</small><h4 class="fw-bold mb-0"><?= $user_count ?></h4></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
                
                <?php if ($user_role === 'sadmin'): ?>
                    <div class="panel-header-sadmin"><h5 class="fw-bold text-danger mb-0">SUPER ADMIN CONTROL PANEL</h5></div>
                    <div class="row g-2">
                        <div class="col-6"><a href="manage_users.php" class="nav-link-custom"><i class="fa fa-users text-danger"></i> Manage Users</a></div>
                        <div class="col-6"><a href="reports.php" class="nav-link-custom"><i class="fa fa-chart-line text-primary"></i> View Reports</a></div>
                        <div class="col-6"><a href="settings.php" class="nav-link-custom"><i class="fa fa-cogs text-secondary"></i> Training Setup</a></div>
                        <div class="col-6"><a href="training_evalution_setup.php" class="nav-link-custom"><i class="fa fa-vial text-warning"></i> Training Evaluation Setup</a></div>
                        <div class="col-6"><a href="certificate_by_batch.php" class="nav-link-custom"><i class="fa fa-certificate text-info"></i> Certificates</a></div>
                        <div class="col-6"><a href="attendence_by_batch.php" class="nav-link-custom"><i class="fa fa-list-check text-dark"></i> Create Attendence Sheet</a></div>
                        <div class="col-6"><a href="exam_set.php" class="nav-link-custom"><i class="fa fa-pen-to-square text-danger"></i> SET Exam</a></div>
                        <div class="col-6"><a href="result_by_batch.php" class="nav-link-custom"><i class="fa fa-poll text-success"></i> Batch Wise Results</a></div>

                        <div class="col-6"><a href="evaluation_by_batch.php" class="nav-link-custom"><i class="fa fa-vial text-warning"></i> Batch Wise Evaluation</a></div>

                    </div>
                    <form action="download_db.php" method="post" class="mt-3">
                        <button class="btn btn-danger w-100 fw-bold" type="submit" name="submit"><i class="fa fa-download me-2"></i> Download System Database</button>
                    </form>

                <?php elseif ($user_role === 'admin'): ?>
                    <div class="panel-header-admin"><h5 class="fw-bold text-warning mb-0">ADMINISTRATOR PANEL</h5></div>
                    <div class="row g-2">
                        <a href="manage_users.php" class="nav-link-custom"><i class="fa fa-users text-warning"></i> Manage Users</a>
                        <a href="reports.php" class="nav-link-custom"><i class="fa fa-chart-pie text-primary"></i> View Reports</a>
                        <a href="settings.php" class="nav-link-custom"><i class="fa fa-sliders text-secondary"></i> Training Settings/Setup</a>
                        <a href="certificate_by_batch.php" class="nav-link-custom"><i class="fa fa-award text-success"></i> Batch Certificates</a>
                    </div>

                <?php elseif ($user_role === 'user'): ?>
                    <div class="panel-header-user"><h5 class="fw-bold text-success mb-0">TRAINEE DASHBOARD</h5></div>
                    <div class="nav-list">
                        <a href="my_profile.php" class="nav-link-custom"><i class="fa fa-id-card text-primary"></i> My Profile</a>
                        <a href="my_certificates.php?email=<?= urlencode($email_id); ?>" class="nav-link-custom"><i class="fa fa-medal text-warning"></i> Certificates & Taining Evaluations</a>
                        <a href="my_exams.php?email=<?= urlencode($email_id); ?>" class="nav-link-custom"><i class="fa fa-file-signature text-info"></i> Take Exam</a>
                        <a href="change_pwd.php" class="nav-link-custom"><i class="fa fa-shield-halved text-danger"></i> Change Password </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Real-time Batch Monitoring</h5>
                
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Batch ID</th>
                                <th>Registration Status</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($batch_data as $b): 
                                $perc = ($total_users > 0) ? ($b['count'] / $total_users) * 100 : 0;
                            ?>
                            <tr>
                                <td class="fw-bold">Batch <?= $b['batch'] ?></td>
                                <td width="50%">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success progress-bar-striped" style="width: <?= $perc ?>%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= $b['count'] ?> Users</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4" style="height: 200px;">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center text-muted">
        <?php require_once "includes/footer.php"; ?>
    </div>
</div>

<script>
    // 1. Role Distribution Chart
    const ctx = document.getElementById('roleChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Super Admin', 'Admin', 'Users'],
            datasets: [{
                data: [<?= $sadmin_count ?>, <?= $admin_count ?>, <?= $user_count ?>],
                backgroundColor: ['#dc3545', '#fd7e14', '#198754'],
                hoverOffset: 10,
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });

    // 2. Original Security Scripts (Unchanged)
    if (performance.getEntriesByType("navigation")[0].type === "reload") {
        window.location.href = "reload_handler.php";
    }
    document.addEventListener("contextmenu", (e) => e.preventDefault());
    window.addEventListener("pageshow", (event) => {
        if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
            window.location.href = "../index.php";
        }
    });
</script>
</body>
</html>