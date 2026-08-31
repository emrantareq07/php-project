<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    die("Unauthorized access");
}

$month_key = isset($_GET['month_key']) ? $_GET['month_key'] : '';
$month_name = isset($_GET['month_name']) ? $_GET['month_name'] : '';

// মাস বাংলায় রূপান্তরের ফাংশন
function convertToBanglaMonth($englishMonth) {
    $months = [
        'January'   => 'জানুয়ারি',
        'February'  => 'ফেব্রুয়ারি',
        'March'     => 'মার্চ',
        'April'     => 'এপ্রিল',
        'May'       => 'মে',
        'June'      => 'জুন',
        'July'      => 'জুলাই',
        'August'    => 'আগস্ট',
        'September' => 'সেপ্টেম্বর',
        'October'   => 'অক্টোবর',
        'November'  => 'নভেম্বর',
        'December'  => 'ডিসেম্বর'
    ];

    return isset($months[$englishMonth]) ? $months[$englishMonth] : $englishMonth;
}

if (empty($month_key)) {
    die("Invalid month");
}

// Helper functions
function csvToArray($csv) {
    if (empty($csv)) return [];
    return array_map('intval', explode(',', $csv));
}

function enToBn($number) {
    $en = array('0','1','2','3','4','5','6','7','8','9');
    $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($en, $bn, (string)$number);
}

// Structure definition with class keys for sub-totals
$structure = [
    'প্রথম শ্রেণী' => ['grades' => range(1, 9), 'grade_range' => '১-৯'],
    'দ্বিতীয় শ্রেণী' => ['grades' => [10], 'grade_range' => '১০'],
    'তৃতীয় শ্রেণী' => ['grades' => range(11, 16), 'grade_range' => '১১-১৬'],
    'চতুর্থ শ্রেণী' => ['grades' => range(17, 20), 'grade_range' => '১৭-২০'],
    'শ্রমিক' => ['grades' => range(1, 20), 'grade_range' => '১-২০']
];

$total_grades = 0;
foreach ($structure as $classData) {
    $total_grades += count($classData['grades']);
}

// Fetch records for the month
$start_date = $month_key . '-01';
$end_date = date('Y-m-t', strtotime($start_date));

$sql = "SELECT * FROM vacant_statistics_tbl WHERE entry_date BETWEEN '$start_date' AND '$end_date' ORDER BY factory_name ASC";
$result = $conn->query($sql);

$records = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $granted_array = csvToArray($row['granted_post']);
        $service_array = csvToArray($row['in_service']);
        $promo_array = csvToArray($row['eligible_promotion']);
        $direct_array = csvToArray($row['direct_recruit']);
        
        while (count($granted_array) < $total_grades) $granted_array[] = 0;
        while (count($service_array) < $total_grades) $service_array[] = 0;
        while (count($promo_array) < $total_grades) $promo_array[] = 0;
        while (count($direct_array) < $total_grades) $direct_array[] = 0;
        
        $row['granted_total'] = array_sum($granted_array);
        $row['service_total'] = array_sum($service_array);
        $row['promo_total'] = array_sum($promo_array);
        $row['direct_total'] = array_sum($direct_array);
        $row['vacant_total'] = $row['promo_total'] + $row['direct_total'];
        $row['granted_array'] = $granted_array;
        $row['service_array'] = $service_array;
        $row['promo_array'] = $promo_array;
        $row['direct_array'] = $direct_array;
        
        $records[] = $row;
    }
}

// Combine all records for the month
$combined = [
    'factory_name' => 'সকল কারখানা (সমন্বিত)',
    'entry_date' => $start_date,
    'granted_array' => array_fill(0, $total_grades, 0),
    'service_array' => array_fill(0, $total_grades, 0),
    'promo_array' => array_fill(0, $total_grades, 0),
    'direct_array' => array_fill(0, $total_grades, 0)
];

foreach ($records as $record) {
    for ($i = 0; $i < $total_grades; $i++) {
        $combined['granted_array'][$i] += $record['granted_array'][$i];
        $combined['service_array'][$i] += $record['service_array'][$i];
        $combined['promo_array'][$i] += $record['promo_array'][$i];
        $combined['direct_array'][$i] += $record['direct_array'][$i];
    }
}

