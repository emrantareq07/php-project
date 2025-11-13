<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
$_SESSION['username'] = 'admin';

 $_SESSION['role'] = 'admin'; 

$username = $_SESSION['username'];
$is_admin = ($username === 'admin'); // Check if user is admin

$role = $_SESSION['role'] ?? ''; // ensure role exists

// Table names
$officer_tbl     = 'officers_tbl';
$staff_tbl       = 'staffs_tbl'; 
$worker_tbl      = 'workers_tbl';
$daily_basis_tbl = 'daily_basis_tbl';
$ansar_tbl       = 'ansar_tbl';

// Bangla number conversion
function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return str_replace($englishNumbers, $banglaNumbers, $number);
}

// ---------- HELPER FUNCTIONS ----------

// Total row count by username (factory_name)
function getTotalRows($conn, $table, $username = null) {
    if ($username) {
        $sql = "SELECT COUNT(*) AS total FROM $table WHERE factory_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $result = $conn->query($sql);
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Sum of all male/female columns for officers table
function getOfficerGenderSum($conn, $table, $prefixList, $username = null) {
    $sumMale = 0;
    $sumFemale = 0;
    foreach ($prefixList as $g) {
        if ($username) {
            $sql = "SELECT 
                        COALESCE(SUM({$g}_m), 0) AS male,
                        COALESCE(SUM({$g}_f), 0) AS female
                    FROM $table
                    WHERE factory_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT 
                        COALESCE(SUM({$g}_m), 0) AS male,
                        COALESCE(SUM({$g}_f), 0) AS female
                    FROM $table";
            $result = $conn->query($sql);
        }
        $row = $result->fetch_assoc();
        $sumMale += $row['male'];
        $sumFemale += $row['female'];
    }
    return ['male' => $sumMale, 'female' => $sumFemale];
}

// Sum of male/female for standard tables
function getStandardTableGenderSum($conn, $table, $username = null) {
    if ($username) {
        $sql = "SELECT 
                    COALESCE(SUM(male), 0) AS male,
                    COALESCE(SUM(female), 0) AS female
                FROM $table
                WHERE factory_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT 
                    COALESCE(SUM(male), 0) AS male,
                    COALESCE(SUM(female), 0) AS female
                FROM $table";
        $result = $conn->query($sql);
    }
    return $result->fetch_assoc();
}

// Get all factories for admin view
function getAllFactories($conn) {
    $factories = [];
    
    // Get unique factory names from all tables
    $tables = ['officers_tbl', 'staffs_tbl', 'workers_tbl', 'daily_basis_tbl', 'ansar_tbl'];
    
    foreach ($tables as $table) {
        $sql = "SELECT DISTINCT factory_name FROM $table WHERE factory_name != 'admin'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $factory_name = $row['factory_name'];
            if (!in_array($factory_name, $factories)) {
                $factories[] = $factory_name;
            }
        }
    }
    
    return $factories;
}

// Get factory-wise data for admin
function getFactoryData($conn, $factory_name) {
    $officerGrades = ['g2','g3','g4','g5','g6','g7','g8','g9','g10'];
    
    $data = [
        'factory_name' => $factory_name,
        'officer' => getOfficerGenderSum($conn, 'officers_tbl', $officerGrades, $factory_name),
        'staff' => getStandardTableGenderSum($conn, 'staffs_tbl', $factory_name),
        'worker' => getStandardTableGenderSum($conn, 'workers_tbl', $factory_name),
        'daily_basis' => getStandardTableGenderSum($conn, 'daily_basis_tbl', $factory_name),
        'ansar' => getStandardTableGenderSum($conn, 'ansar_tbl', $factory_name)
    ];
    
    // Calculate totals
    $data['total_male'] = $data['officer']['male'] + $data['staff']['male'] + $data['worker']['male'] + 
                          $data['daily_basis']['male'] + $data['ansar']['male'];
    $data['total_female'] = $data['officer']['female'] + $data['staff']['female'] + $data['worker']['female'] + 
                            $data['daily_basis']['female'] + $data['ansar']['female'];
    $data['total_people'] = $data['total_male'] + $data['total_female'];
    
    return $data;
}

// ---------- FETCH DATA ----------

$officerGrades = ['g2','g3','g4','g5','g6','g7','g8','g9','g10'];

if ($is_admin) {
    // ADMIN VIEW: All factories data
    $all_factories = getAllFactories($conn);
    $factory_data = [];
    $grand_totals = ['male' => 0, 'female' => 0, 'total' => 0];
    
    foreach ($all_factories as $factory) {
        $data = getFactoryData($conn, $factory);
        $factory_data[$factory] = $data;
        
        $grand_totals['male'] += $data['total_male'];
        $grand_totals['female'] += $data['total_female'];
        $grand_totals['total'] += $data['total_people'];
    }
    
    // Overall totals for admin
    $total_people = $grand_totals['total'];
    $total_people_male = $grand_totals['male'];
    $total_people_female = $grand_totals['female'];
    
} else {
    // FACTORY VIEW: Single factory data
    $officerGender = getOfficerGenderSum($conn, $officer_tbl, $officerGrades, $username);
    $staffGender = getStandardTableGenderSum($conn, $staff_tbl, $username);
    $workerGender = getStandardTableGenderSum($conn, $worker_tbl, $username);
    $dailyGender = getStandardTableGenderSum($conn, $daily_basis_tbl, $username);
    $ansarGender = getStandardTableGenderSum($conn, $ansar_tbl, $username);

    $total_officers = getTotalRows($conn, $officer_tbl, $username);
    $total_staff = getTotalRows($conn, $staff_tbl, $username);
    $total_workers = getTotalRows($conn, $worker_tbl, $username);
    $total_daily = getTotalRows($conn, $daily_basis_tbl, $username);
    $total_ansar = getTotalRows($conn, $ansar_tbl, $username);

    // Gender totals
    $officer_male   = $officerGender['male'];
    $officer_female = $officerGender['female'];
    $staff_male     = $staffGender['male'];
    $staff_female   = $staffGender['female'];
    $worker_male    = $workerGender['male'];
    $worker_female  = $workerGender['female'];
    $daily_male     = $dailyGender['male'];
    $daily_female   = $dailyGender['female'];
    $ansar_male     = $ansarGender['male'];
    $ansar_female   = $ansarGender['female'];

    // Totals
    $total_people_male   = $officer_male + $staff_male + $worker_male + $daily_male + $ansar_male;
    $total_people_female = $officer_female + $staff_female + $worker_female + $daily_female + $ansar_female;
    $total_people        = $total_people_male + $total_people_female;
}

// Convert to Bangla
$total_people_b          = englishToBanglaNumber($total_people);
$total_people_male_b     = englishToBanglaNumber($total_people_male);
$total_people_female_b   = englishToBanglaNumber($total_people_female);

if (!$is_admin) {
    // Factory-specific Bangla numbers
    $total_officers_b = englishToBanglaNumber($total_officers);
    $total_staff_b    = englishToBanglaNumber($total_staff);
    $total_workers_b  = englishToBanglaNumber($total_workers);
    $total_daily_b    = englishToBanglaNumber($total_daily);
    $total_ansar_b    = englishToBanglaNumber($total_ansar);
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Man Power Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
               
body { font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif; background: #f8f9fa; }
.sidebar { height: 100vh; width: 220px; position: fixed; background: #007bff; color: #fff; padding-top: 1rem; transition: .3s; }
.sidebar.collapsed { width: 60px; }
.sidebar a { color: #fff; text-decoration: none; display: block; padding: .75rem 1rem; border-radius: 5px; margin-bottom: .25rem; }
.sidebar a:hover, .sidebar a.active { background: #0056b3; }
.sidebar i { width: 25px; }
.main-content { margin-left: 220px; padding: 2rem; transition: .3s; }
.main-content.collapsed { margin-left: 60px; }
.card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.people-count { font-size: 1.1rem; font-weight: 600; }
.factory-table th { background-color: #f8f9fa; }
.factory-card { transition: transform 0.2s; }
.factory-card:hover { transform: translateY(-2px); }

/* Font Definitions */
@font-face {
  font-family: 'Nikosh';
  src: url('fonts/Nikosh.ttf') format('truetype'),
       url('fonts/Nikosh.woff') format('woff'),
       url('fonts/Nikosh.woff2') format('woff2');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

/* Base Typography */
* {
  font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
}
</style>
</head>
<body>

<div class="sidebar d-flex flex-column" id="sidebar">
    <div class="text-center mb-4">
        <img src="../assets/bcic_logo.png" alt="Logo" width="50" class="mb-2">
        <h6 class="fs-6">BCIC</h6>
        <small>Man Power Management</small>
    </div>

    <a href="#" class="active"><i class="fa fa-home me-2"></i>Dashboard</a>
    <a href="officer_details.php"><i class="fa fa-user-tie me-2"></i>Officers Info.</a>
    <a href="staff_details.php"><i class="fa fa-users me-2"></i>Staff Info.</a>
    <a href="worker_details.php"><i class="fa fa-hard-hat me-2"></i>Worker Info.</a>
    <a href="daily_basis_details.php"><i class="fa fa-calendar-day me-2"></i>Daily Basis Info.</a>
    <a href="ansar_details.php"><i class="fa fa-shield-alt me-2"></i>Ansar Info.</a>
    <a href="combine_details.php"><i class="fa fa-shield-alt me-2"></i>Create User</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    <a href="#" id="toggleSidebar"><i class="fa fa-bars me-2"></i>Collapse</a>
</div>

<div class="main-content" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>স্বাগতম, <?php echo htmlspecialchars($username); ?>!</h4>
            <span class="text-muted">
                <?php echo $is_admin ? 'সমস্ত কারখানা ড্যাশবোর্ড' : 'ড্যাশবোর্ড সারসংক্ষেপ'; ?>
            </span>
        </div>

        <?php if ($is_admin): ?>
        <!-- ADMIN VIEW: All Factories -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3"><i class="fas fa-industry me-2"></i>সমস্ত কারখানা সারসংক্ষেপ</h5>
            <?php echo $role; ?>
            <!-- Grand Total Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card p-3 text-center text-white bg-primary">
                        <i class="fa fa-users fs-2"></i>
                        <h5 class="mt-2">মোট কর্মী (সকল কারখানা)</h5>
                        <p class="fs-4"><?php echo $total_people_b; ?></p>
                        <div class="people-count">
                            <span class="badge bg-info">পুরুষ: <?php echo $total_people_male_b; ?></span>
                            <span class="badge bg-warning mt-1">মহিলা: <?php echo $total_people_female_b; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center text-white bg-success">
                        <i class="fa fa-industry fs-2"></i>
                        <h5 class="mt-2">মোট কারখানা</h5>
                        <p class="fs-4"><?php echo englishToBanglaNumber(count($all_factories)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Factories Table -->
            <div class="table-responsive">
                <table class="table table-bordered factory-table">
                    <thead class="table-light text-center">
                        <tr>
                            <th>কারখানা নাম</th>
                            <th>অফিসার</th>
                            <th>স্টাফ</th>
                            <th>ওয়ার্কার</th>
                            <th>দৈনিক ভিত্তি</th>
                            <th>আনসার</th>
                            <th>মোট কর্মী</th>
                            <th>পুরুষ</th>
                            <th>মহিলা</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php foreach ($factory_data as $factory => $data): ?>
                        <tr>
                            <td><strong><?php echo $factory; ?></strong></td>
                            <td class="text-center"><?php echo englishToBanglaNumber($data['officer']['male'] + $data['officer']['female']); ?></td>
                            <td class="text-center"><?php echo englishToBanglaNumber($data['staff']['male'] + $data['staff']['female']); ?></td>
                            <td class="text-center"><?php echo englishToBanglaNumber($data['worker']['male'] + $data['worker']['female']); ?></td>
                            <td class="text-center"><?php echo englishToBanglaNumber($data['daily_basis']['male'] + $data['daily_basis']['female']); ?></td>
                            <td class="text-center"><?php echo englishToBanglaNumber($data['ansar']['male'] + $data['ansar']['female']); ?></td>
                            <td class="text-center"><strong><?php echo englishToBanglaNumber($data['total_people']); ?></strong></td>
                            <td class="text-center text-primary"><?php echo englishToBanglaNumber($data['total_male']); ?></td>
                            <td class="text-center text-danger"><?php echo englishToBanglaNumber($data['total_female']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Factory-wise Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">কারখানা অনুযায়ী মোট কর্মী</h5>
                    <canvas id="factoryChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">লিঙ্গভিত্তিক বণ্টন (সকল কারখানা)</h5>
                    <canvas id="adminGenderChart"></canvas>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- FACTORY VIEW: Single Factory -->
        <!-- Total People Count -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-primary">
                    <i class="fa fa-users fs-2"></i>
                    <h5 class="mt-2">মোট কর্মী</h5>
                    <p class="fs-4"><?php echo $total_people_b; ?></p>
                    <div class="people-count">
                        <span class="badge bg-info">পুরুষ: <?php echo $total_people_male_b; ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?php echo $total_people_female_b; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-success">
                    <i class="fa fa-user-tie fs-2"></i>
                    <h5 class="mt-2">অফিসার এন্ট্রি</h5>
                    <p class="fs-4"><?php echo $total_officers_b; ?></p>
                    <div class="people-count">
                        <span class="badge bg-info">পুরুষ: <?php echo englishToBanglaNumber($officer_male); ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?php echo englishToBanglaNumber($officer_female); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-warning">
                    <i class="fa fa-user fs-2"></i>
                    <h5 class="mt-2">স্টাফ এন্ট্রি</h5>
                    <p class="fs-4"><?php echo $total_staff_b; ?></p>
                    <div class="people-count">
                        <span class="badge bg-info">পুরুষ: <?php echo englishToBanglaNumber($staff_male); ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?php echo englishToBanglaNumber($staff_female); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-danger">
                    <i class="fa fa-hard-hat fs-2"></i>
                    <h5 class="mt-2">ওয়ার্কার এন্ট্রি</h5>
                    <p class="fs-4"><?php echo $total_workers_b; ?></p>
                    <div class="people-count">
                        <span class="badge bg-info">পুরুষ: <?php echo englishToBanglaNumber($worker_male); ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?php echo englishToBanglaNumber($worker_female); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-info">
                    <i class="fa fa-calendar-day fs-2"></i>
                    <h5 class="mt-2">দৈনিক ভিত্তি এন্ট্রি</h5>
                    <p class="fs-4"><?php echo $total_daily_b; ?></p>
                    <div class="people-count">
                        <span class="badge bg-primary">পুরুষ: <?php echo englishToBanglaNumber($daily_male); ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?php echo englishToBanglaNumber($daily_female); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-secondary">
                    <i class="fa fa-shield-alt fs-2"></i>
                    <h5 class="mt-2">আনসার এন্ট্রি</h5>
                    <p class="fs-4"><?php echo $total_ansar_b; ?></p>
                    <div class="people-count">
                        <span class="badge bg-primary">পুরুষ: <?php echo englishToBanglaNumber($ansar_male); ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?php echo englishToBanglaNumber($ansar_female); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution Chart -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">লিঙ্গভিত্তিক কর্মী বণ্টন</h5>
            <canvas id="genderChart"></canvas>
        </div>

        <!-- Category Distribution Chart -->
        <div class="card p-4">
            <h5 class="mb-3">ক্যাটাগরি অনুযায়ী কর্মী বণ্টন</h5>
            <canvas id="categoryChart"></canvas>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
$('#toggleSidebar').click(() => {
    $('#sidebar').toggleClass('collapsed');
    $('#mainContent').toggleClass('collapsed');
});

<?php if ($is_admin): ?>
// ADMIN CHARTS

// Factory-wise total employees chart
const factoryCtx = document.getElementById('factoryChart').getContext('2d');
new Chart(factoryCtx, {
    type: 'bar',
    data: {
        labels: [<?php echo "'" . implode("','", array_keys($factory_data)) . "'"; ?>],
        datasets: [{
            label: 'মোট কর্মী',
            backgroundColor: '#28a745',
            data: [<?php echo implode(',', array_map(function($data) { return $data['total_people']; }, $factory_data)); ?>]
        }]
    },
    options: { 
        responsive: true, 
        scales: { y: { beginAtZero: true } },
        plugins: {
            legend: {
                labels: {
                    font: { size: 14 }
                }
            }
        }
    }
});

// Admin gender distribution chart
const adminGenderCtx = document.getElementById('adminGenderChart').getContext('2d');
new Chart(adminGenderCtx, {
    type: 'pie',
    data: {
        labels: ['পুরুষ', 'মহিলা'],
        datasets: [{
            data: [<?php echo $total_people_male . ',' . $total_people_female; ?>],
            backgroundColor: ['#0d6efd', '#fd7e14']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 14 }
                }
            }
        }
    }
});

<?php else: ?>
// FACTORY CHARTS

// Gender Distribution Chart
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'bar',
    data: {
        labels: ['অফিসার', 'স্টাফ', 'ওয়ার্কার', 'দৈনিক ভিত্তি', 'আনসার'],
        datasets: [
            { 
                label: 'পুরুষ', 
                backgroundColor: '#0d6efd', 
                data: [
                    <?= $officer_male ?>, 
                    <?= $staff_male ?>, 
                    <?= $worker_male ?>,
                    <?= $daily_male ?>,
                    <?= $ansar_male ?>
                ] 
            },
            { 
                label: 'মহিলা', 
                backgroundColor: '#fd7e14', 
                data: [
                    <?= $officer_female ?>, 
                    <?= $staff_female ?>, 
                    <?= $worker_female ?>,
                    <?= $daily_female ?>,
                    <?= $ansar_female ?>
                ] 
            }
        ]
    },
    options: { 
        responsive: true, 
        scales: { y: { beginAtZero: true } },
        plugins: {
            legend: {
                labels: {
                    font: { size: 14 }
                }
            }
        }
    }
});

// Category Distribution Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: ['অফিসার', 'স্টাফ', 'ওয়ার্কার', 'দৈনিক ভিত্তি', 'আনসার'],
        datasets: [{
            data: [
                <?= $officer_male + $officer_female ?>,
                <?= $staff_male + $staff_female ?>,
                <?= $worker_male + $worker_female ?>,
                <?= $daily_male + $daily_female ?>,
                <?= $ansar_male + $ansar_female ?>
            ],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#17a2b8',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 14 }
                }
            }
        }
    }
});
<?php endif; ?>
</script>
</body>
</html>