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
$table = 'officers_tbl';

// Handle actions
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM $table WHERE id = '$delete_id'";
    if ($conn->query($delete_sql)) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: officer_details.php");
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
    header("Location: officer_details.php");
    exit;
}

// Fetch all officers from all factories
$sql = "SELECT * FROM $table ORDER BY date DESC, factory_name ASC";
$result = $conn->query($sql);

// Group records by month-year and factory for combine print functionality
$monthFactoryGroups = [];
$monthGroups = [];
if ($result && $result->num_rows > 0) {
    $result->data_seek(0); // Reset pointer
    while ($row = $result->fetch_assoc()) {
        $month_year = date("Y-m", strtotime($row['date']));
        $factory_name = $row['factory_name'];
        
        // Group by month for combine print
        if (!isset($monthGroups[$month_year])) {
            $monthGroups[$month_year] = [];
        }
        $monthGroups[$month_year][] = $row;
        
        // Group by month and factory for factory-wise combine print
        $key = $month_year . '|' . $factory_name;
        if (!isset($monthFactoryGroups[$key])) {
            $monthFactoryGroups[$key] = [];
        }
        $monthFactoryGroups[$key][] = $row;
    }
    $result->data_seek(0); // Reset pointer again for display
}

// Departments and Grades
$sections1 = [
    'Administration',
    'Security',
    'Technical (Production, Safety & Environment)',
    'Technical (Forest/FRM)',
    'Technical (Engineering-Mechanical)',
    'Technical (Engineering-Electrical/Instrument/Others)',
    'Technical (Engineering-Civil)',
    'Medical',
    'Commercial',
    'Accounts & Finance',
    'ICT',
    'Educational Institution-College',
    'Educational Institution-School',
    'Library'
];

