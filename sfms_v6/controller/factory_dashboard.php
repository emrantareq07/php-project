<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once('../db/db.php');

$logged_in_user = $_SESSION['username']; // = factory_name

function h($val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}
function dash($val) {
    $v = trim((string)($val ?? ''));
    return $v !== '' ? h($v) : '<span class="text-muted">—</span>';
}
function deriveSource(array $r): string {
    if (!empty($r['bt_import_allotment_id']) && (int)$r['bt_import_allotment_id'] > 0) return 'port_in';
    if (!empty($r['bt_prod_allotment_id'])   && (int)$r['bt_prod_allotment_id']   > 0) return 'factory_in';
    if (!empty($r['bt_buffer_allotment_id']) && (int)$r['bt_buffer_allotment_id'] > 0) return 'buffer_in';
    return 'unknown';
}
function statusClass(string $status): string {
    $s = strtolower(trim($status));
    if (str_contains($s, 'pend'))   return 'status-pending';
    if (str_contains($s, 'appro'))  return 'status-approved';
    if (str_contains($s, 'reject')) return 'status-rejected';
    if (str_contains($s, 'comp'))   return 'status-approved';
    return 'status-default';
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
$flash             = $_SESSION['flash']             ?? null;
$oldDaily          = $_SESSION['old_daily']         ?? ['date' => date('Y-m-d'), 'daily_amount' => '', 'plant_load' => '', 'remarks' => ''];
$oldOpening        = $_SESSION['old_opening']       ?? ['opening_bal' => ''];
$oldDelivery       = $_SESSION['old_delivery']      ?? ['dealer_id' => '', 'amount' => '', 'date' => date('Y-m-d')];
$reopenDailyModal  = $_SESSION['reopen_daily_modal']    ?? false;
$reopenOpeningModal= $_SESSION['reopen_opening_modal']  ?? false;
$reopenDeliveryModal = $_SESSION['reopen_delivery_modal'] ?? false;

$editDailyId   = $_SESSION['edit_daily_id']   ?? 0;

unset(
    $_SESSION['flash'],
    $_SESSION['old_daily'],
    $_SESSION['old_opening'],
    $_SESSION['old_delivery'],
    $_SESSION['reopen_daily_modal'],
    $_SESSION['reopen_opening_modal'],
    $_SESSION['reopen_delivery_modal'],
    $_SESSION['edit_daily_id'],
    $_SESSION['edit_opening_id']
);

// -----------------------------------------------------------------
// HANDLE: ADD / EDIT DAILY PRODUCTION
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(($_POST['action'] ?? ''), ['add_daily', 'edit_daily'], true)) {
    $isEdit       = ($_POST['action'] === 'edit_daily');
    $editId       = $isEdit ? (int)($_POST['edit_id'] ?? 0) : 0;
    $date         = trim($_POST['daily_date']    ?? '');
    $daily_amount = trim($_POST['daily_amount']  ?? '');
    $plant_load   = trim($_POST['plant_load']    ?? '');
    $remarks      = trim($_POST['daily_remarks'] ?? '');

    $errors = [];
    if ($isEdit && $editId <= 0) $errors[] = 'Invalid record id for edit.';
    if ($date === '' || !DateTime::createFromFormat('Y-m-d', $date)) $errors[] = 'A valid date is required (YYYY-MM-DD).';
    if ($daily_amount === '' || !is_numeric($daily_amount) || (float)$daily_amount < 0) $errors[] = 'Daily amount must be a non-negative number.';
    if ($plant_load !== '' && (!is_numeric($plant_load) || (float)$plant_load < 0)) $errors[] = 'Plant load must be a non-negative number.';
    if (mb_strlen($remarks) > 500) $errors[] = 'Remarks must be 500 characters or fewer.';

    if ($errors) {
        $_SESSION['flash']              = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_daily']          = ['date' => $date, 'daily_amount' => $daily_amount, 'plant_load' => $plant_load, 'remarks' => $remarks];
        $_SESSION['reopen_daily_modal'] = true;
        $_SESSION['edit_daily_id']      = $editId;
    } else {
        mysqli_begin_transaction($conn);
        try {
            $amt = (float)$daily_amount;
            $pl  = ($plant_load === '') ? null : (float)$plant_load;

            if ($isEdit) {
                $sql = "UPDATE production_tbl
                           SET date = ?, daily_amount = ?, plant_load = ?, remarks = ?, updated_at = NOW()
                         WHERE id = ? AND factory_name = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
                mysqli_stmt_bind_param($stmt, 'sddsis', $date, $amt, $pl, $remarks, $editId, $logged_in_user);
                if (!mysqli_stmt_execute($stmt)) throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
                if (mysqli_stmt_affected_rows($stmt) === 0) throw new Exception('Record not found or not yours (nothing changed).');
                mysqli_stmt_close($stmt);
                mysqli_commit($conn);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Daily production #{$editId} updated successfully."];
            } else {
                $sql = "INSERT INTO production_tbl
                            (factory_name, date, daily_amount, plant_load, remarks, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
                $stmt = mysqli_prepare($conn, $sql);
                if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
                mysqli_stmt_bind_param($stmt, 'ssdds', $logged_in_user, $date, $amt, $pl, $remarks);
                if (!mysqli_stmt_execute($stmt)) throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
                $newId = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);
                mysqli_commit($conn);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Daily production #{$newId} added successfully."];
            }
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash']              = ['type' => 'danger', 'msg' => ($isEdit ? 'Update failed: ' : 'Add failed: ') . $e->getMessage()];
            $_SESSION['old_daily']          = ['date' => $date, 'daily_amount' => $daily_amount, 'plant_load' => $plant_load, 'remarks' => $remarks];
            $_SESSION['reopen_daily_modal'] = true;
            $_SESSION['edit_daily_id']      = $editId;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// =================================================================
// HANDLE: ADD / EDIT OPENING BALANCE
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(($_POST['action'] ?? ''), ['add_opening', 'edit_opening'], true)) {
    $opening_bal = trim($_POST['opening_bal'] ?? '');

    $errors = [];
    if ($opening_bal === '' || !is_numeric($opening_bal) || (float)$opening_bal < 0) {
        $errors[] = 'Opening balance must be a non-negative number.';
    }

    if ($errors) {
        $_SESSION['flash']                = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_opening']          = ['opening_bal' => $opening_bal];
        $_SESSION['reopen_opening_modal'] = true;
    } else {
        mysqli_begin_transaction($conn);
        try {
            $ob = (float)$opening_bal;

            $sql = "UPDATE office_tbl
                       SET opening_bal = ?, updated_at = NOW()
                     WHERE buffer_name = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'ds', $ob, $logged_in_user);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
            }
            if (mysqli_stmt_affected_rows($stmt) === 0) {
                throw new Exception('No office found for buffer_name = ' . $logged_in_user . ' (or value unchanged).');
            }
            mysqli_stmt_close($stmt);

            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Opening balance updated to " . number_format($ob, 2) . "."];
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash']                = ['type' => 'danger', 'msg' => 'Update failed: ' . $e->getMessage()];
            $_SESSION['old_opening']          = ['opening_bal' => $opening_bal];
            $_SESSION['reopen_opening_modal'] = true;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
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
        header('Location: ' . $_SERVER['PHP_SELF']);
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
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// HANDLE: DELETE (production rows only)
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];
    if ($delId > 0) {
        mysqli_begin_transaction($conn);
        try {
            $sql  = "DELETE FROM production_tbl WHERE id = ? AND factory_name = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, 'is', $delId, $logged_in_user);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Delete failed: ' . mysqli_stmt_error($stmt));
            if (mysqli_stmt_affected_rows($stmt) !== 1) throw new Exception('Record not found or not yours.');
            mysqli_stmt_close($stmt);
            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Record #{$delId} deleted."];
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Delete failed: ' . $e->getMessage()];
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// =================================================================
//  DASHBOARD CALCULATIONS
// =================================================================
$dashStats = [
    'current_stock'   => 0.0,
    'daily_receive'   => 0.0,
    'daily_delivery'  => 0.0,
    'pipeline_amount' => 0.0,
];

// -----------------------------------------------------------------
//  OPENING BALANCE (office_tbl)
// -----------------------------------------------------------------
$openingFromOffice = 0.0;
if ($s = mysqli_prepare($conn, "SELECT COALESCE(opening_bal,0) AS v FROM office_tbl WHERE buffer_name = ? LIMIT 1")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $openingFromOffice = (float)$row['v'];
    mysqli_stmt_close($s);
}

// -----------------------------------------------------------------
//  ALL-TIME PRODUCTION (production_tbl)
// -----------------------------------------------------------------
$prodAll = 0.0;
if ($s = mysqli_prepare($conn, "SELECT COALESCE(SUM(daily_amount),0) AS v FROM production_tbl WHERE factory_name = ?")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $prodAll = (float)$row['v'];
    mysqli_stmt_close($s);
}

// -----------------------------------------------------------------
//  IN — port_in + factory_in + buffer_in (all-time)
//  These add to current stock for this user's buffer/factory.
// -----------------------------------------------------------------
$cond1All = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount),0) AS v
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
    LEFT JOIN urea_allotment   ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
      AND COALESCE(ia.buffer_name, ua.receiver) = ?
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $cond1All = (float)$row['v'];
    mysqli_stmt_close($s);
}

// IN — today
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount),0) AS v
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    LEFT JOIN import_allotment ia ON ia.id = bt.import_allotment_id
    LEFT JOIN urea_allotment   ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('port_in','factory_in','buffer_in')
      AND COALESCE(ia.buffer_name, ua.receiver) = ?
      AND DATE(mt.created_at) = CURDATE()
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $dashStats['daily_receive'] = (float)$row['v'];
    mysqli_stmt_close($s);
}

