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
$transitRows    = [];

$s = mysqli_prepare($conn, "
    SELECT
        bt.created_by                                       AS sender,
        bt.medium                                           AS medium,
        DATE(bt.created_at)                                 AS departure_date,
        COALESCE(ia.buffer_name, ua_buf.receiver, ua_prod.receiver) AS receiver,
        bt.amount                                           AS amount
    FROM buffer_transaction bt
    LEFT JOIN import_allotment ia     ON bt.import_allotment_id = ia.id
    LEFT JOIN urea_allotment  ua_buf  ON bt.buffer_allotment_id = ua_buf.id
    LEFT JOIN urea_allotment  ua_prod ON bt.prod_allotment_id  = ua_prod.id
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
//     (Rows where allotted == sent are EXCLUDED — fully completed)
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
                    WHEN bt.status = 'pending' THEN bt.amount
                    ELSE 0
                END
            ), 0
        ) AS sent_amount
    FROM import_allotment ia
    LEFT JOIN import_urea iu        ON iu.ref_no = ia.ref_no
    LEFT JOIN buffer_transaction bt ON bt.import_allotment_id = ia.id
    GROUP BY ia.id, ia.ref_no, iu.port_name, ia.buffer_name, ia.amount
    ORDER BY ia.id DESC
";

if ($s = mysqli_prepare($conn, $sqlImport)) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    while ($row = mysqli_fetch_assoc($r)) {
        $allotted = (float)$row['allotted_amount'];
        $sent     = (float)$row['sent_amount'];

        // Skip fully completed allotments (allotted == sent, or over-sent)
        if ($allotted > 0 && $sent >= $allotted) {
            continue;
        }

        $row['remaining_amount'] = $allotted - $sent;
        $importRows[] = $row;
        $totalImportAllotted  += $allotted;
        $totalImportSent      += $sent;
        $totalImportRemaining += $row['remaining_amount'];
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  5) BUFFER / FACTORY WISE STOCK BREAKDOWN
// =================================================================
$bufferBreakdownRows = [];
$bwTotals = [
    'opening' => 0.0, 'production' => 0.0, 'receive' => 0.0,
    'exchange' => 0.0, 'delivery' => 0.0, 'closing' => 0.0,
];

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
                ia.buffer_name   = ?
             OR ua_buf.receiver  = ?
             OR ua_prod.receiver = ?
          )
    ")) {
        mysqli_stmt_bind_param($s, 'sss', $bufferName, $bufferName, $bufferName);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $receive = (float)$row['v'];
        mysqli_stmt_close($s);
    }

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
             OR ua_buf.sender    = ?
             OR ua_prod.sender   = ?
          )
    ")) {
        mysqli_stmt_bind_param($s, 'sss', $bufferName, $bufferName, $bufferName);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $exchange = (float)$row['v'];
        mysqli_stmt_close($s);
    }

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
        'sl_no' => $rowNum, 'buffer_name' => $officeName,
        'opening' => $opening, 'production' => $production,
        'receive' => $receive, 'exchange' => $exchange,
        'delivery' => $delivery, 'closing' => $closing,
    ];

    $bwTotals['opening']    += $opening;
    $bwTotals['production'] += $production;
    $bwTotals['receive']    += $receive;
    $bwTotals['exchange']   += $exchange;
    $bwTotals['delivery']   += $delivery;
    $bwTotals['closing']    += $closing;
}

// =================================================================
//  6) DEALER DELIVERY REPORT DATA
//     - Buffer/Factory dropdown = office_tbl (excl. kafco & port_office)
//     - Active dealers only (dealer_tbl.status = 'active')
//     - Daily / Monthly / Yearly(Fiscal) sums from master_transaction.created_at
// =================================================================

// 6a) Buffer/Factory options for the dropdown
$dealerBufferOptions = [];
$sqlDealerBuffers = "
    SELECT id, office_name, buffer_name, office_type
    FROM office_tbl
    WHERE LOWER(TRIM(buffer_name)) <> 'kafco'
      AND office_type <> 'port_office'
    ORDER BY buffer_name ASC
";
if ($res = mysqli_query($conn, $sqlDealerBuffers)) {
    while ($row = mysqli_fetch_assoc($res)) {
        $dealerBufferOptions[] = $row;
    }
    mysqli_free_result($res);
}

// 6b) Date bounds for buckets
$today        = date('Y-m-d');                        // for Daily
$monthStart   = date('Y-m-01');                       // for Monthly
$monthEnd     = date('Y-m-t');                        // for Monthly
$fyStartDel   = $fiscalYearStartProd;                 // Jul 1 of current FY
$fyEndDel     = $fiscalYearEndProd;                   // Jun 30 of current FY

// 6c) Fetch all active dealers, grouped by office_tbl_id
$dealersByOffice = [];
$sqlDealers = "
    SELECT id, office_tbl_id, name, nid, mobile_no, address, dealer_code, status
    FROM dealer_tbl
    WHERE LOWER(TRIM(status)) = 'active'
    ORDER BY name ASC
