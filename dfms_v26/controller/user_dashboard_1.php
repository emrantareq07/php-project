<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once('../db/db.php');

$logged_in_user = $_SESSION['username'];

function h($val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}

// =================================================================
//  1) DAILY PRODUCTION (YESTERDAY)
// =================================================================
$dailyProduction = 0.0;
$yesterday = date('Y-m-d', strtotime('-1 day'));

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(daily_amount),0) AS v
    FROM production_tbl
    WHERE date = ?
")) {
    mysqli_stmt_bind_param($s, 's', $yesterday);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $dailyProduction = (float)$row['v'];
    mysqli_stmt_close($s);
}

// =================================================================
//  2) FERTILIZER STOCK IN TRANSIT (pending buffer_transaction)
// =================================================================
$stockInTransit = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(amount),0) AS v
    FROM buffer_transaction
    WHERE status = 'pending'
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $stockInTransit = (float)$row['v'];
    mysqli_stmt_close($s);
}

// =================================================================
//  3) BUFFER & FACTORY CURRENT TOTAL STOCK
//     = production_all_time
//     + SUM(opening_bal)
//     + master_transaction IN (port_in, buffer_in, factory_in)
//     - master_transaction OUT (buffer_out, factory_out)
// =================================================================
$prodAll    = 0.0;
$openingAll = 0.0;
$mtIn       = 0.0;
$mtOut      = 0.0;

if ($s = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount),0) AS v FROM production_tbl")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $prodAll = (float)$row['v'];
    mysqli_stmt_close($s);
}

if ($s = mysqli_prepare($conn, "SELECT COALESCE(SUM(opening_bal),0) AS v FROM office_tbl")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $openingAll = (float)$row['v'];
    mysqli_stmt_close($s);
}

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(amount),0) AS v
    FROM master_transaction
    WHERE transaction_source IN ('port_in','buffer_in','factory_in')
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $mtIn = (float)$row['v'];
    mysqli_stmt_close($s);
}

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(amount),0) AS v
    FROM master_transaction
    WHERE transaction_source IN ('buffer_out','factory_out')
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $mtOut = (float)$row['v'];
    mysqli_stmt_close($s);
}

$currentTotalStock = $prodAll + $openingAll + $mtIn - $mtOut;

// =================================================================
//  4) PER-IMPORT-ALLOTMENT REMAINING
//     remaining = import_allotment.amount
//               - SUM(buffer_transaction.amount WHERE import_allotment_id = ia.id
//                                            AND status = 'complete')
// =================================================================
$importRows = [];
$sqlImport = "
    SELECT
        ia.id,
        ia.ref_no,
        ia.buffer_name,
        ia.amount                                       AS allotted_amount,
        COALESCE(SUM(CASE WHEN bt.status = 'complete'
                          THEN bt.amount END), 0)       AS sent_amount
    FROM import_allotment ia
    LEFT JOIN buffer_transaction bt
           ON bt.import_allotment_id = ia.id
    GROUP BY ia.id, ia.ref_no, ia.buffer_name, ia.amount
    ORDER BY ia.id DESC
";
if ($res = mysqli_query($conn, $sqlImport)) {
    while ($row = mysqli_fetch_assoc($res)) {
        $row['remaining_amount'] = (float)$row['allotted_amount'] - (float)$row['sent_amount'];
        $importRows[] = $row;
    }
}