// -----------------------------------------------------------------
//  OUT — To Buffer/Factory (urea_allotment.sender = user)
// -----------------------------------------------------------------
$outUaToday = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount),0) AS v
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    INNER JOIN urea_allotment   ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('factory_out','buffer_out')
      AND ua.sender = ?
      AND DATE(mt.created_at) = CURDATE()
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $outUaToday = (float)$row['v'];
    mysqli_stmt_close($s);
}

$outUaAll = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount),0) AS v
    FROM master_transaction mt
    INNER JOIN buffer_transaction bt ON bt.id = mt.buffer_transaction_id
    INNER JOIN urea_allotment   ua ON ua.id = COALESCE(bt.prod_allotment_id, bt.buffer_allotment_id)
    WHERE mt.transaction_source IN ('factory_out','buffer_out')
      AND ua.sender = ?
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $outUaAll = (float)$row['v'];
    mysqli_stmt_close($s);
}

// -----------------------------------------------------------------
//  OUT — To Dealer (office_tbl.buffer_name = user)
// -----------------------------------------------------------------
$outDealerToday = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount),0) AS v
    FROM master_transaction mt
    INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
    INNER JOIN office_tbl o ON o.id = d.office_tbl_id
    WHERE mt.transaction_source IN ('factory_out','buffer_out')
      AND o.buffer_name = ?
      AND DATE(mt.created_at) = CURDATE()
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $outDealerToday = (float)$row['v'];
    mysqli_stmt_close($s);
}

