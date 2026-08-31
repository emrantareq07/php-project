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

// ---------- SHOW LATEST DATE ----------
// echo "<h4>সর্বশেষ হালনাগাদ মাস: $monthBn $yearBn</h4>";

// ---------- SHOW WHICH TABLES HAVE DATA AND WHICH DO NOT ----------
// foreach($tables as $t){
//     $sqlCheck = "SELECT COUNT(*) AS cnt FROM $t WHERE factory_name=? AND date LIKE CONCAT(?, '%')";
//     $stmt = $conn->prepare($sqlCheck);
//     $stmt->bind_param("ss",$username,$yearMonth);
//     $stmt->execute();
//     $res = $stmt->get_result()->fetch_assoc();
//     $count = $res['cnt'] ?? 0;

//     if($count > 0){
//         echo "<p>Table <strong>$t</strong> has data for this month.</p>";
//     } else {
//         echo "<p>Table <strong>$t</strong> has <strong>no data</strong> for this month.</p>";
//     }
// }
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
    <a href="vacant_statistics.php"><i class="fa fa-shield-alt me-2"></i>Vacant Statistcs </a>
    <a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    <a href="#" id="toggleSidebar"><i class="fa fa-bars me-2"></i>Collapse</a>
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

           <!-- All Records List with Edit/Delete/Clone Options -->
    <div class="records-card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <span><i class="fas fa-database me-2"></i> শূণ্যপদের পরিসংখ্যান</span>
            <span class="badge bg-light text-dark">মোট: <?php echo count($all_records); ?> টি রেকর্ড</span>
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
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($all_records) > 0): ?>
                        <?php foreach ($all_records as $record): 
                            $rec_granted = array_sum(csvToArray($record['granted_post']));
                            $rec_service = array_sum(csvToArray($record['in_service']));
                            $rec_promo = array_sum(csvToArray($record['eligible_promotion']));
                            $rec_direct = array_sum(csvToArray($record['direct_recruit']));
                            $rec_vacant = $rec_promo + $rec_direct;
                            $entry_date_display = !empty($record['entry_date']) ? date('d-m-Y', strtotime($record['entry_date'])) : date('d-m-Y', strtotime($record['created_at']));
                            $entry_month_year = !empty($record['entry_date']) ? date('F Y', strtotime($record['entry_date'])) : date('F Y', strtotime($record['created_at']));
                            $is_current_month_record = ($entry_month_year == $current_month_display);
                        ?>
                        <tr>
                            <td><?php echo $record['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($record['factory_name']); ?></strong>
                                <?php if ($is_current_month_record): ?>
                                    <span class="badge bg-success ms-1">বর্তমান মাস</span>
                                <?php endif; ?>
                             </td>
                            <td><?php echo $entry_date_display; ?></td>
                            <td><?php echo $entry_month_year; ?></td>
                            <td><?php echo enToBn($rec_granted); ?></td>
                            <td><?php echo enToBn($rec_service); ?></td>
                            <td class="text-danger fw-bold"><?php echo enToBn($rec_vacant); ?></td>
                            <td>
                                <a href="?edit_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-warning btn-action" title="সম্পাদনা">
                                    <i class="fas fa-edit"></i> এডিট
                                </a>
                                <a href="?clone_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-clone btn-action" title="ক্লোন করুন (শুধু ডাটা কপি হবে, তারিখ আজকের হবে)" onclick="return confirm('এই রেকর্ডটি ক্লোন করতে চান? পুরাতন সব ডাটা কপি হবে কিন্তু এন্ট্রির তারিখ হবে আজকের (<?php echo $current_date; ?>)')">
                                    <i class="fas fa-copy"></i> ক্লোন
                                </a>
                                <a href="?delete_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger btn-action" title="মুছুন" onclick="return confirm('আপনি কি নিশ্চিত? এই রেকর্ড স্থায়ীভাবে মুছে যাবে!')">
                                    <i class="fas fa-trash"></i> ডিলিট
                                </a>
                              </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                কোনো রেকর্ড পাওয়া যায়নি। উপরের ফর্ম ব্যবহার করে নতুন রেকর্ড তৈরি করুন।
                             </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
             </table>
        </div>
    </div>

        <!-- Charts -->
        <div class="row g-4 mb-4">
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
