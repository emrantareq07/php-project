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
function dash($val) {
    $v = trim((string)($val ?? ''));
    return $v !== '' ? h($v) : '<span class="text-muted">—</span>';
}
function statusClass(string $status): string {
    $s = strtolower(trim($status));
    if (str_contains($s, 'pend'))   return 'status-pending';
    if (str_contains($s, 'appro'))  return 'status-approved';
    if (str_contains($s, 'reject')) return 'status-rejected';
    if (str_contains($s, 'comp'))   return 'status-approved';
    return 'status-default';
}
function deriveSource(array $r): string {
    if (!empty($r['bt_import_allotment_id']) && (int)$r['bt_import_allotment_id'] > 0) return 'port_in';
    if (!empty($r['bt_prod_allotment_id'])   && (int)$r['bt_prod_allotment_id']   > 0) return 'factory_in';
    if (!empty($r['bt_buffer_allotment_id']) && (int)$r['bt_buffer_allotment_id'] > 0) return 'buffer_in';
    return 'unknown';
}
function sourceBadge(string $source): string {
    return match ($source) {
        'port_in'    => '<span class="badge bg-primary-subtle text-primary">port_in</span>',
        'factory_in' => '<span class="badge bg-success-subtle text-success">factory_in</span>',
        'buffer_in'  => '<span class="badge bg-warning-subtle text-warning">buffer_in</span>',
        'factory_out'=> '<span class="badge bg-info-subtle text-info">factory_out</span>',
        'buffer_out' => '<span class="badge bg-secondary-subtle text-secondary">buffer_out</span>',
        default      => '<span class="text-muted">—</span>',
    };
}
function mapOfficeTypeToSource(string $officeType, string $side): ?string {
    $t = strtolower(trim($officeType));
    $suffix = ($side === 'out') ? '_out' : '_in';
    if ($t === 'buffer_godown')  return 'buffer'  . $suffix;
    if ($t === 'factory_office') return 'factory' . $suffix;
    return null;
}

// -----------------------------------------------------------------
// FLASH + modal state
// -----------------------------------------------------------------
$flash               = $_SESSION['flash']               ?? null;
$oldDelivery         = $_SESSION['old_delivery']         ?? ['dealer_id' => '', 'amount' => '', 'date' => date('Y-m-d')];
$oldOpening          = $_SESSION['old_opening']          ?? ['opening_bal' => '', 'capacity' => ''];
$reopenDeliveryModal = $_SESSION['reopen_delivery_modal'] ?? false;
$reopenOpeningModal  = $_SESSION['reopen_opening_modal']  ?? false;

unset(
    $_SESSION['flash'],
    $_SESSION['old_delivery'],
    $_SESSION['old_opening'],
    $_SESSION['reopen_delivery_modal'],
    $_SESSION['reopen_opening_modal']
);

// =================================================================
// HANDLE: UPDATE OPENING BALANCE + CAPACITY
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_opening') {
    $openingBal = trim($_POST['opening_bal'] ?? '');
    $capacity   = trim($_POST['capacity']    ?? '');

    $errors = [];
    if ($openingBal === '' || !is_numeric($openingBal) || (float)$openingBal < 0) {
        $errors[] = 'Opening balance must be a non-negative number.';
    }
    if ($capacity === '' || !is_numeric($capacity) || (float)$capacity < 0) {
        $errors[] = 'Capacity must be a non-negative number.';
    }

    if ($errors) {
        $_SESSION['flash']               = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_opening']         = ['opening_bal' => $openingBal, 'capacity' => $capacity];
        $_SESSION['reopen_opening_modal'] = true;
        header('Location: ' . $_SERVER['PHP_SELF'] . (!empty($_GET) ? '?' . http_build_query($_GET) : ''));
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $ob = (float)$openingBal;
        $cp = (float)$capacity;

        $sql = "UPDATE office_tbl
                   SET opening_bal = ?, capacity = ?, updated_at = NOW()
                 WHERE buffer_name = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

        mysqli_stmt_bind_param($stmt, 'dds', $ob, $cp, $logged_in_user);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
        }
        if (mysqli_stmt_affected_rows($stmt) === 0) {
            throw new Exception('No office matched buffer_name = ' . $logged_in_user . ' (or values unchanged).');
        }
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        $_SESSION['flash'] = [
            'type' => 'success',
            'msg'  => "Opening balance updated to " . number_format($ob, 2) . " and capacity to " . number_format($cp, 2) . "."
        ];
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $_SESSION['flash']               = ['type' => 'danger', 'msg' => 'Update failed: ' . $e->getMessage()];
        $_SESSION['old_opening']         = ['opening_bal' => $openingBal, 'capacity' => $capacity];
        $_SESSION['reopen_opening_modal'] = true;
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . (!empty($_GET) ? '?' . http_build_query($_GET) : ''));
    exit();
}

