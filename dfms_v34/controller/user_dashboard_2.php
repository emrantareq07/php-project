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
//  1) DAILY PRODUCTION (YESTERDAY) — KAFCO excluded
// =================================================================
$dailyProduction = 0.0;
$yesterday = date('Y-m-d', strtotime('-1 day'));

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(daily_amount),0) AS v
    FROM production_tbl
    WHERE date = ? AND LOWER(TRIM(factory_name)) <> 'kafco'
")) {
    mysqli_stmt_bind_param($s, 's', $yesterday);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $dailyProduction = (float)$row['v'];
    mysqli_stmt_close($s);
}

// =================================================================
//  1b) TOTAL PRODUCTION (CURRENT FISCAL YEAR TILL DATE) — KAFCO excluded
// =================================================================
$prodAll_fiscalyear = 0.0;

$todayForFY = new DateTime();
$fyStartYearProd = (int)$todayForFY->format('n') >= 7
    ? (int)$todayForFY->format('Y')
    : (int)$todayForFY->format('Y') - 1;

$fiscalYearStartProd = sprintf('%d-07-01', $fyStartYearProd);
$fiscalYearEndProd   = sprintf('%d-06-30', $fyStartYearProd + 1);

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(daily_amount), 0) AS v
    FROM production_tbl
    WHERE DATE(date) BETWEEN ? AND ?
      AND LOWER(TRIM(factory_name)) <> 'kafco'
")) {
    mysqli_stmt_bind_param($s, 'ss', $fiscalYearStartProd, $fiscalYearEndProd);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $prodAll_fiscalyear = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  2) FERTILIZER STOCK IN TRANSIT (pending buffer_transaction)
// =================================================================
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
//     (KAFCO excluded from Production AND Opening Balance)
// =================================================================
$prodAll    = 0.0;
$openingAll = 0.0;
$mtIn       = 0.0;
$mtOut      = 0.0;

// ---- Total Production (current fiscal year, excluding KAFCO) ----
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(daily_amount), 0) AS v
    FROM production_tbl
    WHERE `date` >=
          CASE
              WHEN MONTH(CURDATE()) >= 7
                  THEN MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL 6 MONTH
              ELSE
                  MAKEDATE(YEAR(CURDATE()) - 1, 1) + INTERVAL 6 MONTH
          END
      AND `date` <
          CASE
              WHEN MONTH(CURDATE()) >= 7
                  THEN MAKEDATE(YEAR(CURDATE()) + 1, 1) + INTERVAL 6 MONTH
              ELSE
                  MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL 6 MONTH
          END
      AND (factory_name IS NULL OR LOWER(TRIM(factory_name)) <> 'kafco')
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $prodAll = (float) $row['v'];
    }
    mysqli_stmt_close($s);
}

// ---- Sum of Opening Balance (excluding KAFCO) ----
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(opening_bal), 0) AS v
    FROM office_tbl
    WHERE LOWER(TRIM(buffer_name)) <> 'kafco'
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $openingAll = (float) $row['v'];
    }
    mysqli_stmt_close($s);
}

// ---- Master Transactions IN (port_in, buffer_in, factory_in) ----
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(amount), 0) AS v
    FROM master_transaction
    WHERE transaction_source IN ('port_in', 'buffer_in', 'factory_in')
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $mtIn = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

// ---- Master Transactions OUT (buffer_out, factory_out, no dealer) ----
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(amount), 0) AS v
    FROM master_transaction
    WHERE transaction_source IN ('buffer_out', 'factory_out')
      AND NOT (
          transaction_source = 'factory_out'
          AND LOWER(TRIM(remarks)) = 'from kafco'
      )
      AND dealer_id = 0
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $mtOut = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

$currentTotalStock = $prodAll + $openingAll + $mtIn - $mtOut;