$outDealerAll = 0.0;
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(mt.amount),0) AS v
    FROM master_transaction mt
    INNER JOIN dealer_tbl d ON d.id = mt.dealer_id
    INNER JOIN office_tbl o ON o.id = d.office_tbl_id
    WHERE mt.transaction_source IN ('factory_out','buffer_out')
      AND o.buffer_name = ?
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $outDealerAll = (float)$row['v'];
    mysqli_stmt_close($s);
}

// -----------------------------------------------------------------
//  DELIVERY TOTALS
// -----------------------------------------------------------------
$totalDeliveryToday = $outUaToday + $outDealerToday;
$totalDeliveryAll   = $outUaAll   + $outDealerAll;

$dashStats['daily_delivery'] = $totalDeliveryToday;

// -----------------------------------------------------------------
//  CURRENT STOCK
//  = opening_bal
//  + SUM(production)
//  + IN  (port_in/factory_in/buffer_in)
//  - OUT (factory_out/buffer_out)
// -----------------------------------------------------------------
$dashStats['current_stock'] = $openingFromOffice
                            + $prodAll
                            + $cond1All
                            - $totalDeliveryAll;

// -----------------------------------------------------------------
//  PIPELINE AMOUNT (pending buffer_transaction for this buffer)
// -----------------------------------------------------------------
if ($s = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(b.amount),0) AS v
    FROM buffer_transaction b
    LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
    LEFT JOIN urea_allotment   ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
    WHERE b.status = 'pending'
      AND COALESCE(ia.buffer_name, ua.receiver) = ?
