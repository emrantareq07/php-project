<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    echo "<div class='alert alert-danger'>Unauthorized access</div>";
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger'>Invalid record ID</div>";
    exit;
}

// Helper function to convert CSV to array
function csvToArray($csv) {
    if (empty($csv)) return [];
    return array_map('intval', explode(',', $csv));
}

// Helper function for Bangla numbers
function enToBn($number) {
    $en = array('0','1','2','3','4','5','6','7','8','9');
    $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($en, $bn, (string)$number);
}

// Structure definition
$structure = [
    'প্রথম শ্রেণী' => ['grades' => range(1, 9), 'grade_range' => '১-৯'],
    'দ্বিতীয় শ্রেণী' => ['grades' => [10], 'grade_range' => '১০'],
    'তৃতীয় শ্রেণী' => ['grades' => range(11, 16), 'grade_range' => '১১-১৬'],
    'চতুর্থ শ্রেণী' => ['grades' => range(17, 20), 'grade_range' => '১৭-২০'],
    'শ্রমিক' => ['grades' => range(1, 20), 'grade_range' => '১-২০']
];

// Calculate total grades count
$total_grades = 0;
foreach ($structure as $classData) {
    $total_grades += count($classData['grades']);
}

// Fetch record
$sql = "SELECT * FROM vacant_statistics_tbl WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Record not found</div>";
    exit;
}

$record = $result->fetch_assoc();

// Parse CSV data
$granted_array = csvToArray($record['granted_post']);
$service_array = csvToArray($record['in_service']);
$promo_array = csvToArray($record['eligible_promotion']);
$recruit_array = csvToArray($record['direct_recruit']);

// Pad arrays
while (count($granted_array) < $total_grades) $granted_array[] = 0;
while (count($service_array) < $total_grades) $service_array[] = 0;
while (count($promo_array) < $total_grades) $promo_array[] = 0;
while (count($recruit_array) < $total_grades) $recruit_array[] = 0;

// Calculate totals
$total_granted = array_sum($granted_array);
$total_service = array_sum($service_array);
$total_promo = array_sum($promo_array);
$total_recruit = array_sum($recruit_array);
$total_vacant = $total_promo + $total_recruit;

