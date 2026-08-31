<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// User-defined variables
$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';
$factory_name = $username;
$vacant_statistics_tbl = 'vacant_statistics_tbl';
$is_admin = ($role === 'admin');

// 1. Structure Definition
$structure = [
    'প্রথম শ্রেণী' => ['grades' => range(1, 9), 'grade_range' => '১-৯'],
    'দ্বিতীয় শ্রেণী' => ['grades' => [10], 'grade_range' => '১০'],
    'তৃতীয় শ্রেণী' => ['grades' => range(11, 16), 'grade_range' => '১১-১৬'],
    'চতুর্থ শ্রেণী' => ['grades' => range(17, 20), 'grade_range' => '১৭-২০'],
    'শ্রমিক' => ['grades' => range(1, 16), 'grade_range' => '১-১৬']
];

// Helper functions
function enToBn($number) {
    $en = array('0','1','2','3','4','5','6','7','8','9');
    $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($en, $bn, (string)$number);
}

function arrayToCsv($array) {
    return implode(',', $array);
}

function csvToArray($csv) {
    if (empty($csv)) return [];
    return array_map('intval', explode(',', $csv));
}

// Function to check if a record is from last/recent month
function isRecentMonthRecord($entry_date) {
    $current_month = date('Y-m');
    $record_month = date('Y-m', strtotime($entry_date));
    return $record_month == $current_month;
}

// Calculate total grades count
$total_grades = 0;
foreach ($structure as $classData) {
    $total_grades += count($classData['grades']);
}

// Get current month and year
$current_date = date('Y-m-d');
$current_month = date('Y-m');
$current_month_display = date('F Y');

// ============ CHECK FOR DUPLICATE IN SAME MONTH BASED ON ENTRY_DATE ============
function checkDuplicateMonth($conn, $table, $factory_name, $month, $exclude_id = null) {
    $start_date = $month . '-01';
    $end_date = date('Y-m-t', strtotime($month . '-01'));
    $sql = "SELECT id FROM `$table` WHERE `factory_name` = '$factory_name' 
            AND `entry_date` BETWEEN '$start_date' AND '$end_date'";
    if ($exclude_id) {
        $sql .= " AND `id` != $exclude_id";
    }
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

// ============ CRUD OPERATIONS ============

// DELETE record with permission check
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Check if user has permission to delete
    if (!$is_admin) {
        // Get the record to check its date
        $check_sql = "SELECT entry_date FROM `$vacant_statistics_tbl` WHERE `id` = $delete_id";
        $check_result = $conn->query($check_sql);
        if ($check_result && $check_result->num_rows > 0) {
            $record = $check_result->fetch_assoc();
            if (!isRecentMonthRecord($record['entry_date'])) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=not_authorized");
                exit;
            }
        }
    }
    
    $delete_sql = "DELETE FROM `$vacant_statistics_tbl` WHERE `id` = $delete_id";
    if ($conn->query($delete_sql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deleted");
        exit;
    }
}

// CLONE record - Always allowed for all users
if (isset($_GET['clone_id'])) {
    $clone_id = (int)$_GET['clone_id'];
    $clone_sql = "SELECT * FROM `$vacant_statistics_tbl` WHERE `id` = $clone_id LIMIT 1";
    $clone_result = $conn->query($clone_sql);
    if ($clone_result && $clone_result->num_rows > 0) {
        $clone_record = $clone_result->fetch_assoc();
        
        // Check if there's already a record for current month based on entry_date
        $start_date = $current_month . '-01';
        $end_date = date('Y-m-t', strtotime($current_month . '-01'));
        $check_sql = "SELECT id FROM `$vacant_statistics_tbl` 
                      WHERE `factory_name` = '{$clone_record['factory_name']}' 
                      AND `entry_date` BETWEEN '$start_date' AND '$end_date'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result && $check_result->num_rows > 0) {
            $existing = $check_result->fetch_assoc();
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=duplicate_clone&existing_id=" . $existing['id']);
            exit;
        }
        
        // Insert cloned record with current date
        $insert_sql = "INSERT INTO `$vacant_statistics_tbl` 
                       (`factory_name`, `entry_date`, `granted_post`, `in_service`, `eligible_promotion`, `direct_recruit`, `created_at`, `updated_at`) 
                       VALUES (
                           '{$clone_record['factory_name']}',
                           '$current_date',
                           '{$clone_record['granted_post']}',
                           '{$clone_record['in_service']}',
                           '{$clone_record['eligible_promotion']}',
                           '{$clone_record['direct_recruit']}',
                           NOW(),
                           NOW()
                       )";
        if ($conn->query($insert_sql)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=cloned");
            exit;
        }
    }
}