";
if ($res = mysqli_query($conn, $sqlDealers)) {
    while ($row = mysqli_fetch_assoc($res)) {
        $officeId = (int)$row['office_tbl_id'];
        if (!isset($dealersByOffice[$officeId])) {
            $dealersByOffice[$officeId] = [];
        }
        $dealersByOffice[$officeId][] = $row;
    }
    mysqli_free_result($res);
}

// 6d) Fetch all dealer-linked transactions (buffer_out / factory_out, dealer_id <> 0)
//     Aggregate in PHP so we can build daily/monthly/yearly buckets cleanly.
$dealerTxnRows = [];
$sqlDealerTxn = "
    SELECT
        mt.id,
        mt.dealer_id,
        mt.amount,
        mt.created_at
    FROM master_transaction mt
    INNER JOIN dealer_tbl dt ON dt.id = mt.dealer_id
    WHERE mt.transaction_source IN ('buffer_out', 'factory_out')
      AND mt.dealer_id <> 0
      AND LOWER(TRIM(dt.status)) = 'active'
      AND NOT (
          mt.transaction_source = 'factory_out'
          AND LOWER(TRIM(mt.remarks)) = 'from kafco'
      )
    ORDER BY mt.created_at DESC
";
if ($s = mysqli_prepare($conn, $sqlDealerTxn)) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    while ($row = mysqli_fetch_assoc($r)) {
        $dealerTxnRows[] = $row;
    }
    mysqli_stmt_close($s);
}

// 6e) Build per-dealer totals map keyed by dealer_id
//     ['daily'=>float, 'monthly'=>float, 'yearly'=>float]
$dealerTotalsMap = [];

foreach ($dealerTxnRows as $t) {
    $dealerId = (int)$t['dealer_id'];

    $amt = (float)$t['amount'];
    $ts  = strtotime($t['created_at']);
    if ($ts === false) {
        continue;
    }
    $txnDate = date('Y-m-d', $ts);

    if (!isset($dealerTotalsMap[$dealerId])) {
        $dealerTotalsMap[$dealerId] = ['daily' => 0.0, 'monthly' => 0.0, 'yearly' => 0.0];
    }

    // Daily (today)
    if ($txnDate === $today) {
        $dealerTotalsMap[$dealerId]['daily'] += $amt;
    }
    // Monthly (current calendar month)
    if ($txnDate >= $monthStart && $txnDate <= $monthEnd) {
        $dealerTotalsMap[$dealerId]['monthly'] += $amt;
    }
    // Yearly (current fiscal year: Jul 1 → Jun 30)
    if ($txnDate >= $fyStartDel && $txnDate <= $fyEndDel) {
        $dealerTotalsMap[$dealerId]['yearly'] += $amt;
    }
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
    $rows = []; $total = 0.0;

    $sql = "SELECT id, factory_name, daily_amount, remarks, date
            FROM production_tbl
            WHERE date = ?";
    $params = [$date];
    $types = 's';

    if ($excludeFactory !== '') {
        $sql .= " AND LOWER(TRIM(factory_name)) <> ?";
        $params[] = $excludeFactory;
        $types .= 's';
    }
    $sql .= " ORDER BY factory_name ASC, id ASC";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($res)) {
            $rows[] = [
                'id' => (int)$r['id'],
                'factory_name' => (string)($r['factory_name'] ?? ''),
                'daily_amount' => (float)($r['daily_amount'] ?? 0),
                'date' => (string)($r['date'] ?? ''),
                'remarks' => (string)($r['remarks'] ?? ''),
            ];
            $total += (float)($r['daily_amount'] ?? 0);
        }
        mysqli_stmt_close($stmt);
    }

    echo json_encode(['ok' => true, 'date' => $date, 'rows' => $rows, 'total' => $total]);
    exit();
}