")) {
    mysqli_stmt_bind_param($s, 's', $logged_in_user);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    if ($row = mysqli_fetch_assoc($r)) $dashStats['pipeline_amount'] = (float)$row['v'];
    mysqli_stmt_close($s);
}

// =================================================================
//  FETCH DEALERS (for the Delivery modal)
// =================================================================
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

// =================================================================
//  FETCH BUFFER ROWS for the MERGED BUFFER VIEW table
// =================================================================
$bufferRows = [];
$bufferTotals = ['sent' => 0.0, 'allotted' => 0.0];

$bufferSql = "
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
        ua.medium                 AS ua_medium
    FROM buffer_transaction b
    LEFT JOIN import_allotment ia ON ia.id = b.import_allotment_id
    LEFT JOIN urea_allotment   ua ON (ua.id = b.prod_allotment_id OR ua.id = b.buffer_allotment_id)
    WHERE (
            ia.buffer_name = ?
         OR ua.receiver   = ?
    )
    ORDER BY b.id DESC
";
if ($stmt = mysqli_prepare($conn, $bufferSql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $logged_in_user, $logged_in_user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($res)) {
        $bufferRows[] = $r;
        $bufferTotals['sent']     += (float)($r['bt_amount'] ?? 0);
        $bufferTotals['allotted'] += (float)($r['ia_amount'] ?? $r['ua_amount'] ?? 0);
    }
    mysqli_stmt_close($stmt);
}

// =================================================================
//  FETCH PRODUCTION ROWS + TOTALS
// =================================================================
$rows = [];
$sql  = "SELECT id, factory_name, date, daily_amount, opening_bal, plant_load, remarks, created_at, updated_at
         FROM production_tbl
         WHERE factory_name = ? AND daily_amount IS NOT NULL
         ORDER BY id DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $logged_in_user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    mysqli_stmt_close($stmt);
}

$totalDaily = 0.0;
$totalLoad  = 0.0;
foreach ($rows as $r) {
    $totalDaily += (float)($r['daily_amount'] ?? 0);
    $totalLoad  += (float)($r['plant_load']  ?? 0);
}