// EDIT - Load record for editing with permission check
$edit_record = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    
    // Check if user has permission to edit
    if (!$is_admin) {
        $check_sql = "SELECT entry_date FROM `$vacant_statistics_tbl` WHERE `id` = $edit_id LIMIT 1";
        $check_result = $conn->query($check_sql);
        if ($check_result && $check_result->num_rows > 0) {
            $record = $check_result->fetch_assoc();
            if (!isRecentMonthRecord($record['entry_date'])) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=edit_not_authorized");
                exit;
            }
        }
    }
    
    $edit_sql = "SELECT * FROM `$vacant_statistics_tbl` WHERE `id` = $edit_id LIMIT 1";
    $edit_result = $conn->query($edit_sql);
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_record = $edit_result->fetch_assoc();
    }
}

// ADD / UPDATE - Save data
$duplicate_error = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {
    $factory = mysqli_real_escape_string($conn, $_POST['factory_name']);
    $entry_date = mysqli_real_escape_string($conn, $_POST['entry_date']);
    
    // Collect all data into arrays
    $granted_posts = [];
    $in_services = [];
    $eligible_promotions = [];
    $direct_recruits = [];
    
    foreach ($_POST['grade_no'] as $index => $grade) {
        $granted_posts[] = (int)$_POST['granted_post'][$index];
        $in_services[] = (int)$_POST['in_service'][$index];
        $eligible_promotions[] = (int)$_POST['eligible_promotion'][$index];
        $direct_recruits[] = (int)$_POST['direct_recruit'][$index];
    }
    
    // Convert to CSV
    $granted_str = arrayToCsv($granted_posts);
    $service_str = arrayToCsv($in_services);
    $promo_str = arrayToCsv($eligible_promotions);
    $recruit_str = arrayToCsv($direct_recruits);
    
    if (isset($_POST['record_id']) && !empty($_POST['record_id'])) {
        // UPDATE existing record - Check permission for non-admin
        $record_id = (int)$_POST['record_id'];
        
        if (!$is_admin) {
            $check_sql = "SELECT entry_date FROM `$vacant_statistics_tbl` WHERE `id` = $record_id LIMIT 1";
            $check_result = $conn->query($check_sql);
            if ($check_result && $check_result->num_rows > 0) {
                $record = $check_result->fetch_assoc();
                if (!isRecentMonthRecord($record['entry_date'])) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=edit_not_authorized");
                    exit;
                }
            }
        }
        
        $sql = "UPDATE `$vacant_statistics_tbl` SET 
                `factory_name` = '$factory',
                `entry_date` = '$entry_date',
                `granted_post` = '$granted_str',
                `in_service` = '$service_str',
                `eligible_promotion` = '$promo_str',
                `direct_recruit` = '$recruit_str',
                `updated_at` = NOW()
                WHERE `id` = $record_id";
        if ($conn->query($sql)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=updated");
            exit;
        }
    } else {
        // Get the month from entry_date for duplicate check
        $entry_month = date('Y-m', strtotime($entry_date));
        
        // INSERT new record - check duplicate for the entry_date's month
        if (checkDuplicateMonth($conn, $vacant_statistics_tbl, $factory, $entry_month)) {
            $duplicate_error = true;
            $error_msg = "দুঃখিত! এই মাসে (".date('F Y', strtotime($entry_date)).") ইতিমধ্যে একটি রেকর্ড বিদ্যমান। আপনি ডুপ্লিকেট এন্ট্রি করতে পারবেন না।";
        } else {
            $sql = "INSERT INTO `$vacant_statistics_tbl` 
                    (`factory_name`, `entry_date`, `granted_post`, `in_service`, `eligible_promotion`, `direct_recruit`, `created_at`) 
                    VALUES ('$factory', '$entry_date', '$granted_str', '$service_str', '$promo_str', '$recruit_str', NOW())";
            if ($conn->query($sql)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=added");
                exit;
            }
        }
    }
}

// Fetch ALL records for listing
$all_records = [];
$res = $conn->query("SELECT * FROM `$vacant_statistics_tbl` WHERE factory_name='$username' ORDER BY entry_date DESC, id DESC");
while ($row = $res->fetch_assoc()) {
    $all_records[] = $row;
}

