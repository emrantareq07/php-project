<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];

// Table names
$officer_tbl = 'officers_tbl';
$staff_tbl   = 'staff_tbl';
$worker_tbl  = 'workers_tbl';

// Bangla number conversion
function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    return str_replace($englishNumbers, $banglaNumbers, $number);
}

// ---------- HELPER FUNCTIONS ----------

// Total row count
function getTotalRows($conn, $table) {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'] ?? 0;
}

// Sum of all male/female columns for each table
function getGenderSum($conn, $table, $prefixList) {
    $sumMale = 0;
    $sumFemale = 0;
    foreach ($prefixList as $g) {
        $sql = "SELECT 
            COALESCE(SUM($g"."_m),0) AS male,
            COALESCE(SUM($g"."_f),0) AS female
            FROM $table";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $sumMale += $row['male'];
        $sumFemale += $row['female'];
    }
    return ['male' => $sumMale, 'female' => $sumFemale];
}

// ---------- FETCH DATA ----------

// Officers: G2–G10
$officerGrades = ['g2','g3','g4','g5','g6','g7','g8','g9','g10'];
$officerGender = getGenderSum($conn, $officer_tbl, $officerGrades);
$total_officers = getTotalRows($conn, $officer_tbl);

// Staff: G11–G20
$staffGrades = ['g11','g12','g13','g14','g15','g16','g17','g18','g19','g20'];
$staffGender = getGenderSum($conn, $staff_tbl, $staffGrades);
$total_staff = getTotalRows($conn, $staff_tbl);

// Workers: G1–G16
$workerGrades = ['g1','g2','g3','g4','g5','g6','g7','g8','g9','g10','g11','g12','g13','g14','g15','g16'];
$workerGender = getGenderSum($conn, $worker_tbl, $workerGrades);
$total_workers = getTotalRows($conn, $worker_tbl);

// Total records (not people — number of entries)
$total_all = $total_officers + $total_staff + $total_workers;

// Convert to Bangla for display
$total_all_b      = englishToBanglaNumber($total_all);
$total_officers_b = englishToBanglaNumber($total_officers);
$total_staff_b    = englishToBanglaNumber($total_staff);
$total_workers_b  = englishToBanglaNumber($total_workers);

$officer_male   = $officerGender['male'];
$officer_female = $officerGender['female'];
$staff_male     = $staffGender['male'];
$staff_female   = $staffGender['female'];
$worker_male    = $workerGender['male'];
$worker_female  = $workerGender['female'];
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

<style>
body { font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif; background: #f8f9fa; }
.sidebar { height: 100vh; width: 220px; position: fixed; background: #007bff; color: #fff; padding-top: 1rem; transition: .3s; }
.sidebar.collapsed { width: 60px; }
.sidebar a { color: #fff; text-decoration: none; display: block; padding: .75rem 1rem; border-radius: 5px; margin-bottom: .25rem; }
.sidebar a:hover, .sidebar a.active { background: #0056b3; }
.sidebar i { width: 25px; }
.main-content { margin-left: 220px; padding: 2rem; transition: .3s; }
.main-content.collapsed { margin-left: 60px; }
.card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<div class="sidebar d-flex flex-column" id="sidebar">
    <div class="text-center mb-4">
        <img src="../assets/logo.png" alt="Logo" width="50" class="mb-2">
        <h6 class="fs-6">BCIC</h6>
        <small>Man Power Management</small>
    </div>

    <a href="#" class="active"><i class="fa fa-home me-2"></i>Dashboard</a>
    <a href="officers_info.php"><i class="fa fa-user-tie me-2"></i>Officers Info.</a>
    <a href="staffs_info.php"><i class="fa fa-users me-2"></i>Staff Info.</a>
    <a href="workers_info.php"><i class="fa fa-hard-hat me-2"></i>Worker Info.</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    <a href="#" id="toggleSidebar"><i class="fa fa-bars me-2"></i>Collapse</a>
</div>

<div class="main-content" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>স্বাগতম, <?php echo htmlspecialchars($username); ?>!</h4>
            <span class="text-muted">ড্যাশবোর্ড সারসংক্ষেপ</span>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-3 text-center text-white bg-primary">
                    <i class="fa fa-users fs-2"></i>
                    <h5 class="mt-2">মোট</h5>
                    <p class="fs-4"><?php echo $total_all_b; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center text-white bg-success">
                    <i class="fa fa-user-tie fs-2"></i>
                    <h5 class="mt-2">অফিসার</h5>
                    <p class="fs-4"><?php echo $total_officers_b; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center text-white bg-warning">
                    <i class="fa fa-user fs-2"></i>
                    <h5 class="mt-2">স্টাফ</h5>
                    <p class="fs-4"><?php echo $total_staff_b; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center text-white bg-danger">
                    <i class="fa fa-hard-hat fs-2"></i>
                    <h5 class="mt-2">ওয়ার্কার</h5>
                    <p class="fs-4"><?php echo $total_workers_b; ?></p>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <canvas id="genderChart"></canvas>
        </div>
    </div>
</div>

<script>
$('#toggleSidebar').click(() => {
    $('#sidebar').toggleClass('collapsed');
    $('#mainContent').toggleClass('collapsed');
});

const ctx = document.getElementById('genderChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Officer', 'Staff', 'Worker'],
        datasets: [
            { label: 'Male', backgroundColor: '#0d6efd', data: [<?= $officer_male ?>, <?= $staff_male ?>, <?= $worker_male ?>] },
            { label: 'Female', backgroundColor: '#fd7e14', data: [<?= $officer_female ?>, <?= $staff_female ?>, <?= $worker_female ?>] }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
</body>
</html>
