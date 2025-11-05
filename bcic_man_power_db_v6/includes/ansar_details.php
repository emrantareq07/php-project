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
$is_admin = ($username === 'admin'); // Check if user is admin

$role = $_SESSION['role'] ?? ''; // ensure role exists
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
$sql = "SELECT * FROM $table ORDER BY date DESC ";
$result = $conn->query($sql);


// Departments and Grades
$sections1 = [
    'General Admin', 'Security', 'Medical', 'College', 'School', 'Library',
    'Accounts', 'ICT','Commercial', 'Production (Chemical)', 'Production (Chemist)', 
    'Engineering (Mechanical)', 'Engineering (Electrical + Instrument + Others)',
    'Engineering (Civil)', 'Forest/FRM'
];

$sections = [
    'সাধারণ প্রশাসন', 'নিরাপত্তা', 'চিকিৎসা', 'কলেজ', 'স্কুল', 'লাইব্রেরি',
    'হিসাব/অর্থ', 'আইসিটি','বাণিজ্যিক', 'প্রোডাকশন (কেমিক্যাল ইঞ্জিনিয়ারিং', 'প্রোডাকশন (কেমিস্ট)', 
    'ইঞ্জিনিয়ারিং (মেকানিক্যাল)', 'ইঞ্জিনিয়ারিং (ইলেকট্রিক্যাল + ইন্সট্রুমেন্ট + অন্যান্য)',
    'ইঞ্জিনিয়ারিং (সিভিল)', 'বন/এফআরএম'
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

  <div class="mb-3 no-print float-end">   
    <!-- <button class="btn btn-success" onclick="printAllRecords()">
      <i class="fas fa-print"></i> Print All
    </button>
    <a href="add_officer.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add New
    </a> -->
    <a href="admin_dashboard.php" class="btn btn-primary ">
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
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): 
          $month_year = date("F' Y", strtotime($row['date']));
          $factory_name=$row['factory_name'];
        ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
            <td><?php echo $month_year; ?></td>
            <td><?php echo htmlspecialchars($row['factory_name']); ?></td>
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
               <a href="ansar_info.php?id=<?php echo $row['id']; ?>&factory_name=<?php echo urlencode($row['factory_name']); ?>" class="btn btn-warning btn-sm"
                   data-bs-toggle="tooltip" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                                
                <!-- Print Single -->
                <button class="btn btn-info btn-sm print-btn" data-id="<?php echo $row['id']; ?>" title="Print">
                  <i class="fas fa-print"></i>
                </button>
                
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
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">No Workers found</td>
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

