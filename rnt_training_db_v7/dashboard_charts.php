<?php
session_name('rnt_training_db');
session_start();
$username = $_SESSION['username']; // chairman
$user_type = $_SESSION['user_type']; // admin
$office = $_SESSION['office'];
$code = $_SESSION['code'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include('db/db.php');
include('includes/header.php');

// Fetch data for charts
$designation_data = [];
$training_data = [];
$posting_data = [];

// Get designation distribution
$sql_designation = "SELECT designation, COUNT(*) as count FROM employees GROUP BY designation ORDER BY count DESC LIMIT 10";
$result_designation = mysqli_query($conn, $sql_designation);
while ($row = mysqli_fetch_assoc($result_designation)) {
    $designation_data[] = $row;
}

// Get training title distribution
$sql_training = "SELECT training_title, COUNT(*) as count FROM office_order GROUP BY training_title ORDER BY count DESC LIMIT 10";
$result_training = mysqli_query($conn, $sql_training);
while ($row = mysqli_fetch_assoc($result_training)) {
    $training_data[] = $row;
}

// Get place of posting distribution
$sql_posting = "SELECT place_of_posting, COUNT(*) as count FROM employees GROUP BY place_of_posting ORDER BY count DESC LIMIT 10";
$result_posting = mysqli_query($conn, $sql_posting);
while ($row = mysqli_fetch_assoc($result_posting)) {
    $posting_data[] = $row;
}

// Get monthly training data for line chart
$sql_monthly = "SELECT 
    MONTH(start_date) as month_num,
    MONTHNAME(start_date) as month_name,
    COUNT(*) as training_count
    FROM office_order 
    WHERE YEAR(start_date) = YEAR(CURDATE())
    GROUP BY MONTH(start_date), MONTHNAME(start_date)
    ORDER BY month_num";
$result_monthly = mysqli_query($conn, $sql_monthly);
$monthly_data = [];
while ($row = mysqli_fetch_assoc($result_monthly)) {
    $monthly_data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Analytics Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --info-color: #7209b7;
            --warning-color: #f72585;
            --dark-color: #2b2d42;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-header {
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .chart-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
        }
        
        .total-count {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-1 { border-left: 4px solid #4361ee; }
        .card-2 { border-left: 4px solid #3a0ca3; }
        .card-3 { border-left: 4px solid #4cc9f0; }
        .card-4 { border-left: 4px solid #f72585; }
        
        .btn-dashboard {
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-dashboard:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .filter-buttons {
            margin-bottom: 1rem;
        }
        
        .filter-btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 10px;
            font-size: 0.8rem;
        }
        
        .legend-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 5px;
            border-radius: 2px;
        }
        
        .dashboard-nav {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        @media (max-width: 768px) {
            .chart-container {
                margin-bottom: 1rem;
            }
            
            .total-count {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold"><i class="fas fa-chart-pie me-3"></i>Training Analytics Dashboard</h1>
                <p class="lead mb-0">Real-time insights and statistics from your training database</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group" role="group">
                    <a href="index.php" class="btn btn-light btn-dashboard me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>Main Dashboard
                    </a>
                    <button class="btn btn-light btn-dashboard" onclick="refreshCharts()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4 animate__animated animate__fadeInUp">
        <?php
        // Get total counts
        $total_employees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM employees"))['total'];
        $total_trainings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM office_order"))['total'];
        $total_designations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT designation) as total FROM employees"))['total'];
        $total_postings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT place_of_posting) as total FROM employees"))['total'];
        ?>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card card-1 animate__animated animate__fadeInLeft">
                <div class="stat-icon text-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="total-count"><?php echo number_format($total_employees); ?></div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card card-2 animate__animated animate__fadeInLeft" style="animation-delay: 0.1s">
                <div class="stat-icon text-info">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="total-count"><?php echo number_format($total_trainings); ?></div>
                <div class="stat-label">Total Trainings</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card card-3 animate__animated animate__fadeInLeft" style="animation-delay: 0.2s">
                <div class="stat-icon text-success">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="total-count"><?php echo number_format($total_designations); ?></div>
                <div class="stat-label">Designations</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="stat-card card-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.3s">
                <div class="stat-icon text-warning">
                    <i class="fas fa-building"></i>
                </div>
                <div class="total-count"><?php echo number_format($total_postings); ?></div>
                <div class="stat-label">Posting Locations</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Designation Distribution -->
        <div class="col-lg-6 col-md-12">
            <div class="chart-container animate__animated animate__fadeInUp">
                <h4 class="chart-title">
                    <i class="fas fa-user-tie me-2"></i>Designation Distribution
                </h4>
                <div class="chart-wrapper">
                    <canvas id="designationChart"></canvas>
                </div>
                <div class="mt-3">
                    <?php
                    $colors = ['#4361ee', '#3a0ca3', '#4cc9f0', '#7209b7', '#f72585', '#4895ef', '#560bad', '#b5179e', '#f15bb5', '#9b5de5'];
                    $i = 0;
                    foreach ($designation_data as $item):
                    ?>
                    <div class="legend-item">
                        <span class="legend-color" style="background-color: <?php echo $colors[$i % count($colors)]; ?>"></span>
                        <?php echo htmlspecialchars(substr($item['designation'], 0, 20)); ?> (<?php echo $item['count']; ?>)
                    </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Training Title Distribution -->
        <div class="col-lg-6 col-md-12">
            <div class="chart-container animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <h4 class="chart-title">
                    <i class="fas fa-book me-2"></i>Training Topics Distribution
                </h4>
                <div class="chart-wrapper">
                    <canvas id="trainingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Place of Posting Distribution -->
        <div class="col-lg-6 col-md-12">
            <div class="chart-container animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <h4 class="chart-title">
                    <i class="fas fa-map-marker-alt me-2"></i>Place of Posting Distribution
                </h4>
                <div class="chart-wrapper">
                    <canvas id="postingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Training Trend -->
        <div class="col-lg-6 col-md-12">
            <div class="chart-container animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <h4 class="chart-title">
                    <i class="fas fa-chart-line me-2"></i>Monthly Training Trend (Current Year)
                </h4>
                <div class="chart-wrapper">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="row">
        <div class="col-12">
            <div class="table-container animate__animated animate__fadeIn">
                <h4 class="chart-title mb-3">
                    <i class="fas fa-table me-2"></i>Top Designations with Training Count
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Designation</th>
                                <th>Employee Count</th>
                                <th>Training Count</th>
                                <th>Training %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_top = "SELECT 
                                e.designation,
                                COUNT(DISTINCT e.id) as emp_count,
                                COUNT(DISTINCT oo.id) as training_count
                                FROM employees e
                                LEFT JOIN office_order oo ON e.id = oo.employee_id
                                GROUP BY e.designation
                                ORDER BY emp_count DESC
                                LIMIT 10";
                            
                            $result_top = mysqli_query($conn, $sql_top);
                            $counter = 1;
                            while ($row = mysqli_fetch_assoc($result_top)):
                                $percentage = $row['emp_count'] > 0 ? round(($row['training_count'] / $row['emp_count']) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['designation']); ?></strong></td>
                                <td><?php echo $row['emp_count']; ?></td>
                                <td><?php echo $row['training_count']; ?></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?php echo min($percentage, 100); ?>%">
                                            <?php echo $percentage; ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Generate vibrant colors
function generateColors(count) {
    const colors = [];
    const hueStep = 360 / count;
    
    for (let i = 0; i < count; i++) {
        const hue = i * hueStep;
        colors.push(`hsl(${hue}, 70%, 60%)`);
    }
    
    return colors;
}

// Designation Chart
const designationCtx = document.getElementById('designationChart').getContext('2d');
const designationChart = new Chart(designationCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode(array_column($designation_data, 'designation')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($designation_data, 'count')); ?>,
            backgroundColor: generateColors(<?php echo count($designation_data); ?>),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.raw + ' employees';
                        return label;
                    }
                }
            }
        }
    }
});

