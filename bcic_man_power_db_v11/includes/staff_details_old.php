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
$table = 'staffs_tbl';

// Handle actions
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM $table WHERE id = '$delete_id'";
    if ($conn->query($delete_sql)) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: staffs_details.php");
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
    header("Location: staffs_details.php");
    exit;
}

// Fetch all officers from all factories
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
   /* Add these print-specific styles */
    @media print {
        .no-print, .btn, .alert, .modal, .navbar, .month-print-btn, .print-btn, .combine-print-btn {
            display: none !important;
        }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white;
        }
        
        .print-section {
            display: block !important;
            visibility: visible !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Ensure table doesn't break inside */
        table {
            page-break-inside: auto !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }
        
        thead {
            display: table-header-group !important;
        }
        
        tfoot {
            display: table-footer-group !important;
        }
        
        /* Prevent header from breaking */
        h1, h2, h3, h4, h5, h6, .month-group-header {
            page-break-after: avoid !important;
        }
        
        /* Month group header stays with its content */
        .month-group-header {
            page-break-after: avoid !important;
            break-inside: avoid !important;
        }
        
        /* Each row stays together */
        tr {
            break-inside: avoid !important;
        }
        
        /* Container adjustments */
        .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Table header repeats on each page */
        thead {
            display: table-header-group;
        }
        
        /* Ensure content fits within page width */
        .table {
            width: 100% !important;
            table-layout: auto !important;
            word-wrap: break-word !important;
        }
        
        /* Reduce font size for print to fit more content */
        .table td, .table th {
            font-size: 10pt !important;
            padding: 4px !important;
        }
        
        /* Month group header styling for print */
        .month-group-header td {
            background-color: #e9ecef !important;
            font-weight: bold !important;
            page-break-after: avoid !important;
        }
        
        /* Force page breaks between months when needed */
        .month-group-header + tr + tr {
            page-break-before: avoid !important;
        }
    }
</style>
</head>
<body>