$totalImportAllotted  = 0.0;
$totalImportSent      = 0.0;
$totalImportRemaining = 0.0;
foreach ($importRows as $row) {
    $totalImportAllotted  += (float)$row['allotted_amount'];
    $totalImportSent      += (float)$row['sent_amount'];
    $totalImportRemaining += (float)$row['remaining_amount'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - User Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { background:#f4f6fa; }
    .page-title { font-weight:700; color:#0f172a; }
    .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
    .card-header {
        border-bottom:1px solid #eef1f6;
        border-radius:14px 14px 0 0 !important;
    }
    .stat-card {
        border:0; border-radius:14px; color:#fff; overflow:hidden;
        box-shadow:0 4px 14px rgba(15,23,42,.08);
        position:relative;
    }
    .stat-card .stat-label {
        font-size:13px; font-weight:600; text-transform:uppercase;
        letter-spacing:.5px; opacity:.9;
    }
    .stat-card .stat-value {
        font-size:28px; font-weight:700; line-height:1.2;
    }
    .stat-card .stat-icon {
        font-size:48px; opacity:.28; position:absolute; right:16px; top:14px;
    }
    .stat-daily   { background:linear-gradient(135deg,#2563eb,#1e40af); }
    .stat-transit { background:linear-gradient(135deg,#f59e0b,#b45309); }
    .stat-stock   { background:linear-gradient(135deg,#059669,#065f46); }
    .stat-import  { background:linear-gradient(135deg,#7c3aed,#5b21b6); }

    .table thead th {
        background:#f8fafc; font-size:11px; text-transform:uppercase;
        letter-spacing:.4px; color:#475569; white-space:nowrap;
    }
    .table td { vertical-align:middle; }
    .badge-soft-success { background:#d1fae5; color:#065f46; }
    .badge-soft-warning { background:#fef3c7; color:#92400e; }
    .badge-soft-danger  { background:#fee2e2; color:#991b1b; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <i class="fa fa-industry"></i> Smart Fertilizer Monitoring System (SFMS), BCIC
    </a>
  </div>
</nav>

<div class="container-fluid p-3">

    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h3 class="page-title mb-0">Welcome <b class="text-danger"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">User Dashboard</small>
        </div>

        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="summary_reports.php" class="btn btn-primary"><i class="fa fa-eye"></i> Details </a>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <!-- ======================= TOP STAT CARDS ======================= -->
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-card stat-daily p-3">
                <div class="stat-label">Total Production</div>
                <div class="stat-value"><?= h(number_format($prodAll, 2)); ?> <small class="fs-6">MT</small></div>
                <div class="small opacity-75 mt-1"><?= h($yesterday); ?></div>
                <i class="fa fa-industry stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-import p-3">
                <div class="stat-label">Total Import Remaining</div>
                <div class="stat-value"><?= h(number_format($totalImportRemaining, 2)); ?> <small class="fs-6">MT</small></div>
                <div class="small opacity-75 mt-1">Across <?= count($importRows); ?> import allotments</div>
                <i class="fa fa-cubes stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-transit p-3">
                <div class="stat-label">Fertilizer in Transit</div>
                <div class="stat-value"><?= h(number_format($stockInTransit, 2)); ?> <small class="fs-6">MT</small></div>
                <div class="small opacity-75 mt-1">Pending buffer transactions</div>
                <i class="fa fa-truck stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-stock p-3">
                <div class="stat-label">Buffer/Factory Total Stock</div>
                <div class="stat-value"><?= h(number_format($currentTotalStock, 2)); ?> <small class="fs-6">MT</small></div>
                <!-- <div class="small opacity-75 mt-1">Production + Opening + IN − OUT</div> -->
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-stock p-3">
                <div class="stat-label"> Total Available Stock in Bangladesh</div>
                <div class="stat-value"><?= h(number_format($currentTotalStock + $totalImportRemaining)); ?> <small class="fs-6">MT</small></div>
                <!-- <div class="small opacity-75 mt-1">Production + Opening + IN − OUT</div> -->
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div>


    </div>

    <!-- ======================= STOCK FORMULA BREAKDOWN ======================= -->
    <div class="card mb-3">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fa fa-calculator text-primary"></i> Current Total Stock — Breakdown</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width:50%">Component</th>
                        <th class="text-center" style="width:25%">Direction</th>
                        <th class="text-end" style="width:25%">Amount (MT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Production (all-time)</td>
                        <td class="text-center"><span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span></td>
                        <td class="text-end fw-semibold"><?= h(number_format($prodAll, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Sum of Opening Balance (office_tbl)</td>
                        <td class="text-center"><span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span></td>
                        <td class="text-end fw-semibold"><?= h(number_format($openingAll, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Master Transactions IN <small class="text-muted">(port_in, buffer_in, factory_in)</small></td>
                        <td class="text-center"><span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span></td>
                        <td class="text-end fw-semibold"><?= h(number_format($mtIn, 2)); ?></td>
                    </tr>
                    <tr>
                        <td>Master Transactions OUT <small class="text-muted">(buffer_out, factory_out)</small></td>
                        <td class="text-center"><span class="badge badge-soft-danger"><i class="fa fa-minus"></i> Subtract</span></td>
                        <td class="text-end fw-semibold"><?= h(number_format($mtOut, 2)); ?></td>
                    </tr>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">Current Total Stock:</td>
                        <td class="text-end text-success fs-5"><?= h(number_format($currentTotalStock, 2)); ?></td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>

    <!-- ======================= PER-IMPORT-ALLOTMENT TABLE ======================= -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-list text-primary"></i> Import Allotment — Remaining Details</h5>
            <span class="badge bg-secondary">Rows: <?= count($importRows); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Ref No</th>
                        <th>Buffer Name</th>
                        <th class="text-end">Allotted (MT)</th>
                        <th class="text-end">Sent (MT)</th>
                        <th class="text-end">Remaining (MT)</th>
                        <th class="text-center">Progress</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($importRows)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No import allotments found.</td></tr>
                <?php else: ?>
                    <?php foreach ($importRows as $row):
                        $allotted = (float)$row['allotted_amount'];
                        $sent     = (float)$row['sent_amount'];
                        $remain   = (float)$row['remaining_amount'];
                        $pct      = $allotted > 0 ? min(100, max(0, ($sent / $allotted) * 100)) : 0;
                        $barColor = $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-primary' : 'bg-warning');
                    ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$row['id']; ?></td>
                            <td><?= $row['ref_no'] !== null && $row['ref_no'] !== '' ? h($row['ref_no']) : '<span class="text-muted">—</span>'; ?></td>
                            <td><?= $row['buffer_name'] !== null && $row['buffer_name'] !== '' ? h($row['buffer_name']) : '<span class="text-muted">—</span>'; ?></td>
                            <td class="text-end"><?= h(number_format($allotted, 2)); ?></td>
                            <td class="text-end"><?= h(number_format($sent, 2)); ?></td>
                            <td class="text-end fw-semibold <?= $remain > 0 ? 'text-warning' : 'text-success'; ?>">
                                <?= h(number_format($remain, 2)); ?>
                            </td>
                            <td class="text-center" style="min-width:140px;">
                                <div class="progress" style="height:16px;">
                                    <div class="progress-bar <?= $barColor; ?>"
                                         role="progressbar"
                                         style="width: <?= h(number_format($pct, 1, '.', '')); ?>%;"
                                         aria-valuenow="<?= h(number_format($pct, 1, '.', '')); ?>"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        <?= h(number_format($pct, 0)); ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Totals:</td>
                        <td class="text-end"><?= h(number_format($totalImportAllotted, 2)); ?></td>
                        <td class="text-end"><?= h(number_format($totalImportSent, 2)); ?></td>
                        <td class="text-end text-warning"><?= h(number_format($totalImportRemaining, 2)); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>