$sections = [
      'প্রশাসন',
      'নিরাপত্তা',
      'কারিগরি (প্রোডাকশন, সেফটি এন্ড এনভায়রনমেন্ট)',
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

$grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g9', 'g10'];
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
    .factory-print-btn {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        border: none;
        color: white;
    }
    .factory-print-btn:hover {
        background: linear-gradient(135deg, #5a6268 0%, #545b62 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .month-group-header {
        background-color: #e9ecef !important;
        font-weight: bold;
    }
    .factory-group-header {
        background-color: #f8f9fa !important;
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
  <h2>All Officers From All Factory</h2>
  
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
    <a href="add_officer.php" class="btn btn-primary">
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
        <th>Created at</th>
        <th>Updated at</th>
        <th class="no-print">Actions</th>
        <th class="no-print">Print</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php 
        $current_month = '';
        $current_factory = '';
        $factory_record_count = 0;
        while ($row = $result->fetch_assoc()): 
          $month_year = date("F' Y", strtotime($row['date']));
          $month_key = date("Y-m", strtotime($row['date']));
          $factory_name = $row['factory_name'];

          
          // Check if this is a new month group
          if ($month_key !== $current_month):
            $current_month = $month_key;
            $month_record_count = count($monthGroups[$month_key]);
        ?>
          <tr class="month-group-header">
            <td colspan="8" class="text-center">
                <strong>Month: <?php echo $month_year; ?></strong> 
                <span class="badge bg-primary badge-count"><?php echo $month_record_count; ?> record<?php echo $month_record_count > 1 ? 's' : ''; ?></span>
                <button class="btn btn-sm combine-print-btn ms-3 month-print-btn" 
                        data-month-key="<?php echo $month_key; ?>" 
                        data-month-name="<?php echo $month_year; ?>">
                    <i class="fas fa-print"></i> Print Combine for <?php echo $month_year; ?>
                </button>
            </td>
          </tr>
        <?php 
            // Reset factory tracking for new month
            $current_factory = '';
            $factory_record_count = 0;
          endif; 
          
          // Check if this is a new factory within the same month
          if ($factory_name !== $current_factory):
            $current_factory = $factory_name;
            $factory_key = $month_key . '|' . $factory_name;
            $factory_record_count = isset($monthFactoryGroups[$factory_key]) ? count($monthFactoryGroups[$factory_key]) : 0;
        ?>
          <!-- <tr class="factory-group-header">
            <td colspan="8" class="text-center">
                <strong>Factory: <?php echo htmlspecialchars($factory_name); ?></strong> 
                <span class="badge bg-secondary badge-count"><?php echo $factory_record_count; ?> record<?php echo $factory_record_count > 1 ? 's' : ''; ?></span>
                <button class="btn btn-sm factory-print-btn ms-3 factory-print-btn" 
                        data-month-key="<?php echo $month_key; ?>" 
                        data-factory-name="<?php echo htmlspecialchars($factory_name); ?>"
                        data-month-name="<?php echo $month_year; ?>">
                    <i class="fas fa-industry"></i> Print Factory Combine for <?php echo $month_year; ?>
                </button>
            </td>
          </tr> -->
        <?php endif; ?>
        
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
            <td><?php echo $month_year; ?></td>
            <td><?php echo htmlspecialchars($factory_name); ?></td>
            <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
            <td><?php echo date('d-m-Y H:i', strtotime($row['updated_at'])); ?></td>
            <td class="no-print">
              <div class="btn-group">
                <!-- View -->
                <button class="btn btn-info btn-sm" onclick="viewOfficer(<?php echo $row['id']; ?>)"
                        data-bs-toggle="tooltip" title="View">
                  <i class="fas fa-eye"></i>
                </button>
                
               <!-- Edit -->
               <a href="officers_info.php?id=<?php echo $row['id']; ?>&factory_name=<?php echo urlencode($factory_name); ?>" class="btn btn-warning btn-sm"
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
                        data-id="<?php echo $row['id']; ?>" 
                        title="Print this record">
                  <i class="fas fa-print"></i> Print
                </button>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-center">No officers found</td>
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
        <h5 class="modal-title">Officer Details</h5>
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
    const $printBtn = $('.combine-print-btn:not(.month-print-btn):not(.factory-print-btn)');
    const originalHtml = $printBtn.html();
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading Combined Data...');

    // Process each month sequentially
    processMonthsForPrint(monthKeys, 0, []);
}

// Print factory-wise combine for specific month and factory
function printFactoryCombine(monthKey, factoryName, monthDisplayName, event = null) {
    let $printBtn;
    
    if (event) {
        $printBtn = $(event.target).closest('button');
    } else {
        $printBtn = $(`.factory-print-btn[data-month-key="${monthKey}"][data-factory-name="${factoryName}"]`);
    }
    
    const originalHtml = $printBtn.html();
    
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

    $.ajax({
        url: 'get_factory_combine_officer_data.php',
        type: 'POST',
        data: { 
            month_key: monthKey,
            factory_name: factoryName,
            action: 'get_factory_combine_data'
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    generateFactoryCombinePrintView({
                        month_key: monthKey,
                        month_name: monthDisplayName,
                        factory_name: factoryName,
                        data: response.data,
                        records_count: response.records_count
                    });
                } else {
                    alert('Error loading factory combine data: ' + (response.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Parsing error:', e);
                alert('Error parsing server response.');
            }
            
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function(xhr, status, error) {
            alert('Error loading factory combine data.');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

// Print combine for specific month
function printCombineMonth(monthKey, monthDisplayName, event = null) {
    let $printBtn;
    
    if (event) {
        $printBtn = $(event.target).closest('button');
    } else {
        $printBtn = $(`.month-print-btn[data-month-key="${monthKey}"]`);
    }
    
    const originalHtml = $printBtn.html();
    
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

    $.ajax({
        url: 'get_combine_officer_data.php',
        type: 'POST',
        data: { 
            month_key: monthKey,
            action: 'get_combine_data'
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    // Show combined result info
                    if (response.records_count > 1) {
                        console.log(`Combined ${response.records_count} records for ${response.month}`);
                    }
                    generateCombinePrintView([{
                        month_key: monthKey,
                        month_name: monthDisplayName,
                        data: response.data,
                        records_count: response.records_count,
                        month: response.month
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

// Print single record - REMOVED DUPLICATE
// Use the existing print button functionality below

// Process months for combine print
function processMonthsForPrint(monthKeys, index, allData) {
    if (index >= monthKeys.length) {
        generateCombinePrintView(allData);
        
        const $printBtn = $('.combine-print-btn:not(.month-print-btn):not(.factory-print-btn)');
        $printBtn.prop('disabled', false).html('<i class="fas fa-copy"></i> Print All Combine');
        return;
    }

    const monthKey = monthKeys[index];
    const monthName = getMonthName(monthKey);
    
    $.ajax({
        url: 'get_combine_officer_data.php',
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
                        data: response.data,
                        records_count: response.records_count,
                        month: response.month
                    });
                }
            } catch (e) {
                console.error('Parsing error for month ' + monthKey + ':', e);
            }
            
            processMonthsForPrint(monthKeys, index + 1, allData);
        },
        error: function(xhr, status, error) {
            console.error('Error loading data for month ' + monthKey + ':', error);
            processMonthsForPrint(monthKeys, index + 1, allData);
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
    // Individual print buttons - FIXED: Removed duplicate event handler
    $(document).on('click', '.print-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $printBtn = $(this);

        $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

        $.ajax({
            url: 'get_record.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                try {
                    if (typeof response !== 'object') response = JSON.parse(response);

                    if (response.success) {
                        // Show combined result info
                        if (response.records_count > 1) {
                            console.log(`Combined ${response.records_count} records for ${response.month}`);
                        }
                        generatePrintView(response.data, response.records_count, response.month);
                    } else {
                        alert('Error loading record for printing: ' + (response.message || 'Unknown error'));
                    }
                } catch (e) {
                    console.error('Parsing error:', e);
                    alert('Error parsing server response for printing.');
                }
                $printBtn.prop('disabled', false).html('<i class="fas fa-print"></i>');
            },
            error: function(xhr, status, error) {
                alert('Error loading record for printing.');
                $printBtn.prop('disabled', false).html('<i class="fas fa-print"></i>');
            }
        });
    });
    
    // Month-specific combine print buttons
    $(document).on('click', '.month-print-btn', function(e) {
        e.preventDefault();
        const monthKey = $(this).data('month-key');
        const monthName = $(this).data('month-name');
        printCombineMonth(monthKey, monthName, e);
    });
    
    // Factory-specific combine print buttons
    $(document).on('click', '.factory-print-btn', function(e) {
        e.preventDefault();
        const monthKey = $(this).data('month-key');
        const factoryName = $(this).data('factory-name');
        const monthName = $(this).data('month-name');
        printFactoryCombine(monthKey, factoryName, monthName, e);
    });
    
    // Main combine print button (Print All Combine)
    $(document).on('click', '.combine-print-btn:not(.month-print-btn):not(.factory-print-btn)', function(e) {
        e.preventDefault();
        printCombineRecords();
    });
});

// Function to generate factory combine print view for officers
function generateFactoryCombinePrintView(data) {
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Factory Combined Officer Report - ${data.factory_name}</title>
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
                    margin-bottom: 30px;
                    border-bottom: 1px solid #333;
                    padding-bottom: 20px;
                }
                .combined-info { 
                    background-color: #d4edda; 
                    border: 1px solid #c3e6cb; 
                    padding: 10px; 
                    margin-bottom: 15px; 
                    border-radius: 5px;
                    text-align: center;
                }
                .bangla-number, .bangla-text {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-size: 14px;
                }
                @media print {
                    .no-print { display: none; }
                    body { margin: 10px; font-size: 12px; }
                    @page {
                        size: Letter landscape; /* Portrait মোডে বাধ্য করবে */
                        margin: 10mm;      /* চাইলে মার্জিন সেট করতে পারো */
                    }
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
            </style>
        </head>
        <body>
            <div class="container-fluid">
    `;

    // Add combined records info
    if (data.records_count > 1) {
        printContent += `
            <div class="combined-info">
                <h5 class="mb-0 bangla-number">
                    <i class="fas fa-industry me-2"></i>
                    ${englishToBanglaNumber(data.records_count)}টি রেকর্ডের সমন্বিত ফলাফল (${convertToBanglaMonth(data.month_name)})
                </h5>
            </div>
        `;
    }

    // Create the table structure using the existing format
    printContent += `
                <div class="print-header text-center">
                    <h3 class="mb-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                    <h6 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h6>

                   <h5 class="mb-1">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : ${data.factory_name}</h5>

                    <h6 class="mb-0">বিভাগ ভিত্তিক কর্মকর্তাদের বিদ্যমান জনবলের পরিসংখ্যান : (${convertDateToBangla(data.month_name.split("'")[0].trim() + ' 01')} তারিখে)</h6>
                </div>
    `;

    // Now generate the actual officer data table
    // We need to combine all records for this factory in this month
    const sections = <?php echo json_encode($sections); ?>;
    const grades = <?php echo json_encode($grades); ?>;
    
    // Initialize data structure
    const combinedData = {
        date: data.month_name.split("'")[0].trim() + ' 01',
        factory_name: data.factory_name
    };
    
    // Initialize all grade columns
    grades.forEach(grade => {
        combinedData[grade + '_m'] = Array(sections.length).fill(0);
        combinedData[grade + '_f'] = Array(sections.length).fill(0);
    });
    
    // Combine all records
    data.data.forEach(record => {
        grades.forEach(grade => {
            if (record[grade + '_m']) {
                const values = record[grade + '_m'].split(',');
                values.forEach((value, index) => {
                    combinedData[grade + '_m'][index] += parseInt(value || 0);
                });
            }
            if (record[grade + '_f']) {
                const values = record[grade + '_f'].split(',');
                values.forEach((value, index) => {
                    combinedData[grade + '_f'][index] += parseInt(value || 0);
                });
            }
        });
    });
    
    // Convert arrays back to strings for the existing print function
    grades.forEach(grade => {
        combinedData[grade + '_m'] = combinedData[grade + '_m'].join(',');
        combinedData[grade + '_f'] = combinedData[grade + '_f'].join(',');
    });
    
    // Generate the table content
    printContent += generatePrintViewContent(combinedData, data.records_count, data.month_name);

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

// Wrapper function to generate print view content
function generatePrintViewContent(data, recordsCount = 1, month = '') {
    // Create print content
    let printContent = `
        <style>
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

             .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }
        </style>
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
    const grades = <?php echo json_encode($grades); ?>;
    grades.forEach(grade => {
        const gradeNum = grade.replace('g', '');
        printContent += `
            <th colspan="3" class="text-center">
                গ্রেড ${englishToBanglaNumber(gradeNum)}
            </th>
        `;
    });

    printContent += `
        <th colspan="3" class="text-center">সর্বমোট</th>
                </tr>
                <tr>
    `;

    // Add sub-headers for each grade
    grades.forEach(grade => {
        printContent += `
            <th class="male-col">পুরুষ</th>
            <th class="female-col">মহিলা</th>
            <th class="grade-total">মোট</th>
        `;
    });

    printContent += `
        <th class="male-col">পুরুষ</th>
        <th class="female-col">মহিলা</th>
        <th class="grade-total">মোট</th>
                </tr>
            </thead>
            <tbody>
    `;

    // Define sections array for JavaScript (Bengali version)
    const sections = <?php echo json_encode($sections); ?>;

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
    `;
    
    return printContent;
}

// Function to generate combine print view for officers
function generateCombinePrintView(monthsData) {
    if (!monthsData || monthsData.length === 0) {
        alert('No data available for printing.');
        return;
    }

    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Combined Officer Report - Bangladesh Chemical Industries Corporation</title>
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
                    margin-bottom: 10px;
                    border-bottom: 0px solid #333;
                    padding-bottom: 10px;
                }
                .month-section { 
                    margin-bottom: 20px; 
                    page-break-after: avoid; 
                }
                .month-section:last-child { 
                    page-break-after: avoid; 
                }
                .month-title { 
                    background: #f8f9fa; 
                    padding: 15px; 
                    border-radius: 5px; 
                    margin-bottom: 10px;
                    border-left: 4px solid #007bff;
                }
                .combined-info { 
                    background-color: #d4edda; 
                    border: 1px solid #c3e6cb; 
                    padding: 10px; 
                    margin-bottom: 15px; 
                    border-radius: 5px;
                    text-align: center;
                }
                .bangla-number, .bangla-text {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-size: 14px;
                }

                .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }
                @media print {
                    .no-print { display: none; }
                    .month-section { page-break-inside: avoid; }
                    body { margin: 10px; font-size: 12px; }
                @page {
                    size: Letter landscape; /* Portrait মোডে বাধ্য করবে */
                    margin: 10mm;      /* চাইলে মার্জিন সেট করতে পারো */
                }
                .bengali-title {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-weight: bold;
                    font-size: 16px;
                }
            }
               
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header">
                    <h3 class="text-bangla">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                    <h6 class="text-bangla">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h6>
                    <h5 class="text-bangla">সমস্ত কারখানার সমন্বিত রিপোর্ট</h5>
                    <h6 class="text-bangla">কর্মকর্তাদের বিভাগ ভিত্তিক বিদ্যমান জনবলের পরিসংখ্যান</h6>
                    <p class="bangla-text">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</p>
                    
                </div>
    `;

    monthsData.forEach(monthData => {
        if (!monthData.data || monthData.data.length === 0) return;
        
        // Process all records for this month and combine
        const combinedData = {
            date: monthData.month_name.split("'")[0].trim() + ' 01',
            factory_name: 'সমস্ত কারখানা'
        };
        
        const sections = <?php echo json_encode($sections); ?>;
        const grades = <?php echo json_encode($grades); ?>;
        
        // Initialize all grade columns
        grades.forEach(grade => {
            combinedData[grade + '_m'] = Array(sections.length).fill(0);
            combinedData[grade + '_f'] = Array(sections.length).fill(0);
        });
        
        // Combine all records for this month
        monthData.data.forEach(record => {
            grades.forEach(grade => {
                if (record[grade + '_m']) {
                    const values = record[grade + '_m'].split(',');
                    values.forEach((value, index) => {
                        combinedData[grade + '_m'][index] += parseInt(value || 0);
                    });
                }
                if (record[grade + '_f']) {
                    const values = record[grade + '_f'].split(',');
                    values.forEach((value, index) => {
                        combinedData[grade + '_f'][index] += parseInt(value || 0);
                    });
                }
            });
        });
        
        // Convert arrays back to strings
        grades.forEach(grade => {
            combinedData[grade + '_m'] = combinedData[grade + '_m'].join(',');
            combinedData[grade + '_f'] = combinedData[grade + '_f'].join(',');
        });

        printContent += `
            <div class="month-section ">
                <div class="month-title no-print">
                    <h3 class="bangla-text">মাস: ${convertToBanglaMonth(monthData.month_name)}</h3>
        `;
        
        // Add combined records info
        if (monthData.records_count > 1) {
            printContent += `
                <div class="combined-info no-print">
                    <h5 class="mb-0 bangla-number">
                        <i class="fas fa-info-circle me-2"></i>
                        ${englishToBanglaNumber(monthData.records_count)}টি রেকর্ডের সমন্বিত ফলাফল
                    </h5>
                </div>
            `;
        }
        
        printContent += `
                    <p class="bangla-text no-print">মোট কারখানা: ${englishToBanglaNumber(Object.keys(monthData.data.reduce((acc, record) => {
                        acc[record.factory_name] = true;
                        return acc;
                    }, {})).length)} | মোট রেকর্ড: ${englishToBanglaNumber(monthData.records_count)}</p>
                </div>
        `;
        
        // Generate the table for this month
        printContent += generatePrintViewContent(combinedData, monthData.records_count, monthData.month_name);
        
        printContent += `
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




// ✅ UPDATED: Function to generate print view with combined data
function generatePrintView(data, recordsCount = 1, month = '') {
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
                .combined-info { 
                    background-color: #d4edda; 
                    border: 1px solid #c3e6cb; 
                    padding: 10px; 
                    margin-bottom: 15px; 
                    border-radius: 5px;
                    text-align: center;
                }
                
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; }
                    .print-table { font-size: 10px; }
                    .print-header { margin: 10px; }
                     @page {
                    size: Letter landscape; /* Portrait মোডে বাধ্য করবে */
                    margin: 10mm;      /* চাইলে মার্জিন সেট করতে পারো */
                }
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

                .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }

                /* Base Typography */
                * {
                  font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
    `;
// <h4 class="mb-1">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : ${data.factory_name || ''} ff</h4>
    // ✅ NEW: Add combined records info
    if (recordsCount > 1) {
        printContent += `
            <div class="combined-info">
                <h5 class="mb-0 bangla-number">
                    <i class="fas fa-info-circle me-2"></i>
                    ${englishToBanglaNumber(recordsCount)}টি রেকর্ডের সমন্বিত ফলাফল (${month})
                </h5>
            </div>
        `;
    }

    printContent += `
                <div class="print-header text-center">
                    <h3 class="mb-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                    <h6 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h6>
                <h5 class="mb-1">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : ${data.factory_name}</h5>                  

                    <h6 class="mb-0">কর্মকর্তাদের বিভাগ ভিত্তিক বিদ্যমান জনবলের পরিসংখ্যান : (${convertDateToBangla(data.date)} তারিখে)</h6>
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
    const grades = <?php echo json_encode($grades); ?>;
    grades.forEach(grade => {
        const gradeNum = grade.replace('g', '');
        printContent += `
            <th colspan="3" class="text-center">
                গ্রেড ${englishToBanglaNumber(gradeNum)}
            </th>
        `;
    });

    printContent += `
        <th colspan="3" class="text-center">সর্বমোট</th>
                </tr>
                <tr>
    `;

    // Add sub-headers for each grade
    grades.forEach(grade => {
        printContent += `
            <th class="male-col">পুরুষ</th>
            <th class="female-col">মহিলা</th>
            <th class="grade-total">মোট</th>
        `;
    });

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
      'কারিগরি (প্রোডাকশন, সেফটি এন্ড এনভায়রনমেন্ট)',
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
            <td class="department-cell grand-total "><strong>সর্বমোট</strong></td>
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
        <div class="row mt-2">
           <div class="col-md-12 text-center">
                <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                    <strong>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</strong>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="print-footer">
            Design & Developed by ICT Division, BCIC.
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
    
    const [month, year] = englishMonth.split(' ');
    const banglaMonth = months[month] || month;
    const banglaYear = englishToBanglaNumber(year);
    
    return `${banglaMonth} ${banglaYear}`;
}

// Helper function for grade conversion (used in generateSinglePrintView)
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
        'Grade 20': 'গ্রেড ২০',
        'g2': 'গ্রেড ২',
        'g3': 'গ্রেড ৩',
        'g4': 'গ্রেড ৪',
        'g5': 'গ্রেড ৫',
        'g6': 'গ্রেড ৬',
        'g7': 'গ্রেড ৭',
        'g8': 'গ্রেড ৮',
        'g9': 'গ্রেড ৯',
        'g10': 'গ্রেড ১০'
    };
    
    return gradeMap[grade] || grade;
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