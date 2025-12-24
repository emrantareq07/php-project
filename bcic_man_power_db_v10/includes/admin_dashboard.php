<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// NOTE: You previously had hard-coded admin session assignment here.
// If you want real login behavior, remove the next two lines in production.
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
    $englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];
    $banglaNumbers = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    // ensure string
    return str_replace($englishNumbers, $banglaNumbers, (string)$number);
}

// ---------- HELPER FUNCTIONS ----------

// Parse CSV column of numbers and sum them (safe)
function sumCsvColumn($csv) {
    if ($csv === null || $csv === '') return 0;
    $parts = array_map('trim', explode(',', $csv));
    $sum = 0;
    foreach ($parts as $p) {
        // handle possible non-numeric chars - extract numbers
        $p = preg_replace('/\D+/', '', $p);
        if ($p === '') continue;
        $sum += intval($p);
    }
    return $sum;
}

// Sum male/female from tables where male/female columns store CSV numeric strings
// If $factory_name provided, restrict by factory_name
// If $yearMonth provided (format 'YYYY-MM'), restrict by date LIKE 'YYYY-MM%'
function getCsvTableGenderSum($conn, $table, $factory_name = null, $yearMonth = null) {
    $sumMale = 0;
    $sumFemale = 0;

    // Build SQL with optional filters
    $sql = "SELECT male, female FROM `$table`";
    $where = [];
    $params = [];
    $types = '';

    if ($factory_name) {
        $where[] = "factory_name = ?";
        $params[] = $factory_name;
        $types .= 's';
    }
    if ($yearMonth) {
        $where[] = "`date` LIKE ?";
        $params[] = $yearMonth . '%';
        $types .= 's';
    }
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    // Prepared statement if we have params, otherwise simple query
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // fallback to query
            $result = $conn->query($sql);
        } else {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        $result = $conn->query($sql);
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $sumMale += sumCsvColumn($row['male'] ?? '');
            $sumFemale += sumCsvColumn($row['female'] ?? '');
        }
    }

    return ['male' => $sumMale, 'female' => $sumFemale];
}

// Total people from CSV-style table (male+female)
function getCsvTotalPeopleCount($conn, $table, $factory_name = null, $yearMonth = null) {
    $g = getCsvTableGenderSum($conn, $table, $factory_name, $yearMonth);
    return ($g['male'] + $g['female']);
}

// Count rows (entries) with optional factory and yearMonth filters
function getTotalRows($conn, $table, $factory_name = null, $yearMonth = null) {
    $sql = "SELECT COUNT(*) AS total FROM `$table`";
    $where = [];
    $params = [];
    $types = '';

    if ($factory_name) {
        $where[] = "factory_name = ?";
        $params[] = $factory_name;
        $types .= 's';
    }
    if ($yearMonth) {
        $where[] = "`date` LIKE ?";
        $params[] = $yearMonth . '%';
        $types .= 's';
    }
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $res = $conn->query($sql);
            $row = $res ? $res->fetch_assoc() : null;
        } else {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res ? $res->fetch_assoc() : null;
        }
    } else {
        $res = $conn->query($sql);
        $row = $res ? $res->fetch_assoc() : null;
    }

    return $row['total'] ?? 0;
}

// Officers numeric columns summation (for CSV columns) - CORRECTED VERSION
function getOfficerGenderSum($conn, $table, $prefixList, $factory_name = null, $yearMonth = null) {
    $sumMale = 0;
    $sumFemale = 0;

    // Build SQL to fetch all records
    $sql = "SELECT * FROM `$table`";
    $where = [];
    $params = [];
    $types = '';

    if ($factory_name) {
        $where[] = "factory_name = ?";
        $params[] = $factory_name;
        $types .= 's';
    }
    if ($yearMonth) {
        $where[] = "`date` LIKE ?";
        $params[] = $yearMonth . '%';
        $types .= 's';
    }
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    // Execute query
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $result = $conn->query($sql);
        } else {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        $result = $conn->query($sql);
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            foreach ($prefixList as $prefix) {
                $maleKey = $prefix . '_m';
                $femaleKey = $prefix . '_f';
                
                // Sum male values from CSV
                if (isset($row[$maleKey]) && !empty(trim($row[$maleKey]))) {
                    $maleValues = explode(',', $row[$maleKey]);
                    $sumMale += array_sum(array_map('intval', $maleValues));
                }
                
                // Sum female values from CSV
                if (isset($row[$femaleKey]) && !empty(trim($row[$femaleKey]))) {
                    $femaleValues = explode(',', $row[$femaleKey]);
                    $sumFemale += array_sum(array_map('intval', $femaleValues));
                }
            }
        }
    }

    return ['male' => $sumMale, 'female' => $sumFemale];
}

