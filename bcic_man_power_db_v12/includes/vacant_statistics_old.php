<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// User-defined variables
$username = $_SESSION['username'];
$factory_name = $username; // Factory name is set to the logged-in username
$vacant_statistics_tbl = 'vacant_statistics_tbl';

// 1. Structure Definition
$structure = [
    'প্রথম শ্রেণী' => range(1, 9),
    'দ্বিতীয় শ্রেণী' => [10],
    'তৃতীয় শ্রেণী' => range(11, 16),
    'চতুর্থ শ্রেণী' => range(17, 20),
    'শ্রমিক' => range(1, 20)
];

// Helper for Bangla Numbers
function enToBn($number) {
    $en = array('0','1','2','3','4','5','6','7','8','9');
    $bn = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($en, $bn, $number);
}

// 2. CRUD: Save/Update Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_data'])) {
    foreach ($_POST['grade_no'] as $index => $grade) {
        $granted = (int)$_POST['granted_post'][$index];
        $service = (int)$_POST['in_service'][$index];
        $promo   = (int)$_POST['eligible_promotion'][$index];
        $recruit = (int)$_POST['direct_recruit'][$index];
        $row_id  = $_POST['row_db_id'][$index];

        if (!empty($row_id)) {
            $sql = "UPDATE `$vacant_statistics_tbl` SET 
                    `factory_name`='$factory_name', `granted_post`='$granted', 
                    `in_service`='$service', `eligible_promotion`='$promo', 
                    `direct_recruit`='$recruit', `updated_at`=NOW() 
                    WHERE `id`='$row_id'";
        } else {
            $sql = "INSERT INTO `$vacant_statistics_tbl` 
                    (`factory_name`, `granted_post`, `in_service`, `eligible_promotion`, `direct_recruit`, `created_at`) 
                    VALUES ('$factory_name', '$granted', '$service', '$promo', '$recruit', NOW())";
        }
        $conn->query($sql);
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=success");
    exit;
}

// 3. Fetch Existing Data for the specific factory
$existing_data = [];
$res = $conn->query("SELECT * FROM `$vacant_statistics_tbl` WHERE `factory_name`='$factory_name' ORDER BY id ASC");
while($row = $res->fetch_assoc()) { $existing_data[] = $row; }
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>গ্রেড ভিত্তিক এন্ট্রি | <?php echo $factory_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .table thead { background: #1e4663; color: white; position: sticky; top: 0; }
        .class-td { background: white !important; font-weight: bold; vertical-align: middle; text-align: center; border-right: 2px solid #dee2e6 !important; }
        .grade-badge { background: #e9ecef; color: #1e4663; font-weight: 600; padding: 5px 10px; border-radius: 15px; border: 1px solid #ced4da; display: inline-block; }
        .total-box { font-weight: bold; color: #dc3545; background-color: #fffafa; }
        input[type=number] { width: 80px; text-align: center; border-radius: 4px; border: 1px solid #ced4da; }
    </style>
</head>
<body>

<div class="container-fluid mt-4 px-5">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h5 class="mb-0">কারখানা: <?php echo $factory_name; ?> (তথ্য এন্ট্রি ফর্ম)</h5>
            <span>স্বাগতম, <?php echo $username; ?></span>
        </div>
        <div class="card-body">
            
            <?php if(isset($_GET['msg'])) echo '<div class="alert alert-success">তথ্যাদি সফলভাবে সংরক্ষিত হয়েছে!</div>'; ?>

            <form method="POST">
                <div class="table-responsive" style="max-height: 700px;">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>শ্রেণী</th>
                                <th>গ্রেড</th>
                                <th>অনুমোদিত পদ</th>
                                <th>কর্মরত</th>
                                <th>পদোন্নতিযোগ্য</th>
                                <th>সরাসরি নিয়োগ</th>
                                <th>মোট শূন্য পদ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $global_idx = 0;
                            foreach ($structure as $className => $grades): 
                                $span = count($grades);
                                foreach ($grades as $i => $gNum): 
                                    $row = $existing_data[$global_idx] ?? null;
                                    $promo = $row['eligible_promotion'] ?? 0;
                                    $recruit = $row['direct_recruit'] ?? 0;
                                ?>
                                <tr>
                                    <?php if($i === 0): ?>
                                        <td rowspan="<?php echo $span; ?>" class="class-td"><?php echo $className; ?></td>
                                    <?php endif; ?>

                                    <td>
                                        <span class="grade-badge">গ্রেড <?php echo enToBn($gNum); ?></span>
                                        <input type="hidden" name="grade_no[]" value="<?php echo $gNum; ?>">
                                        <input type="hidden" name="row_db_id[]" value="<?php echo $row['id'] ?? ''; ?>">
                                    </td>

                                    <td><input type="number" name="granted_post[]" class="form-control" value="<?php echo $row['granted_post'] ?? 0; ?>"></td>
                                    <td><input type="number" name="in_service[]" class="form-control" value="<?php echo $row['in_service'] ?? 0; ?>"></td>
                                    
                                    <td><input type="number" name="eligible_promotion[]" class="form-control calc" value="<?php echo $promo; ?>"></td>
                                    <td><input type="number" name="direct_recruit[]" class="form-control calc" value="<?php echo $recruit; ?>"></td>
                                    
                                    <td class="total-box"><?php echo enToBn($promo + $recruit); ?></td>
                                </tr>
                                <?php 
                                $global_idx++;
                                endforeach; 
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" name="save_data" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i> তথ্য সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    function enToBnJs(n) {
        var dict = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
        return String(n).replace(/[0-9]/g, function(w){ return dict[w]; });
    }

    // Dynamic Sum: Promo + Recruit = Total
    $('.calc').on('input', function() {
        var row = $(this).closest('tr');
        var p = parseInt(row.find('input[name="eligible_promotion[]"]').val()) || 0;
        var r = parseInt(row.find('input[name="direct_recruit[]"]').val()) || 0;
        row.find('.total-box').text(enToBnJs(p + r));
    });
});
</script>
</body>
</html>