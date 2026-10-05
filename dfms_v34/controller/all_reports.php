<?php
session_name('dfms_db');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once('../db/db.php');

$logged_in_user = $_SESSION['username'];

function h($val) {
    return htmlspecialchars(trim((string)($val ?? '')), ENT_QUOTES, 'UTF-8');
}
function dash($val) {
    $v = trim((string)($val ?? ''));
    return $v !== '' ? h($v) : '<span class="text-muted">—</span>';
}

// Report types
$report_types = [
    'urea_statement'   => 'Daily Statement of Urea Fertilizer',
    'production'       => 'Daily Production Report',
    'transit'          => 'Daily Transit Report',
    'port'             => 'At Port Report',
    'dealer_sales'     => 'Daily Sales (Dealer) Report',
    'godown_transfer'  => 'Transfer to Other Godown Report',
];

$selected_type = $_GET['report_type'] ?? '';
$has_search    = isset($_GET['search']) && $selected_type !== '';

// AS-OF DATE
$today    = date('Y-m-d');
$asOfDate = $_GET['as_of_date'] ?? $today;
if (!DateTime::createFromFormat('Y-m-d', $asOfDate)) {
    $asOfDate = $today;
}
$asOfYear  = (int)date('Y', strtotime($asOfDate));
$asOfMonth = (int)date('n', strtotime($asOfDate));

// Global accumulators
$allBufferStats    = [];
$groupedByZone     = [];
$grand             = [
    'capacity' => 0.0, 'opening' => 0.0, 'prod' => 0.0, 'receive' => 0.0,
    'totalReceive' => 0.0, 'transfer' => 0.0, 'delivery' => 0.0, 'stock' => 0.0,
    'demand' => 0.0, 'monthDel' => 0.0, 'rest' => 0.0, 'pipeline' => 0.0,
];
$zoneLetters    = range('A', 'Z');
$ZONE_ORDER     = ['NORTH ZONE', 'SOUTH ZONE', 'FACTORY ZONE', 'TRANSIT GODOWN ZONE'];
$factoryProdStats = [];

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