// For the form - ALWAYS BLANK ON PAGE LOAD (only show when editing)
$current_data = null;
$is_edit_mode = false;

if ($edit_record) {
    $current_data = $edit_record;
    $is_edit_mode = true;
}

// Parse data for form display (only if in edit mode)
$granted_array = array_fill(0, $total_grades, 0);
$service_array = array_fill(0, $total_grades, 0);
$promo_array = array_fill(0, $total_grades, 0);
$recruit_array = array_fill(0, $total_grades, 0);
$form_entry_date = $current_date;
$display_month_year = $current_month_display;

if ($current_data) {
    $granted_array = csvToArray($current_data['granted_post']);
    $service_array = csvToArray($current_data['in_service']);
    $promo_array = csvToArray($current_data['eligible_promotion']);
    $recruit_array = csvToArray($current_data['direct_recruit']);
    $form_entry_date = $current_data['entry_date'];
    if (!empty($current_data['entry_date'])) {
        $display_month_year = date('F Y', strtotime($current_data['entry_date']));
    }
}

// Pad arrays
while (count($granted_array) < $total_grades) $granted_array[] = 0;
while (count($service_array) < $total_grades) $service_array[] = 0;
while (count($promo_array) < $total_grades) $promo_array[] = 0;
while (count($recruit_array) < $total_grades) $recruit_array[] = 0;

// Calculate summary for current record
$summary = [
    'total_granted' => array_sum($granted_array),
    'total_in_service' => array_sum($service_array),
    'total_promotion' => array_sum($promo_array),
    'total_direct' => array_sum($recruit_array),
    'total_vacancy' => array_sum($promo_array) + array_sum($recruit_array)
];