// =================================================================
// HANDLE: ADD DELIVERY TO DEALER
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_delivery') {
    $dealerId = (int)($_POST['dealer_id'] ?? 0);
    $amount   = trim($_POST['amount']      ?? '');
    $date     = trim($_POST['delivery_date'] ?? '');

    $errors = [];
    if ($dealerId <= 0)   $errors[] = 'Please select a dealer.';
    if ($amount === '' || !is_numeric($amount) || (float)$amount <= 0) {
        $errors[] = 'Amount must be a positive number.';
    }
    if ($date === '' || !DateTime::createFromFormat('Y-m-d', $date)) {
        $errors[] = 'A valid date is required (YYYY-MM-DD).';
    }

    $dealerRow = null;
    if (!$errors) {
        $dSql = "
            SELECT d.id, d.name, d.office_tbl_id, o.office_type
            FROM dealer_tbl d
            LEFT JOIN office_tbl o ON o.id = d.office_tbl_id
            WHERE d.id = ?
            LIMIT 1
        ";
        $dStmt = mysqli_prepare($conn, $dSql);
        if ($dStmt) {
            mysqli_stmt_bind_param($dStmt, 'i', $dealerId);
            mysqli_stmt_execute($dStmt);
            $dRes = mysqli_stmt_get_result($dStmt);
            $dealerRow = mysqli_fetch_assoc($dRes) ?: null;
            mysqli_stmt_close($dStmt);
        }
        if (!$dealerRow) {
            $errors[] = 'Selected dealer not found.';
        }
    }

    $source = null;
    if (!$errors && $dealerRow) {
        $officeType = strtolower(trim((string)$dealerRow['office_type']));
        if ($officeType === 'buffer_godown') {
            $source = 'buffer_out';
        } elseif ($officeType === 'factory_office') {
            $source = 'factory_out';
        } else {
            $errors[] = "Dealer's office type '{$officeType}' is not supported for delivery (buffer_godown or factory_office expected).";
        }
    }

    if ($errors) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_delivery'] = ['dealer_id' => $dealerId, 'amount' => $amount, 'date' => $date];
        $_SESSION['reopen_delivery_modal'] = true;
        header('Location: ' . $_SERVER['PHP_SELF'] . (!empty($_GET) ? '?' . http_build_query($_GET) : ''));
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $amt  = (float)$amount;
        $note = "delivery to dealer #{$dealerId} ({$dealerRow['name']}) on {$date}";

        $insSql = "
            INSERT INTO master_transaction
                (dealer_id, transaction_source, amount, remarks, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ";
        $stmt = mysqli_prepare($conn, $insSql);
        if (!$stmt) throw new Exception('Prepare (delivery insert) failed: ' . mysqli_error($conn));

        mysqli_stmt_bind_param($stmt, 'isds', $dealerId, $source, $amt, $note);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
        }
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => "Delivery #{$newId} recorded for dealer '{$dealerRow['name']}' ({$source})."];
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Delivery insert failed: ' . $e->getMessage()];
        $_SESSION['old_delivery'] = ['dealer_id' => $dealerId, 'amount' => $amount, 'date' => $date];
        $_SESSION['reopen_delivery_modal'] = true;
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . (!empty($_GET) ? '?' . http_build_query($_GET) : ''));
    exit();
}

