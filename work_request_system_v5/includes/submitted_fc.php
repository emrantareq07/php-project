<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Session variables
$section      = $_SESSION['section'];
$division     = $_SESSION['division'];
$emp_type     = $_SESSION['emp_type'];
$routine_role = $_SESSION['routine_role'];
$addl_role    = $_SESSION['addl_role'];

// Get current Month and Year for filtering
$currentMonth = date('m');
$currentYear  = date('Y');

// Permission Check
if ($emp_type === 'officer' 
    && ($routine_role === 'section_head' || $routine_role === 'division_head') 
    && $addl_role === 'fc_officer') {

    // MODIFIED QUERY: Added Month/Year check and status='draft'
    $sql_query = "
        SELECT 
            name, designation, emp_id, remarks, month, section, division,
            (LENGTH(date) - LENGTH(REPLACE(date, ',', '')) + 1) AS total_days
        FROM fc_tbl
        WHERE section = '$section' 
        AND division = '$division' 
        AND status = 'draft'
        AND MONTH(`current_date`) = '$currentMonth'
        AND YEAR(`current_date`) = '$currentYear'
        GROUP BY name, designation, emp_id, remarks, month, section, division
    ";

    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    $result = mysqli_query($conn, $sql_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $first_row = $all_rows[0];
        $monthName = $monthNames[(int)$first_row['month']] ?? $monthNames[(int)$currentMonth];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Draft_Report_<?= $monthName ?>_<?= $currentYear ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bcic-green: #006837; }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        
        .report-container {
            background: white;
            padding: 50px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            max-width: 1050px;
            border-top: 10px solid var(--bcic-green);
        }

        .table thead { background-color: #f8f9fa; border-bottom: 2px solid var(--bcic-green); }
        .table th { font-weight: 700; text-transform: uppercase; font-size: 0.8rem; color: #333; }
        .table td { vertical-align: middle; }
        
        .header-main h1 { letter-spacing: 3px; color: var(--bcic-green); font-weight: 800; }
        .draft-watermark { color: #dc3545; font-weight: bold; border: 2px solid #dc3545; padding: 2px 10px; border-radius: 5px; display: inline-block; margin-bottom: 10px; }

        @media print {
            body { background: white; }
            .report-container { box-shadow: none; margin: 0; max-width: 100%; border-top: none; padding: 20px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container no-print mt-4">
    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
        <div class="d-flex gap-2">
            <span class="badge bg-warning text-dark d-flex align-items-center px-3">Status: Draft Only</span>
            <button class="btn btn-dark px-4" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print Official Copy
            </button>
        </div>
    </div>
</div>

<div class="report-container">
    <div class="header-main text-center mb-5">
        <div class="draft-watermark no-print">DRAFT COPY</div>
        <h1 class="mb-0">BCIC</h1>
        <h5 class="text-secondary fw-normal">Bangladesh Chemical Industries Corporation</h5>
        <p class="small text-muted mb-2">BCIC Bhaban, 30-31 Dilkusha C/A, Dhaka-1000</p>
        <div class="d-flex justify-content-center mb-3">
            <div class="border-bottom border-2 w-25"></div>
        </div>
        <h4 class="fw-bold text-uppercase">Food & Conveyance Summary Sheet</h4>
        <h5 class="text-muted"><?= $monthName ?> - <?= $currentYear ?></h5>
    </div>

    <div class="row mb-4 py-3 bg-light rounded mx-0">
        <div class="col-4 border-end">
            <div class="small text-muted text-uppercase">Section / Division</div>
            <div class="fw-bold text-dark"><?= $first_row['section'] ?> / <?= $first_row['division'] ?></div>
        </div>
        <div class="col-4 border-end text-center">
            <div class="small text-muted text-uppercase">Reporting Month</div>
            <div class="fw-bold text-primary"><?= $monthName ?></div>
        </div>
        <div class="col-4 text-end">
            <div class="small text-muted text-uppercase">Print Date</div>
            <div class="fw-bold"><?= date("d M, Y") ?></div>
        </div>
    </div>

    <table class="table table-bordered align-middle">
        <thead>
            <tr class="text-center table-light">
                <th style="width: 60px;">SL</th>
                <th class="text-start">Employee Name</th>
                <th>Designation</th>
                <th>Emp ID</th>
                <th>Total Days</th>
                <th class="text-start">Remarks</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $i = 1;
    $grand_total_days = 0;
    
    if (!empty($all_rows)) {
        foreach ($all_rows as $row) {
            $days = (int)$row['total_days'];
            $grand_total_days += $days;
            $sl_display = str_pad($i, 2, '0', STR_PAD_LEFT);
            ?>
            <tr>
                <td class="text-center text-muted fw-bold"><?= $sl_display ?></td>
                <td><div class="fw-bold text-dark"><?= htmlspecialchars($row['name']) ?></div></td>
                <td class="text-center text-secondary"><?= htmlspecialchars($row['designation']) ?></td>
                <td class="text-center"><code><?= htmlspecialchars($row['emp_id']) ?></code></td>
                <td class="text-center">
                    <span class="badge bg-white text-dark border shadow-sm px-3"><?= $days ?> Days</span>
                </td>
                <td class="small text-muted"><?= $row['remarks'] ? htmlspecialchars($row['remarks']) : '-' ?></td>
            </tr>
            <?php
            $i++;
        }
    }
    ?>
</tbody>
        <tfoot>
            <tr class="fw-bold table-light">
                <td colspan="4" class="text-end text-uppercase py-3">Total Cumulative Days:</td>
                <td class="text-center text-success fs-5"><?= $grand_total_days ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-5">
        <div class="col-4 text-center">
            <div class="mt-5 border-top border-dark pt-2 small fw-bold">PREPARED BY</div>
        </div>
        <div class="col-4 text-center">
            <div class="mt-5 border-top border-dark pt-2 small fw-bold">HEAD OF DIVISION</div>
        </div>
        <div class="col-4 text-center">
            <div class="mt-5 border-top border-dark pt-2 small fw-bold">MANAGING DIRECTOR</div>
        </div>
    </div>

    <div class="mt-5 text-center d-none d-print-block">
        <hr>
        <small class="text-muted italic">System Generated Draft Report | Internal Use Only</small>
    </div>
</div>

</body>
</html>
<?php
    } else {
        // Define the month name before the echo to avoid the syntax error
        $displayMonth = $monthNames[(int)$currentMonth] ?? 'Current';
        
        echo "
        <div class='container mt-5'>
            <div class='alert alert-info text-center shadow-sm py-5'>
                <i class='fas fa-info-circle fa-3x mb-3 text-primary'></i>
                <h4 class='fw-bold'>No Draft Records Found</h4>
                <p class='text-muted'>
                    There are no <b>Draft</b> status FC records submitted for 
                    <span class='text-primary fw-bold'>" . $displayMonth . " " . $currentYear . "</span>.
                </p>
                <hr class='w-25 mx-auto'>
                <a href='dashboard.php' class='btn btn-primary px-4 mt-2'>
                    <i class='fas fa-arrow-left me-2'></i> Return to Dashboard
                </a>
            </div>
        </div>";
    }
} else {
    echo "<div class='container mt-5 alert alert-danger'>Access Denied: Required roles not found.</div>";
}
?>