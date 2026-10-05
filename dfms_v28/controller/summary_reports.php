<?php
// session_start();
// if (!isset($_SESSION['username'])) { header("Location: ../index.php"); exit(); }

require_once('../db/db.php');

function h($val) {
    return htmlspecialchars(trim((string)($val ?? '')), ENT_QUOTES, 'UTF-8');
}
function dash($val) {
    $v = trim((string)($val ?? ''));
    return $v !== '' ? h($v) : '<span class="text-muted">—</span>';
}

// =====================================================================
//  AS-OF DATE — the whole report is calculated as if this date were
//  "today". Defaults to yesterday (matches the date picker's default).
//  Daily figures use = $asOfDate. Monthly figures use the month/year
//  containing $asOfDate. Cumulative stock sums are cut off at
//  <= $asOfDate so stock reflects the balance as of that date, not
//  as of right now.
// =====================================================================
$today     = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

$asOfDate = $_GET['as_of_date'] ?? $yesterday;
if (!DateTime::createFromFormat('Y-m-d', $asOfDate)) {
    $asOfDate = $yesterday;
}
$asOfYear  = (int)date('Y', strtotime($asOfDate));
$asOfMonth = (int)date('n', strtotime($asOfDate));

// 1. Fetch all unique buffer_name list from office_tbl
$buffers = [];
$bufQuery = "SELECT DISTINCT buffer_name, office_name, zone, capacity, opening_bal
             FROM office_tbl
             WHERE buffer_name IS NOT NULL AND buffer_name != ''";
$bufResult = mysqli_query($conn, $bufQuery);

if ($bufResult) {
    while ($bRow = mysqli_fetch_assoc($bufResult)) {
        $buffers[] = $bRow;
    }
}

// Array to store final calculated data for each buffer
$allBufferStats = [];