$totalOpening = $openingFromOffice;
$openingFormValue = $oldOpening['opening_bal'] !== '' ? $oldOpening['opening_bal'] : $openingFromOffice;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - Production</title>
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
    .stat-card .stat-value { font-size:26px; font-weight:700; line-height:1.2; }
    .stat-card .stat-icon  { font-size:42px; opacity:.28; position:absolute; right:14px; top:14px; }
    .stat-opening  { background:linear-gradient(135deg,#7c3aed,#5b21b6); }
    .stat-daily    { background:linear-gradient(135deg,#2563eb,#1e40af); }
    .stat-load     { background:linear-gradient(135deg,#f59e0b,#b45309); }
    .stat-stock    { background:linear-gradient(135deg,#059669,#065f46); }
    .stat-pipeline { background:linear-gradient(135deg,#64748b,#334155); }
    .stat-receive  { background:linear-gradient(135deg,#0ea5e9,#0369a1); }
    .stat-delivery { background:linear-gradient(135deg,#f97316,#c2410c); }
    .stat-delivery .sub-line { font-size:12px; font-weight:600; opacity:.95; display:block; margin-top:2px; }
    .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
    .card-header { border-bottom:1px solid #eef1f6; border-radius:14px 14px 0 0 !important; }
    .table thead th { background:#f8fafc; font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#475569; white-space:nowrap; }
    .table td { vertical-align:middle; white-space:nowrap; }
    .group-head { font-size:10px; font-weight:700; letter-spacing:.6px; color:#334155; }
    .page-title { font-weight:700; color:#0f172a; }
    .btn-sm-icon { padding:3px 10px; font-size:12px; }
    .status-badge { padding:3px 10px; border-radius:10px; font-size:12px; font-weight:600; text-transform:capitalize; display:inline-block; }
    .status-pending   { background:#fef3c7; color:#92400e; }
    .status-approved  { background:#d1fae5; color:#065f46; }
    .status-rejected  { background:#fee2e2; color:#991b1b; }
    .status-default   { background:#e2e8f0; color:#2d3748; }
    .btn-accept { padding:3px 14px; font-size:12px; font-weight:600; }
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
        <div class="col-md-3">
            <h3 class="page-title mb-0">Welcome <b class="text-danger"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">Factory Production Dashboard</small>
        </div>
        <div class="col-md-9 text-md-end mt-2 mt-md-0">

            <a href="delivery_buffer_factory.php" class="btn btn-outline-secondary"><i class="fa fa-truck"></i> Delivery to Buffer/Factory</a>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deliveryModal">
                <i class="fa fa-truck"></i> Delivery to Dealer
            </button>

            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#openingModal">
                <i class="fa fa-plus"></i> Add Opening Balance
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dailyModal">
                <i class="fa fa-plus"></i> Add Daily Production
            </button>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= h($flash['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-3">

        <div class="col-md-3">
            <div class="stat-card stat-opening p-3 position-relative">
                <div class="stat-label">Opening Balance</div>
                <div class="stat-value"><?= h(number_format($totalOpening, 2)); ?></div>
                <i class="fa fa-wallet stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-daily p-3 position-relative">
                <div class="stat-label">Total Daily Production</div>
                <div class="stat-value"><?= h(number_format($totalDaily, 2)); ?></div>
                <i class="fa fa-cubes stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-load p-3 position-relative">
                <div class="stat-label">Total Plant Load</div>
                <div class="stat-value"><?= h(number_format($totalLoad, 2)); ?></div>
                <i class="fa fa-truck stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-stock p-3 position-relative">
                <div class="stat-label">Current Stock</div>
                <div class="stat-value"><?= h(number_format($dashStats['current_stock'], 2)); ?></div>
                <i class="fa fa-warehouse stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-pipeline p-3 position-relative">
                <div class="stat-label">Current Pipeline Amount</div>
                <div class="stat-value"><?= h(number_format($dashStats['pipeline_amount'], 2)); ?></div>
                <i class="fa fa-tasks stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card stat-receive p-3 position-relative">
                <div class="stat-label">Daily Receive</div>
                <div class="stat-value"><?= h(number_format($dashStats['daily_receive'], 2)); ?></div>
                <i class="fa fa-download stat-icon"></i>
            </div>
        </div>

        <!-- Total Daily Delivery -->
        <div class="col-md-3">
            <div class="stat-card stat-delivery p-3 position-relative">
                <div class="stat-label">Total Daily Delivery</div>
                <div class="stat-value"><?= h(number_format($dashStats['daily_delivery'], 2)); ?></div>
                <span class="sub-line">To Buffer/Factory: <?= h(number_format($outUaToday, 2)); ?></span>
                <span class="sub-line">To Dealer: <?= h(number_format($outDealerToday, 2)); ?></span>
                <i class="fa fa-upload stat-icon"></i>
            </div>
        </div>

    </div>

    <!-- MERGED TABLE -->
    <div class="card mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-exchange text-primary"></i> Merged Buffer View</h5>
            <span class="summary-pill pill-neutral">Records: <?= count($bufferRows); ?></span>
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
                        <th class="text-center">amount (sent)</th>
                        <th>medium</th>
                        <th>created_by</th>
                        <th>ref_no</th>
                        <th class="text-center">amount (allotted)</th>
                        <th class="text-center">Transaction Source</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($bufferRows)): ?>
                    <tr><td colspan="13" class="text-center text-muted py-4">No buffer transactions found for this buffer.</td></tr>
                <?php else: ?>
                    <?php foreach ($bufferRows as $r):
                        $btStatus  = (string)($r['bt_status'] ?? '');
                        $isPending = strtolower(trim($btStatus)) === 'pending';
                        $source    = deriveSource($r);
                        $refNo     = $r['ia_ref_no'] ?? $r['ua_ref_no'] ?? '';
                        $allotted  = $r['ia_amount'] ?? $r['ua_amount'] ?? 0;
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

    <!-- PRODUCTION RECORDS TABLE -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-table text-primary"></i> Production Records — <?= h($logged_in_user); ?></h5>
            <span class="summary-pill pill-neutral">Records: <?= count($rows); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Factory Name</th>
                        <th>Date</th>
                        <th class="text-center">Daily Amount</th>
                        <th class="text-center">Plant Load</th>
                        <th>Remarks</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">No production records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$r['id']; ?></td>
                            <td class="fw-semibold"><?= h($r['factory_name']); ?></td>
                            <td><?= dash($r['date']); ?></td>
                            <td class="text-center"><?= $r['daily_amount'] !== null ? h(number_format((float)$r['daily_amount'], 2)) : '<span class="text-muted">—</span>'; ?></td>
                            <td class="text-center"><?= $r['plant_load']   !== null ? h(number_format((float)$r['plant_load'],   2)) : '<span class="text-muted">—</span>'; ?></td>
                            <td><?= dash($r['remarks']); ?></td>
                            <td class="text-muted small"><?= h($r['created_at'] ?? ''); ?></td>
                            <td class="text-muted small"><?= h($r['updated_at'] ?? ''); ?></td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm-icon btn-edit-daily"
                                        data-id="<?= (int)$r['id']; ?>"
                                        data-date="<?= h((string)($r['date'] ?? '')); ?>"
                                        data-amount="<?= h((string)($r['daily_amount'] ?? '')); ?>"
                                        data-load="<?= h((string)($r['plant_load'] ?? '')); ?>"
                                        data-remarks="<?= h((string)($r['remarks'] ?? '')); ?>">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form method="POST" action="" class="d-inline"
                                      onsubmit="return confirm('Delete record #<?= (int)$r['id']; ?>?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="delete_id" value="<?= (int)$r['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm-icon">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Totals:</td>
                        <td class="text-center"><?= h(number_format($totalDaily, 2)); ?></td>
                        <td class="text-center"><?= h(number_format($totalLoad, 2)); ?></td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>

</div>

<!-- ============ MODAL: DAILY PRODUCTION (ADD / EDIT) ============ -->
<div class="modal fade" id="dailyModal" tabindex="-1" aria-labelledby="dailyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="" id="dailyForm">
        <input type="hidden" name="action"  id="daily_action"  value="add_daily">
        <input type="hidden" name="edit_id" id="daily_edit_id" value="">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="dailyModalLabel"><i class="fa fa-plus-circle"></i> Add Daily Production</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Factory Name</label>
            <input type="text" class="form-control" value="<?= h($logged_in_user); ?>" disabled>
            <div class="form-text">Auto-filled from your session.</div>
          </div>
          <div class="mb-3">
            <label for="daily_date" class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="daily_date" name="daily_date"
                   value="<?= h($oldDaily['date']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="daily_amount" class="form-label">Daily Amount <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" class="form-control" id="daily_amount" name="daily_amount"
                   value="<?= h($oldDaily['daily_amount']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="plant_load" class="form-label">Plant Load</label>
            <input type="number" step="0.01" min="0" class="form-control" id="plant_load" name="plant_load"
                   value="<?= h($oldDaily['plant_load']); ?>">
          </div>
          <div class="mb-3">
            <label for="daily_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="daily_remarks" name="daily_remarks" rows="3"
                      maxlength="500"><?= h($oldDaily['remarks']); ?></textarea>
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

<!-- ============ MODAL: OPENING BALANCE ============ -->
<div class="modal fade" id="openingModal" tabindex="-1" aria-labelledby="openingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="" id="openingForm">
        <input type="hidden" name="action" value="add_opening">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="openingModalLabel"><i class="fa fa-wallet"></i> Set Opening Balance</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Factory Name</label>
            <input type="text" class="form-control" value="<?= h($logged_in_user); ?>" disabled>
            <div class="form-text">Auto-filled from your session.</div>
          </div>
          <div class="mb-3">
            <label for="opening_bal" class="form-label">Opening Balance <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" class="form-control" id="opening_bal" name="opening_bal"
                   value="<?= h($openingFormValue); ?>" required>
            <div class="form-text">This updates your office's opening balance.</div>
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

    var dailyModalEl    = document.getElementById('dailyModal');
    var openingModalEl  = document.getElementById('openingModal');
    var deliveryModalEl = document.getElementById('deliveryModal');
    var dailyModal      = new bootstrap.Modal(dailyModalEl);
    var openingModal    = new bootstrap.Modal(openingModalEl);
    var deliveryModal   = new bootstrap.Modal(deliveryModalEl);

    var editingDaily   = false;

    dailyModalEl.addEventListener('show.bs.modal', function () {
        if (editingDaily) return;
        document.getElementById('dailyForm').reset();
        document.getElementById('daily_action').value  = 'add_daily';
        document.getElementById('daily_edit_id').value = '';
        document.getElementById('dailyModalLabel').innerHTML =
            '<i class="fa fa-plus-circle"></i> Add Daily Production';
        document.getElementById('daily_date').value = "<?= h(date('Y-m-d')); ?>";
    });

    dailyModalEl.addEventListener('hidden.bs.modal', function () { editingDaily = false; });

    document.querySelectorAll('.btn-edit-daily').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            editingDaily = true;
            document.getElementById('daily_action').value  = 'edit_daily';
            document.getElementById('daily_edit_id').value = this.dataset.id || '';
            document.getElementById('daily_date').value    = this.dataset.date || '';
            document.getElementById('daily_amount').value  = this.dataset.amount || '';
            document.getElementById('plant_load').value    = this.dataset.load || '';
            document.getElementById('daily_remarks').value = this.dataset.remarks || '';
            document.getElementById('dailyModalLabel').innerHTML =
                '<i class="fa fa-edit"></i> Edit Daily Production';
            dailyModal.show();
        });
    });

    <?php if ($reopenDailyModal): ?>
    (function () {
        editingDaily = true;
        document.getElementById('daily_action').value  = <?= $editDailyId > 0 ? "'edit_daily'" : "'add_daily'"; ?>;
        document.getElementById('daily_edit_id').value = "<?= (int)$editDailyId; ?>";
        document.getElementById('dailyModalLabel').innerHTML =
            <?= $editDailyId > 0
                ? "'<i class=\"fa fa-edit\"></i> Edit Daily Production'"
                : "'<i class=\"fa fa-plus-circle\"></i> Add Daily Production'"; ?>;
        dailyModal.show();
    })();
    <?php endif; ?>

    <?php if ($reopenOpeningModal): ?>
    (function () {
        openingModal.show();
    })();
    <?php endif; ?>

    <?php if ($reopenDeliveryModal): ?>
    (function () {
        deliveryModal.show();
    })();
    <?php endif; ?>

});
</script>

</body>
</html>