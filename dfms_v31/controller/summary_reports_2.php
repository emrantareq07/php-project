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
        'advance'          => 0.0,
    ];

    // ---- A) Totals: Allotted, Sent, Pending ----
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

    // ---- B) Production Today ----
    $prodStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ? AND date = CURDATE()");
    if ($prodStmt) {
        mysqli_stmt_bind_param($prodStmt, 's', $current_buffer);
        mysqli_stmt_execute($prodStmt);
        $pRes = mysqli_stmt_get_result($prodStmt);
        if ($p = mysqli_fetch_assoc($pRes)) $dashStats['production_today'] = (float)$p['s'];
        mysqli_stmt_close($prodStmt);
    }

    // ---- C) Daily Receive ----
    $receiveStmt = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS s
        FROM master_transaction mt
        INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
        LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
        LEFT JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
        WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
          AND COALESCE(ia.buffer_name, ua.receiver) = ?
          AND DATE(mt.created_at) = CURDATE()");
    if ($receiveStmt) {
        mysqli_stmt_bind_param($receiveStmt, 's', $current_buffer);
        mysqli_stmt_execute($receiveStmt);
        $rRes = mysqli_stmt_get_result($receiveStmt);
        if ($rr = mysqli_fetch_assoc($rRes)) $dashStats['daily_receive'] = (float)$rr['s'];
        mysqli_stmt_close($receiveStmt);
    }

    // ---- D) Pipeline Amount ----
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

    // ---- E) Monthly Demand ----
    $dashStats['monthly_demand'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(md.d_amount + md.addition - md.substration), 0) AS v
        FROM monthly_demand md
        INNER JOIN office_tbl o ON o.id = md.office_tbl_id
        WHERE o.buffer_name = ?
          AND md.date = CURDATE()
    ")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['monthly_demand'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // ---- E2) Month Delivery ----
    $dashStats['month_delivery'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.dealer_id IS NOT NULL
          AND o.buffer_name = ?
          AND YEAR(mt.created_at) = YEAR(CURDATE())
          AND MONTH(mt.created_at) = MONTH(CURDATE())
    ")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['month_delivery'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // ---- E3) Advance + Rest of Delivery ----
    $dashStats['advance']          = max(0.0, $dashStats['month_delivery'] - $dashStats['monthly_demand']);
    $dashStats['rest_of_delivery'] = max(0.0, $dashStats['monthly_demand'] - $dashStats['month_delivery']);

    // =================================================================
    //  DAILY DELIVERIES
    // =================================================================

    // ---- 1) To Buffer / Factory — pending ----
    $dashStats['out_ua_today'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE bt.status = 'pending'
          AND DATE(bt.date) = CURDATE()
          AND ua.sender = ?
    ")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['out_ua_today'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    // ---- 2) To Buffer / Factory — completed ----
    $dashStats['output'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(bt.amount), 0) AS v
        FROM buffer_transaction bt
        INNER JOIN urea_allotment ua
            ON ua.id = bt.prod_allotment_id
            OR ua.id = bt.buffer_allotment_id
        WHERE bt.status = 'complete'
          AND DATE(bt.date) = CURDATE()
          AND ua.sender = ?
    ")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['output'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $dashStats['daily_delivery_to_buffer'] =
          $dashStats['out_ua_today']
        + $dashStats['output'];

    // ---- 3) To Dealer ----
    $dashStats['out_dealer_today'] = 0.0;
    if ($s = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS v
        FROM master_transaction mt
        INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
        INNER JOIN office_tbl o ON o.id = d.office_tbl_id
        WHERE mt.transaction_source IN ('buffer_out','factory_out')
          AND o.buffer_name = ?
          AND DATE(mt.created_at) = CURDATE()
    ")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $dashStats['out_dealer_today'] = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $dashStats['daily_delivery'] = $dashStats['out_dealer_today'];

    // ---- F) Stock Calculation ----
    $receiveAllStmt = mysqli_prepare($conn, "
        SELECT COALESCE(SUM(mt.amount), 0) AS s FROM master_transaction mt
        INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
        LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
        LEFT JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
        WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in') AND COALESCE(ia.buffer_name, ua.receiver) = ?");
    $cond1All = 0.0;
    if ($receiveAllStmt) {
        mysqli_stmt_bind_param($receiveAllStmt, 's', $current_buffer);
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
        WHERE mt.transaction_source IN ('buffer_out','factory_out') AND ua.sender = ?")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
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
        WHERE mt.transaction_source IN ('buffer_out','factory_out') AND o.buffer_name = ?")) {
        mysqli_stmt_bind_param($s, 's', $current_buffer);
        mysqli_stmt_execute($s);
        $r = mysqli_stmt_get_result($s);
        if ($row = mysqli_fetch_assoc($r)) $outDealerAll = (float)$row['v'];
        mysqli_stmt_close($s);
    }

    $prodAll = 0.0;
    $prodAllStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ?");
    if ($prodAllStmt) {
        mysqli_stmt_bind_param($prodAllStmt, 's', $current_buffer);
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
//  GROUP BUFFERS BY ZONE — ONLY THESE FIVE, IN THIS ORDER
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
    // Zones not in the list are skipped
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
    'advance'    => 0.0,
    'monthDel'   => 0.0,
    'totDel'     => 0.0,
    'rest'       => 0.0,
    'pipeline'   => 0.0,
];

$zoneLetters = range('A', 'Z');
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

        /* Zone section row inside the single table */
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
    Reporting Date: </h3>
        <span class="badge bg-primary fs-6">Total Buffers: <?= count($allBufferStats); ?></span>
        <a href="user_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <td colspan="16" class="text-end">(Figure in M.T)</td>
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
                        <th>Advance Delivery</th>
                        <th>Month Delivery</th>
                        <th>Total Delivery</th>
                        <th>Rest of Delivery</th>
                        <th>Pipeline Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($allBufferStats)): ?>
                    <tr><td colspan="16" class="text-muted text-center">No buffer data found.</td></tr>
                <?php else: ?>

                    <?php $zoneIndex = 0; $anyZoneRendered = false; ?>

                    <?php foreach ($groupedByZone as $zoneName => $rows): ?>
                        <?php if (empty($rows)) continue; // skip empty zones entirely ?>
                        <?php $anyZoneRendered = true; ?>

                        <?php
                        // Zone subtotal accumulators
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
                            'advance'    => 0.0,
                            'monthDel'   => 0.0,
                            'totDel'     => 0.0,
                            'rest'       => 0.0,
                            'pipeline'   => 0.0,
                        ];
                        ?>

                        <!-- Zone section header inside the same table -->
                        <tr class="zone-section-row">
                            <td colspan="16">
                                <?= $zoneLetters[$zoneIndex] ?? ('#' . ($zoneIndex + 1)); ?>) <?= h($zoneName); ?>
                                <span class="text-muted small">(<?= count($rows); ?> buffer<?= count($rows) === 1 ? '' : 's'; ?>)</span>
                            </td>
                        </tr>

                        <?php foreach ($rows as $i => $st):
                            $d = zoneRowDerived($st);
                            $currentStockCalc = $d['current_stock_calc'];
                            $openingBalCalc   = $d['opening_bal_calc'];

                            // Zone subtotal accumulation
                            $z['capacity']   += (float)($st['capacity'] ?? 0);
                            $z['opening']    += $openingBalCalc;
                            $z['prod']       += (float)($st['production_today'] ?? 0);
                            $z['receive']    += (float)($st['daily_receive'] ?? 0);
                            $z['receiveAll'] += (float)($st['production_today'] ?? 0) + (float)($st['daily_receive'] ?? 0);
                            $z['transfer']   += (float)($st['daily_delivery_to_buffer'] ?? 0);
                            $z['delivery']   += (float)($st['daily_delivery'] ?? 0);
                            $z['stock']      += $currentStockCalc;
                            $z['demand']     += (float)($st['monthly_demand'] ?? 0);
                            $z['advance']    += (float)($st['advance'] ?? 0);
                            $z['monthDel']   += (float)($st['month_delivery'] ?? 0) - (float)($st['advance'] ?? 0);
                            $z['totDel']     += (float)($st['month_delivery'] ?? 0);
                            $z['rest']       += (float)($st['rest_of_delivery'] ?? 0);
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
                                <td class="fw-bold text-danger">
                                    <?php if ((float)$st['advance'] > 0): ?>
                                        <?= h(number_format((float)$st['advance'], 2)); ?>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h(number_format((float)$st['month_delivery'] - (float)$st['advance'], 2)); ?></td>
                                <td><?= h(number_format((float)$st['month_delivery'], 2)); ?></td>
                                <td><?= h(number_format((float)$st['rest_of_delivery'], 2)); ?></td>
                                <td><?= h(number_format((float)$st['pipeline_amount'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Zone subtotal row -->
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
                            <td class="text-danger"><?= number_format($z['advance'], 2); ?></td>
                            <td><?= number_format($z['monthDel'], 2); ?></td>
                            <td><?= number_format($z['totDel'], 2); ?></td>
                            <td><?= number_format($z['rest'], 2); ?></td>
                            <td><?= number_format($z['pipeline'], 2); ?></td>
                        </tr>

                        <?php
                        // Accumulate into grand total
                        $grand['capacity']   += $z['capacity'];
                        $grand['opening']    += $z['opening'];
                        $grand['prod']       += $z['prod'];
                        $grand['receive']    += $z['receive'];
                        $grand['receiveAll'] += $z['receiveAll'];
                        $grand['transfer']   += $z['transfer'];
                        $grand['delivery']   += $z['delivery'];
                        $grand['stock']      += $z['stock'];
                        $grand['demand']     += $z['demand'];
                        $grand['advance']    += $z['advance'];
                        $grand['monthDel']   += $z['monthDel'];
                        $grand['totDel']     += $z['totDel'];
                        $grand['rest']       += $z['rest'];
                        $grand['pipeline']   += $z['pipeline'];
                        $zoneIndex++;
                        ?>
                    <?php endforeach; ?>

                    <?php if (!$anyZoneRendered): ?>
                        <tr><td colspan="16" class="text-muted text-center">No buffers found for the configured zones.</td></tr>
                    <?php else: ?>
                        <!-- GRAND TOTAL ROW -->
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
                            <td><?= number_format($grand['advance'], 2); ?></td>
                            <td><?= number_format($grand['monthDel'], 2); ?></td>
                            <td><?= number_format($grand['totDel'], 2); ?></td>
                            <td><?= number_format($grand['rest'], 2); ?></td>
                            <td><?= number_format($grand['pipeline'], 2); ?></td>
                        </tr>
                    <?php endif; ?>

                <?php endif; ?>
                </tbody>
            </table>            
        </div>
         <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <td colspan="16" class="text-end">(Figure in M.T)</td>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Factory Name</th> 

                        Production header under those 3 column
                        <th>Daily</th>
                        <th>Monthly</th>
                        <th>Yearly</th>

                        Lifting header under those 3 column
                        <th>Daily</th>
                        <th>Monthly</th>
                        <th>Yearly</th>


                        <th>Stock </th> is single col
                        
                    </tr>
                </thead>
                <tbody>
    </div>
</div>

</body>
</html>