// =================================================================
// HANDLE "ACCEPT" (POST)
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_bt_id'])) {
    $btId = (int)$_POST['accept_bt_id'];

    if ($btId <= 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Invalid transaction id.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $selSql = "
            SELECT b.id, b.amount, b.status,
                   b.import_allotment_id, b.prod_allotment_id, b.buffer_allotment_id
            FROM buffer_transaction b
            WHERE b.id = ?
              AND b.status = 'pending'
              AND (
                    b.import_allotment_id IN (SELECT id FROM import_allotment WHERE buffer_name = ?)
                 OR b.prod_allotment_id   IN (SELECT id FROM urea_allotment   WHERE receiver    = ?)
                 OR b.buffer_allotment_id IN (SELECT id FROM urea_allotment   WHERE receiver    = ?)
              )
            FOR UPDATE
        ";
        $stmt = mysqli_prepare($conn, $selSql);
        if (!$stmt) throw new Exception('Prepare (select) failed: ' . mysqli_error($conn));

        mysqli_stmt_bind_param($stmt, 'isss', $btId, $logged_in_user, $logged_in_user, $logged_in_user);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $bt  = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if (!$bt) throw new Exception('Transaction not found, not yours, or not pending.');

        $amount = (float)$bt['amount'];

        $isImportPath = !empty($bt['import_allotment_id']) && (int)$bt['import_allotment_id'] > 0;
        $isUreaPath   = !empty($bt['prod_allotment_id']) || !empty($bt['buffer_allotment_id']);

        if ($isImportPath && !$isUreaPath) {
            $source = 'port_in';
            $insSql = "
                INSERT INTO master_transaction
                    (buffer_transaction_id, transaction_source, amount, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW())
            ";
            $stmt = mysqli_prepare($conn, $insSql);
            if (!$stmt) throw new Exception('Prepare (insert) failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'isd', $btId, $source, $amount);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);

        } elseif ($isUreaPath) {
            $ureaId = !empty($bt['prod_allotment_id'])
                ? (int)$bt['prod_allotment_id']
                : (int)$bt['buffer_allotment_id'];

            if ($ureaId <= 0) throw new Exception('Cannot determine urea_allotment id.');

            $uaSql = "SELECT id, sender, receiver FROM urea_allotment WHERE id = ? LIMIT 1";
            $ua = mysqli_prepare($conn, $uaSql);
            if (!$ua) throw new Exception('Prepare (urea lookup) failed: ' . mysqli_error($conn));
            mysqli_stmt_bind_param($ua, 'i', $ureaId);
            mysqli_stmt_execute($ua);
            $uaRes = mysqli_stmt_get_result($ua);
            $uaRow = mysqli_fetch_assoc($uaRes);
            mysqli_stmt_close($ua);

            if (!$uaRow) throw new Exception("urea_allotment #{$ureaId} not found.");

            $senderBuffer   = (string)$uaRow['sender'];
            $receiverBuffer = (string)$uaRow['receiver'];

            $oStmt = mysqli_prepare($conn, "SELECT office_type FROM office_tbl WHERE buffer_name = ? LIMIT 1");
            if (!$oStmt) throw new Exception('Prepare (office lookup) failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($oStmt, 's', $senderBuffer);
            mysqli_stmt_execute($oStmt);
            $oRes = mysqli_stmt_get_result($oStmt);
            $senderOfficeType = (mysqli_fetch_assoc($oRes)['office_type'] ?? '');

            mysqli_stmt_bind_param($oStmt, 's', $receiverBuffer);
            mysqli_stmt_execute($oStmt);
            $oRes = mysqli_stmt_get_result($oStmt);
            $receiverOfficeType = (mysqli_fetch_assoc($oRes)['office_type'] ?? '');

            mysqli_stmt_close($oStmt);

            $senderSource   = mapOfficeTypeToSource($senderOfficeType,   'out');
            $receiverSource = mapOfficeTypeToSource($receiverOfficeType, 'in');

            if ($senderSource === null)   throw new Exception("Sender office type '{$senderOfficeType}' not supported.");
            if ($receiverSource === null) throw new Exception("Receiver office type '{$receiverOfficeType}' not supported.");

            $insSql = "
                INSERT INTO master_transaction
                    (buffer_transaction_id, transaction_source, amount, remarks, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ";

            $stmt = mysqli_prepare($conn, $insSql);
            if (!$stmt) throw new Exception('Prepare (sender insert) failed: ' . mysqli_error($conn));
            $senderRemarks = "from {$senderBuffer}";
            mysqli_stmt_bind_param($stmt, 'isds', $btId, $senderSource, $amount, $senderRemarks);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Sender insert failed: ' . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn, $insSql);
            if (!$stmt) throw new Exception('Prepare (receiver insert) failed: ' . mysqli_error($conn));
            $receiverRemarks = "to {$receiverBuffer}";
            mysqli_stmt_bind_param($stmt, 'isds', $btId, $receiverSource, $amount, $receiverRemarks);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Receiver insert failed: ' . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);

        } else {
            throw new Exception("buffer_transaction #{$btId} has no valid source column.");
        }

        $updSql = "
            UPDATE buffer_transaction
               SET status = 'complete', updated_at = NOW()
             WHERE id = ? AND status = 'pending'
        ";
        $stmt = mysqli_prepare($conn, $updSql);
        if (!$stmt) throw new Exception('Prepare (update) failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $btId);
        if (!mysqli_stmt_execute($stmt)) throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
        if (mysqli_stmt_affected_rows($stmt) !== 1) throw new Exception('Status update affected unexpected rows.');
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => "Transaction #{$btId} accepted successfully."];
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Accept failed: ' . $e->getMessage()];
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . (!empty($_GET) ? '?' . http_build_query($_GET) : ''));
    exit();
}

// =================================================================
//  DASHBOARD CALCULATIONS
// =================================================================
$dashStats = [
    'production_today'   => 0.0,
    'current_stock'      => 0.0,
    'daily_receive'      => 0.0,
    'daily_delivery'     => 0.0,
    'pipeline_amount'    => 0.0,
    'opening_bal'        => 0.0,
    'capacity'           => 0.0,
];

// ---- A) Opening Balance + Capacity (also used later for stock) ----
$currentOpening  = '';
$currentCapacity = '';
$obStmt = mysqli_prepare($conn, "SELECT opening_bal, capacity FROM office_tbl WHERE buffer_name = ? LIMIT 1");
if ($obStmt) {
    mysqli_stmt_bind_param($obStmt, 's', $logged_in_user);
    mysqli_stmt_execute($obStmt);
    $obRes = mysqli_stmt_get_result($obStmt);
    $obRow = mysqli_fetch_assoc($obRes);
    mysqli_stmt_close($obStmt);
    if ($obRow) {
        $currentOpening  = (string)($obRow['opening_bal'] ?? '');
        $currentCapacity = (string)($obRow['capacity']    ?? '');
        $dashStats['opening_bal'] = (float)$currentOpening;
        $dashStats['capacity']    = (float)$currentCapacity;
    }
}
$openingFormValue  = $oldOpening['opening_bal'] !== '' ? $oldOpening['opening_bal'] : $currentOpening;
$capacityFormValue = $oldOpening['capacity']    !== '' ? $oldOpening['capacity']    : $currentCapacity;

// ---- B) Total Production Today ----
$prodStmt = mysqli_prepare($conn,
    "SELECT COALESCE(SUM(daily_amount), 0) AS s
     FROM production_tbl
     WHERE factory_name = ? AND date = CURDATE()"
);
if ($prodStmt) {
    mysqli_stmt_bind_param($prodStmt, 's', $logged_in_user);
    mysqli_stmt_execute($prodStmt);
    $prodRes = mysqli_stmt_get_result($prodStmt);
    if ($p = mysqli_fetch_assoc($prodRes)) $dashStats['production_today'] = (float)$p['s'];
    mysqli_stmt_close($prodStmt);
}

// ---- C) Condition 1 — Daily Receive (IN to this buffer, today) ----
$receiveStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS s
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
    LEFT JOIN urea_allotment   ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
      AND COALESCE(ia.buffer_name, ua.receiver) = ?
      AND DATE(mt.created_at) = CURDATE()
");
if ($receiveStmt) {
    mysqli_stmt_bind_param($receiveStmt, 's', $logged_in_user);
    mysqli_stmt_execute($receiveStmt);
    $rRes = mysqli_stmt_get_result($receiveStmt);
    if ($rr = mysqli_fetch_assoc($rRes)) $dashStats['daily_receive'] = (float)$rr['s'];
    mysqli_stmt_close($receiveStmt);
}

// ---- D) Condition 1 (all-time) — for Current Stock ----
$receiveAllStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS s
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
    LEFT JOIN urea_allotment   ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
      AND COALESCE(ia.buffer_name, ua.receiver) = ?
");
$cond1All = 0.0;
if ($receiveAllStmt) {
    mysqli_stmt_bind_param($receiveAllStmt, 's', $logged_in_user);
    mysqli_stmt_execute($receiveAllStmt);
    $rRes = mysqli_stmt_get_result($receiveAllStmt);
    if ($rr = mysqli_fetch_assoc($rRes)) $cond1All = (float)$rr['s'];
    mysqli_stmt_close($receiveAllStmt);
}

// ---- E) Condition 2 — OUT via urea_allotment sender (today + all-time) ----
$outSenderTodayStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS s
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    INNER JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('buffer_out','factory_out')
      AND ua.sender = ?
      AND DATE(mt.created_at) = CURDATE()
");
$cond2Today = 0.0;
if ($outSenderTodayStmt) {
    mysqli_stmt_bind_param($outSenderTodayStmt, 's', $logged_in_user);
    mysqli_stmt_execute($outSenderTodayStmt);
    $rRes = mysqli_stmt_get_result($outSenderTodayStmt);
    if ($rr = mysqli_fetch_assoc($rRes)) $cond2Today = (float)$rr['s'];
    mysqli_stmt_close($outSenderTodayStmt);
}

$outSenderAllStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS s
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    INNER JOIN urea_allotment ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('buffer_out','factory_out')
      AND ua.sender = ?
");
$cond2All = 0.0;
if ($outSenderAllStmt) {
    mysqli_stmt_bind_param($outSenderAllStmt, 's', $logged_in_user);
    mysqli_stmt_execute($outSenderAllStmt);
    $rRes = mysqli_stmt_get_result($outSenderAllStmt);
    if ($rr = mysqli_fetch_assoc($rRes)) $cond2All = (float)$rr['s'];
    mysqli_stmt_close($outSenderAllStmt);
}

// ---- F) Condition 3 — OUT via dealer delivery (today + all-time) ----
$outDealerTodayStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS s
    FROM master_transaction mt
    INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
    INNER JOIN office_tbl o ON o.id = d.office_tbl_id
    WHERE mt.transaction_source IN ('buffer_out','factory_out')
      AND o.buffer_name = ?
      AND DATE(mt.created_at) = CURDATE()
");
$cond3Today = 0.0;
if ($outDealerTodayStmt) {
    mysqli_stmt_bind_param($outDealerTodayStmt, 's', $logged_in_user);
    mysqli_stmt_execute($outDealerTodayStmt);
    $rRes = mysqli_stmt_get_result($outDealerTodayStmt);
    if ($rr = mysqli_fetch_assoc($rRes)) $cond3Today = (float)$rr['s'];
    mysqli_stmt_close($outDealerTodayStmt);
}

$outDealerAllStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount), 0) AS s
    FROM master_transaction mt
    INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
    INNER JOIN office_tbl o ON o.id = d.office_tbl_id
    WHERE mt.transaction_source IN ('buffer_out','factory_out')
      AND o.buffer_name = ?