// =================================================================
//  AJAX: DEALER DELIVERY BY BUFFER
//     Returns ALL active dealers for the selected buffer/factory.
//     Dealers without transactions are shown with 0.00 values.
// =================================================================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'dealer_delivery_by_buffer') {
    header('Content-Type: application/json; charset=utf-8');

    $officeId = isset($_GET['office_id']) ? (int)$_GET['office_id'] : 0;

    // Build list of dealers to display
    if ($officeId > 0) {
        $dealersToShow = isset($dealersByOffice[$officeId]) ? $dealersByOffice[$officeId] : [];
        // Resolve buffer_name for the selected office
        $selectedBufferName = '';
        foreach ($dealerBufferOptions as $opt) {
            if ((int)$opt['id'] === $officeId) {
                $selectedBufferName = $opt['buffer_name'];
                break;
            }
        }
    } else {
        // All buffers → flatten all active dealers
        $dealersToShow = [];
        foreach ($dealersByOffice as $offId => $list) {
            foreach ($list as $d) {
                $dealersToShow[] = $d;
            }
        }
        $selectedBufferName = '';
    }

    // Sort dealers alphabetically by name
    usort($dealersToShow, function ($a, $b) {
        return strcasecmp($a['name'] ?? '', $b['name'] ?? '');
    });

    $rows = [];
    $sl   = 0;
    $tDaily = 0.0; $tMonthly = 0.0; $tYearly = 0.0;

    foreach ($dealersToShow as $d) {
        $sl++;
        $dealerId = (int)$d['id'];
        $offId    = (int)$d['office_tbl_id'];

        // Resolve buffer_name for this dealer's office
        $bufferName = '';
        if ($officeId > 0) {
            $bufferName = $selectedBufferName;
        } else {
            foreach ($dealerBufferOptions as $opt) {
                if ((int)$opt['id'] === $offId) {
                    $bufferName = $opt['buffer_name'];
                    break;
                }
            }
        }

        $totals = isset($dealerTotalsMap[$dealerId])
            ? $dealerTotalsMap[$dealerId]
            : ['daily' => 0.0, 'monthly' => 0.0, 'yearly' => 0.0];

        $rows[] = [
            'sl_no'        => $sl,
            'buffer_name'  => (string)$bufferName,
            'dealer_name'  => (string)($d['name'] ?? ''),
            'dealer_code'  => (string)($d['dealer_code'] ?? ''),
            'nid'          => (string)($d['nid'] ?? ''),
            'mobile_no'    => (string)($d['mobile_no'] ?? ''),
            'address'      => (string)($d['address'] ?? ''),
            'status'       => (string)($d['status'] ?? ''),
            'daily'        => (float)$totals['daily'],
            'monthly'      => (float)$totals['monthly'],
            'yearly'       => (float)$totals['yearly'],
        ];

        $tDaily   += $totals['daily'];
        $tMonthly += $totals['monthly'];
        $tYearly  += $totals['yearly'];
    }

    echo json_encode([
        'ok'      => true,
        'office_id' => $officeId,
        'rows'    => $rows,
        'totals'  => [
            'daily'   => $tDaily,
            'monthly' => $tMonthly,
            'yearly'  => $tYearly,
        ],
    ]);
    exit();
}

// =================================================================
//  YEARLY DEMAND (Fiscal Year)
// =================================================================
$fyMonthNow = (int)date('n');
$fyYearNow  = (int)date('Y');
if ($fyMonthNow >= 7) {
    $fyStartYear = $fyYearNow;       $fyEndYear = $fyYearNow + 1;
} else {
    $fyStartYear = $fyYearNow - 1;   $fyEndYear = $fyYearNow;
}

$fiscalYearLabel = $fyStartYear . '-' . $fyEndYear;

$yearly_demand = 0.0; $addition = 0.0; $yearlyDemandTotal = 0.0;
$fiscal_year = $fiscalYearLabel;

if ($s = mysqli_prepare($conn, "
    SELECT yearly_demand, addition, fiscal_year
    FROM comparison_tbl WHERE fiscal_year = ? LIMIT 1
")) {
    mysqli_stmt_bind_param($s, 's', $fiscalYearLabel);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $yearly_demand = (float)($row['yearly_demand'] ?? 0);
        $addition      = (float)($row['addition'] ?? 0);
        $fiscal_year   = $row['fiscal_year'] ?? $fiscalYearLabel;
        $yearlyDemandTotal = $yearly_demand + $addition;
    }
    mysqli_stmt_close($s);
}

// =================================================================
//  YEARLY TARGET
// =================================================================
$yearlyTargetTotal = 0.0;
if ($s = mysqli_prepare($conn, "SELECT COALESCE(SUM(yearly_target), 0) AS v FROM office_tbl")) {
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) {
        $yearlyTargetTotal = (float)$row['v'];
    }
    mysqli_stmt_close($s);
}
// $mtOutDealerOpening_bal=500;

