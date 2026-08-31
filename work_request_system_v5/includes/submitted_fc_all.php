<?php
session_name('factory_work_request_db');
session_start();
require_once 'db.php'; 

date_default_timezone_set('Asia/Dhaka');

// Security & Role Check
$emp_type     = $_SESSION['emp_type'] ?? '';
$routine_role = $_SESSION['routine_role'] ?? '';
$addl_role    = $_SESSION['addl_role'] ?? '';

if (!($emp_type === 'officer' && $routine_role === 'md_charge' && $addl_role === 'md_charge')) {
    die("<div class='alert alert-danger m-5'>Access Denied: MD Charge permissions required.</div>");
}

// --- START DELETE/EDIT LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reduce_days') {
    $div = mysqli_real_escape_string($conn, $_POST['div']);
    $sec = mysqli_real_escape_string($conn, $_POST['sec']);
    $m   = mysqli_real_escape_string($conn, $_POST['month']);
    $y   = mysqli_real_escape_string($conn, $_POST['year']);
    $new_total = (int)$_POST['new_total_days'];
    $old_total = (int)$_POST['old_total_days'];

    $reduction_needed = $old_total - $new_total;

    if ($reduction_needed > 0) {
        // Find employees to calculate per-person reduction
        $emp_check = mysqli_query($conn, "SELECT id, date, time_from, time_to, total_hours FROM fc_tbl 
            WHERE division='$div' AND section='$sec' AND MONTH(`current_date`)='$m' AND YEAR(`current_date`)='$y' AND status='draft'");
        
        $emp_count = mysqli_num_rows($emp_check);

        if ($emp_count > 0) {
            $remove_per_person = floor($reduction_needed / $emp_count);

            while ($row = mysqli_fetch_assoc($emp_check)) {
                $id = $row['id'];
                
                // Helper to pop last N elements
                $pop_last = function($str, $count) {
                    $arr = array_filter(explode(',', $str));
                    for($i=0; $i<$count; $i++) { array_pop($arr); }
                    return implode(',', $arr);
                };

                $new_date  = $pop_last($row['date'], $remove_per_person);
                $new_from  = $pop_last($row['time_from'], $remove_per_person);
                $new_to    = $pop_last($row['time_to'], $remove_per_person);
                $new_hours = $pop_last($row['total_hours'], $remove_per_person);

                mysqli_query($conn, "UPDATE fc_tbl SET 
                    date='$new_date', time_from='$new_from', time_to='$new_to', total_hours='$new_hours' 
                    WHERE id='$id'");
            }
            $msg = "Successfully reduced $remove_per_person days per employee.";
        }
    }
}
// --- END DELETE/EDIT LOGIC ---

$month = $_GET['month'] ?? date('m');
$year  = $_GET['year']  ?? date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MD Charge - Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header-section { background: #004d40; color: white; padding: 20px; border-radius: 12px 12px 0 0; }
        .status-draft { background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; border: 1px solid #ffeeba; }
    </style>
</head>
<body class="container py-4">

    <?php if(isset($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="header-section d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-user-shield me-2"></i>MD Charge: FC Summary</h4>
            <button type="button" onclick="window.print()" class="btn btn-light btn-sm no-print"><i class="fas fa-print"></i> Print</button>
        </div>
        
        <div class="card-body">
            <table class="table table-hover align-middle" id="summaryTable">
                <thead class="table-light">
                    <tr>
                        <th>SL</th>
                        <th>Division</th>
                        <th>Section</th>
                        <th class="text-center">Employees</th>
                        <th class="text-center">Total Days</th>
                        <th class="text-center">Status</th>
                        <th class="text-center no-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT division, section, COUNT(DISTINCT emp_id) as total_employees,
                             SUM(LENGTH(date) - LENGTH(REPLACE(date, ',', '')) + 1) AS total_days_sum, status
                             FROM fc_tbl WHERE MONTH(`current_date`) = '$month' AND YEAR(`current_date`) = '$year'
                             AND status = 'draft' GROUP BY division, section, status ORDER BY division ASC";
                    $result = mysqli_query($conn, $query);
                    $sl = 1;
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <td><?= str_pad($sl++, 2, '0', STR_PAD_LEFT) ?></td>
                        <td class="fw-bold"><?= $row['division'] ?></td>
                        <td><?= $row['section'] ?></td>
                        <td class="text-center"><?= $row['total_employees'] ?></td>
                        <td class="text-center text-primary fw-bold"><?= $row['total_days_sum'] ?></td>
                        <td class="text-center"><span class="status-draft">DRAFT</span></td>
                        <td class="text-center no-print">
                            <a href="view_group_details.php?div=<?=urlencode($row['division'])?>&sec=<?=urlencode($row['section'])?>&month=<?=$month?>&year=<?=$year?>" class="btn btn-outline-dark btn-sm"><i class="fas fa-eye"></i></a>
                            
                            <button type="button" class="btn btn-outline-success btn-sm" 
                                    onclick="openEditModal('<?= $row['division'] ?>', '<?= $row['section'] ?>', <?= $row['total_days_sum'] ?>, <?= $row['total_employees'] ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Adjust Section Days</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="reduce_days">
                    <input type="hidden" name="update_days" value="1">
                    <input type="hidden" name="div" id="modal_div">
                    <input type="hidden" name="sec" id="modal_sec">
                    <input type="hidden" name="month" value="<?= $month ?>">
                    <input type="hidden" name="year" value="<?= $year ?>">
                    <input type="hidden" name="old_total_days" id="modal_old_days">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Section: <span id="display_sec" class="text-success"></span></label>
                        <p class="small text-muted mb-0">Employees: <span id="span_emp"></span></p>
                        <p class="small text-muted">Current Total Days in Section: <b id="display_old_days"></b></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Reduced Total Days</label>
                        <input type="number" name="new_total_days" id="new_total_input" class="form-control" required>
                        <div id="calc_hint" class="form-text text-danger mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Warning: This will permanently delete data for all employees in this section. Continue?')">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModal(div, sec, oldDays, empCount) {
            $('#modal_div').val(div);
            $('#modal_sec').val(sec);
            $('#modal_old_days').val(oldDays);
            $('#display_sec').text(sec);
             $('#span_emp').text(empCount);
            $('#display_old_days').text(oldDays);
            $('#new_total_input').val(oldDays);
            
            $('#new_total_input').on('input', function() {
                let newVal = $(this).val();
                let diff = oldDays - newVal;
                if(diff > 0) {
                    let perPerson = Math.floor(diff / empCount);
                    $('#calc_hint').text(`System will delete the last ${perPerson} days from each of the ${empCount} employees.`);
                } else {
                    $('#calc_hint').text("");
                }
            });

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>