// get distinct factories across all candidate tables
function getAllFactories($conn) {
    $factories = [];
    $tables = ['officers_tbl', 'staffs_tbl', 'workers_tbl', 'daily_basis_tbl', 'ansar_tbl'];
    foreach ($tables as $table) {
        $sql = "SELECT DISTINCT factory_name FROM `$table` WHERE factory_name IS NOT NULL AND factory_name != '' AND factory_name != 'admin'";
        $res = $conn->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $factory_name = $row['factory_name'];
                if (!in_array($factory_name, $factories)) {
                    $factories[] = $factory_name;
                }
            }
        }
    }
    sort($factories);
    return $factories;
}

// Get factory-wise data for admin — use CSV parsers for CSV-storing tables
function getFactoryData($conn, $factory_name, $yearMonth = null) {
    $officerGrades = ['g2','g3','g4','g5','g6','g7','g8','g9','g10'];

    $data = [
        'factory_name' => $factory_name,
        // officers: numeric columns (support month filtering)
        'officer' => getOfficerGenderSum($conn, 'officers_tbl', $officerGrades, $factory_name, $yearMonth),
        // staff/worker/daily/ansar: CSV-style male/female columns
        'staff' => getCsvTableGenderSum($conn, 'staffs_tbl', $factory_name, $yearMonth),
        'worker' => getCsvTableGenderSum($conn, 'workers_tbl', $factory_name, $yearMonth),
        'daily_basis' => getCsvTableGenderSum($conn, 'daily_basis_tbl', $factory_name, $yearMonth),
        'ansar' => getCsvTableGenderSum($conn, 'ansar_tbl', $factory_name, $yearMonth)
    ];

    // Calculate totals
    $data['total_male'] = $data['officer']['male'] + $data['staff']['male'] + $data['worker']['male'] +
                          $data['daily_basis']['male'] + $data['ansar']['male'];
    $data['total_female'] = $data['officer']['female'] + $data['staff']['female'] + $data['worker']['female'] +
                            $data['daily_basis']['female'] + $data['ansar']['female'];
    $data['total_people'] = $data['total_male'] + $data['total_female'];

    return $data;
}

// ---------- GET LAST UPDATED MONTH ----------
// For admin: across all factories & tables
// For factory: only for that factory

$tables = [$officer_tbl, $staff_tbl, $worker_tbl, $daily_basis_tbl, $ansar_tbl];

$lastDate = null;

foreach($tables as $t){
    // Build SQL depending on admin or single factory
    if ($is_admin) {
        $sql = "SELECT `date` FROM `$t` WHERE `date` IS NOT NULL AND `date` != '' ORDER BY `date` DESC LIMIT 1";
        $res = $conn->query($sql);
        $row = $res ? $res->fetch_assoc() : null;
    } else {
        $sql = "SELECT `date` FROM `$t` WHERE factory_name = ? AND `date` IS NOT NULL AND `date` != '' ORDER BY `date` DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
    }

    if($row && !empty($row['date'])){
        // normalize date string
        $d = $row['date'];
        // ensure Y-m-d parseable
        $ts = strtotime($d);
        if ($ts !== false) {
            $formatted = date('Y-m-d', $ts);
        } else {
            // fallback if only YYYY-MM present
            $formatted = $d;
        }
        if(!$lastDate || $formatted > $lastDate){
            $lastDate = $formatted;
        }
    }
}

// if no data found, use today
if(!$lastDate){
    $lastDate = date('Y-m-d');
}

// convert to year-month
$m = date('m', strtotime($lastDate));
$y = date('Y', strtotime($lastDate));
$yearMonth = "$y-$m"; // for LIKE filter

// Bangla month/year
$monthNamesBn = [
    '01'=>'জানুয়ারি','02'=>'ফেব্রুয়ারি','03'=>'মার্চ','04'=>'এপ্রিল','05'=>'মে','06'=>'জুন',
    '07'=>'জুলাই','08'=>'আগস্ট','09'=>'সেপ্টেম্বর','10'=>'অক্টোবর','11'=>'নভেম্বর','12'=>'ডিসেম্বর'
];