$combined['granted_total'] = array_sum($combined['granted_array']);
$combined['service_total'] = array_sum($combined['service_array']);
$combined['promo_total'] = array_sum($combined['promo_array']);
$combined['direct_total'] = array_sum($combined['direct_array']);
$combined['vacant_total'] = $combined['promo_total'] + $combined['direct_total'];
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>শূন্য পদ পরিসংখ্যান - <?php echo $month_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Noto Sans Bengali', sans-serif; 
            padding: 20px; 
            background: white;
            font-size: 14px;
        }
        
        .print-header { 
            text-align: center; 
            margin-bottom: 0px; 
            padding-bottom: 0px; 
            border-bottom: 0px solid #333;
        }
        
        .print-header h3 {
            margin-bottom: 5px;
        }
        
        .print-header h6 {
            margin-bottom: 10px;
            color: #555;
        }
        
        /* Combined Info Box - Will show in print */
        .combined-info { 
            background: #d4edda !important;
            border: 2px solid #28a745 !important;
            padding: 15px !important;
            margin-bottom: 25px !important;
            text-align: center !important;
            border-radius: 8px !important;
            page-break-inside: avoid !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .combined-info strong {
            font-size: 16px;
            color: #155724;
        }
        
        .combined-info i {
            margin-right: 8px;
        }
        
        /* Summary Statistics Boxes - Will show in print */
        .summary-container {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            page-break-inside: avoid;
        }
        
        .summary-box { 
            display: inline-block; 
            padding: 15px 25px; 
            background: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 10px; 
            text-align: center;
            min-width: 180px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .summary-box strong {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }
        
        .summary-box span {
            font-size: 28px;
            font-weight: bold;
            display: block;
        }
        
        .summary-box.vacant { 
            background: #dc3545 !important;
            border-color: #dc3545 !important;
        }
        
        .summary-box.vacant strong,
        .summary-box.vacant span {
            color: white !important;
        }
        
        /* Print Table */
        .print-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            font-size: 12px;
        }
        
        .print-table th, 
        .print-table td { 
            border: 1px solid #000; 
            padding: 8px 6px; 
            text-align: center; 
            vertical-align: middle; 
        }
        
        .print-table th { 
            background-color: #e9ecef !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .vacant-cell { 
            color: #dc3545; 
            font-weight: bold; 
        }
        
        .text-start { 
            text-align: left; 
        }
        
        .subtotal-row {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .subtotal-row td {
            background-color: #f0f0f0 !important;
            font-weight: bold;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            padding-top: 10px;
            width: 80%;
            margin: 0 auto;
        }
        
        /* Print Styles - Portrait Orientation */
        @media print {
            body { 
                padding: 0; 
                margin: 0;
                background: white;
            }
            
            .no-print { 
                display: none !important; 
            }
            
            .print-table { 
                font-size: 10px; 
                width: 100%;
            }
            
            .print-table th, 
            .print-table td { 
                padding: 5px 3px; 
            }
            
            .print-table th {
                font-size: 10px;
            }
            
            .summary-box {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            
            .combined-info {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            
            .subtotal-row {
                background-color: #f0f0f0 !important;
            }
            
            .print-header {
                margin-bottom: 15px;
                padding-bottom: 8px;
            }
            
            .print-header h3 {
                font-size: 16px;
            }
            
            .print-header h5 {
                font-size: 14px;
            }
            
            .signature-section {
                margin-top: 30px;
            }
        }
        
        /* Portrait page orientation */
        @page {
            size: portrait;
            margin: 1cm;
        }
    </style>
</head>
<body>

<div class="print-header">
    <h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
    <h6>বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h6>
    <h5>শূন্য পদ পরিসংখ্যান প্রতিবেদন</h5>
    <h5>মাস: <?php echo convertToBanglaMonth($month_name); ?></h5>
    <p>প্রতিবেদন তৈরির তারিখ: <?php echo date('d-m-Y'); ?></p>
</div>

<?php if (count($records) > 1): ?>
<div class="combined-info no-print">
    <strong><i class="fas fa-copy"></i> <?php echo enToBn(count($records)); ?>টি রেকর্ডের সমন্বিত ফলাফল</strong><br>
    মোট কারখানা: <?php echo enToBn(count(array_unique(array_column($records, 'factory_name')))); ?>
</div>
<?php endif; ?>

<!-- Summary Statistics -->
<div class="summary-container no-print">
    <div class="summary-box">
        <strong>মোট অনুমোদিত পদ</strong>
        <span><?php echo enToBn($combined['granted_total']); ?></span>
    </div>
    <div class="summary-box">
        <strong>মোট কর্মরত</strong>
        <span><?php echo enToBn($combined['service_total']); ?></span>
    </div>
    <div class="summary-box vacant">
        <strong>মোট শূন্য পদ</strong>
        <span><?php echo enToBn($combined['vacant_total']); ?></span>
    </div>
</div>

<!-- Detailed Table with Sub-Totals -->
<div style="overflow-x: auto;">
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 15%">শ্রেণী</th>
                <th style="width: 15%">গ্রেড</th>
                <th style="width: 14%">অনুমোদিত পদ</th>
                <th style="width: 14%">কর্মরত</th>
                <th style="width: 14%">পদোন্নতিযোগ্য</th>
                <th style="width: 14%">সরাসরি নিয়োগ</th>
                <th style="width: 16%">মোট শূন্য</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $globalIdx = 0;
            $totalGranted = 0;
            $totalService = 0;
            $totalPromo = 0;
            $totalDirect = 0;
            $totalVacant = 0;
            
            foreach ($structure as $className => $classData):
                $grades = $classData['grades'];
                $span = count($grades);
                
                // Initialize sub-total variables for this class
                $classGranted = 0;
                $classService = 0;
                $classPromo = 0;
                $classDirect = 0;
                $classVacant = 0;
                
                // Display all grades in this class
                foreach ($grades as $i => $gradeNum):
                    $grantedVal = $combined['granted_array'][$globalIdx] ?? 0;
                    $serviceVal = $combined['service_array'][$globalIdx] ?? 0;
                    $promoVal = $combined['promo_array'][$globalIdx] ?? 0;
                    $directVal = $combined['direct_array'][$globalIdx] ?? 0;
                    $vacantVal = $promoVal + $directVal;
                    
                    // Add to class sub-totals
                    $classGranted += $grantedVal;
                    $classService += $serviceVal;
                    $classPromo += $promoVal;
                    $classDirect += $directVal;
                    $classVacant += $vacantVal;
                    
                    // Add to grand totals
                    $totalGranted += $grantedVal;
                    $totalService += $serviceVal;
                    $totalPromo += $promoVal;
                    $totalDirect += $directVal;
                    $totalVacant += $vacantVal;
            ?>
            <tr>
                <?php if ($i === 0): ?>
                    <td rowspan="<?php echo $span + 1; ?>" style="vertical-align: middle; text-align: middle;"><strong><?php echo $className; ?></strong><br><small>(গ্রেড <?php echo $classData['grade_range']; ?>)</small></td>
                <?php endif; ?>
                <td class="text-center">গ্রেড <?php echo enToBn($gradeNum); ?></td>
                <td class="text-center"><?php echo enToBn($grantedVal); ?></td>
                <td class="text-center"><?php echo enToBn($serviceVal); ?></td>
                <td class="text-center"><?php echo enToBn($promoVal); ?></td>
                <td class="text-center"><?php echo enToBn($directVal); ?></td>
                <td class="text-center vacant-cell"><?php echo enToBn($vacantVal); ?></td>
            </tr>
            <?php
                    $globalIdx++;
                endforeach;
            ?>
            
            <!-- Sub-Total Row for this class -->
            <tr class="subtotal-row">
                <td class="text-center"><strong><?php echo $className; ?> - উপমোট</strong></td>
                <td class="text-center"><strong><?php echo enToBn($classGranted); ?></strong></td>
                <td class="text-center"><strong><?php echo enToBn($classService); ?></strong></td>
                <td class="text-center"><strong><?php echo enToBn($classPromo); ?></strong></td>
                <td class="text-center"><strong><?php echo enToBn($classDirect); ?></strong></td>
                <td class="text-center vacant-cell"><strong><?php echo enToBn($classVacant); ?></strong></td>
            </tr>
            
            <?php endforeach; ?>
        </tbody>
        <!-- <tfoot style="background-color: #e9ecef; font-weight: bold;"> -->
            <tr class="grand-total-row">
                <td class="text-start text-center" colspan="2"><strong>সর্বমোট</strong></td>
                <td class="text-center"><strong><?php echo enToBn($totalGranted); ?></strong></td>
                <td class="text-center"><strong><?php echo enToBn($totalService); ?></strong></td>
                <td class="text-center"><strong><?php echo enToBn($totalPromo); ?></strong></td>
                <td class="text-center"><strong><?php echo enToBn($totalDirect); ?></strong></td>
                <td class="text-center vacant-cell"><strong><?php echo enToBn($totalVacant); ?></strong></td>
            </tr>
        <!-- </tfoot> -->
    </table>
</div>
<div class="row mt-0">
           <div class="col-md-12 text-center">
                <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                    <strong>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</strong>
                    
                </div>
            </div>
        </div>
<!-- Signature Section -->
<div class="signature-section">

   <!--  <div class="signature-box">
        <div class="signature-line">
            <strong>প্রস্তুতকারীর স্বাক্ষর</strong><br>
            <small>নাম ও পদবী</small>
        </div>
    </div>
    <div class="signature-box">
        <div class="signature-line">
            <strong>দায়িত্বপ্রাপ্ত কর্মকর্তার স্বাক্ষর</strong><br>
            <small>নাম ও পদবী</small>
        </div>
    </div> -->
</div>

<div class="no-print text-center mt-0" style="text-align: center; margin-top: 0px;">
    <button class="btn btn-primary" onclick="window.print()" style="padding: 10px 20px; margin: 5px;">
        <i class="fas fa-print"></i> প্রিন্ট করুন
    </button>
    <button class="btn btn-secondary" onclick="window.close()" style="padding: 10px 20px; margin: 5px;">
        <i class="fas fa-times"></i> বন্ধ করুন
    </button>

</div>
<small class="text-muted float-end">
     Design & Developed by ICT Division, BCIC.
    </small>

<script>
    // Auto print when page loads if auto_print parameter is present
    <?php if(isset($_GET['auto_print']) && $_GET['auto_print'] == 1): ?>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 1000);
    }
    <?php endif; ?>    
</script>

</body>
</html>