");
$cond3All = 0.0;
if ($outDealerAllStmt) {
    mysqli_stmt_bind_param($outDealerAllStmt, 's', $logged_in_user);
    mysqli_stmt_execute($outDealerAllStmt);
    $rRes = mysqli_stmt_get_result($outDealerAllStmt);
    if ($rr = mysqli_fetch_assoc($rRes)) $cond3All = (float)$rr['s'];
    mysqli_stmt_close($outDealerAllStmt);
}

// ---- G) Production all-time (for stock) ----
$prodAllStmt = mysqli_prepare($conn,
    "SELECT COALESCE(SUM(daily_amount), 0) AS s
     FROM production_tbl
     WHERE factory_name = ?"
);
$prodAll = 0.0;
if ($prodAllStmt) {
    mysqli_stmt_bind_param($prodAllStmt, 's', $logged_in_user);
    mysqli_stmt_execute($prodAllStmt);
    $pRes = mysqli_stmt_get_result($prodAllStmt);
    if ($p = mysqli_fetch_assoc($pRes)) $prodAll = (float)$p['s'];
    mysqli_stmt_close($prodAllStmt);
}

// ---- H) Final values ----
$dashStats['daily_delivery'] = $cond2Today + $cond3Today;       // total delivery (today)
$dashStats['current_stock']  = $prodAll
                             + $dashStats['opening_bal']
                             + $cond1All
                             - $cond2All
                             - $cond3All;