// =====================================================================
//  DATA FOR: Daily Statement of Urea Fertilizer
// =====================================================================
if ($has_search && $selected_type === 'urea_statement') {

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
            'total_receive'    => 0.0,
        ];

        // A) Allotted / Sent / Pending
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

        // B) Production on as-of date
        $prodStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ? AND date = ?");
        if ($prodStmt) {
            mysqli_stmt_bind_param($prodStmt, 'ss', $current_buffer, $asOfDate);
            mysqli_stmt_execute($prodStmt);
            $pRes = mysqli_stmt_get_result($prodStmt);
            if ($p = mysqli_fetch_assoc($pRes)) $dashStats['production_today'] = (float)$p['s'];
            mysqli_stmt_close($prodStmt);
        }

        // C) Receive on as-of date
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

        // D) Pipeline
        $isToday = ($asOfDate === date('Y-m-d'));
        $dashStats['pipeline_amount'] = 0.0;

        if ($isToday) {
            if ($s = mysqli_prepare($conn, "
                SELECT COALESCE(SUM(b.amount), 0) AS s
                FROM buffer_transaction b
                LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
                LEFT JOIN urea_allotment   ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
                WHERE b.status = 'pending'
                  AND (ia.buffer_name = ? OR ua.receiver = ?)
            ")) {
                mysqli_stmt_bind_param($s, 'ss', $current_buffer, $current_buffer);
                mysqli_stmt_execute($s);
                $pRes = mysqli_stmt_get_result($s);
                if ($p = mysqli_fetch_assoc($pRes)) $dashStats['pipeline_amount'] = (float)$p['s'];
                mysqli_stmt_close($s);
            }
        } else {
            if ($s = mysqli_prepare($conn, "
                SELECT COALESCE(SUM(b.amount), 0) AS s
                FROM buffer_transaction b
                LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
                LEFT JOIN urea_allotment   ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
                WHERE (
                        (b.created_at < ? AND b.updated_at > ?)
                     OR (b.created_at <= ? AND b.status = 'pending')
                )
                  AND (ia.buffer_name = ? OR ua.receiver = ?)
            ")) {
                mysqli_stmt_bind_param($s, 'sssss',
                    $asOfDate, $asOfDate, $asOfDate,
                    $current_buffer, $current_buffer);
                mysqli_stmt_execute($s);
                $pRes = mysqli_stmt_get_result($s);
                if ($p = mysqli_fetch_assoc($pRes)) $dashStats['pipeline_amount'] = (float)$p['s'];
                mysqli_stmt_close($s);
            }
        }

        // E) Monthly Demand
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

        // E2) Month Delivery
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS v
            FROM master_transaction mt
            INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
            INNER JOIN office_tbl o ON o.id = d.office_tbl_id
            WHERE mt.dealer_id <> 0
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

        $dashStats['rest_of_delivery'] = $dashStats['monthly_demand'] - $dashStats['month_delivery'];

        // 1) To Buffer/Factory — pending on date
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

        // 2) To Buffer/Factory — complete on date
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

        $dashStats['daily_delivery_to_buffer'] = $dashStats['out_ua_today'] + $dashStats['output'];

        // 3) To Dealer
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

        // F) Stock cumulative
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

        $dashStats['total_receive'] = $dashStats['production_today'] + $dashStats['daily_receive'];

        $dashStats['current_stock'] = $prodAll
                                    + $dashStats['opening_bal']
                                    + $cond1All
                                    - ($outUaAll + $outDealerAll);

        $allBufferStats[] = $dashStats;
    }

    foreach ($ZONE_ORDER as $zn) {
        $groupedByZone[$zn] = [];
    }
    foreach ($allBufferStats as $st) {
        $zone = trim((string)($st['zone'] ?? ''));
        if (isset($groupedByZone[$zone])) {
            $groupedByZone[$zone][] = $st;
        }
    }

    // KAFCO Production & Lifting
    if ($asOfMonth >= 7) {
        $fyStart = sprintf('%04d-07-01', $asOfYear);
        $fyEnd   = sprintf('%04d-06-30', $asOfYear + 1);
    } else {
        $fyStart = sprintf('%04d-07-01', $asOfYear - 1);
        $fyEnd   = sprintf('%04d-06-30', $asOfYear);
    }

    $TARGET_FACTORY = 'kafco';
    $factoryNames   = [$TARGET_FACTORY];

    $bufferStatsByName = [];
    foreach ($allBufferStats as $st) {
        $bufferStatsByName[strtolower($st['buffer_name'])] = $st;
    }

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

        if ($s = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS v FROM production_tbl WHERE LOWER(factory_name) = LOWER(?) AND date = ?")) {
            mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $row['prod_daily'] = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(daily_amount), 0) AS v
            FROM production_tbl
            WHERE LOWER(factory_name) = LOWER(?)
              AND YEAR(date) = ? AND MONTH(date) = ?
        ")) {
            mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $row['prod_monthly'] = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(daily_amount), 0) AS v
            FROM production_tbl
            WHERE LOWER(factory_name) = LOWER(?) AND date BETWEEN ? AND ?
        ")) {
            mysqli_stmt_bind_param($s, 'sss', $factoryName, $fyStart, $fyEnd);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $row['prod_yearly'] = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        $transferDaily = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(bt.amount), 0) AS v
            FROM buffer_transaction bt
            INNER JOIN urea_allotment ua
                ON ua.id = bt.prod_allotment_id OR ua.id = bt.buffer_allotment_id
            WHERE LOWER(ua.sender) = LOWER(?) AND DATE(bt.date) = ?
        ")) {
            mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $transferDaily = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        $transferMonthly = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(bt.amount), 0) AS v
            FROM buffer_transaction bt
            INNER JOIN urea_allotment ua
                ON ua.id = bt.prod_allotment_id OR ua.id = bt.buffer_allotment_id
            WHERE LOWER(ua.sender) = LOWER(?)
              AND YEAR(bt.date) = ? AND MONTH(bt.date) = ?
        ")) {
            mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $transferMonthly = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        $transferYearly = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(bt.amount), 0) AS v
            FROM buffer_transaction bt
            INNER JOIN urea_allotment ua
                ON ua.id = bt.prod_allotment_id OR ua.id = bt.buffer_allotment_id
            WHERE LOWER(ua.sender) = LOWER(?) AND bt.date BETWEEN ? AND ?
        ")) {
            mysqli_stmt_bind_param($s, 'sss', $factoryName, $fyStart, $fyEnd);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $transferYearly = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        $dealerDaily = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS v
            FROM master_transaction mt
            INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
            INNER JOIN office_tbl o ON o.id = d.office_tbl_id
            WHERE mt.transaction_source IN ('buffer_out','factory_out')
              AND LOWER(o.buffer_name) = LOWER(?) AND DATE(mt.created_at) = ?
        ")) {
            mysqli_stmt_bind_param($s, 'ss', $factoryName, $asOfDate);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $dealerDaily = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        $dealerMonthly = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS v
            FROM master_transaction mt
            INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
            INNER JOIN office_tbl o ON o.id = d.office_tbl_id
            WHERE mt.transaction_source IN ('buffer_out','factory_out')
              AND LOWER(o.buffer_name) = LOWER(?)
              AND YEAR(mt.created_at) = ? AND MONTH(mt.created_at) = ?
        ")) {
            mysqli_stmt_bind_param($s, 'sii', $factoryName, $asOfYear, $asOfMonth);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($rr = mysqli_fetch_assoc($r)) $dealerMonthly = (float)$rr['v'];
            mysqli_stmt_close($s);
        }

        $dealerYearly = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS v
            FROM master_transaction mt
            INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
            INNER JOIN office_tbl o ON o.id = d.office_tbl_id
            WHERE mt.transaction_source IN ('buffer_out','factory_out')
              AND LOWER(o.buffer_name) = LOWER(?) AND DATE(mt.created_at) BETWEEN ? AND ?
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
    :root{
      --navy:#16232e;
      --field-green:#2f6f4f;
      --field-green-dark:#25573f;
      --wheat:#e2a63b;
      --wheat-dark:#c98f2a;
      --bg:#f4f6fa;
      --card-border:#e3e7ee;
      --ink:#22303c;
      --muted:#6b7a86;
    }
    body{ background:var(--bg); color:var(--ink); }
    .navbar{ background:var(--navy) !important; }
    .navbar-brand i{ color:var(--wheat); margin-right:6px; }
    .page-title{ color:var(--ink); font-weight:600; }
    .btn-field{ background:var(--field-green); border-color:var(--field-green); color:#fff; }
    .btn-field:hover{ background:var(--field-green-dark); border-color:var(--field-green-dark); color:#fff; }
    .btn-wheat{ background:var(--wheat); border-color:var(--wheat); color:var(--navy); font-weight:600; }
    .btn-wheat:hover{ background:var(--wheat-dark); border-color:var(--wheat-dark); color:var(--navy); }
    .filter-card{ background:#fff; border:1px solid var(--card-border); border-radius:10px; padding:1.25rem 1.5rem; box-shadow:0 1px 2px rgba(22,35,46,0.04); }
    .filter-card label{ font-size:.82rem; color:var(--muted); margin-bottom:.3rem; font-weight:600; }
    .filter-card .form-select, .filter-card .form-control{ border-color:#d7dee5; }
    .filter-card .form-select:focus, .filter-card .form-control:focus{ border-color:var(--field-green); box-shadow:0 0 0 .2rem rgba(47,111,79,.15); }
    .result-panel{ background:#fff; border:1px solid var(--card-border); border-radius:10px; min-height:320px; margin-top:1.25rem; }
    .result-panel .panel-head{ display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-bottom:1px solid var(--card-border); }
    .result-panel .panel-head h5{ margin:0; font-size:1.05rem; font-weight:600; }
    .result-panel .panel-head small{ color:var(--muted); }
    .empty-state{ display:flex; flex-direction:column; align-items:center; justify-content:center; padding:4rem 1rem; color:var(--muted); text-align:center; }
    .empty-state i{ font-size:2.4rem; color:#c7d0d8; margin-bottom:.75rem; }
    .badge-period{ background:#eef4f0; color:var(--field-green-dark); font-weight:600; font-size:.78rem; padding:.4rem .7rem; border-radius:6px; }
    .table td, .table th{ font-size:.86rem; vertical-align:middle; }
    .kafco-card{ background:#fff; border:1px solid var(--card-border); border-radius:10px; padding:1rem 1.25rem; margin-top:1.25rem; }
    .kafco-card h6{ font-size:.95rem; font-weight:700; color:var(--field-green-dark); margin-bottom:.75rem; display:flex; align-items:center; gap:8px; }
    .kafco-card h6 i{ color:var(--wheat-dark); }
    .kafco-table thead th{ background:#f3f6f4; color:#2f6f4f; font-size:.78rem; text-transform:uppercase; letter-spacing:.4px; text-align:center; vertical-align:middle; }
    .kafco-table td{ text-align:center; }
    .kafco-table td.kafco-name{ text-align:left; font-weight:700; color:#1d4ed8; }
    .kafco-table .kafco-stock{ font-weight:800; color:#047857; font-size:.95rem; }
    .stockpos-card{ background:#fff; border:1px solid var(--card-border); border-radius:10px; padding:1rem 1.25rem; margin-top:1.25rem; }
    .stockpos-card h6{ font-size:.95rem; font-weight:700; color:var(--field-green-dark); margin-bottom:.75rem; display:flex; align-items:center; gap:8px; }
    .stockpos-card h6 i{ color:var(--wheat-dark); }
    .stockpos-table thead th{ background:#f3f6f4; color:#2f6f4f; font-size:.78rem; text-transform:uppercase; letter-spacing:.4px; text-align:center; }
    .stockpos-table td{ text-align:center; font-weight:600; }
    .stockpos-table td.sp-label{ text-align:left; font-weight:700; color:#334155; }
    .stockpos-table .sp-present{ color:#047857; font-weight:800; }
    .stockpos-table .sp-pipe{ color:#b45309; font-weight:800; }
    .stockpos-table .sp-total{ color:#1d4ed8; font-weight:800; font-size:1.02rem; }

    /* ============================================================
       PRINT LETTERHEAD + GLOBAL PRINT FOOTER
       ============================================================ */
    .print-letterhead { display: none; }
    .print-sign-block { display: none; }
    .print-footer { display: none; }

    @media print {
      /* Tighter page margins so more rows fit */
      @page { size: A4 portrait; margin: 3mm 3mm 5mm 3mm; }

      html, body {
        background:#fff !important;
        font-size: 9px !important;
        line-height: 1.05 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }

      .table-responsive,
      .table-responsive-sm,
      .table-responsive-md,
      .table-responsive-lg,
      .table-responsive-xl {
        overflow: visible !important;
        overflow-x: visible !important;
        overflow-y: visible !important;
        width: 100% !important;
        max-width: 100% !important;
      }
      .result-panel,
      .container-fluid,
      .kafco-card,
      .stockpos-card,
      .card,
      .card-body {
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
      }

      .no-print { display: none !important; }

      /* ------------------------------------------------------------
         HIDE ALL ICONS IN CARD HEADINGS WHILE PRINTING
         ------------------------------------------------------------ */
      .kafco-card h6 i,
      .kafco-card h6 .fa,
      .stockpos-card h6 i,
      .stockpos-card h6 .fa,
      .kafco-card h6::before,
      .stockpos-card h6::before,
      .kafco-card h6::after,
      .stockpos-card h6::after {
        display: none !important;
        content: none !important;
      }
      .kafco-card h6,
      .stockpos-card h6 {
        display: block !important;
        gap: 0 !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
        text-indent: 0 !important;
      }

      /* ------------------------------------------------------------
         HIDE ICONS IN THE ZONE HEADER ROWS
         ------------------------------------------------------------ */
      tr.table-secondary td[colspan] i,
      tr.table-secondary td[colspan] .fa,
      tr.table-secondary td[colspan] svg,
      tr.table-secondary td[colspan] img {
        display: none !important;
        content: none !important;
      }

      /* ------------------------------------------------------------
         ZONE HEADER ROW — force left alignment, remove indent
         ------------------------------------------------------------ */
      tr.table-secondary td[colspan] {
        text-align: left !important;
        padding-left: 4px !important;
        text-indent: 0 !important;
        margin-left: 0 !important;
      }

      .print-letterhead {
        display: block !important;
        text-align: center;
        margin: 0 0 2px 0;
        line-height: 1;
      }
      .print-letterhead .org-name { font-size: 12px; font-weight: 800; color: #000; margin: 0; letter-spacing: .2px; }
      .print-letterhead .org-addr { font-size: 9px; color: #111; margin: 0; }
      .print-letterhead .report-title { font-size: 10.5px; font-weight: 700; color: #000; margin: 1px 0 0; }
      .print-letterhead .report-sub,
      .print-letterhead .report-sub-date { font-size: 9px; color: #111; margin: 0; font-style: italic; }
      .print-letterhead hr { border-top: 1px solid #000; margin: 1px 0 2px; }

      table.table {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 9px !important;
        margin-bottom: 1px !important;
      }
      table.table thead th {
        background: #e8e8e8 !important;
        color: #000 !important;
        padding: 1px 2px !important;
        border: 1px solid #333 !important;
        font-size: 8px !important;
        line-height: 1.05 !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        text-align: center !important;
        vertical-align: middle !important;
      }
      table.table td {
        padding: 1px 2px !important;
        border: 1px solid #333 !important;
        color: #000 !important;
        line-height: 1.05 !important;
        font-size: 9px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: clip !important;
        vertical-align: middle !important;
      }

      /* ------------------------------------------------------------
         GENERIC FIRST COLUMN (#) — narrow
         (the urea statement table gets its own widths from <colgroup>)
         ------------------------------------------------------------ */
      table.table thead th:first-child,
      table.table tbody tr:not(.table-secondary):not(.table-light):not(.table-dark) td:first-child {
        width: 5% !important;
        min-width: 5% !important;
        max-width: 5% !important;
        padding: 1px 0 !important;
        text-align: center !important;
        font-size: 8px !important;
        overflow: hidden !important;
      }

      /* ------------------------------------------------------------
         UREA STATEMENT TABLE — # narrow, name column wraps (not cut)
         Widths are controlled by the <colgroup> in the markup.
         ------------------------------------------------------------ */
      table.urea-table { table-layout: fixed !important; }

      table.urea-table thead th:first-child,
      table.urea-table tbody tr:not(.table-secondary):not(.table-light):not(.table-dark) td:first-child {
        width: auto !important;
        min-width: 0 !important;
        max-width: none !important;
      }

      table.urea-table tbody tr:not(.table-secondary):not(.table-light):not(.table-dark) td:nth-child(2) {
        white-space: normal !important;
        word-break: break-word !important;
        overflow: visible !important;
        text-align: left !important;
      }

      table.table td.text-end,
      table.table th.text-end { text-align: right !important; }

      .table-dark, .table-secondary, .table-light {
        background: #d9d9d9 !important; color: #000 !important;
      }
      .table-dark td, .table-secondary td, .table-light td {
        color: #000 !important; font-weight: 700;
        padding: 1px 2px !important;
        font-size: 9px !important;
      }
      .table-secondary td[colspan] {
        font-size: 9.5px !important;
        padding: 2px 4px !important;
        font-weight: 700 !important;
        background: #d9d9d9 !important;
        text-align: left !important;
      }

      .kafco-card { margin-top: 3px !important; page-break-inside: avoid; }
      .kafco-card h6 { color: #000 !important; font-size: 10px !important; margin: 1px 0 !important; }
      .kafco-table { table-layout: auto !important; font-size: 9px !important; }
      .kafco-table thead th { background:#e8e8e8 !important; color:#000 !important; padding: 1px 3px !important; font-size: 8.5px !important; white-space: normal !important; }
      .kafco-table td { padding: 1px 3px !important; font-size: 9px !important; }
      .kafco-table .kafco-name { color:#000 !important; }
      .kafco-table .kafco-stock { color:#000 !important; }

      .stockpos-card { margin-top: 3px !important; page-break-inside: avoid; }
      .stockpos-card h6 { color: #000 !important; font-size: 10px !important; margin: 1px 0 !important; }
      .stockpos-table { table-layout: auto !important; font-size: 9px !important; }
      .stockpos-table thead th { background:#e8e8e8 !important; color:#000 !important; padding: 1px 3px !important; font-size: 8.5px !important; }
      .stockpos-table td { padding: 1px 3px !important; font-size: 9px !important; }
      .stockpos-table .sp-present,
      .stockpos-table .sp-pipe,
      .stockpos-table .sp-total { color:#000 !important; }

      /* ------------------------------------------------------------
         SIGNATURE BLOCK — compact, page-break safe
         ------------------------------------------------------------ */
      .print-sign-block {
        display: block !important;
        margin-top: 2px !important;
        page-break-inside: avoid;
        page-break-before: avoid;
      }
      .print-sign-block table { width: 100%; border: none !important; margin: 0; }
      .print-sign-block td {
        border: none !important;
        padding: 1px 4px !important;
        font-size: 9px !important;
        color: #000;
        vertical-align: top;
        line-height: 1.05 !important;
        white-space: normal !important;
      }
      .print-sign-block .sig-line {
        border-top: 0px solid #000 !important;
        margin-top: 18px !important;
        padding-top: 1px !important;
        text-align: center;
        font-weight: 700;
        font-size: 9px !important;
      }
      .print-sign-block .cc-title { font-weight: 700; font-size: 9px !important; margin: 4px 0 1px !important; }
      .print-sign-block .cc-list { padding-left: 12px !important; margin: 0 !important; font-size: 8.5px !important; line-height: 1.1 !important; }
      .print-sign-block .cc-list li { margin-bottom: 0 !important; }

      /* ============================================================
         GLOBAL PRINT FOOTER
         ============================================================ */
      .print-footer {
        display: block !important;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 6mm;
        padding: 1px 4px 0 4px;
        font-size: 7.5px;
        color: #000;
        border-top: 1px solid #000;
        background: #fff;
        line-height: 1;
        text-align: left;
        z-index: 9999;
      }
      .print-footer .pf-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
      }
      .print-footer .pf-left  { text-align: left;  flex: 1; }
      .print-footer .pf-right { text-align: right; flex: 1; }
      .print-footer b { font-weight: 700; }

      body { padding-bottom: 6mm !important; }

      tr { page-break-inside: avoid; }
    }
  </style>
</head>
<body>

<!-- ============================================================
     GLOBAL PRINT-ONLY FOOTER
     ============================================================ -->
<div class="print-footer" id="globalPrintFooter">
  <div class="pf-row">
    <div class="pf-left">
      Printed by: <b><?= h($logged_in_user); ?></b>
    </div>
    <div class="pf-right">
      Printed on: <b id="globalPrintTime">—</b>
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark no-print">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <i class="fa fa-industry"></i> Digital Fertilizer Monitoring System (DFMS), BCIC
    </a>
  </div>
</nav>

<div class="container-fluid p-3">

    <div class="row align-items-center mb-3 no-print">
        <div class="col-md-6">
            <h3 class="page-title mb-0">Welcome <b class="text-danger text-uppercase"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">User Dashboard</small>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="user_dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back </a>
            <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout </a>
        </div>
    </div>

    <!-- Report filters -->
    <form method="GET" action="" id="reportForm">
        <div class="filter-card no-print">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5 col-md-6">
                    <label for="report_type">Report</label>
                    <select class="form-select" id="report_type" name="report_type" required>
                        <option value="" disabled <?= $selected_type === '' ? 'selected' : '' ?>>Select a report</option>
                        <?php foreach ($report_types as $key => $label): ?>
                            <option value="<?= h($key) ?>" <?= $selected_type === $key ? 'selected' : '' ?>>
                                <?= h($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-6">
                    <label for="as_of_date">As of date</label>
                    <input type="date" class="form-control" id="as_of_date" name="as_of_date"
                           value="<?= h($asOfDate) ?>" required>
                </div>
                <div class="col-lg-4 col-md-3 col-6 d-flex gap-2">
                    <button type="submit" name="search" value="1" class="btn btn-field flex-fill">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <button type="button" class="btn btn-wheat" onclick="window.print()" title="Print this report">
                        <i class="fa fa-print"> Print</i>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- PRINT-ONLY LETTERHEAD -->
    <?php if ($has_search && ($selected_type === 'urea_statement' || $selected_type === 'production')): ?>
    <div class="print-letterhead">
        <div class="org-name">Bangladesh Chemical Industries Corporation</div>
        <div class="org-addr">BCIC Bhaban, 30-31, Dilkusha C/A., Dhaka-1000, Bangladesh.</div>
        <?php if ($selected_type === 'urea_statement'): ?>
            <div class="report-title">Daily Statement of Urea Fertilizer,</div>
            <div class="report-sub">
                Opening Stock, Receive, Delivery &amp; Closing Stock (Provisional)
                As On <b><?= h(date('d-m-Y', strtotime($asOfDate))); ?></b>
            </div>
        <?php else: ?>
            <div class="report-title">Daily Production Report (Factories),</div>
            <div class="report-sub">
                Production Summary As On <b><?= h(date('d-m-Y', strtotime($asOfDate))); ?></b>
            </div>
        <?php endif; ?>
        <div class="report-sub-date">
            Reporting Date: <b><?= h(date('d-m-Y')); ?></b>
        </div>
        <hr>
    </div>
    <?php endif; ?>

    <!-- Results -->
    <div class="result-panel">
        <div class="panel-head no-print">
            <div>
                <h5><?= $selected_type !== '' && isset($report_types[$selected_type]) ? h($report_types[$selected_type]) : 'Report results' ?></h5>
                <?php if ($has_search): ?>
                    <small>Figures calculated as of the selected date</small>
                <?php else: ?>
                    <small>Choose a report and date, then search</small>
                <?php endif; ?>
            </div>
            <?php if ($has_search): ?>
                <span class="badge-period"><i class="fa fa-calendar"></i> <?= h($asOfDate) ?></span>
            <?php endif; ?>
        </div>

        <?php if (!$has_search): ?>

            <div class="empty-state">
                <i class="fa fa-file-text-o"></i>
                <div class="fw-semibold mb-1">No report loaded yet</div>
                <div>Select a report type and date above, then click Search.</div>
            </div>

        <?php elseif ($selected_type === 'urea_statement'): ?>

            <!-- TABLE 1: Daily Statement -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 urea-table">
                    <colgroup>
                        <col style="width:3%">    <!-- # -->
                        <col style="width:19%">   <!-- Factory/Buffer Name -->
                        <col style="width:6.5%"><col style="width:6.5%"><col style="width:6.5%">
                        <col style="width:6.5%"><col style="width:6.5%"><col style="width:6.5%">
                        <col style="width:6.5%"><col style="width:6.5%"><col style="width:6.5%">
                        <col style="width:6.5%"><col style="width:6.5%"><col style="width:6.5%">
                    </colgroup>
                    <thead>
                        <tr>
                            <td colspan="14" class="text-end">(Figure in M.T)</td>
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
                    <?php
                    $rowNum    = 0;
                    $zoneIndex = 0;
                    $anyRows   = false;

                    foreach ($groupedByZone as $zoneName => $bufferRows):
                        $zoneLetter = $zoneLetters[$zoneIndex] ?? '';
                        $zoneIndex++;
                        if (empty($bufferRows)) continue;
                        $anyRows = true;

                        $zoneSubtotal = [
                            'capacity' => 0.0, 'opening' => 0.0, 'prod' => 0.0, 'receive' => 0.0,
                            'totalReceive' => 0.0, 'transfer' => 0.0, 'delivery' => 0.0, 'stock' => 0.0,
                            'demand' => 0.0, 'monthDel' => 0.0, 'rest' => 0.0, 'pipeline' => 0.0,
                        ];
                    ?>
                        <tr class="table-secondary">
                            <td colspan="14" class="fw-semibold"><?= h($zoneLetter) ?>. <?= h($zoneName) ?></td>
                        </tr>
                        <?php foreach ($bufferRows as $st):
                            $rowNum++;
                            $derived      = zoneRowDerived($st);
                            $openingStock = $derived['opening_bal_calc'];
                            $closingStock = $derived['current_stock_calc'];
                            $transferOut  = (float)($st['daily_delivery_to_buffer'] ?? 0);
                            $totalReceive = (float)($st['total_receive'] ?? 0);

                            $zoneSubtotal['capacity']     += (float)$st['capacity'];
                            $zoneSubtotal['opening']      += $openingStock;
                            $zoneSubtotal['prod']         += (float)$st['production_today'];
                            $zoneSubtotal['receive']      += (float)$st['daily_receive'];
                            $zoneSubtotal['totalReceive'] += $totalReceive;
                            $zoneSubtotal['transfer']     += $transferOut;
                            $zoneSubtotal['delivery']     += (float)$st['daily_delivery'];
                            $zoneSubtotal['stock']        += $closingStock;
                            $zoneSubtotal['demand']       += (float)$st['monthly_demand'];
                            $zoneSubtotal['monthDel']     += (float)$st['month_delivery'];
                            $zoneSubtotal['rest']         += (float)$st['rest_of_delivery'];
                            $zoneSubtotal['pipeline']     += (float)$st['pipeline_amount'];

                            $grand['capacity']     += (float)$st['capacity'];
                            $grand['opening']      += $openingStock;
                            $grand['prod']         += (float)$st['production_today'];
                            $grand['receive']      += (float)$st['daily_receive'];
                            $grand['totalReceive'] += $totalReceive;
                            $grand['transfer']     += $transferOut;
                            $grand['delivery']     += (float)$st['daily_delivery'];
                            $grand['stock']        += $closingStock;
                            $grand['demand']       += (float)$st['monthly_demand'];
                            $grand['monthDel']     += (float)$st['month_delivery'];
                            $grand['rest']         += (float)$st['rest_of_delivery'];
                            $grand['pipeline']     += (float)$st['pipeline_amount'];
                        ?>
                            <tr>
                                <td><?= $rowNum ?></td>
                                <td><?= dash($st['office_name'] ?: $st['buffer_name']) ?></td>
                                <td class="text-end"><?= number_format($st['capacity'], 2) ?></td>
                                <td class="text-end"><?= number_format($openingStock, 2) ?></td>
                                <td class="text-end"><?= number_format($st['production_today'], 2) ?></td>
                                <td class="text-end"><?= number_format($st['daily_receive'], 2) ?></td>
                                <td class="text-end"><?= number_format($totalReceive, 2) ?></td>
                                <td class="text-end"><?= number_format($transferOut, 2) ?></td>
                                <td class="text-end"><?= number_format($st['daily_delivery'], 2) ?></td>
                                <td class="text-end fw-semibold"><?= number_format($closingStock, 2) ?></td>
                                <td class="text-end"><?= number_format($st['monthly_demand'], 2) ?></td>
                                <td class="text-end"><?= number_format($st['month_delivery'], 2) ?></td>
                                <td class="text-end <?= $st['rest_of_delivery'] < 0 ? 'text-danger' : '' ?>">
                                    <?= number_format($st['rest_of_delivery'], 2) ?>
                                </td>
                                <td class="text-end"><?= number_format($st['pipeline_amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="table-light fw-semibold">
                            <td colspan="2">Subtotal — <?= h($zoneName) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['capacity'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['opening'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['prod'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['receive'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['totalReceive'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['transfer'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['delivery'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['stock'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['demand'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['monthDel'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['rest'], 2) ?></td>
                            <td class="text-end"><?= number_format($zoneSubtotal['pipeline'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if ($anyRows): ?>
                        <tr class="table-dark text-white fw-bold">
                            <td colspan="2">Grand Total</td>
                            <td class="text-end"><?= number_format($grand['capacity'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['opening'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['prod'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['receive'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['totalReceive'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['transfer'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['delivery'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['stock'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['demand'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['monthDel'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['rest'], 2) ?></td>
                            <td class="text-end"><?= number_format($grand['pipeline'], 2) ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="14" class="text-center text-muted py-4">No buffer/factory data found for this date.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- TABLE 2: Production & Lifting — KAFCO -->
            <div class="kafco-card">
                <h6><i class="fa fa-industry"></i> Production &amp; Lifting Summary — KAFCO</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0 kafco-table">
                        <thead>
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
                                <tr><td colspan="9" class="text-muted text-center py-3">No production data found for this date.</td></tr>
                            <?php else: ?>
                                <?php foreach ($factoryProdStats as $i => $fp): ?>
                                    <tr>
                                        <td><?= $i + 1; ?></td>
                                        <td class="kafco-name"><?= dash(strtoupper($fp['factory_name'])); ?></td>
                                        <td><?= h(number_format($fp['prod_daily'], 2)); ?></td>
                                        <td><?= h(number_format($fp['prod_monthly'], 2)); ?></td>
                                        <td><?= h(number_format($fp['prod_yearly'], 2)); ?></td>
                                        <td><?= h(number_format($fp['lift_daily'], 2)); ?></td>
                                        <td><?= h(number_format($fp['lift_monthly'], 2)); ?></td>
                                        <td><?= h(number_format($fp['lift_yearly'], 2)); ?></td>
                                        <td class="kafco-stock"><?= h(number_format($fp['stock'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE 3: Stock Position -->
            <div class="stockpos-card">
                <h6><i class="fa fa-cubes"></i> Stock Position</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0 stockpos-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>At Present in Godown</th>
                                <th>Total Pipeline</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="sp-present"><?= number_format($grand['stock'], 2); ?></td>
                                <td class="sp-pipe"><?= number_format($grand['pipeline'], 2); ?></td>
                                <td class="sp-total"><?= number_format($grand['stock'] + $grand['pipeline'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
<br><br>

            <!-- PRINT-ONLY SIGNATURE BLOCK -->
            <div class="print-sign-block mt-5" style="margin-top: 60px;">
                <table class="w-100">
                <!-- 3-row gap (remove/add rows or change height to adjust) -->
                    <tr><td colspan="5" style="height:16px;">&nbsp;</td></tr>
                    <tr><td colspan="5" style="height:16px;">&nbsp;</td></tr>
                    <tr><td colspan="5" style="height:16px;">&nbsp;</td></tr>
                    <!-- Main signatures -->
                    <tr>
                        <td class="sig-line">DM (MKT)</td>
                        <td class="sig-line">GM (MKT)</td>
                        <td class="sig-line">Head of the Department</td>
                        <td class="sig-line">Dir (Com.)</td>
                        <td></td>
                    </tr>

                    <!-- 3-row gap (remove/add rows or change height to adjust) -->
                    <tr><td colspan="5" style="height:16px;">&nbsp;</td></tr>
                    <tr><td colspan="5" style="height:16px;">&nbsp;</td></tr>
                    <tr><td colspan="5" style="height:16px;">&nbsp;</td></tr>

                    <!-- Chairman on right -->
                    <tr>
                        <td colspan="5">
                            <div class="text-end fw-bold">
                                Chairman
                            </div>
                            <div class="text-end" style="font-size: 9.5px;">
                                BCIC
                            </div>
                        </td>
                    </tr>

                </table>

                <!-- Gap before CC -->
                <div class="mt-4">
                    <div class="cc-title">CC: For your kind information.</div>

                    <ol class="cc-list">
                        <li>Principal Secretary to the Hon'ble Prime Minister, Tejgoan, Dhaka.</li>
                        <li>Secretary, Ministry of Industries, 91, Motijheel C/A, Dhaka-1000.</li>
                        <li>Secretary, Energy &amp; Mineral Resources Division, Bangladesh Secretariat, Dhaka-1000.</li>
                        <li>Secretary, Ministry of Agriculture, Bangladesh Secretariat, Dhaka-1000.</li>
                        <li>PS to Minister, Ministry of Industries, 91, Motijheel C/A, Dhaka-1000.</li>
                    </ol>
                </div>
            </div>

        <?php elseif ($selected_type === 'production'): ?>

            <!-- DAILY PRODUCTION REPORT (FACTORIES) — AJAX LOAD -->
            <div class="d-flex justify-content-between align-items-center mb-2 no-print">
                <span class="small text-muted">
                    Rows: <b id="prodRptCount">—</b>
                </span>
            </div>

            <div class="table-responsive p-2">
                <table class="table table-bordered table-striped table-hover" style="font-size: 0.8rem;">
                    <thead class="table-primary text-center p-0 m-0">
                        <tr>
                            <th>#</th>
                            <th>Factory Name</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Installed Capacity</th>
                            <th>Daily</th>
                            <th>Monthly</th>
                            <th>Yearly</th>
                            <th>Yearly Production Target</th>
                            <th>Due</th>
                            <th>Monthly Target</th>
                            <th>Monthly production till date</th>
                            <th>Plant Load (%)</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle" id="prodRptBody">
                        <tr>
                            <td colspan="14" class="text-center text-muted py-4">Loading…</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-secondary fw-bold" id="prodRptFoot"></tfoot>
                </table>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <i class="fa fa-wrench"></i>
                <div class="fw-semibold mb-1">Report not wired up yet</div>
                <div><?= h($report_types[$selected_type] ?? '') ?> — add its query and table markup the same way the urea statement report is done above.</div>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var AS_OF_DATE = <?= json_encode($asOfDate); ?>;
    var SELECTED_TYPE = <?= json_encode($selected_type); ?>;
    var HAS_SEARCH = <?= $has_search ? 'true' : 'false'; ?>;

    /* ============================================================
       GLOBAL PRINT FOOTER — always update time before printing
       ============================================================ */
    function updatePrintTimestamp() {
        var el = document.getElementById('globalPrintTime');
        if (!el) return;
        var now = new Date();
        var pad = function (n) { return n < 10 ? '0' + n : '' + n; };
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var s = pad(now.getDate()) + '-' + months[now.getMonth()] + '-' + now.getFullYear()
              + ' ' + pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        el.textContent = s;
    }

    window.addEventListener('beforeprint', updatePrintTimestamp);
    updatePrintTimestamp();

    var printBtn = document.querySelector('button[onclick="window.print()"]');
    if (printBtn) {
        printBtn.removeAttribute('onclick');
        printBtn.addEventListener('click', function () {
            updatePrintTimestamp();
            window.print();
        });
    }

    /* ============================================================
       PRODUCTION REPORT (AJAX)
       ============================================================ */
    if (!HAS_SEARCH || SELECTED_TYPE !== 'production') return;

    var spinner = document.getElementById('prodRptSpinner');
    var tbody   = document.getElementById('prodRptBody');
    var tfoot   = document.getElementById('prodRptFoot');
    var countEl = document.getElementById('prodRptCount');

    function fmt(n) {
        n = Number(n || 0);
        return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function loadProductionReport() {
        tbody.innerHTML = '<tr><td colspan="14" class="text-center text-muted py-4">Loading…</td></tr>';
        tfoot.innerHTML = '';

        fetch('production_report.php?as_of_date=' + encodeURIComponent(AS_OF_DATE), {
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (spinner) spinner.classList.add('d-none');

            if (!data.ok) {
                tbody.innerHTML = '<tr><td colspan="14" class="text-center text-danger py-4">'
                    + esc(data.error || 'Failed to load.') + '</td></tr>';
                countEl.textContent = '0';
                return;
            }

            countEl.textContent = data.count || 0;

            if (!data.rows || data.rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="14" class="text-center text-muted py-4">'
                    + 'No factory production data found for this date.</td></tr>';
                return;
            }

            var html = '';
            data.rows.forEach(function (row, idx) {
                var dueClass = Number(row.due) < 0 ? ' text-danger' : '';
                html += '<tr>';
                html += '<td>' + (idx + 1) + '</td>';
                html += '<td class="fw-bold text-primary text-start">' + esc(row.office_name) + '</td>';
                html += '<td>' + esc(row.product_produce) + '</td>';
                html += '<td>M.T</td>';
                html += '<td class="text-end">' + fmt(row.installed_capacity) + '</td>';
                html += '<td class="text-end">' + fmt(row.daily_amount) + '</td>';
                html += '<td class="text-end">' + fmt(row.monthly_amount) + '</td>';
                html += '<td class="text-end">' + fmt(row.yearly_amount) + '</td>';
                html += '<td class="text-end">' + fmt(row.yearly_target) + '</td>';
                html += '<td class="text-end' + dueClass + '">' + fmt(row.due) + '</td>';
                html += '<td class="text-end">' + fmt(row.monthly_target) + '</td>';
                html += '<td class="text-end">' + fmt(row.monthly_till_date) + '</td>';
                html += '<td class="text-end">' + fmt(row.plant_load) + '</td>';
                html += '<td class="text-start small">' + esc(row.remarks || '—') + '</td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;

            var t = data.totals || {};
            tfoot.innerHTML =
                '<tr>' +
                    '<td colspan="4" class="text-end">Total =</td>' +
                    '<td class="text-end">' + fmt(t.installed_capacity) + '</td>' +
                    '<td class="text-end">' + fmt(t.daily_amount) + '</td>' +
                    '<td class="text-end">' + fmt(t.monthly_amount) + '</td>' +
                    '<td class="text-end">' + fmt(t.yearly_amount) + '</td>' +
                    '<td class="text-end">' + fmt(t.yearly_target) + '</td>' +
                    '<td class="text-end">' + fmt(t.due) + '</td>' +
                    '<td class="text-end">' + fmt(t.monthly_target) + '</td>' +
                    '<td class="text-end">' + fmt(t.monthly_till_date) + '</td>' +
                    '<td class="text-end">—</td>' +
                    '<td class="text-start">—</td>' +
                '</tr>';
        })
        .catch(function (err) {
            if (spinner) spinner.classList.add('d-none');
            tbody.innerHTML = '<tr><td colspan="14" class="text-center text-danger py-4">'
                + 'Network error: ' + esc(err.message || 'failed') + '</td></tr>';
        });
    }

    loadProductionReport();
});
</script>
</body>
</html>