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
$factory_name = $_SESSION['factory_name'] ?? '';

// Dynamic months from database - get distinct months from all tables
$months = [];
$tables = ['officers_tbl', 'staffs_tbl', 'workers_tbl'];

foreach ($tables as $table) {
    $table_check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($table_check->num_rows > 0) {
        $sql = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month_key, 
                DATE_FORMAT(date, '%M %Y') as month_name 
                FROM $table 
                WHERE date IS NOT NULL 
                ORDER BY date DESC";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $month_key = $row['month_key'];
                $month_name = $row['month_name'];
                if (!isset($months[$month_key])) {
                    // Convert English month name to Bangla
                    $bangla_months = [
                        'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
                        'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
                        'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
                        'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
                    ];
                    foreach ($bangla_months as $eng => $ban) {
                        $month_name = str_replace($eng, $ban, $month_name);
                    }
                    $months[$month_key] = ['key' => $month_key, 'name' => $month_name];
                }
            }
        }
    }
}

// Sort months in descending order (newest first)
arsort($months);

// If no months found, add default current month
if (empty($months)) {
    $current_month_key = date('Y-m');
    $current_month_name = date('F Y');
    $bangla_months = [
        'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
        'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
        'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
        'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
    ];
    foreach ($bangla_months as $eng => $ban) {
        $current_month_name = str_replace($eng, $ban, $current_month_name);
    }
    $months[$current_month_key] = ['key' => $current_month_key, 'name' => $current_month_name];
}

