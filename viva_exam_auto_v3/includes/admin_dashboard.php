<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$is_admin = ($username === 'admin'); // Check if user is admin

$role = $_SESSION['role'] ?? ''; // ensure role exists


include('header_admin.php');
?>

<body class="bg-light">

<div class="container mt-4">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-2"><i class="fas fa-user-shield me-2"></i>Admin Dashboard</h3>
                <div class="welcome-user">
                    <i class="fas fa-user-circle"></i>
                    <span>Welcome, <?= $username ?></span>
                    <span class="admin-badge"><i class="fas fa-crown me-1"></i>Administrator</span>
                </div>
            </div>
            <a href="logout.php" class="btn btn-light"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </div>

<?php 
    // SQL query to count distinct committee names
    $sql = "SELECT COUNT(DISTINCT committe_name) AS no_of_com FROM committee_tbl";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $no_of_com = $row['no_of_com'];
    } else {
        $no_of_com = 0;
    }

        // SQL query to count distinct committee names
    $sql_can = "SELECT COUNT(DISTINCT roll_no) AS no_of_can FROM candidates_tbl";
    $result_can = $conn->query($sql_can);

    if ($result_can && $row_can = $result_can->fetch_assoc()) {
        $no_of_can = $row_can['no_of_can'];
    } else {
        $no_of_can = 0;
    }
    $sql_examiner = "SELECT COUNT(id) AS no_of_examiner  FROM committee_tbl";
    $result_examiner  = $conn->query($sql_examiner);

    if ($result_examiner  && $row_examiner  = $result_examiner->fetch_assoc()) {
        $no_of_examiner  = $row_examiner['no_of_examiner'];
    } else {
        $no_of_examiner = 0;
    }
?>


    <!-- Quick Stats -->
    <div class="quick-stats">
        <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar me-2"></i>System Overview</h5>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value"><?php echo htmlspecialchars($no_of_com); ?></div>
                    <div class="stat-label">Total Committees</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value"><?php echo htmlspecialchars($no_of_can); ?></div>
                    <div class="stat-label">Total Candidates</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value"><?php echo htmlspecialchars($no_of_examiner); ?></div>
                    <div class="stat-label">Active Examiners</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value">84%</div>
                    <div class="stat-label">Evaluation Completed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Features -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="feature-card feature-1">
                <i class="fas fa-users-cog"></i>
                <h3>Committee Management</h3>
                <p>Create and manage examination committees, assign examiners, and oversee the evaluation process.</p>
                <a href="committee_details.php" class="btn btn-light w-100">Manage Committees</a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="feature-card feature-2">
                <i class="fas fa-user-graduate"></i>
                <h3>Candidate Management</h3>
                <p>Add, edit, and manage candidate information, assign to committees, and track evaluation progress.</p>
                <a href="candidates_details.php" class="btn btn-light w-100">Manage Candidates</a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="feature-card feature-3">
                <i class="fas fa-chart-line"></i>
                <h3>Results & Reports</h3>
                <p>View evaluation results, generate comprehensive reports, and analyze examination statistics.</p>
                <a href="results.php" class="btn btn-light w-100">View Reports</a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="feature-card feature-4">
                <i class="fas fa-cogs"></i>
                <h3>Create Exam Schedule</h3>
                <p>Configure system parameters, manage user accounts, and customize examination settings.</p>
                <a href="exam_schedule.php" class="btn btn-light w-100">Create Exam Schedule</a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-plus text-success me-2"></i>
                                <span>New candidate added by examiner_john</span>
                            </div>
                            <small class="text-muted">10 minutes ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-edit text-primary me-2"></i>
                                <span>Committee A updated by admin</span>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check-circle text-info me-2"></i>
                                <span>Evaluation completed for 5 candidates</span>
                            </div>
                            <small class="text-muted">5 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-export text-warning me-2"></i>
                                <span>Monthly report generated</span>
                            </div>
                            <small class="text-muted">1 day ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-shield text-secondary me-2"></i>
                                <span>New examiner account created</span>
                            </div>
                            <small class="text-muted">2 days ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="committee_details.php" class="btn btn-primary text-start">
                            <i class="fas fa-plus-circle me-2"></i>Create New Committee
                        </a>
                        <a href="candidates_details.php" class="btn btn-success text-start">
                            <i class="fas fa-user-plus me-2"></i>Add New Candidate
                        </a>
                        <a href="reports.php" class="btn btn-info text-start">
                            <i class="fas fa-chart-pie me-2"></i>Generate Report
                        </a>
                        <a href="user_management.php" class="btn btn-warning text-start">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="#" class="btn btn-secondary text-start">
                            <i class="fas fa-database me-2"></i>System Backup
                        </a>
                    <form id="downloadForm" action="download_db.php" method="post">
                      <button class="btn btn-warning w-100 text-start" type="submit" name="submit">
                        <i class="fa fa-database" style="font-size:15px;color:black"></i> System Backup
                      </button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simple animation for stats counter (for demo purposes)
    $(document).ready(function() {
        $('.stat-value').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    if ($(this).parent().find('.stat-label').text() === 'Evaluation Completed') {
                        $(this).text(Math.ceil(now) + '%');
                    } else {
                        $(this).text(Math.ceil(now));
                    }
                }
            });
        });
    });
</script>
</body>
</html>