// expose the two sub-totals for the card
$outUaToday     = $cond2Today;      // To Buffer / Factory (today)
$outDealerToday = $cond3Today;      // To Dealer (today)

// ---- I) Pipeline amount (pending buffer_transaction totals for this buffer) ----
$pipeStmt = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(b.amount), 0) AS s
    FROM buffer_transaction b
    LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
    LEFT JOIN urea_allotment   ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
    WHERE b.status = 'pending'
      AND (ia.buffer_name = ? OR ua.receiver = ?)
");
if ($pipeStmt) {
    mysqli_stmt_bind_param($pipeStmt, 'ss', $logged_in_user, $logged_in_user);
    mysqli_stmt_execute($pipeStmt);
    $pRes = mysqli_stmt_get_result($pipeStmt);
    if ($p = mysqli_fetch_assoc($pRes)) $dashStats['pipeline_amount'] = (float)$p['s'];
    mysqli_stmt_close($pipeStmt);
}

// =================================================================
//  Existing UI data: dealers, statuses, rows, totals
// =================================================================

// ---- Dealers for the modal ----
$dealers = [];
$dSql = "
    SELECT d.id, d.name, d.office_tbl_id, o.office_name, o.office_type
    FROM dealer_tbl d
    INNER JOIN office_tbl o ON o.id = d.office_tbl_id
    WHERE o.buffer_name = ?
    ORDER BY d.name ASC
";
if ($dStmt = mysqli_prepare($conn, $dSql)) {
    mysqli_stmt_bind_param($dStmt, 's', $logged_in_user);
    mysqli_stmt_execute($dStmt);
    $dRes = mysqli_stmt_get_result($dStmt);
    while ($d = mysqli_fetch_assoc($dRes)) $dealers[] = $d;
    mysqli_stmt_close($dStmt);
}

// ---- Filters ----
$filterStatus = isset($_GET['status']) ? trim($_GET['status']) : '';

$statusList = [];
$rows       = [];
$totals     = ['allotted' => 0.0, 'sent' => 0.0, 'pending' => 0.0];

$sql = "
    SELECT DISTINCT b.status
    FROM buffer_transaction b
    WHERE b.status IS NOT NULL
      AND (
            b.import_allotment_id IN (SELECT id FROM import_allotment WHERE buffer_name = ?)
         OR b.prod_allotment_id   IN (SELECT id FROM urea_allotment   WHERE receiver    = ?)
         OR b.buffer_allotment_id IN (SELECT id FROM urea_allotment   WHERE receiver    = ?)
      )
    ORDER BY b.status ASC
";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, 'sss', $logged_in_user, $logged_in_user, $logged_in_user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($res)) {
        if ($r['status'] !== null && $r['status'] !== '') $statusList[] = $r['status'];
    }
    mysqli_stmt_close($stmt);
}

