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
$is_admin = ($username === 'admin');
$role = $_SESSION['role'] ?? '';
$table = 'workers_tbl';

// Handle actions
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM $table WHERE id = '$delete_id'";
    if ($conn->query($delete_sql)) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: workers_details.php");
    exit;
}

if (isset($_GET['clone_id'])) {
    $clone_id = $conn->real_escape_string($_GET['clone_id']);
    $clone_sql = "INSERT INTO $table (factory_name, date, designation, grade, sanctioned_post, male, female, total, status, created_at, updated_at)
                  SELECT factory_name, date, designation, grade, sanctioned_post, male, female, total, status, NOW(), NOW() 
                  FROM $table WHERE id = '$clone_id'";
    if ($conn->query($clone_sql)) {
        $_SESSION['message'] = "Record cloned successfully!";
    } else {
        $_SESSION['error'] = "Error cloning record: " . $conn->error;
    }
    header("Location: workers_details.php");
    exit;
}

// Fetch all workers from all factories
$sql = "SELECT * FROM $table ORDER BY date DESC, factory_name ASC";
$result = $conn->query($sql);

// Group records by month-year for combine print functionality
$monthGroups = [];
if ($result && $result->num_rows > 0) {
    $result->data_seek(0); // Reset pointer
    while ($row = $result->fetch_assoc()) {
        $month_year = date("Y-m", strtotime($row['date']));
        if (!isset($monthGroups[$month_year])) {
            $monthGroups[$month_year] = [];
        }
        $monthGroups[$month_year][] = $row;
    }
    $result->data_seek(0); // Reset pointer again for display
}