// Get message
$msg = '';
$msg_type = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'added') { $msg = 'নতুন রেকর্ড সফলভাবে যোগ করা হয়েছে!'; $msg_type = 'success'; }
    if ($_GET['msg'] == 'updated') { $msg = 'রেকর্ড সফলভাবে আপডেট করা হয়েছে!'; $msg_type = 'success'; }
    if ($_GET['msg'] == 'deleted') { $msg = 'রেকর্ড সফলভাবে মুছে ফেলা হয়েছে!'; $msg_type = 'success'; }
    if ($_GET['msg'] == 'cloned') { $msg = 'রেকর্ড সফলভাবে ক্লোন করা হয়েছে! বর্তমান তারিখ ব্যবহার করা হয়েছে।'; $msg_type = 'success'; }
    if ($_GET['msg'] == 'duplicate_clone') { 
        $existing_id = isset($_GET['existing_id']) ? $_GET['existing_id'] : '';
        $msg = "ক্লোন করা সম্ভব নয়! এই মাসে (" . $current_month_display . ") ইতিমধ্যে একটি রেকর্ড আছে। আপনি বিদ্যমান রেকর্ডটি এডিট করতে পারেন।";
        $msg_type = 'warning'; 
    }
    if ($_GET['msg'] == 'edit_not_authorized') { 
        $msg = 'দুঃখিত! আপনি শুধুমাত্র বর্তমান মাসের রেকর্ড এডিট করতে পারবেন। পুরাতন রেকর্ড এডিট করার অনুমতি আপনার নেই।'; 
        $msg_type = 'danger'; 
    }
    if ($_GET['msg'] == 'not_authorized') { 
        $msg = 'দুঃখিত! আপনি শুধুমাত্র বর্তমান মাসের রেকর্ড ডিলিট করতে পারবেন। পুরাতন রেকর্ড ডিলিট করার অনুমতি আপনার নেই।'; 
        $msg_type = 'danger'; 
    }
}
if (isset($error_msg)) { $msg = $error_msg; $msg_type = 'danger'; }
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>গ্রেড ভিত্তিক শুণ্য জনবলের পরিসংখ্যান  </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family:'Noto Sans Bengali',sans-serif;background:#f8f9fa;
        }
        .header-banner {
            background: linear-gradient(135deg, #0b2b3b, #1a4a6e);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stats-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .stat-box {
            text-align: center;
            padding: 12px;
            border-radius: 15px;
            background: #f8f9fa;
            transition: transform 0.2s;
        }
        .stat-box:hover { transform: translateY(-3px); }
        .stat-box i { font-size: 2rem; margin-bottom: 8px; }
        .stat-box h3 { margin: 0; font-size: 1.8rem; font-weight: bold; }
        .main-card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.12);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .main-card .card-header {
            background: linear-gradient(135deg, #1e4663, #2a5a7a);
            color: white;
            padding: 18px 25px;
            font-size: 1.2rem;
            font-weight: 600;
            border: none;
        }
        .table-container {
            padding: 0;
            overflow-x: auto;
            max-height: 550px;
            overflow-y: auto;
        }
        .table {
            margin-bottom: 0;
            font-size: 0.85rem;
            min-width: 900px;
        }
        .table thead th {
            background: #1e4663;
            color: white;
            font-weight: 600;
            padding: 14px 10px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table tbody td {
            padding: 12px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .class-cell {
            background: #f0f5fb !important;
            font-weight: 700;
            font-size: 1rem;
            color: #1e4663;
            vertical-align: middle;
            text-align: center;
            border-right: 2px solid #d0dce8 !important;
        }
        .grade-badge {
            background: #e9ecef;
            color: #1e4663;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 25px;
            display: inline-block;
            font-size: 0.8rem;
            border: 1px solid #cbd5e1;
        }
        input[type=number] {
            width: 95px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 7px 5px;
            font-size: 0.85rem;
        }
        input[type=number]:focus {
            border-color: #1e4663;
            outline: none;
            box-shadow: 0 0 0 3px rgba(30,70,99,0.2);
        }
        .vacant-box {
            font-weight: 700;
            color: #dc3545;
            background: #fff5f5;
            border-radius: 10px;
            padding: 5px 10px;
            display: inline-block;
            min-width: 60px;
        }
        .btn-save {
            background: linear-gradient(135deg, #2a9d8f, #21867a);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 50px;
        }
        .btn-save:hover {
            background: linear-gradient(135deg, #21867a, #1a6b61);
            transform: scale(1.02);
        }
        .records-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .records-card .card-header {
            background: #2c5a7a;
            color: white;
            padding: 15px 20px;
        }
        .btn-action {
            padding: 4px 12px;
            margin: 0 3px;
            border-radius: 20px;
        }
        .btn-clone {
            background: #17a2b8;
            color: white;
        }
        .btn-clone:hover {
            background: #138496;
            color: white;
        }
        .btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        footer {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            color: #4a627a;
        }
        .month-info {
            background: #e9ecef;
            border-radius: 10px;
            padding: 10px 15px;
        }
        .old-record {
            background-color: #fff3cd !important;
        }
        @media (max-width: 768px) {
            input[type=number] { width: 60px; font-size: 0.7rem; }
            .table { font-size: 0.7rem; }
            .stat-box h3 { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

<div class="header-banner">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h3 class="mb-0"><i class="fas fa-chalkboard-user me-2"></i>গ্রেড ভিত্তিক শুণ্য জনবলের পরিসংখ্যান</h3>
                <p class="mb-0 mt-1 opacity-75"><i class="fas fa-building me-1"></i>কারখানা: <?php echo htmlspecialchars($factory_name); ?></p>
            </div>
            <div>
                <?php 
                if($role==='admin'){                
                ?>
                <a href="vacant_statistics_details.php" class="btn btn-sm btn-outline-light"><i class="fas fa-arrow-left"></i> Back</a>
                <?php 
                }
                else{
                ?>
                <a href="dashboard.php" class="btn btn-sm btn-outline-light"><i class="fas fa-arrow-left"></i> Back</a>
                <?php
                    }
                ?>             

                <span class="badge bg-light text-dark p-2 me-2">
                    <i class="fas fa-user-check"></i> <?php echo htmlspecialchars($username); ?> (<?php echo htmlspecialchars($role); ?>)
                </span>
                <a href="../logout.php" class="btn btn-sm btn-outline-light"><i class="fas fa-sign-out-alt"></i> প্রস্থান</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4 mb-5">
    <!-- Alert Message -->
    <?php if ($msg): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-<?php echo $msg_type == 'success' ? 'check-circle' : ($msg_type == 'warning' ? 'exclamation-triangle' : 'times-circle'); ?> me-2"></i> <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Month Information -->
    <div class="month-info text-center mb-3">
        <i class="fas fa-calendar-alt text-primary me-2"></i>
        <strong>বর্তমান মাস: <?php echo $current_month_display; ?></strong>
        <span class="text-muted ms-2">(প্রতি মাসে শুধুমাত্র একটি রেকর্ড তৈরি করা যাবে - entry_date অনুযায়ী)</span>
        <span class="text-info ms-2"><i class="fas fa-copy"></i> ক্লোন করলে পুরাতন ডাটা কপি হবে কিন্তু এন্ট্রির তারিখ হবে আজকের</span>
        <?php if (!$is_admin): ?>
        <span class="text-warning ms-2"><i class="fas fa-exclamation-triangle"></i> আপনি শুধুমাত্র বর্তমান মাসের রেকর্ড এডিট/ডিলিট করতে পারবেন</span>
        <?php endif; ?>
    </div>

    <!-- Edit Mode Indicator -->
    <?php if ($is_edit_mode): ?>
    <div class="alert alert-warning text-center mb-3">
        <i class="fas fa-edit me-2"></i>
        <strong>এডিট মোড:</strong> আপনি রেকর্ড #<?php echo $edit_record['id']; ?> সম্পাদনা করছেন - "<?php echo htmlspecialchars($edit_record['factory_name']); ?>"
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-sm btn-secondary ms-3">নতুন রেকর্ড তৈরি করুন</a>
    </div>
    <?php endif; ?>

    <!-- Statistics Cards (only show if in edit mode or has data) -->
    <?php if ($is_edit_mode || array_sum($granted_array) > 0): ?>
    <div class="stats-container">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <i class="fas fa-tasks text-success"></i>
                    <h3><?php echo enToBn($summary['total_granted']); ?></h3>
                    <small class="text-muted">মোট অনুমোদিত পদ</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <i class="fas fa-user-friends text-info"></i>
                    <h3><?php echo enToBn($summary['total_in_service']); ?></h3>
                    <small class="text-muted">মোট কর্মরত</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <i class="fas fa-chart-line text-danger"></i>
                    <h3><?php echo enToBn($summary['total_vacancy']); ?></h3>
                    <small class="text-muted">মোট শূন্য পদ</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <i class="fas fa-arrow-up text-warning"></i>
                    <h3><?php echo enToBn($summary['total_promotion']); ?> + <?php echo enToBn($summary['total_direct']); ?></h3>
                    <small class="text-muted">পদোন্নতি + সরাসরি</small>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Entry Form -->
    <div class="main-card">
        <div class="card-header">
            <i class="fas fa-<?php echo $is_edit_mode ? 'edit' : 'plus-circle'; ?> me-2"></i>
            <?php echo $is_edit_mode ? 'রেকর্ড সম্পাদনা করুন' : 'নতুন রেকর্ড তৈরি করুন'; ?>
            <small class="float-end"><i class="fas fa-info-circle"></i> মোট শূন্য পদ = পদোন্নতিযোগ্য + সরাসরি নিয়োগযোগ্য</small>
        </div>
        <div class="table-container">
            <form method="POST" id="mainForm">
                <div class="row p-3 bg-light">
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-calendar-day"></i> এন্ট্রির তারিখ:</label>
                        <input type="date" name="entry_date" class="form-control" value="<?php echo $form_entry_date; ?>" required>
                        <small class="text-muted">এই তারিখের মাস অনুযায়ী ডুপ্লিকেট চেক করা হবে</small>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-building"></i> কারখানার নাম / ইউনিট:</label>
                        <input type="text" name="factory_name" class="form-control" value="<?php echo $is_edit_mode ? htmlspecialchars($edit_record['factory_name']) : htmlspecialchars($factory_name); ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-calendar-alt"></i> মাস:</label>
                        <input type="text" class="form-control" value="<?php echo $is_edit_mode ? $display_month_year : $current_month_display; ?>" disabled>
                        <small class="text-muted">
                            <?php if ($is_edit_mode): ?>
                                এন্ট্রির তারিখ অনুযায়ী মাস
                            <?php else: ?>
                                প্রতি মাসে একটি রেকর্ড
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-clock"></i> অবস্থা:</label>
                        <input type="text" class="form-control" value="<?php echo $is_edit_mode ? 'এডিট মোড' : 'নতুন রেকর্ড'; ?>" disabled>
                    </div>
                </div>
                
                <?php if ($is_edit_mode): ?>
                    <input type="hidden" name="record_id" value="<?php echo $edit_record['id']; ?>">
                <?php endif; ?>
                
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 12%">শ্রেণী</th>
                            <th style="width: 10%">গ্রেড</th>
                            <th style="width: 15%">অনুমোদিত পদ</th>
                            <th style="width: 15%">কর্মরত পদ</th>
                            <th style="width: 18%">পদোন্নতিযোগ্য শূন্য পদ</th>
                            <th style="width: 18%">সরাসরি নিয়োগযোগ্য শূন্য পদ</th>
                            <th style="width: 12%">মোট শূন্য পদ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $global_idx = 0;
                        foreach ($structure as $className => $classData): 
                            $grades = $classData['grades'];
                            $span = count($grades);
                            foreach ($grades as $i => $gNum): 
                        ?>
                        <tr>
                            <?php if($i === 0): ?>
                                <td rowspan="<?php echo $span; ?>" class="class-cell">
                                    <i class="fas fa-layer-group me-1"></i> <?php echo $className; ?>
                                    <br><small class="text-muted">(গ্রেড <?php echo $classData['grade_range']; ?>)</small>
                                 </td>
                            <?php endif; ?>
                            
                            <td>
                                <span class="grade-badge">
                                    <i class="fas fa-star-of-life me-1"></i> গ্রেড <?php echo enToBn($gNum); ?>
                                </span>
                                <input type="hidden" name="grade_no[]" value="<?php echo $gNum; ?>">
                             </td>
                            
                            <td><input type="number" name="granted_post[]" class="form-control granted-input" value="<?php echo $granted_array[$global_idx] ?? 0; ?>" min="0"></td>
                            <td><input type="number" name="in_service[]" class="form-control service-input" value="<?php echo $service_array[$global_idx] ?? 0; ?>" min="0"></td>
                            <td><input type="number" name="eligible_promotion[]" class="form-control calc" value="<?php echo $promo_array[$global_idx] ?? 0; ?>" min="0"></td>
                            <td><input type="number" name="direct_recruit[]" class="form-control calc" value="<?php echo $recruit_array[$global_idx] ?? 0; ?>" min="0"></td>
                            <td class="vacant-cell">
                                <span class="vacant-box"><?php echo enToBn(($promo_array[$global_idx] ?? 0) + ($recruit_array[$global_idx] ?? 0)); ?></span>
                              </td>
                        </tr>
                        <?php 
                            $global_idx++;
                            endforeach; 
                        endforeach; ?>
                    </tbody>
                </table>
               
        </div>
         <div class="text-center p-4" style="background: #f8f9fa;">
                    <button type="submit" name="save_data" class="btn btn-save text-white">
                        <i class="fas fa-save me-2"></i> <?php echo $is_edit_mode ? 'আপডেট করুন' : 'সংরক্ষণ করুন'; ?>
                    </button>
                    <?php if ($is_edit_mode): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary ms-3">
                            <i class="fas fa-plus me-2"></i> নতুন রেকর্ড
                        </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary ms-3" onclick="resetForm()">
                        <i class="fas fa-undo me-2"></i> রিসেট
                    </button>
                </div>
            </form>
    </div>

<!-- All Records List with Edit/Delete/Clone Options -->
<div class="records-card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <span><i class="fas fa-database me-2"></i> সমস্ত সংরক্ষিত রেকর্ডের তালিকা</span>
        <span class="badge bg-light text-dark">মোট: <?php echo count($all_records); ?> টি রেকর্ড</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background: #e9ecef;">
                <tr>
                    <th >ID</th>
                    <th >কারখানার নাম</th>
                    <th >এন্ট্রির তারিখ</th>
                    <th >মাস/বছর</th>
                    <th >মোট অনুমোদিত</th>
                    <th >মোট কর্মরত</th>
                    <th >মোট শূন্য</th>
                    <th >অ্যাকশন</th>
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
                        $can_edit_delete = $is_admin || $is_current_month_record;
                        $row_class = (!$is_admin && !$is_current_month_record) ? 'old-record' : '';
                    ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td class="text-center"><?php echo $record['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($record['factory_name']); ?></strong>
                            <?php if ($is_current_month_record): ?>
                                <span class="badge bg-success ms-1">বর্তমান মাস</span>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-1">পুরাতন রেকর্ড</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo $entry_date_display; ?></td>
                        <td class="text-center"><?php echo $entry_month_year; ?></td>
                        <td class="text-center"><?php echo enToBn($rec_granted); ?></td>
                        <td class="text-center"><?php echo enToBn($rec_service); ?></td>
                        <td class="text-center text-danger fw-bold"><?php echo enToBn($rec_vacant); ?></td>
                        <td class="text-center">
                            <?php if ($can_edit_delete || $is_admin): ?>
                                <a href="?edit_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-warning btn-action" title="সম্পাদনা">
                                    <i class="fas fa-edit"></i> এডিট
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary btn-action" disabled title="শুধুমাত্র বর্তমান মাসের রেকর্ড এডিট করা যাবে">
                                    <i class="fas fa-edit"></i> এডিট
                                </button>
                            <?php endif; ?>
                            
                            <!-- Clone button - Always available -->
                            <a href="?clone_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-clone btn-action" title="ক্লোন করুন (শুধু ডাটা কপি হবে, তারিখ আজকের হবে)" onclick="return confirm('এই রেকর্ডটি ক্লোন করতে চান? পুরাতন সব ডাটা কপি হবে কিন্তু এন্ট্রির তারিখ হবে আজকের (<?php echo $current_date; ?>)')">
                                <i class="fas fa-copy"></i> ক্লোন
                            </a>
                            
                            <?php if ($can_edit_delete || $is_admin): ?>
                               <!--  <a href="?delete_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger btn-action" title="মুছুন" onclick="return confirm('আপনি কি নিশ্চিত? এই রেকর্ড স্থায়ীভাবে মুছে যাবে!')">
                                    <i class="fas fa-trash"></i> ডিলিট
                                </a> -->

                                
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary btn-action" disabled title="শুধুমাত্র বর্তমান মাসের রেকর্ড ডিলিট করা যাবে">
                                    <i class="fas fa-trash"></i> ডিলিট
                                </button>
                            <?php endif; ?>
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

    <!-- Footer Note -->
    <footer>
        <i class="fas fa-info-circle text-primary me-2"></i>
        <strong>গুরুত্বপূর্ণ তথ্য:</strong> 
        শূন্য পদ = পদোন্নতিযোগ্য পদ + সরাসরি নিয়োগযোগ্য পদ। 
        <strong>ডুপ্লিকেট চেক করা হয় entry_date কলাম অনুযায়ী</strong> - প্রতি মাসে শুধুমাত্র একটি রেকর্ড তৈরি করা যাবে।
        <strong>ক্লোন ফিচার:</strong> যেকোনো পুরাতন রেকর্ডের সব ডাটা কপি হবে কিন্তু <strong class="text-info">এন্ট্রির তারিখ হবে আজকের তারিখ (<?php echo $current_date; ?>)</strong>।
        <strong>এডিট মোডে মাস:</strong> এন্ট্রির তারিখ অনুযায়ী মাস স্বয়ংক্রিয়ভাবে দেখাবে।
        <?php if (!$is_admin): ?>
        <br><strong class="text-warning">নিয়ম:</strong> সাধারণ ব্যবহারকারী শুধুমাত্র বর্তমান মাসের রেকর্ড এডিট ও ডিলিট করতে পারবেন। ক্লোন সব সময় করা যাবে।
        <?php endif; ?>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // English to Bangla number converter
    function enToBnJs(n) {
        var dict = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
        return String(n).replace(/[0-9]/g, function(w){ return dict[w]; });
    }

    // Function to validate a single row
    function validateRow(row) {
        var granted = parseInt(row.find('.granted-input').val()) || 0;
        var service = parseInt(row.find('.service-input').val()) || 0;
        var promo = parseInt(row.find('input[name="eligible_promotion[]"]').val()) || 0;
        var recruit = parseInt(row.find('input[name="direct_recruit[]"]').val()) || 0;

        var vacancy_limit = granted - service;
        var current_input_total = promo + recruit;
        
        var displayBox = row.find('.vacant-box');
        var isValid = true;
        
        // Update the display with Bangla numbers
        displayBox.text(enToBnJs(current_input_total));
        
        // Check if over limit (only when limit is not negative)
        if (vacancy_limit >= 0 && current_input_total > vacancy_limit) {
            // Over limit - Show RED error
            displayBox.css({
                'color': '#dc3545',
                'background': '#ffeef0',
                'border': '1px solid #dc3545',
                'font-weight': 'bold'
            });
            row.find('.calc').css('border-color', '#dc3545');
            isValid = false;
        } else if (vacancy_limit < 0 && current_input_total > 0) {
            // Negative vacancy (more service than granted) - Also error
            displayBox.css({
                'color': '#dc3545',
                'background': '#ffeef0',
                'border': '1px solid #dc3545',
                'font-weight': 'bold'
            });
            row.find('.granted-input, .service-input').css('border-color', '#dc3545');
            isValid = false;
        } else {
            // Within limit or zero - Show GREEN success
            displayBox.css({
                'color': '#198754',
                'background': '#eefdf5',
                'border': '1px solid #198754'
            });
            row.find('.calc, .granted-input, .service-input').css('border-color', '#ced4da');
            isValid = true;
        }
        
        return isValid;
    }
    
    // Function to check all rows and enable/disable save button
    function checkGlobalValidity() {
        var allValid = true;
        
        $('table tbody tr').each(function() {
            var row = $(this);
            var granted = parseInt(row.find('.granted-input').val()) || 0;
            var service = parseInt(row.find('.service-input').val()) || 0;
            var promo = parseInt(row.find('input[name="eligible_promotion[]"]').val()) || 0;
            var recruit = parseInt(row.find('input[name="direct_recruit[]"]').val()) || 0;
            
            var vacancy_limit = granted - service;
            var current_input_total = promo + recruit;
            
            // Check if this row is invalid
            if (vacancy_limit >= 0 && current_input_total > vacancy_limit) {
                allValid = false;
            } else if (vacancy_limit < 0 && current_input_total > 0) {
                allValid = false;
            }
        });
        
        // Enable/disable save button based on validation
        if (allValid) {
            $('.btn-save').prop('disabled', false).removeClass('opacity-50');
        } else {
            $('.btn-save').prop('disabled', true).addClass('opacity-50');
        }
        
        return allValid;
    }
    
    // Trigger validation on any input change
    $(document).on('input', '.granted-input, .service-input, .calc', function() {
        var row = $(this).closest('tr');
        validateRow(row);
        checkGlobalValidity();
    });
    
    // Initial validation on page load
    $('table tbody tr').each(function() {
        validateRow($(this));
    });
    checkGlobalValidity();
    
    // Also trigger validation when any number input loses focus
    $(document).on('blur', '.granted-input, .service-input, .calc', function() {
        var row = $(this).closest('tr');
        validateRow(row);
        checkGlobalValidity();
    });
});

function resetForm() {
    if(confirm('সমস্ত ইনপুট ফিল্ড রিসেট করতে চান? সব মান ০ (শূন্য) হয়ে যাবে।')) {
        // Reset all number inputs to 0
        $('input[type="number"]').not('input[name="record_id"]').val(0);
        
        // Reset all vacant boxes to ০
        $('.vacant-box').each(function() { 
            $(this).text('০');
            $(this).css({
                'color': '#198754',
                'background': '#eefdf5',
                'border': '1px solid #198754'
            });
        });
        
        // Reset border colors
        $('.granted-input, .service-input, .calc').css('border-color', '#ced4da');
        
        // Reset entry date to current date if not in edit mode
        <?php if (!$is_edit_mode): ?>
        $('input[name="entry_date"]').val('<?php echo $current_date; ?>');
        <?php endif; ?>
        
        // Enable save button after reset
        $('.btn-save').prop('disabled', false).removeClass('opacity-50');
    }
}

// Clear form on page load to ensure blank state (unless in edit mode)
<?php if (!$is_edit_mode): ?>
$(document).ready(function() {
    // Ensure all number inputs are zero on fresh page load
    $('input[type="number"]').not('input[name="record_id"]').val(0);
    $('.vacant-box').each(function() { 
        $(this).text('০');
        $(this).css({
            'color': '#198754',
            'background': '#eefdf5',
            'border': '1px solid #198754'
        });
    });
    $('input[name="entry_date"]').val('<?php echo $current_date; ?>');
    
    // Re-run validation after resetting
    $('table tbody tr').each(function() {
        var row = $(this);
        var granted = parseInt(row.find('.granted-input').val()) || 0;
        var service = parseInt(row.find('.service-input').val()) || 0;
        var promo = parseInt(row.find('input[name="eligible_promotion[]"]').val()) || 0;
        var recruit = parseInt(row.find('input[name="direct_recruit[]"]').val()) || 0;
        
        var vacancy_limit = granted - service;
        var current_input_total = promo + recruit;
        
        if (vacancy_limit >= 0 && current_input_total <= vacancy_limit) {
            $('.btn-save').prop('disabled', false).removeClass('opacity-50');
        }
    });
});
<?php endif; ?>
</script>
</body>
</html>