$txSql = "
    SELECT
        b.id                      AS bt_id,
        b.import_allotment_id     AS bt_import_allotment_id,
        b.prod_allotment_id       AS bt_prod_allotment_id,
        b.buffer_allotment_id     AS bt_buffer_allotment_id,
        b.date                    AS bt_date,
        b.amount                  AS bt_amount,
        b.medium                  AS bt_medium,
        b.status                  AS bt_status,
        b.created_by              AS bt_created_by,
        b.created_at              AS bt_created_at,
        b.updated_at              AS bt_updated_at,
        ia.id                     AS ia_id,
        ia.ref_no                 AS ia_ref_no,
        ia.buffer_name            AS ia_buffer_name,
        ia.amount                 AS ia_amount,
        ia.mdium                  AS ia_mdium,
        ua.id                     AS ua_id,
        ua.ref_no                 AS ua_ref_no,
        ua.sender                 AS ua_sender,
        ua.receiver               AS ua_receiver,
        ua.amount                 AS ua_amount,
        ua.medium                 AS ua_medium,
        ua.total_allot_amount                 AS ua_total_allot_amount
    FROM buffer_transaction b
    LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
    LEFT JOIN urea_allotment   ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
    WHERE (
            ia.buffer_name = ?
         OR ua.receiver   = ?
    )
";
$params = [$logged_in_user, $logged_in_user];
$types  = 'ss';

if ($filterStatus !== '') {
    $txSql   .= " AND b.status = ? ";
    $params[] = $filterStatus;
    $types   .= 's';
}
$txSql .= " ORDER BY b.id DESC";

if ($stmt = mysqli_prepare($conn, $txSql)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    mysqli_stmt_close($stmt);
}

$pendingSql = "
    SELECT
        COALESCE(SUM(t.total_allotted), 0) AS total_allotted,
        COALESCE(SUM(t.total_sent),     0) AS total_sent
    FROM (
        SELECT ia.amount AS total_allotted, COALESCE(b.amount, 0) AS total_sent
        FROM import_allotment ia
        LEFT JOIN buffer_transaction b
               ON b.import_allotment_id = ia.id AND b.status = 'pending'
        WHERE ia.buffer_name = ?
        UNION ALL
        SELECT ua.total_allot_amount AS total_allotted, COALESCE(b2.amount, 0) AS total_sent
        FROM urea_allotment ua
        LEFT JOIN buffer_transaction b2
               ON (b2.prod_allotment_id = ua.id OR b2.buffer_allotment_id = ua.id)
              AND b2.status = 'pending'
        WHERE ua.receiver = ?
    ) t
