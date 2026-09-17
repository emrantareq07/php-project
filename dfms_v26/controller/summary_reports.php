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

// =================================================================
//  DAILY DELIVERIES (To Buffer & To Dealer)
// =================================================================

// ---- 1) To Buffer / Factory — pending buffer_transaction where
//          this buffer is the sender in urea_allotment ----
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


// ---- 2) To Buffer / Factory — completed master_transaction


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


// ---- 3) To Dealer — master_transaction rows where the dealer's
//          office belongs to this buffer ----
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

// ---- Combine the three ----
$dashStats['daily_delivery'] =
      $dashStats['out_ua_today']
    + $dashStats['output']
    + $dashStats['out_dealer_today'];





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
        .bg-delivery-total { background-color: #fff7ed; font-weight: bold; }
        .bg-subrow { background-color: #fafafa; font-size: 12px; color: #475569; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa fa-leaf"></i> Smart Fertilizer Monitoring System (SFMS), BCIC</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">All Buffer Summary Report</h3>
        <span class="badge bg-primary fs-6">Total Buffers: <?= count($allBufferStats); ?></span>
        <a href="user_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <!-- MAIN TABULAR REPORT -->
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th >#</th>
                        <th >Factory / Buffer Name</th>
                        <th >Zone</th>
                        <th >Capacity (MT)</th>
                        <th >Opening Stock (MT)</th>
                        <th >Production Today (MT)</th>
                        <th >Daily Receive(MT)</th>
                        <th >Daily Delivery (MT) </th>
                        <th >Closing Stock (MT)</th>
                        <th >Pipeline Amount (MT)</th>
                        
                    
                    </tr>
                </thead>
              
<tbody>
    <?php if (empty($allBufferStats)): ?>

        <tr>
            <td colspan="10" class="text-muted text-center">
                No buffer data found.
            </td>
        </tr>

    <?php else: ?>

        <?php
        // Initialize totals
        $totalCapacity       = 0;
        $totalOpeningBal     = 0;
        $totalProduction     = 0;
        $totalReceive        = 0;
        $totalDelivery       = 0;
        $totalCurrentStock   = 0;
        $totalPipeline       = 0;
        ?>

        <?php foreach ($allBufferStats as $index => $st): ?>

            <?php
            // Add values to totals
            $totalCapacity     += (float)($st['capacity'] ?? 0);
            $totalOpeningBal   += (float)($st['opening_bal'] ?? 0);
            $totalProduction   += (float)($st['production_today'] ?? 0);
            $totalReceive      += (float)($st['daily_receive'] ?? 0);
            $totalDelivery     += (float)($st['daily_delivery'] ?? 0);
            $totalCurrentStock += (float)($st['current_stock'] ?? 0);
            $totalPipeline     += (float)($st['pipeline_amount'] ?? 0);
            ?>

            <tr>
                <td class="fw-bold">
                    <?= $index + 1; ?>
                </td>

                <td class="text-left fw-bold text-primary">
                    <?= dash(
                        $st['office_name'] !== ''
                            ? $st['office_name']
                            : $st['buffer_name']
                    ); ?>
                </td>

                <td>
                    <?= dash($st['zone']); ?>
                </td>

                <td>
                    <?= h(number_format($st['capacity'], 2)); ?>
                </td>

                <td>
                    <?= h(number_format($st['opening_bal'], 2)); ?>
                </td>

                <td>
                    <?= h(number_format($st['production_today'], 2)); ?>
                </td>

                <td>
                    <?= h(number_format($st['daily_receive'], 2)); ?>
                </td>

                <td class="fw-bold">
                    <?= h(number_format($st['daily_delivery'], 2)); ?>
                </td>

                <td class="fw-bold text-success">
                    <?= h(number_format($st['current_stock'], 2)); ?>
                </td>

                <td>
                    <?= h(number_format($st['pipeline_amount'], 2)); ?>
                </td>
            </tr>

        <?php endforeach; ?>

        <!-- TOTAL ROW -->
        <tr class="fw-bold table-secondary">
            <td colspan="3" class="text-end">
                Total =
            </td>

            <td>
                <?= number_format($totalCapacity, 2); ?>
            </td>

            <td>
                <?= number_format($totalOpeningBal, 2); ?>
            </td>

            <td>
                <?= number_format($totalProduction, 2); ?>
            </td>

            <td>
                <?= number_format($totalReceive, 2); ?>
            </td>

            <td>
                <?= number_format($totalDelivery, 2); ?>
            </td>

            <td class="text-success">
                <?= number_format($totalCurrentStock, 2); ?>
            </td>

            <td>
                <?= number_format($totalPipeline, 2); ?>
            </td>
        </tr>

    <?php endif; ?>
</tbody>


            </table>
        </div>
    </div>
</div>

</body>
</html>