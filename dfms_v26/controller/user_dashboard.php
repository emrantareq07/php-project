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

// -----------------------------------------------------------------
// FERTILIZER IN TRANSIT: pending buffer_transaction rows, with
// Sender (who recorded the transaction) and Receiver (the buffer
// that import_allotment was allotted to)
// -----------------------------------------------------------------
// -----------------------------------------------------------------
// FERTILIZER IN TRANSIT: pending buffer_transaction rows.
// A row links to exactly ONE of three tables depending on which FK
// is populated:
//   - import_allotment_id  -> import_allotment.buffer_name
//   - buffer_allotment_id  -> urea_allotment.receiver
//   - prod_allotment_id    -> urea_allotment.receiver
// LEFT JOIN all three, then COALESCE picks whichever one matched
// (the other two will be NULL for that row).
// -----------------------------------------------------------------
$stockInTransit = 0.0;
$transitRows = [];

$s = mysqli_prepare($conn, "
    SELECT
        bt.created_by AS sender,
        COALESCE(ia.buffer_name, ua_buf.receiver, ua_prod.receiver) AS receiver,
        bt.amount AS amount
    FROM buffer_transaction bt
    LEFT JOIN import_allotment ia      ON bt.import_allotment_id = ia.id
    LEFT JOIN urea_allotment  ua_buf   ON bt.buffer_allotment_id = ua_buf.id
    LEFT JOIN urea_allotment  ua_prod  ON bt.prod_allotment_id  = ua_prod.id
    WHERE bt.status = 'pending'
    ORDER BY bt.created_at DESC
");
if ($s) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    while ($row = mysqli_fetch_assoc($r)) {
        $transitRows[] = $row;
        $stockInTransit += (float)$row['amount'];
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  3) BUFFER & FACTORY CURRENT TOTAL STOCK
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
// =================================================================
// $importRows = [];
// $sqlImport = "
//     SELECT
//         ia.id,
//         ia.ref_no,
//         ia.buffer_name,
//         ia.amount                                       AS allotted_amount,
//         COALESCE(SUM(CASE WHEN bt.status = 'complete'
//                           THEN bt.amount END), 0)       AS sent_amount
//     FROM import_allotment ia
//     LEFT JOIN buffer_transaction bt
//            ON bt.import_allotment_id = ia.id
//     GROUP BY ia.id, ia.ref_no, ia.buffer_name, ia.amount
//     ORDER BY ia.id DESC
// ";
// if ($res = mysqli_query($conn, $sqlImport)) {
//     while ($row = mysqli_fetch_assoc($res)) {
//         $row['remaining_amount'] = (float)$row['allotted_amount'] - (float)$row['sent_amount'];
//         $importRows[] = $row;
//     }
// }

// $totalImportAllotted  = 0.0;
// $totalImportSent      = 0.0;
// $totalImportRemaining = 0.0;
// foreach ($importRows as $row) {
//     $totalImportAllotted  += (float)$row['allotted_amount'];
//     $totalImportSent      += (float)$row['sent_amount'];
//     $totalImportRemaining += (float)$row['remaining_amount'];
// }

$importRows = []; $sqlImport = " SELECT ia.id, ia.ref_no, iu.port_name, ia.buffer_name, ia.amount AS allotted_amount, COALESCE( SUM( CASE WHEN bt.status = 'complete' THEN bt.amount ELSE 0 END ), 0 ) AS sent_amount FROM import_allotment ia LEFT JOIN import_urea iu ON iu.ref_no = ia.ref_no LEFT JOIN buffer_transaction bt ON bt.import_allotment_id = ia.id GROUP BY ia.id, ia.ref_no, iu.port_name, ia.buffer_name, ia.amount ORDER BY ia.id DESC "; 
    
    if ($res = mysqli_query($conn, $sqlImport)) { 
    while ($row = mysqli_fetch_assoc($res)) { $row['remaining_amount'] = (float)$row['allotted_amount'] - (float)$row['sent_amount']; $importRows[] = $row; } 
    mysqli_free_result($res); } 

// -------------------------------------------------- // TOTALS // -------------------------------------------------- 
$totalImportAllotted = 0.0; $totalImportSent = 0.0; $totalImportRemaining = 0.0; foreach ($importRows as $row) { $totalImportAllotted += (float)$row['allotted_amount']; $totalImportSent += (float)$row['sent_amount']; $totalImportRemaining += (float)$row['remaining_amount']; }




// =================================================================
//  AJAX: PRODUCTION BY DATE (used by the modal)
// =================================================================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'production_by_date') {
    header('Content-Type: application/json; charset=utf-8');

    $date = trim($_GET['date'] ?? date('Y-m-d', strtotime('-1 day')));
    if ($date === '' || !DateTime::createFromFormat('Y-m-d', $date)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid date']);
        exit();
    }

    $rows  = [];
    $total = 0.0;

    $sql = "SELECT id, factory_name, daily_amount, remarks, date
            FROM production_tbl
            WHERE date = ?
            ORDER BY factory_name ASC, id ASC";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $date);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($res)) {
            $rows[] = [
                'id'           => (int)$r['id'],
                'factory_name' => (string)($r['factory_name'] ?? ''),
                'daily_amount' => (float)($r['daily_amount'] ?? 0),
                'date'         => (string)($r['date'] ?? ''),
                'remarks'         => (string)($r['remarks'] ?? ''),
            ];
            $total += (float)($r['daily_amount'] ?? 0);
        }
        mysqli_stmt_close($stmt);
    }

    echo json_encode([
        'ok'    => true,
        'date'  => $date,
        'rows'  => $rows,
        'total' => $total,
    ]);
    exit();
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

    /* clickable card */
    .stat-clickable { cursor:pointer; transition:transform .15s ease, box-shadow .15s ease; }
    .stat-clickable:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(15,23,42,.15); }

    .table thead th {
        background:#f8fafc; font-size:11px; text-transform:uppercase;
        letter-spacing:.4px; color:#475569; white-space:nowrap;
    }
    .table td { vertical-align:middle; }
    .badge-soft-success { background:#d1fae5; color:#065f46; }
    .badge-soft-warning { background:#fef3c7; color:#92400e; }
    .badge-soft-danger  { background:#fee2e2; color:#991b1b; }

    .stat-card { height: 120px; min-height: 120px; max-height: 120px; padding: 15px !important; position: relative; overflow: hidden; } .stat-card .stat-label { font-size: 14px; line-height: 1.3; } .stat-card .stat-value { font-size: 28px; font-weight: 700; margin-top: 8px; } .stat-card .stat-icon { font-size: 42px; position: absolute; right: 15px; bottom: 10px; opacity: 0.15; }


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
        <!-- <div class="col-md-3">
            <div class="stat-card stat-daily p-3">
                <div class="stat-label">Total Production</div>
                <div class="stat-value"><?= h(number_format($prodAll, 2)); ?> <small class="fs-6">MT</small></div>
                <div class="small opacity-75 mt-1"><?= h($yesterday); ?></div>
                <i class="fa fa-industry stat-icon"></i>
            </div>
        </div> -->

        <div class="col-md-4">
            <div class="stat-card stat-daily stat-clickable p-3"
                 role="button"
                 data-bs-toggle="modal"
                 data-bs-target="#productionByDateModal">
                <div class="stat-label">Yearly Demand </div>
                <div class="stat-value">
                    <?= h(number_format($prodAll, 2)); ?> <small class="fs-6">MT</small>
                </div>
                <div class="small opacity-75 mt-1">
                    <i class="fa fa-mouse-pointer"></i> Click for details (<?= h($yesterday); ?>)
                </div>
                <i class="fa fa-industry stat-icon"></i>
            </div>
        </div>


        <div class="col-md-4">
            <div class="stat-card stat-daily stat-clickable p-3"
                 role="button"
                 data-bs-toggle="modal"
                 data-bs-target="#productionByDateModal">
                <div class="stat-label">Production Stock</div>
                <div class="stat-value">
                    <?= h(number_format($prodAll, 2)); ?> <small class="fs-6">MT</small>
                </div>
                <div class="small opacity-75 mt-1">
                    <i class="fa fa-mouse-pointer"></i> Click for details (<?= h($yesterday); ?>)
                </div>
                <i class="fa fa-industry stat-icon"></i>
            </div>
        </div>

        <!-- Clickable card: Total Import Remaining -->
        <div class="col-md-4">
            <div class="stat-card stat-import stat-clickable p-3"
                 role="button"
                 data-bs-toggle="modal"
                 data-bs-target="#importRemainingModal">
                <div class="stat-label">Discharge Port</div>
                <div class="stat-value"><?= h(number_format($totalImportRemaining, 2)); ?> <small class="fs-6">MT</small></div>
                <div class="small opacity-75 mt-1">
                    <i class="fa fa-mouse-pointer"></i>
                    Across <?= count($importRows); ?> import allotments (Available in PORT)
                </div>
                <i class="fa fa-cubes stat-icon"></i>
            </div>
        </div>

        <div class="col-md-4">
    <div class="stat-card stat-transit stat-clickable p-3" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#transitModal">
        <div class="stat-label">Fertilizer in Transit</div>
        <div class="stat-value"><?= h(number_format($stockInTransit, 2)); ?> <small class="fs-6">MT</small></div>
        <!-- <div class="small opacity-75 mt-1">Pending buffer transactions</div> -->
        <div class="small opacity-75 mt-1">
                    <i class="fa fa-mouse-pointer"></i> Click for breakdown
                </div>
        <i class="fa fa-truck stat-icon"></i>
    </div>
</div>

        <!-- <div class="col-md-4">
            <div class="stat-card stat-transit p-3">
                <div class="stat-label">Fertilizer in Transit</div>
                <div class="stat-value"><?= h(number_format($stockInTransit, 2)); ?> <small class="fs-6">MT</small></div>
                <div class="small opacity-75 mt-1">Pending buffer transactions</div>
                <i class="fa fa-truck stat-icon"></i>
            </div>
        </div> -->

           <div class="col-md-4">
            <div class="stat-card stat-stock stat-clickable p-3"
                 role="button"
                 data-bs-toggle="modal"
                 data-bs-target="#stockBreakdownModal">
                <div class="stat-label">Buffer/Factory Total Stock</div>
                <div class="stat-value">
                    <?= h(number_format($currentTotalStock, 2)); ?> <small class="fs-6">MT</small>
                </div>
                <div class="small opacity-75 mt-1">
                    <i class="fa fa-mouse-pointer"></i> Click for breakdown
                </div>
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div>

        <!-- <div class="col-md-3">
            <div class="stat-card stat-stock p-3">
                <div class="stat-label">Buffer/Factory Total Stock</div>
                <div class="stat-value"><?= h(number_format($currentTotalStock, 2)); ?> <small class="fs-6">MT</small></div>
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div> -->

        <div class="col-md-4"> <div class="stat-card stat-stock"> <div class="stat-label"> Total Available Stock in Bangladesh </div> <div class="stat-value"> <?= h(number_format($currentTotalStock + $totalImportRemaining)); ?> <small class="fs-6">MT</small> </div> <i class="fa fa-warehouse stat-icon"></i> </div> </div>
        
        <div class="col-md-4">
            <div class="stat-card stat-stock stat-clickable p-3"
                 role="button"
                 data-bs-toggle="modal"
                 data-bs-target="#dailySummaryModal">
                <div class="stat-label">Daily Summary Sheet</div>
                <div class="stat-value">
                    <i class="fa fa-table"></i> <small class="fs-6">Click to open</small>
                </div>
                <div class="small opacity-75 mt-1">
                    <i class="fa fa-mouse-pointer"></i> All buffers snapshot
                </div>
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div>
    </div>
 <!-- ============ MODAL: Transit Amount ============ -->
<div class="modal fade" id="transitModal" tabindex="-1" aria-labelledby="transitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transitModalLabel"><i class="fa fa-truck"></i> Fertilizer in Transit — Pending Transactions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Sender</th>
                <th>Receiver</th>
                <th class="text-end">Transit Amount (MT)</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($transitRows)): ?>
                <tr><td colspan="3" class="text-center text-muted py-3">No pending transactions.</td></tr>
              <?php else: ?>
                <?php foreach ($transitRows as $t): ?>
                  <tr>
                    <td><?= h($t['sender']); ?></td>
                    <td class="text-uppercase"><?= h($t['receiver']); ?></td>
                    <td class="text-end"><?= h(number_format((float)$t['amount'], 2)); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td colspan="2" class="text-end">Total</td>
                <td class="text-end"><?= h(number_format($stockInTransit, 2)); ?> MT</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


 <!-- ============ MODAL: DAILY SUMMARY SHEET ============ -->
<div class="modal fade" id="dailySummaryModal" tabindex="-1"
     aria-labelledby="dailySummaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="dailySummaryModalLabel">
          <i class="fa fa-table"></i>  Summary Report
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-2 py-2">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
          <span class="small text-muted">
            Total Factory, Buffers & Port : <b id="summaryBufferCount">—</b>
          </span>
           <span class="small text-muted">
            <i class="fa fa-spinner fa-spin" id="summarySpinner"></i> 
          </span> 
        </div>

        <div class="table-responsive ">
          <table class="table table-bordered align-middle mb-0 ">
            <thead>
              <tr>
                <th>#</th>
                <th>Factory / Buffer Name</th>
                <th>Zone</th>
                <th>Capacity (MT)</th>
                <th>Opening Stock (MT)</th>
                <th>Production Today (MT)</th>
                <th>Daily Receive (MT)</th>
                <th>Daily Delivery (MT)</th>
                <th>Closing Stock (MT)</th>
                <th>Pipeline Amount (MT)</th>
              </tr>
            </thead>
            <tbody id="summaryTableBody">
              <tr>
                <td colspan="10" class="text-center text-muted py-4">Loading…</td>
              </tr>
            </tbody>
            <tfoot id="summaryTableFoot" class="table-secondary fw-bold"></tfoot>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

    <!-- ============ MODAL: PRODUCTION BY DATE ============ -->
<div class="modal fade" id="productionByDateModal" tabindex="-1"
     aria-labelledby="productionByDateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="productionByDateModalLabel">
          <i class="fa fa-industry"></i> Production by Date
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Date picker -->
        <div class="row g-2 align-items-end mb-3">
          <div class="col-md-4">
            <label for="productionDateInput" class="form-label small text-uppercase text-muted mb-1">
              Select Date
            </label>
            <input type="date"
                   id="productionDateInput"
                   class="form-control"
                   value="<?= h($yesterday); ?>">
          </div>
          <div class="col-md-8">
            <button type="button" id="productionLoadBtn" class="btn btn-primary">
              <i class="fa fa-search"></i> Search
            </button>
            <span id="productionLoading" class="ms-2 text-muted small d-none">
              <i class="fa fa-spinner fa-spin"></i> Loading…
            </span>
          </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th class="text-center" >#</th>
                <th>Factory Name</th>
                <th class="text-end" >Daily Amount (MT)</th>
                <th class="text-end">Remarks</th>
              </tr>
            </thead>
            <tbody id="productionTableBody">
              <tr>
                <td colspan="3" class="text-center text-muted py-4">Loading…</td>
              </tr>
            </tbody>
            <tfoot class="table-light">
              <tr class="fw-bold">
                <td colspan="2" class="text-end">Total:</td>
                <td class="text-end text-success" id="productionTableTotal">—</td>
                <td  class="text-end"></td>
              </tr>
            </tfoot>
          </table>
        </div>

      </div>

      <div class="modal-footer">
        <span class="me-auto small text-muted">
          Date: <b id="productionCurrentDate"><?= h($yesterday); ?></b>
        </span>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

  <!-- ============ MODAL: STOCK FORMULA BREAKDOWN ============ -->
<div class="modal fade" id="stockBreakdownModal" tabindex="-1"
     aria-labelledby="stockBreakdownModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="stockBreakdownModalLabel">
          <i class="fa fa-calculator"></i> Current Total Stock — Breakdown
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-0">
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
                <td class="text-center">
                  <span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span>
                </td>
                <td class="text-end fw-semibold"><?= h(number_format($prodAll, 2)); ?></td>
              </tr>
              <tr>
                <td>Sum of Opening Balance (office_tbl)</td>
                <td class="text-center">
                  <span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span>
                </td>
                <td class="text-end fw-semibold"><?= h(number_format($openingAll, 2)); ?></td>
              </tr>
              <tr>
                <td>
                  Master Transactions IN
                  <small class="text-muted">(port_in, buffer_in, factory_in)</small>
                </td>
                <td class="text-center">
                  <span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span>
                </td>
                <td class="text-end fw-semibold"><?= h(number_format($mtIn, 2)); ?></td>
              </tr>
              <tr>
                <td>
                  Master Transactions OUT
                  <small class="text-muted">(buffer_out, factory_out)</small>
                </td>
                <td class="text-center">
                  <span class="badge badge-soft-danger"><i class="fa fa-minus"></i> Subtract</span>
                </td>
                <td class="text-end fw-semibold"><?= h(number_format($mtOut, 2)); ?></td>
              </tr>
            </tbody>
            <tfoot class="table-light">
              <tr class="fw-bold">
                <td colspan="2" class="text-end">Current Total Stock:</td>
                <td class="text-end text-success fs-5">
                  <?= h(number_format($currentTotalStock, 2)); ?>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

    

<!-- ============ MODAL: IMPORT ALLOTMENT — REMAINING DETAILS ============ -->
<div class="modal fade" id="importRemainingModal" tabindex="-1"
     aria-labelledby="importRemainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="importRemainingModalLabel">
          <i class="fa fa-list"></i> Import Allotment — Remaining Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped mb-0 align-middle">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th>Ref No</th>
                <th>Port Name(Source)</th>
                <th>Buffer/Factory(Destination)</th>
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
                  <td><?= $row['port_name'] !== null && $row['port_name'] !== '' ? h($row['port_name']) : '<span class="text-muted">—</span>'; ?></td>
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

      <div class="modal-footer">
        <span class="me-auto small text-muted">
          Rows: <?= count($importRows); ?>
        </span>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       PRODUCTION BY DATE
       ============================================================ */
    var prodModalEl  = document.getElementById('productionByDateModal');
    var dateInput    = document.getElementById('productionDateInput');
    var loadBtn      = document.getElementById('productionLoadBtn');
    var prodTbody    = document.getElementById('productionTableBody');
    var prodTotal    = document.getElementById('productionTableTotal');
    var currentDate  = document.getElementById('productionCurrentDate');
    var loadingSpan  = document.getElementById('productionLoading');

    function fmt(n) {
        n = Number(n || 0);
        return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function esc(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function loadProduction(date) {
        if (!date) return;

        loadingSpan.classList.remove('d-none');
        prodTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">Loading…</td></tr>';
        prodTotal.textContent = '—';

        fetch('?ajax=production_by_date&date=' + encodeURIComponent(date))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                loadingSpan.classList.add('d-none');

                if (!data.ok) {
                    prodTbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger py-4">'
                        + esc(data.error || 'Failed to load.') + '</td></tr>';
                    return;
                }

                currentDate.textContent = data.date;

                if (!data.rows || data.rows.length === 0) {
                    prodTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">No production records for this date.</td></tr>';
                    prodTotal.textContent = fmt(0);
                    return;
                }

                var html = '';
                data.rows.forEach(function (row, idx) {
                    html += '<tr>';
                    html += '<td class="text-center text-muted">' + (idx + 1) + '</td>';
                    html += '<td class="text-uppercase">' + esc(row.factory_name) + '</td>';
                    html += '<td class="text-end fw-semibold">' + fmt(row.daily_amount) + '</td>';
                    html += '<td class="text-end fw-semibold">' + row.remarks + '</td>';
                    html += '</tr>';
                });
                prodTbody.innerHTML = html;
                prodTotal.textContent = fmt(data.total);
            })
            .catch(function () {
                loadingSpan.classList.add('d-none');
                prodTbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger py-4">Network error.</td></tr>';
            });
    }

    if (prodModalEl) {
        prodModalEl.addEventListener('show.bs.modal', function () {
            loadProduction(dateInput.value);
        });
    }
    if (loadBtn) {
        loadBtn.addEventListener('click', function () {
            loadProduction(dateInput.value);
        });
    }
    if (dateInput) {
        dateInput.addEventListener('change', function () {
            loadProduction(dateInput.value);
        });
        dateInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                loadProduction(dateInput.value);
            }
        });
    }

    /* ============================================================
       DAILY SUMMARY SHEET
       ============================================================ */
    var sumModalEl  = document.getElementById('dailySummaryModal');
    var sumTbody    = document.getElementById('summaryTableBody');
    var sumTfoot    = document.getElementById('summaryTableFoot');
    var sumCountEl  = document.getElementById('summaryBufferCount');
    var sumSpinner  = document.getElementById('summarySpinner');
    var sumLoaded   = false;

    function renderSummary(data) {
        sumSpinner.classList.add('d-none');
        sumCountEl.textContent = data.count || 0;

        if (!data.rows || data.rows.length === 0) {
            sumTbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-4">No buffer data found.</td></tr>';
            sumTfoot.innerHTML = '';
            return;
        }

        var html = '';
        var totals = {
            capacity: 0, opening: 0, prod: 0, receive: 0,
            delivery: 0, stock: 0, pipeline: 0
        };

        data.rows.forEach(function (st, i) {
            totals.capacity += Number(st.capacity || 0);
            totals.opening  += Number(st.opening_bal || 0);
            totals.prod     += Number(st.production_today || 0);
            totals.receive  += Number(st.daily_receive || 0);
            totals.delivery += Number(st.daily_delivery || 0);
            totals.stock    += Number(st.current_stock || 0);
            totals.pipeline += Number(st.pipeline_amount || 0);

            var name = st.office_name && st.office_name !== '' ? st.office_name : st.buffer_name;

            html += '<tr>';
            html += '<td class="fw-bold">' + (i + 1) + '</td>';
            html += '<td class="text-left fw-bold text-primary">' + esc(name) + '</td>';
            html += '<td>' + esc(st.zone || '—') + '</td>';
            html += '<td>' + fmt(st.capacity) + '</td>';
            html += '<td>' + fmt(st.opening_bal) + '</td>';
            html += '<td>' + fmt(st.production_today) + '</td>';
            html += '<td>' + fmt(st.daily_receive) + '</td>';
            html += '<td class="fw-bold">' + fmt(st.daily_delivery) + '</td>';
            html += '<td class="fw-bold text-success">' + fmt(st.current_stock) + '</td>';
            html += '<td>' + fmt(st.pipeline_amount) + '</td>';
            html += '</tr>';
        });

        sumTbody.innerHTML = html;

        sumTfoot.innerHTML =
            '<tr>' +
                '<td colspan="3" class="text-end">Total =</td>' +
                '<td>' + fmt(totals.capacity) + '</td>' +
                '<td>' + fmt(totals.opening)  + '</td>' +
                '<td>' + fmt(totals.prod)     + '</td>' +
                '<td>' + fmt(totals.receive)  + '</td>' +
                '<td>' + fmt(totals.delivery) + '</td>' +
                '<td class="text-success">' + fmt(totals.stock) + '</td>' +
                '<td>' + fmt(totals.pipeline) + '</td>' +
            '</tr>';
    }

    function loadSummary() {
        sumSpinner.classList.remove('d-none');
        sumTbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-4">Loading…</td></tr>';
        sumTfoot.innerHTML = '';
        sumCountEl.textContent = '—';

        fetch('summary_reports_json.php', { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.ok) {
                    sumSpinner.classList.add('d-none');
                    sumTbody.innerHTML = '<tr><td colspan="10" class="text-center text-danger py-4">'
                        + esc(data.error || 'Failed to load.') + '</td></tr>';
                    return;
                }
                renderSummary(data);
            })
            .catch(function () {
                sumSpinner.classList.add('d-none');
                sumTbody.innerHTML = '<tr><td colspan="10" class="text-center text-danger py-4">Network error.</td></tr>';
            });
    }

    if (sumModalEl) {
        sumModalEl.addEventListener('show.bs.modal', function () {
            if (!sumLoaded) {
                sumLoaded = true;
                loadSummary();
            }
        });
    }

});
</script>

</body>
</html>