";
if ($stmt = mysqli_prepare($conn, $pendingSql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $logged_in_user, $logged_in_user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $totals['allotted'] = (float)$row['total_allotted'];
        $totals['sent']     = (float)$row['total_sent'];
        $totals['pending']  = $totals['allotted'] - $totals['sent'];
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - Buffer Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { background:#f4f6fa; }
    .summary-pill { padding:5px 12px; border-radius:14px; font-size:13px; font-weight:600; display:inline-block; margin-right:6px; }
    .pill-neutral  { background:#e2e8f0; color:#2d3748; }
    .stat-card { border:0; border-radius:14px; color:#fff; overflow:hidden; box-shadow:0 4px 14px rgba(15,23,42,.08); }
    .stat-card .stat-label { font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; opacity:.9; }
    .stat-card .stat-value { font-size:28px; font-weight:700; line-height:1.2; }
    .stat-card .stat-icon  { font-size:42px; opacity:.28; position:absolute; right:14px; top:14px; }
    .stat-card .sub-line   { font-size:12px; font-weight:600; opacity:.95; display:block; margin-top:2px; }
    .stat-allotted { background:linear-gradient(135deg,#2563eb,#1e40af); }
    .stat-sent     { background:linear-gradient(135deg,#0ea5e9,#0369a1); }
    .stat-pending  { background:linear-gradient(135deg,#f59e0b,#b45309); }
    .stat-pending.due-zero { background:linear-gradient(135deg,#10b981,#047857); }
    .stat-pending.due-negative { background:linear-gradient(135deg,#ef4444,#991b1b); }
    .stat-stock    { background:linear-gradient(135deg,#10b981,#047857); }
    .stat-prod     { background:linear-gradient(135deg,#7c3aed,#5b21b6); }
    .stat-pipeline { background:linear-gradient(135deg,#64748b,#334155); }
    .status-badge { padding:3px 10px; border-radius:10px; font-size:12px; font-weight:600; text-transform:capitalize; display:inline-block; }
    .status-pending   { background:#fef3c7; color:#92400e; }
    .status-approved  { background:#d1fae5; color:#065f46; }
    .status-rejected  { background:#fee2e2; color:#991b1b; }
    .status-default   { background:#e2e8f0; color:#2d3748; }
    .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
    .card-header { border-bottom:1px solid #eef1f6; border-radius:14px 14px 0 0 !important; }
    .table thead th { background:#f8fafc; font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#475569; white-space:nowrap; }
    .table td { vertical-align:middle; white-space:nowrap; }
    .group-head { font-size:10px; font-weight:700; letter-spacing:.6px; color:#334155; }
    .page-title { font-weight:700; color:#0f172a; }
    .btn-accept { padding:3px 14px; font-size:12px; font-weight:600; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <i class="fa fa-leaf"></i> Smart Fertilizer Monitoring System (SFMS), BCIC
    </a>
  </div>
</nav>

<div class="container-fluid p-3">

    <div class="row align-items-center mb-3">
        <div class="col-md-7">
            <h3 class="page-title mb-0">Welcome <b class="text-danger"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">Buffer Dashboard</small>
        </div>

        <div class="col-md-5 text-md-end mt-2 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deliveryModal">
                <i class="fa fa-truck"></i> Delivery to Dealer
            </button>
            <a href="delivery_buffer_factory.php" class="btn btn-outline-secondary"><i class="fa fa-truck"></i> Delivery to Buffer/Factory</a>

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#openingModal">
                <i class="fa fa-wallet"></i> Add Opening Balance
            </button>
            <a href="dashboard.php" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Previous Page</a>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= h($flash['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-3">
        <!-- <div class="col-md-4">
            <div class="stat-card stat-allotted p-3 position-relative">
                <div class="stat-label">Total Allotted</div>
                <div class="stat-value"><?= h(number_format($totals['allotted'], 2)); ?></div>
                <i class="fa fa-cubes stat-icon"></i>
            </div>
        </div> -->
        <div class="col-md-4">
            <div class="stat-card stat-pipeline p-3 position-relative">
                <div class="stat-label">Current Pipeline Amount</div>
                <div class="stat-value"><?= h(number_format($dashStats['pipeline_amount'], 2)); ?></div>
                <i class="fa fa-tasks stat-icon"></i>
            </div>
        </div>
       <!--  <div class="col-md-4">
            <div class="stat-card stat-sent p-3 position-relative">
                <div class="stat-label">Total Sent (Pending)</div>
                <div class="stat-value"><?= h(number_format($totals['sent'], 2)); ?></div>
                <i class="fa fa-paper-plane stat-icon"></i>
            </div>
        </div> -->
        <!-- <div class="col-md-4">
            <?php
                $pendingCardClass = 'stat-pending';
                if ((float)$totals['pending'] == 0.0)  $pendingCardClass .= ' due-zero';
                elseif ((float)$totals['pending'] < 0) $pendingCardClass .= ' due-negative';
            ?>
            <div class="stat-card <?= $pendingCardClass; ?> p-3 position-relative">
                <div class="stat-label">Pending</div>
                <div class="stat-value"><?= h(number_format($totals['pending'], 2)); ?></div>
                <i class="fa fa-hourglass-half stat-icon"></i>
            </div>
        </div> -->

       <!--  <div class="col-md-4">
            <div class="stat-card stat-prod p-3 position-relative">
                <div class="stat-label">Total Production Today</div>
                <div class="stat-value"><?= h(number_format($dashStats['production_today'], 2)); ?></div>
                <i class="fa fa-industry stat-icon"></i>
            </div>
        </div> -->

        <div class="col-md-4">
            <div class="stat-card stat-stock p-3 position-relative">
                <div class="stat-label">Current Stock</div>
                <div class="stat-value"><?= h(number_format($dashStats['current_stock'], 2)); ?></div>
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div>

        

        <div class="col-md-4">
            <div class="stat-card stat-sent p-3 position-relative">
                <div class="stat-label">Daily Receive</div>
                <div class="stat-value"><?= h(number_format($dashStats['daily_receive'], 2)); ?></div>
                <i class="fa fa-download stat-icon"></i>
            </div>
        </div>

        <div class="col-md-8">
            <div class="stat-card stat-sent p-3 position-relative">
                <div class="stat-label">Total Daily Delivery</div>
                <div class="stat-value"><?= h(number_format($dashStats['daily_delivery'], 2)); ?></div>
                <span class="sub-line">To Buffer/Factory: <?= h(number_format($outUaToday, 2)); ?></span>
                <span class="sub-line">To Dealer: <?= h(number_format($outDealerToday, 2)); ?></span>
                <i class="fa fa-upload stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- FILTER -->
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label mb-1 small text-uppercase text-muted">Filter by Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Statuses --</option>
                        <?php foreach ($statusList as $st): ?>
                            <option value="<?= h($st); ?>" <?= ($st === $filterStatus) ? 'selected' : ''; ?>>
                                <?= h(ucfirst($st)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8">
                    <?php if ($filterStatus !== ''): ?>
                        <a href="buffer_dashboard.php" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i> Clear Filter
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- MERGED TABLE -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-table text-primary"></i> Merged Buffer View</h5>
            <span class="summary-pill pill-neutral">Records: <?= count($rows); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th colspan="13" class="group-head text-center bg-light">
                            <i class="fa fa-exchange"></i> buffer_transaction + import_allotment / urea_allotment
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">bt.id</th>
                        <th class="text-center">import_allotment_id</th>
                        <th class="text-center">prod_allotment_id</th>
                        <th class="text-center">buffer_allotment_id</th>
                        <th>date</th>
                        <th class="text-center">amount (Receive)</th>
                        <th>medium</th>
                        <th>created_by</th>
                        <th>ref_no</th>
                        <th class="text-center">Amount (Allotted)</th>
                        <th class="text-center">Transaction Source</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="13" class="text-center text-muted py-4">No records found for this buffer.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r):
                        $btStatus  = (string)($r['bt_status'] ?? '');
                        $isPending = strtolower(trim($btStatus)) === 'pending';
                        $source    = deriveSource($r);
                        $refNo     = $r['ia_ref_no'] ?? $r['ua_ref_no'] ?? '';
                        $allotted  = $r['ia_amount'] ?? $r['ua_total_allot_amount'] ?? 0;
                    ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$r['bt_id']; ?></td>
                            <td class="text-center"><?= dash($r['bt_import_allotment_id']); ?></td>
                            <td class="text-center"><?= dash($r['bt_prod_allotment_id']); ?></td>
                            <td class="text-center"><?= dash($r['bt_buffer_allotment_id']); ?></td>
                            <td><?= h($r['bt_date'] ?? ''); ?></td>
                            <td class="text-center fw-semibold"><?= h(number_format((float)($r['bt_amount'] ?? 0), 2)); ?></td>
                            <td><?= h($r['bt_medium'] ?? ''); ?></td>
                            <td><?= h($r['bt_created_by'] ?? ''); ?></td>
                            <td><?= dash($refNo); ?></td>
                            <td class="text-center"><?= h(number_format((float)$allotted, 2)); ?></td>
                            <td class="text-center"><?= sourceBadge($source); ?></td>
                            <td class="text-center">
                                <span class="status-badge <?= statusClass($btStatus); ?>">
                                    <?= h($btStatus); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($isPending): ?>
                                    <form method="POST" action="" class="d-inline"
                                          onsubmit="return confirm('Accept transaction #<?= (int)$r['bt_id']; ?>?');">
                                        <input type="hidden" name="accept_bt_id" value="<?= (int)$r['bt_id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-accept">
                                            <i class="fa fa-check"></i> Accept
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>

<!-- ============ MODAL: OPENING BALANCE + CAPACITY ============ -->
<div class="modal fade" id="openingModal" tabindex="-1" aria-labelledby="openingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="" id="openingForm">
        <input type="hidden" name="action" value="add_opening">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="openingModalLabel"><i class="fa fa-wallet"></i> Set Opening Balance &amp; Capacity</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Buffer Name</label>
            <input type="text" class="form-control" value="<?= h($logged_in_user); ?>" disabled>
            <div class="form-text">Auto-filled from your session.</div>
          </div>
          <div class="mb-3">
            <label for="opening_bal" class="form-label">Opening Balance <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" class="form-control" id="opening_bal" name="opening_bal"
                   value="<?= h($openingFormValue); ?>" required>
          </div>
          <div class="mb-3">
            <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" class="form-control" id="capacity" name="capacity"
                   value="<?= h($capacityFormValue); ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ============ MODAL: DELIVERY TO DEALER ============ -->
<div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="" id="deliveryForm">
        <input type="hidden" name="action" value="add_delivery">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="deliveryModalLabel"><i class="fa fa-truck"></i> Delivery to Dealer</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="dealer_id" class="form-label">Dealer <span class="text-danger">*</span></label>
            <select class="form-select" id="dealer_id" name="dealer_id" required>
              <option value="">-- Select Dealer --</option>
              <?php foreach ($dealers as $d): ?>
                <option value="<?= (int)$d['id']; ?>"
                    <?= ((string)$oldDelivery['dealer_id'] === (string)$d['id']) ? 'selected' : ''; ?>>
                  <?= h($d['name']); ?>
                  <?= !empty($d['office_name']) ? ' — ' . h($d['office_name']) : ''; ?>
                  <?= !empty($d['office_type']) ? ' (' . h($d['office_type']) . ')' : ''; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount"
                   value="<?= h($oldDelivery['amount']); ?>" required>
          </div>

          <div class="mb-3">
            <label for="delivery_date" class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                   value="<?= h($oldDelivery['date'] ?: date('Y-m-d')); ?>" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Delivery</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($reopenDeliveryModal): ?>
    (function () {
        var modalEl = document.getElementById('deliveryModal');
        var m = new bootstrap.Modal(modalEl);
        m.show();
    })();
    <?php endif; ?>

    <?php if ($reopenOpeningModal): ?>
    (function () {
        var modalEl = document.getElementById('openingModal');
        var m = new bootstrap.Modal(modalEl);
        m.show();
    })();
    <?php endif; ?>
});
</script>

</body>
</html>