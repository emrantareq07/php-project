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
$table = 'ansar_tbl';

// Handle actions
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM $table WHERE id = '$delete_id'";
    if ($conn->query($delete_sql)) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: ansar_details.php");
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
    header("Location: ansar_details.php");
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
  <h2 class="text-muted text-center text-uppercase fw-bold">Ansar From All Factory</h2>
  
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
    <!-- <a href="add_worker.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add New
    </a> -->
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
                <a href="daily_basis_info.php?id=<?php echo $row['id']; ?>&factory_name=<?php echo urlencode($factory_name); ?>" class="btn btn-warning btn-sm"
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
          <td colspan="9" class="text-center">No Daily Basis Record found</td>
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
        url: 'get_ansar_data.php',
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
        url: 'get_combine_ansar_data.php',
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
        url: 'get_single_ansar_data.php',
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
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { 
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif; 
                    margin: 20px; 
                    direction: ltr;
                }
                .print-header { 
                    text-align: center; 
                    margin-bottom: 30px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
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
                    padding: 15px; 
                    border-radius: 5px; 
                    margin-bottom: 20px;
                    border-left: 4px solid #007bff;
                }
                .summary-table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 20px;
                    font-size: 14px;
                }
                .summary-table th, .summary-table td { 
                    border: 1px solid #000; 
                    padding: 8px; 
                    text-align: center; 
                }
                .summary-table th { 
                    background-color: #e9ecef; 
                    font-weight: bold;
                }
                .total-row {
                    background-color: #d1ecf1 !important;
                    font-weight: bold;
                }
                .bangla-number, .bangla-text {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-size: 14px;
                }
                @media print {
                    .no-print { display: none; }
                    .month-section { page-break-inside: avoid; }
                    body { margin: 10px; font-size: 12px; }
                    .summary-table { font-size: 12px; }
                }
                .text-bangla {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                }
                .bengali-title {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-weight: bold;
                    font-size: 16px;
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header">
                    <h2 class="text-bangla">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                    <h5 class="text-bangla">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h5>
                    <h4 class="text-bangla">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : সমন্বিত রিপোর্ট (দৈনিক ভিত্তিক)</h4>
                    <h5 class="text-bangla">বিদ্যমান জনবলের পরিসংখ্যান</h5>
                    <p class="bangla-text">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</p>
                </div>
    `;

    monthsData.forEach(monthData => {
        if (!monthData.data || monthData.data.length === 0) return;
        
        // Process all records for this month and organize by grade and designation
        const gradeDesignationSummary = {};
        let totalSanctioned = 0;
        let totalMale = 0;
        let totalFemale = 0;
        let grandTotal = 0;
        let totalVacant = 0;

        // Process each record
        monthData.data.forEach(record => {
            const designations = record.designation ? record.designation.split(',') : [];
            const grades = record.grade ? record.grade.split(',') : [];
            const sanctionedPosts = record.sanctioned_post ? record.sanctioned_post.split(',') : [];
            const maleCounts = record.male ? record.male.split(',') : [];
            const femaleCounts = record.female ? record.female.split(',') : [];
            const totalCounts = record.total ? record.total.split(',') : [];

            // Process each designation in the record
            designations.forEach((designation, index) => {
                const grade = grades[index] ? grades[index].trim() : '';
                const designationName = designation.trim();
                const sanctioned = sanctionedPosts[index] ? parseInt(sanctionedPosts[index]) : 0;
                const male = maleCounts[index] ? parseInt(maleCounts[index]) : 0;
                const female = femaleCounts[index] ? parseInt(femaleCounts[index]) : 0;
                const total = totalCounts[index] ? parseInt(totalCounts[index]) : 0;
                const vacant = sanctioned - total;

                // Create unique key for grade-designation combination
                const key = `${grade}|${designationName}`;
                
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

                totalSanctioned += sanctioned;
                totalMale += male;
                totalFemale += female;
                grandTotal += total;
                totalVacant += vacant;
            });
        });

        printContent += `
            <div class="month-section">
                <div class="month-title">
                    <h3 class="bangla-text">মাস: ${convertToBanglaMonth(monthData.month_name)}</h3>
                    <p class="bangla-text">মোট রেকর্ড: ${englishToBanglaNumber(monthData.data.length)}</p>
                </div>

                <h4 class="bengali-title">গ্রেড ও পদভিত্তিক সারসংক্ষেপ (আনছার)</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th class="bangla-text" width="8%">ক্রমিক</th>
                            <th class="bangla-text" width="25%">পদের নাম</th>
                            <th class="bangla-text" width="10%">গ্রেড</th>
                            <th class="bangla-text" width="12%">অনুমোদিত পদ</th>
                            <th class="bangla-text" width="10%">পুরুষ</th>
                            <th class="bangla-text" width="10%">মহিলা</th>
                            <th class="bangla-text" width="10%">মোট</th>
                            <th class="bangla-text" width="12%">খালি পদ</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Convert object to array and sort by grade
        const summaryArray = Object.values(gradeDesignationSummary);
        summaryArray.sort((a, b) => {
            // Extract grade number for sorting
            const gradeA = parseInt(a.grade.replace('Grade ', ''));
            const gradeB = parseInt(b.grade.replace('Grade ', ''));
            return gradeA - gradeB;
        });

        // Add rows to table
        let serial = 1;
        summaryArray.forEach((item) => {
            // Add designation row
            printContent += `
                <tr>
                    <td class="bangla-number">${englishToBanglaNumber(serial)}</td>
                    <td class="bangla-text" style="text-align: left;">${item.designation}</td>
                    <td class="bangla-text">${convertGradeToBangla(item.grade)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.total)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.vacant)}</td>
                </tr>
            `;
            serial++;
        });

        // Add grand total row only
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
    });

    printContent += `
                <div class="no-print text-center mt-4">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> প্রিন্ট করুন
                    </button>
                    <button class="btn btn-secondary" onclick="window.close()">
                        <i class="fas fa-times me-1"></i> বন্ধ করুন
                    </button>
                     <br>
                    <small class="text-muted bangla-number">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</small>
                </div>
            </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
}

// Function to generate single worker record print view in Bangla
function generateSingleWorkerPrintView(data) {
    const designations = data.designation ? data.designation.split(',') : [];
    const grades = data.grade ? data.grade.split(',') : [];
    const sanctionedPosts = data.sanctioned_post ? data.sanctioned_post.split(',') : [];
    const maleCounts = data.male ? data.male.split(',') : [];
    const femaleCounts = data.female ? data.female.split(',') : [];
    const totalCounts = data.total ? data.total.split(',') : [];
    
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Worker Record - ${data.factory_name || ''}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { 
                    font-family: 'Noto Sans Bengali', Arial, sans-serif; 
                    margin: 20px; 
                }
                .print-header { 
                    text-align: center; 
                    margin-bottom: 30px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                }
                .detail-table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 20px;
                }
                .detail-table th, .detail-table td { 
                    border: 1px solid #ddd; 
                    padding: 10px; 
                    text-align: left; 
                }
                .detail-table th { 
                    background-color: #f8f9fa; 
                    width: 30%; 
                }
                .staff-breakdown {
                    margin-top: 30px;
                }
                .breakdown-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .breakdown-table th, .breakdown-table td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: center;
                }
                .breakdown-table th {
                    background-color: #e9ecef;
                    font-weight: bold;
                }
                .total-row {
                    background-color: #d1ecf1 !important;
                    font-weight: bold;
                }
                .bangla-text, .bangla-number {
                    font-family: 'Noto Sans Bengali', Arial, sans-serif;
                }
                .bengali-title {
                    font-family: 'Noto Sans Bengali', Arial, sans-serif;
                    font-weight: bold;
                    font-size: 18px;
                    text-align: center;
                    margin-bottom: 20px;
                }
                @media print {
                    .no-print { display: none; }
                    body { margin: 10px; }
                    .breakdown-table { font-size: 12px; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="print-header">
                    <h2 class="bangla-text">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                    <h5 class="bangla-text">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h5>
                    <h4 class="bangla-text">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : ${data.factory_name || ''}</h4>
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
                        <th class="bangla-text">মোট আনছার সংখ্যা</th>
                        <td class="bangla-number"><strong>${data.total ? englishToBanglaNumber(data.total.split(',').reduce((sum, val) => sum + parseInt(val || 0), 0)) : '০'}</strong></td>
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
    if (designations.length > 0) {
        printContent += `
            <div class="staff-breakdown">
                <h4 class="bengali-title">গ্রেড ও পদভিত্তিক আনছার বিবরণ</h4>
                <table class="breakdown-table">
                    <thead>
                        <tr>
                            <th class="bangla-text" width="8%">ক্রমিক</th>
                            <th class="bangla-text" width="32%">পদের নাম</th>
                            <th class="bangla-text" width="20%">গ্রেড</th>
                            <th class="bangla-text" width="12%">অনুমোদিত পদ</th>
                            <th class="bangla-text" width="10%">পুরুষ</th>
                            <th class="bangla-text" width="10%">মহিলা</th>
                            <th class="bangla-text" width="10%">মোট</th>
                            <th class="bangla-text" width="12%">খালি পদ</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        let totalSanctioned = 0;
        let totalMale = 0;
        let totalFemale = 0;
        let grandTotal = 0;
        let totalVacant = 0;

        designations.forEach((designation, index) => {
            const sanctioned = sanctionedPosts[index] ? parseInt(sanctionedPosts[index]) : 0;
            const male = maleCounts[index] ? parseInt(maleCounts[index]) : 0;
            const female = femaleCounts[index] ? parseInt(femaleCounts[index]) : 0;
            const total = totalCounts[index] ? parseInt(totalCounts[index]) : 0;
            const vacant = sanctioned - total;
            
            totalSanctioned += sanctioned;
            totalMale += male;
            totalFemale += female;
            grandTotal += total;
            totalVacant += vacant;

            printContent += `
                <tr>
                    <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
                    <td class="bangla-text" style="text-align: left;">${designation.trim()}</td>
                    <td class="bangla-text">${convertGradeToBangla(grades[index] ? grades[index].trim() : '')}</td>
                    <td class="bangla-number">${englishToBanglaNumber(sanctioned)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(total)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(vacant)}</td>
                </tr>
            `;
        });

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
    } else {
        printContent += `
            <div class="alert alert-info text-center">
                <p class="bangla-text">এই রেকর্ডের জন্য কোন শ্রমিক ডাটা পাওয়া যায়নি</p>
            </div>
        `;
    }

    // Add signature section
    printContent += `
        <div class="row mt-5">
            <div class="col-md-6 text-center">
                <div style="border-top: 1px solid #000; width: 250px; margin: 0 auto; padding-top: 10px;">
                    <strong class="bangla-text">প্রস্তুতকারীর স্বাক্ষর</strong><br>
                    <small class="bangla-text">নাম ও পদবী</small>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div style="border-top: 1px solid #000; width: 250px; margin: 0 auto; padding-top: 10px;">
                    <strong class="bangla-text">দায়িত্বপ্রাপ্ত কর্মকর্তার স্বাক্ষর</strong><br>
                    <small class="bangla-text">নাম ও পদবী</small>
                </div>
            </div>
        </div>

        <div class="no-print text-center mt-4">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> প্রিন্ট করুন
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                <i class="fas fa-times me-1"></i> বন্ধ করুন
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

    const printWindow = window.open('', '_blank', 'width=900,height=700');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
}

// Utility functions for Bangla conversion
function englishToBanglaNumber(number) {
    const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return number.toString().replace(/\d/g, function(match) {
        return banglaNumbers[parseInt(match)];
    });
}

function convertDateToBangla(dateString) {
    const date = new Date(dateString);
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    
    return `${englishToBanglaNumber(day)}-${englishToBanglaNumber(month)}-${englishToBanglaNumber(year)}`;
}

function convertToBanglaMonth(englishMonth) {
    const months = {
        'January': 'জানুয়ারি',
        'February': 'ফেব্রুয়ারি',
        'March': 'মার্চ',
        'April': 'এপ্রিল',
        'May': 'মে',
        'June': 'জুন',
        'July': 'জুলাই',
        'August': 'আগস্ট',
        'September': 'সেপ্টেম্বর',
        'October': 'অক্টোবর',
        'November': 'নভেম্বর',
        'December': 'ডিসেম্বর'
    };
    
    // Extract month and year
    const [month, year] = englishMonth.split(' ');
    const banglaMonth = months[month] || month;
    const banglaYear = englishToBanglaNumber(year);
    
    return `${banglaMonth} ${banglaYear}`;
}

function convertGradeToBangla(grade) {
    const gradeMap = {
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
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>

</body>
</html>