$restOfTarget = $yearlyTargetTotal - $prodAll_fiscalyear;
$totalAvailableBD = ($currentTotalStock + $stockInTransit + $totalImportRemaining) - $mtOutDealer;
$remainingDemandRow = $yearlyDemandTotal - $mtOutDealer;
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
    .card-header { border-bottom:1px solid #eef1f6; border-radius:14px 14px 0 0 !important; }

    .group-card {
        background:#ffffff;
        border-radius:14px;
        padding:14px;
        box-shadow: 0 4px 14px rgba(15,23,42,.06);
        height: 100%;
    }
    .group-card .group-title {
        font-size:14px; font-weight:700; text-transform:uppercase;
        letter-spacing:.5px; color:#1e293b;
        margin-bottom:12px;
        display:flex; align-items:center; gap:8px;
    }
    .group-card .group-title i { color:#2563eb; }

    .table thead th {
        background:#f8fafc; font-size:12px; text-transform:uppercase;
        letter-spacing:.4px; color:#475569; white-space:nowrap;
        padding: 10px 12px;
    }
    .table td { vertical-align:middle; }
    .badge-soft-success { background:#d1fae5; color:#065f46; }
    .badge-soft-warning { background:#fef3c7; color:#92400e; }
    .badge-soft-danger  { background:#fee2e2; color:#991b1b; }

    /* ============================================================
       BREAKDOWN TABLE — Clean white, colored titles, normal font
       ============================================================ */
    .breakdown-table {
        border-collapse: separate;
        border-spacing: 0;
        background:#ffffff;
    }
    .breakdown-table tbody tr {
        border-left: 5px solid transparent;
        background:#ffffff;
    }
    .breakdown-table tbody tr td {
        padding: 10px 12px !important;
        font-size: 14px;
        background: transparent;
    }
    .breakdown-table tbody tr td:first-child {
        font-weight: 700;
        font-size: 14px;
        color: #94a3b8;
        text-align: center;
        width: 60px;
    }
    .breakdown-table .icon-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: 8px;
        color: #fff;
        font-size: 14px;
        margin-right: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
        flex-shrink: 0;
    }
    .breakdown-table .title-cell {
        display: flex;
        align-items: center;
        font-weight: 800;
        font-size: 25px;
        line-height: 1.3;
    }
    .breakdown-table .title-sub {
        display: block;
        font-size: 11.5px;
        color: #94a3b8;
        font-weight: 500;
        margin-left: 40px;
        margin-top: 1px;
    }
    .breakdown-table .title-sub-1 {
        
        font-size: 11.5px;
        
        font-weight: 500;
        margin-left: 40px;
        margin-top: 1px;
    }
    .breakdown-table .amt-cell {
        font-weight: 800;
        font-size: 25px;
        letter-spacing: .3px;
        font-variant-numeric: tabular-nums;
    }

    /* Colored row titles (matching each row's theme) */
    .row-demand   .title-cell span:last-child { color: #1d4ed8; }
    .row-stock    .title-cell span:last-child { color: #047857; }
    .row-transit  .title-cell span:last-child { color: #b45309; }
    .row-port     .title-cell span:last-child { color: #6d28d9; }
    .row-delivery .title-cell span:last-child { color: #b91c1c; }
    .row-total    .title-cell span:last-child { color: #065f46; font-size: 25px; }
    .row-remain   .title-cell span:last-child { color: #a16207; font-size: 25px; }

    /* Icon pill color themes */
    .ip-blue    { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
    .ip-orange  { background: linear-gradient(135deg,#f59e0b,#b45309); }
    .ip-green   { background: linear-gradient(135deg,#10b981,#047857); }
    .ip-purple  { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
    .ip-red     { background: linear-gradient(135deg,#ef4444,#b91c1c); }
    .ip-teal    { background: linear-gradient(135deg,#06b6d4,#0e7490); }
    .ip-yellow  { background: linear-gradient(135deg,#eab308,#a16207); }

    /* Left accent border on each row */
    .row-demand   { border-left-color:#3b82f6 !important; }
    .row-stock    { border-left-color:#10b981 !important; }
    .row-transit  { border-left-color:#f59e0b !important; }
    .row-port     { border-left-color:#8b5cf6 !important; }
    .row-delivery { border-left-color:#ef4444 !important; }
    .row-total    { border-left-color:#059669 !important; }
    .row-remain   { border-left-color:#ca8a04 !important; }

    /* Amount colors match each row's theme */
    .row-demand   .amt-cell { color:#1d4ed8; }
    .row-stock    .amt-cell { color:#047857; }
    .row-transit  .amt-cell { color:#b45309; }
    .row-port     .amt-cell { color:#6d28d9; }
    .row-delivery .amt-cell { color:#b91c1c; }
    .row-total    .amt-cell { color:#065f46; }
    .row-remain   .amt-cell { color:#a16207; }

    /* Clickable rows — NO hover effect */
    .table-row-clickable {
        cursor: pointer;
    }

    /* Reload button */
    .btn-reload {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color:#fff; border:0; font-weight:700;
        padding: 8px 18px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(37,99,235,.30);
        transition: all .2s ease;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-reload:hover {
        color:#fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37,99,235,.45);
    }
    .btn-reload:active { transform: translateY(0); }
    .btn-reload .fa { transition: transform .4s ease; }
    .btn-reload:hover .fa { transform: rotate(180deg); }
    .btn-reload.loading .fa { animation: spin .8s linear infinite; }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ============================================================
       PRODUCTION OVERVIEW TABLE — clean white with colored title
       ============================================================ */
    .production-table {
        background:#ffffff;
    }
    .production-table tbody tr td {
        padding: 12px 12px !important;
        font-size: 14px;
        background:#ffffff;
        vertical-align: middle;
    }
    .production-table tbody tr td:first-child {
        border-left: 5px solid #2563eb;
        font-weight: 700;
    }
    .production-table .prod-title {
        display: inline-flex;
        align-items: center;
        font-weight: 700;
        font-size: 14px;
        color: #1d4ed8;
    }
    .production-table .prod-title i {
        color: #2563eb;
        font-size: 16px;
        margin-right: 8px;
    }
    .production-table .prod-amt {
        font-weight: 800;
        font-size: 15px;
        color: #065f46;
        font-variant-numeric: tabular-nums;
    }
    .production-table .prod-details {
        font-size: 13px;
        color: #475569;
    }
    .production-table .prod-details b { color: #1e293b; }

    /* Dealer report styling */
    .dealer-info-cell { font-size: 13px; line-height: 1.4; }
    .dealer-info-cell .d-name { font-weight: 700; color:#1e293b; }
    .dealer-info-cell .d-meta { color:#64748b; }
    .dealer-info-cell .d-meta i { width: 14px; color:#94a3b8; }
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

        <div class="col-md-6 text-md-end mt-2 mt-md-0 d-flex justify-content-md-end gap-2">
            <button type="button" id="reloadBtn" class="btn-reload" onclick="reloadPage(this)">
                <i class="fa fa-refresh"></i>
                <span>Reload</span>
            </button>
            <a href="all_reports.php" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> All Report </a>
            <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout </a>
        </div>
    </div>

     <!-- ============================================================
         DEMAND vs STOCK POSITION — COLORFUL BREAKDOWN TABLE
         ============================================================ -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="group-card">
                <div class="group-title text-success "><h2 class="fw-bold">
                    <i class="fa fa-table"></i> Demand &amp; Stock Position Breakdown</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0 breakdown-table table-striped">
                        <thead>
                            <tr>
    <th style="width:8%;" class="text-center fs-4 fw-bold">SL No</th>
    <th style="width:60%;" class="fs-4 fw-bold">Title</th>
    <th class="fs-4 fw-bold">Amount (M.T)</th>
</tr>
                        </thead>
                        <tbody>
                            <tr class="table-row-clickable row-demand" role="button"
                                data-bs-toggle="modal" data-bs-target="#demandModal">
                                <td>1.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-blue"><i class="fa fa-line-chart"></i></span>
                                        <span class="text-uppercase">Total Demand (Fiscal Year)</span>
                                    </div>
                                    <span class="title-sub">Fiscal year <?= h($fiscal_year); ?> — Yearly + Addition
                                        <i class="fa fa-mouse-pointer ms-5"></i>
                                    <span class="ms-1">Click for breakdown</span>
                                    </span>
                                </td>
                                <td class=" amt-cell text-primary"><?= h(number_format($yearlyDemandTotal, 2)); ?></td>
                            </tr>

                            <!-- ROW 5 — clickable, opens dealer delivery modal -->
                            <tr class="table-row-clickable row-delivery" role="button"
                                data-bs-toggle="modal" data-bs-target="#dealerDeliveryModal">
                                <td>2.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-red"><i class="fa fa-handshake-o"></i></span>
                                        <span>DELIVERED (TO DEALER)</span>
                                    </div>
                                    <span class="title-sub">Total Distribution Record
                                        <i class="fa fa-mouse-pointer ms-5"></i>
                                    <span class="ms-1">Click for breakdown</span>
                                    </span>
                                </td>
                                <td class=" amt-cell text-danger"><?= h(number_format($mtOutDealer, 2)); ?></td>
                            </tr>

                            <tr class="row-remain">
                                <td>3.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-yellow"><i class="fa fa-exclamation-triangle"></i></span>
                                        <span>Remaining Demand</span>
                                    </div>
                                    <!-- <span class=" badge-soft-success title-sub text-dark"></span> -->
                                    <span class="badge bg-primary title-sub-1">(1) − (2)</span>
                                </td>
                                <td class=" amt-cell" style="color:#a16207;"><?= h(number_format($remainingDemandRow, 2)); ?></td>
                            </tr>

                          <!--   <tr class="table-row-clickable row-stock" role="button"
                                data-bs-toggle="modal" data-bs-target="#stockBreakdownModal">
                                <td>2.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-green"><i class="fa fa-cubes"></i></span>
                                        <span>BUFFER &amp; FACTORY STOCK</span>
                                    </div>
                                    <span class="title-sub">Production + Opening + Receive − Exchange
                                        <i class="fa fa-mouse-pointer ms-5"></i>
                                    <span class="ms-1">Click for breakdown</span>
                                    </span>
                                </td>
                                <td class=" amt-cell"><?= h(number_format($currentTotalStock, 2)); ?></td>
                            </tr> -->
                         <tr class="table-row-clickable row-port" role="button"
                                data-bs-toggle="modal" data-bs-target="#importRemainingModal">
                                <td>4.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-purple"><i class="fa fa-anchor"></i></span>
                                        <span>AT PORT</span>
                                    </div>
                                    <span class="title-sub">Import allotment remaining
                                    <i class="fa fa-mouse-pointer ms-5"></i>
                                    <span class="ms-1">Click for breakdown</span>
                                </span>
                                </td>
                                <td class=" amt-cell"><?= h(number_format($totalImportRemaining, 2)); ?></td>
                            </tr>

                            <tr class="table-row-clickable row-transit" role="button"
                                data-bs-toggle="modal" data-bs-target="#transitModal">
                                <td>5.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-orange"><i class="fa fa-truck"></i></span>
                                        <span>FERTILIZER IN TRANSIT </span>
                                    </div>
                                    <span class="title-sub">Pending buffer_transaction records
                                        <i class="fa fa-mouse-pointer ms-5"></i>
                                    <span class="ms-1">Click for breakdown</span>

                                    </span>
                                </td>
                                <td class=" amt-cell ip-orange" style="color:#b45309;"><?= h(number_format($stockInTransit, 2)); ?></td>
                            </tr>

                            <tr class="table-row-clickable row-total bg-light border-top border-bottom border-2 border-dark"
                                role="button"
                                data-bs-toggle="modal"
                                data-bs-target="#bufferWiseModal">
                                <td>6.</td>
                                <td>
                                    <div class="title-cell">
                                        <span class="icon-pill ip-teal">
                                            <i class="fa fa-check-circle"></i>
                                        </span>
                                        <span class="text-uppercase">Current Stock</span>
                                    </div>
                                    <span class="badge bg-primary title-sub-1">
                                    (Closing Stock + 4 + 5) − 2</span>
                                    <i class="fa fa-mouse-pointer ms-5" style="color: #94a3b8;"></i>
                                    <span class="ms-1" style="color: #94a3b8;">Click for breakdown</span>

                                <!-- <span class="badge bg-primary title-sub-1">(1) − (6) + (5)</span> -->
                                </td>
                                <td class=" amt-cell">
                                    <?= h(number_format($totalAvailableBD, 2)); ?>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="small text-muted mt-2">
                    <i class="fa fa-mouse-pointer"></i> Rows 1&ndash;6 are clickable for a detailed breakdown.
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
                    <table class="table table-bordered align-middle mb-0 production-table">
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
                                <td>
                                    <span class="prod-title">
                                        <i class="fa fa-cogs"></i> Total Daily Production
                                    </span>
                                    <span class="badge bg-warning text-dark ms-2">ALL FACTORY</span>
                                </td>
                                <td class="text-end prod-amt"><?= h(number_format($dailyProduction, 2)); ?></td>
                                <td class="prod-details">
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
                                <td class="text-end fw-bold"><?= h(number_format($yearlyDemandTotal, 2)); ?> MT</td>
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
        <h5 class="modal-title" id="transitModalLabel">
          <i class="fa fa-truck"></i> Fertilizer in Transit — Pending Transactions
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered align-middle">
            <thead>
              <tr>
                <th>Sender</th>
                <th>Receiver</th>
                <th class="text-end">Transit Amount (MT)</th>
                <th>Medium</th>
                <th>Departure Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($transitRows)): ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-3">No pending transactions.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($transitRows as $t): ?>
                  <tr>
                    <td><?= h($t['sender']); ?></td>
                    <td class="text-uppercase"><?= h($t['receiver']); ?></td>
                    <td class="text-end"><?= h(number_format((float)$t['amount'], 2)); ?></td>
                    <td class="text-uppercase"><?= h($t['medium']); ?></td>
                    <td class="text-uppercase"><?= h($t['departure_date']); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot>
              <tr class="fw-bold">
                <td colspan="2" class="text-end">Total</td>
                <td class="text-end"><?= h(number_format($stockInTransit, 2)); ?> MT</td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          </table>
        </div>
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
                <input type="date" id="productionDateInput" class="form-control" value="<?= h($yesterday); ?>">
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
                  <tr><td colspan="4" class="text-center text-muted py-4">Loading…</td></tr>
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
                    <td>Total Production (Till Date)</td>
                    <td class="text-center"><span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span></td>
                    <td class="text-end fw-semibold"><?= h(number_format($prodAll, 2)); ?></td>
                  </tr>
                  <tr>
                    <td>Opening Balance </td>
                    <td class="text-center"><span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span></td>
                    <td class="text-end fw-semibold"><?= h(number_format($openingAll, 2)); ?></td>
                  </tr>
                  <tr>
                    <td>Total Receive<small class="text-muted"></small></td>
                    <td class="text-center"><span class="badge badge-soft-success"><i class="fa fa-plus"></i> Add</span></td>
                    <td class="text-end fw-semibold"><?= h(number_format($mtIn, 2)); ?></td>
                  </tr>
                  <tr>
                    <td>Transfer to Other Godown <small class="text-muted"></small></td>
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
          <div class="modal-body p-2">
            <div class="table-responsive">
              <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                  <tr>
                    <th class="text-center">Sl No</th>
                    <th>Factory/Buffer</th>
                    <th class="text-end">Opening Balance</th>
                    <th class="text-end">Total Production (Till Date)</th>
                    <th class="text-end">Total Receive</th>
                    <th class="text-end">Transfer to other Godown</th>
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
                      <td class="text-end text-danger <?= $bw['exchange'] < 0 ? 'text-danger' : ''; ?>"><?= h(number_format($bw['exchange'], 2)); ?></td>
                      <td class="text-end text-danger"><?= h(number_format($bw['delivery'], 2)); ?></td>
                      <td class="text-end fw-bold text-success"><?= h(number_format($bw['closing'], 2)); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                  <tr class="fw-bold table-secondary">
                    <td colspan="2" class="text-end ">Total:</td>
                    <td class="text-end"><?= h(number_format($bwTotals['opening'], 2)); ?></td>
                    <td class="text-end"><?= h(number_format($bwTotals['production'], 2)); ?></td>
                    <td class="text-end"><?= h(number_format($bwTotals['receive'], 2)); ?></td>
                    <td class="text-end text-danger"><?= h(number_format($bwTotals['exchange'], 2)); ?></td>
                    <td class="text-end text-danger"><?= h(number_format($bwTotals['delivery'], 2)); ?></td>
                    <td class="text-end text-success"><?= h(number_format($bwTotals['closing'], 2)); ?></td>
                  </tr>
                  <tr class="fw-bold">
                    <td colspan="7" class="text-end"><i class="fa fa-anchor text-primary"></i> AT PORT</td>
                    <td class="text-end text-primary"><?= h(number_format($totalImportRemaining, 2)); ?></td>
                  </tr>
                  <tr class="fw-bold">
                    <td colspan="7" class="text-end"><i class="fa fa-truck text-warning"></i> FERTILIZER IN TRANSIT</td>
                    <td class="text-end text-warning"><?= h(number_format($stockInTransit, 2)); ?></td>
                  </tr>
                  <tr class="table-success fw-bold fs-6">
                    <td colspan="7" class="text-end"><i class="fa fa-cubes text-success"></i> Closing Stock + AT PORT + FERTILIZER IN TRANSIT</td>
                    <td class="text-end text-success"><?= h(number_format($bwTotals['closing'] + $totalImportRemaining + $stockInTransit, 2)); ?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <span class="me-auto small text-muted">Rows: <?= count($bufferBreakdownRows); ?></span>
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
                  <tr><td colspan="8" class="text-center text-muted py-4">No pending import allotments found.</td></tr>
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
                      <td class="text-end fw-semibold <?= $remain > 0 ? 'text-warning' : 'text-success'; ?>"><?= h(number_format($remain, 2)); ?></td>
                      <td class="text-center" style="min-width:140px;">
                        <div class="progress" style="height:16px;">
                          <div class="progress-bar <?= $barColor; ?>" role="progressbar"
                               style="width: <?= h(number_format($pct, 1, '.', '')); ?>%;"
                               aria-valuenow="<?= h(number_format($pct, 1, '.', '')); ?>"
                               aria-valuemin="0" aria-valuemax="100">
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
            <span class="me-auto small text-muted">Rows: <?= count($importRows); ?></span>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ============ MODAL: DEALER DELIVERY (ROW 5) ============ -->
    <div class="modal fade" id="dealerDeliveryModal" tabindex="-1"
         aria-labelledby="dealerDeliveryModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="dealerDeliveryModalLabel">
              <i class="fa fa-handshake-o"></i> Delivered to Dealer — Breakdown
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-2 align-items-end mb-3">
              <div class="col-md-5">
                <label for="dealerBufferSelect" class="form-label small text-uppercase text-muted mb-1">
                  Select Buffer / Factory
                </label>
                <select id="dealerBufferSelect" class="form-select">
                  <option value="0">— All Buffers / Factories —</option>
                  <?php foreach ($dealerBufferOptions as $opt): ?>
                    <option value="<?= (int)$opt['id']; ?>">
                      <?= h($opt['buffer_name']); ?><?= $opt['office_name'] && $opt['office_name'] !== $opt['buffer_name'] ? ' — ' . h($opt['office_name']) : ''; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-7">
                <button type="button" id="dealerLoadBtn" class="btn btn-danger">
                  <i class="fa fa-search"></i> Load
                </button>
                <span id="dealerLoading" class="ms-2 text-muted small d-none">
                  <i class="fa fa-spinner fa-spin"></i> Loading…
                </span>
                <span class="ms-3 small text-muted">
                  Fiscal Year: <b><?= h($fiscalYearLabel); ?></b>
                </span>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                  <tr>
                    <th class="text-center" style="width:60px;">SL No</th>
                    <th>Factory / Buffer Name</th>
                    <th>Dealer Info</th>
                    <th class="text-end">Daily (MT)</th>
                    <th class="text-end">Monthly (MT)</th>
                    <th class="text-end">Yearly (MT)</th>
                  </tr>
                </thead>
                <tbody id="dealerTableBody">
                  <tr><td colspan="6" class="text-center text-muted py-4">
                    Please select a Buffer / Factory from the dropdown and click <b>Load</b>.
                  </td></tr>
                </tbody>
                <tfoot class="table-light">
                  <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total:</td>
                    <td class="text-end text-danger" id="dealerTotalDaily">—</td>
                    <td class="text-end text-primary" id="dealerTotalMonthly">—</td>
                    <td class="text-end text-success" id="dealerTotalYearly">—</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <span class="me-auto small text-muted">
              <i class="fa fa-info-circle"></i>
              Daily = today &middot; Monthly = current calendar month &middot; Yearly = current fiscal year
            </span>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

</div>

<script>
function reloadPage(btn) {
    btn.classList.add('loading');
    btn.querySelector('span').textContent = 'Loading…';
    setTimeout(function () {
        window.location.reload();
    }, 300);
}

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
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
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
        prodModalEl.addEventListener('show.bs.modal', function () { loadProduction(dateInput.value); });
    }
    if (loadBtn) { loadBtn.addEventListener('click', function () { loadProduction(dateInput.value); }); }
    if (dateInput) {
        dateInput.addEventListener('change', function () { loadProduction(dateInput.value); });
        dateInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); loadProduction(dateInput.value); }
        });
    }

    /* ============================================================
       DEALER DELIVERY MODAL (Row 5)
       - No auto-load on modal open
       - Load button (and dropdown change) triggers fetch
       ============================================================ */
    var dealerModalEl  = document.getElementById('dealerDeliveryModal');
    var dealerSelect   = document.getElementById('dealerBufferSelect');
    var dealerLoadBtn  = document.getElementById('dealerLoadBtn');
    var dealerTbody    = document.getElementById('dealerTableBody');
    var dealerLoading  = document.getElementById('dealerLoading');
    var dealerTotalD   = document.getElementById('dealerTotalDaily');
    var dealerTotalM   = document.getElementById('dealerTotalMonthly');
    var dealerTotalY   = document.getElementById('dealerTotalYearly');

    function loadDealerReport(officeId) {
        dealerLoading.classList.remove('d-none');
        dealerTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Loading…</td></tr>';
        dealerTotalD.textContent = '—';
        dealerTotalM.textContent = '—';
        dealerTotalY.textContent = '—';

        fetch('?ajax=dealer_delivery_by_buffer&office_id=' + encodeURIComponent(officeId))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                dealerLoading.classList.add('d-none');
                if (!data.ok) {
                    dealerTbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">'
                        + esc(data.error || 'Failed to load.') + '</td></tr>';
                    return;
                }
                if (!data.rows || data.rows.length === 0) {
                    dealerTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No active dealers found for this selection.</td></tr>';
                    dealerTotalD.textContent = fmt(0);
                    dealerTotalM.textContent = fmt(0);
                    dealerTotalY.textContent = fmt(0);
                    return;
                }
                var html = '';
                data.rows.forEach(function (row) {
                    html += '<tr>';
                    html += '<td class="text-center text-muted">' + row.sl_no + '</td>';
                    html += '<td class="fw-semibold text-uppercase">' + esc(row.buffer_name) + '</td>';
                    html += '<td class="dealer-info-cell">';
                    html +=   '<div class="d-name"><i class="fa fa-user"></i> ' + esc(row.dealer_name) + '</div>';
                    if (row.dealer_code) html += '<div class="d-meta"><i class="fa fa-id-badge"></i> Code: ' + esc(row.dealer_code) + '</div>';
                    if (row.mobile_no)   html += '<div class="d-meta"><i class="fa fa-phone"></i> ' + esc(row.mobile_no) + '</div>';
                    if (row.nid)         html += '<div class="d-meta"><i class="fa fa-id-card"></i> NID: ' + esc(row.nid) + '</div>';
                    if (row.address)     html += '<div class="d-meta"><i class="fa fa-map-marker"></i> ' + esc(row.address) + '</div>';
                    if (row.status)      html += '<div class="d-meta"><span class="badge badge-soft-success">' + esc(row.status) + '</span></div>';
                    html += '</td>';
                    html += '<td class="text-end text-danger fw-semibold">' + fmt(row.daily) + '</td>';
                    html += '<td class="text-end text-primary fw-semibold">' + fmt(row.monthly) + '</td>';
                    html += '<td class="text-end text-success fw-semibold">' + fmt(row.yearly) + '</td>';
                    html += '</tr>';
                });
                dealerTbody.innerHTML = html;
                dealerTotalD.textContent = fmt(data.totals.daily);
                dealerTotalM.textContent = fmt(data.totals.monthly);
                dealerTotalY.textContent = fmt(data.totals.yearly);
            })
            .catch(function () {
                dealerLoading.classList.add('d-none');
                dealerTbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">Network error.</td></tr>';
            });
    }

    // Only the Load button triggers the fetch (and dropdown change also triggers)
    if (dealerLoadBtn) {
        dealerLoadBtn.addEventListener('click', function () {
            loadDealerReport(dealerSelect.value || 0);
        });
    }
    if (dealerSelect) {
        dealerSelect.addEventListener('change', function () {
            loadDealerReport(dealerSelect.value || 0);
        });
    }
    // No auto-load on modal open
});
</script>

</body>
</html>