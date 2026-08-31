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

// Mock Data for the demonstration of UI rendering. Replace this with your actual SQL queries.
$months = [
    ['month_key' => '2026-05', 'month_name' => 'মে ২০২৬'],
    ['month_key' => '2026-04', 'month_name' => 'এপ্রিল ২০২৬'],
    ['month_key' => '2026-03', 'month_name' => 'মার্চ ২০২৬'],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCIC - প্রতিবেদন ও পরিসংখ্যান</title>
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Hind Siliguri', 'SolaimanLipi', Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .main-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .table th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
            text-align: center;
        }
        .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn-action {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="container my-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase text-dark mb-2">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (BCIC)</h2>
        <h5 class="text-secondary">বিদ্যমান জনবলের পরিসংখ্যান ও সমন্বিত প্রতিবেদন উইন্ডো</h5>
    </div>

    <!-- Main Controller Card -->
    <div class="card main-card p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h4 class="m-0 text-primary fw-bold"><i class="fa-solid fa-file-invoice me-2"></i>মাসিক ভিত্তিক প্রতিবেদন তালিকা</h4>
            <button class="btn btn-success fw-bold" onclick="printAllMonths()">
                <i class="fa-solid fa-print me-2"></i>সব মাসের সকল ক্যাটাগরি প্রিন্ট করুন
            </button>
        </div>

        <!-- Reports Grid Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover m-0">
                <thead>
                    <tr>
                        <th style="width: 10%;">ক্রমিক</th>
                        <th style="width: 25%;">প্রতিবেদন মাস</th>
                        <th style="width: 65%;">রিপোর্ট অ্যাকশন সমূহ (ক্যাটাগরি ভিত্তিক)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($months as $index => $month): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td class="fw-bold text-start ps-4"><?php echo $month['month_name']; ?></td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <!-- Officer Report Button -->
                                    <button class="btn btn-outline-primary btn-action officer-print-btn" 
                                            data-month-key="<?php echo $month['month_key']; ?>"
                                            onclick="printOfficerReport('<?php echo $month['month_key']; ?>', '<?php echo $month['month_name']; ?>', event)">
                                        <i class="fa-solid fa-user-tie me-1"></i> কর্মকর্তা (Officer)
                                    </button>

                                    <!-- Staff Report Button -->
                                    <button class="btn btn-outline-info btn-action staff-print-btn" 
                                            data-month-key="<?php echo $month['month_key']; ?>"
                                            onclick="printStaffReport('<?php echo $month['month_key']; ?>', '<?php echo $month['month_name']; ?>', event)">
                                        <i class="fa-solid fa-users me-1"></i> কর্মচারী (Staff)
                                    </button>

                                    <!-- Worker Report Button -->
                                    <button class="btn btn-outline-warning btn-action worker-print-btn" 
                                            data-month-key="<?php echo $month['month_key']; ?>"
                                            onclick="printWorkerReport('<?php echo $month['month_key']; ?>', '<?php echo $month['month_name']; ?>', event)">
                                        <i class="fa-solid fa-helmet-safety me-1"></i> শ্রমিক (Worker)
                                    </button>

                                    <!-- Daily/Ansar Report Button -->
                                    <button class="btn btn-outline-secondary btn-action daily-print-btn" 
                                            data-month-key="<?php echo $month['month_key']; ?>"
                                            onclick="printDailyAnsarReport('<?php echo $month['month_key']; ?>', '<?php echo $month['month_name']; ?>', event)">
                                        <i class="fa-solid fa-clock-history me-1"></i> দৈনিক ভিত্তিক ও আনসার
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endphp; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS Dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/**
 * Global Helper: Convert English integers/strings to Bengali representations
 */
function englishToBanglaNumber(num) {
    if (num === null || num === undefined) return '০';
    const banglaDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
    return num.toString().split('').map(digit => banglaDigits[digit] || digit).join('');
}

/**
 * 1. Print Officer Report
 */
function printOfficerReport(monthKey, monthDisplayName, event = null) {
    let $printBtn = event ? $(event.target).closest('button') : $(`.officer-print-btn[data-month-key="${monthKey}"]`);
    const originalHtml = $printBtn.html();
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> প্রসেসিং...');

    $.ajax({
        url: 'get_combine_officer_data.php', 
        type: 'POST',
        data: { month_key: monthKey, action: 'get_officer_data' },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                if (response.success) {
                    generatePrintWindow("কর্মকর্তা (Officer)", monthDisplayName, response.data, response.records_count, 'officer');
                } else {
                    alert('ত্রুটি: ' + response.message);
                }
            } catch (e) { console.error('Parsing error:', e); }
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function() {
            alert('সার্ভার কানেকশন ব্যর্থ হয়েছে।');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

/**
 * 2. Print Staff Report
 */
function printStaffReport(monthKey, monthDisplayName, event = null) {
    let $printBtn = event ? $(event.target).closest('button') : $(`.staff-print-btn[data-month-key="${monthKey}"]`);
    const originalHtml = $printBtn.html();
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> প্রসেসিং...');

    $.ajax({
        url: 'get_combine_staff_data.php',
        type: 'POST',
        data: { month_key: monthKey, action: 'get_staff_data' },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                if (response.success) {
                    generatePrintWindow("কর্মচারী (Staff)", monthDisplayName, response.data, response.records_count, 'staff');
                } else {
                    alert('ত্রুটি: ' + response.message);
                }
            } catch (e) { console.error('Parsing error:', e); }
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function() {
            alert('সার্ভার কানেকশন ব্যর্থ হয়েছে।');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

/**
 * 3. Print Worker Report
 */
function printWorkerReport(monthKey, monthDisplayName, event = null) {
    let $printBtn = event ? $(event.target).closest('button') : $(`.worker-print-btn[data-month-key="${monthKey}"]`);
    const originalHtml = $printBtn.html();
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> প্রসেসিং...');

    $.ajax({
        url: 'get_combine_worker_data.php',
        type: 'POST',
        data: { month_key: monthKey, action: 'get_worker_data' },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                if (response.success) {
                    generatePrintWindow("শ্রমিক (Worker)", monthDisplayName, response.data, response.records_count, 'worker');
                } else {
                    alert('ত্রুটি: ' + response.message);
                }
            } catch (e) { console.error('Parsing error:', e); }
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function() {
            alert('সার্ভার কানেকশন ব্যর্থ হয়েছে।');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

/**
 * 4. Print Daily Basis & Ansar Report
 */
function printDailyAnsarReport(monthKey, monthDisplayName, event = null) {
    let $printBtn = event ? $(event.target).closest('button') : $(`.daily-print-btn[data-month-key="${monthKey}"]`);
    const originalHtml = $printBtn.html();
    $printBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> প্রসেসিং...');

    $.ajax({
        url: 'get_combine_daily_ansar_data.php',
        type: 'POST',
        data: { month_key: monthKey, action: 'get_daily_ansar_data' },
        success: function(response) {
            try {
                if (typeof response !== 'object') response = JSON.parse(response);
                if (response.success) {
                    generatePrintWindow("দৈনিক ভিত্তিক ও আনসার", monthDisplayName, response.data, response.records_count, 'daily_ansar');
                } else {
                    alert('ত্রুটি: ' + response.message);
                }
            } catch (e) { console.error('Parsing error:', e); }
            $printBtn.prop('disabled', false).html(originalHtml);
        },
        error: function() {
            alert('সার্ভার কানেকশন ব্যর্থ হয়েছে।');
            $printBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

/**
 * Dynamic Universal Print Document Window Builder
 */
function generatePrintWindow(reportTitle, monthName, rawData, totalCount, type) {
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>${reportTitle} সমন্বিত প্রতিবেদন</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;700&display=swap');
                body { font-family: 'Hind Siliguri', 'Nikosh', sans-serif; margin: 15px; color: #000; }
                .print-header { text-align: center; margin-bottom: 25px; border-bottom: 3px double #000; padding-bottom: 12px; }
                .report-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 15px; }
                .report-table th, .report-table td { border: 1px solid #000 !important; padding: 6px 4px; text-align: center; vertical-align: middle; }
                .header-bg { background-color: #f1f2f6 !important; font-weight: bold; }
                .text-left-align { text-align: left !important; padding-left: 8px !important; }
                @media print {
                    .no-print { display: none !important; }
                    @page { size: letter landscape; margin: 8mm; }
                    body { zoom: 0.78; }
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header">
                    <h3 class="fw-bold mb-1">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (BCIC)</h3>
                    <h5 class="mb-1">${reportTitle} বিদ্যমান জনবলের পরিসংখ্যান</h5>
                    <p class="mb-0">প্রতিবেদন মাস: <strong>${monthName}</strong> | মোট রেকর্ড সংখ্যা: ${englishToBanglaNumber(totalCount)}টি</p>
                </div>
                
                ${buildReportTableStructure(rawData, type)}
                
                <div class="no-print text-center mt-5">
                    <button class="btn btn-lg btn-dark px-5" onclick="window.print()"><i class="fa fa-print me-2"></i>প্রিন্ট ডক নিন</button>
                </div>
            </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
}

/**
 * Structural Router Matrix matching your structural database mapping
 */
function buildReportTableStructure(data, type) {
    let rowsHtml = '';
    
    if (type === 'officer') {
        // Formulate Officer Table Structure (Grades 1 to 10)
        let totalG1=0, totalG2=0, totalG10=0;
        data.forEach((item, index) => {
            totalG1 += parseInt(item.g1 || 0);
            totalG2 += parseInt(item.g2 || 0);
            totalG10 += parseInt(item.g10 || 0);
            
            rowsHtml += `<tr>
                <td>${englishToBanglaNumber(index + 1)}</td>
                <td class="text-left-align">${item.enterprise_name || ''}</td>
                <td>${englishToBanglaNumber(item.g1 || 0)}</td>
                <td>${englishToBanglaNumber(item.g2 || 0)}</td>
                <td>${englishToBanglaNumber(item.g10 || 0)}</td>
            </tr>`;
        });
        
        return `
            <table class="report-table">
                <thead>
                    <tr class="header-bg">
                        <th rowspan="2" style="width: 5%;">ক্র: নং</th>
                        <th rowspan="2" style="width: 45%;">কারখানা/প্রতিষ্ঠানের নাম</th>
                        <th colspan="3">গ্রেড ভিত্তিক কর্মকর্তা পরিসংখ্যান</th>
                    </tr>
                    <tr class="header-bg">
                        <th>গ্রেড-১</th>
                        <th>গ্রেড-২</th>
                        <th>গ্রেড-১০</th>
                    </tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
                <tfoot>
                    <tr class="header-bg">
                        <td colspan="2">সর্বমোট</td>
                        <td>${englishToBanglaNumber(totalG1)}</td>
                        <td>${englishToBanglaNumber(totalG2)}</td>
                        <td>${englishToBanglaNumber(totalG10)}</td>
                    </tr>
                </tfoot>
            </table>`;
            
    } else if (type === 'staff') {
        // Formulate Staff Table Structure (Grades 11 to 20)
        let totalG11=0, totalG16=0, totalG20=0;
        data.forEach((item, index) => {
            totalG11 += parseInt(item.g11 || 0);
            totalG16 += parseInt(item.g16 || 0);
            totalG20 += parseInt(item.g20 || 0);
            
            rowsHtml += `<tr>
                <td>${englishToBanglaNumber(index + 1)}</td>
                <td class="text-left-align">${item.enterprise_name || ''}</td>
                <td>${englishToBanglaNumber(item.g11 || 0)}</td>
                <td>${englishToBanglaNumber(item.g16 || 0)}</td>
                <td>${englishToBanglaNumber(item.g20 || 0)}</td>
            </tr>`;
        });
        
        return `
            <table class="report-table">
                <thead>
                    <tr class="header-bg">
                        <th rowspan="2" style="width: 5%;">ক্র: নং</th>
                        <th rowspan="2" style="width: 45%;">কারখানা/প্রতিষ্ঠানের নাম</th>
                        <th colspan="3">গ্রেড ভিত্তিক কর্মচারী পরিসংখ্যান</th>
                    </tr>
                    <tr class="header-bg">
                        <th>গ্রেড-১১</th>
                        <th>গ্রেড-১৬</th>
                        <th>গ্রেড-২০</th>
                    </tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
                <tfoot>
                    <tr class="header-bg">
                        <td colspan="2">সর্বমোট</td>
                        <td>${englishToBanglaNumber(totalG11)}</td>
                        <td>${englishToBanglaNumber(totalG16)}</td>
                        <td>${englishToBanglaNumber(totalG20)}</td>
                    </tr>
                </tfoot>
            </table>`;
            
    } else {
        // Fallback Layout Engine for Workers and Daily/Ansar groups (Categorical variables)
        let totalCat1=0, totalCat2=0;
        data.forEach((item, index) => {
            totalCat1 += parseInt(item.permanent || item.daily_rate_skilled || 0);
            totalCat2 += parseInt(item.temporary || item.ansar_guard || 0);
            
            rowsHtml += `<tr>
                <td>${englishToBanglaNumber(index + 1)}</td>
                <td class="text-left-align">${item.enterprise_name || ''}</td>
                <td>${englishToBanglaNumber(item.permanent || item.daily_rate_skilled || 0)}</td>
                <td>${englishToBanglaNumber(item.temporary || item.ansar_guard || 0)}</td>
            </tr>`;
        });
        
        return `
            <table class="report-table">
                <thead>
                    <tr class="header-bg">
                        <th style="width: 5%;">ক্র: নং</th>
                        <th style="width: 55%;">কারখানা/প্রতিষ্ঠানের নাম</th>
                        <th>ক্যাটাগরি-০১ (স্থায়ী/দক্ষ)</th>
                        <th>ক্যাটাগরি-০২ (অস্থায়ী/আনসার)</th>
                    </tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
                <tfoot>
                    <tr class="header-bg">
                        <td colspan="2">সর্বমোট</td>
                        <td>${englishToBanglaNumber(totalCat1)}</td>
                        <td>${englishToBanglaNumber(totalCat2)}</td>
                    </tr>
                </tfoot>
            </table>`;
    }
}

/**
 * 5. Global Action Matrix: Cascading recursive loop printing execution
 */
function printAllMonths() {
    const monthElements = $('.officer-print-btn');
    if (monthElements.length === 0) return;
    
    let currentIndex = 0;
    function processNext() {
        if (currentIndex < monthElements.length) {
            const mKey = $(monthElements[currentIndex]).attr('data-month-key');
            const mName = $(monthElements[currentIndex]).closest('tr').find('td:nth-child(2)').text();
            
            // Execute sequential loads across categories
            printOfficerReport(mKey, mName);
            printStaffReport(mKey, mName);
            
            currentIndex++;
            setTimeout(processNext, 600); // Thread safety delay window
        }
    }
    processNext();
}
</script>
</body>
</html>