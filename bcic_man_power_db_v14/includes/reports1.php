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
$factory_name = $_SESSION['factory_name'];

$months = [
    ['key' => '2026-05', 'name' => 'মে ২০২৬'],
    ['key' => '2026-04', 'name' => 'এপ্রিল ২০২৬'],
    ['key' => '2026-03', 'name' => 'মার্চ ২০২৬'],
];

$employee_types = [
    ['key' => 'officer', 'name' => 'কর্মকর্তা (Officer)'],
    ['key' => 'staff', 'name' => 'কর্মচারী (Staff)'],
    ['key' => 'worker', 'name' => 'শ্রমিক (Worker)'],
    ['key' => 'daily_ansar', 'name' => 'দৈনিক ভিত্তিক ও আনসার'],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCIC - অন-ডিমান্ড অনুসন্ধান ও প্রতিবেদন</title>
    <!-- CSS Assets -->
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
            display: none; /* Hidden on initialization */
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
    </style>
</head>
<body>

<div class="container my-5">
    <!-- Corporate Identity Header -->
    <div class="text-center mb-4">
    <h2 class="fw-bold text-dark mb-1">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (BCIC)</h2>
    <h5 class="text-secondary fw-semibold">সমন্বিত ড্যাশবোর্ড: অন-ডিমান্ড জনবল পরিসংখ্যান অনুসন্ধান ইঞ্জিন</h5>
    <!-- ADD THIS LINE -->
    <h5 class="text-primary fw-bold mt-2">বর্তমান প্রতিষ্ঠান: <?php echo htmlspecialchars($factory_name); ?></h5>
</div>

    <!-- Filter Control Panel Box -->
    <div class="card filter-card p-4 mb-4">
        <form id="searchFilterForm" onsubmit="executeReportSearch(event)">
            <div class="row g-3 align-items-end">
                <!-- Month Filter -->
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary"><i class="fa-solid fa-calendar-days me-1"></i> প্রতিবেদন মাস নির্বাচন করুন</label>
                    <select class="form-select form-select-lg" id="filterMonth" required>
                        <option value="" selected disabled>মাস বেছে নিন...</option>
                        <?php foreach ($months as $m): ?>
                            <option value="<?php echo $m['key']; ?>" data-name="<?php echo $m['name']; ?>"><?php echo $m['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Employee Type Filter -->
                <div class="col-md-5">
                    <label class="form-label fw-bold text-secondary"><i class="fa-solid fa-user-gear me-1"></i> জনবলের ধরণ (Employee Type)</label>
                    <select class="form-select form-select-lg" id="filterType" required>
                        <option value="" selected disabled>ক্যাটাগরি বেছে নিন...</option>
                        <?php foreach ($employee_types as $type): ?>
                            <option value="<?php echo $type['key']; ?>" data-name="<?php echo $type['name']; ?>"><?php echo $type['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submission Target Action -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" id="searchBtn">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> খুঁজুন
                    </button>
                    <a href="admin_dashboard.php" class="btn btn-primary">
      <i class="fas fa-arrow-left"></i> Back
    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Live Asynchronous Dynamic Data Result Window -->
    <div class="card data-card p-4" id="resultCard">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="m-0 text-dark fw-bold" id="renderReportTitle">পরিসংখ্যান ছক</h4>
                <small class="text-muted" id="renderReportMeta"></small>
            </div>
            <button class="btn btn-dark fw-bold px-4" onclick="triggerIsolatedPrint()">
                <i class="fa-solid fa-print me-2"></i> ডাটা প্রিন্ট করুন (Print Window)
            </button>
        </div>

        <!-- Render Target Segment for Table Content -->
        <div class="table-responsive" id="tableContainer">
            <!-- Programmatically populated by JS Engine -->
        </div>
    </div>
</div>

<!-- Core Dependency Script Layer -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
// Cache runtime global variables for reporting actions
let currentReportCache = {
    title: '',
    monthName: '',
    rawData: [],
    totalCount: 0,
    type: ''
};

/**
 * Universal conversion wrapper for numbers
 */
function englishToBanglaNumber(num) {
    if (num === null || num === undefined) return '০';
    const bDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
    return num.toString().split('').map(d => bDigits[d] || d).join('');
}

/**
 * Asynchronous Form Search Handler Pipeline
 */
function executeReportSearch(event) {
    event.preventDefault();

    const selectedMonth = $('#filterMonth').val();
    const selectedMonthName = $('#filterMonth option:selected').attr('data-name');
    const selectedType = $('#filterType').val();
    const selectedTypeName = $('#filterType option:selected').attr('data-name');

    // UI Loading State Switcher
    const $searchBtn = $('#searchBtn');
    $searchBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> লোড হচ্ছে...');
    $('#resultCard').hide();

    // Map the system key parameter to target endpoints natively
    let targetUrl = '';
    if (selectedType === 'officer') targetUrl = 'reports_get_combine_officer_data.php';
    else if (selectedType === 'staff') targetUrl = 'reports_get_combine_staff_data.php';
    else if (selectedType === 'worker') targetUrl = 'reports_get_combine_worker_data.php';
    else if (selectedType === 'daily_ansar') targetUrl = 'get_combine_daily_ansar_data.php';

    $.ajax({
        url: targetUrl,
        type: 'POST',
        data: {
            month_key: selectedMonth,
            action: 'get_' + selectedType + '_data'
        },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                
                if (response.success) {
                    // Update Global Document Print Context Cache Variables
                    currentReportCache.title = selectedTypeName;
                    currentReportCache.monthName = selectedMonthName;
                    currentReportCache.rawData = response.data;
                    currentReportCache.totalCount = response.records_count;
                    currentReportCache.type = selectedType;

                    // Render Document Grid layout structures inside local HTML DOM context
                    $('#renderReportTitle').text(`${selectedTypeName} - বিদ্যমান জনবল পরিসংখ্যান ছক`);
                    $('#renderReportMeta').html(`<i class="fa-regular fa-clock me-1"></i> প্রতিবেদন মাস: <b>${selectedMonthName}</b> | মোট রেকর্ড সীমা: <b>${englishToBanglaNumber(response.records_count)}</b> টি`);
                    
                    const dynamicTableHtml = renderHtmlTableTemplate(response.data, selectedType);
                    $('#tableContainer').html(dynamicTableHtml);
                    
                    // Show target results area instantly smoothly
                    $('#resultCard').fadeIn(300);
                } else {
                    alert('সার্ভার প্রসেসিং রেসপন্স ত্রুটি: ' + response.message);
                }
            } catch (err) {
                console.error("Data validation stack error:", err);
                alert('ডাটা প্রসেস করতে সমস্যা হয়েছে। কনসোল দেখুন।');
            }
            $searchBtn.prop('disabled', false).html('<i class="fa-solid fa-magnifying-glass me-2"></i> খুঁজুন');
        },
        error: function() {
            alert('সার্ভারের সাথে সংযোগ স্থাপন করা সম্ভব হয়নি।');
            $searchBtn.prop('disabled', false).html('<i class="fa-solid fa-magnifying-glass me-2"></i> খুঁজুন');
        }
    });
}

/**
 * Highly optimized client-side HTML layout generator loop
 */
/**
 * Highly optimized client-side HTML layout generator loop
 */
function renderHtmlTableTemplate(data, type) {
    let tbodyRows = '';
    
    if (data.length === 0) {
        return `<div class="alert alert-warning text-center fw-bold my-3"><i class="fa-solid fa-triangle-exclamation me-2"></i> দুঃখিত! এই মাস এবং ক্যাটাগরিতে কোনো ডাটা পাওয়া যায়নি।</div>`;
    }

    if (type === 'officer') {
        // Initialize running totals for each grade (Grade 2 to Grade 10)
        let totals = { g2: 0, g3: 0, g4: 0, g5: 0, g6: 0, g7: 0, g8: 0, g9: 0, g10: 0 };

        data.forEach((row, i) => {
            // Combine Male (_m) and Female (_f) numbers for each row safely
            let g2RowTotal  = parseInt(row.g2_m || 0)  + parseInt(row.g2_f || 0);
            let g3RowTotal  = parseInt(row.g3_m || 0)  + parseInt(row.g3_f || 0);
            let g4RowTotal  = parseInt(row.g4_m || 0)  + parseInt(row.g4_f || 0);
            let g5RowTotal  = parseInt(row.g5_m || 0)  + parseInt(row.g5_f || 0);
            let g6RowTotal  = parseInt(row.g6_m || 0)  + parseInt(row.g6_f || 0);
            let g7RowTotal  = parseInt(row.g7_m || 0)  + parseInt(row.g7_f || 0);
            let g8RowTotal  = parseInt(row.g8_m || 0)  + parseInt(row.g8_f || 0);
            let g9RowTotal  = parseInt(row.g9_m || 0)  + parseInt(row.g9_f || 0);
            let g10RowTotal = parseInt(row.g10_m || 0) + parseInt(row.g10_f || 0);

            // Accumulate global column totals
            totals.g2  += g2RowTotal;
            totals.g3  += g3RowTotal;
            totals.g4  += g4RowTotal;
            totals.g5  += g5RowTotal;
            totals.g6  += g6RowTotal;
            totals.g7  += g7RowTotal;
            totals.g8  += g8RowTotal;
            totals.g9  += g9RowTotal;
            totals.g10 += g10RowTotal;

            tbodyRows += `<tr>
                <td>${englishToBanglaNumber(i + 1)}</td>
                <td class="text-left-align">${row.factory_name || row.department || ''}</td>
                <td>${englishToBanglaNumber(g2RowTotal)}</td>
                <td>${englishToBanglaNumber(g3RowTotal)}</td>
                <td>${englishToBanglaNumber(g4RowTotal)}</td>
                <td>${englishToBanglaNumber(g5RowTotal)}</td>
                <td>${englishToBanglaNumber(g6RowTotal)}</td>
                <td>${englishToBanglaNumber(g7RowTotal)}</td>
                <td>${englishToBanglaNumber(g8RowTotal)}</td>
                <td>${englishToBanglaNumber(g9RowTotal)}</td>
                <td>${englishToBanglaNumber(g10RowTotal)}</td>
            </tr>`;
        });

        return `
            <table class="table table-bordered table-striped report-table m-0">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 5%;">ক্রমিক</th>
                        <th rowspan="2" style="width: 25%;">কারখানা/বিভাগের নাম</th>
                        <th colspan="9">কর্মকর্তাদের গ্রেড ভিত্তিক ডাটা (মোট জনবল)</th>
                    </tr>
                    <tr>
                        <th>গ্রেড-২</th>
                        <th>গ্রেড-৩</th>
                        <th>গ্রেড-৪</th>
                        <th>গ্রেড-৫</th>
                        <th>গ্রেড-৬</th>
                        <th>গ্রেড-৭</th>
                        <th>গ্রেড-৮</th>
                        <th>গ্রেড-৯</th>
                        <th>গ্রেড-১০</th>
                    </tr>
                </thead>
                <tbody>${tbodyRows}</tbody>
                <tfoot class="table-dark fw-bold">
                    <tr>
                        <td colspan="2">সর্বমোট কর্মকর্তা সংখ্যা</td>
                        <td>${englishToBanglaNumber(totals.g2)}</td>
                        <td>${englishToBanglaNumber(totals.g3)}</td>
                        <td>${englishToBanglaNumber(totals.g4)}</td>
                        <td>${englishToBanglaNumber(totals.g5)}</td>
                        <td>${englishToBanglaNumber(totals.g6)}</td>
                        <td>${englishToBanglaNumber(totals.g7)}</td>
                        <td>${englishToBanglaNumber(totals.g8)}</td>
                        <td>${englishToBanglaNumber(totals.g9)}</td>
                        <td>${englishToBanglaNumber(totals.g10)}</td>
                    </tr>
                </tfoot>
            </table>`;

    } else if (type === 'staff') {
    // Staff table with proper fields
    let total_male = 0, total_female = 0, total_all = 0;
    
    data.forEach((row, i) => {
        let male = parseInt(row.male) || 0;
        let female = parseInt(row.female) || 0;
        let total = parseInt(row.total) || 0;
        
        total_male += male;
        total_female += female;
        total_all += total;
        
        tbodyRows += `<tr>
            <td>${englishToBanglaNumber(i + 1)}</td>
            <td class="text-left-align">${row.designation || '-'}</td>
            <td>${row.grade || '-'}</td>
            <td>${englishToBanglaNumber(row.sanctioned_post || 0)}</td>
            <td>${englishToBanglaNumber(male)}</td>
            <td>${englishToBanglaNumber(female)}</td>
            <td>${englishToBanglaNumber(total)}</td>
        </tr>`;
    });
    
    return `
        <table class="table table-bordered table-striped report-table m-0">
            <thead>
                <tr>
                    <th style="width: 5%;">ক্রমিক</th>
                    <th style="width: 25%;">পদবি (Designation)</th>
                    <th style="width: 10%;">গ্রেড (Grade)</th>
                    <th style="width: 15%;">স্যান্কশনড পোস্ট</th>
                    <th style="width: 15%;">পুরুষ</th>
                    <th style="width: 15%;">মহিলা</th>
                    <th style="width: 15%;">মোট</th>
                </tr>
            </thead>
            <tbody>${tbodyRows}</tbody>
            <tfoot class="table-dark fw-bold">
                <tr>
                    <td colspan="4"><strong>সর্বমোট</strong></td>
                    <td><strong>${englishToBanglaNumber(total_male)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_female)}</strong></td>
                    <td><strong>${englishToBanglaNumber(total_all)}</strong></td>
                </tr>
            </tfoot>
        </tr>`;
    } else {
        let cat1Total = 0, cat2Total = 0;
        data.forEach((row, i) => {
            let val1 = parseInt(row.permanent || row.daily_rate_skilled || 0);
            let val2 = parseInt(row.temporary || row.ansar_guard || 0);
            cat1Total += val1;
            cat2Total += val2;

            tbodyRows += `<tr>
                <td>${englishToBanglaNumber(i + 1)}</td>
                <td class="text-left-align">${row.factory_name || row.enterprise_name || ''}</td>
                <td>${englishToBanglaNumber(val1)}</td>
                <td>${englishToBanglaNumber(val2)}</td>
            </tr>`;
        });

        return `
            <table class="table table-bordered table-striped report-table m-0">
                <thead>
                    <tr>
                        <th style="width: 8%;">ক্রমিক</th>
                        <th style="width: 52%;">কারখানা/প্রতিষ্ঠানের নাম</th>
                        <th>শ্রেণিবিভাগ ০১ (স্থায়ী/দক্ষ শ্রমবল)</th>
                        <th>শ্রেণিবিভাগ ০২ (অস্থায়ী/নিরাপত্তা বাহিনী)</th>
                    </tr>
                </thead>
                <tbody>${tbodyRows}</tbody>
                <tfoot class="table-dark fw-bold">
                    <tr>
                        <td colspan="2">সর্বমোট সমষ্টিগত পরিসংখ্যান</td>
                        <td>${englishToBanglaNumber(cat1Total)}</td>
                        <td>${englishToBanglaNumber(cat2Total)}</td>
                    </tr>
                </tfoot>
            </table>`;
    }
}
/**
 * High-fidelity Native OS Print Window Interface Injector
 */
function triggerIsolatedPrint() {
    // Generate an isolated sandboxed print canvas frame cleanly bypassing client viewport restrictions
    let printMarkup = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>${currentReportCache.title} - প্রিন্ট সংস্করণ</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;700&display=swap');
                body { font-family: 'Hind Siliguri', Arial, sans-serif; margin: 20px; color: #000; background-color: #fff; }
                .print-doc-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 25px; }
                .report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .report-table th, .report-table td { border: 1px solid #000 !important; padding: 6px 4px; text-align: center; vertical-align: middle; font-size: 13px; }
                .header-bg { background-color: #f2f3f5 !important; font-weight: bold; }
                .text-left-align { text-align: left !important; padding-left: 10px !important; }
                @media print {
                    .no-print-zone { display: none !important; }
                    @page { size: letter landscape; margin: 10mm; }
                    body { zoom: 0.82; }
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-doc-header">
                    <h3 class="fw-bold mb-1">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (BCIC)</h3>
                    <h5 class="mb-1">${currentReportCache.title} - বিদ্যমান জনবলের পরিসংখ্যান ছক</h5>
                    <p class="mb-0">প্রতিবেদন মাস: <b>${currentReportCache.monthName}</b> | মোট রেকর্ড: ${englishToBanglaNumber(currentReportCache.totalCount)} টি</p>
                </div>
                
                ${renderHtmlTableTemplate(currentReportCache.rawData, currentReportCache.type).replace(/table-dark/g, 'header-bg').replace(/table-striped/g, '')}
                
                <div class="no-print-zone text-center mt-5">
                    <button class="btn btn-dark btn-lg px-5" onclick="window.print()"><i class="fa fa-print me-2"></i> প্রিন্ট উইন্ডো চালু করুন</button>
                </div>
            </div>
        </body>
        </html>
    `;

    const cleanCanvasWindow = window.open('', '_blank');
    cleanCanvasWindow.document.write(printMarkup);
    cleanCanvasWindow.document.close();
}

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
                <th>ক্রমিক</th>
                <th>পদবি (Designation)</th>
                <th>গ্রেড (Grade)</th>
                <th>স্যান্কশনড পোস্ট</th>
                <th>পুরুষ</th>
                <th>মহিলা</th>
                <th>মোট</th>
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
</script>

</body>
</html>