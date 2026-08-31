<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';
$factory_name = $_SESSION['factory_name'];

// Table names
$officer_tbl     = 'officers_tbl';
$staff_tbl       = 'staffs_tbl';
$worker_tbl      = 'workers_tbl';
$daily_basis_tbl = 'daily_basis_tbl';
$ansar_tbl       = 'ansar_tbl';
$vacant_statistics_tbl = 'vacant_statistics_tbl';

// Bangla number conversion
function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return str_replace($englishNumbers, $banglaNumbers, (string)$number);
}

// Helper functions for vacant statistics
function csvToArray($csv) {
    if (empty($csv)) return [];
    return array_map('intval', explode(',', $csv));
}

// ---------- HELPER FUNCTIONS ----------

// Parse CSV column and sum values
function sumCsvColumn($csv){
    if(!$csv || trim($csv) === '') return 0;
    $arr = array_map('trim', explode(',', $csv));
    return array_sum(array_map('intval', $arr));
}

// Sum CSV male/female columns for standard tables
function getCsvTableGenderSum($conn, $table, $username, $yearMonth){
    $sql = "SELECT male, female FROM $table WHERE factory_name=? AND date LIKE CONCAT(?, '%')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $yearMonth);
    $stmt->execute();
    $result = $stmt->get_result();

    $sumMale = $sumFemale = 0;
    while($row = $result->fetch_assoc()){
        $sumMale   += sumCsvColumn($row['male']);
        $sumFemale += sumCsvColumn($row['female']);
    }
    
    $stmt->close();
    return ['male'=>$sumMale, 'female'=>$sumFemale];
}

// Officer gender sum (for CSV columns) - CORRECTED
function getOfficerGenderSum($conn, $table, $grades, $username, $yearMonth){
    $sumMale = $sumFemale = 0;
    
    // Get all officer records for this month
    $sql = "SELECT * FROM $table WHERE factory_name=? AND date LIKE CONCAT(?, '%')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $yearMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()){
        foreach($grades as $grade){
            // Sum male values
            if(isset($row[$grade . '_m']) && !empty(trim($row[$grade . '_m']))){
                $maleValues = explode(',', $row[$grade . '_m']);
                $sumMale += array_sum(array_map('intval', $maleValues));
            }
            
            // Sum female values
            if(isset($row[$grade . '_f']) && !empty(trim($row[$grade . '_f']))){
                $femaleValues = explode(',', $row[$grade . '_f']);
                $sumFemale += array_sum(array_map('intval', $femaleValues));
            }
        }
    }
    
    $stmt->close();
    return ['male'=>$sumMale, 'female'=>$sumFemale];
}

// Count rows
function getTotalRows($conn, $table, $username, $yearMonth){
    $sql = "SELECT COUNT(*) AS total FROM $table WHERE factory_name=? AND date LIKE CONCAT(?, '%')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $yearMonth);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row['total'] ?? 0;
}

// Total people count for CSV tables
function getTotalPeopleCount($conn, $table, $username, $yearMonth){
    $gender = getCsvTableGenderSum($conn, $table, $username, $yearMonth);
    return $gender['male'] + $gender['female'];
}

// ---------- GET LAST UPDATED MONTH ----------
$tables = [$officer_tbl, $staff_tbl, $worker_tbl, $daily_basis_tbl, $ansar_tbl];

$lastDate = null;

