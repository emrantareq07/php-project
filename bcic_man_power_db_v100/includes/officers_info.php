<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$back=1;

if ($_SESSION['role'] == 'admin' && $_SESSION['username'] == 'admin') {
  $role = $_SESSION['role'] ?? ''; // ensure role exists
  $username = $conn->real_escape_string($_GET['factory_name']);

  $_SESSION['username']=$username;

}else{
  $role = $_SESSION['role'] ?? ''; // ensure role exists
  $username = $_SESSION['username'];
  
}
$table = 'officers_tbl';

//$id = $conn->real_escape_string($_GET['id']);
//$factory_name = $conn->real_escape_string($_GET['factory']);

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));

// Calculate first day of next month for edit restrictions
$first_day_next_month = date('Y-m-01', strtotime('+1 month'));

// Fetch existing records for the table
$records = [];
$sql = "SELECT * FROM $table WHERE factory_name = '$username'  ORDER BY date DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

// Function to check if entry exists for selected month (excluding current record during edit)
function checkMonthlyEntry($conn, $table, $username, $date, $exclude_id = null) {
    $year_month = date('Y-m', strtotime($date));
    $sql = "SELECT id FROM $table WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    
    if ($exclude_id) {
        $sql .= " AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $username, $year_month, $exclude_id);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $year_month);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Initialize variables
$is_loading = false;
$load_record = null;
$editing_record_id = null;
$is_editing = false;
$edit_record = null;
$monthly_entry_exists = false;
$monthly_entry_message = '';

// Check if we're loading an existing record
if (isset($_GET['load_id'])) {
    $editing_record_id = $_GET['load_id'];
    $is_loading = true;
    
    // Fetch the specific record for loading
    $load_sql = "SELECT * FROM $table WHERE id = ?";
    $load_stmt = $conn->prepare($load_sql);
    $load_stmt->bind_param('i', $editing_record_id);
    $load_stmt->execute();
    $load_result = $load_stmt->get_result();
    
    if ($load_result && $load_result->num_rows > 0) {
        $load_record = $load_result->fetch_assoc();
        
        // Check monthly restriction for load mode (saving as new record)
        $selected_date = $load_record['date'];
        $monthly_entry_exists = checkMonthlyEntry($conn, $table, $username, $selected_date);
        if ($monthly_entry_exists) {
            $monthly_entry_message = "An entry already exists for " . date('F Y', strtotime($selected_date)) . ". You cannot create another entry for this month.";
        }
    } else {
        $is_loading = false;
        $editing_record_id = null;
        $load_record = null;
    }
    $load_stmt->close();
}

// Check if we're editing an existing record
if (isset($_GET['edit_id'])) {
    $editing_record_id = $_GET['edit_id'];
    $is_editing = true;
    
    // Fetch the specific record for editing
    $edit_sql = "SELECT * FROM $table WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param('i', $editing_record_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_record = $edit_result->fetch_assoc();
        
        // For editing, we exclude the current record from monthly check
        // But we don't show error message for editing - only informational
        $selected_date = $edit_record['date'];
        $monthly_entry_exists = checkMonthlyEntry($conn, $table, $username, $selected_date, $editing_record_id);
        // Don't set monthly_entry_message for editing - allow editing even if other entries exist
    } else {
        $is_editing = false;
        $editing_record_id = null;
        $edit_record = null;
    }
    $edit_stmt->close();
}

// Check for monthly restrictions for new entries (not loading or editing)
if (!$is_loading && !$is_editing) {
    $selected_date = $today_date;
    $monthly_entry_exists = checkMonthlyEntry($conn, $table, $username, $selected_date);
    if ($monthly_entry_exists) {
        $monthly_entry_message = "An entry already exists for " . date('F Y', strtotime($selected_date)) . ". Please edit the existing entry.";
    }
}

// If form is submitted via POST, check the submitted date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $submitted_date = $_POST['date'];
    
    if ($is_editing) {
        // For edit: exclude current record but don't prevent saving
        // Allow editing even if other entries exist for the same month
        $monthly_entry_exists = false; // Don't block editing
    } else {
        // For new entry or load-as-new: check without exclusion
        $monthly_entry_exists = checkMonthlyEntry($conn, $table, $username, $submitted_date);
    }
    
    if ($monthly_entry_exists) {
        $monthly_entry_message = "An entry already exists for " . date('F Y', strtotime($submitted_date)) . ". You can only have one entry per month.";
    }
}

// Departments and Grades
// $sections1 = [
//     'General Admin', 'Security', 'Medical', 'College', 'School', 'Library',
//     'Accounts', 'ICT','Commercial', 'Production (Chemical)', 'Production (Chemist)', 
//     'Engineering (Mechanical)', 'Engineering (Electrical + Instrument + Others)',
//     'Engineering (Civil)', 'Forest/FRM'
// ];

// $sections = [
//     'সাধারণ প্রশাসন', 'নিরাপত্তা', 'চিকিৎসা', 'কলেজ', 'স্কুল', 'লাইব্রেরি',
//     'হিসাব/অর্থ', 'আইসিটি','বাণিজ্যিক', 'প্রোডাকশন (কেমিক্যাল ইঞ্জিনিয়ারিং', 'প্রোডাকশন (কেমিস্ট)', 
//     'ইঞ্জিনিয়ারিং (মেকানিক্যাল)', 'ইঞ্জিনিয়ারিং (ইলেকট্রিক্যাল + ইন্সট্রুমেন্ট + অন্যান্য)',
//     'ইঞ্জিনিয়ারিং (সিভিল)', 'বন/এফআরএম'
// ];




// $sections1 = [
//     'Administration1',
//     'Security',
//     'Technical (Production, Safety & Environment)',
//     'Technical (Forest/FRM)',
//     'Technical (Engineering-Mechanical)',
//     'Technical (Engineering-Electrical/Instrument/Others)',
//     'Technical (Engineering-Civil)',
//     'Medical',
//     'Commercial',
//     'Accounts & Finance',
//     'ICT',
//     'Educational Institution-College',
//     'Educational Institution-School',
//     'Library'
// ];


$sections1 = [
      'প্রশাসন',
    'নিরাপত্তা',
    'কারিগরি (উৎপাদন, নিরাপত্তা ও পরিবেশ)',
    'কারিগরি (বন/এফআরএম)',
    'কারিগরি (ইঞ্জিনিয়ারিং - মেকানিক্যাল)',
    'কারিগরি (ইঞ্জিনিয়ারিং - ইলেকট্রিক্যাল/ইন্সট্রুমেন্ট/অন্যান্য)',
    'কারিগরি (ইঞ্জিনিয়ারিং - সিভিল)',
    'চিকিৎসা',
    'বাণিজ্যিক',
    'হিসাব ও অর্থ',
    'আইসিটি',
    'শিক্ষা প্রতিষ্ঠান - কলেজ',
    'শিক্ষা প্রতিষ্ঠান - স্কুল',
    'লাইব্রেরি'
];