$monthBn = $monthNamesBn[$m] ?? $m;
$yearBn  = englishToBanglaNumber($y);

// ---------- FETCH DATA ----------

$officerGrades = ['g2','g3','g4','g5','g6','g7','g8','g9','g10'];

if ($is_admin) {
    // ADMIN VIEW: All factories data (for last month)
    $all_factories = getAllFactories($conn);
    $factory_data = [];
    $grand_totals = ['male' => 0, 'female' => 0, 'total' => 0];

    // DEBUG: Show what we're getting
    echo "<!-- DEBUG: Last month = $yearMonth -->";
    echo "<!-- DEBUG: Found " . count($all_factories) . " factories -->";
    
    foreach ($all_factories as $factory) {
        echo "<!-- DEBUG: Processing factory: $factory -->";
        $data = getFactoryData($conn, $factory, $yearMonth);
        
        // DEBUG: Show raw data
        echo "<!-- DEBUG for $factory: officer_male=" . $data['officer']['male'] . 
             ", officer_female=" . $data['officer']['female'] . 
             ", total_people=" . $data['total_people'] . " -->";
        
        $factory_data[$factory] = $data;

        $grand_totals['male'] += $data['total_male'];
        $grand_totals['female'] += $data['total_female'];
        $grand_totals['total'] += $data['total_people'];
    }

    // Overall totals for admin (for last month)
    $total_people = $grand_totals['total'];
    $total_people_male = $grand_totals['male'];
    $total_people_female = $grand_totals['female'];

    // For admin, also provide totals per-category aggregated across factories (for charts)
    $admin_officer = ['male'=>0,'female'=>0];
    $admin_staff   = ['male'=>0,'female'=>0];
    $admin_worker  = ['male'=>0,'female'=>0];
    $admin_daily   = ['male'=>0,'female'=>0];
    $admin_ansar   = ['male'=>0,'female'=>0];
    foreach ($factory_data as $f => $d) {
        $admin_officer['male'] += $d['officer']['male'];
        $admin_officer['female'] += $d['officer']['female'];
        $admin_staff['male'] += $d['staff']['male'];
        $admin_staff['female'] += $d['staff']['female'];
        $admin_worker['male'] += $d['worker']['male'];
        $admin_worker['female'] += $d['worker']['female'];
        $admin_daily['male'] += $d['daily_basis']['male'];
        $admin_daily['female'] += $d['daily_basis']['female'];
        $admin_ansar['male'] += $d['ansar']['male'];
        $admin_ansar['female'] += $d['ansar']['female'];
    }

} else {
    // FACTORY VIEW: Single factory data for last month
    // officers numeric
    $officerGender = getOfficerGenderSum($conn, $officer_tbl, $officerGrades, $username, $yearMonth);
    // staff/worker/daily/ansar CSV-style
    $staffGender = getCsvTableGenderSum($conn, $staff_tbl, $username, $yearMonth);
    $workerGender = getCsvTableGenderSum($conn, $worker_tbl, $username, $yearMonth);
    $dailyGender = getCsvTableGenderSum($conn, $daily_basis_tbl, $username, $yearMonth);
    $ansarGender = getCsvTableGenderSum($conn, $ansar_tbl, $username, $yearMonth);

    $total_officers = getTotalRows($conn, $officer_tbl, $username, $yearMonth);
    $total_staff = getTotalRows($conn, $staff_tbl, $username, $yearMonth);
    $total_workers = getTotalRows($conn, $worker_tbl, $username, $yearMonth);
    $total_daily = getTotalRows($conn, $daily_basis_tbl, $username, $yearMonth);
    $total_ansar = getTotalRows($conn, $ansar_tbl, $username, $yearMonth);

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

// Convert to Bangla (ensure variables exist)
$total_people_b          = englishToBanglaNumber($total_people ?? 0);
$total_people_male_b     = englishToBanglaNumber($total_people_male ?? 0);
$total_people_female_b   = englishToBanglaNumber($total_people_female ?? 0);

if (!$is_admin) {
    // Factory-specific Bangla numbers (entries)
    $total_officers_b = englishToBanglaNumber($total_officers ?? 0);
    $total_staff_b    = englishToBanglaNumber($total_staff ?? 0);
    $total_workers_b  = englishToBanglaNumber($total_workers ?? 0);
    $total_daily_b    = englishToBanglaNumber($total_daily ?? 0);
    $total_ansar_b    = englishToBanglaNumber($total_ansar ?? 0);
}

// ---------- END DATA PREP ----------
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
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
<!-- Fonts -->
<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->

<style>
.akaya-kanadaka-regular {
  font-family: "Akaya Kanadaka", system-ui;
  font-weight: 400;
  font-style: normal;
}

@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
               
/*body { font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif; background: #f8f9fa; }*/
body{font-family:'Noto Sans Bengali',sans-serif;background:#f8f9fa;}
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

* {
  font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
}
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
    <?php  
    if($_SESSION['role'] == 'sadmin' && $_SESSION['username'] == 'sadmin') {
    ?>
    <a href="user_manage.php"><i class="fa fa-shield-alt me-2"></i> User Manage</a>
    <?php 
    }
    ?>
    <a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    <a href="#" id="toggleSidebar"><i class="fa fa-bars me-2"></i>Collapse</a>
</div>

<div class="main-content" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>স্বাগতম, <?php echo htmlspecialchars($username); ?>!</h4>
            <span class="akaya-kanadaka-regular bg-primary px-3 py-1 rounded text-white d-inline-block">
                <i class="fa fa-copyright"></i><?php echo date("Y");?>  BCIC. [--Design & Developed by ICT Division, BCIC.--]
            </span>
            <span class="text-muted">
                <?php echo $is_admin ? 'সমস্ত কারখানা ড্যাশবোর্ড' : 'ড্যাশবোর্ড সারসংক্ষেপ'; ?>
            </span>
        </div>

        <?php if ($is_admin): ?>
        <!-- ADMIN VIEW: All Factories -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3"><i class="fas fa-industry me-2"></i>সমস্ত কারখানা সারসংক্ষেপ</h5>

            <!-- Grand Total Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card p-3 text-center text-white bg-primary">
                        <i class="fa fa-users fs-2"></i>
                        <h5 class="mt-2">মোট কর্মী (<?= htmlspecialchars($monthBn) ?> <?= htmlspecialchars($yearBn) ?>)</h5>
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
                <h5 class="mb-3">ক্যাটাগরি অনুযায়ী সারসংক্ষেপ (<?= $monthBn ?> <?= $yearBn ?>)</h5>
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
                            <td><strong><?php echo htmlspecialchars($factory); ?></strong></td>
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
                    <h5 class="mb-3">কারখানা অনুযায়ী মোট কর্মী (<?= htmlspecialchars($monthBn) ?> <?= htmlspecialchars($yearBn) ?>)</h5>
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
                    <h5 class="mt-2">মোট কর্মী (<?= htmlspecialchars($monthBn) ?> <?= htmlspecialchars($yearBn) ?>)</h5>
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
        labels: <?php echo json_encode(array_keys($factory_data)); ?>,
        datasets: [{
            label: 'মোট কর্মী',
            // Chart.js accepts arrays or single color; using single color is fine
            backgroundColor: '#28a745',
            data: <?php 
                $vals = array_map(function($d){ return $d['total_people']; }, $factory_data);
                echo json_encode(array_values($vals));
            ?>
        }]
    },
    options: { 
        responsive: true, 
        scales: { y: { beginAtZero: true } },
        plugins: {
            legend: {
                display: false
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
            data: [<?php echo intval($total_people_male) . ',' . intval($total_people_female); ?>],
            backgroundColor: ['#0d6efd', '#fd7e14']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
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
                    <?= intval($officer_male) ?>, 
                    <?= intval($staff_male) ?>, 
                    <?= intval($worker_male) ?>,
                    <?= intval($daily_male) ?>,
                    <?= intval($ansar_male) ?>
                ] 
            },
            { 
                label: 'মহিলা', 
                backgroundColor: '#fd7e14', 
                data: [
                    <?= intval($officer_female) ?>, 
                    <?= intval($staff_female) ?>, 
                    <?= intval($worker_female) ?>,
                    <?= intval($daily_female) ?>,
                    <?= intval($ansar_female) ?>
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
                <?= intval($officer_male + $officer_female) ?>,
                <?= intval($staff_male + $staff_female) ?>,
                <?= intval($worker_male + $worker_female) ?>,
                <?= intval($daily_male + $daily_female) ?>,
                <?= intval($ansar_male + $ansar_female) ?>
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