foreach($tables as $t){
    $sql = "SELECT date FROM $t WHERE factory_name=? ORDER BY date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if($res && $res['date']){
        if(!$lastDate || $res['date'] > $lastDate){
            $lastDate = $res['date'];
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

$monthBn = $monthNamesBn[$m];
$yearBn  = englishToBanglaNumber($y);

// ---------- FETCH DATA FOR LAST MONTH ----------
$officerGrades=['g2','g3','g4','g5','g6','g7','g8','g9','g10'];
$officerGender = getOfficerGenderSum($conn,$officer_tbl,$officerGrades,$username,$yearMonth);
$total_officers = getTotalRows($conn,$officer_tbl,$username,$yearMonth);
$total_officers_people = $officerGender['male']+$officerGender['female'];

$staffGender = getCsvTableGenderSum($conn,$staff_tbl,$username,$yearMonth);
$total_staff_entries = getTotalRows($conn,$staff_tbl,$username,$yearMonth);
$total_staff_people = getTotalPeopleCount($conn,$staff_tbl,$username,$yearMonth);

$workerGender = getCsvTableGenderSum($conn,$worker_tbl,$username,$yearMonth);
$total_workers_entries = getTotalRows($conn,$worker_tbl,$username,$yearMonth);
$total_workers_people = getTotalPeopleCount($conn,$worker_tbl,$username,$yearMonth);

$dailyGender = getCsvTableGenderSum($conn,$daily_basis_tbl,$username,$yearMonth);
$total_daily_entries = getTotalRows($conn,$daily_basis_tbl,$username,$yearMonth);
$total_daily_people = getTotalPeopleCount($conn,$daily_basis_tbl,$username,$yearMonth);

$ansarGender = getCsvTableGenderSum($conn,$ansar_tbl,$username,$yearMonth);
$total_ansar_entries = getTotalRows($conn,$ansar_tbl,$username,$yearMonth);
$total_ansar_people = getTotalPeopleCount($conn,$ansar_tbl,$username,$yearMonth);

$total_entries = $total_officers + $total_staff_entries + $total_workers_entries + $total_daily_entries + $total_ansar_entries;
$total_people_male   = $officerGender['male']+$staffGender['male']+$workerGender['male']+$dailyGender['male']+$ansarGender['male'];
$total_people_female = $officerGender['female']+$staffGender['female']+$workerGender['female']+$dailyGender['female']+$ansarGender['female'];
$total_people        = $total_people_male+$total_people_female;

// ---------- FETCH VACANT STATISTICS DATA ----------
$vacant_records = [];
$vacant_sql = "SELECT * FROM `$vacant_statistics_tbl` WHERE `factory_name` = '$username' ORDER BY entry_date DESC, id DESC";
$vacant_result = $conn->query($vacant_sql);
if ($vacant_result && $vacant_result->num_rows > 0) {
    while ($row = $vacant_result->fetch_assoc()) {
        $vacant_records[] = $row;
    }
}

// Calculate summary for vacant statistics
$total_granted_all = 0;
$total_service_all = 0;
$total_vacant_all = 0;
$latest_vacant_record = null;

if (count($vacant_records) > 0) {
    $latest_vacant_record = $vacant_records[0]; // Most recent record
    
    foreach ($vacant_records as $record) {
        $granted_sum = array_sum(csvToArray($record['granted_post']));
        $service_sum = array_sum(csvToArray($record['in_service']));
        $promo_sum = array_sum(csvToArray($record['eligible_promotion']));
        $direct_sum = array_sum(csvToArray($record['direct_recruit']));
        
        $total_granted_all += $granted_sum;
        $total_service_all += $service_sum;
        $total_vacant_all += ($promo_sum + $direct_sum);
    }
}

// Get current month display for latest record
$current_month_display = date('F Y');
$latest_entry_month = $latest_vacant_record ? date('F Y', strtotime($latest_vacant_record['entry_date'])) : $current_month_display;
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
<style>
body{font-family:'Noto Sans Bengali',sans-serif;background:#f8f9fa;}
.sidebar{height:100vh;width:220px;position:fixed;background:#007bff;color:#fff;padding-top:1rem;transition:.3s;}
.sidebar.collapsed{width:60px;}
.sidebar a{color:#fff;text-decoration:none;display:block;padding:.75rem 1rem;border-radius:5px;margin-bottom:.25rem;}
.sidebar a:hover,.sidebar a.active{background:#0056b3;}
.sidebar i{width:25px;}
.main-content{margin-left:220px;padding:2rem;transition:.3s;}
.main-content.collapsed{margin-left:60px;}
.card{border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
.people-count{font-size:1.1rem;font-weight:600;}
.entry-count{font-size:0.85rem;color:#6c757d;margin-top:5px;}
.records-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-top: 30px;
}
.records-card .card-header {
    background: #2c5a7a;
    color: white;
    padding: 15px 20px;
    font-weight: 600;
}
.btn-action {
    padding: 4px 12px;
    margin: 0 3px;
    border-radius: 20px;
    font-size: 0.85rem;
}
.btn-clone {
    background: #17a2b8;
    color: white;
}
.btn-clone:hover {
    background: #138496;
    color: white;
}
.vacant-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.summary-stats {
    display: inline-block;
    padding: 10px 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    margin: 5px;
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
    <a href="officers_info.php"><i class="fa fa-user-tie me-2"></i>Officers Info.</a>
    <a href="staffs_info.php"><i class="fa fa-users me-2"></i>Staff Info.</a>
    <a href="workers_info_1.php"><i class="fa fa-hard-hat me-2"></i>Worker Info.</a>
    <a href="daily_basis_info.php"><i class="fa fa-calendar-day me-2"></i>Daily Basis Info.</a>
    <a href="ansar_info.php"><i class="fa fa-shield-alt me-2"></i>Ansar Info.</a>
    <a href="vacant_statistics1.php"><i class="fa fa-chart-line me-2"></i>Vacant Statistics</a>
    <a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    <!-- <a href="#" id="toggleSidebar"><i class="fa fa-bars me-2"></i>Collapse</a> -->
</div>

<div class="main-content" id="mainContent">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>স্বাগতম, <?= htmlspecialchars($username) ?>!</h4>
            <span class="akaya-kanadaka-regular bg-primary px-3 py-1 rounded text-white d-inline-block">
                <i class="fa fa-copyright"></i><?php echo date("Y");?>  BCIC. [--Design & Developed by ICT Division, BCIC.--]
            </span>
            <span class="text-muted">ড্যাশবোর্ড সারসংক্ষেপ</span>
        </div>

        <!-- Vacant Statistics Summary Card -->
        <?php if (count($vacant_records) > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card vacant-summary-card p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-2"><i class="fas fa-chart-line me-2"></i>শূন্য পদ পরিসংখ্যান (সর্বশেষ: <?= $latest_entry_month ?>)</h5>
                            <div>
                                <span class="summary-stats"><i class="fas fa-tasks me-1"></i>মোট অনুমোদিত: <?= englishToBanglaNumber($total_granted_all) ?></span>
                                <span class="summary-stats"><i class="fas fa-user-friends me-1"></i>মোট কর্মরত: <?= englishToBanglaNumber($total_service_all) ?></span>
                                <span class="summary-stats"><i class="fas fa-chart-line me-1"></i>মোট শূন্য পদ: <?= englishToBanglaNumber($total_vacant_all) ?></span>
                                <span class="summary-stats"><i class="fas fa-calendar me-1"></i>মোট রেকর্ড: <?= englishToBanglaNumber(count($vacant_records)) ?></span>
                            </div>
                        </div>
                        <a href="vacant_statistics.php" class="btn btn-light btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-1"></i>বিস্তারিত দেখুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Cards -->
        <div class="row g-4 mb-4">
            <?php 
            $cards = [
                ['title'=>'মোট কর্মী','count'=>$total_people,'male'=>$total_people_male,'female'=>$total_people_female,'entries'=>$total_entries,'bg'=>'primary','icon'=>'fa-users'],
                ['title'=>'অফিসার','count'=>$total_officers_people,'male'=>$officerGender['male'],'female'=>$officerGender['female'],'entries'=>$total_officers,'bg'=>'success','icon'=>'fa-user-tie'],
                ['title'=>'স্টাফ','count'=>$total_staff_people,'male'=>$staffGender['male'],'female'=>$staffGender['female'],'entries'=>$total_staff_entries,'bg'=>'warning','icon'=>'fa-user'],
                ['title'=>'ওয়ার্কার','count'=>$total_workers_people,'male'=>$workerGender['male'],'female'=>$workerGender['female'],'entries'=>$total_workers_entries,'bg'=>'danger','icon'=>'fa-hard-hat'],
                ['title'=>'দৈনিক ভিত্তি','count'=>$total_daily_people,'male'=>$dailyGender['male'],'female'=>$dailyGender['female'],'entries'=>$total_daily_entries,'bg'=>'info','icon'=>'fa-calendar-day'],
                ['title'=>'আনসার','count'=>$total_ansar_people,'male'=>$ansarGender['male'],'female'=>$ansarGender['female'],'entries'=>$total_ansar_entries,'bg'=>'secondary','icon'=>'fa-shield-alt']
            ];
            foreach($cards as $c){ ?>
            <div class="col-md-4">
                <div class="card p-3 text-center text-white bg-<?= $c['bg'] ?>">
                    <i class="fa <?= $c['icon'] ?> fs-2"></i>
                    <h5 class="mt-2"><?= $c['title'] ?></h5>
                    <p class="fs-4"><?= englishToBanglaNumber($c['count']) ?></p>
                    <div class="people-count">
                        <span class="badge bg-info">পুরুষ: <?= englishToBanglaNumber($c['male']) ?></span>
                        <span class="badge bg-warning mt-1">মহিলা: <?= englishToBanglaNumber($c['female']) ?></span>
                    </div>
                    <div class="entry-count">
                        এন্ট্রি: <?= englishToBanglaNumber($c['entries']) ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Summary Table -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3">ক্যাটাগরি অনুযায়ী সারসংক্ষেপ (<?= $monthBn ?> <?= $yearBn ?>)</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>ক্যাটাগরি</th>
                            <th>এন্ট্রি সংখ্যা</th>
                            <th>পুরুষ</th>
                            <th>মহিলা</th>
                            <th>মোট মানুষ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cards as $c){ ?>
                        <tr>
                            <td><?= $c['title'] ?></td>
                            <td><?= englishToBanglaNumber($c['entries']) ?></td>
                            <td><?= englishToBanglaNumber($c['male']) ?></td>
                            <td><?= englishToBanglaNumber($c['female']) ?></td>
                            <td><?= englishToBanglaNumber($c['count']) ?></td>
                        </tr>
                        <?php } ?>
                        <tr class="table-secondary fw-bold">
                            <td>মোট</td>
                            <td><?= englishToBanglaNumber($total_entries) ?></td>
                            <td><?= englishToBanglaNumber($total_people_male) ?></td>
                            <td><?= englishToBanglaNumber($total_people_female) ?></td>
                            <td><?= englishToBanglaNumber($total_people) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vacant Statistics Records Table -->
        <div class="records-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <span><i class="fas fa-chart-line me-2"></i>শূন্য পদ পরিসংখ্যানের রেকর্ডসমূহ</span>
                <span class="badge bg-light text-dark">মোট: <?= englishToBanglaNumber(count($vacant_records)) ?> টি রেকর্ড</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #e9ecef;">
                        <tr>
                            <th>ID</th>
                            <th>কারখানার নাম</th>
                            <th>এন্ট্রির তারিখ</th>
                            <th>মাস/বছর</th>
                            <th>মোট অনুমোদিত</th>
                            <th>মোট কর্মরত</th>
                            <th>মোট শূন্য</th>
                            <th>পদোন্নতিযোগ্য</th>
                            <th>সরাসরি নিয়োগ</th>
                            <!-- <th>অ্যাকশন</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($vacant_records) > 0): ?>
                            <?php foreach ($vacant_records as $record): 
                                $granted_array = csvToArray($record['granted_post']);
                                $service_array = csvToArray($record['in_service']);
                                $promo_array = csvToArray($record['eligible_promotion']);
                                $direct_array = csvToArray($record['direct_recruit']);
                                
                                $rec_granted = array_sum($granted_array);
                                $rec_service = array_sum($service_array);
                                $rec_promo = array_sum($promo_array);
                                $rec_direct = array_sum($direct_array);
                                $rec_vacant = $rec_promo + $rec_direct;
                                
                                $entry_date_display = !empty($record['entry_date']) ? date('d-m-Y', strtotime($record['entry_date'])) : date('d-m-Y', strtotime($record['created_at']));
                                $entry_month_year = !empty($record['entry_date']) ? date('F Y', strtotime($record['entry_date'])) : date('F Y', strtotime($record['created_at']));
                                $is_current_month_record = ($entry_month_year == $current_month_display);
                            ?>
                            <tr>
                                <td><?= $record['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($record['factory_name']) ?></strong>
                                    <?php if ($is_current_month_record): ?>
                                        <span class="badge bg-success ms-1">বর্তমান মাস</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $entry_date_display ?></td>
                                <td><?= $entry_month_year ?></td>
                                <td><?= englishToBanglaNumber($rec_granted) ?></td>
                                <td><?= englishToBanglaNumber($rec_service) ?></td>
                                <td class="text-danger fw-bold"><?= englishToBanglaNumber($rec_vacant) ?></td>
                                <td><?= englishToBanglaNumber($rec_promo) ?></td>
                                <td><?= englishToBanglaNumber($rec_direct) ?></td>
                              <!--   <td>
                                    <a href="vacant_statistics.php?edit_id=<?= $record['id'] ?>" class="btn btn-sm btn-warning btn-action" title="সম্পাদনা">
                                        <i class="fas fa-edit"></i> এডিট
                                    </a>
                                    <a href="vacant_statistics.php?clone_id=<?= $record['id'] ?>" class="btn btn-sm btn-clone btn-action" title="ক্লোন করুন" onclick="return confirm('এই রেকর্ডটি ক্লোন করতে চান?')">
                                        <i class="fas fa-copy"></i> ক্লোন
                                    </a>
                                    <a href="vacant_statistics.php?delete_id=<?= $record['id'] ?>" class="btn btn-sm btn-danger btn-action" title="মুছুন" onclick="return confirm('আপনি কি নিশ্চিত? এই রেকর্ড স্থায়ীভাবে মুছে যাবে!')">
                                        <i class="fas fa-trash"></i> ডিলিট
                                    </a>
                                </td> -->
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                    কোনো শূন্য পদ রেকর্ড পাওয়া যায়নি। 
                                    <a href="vacant_statistics.php" class="btn btn-sm btn-primary mt-2">নতুন রেকর্ড তৈরি করুন</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts -->
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">লিঙ্গভিত্তিক কর্মী বণ্টন</h5>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="mb-3">ক্যাটাগরি অনুযায়ী কর্মী বণ্টন</h5>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#toggleSidebar').click(()=>{$('#sidebar').toggleClass('collapsed');$('#mainContent').toggleClass('collapsed');});

// Gender Chart
new Chart(document.getElementById('genderChart').getContext('2d'),{
    type:'bar',
    data:{
        labels:['অফিসার','স্টাফ','ওয়ার্কার','দৈনিক ভিত্তি','আনসার'],
        datasets:[
            {label:'পুরুষ', backgroundColor:'#0d6efd', data:[
                <?= $officerGender['male'] ?>, <?= $staffGender['male'] ?>, <?= $workerGender['male'] ?>, <?= $dailyGender['male'] ?>, <?= $ansarGender['male'] ?>
            ]},
            {label:'মহিলা', backgroundColor:'#fd7e14', data:[
                <?= $officerGender['female'] ?>, <?= $staffGender['female'] ?>, <?= $workerGender['female'] ?>, <?= $dailyGender['female'] ?>, <?= $ansarGender['female'] ?>
            ]}
        ]
    },
    options:{responsive:true, scales:{y:{beginAtZero:true}},plugins:{legend:{labels:{font:{size:14}}}}}
});

// Category Chart
new Chart(document.getElementById('categoryChart').getContext('2d'),{
    type:'pie',
    data:{
        labels:['অফিসার','স্টাফ','ওয়ার্কার','দৈনিক ভিত্তি','আনসার'],
        datasets:[{
            data:[
                <?= $total_officers_people ?>,
                <?= $total_staff_people ?>,
                <?= $total_workers_people ?>,
                <?= $total_daily_people ?>,
                <?= $total_ansar_people ?>
            ],
            backgroundColor:['#28a745','#ffc107','#dc3545','#17a2b8','#6c757d']
        }]
    },
    options:{responsive:true,plugins:{legend:{position:'bottom',labels:{font:{size:14}}}}}
});
</script>

</body>
</html>