$entry_date = !empty($record['entry_date']) ? $record['entry_date'] : $record['created_at'];
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শূন্য পদ পরিসংখ্যান - বিস্তারিত</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Bengali', sans-serif; padding: 20px; background: #f8f9fa; }
        .summary-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; }
        .info-table { background: white; border-radius: 10px; }
        .print-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; display: none; }
        .stat-box { background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; text-align: center; margin: 5px; }
        .stat-number { font-size: 24px; font-weight: bold; }
        @media print {
            body { padding: 0; margin: 0; background: white; }
            .no-print { display: none !important; }
            .print-header { display: block !important; }
            .table { font-size: 12px; }
            .summary-card { background: #f8f9fa; color: #333; border: 1px solid #ddd; }
            .stat-box { background: #f0f0f0; color: #333; }
            .modal-content { border: none; }
            .modal-header, .modal-footer { display: none; }
        }
        @page { size: landscape; margin: 1cm; }
    </style>
</head>
<body>

<div class="print-header">
    <h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h3>
    <h6>বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h6>
    <h5>শূন্য পদ পরিসংখ্যান প্রতিবেদন</h5>
    <p>প্রতিবেদন তৈরির তারিখ: <?php echo date('d-m-Y'); ?></p>
</div>

<div class="container-fluid">
    <!-- Basic Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card info-table">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>মৌলিক তথ্য</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 40%">কারখানার নাম:</th>
                            <td><strong><?php echo htmlspecialchars($record['factory_name']); ?></strong></td>
                        </tr>
                        <tr>
                            <th>এন্ট্রির তারিখ:</th>
                            <td><?php echo date('d-m-Y', strtotime($entry_date)); ?></td>
                        </tr>
                        <tr>
                            <th>মাস/বছর:</th>
                            <td><?php echo date('F Y', strtotime($entry_date)); ?></td>
                        </tr>
                        <tr>
                            <th>তৈরির তারিখ:</th>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($record['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <th>সর্বশেষ আপডেট:</th>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($record['updated_at'])); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <div class="col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>সারসংক্ষেপ</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-box">
                                <small>মোট অনুমোদিত পদ</small>
                                <div class="stat-number"><?php echo enToBn($total_granted); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <small>মোট কর্মরত</small>
                                <div class="stat-number"><?php echo enToBn($total_service); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <small>পদোন্নতিযোগ্য</small>
                                <div class="stat-number"><?php echo enToBn($total_promo); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <small>সরাসরি নিয়োগযোগ্য</small>
                                <div class="stat-number"><?php echo enToBn($total_recruit); ?></div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="stat-box bg-danger text-white">
                                <small>মোট শূন্য পদ</small>
                                <div class="stat-number"><?php echo enToBn($total_vacant); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade-wise Detailed Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-table me-2"></i>গ্রেড ভিত্তিক বিস্তারিত তথ্য</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 12%">শ্রেণী</th>
                            <th style="width: 10%">গ্রেড</th>
                            <th style="width: 15%">অনুমোদিত পদ</th>
                            <th style="width: 15%">কর্মরত</th>
                            <th style="width: 18%">পদোন্নতিযোগ্য</th>
                            <th style="width: 18%">সরাসরি নিয়োগ</th>
                            <th style="width: 12%">মোট শূন্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $global_idx = 0;
                        foreach ($structure as $className => $classData): 
                            $grades = $classData['grades'];
                            $span = count($grades);
                            foreach ($grades as $i => $gNum): 
                                $granted_val = $granted_array[$global_idx] ?? 0;
                                $service_val = $service_array[$global_idx] ?? 0;
                                $promo_val = $promo_array[$global_idx] ?? 0;
                                $recruit_val = $recruit_array[$global_idx] ?? 0;
                                $vacant_val = $promo_val + $recruit_val;
                        ?>
                        <tr>
                            <?php if($i === 0): ?>
                                <td rowspan="<?php echo $span; ?>" style="background-color: #f0f5fb; vertical-align: middle;">
                                    <strong><?php echo $className; ?></strong>
                                    <br><small>(গ্রেড <?php echo $classData['grade_range']; ?>)</small>
                                 </td>
                            <?php endif; ?>
                            <td class="text-center">গ্রেড <?php echo enToBn($gNum); ?></td>
                            <td class="text-center"><?php echo enToBn($granted_val); ?></td>
                            <td class="text-center"><?php echo enToBn($service_val); ?></td>
                            <td class="text-center"><?php echo enToBn($promo_val); ?></td>
                            <td class="text-center"><?php echo enToBn($recruit_val); ?></td>
                            <td class="text-center text-danger fw-bold"><?php echo enToBn($vacant_val); ?></td>
                        </tr>
                        <?php 
                            $global_idx++;
                            endforeach; 
                        endforeach; 
                        ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">সর্বমোট:</td>
                            <td class="text-center fw-bold"><?php echo enToBn($total_granted); ?></td>
                            <td class="text-center fw-bold"><?php echo enToBn($total_service); ?></td>
                            <td class="text-center fw-bold"><?php echo enToBn($total_promo); ?></td>
                            <td class="text-center fw-bold"><?php echo enToBn($total_recruit); ?></td>
                            <td class="text-center text-danger fw-bold"><?php echo enToBn($total_vacant); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-4 text-center no-print">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">
            <i class="fas fa-times"></i> বন্ধ করুন
        </button>
        <button type="button" class="btn btn-primary" onclick="printRecord()">
            <i class="fas fa-print"></i> প্রিন্ট করুন
        </button>
    </div>
</div>

<script>
function printRecord() {
    // Store original title
    var originalTitle = document.title;
    document.title = 'Vacant_Statistics_Report_' + '<?php echo $record['factory_name']; ?>';
    
    // Trigger print
    window.print();
    
    // Restore title after print dialog closes
    setTimeout(function() {
        document.title = originalTitle;
    }, 100);
}

function closeModal() {
    // Find and close the modal
    var modal = document.querySelector('#viewModal');
    if (modal) {
        var bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    }
}

// Auto trigger print when page loads (if print parameter is present)
<?php if(isset($_GET['print']) && $_GET['print'] == '1'): ?>
window.onload = function() {
    setTimeout(function() {
        window.print();
    }, 500);
}
<?php endif; ?>
</script>

</body>
</html>