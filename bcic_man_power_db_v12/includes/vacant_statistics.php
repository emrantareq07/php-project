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

// 1. Structure Definition
$structure = [
    'প্রথম শ্রেণী' => ['grades' => range(1, 9), 'grade_range' => '১-৯'],
    'দ্বিতীয় শ্রেণী' => ['grades' => [10], 'grade_range' => '১০'],
    'তৃতীয় শ্রেণী' => ['grades' => range(11, 16), 'grade_range' => '১১-১৬'],
    'চতুর্থ শ্রেণী' => ['grades' => range(17, 20), 'grade_range' => '১৭-২০'],
    'শ্রমিক' => ['grades' => range(1, 20), 'grade_range' => '১-২০']
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

// ============ VALIDATION FUNCTION ============
function validateVacancyMatch($granted, $in_service, $eligible_promotion, $direct_recruit, $grade_name) {
    $calculated_vacant = $granted - $in_service;
    $total_filled = $eligible_promotion + $direct_recruit;
    
    if ($calculated_vacant != $total_filled) {
        return [
            'valid' => false,
            'message' => "{$grade_name}: অনুমোদিত পদ ({$granted}) - কর্মরত ({$in_service}) = শূন্য পদ ({$calculated_vacant}), কিন্তু পদোন্নতিযোগ্য ({$eligible_promotion}) + সরাসরি নিয়োগ ({$direct_recruit}) = {$total_filled}। শূন্য পদ অবশ্যই {$calculated_vacant} হতে হবে!"
        ];
    }
    return ['valid' => true, 'message' => ''];
}

// ============ CRUD OPERATIONS ============

// DELETE record
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_sql = "DELETE FROM `$vacant_statistics_tbl` WHERE `id` = $delete_id";
    if ($conn->query($delete_sql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deleted");
        exit;
    }
}

// CLONE record
if (isset($_GET['clone_id'])) {
    $clone_id = (int)$_GET['clone_id'];
    $clone_sql = "SELECT * FROM `$vacant_statistics_tbl` WHERE `id` = $clone_id LIMIT 1";
    $clone_result = $conn->query($clone_sql);
    if ($clone_result && $clone_result->num_rows > 0) {
        $clone_record = $clone_result->fetch_assoc();
        
        // Check for duplicate in current month
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
        
        // Insert cloned record
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

// EDIT - Load record for editing
$edit_record = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $edit_sql = "SELECT * FROM `$vacant_statistics_tbl` WHERE `id` = $edit_id LIMIT 1";
    $edit_result = $conn->query($edit_sql);
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_record = $edit_result->fetch_assoc();
    }
}

// ADD / UPDATE - Save data with validation
$duplicate_error = false;
$validation_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {
    $factory = mysqli_real_escape_string($conn, $_POST['factory_name']);
    $entry_date = mysqli_real_escape_string($conn, $_POST['entry_date']);
    
    // Collect all data into arrays
    $granted_posts = [];
    $in_services = [];
    $eligible_promotions = [];
    $direct_recruits = [];
    $grade_names = [];
    
    foreach ($_POST['grade_no'] as $index => $grade) {
        $granted_posts[] = (int)$_POST['granted_post'][$index];
        $in_services[] = (int)$_POST['in_service'][$index];
        $eligible_promotions[] = (int)$_POST['eligible_promotion'][$index];
        $direct_recruits[] = (int)$_POST['direct_recruit'][$index];
        $grade_names[] = "গ্রেড " . $grade;
    }
    
    // Validate each row
    $all_valid = true;
    for ($i = 0; $i < count($granted_posts); $i++) {
        $validation = validateVacancyMatch(
            $granted_posts[$i], 
            $in_services[$i], 
            $eligible_promotions[$i], 
            $direct_recruits[$i],
            $grade_names[$i]
        );
        if (!$validation['valid']) {
            $all_valid = false;
            $validation_errors[] = $validation['message'];
        }
    }
    
    if (!$all_valid) {
        $error_msg = implode("<br>", $validation_errors);
        $duplicate_error = true;
    } else {
        // Convert to CSV
        $granted_str = arrayToCsv($granted_posts);
        $service_str = arrayToCsv($in_services);
        $promo_str = arrayToCsv($eligible_promotions);
        $recruit_str = arrayToCsv($direct_recruits);
        
        if (isset($_POST['record_id']) && !empty($_POST['record_id'])) {
            // UPDATE existing record
            $record_id = (int)$_POST['record_id'];
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
            
            // INSERT new record
            if (checkDuplicateMonth($conn, $vacant_statistics_tbl, $factory, $entry_month)) {
                $duplicate_error = true;
                $error_msg = "দুঃখিত! এই মাসে (" . date('F Y', strtotime($entry_date)) . ") ইতিমধ্যে একটি রেকর্ড বিদ্যমান। আপনি ডুপ্লিকেট এন্ট্রি করতে পারবেন না।";
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
}

// Fetch ALL records for listing
$all_records = [];
$res = $conn->query("SELECT * FROM `$vacant_statistics_tbl` ORDER BY entry_date DESC, id DESC");
while ($row = $res->fetch_assoc()) {
    $all_records[] = $row;
}

// For the form
$current_data = null;
$is_edit_mode = false;

if ($edit_record) {
    $current_data = $edit_record;
    $is_edit_mode = true;
}

// Parse data for form display
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

// Calculate summary
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
    if ($_GET['msg'] == 'cloned') { $msg = 'রেকর্ড সফলভাবে ক্লোন করা হয়েছে!'; $msg_type = 'success'; }
    if ($_GET['msg'] == 'duplicate_clone') { 
        $msg = "ক্লোন করা সম্ভব নয়! এই মাসে (" . $current_month_display . ") ইতিমধ্যে একটি রেকর্ড আছে।";
        $msg_type = 'warning'; 
    }
}
if (isset($error_msg)) { $msg = $error_msg; $msg_type = 'danger'; }
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>গ্রেড ভিত্তিক জনবল ব্যবস্থাপনা | CRUD with Validation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
        body { 
            /*background: linear-gradient(135deg, #e8f0f7 0%, #d4e0ec 100%);
            font-family: 'Segoe UI', 'Hind Siliguri', sans-serif;*/
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
        .vacant-box.valid {
            background: #d4edda;
            color: #155724;
        }
        .vacant-box.invalid {
            background: #f8d7da;
            color: #721c24;
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
        .validation-rule {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 8px 15px;
            margin-bottom: 15px;
            border-radius: 8px;
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
                <h3 class="mb-0"><i class="fas fa-chalkboard-user me-2"></i>গ্রেড ভিত্তিক জনবল শূন্যতা ব্যবস্থাপনা</h3>
                <p class="mb-0 mt-1 opacity-75"><i class="fas fa-building me-1"></i>কারখানা: <?php echo htmlspecialchars($factory_name); ?></p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-sm btn-outline-light"><i class="fas fa-arrow-left"></i> Back</a>
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

    <!-- Validation Rule -->
    <div class="validation-rule">
        <i class="fas fa-check-circle text-success me-2"></i>
        <strong>গুরুত্বপূর্ণ নিয়ম:</strong> (অনুমোদিত পদ - কর্মরত) = (পদোন্নতিযোগ্য + সরাসরি নিয়োগ) হতে হবে।
        <br><small class="text-muted">উদাহরণ: অনুমোদিত পদ=১০, কর্মরত=২, তাহলে শূন্য পদ=৮, তাই পদোন্নতিযোগ্য+সরাসরি নিয়োগ = ৮ হতে হবে।</small>
    </div>

    <!-- Month Information -->
    <div class="month-info text-center mb-3">
        <i class="fas fa-calendar-alt text-primary me-2"></i>
        <strong>বর্তমান মাস: <?php echo $current_month_display; ?></strong>
        <span class="text-muted ms-2">(প্রতি মাসে শুধুমাত্র একটি রেকর্ড তৈরি করা যাবে - entry_date অনুযায়ী)</span>
    </div>

    <!-- Edit Mode Indicator -->
    <?php if ($is_edit_mode): ?>
    <div class="alert alert-warning text-center mb-3">
        <i class="fas fa-edit me-2"></i>
        <strong>এডিট মোড:</strong> আপনি রেকর্ড #<?php echo $edit_record['id']; ?> সম্পাদনা করছেন
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-sm btn-secondary ms-3">নতুন রেকর্ড তৈরি করুন</a>
    </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
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
                    <h3><?php echo enToBn($summary['total_granted'] - $summary['total_in_service']); ?></h3>
                    <small class="text-muted">মোট গণনাকৃত শূন্য</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <i class="fas fa-arrow-up text-warning"></i>
                    <h3><?php echo enToBn($summary['total_promotion']); ?> + <?php echo enToBn($summary['total_direct']); ?></h3>
                    <small class="text-muted">মোট প্রদত্ত শূন্য</small>
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
            <small class="float-end"><i class="fas fa-info-circle"></i> শূন্য পদ = অনুমোদিত - কর্মরত = পদোন্নতি + সরাসরি</small>
        </div>
        <div class="table-container">
            <form method="POST" id="mainForm" onsubmit="return validateForm()">
                <div class="row p-3 bg-light">
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-calendar-day"></i> এন্ট্রির তারিখ:</label>
                        <input type="date" name="entry_date" class="form-control" value="<?php echo $form_entry_date; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-building"></i> কারখানার নাম:</label>
                        <input type="text" name="factory_name" class="form-control" value="<?php echo $is_edit_mode ? htmlspecialchars($edit_record['factory_name']) : htmlspecialchars($factory_name); ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold"><i class="fas fa-calendar-alt"></i> মাস:</label>
                        <input type="text" class="form-control" value="<?php echo $is_edit_mode ? $display_month_year : $current_month_display; ?>" disabled>
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
                            <th style="width: 15%">অনুমোদিত পদ<br><small>(A)</small></th>
                            <th style="width: 15%">কর্মরত<br><small>(B)</small></th>
                            <th style="width: 15%">গণনাকৃত শূন্য<br><small>(A-B)</small></th>
                            <th style="width: 15%">পদোন্নতিযোগ্য<br><small>(C)</small></th>
                            <th style="width: 18%">সরাসরি নিয়োগ<br><small>(D)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $global_idx = 0;
                        foreach ($structure as $className => $classData): 
                            $grades = $classData['grades'];
                            $span = count($grades);
                            foreach ($grades as $i => $gNum): 
                                $calculated_vacant = ($granted_array[$global_idx] ?? 0) - ($service_array[$global_idx] ?? 0);
                                $provided_vacant = ($promo_array[$global_idx] ?? 0) + ($recruit_array[$global_idx] ?? 0);
                                $is_match = ($calculated_vacant == $provided_vacant);
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
                            
                            <td><input type="number" name="granted_post[]" class="form-control granted-input" value="<?php echo $granted_array[$global_idx] ?? 0; ?>" min="0" oninput="calculateVacant(this)"></td>
                            <td><input type="number" name="in_service[]" class="form-control service-input" value="<?php echo $service_array[$global_idx] ?? 0; ?>" min="0" oninput="calculateVacant(this)"></td>
                            <td class="calculated-vacant" id="calculated_<?php echo $global_idx; ?>">
                                <span class="badge bg-secondary"><?php echo enToBn($calculated_vacant); ?></span>
                            </td>
                            <td><input type="number" name="eligible_promotion[]" class="form-control promo-input" value="<?php echo $promo_array[$global_idx] ?? 0; ?>" min="0" oninput="validateRow(this)"></td>
                            <td><input type="number" name="direct_recruit[]" class="form-control recruit-input" value="<?php echo $recruit_array[$global_idx] ?? 0; ?>" min="0" oninput="validateRow(this)"></td>
                        </tr>
                        <?php 
                            $global_idx++;
                            endforeach; 
                        endforeach; ?>
                    </tbody>
                </table>
                <div class="text-center p-4" style="background: #f8f9fa;">
                    <button type="submit" name="save_data" class="btn btn-save text-white" id="submitBtn">
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
    </div>

    <!-- All Records List -->
    <div class="records-card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <span><i class="fas fa-database me-2"></i> সমস্ত সংরক্ষিত রেকর্ডের তালিকা</span>
            <span class="badge bg-light text-dark">মোট: <?php echo count($all_records); ?> টি রেকর্ড</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: #e9ecef;">
                    <tr>
                        <th>ID</th>
                        <th>কারখানা</th>
                        <th>এন্ট্রির তারিখ</th>
                        <th>মাস/বছর</th>
                        <th>মোট অনুমোদিত</th>
                        <th>মোট কর্মরত</th>
                        <th>গণনাকৃত শূন্য</th>
                        <th>প্রদত্ত শূন্য</th>
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
                            $calculated_vacant = $rec_granted - $rec_service;
                            $provided_vacant = $rec_promo + $rec_direct;
                            $is_valid = ($calculated_vacant == $provided_vacant);
                            $entry_date_display = !empty($record['entry_date']) ? date('d-m-Y', strtotime($record['entry_date'])) : date('d-m-Y', strtotime($record['created_at']));
                            $entry_month_year = !empty($record['entry_date']) ? date('F Y', strtotime($record['entry_date'])) : date('F Y', strtotime($record['created_at']));
                        ?>
                        <tr>
                            <td><?php echo $record['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($record['factory_name']); ?></strong></td>
                            <td><?php echo $entry_date_display; ?></td>
                            <td><?php echo $entry_month_year; ?></td>
                            <td><?php echo enToBn($rec_granted); ?></td>
                            <td><?php echo enToBn($rec_service); ?></td>
                            <td><?php echo enToBn($calculated_vacant); ?></td>
                            <td class="<?php echo $is_valid ? 'text-success' : 'text-danger fw-bold'; ?>">
                                <?php echo enToBn($provided_vacant); ?>
                                <?php if (!$is_valid): ?>
                                    <i class="fas fa-exclamation-triangle ms-1"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?edit_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-warning btn-action">
                                    <i class="fas fa-edit"></i> এডিট
                                </a>
                                <a href="?clone_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-clone btn-action" onclick="return confirm('ক্লোন করতে চান?')">
                                    <i class="fas fa-copy"></i> ক্লোন
                                </a>
                                <a href="?delete_id=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('মুছতে চান?')">
                                    <i class="fas fa-trash"></i> ডিলিট
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                কোনো রেকর্ড পাওয়া যায়নি।
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
             </table>
        </div>
    </div>

    <footer>
        <i class="fas fa-info-circle text-primary me-2"></i>
        <strong>গুরুত্বপূর্ণ তথ্য:</strong> 
        শূন্য পদ = অনুমোদিত পদ - কর্মরত = পদোন্নতিযোগ্য + সরাসরি নিয়োগ হতে হবে।
        <strong>ভ্যালিডেশন:</strong> সংরক্ষণের আগে স্বয়ংক্রিয়ভাবে যাচাই করা হয়।
    </footer>
</div>

<script>
function calculateVacant(element) {
    var row = $(element).closest('tr');
    var granted = parseInt(row.find('input[name="granted_post[]"]').val()) || 0;
    var inService = parseInt(row.find('input[name="in_service[]"]').val()) || 0;
    var calculated = granted - inService;
    row.find('.calculated-vacant').html('<span class="badge bg-secondary">' + enToBnJs(calculated) + '</span>');
    validateRow(element);
}

function validateRow(element) {
    var row = $(element).closest('tr');
    var granted = parseInt(row.find('input[name="granted_post[]"]').val()) || 0;
    var inService = parseInt(row.find('input[name="in_service[]"]').val()) || 0;
    var promo = parseInt(row.find('input[name="eligible_promotion[]"]').val()) || 0;
    var direct = parseInt(row.find('input[name="direct_recruit[]"]').val()) || 0;
    
    var calculated = granted - inService;
    var provided = promo + direct;
    
    if (calculated !== provided && (granted > 0 || inService > 0 || promo > 0 || direct > 0)) {
        row.css('background-color', '#fff3cd');
        row.find('.calculated-vacant').html('<span class="badge bg-danger">' + enToBnJs(calculated) + ' ≠ ' + enToBnJs(provided) + '</span>');
        return false;
    } else {
        row.css('background-color', '');
        row.find('.calculated-vacant').html('<span class="badge bg-success">' + enToBnJs(calculated) + ' = ' + enToBnJs(provided) + '</span>');
        return true;
    }
}

function enToBnJs(n) {
    var dict = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
    return String(n).replace(/[0-9]/g, function(w){ return dict[w]; });
}

function validateForm() {
    var allValid = true;
    $('tbody tr').each(function() {
        var granted = parseInt($(this).find('input[name="granted_post[]"]').val()) || 0;
        var inService = parseInt($(this).find('input[name="in_service[]"]').val()) || 0;
        var promo = parseInt($(this).find('input[name="eligible_promotion[]"]').val()) || 0;
        var direct = parseInt($(this).find('input[name="direct_recruit[]"]').val()) || 0;
        
        var calculated = granted - inService;
        var provided = promo + direct;
        
        if (calculated !== provided && (granted > 0 || inService > 0 || promo > 0 || direct > 0)) {
            allValid = false;
            $(this).css('background-color', '#f8d7da');
        }
    });
    
    if (!allValid) {
        alert('দয়া করে সব গ্রেডের জন্য শূন্য পদের মান সঠিক করুন!\n\nনিয়ম: (অনুমোদিত - কর্মরত) = (পদোন্নতিযোগ্য + সরাসরি নিয়োগ)');
        return false;
    }
    return true;
}

function resetForm() {
    if (confirm('সমস্ত ইনপুট ফিল্ড রিসেট করতে চান?')) {
        $('input[type="number"]').not('input[name="record_id"]').val(0);
        $('.calculated-vacant').each(function() {
            $(this).html('<span class="badge bg-secondary">০</span>');
        });
        $('tbody tr').css('background-color', '');
        $('input[name="entry_date"]').val('<?php echo $current_date; ?>');
    }
}

$(document).ready(function() {
    // Initialize all rows
    $('tbody tr').each(function() {
        validateRow(this);
    });
});
</script>
</body>
</html>