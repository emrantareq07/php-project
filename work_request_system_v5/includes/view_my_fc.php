<?php
session_name('factory_work_request_db');

require_once '../db/config.php'; 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? '';

if (!$id) {
    die("Invalid Request");
}

// Fetch the specific FC record
$query = "SELECT * FROM fc_tbl WHERE id = '$id' AND emp_id = '{$_SESSION['emp_id']}'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Record not found or access denied.");
}

// Explode the comma-separated strings into arrays
$dates      = explode(',', $data['date']);
$time_from  = explode(',', $data['time_from']);
$time_to    = explode(',', $data['time_to']);
$hours      = explode(',', $data['total_hours']);
$remarks    = explode(',', $data['remarks']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View FC Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .detail-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .table thead { background: #212529; color: white; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h4 class="fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i>FC Details</h4>
        <div>
            <button onclick="window.print()" class="btn btn-dark"><i class="bi bi-printer"></i> Print</button>
            <a href="my_fc_monthwise_report.php" class="btn btn-outline-secondary ms-2">Back</a>
        </div>
    </div>

    <div class="card detail-card overflow-hidden">
        <div class="card-header bg-white border-bottom p-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small mb-1">EMPLOYEE NAME</p>
                    <h5 class="fw-bold"><?= $data['name'] ?></h5>
                </div>
                <div class="col-md-3">
                    <p class="text-muted small mb-1">ID NO</p>
                    <h6 class="fw-bold"><?= $data['emp_id'] ?></h6>
                </div>
                <div class="col-md-3 text-md-end">
                    <p class="text-muted small mb-1">MONTH/YEAR</p>
                    <h6 class="fw-bold"><?= date("F Y", strtotime($data['current_date'])) ?></h6>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="px-4 py-3">SL</th>
                        <th>Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th class="text-center">Hrs</th>
                        <th>Remarks</th>
                        <th class="text-center no-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_sum = 0;
                    for($i=0; $i < count($dates); $i++): 
                        if(empty(trim($dates[$i]))) continue;
                        $total_sum += (float)($hours[$i] ?? 0);
                    ?>
                    <tr>
                        <td class="px-4 fw-bold text-muted"><?= $i + 1 ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $dates[$i] ?></span></td>
                        <td><?= $time_from[$i] ?? '-' ?></td>
                        <td><?= $time_to[$i] ?? '-' ?></td>
                        <td class="text-center fw-bold text-success"><?= $hours[$i] ?? '0.00' ?></td>
                        <td class="text-muted small"><?= $remarks[$i] ?? '-' ?></td>
                        <td class="text-center no-print">
                            <span class="text-success"><i class="bi bi-check-circle"></i></span>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold px-4">Grand Total Hours:</td>
                        <td class="text-center fw-bold fs-5 text-primary"><?= number_format($total_sum, 2) ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="row mt-5 pt-4 text-center">
        <div class="col-4"><div class="border-top pt-2 small fw-bold">Applicant Signature</div></div>
        <div class="col-4"></div>
        <div class="col-4"><div class="border-top pt-2 small fw-bold">Verified By</div></div>
    </div>
</div>

</body>
</html>