$employee_types = [
    ['key' => 'officer', 'name' => 'কর্মকর্তা (Officer)'],
    ['key' => 'staff', 'name' => 'কর্মচারী (Staff)'],
    ['key' => 'worker', 'name' => 'শ্রমিক (Worker)'],
    ['key' => 'daily_basis', 'name' => 'দৈনিক ভিত্তিক'],
    ['key' => 'ansar', 'name' => 'আনসার'],
    ['key' => 'vacant_statistics', 'name' => 'শূণ্য পদ'],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCIC - অন-ডিমান্ড অনুসন্ধান ও প্রতিবেদন</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Hind Siliguri', 'SolaimanLipi', Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .filter-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            background: #ffffff;
        }
        .data-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            background: #ffffff;
            display: none;
        }
        .report-table th {
            background-color: #2c3e50;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
        }
        .report-table td {
            vertical-align: middle;
            text-align: center;
        }
        .text-left-align {
            text-align: left !important;
            padding-left: 15px !important;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 50px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-dark mb-1">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (BCIC)</h2>
        <h5 class="text-secondary fw-semibold">সমন্বিত ড্যাশবোর্ড: অন-ডিমান্ড জনবল পরিসংখ্যান অনুসন্ধান ইঞ্জিন</h5>
        <h5 class="text-primary fw-bold mt-2">বর্তমান প্রতিষ্ঠান: <?php echo htmlspecialchars($factory_name); ?></h5>
    </div>

    <!-- Filter Card -->
    <div class="card filter-card p-4 mb-4">
        <form id="searchFilterForm" onsubmit="executeReportSearch(event)">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary"><i class="fa-solid fa-calendar-days me-1"></i> প্রতিবেদন মাস নির্বাচন করুন</label>
                    <select class="form-select form-select-lg" id="filterMonth" required>
                        <option value="" selected disabled>মাস বেছে নিন...</option>
                        <?php foreach ($months as $m): ?>
                            <option value="<?php echo $m['key']; ?>" data-name="<?php echo $m['name']; ?>"><?php echo $m['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary"><i class="fa-solid fa-user-gear me-1"></i> জনবলের ধরণ (Employee Type)</label>
                    <select class="form-select form-select-lg" id="filterType" required>
                        <option value="" selected disabled>ক্যাটাগরি বেছে নিন...</option>
                        <?php foreach ($employee_types as $type): ?>
                            <option value="<?php echo $type['key']; ?>" data-name="<?php echo $type['name']; ?>"><?php echo $type['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" id="searchBtn">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> খুঁজুন
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary btn-lg w-100 mt-2">
                        <i class="fas fa-arrow-left"></i> ব্যাক
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 fw-bold">ডাটা লোড হচ্ছে, দয়া করে অপেক্ষা করুন...</p>
    </div>

    <!-- Result Card -->
    <div class="card data-card p-4" id="resultCard">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="m-0 text-dark fw-bold" id="renderReportTitle">পরিসংখ্যান ছক</h4>
                <small class="text-muted" id="renderReportMeta"></small>
            </div>
            <button class="btn btn-dark fw-bold px-4" onclick="triggerIsolatedPrint()" id="printBtn" style="display: none;">
                <i class="fa-solid fa-print me-2"></i> ডাটা প্রিন্ট করুন
            </button>
        </div>

        <div class="table-responsive" id="tableContainer">
            <!-- Populated by JavaScript -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
// Global variable to store current data
let currentData = {
    type: '',
    monthName: '',
    data: [],
    totalCount: 0
};

// Convert to Bangla numbers
function englishToBanglaNumber(num) {
    if (num === null || num === undefined || num === '') return '০';
    const banglaDigits = {
        '0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪',
        '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'
    };
    return num.toString().replace(/\d/g, function(digit) {
        return banglaDigits[digit];
    });
}

// Execute search
function executeReportSearch(event) {
    event.preventDefault();
    
    const selectedMonth = $('#filterMonth').val();
    const selectedMonthName = $('#filterMonth option:selected').text();
    const selectedType = $('#filterType').val();
    const selectedTypeName = $('#filterType option:selected').text();
    
    if (!selectedMonth || !selectedType) {
        alert('দয়া করে মাস এবং কর্মী ধরন নির্বাচন করুন!');
        return;
    }
    
    $('#loadingSpinner').show();
    $('#resultCard').hide();
    $('#printBtn').hide();
    
    const $searchBtn = $('#searchBtn');
    $searchBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> লোড হচ্ছে...');
    
    let targetUrl = '';
    if (selectedType === 'officer') targetUrl = 'reports_get_combine_officer_data.php';
    else if (selectedType === 'staff') targetUrl = 'reports_get_combine_staff_data.php';
    else if (selectedType === 'worker') targetUrl = 'reports_get_combine_worker_data.php';
    else if (selectedType === 'daily_basis') targetUrl = 'reports_get_combine_daily_basis_data.php';
    else if (selectedType === 'ansar') targetUrl = 'reports_get_combine_ansar_data.php';
    else if (selectedType === 'vacant_statistics') targetUrl = 'reports_get_combine_vacant_statistics_data.php';
    
    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: {
            month_key: selectedMonth,
            action: 'get_' + selectedType + '_data'
        },
        dataType: 'json',
        success: function(response) {
            $('#loadingSpinner').hide();
            
            if (response.success) {
                if (response.data && response.data.length > 0) {
                    currentData = {
                        type: selectedType,
                        monthName: selectedMonthName,
                        data: response.data,
                        totalCount: response.records_count
                    };
                    
                    $('#renderReportTitle').text(`${selectedTypeName} - বিদ্যমান জনবল পরিসংখ্যান ছক`);
                    $('#renderReportMeta').html(`
                        <i class="fa-regular fa-clock me-1"></i> 
                        প্রতিবেদন মাস: <b>${selectedMonthName}</b> | 
                        মোট রেকর্ড: <b>${englishToBanglaNumber(response.records_count)}</b> টি
                    `);
                    
                    const tableHtml = renderTable(response.data, selectedType);
                    $('#tableContainer').html(tableHtml);
                    $('#resultCard').fadeIn(300);
                    $('#printBtn').show();
                } else {
                    $('#tableContainer').html(`
                        <div class="alert alert-warning text-center py-5">
                            <i class="fa-solid fa-database fa-2x mb-3 d-block"></i>
                            <h5>দুঃখিত!</h5>
                            <p>এই মাস এবং ক্যাটাগরিতে কোনো ডাটা পাওয়া যায়নি।</p>
                            <small class="text-muted">চেক করা হয়েছে: ${selectedMonthName} - ${selectedTypeName}</small>
                        </div>
                    `);
                    $('#resultCard').fadeIn(300);
                    $('#printBtn').hide();
                }
            } else {
                alert('ত্রুটি: ' + (response.message || 'অজানা ত্রুটি'));
                $('#tableContainer').html(`
                    <div class="alert alert-danger">
                        <strong>Error:</strong> ${response.message || 'ডাটা লোড করতে ব্যর্থ হয়েছে'}
                    </div>
                `);
                $('#resultCard').fadeIn(300);
            }
            
            $searchBtn.prop('disabled', false).html('<i class="fa-solid fa-magnifying-glass me-2"></i> খুঁজুন');
        },
        error: function(xhr, status, error) {
            $('#loadingSpinner').hide();
            console.error('AJAX Error:', error);
            console.error('Response:', xhr.responseText);
            alert('সার্ভার ত্রুটি! ' + error);
            $searchBtn.prop('disabled', false).html('<i class="fa-solid fa-magnifying-glass me-2"></i> খুঁজুন');
        }
    });
}

// Render table based on type
function renderTable(data, type) {
    if (!data || data.length === 0) {
        return '<div class="alert alert-warning">কোনো ডাটা নেই</div>';
    }
    
    if (type === 'officer') {
        return renderOfficerTable(data);
    } else if (type === 'staff') {
        return renderStaffTable(data);
    } else if (type === 'worker') {
        return renderWorkerTable(data);
    } else if (type === 'daily_basis') {
        return renderDailyBasisTable(data);
    } else if (type === 'ansar') {
        return renderAnsarTable(data);
    } else if (type === 'vacant_statistics') {
        return renderVacantStatisticsTable(data);
    } else {
        return renderStaffTable(data);
    }
}
// Officer Table
function renderOfficerTable(data) {
    let rows = '';
    let totals = {
        g2: 0, g3: 0, g4: 0, g5: 0, 
        g6: 0, g7: 0, g8: 0, g9: 0, g10: 0
    };
    
    data.forEach((item, index) => {
        let g2 = (parseInt(item.g2_m) || 0) + (parseInt(item.g2_f) || 0);
        let g3 = (parseInt(item.g3_m) || 0) + (parseInt(item.g3_f) || 0);
        let g4 = (parseInt(item.g4_m) || 0) + (parseInt(item.g4_f) || 0);
        let g5 = (parseInt(item.g5_m) || 0) + (parseInt(item.g5_f) || 0);
        let g6 = (parseInt(item.g6_m) || 0) + (parseInt(item.g6_f) || 0);
        let g7 = (parseInt(item.g7_m) || 0) + (parseInt(item.g7_f) || 0);
        let g8 = (parseInt(item.g8_m) || 0) + (parseInt(item.g8_f) || 0);
        let g9 = (parseInt(item.g9_m) || 0) + (parseInt(item.g9_f) || 0);
        let g10 = (parseInt(item.g10_m) || 0) + (parseInt(item.g10_f) || 0);
        
        totals.g2 += g2;
        totals.g3 += g3;
        totals.g4 += g4;
        totals.g5 += g5;
        totals.g6 += g6;
        totals.g7 += g7;
        totals.g8 += g8;
        totals.g9 += g9;
        totals.g10 += g10;
        
        rows += `<tr>
            <td>${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${item.department || item.factory_name || '-'}</td>
            <td>${englishToBanglaNumber(g2)}</td>
            <td>${englishToBanglaNumber(g3)}</td>
            <td>${englishToBanglaNumber(g4)}</td>
            <td>${englishToBanglaNumber(g5)}</td>
            <td>${englishToBanglaNumber(g6)}</td>
            <td>${englishToBanglaNumber(g7)}</td>
            <td>${englishToBanglaNumber(g8)}</td>
            <td>${englishToBanglaNumber(g9)}</td>
            <td>${englishToBanglaNumber(g10)}</td>
        </tr>`;
    });
    
    return `<table class="table table-bordered table-striped report-table">
        <thead>
            <tr>
                <th rowspan="2" >ক্রমিক</th>
                <th rowspan="2" >বিভাগের নাম</th>
                <th colspan="9">কর্মকর্তাদের গ্রেড ভিত্তিক ডাটা (মোট জনবল)</th>
            </tr>
            <tr>
                <th>গ্রেড-২</th><th>গ্রেড-৩</th><th>গ্রেড-৪</th><th>গ্রেড-৫</th>
                <th>গ্রেড-৬</th><th>গ্রেড-৭</th><th>গ্রেড-৮</th><th>গ্রেড-৯</th><th>গ্রেড-১০</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="2"><strong>সর্বমোট কর্মকর্তা সংখ্যা</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g2)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g3)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g4)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g5)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g6)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g7)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g8)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g9)}</strong></td>
                <td><strong>${englishToBanglaNumber(totals.g10)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Staff Table
function renderStaffTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || 0;
        let female = parseInt(item.female) || 0;
        let total = parseInt(item.total) || 0;
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
            <td>${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${item.designation || '-'}</td>
            <td>${item.grade || '-'}</td>
            <td>${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
            <td>${englishToBanglaNumber(male)}</td>
            <td>${englishToBanglaNumber(female)}</td>
            <td>${englishToBanglaNumber(total)}</td>
        </tr>`;
    });
    
    return `<table class="table table-bordered table-striped report-table">
        <thead>
            <tr>
                <th style="width: 5%;">ক্রমিক</th>
                <th style="width: 25%;">পদবি (Designation)</th>
                <th style="width: 10%;">গ্রেড</th>
                <th style="width: 15%;">স্যান্কশনড পোস্ট</th>
                <th style="width: 15%;">পুরুষ</th>
                <th style="width: 15%;">মহিলা</th>
                <th style="width: 15%;">মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_all)}</strong></td>
            <tr>
        </tfoot>
    </table>`;
}
// Worker Table (same structure as staff)
function renderWorkerTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || 0;
        let female = parseInt(item.female) || 0;
        let total = parseInt(item.total) || 0;
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
            <td>${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${item.designation || item.category || '-'}</td>
            <td>${item.grade || '-'}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(male)}</td>
            <td class="bangla-number">${englishToBanglaNumber(female)}</td>
            <td class="bangla-number">${englishToBanglaNumber(total)}</td>
        </tr>`;
    });
    
    return `<table class="table table-bordered table-striped report-table">
        <thead>
            <tr>
                <th style="width: 5%;">ক্রমিক</th>
                <th style="width: 25%;">পদবি (Designation)</th>
                <th style="width: 10%;">গ্রেড</th>
                <th style="width: 15%;">স্যান্কশনড পোস্ট</th>
                <th style="width: 15%;">পুরুষ</th>
                <th style="width: 15%;">মহিলা</th>
                <th style="width: 15%;">মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Daily Basis Table
function renderDailyBasisTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || parseInt(item.category1) || 0;
        let female = parseInt(item.female) || parseInt(item.category2) || 0;
        let total = parseInt(item.total) || (male + female);
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
            <td>${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${item.designation || 'দৈনিক ভিত্তিক কর্মী'}</td>
            <td>${item.grade || '-'}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(male)}</td>
            <td class="bangla-number">${englishToBanglaNumber(female)}</td>
            <td class="bangla-number">${englishToBanglaNumber(total)}</td>
        </tr>`;
    });
    
    return `<table class="table table-bordered table-striped report-table">
        <thead>
            <tr>
                <th style="width: 5%;">ক্রমিক</th>
                <th style="width: 25%;">পদবি (Designation)</th>
                <th style="width: 10%;">গ্রেড</th>
                <th style="width: 15%;">স্যান্কশনড পোস্ট</th>
                <th style="width: 15%;">পুরুষ</th>
                <th style="width: 15%;">মহিলা</th>
                <th style="width: 15%;">মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    <tr>`;
}

// Ansar Table
function renderAnsarTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || parseInt(item.category1) || 0;
        let female = parseInt(item.female) || parseInt(item.category2) || 0;
        let total = parseInt(item.total) || (male + female);
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
            <td>${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${item.designation || 'আনসার সদস্য'}</td>
            <td>${item.grade || '-'}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(male)}</td>
            <td class="bangla-number">${englishToBanglaNumber(female)}</td>
            <td class="bangla-number">${englishToBanglaNumber(total)}</td>
        </tr>`;
    });
    
    return `<table class="table table-bordered table-striped report-table">
        <thead>
            <tr>
                <th style="width: 5%;">ক্রমিক</th>
                <th style="width: 25%;">পদবি (Designation)</th>
                <th style="width: 10%;">গ্রেড</th>
                <th style="width: 15%;">স্যান্কশনড পোস্ট</th>
                <th style="width: 15%;">পুরুষ</th>
                <th style="width: 15%;">মহিলা</th>
                <th style="width: 15%;">মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}
// Worker/Daily/Anser Table
function renderWorkerDailyTable(data, type) {
    let rows = '';
    let total_cat1 = 0, total_cat2 = 0;
    
    let category1Label = '', category2Label = '';
    
    if (type === 'worker') {
        category1Label = 'স্থায়ী শ্রমিক';
        category2Label = 'অস্থায়ী শ্রমিক';
    } else if (type === 'daily_basis') {
        category1Label = 'দৈনিক ভিত্তিক (দক্ষ)';
        category2Label = 'দৈনিক ভিত্তিক (অদক্ষ)';
    } else if (type === 'ansar') {
        category1Label = 'আনসার (স্থায়ী)';
        category2Label = 'আনসার (অস্থায়ী)';
    } else {
        category1Label = 'শ্রেণি-১';
        category2Label = 'শ্রেণি-২';
    }
    
    data.forEach((item, index) => {
        let cat1 = parseInt(item.category1) || parseInt(item.permanent) || parseInt(item.daily_rate_skilled) || 0;
        let cat2 = parseInt(item.category2) || parseInt(item.temporary) || parseInt(item.ansar_guard) || 0;
        
        total_cat1 += cat1;
        total_cat2 += cat2;
        
        rows += `<tr>
            <td>${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${item.factory_name || item.department || '-'}</td>
            <td>${englishToBanglaNumber(cat1)}</td>
            <td>${englishToBanglaNumber(cat2)}</td>
        </tr>`;
    });
    
    return `<table class="table table-bordered table-striped report-table">
        <thead>
            <tr>
                <th style="width: 8%;">ক্রমিক</th>
                <th style="width: 52%;">কারখানা/বিভাগের নাম</th>
                <th>${category1Label}</th>
                <th>${category2Label}</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="2"><strong>সর্বমোট</strong></td>
                <td><strong>${englishToBanglaNumber(total_cat1)}</strong></td>
                <td><strong>${englishToBanglaNumber(total_cat2)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Print function with professional format
function triggerIsolatedPrint() {
    if (!currentData.data || currentData.data.length === 0) {
        alert('প্রিন্ট করার মতো কোনো ডাটা নেই!');
        return;
    }
    
    // Get month name and format date
    const monthName = currentData.monthName;
    const currentDate = new Date().toISOString().split('T')[0];
    
    // Get factory name from session
    const factoryName = '<?php echo htmlspecialchars($factory_name); ?>';
    
    // For officer data, we need to combine multiple records into one combined data object
    let printData = {};
    
    if (currentData.type === 'officer') {
        // Combine all officer records
        const sections = [
            'প্রশাসন', 'নিরাপত্তা',
            'কারিগরি (প্রোডাকশন, সেফটি এন্ড এনভায়রনমেন্ট)',
            'কারিগরি (বন/এফআরএম)',
            'কারিগরি (ইঞ্জিনিয়ারিং - মেকানিক্যাল)',
            'কারিগরি (ইঞ্জিনিয়ারিং - ইলেকট্রিক্যাল/ইন্সট্রুমেন্ট/অন্যান্য)',
            'কারিগরি (ইঞ্জিনিয়ারিং - সিভিল)', 'চিকিৎসা', 'বাণিজ্যিক',
            'হিসাব ও অর্থ', 'আইসিটি', 'শিক্ষা প্রতিষ্ঠান - কলেজ',
            'শিক্ষা প্রতিষ্ঠান - স্কুল', 'লাইব্রেরি'
        ];
        
        const grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];
        
        // Initialize data structure
        printData = {
            factory_name: factoryName,
            date: monthName.split("'")[0].trim() + ' 01'
        };
        
        // Initialize all grade columns
        grades.forEach(grade => {
            printData[grade + '_m'] = Array(sections.length).fill(0);
            printData[grade + '_f'] = Array(sections.length).fill(0);
        });
        
        // Combine all records
        currentData.data.forEach(record => {
            grades.forEach(grade => {
                if (record[grade + '_m']) {
                    const values = record[grade + '_m'].split(',');
                    values.forEach((value, index) => {
                        printData[grade + '_m'][index] += parseInt(value || 0);
                    });
                }
                if (record[grade + '_f']) {
                    const values = record[grade + '_f'].split(',');
                    values.forEach((value, index) => {
                        printData[grade + '_f'][index] += parseInt(value || 0);
                    });
                }
            });
        });
        
        // Convert arrays back to strings
        grades.forEach(grade => {
            printData[grade + '_m'] = printData[grade + '_m'].join(',');
            printData[grade + '_f'] = printData[grade + '_f'].join(',');
        });
    } else if (currentData.type === 'staff') {
        printData = currentData.data[0];
        printData.factory_name = factoryName;
    } else if (currentData.type === 'worker') {
        printData = currentData.data[0];
        printData.factory_name = factoryName;
    } else if (currentData.type === 'daily_basis') {
        printData = currentData.data[0];
        printData.factory_name = factoryName;
    } else if (currentData.type === 'ansar') {
        printData = currentData.data[0];
        printData.factory_name = factoryName;
    } else if (currentData.type === 'vacant_statistics') {
        printData = currentData.data;
        printData.factory_name = factoryName;
    } else {
        printData = currentData.data[0];
        printData.factory_name = factoryName;
    }
    
    let titleText = '';
    if (currentData.type === 'officer') titleText = 'কর্মকর্তাদের বিভাগ ভিত্তিক বিদ্যমান জনবলের পরিসংখ্যান';
    else if (currentData.type === 'staff') titleText = 'কর্মচারীদের বিদ্যমান জনবলের পরিসংখ্যান';
    else if (currentData.type === 'worker') titleText = 'শ্রমিকদের বিদ্যমান জনবলের পরিসংখ্যান';
    else if (currentData.type === 'daily_basis') titleText = 'দৈনিক ভিত্তিক কর্মীদের বিদ্যমান জনবলের পরিসংখ্যান';
    else if (currentData.type === 'ansar') titleText = 'আনসার বাহিনীর বিদ্যমান জনবলের পরিসংখ্যান';
    else if (currentData.type === 'vacant_statistics') titleText = 'শূণ্য পদের পরিসংখ্যান';
    
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Employee Data Report</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
             <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif; margin: 20px; }
                .print-header { background: #f8f9fa; padding: 20px; border-bottom: 0px solid #dee2e6; margin-bottom: 0px; border-radius: 8px; text-align: center; }
                .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
                .print-table th, .print-table td { border: 1px solid #dee2e6; padding: 6px; text-align: center; }
                .print-table th { background-color: #e9ecef; font-weight: bold; }
                .department-cell { text-align: left; font-weight: bold; background-color: #f8f9fa !important; }
                .total-row { background-color: #e9ecef !important; font-weight: bold; }
                .male-col { background-color: #e3f2fd !important; }
                .female-col { background-color: #fce4ec !important; }
                .grade-total { background-color: #f5f5f5 !important; }
                .grand-total { background-color: #495057 !important; color: white !important; }
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
                    @page { size: landscape; margin: 10mm; }
                }
                .text-center { text-align: center; }
                .print-footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #dee2e6; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class="container-fluid">
    `;
    
    // Add combined records info if multiple records and not vacant statistics
    if (currentData.totalCount > 1 && currentData.type !== 'vacant_statistics') {
        printContent += `
            <div class="combined-info">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    ${englishToBanglaNumber(currentData.totalCount)}টি রেকর্ডের সমন্বিত ফলাফল (${monthName})
                </h5>
            </div>
        `;
    }
    
    printContent += `
                <div class="print-header">
                    <h3 class="mb-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
                    <h6 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h6>
                    <h5 class="mb-1">কারখানা/প্রতিষ্ঠানের নাম : ${factoryName}</h5>                  
                    <h6 class="mb-0">${titleText} : (${convertDateToBangla(printData.date || monthName)} তারিখে)</h6>
                </div>
    `;
    
    // Generate appropriate table based on type
    if (currentData.type === 'officer') {
        printContent += generateOfficerPrintTable(printData);
    } else if (currentData.type === 'staff') {
        printContent += generateStaffPrintTable(currentData.data);
    } else if (currentData.type === 'worker') {
        printContent += generateWorkerPrintTable(currentData.data);
    } else if (currentData.type === 'daily_basis') {
        printContent += generateDailyBasisPrintTable(currentData.data);
    } else if (currentData.type === 'ansar') {
        printContent += generateAnsarPrintTable(currentData.data);
    } else if (currentData.type === 'vacant_statistics') {
        printContent += generateVacantStatisticsPrintTable(currentData.data);
    } else {
        printContent += generateStaffPrintTable(currentData.data);
    }
    
    printContent += `
                <div class="print-footer">
                    Design & Developed by ICT Division, BCIC.<br>
                    <small>প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(currentDate)}</small>
                </div>
                <div class="mt-4 text-center no-print">
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-1"></i> প্রিন্ট করুন</button>
                    <button class="btn btn-secondary" onclick="window.close()"><i class="fas fa-times me-1"></i> বন্ধ করুন</button>
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

// Generate Worker Print Table
function generateWorkerPrintTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || 0;
        let female = parseInt(item.female) || 0;
        let total = parseInt(item.total) || (male + female);
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
                    <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
                    <td class="text-left-align">${item.designation || '-'}</td>
                    <td>${item.grade || '-'}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(total)}</td>
                </tr>`;
    });
    
    return `<table class="print-table">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>পদবি (Designation)</th>
                <th>গ্রেড</th>
                <th>স্যান্কশনড পোস্ট</th>
                <th>পুরুষ</th>
                <th>মহিলা</th>
                <th>মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    <table>`;
}

// Generate Daily Basis Print Table
function generateDailyBasisPrintTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || parseInt(item.category1) || 0;
        let female = parseInt(item.female) || parseInt(item.category2) || 0;
        let total = parseInt(item.total) || (male + female);
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
                    <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
                    <td class="text-left-align">${item.designation || 'দৈনিক ভিত্তিক কর্মী'}</td>
                    <td>${item.grade || '-'}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(total)}</td>
                </tr>`;
    });
    
    return `<table class="print-table">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>পদবি (Designation)</th>
                <th>গ্রেড</th>
                <th>স্যান্কশনড পোস্ট</th>
                <th>পুরুষ</th>
                <th>মহিলা</th>
                <th>মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Generate Ansar Print Table
function generateAnsarPrintTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || parseInt(item.category1) || 0;
        let female = parseInt(item.female) || parseInt(item.category2) || 0;
        let total = parseInt(item.total) || (male + female);
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
                    <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
                    <td class="text-left-align">${item.designation || 'আনসার সদস্য'}</td>
                    <td>${item.grade || '-'}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(total)}</td>
                </tr>`;
    });
    
    return `<table class="print-table">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>পদবি (Designation)</th>
                <th>গ্রেড</th>
                <th>স্যান্কশনড পোস্ট</th>
                <th>পুরুষ</th>
                <th>মহিলা</th>
                <th>মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Generate Officer Print Table
function generateOfficerPrintTable(data) {
    const sections = [
        'প্রশাসন', 'নিরাপত্তা',
        'কারিগরি (প্রোডাকশন, সেফটি এন্ড এনভায়রনমেন্ট)',
        'কারিগরি (বন/এফআরএম)',
        'কারিগরি (ইঞ্জিনিয়ারিং - মেকানিক্যাল)',
        'কারিগরি (ইঞ্জিনিয়ারিং - ইলেকট্রিক্যাল/ইন্সট্রুমেন্ট/অন্যান্য)',
        'কারিগরি (ইঞ্জিনিয়ারিং - সিভিল)', 'চিকিৎসা', 'বাণিজ্যিক',
        'হিসাব ও অর্থ', 'আইসিটি', 'শিক্ষা প্রতিষ্ঠান - কলেজ',
        'শিক্ষা প্রতিষ্ঠান - স্কুল', 'লাইব্রেরি'
    ];
    
    const grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];
    
    let tableContent = `
        <table class="print-table">
            <thead>
                <tr>
                    <th class="division-cell" rowspan="2">ক্রম</th>
                    <th class="department-cell" rowspan="2">বিভাগ/উপ-বিভাগ/শাখা</th>
    `;
    
    grades.forEach(grade => {
        const gradeNum = grade.replace('g', '');
        tableContent += `<th colspan="3" class="text-center">গ্রেড ${englishToBanglaNumber(gradeNum)}</th>`;
    });
    
    tableContent += `<th colspan="3" class="text-center">সর্বমোট</th></tr></tr>`;
    
    grades.forEach(() => {
        tableContent += `<th class="male-col">পুরুষ</th><th class="female-col">মহিলা</th><th class="grade-total">মোট</th>`;
    });
    
    tableContent += `<th class="male-col">পুরুষ</th><th class="female-col">মহিলা</th><th class="grade-total">মোট</th></tr></thead><tbody>`;
    
    let grandMaleTotal = 0, grandFemaleTotal = 0, grandTotal = 0;
    let serialNumber = 1;
    
    sections.forEach((section, index) => {
        let sectionMaleTotal = 0, sectionFemaleTotal = 0, sectionTotal = 0;
        
        tableContent += `<tr><td class="division-cell bangla-number">${englishToBanglaNumber(serialNumber)}</td><td class="department-cell">${section}</td>`;
        
        grades.forEach(grade => {
            const grade_m_values = data[grade + '_m'] ? data[grade + '_m'].split(',') : [];
            const grade_f_values = data[grade + '_f'] ? data[grade + '_f'].split(',') : [];
            const grade_m = parseInt(grade_m_values[index] || 0);
            const grade_f = parseInt(grade_f_values[index] || 0);
            const grade_total = grade_m + grade_f;
            
            sectionMaleTotal += grade_m;
            sectionFemaleTotal += grade_f;
            sectionTotal += grade_total;
            
            tableContent += `<td class="male-col bangla-number">${englishToBanglaNumber(grade_m)}</td>
                            <td class="female-col bangla-number">${englishToBanglaNumber(grade_f)}</td>
                            <td class="grade-total bangla-number">${englishToBanglaNumber(grade_total)}</td>`;
        });
        
        grandMaleTotal += sectionMaleTotal;
        grandFemaleTotal += sectionFemaleTotal;
        grandTotal += sectionTotal;
        
        tableContent += `<td class="male-col section-total bangla-number">${englishToBanglaNumber(sectionMaleTotal)}</td>
                        <td class="female-col section-total bangla-number">${englishToBanglaNumber(sectionFemaleTotal)}</td>
                        <td class="grade-total section-total bangla-number">${englishToBanglaNumber(sectionTotal)}</td></tr>`;
        
        serialNumber++;
    });
    
    tableContent += `<tr class="total-row"><td class="division-cell grand-total"></td><td class="department-cell grand-total"><strong>সর্বমোট</strong></td>`;
    
    grades.forEach(grade => {
        let gradeMaleTotal = 0, gradeFemaleTotal = 0;
        if (data[grade + '_m']) {
            gradeMaleTotal = data[grade + '_m'].split(',').reduce((sum, val) => sum + parseInt(val || 0), 0);
        }
        if (data[grade + '_f']) {
            gradeFemaleTotal = data[grade + '_f'].split(',').reduce((sum, val) => sum + parseInt(val || 0), 0);
        }
        const gradeTotal = gradeMaleTotal + gradeFemaleTotal;
        
        tableContent += `<td class="male-col grand-total bangla-number"><strong>${englishToBanglaNumber(gradeMaleTotal)}</strong></td>
                        <td class="female-col grand-total bangla-number"><strong>${englishToBanglaNumber(gradeFemaleTotal)}</strong></td>
                        <td class="grade-total grand-total bangla-number"><strong>${englishToBanglaNumber(gradeTotal)}</strong></td>`;
    });
    
    tableContent += `<td class="male-col grand-total bangla-number"><strong>${englishToBanglaNumber(grandMaleTotal)}</strong></td>
                    <td class="female-col grand-total bangla-number"><strong>${englishToBanglaNumber(grandFemaleTotal)}</strong></td>
                    <td class="grade-total grand-total bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td></tr>`;
    
    tableContent += `</tbody></table>`;
    return tableContent;
}

// Generate Staff Print Table
function generateStaffPrintTable(data) {
    let rows = '';
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((item, index) => {
        let male = parseInt(item.male) || 0;
        let female = parseInt(item.female) || 0;
        let total = parseInt(item.total) || 0;
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        rows += `<tr>
                    <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
                    <td class="text-left-align">${item.designation || '-'}</td>
                    <td>${item.grade || '-'}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned_post || 0)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(total)}</td>
                </tr>`;
    });
    
    return `<table class="print-table">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>পদবি (Designation)</th>
                <th>গ্রেড</th>
                <th>স্যান্কশনড পোস্ট</th>
                <th>পুরুষ</th>
                <th>মহিলা</th>
                <th>মোট</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>সর্বমোট</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_male)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_female)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_all)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Generate Worker/Daily/Ansar Print Table
function generateWorkerDailyPrintTable(data, type) {
    let rows = '';
    let total_cat1 = 0, total_cat2 = 0;
    
    let category1Label = '', category2Label = '';
    
    if (type === 'worker') {
        category1Label = 'স্থায়ী শ্রমিক';
        category2Label = 'অস্থায়ী শ্রমিক';
    } else if (type === 'daily_basis') {
        category1Label = 'দৈনিক ভিত্তিক (দক্ষ)';
        category2Label = 'দৈনিক ভিত্তিক (অদক্ষ)';
    } else if (type === 'ansar') {
        category1Label = 'আনসার (স্থায়ী)';
        category2Label = 'আনসার (অস্থায়ী)';
    } else {
        category1Label = 'শ্রেণি-১';
        category2Label = 'শ্রেণি-২';
    }
    
    data.forEach((item, index) => {
        let cat1 = parseInt(item.category1) || parseInt(item.permanent) || parseInt(item.daily_rate_skilled) || 0;
        let cat2 = parseInt(item.category2) || parseInt(item.temporary) || parseInt(item.ansar_guard) || 0;
        
        total_cat1 += cat1;
        total_cat2 += cat2;
        
        rows += `<tr>
                    <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
                    <td class="text-left-align">${item.factory_name || item.department || '-'}</td>
                    <td class="bangla-number">${englishToBanglaNumber(cat1)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(cat2)}</td>
                </tr>`;
    });
    
    return `<table class="print-table">
        <thead>
            <tr>
                <th>ক্রমিক</th>
                <th>কারখানা/বিভাগের নাম</th>
                <th>${category1Label}</th>
                <th>${category2Label}</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2"><strong>সর্বমোট</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_cat1)}</strong></td>
                <td class="bangla-number"><strong>${englishToBanglaNumber(total_cat2)}</strong></td>
            </tr>
        </tfoot>
    </table>`;
}

// Render Vacant Statistics Table
function renderVacantStatisticsTable(data) {
    if (!data || data.length === 0) {
        return '<div class="alert alert-warning">কোনো ডাটা নেই</div>';
    }
    
    let rows = '';
    let total_granted = 0, total_in_service = 0, total_vacant = 0;
    let total_eligible_promotion = 0, total_direct_recruit = 0;
    
    data.forEach((item, index) => {
        total_granted += item.granted_post || 0;
        total_in_service += item.in_service || 0;
        total_vacant += item.vacant || 0;
        total_eligible_promotion += item.eligible_promotion || 0;
        total_direct_recruit += item.direct_recruit || 0;
        
        rows += `<tr>
            <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${formatDateToBangla(item.entry_date)}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.granted_post || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.in_service || 0)}</td>
            <td class="bangla-number text-danger"><strong>${englishToBanglaNumber(item.vacant || 0)}</strong></td>
            <td class="bangla-number">${englishToBanglaNumber(item.eligible_promotion || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.direct_recruit || 0)}</td>
        </tr>`;
    });
    
    return `
        <table class="table table-bordered table-striped report-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ক্রমিক</th>
                    <th style="width: 20%;">তারিখ</th>
                    <th style="width: 15%;">স্যান্কশনড পদ</th>
                    <th style="width: 15%;">কর্মরত</th>
                    <th style="width: 15%;">শূণ্য পদ</th>
                    <th style="width: 15%;">পদোন্নতিযোগ্য</th>
                    <th style="width: 15%;">সরাসরি নিয়োগ</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
            <tfoot class="table-dark fw-bold">
                <tr>
                    <td colspan="2"><strong>সর্বমোট</strong></td>
                    <td><strong>${englishToBanglaNumber(total_granted)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_in_service)}</strong></td>
                    <td class="text-danger"><strong>${englishToBanglaNumber(total_vacant)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_eligible_promotion)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_direct_recruit)}</strong></td>
                </tr>
            </tfoot>
        </table>
    `;
}

// Generate Vacant Statistics Print Table
function generateVacantStatisticsPrintTable(data) {
    if (!data || data.length === 0) {
        return '<div class="alert alert-warning">কোনো ডাটা নেই</div>';
    }
    
    let rows = '';
    let total_granted = 0, total_in_service = 0, total_vacant = 0;
    let total_eligible_promotion = 0, total_direct_recruit = 0;
    
    data.forEach((item, index) => {
        total_granted += item.granted_post || 0;
        total_in_service += item.in_service || 0;
        total_vacant += item.vacant || 0;
        total_eligible_promotion += item.eligible_promotion || 0;
        total_direct_recruit += item.direct_recruit || 0;
        
        rows += `<tr>
            <td class="bangla-number">${englishToBanglaNumber(index + 1)}</td>
            <td class="text-left-align">${formatDateToBangla(item.entry_date)}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.granted_post || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.in_service || 0)}</td>
            <td class="bangla-number"><strong>${englishToBanglaNumber(item.vacant || 0)}</strong></td>
            <td class="bangla-number">${englishToBanglaNumber(item.eligible_promotion || 0)}</td>
            <td class="bangla-number">${englishToBanglaNumber(item.direct_recruit || 0)}</td>
        </tr>`;
    });
    
    return `
        <table class="print-table">
            <thead>
                <tr>
                    <th>ক্রমিক</th>
                    <th>তারিখ</th>
                    <th>স্যান্কশনড পদ</th>
                    <th>কর্মরত</th>
                    <th>শূণ্য পদ</th>
                    <th>পদোন্নতিযোগ্য</th>
                    <th>সরাসরি নিয়োগ</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2"><strong>সর্বমোট</strong></td>
                    <td><strong>${englishToBanglaNumber(total_granted)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_in_service)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_vacant)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_eligible_promotion)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_direct_recruit)}</strong></td>
                </tr>
            </tfoot>
        </table>
    `;
}
// Format date to Bangla
function formatDateToBangla(dateString) {
    if (!dateString) return '-';
    var date = new Date(dateString);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return englishToBanglaNumber(day) + '-' + englishToBanglaNumber(month) + '-' + englishToBanglaNumber(year);
}
// Date conversion function
function convertDateToBangla(dateString) {
    var date = new Date(dateString);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return englishToBanglaNumber(day) + '-' + englishToBanglaNumber(month) + '-' + englishToBanglaNumber(year);
}
</script>

</body>
</html>