// Training Chart
const trainingCtx = document.getElementById('trainingChart').getContext('2d');
const trainingChart = new Chart(trainingCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_column($training_data, 'training_title')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($training_data, 'count')); ?>,
            backgroundColor: [
                '#4361ee', '#3a0ca3', '#4cc9f0', '#7209b7', '#f72585',
                '#4895ef', '#560bad', '#b5179e', '#f15bb5', '#9b5de5'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Posting Chart
const postingCtx = document.getElementById('postingChart').getContext('2d');
const postingChart = new Chart(postingCtx, {
    type: 'polarArea',
    data: {
        labels: <?php echo json_encode(array_column($posting_data, 'place_of_posting')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($posting_data, 'count')); ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right'
            }
        }
    }
});

// Monthly Trend Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthly_data, 'month_name')); ?>,
        datasets: [{
            label: 'Trainings Conducted',
            data: <?php echo json_encode(array_column($monthly_data, 'training_count')); ?>,
            backgroundColor: 'rgba(67, 97, 238, 0.1)',
            borderColor: '#4361ee',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#4361ee',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
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
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Refresh charts function
function refreshCharts() {
    // Show loading animation
    const cards = document.querySelectorAll('.stat-card, .chart-container');
    cards.forEach(card => {
        card.style.opacity = '0.7';
    });
    
    // Reload page after short delay
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Add animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    }, observerOptions);

    // Observe all chart containers
    document.querySelectorAll('.chart-container').forEach(el => {
        observer.observe(el);
    });
});
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>