// ---- Outgoing to Dealer ----
$mtOutDealer = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(amount), 0) AS v
    FROM master_transaction
    WHERE transaction_source IN ('buffer_out', 'factory_out')
      AND NOT (
          transaction_source = 'factory_out'
          AND LOWER(TRIM(remarks)) = 'from kafco'
      )
      AND dealer_id <> 0
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $mtOutDealer = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  4) PER-IMPORT-ALLOTMENT REMAINING
// =================================================================
$importRows = [];
$totalImportAllotted  = 0.0;
$totalImportSent      = 0.0;
$totalImportRemaining = 0.0;

$sqlImport = "
    SELECT
        ia.id,
        ia.ref_no,
        iu.port_name,
        ia.buffer_name,
        ia.amount AS allotted_amount,
        COALESCE(
            SUM(
                CASE
                    WHEN bt.status = 'complete' THEN bt.amount
                    ELSE 0
                END
            ), 0
        ) AS sent_amount
    FROM import_allotment ia
    LEFT JOIN import_urea iu        ON iu.ref_no = ia.ref_no
    LEFT JOIN buffer_transaction bt ON bt.import_allotment_id = ia.id
    GROUP BY
        ia.id,
        ia.ref_no,
        iu.port_name,
        ia.buffer_name,
        ia.amount
    ORDER BY ia.id DESC
";

if ($s = mysqli_prepare($conn, $sqlImport)) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);

    while ($row = mysqli_fetch_assoc($r)) {
        $allotted = (float)$row['allotted_amount'];
        $sent     = (float)$row['sent_amount'];

        $row['remaining_amount'] = $allotted - $sent;
        $importRows[] = $row;

        $totalImportAllotted  += $allotted;
        $totalImportSent      += $sent;
        $totalImportRemaining += $row['remaining_amount'];
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  5) BUFFER / FACTORY WISE STOCK BREAKDOWN (office_tbl wise)
//     - Total Receive   = master_transaction IN  (port_in, buffer_in, factory_in)
//     - Exchange        = master_transaction OUT (buffer_out, factory_out, dealer_id IS NULL)
//     - Delivery        = master_transaction OUT (dealer_id IS NOT NULL) via dealer_tbl
// =================================================================
$bufferBreakdownRows = [];
$bwTotals = [
    'opening'    => 0.0,
    'production' => 0.0,
    'receive'    => 0.0,
    'exchange'   => 0.0,
    'delivery'   => 0.0,
    'closing'    => 0.0,
];

// Pull all offices first (KAFCO and port offices excluded)
$offices = [];

$sqlOffices = "
    SELECT id, office_name, buffer_name, opening_bal, office_type
    FROM office_tbl
    WHERE LOWER(TRIM(buffer_name)) <> 'kafco'
      AND office_type <> 'port_office'
    ORDER BY office_type
";

if ($res = mysqli_query($conn, $sqlOffices)) {
    while ($row = mysqli_fetch_assoc($res)) {
        $offices[] = $row;
    }
    mysqli_free_result($res);
}

$rowNum = 0;
foreach ($offices as $ot) {
    $rowNum++;
    $officeId   = (int)$ot['id'];
    $officeName = $ot['office_name'];
    $bufferName = $ot['buffer_name'];
    $opening    = (float)$ot['opening_bal'];

    // ---------- Total Production (KAFCO excluded) ----------
    $production = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(daily_amount), 0) AS v
        FROM production_tbl
        WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?))
          AND LOWER(TRIM(factory_name)) <> 'kafco'
    ")) {
        mysqli_stmt_bind_param($s, 's', $bufferName);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $production = (float)$row['v'];
        mysqli_stmt_close($s);
    }