$sections = [
    'প্রশাসন',
    'নিরাপত্তা',
    'কারিগরি (উৎপাদন, নিরাপত্তা ও পরিবেশ)',
    'কারিগরি (বন/এফআরএম)',
    'কারিগরি (ইঞ্জিনিয়ারিং - মেকানিক্যাল)',
    'কারিগরি (ইঞ্জিনিয়ারিং - ইলেকট্রিক্যাল/ইন্সট্রুমেন্ট/অন্যান্য)',
    'কারিগরি (ইঞ্জিনিয়ারিং - সিভিল)',
    'চিকিৎসা',
    'বাণিজ্যিক',
    'হিসাব ও অর্থ',
    'আইসিটি',
    'শিক্ষা প্রতিষ্ঠান - কলেজ',
    'শিক্ষা প্রতিষ্ঠান - স্কুল',
    'লাইব্রেরি'
];

$divisions = [
    'প্রশাসন',
    'হিসাব ও অর্থ', 'বাণিজ্যিক', 'কারিগরি'
];

$grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Management Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <style>
    .male-col { background-color: #e3f2fd !important; }
    .female-col { background-color: #fce4ec !important; }
    .total-col { background-color: #f5f5f5 !important; font-weight: bold; }
    .section-row:hover { background-color: #f8f9fa !important; }
    .input-comfort { 
        height: 45px; 
        padding: 8px 12px; 
        font-size: 1rem; 
        text-align: center;
        font-weight: 500;
    }
    .compact-table th, .compact-table td { padding: 10px 12px !important; }
    .sticky-header { position: sticky; top: 0; background: white; z-index: 100; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .summary-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .quick-total { font-size: 1rem; padding: 12px 16px; }
    .grade-badge { font-size: 0.8rem; padding: 4px 8px; }
    .section-name { font-weight: 600; font-size: 1rem; }
    .input-highlight:focus { 
        border-color: #0d6efd; 
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        background-color: #fff;
    }
    .total-display {
        font-size: 1.1rem;
        font-weight: bold;
        padding: 8px 4px;
        display: block;
        text-align: center;
    }
    .column-header {
        min-width: 90px;
    }
    .department-column {
        min-width: 220px;
        position: sticky;
        left: 0;
        background: #f8f9fa !important;
        z-index: 5;
        border-right: 2px solid #dee2e6;
    }
    .has-data {
        background-color: #e8f5e8 !important;
        border: 1px solid #28a745 !important;
    }
    .partial-save-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
    }
    .print-btn {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        color: white;
    }
    .print-btn:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .disabled-record {
        background-color: #f8f9fa;
/*        opacity: 0.6;*/
    }
    .load-mode-indicator {
        background-color: #cce7ff;
        color: #004085;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid #004085;
    }
    .edit-mode-indicator {
        background-color: #fff3cd;
        color: #856404;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid #856404;
    }
  </style>
</head>

<body class="bg-light">
<div class="container-fluid py-3">

  <!-- Custom Alert Container -->
  <div id="alertContainer"></div>

  <!-- Load Mode Indicator -->
  <?php if ($is_loading && $load_record): ?>
  <div class="load-mode-indicator">
    <i class="fas fa-hourglass-half me-2"></i>
    <strong>LOAD MODE:</strong> Loading record from <?php echo $load_record['date']; ?> (ID: <?php echo $editing_record_id; ?>). 
    You can modify the data and save as a new record.
    <button class="btn btn-secondary btn-sm ms-3" id="cancelLoad">
      <i class="fas fa-times me-1"></i>Cancel Load
    </button>
  </div>
  <?php endif; ?>

  <!-- Edit Mode Indicator -->
  <?php if ($is_editing && $edit_record): ?>
  <div class="edit-mode-indicator">
    <i class="fas fa-edit me-2"></i>
    <strong>EDIT MODE:</strong> Editing record from <?php echo $edit_record['date']; ?> (ID: <?php echo $editing_record_id; ?>). 
    You can modify the data and update the existing record.
    <button class="btn btn-secondary btn-sm ms-3" id="cancelEdit">
      <i class="fas fa-times me-1"></i>Cancel Edit
    </button>
  </div>
  <?php endif; ?>
          <!-- Monthly Restriction Warning -->
        <?php if ($monthly_entry_message): ?>
        <div class="monthly-warning alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php echo $monthly_entry_message; ?>
        </div>
        <?php endif; ?>

  <!-- Header -->
  <div class="sticky-header bg-white p-4 rounded shadow-sm mb-4">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h4 class="mb-1 text-primary">
          <i class="fas fa-users me-2"></i>BCIC Officer's Info. Data Entry
        </h4>
        
        <small class="text-muted">Enter employee counts - You can save partially filled data</small>
      </div>
      <div class="col-md-6 text-end">
        <div class="btn-group">

      <?php if ($role == 'admin') { ?>
         
          <a href="officer_details.php" class="btn btn-outline-secondary btn-md">
              <i class="fa fa-arrow-left me-1"></i>Back
          </a>
      <?php } else { ?>
          <a href="dashboard.php" class="btn btn-outline-secondary btn-md">
              <i class="fa fa-arrow-left me-1"></i>Back
          </a>
      <?php } ?>

          
          <button class="btn btn-outline-primary btn-md" id="clearAll" title="Clear all inputs">
            <i class="fas fa-eraser me-1"></i>Clear All
          </button>
          <button class="btn btn-outline-secondary btn-md" id="fillZeros" title="Fill all with zero">
            <i class="fas fa-sync me-1"></i>Fill Zeros
          </button>
          <button class="btn btn-outline-info btn-md" id="savePartial" title="Save current progress">
            <i class="fas fa-save me-1"></i>Save Progress
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Summary -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card bg-primary text-white quick-total">
        <div class="card-body py-3">
          <h6 class="mb-1">Total Male</h6>
          <h3 class="mb-0" id="quickMaleTotal">0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-danger text-white quick-total">
        <div class="card-body py-3">
          <h6 class="mb-1">Total Female</h6>
          <h3 class="mb-0" id="quickFemaleTotal">0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-success text-white quick-total">
        <div class="card-body py-3">
          <h6 class="mb-1">Grand Total</h6>
          <h3 class="mb-0" id="quickGrandTotal">0</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Progress Indicator -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h6 class="mb-2">Data Entry Progress</h6>
          <div class="progress" style="height: 20px;">
            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%">0%</div>
          </div>
        </div>
        <div class="col-md-4 text-end">
          <small class="text-muted" id="progressText">No data entered yet</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Form -->
  <form id="employeeForm" class="card shadow-sm mb-4">
    <input type="hidden" name="id" id="id" value="<?php echo $is_editing ? $editing_record_id : ''; ?>">
    <input type="hidden" name="load_id" id="load_id" value="<?php echo $is_loading ? $editing_record_id : ''; ?>">
    
    <div class="card-header bg-light py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <strong class="text-dark h6">Factory:</strong> 
          <span class="text-muted h6"><?php echo $username; ?></span>
        </div>
        <div class="col-md-6 text-end">
          <strong class="text-dark h6 me-2">Date:</strong>
          <input type="date" name="date" class="form-control d-inline-block w-auto" id="date" 
                 value="<?php 
                 if ($is_loading && $load_record) echo $load_record['date'];
                 elseif ($is_editing && $edit_record) echo $edit_record['date'];
                 else echo $today_date; 
                 ?>" 
                 <?php echo $is_editing ? 'readonly' : ''; ?>>
        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive" style="max-height: 65vh;">
        <table class="table table-bordered mb-0">
          <thead class="table-light sticky-top" style="top: 0;">
            <tr>
              <th class="department-column">Department</th>
              <?php foreach($grades as $grade): ?>
              <th colspan="3" class="text-center border-start column-header">
                <span class="badge grade-badge bg-dark">Grade <?php echo substr($grade, 1); ?></span>
              </th>
              <?php endforeach; ?>
              <th colspan="3" class="text-center border-start bg-light column-header">Department Total</th>
            </tr>
            <tr>
              <th class="department-column"></th>
              <?php foreach($grades as $grade): ?>
              <th class="male-col text-center column-header"><i class="fas fa-male me-1"></i>Male</th>
              <th class="female-col text-center column-header"><i class="fas fa-female me-1"></i>Female</th>
              <th class="total-col text-center border-end column-header">Total</th>
              <?php endforeach; ?>
              <th class="male-col text-center column-header">Male</th>
              <th class="female-col text-center column-header">Female</th>
              <th class="total-col text-center column-header">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sections1 as $index => $section): ?>
            <tr class="section-row">
              <td class="section-name department-column">
                <strong><?php echo $section; ?></strong>
              </td>
              
              <?php foreach($grades as $grade): ?>
              <td class="male-col">
                <input type="number" class="form-control input-comfort input-highlight <?php echo $grade; ?>_m" 
                       name="data[<?php echo $index; ?>][<?php echo $grade; ?>_m]" 
                       min="0" value="" 
                       data-grade="<?php echo $grade; ?>" 
                       data-section="<?php echo $index; ?>"
                       placeholder="0" 
                       style="font-size: 1.1rem;">
              </td>
              <td class="female-col">
                <input type="number" class="form-control input-comfort input-highlight <?php echo $grade; ?>_f" 
                       name="data[<?php echo $index; ?>][<?php echo $grade; ?>_f]" 
                       min="0" value="" 
                       data-grade="<?php echo $grade; ?>" 
                       data-section="<?php echo $index; ?>"
                       placeholder="0" 
                       style="font-size: 1.1rem;">
              </td>
              <td class="total-col text-center border-end">
                <span class="total-display <?php echo $grade; ?>_total">0</span>
              </td>
              <?php endforeach; ?>
              
              <td class="male-col text-center">
                <span class="total-display section_male">0</span>
              </td>
              <td class="female-col text-center">
                <span class="total-display section_female">0</span>
              </td>
              <td class="total-col text-center">
                <span class="total-display section_total" style="color: #2c5aa0;">0</span>
              </td>
            </tr>
            <?php endforeach; ?>
            
            <!-- Totals Row -->
            <tr class="table-active">
              <td class="department-column bg-dark text-dark">
                <strong class="h6">GRAND TOTALS</strong>
              </td>
              
              <?php foreach($grades as $grade): ?>
              <td class="male-col text-center">
                <strong class="total-display total_<?php echo $grade; ?>_m">0</strong>
              </td>
              <td class="female-col text-center">
                <strong class="total-display total_<?php echo $grade; ?>_f">0</strong>
              </td>
              <td class="total-col text-center border-end">
                <strong class="total-display total_<?php echo $grade; ?>" style="color: #2c5aa0;">0</strong>
              </td>
              <?php endforeach; ?>
              
              <td class="male-col text-center bg-primary text-white">
                <strong class="total-display" id="finalMaleTotal">0</strong>
              </td>
              <td class="female-col text-center bg-danger text-white">
                <strong class="total-display" id="finalFemaleTotal">0</strong>
              </td>
              <td class="total-col text-center bg-success text-white">
                <strong class="total-display" id="finalGrandTotal">0</strong>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer bg-light py-4">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            <small>You can save partially filled data. Empty fields will be saved as 0.</small>
          </div>
        </div>
        <div class="col-md-6 text-end">
          <button class="btn btn-outline-secondary btn-lg px-4 me-2" type="button" id="cancelBtn" style="display:none;">
            <i class="fas fa-times me-1"></i>Cancel
          </button>
          <?php if ($is_loading && $load_record): ?>
          <button class="btn btn-primary btn-lg px-4" type="submit" id="submitBtn">
            <i class="fas fa-save me-1"></i>Save as New Record
          </button>
          <?php elseif ($is_editing && $edit_record): ?>
          <button class="btn btn-warning btn-lg px-4" type="submit" id="submitBtn">
            <i class="fas fa-sync me-1"></i>Update Record
          </button>
          <?php else: ?>
          <button class="btn btn-success btn-lg px-4" type="submit" id="submitBtn">
            <i class="fas fa-save me-1"></i>Save Data
          </button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </form>

  <!-- Records Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-light py-3">
      <h5 class="mb-0"><i class="fas fa-history me-2"></i>Previous Records</h5>
    </div>
    <div class="card-body p-2">
      <div class="table-responsive">
        <table class="table table-bordered mb-0" id="recordsTable">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              <?php foreach($grades as $grade): ?>
              <th>G<?php echo substr($grade, 1); ?> M</th>
              <th>G<?php echo substr($grade, 1); ?> F</th>
              <?php endforeach; ?>
              <th>Total</th>
              <th style="width: 180px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $current_year_month = date('Y-m');
            
            foreach ($records as $record): 
                $record_date = $record['date'];
                $record_year_month = date('Y-m', strtotime($record_date));
                
                // Check if record is from current or future month
                $is_current_or_future = ($record_year_month >= $current_year_month);
            ?>
            <tr class="<?php echo $is_current_or_future ? '' : 'disabled-record'; ?>">
                <td><strong><?php echo $record['date']; ?></strong></td>
                <?php 
                $total_employees = 0;
                foreach($grades as $grade): 
                    $grade_m = array_sum(explode(',', $record[$grade.'_m']));
                    $grade_f = array_sum(explode(',', $record[$grade.'_f']));
                    $total_employees += $grade_m + $grade_f;
                ?>
                <td class="text-center"><?php echo $grade_m; ?></td>
                <td class="text-center"><?php echo $grade_f; ?></td>
                <?php endforeach; ?>
                <td class="text-center"><strong><?php echo $total_employees; ?></strong></td>
                <td class="text-center">
              <?php if ($role != 'admin') { ?>
                  <?php if ($is_current_or_future) { ?>
                      <!-- Show all buttons for current and future months -->
                      <button class="btn btn-warning btn-sm edit-btn me-1" data-id="<?php echo $record['id']; ?>" title="Edit">
                          <i class="fas fa-edit me-1"></i>Edit
                      </button>
                      <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $record['id']; ?>" title="Delete">
                          <i class="fas fa-trash me-1"></i>Delete
                      </button>
                  <?php } ?>
                  <!-- Always show Print and Load buttons -->
                  <button class="btn btn-info btn-sm print-btn me-1" data-id="<?php echo $record['id']; ?>" title="Print">
                      <i class="fas fa-print me-1"></i>Print
                  </button>
                  <button class="btn btn-primary btn-sm load-btn" data-id="<?php echo $record['id']; ?>" title="Load for New Record">
                      <i class="fas fa-hourglass-half me-1"></i>Clone
                  </button>
              <?php } else { ?>
                  <!-- Show all buttons for current and future months -->
                  <button class="btn btn-warning btn-sm edit-btn me-1" data-id="<?php echo $record['id']; ?>" title="Edit">
                      <i class="fas fa-edit me-1"></i>Edit
                  </button>
                  <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $record['id']; ?>" title="Delete">
                      <i class="fas fa-trash me-1"></i>Delete
                  </button>

                  <!-- Always show Print and Load buttons -->
                  <button class="btn btn-info btn-sm print-btn me-1" data-id="<?php echo $record['id']; ?>" title="Print">
                      <i class="fas fa-print me-1"></i>Print
                  </button>
                  <button class="btn btn-primary btn-sm load-btn" data-id="<?php echo $record['id']; ?>" title="Load for New Record">
                      <i class="fas fa-hourglass-half me-1"></i>Clone
                  </button>
              <?php } ?>
          </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
$(document).ready(function() {
  // Function to convert English numbers to Bangla (for print only)
  function englishToBanglaNumber(number) {
    if (number === null || number === undefined || number === '') return '০';
    
    const english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const bangla = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    
    let numStr = number.toString();
    english.forEach((eng, index) => {
      numStr = numStr.replace(new RegExp(eng, 'g'), bangla[index]);
    });
    
    return numStr;
  }

  // Function to convert date to Bangla format
  function convertDateToBangla(dateString) {
    const date = new Date(dateString);
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    
    return englishToBanglaNumber(day) + '/' + englishToBanglaNumber(month) + '/' + englishToBanglaNumber(year);
  }

  // Initialize DataTable
  $('#recordsTable').DataTable({
    pageLength: 8,
    order: [[0, 'desc']],
    language: {
      search: "Search records:",
      lengthMenu: "Show _MENU_ records"
    }
  });

  // Function to show alerts with auto-dismiss
  // function showAlert(message, type = 'info') {
  //   const alertClass = {
  //     'success': 'alert-success',
  //     'error': 'alert-danger',
  //     'warning': 'alert-warning',
  //     'info': 'alert-info'
  //   }[type] || 'alert-info';

  //   // Remove existing alerts
  //   $('.auto-dismiss-alert').remove();

  //   const alertHtml = `
  //     <div class="alert ${alertClass} alert-dismissible fade show auto-dismiss-alert" role="alert"
  //       style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
  //       <div class="d-flex justify-content-between align-items-center">
  //         <div>
  //           <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
  //         </div>
  //         <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
  //       </div>
  //     </div>
  //   `;
  //   $('body').append(alertHtml);

  //   // Auto-dismiss after 20 seconds
  //   setTimeout(() => {
  //     $('.auto-dismiss-alert').alert('close');
  //   }, 2000);
  // }

  // Function to show alerts with configurable auto-dismiss
function showAlert(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    // Remove existing alerts
    $('.auto-dismiss-alert, .persistent-alert').remove();

    // Use persistent alerts for errors, auto-dismiss for others
    const alertClassname = type === 'error' ? 'persistent-alert' : 'auto-dismiss-alert';
    
    const alertHtml = `
      <div class="alert ${alertClass} alert-dismissible fade show ${alertClassname}" role="alert"
        style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
          </div>
          <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
        </div>
      </div>
    `;
    $('body').append(alertHtml);

    // Auto-dismiss only for non-error messages (after 20 seconds)
    if (type !== 'error') {
        setTimeout(() => {
            $(`.${alertClassname}`).alert('close');
        }, 20000); // 20 seconds
    }
    // Error messages will persist until manually closed
}

  // Function to calculate progress
  function calculateProgress() {
    const totalInputs = $('input[type="number"]').length;
    const filledInputs = $('input[type="number"]').filter(function() {
      return $(this).val().trim() !== '';
    }).length;
    
    const progress = totalInputs > 0 ? Math.round((filledInputs / totalInputs) * 100) : 0;
    
    $('#progressBar').css('width', progress + '%').text(progress + '%');
    
    if (progress === 0) {
      $('#progressText').text('No data entered yet');
    } else if (progress < 50) {
      $('#progressText').text(filledInputs + '/' + totalInputs + ' fields filled - Getting started');
    } else if (progress < 100) {
      $('#progressText').text(filledInputs + '/' + totalInputs + ' fields filled - Good progress');
    } else {
      $('#progressText').text('All fields filled - Ready to save');
    }
    
    return progress;
  }

  // Function to calculate totals for a row
  function calculateRowTotals(row) {
    let section_male = 0;
    let section_female = 0;
    let section_total = 0;

    // Calculate each grade total and accumulate section totals
    <?php foreach($grades as $grade): ?>
    const <?php echo $grade; ?>_m = parseFloat($(row).find('.<?php echo $grade; ?>_m').val()) || 0;
    const <?php echo $grade; ?>_f = parseFloat($(row).find('.<?php echo $grade; ?>_f').val()) || 0;
    const <?php echo $grade; ?>_total = <?php echo $grade; ?>_m + <?php echo $grade; ?>_f;
    
    $(row).find('.<?php echo $grade; ?>_total').text(<?php echo $grade; ?>_total);
    section_male += <?php echo $grade; ?>_m;
    section_female += <?php echo $grade; ?>_f;
    section_total += <?php echo $grade; ?>_total;
    <?php endforeach; ?>

    $(row).find('.section_male').text(section_male);
    $(row).find('.section_female').text(section_female);
    $(row).find('.section_total').text(section_total);
  }

  // Function to calculate all totals
  function calculateAllTotals() {
    let grade_totals = {};
    let total_male = 0;
    let total_female = 0;
    let grand_total = 0;

    // Initialize grade totals
    <?php foreach($grades as $grade): ?>
    grade_totals['<?php echo $grade; ?>_m'] = 0;
    grade_totals['<?php echo $grade; ?>_f'] = 0;
    grade_totals['<?php echo $grade; ?>_total'] = 0;
    <?php endforeach; ?>

    // Calculate row totals and accumulate
    $('#employeeForm tbody tr:not(.table-active)').each(function() {
      calculateRowTotals(this);
      
      <?php foreach($grades as $grade): ?>
      const <?php echo $grade; ?>_m = parseFloat($(this).find('.<?php echo $grade; ?>_m').val()) || 0;
      const <?php echo $grade; ?>_f = parseFloat($(this).find('.<?php echo $grade; ?>_f').val()) || 0;
      
      grade_totals['<?php echo $grade; ?>_m'] += <?php echo $grade; ?>_m;
      grade_totals['<?php echo $grade; ?>_f'] += <?php echo $grade; ?>_f;
      <?php endforeach; ?>
    });

    // Calculate grade totals and overall totals
    <?php foreach($grades as $grade): ?>
    grade_totals['<?php echo $grade; ?>_total'] = grade_totals['<?php echo $grade; ?>_m'] + grade_totals['<?php echo $grade; ?>_f'];
    total_male += grade_totals['<?php echo $grade; ?>_m'];
    total_female += grade_totals['<?php echo $grade; ?>_f'];
    <?php endforeach; ?>
    
    grand_total = total_male + total_female;

    // Update grade total row
    <?php foreach($grades as $grade): ?>
    $('.total_<?php echo $grade; ?>_m').text(grade_totals['<?php echo $grade; ?>_m']);
    $('.total_<?php echo $grade; ?>_f').text(grade_totals['<?php echo $grade; ?>_f']);
    $('.total_<?php echo $grade; ?>').text(grade_totals['<?php echo $grade; ?>_total']);
    <?php endforeach; ?>

    // Update final totals
    $('#finalMaleTotal').text(total_male);
    $('#finalFemaleTotal').text(total_female);
    $('#finalGrandTotal').text(grand_total);

    // Update quick summary
    $('#quickMaleTotal').text(total_male);
    $('#quickFemaleTotal').text(total_female);
    $('#quickGrandTotal').text(grand_total);

    // Update progress
    calculateProgress();
  }
  
  // Calculate totals when any input changes
  $('#employeeForm').on('input', 'input[type="number"]', function() {
    // Add visual indicator for filled fields
    if ($(this).val().trim() !== '') {
      $(this).addClass('has-data');
    } else {
      $(this).removeClass('has-data');
    }
    
    calculateAllTotals();
  });
  
  // Initialize totals on page load
  calculateAllTotals();

  

  // Load button behavior
  $(document).on('click', '.load-btn', function() {
    const id = $(this).data('id');
    window.location.href = 'officers_info.php?load_id=' + id;
  });

  // Cancel Load button
  $('#cancelLoad').on('click', function() {
    window.location.href = 'officers_info.php';
  });

  // Cancel Edit button
  $('#cancelEdit').on('click', function() {
    window.location.href = 'officers_info.php';
  });

  // Quick Actions
  $('#clearAll').on('click', function() {
    if (confirm('Are you sure you want to clear all data?')) {
      $('#employeeForm input[type="number"]').val('').removeClass('has-data');
      calculateAllTotals();
      showAlert('All fields cleared successfully!', 'success');
    }
  });

  $('#fillZeros').on('click', function() {
    $('#employeeForm input[type="number"]').val('0').addClass('has-data');
    calculateAllTotals();
    showAlert('All fields filled with zeros!', 'success');
  });

  $('#savePartial').on('click', function() {
    $('#employeeForm').submit();
  });

  // Reset form function
  function resetForm() {
    $('#id').val('');
    $('#load_id').val('');
    $('#date').val('<?php echo $today_date; ?>').prop('readonly', false);
    $('#submitBtn').html('<i class="fas fa-save me-1"></i>Save Data');
    $('#cancelBtn').hide();
    $('#employeeForm input[type="number"]').val('').removeClass('has-data');
    calculateAllTotals();
  }

  // Cancel edit
  $('#cancelBtn').on('click', resetForm);

  // Edit button click
  $(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    const $editBtn = $(this);

    $editBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

    $.ajax({
        url: 'get_record.php',
        type: 'POST',
        data: { 
            id: id,
            mode: 'single' // Use single mode for editing
        },
        timeout: 10000,
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);

                if (response.success) {
                    $('#id').val(response.data.id);
                    $('#date').val(response.data.date).prop('readonly', true);

                    // Populate inputs for each section & grade
                    $('#employeeForm tbody tr:not(.table-active)').each(function(index) {
                        <?php foreach($grades as $grade): ?>
                        let <?php echo $grade; ?>_m = response.data.<?php echo $grade; ?>_m ? response.data.<?php echo $grade; ?>_m.split(',') : [];
                        let <?php echo $grade; ?>_f = response.data.<?php echo $grade; ?>_f ? response.data.<?php echo $grade; ?>_f.split(',') : [];

                        // Fallback to 0 if undefined
                        $(this).find('.<?php echo $grade; ?>_m').val(<?php echo $grade; ?>_m[index] !== undefined ? <?php echo $grade; ?>_m[index] : '');
                        $(this).find('.<?php echo $grade; ?>_f').val(<?php echo $grade; ?>_f[index] !== undefined ? <?php echo $grade; ?>_f[index] : '');
                        <?php endforeach; ?>
                    });

                    // Add visual indicators
                    $('#employeeForm input[type="number"]').each(function() {
                        if ($(this).val().trim() !== '') $(this).addClass('has-data');
                        else $(this).removeClass('has-data');
                    });

                    calculateAllTotals();

                    $('#submitBtn').html('<i class="fas fa-sync me-1"></i>Update Record');
                    $('#cancelBtn').show();
                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    showAlert('Record loaded for editing! Date field is locked.', 'success');
                } else {
                    showAlert('Error loading record: ' + (response.message || 'Unknown error'), 'error');
                }
            } catch (e) {
                console.error('Parsing error:', e, 'Response:', response);
                showAlert('Error parsing server response. Please try again.', 'error');
            }
        },
        error: function(xhr, status, error) {
            let errorMsg = 'Error loading record: ';
            if (status === 'timeout') errorMsg += 'Request timed out.';
            else if (xhr.status === 0) errorMsg += 'Network error.';
            else if (xhr.status === 404) errorMsg += 'Record not found.';
            else if (xhr.status === 500) errorMsg += 'Server error.';
            else errorMsg += error || 'Unknown error.';
            showAlert(errorMsg, 'error');
        },
        complete: function() {
            $editBtn.prop('disabled', false).html('<i class="fas fa-edit me-1"></i>Edit');
        }
    });
  });

  // Delete button click
  $(document).on('click', '.delete-btn', function() {
    if (!confirm('Are you sure you want to delete this record?')) {
      return;
    }
    
    const id = $(this).data('id');
    
    $.ajax({
      url: 'delete_record.php',
      type: 'POST',
      data: { id: id },
      success: function(response) {
        try {
          const result = JSON.parse(response);
          if (result.success) {
            showAlert('Record deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
          } else {
            showAlert('Error deleting record: ' + result.message, 'error');
          }
        } catch (e) {
          showAlert('Error parsing response', 'error');
        }
      },
      error: function() {
        showAlert('Error deleting record', 'error');
      }
    });
  });

// Print button functionality
$(document).on('click', '.print-btn', function() {
    const id = $(this).data('id');
    const $printBtn = $(this);

    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

     $.ajax({
        url: 'get_record.php',
        type: 'POST',
        data: { 
            id: id,
            mode: 'combined' // Use combined mode for printing
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);

                if (response.success) {
                    generatePrintView(response.data);
                } else {
                    showAlert('Error loading record for printing: ' + (response.message || 'Unknown error'), 'error');
                }
            } catch (e) {
                console.error('Parsing error:', e);
                showAlert('Error parsing server response for printing.', 'error');
            }
        },
        error: function(xhr, status, error) {
            showAlert('Error loading record for printing.', 'error');
        },
        complete: function() {
            $printBtn.prop('disabled', false).html('<i class="fas fa-print me-1"></i>Print');
        }
    });
});