<div class="container mt-3 shadow rounded p-4">
  <h2>All Staffs From All Factory</h2>
  
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
    <!-- <a href="add_officer.php" class="btn btn-primary">
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
        <th>Total Staff</th>
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
          
          $total_staff = array_sum($total_counts);
          
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
            <td class="text-center"><strong><?php echo $total_staff; ?></strong></td>
            <td class="no-print">
              <div class="btn-group">
                <!-- View -->
                <button class="btn btn-info btn-sm" onclick="viewOfficer(<?php echo $row['id']; ?>)"
                        data-bs-toggle="tooltip" title="View">
                  <i class="fas fa-eye"></i>
                </button>
                
                <!-- Edit -->
                <a href="staffs_info.php?id=<?php echo $row['id']; ?>&factory_name=<?php echo urlencode($factory_name); ?>" class="btn btn-warning btn-sm"
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
                        onclick="printSingleRecord(<?php echo $row['id']; ?>)"
                        title="Print this record">
                  <i class="fas fa-print"></i> Print
                </button>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="text-center">No Staff found</td>
        </tr>
      <?php endif; ?>
    </tbody>
    <tfoot>
        <td colspan="8" class="text-center"><small>Design & Developed by ICT Division, BCIC.</small></td>
    </tfoot>
  </table>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Staff Details</h5>
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
        generateCombinePrintView(allData);
        
        // Re-enable button
        const $printBtn = $('.combine-print-btn:not(.month-print-btn)');
        $printBtn.prop('disabled', false).html('<i class="fas fa-copy"></i> Print All Combine');
        return;
    }

    const monthKey = monthKeys[index];
    const monthName = getMonthName(monthKey);
    
    $.ajax({
        url: 'get_combine_staff_data.php',
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
        url: 'get_combine_staff_data.php',
        type: 'POST',
        data: { 
            month_key: monthKey,
            action: 'get_combine_data'
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    generateCombinePrintView([{
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

// Print single record
function printSingleRecord(id, event) {
    const $printBtn = $(event.target).closest('button');
    const originalHtml = $printBtn.html();
    
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

    $.ajax({
        url: 'get_single_staff_data.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    generateSinglePrintView(response.data);
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
        printSingleRecord(id, e);
    });
});

// Function to generate combine print view with grade-wise combination in Bangla
function generateCombinePrintView(monthsData) {
    if (!monthsData || monthsData.length === 0) {
        alert('No data available for printing.');
        return;
    }

    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Combined Staff Report - Bangladesh Chemical Industries Corporation</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
              <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { 
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif; 
                    margin: 20px; 
                    direction: ltr;
                }
                .print-header { 
                    text-align: center; 
                    margin-bottom: 0px;
                    border-bottom: 0px solid #333;
                    padding-bottom: 0px;
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
                    padding: 5px; 
                    border-radius: 5px; 
                    margin-bottom: 10px;
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
                .grade-header {
                    background-color: #e3f2fd !important;
                }
                .bangla-number, .bangla-text {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-size: 14px;
                }
                @media print {
                    .no-print { display: none; }
                    .month-section { page-break-inside: avoid; }
                    body { margin: 0px; font-size: 12px; }
                    .summary-table { font-size: 12px; }

                    @page {
        size: Letter Portrait; /* Portrait মোডে বাধ্য করবে */
        margin: 10mm;      /* চাইলে মার্জিন সেট করতে পারো */
    }
                }
                .text-bangla {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                }
                .factory-details {
                    margin-top: 20px;
                    font-size: 12px;
                }
                .factory-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 10px;
                }
                .factory-table th, .factory-table td {
                    border: 1px solid #000;
                    padding: 5px;
                    text-align: left;
                    font-size: 11px;
                }
                .bengali-title {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-weight: bold;
                    font-size: 16px;
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

                /* Base Typography */
                * {
                  font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
                }

                 .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header">
                    <h3 class="text-bangla">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                    <h6 class="text-bangla">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h6>
                    <h5 class="text-bangla">কারখানা/প্রতিষ্ঠান নাম : সমন্বিত রিপোর্ট (কর্মচারী)</h5>
                    <h5 class="text-bangla">কর্মচারীদের বিদ্যমান জনবলের পরিসংখ্যান</h5>
                    <p class="bangla-text">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</p>
                </div>
    `;

    monthsData.forEach(monthData => {
        if (!monthData.data || monthData.data.length === 0) return;
        
        // Process all records for this month and combine by grade
        const gradeSummary = {};
        const factoryDetails = {};
        let totalSanctioned = 0;
        let totalMale = 0;
        let totalFemale = 0;
        let grandTotal = 0;
        let totalVacant = 0;

        // Initialize all grades
        const allGrades = ['Grade 11', 'Grade 12', 'Grade 13', 'Grade 14', 'Grade 15', 'Grade 16', 'Grade 17', 'Grade 18', 'Grade 19', 'Grade 20'];
        allGrades.forEach(grade => {
            gradeSummary[grade] = { 
                sanctioned: 0, 
                male: 0, 
                female: 0, 
                total: 0,
                vacant: 0
            };
        });

        // Process each record
        monthData.data.forEach(record => {
            const factoryName = record.factory_name;
            const designations = record.designation ? record.designation.split(',') : [];
            const grades = record.grade ? record.grade.split(',') : [];
            const sanctionedPosts = record.sanctioned_post ? record.sanctioned_post.split(',') : [];
            const maleCounts = record.male ? record.male.split(',') : [];
            const femaleCounts = record.female ? record.female.split(',') : [];
            const totalCounts = record.total ? record.total.split(',') : [];

            // Store factory details
            if (!factoryDetails[factoryName]) {
                factoryDetails[factoryName] = [];
            }

            // Process each designation in the record
            designations.forEach((designation, index) => {
                const grade = grades[index] ? grades[index].trim() : '';
                const sanctioned = sanctionedPosts[index] ? parseInt(sanctionedPosts[index]) : 0;
                const male = maleCounts[index] ? parseInt(maleCounts[index]) : 0;
                const female = femaleCounts[index] ? parseInt(femaleCounts[index]) : 0;
                const total = totalCounts[index] ? parseInt(totalCounts[index]) : 0;
                const vacant = sanctioned - total;

                // Add to grade summary
                if (grade && gradeSummary[grade]) {
                    gradeSummary[grade].sanctioned += sanctioned;
                    gradeSummary[grade].male += male;
                    gradeSummary[grade].female += female;
                    gradeSummary[grade].total += total;
                    gradeSummary[grade].vacant += vacant;
                }

                // Add to factory details
                factoryDetails[factoryName].push({
                    designation: designation.trim(),
                    grade: grade,
                    sanctioned: sanctioned,
                    male: male,
                    female: female,
                    total: total,
                    vacant: vacant,
                    date: record.date
                });

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
                    <h5 class="bangla-text">মাস: ${convertToBanglaMonth(monthData.month_name)}</h5>
                    <p class="bangla-text">মোট কারখানা: ${englishToBanglaNumber(Object.keys(factoryDetails).length)} | মোট রেকর্ড: ${englishToBanglaNumber(monthData.data.length)}</p>
                </div>

                <h4 class="bengali-title">গ্রেড ভিত্তিক সারসংক্ষেপ</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th class="grade-header bangla-text" width="8%">ক্রমিক</th>
                            <th class="grade-header bangla-text" width="20%">গ্রেড</th>
                            <th class="grade-header bangla-text" width="15%">অনুমোদিত পদ</th>
                            <th class="grade-header bangla-text" width="12%">পুরুষ</th>
                            <th class="grade-header bangla-text" width="12%">মহিলা</th>
                            <th class="grade-header bangla-text" width="12%">মোট</th>
                            <th class="grade-header bangla-text" width="15%">খালি পদ</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Add grade-wise summary rows
        let serial = 1;
        allGrades.forEach(grade => {
            const gradeData = gradeSummary[grade];
            if (gradeData.sanctioned > 0 || gradeData.total > 0) {
                printContent += `
                    <tr>
                        <td class="bangla-number">${englishToBanglaNumber(serial)}</td>
                        <td class="bangla-text">${convertGradeToBangla(grade)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(gradeData.sanctioned)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(gradeData.male)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(gradeData.female)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(gradeData.total)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(gradeData.vacant)}</td>
                    </tr>
                `;
                serial++;
            }
        });

        // Add grand total row
        printContent += `
                        <tr class="total-row">
                            <td class="bangla-text" colspan="2"><strong>সর্বমোট</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalSanctioned)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalMale)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalFemale)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalVacant)}</strong></td>
                        </tr>
                    </tbody>
                </table>
        `;

        // Add factory-wise details (collapsed view)
        printContent += `
                <div class="factory-details">
                    <h5 class="bengali-title">কারখানা ভিত্তিক বিস্তারিত</h5>
        `;

        Object.keys(factoryDetails).forEach(factoryName => {
            const details = factoryDetails[factoryName];
            let factorySanctionedTotal = 0;
            let factoryMaleTotal = 0;
            let factoryFemaleTotal = 0;
            let factoryTotal = 0;
            let factoryVacantTotal = 0;

            printContent += `
                <div style="margin-bottom: 15px;">
                    <table class="factory-table">
                        <thead>
                            <tr>
                                <th colspan="8" style="background: #f8f9fa; text-align: center;" class="bangla-text">
                                    <strong>কারখানা: ${factoryName}</strong>
                                </th>
                            </tr>
                            <tr>
                                <th width="5%" class="bangla-text">ক্রমিক</th>
                                <th width="30%" class="bangla-text">পদের নাম</th>
                                <th width="15%" class="bangla-text">গ্রেড</th>
                                <th width="12%" class="bangla-text">অনুমোদিত পদ</th>
                                <th width="10%" class="bangla-text">পুরুষ</th>
                                <th width="10%" class="bangla-text">মহিলা</th>
                                <th width="10%" class="bangla-text">মোট</th>
                                <th width="8%" class="bangla-text">খালি পদ</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            let factorySerial = 1;
            details.forEach(detail => {
                factorySanctionedTotal += detail.sanctioned;
                factoryMaleTotal += detail.male;
                factoryFemaleTotal += detail.female;
                factoryTotal += detail.total;
                factoryVacantTotal += detail.vacant;

                printContent += `
                    <tr>
                        <td class="bangla-number">${englishToBanglaNumber(factorySerial)}</td>
                        <td class="bangla-text">${detail.designation}</td>
                        <td class="bangla-text">${convertGradeToBangla(detail.grade)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.sanctioned)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.male)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.female)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.total)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.vacant)}</td>
                    </tr>
                `;
                factorySerial++;
            });

            printContent += `
                            <tr style="background: #f8f9fa;">
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
        });

        printContent += `
                </div>
                  <!-- Signature Section -->
        <div class="row mt-1">
           <div class="col-md-12 text-center">
                <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                    <strong>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="print-footer">
            <strong>Design & Developed by ICT Division, BCIC.</strong>
        </div>
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
                    <small class="text-muted bangla-number">
                প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}
            </small>
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
// ✅ Final version: Hide 'designation' column if empty
function generateSinglePrintView(data) {
    const isOnlyComma = (v) => v && v.replace(/,/g, '').trim() === '';

    const hasDesignation = data.designation && !isOnlyComma(data.designation);
    const hasGrade = data.grade && !isOnlyComma(data.grade);

    // Split CSV safely
    const designations = hasDesignation ? data.designation.split(',') : [];
    const grades = hasGrade ? data.grade.split(',') : [];
    const sanctionedPosts = data.sanctioned_post ? data.sanctioned_post.split(',') : [];
    const maleCounts = data.male ? data.male.split(',') : [];
    const femaleCounts = data.female ? data.female.split(',') : [];
    const totalCounts = data.total ? data.total.split(',') : [];

    let printContent = `
    <!DOCTYPE html>
    <html lang="bn">
    <head>
        <meta charset="UTF-8">
        <title>Staff Record - ${data.factory_name || ''}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
            body { font-family: 'Noto Sans Bengali', Arial, sans-serif; margin: 20px; }
            .print-header { text-align: center; margin-bottom: 0px; border-bottom: 0px solid #333; padding-bottom: 0px; }
            .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .detail-table th, .detail-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
            .detail-table th { background-color: #f8f9fa; width: 35%; }
            .staff-breakdown { margin-top: 30px; }
            .breakdown-table { width: 100%; border-collapse: collapse; }
            .breakdown-table th, .breakdown-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            .breakdown-table th { background-color: #f8f9fa; }
            .total-row { background-color: #e9ecef; font-weight: bold; }
            .designation-cell { text-align: left; font-weight: bold; background-color: #f8f9fa; }
            .bangla-text, .bangla-number { font-family: 'Noto Sans Bengali', Arial, sans-serif; }
            
            @media print {
                    .no-print { display: none; }
                    .month-section { page-break-inside: avoid; }
                    body { margin: 0px; font-size: 12px; }
                    .summary-table { font-size: 12px; }

                    @page {
        size: Letter portrait; /* Portrait মোডে বাধ্য করবে */
        margin: 10mm;      /* চাইলে মার্জিন সেট করতে পারো */
    }
                }

            .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="print-header">
                <h2 class="bangla-text">কর্মকর্তা/কর্মচারী বিস্তারিত</h2>
                <h4 class="bangla-text">কারখানা: ${data.factory_name || ''}</h4>
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
                    <th class="bangla-text">মোট কর্মী সংখ্যা</th>
                    <td class="bangla-number"><strong>${
                        data.total
                            ? englishToBanglaNumber(
                                  data.total.split(',').reduce(
                                      (sum, val) => sum + (parseInt(val.trim()) || 0),
                                      0
                                  )
                              )
                            : '০'
                    }</strong></td>
                </tr>
                <tr>
                    <th class="bangla-text">তৈরির তারিখ</th>
                    <td class="bangla-text">${
                        data.created_at ? convertDateToBangla(data.created_at.split(' ')[0]) : ''
                    }</td>
                </tr>
                <tr>
                    <th class="bangla-text">হালনাগাদের তারিখ</th>
                    <td class="bangla-text">${
                        data.updated_at ? convertDateToBangla(data.updated_at.split(' ')[0]) : ''
                    }</td>
                </tr>
            </table>
    `;

    if (designations.length > 0 || hasGrade) {
        printContent += `
        <div class="staff-breakdown">
            <h4 class="bangla-text">পদভিত্তিক কর্মী বিবরণ</h4>
            <table class="breakdown-table">
                <thead>
                    <tr>
                        <th class="bangla-text">ক্রমিক</th>
                        ${hasDesignation ? '<th class="designation-cell bangla-text">পদের নাম</th>' : ''}
                        ${hasGrade ? '<th class="bangla-text">গ্রেড</th>' : ''}
                        <th class="bangla-text">অনুমোদিত পদ</th>
                        <th class="bangla-text">পুরুষ</th>
                        <th class="bangla-text">মহিলা</th>
                        <th class="bangla-text">মোট</th>
                        <th class="bangla-text">খালি পদ</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let totalSanctioned = 0, totalMale = 0, totalFemale = 0, grandTotal = 0, totalVacant = 0;

        const maxLength = Math.max(
            designations.length,
            grades.length,
            sanctionedPosts.length,
            maleCounts.length,
            femaleCounts.length,
            totalCounts.length
        );

        for (let i = 0; i < maxLength; i++) {
            const designation = designations[i] ? designations[i].trim() : '';
            const grade = grades[i] ? grades[i].trim() : '';
            const sanctioned = parseInt(sanctionedPosts[i]) || 0;
            const male = parseInt(maleCounts[i]) || 0;
            const female = parseInt(femaleCounts[i]) || 0;
            const total = parseInt(totalCounts[i]) || 0;
            const vacant = sanctioned - total;

            totalSanctioned += sanctioned;
            totalMale += male;
            totalFemale += female;
            grandTotal += total;
            totalVacant += vacant;

            printContent += `
                <tr>
                    <td class="bangla-number">${englishToBanglaNumber(i + 1)}</td>
                    ${hasDesignation ? `<td class="bangla-text">${designation}</td>` : ''}
                    ${hasGrade ? `<td class="bangla-text">${convertGradeToBangla(grade)}</td>` : ''}
                    <td class="bangla-number">${englishToBanglaNumber(sanctioned)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(total)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(vacant)}</td>
                </tr>
            `;
        }

        // ✅ Auto colspan based on visible columns
        const colspan = 1 + (hasDesignation ? 1 : 0) + (hasGrade ? 1 : 0);

        printContent += `
            <tr class="total-row">
                <td colspan="${colspan}" class="bangla-text"><strong>মোট</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(totalSanctioned)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(totalMale)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(totalFemale)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(totalVacant)}</strong></td>
            </tr>
                </tbody>
            </table>
        </div>`;
    }

    printContent += `
       <!-- Signature Section -->
        <div class="row mt-3">
           <div class="col-md-12 text-center">
                <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                    <strong>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
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
            <small class="text-muted bangla-number">
                প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}
            </small>
        </div>
        </div>
    </body>
    </html>`;

    const printWindow = window.open('', '_blank', 'width=auto,height=auto,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
}


function viewOfficer(id) {
    $.ajax({
        url: 'view_officer.php',
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