/// ---------- Total Receive = master_transaction IN ----------
$receive = 0.0;

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS v
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    LEFT JOIN import_allotment ia      ON bt.import_allotment_id = ia.id
    LEFT JOIN urea_allotment   ua_buf  ON bt.buffer_allotment_id = ua_buf.id
    LEFT JOIN urea_allotment   ua_prod ON bt.prod_allotment_id  = ua_prod.id
    WHERE mt.transaction_source IN ('port_in', 'buffer_in', 'factory_in')
      AND (
            ia.buffer_name   = ?   -- port_in  → import_allotment.buffer_name
         OR ua_buf.receiver     = ?   -- buffer_in → urea_allotment.sender
         OR ua_prod.receiver    = ?   -- factory_in → urea_allotment.sender
      )
")) {
    mysqli_stmt_bind_param($s, 'sss', $bufferName, $bufferName, $bufferName);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $receive = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

// ---------- Buffer & Factory Exchange = master_transaction OUT (no dealer) ----------
$exchange = 0.0;

if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS v
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    LEFT JOIN import_allotment ia      ON bt.import_allotment_id = ia.id
    LEFT JOIN urea_allotment   ua_buf  ON bt.buffer_allotment_id = ua_buf.id
    LEFT JOIN urea_allotment   ua_prod ON bt.prod_allotment_id  = ua_prod.id
    WHERE mt.transaction_source IN ('buffer_out', 'factory_out')
      AND mt.dealer_id = 0
      AND (
            ia.buffer_name   = ?
         OR ua_buf.sender     = ?
         OR ua_prod.sender    = ?
      )
")) {
    mysqli_stmt_bind_param($s, 'sss', $bufferName, $bufferName, $bufferName);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $exchange = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

    // ---------- Delivery = master_transaction OUT (dealer-linked) ----------
    $delivery = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl dt ON dt.id = mt.dealer_id
        WHERE mt.transaction_source IN ('buffer_out', 'factory_out')
          AND mt.dealer_id IS NOT NULL
          AND dt.office_tbl_id = ?
    ")) {
        mysqli_stmt_bind_param($s, 'i', $officeId);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $delivery = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $closing = $opening + $production + $receive - $exchange - $delivery;

    $bufferBreakdownRows[] = [
        'sl_no'       => $rowNum,
        'buffer_name' => $officeName,
        'opening'     => $opening,
        'production'  => $production,
        'receive'     => $receive,
        'exchange'    => $exchange,
        'delivery'    => $delivery,
        'closing'     => $closing,
    ];

    $bwTotals['opening']    += $opening;
    $bwTotals['production'] += $production;
    $bwTotals['receive']    += $receive;
    $bwTotals['exchange']   += $exchange;
    $bwTotals['delivery']   += $delivery;
    $bwTotals['closing']    += $closing;
}

// =================================================================
//  AJAX: PRODUCTION BY DATE
// =================================================================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'production_by_date') {
    header('Content-Type: application/json; charset=utf-8');

    $date = trim($_GET['date'] ?? date('Y-m-d', strtotime('-1 day')));
    if ($date === '' || !DateTime::createFromFormat('Y-m-d', $date)) {
        echo json_encode(['ok' => false, 'error' => 'Invalid date']);
        exit();
    }

    $excludeFactory = strtolower(trim($_GET['exclude_factory'] ?? ''));

    $rows  = [];
    $total = 0.0;

    if ($excludeFactory !== '') {
        $sql = "SELECT id, factory_name, daily_amount, remarks, date
                FROM production_tbl
                WHERE date = ?
                  AND LOWER(TRIM(factory_name)) <> ?
                ORDER BY factory_name ASC, id ASC";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ss', $date, $excludeFactory);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            while ($r = mysqli_fetch_assoc($res)) {
                $rows[] = [
                    'id'           => (int)$r['id'],
                    'factory_name' => (string)($r['factory_name'] ?? ''),
                    'daily_amount' => (float)($r['daily_amount'] ?? 0),
                    'date'         => (string)($r['date'] ?? ''),
                    'remarks'      => (string)($r['remarks'] ?? ''),
                ];
                $total += (float)($r['daily_amount'] ?? 0);
            }
            mysqli_stmt_close($stmt);
        }
    } else {
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
                    'remarks'      => (string)($r['remarks'] ?? ''),
                ];
                $total += (float)($r['daily_amount'] ?? 0);
            }
            mysqli_stmt_close($stmt);
        }
    }

    echo json_encode([
        'ok'    => true,
        'date'  => $date,
        'rows'  => $rows,
        'total' => $total,
    ]);
    exit();
}