// Print button functionality
$(document).on('click', '.print-btn', function() {
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
                    generatePrintView(response.data);
                } else {
                    alert('Error loading record for printing: ' + (response.message || 'Unknown error'));
                }
            } catch (e) {
                console.error('Parsing error:', e);
                alert('Error parsing server response for printing.');
            }
        },
        error: function(xhr, status, error) {
            alert('Error loading record for printing.');
        },
        complete: function() {
            $printBtn.prop('disabled', false).html('<i class="fas fa-print"></i>');
        }
    });
});

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
                .division-cell { text-align: center; font-weight: bold; background-color: #f8f9fa !important; min-width: 100px; vertical-align: middle; }
                .total-row { background-color: #e9ecef !important; font-weight: bold; }
                .male-col { background-color: #e3f2fd !important; }
                .female-col { background-color: #fce4ec !important; }
                .grade-total { background-color: #f5f5f5 !important; }
                .section-total { background-color: #e9ecef !important; font-weight: bold; }
                .grand-total { background-color: #495057 !important; color: white !important; }
                
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
                    <h2 class="mb-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                    <h5 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h5>
                    <h4 class="mb-1">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : <?php echo $factory_name; ?></h4>
                    <h5 class="mb-0">বিদ্যমান জনবলের পরিসংখ্যান : (${convertDateToBangla(data.date)} তারিখে)</h5>
                </div>
    `;

    // Create the table structure
    printContent += `
        <table class="print-table">
            <thead>
                <tr>
                    <th class="division-cell" rowspan="2">বিভাগ</th>
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
        'সাধারণ প্রশাসন', 'নিরাপত্তা', 'চিকিৎসা', 'কলেজ', 'স্কুল', 'লাইব্রেরি',
        'হিসাব/অর্থ', 'আইসিটি', 'বাণিজ্যিক', 'প্রোডাকশন (কেমিক্যাল ইঞ্জিনিয়ারিং)', 'প্রোডাকশন (কেমিস্ট)', 
        'ইঞ্জিনিয়ারিং (মেকানিক্যাল)', 'ইঞ্জিনিয়ারিং (ইলেকট্রিক্যাল + ইন্সট্রুমেন্ট + অন্যান্য)',
        'ইঞ্জিনিয়ারিং (সিভিল)', 'বন/এফআরএম'
    ];

    const grades = <?php echo json_encode($grades); ?>;

    // Define division mapping for Bengali sections
    const divisionMap = {
        // প্রশাসন বিভাগ
        'সাধারণ প্রশাসন': 'প্রশাসন',
        'নিরাপত্তা': 'প্রশাসন',
        'চিকিৎসা': 'প্রশাসন',
        'কলেজ': 'প্রশাসন',
        'স্কুল': 'প্রশাসন',
        'লাইব্রেরি': 'প্রশাসন',
        
        // হিসাব ও অর্থ বিভাগ
        'হিসাব/অর্থ': 'হিসাব ও অর্থ',
        'আইসিটি': 'হিসাব ও অর্থ',
        
        // বাণিজ্যিক বিভাগ
        'বাণিজ্যিক': 'বাণিজ্যিক',
        
        // উৎপাদন বিভাগ
        'প্রোডাকশন (কেমিক্যাল ইঞ্জিনিয়ারিং)': 'উৎপাদন',
        'প্রোডাকশন (কেমিস্ট)': 'উৎপাদন',
        
        // প্রকৌশল বিভাগ
        'ইঞ্জিনিয়ারিং (মেকানিক্যাল)': 'প্রকৌশল',
        'ইঞ্জিনিয়ারিং (ইলেকট্রিক্যাল + ইন্সট্রুমেন্ট + অন্যান্য)': 'প্রকৌশল',
        'ইঞ্জিনিয়ারিং (সিভিল)': 'প্রকৌশল',
        
        // বন বিভাগ
        'বন/এফআরএম': 'বন'
    };

    // Calculate rowspan for each division
    const divisionCounts = {};
    sections.forEach(section => {
        const division = divisionMap[section];
        divisionCounts[division] = (divisionCounts[division] || 0) + 1;
    });

    // Add data rows with Bangla numbers and rowspan
    let grandMaleTotal = 0;
    let grandFemaleTotal = 0;
    let grandTotal = 0;

    let currentDivision = '';
    let divisionRowspan = 0;
    let divisionRowIndex = 0;

    sections.forEach((section, index) => {
        const division = divisionMap[section];
        
        // Check if we're starting a new division
        if (division !== currentDivision) {
            currentDivision = division;
            divisionRowspan = divisionCounts[division];
            divisionRowIndex = 0;
        }

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

        // Add row with rowspan for first row of each division
        if (divisionRowIndex === 0) {
            printContent += `
                <tr>
                    <td class="division-cell" rowspan="${divisionRowspan}">${division}</td>
                    <td class="department-cell">${section}</td>
            `;
        } else {
            printContent += `
                <tr>
                    <td class="department-cell">${section}</td>
            `;
        }

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

        divisionRowIndex++;
    });

    // Add grand totals row with Bangla numbers
    printContent += `
        <tr class="total-row">
            <td class="division-cell grand-total" colspan="2"><strong>সর্বমোট</strong></td>
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