foreach ($buffers as $buf) {
    $current_buffer = $buf['buffer_name'];

    $dashStats = [
        'buffer_name'      => $current_buffer,
        'office_name'      => $buf['office_name'] ?? '',
        'zone'             => $buf['zone'] ?? '',
        'capacity'         => (float)($buf['capacity'] ?? 0),
        'opening_bal'      => (float)($buf['opening_bal'] ?? 0),
        'allotted'         => 0.0,
        'sent'             => 0.0,
        'pending'          => 0.0,
        'production_today' => 0.0,
        'current_stock'    => 0.0,
        'pipeline_amount'  => 0.0,
        'daily_receive'    => 0.0,
        'daily_delivery'   => 0.0,
        'out_ua_today'     => 0.0,
        'out_dealer_today' => 0.0,
        'monthly_demand'   => 0.0,
        'month_delivery'   => 0.0,
        'rest_of_delivery' => 0.0,
    ];

    // ---- A) Totals: Allotted, Sent, Pending (not date-scoped: these
    //        are lifetime allotment/pending figures, unrelated to the
    //        as-of-date filter) ----
    $pendingSql = "
        SELECT
            COALESCE(SUM(t.total_allotted), 0) AS total_allotted,
            COALESCE(SUM(t.total_sent), 0) AS total_sent
        FROM (
            SELECT ia.amount AS total_allotted, COALESCE(b.amount, 0) AS total_sent
            FROM import_allotment ia
            LEFT JOIN buffer_transaction b ON b.import_allotment_id = ia.id AND b.status = 'pending'
            WHERE ia.buffer_name = ?
            UNION ALL
            SELECT ua.amount AS total_allotted, COALESCE(b2.amount, 0) AS total_sent
            FROM urea_allotment ua
            LEFT JOIN buffer_transaction b2 ON (b2.prod_allotment_id = ua.id OR b2.buffer_allotment_id = ua.id) AND b2.status = 'pending'
            WHERE ua.receiver = ?
        ) t";
    if ($pendingStmt = mysqli_prepare($conn, $pendingSql)) {
        mysqli_stmt_bind_param($pendingStmt, 'ss', $current_buffer, $current_buffer);
        mysqli_stmt_execute($pendingStmt);
        $res = mysqli_stmt_get_result($pendingStmt);
        if ($row = mysqli_fetch_assoc($res)) {
            $dashStats['allotted'] = (float)$row['total_allotted'];
            $dashStats['sent']     = (float)$row['total_sent'];
            $dashStats['pending']  = $dashStats['allotted'] - $dashStats['sent'];
        }
        mysqli_stmt_close($pendingStmt);
    }

    // ---- B) Production on the as-of date ----
    $prodStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ? AND date = ?");
    if ($prodStmt) {
        mysqli_stmt_bind_param($prodStmt, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($prodStmt);
        $pRes = mysqli_stmt_get_result($prodStmt);
        if ($p = mysqli_fetch_assoc($pRes)) $dashStats['production_today'] = (float)$p['s'];
        mysqli_stmt_close($prodStmt);
    }

    // ---- C) Receive on the as-of date ----
    $receiveStmt = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS s
        FROM master_transaction mt
        INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
        LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
        LEFT JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
        WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
          AND COALESCE(ia.buffer_name, ua.receiver) = ?
          AND DATE(mt.created_at) = ?");
    if ($receiveStmt) {
        mysqli_stmt_bind_param($receiveStmt, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($receiveStmt);
        $rRes = mysqli_stmt_get_result($receiveStmt);
        if ($rr = mysqli_fetch_assoc($rRes)) $dashStats['daily_receive'] = (float)$rr['s'];
        mysqli_stmt_close($receiveStmt);
    }

    // ---- D) Pipeline Amount ----
    // NOTE: this reflects transactions that are CURRENTLY pending right
    // now. There's no status-history table, so we can't reconstruct
    // what was pending as of a past date — this figure is always "live"
    // regardless of the as-of-date filter.
    $pipeStmt = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(b.amount), 0) AS s
        FROM buffer_transaction b
        LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
        LEFT JOIN urea_allotment ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
        WHERE b.status = 'pending' AND (ia.buffer_name = ? OR ua.receiver = ?)");
    if ($pipeStmt) {
        mysqli_stmt_bind_param($pipeStmt, 'ss', $current_buffer, $current_buffer);
        mysqli_stmt_execute($pipeStmt);
        $pRes = mysqli_stmt_get_result($pipeStmt);
        if ($p = mysqli_fetch_assoc($pRes)) $dashStats['pipeline_amount'] = (float)$p['s'];
        mysqli_stmt_close($pipeStmt);
    }

    // ---- E) Monthly Demand — month/year containing the as-of date ----
    $dashStats['monthly_demand'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(md.d_amount + md.addition - md.substration), 0) AS v
        FROM monthly_demand md
        INNER JOIN office_tbl o ON o.id = md.office_tbl_id
        WHERE o.buffer_name = ?
          AND YEAR(md.date) = ?
          AND MONTH(md.date) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'sii', $current_buffer, $asOfYear, $asOfMonth);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['monthly_demand'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // ---- E2) Month Delivery — month/year containing the as-of date ----
    $dashStats['month_delivery'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.dealer_id IS NOT NULL
          AND o.buffer_name = ?
          AND YEAR(mt.created_at) = ?
          AND MONTH(mt.created_at) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'sii', $current_buffer, $asOfYear, $asOfMonth);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['month_delivery'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // ---- E3) Rest of Delivery (can be negative) ----
    $dashStats['rest_of_delivery'] = $dashStats['monthly_demand'] - $dashStats['month_delivery'];

    // =================================================================
    //  DELIVERIES ON THE AS-OF DATE
    // =================================================================

    // ---- 1) To Buffer / Factory — pending, on the as-of date ----
    $dashStats['out_ua_today'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE bt.status = 'pending'
          AND DATE(bt.date) = ?
          AND ua.sender = ?
    ")) {
        mysqli_stmt_bind_param($s, 'ss', $asOfDate, $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['out_ua_today'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // ---- 2) To Buffer / Factory — completed, on the as-of date ----
    $dashStats['output'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE bt.status = 'complete'
          AND DATE(bt.date) = ?
          AND ua.sender = ?
    ")) {
        mysqli_stmt_bind_param($s, 'ss', $asOfDate, $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['output'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $dashStats['daily_delivery_to_buffer'] =
          $dashStats['out_ua_today']
        + $dashStats['output'];

    // ---- 3) To Dealer — on the as-of date ----
    $dashStats['out_dealer_today'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND o.buffer_name = ?
          AND DATE(mt.created_at) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['out_dealer_today'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $dashStats['daily_delivery'] = $dashStats['out_dealer_today'];

    // ---- F) Stock Calculation — cumulative up to and including the
    //        as-of date, so Closing Stock reflects the balance as of
    //        that date rather than as of right now. ----
    $receiveAllStmt = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS s FROM master_transaction mt
        INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
        LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
        LEFT JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
        WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
          AND COALESCE(ia.buffer_name, ua.receiver) = ?
          AND DATE(mt.created_at) <= ?");
    $cond1All = 0.0;
    if ($receiveAllStmt) {
        mysqli_stmt_bind_param($receiveAllStmt, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($receiveAllStmt);
        $rRes = mysqli_stmt_get_result($receiveAllStmt);
        if ($rr = mysqli_fetch_assoc($rRes)) $cond1All = (float)$rr['s'];
        mysqli_stmt_close($receiveAllStmt);
    }

    $outUaAll = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount),0) AS v FROM master_transaction mt
        INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
        INNER JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND ua.sender = ?
          AND DATE(mt.created_at) <= ?")) {
        mysqli_stmt_bind_param($s, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $outUaAll = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $outDealerAll = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount),0) AS v FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND o.buffer_name = ?
          AND DATE(mt.created_at) <= ?")) {
        mysqli_stmt_bind_param($s, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $outDealerAll = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $prodAll = 0.0;
    $prodAllStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ? AND date <= ?");
    if ($prodAllStmt) {
        mysqli_stmt_bind_param($prodAllStmt, 'ss', $current_buffer, $asOfDate);
        mysqli_stmt_execute($prodAllStmt);
        $pRes = mysqli_stmt_get_result($prodAllStmt);
        if ($p = mysqli_fetch_assoc($pRes)) $prodAll = (float)$p['s'];
        mysqli_stmt_close($prodAllStmt);
    }

    $dashStats['current_stock'] = $prodAll
                                + $dashStats['opening_bal']
                                + $cond1All
                                - ($outUaAll + $outDealerAll);

    $allBufferStats[] = $dashStats;
}

// =================================================================
//  GROUP BUFFERS BY ZONE — ONLY THESE, IN THIS ORDER
// =================================================================
$ZONE_ORDER = [
    'North Zone',
    'South Zone',
    'Factory Zone',
    'Transit Godown Zone',
];

$groupedByZone = [];
foreach ($ZONE_ORDER as $zn) {
    $groupedByZone[$zn] = [];
}
foreach ($allBufferStats as $st) {
    $zone = trim((string)($st['zone'] ?? ''));
    if (isset($groupedByZone[$zone])) {
        $groupedByZone[$zone][] = $st;
    }
}

// Helper: derived values
function zoneRowDerived(array $st): array {
    $currentStockCalc = (float)($st['current_stock'] ?? 0) - (float)($st['out_ua_today'] ?? 0);
    $openingBalCalc   = $currentStockCalc
                      + (float)($st['daily_delivery_to_buffer'] ?? 0)
                      + (float)($st['daily_delivery'] ?? 0)
                      - ((float)($st['production_today'] ?? 0) + (float)($st['daily_receive'] ?? 0));
    return [
        'current_stock_calc' => $currentStockCalc,
        'opening_bal_calc'   => $openingBalCalc,
    ];
}

// Grand total accumulators
$grand = [
    'capacity'   => 0.0,
    'opening'    => 0.0,
    'prod'       => 0.0,
    'receive'    => 0.0,
    'receiveAll' => 0.0,
    'transfer'   => 0.0,
    'delivery'   => 0.0,
    'stock'      => 0.0,
    'demand'     => 0.0,
    'monthDel'   => 0.0,
    'totDel'     => 0.0,
    'rest'       => 0.0,
    'pipeline'   => 0.0,
];

$zoneLetters = range('A', 'Z');

// =================================================================
//  SECOND TABLE: PRODUCTION & LIFTING SUMMARY
//  Fiscal year is computed around the as-of date, not the real
//  current date, so the whole table shifts consistently with the
//  selected date.
// =================================================================
if ($asOfMonth >= 7) {
    $fyStart = sprintf('%04d-07-01', $asOfYear);
    $fyEnd   = sprintf('%04d-06-30', $asOfYear + 1);
} else {
    $fyStart = sprintf('%04d-07-01', $asOfYear - 1);
    $fyEnd   = sprintf('%04d-06-30', $asOfYear);
}

$TARGET_FACTORY = 'kafco';
$factoryNames = [$TARGET_FACTORY];

$bufferStatsByName = [];
foreach ($allBufferStats as $st) {
    $bufferStatsByName[strtolower($st['buffer_name'])] = $st;
}

$factoryProdStats = [];

foreach ($factoryNames as $factoryName) {
    $row = [
        'factory_name' => $factoryName,
        'prod_daily'   => 0.0,
        'prod_monthly' => 0.0,
        'prod_yearly'  => 0.0,
        'lift_daily'   => 0.0,
        'lift_monthly' => 0.0,
        'lift_yearly'  => 0.0,
        'stock'        => 0.0,
    ];

    // Production on the as-of date
    if ($s = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS v FROM production_tbl WHERE LOWER(factory_name) = LOWER(?) AND date = ?")) {
        mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $row['prod_daily'] = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Production, month/year containing the as-of date
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(daily_amount), 0) AS v
        FROM production_tbl
        WHERE LOWER(factory_name) = LOWER(?)
          AND YEAR(date) = ?
          AND MONTH(date) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $row['prod_monthly'] = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Production, fiscal year containing the as-of date
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(daily_amount), 0) AS v
        FROM production_tbl
        WHERE LOWER(factory_name) = LOWER(?)
          AND date BETWEEN ? AND ?
    ")) {
        mysqli_stmt_bind_param($s, 'sss', $factoryName, $fyStart, $fyEnd);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $row['prod_yearly'] = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Transfer, on the as-of date
    $transferDaily = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE LOWER(ua.sender) = LOWER(?)
          AND DATE(bt.date) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $transferDaily = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Transfer, month/year containing the as-of date
    $transferMonthly = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE LOWER(ua.sender) = LOWER(?)
          AND YEAR(bt.date) = ?
          AND MONTH(bt.date) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $transferMonthly = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Transfer, fiscal year containing the as-of date
    $transferYearly = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE LOWER(ua.sender) = LOWER(?)
          AND bt.date BETWEEN ? AND ?
    ")) {
        mysqli_stmt_bind_param($s, 'sss', $factoryName, $fyStart, $fyEnd);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $transferYearly = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Dealer delivery, on the as-of date
    $dealerDaily = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND LOWER(o.buffer_name) = LOWER(?)
          AND DATE(mt.created_at) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $dealerDaily = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Dealer delivery, month/year containing the as-of date
    $dealerMonthly = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND LOWER(o.buffer_name) = LOWER(?)
          AND YEAR(mt.created_at) = ?
          AND MONTH(mt.created_at) = ?
    ")) {
        mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $dealerMonthly = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    // Dealer delivery, fiscal year containing the as-of date
    $dealerYearly = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND LOWER(o.buffer_name) = LOWER(?)
          AND DATE(mt.created_at) BETWEEN ? AND ?
    ")) {
        mysqli_stmt_bind_param($s, 'sss', $factoryName, $fyStart, $fyEnd);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($rr = mysqli_fetch_assoc($r)) $dealerYearly = (float)$rr['v'];
        mysqli_stmt_close($s);
    }

    $row['lift_daily']   = $transferDaily   + $dealerDaily;
    $row['lift_monthly'] = $transferMonthly + $dealerMonthly;
    $row['lift_yearly']  = $transferYearly  + $dealerYearly;

    if (isset($bufferStatsByName[strtolower($factoryName)])) {
        $matched = $bufferStatsByName[strtolower($factoryName)];
        $row['stock'] = (float)($matched['current_stock'] ?? 0) - (float)($matched['out_ua_today'] ?? 0);
    }

    $factoryProdStats[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BCIC SFMS - Buffer Dashboard Summary</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background:#f4f6fa; }
        .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
        .table thead th {
            background:#1e293b; color:#fff; font-size:12px;
            text-transform:uppercase; letter-spacing:.5px;
            vertical-align:middle; text-align:center;
        }
        .table td { vertical-align:middle; font-size:13px; text-align:center; }
        .text-left { text-align:left !important; }

        .zone-section-row td {
            background:#cbd5e1 !important;
            color:#0f172a !important;
            font-weight:700;
            font-size:14px;
            text-align:left !important;
            padding: 8px 14px !important;
            border-left: 5px solid #2563eb;
        }
        .zone-subtotal-row td {
            background:#f1f5f9 !important;
            font-weight:700;
            color:#0f172a;
        }
        .grand-total-row td {
            background:#0f172a !important;
            color:#fff !important;
            font-weight:700;
        }
        .text-negative { color: #dc3545; font-weight: 700; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa fa-leaf"></i> Digital Fertilizer Monitoring System (DFMS), BCIC</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-success">Daily Statement of Urea Fertilizer
         As on:
    Reporting Date: <?= h(date('d-M-Y', strtotime($asOfDate))); ?></h3>
        <span class="badge bg-primary fs-6">Total Buffers: <?= count($allBufferStats); ?></span>
        <a href="user_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <div class="card p-3">

        <form method="GET" action="" class="row g-3 align-items-end mb-3" id="asOfDateForm">
            <div class="col-md-4">
                <label for="productionDateInput" class="form-label small text-uppercase text-muted mb-1">
                  Select Date
                </label>
                <input type="date"
                       id="productionDateInput"
                       name="as_of_date"
                       class="form-control"
                       value="<?= h($asOfDate); ?>">
            </div>
            <div class="col-md-8">
                <button type="submit" id="productionLoadBtn" class="btn btn-primary">
                  <i class="fa fa-search"></i> Search
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <td colspan="15" class="text-end">(Figure in M.T)</td>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Factory/Buffer Name</th>
                        <th>Capacity</th>
                        <th>Opening Stock</th>
                        <th>Production Today</th>
                        <th>Daily Receive</th>
                        <th>Total Receive</th>
                        <th>Transfer to Other Godown</th>
                        <th>Delivery(Dealer)</th>
                        <th>Closing Stock</th>
                        <th>Monthly Demand</th>
                        <th>Month Delivery</th>
                        <th>Rest of Delivery</th>
                        <th>Pipeline Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($allBufferStats)): ?>
                    <tr><td colspan="15" class="text-muted text-center">No buffer data found.</td></tr>
                <?php else: ?>

                    <?php $zoneIndex = 0; $anyZoneRendered = false; ?>

                    <?php foreach ($groupedByZone as $zoneName => $rows): ?>
                        <?php if (empty($rows)) continue; ?>
                        <?php $anyZoneRendered = true; ?>

                        <?php
                        $z = [
                            'capacity'   => 0.0,
                            'opening'    => 0.0,
                            'prod'       => 0.0,
                            'receive'    => 0.0,
                            'receiveAll' => 0.0,
                            'transfer'   => 0.0,
                            'delivery'   => 0.0,
                            'stock'      => 0.0,
                            'demand'     => 0.0,
                            'monthDel'   => 0.0,
                            'totDel'     => 0.0,
                            'rest'       => 0.0,
                            'pipeline'   => 0.0,
                        ];
                        ?>

                        <tr class="zone-section-row">
                            <td colspan="15">
                                <?= $zoneLetters[$zoneIndex] ?? ('#' . ($zoneIndex + 1)); ?>) <?= h($zoneName); ?>
                                <span class="text-muted small">(<?= count($rows); ?> buffer<?= count($rows) === 1 ? '' : 's'; ?>)</span>
                            </td>
                        </tr>

                        <?php foreach ($rows as $i => $st):
                            $d = zoneRowDerived($st);
                            $currentStockCalc = $d['current_stock_calc'];
                            $openingBalCalc   = $d['opening_bal_calc'];

                            $restOfDelivery = (float)($st['rest_of_delivery'] ?? 0);

                            $z['capacity']   += (float)($st['capacity'] ?? 0);
                            $z['opening']    += $openingBalCalc;
                            $z['prod']       += (float)($st['production_today'] ?? 0);
                            $z['receive']    += (float)($st['daily_receive'] ?? 0);
                            $z['receiveAll'] += (float)($st['production_today'] ?? 0) + (float)($st['daily_receive'] ?? 0);
                            $z['transfer']   += (float)($st['daily_delivery_to_buffer'] ?? 0);
                            $z['delivery']   += (float)($st['daily_delivery'] ?? 0);
                            $z['stock']      += $currentStockCalc;
                            $z['demand']     += (float)($st['monthly_demand'] ?? 0);
                            $z['monthDel']   += (float)($st['month_delivery'] ?? 0);
                            $z['totDel']     += (float)($st['month_delivery'] ?? 0);
                            $z['rest']       += $restOfDelivery;
                            $z['pipeline']   += (float)($st['pipeline_amount'] ?? 0);
                        ?>
                            <tr>
                                <td><?= $i + 1; ?></td>
                                <td class="text-left fw-bold text-primary">
                                    <?= dash($st['office_name'] !== '' ? $st['office_name'] : $st['buffer_name']); ?>
                                </td>
                                <td><?= h(number_format((float)$st['capacity'], 2)); ?></td>
                                <td><?= h(number_format($openingBalCalc, 2)); ?></td>
                                <td><?= h(number_format((float)$st['production_today'], 2)); ?></td>
                                <td><?= h(number_format((float)$st['daily_receive'], 2)); ?></td>
                                <td><?= h(number_format((float)$st['production_today'] + (float)$st['daily_receive'], 2)); ?></td>
                                <td class="fw-bold"><?= h(number_format((float)$st['daily_delivery_to_buffer'], 2)); ?></td>
                                <td class="fw-bold"><?= h(number_format((float)$st['daily_delivery'], 2)); ?></td>
                                <td class="fw-bold text-success"><?= h(number_format($currentStockCalc, 2)); ?></td>
                                <td><?= h(number_format((float)$st['monthly_demand'], 2)); ?></td>
                                <td><?= h(number_format((float)$st['month_delivery'], 2)); ?></td>
                                <td class="<?= $restOfDelivery < 0 ? 'text-negative' : ''; ?>">
                                    <?= h(number_format($restOfDelivery, 2)); ?>
                                </td>
                                <td><?= h(number_format((float)$st['pipeline_amount'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="zone-subtotal-row">
                            <td colspan="2" class="text-end">
                                <?= h($zoneName); ?> Subtotal =
                            </td>
                            <td><?= number_format($z['capacity'], 2); ?></td>
                            <td><?= number_format($z['opening'], 2); ?></td>
                            <td><?= number_format($z['prod'], 2); ?></td>
                            <td><?= number_format($z['receive'], 2); ?></td>
                            <td><?= number_format($z['receiveAll'], 2); ?></td>
                            <td><?= number_format($z['transfer'], 2); ?></td>
                            <td><?= number_format($z['delivery'], 2); ?></td>
                            <td class="text-success"><?= number_format($z['stock'], 2); ?></td>
                            <td><?= number_format($z['demand'], 2); ?></td>
                            <td><?= number_format($z['monthDel'], 2); ?></td>
                            <td class="<?= $z['rest'] < 0 ? 'text-negative' : ''; ?>">
                                <?= number_format($z['rest'], 2); ?>
                            </td>
                            <td><?= number_format($z['pipeline'], 2); ?></td>
                        </tr>

                        <?php
                        $grand['capacity']   += $z['capacity'];
                        $grand['opening']    += $z['opening'];
                        $grand['prod']       += $z['prod'];
                        $grand['receive']    += $z['receive'];
                        $grand['receiveAll'] += $z['receiveAll'];
                        $grand['transfer']   += $z['transfer'];
                        $grand['delivery']   += $z['delivery'];
                        $grand['stock']      += $z['stock'];
                        $grand['demand']     += $z['demand'];
                        $grand['monthDel']   += $z['monthDel'];
                        $grand['totDel']     += $z['totDel'];
                        $grand['rest']       += $z['rest'];
                        $grand['pipeline']   += $z['pipeline'];
                        $zoneIndex++;
                        ?>
                    <?php endforeach; ?>

                    <?php if (!$anyZoneRendered): ?>
                        <tr><td colspan="15" class="text-muted text-center">No buffers found for the configured zones.</td></tr>
                    <?php else: ?>
                        <tr class="grand-total-row">
                            <td colspan="2" class="text-end">Grand Total =</td>
                            <td><?= number_format($grand['capacity'], 2); ?></td>
                            <td><?= number_format($grand['opening'], 2); ?></td>
                            <td><?= number_format($grand['prod'], 2); ?></td>
                            <td><?= number_format($grand['receive'], 2); ?></td>
                            <td><?= number_format($grand['receiveAll'], 2); ?></td>
                            <td><?= number_format($grand['transfer'], 2); ?></td>
                            <td><?= number_format($grand['delivery'], 2); ?></td>
                            <td class="text-success"><?= number_format($grand['stock'], 2); ?></td>
                            <td><?= number_format($grand['demand'], 2); ?></td>
                            <td><?= number_format($grand['monthDel'], 2); ?></td>
                            <td class="<?= $grand['rest'] < 0 ? 'text-negative' : ''; ?>">
                                <?= number_format($grand['rest'], 2); ?>
                            </td>
                            <td><?= number_format($grand['pipeline'], 2); ?></td>
                        </tr>
                    <?php endif; ?>

                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- SECOND TABLE: PRODUCTION & LIFTING SUMMARY -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <td colspan="9" class="text-end">(Figure in M.T)</td>
                    </tr>
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2">Factory Name</th>
                        <th colspan="3">Production</th>
                        <th colspan="3">Lifting</th>
                        <th rowspan="2">Stock</th>
                    </tr>
                    <tr>
                        <th>Daily</th>
                        <th>Monthly</th>
                        <th>Yearly</th>
                        <th>Daily</th>
                        <th>Monthly</th>
                        <th>Yearly</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($factoryProdStats)): ?>
                        <tr><td colspan="9" class="text-muted text-center">No production data found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($factoryProdStats as $i => $fp): ?>
                            <tr>
                                <td><?= $i + 1; ?></td>
                                <td class="text-left fw-bold text-primary"><?= dash($fp['factory_name']); ?></td>
                                <td><?= h(number_format($fp['prod_daily'], 2)); ?></td>
                                <td><?= h(number_format($fp['prod_monthly'], 2)); ?></td>
                                <td><?= h(number_format($fp['prod_yearly'], 2)); ?></td>
                                <td><?= h(number_format($fp['lift_daily'], 2)); ?></td>
                                <td><?= h(number_format($fp['lift_monthly'], 2)); ?></td>
                                <td><?= h(number_format($fp['lift_yearly'], 2)); ?></td>
                                <td class="fw-bold text-success"><?= h(number_format($fp['stock'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- THIRD TABLE: Stock Position -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>At Present in Godown</th>
                        <th>Total Pipeline</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="fw-bold text-success"><?= number_format($grand['stock'], 2); ?></td>
                        <td class="fw-bold"><?= number_format($grand['pipeline'], 2); ?></td>
                        <td class="fw-bold"><?= number_format($grand['stock'] + $grand['pipeline'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>