// =================================================================
//  YEARLY DEMAND (Fiscal Year) — from comparison_tbl
// =================================================================
$fyMonthNow = (int)date('n');
$fyYearNow  = (int)date('Y');
if ($fyMonthNow >= 7) {
    $fyStartYear = $fyYearNow;
    $fyEndYear   = $fyYearNow + 1;
} else {
    $fyStartYear = $fyYearNow - 1;
    $fyEndYear   = $fyYearNow;
}

$fiscalYearLabel = $fyStartYear . '-' . $fyEndYear;

$yearly_demand      = 0.0;
$addition           = 0.0;
$yearlyDemandTotal  = 0.0;
$fiscal_year        = $fiscalYearLabel;

if ($s = mysqli_prepare($conn, "
    SELECT yearly_demand, addition, fiscal_year
    FROM comparison_tbl
    WHERE fiscal_year = ?
    LIMIT 1
")) {
    mysqli_stmt_bind_param($s, 's', $fiscalYearLabel);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $yearly_demand = (float)($row['yearly_demand'] ?? 0);
        $addition      = (float)($row['addition']      ?? 0);
        $fiscal_year   = $row['fiscal_year'] ?? $fiscalYearLabel;
        $yearlyDemandTotal = $yearly_demand + $addition;
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  YEARLY TARGET — SUM from office_tbl
// =================================================================
$yearlyTargetTotal = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(yearly_target), 0) AS v
    FROM office_tbl
")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $yearlyTargetTotal = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}

$restOfTarget = $yearlyTargetTotal - $prodAll_fiscalyear;

// Row 6: Total Urea Fertilizer available in Bangladesh
$totalAvailableBD = ($currentTotalStock + $stockInTransit + $totalImportRemaining) - $mtOutDealer;
// Row 7: Remaining Demand
$remainingDemandRow = $yearlyDemandTotal - $totalAvailableBD;
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
        border:0; border-radius:12px; color:#fff; overflow:hidden;
        box-shadow:0 4px 14px rgba(15,23,42,.08);
        position:relative;
        height: 150px;
        min-height: 150px;
        max-height: 150px;
        padding: 16px 18px !important;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15,23,42,.15); }

    .stat-card .stat-label {
        font-size:12px; font-weight:700; text-transform:uppercase;
        letter-spacing:.6px; opacity:.95;
        display:flex; align-items:center; gap:6px;
    }
    .stat-card .stat-value {
        font-size:28px; font-weight:800; line-height:1.15; margin-top:6px;
        text-shadow: 0 2px 6px rgba(0,0,0,.12);
    }
    .stat-card .stat-value small { font-size:12px; font-weight:600; opacity:.85; }
    .stat-card .stat-sub {
        font-size:11.5px; font-weight:600; opacity:.92; margin-top:6px;
        display:flex; align-items:center; gap:5px;
    }
    .stat-card .stat-sub.pill {
        display:inline-flex; padding:2px 8px; border-radius:20px;
        background: rgba(255,255,255,.18);
        backdrop-filter: blur(4px);
    }
    .stat-card .stat-icon {
        font-size:52px;
        position:absolute; right:12px; bottom:8px;
        opacity:.16;
        pointer-events:none;
    }
    .stat-card .stat-split {
        display:flex; gap:6px; margin-top:8px;
    }
    .stat-card .stat-split .cell {
        flex:1;
        padding:4px 8px; border-radius:7px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.15);
        font-size:10.5px; line-height:1.25;
        font-weight:600;
    }
    .stat-card .stat-split .cell .k {
        display:block; font-size:9.5px; text-transform:uppercase;
        letter-spacing:.4px; opacity:.85; font-weight:700;
    }
    .stat-card .stat-split .cell .v {
        display:block; font-size:13px; font-weight:800; margin-top:1px;
    }

    .stat-daily   { background:linear-gradient(135deg,#2563eb,#1e40af); }
    .stat-transit { background:linear-gradient(135deg,#f59e0b,#b45309); }
    .stat-stock   { background:linear-gradient(135deg,#059669,#065f46); }
    .stat-import  { background:linear-gradient(135deg,#7c3aed,#5b21b6); }
    .stat-prod {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 45%, #0ea5e9 100%);
    }
    .stat-prod::before {
        content:"";
        position:absolute; top:-60px; right:-60px;
        width:180px; height:180px;
        background: radial-gradient(circle, rgba(255,255,255,.25), transparent 70%);
        pointer-events:none;
    }

    .group-card {
        background:#fff;
        border-radius:14px;
        padding:14px;
        box-shadow: 0 4px 14px rgba(15,23,42,.06);
        height: 100%;
    }
    .group-card .group-title {
        font-size:13px; font-weight:700; text-transform:uppercase;
        letter-spacing:.5px; color:#475569;
        margin-bottom:10px;
        display:flex; align-items:center; gap:6px;
    }
    .group-card .group-title i { color:#2563eb; }

    .stat-clickable { cursor:pointer; }
    .table-row-clickable { cursor:pointer; }
    .table-row-clickable:hover { background-color:#eef2ff !important; }

    .table thead th {
        background:#f8fafc; font-size:11px; text-transform:uppercase;
        letter-spacing:.4px; color:#475569; white-space:nowrap;
    }
    .table td { vertical-align:middle; }
    .badge-soft-success { background:#d1fae5; color:#065f46; }
    .badge-soft-warning { background:#fef3c7; color:#92400e; }
    .badge-soft-danger  { background:#fee2e2; color:#991b1b; }

    .stat-card.stat-card-lg {
        height: 210px;
        min-height: 210px;
        max-height: 210px;
        padding: 18px 20px !important;
    }
    .stat-card.stat-card-lg .stat-label { font-size: 13px; letter-spacing: .7px; }
    .stat-card.stat-card-lg .stat-value { font-size: 34px; margin-top: 8px; }
    .stat-card.stat-card-lg .stat-value small { font-size: 13px; }
    .stat-card.stat-card-lg .stat-sub { font-size: 12px; margin-top: 8px; }
    .stat-card.stat-card-lg .stat-split { margin-top: 10px; gap: 8px; }
    .stat-card.stat-card-lg .stat-split .cell { padding: 6px 10px; border-radius: 8px; }
    .stat-card.stat-card-lg .stat-split .cell .k { font-size: 10px; }
    .stat-card.stat-card-lg .stat-split .cell .v { font-size: 15px; margin-top: 2px; }
    .stat-card.stat-card-lg .stat-icon { font-size: 60px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <i class="fa fa-industry"></i> Digital Fertilizer Monitoring System (DFMS), BCIC
    </a>
  </div>
</nav>

<div class="container-fluid p-3">

    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h3 class="page-title mb-0">Welcome <b class="text-danger text-uppercase"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">User Dashboard</small>
        </div>

        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="summary_reports.php" class="btn btn-primary"><i class="fa fa-eye"></i> Details </a>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <!-- ============================================================
         DEMAND vs STOCK POSITION — NUMBERED BREAKDOWN TABLE
         ============================================================ -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="group-card">
                <div class="group-title">
                    <i class="fa fa-table"></i> Demand &amp; Stock Position Breakdown
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:8%;">SL No</th>
                                <th>Title</th>
                                <th class="text-end" style="width:22%;">Amount (MT)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-row-clickable" role="button"
                                data-bs-toggle="modal" data-bs-target="#demandModal">
                                <td>1.</td>
                                <td>Total Demand (Fiscal Year)</td>
                                <td class="text-end fw-bold"><?= h(number_format($yearlyDemandTotal, 2)); ?></td>
                            </tr>
                            <tr class="table-row-clickable" role="button"
                                data-bs-toggle="modal" data-bs-target="#stockBreakdownModal">
                                <td>2.</td>
                                <td>BUFFER &amp; FACTORY STOCK</td>
                                <td class="text-end fw-bold"><?= h(number_format($currentTotalStock, 2)); ?></td>
                            </tr>
                            <tr class="table-row-clickable" role="button"
                                data-bs-toggle="modal" data-bs-target="#transitModal">
                                <td>3.</td>
                                <td>FERTILIZER IN TRANSIT</td>
                                <td class="text-end fw-bold"><?= h(number_format($stockInTransit, 2)); ?></td>
                            </tr>
                            <tr class="table-row-clickable" role="button"
                                data-bs-toggle="modal" data-bs-target="#importRemainingModal">
                                <td>4.</td>
                                <td>AT PORT</td>
                                <td class="text-end fw-bold"><?= h(number_format($totalImportRemaining, 2)); ?></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>DELIVERED (DEALER)</td>
                                <td class="text-end fw-bold"><?= h(number_format($mtOutDealer, 2)); ?></td>
                            </tr>
                            <tr class="table-success table-row-clickable" role="button"
                                data-bs-toggle="modal" data-bs-target="#bufferWiseModal">
                                <td>6.</td>
                                <td>TOTAL UREA FERTILIZER AVAILABLE IN BANGLADESH (2+3+4)-(5)</td>
                                <td class="text-end fw-bold"><?= h(number_format($totalAvailableBD, 2)); ?></td>
                            </tr>
                            <tr class="table-warning">
                                <td>7.</td>
                                <td>Remaining Demand (1-6)</td>
                                <td class="text-end fw-bold"><?= h(number_format($remainingDemandRow, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="small text-muted mt-2">
                    <i class="fa fa-mouse-pointer"></i> Rows 1&ndash;4 are clickable for a detailed breakdown.
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         PRODUCTION OVERVIEW TABLE
         ============================================================ -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="group-card">
                <div class="group-title">
                    <i class="fa fa-industry"></i> Production Overview
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th class="text-end">Amount (MT)</th>
                                <th>Details</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fa fa-cogs text-primary"></i> Total Daily Production
                                    <span class="badge bg-warning text-dark">ALL FACTORY</span>
                                </td>
                                <td class="text-end fw-bold"><?= h(number_format($dailyProduction, 2)); ?></td>
                                <td>
                                    As on <?= h(date('d-M-Y', strtotime($yesterday))); ?> &middot;
                                    Till Date: <b><?= h(number_format($prodAll_fiscalyear, 2)); ?> MT</b> &middot;
                                    Yearly Target: <b><?= h(number_format($yearlyTargetTotal, 2)); ?> MT</b> &middot;
                                    Rest of Target: <b><?= h(number_format($restOfTarget, 2)); ?> MT</b>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#productionByDateModal">
                                        <i class="fa fa-search"></i> By Date
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         MODALS
         ============================================================ -->

    <!-- ==================== MODAL: TOTAL DEMAND ==================== -->
    <div class="modal fade" id="demandModal" tabindex="-1" aria-labelledby="demandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="demandModalLabel">
                        <i class="fa fa-industry"></i> Total Demand (Fiscal Year) — Breakdown
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered align-middle mb-0">
                        <tbody>
                            <tr>
                                <th class="text-start" style="width:60%;">Fiscal Year</th>
                                <td class="text-end fw-bold"><?= h($fiscal_year); ?></td>
                            </tr>
                            <tr>
                                <th class="text-start">Yearly Demand</th>
                                <td class="text-end"><?= h(number_format($yearly_demand, 2)); ?> MT</td>
                            </tr>
                            <tr>
                                <th class="text-start">Addition</th>
                                <td class="text-end"><?= h(number_format($addition, 2)); ?> MT</td>
                            </tr>
                            <tr class="table-success">
                                <th class="text-start">Total (Yearly + Addition)</th>
                                <td class="text-end fw-bold">
                                    <?= h(number_format($yearlyDemandTotal, 2)); ?> MT
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL: TRANSIT AMOUNT ============ -->
    <div class="modal fade" id="transitModal" tabindex="-1" aria-labelledby="transitModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-warning">
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
              <i class="fa fa-table"></i> Summary Report
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-2 py-2">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
              <span class="small text-muted">
                Total Factory, Buffers &amp; Port : <b id="summaryBufferCount">—</b>
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
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0 align-middle">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Factory Name</th>
                    <th class="text-end">Daily Amount (MT)</th>
                    <th class="text-end">Remarks</th>
                  </tr>
                </thead>
                <tbody id="productionTableBody">
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Loading…</td>
                  </tr>
                </tbody>
                <tfoot class="table-light">
                  <tr class="fw-bold">
                    <td colspan="2" class="text-end">Total:</td>
                    <td class="text-end text-success" id="productionTableTotal">—</td>
                    <td class="text-end"></td>
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
          <div class="modal-body p-3">
            <div class="table-responsive">
              <table class="table table-striped mb-0 align-middle">
                <thead>
                  <tr>
                    <th>Component</th>
                    <th class="text-center">Direction</th>
                    <th class="text-end">Amount (MT)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Total Production (all-time, KAFCO excluded)</td>
                    <td class="text-center">
                      <span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span>
                    </td>
                    <td class="text-end fw-semibold"><?= h(number_format($prodAll, 2)); ?></td>
                  </tr>
                  <tr>
                    <td>Sum of Opening Balance (KAFCO excluded)</td>
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

    <!-- ============ MODAL: BUFFER / FACTORY WISE STOCK BREAKDOWN ============ -->
<div class="modal fade" id="bufferWiseModal" tabindex="-1"
     aria-labelledby="bufferWiseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="bufferWiseModalLabel">
          <i class="fa fa-warehouse"></i> Total Urea Available — Buffer / Factory Wise Breakdown
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped mb-0 align-middle">
            <thead>
              <tr>
                <th class="text-center">Sl No</th>
                <th>Factory or Buffer Name</th>
                <th class="text-end">Opening Balance</th>
                <th class="text-end">Total Production (Till Date)</th>
                <th class="text-end">Total Receive</th>
                <th class="text-end">Buffer &amp; Factory Exchange</th>
                <th class="text-end">Delivery</th>
                <th class="text-end">Closing Stock</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($bufferBreakdownRows)): ?>
              <tr><td colspan="8" class="text-center text-muted py-4">No buffer/factory data found.</td></tr>
            <?php else: ?>
              <?php foreach ($bufferBreakdownRows as $bw): ?>
                <tr>
                  <td class="text-center text-muted"><?= (int)$bw['sl_no']; ?></td>
                  <td class="fw-semibold"><?= h($bw['buffer_name']); ?></td>
                  <td class="text-end"><?= h(number_format($bw['opening'], 2)); ?></td>
                  <td class="text-end"><?= h(number_format($bw['production'], 2)); ?></td>
                  <td class="text-end"><?= h(number_format($bw['receive'], 2)); ?></td>
                  <td class="text-end <?= $bw['exchange'] < 0 ? 'text-danger' : ''; ?>">
                    <?= h(number_format($bw['exchange'], 2)); ?>
                  </td>
                  <td class="text-end text-danger"><?= h(number_format($bw['delivery'], 2)); ?></td>
                  <td class="text-end fw-bold text-success"><?= h(number_format($bw['closing'], 2)); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot class="table-light">
              <!-- Sub-totals row -->
              <tr class="fw-bold">
                <td colspan="2" class="text-end">Totals:</td>
                <td class="text-end"><?= h(number_format($bwTotals['opening'], 2)); ?></td>
                <td class="text-end"><?= h(number_format($bwTotals['production'], 2)); ?></td>
                <td class="text-end"><?= h(number_format($bwTotals['receive'], 2)); ?></td>
                <td class="text-end"><?= h(number_format($bwTotals['exchange'], 2)); ?></td>
                <td class="text-end text-danger"><?= h(number_format($bwTotals['delivery'], 2)); ?></td>
                <td class="text-end text-success"><?= h(number_format($bwTotals['closing'], 2)); ?></td>
              </tr>

              <!-- AT PORT row -->
              <tr class="fw-bold">
                <td colspan="7" class="text-end">
                  <i class="fa fa-anchor text-primary"></i> AT PORT
                </td>
                <td class="text-end text-primary">
                  <?= h(number_format($totalImportRemaining, 2)); ?>
                </td>
              </tr>

              <!-- FERTILIZER IN TRANSIT row -->
              <tr class="fw-bold">
                <td colspan="7" class="text-end">
                  <i class="fa fa-truck text-warning"></i> FERTILIZER IN TRANSIT
                </td>
                <td class="text-end text-warning">
                  <?= h(number_format($stockInTransit, 2)); ?>
                </td>
              </tr>

              <!-- Grand Closing Stock row -->
              <tr class="table-success fw-bold fs-6">
                <td colspan="7" class="text-end">
                  <i class="fa fa-cubes text-success"></i> Closing Stock + AT PORT + FERTILIZER IN TRANSIT
                </td>
                <td class="text-end text-success">
                  <?= h(number_format($bwTotals['closing'] + $totalImportRemaining + $stockInTransit, 2)); ?>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <span class="me-auto small text-muted">
          Rows: <?= count($bufferBreakdownRows); ?>
        </span>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <!-- ============ MODAL: IMPORT ALLOTMENT ============ -->
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
                    <th>Port Name (Source)</th>
                    <th>Buffer / Factory (Destination)</th>
                    <th class="text-end">Allotted (MT)</th>
                    <th class="text-end">Sent (MT)</th>
                    <th class="text-end">Remaining (MT)</th>
                    <th class="text-center">Progress</th>
                  </tr>
                </thead>
                <tbody>
                <?php if (empty($importRows)): ?>
                  <tr><td colspan="8" class="text-center text-muted py-4">No import allotments found.</td></tr>
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
                    <td colspan="4" class="text-end">Totals:</td>
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

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       PRODUCTION BY DATE  (excludes factory_name = 'kafco')
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
        prodTbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Loading…</td></tr>';
        prodTotal.textContent = '—';

        fetch('?ajax=production_by_date&exclude_factory=kafco&date=' + encodeURIComponent(date))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                loadingSpan.classList.add('d-none');

                if (!data.ok) {
                    prodTbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">'
                        + esc(data.error || 'Failed to load.') + '</td></tr>';
                    return;
                }

                currentDate.textContent = data.date;

                if (!data.rows || data.rows.length === 0) {
                    prodTbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">No production records for this date.</td></tr>';
                    prodTotal.textContent = fmt(0);
                    return;
                }

                var html = '';
                data.rows.forEach(function (row, idx) {
                    html += '<tr>';
                    html += '<td class="text-center text-muted">' + (idx + 1) + '</td>';
                    html += '<td class="text-uppercase">' + esc(row.factory_name) + '</td>';
                    html += '<td class="text-end fw-semibold">' + fmt(row.daily_amount) + '</td>';
                    html += '<td class="text-end fw-semibold">' + esc(row.remarks || '') + '</td>';
                    html += '</tr>';
                });
                prodTbody.innerHTML = html;
                prodTotal.textContent = fmt(data.total);
            })
            .catch(function () {
                loadingSpan.classList.add('d-none');
                prodTbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Network error.</td></tr>';
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