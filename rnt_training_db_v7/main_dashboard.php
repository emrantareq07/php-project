<?php
session_name('rnt_training_db');
session_start();
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];
$code = $_SESSION['code'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include('db/db.php');
include('includes/header.php');

// Fetch all data for dashboard
$stats = [];

// Get total counts
$stats['total_employees'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM employees"))['total'];
$stats['total_bcic_staff'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bcic_staff"))['total'];
$stats['total_trainings'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM office_order"))['total'];
$stats['total_training_titles'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM training_list"))['total'];
$stats['total_designations'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM designation"))['total'];
$stats['total_postings'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM place_of_posting"))['total'];
$stats['total_countries'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM countries"))['total'];

// Get designation distribution
$designation_data = [];
$sql_designation = "SELECT d.designation, COUNT(e.id) as employee_count 
                    FROM designation d 
                    LEFT JOIN employees e ON d.designation = e.designation 
                    GROUP BY d.id 
                    ORDER BY employee_count DESC 
                    LIMIT 8";
$result_designation = mysqli_query($conn, $sql_designation);
while ($row = mysqli_fetch_assoc($result_designation)) {
    $designation_data[] = $row;
}

// Get training list distribution
$training_data = [];
$sql_training = "SELECT t_name, 
                (SELECT COUNT(*) FROM office_order oo WHERE oo.training_title LIKE CONCAT('%', tl.t_name, '%')) as training_count
                FROM training_list tl 
                ORDER BY training_count DESC 
                LIMIT 8";
$result_training = mysqli_query($conn, $sql_training);
while ($row = mysqli_fetch_assoc($result_training)) {
    $training_data[] = $row;
}

// Get place of posting distribution
$posting_data = [];
$sql_posting = "SELECT p.place_of_posting, 
                (SELECT COUNT(*) FROM employees e WHERE e.place_of_posting = p.place_of_posting) as employee_count,
                (SELECT COUNT(*) FROM bcic_staff b WHERE b.place_of_posting = p.place_of_posting) as bcic_count
                FROM place_of_posting p 
                ORDER BY (employee_count + bcic_count) DESC 
                LIMIT 8";
$result_posting = mysqli_query($conn, $sql_posting);
while ($row = mysqli_fetch_assoc($result_posting)) {
    $posting_data[] = $row;
}

// Get monthly training trend
$monthly_data = [];
$sql_monthly = "SELECT 
    MONTH(start_date) as month_num,
    MONTHNAME(start_date) as month_name,
    COUNT(*) as training_count
    FROM office_order 
    WHERE YEAR(start_date) = YEAR(CURDATE())
    GROUP BY MONTH(start_date), MONTHNAME(start_date)
    ORDER BY month_num";
$result_monthly = mysqli_query($conn, $sql_monthly);
while ($row = mysqli_fetch_assoc($result_monthly)) {
    $monthly_data[] = $row;
}

// Get top performing offices - FIXED: Since office_order doesn't have emp_id, we'll calculate differently
$office_performance = [];
$sql_office = "SELECT 
    e.place_of_posting,
    COUNT(DISTINCT e.id) as total_employees,
    (SELECT COUNT(*) FROM office_order) as total_trainings, -- Total trainings for all
    ROUND((SELECT COUNT(*) FROM office_order) * 100.0 / (SELECT COUNT(*) FROM employees), 2) as training_percentage
    FROM employees e
    WHERE e.place_of_posting != ''
    GROUP BY e.place_of_posting
    HAVING total_employees > 0
    ORDER BY total_employees DESC
    LIMIT 10";
$result_office = mysqli_query($conn, $sql_office);
while ($row = mysqli_fetch_assoc($result_office)) {
    $office_performance[] = $row;
}

// Get recent activities - FIXED: Simplified query
$recent_activities = [];
$sql_recent = "SELECT 
    'Training Program' as type,
    training_title as title,
    start_date as date,
    CONCAT('Ref: ', ref_no) as details
    FROM office_order 
    WHERE start_date IS NOT NULL
    ORDER BY start_date DESC 
    LIMIT 10";
$result_recent = mysqli_query($conn, $sql_recent);
while ($row = mysqli_fetch_assoc($result_recent)) {
    $recent_activities[] = $row;
}

// Get employee vs bcic staff ratio
$staff_ratio = [
    'employees' => $stats['total_employees'],
    'bcic_staff' => $stats['total_bcic_staff']
];

// Get unique training attendees count (if you have a relation table)
// For now, we'll show total trainings
$stats['training_participants'] = $stats['total_employees'] + $stats['total_bcic_staff'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Management Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);
            --light-gradient: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem 2rem;
            border-radius: 20px;
            margin-bottom: 1rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.1;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }
        
        .stat-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1.2rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .stat-card-1::before { background: var(--primary-gradient); }
        .stat-card-2::before { background: var(--secondary-gradient); }
        .stat-card-3::before { background: var(--success-gradient); }
        .stat-card-4::before { background: var(--warning-gradient); }
        .stat-card-5::before { background: var(--info-gradient); }
        .stat-card-6::before { background: var(--dark-gradient); }
        
        .total-count {
            font-size: 2.8rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            line-height: 1;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }
        
        .stat-change {
            position: absolute;
            bottom: 1.5rem;
            right: 1.5rem;
            font-size: 0.85rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            background: rgba(108, 117, 125, 0.1);
        }
        
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .chart-container:hover {
            transform: translateY(-5px);
        }
        
        .chart-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 3px solid;
            border-image: var(--primary-gradient) 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chart-title i {
            font-size: 1.3rem;
        }
        
        .dashboard-nav {
            background: white;
            border-radius: 15px;
            padding: 1.2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .nav-pills .nav-link {
            border-radius: 10px;
            padding: 0.8rem 1.5rem;
            margin-right: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link.active {
            background: var(--primary-gradient);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-dashboard {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 5px solid;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .activity-card:hover {
            transform: translateX(5px);
        }
        
        .activity-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-right: 1rem;
        }
        
        .progress-bar-gradient {
            background: var(--primary-gradient);
            border-radius: 10px;
        }
        
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 4px;
            display: inline-block;
            margin-right: 8px;
        }
        
        .table-hover tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
        }
        
        .glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
            }
            to {
                box-shadow: 0 0 30px rgba(102, 126, 234, 0.4);
            }
        }
        
        .dashboard-footer {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 3rem;
        }
        
        .watermark {
            position: absolute;
            bottom: 10px;
            right: 20px;
            font-size: 0.8rem;
            color: rgba(108, 117, 125, 0.3);
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
            }
            
            .total-count {
                font-size: 2.2rem;
            }
            
            .chart-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid py-2">
    <!-- Dashboard Header -->
    <div class="dashboard-header animate__animated animate__fadeIn" data-aos="fade-down">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-chart-network me-3"></i>Training Management Analytics
                </h1>
                <p class="lead mb-0 opacity-90">
                    <i class="fas fa-info-circle me-2"></i>
                    Comprehensive insights and real-time statistics from your training database
                </p>
            </div>
            <div class="col-md-4 text-end position-relative">
                <div class="btn-group" role="group">
                    <button class="btn btn-light btn-lg me-2 glow" onclick="window.location.href='dashboard.php'">
                        <i class="fas fa-tachometer-alt me-2"></i>Main View
                    </button>
                    <button class="btn btn-light btn-lg" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                    <a href="includes/logout.php" class="btn btn-gradient-danger  btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                </div>
                <div class="watermark">
                    <i class="fas fa-database me-1"></i> 
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="dashboard-nav animate__animated animate__fadeIn" data-aos="fade-up" data-aos-delay="100">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" href="#overview" data-bs-toggle="tab">
                    <i class="fas fa-globe me-2"></i>Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#analytics" data-bs-toggle="tab">
                    <i class="fas fa-chart-bar me-2"></i>Analytics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#performance" data-bs-toggle="tab">
                    <i class="fas fa-trophy me-2"></i>Performance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#activities" data-bs-toggle="tab">
                    <i class="fas fa-history me-2"></i>Activities
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview">
            <!-- Quick Stats -->
            <div class="row mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stat-card stat-card-1">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="total-count"><?php echo number_format($stats['total_employees']); ?></div>
                        <div class="stat-label">Total Employees</div>
                        <div class="stat-change">
                            <i class="fas fa-user-plus me-1"></i> Active
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stat-card stat-card-2">
                        <div class="stat-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="total-count"><?php echo number_format($stats['total_bcic_staff']); ?></div>
                        <div class="stat-label">BCIC Staff</div>
                        <div class="stat-change">
                            <i class="fas fa-building me-1"></i> Corporate
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stat-card stat-card-3">
                        <div class="stat-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="total-count"><?php echo number_format($stats['total_trainings']); ?></div>
                        <div class="stat-label">Total Trainings</div>
                        <div class="stat-change">
                            <i class="fas fa-chart-line me-1"></i> Programs
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stat-card stat-card-4">
                        <div class="stat-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="total-count"><?php echo number_format($stats['total_training_titles']); ?></div>
                        <div class="stat-label">Training Programs</div>
                        <div class="stat-change">
                            <i class="fas fa-list me-1"></i> Available
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stat-card stat-card-5">
                        <div class="stat-icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="total-count"><?php echo number_format($stats['total_designations']); ?></div>
                        <div class="stat-label">Designations</div>
                        <div class="stat-change">
                            <i class="fas fa-sitemap me-1"></i> Roles
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="stat-card stat-card-6">
                        <div class="stat-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="total-count"><?php echo number_format($stats['total_postings']); ?></div>
                        <div class="stat-label">Posting Locations</div>
                        <div class="stat-change">
                            <i class="fas fa-location-arrow me-1"></i> Active
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Charts Row -->
            <div class="row mb-4">
                <div class="col-lg-6 col-md-12">
                    <div class="chart-container" data-aos="fade-right">
                        <h4 class="chart-title">
                            <i class="fas fa-user-tie text-primary"></i>Designation Distribution
                        </h4>
                        <canvas id="designationChart" height="250"></canvas>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="chart-container" data-aos="fade-left">
                        <h4 class="chart-title">
                            <i class="fas fa-chart-pie text-warning"></i>Staff Composition
                        </h4>
                        <canvas id="staffChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analytics">
            <div class="row mb-4">
                <div class="col-lg-6 col-md-12">
                    <div class="chart-container" data-aos="zoom-in">
                        <h4 class="chart-title">
                            <i class="fas fa-book-open text-info"></i>Top Training Programs
                        </h4>
                        <canvas id="trainingChart" height="300"></canvas>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="chart-container" data-aos="zoom-in" data-aos-delay="100">
                        <h4 class="chart-title">
                            <i class="fas fa-map-marker-alt text-danger"></i>Place of Posting Distribution
                        </h4>
                        <canvas id="postingChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="chart-container" data-aos="fade-up">
                        <h4 class="chart-title">
                            <i class="fas fa-chart-line text-success"></i>Monthly Training Trend (<?php echo date('Y'); ?>)
                        </h4>
                        <canvas id="monthlyChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tab -->
        <div class="tab-pane fade" id="performance">
            <div class="row mb-4">
                <div class="col-lg-8 col-md-12">
                    <div class="chart-container" data-aos="fade-right">
                        <h4 class="chart-title">
                            <i class="fas fa-trophy text-warning"></i>Office Distribution by Employee Count
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="table-dark">
                                        <th>Rank</th>
                                        <th>Office Location</th>
                                        <th>Employees</th>
                                        <th>BCIC Staff</th>
                                        <th>Total Staff</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_staff = $stats['total_employees'] + $stats['total_bcic_staff'];
                                    foreach ($posting_data as $index => $posting): 
                                        $staff_total = $posting['employee_count'] + $posting['bcic_count'];
                                        $percentage = $total_staff > 0 ? round(($staff_total / $total_staff) * 100, 1) : 0;
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="badge rounded-pill bg-primary">#<?php echo $index + 1; ?></span>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($posting['place_of_posting']); ?></strong></td>
                                        <td><?php echo $posting['employee_count']; ?></td>
                                        <td><?php echo $posting['bcic_count']; ?></td>
                                        <td><?php echo $staff_total; ?></td>
                                        <td style="width: 200px;">
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar progress-bar-gradient" 
                                                         role="progressbar" 
                                                         style="width: <?php echo $percentage; ?>%">
                                                    </div>
                                                </div>
                                                <span class="text-nowrap"><?php echo $percentage; ?>%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="chart-container" data-aos="fade-left">
                        <h4 class="chart-title">
                            <i class="fas fa-award text-success"></i>Training Statistics
                        </h4>
                        <div class="text-center py-4">
                            <?php
                            $avg_trainings_per_employee = $stats['total_employees'] > 0 ? 
                                round($stats['total_trainings'] / $stats['total_employees'], 1) : 0;
                            ?>
                            <div class="display-1 fw-bold text-primary mb-3">
                                <?php echo $avg_trainings_per_employee; ?>x
                            </div>
                            <p class="text-muted mb-4">Avg. Trainings per Employee</p>
                            
                            <div class="row text-start">
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Training Completion Rate:</span>
                                        <strong>
                                            <?php 
                                            $completion_rate = $stats['total_employees'] > 0 ? 
                                                round(($stats['total_trainings'] / ($stats['total_employees'] * 2)) * 100, 1) : 0;
                                            echo min($completion_rate, 100); 
                                            ?>%
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Active Training Programs:</span>
                                        <strong><?php echo $stats['total_training_titles']; ?></strong>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Countries Covered:</span>
                                        <strong><?php echo $stats['total_countries']; ?></strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button class="btn btn-dashboard w-100" onclick="window.location.href='index.php'">
                                    <i class="fas fa-eye me-2"></i>View All Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Tab -->
        <div class="tab-pane fade" id="activities">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="chart-container" data-aos="fade-up">
                        <h4 class="chart-title">
                            <i class="fas fa-history text-info"></i>Recent Training Activities
                        </h4>
                        <?php foreach ($recent_activities as $activity): 
                            $icon_bg = 'bg-primary';
                            $icon = 'fa-graduation-cap';
                            $border_color = '#667eea';
                        ?>
                        <div class="activity-card" style="border-left-color: <?php echo $border_color; ?>">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon <?php echo $icon_bg; ?>">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($activity['title']); ?></h6>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <?php echo $activity['details']; ?>
                                    </p>
                                    <?php if ($activity['date']): ?>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Date: <?php echo date('F j, Y', strtotime($activity['date'])); ?>
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($recent_activities)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent training activities found.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="chart-container" data-aos="fade-up" data-aos-delay="100">
                        <h4 class="chart-title">
                            <i class="fas fa-globe-asia text-warning"></i>Global Reach
                        </h4>
                        <div class="text-center py-5">
                            <div class="display-4 fw-bold text-primary mb-3">
                                <?php echo $stats['total_countries']; ?>
                            </div>
                            <p class="text-muted mb-4">Countries in Database</p>
                            
                            <div class="row text-start mb-4">
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-success me-3"></i>
                                        <div>
                                            <div class="fw-bold"><?php echo $stats['total_employees']; ?></div>
                                            <small class="text-muted">Total Employees</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-tie text-info me-3"></i>
                                        <div>
                                            <div class="fw-bold"><?php echo $stats['total_bcic_staff']; ?></div>
                                            <small class="text-muted">BCIC Staff</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-graduation-cap text-warning me-3"></i>
                                        <div>
                                            <div class="fw-bold"><?php echo $stats['total_trainings']; ?></div>
                                            <small class="text-muted">Training Programs</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button class="btn btn-outline-primary w-100 mb-3" onclick="window.location.href='index.php'">
                                    <i class="fas fa-database me-2"></i>Manage Database
                                </button>
                                <button class="btn btn-dashboard w-100" onclick="window.location.href='index.php'">
                                    <i class="fas fa-plus me-2"></i>Add New Entry
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Footer -->
    <div class="dashboard-footer" data-aos="fade-up">
        <p class="mb-2">
            <i class="fas fa-shield-alt me-2"></i>
            Data last updated: <?php echo date('F j, Y, g:i a'); ?>
        </p>
        <p class="text-muted">
            <i class="fas fa-copyright me-1"></i>
            <?php echo date('Y'); ?> Design & Developed By | ICT Division, BCIC.
        </p>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
// Initialize AOS
AOS.init({
    duration: 1000,
    once: true,
    offset: 100
});

// Generate vibrant colors
const colorPalettes = {
    primary: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#43e97b', '#38f9d7', '#fa709a', '#fee140'],
    pastel: ['#FFB6C1', '#87CEEB', '#98FB98', '#DDA0DD', '#FFD700', '#FFA07A', '#20B2AA', '#778899', '#FF6347', '#7B68EE']
};

// Designation Chart
const designationCtx = document.getElementById('designationChart').getContext('2d');
new Chart(designationCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($designation_data, 'designation')); ?>,
        datasets: [{
            label: 'Employees Count',
            data: <?php echo json_encode(array_column($designation_data, 'employee_count')); ?>,
            backgroundColor: colorPalettes.primary.slice(0, <?php echo count($designation_data); ?>),
            borderColor: 'rgba(255, 255, 255, 0.8)',
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
                padding: 12,
                cornerRadius: 6
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    }
});

// Staff Composition Chart
const staffCtx = document.getElementById('staffChart').getContext('2d');
new Chart(staffCtx, {
    type: 'doughnut',
    data: {
        labels: ['Regular Employees', 'BCIC Staff'],
        datasets: [{
            data: [<?php echo $staff_ratio['employees']; ?>, <?php echo $staff_ratio['bcic_staff']; ?>],
            backgroundColor: [colorPalettes.primary[0], colorPalettes.primary[1]],
            borderColor: ['#fff', '#fff'],
            borderWidth: 3,
            hoverOffset: 20
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 13,
                        weight: '600'
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.raw / total) * 100).toFixed(1);
                        return `${context.label}: ${context.raw} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Training Programs Chart
const trainingCtx = document.getElementById('trainingChart').getContext('2d');
new Chart(trainingCtx, {
    type: 'polarArea',
    data: {
        labels: <?php echo json_encode(array_column($training_data, 't_name')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($training_data, 'training_count')); ?>,
            backgroundColor: colorPalettes.pastel.slice(0, <?php echo count($training_data); ?>),
            borderColor: 'rgba(255, 255, 255, 0.8)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        }
    }
});

// Posting Distribution Chart
const postingCtx = document.getElementById('postingChart').getContext('2d');
new Chart(postingCtx, {
    type: 'radar',
    data: {
        labels: <?php echo json_encode(array_column($posting_data, 'place_of_posting')); ?>,
        datasets: [{
            label: 'Employees Count',
            data: <?php echo json_encode(array_column($posting_data, 'employee_count')); ?>,
            backgroundColor: 'rgba(102, 126, 234, 0.2)',
            borderColor: colorPalettes.primary[0],
            borderWidth: 3,
            pointBackgroundColor: colorPalettes.primary[0],
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
        }, {
            label: 'BCIC Staff Count',
            data: <?php echo json_encode(array_column($posting_data, 'bcic_count')); ?>,
            backgroundColor: 'rgba(118, 75, 162, 0.2)',
            borderColor: colorPalettes.primary[1],
            borderWidth: 3,
            pointBackgroundColor: colorPalettes.primary[1],
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        scales: {
            r: {
                angleLines: {
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    backdropColor: 'transparent'
                }
            }
        },
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Trend Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthly_data, 'month_name')); ?>,
        datasets: [{
            label: 'Trainings Conducted',
            data: <?php echo json_encode(array_column($monthly_data, 'training_count')); ?>,
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderColor: colorPalettes.primary[0],
            borderWidth: 4,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: colorPalettes.primary[0],
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointRadius: 8,
            pointHoverRadius: 12
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Refresh dashboard function
function refreshDashboard() {
    // Show loading animation
    const dashboard = document.querySelector('.container-fluid');
    dashboard.style.opacity = '0.7';
    
    // Create loading spinner
    const spinner = document.createElement('div');
    spinner.className = 'position-fixed top-50 start-50 translate-middle';
    spinner.innerHTML = `
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    document.body.appendChild(spinner);
    
    // Reload page after delay
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Tab change animation
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        AOS.refresh();
    });
});

// Auto refresh every 5 minutes
setTimeout(refreshDashboard, 5 * 60 * 1000);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

</body>
</html>