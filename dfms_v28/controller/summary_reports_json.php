<?php
// summary_reports_json.php
// AJAX endpoint — returns the "All Buffer Summary Report" as JSON.

require_once('../db/db.php');

header('Content-Type: application/json; charset=utf-8');

function clean($val) {
    return trim((string)($val ?? ''));
}

try {
    // ---- 1. Fetch all unique buffers from office_tbl ----
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

        // ---- A) Allotted, Sent, Pending ----
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
        if ($prodStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ? AND date = CURDATE()")) {
            mysqli_stmt_bind_param($prodStmt, 's', $current_buffer);
            mysqli_stmt_execute($prodStmt);
            $pRes = mysqli_stmt_get_result($prodStmt);
            if ($p = mysqli_fetch_assoc($pRes)) $dashStats['production_today'] = (float)$p['s'];
            mysqli_stmt_close($prodStmt);
        }

        // ---- C) Daily Receive ----
        if ($receiveStmt = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS s
            FROM master_transaction mt
            INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
            LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
            LEFT JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
            WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
              AND COALESCE(ia.buffer_name, ua.receiver) = ?
              AND DATE(mt.created_at) = CURDATE()")) {
            mysqli_stmt_bind_param($receiveStmt, 's', $current_buffer);
            mysqli_stmt_execute($receiveStmt);
            $rRes = mysqli_stmt_get_result($receiveStmt);
            if ($rr = mysqli_fetch_assoc($rRes)) $dashStats['daily_receive'] = (float)$rr['s'];
            mysqli_stmt_close($receiveStmt);
        }

        // ---- D) Pipeline Amount ----
        if ($pipeStmt = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(b.amount), 0) AS s
            FROM buffer_transaction b
            LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
            LEFT JOIN urea_allotment ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
            WHERE b.status = 'pending' AND (ia.buffer_name = ? OR ua.receiver = ?)")) {
            mysqli_stmt_bind_param($pipeStmt, 'ss', $current_buffer, $current_buffer);
            mysqli_stmt_execute($pipeStmt);
            $pRes = mysqli_stmt_get_result($pipeStmt);
            if ($p = mysqli_fetch_assoc($pRes)) $dashStats['pipeline_amount'] = (float)$p['s'];
            mysqli_stmt_close($pipeStmt);
        }

        // ---- E1) OUT — pending to Buffer/Factory ----
        $dashStats['out_ua_today'] = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(bt.amount), 0) AS v
            FROM buffer_transaction bt
            INNER JOIN urea_allotment ua
                ON ua.id = bt.prod_allotment_id
                OR ua.id = bt.buffer_allotment_id
            WHERE bt.status = 'pending'
              AND DATE(bt.date) = CURDATE()
              AND ua.sender = ?")) {
            mysqli_stmt_bind_param($s, 's', $current_buffer);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($row = mysqli_fetch_assoc($r)) $dashStats['out_ua_today'] = (float)$row['v'];
            mysqli_stmt_close($s);
        }

        // ---- E2) OUT — completed to Buffer/Factory ----
        $dashStats['output'] = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(bt.amount), 0) AS v
            FROM buffer_transaction bt
            INNER JOIN urea_allotment ua
                ON ua.id = bt.prod_allotment_id
                OR ua.id = bt.buffer_allotment_id
            WHERE bt.status = 'complete'
              AND DATE(bt.date) = CURDATE()
              AND ua.sender = ?")) {
            mysqli_stmt_bind_param($s, 's', $current_buffer);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($row = mysqli_fetch_assoc($r)) $dashStats['output'] = (float)$row['v'];
            mysqli_stmt_close($s);
        }

        // ---- E3) OUT — To Dealer ----
        $dashStats['out_dealer_today'] = 0.0;
        if ($s = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS v
            FROM master_transaction mt
            INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
            INNER JOIN office_tbl o ON o.id = d.office_tbl_id
            WHERE mt.transaction_source IN ('buffer_out','factory_out')
              AND o.buffer_name = ?
              AND DATE(mt.created_at) = CURDATE()")) {
            mysqli_stmt_bind_param($s, 's', $current_buffer);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($row = mysqli_fetch_assoc($r)) $dashStats['out_dealer_today'] = (float)$row['v'];
            mysqli_stmt_close($s);
        }

        // ---- Daily delivery total ----
        $dashStats['daily_delivery'] =
              $dashStats['out_ua_today']
            + $dashStats['output']
            + $dashStats['out_dealer_today'];

        // ---- F) Stock Calculation ----
        $cond1All = 0.0;
        if ($receiveAllStmt = mysqli_prepare($conn, "
            SELECT COALESCE(SUM(mt.amount), 0) AS s FROM master_transaction mt
            INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
            LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
            LEFT JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
            WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
              AND COALESCE(ia.buffer_name, ua.receiver) = ?")) {
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
            WHERE mt.transaction_source IN ('buffer_out','factory_out')
              AND ua.sender = ?")) {
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
            WHERE mt.transaction_source IN ('buffer_out','factory_out')
              AND o.buffer_name = ?")) {
            mysqli_stmt_bind_param($s, 's', $current_buffer);
            mysqli_stmt_execute($s);
            $r = mysqli_stmt_get_result($s);
            if ($row = mysqli_fetch_assoc($r)) $outDealerAll = (float)$row['v'];
            mysqli_stmt_close($s);
        }

        $prodAll = 0.0;
        if ($prodAllStmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount), 0) AS s FROM production_tbl WHERE factory_name = ?")) {
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

    echo json_encode([
        'ok'    => true,
        'rows'  => $allBufferStats,
        'count' => count($allBufferStats),
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode([
        'ok'    => false,
        'error' => $e->getMessage(),
    ]);
}