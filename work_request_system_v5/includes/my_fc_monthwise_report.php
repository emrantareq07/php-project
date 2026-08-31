<?php
session_name('factory_work_request_db');

require_once '../db/config.php'; 

date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$month = $_GET['month'] ?? '';
$year  = $_GET['year']  ?? '';
$session_emp_id = $_SESSION['emp_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My FC History - BCIC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root { --bcic-green: #2d6a4f; --glass-bg: rgba(255, 255, 255, 0.9); }
        body { background: linear-gradient(135deg, #f0f4f8 0%, #d7e3fc 100%); min-height: 100vh; }
        
        .report-card { 
            border: none; 
            border-radius: 16px; 
            background: var(--glass-bg); 
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        }

        .header-banner {
            background: linear-gradient(45deg, #1b4332, #2d6a4f);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 20px;
        }

        .table thead { background-color: #f8f9fa; border-bottom: 2px solid var(--bcic-green); }
        .table th { color: #1b4332; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        
        .btn-filter { 
            background: #2d6a4f; 
            color: white; 
            border-radius: 8px;
            padding: 10px 25px;
            transition: 0.3s;
        }
        .btn-filter:hover { background: #1b4332; color: white; transform: translateY(-2px); }

        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .report-card { box-shadow: none; border: 1px solid #eee; }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="fw-bold text-dark"><i class="bi bi-clock-history me-2 text-success"></i>FC Submission History</h3>
        <div class="btn-group shadow-sm">
            <a href="dashboard.php" class="btn btn-white bg-white border"><i class="bi bi-house"></i></a>
            <button onclick="window.print()" class="btn btn-white bg-white border"><i class="bi bi-printer"></i> Print</button>
        </div>
    </div>

    <div class="report-card p-4 mb-4 no-print">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Select Month</label>
                <select name="month" class="form-select border-0 bg-light shadow-none">
                    <option value="">All Months</option>
                    <?php
                    for($m=1; $m<=12; $m++){
                        $mName = date("F", mktime(0, 0, 0, $m, 1));
                        $sel = ($month == $m) ? 'selected' : '';
                        echo "<option value='$m' $sel>$mName</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Select Year</label>
                <select name="year" class="form-select border-0 bg-light shadow-none">
                    <option value="">All Years</option>
                    <?php
                    // FIXED: Corrected column name to `current_date`
                    $year_sql = "SELECT DISTINCT YEAR(`current_date`) AS yr FROM fc_tbl WHERE emp_id = '$session_emp_id' ORDER BY yr DESC";
                    $year_res = mysqli_query($conn, $year_sql);
                    
                    if($year_res && mysqli_num_rows($year_res) > 0){
                        while($yr = mysqli_fetch_assoc($year_res)){
                            if(empty($yr['yr'])) continue; 
                            $selected = ($year == $yr['yr']) ? 'selected' : '';
                            echo "<option value='".$yr['yr']."' $selected>".$yr['yr']."</option>";
                        }
                    } else {
                        echo "<option value='".date('Y')."'>".date('Y')."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-5">
                <button type="submit" class="btn btn-filter w-100 fw-bold">
                    <i class="bi bi-funnel-fill me-2"></i>Generate Report
                </button>
            </div>
        </form>
    </div>

    <?php
    $where_clauses = ["emp_id = '$session_emp_id'"];
    if($month) $where_clauses[] = "MONTH(`current_date`) = '$month'";
    if($year)  $where_clauses[] = "YEAR(`current_date`) = '$year'";
    
    $where_sql = implode(" AND ", $where_clauses);
    $query = "SELECT * FROM fc_tbl WHERE $where_sql AND  status='approved' ORDER BY `current_date` DESC";
    $result = mysqli_query($conn, $query);
    ?>

    <div class="report-card overflow-hidden">
        <div class="header-banner d-flex justify-content-between align-items-center">
            <div>
                <p class="small text-uppercase mb-0 opacity-75">Employee Record</p>
                <h5 class="mb-0 fw-bold"><?= $_SESSION['full_name'] ?> (<?= $session_emp_id ?>)</h5>
            </div>
            <div class="text-end">
                <span class="badge bg-white text-success px-3 py-2 rounded-pill shadow-sm">
                    <?= mysqli_num_rows($result) ?> Records Found
                </span>
            </div>
        </div>

        <div class="p-4">
            <div class="table-responsive">
                <table id="fcTable" class="table table-hover align-middle border-0">
                    <thead>
                        <tr class="text-center">
                            <th>SL</th>
                            <th>Date</th>
                            <th>Section</th>
                            <th>Total Days</th>
                            <th>Total Hours</th>
                            <th class="no-print">Status</th>
                            <th class="no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        while($row = mysqli_fetch_assoc($result)){
                            // Cleanup comma separated data
                            $days_arr = array_filter(explode(",", $row['date']));
                            $hrs_arr = array_filter(explode(",", $row['total_hours']));
                            $total_days = count($days_arr);
                            $total_hrs = array_sum($hrs_arr);
                        ?>
                        <tr class="text-center">
                            <td class="text-muted fw-bold"><?= $sl++ ?></td>
                            <td class="fw-bold text-dark"><?= date("d M, Y", strtotime($row['current_date'])) ?></td>
                            <td><span class="text-secondary small"><?= $row['section'] ?></span></td>
                            <td><span class="badge bg-light text-success border border-success-subtle px-3"><?= $total_days ?> Days</span></td>
                            <td><span class="fw-bold"><?= number_format($total_hrs, 2) ?></span> <small>Hrs</small></td>
                            <td class="no-print">
                                <span class="text-primary small fw-semibold"><?=$row['status'] ?><i class="bi bi-cloud-check-fill"></i> Verified</span>
                            </td>
                          <td>
						    <a href="view_my_fc.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary">
						        <i class="fas fa-eye"></i> View
						    </a>
						</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function(){
    $('#fcTable').DataTable({
        "pageLength": 10,
        "ordering": false,
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search logs..."
        }
    });
    // Customizing Search Input
    $('.dataTables_filter input').addClass('form-control form-control-sm border-0 bg-light shadow-none px-3 py-2');
});
</script>

</body>
</html>