$grades = ['g1', 'g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10', 'g11', 'g12', 'g13', 'g14', 'g15', 'g16', 'g17', 'g18', 'g19', 'g20'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard | Man Power Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

     * {
               font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif;
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
    .combine-print-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
    }
    .combine-print-btn:hover {
        background: linear-gradient(135deg, #20c997 0%, #1e7e34 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .month-group-header {
        background-color: #e9ecef !important;
        font-weight: bold;
    }
    .badge-count {
        font-size: 0.8em;
        margin-left: 5px;
    }
    @media print {
        .no-print, .btn, .alert, .modal, .navbar {
            display: none !important;
        }
        body * {
            visibility: hidden;
        }
        .print-section, .print-section * {
            visibility: visible;
        }
        .print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    }
  </style>
</head>
<body>

<div class="container mt-3 shadow rounded p-4">
  <h2 class="text-muted text-center text-uppercase fw-bold">সকল শ্রমিক জনবল বিস্তারিত</h2>
  
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="mb-3 no-print">   
    <button class="btn btn-success" onclick="printAllRecords()">
      <i class="fas fa-print"></i> Print All
    </button>
    <button class="btn combine-print-btn" onclick="printCombineRecords()">
      <i class="fas fa-copy"></i> Print All Combine
    </button>
    <a href="add_worker.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add New
    </a>
    <a href="admin_dashboard.php" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>
         
  <table class="table table-bordered print-section">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Month' Year</th>
        <th>Factory Name</th>
        <th>Designations</th>
        <th>Grades</th>
        <th>Total Workers</th>
        <th class="no-print">Actions</th>
        <th class="no-print">Print</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php 
        $current_month = '';
        while ($row = $result->fetch_assoc()): 
          $month_year = date("F' Y", strtotime($row['date']));
          $month_key = date("Y-m", strtotime($row['date']));
          $factory_name = $row['factory_name'];
          
          // Parse comma-separated data
          $designations = $row['designation'] ? explode(',', $row['designation']) : [];
          $grades = $row['grade'] ? explode(',', $row['grade']) : [];
          $male_counts = $row['male'] ? explode(',', $row['male']) : [];
          $female_counts = $row['female'] ? explode(',', $row['female']) : [];
          $total_counts = $row['total'] ? explode(',', $row['total']) : [];
          
          $total_workers = array_sum($total_counts);
          
          // Check if this is a new month group
          if ($month_key !== $current_month):
            $current_month = $month_key;
            $month_record_count = count($monthGroups[$month_key]);
        ?>
          <tr class="month-group-header">
            <td colspan="9" class="text-center">
              <strong>Month: <?php echo $month_year; ?></strong> 
              <span class="badge bg-primary badge-count"><?php echo $month_record_count; ?> record<?php echo $month_record_count > 1 ? 's' : ''; ?></span>
              <button class="btn btn-sm combine-print-btn ms-3 month-print-btn" 
                      data-month-key="<?php echo $month_key; ?>" 
                      data-month-name="<?php echo $month_year; ?>">
                <i class="fas fa-print"></i> Print Combine for <?php echo $month_year; ?>
              </button>
            </td>
          </tr>
        <?php endif; ?>
        
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
            <td><?php echo $month_year; ?></td>
            <td><?php echo htmlspecialchars($factory_name); ?></td>
            <td>
              <?php 
              foreach($designations as $index => $designation) {
                  echo htmlspecialchars(trim($designation));
                  if ($index < count($designations) - 1) echo ', ';
              }
              ?>
            </td>
            <td>
              <?php 
              foreach($grades as $index => $grade) {
                  echo htmlspecialchars(trim($grade));
                  if ($index < count($grades) - 1) echo ', ';
              }
              ?>
            </td>
            <td class="text-center"><strong><?php echo $total_workers; ?></strong></td>
            <td class="no-print">
              <div class="btn-group">
                <!-- View -->
                <button class="btn btn-info btn-sm" onclick="viewWorker(<?php echo $row['id']; ?>)"
                        data-bs-toggle="tooltip" title="View">
                  <i class="fas fa-eye"></i>
                </button>
                
                <!-- Edit -->
                <a href="workers_info_1.php?id=<?php echo $row['id']; ?>&factory_name=<?php echo urlencode($factory_name); ?>" class="btn btn-warning btn-sm"
                   data-bs-toggle="tooltip" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                                
                <!-- Clone -->
                <a href="?clone_id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm"
                   data-bs-toggle="tooltip" title="Clone" 
                   onclick="return confirm('Clone this record?')">
                  <i class="fas fa-copy"></i>
                </a>
                
                <!-- Delete -->
                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                   data-bs-toggle="tooltip" title="Delete"
                   onclick="return confirm('Delete this record?')">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
            </td>
            <td class="no-print">
                <!-- Individual Print Button -->
                <button class="btn btn-success btn-sm print-btn" 
                        onclick="printSingleWorker(<?php echo $row['id']; ?>, event)"
                        title="Print this record">
                  <i class="fas fa-print"></i> Print
                </button>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="text-center">No Workers found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Worker Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewModalBody">
        <!-- Content loaded via AJAX -->
      </div>
    </div>
  </div>
</div>

<script>
// Print all records
function printAllRecords() {
    window.print();
}

// Print combine records for all months
function printCombineRecords() {
    // Get all unique month keys
    const monthKeys = <?php echo json_encode(array_keys($monthGroups)); ?>;
    
    if (monthKeys.length === 0) {
        alert('No records found to print.');
        return;
    }
    
    // Create a loading indicator
    const $printBtn = $('.combine-print-btn:not(.month-print-btn)');
    const originalHtml = $printBtn.html();
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading Combined Data...');

    // Process each month sequentially
    processMonthsForPrint(monthKeys, 0, []);
}

function processMonthsForPrint(monthKeys, index, allData) {
    if (index >= monthKeys.length) {
        // All months processed, generate final print view
        generateCombineWorkersPrintView(allData);
        
        // Re-enable button
        const $printBtn = $('.combine-print-btn:not(.month-print-btn)');
        $printBtn.prop('disabled', false).html('<i class="fas fa-copy"></i> Print All Combine');
        return;
    }

    const monthKey = monthKeys[index];
    const monthName = getMonthName(monthKey);
    
    $.ajax({
        url: 'get_combine_workers_data.php',
        type: 'POST',
        data: { 
            month_key: monthKey,
            action: 'get_combine_data'
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    allData.push({
                        month_key: monthKey,
                        month_name: monthName,
                        data: response.data
                    });
                } else {
                    console.error('Error loading data for month ' + monthKey + ': ' + (response.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Parsing error for month ' + monthKey + ':', e);
            }
            
            // Process next month
            processMonthsForPrint(monthKeys, index + 1, allData);
        },
        error: function(xhr, status, error) {
            console.error('Error loading data for month ' + monthKey + ':', error);
            // Continue with next month even if this one fails
            processMonthsForPrint(monthKeys, index + 1, allData);
        }
    });
}

// Print combine for specific month
function printCombineMonth(monthKey, monthDisplayName, event = null) {
    let $printBtn;
    
    if (event) {
        $printBtn = $(event.target).closest('button');
    } else {
        // Fallback: find the button by data attributes
        $printBtn = $(`.month-print-btn[data-month-key="${monthKey}"]`);
    }
    
    const originalHtml = $printBtn.html();
    
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

    $.ajax({
        url: 'get_combine_workers_data.php',
        type: 'POST',
        data: { 
            month_key: monthKey,
            action: 'get_combine_data'
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    generateCombineWorkersPrintView([{
                        month_key: monthKey,
                        month_name: monthDisplayName,
                        data: response.data
                    }]);
                } else {
                    alert('Error loading combine data: ' + (response.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Parsing error:', e);
                alert('Error parsing server response.');
            }
            
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function(xhr, status, error) {
            alert('Error loading combine data.');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

// Print single worker record
function printSingleWorker(id, event) {
    const $printBtn = $(event.target).closest('button');
    const originalHtml = $printBtn.html();
    
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

    $.ajax({
        url: 'get_single_worker_data.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    generateSingleWorkerPrintView(response.data);
                } else {
                    alert('Error loading record: ' + (response.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Parsing error:', e);
                alert('Error parsing server response.');
            }
            
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function(xhr, status, error) {
            alert('Error loading record.');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

// Utility function to get month name from key
function getMonthName(monthKey) {
    const [year, month] = monthKey.split('-');
    const date = new Date(year, month - 1);
    return date.toLocaleString('en', { month: 'long', year: 'numeric' });
}

// Event delegation for all buttons
$(document).ready(function() {
    // Month-specific combine print buttons
    $(document).on('click', '.month-print-btn', function(e) {
        e.preventDefault();
        const monthKey = $(this).data('month-key');
        const monthName = $(this).data('month-name');
        printCombineMonth(monthKey, monthName, e);
    });
    
    // Main combine print button (Print All Combine)
    $(document).on('click', '.combine-print-btn:not(.month-print-btn)', function(e) {
        e.preventDefault();
        printCombineRecords();
    });
    
    // Individual print buttons
    $(document).on('click', '.print-btn', function(e) {
        e.preventDefault();
        const id = $(this).closest('tr').find('td:first').text();
        printSingleWorker(id, e);
    });
});

// Function to generate combine workers print view with grade and designation combined in one table
function generateCombineWorkersPrintView(monthsData) {
    if (!monthsData || monthsData.length === 0) {
        alert('No data available for printing.');
        return;
    }

    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Combined Workers Report - Bangladesh Chemical Industries Corporation</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>

                @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

                body {
                    font-family: 'Noto Sans Bengali', Arial, sans-serif;
                    margin: 20px;
                }

                .print-header {
                    text-align: center;
                    margin-bottom: 5px;
                }

                .month-section { 
                    margin-bottom: 40px; 
                    page-break-after: always; 
                }
                .month-section:last-child { 
                    page-break-after: avoid; 
                }
                .month-title { 
                    background: #f8f9fa; 
                    padding: 12px 15px; 
                    border-radius: 5px; 
                    margin-bottom: 10px;
                    border-left: 4px solid #007bff;
                }
                .month-title h3 {
                    margin-bottom: 5px;
                }
                .month-title p {
                    margin-bottom: 0;
                }

                .summary-table,
                .factory-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                    font-size: 13px;
                }

                .summary-table th,
                .summary-table td,
                .factory-table th,
                .factory-table td {
                    border: 1px solid #000;
                    padding: 5px;
                    text-align: center;
                }

                .summary-table th,
                .factory-table th {
                    background: #e9ecef;
                    font-weight: bold;
                }

                .total-row,
                .factory-total-row {
                    background: #d1ecf1;
                    font-weight: bold;
                }

                .bengali-title {
                    font-weight: bold;
                    margin-bottom: 10px;
                }

                .print-footer {
                    text-align: center;
                    margin-top: 10px;
                    font-size: 11px;
                }

                @media print {

                    .no-print {
                        display: none;
                    }

                    body {
                        margin: 10px;
                    }

                    @page {
                        size: Letter portrait;
                        margin: 10mm;
                    }
                }

            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header">
                    <h2 class="text-bangla">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                    <h5 class="text-bangla">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h5>
                    <h5 class="text-bangla">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : সমন্বিত রিপোর্ট (শ্রমিক)</h5>
                    <h5 class="text-bangla">বিদ্যমান জনবলের পরিসংখ্যান</h5>
                    <p class="bangla-text">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</p>
                </div>
    `;

    for (var m = 0; m < monthsData.length; m++) {
        var monthData = monthsData[m];
        if (!monthData.data || monthData.data.length === 0) continue;
        
        // Process all records for this month and organize by grade and designation
        var gradeDesignationSummary = {};
        var factoryDetails = {};
        var totalSanctioned = 0;
        var totalMale = 0;
        var totalFemale = 0;
        var grandTotal = 0;
        var totalVacant = 0;

        // Process each record
        for (var i = 0; i < monthData.data.length; i++) {
            var record = monthData.data[i];
            var factoryName = record.factory_name || 'অনামী কারখানা';
            
            // Helper function to convert single value or comma-separated string to array
            function toArray(value) {
                if (!value) return [];
                if (typeof value === 'string') {
                    // Check if it contains comma (multiple values)
                    if (value.indexOf(',') !== -1) {
                        return value.split(',');
                    } else {
                        // Single value
                        return [value];
                    }
                }
                return [value];
            }


            
            var designations = toArray(record.designation);
            var grades = toArray(record.grade);
            var sanctionedPosts = toArray(record.sanctioned_post);
            var maleCounts = toArray(record.male);
            var femaleCounts = toArray(record.female);
            var totalCounts = toArray(record.total);

            // Initialize factory details if not exists
            if (!factoryDetails[factoryName]) {
                factoryDetails[factoryName] = [];
            }

            // Get the maximum length among all arrays
            var maxLen = Math.max(
                designations.length,
                grades.length,
                sanctionedPosts.length,
                maleCounts.length,
                femaleCounts.length,
                totalCounts.length
            );
            
            // If maxLen is 0, use 1 to process at least the main record
            if (maxLen === 0) maxLen = 1;

            // Process each entry
            for (var j = 0; j < maxLen; j++) {
                var grade = grades[j] ? grades[j].trim() : (j === 0 ? (record.grade || '') : '');
                // var designationName = designations[j] ? designations[j].trim() : (j === 0 ? (record.designation || '') : '');

                var designationNameRaw = designations[j] ? designations[j] : (j === 0 ? record.designation : '');
                var designationName = cleanValue(designationNameRaw);

                var sanctioned = sanctionedPosts[j] ? parseInt(sanctionedPosts[j]) || 0 : (j === 0 && record.sanctioned_post ? parseInt(record.sanctioned_post) || 0 : 0);
                var male = maleCounts[j] ? parseInt(maleCounts[j]) || 0 : (j === 0 && record.male ? parseInt(record.male) || 0 : 0);
                var female = femaleCounts[j] ? parseInt(femaleCounts[j]) || 0 : (j === 0 && record.female ? parseInt(record.female) || 0 : 0);
                var total = totalCounts[j] ? parseInt(totalCounts[j]) || 0 : (j === 0 && record.total ? parseInt(record.total) || 0 : 0);
                
                // Skip if no meaningful data
                if (sanctioned === 0 && male === 0 && female === 0 && total === 0 && !designationName && !grade) {
                    continue;
                }
                
                var vacant = sanctioned - total;

                // Create unique key for grade-designation combination
                var key = (grade || '') + '|' + (designationName || '');
                
                if (!gradeDesignationSummary[key]) {
                    gradeDesignationSummary[key] = {
                        grade: grade,
                        designation: designationName,
                        sanctioned: 0,
                        male: 0,
                        female: 0,
                        total: 0,
                        vacant: 0
                    };
                }
                
                gradeDesignationSummary[key].sanctioned += sanctioned;
                gradeDesignationSummary[key].male += male;
                gradeDesignationSummary[key].female += female;
                gradeDesignationSummary[key].total += total;
                gradeDesignationSummary[key].vacant += vacant;

                // Store factory-wise details
                factoryDetails[factoryName].push({
                    designation: designationName,
                    grade: grade,
                    sanctioned: sanctioned,
                    male: male,
                    female: female,
                    total: total,
                    vacant: vacant
                });

                totalSanctioned += sanctioned;
                totalMale += male;
                totalFemale += female;
                grandTotal += total;
                totalVacant += vacant;
            }
        }

        printContent += `
            <div class="month-section">
                <div class="month-title">
                    <h6 class="bangla-text">মাস: ${convertToBanglaMonth(monthData.month_name)}</h6>
                    <p class="bangla-text">মোট কারখানা: ${englishToBanglaNumber(Object.keys(factoryDetails).length)} | মোট রেকর্ড: ${englishToBanglaNumber(monthData.data.length)}</p>
                </div>

                <h4 class="bengali-title">গ্রেড ও পদভিত্তিক সারসংক্ষেপ (শ্রমিক)</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th class="bangla-text">ক্রমিক</th>
                            <th class="bangla-text">পদের নাম</th>
                            <th class="bangla-text">গ্রেড</th>
                            <th class="bangla-text">অনুমোদিত পদ</th>
                            <th class="bangla-text">পুরুষ (কর্মরত)</th>
                            <th class="bangla-text">মহিলা (কর্মরত)</th>
                            <th class="bangla-text">মোট (কর্মরত)</th>
                            <th class="bangla-text">শূন্য/অতিরিক্ত পদ</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Convert object to array and sort by grade
        var summaryArray = [];
        for (var key in gradeDesignationSummary) {
            if (gradeDesignationSummary.hasOwnProperty(key)) {
                summaryArray.push(gradeDesignationSummary[key]);
            }
        }
        
        summaryArray.sort(function(a, b) {
            var gradeA = parseInt((a.grade || '').replace('Grade ', '')) || 0;
            var gradeB = parseInt((b.grade || '').replace('Grade ', '')) || 0;
            return gradeA - gradeB;
        });

        // Add rows to table
        var serial = 1;
        for (var s = 0; s < summaryArray.length; s++) {
            var item = summaryArray[s];
            printContent += `
                <tr>
                    <td class="bangla-number">${englishToBanglaNumber(serial)}</td>
                    <td class="bangla-text" style="text-align: center;">${item.designation || '-'}</td>
                    <td class="bangla-text">${convertGradeToBangla(item.grade)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.total)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.vacant)}</td>
                </tr>
            `;
            serial++;
        }

        // Add grand total row
        printContent += `
                        <tr class="total-row">
                            <td colspan="3" class="bangla-text"><strong>সর্বমোট</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalSanctioned)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalMale)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalFemale)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalVacant)}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="factory-details">
                    <h4 class="bengali-title">কারখানা ভিত্তিক বিস্তারিত বিবরণ (শ্রমিক)</h4>
        `;

        // Display factory-wise details
        var factoryNames = [];
        for (var fname in factoryDetails) {
            if (factoryDetails.hasOwnProperty(fname)) {
                factoryNames.push(fname);
            }
        }
        
        for (var f = 0; f < factoryNames.length; f++) {
            var factoryName = factoryNames[f];
            var details = factoryDetails[factoryName];
            var factorySanctionedTotal = 0;
            var factoryMaleTotal = 0;
            var factoryFemaleTotal = 0;
            var factoryTotal = 0;
            var factoryVacantTotal = 0;

            printContent += `
                <div style="margin-bottom: 20px;">
                    <div class="factory-header">
                        <strong>কারখানা: ${convertToBanglaFactory(factoryName)}</strong>
                    </div>
                    <table class="factory-table">
                        <thead>
                            <tr>
                                <th class="bangla-text">ক্রমিক</th>
                                <th class="bangla-text">পদের নাম</th>
                                <th class="bangla-text">গ্রেড</th>
                                <th class="bangla-text">অনুমোদিত পদ</th>
                                <th class="bangla-text">পুরুষ (কর্মরত)</th>
                                <th class="bangla-text">মহিলা (কর্মরত)</th>
                                <th class="bangla-text">মোট (কর্মরত)</th>
                                <th class="bangla-text">শূন্য/অতিরিক্ত পদ</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            var factorySerial = 1;
            for (var d = 0; d < details.length; d++) {
                var detail = details[d];
                factorySanctionedTotal += detail.sanctioned;
                factoryMaleTotal += detail.male;
                factoryFemaleTotal += detail.female;
                factoryTotal += detail.total;
                factoryVacantTotal += detail.vacant;

                printContent += `
                    <tr>
                        <td class="bangla-number">${englishToBanglaNumber(factorySerial)}</td>
                        <td class="bangla-text" style="text-align:center;">${detail.designation || '-'}</td>
                        <td class="bangla-text">${convertGradeToBangla(detail.grade)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.sanctioned)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.male)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.female)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.total)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.vacant)}</td>
                    </tr>
                `;
                factorySerial++;
            }

            printContent += `
                            <tr class="factory-total-row">
                                <td colspan="3" class="bangla-text" style="text-align: right;"><strong>কারখানা মোট:</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factorySanctionedTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryMaleTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryFemaleTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryVacantTotal)}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        printContent += `
            <!-- Signature Section -->
                <div class="signature-section">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                                <strong><small>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</small></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="print-footer">
                    <strong><small>Design & Developed by ICT Division, BCIC.</small></strong>
                </div>
                
        `;
    }

 printContent += `
                
                
                <div class="no-print text-center mt-2">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> প্রিন্ট করুন
                    </button>
                    <button class="btn btn-secondary" onclick="window.close()">
                        <i class="fas fa-times me-1"></i> বন্ধ করুন
                    </button>
                    <br>
                    
                </div>
                <div class="text-center mt-1">
                    <small class="text-muted">
                        প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}
                    </small>
                
            </div>
         </div>
            </div>
            
        </body>
        </html>
    `;
    var printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
}

// Function to generate single worker record print view in Bangla
function generateSingleWorkerPrintView(data) {
    // Get all CSV data
    var rawDesignations = data.designation ? data.designation.split(',') : [];
    var rawGrades = data.grade ? data.grade.split(',') : [];
    var sanctionedPosts = data.sanctioned_post ? data.sanctioned_post.split(',') : [];
    var maleCounts = data.male ? data.male.split(',') : [];
    var femaleCounts = data.female ? data.female.split(',') : [];
    var totalCounts = data.total ? data.total.split(',') : [];
    
    // Create valid entries
    var validEntries = [];
    var maxLength = Math.max(
        rawDesignations.length,
        rawGrades.length,
        sanctionedPosts.length,
        maleCounts.length,
        femaleCounts.length,
        totalCounts.length
    );
    
    for (var i = 0; i < maxLength; i++) {
        var designation = rawDesignations[i] ? rawDesignations[i].trim() : '';
        var grade = rawGrades[i] ? rawGrades[i].trim() : '';
        var sanctioned = sanctionedPosts[i] ? parseInt(sanctionedPosts[i]) || 0 : 0;
        var male = maleCounts[i] ? parseInt(maleCounts[i]) || 0 : 0;
        var female = femaleCounts[i] ? parseInt(femaleCounts[i]) || 0 : 0;
        var total = totalCounts[i] ? parseInt(totalCounts[i]) || 0 : 0;
        
        var hasValidGrade = grade !== '' && grade !== 'Grade ';
        var hasValidDesignation = designation !== '';
        var hasNonZeroData = sanctioned > 0 || male > 0 || female > 0 || total > 0;
        
        if (hasValidGrade || hasValidDesignation || hasNonZeroData) {
            if (designation === '' && (hasValidGrade || hasNonZeroData)) {
                designation = '';
            }
            
            validEntries.push({
                designation: designation,
                grade: grade,
                sanctioned: sanctioned,
                male: male,
                female: female,
                total: total
            });
        }
    }
    
    var printContent = `
    <!DOCTYPE html>
    <html lang="bn">
    <head>
        <meta charset="UTF-8">
        <title>Staff Record</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body { 
                font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif; 
                margin: 10px !important; 
                padding: 10px !important;
                background: white;
                font-size: 12px;
            }
            .container {
                margin: 0 !important;
                padding: 3px !important;
                max-width: 100% !important;
            }
            .print-header { 
                text-align: center; 
                margin-bottom: 3px;
                padding-bottom: 2px;
            }
            .print-header h2 {
                margin-bottom: 2px;
            }
            .print-header h4 {
                font-size: 14px;
                margin-bottom: 2px;
            }
            .print-header p {
                font-size: 11px;
                margin-bottom: 2px;
            }
            .detail-table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 5px; 
            }
            .detail-table th, .detail-table td { 
                border: 1px solid #ddd; 
                padding: 3px 5px; 
                text-align: left; 
                font-size: 11px;
            }
            .detail-table th { 
                background-color: #f8f9fa; 
                width: 30%; 
            }
            .staff-breakdown { 
                margin-top: 5px; 
            }
            .staff-breakdown h4 {
                font-size: 13px;
                margin-bottom: 3px;
            }
            .breakdown-table { 
                width: 100%; 
                border-collapse: collapse; 
            }
            .breakdown-table th, .breakdown-table td { 
                border: 1px solid #ddd; 
                padding: 2px 3px; 
                text-align: center; 
                font-size: 10px;
            }
            .breakdown-table th { 
                background-color: #f8f9fa; 
            }
            .total-row { 
                background-color: #e9ecef; 
                font-weight: bold; 
            }
            .designation-cell { 
                text-align: left; 
                font-weight: bold; 
                background-color: #f8f9fa; 
            }
            .bangla-text, .bangla-number { 
                font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial; 
            }
            .placeholder-text {
                color: #ff6b6b;
                font-style: italic;
            }
            .print-footer {
                text-align: center;
                margin-top: 3px;
                padding-top: 3px;
                font-size: 8px;
                border-top: 1px solid #dee2e6;
            }
            .signature-section {
                margin-top: 15px;
                text-align: center;
                font-size: 9px;
            }
            @media print {
                .no-print { display: none; }
                body { margin: 0; padding: 0; }
                @page {
                    size: auto;
                    margin: 10mm;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="print-header">
                <h3 class="bangla-text">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                <h5 class="bangla-text">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h5>
                <h5 class="bangla-text">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : ${data.factory_name || ''}</h5>
                <h5 class="bangla-text">বিদ্যমান জনবলের পরিসংখ্যান (শ্রমিক)</h5>
                <p class="bangla-text">তারিখ: ${data.date ? convertDateToBangla(data.date) : ''}</p>
            </div>
            
            <table class="detail-table">
                <tr>
                    <th class="bangla-text">কারখানার নাম</th>
                    <td class="bangla-text">${data.factory_name || ''}</td>
                </tr>
                <tr>
                    <th class="bangla-text">তারিখ</th>
                    <td class="bangla-text">${data.date ? convertDateToBangla(data.date) : ''}</td>
                </tr>
                <tr>
                    <th class="bangla-text">মোট শ্রমিক সংখ্যা</th>
                    <td class="bangla-number"><strong>${data.total ? englishToBanglaNumber(data.total.split(',').reduce(function(sum, val) { return sum + (parseInt(val) || 0); }, 0)) : '০'}</strong></td>
                </tr>
                <tr>
                    <th class="bangla-text">তৈরির তারিখ</th>
                    <td class="bangla-text">${data.created_at ? convertDateToBangla(data.created_at.split(' ')[0]) + ' ' + data.created_at.split(' ')[1] : ''}</td>
                </tr>
                <tr>
                    <th class="bangla-text">হালনাগাদের তারিখ</th>
                    <td class="bangla-text">${data.updated_at ? convertDateToBangla(data.updated_at.split(' ')[0]) + ' ' + data.updated_at.split(' ')[1] : ''}</td>
                </tr>
            </table>
    `;

    // Add staff breakdown table
    if (validEntries.length > 0) {
        printContent += `
            <div class="staff-breakdown">
                <h4 class="bangla-text mt-3 fw-bold">গ্রেড ও পদভিত্তিক শ্রমিক বিবরণ</h4>
                <table class="breakdown-table">
                    <thead>
                        <tr>
                            <th class="bangla-text">ক্রমিক</th>
                            <th class="bangla-text">পদের নাম</th>
                            <th class="bangla-text">গ্রেড</th>
                            <th class="bangla-text">অনুমোদিত পদ</th>
                            <th class="bangla-text">পুরুষ (কর্মরত)</th>
                            <th class="bangla-text">মহিলা (কর্মরত)</th>
                            <th class="bangla-text">মোট (কর্মরত)</th>
                            <th class="bangla-text">শূন্য/অতিরিক্ত পদ</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        var totalSanctioned = 0;
        var totalMale = 0;
        var totalFemale = 0;
        var grandTotal = 0;
        var totalVacant = 0;

        for (var e = 0; e < validEntries.length; e++) {
            var entry = validEntries[e];
            var vacant = entry.sanctioned - entry.total;
            
            totalSanctioned += entry.sanctioned;
            totalMale += entry.male;
            totalFemale += entry.female;
            grandTotal += entry.total;
            totalVacant += vacant;
            
            var isPlaceholder = entry.designation === '';
            var designationClass = isPlaceholder ? 'placeholder-text' : '';

            printContent += `
                <tr>
                    <td class="bangla-number">${englishToBanglaNumber(e + 1)}</td>
                    <td class="bangla-text " style="text-align: center;">${entry.designation || '-'}</td>
                
                    <td class="bangla-text">${convertGradeToBangla(entry.grade)|| '-'}</td>
                    <td class="bangla-number">${englishToBanglaNumber(entry.sanctioned)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(entry.male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(entry.female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(entry.total)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(vacant)}</td>
                </tr>
            `;
        }

        printContent += `
                <tr class="total-row">
                    <td colspan="3" class="bangla-text"><strong>সর্বমোট</strong></td>
                    <td class="bangla-number"><strong>${englishToBanglaNumber(totalSanctioned)}</strong></td>
                    <td class="bangla-number"><strong>${englishToBanglaNumber(totalMale)}</strong></td>
                    <td class="bangla-number"><strong>${englishToBanglaNumber(totalFemale)}</strong></td>
                    <td class="bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td>
                    <td class="bangla-number"><strong>${englishToBanglaNumber(totalVacant)}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
        `;
    }
    
    // Add signature section
    printContent += `
        <div class="signature-section">
            <strong>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</strong>
        </div>
        <div class="print-footer">
            <strong>Design & Developed by ICT Division, BCIC.</strong>
        </div>

        <div class="no-print text-center mt-4">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> প্রিন্ট করুন
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                <i class="fas fa-times me-1"></i> বন্ধ করুন
            </button>
        </div>
        <div class="text-center mt-2">
            <small class="text-muted bangla-number">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</small>
        </div>
    `;

    printContent += `
        </div>
    </body>
    </html>
    `;

    var printWindow = window.open('', '_blank', 'width=auto,height=auto,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
}

// Utility functions for Bangla conversion
function englishToBanglaNumber(number) {
    var banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return number.toString().replace(/\d/g, function(match) {
        return banglaNumbers[parseInt(match)];
    });
}

function cleanValue(val) {
    if (!val) return '-';

    // Remove commas and spaces
    let cleaned = val.replace(/,/g, '').trim();

    // If empty after cleaning → return '-'
    return cleaned === '' ? '-' : val.trim();
}

function convertToBanglaFactory(factoryName) {
    var factoryMap = {
        'sfcl': 'শাহজালাল ফার্টিলাইজার কোম্পানী লিমিটেড (এসএফসিএল)',
        'jfcl': 'যমুনা ফার্টিলাইজার ফ্যাক্টরী লিমিটেড (জেএফসিএল)',
        'admin': 'প্রশাসন',
        'cufl': 'চিটাগাং ইউরিয়া ফার্টিলাইজার লিমিটেড (সিইউএফএল)',
        'bcicho': 'বিসিআইসি প্রধান কার্যালয়',
        'bcicclg': 'বিসিআইসি কলেজ',
        'gpfplc': 'ঘোড়াশাল পলাশ ফার্টিলাইজার পাবলিক লিমিটেড কোম্পানী',
        'afccl': 'আশুগঞ্জ ফার্টিলাইজার এন্ড কেমিক্যাল কোম্পানী লিমিটেড',
        'dapfcl': 'ডিএপি ফার্টিলাইজার কোম্পানী লিমিটেড (ডিএপিএফসিএল)',
        'tspcl': 'টিএসপি কমপ্লেক্স লিমিটেড (টিএসপিসিএল)',
        'tici': 'ট্রেনিং ইন্সটিটিউট ফর কেমিক্যাল ইন্ডাস্ট্রিজ (টিআইসিআই)',
        'cccl': 'ছাতক সিমেন্ট কোম্পানী লিমিটেড (সিসিসিএল)',
        'kpml': 'কর্ণফুলী পেপার মিলস লিমিটেড (কেপিএমএল)',
        'bisf': 'বাংলাদেশ ইনসুলেটর এন্ড স্যানিটারীওয়্যার ফ্যাক্টরী',
        'usgfl': 'উসমানিয়া গ্লাস শীট ফ্যাক্টরী লিমিটেড (ইউজিএসএফএল)',
        'dlcl': 'ঢাকা লেদার কোম্পানি লিমিটেড (ডিএলসিএল)',
        'ccc': 'চিটাগাং কেমিক্যাল কমপ্লেক্স (সিসিসি)',
        'khbm': 'খুলনা হার্ড বোর্ড মিলস লিমিটেড (কেএইচবিএমএল)',
        'knm': 'খুলনা নিউজপ্রিন্ট মিলস (কেএনএম)',
        'krc': 'কেআরসি',
        'nbpm': 'নর্থ বেঙ্গল পেপার মিলস (এনবিপিএম)',
        'gpufp': 'ঘোড়াশাল পলাশ ইউরিয়া ফার্টিলাইজার প্রকল্প (জিপিইউএফপি)',
        '34buffer': '৩৪ বাফার গুদাম নির্মাণ প্রকল্প',
        '13buffer': '১৩ বাফার গুদাম নির্মাণ প্রকল্প',
        'broffice': 'বিসিআইসি প্রধান কার্যালয়',
        'sadmin': 'সিনিয়র প্রশাসন',
        'null': 'এনএ/এনুল'
    };
    
    var key = factoryName.toString().toLowerCase().trim();
    return factoryMap[key] || factoryName;
}

function convertDateToBangla(dateString) {
    var date = new Date(dateString);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    
    return englishToBanglaNumber(day) + '-' + englishToBanglaNumber(month) + '-' + englishToBanglaNumber(year);
}

function convertToBanglaMonth(dateString) {
    var months = {
        '01': 'জানুয়ারি',
        '02': 'ফেব্রুয়ারি',
        '03': 'মার্চ',
        '04': 'এপ্রিল',
        '05': 'মে',
        '06': 'জুন',
        '07': 'জুলাই',
        '08': 'আগস্ট',
        '09': 'সেপ্টেম্বর',
        '10': 'অক্টোবর',
        '11': 'নভেম্বর',
        '12': 'ডিসেম্বর'
    };
    
    var date = new Date(dateString);
    if (!isNaN(date.getTime())) {
        var month = date.getMonth() + 1;
        var monthStr = month.toString().padStart(2, '0');
        var year = date.getFullYear();
        return months[monthStr] + ' ' + englishToBanglaNumber(year);
    }
    
    return dateString;
}

function convertGradeToBangla(grade) {
    var gradeMap = {
        'Grade 1': 'গ্রেড ১',
        'Grade 2': 'গ্রেড ২',
        'Grade 3': 'গ্রেড ৩',
        'Grade 4': 'গ্রেড ৪',
        'Grade 5': 'গ্রেড ৫',
        'Grade 6': 'গ্রেড ৬',
        'Grade 7': 'গ্রেড ৭',
        'Grade 8': 'গ্রেড ৮',
        'Grade 9': 'গ্রেড ৯',
        'Grade 10': 'গ্রেড ১০',
        'Grade 11': 'গ্রেড ১১',
        'Grade 12': 'গ্রেড ১২',
        'Grade 13': 'গ্রেড ১৩',
        'Grade 14': 'গ্রেড ১৪',
        'Grade 15': 'গ্রেড ১৫',
        'Grade 16': 'গ্রেড ১৬',
        'Grade 17': 'গ্রেড ১৭',
        'Grade 18': 'গ্রেড ১৮',
        'Grade 19': 'গ্রেড ১৯',
        'Grade 20': 'গ্রেড ২০'
    };
    
    return gradeMap[grade] || grade;
}

function viewWorker(id) {
    $.ajax({
        url: 'view_worker.php',
        type: 'GET', 
        data: {id: id},
        success: function(response) {
            $('#viewModalBody').html(response);
            $('#viewModal').modal('show');
        }
    });
}

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

</body>
</html>