// Function to generate print view with Bangla numbers
// Function to generate print view with Bangla numbers
function generatePrintView(data) {
    // Create print content
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Employee Data Report - ${data.date}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { font-family: 'SolaimanLipi', 'Siyam Rupali', 'Arial', sans-serif; margin: 20px; }
                .print-header { background: #f8f9fa; padding: 20px; border-bottom: 2px solid #dee2e6; margin-bottom: 20px; border-radius: 8px; }
                .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
                .print-table th, .print-table td { border: 1px solid #dee2e6; padding: 6px; text-align: center; }
                .print-table th { background-color: #e9ecef; font-weight: bold; }
                .department-cell { text-align: left; font-weight: bold; background-color: #f8f9fa !important; min-width: 180px; }
                .division-cell { text-align: center; font-weight: bold; background-color: #f8f9fa !important; min-width: 40px; vertical-align: middle; }
                .total-row { background-color: #e9ecef !important; font-weight: bold; }
                .male-col { background-color: #e3f2fd !important; }
                .female-col { background-color: #fce4ec !important; }
                .grade-total { background-color: #f5f5f5 !important; }
                .section-total { background-color: #e9ecef !important; font-weight: bold; }
                .grand-total { background-color: #495057 !important; color: white !important; }
                .serial-col { text-align: center; font-weight: bold; min-width: 50px; }
                
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; }
                    .print-table { font-size: 10px; }
                    .print-header { margin: 10px; }
                }
                .text-center { text-align: center; }
                .mb-2 { margin-bottom: 10px; }
                .mb-1 { margin-bottom: 5px; }
                .mb-0 { margin-bottom: 0; }

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
            <div class="container-fluid">
                <div class="print-header text-center">
                    <h3 class="mb-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                    <h6 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h6>
                    <h5 class="mb-0">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : <?php 

                     $sql_factory_name="SELECT factory_name FROM users WHERE username='$username'";
  $result_factory_name = $conn->query($sql_factory_name);
  $row_factory_name = $result_factory_name->fetch_assoc();
  $factory_name=$row_factory_name['factory_name'];

                    echo $factory_name; ?></h5>
                    <h6 class="mb-0">বিদ্যমান জনবলের পরিসংখ্যান : (${convertDateToBangla(data.date)} তারিখে)</h6>
                </div>
    `;

    // Create the table structure
    printContent += `
        <table class="print-table">
            <thead>
                <tr>
                    <th class="division-cell" rowspan="2">ক্রম</th>
                    <th class="department-cell" rowspan="2">উপ-বিভাগ/শাখা</th>
    `;

    // Add grade headers with Bangla numbers
    <?php foreach($grades as $grade): ?>
    printContent += `
        <th colspan="3" class="text-center">
            গ্রেড ${englishToBanglaNumber('<?php echo substr($grade, 1); ?>')}
        </th>
    `;
    <?php endforeach; ?>

    printContent += `
        <th colspan="3" class="text-center">সর্বমোট</th>
                </tr>
                <tr>
    `;

    // Add sub-headers for each grade
    <?php foreach($grades as $grade): ?>
    printContent += `
        <th class="male-col">পুরুষ</th>
        <th class="female-col">মহিলা</th>
        <th class="grade-total">মোট</th>
    `;
    <?php endforeach; ?>

    printContent += `
        <th class="male-col">পুরুষ</th>
        <th class="female-col">মহিলা</th>
        <th class="grade-total">মোট</th>
                </tr>
            </thead>
            <tbody>
    `;

    // Define sections array for JavaScript (Bengali version)
    const sections = [
      'প্রশাসন',
      'নিরাপত্তা',
      'কারিগরি (উৎপাদন, নিরাপত্তা ও পরিবেশ)',
      'কারিগরি (বন/এফআরএম)',
      'কারিগরি (ইঞ্জিনিয়ারিং - মেকানিক্যাল)',
      'কারিগরি (ইঞ্জিনিয়ারিং - ইলেকট্রিক্যাল/ইন্সট্রুমেন্ট/অন্যান্য)',
      'কারিগরি (ইঞ্জিনিয়ারিং - সিভিল)',
      'চিকিৎসা',
      'বাণিজ্যিক',
      'হিসাব ও অর্থ',
      'আইসিটি',
      'শিক্ষা প্রতিষ্ঠান - কলেজ',
      'শিক্ষা প্রতিষ্ঠান - স্কুল',
      'লাইব্রেরি'
    ];

    const grades = <?php echo json_encode($grades); ?>;

    // Add data rows with Bangla numbers
    let grandMaleTotal = 0;
    let grandFemaleTotal = 0;
    let grandTotal = 0;
    
    // Counter for serial numbers
    let serialNumber = 1;

    sections.forEach((section, index) => {
        let sectionMaleTotal = 0;
        let sectionFemaleTotal = 0;
        let sectionTotal = 0;

        grades.forEach(grade => {
            // Get the values for this section and grade
            const grade_m_values = data[grade + '_m'] ? data[grade + '_m'].split(',') : [];
            const grade_f_values = data[grade + '_f'] ? data[grade + '_f'].split(',') : [];
            const grade_m = grade_m_values[index] || 0;
            const grade_f = grade_f_values[index] || 0;
            const grade_total = parseInt(grade_m) + parseInt(grade_f);
            
            // Accumulate section totals
            sectionMaleTotal += parseInt(grade_m);
            sectionFemaleTotal += parseInt(grade_f);
            sectionTotal += grade_total;
        });

        // Accumulate grand totals
        grandMaleTotal += sectionMaleTotal;
        grandFemaleTotal += sectionFemaleTotal;
        grandTotal += sectionTotal;

        // Add row with serial number in Bangla
        printContent += `
                <tr>
                    <td class="division-cell bangla-number">${englishToBanglaNumber(serialNumber)}</td>
                    <td class="department-cell">${section}</td>
        `;

        // Add grade data
        grades.forEach(grade => {
            const grade_m_values = data[grade + '_m'] ? data[grade + '_m'].split(',') : [];
            const grade_f_values = data[grade + '_f'] ? data[grade + '_f'].split(',') : [];
            const grade_m = grade_m_values[index] || 0;
            const grade_f = grade_f_values[index] || 0;
            const grade_total = parseInt(grade_m) + parseInt(grade_f);
            
            printContent += `
                    <td class="male-col bangla-number">${englishToBanglaNumber(grade_m)}</td>
                    <td class="female-col bangla-number">${englishToBanglaNumber(grade_f)}</td>
                    <td class="grade-total bangla-number">${englishToBanglaNumber(grade_total)}</td>
            `;
        });
        
        printContent += `
                    <td class="male-col section-total bangla-number">${englishToBanglaNumber(sectionMaleTotal)}</td>
                    <td class="female-col section-total bangla-number">${englishToBanglaNumber(sectionFemaleTotal)}</td>
                    <td class="grade-total section-total bangla-number">${englishToBanglaNumber(sectionTotal)}</td>
                </tr>
        `;

        serialNumber++; // Increment serial number for next row
    });

    // Add grand totals row with Bangla numbers
    printContent += `
        <tr class="total-row">
            <td class="division-cell grand-total"></td>
            <td class="department-cell grand-total"><strong>সর্বমোট</strong></td>
    `;

    // Calculate and display grade-wise grand totals with Bangla numbers
    grades.forEach(grade => {
        let gradeMaleTotal = 0;
        let gradeFemaleTotal = 0;
        let gradeTotal = 0;

        if (data[grade + '_m']) {
            gradeMaleTotal = data[grade + '_m'].split(',').reduce((sum, val) => sum + parseInt(val || 0), 0);
        }
        if (data[grade + '_f']) {
            gradeFemaleTotal = data[grade + '_f'].split(',').reduce((sum, val) => sum + parseInt(val || 0), 0);
        }
        gradeTotal = gradeMaleTotal + gradeFemaleTotal;

        printContent += `
            <td class="male-col grand-total bangla-number"><strong>${englishToBanglaNumber(gradeMaleTotal)}</strong></td>
            <td class="female-col grand-total bangla-number"><strong>${englishToBanglaNumber(gradeFemaleTotal)}</strong></td>
            <td class="grade-total grand-total bangla-number"><strong>${englishToBanglaNumber(gradeTotal)}</strong></td>
        `;
    });

    printContent += `
        <td class="male-col grand-total bangla-number"><strong>${englishToBanglaNumber(grandMaleTotal)}</strong></td>
        <td class="female-col grand-total bangla-number"><strong>${englishToBanglaNumber(grandFemaleTotal)}</strong></td>
        <td class="grade-total grand-total bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td>
        </tr>
    `;

    printContent += `
            </tbody>
        </table>
        
        <!-- Signature Section -->
        <div class="row mt-5">
            <div class="col-md-6 text-center">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto; padding-top: 10px;">
                    <strong>প্রস্তুতকারীর স্বাক্ষর</strong><br>
                    <small>নাম ও পদবী</small>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto; padding-top: 10px;">
                    <strong>দায়িত্বপ্রাপ্ত কর্মকর্তার স্বাক্ষর</strong><br>
                    <small>নাম ও পদবী</small>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center no-print">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>প্রিন্ট করুন
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                <i class="fas fa-times me-1"></i>বন্ধ করুন
            </button>
        </div>
        <div class="text-center no-print mt-2">
            <small class="text-muted bangla-number">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</small>
        </div>
    `;

    printContent += `
            </div>
        </body>
        </html>
    `;

    // Open print window
    const printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Focus the print window
    printWindow.focus();
}
  // Monthly restriction check function
function checkMonthlyRestriction(date) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'check_monthly_entry_officer.php',
            type: 'POST',
            data: { 
                date: date,
                factory_name: '<?php echo $username; ?>',
                table: 'officers_tbl',
                exclude_id: '<?php echo $is_editing ? $editing_record_id : ""; ?>'
            },
            dataType: 'json',
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}

// Date change handler with monthly validation
$('#date').on('change', function() {
    const selectedDate = $(this).val();
    if (!selectedDate) return;

    const isEdit = $('#id').val() !== '';
    
    // Don't check monthly restrictions when editing
    if (isEdit) {
        return;
    }

    checkMonthlyRestriction(selectedDate)
        .then(response => {
            if (response.exists) {
                // Show warning and disable save button
                showAlert(`An entry already exists for ${response.month_year}. Please edit the existing entry.`, 'warning');
                $('#submitBtn').prop('disabled', true).addClass('btn-secondary').removeClass('btn-success btn-primary btn-warning');
                
                // Update monthly warning in the form
                $('.monthly-warning').remove();
                $('.card-header').after(`
                    <div class="monthly-warning alert alert-warning mx-3 mt-3">
                        <i class="fas fa-exclamation-triangle"></i> 
                        An entry already exists for ${response.month_year}. Please edit the existing entry.
                    </div>
                `);
            } else {
                // Enable save button and remove warnings
                $('#submitBtn').prop('disabled', false);
                if ('<?php echo $is_loading; ?>' === '1') {
                    $('#submitBtn').removeClass('btn-secondary').addClass('btn-primary');
                } else if ('<?php echo $is_editing; ?>' === '1') {
                    $('#submitBtn').removeClass('btn-secondary').addClass('btn-warning');
                } else {
                    $('#submitBtn').removeClass('btn-secondary').addClass('btn-success');
                }
                $('.monthly-warning').remove();
            }
        })
        .catch(error => {
            console.error('Monthly check error:', error);
            showAlert('Error checking monthly restrictions. Please try again.', 'error');
        });
});

// Enhanced form submission with monthly validation
$('#employeeForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    const selectedDate = $('#date').val();
    const isEdit = $('#id').val() !== '';
    const isLoad = $('#load_id').val() !== '';
    
    // Show saving state
    const $submitBtn = $('#submitBtn');
    const originalText = $submitBtn.html();
    $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Validating...');
    
    // Only check monthly restriction for NEW entries and LOAD-AS-NEW, not for editing
    if (!isEdit) {
        // First check monthly restriction for new entries
        checkMonthlyRestriction(selectedDate)
            .then(response => {
                if (response.exists) {
                    showAlert(`Cannot save: An entry already exists for ${response.month_year}. Please edit the existing entry.`, 'error');
                    $submitBtn.prop('disabled', false).html(originalText);
                    return;
                }
                
                // If no monthly conflict, proceed with save
                proceedWithSave(formData, isEdit, isLoad, $submitBtn, originalText);
            })
            .catch(error => {
                showAlert('Error validating monthly restrictions. Please try again.', 'error');
                $submitBtn.prop('disabled', false).html(originalText);
            });
    } else {
        // For editing, skip monthly check and proceed directly to save
        proceedWithSave(formData, isEdit, isLoad, $submitBtn, originalText);
    }
});

// Separate function for saving
function proceedWithSave(formData, isEdit, isLoad, $submitBtn, originalText) {
    $submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');
    
    $.ajax({
        url: 'save_data.php',
        type: 'POST',
        data: formData,
        timeout: 5000,
        success: function(response) {
            let result;
            try {
                result = typeof response === 'string' ? JSON.parse(response) : response;
            } catch (e) {
                console.error('Invalid JSON response:', response);
                showAlert('Server returned an invalid response.', 'error');
                $submitBtn.prop('disabled', false).html(originalText);
                return;
            }
            
            if (result.success) {
                if (isLoad) {
                    showAlert('Data saved as new record successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = 'officers_info.php';
                    }, 1500);
                } else if (isEdit) {
                    showAlert('Record updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = 'officers_info.php';
                    }, 1500);
                } else {
                    showAlert('Data saved successfully!', 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            } else {
                showAlert('Error: ' + result.message, 'error');
                $submitBtn.prop('disabled', false).html(originalText);
            }
        },
        error: function(xhr, status, error) {
            let errorMsg = 'Error saving data: ';
            
            if (status === 'timeout') {
                errorMsg = 'Request timed out. Please try again.';
            } else if (xhr.status === 0) {
                errorMsg = 'Network error. Please check your internet connection.';
            } else if (xhr.status === 500) {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = 'Server Error: ' + (errorResponse.message || 'Unknown server error');
                } catch (e) {
                    errorMsg = 'Server error occurred. Please try again later.';
                }
            } else {
                errorMsg += error || 'Unknown error occurred.';
            }
            
            showAlert(errorMsg, 'error');
            $submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

  // Auto-load data if in load mode
  <?php if ($is_loading && $load_record): ?>
  $(document).ready(function() {
    // Populate the form with loaded data
    $('#employeeForm tbody tr:not(.table-active)').each(function(index) {
        <?php foreach($grades as $grade): ?>
        let <?php echo $grade; ?>_m = '<?php echo $load_record[$grade."_m"] ?? ""; ?>'.split(',');
        let <?php echo $grade; ?>_f = '<?php echo $load_record[$grade."_f"] ?? ""; ?>'.split(',');

        // Fallback to empty if undefined
        $(this).find('.<?php echo $grade; ?>_m').val(<?php echo $grade; ?>_m[index] !== undefined && <?php echo $grade; ?>_m[index] !== '' ? <?php echo $grade; ?>_m[index] : '');
        $(this).find('.<?php echo $grade; ?>_f').val(<?php echo $grade; ?>_f[index] !== undefined && <?php echo $grade; ?>_f[index] !== '' ? <?php echo $grade; ?>_f[index] : '');
        <?php endforeach; ?>
    });

    // Add visual indicators
    $('#employeeForm input[type="number"]').each(function() {
        if ($(this).val().trim() !== '') $(this).addClass('has-data');
        else $(this).removeClass('has-data');
    });

    calculateAllTotals();
    showAlert('Record loaded successfully! You can modify the data and save as a new record.', 'info');
  });
  <?php endif; ?>

  // Auto-load data if in edit mode
  <?php if ($is_editing && $edit_record): ?>
  $(document).ready(function() {
    // Populate the form with edit data
    $('#employeeForm tbody tr:not(.table-active)').each(function(index) {
        <?php foreach($grades as $grade): ?>
        let <?php echo $grade; ?>_m = '<?php echo $edit_record[$grade."_m"] ?? ""; ?>'.split(',');
        let <?php echo $grade; ?>_f = '<?php echo $edit_record[$grade."_f"] ?? ""; ?>'.split(',');

        // Fallback to empty if undefined
        $(this).find('.<?php echo $grade; ?>_m').val(<?php echo $grade; ?>_m[index] !== undefined && <?php echo $grade; ?>_m[index] !== '' ? <?php echo $grade; ?>_m[index] : '');
        $(this).find('.<?php echo $grade; ?>_f').val(<?php echo $grade; ?>_f[index] !== undefined && <?php echo $grade; ?>_f[index] !== '' ? <?php echo $grade; ?>_f[index] : '');
        <?php endforeach; ?>
    });

    // Add visual indicators
    $('#employeeForm input[type="number"]').each(function() {
        if ($(this).val().trim() !== '') $(this).addClass('has-data');
        else $(this).removeClass('has-data');
    });

    calculateAllTotals();
    showAlert('Record loaded for editing! Date field is locked.', 'success');
  });
  <?php endif; ?>
});
</script>
</body>
</html>