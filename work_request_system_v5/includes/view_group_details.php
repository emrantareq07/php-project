<?php
session_name('factory_work_request_db');
session_start();
require_once 'db.php';

date_default_timezone_set('Asia/Dhaka');

// Security Check (Ensure only MD Charge can see this)
if (!isset($_SESSION['emp_type']) || $_SESSION['routine_role'] !== 'md_charge') {
    die("<div class='alert alert-danger m-5'>Access Denied.</div>");
}

// Catch URL Parameters
$div   = $_GET['div'] ?? '';
$sec   = $_GET['sec'] ?? '';
$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

if (empty($div) || empty($sec)) {
    die("<div class='alert alert-warning m-5'>Missing Division or Section parameters.</div>");
}

$monthNames = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
$displayMonth = $monthNames[(int)$month] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Section Detailed View - <?= htmlspecialchars($sec) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .report-header { border-left: 5px solid #198754; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table thead { background: #212529; color: white; }
        .badge-day { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        @media print { .no-print { display: none !important; } .container { max-width: 100%; } }
    </style>
</head>
<body class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Summary
        </a>
        <button onclick="window.print()" class="btn btn-dark">
            <i class="fas fa-print me-1"></i> Print Section Report
        </button>
    </div>

    <div class="report-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="text-uppercase text-muted small fw-bold mb-1">Detailed FC List for:</h5>
                <h3 class="fw-bold text-dark mb-0"><?= htmlspecialchars($div) ?> <i class="fas fa-chevron-right mx-2 text-muted small"></i> <?= htmlspecialchars($sec) ?></h3>
                <p class="mb-0 text-secondary mt-1">
                    <i class="far fa-calendar-alt me-1"></i> Period: <b><?= $displayMonth ?> <?= $year ?></b> 
                    <span class="mx-2">|</span> 
                    <i class="fas fa-info-circle me-1"></i> Status: <span class="badge bg-warning text-dark">DRAFT</span>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <img src="../assets/img/logo.png" alt="Logo" style="height: 60px;" onerror="this.style.display='none'">
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr class="text-center">
                        <th style="width: 60px;">SL</th>
                        <th class="text-start">Employee Name</th>
                        <th>ID Number</th>
                        <th>Designation</th>
                        <th>Total Days</th>
                        <th>Total Hours</th>
                        <th class="text-start">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query for individual employee data within this specific section/division
                    $sql = "SELECT * FROM fc_tbl 
                            WHERE division = '" . mysqli_real_escape_with_backticks($conn, $div) . "' 
                            AND section = '" . mysqli_real_escape_with_backticks($conn, $sec) . "' 
                            AND MONTH(`current_date`) = '$month' 
                            AND YEAR(`current_date`) = '$year' 
                            AND status = 'draft'
                            ORDER BY name ASC";

                    $res = mysqli_query($conn, $sql);
                    $i = 1;
                    $grand_days = 0;
                    $grand_hrs = 0;

                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            // Calculate days and hours from comma strings
                            $days_count = count(array_filter(explode(',', $row['date'])));
                            $hrs_sum = array_sum(array_filter(explode(',', $row['total_hours'])));
                            
                            $grand_days += $days_count;
                            $grand_hrs  += $hrs_sum;
                    ?>
                    <tr class="text-center">
                        <td class="fw-bold text-muted"><?= $i++ ?></td>
                        <td class="text-start fw-bold text-dark"><?= htmlspecialchars($row['name']) ?></td>
                        <td><code><?= htmlspecialchars($row['emp_id']) ?></code></td>
                        <td class="small text-secondary"><?= htmlspecialchars($row['designation']) ?></td>
                        <td><span class="badge badge-day px-3"><?= $days_count ?> Days</span></td>
                        <td class="fw-bold text-primary"><?= number_format($hrs_sum, 2) ?></td>
                        <td class="text-start small text-muted text-truncate" style="max-width: 150px;">
                            <?= $row['remarks'] ? htmlspecialchars($row['remarks']) : '-' ?>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-5'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr class="text-center">
                        <td colspan="4" class="text-end text-uppercase fw-bold">Section Grand Total:</td>
                        <td><?= $grand_days ?> Days</td>
                        <td><?= number_format($grand_hrs, 2) ?> Hrs</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="row mt-5 pt-5 d-none d-print-flex">
        <div class="col-4 text-center">
            <div class="border-top border-dark pt-2 small fw-bold">Prepared By</div>
        </div>
        <div class="col-4 text-center">
            <div class="border-top border-dark pt-2 small fw-bold">Verified (HOD)</div>
        </div>
        <div class="col-4 text-center">
            <div class="border-top border-dark pt-2 small fw-bold">Approved (MD)</div>
        </div>
    </div>

</body>
</html>

<?php
// Helper function to handle backticks if necessary
function mysqli_real_escape_with_backticks($conn, $string) {
    return mysqli_real_escape_string($conn, $string);
}
?>