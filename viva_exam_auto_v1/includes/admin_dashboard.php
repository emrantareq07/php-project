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



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Viva Examination System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --card-shadow: 0 10px 20px rgba(0,0,0,0.1);
            --hover-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-header {
            background: var(--gradient);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: rotate(30deg);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .card-header {
            background: var(--gradient);
            color: white;
            border-bottom: none;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #4cc9f0;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-success:hover {
            background: #3aa8d8;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: #f72585;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-warning:hover {
            background: #d1146f;
            transform: translateY(-2px);
        }
        
        .btn-outline-danger {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-outline-danger:hover {
            transform: translateY(-2px);
        }
        
        .feature-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .feature-card i {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .feature-card p {
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .feature-1 {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
        }
        
        .feature-2 {
            background: linear-gradient(135deg, #4cc9f0, #3a86ff);
        }
        
        .feature-3 {
            background: linear-gradient(135deg, #f72585, #b5179e);
        }
        
        .feature-4 {
            background: linear-gradient(135deg, #7209b7, #560bad);
        }
        
        .welcome-user {
            font-size: 1.2rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .welcome-user i {
            font-size: 1.5rem;
        }
        
        .admin-badge {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .quick-stats {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .dashboard-header h3 {
                font-size: 1.5rem;
            }
            
            .feature-card {
                padding: 20px 15px;
            }
            
            .feature-card i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
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

    <!-- Quick Stats -->
    <div class="quick-stats">
        <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar me-2"></i>System Overview</h5>
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value">24</div>
                    <div class="stat-label">Total Committees</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value">156</div>
                    <div class="stat-label">Total Candidates</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-value">12</div>
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
                        <a href="backup.php" class="btn btn-secondary text-start">
                            <i class="fas fa-database me-2"></i>System Backup
                        </a>
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