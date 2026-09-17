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
    if (str_contains($s, 'appro') || str_contains($s, 'comp')) return 'status-approved';
    if (str_contains($s, 'reject')) return 'status-rejected';
    return 'status-default';
}

$flash       = $_SESSION['flash']      ?? null;
$oldAllot    = $_SESSION['old_allot']  ?? [
    'ref_no' => '', 'sender' => '', 'receiver' => '', 'amount' => '',
    'medium' => '', 'created_by' => '', 'total_allot_amount' => ''
];
$editAllotId = $_SESSION['edit_allot_id'] ?? 0;

unset(
    $_SESSION['flash'],
    $_SESSION['old_allot'],
    $_SESSION['edit_allot_id']
);

if ($editAllotId <= 0 && isset($_GET['edit_allotment'])) {
    $editAllotId = (int)$_GET['edit_allotment'];
}

// -----------------------------------------------------------------
// Helper: is this allotment already referenced by buffer_transaction?
// -----------------------------------------------------------------
function allotmentIsSent(mysqli $conn, int $allotId): bool {
    $sql  = "SELECT 1 FROM buffer_transaction
             WHERE (prod_allotment_id = ? OR buffer_allotment_id = ?)
               AND status IN ('pending','complete','approved')
             LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, 'ii', $allotId, $allotId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $sent = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $sent;
}

// -----------------------------------------------------------------
// Helper: fetch ref_no summary for a given sender
//   returns [ref_no => ['total'=>x, 'used'=>y, 'remaining'=>z]]
//   $excludeId = row id to exclude from "used" (for edit)
// -----------------------------------------------------------------
function fetchRefNoSummary(mysqli $conn, string $sender, int $excludeId = 0): array {
    $sql = "SELECT ref_no,
                   MAX(total_allot_amount) AS total_allot_amount,
                   COALESCE(SUM(amount),0) AS used_amount
            FROM urea_allotment
            WHERE sender = ? AND ref_no IS NOT NULL AND ref_no <> '' AND id <> ?
            GROUP BY ref_no
            ORDER BY ref_no ASC";
    $out = [];
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $sender, $excludeId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($res)) {
            $total = (float)($r['total_allot_amount'] ?? 0);
            $used  = (float)$r['used_amount'];
            $out[$r['ref_no']] = [
                'total'     => $total,
                'used'      => $used,
                'remaining' => $total - $used,
            ];
        }
        mysqli_stmt_close($stmt);
    }
    return $out;
}

// =================================================================
// HANDLE: ADD / EDIT UREA ALLOTMENT
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(($_POST['action'] ?? ''), ['save_allotment', 'edit_allotment'], true)) {
    $isEdit   = ($_POST['action'] === 'edit_allotment');
    $editId   = $isEdit ? (int)($_POST['al_id'] ?? 0) : 0;

    // Ref No can come from a select OR from a "new ref" text input
    $refNoSelect = trim($_POST['ref_no_select'] ?? '');
    $refNoNew    = trim($_POST['ref_no_new']    ?? '');
    if ($refNoSelect === '__NEW__') {
        $ref_no = $refNoNew;
    } else {
        $ref_no = $refNoSelect !== '' ? $refNoSelect : $refNoNew;
    }

    $sender   = trim($_POST['sender']    ?? '');
    $receiver = trim($_POST['receiver']  ?? '');
    $amount   = trim($_POST['amount']    ?? '');
    $medium   = trim($_POST['medium']    ?? '');
    $totalAllotAmount = trim($_POST['total_allot_amount'] ?? '');
    $created_by = $logged_in_user;

    $errors = [];
    if ($isEdit && $editId <= 0)   $errors[] = 'Invalid record id for edit.';
    if ($ref_no === '')            $errors[] = 'Reference number is required.';
    if (mb_strlen($ref_no) > 100)  $errors[] = 'Reference number must be 100 characters or fewer.';
    if ($sender === '')            $errors[] = 'Sender is required.';
    if ($receiver === '')          $errors[] = 'Receiver is required.';
    if ($amount === '' || !is_numeric($amount) || (float)$amount <= 0) $errors[] = 'Amount must be a positive number.';
    if ($medium === '')            $errors[] = 'Medium is required.';
    if (mb_strlen($medium) > 100)  $errors[] = 'Medium must be 100 characters or fewer.';
    if ($totalAllotAmount === '' || !is_numeric($totalAllotAmount) || (float)$totalAllotAmount <= 0) {
        $errors[] = 'Total allotment amount must be a positive number.';
    }

    // Ownership guard for edit
    if ($isEdit && $editId > 0 && !$errors) {
        $own = mysqli_prepare($conn, "SELECT sender FROM urea_allotment WHERE id = ?");
        if ($own) {
            mysqli_stmt_bind_param($own, 'i', $editId);
            mysqli_stmt_execute($own);
            $ownRes = mysqli_stmt_get_result($own);
            $ownRow = mysqli_fetch_assoc($ownRes);
            mysqli_stmt_close($own);
            if (!$ownRow) {
                $errors[] = 'Allotment not found.';
            } elseif ($ownRow['sender'] !== $logged_in_user) {
                $errors[] = 'You can only edit your own allotments.';
            }
        }
    }

    // Validate sender/receiver against office_tbl
    if (!$errors) {
        $allowedBuffers = [];
        $vq = mysqli_query($conn, "SELECT buffer_name FROM office_tbl WHERE buffer_name IS NOT NULL AND buffer_name <> ''");
        if ($vq) {
            while ($vr = mysqli_fetch_assoc($vq)) $allowedBuffers[] = $vr['buffer_name'];
        }
        if (!in_array($sender, $allowedBuffers, true))   $errors[] = 'Invalid sender selected.';
        if (!in_array($receiver, $allowedBuffers, true)) $errors[] = 'Invalid receiver selected.';
    }

    // Enforce: amount must not exceed remaining for this ref_no (exclude own row on edit)
    if (!$errors) {
        $summary  = fetchRefNoSummary($conn, $logged_in_user, $isEdit ? $editId : 0);
        $amt      = (float)$amount;
        $totalAmt = (float)$totalAllotAmount;

        if (isset($summary[$ref_no])) {
            // Existing ref_no → total must match the stored one, and amount must fit remaining
            $storedTotal = (float)$summary[$ref_no]['total'];
            if (abs($storedTotal - $totalAmt) > 0.001) {
                $errors[] = "Total allotment amount for ref_no '{$ref_no}' is fixed at " . number_format($storedTotal, 2) . " MT. You cannot change it.";
            } else {
                $remaining = $summary[$ref_no]['remaining'];
                if ($amt > $remaining + 0.0001) {
                    $errors[] = "Amount exceeds remaining balance for ref_no '{$ref_no}'. Remaining: " . number_format($remaining, 2) . " MT.";
                }
            }
        }
        // If ref_no is new (not in summary), no cap is applied other than amount > 0
    }

    if ($isEdit && $editId > 0 && allotmentIsSent($conn, $editId)) {
        $errors[] = 'This allotment has already been sent and cannot be edited.';
    }

    if ($errors) {
        $_SESSION['flash']     = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_allot'] = [
            'ref_no' => $ref_no, 'sender' => $sender, 'receiver' => $receiver,
            'amount' => $amount, 'medium' => $medium, 'created_by' => $created_by,
            'total_allot_amount' => $totalAllotAmount
        ];
        $_SESSION['edit_allot_id'] = $editId;
        header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $amt      = (float)$amount;
        $totalAmt = (float)$totalAllotAmount;

        if ($isEdit) {
            $sql = "UPDATE urea_allotment
                       SET ref_no = ?, sender = ?, receiver = ?, amount = ?, total_allot_amount = ?,
                           medium = ?, updated_at = NOW()
                     WHERE id = ? AND sender = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'sssddsis',
                $ref_no, $sender, $receiver, $amt, $totalAmt, $medium, $editId, $logged_in_user);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
            if (mysqli_stmt_affected_rows($stmt) === 0) throw new Exception('Record not found, not owned by you, or nothing changed.');
            mysqli_stmt_close($stmt);

            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Allotment #{$editId} updated successfully."];
        } else {
            $sql = "INSERT INTO urea_allotment
                        (ref_no, sender, receiver, amount, total_allot_amount, medium, status, created_by, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, NOW(), NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'sssddss',
                $ref_no, $sender, $receiver, $amt, $totalAmt, $medium, $created_by);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
            $newId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Allotment #{$newId} added successfully."];
        }
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $_SESSION['flash']     = ['type' => 'danger', 'msg' => ($isEdit ? 'Update failed: ' : 'Add failed: ') . $e->getMessage()];
        $_SESSION['old_allot'] = [
            'ref_no' => $ref_no, 'sender' => $sender, 'receiver' => $receiver,
            'amount' => $amount, 'medium' => $medium, 'created_by' => $created_by,
            'total_allot_amount' => $totalAllotAmount
        ];
        $_SESSION['edit_allot_id'] = $editId;
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
    exit();
}

// -----------------------------------------------------------------
// HANDLE: DELETE UREA ALLOTMENT (blocked if already sent, owner only)
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_allotment' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];

    if ($delId > 0) {
        if (allotmentIsSent($conn, $delId)) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Cannot delete: this allotment has already been sent to the buffer.'];
            header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
            exit();
        }

        mysqli_begin_transaction($conn);
        try {
            $stmt = mysqli_prepare($conn, "DELETE FROM urea_allotment WHERE id = ? AND sender = ?");
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, 'is', $delId, $logged_in_user);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Delete failed: ' . mysqli_stmt_error($stmt));
            if (mysqli_stmt_affected_rows($stmt) !== 1) throw new Exception('Allotment not found or not owned by you.');
            mysqli_stmt_close($stmt);
            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Allotment #{$delId} deleted."];
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Delete failed: ' . $e->getMessage()];
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
    exit();
}

// =================================================================
// HANDLE: SEND ALLOTMENT → buffer_transaction
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'send_allotment' && isset($_POST['allotment_id'])) {
    $allotId = (int)$_POST['allotment_id'];

    if ($allotId <= 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Invalid allotment id.'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $selSql = "SELECT id, ref_no, sender, receiver, amount, medium, status
                   FROM urea_allotment
                   WHERE id = ?
                   FOR UPDATE";
        $stmt = mysqli_prepare($conn, $selSql);
        if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $allotId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $ua  = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if (!$ua) throw new Exception('Allotment not found.');

        if ($ua['sender'] !== $logged_in_user) {
            throw new Exception('You can only send your own allotments.');
        }

        if (strtolower(trim((string)$ua['status'])) !== 'pending') {
            throw new Exception('Only pending allotments can be sent. Current status: ' . $ua['status']);
        }

        $rcvSql = "SELECT id, office_name, office_type
                   FROM office_tbl
                   WHERE buffer_name = ?
                   LIMIT 1";
        $rcv = mysqli_prepare($conn, $rcvSql);
        if (!$rcv) throw new Exception('Prepare (receiver lookup) failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($rcv, 's', $ua['receiver']);
        mysqli_stmt_execute($rcv);
        $rcvRes = mysqli_stmt_get_result($rcv);
        $receiverOffice = mysqli_fetch_assoc($rcvRes);
        mysqli_stmt_close($rcv);

        if (!$receiverOffice) {
            throw new Exception('Receiver office not found for buffer_name: ' . $ua['receiver']);
        }

        $officeType = strtolower(trim((string)$receiverOffice['office_type']));

        $prodAllotmentId   = null;
        $bufferAllotmentId = null;

        if ($officeType === 'buffer_godown') {
            $bufferAllotmentId = $allotId;
        } elseif ($officeType === 'factory_office') {
            $prodAllotmentId = $allotId;
        } else {
            throw new Exception('Cannot send: receiver office_type "' . $officeType . '" is not supported.');
        }

        $dupSql = "SELECT id FROM buffer_transaction
                   WHERE (prod_allotment_id = ? OR buffer_allotment_id = ?)
                     AND status IN ('pending','approved','complete')
                   LIMIT 1";
        $dup = mysqli_prepare($conn, $dupSql);
        if ($dup) {
            mysqli_stmt_bind_param($dup, 'ii', $allotId, $allotId);
            mysqli_stmt_execute($dup);
            mysqli_stmt_store_result($dup);
            if (mysqli_stmt_num_rows($dup) > 0) {
                mysqli_stmt_close($dup);
                throw new Exception('This allotment has already been sent to the buffer.');
            }
            mysqli_stmt_close($dup);
        }

        $insSql = "INSERT INTO buffer_transaction
                        (prod_allotment_id, buffer_allotment_id, date, amount, medium, status, created_by, created_at, updated_at)
                   VALUES (?, ?, CURDATE(), ?, ?, 'pending', ?, NOW(), NOW())";
        $stmt = mysqli_prepare($conn, $insSql);
        if (!$stmt) throw new Exception('Prepare (insert) failed: ' . mysqli_error($conn));

        $amt    = (float)$ua['amount'];
        $medium = (string)$ua['medium'];

        mysqli_stmt_bind_param($stmt, 'iidss',
            $prodAllotmentId,
            $bufferAllotmentId,
            $amt,
            $medium,
            $logged_in_user
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
        }
        $newBtId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $updSql = "UPDATE urea_allotment
                      SET status = 'complete', updated_at = NOW()
                    WHERE id = ? AND status = 'pending'";
        $stmt = mysqli_prepare($conn, $updSql);
        if (!$stmt) throw new Exception('Prepare (update) failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $allotId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Status update failed: ' . mysqli_stmt_error($stmt));
        }
        if (mysqli_stmt_affected_rows($stmt) !== 1) {
            throw new Exception('Status update affected unexpected rows.');
        }
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => "Allotment #{$allotId} sent to buffer (via {$officeType}). Pending transaction #{$newBtId} created."];
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Send failed: ' . $e->getMessage()];
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
    exit();
}

// -----------------------------------------------------------------
// FETCH UREA ALLOTMENTS  (only rows where sender = logged-in user)
// -----------------------------------------------------------------
$allotRows = [];
$sqlAllot  = "SELECT id, ref_no, sender, receiver, amount, total_allot_amount, medium, status, created_by, created_at, updated_at
              FROM urea_allotment
              WHERE sender = ?
              ORDER BY id DESC";
$stmtAllot = mysqli_prepare($conn, $sqlAllot);
if ($stmtAllot) {
    mysqli_stmt_bind_param($stmtAllot, 's', $logged_in_user);
    mysqli_stmt_execute($stmtAllot);
    $resA = mysqli_stmt_get_result($stmtAllot);
    if ($resA) {
        while ($a = mysqli_fetch_assoc($resA)) $allotRows[] = $a;
    }
    mysqli_stmt_close($stmtAllot);
}

// -----------------------------------------------------------------
// Ref No summary (for dropdown + JS validation)
// Excludes the row currently being edited so its own amount
// doesn't count against itself.
// -----------------------------------------------------------------
$refNoSummary = fetchRefNoSummary($conn, $logged_in_user, $editAllotId);

// -----------------------------------------------------------------
// FETCH which allotments already have a buffer_transaction row
// -----------------------------------------------------------------
$sentAllotmentIds = [];
if (!empty($allotRows)) {
    $ids   = array_map(fn($r) => (int)$r['id'], $allotRows);
    $place = implode(',', array_fill(0, count($ids), '?'));

    $sqlSent = "SELECT prod_allotment_id, buffer_allotment_id
                FROM buffer_transaction
                WHERE (prod_allotment_id IN ($place) OR buffer_allotment_id IN ($place))
                  AND status IN ('pending','complete','approved')";
    $stmtSent = mysqli_prepare($conn, $sqlSent);
    if ($stmtSent) {
        $params = array_merge($ids, $ids);
        $types  = str_repeat('i', count($params));
        mysqli_stmt_bind_param($stmtSent, $types, ...$params);
        mysqli_stmt_execute($stmtSent);
        $resSent = mysqli_stmt_get_result($stmtSent);
        if ($resSent) {
            while ($s = mysqli_fetch_assoc($resSent)) {
                if (!empty($s['prod_allotment_id']))   $sentAllotmentIds[(int)$s['prod_allotment_id']]   = true;
                if (!empty($s['buffer_allotment_id'])) $sentAllotmentIds[(int)$s['buffer_allotment_id']] = true;
            }
        }
        mysqli_stmt_close($stmtSent);
    }
}

// -----------------------------------------------------------------
// Load row for edit — but refuse if it's already sent OR not owned
// -----------------------------------------------------------------
$editAllotment = null;
if ($editAllotId > 0) {
    if (!empty($sentAllotmentIds[$editAllotId])) {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'This allotment has already been sent and cannot be edited.'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
        exit();
    }
    $stmtEdit = mysqli_prepare($conn, "SELECT * FROM urea_allotment WHERE id = ? AND sender = ?");
    if ($stmtEdit) {
        mysqli_stmt_bind_param($stmtEdit, 'is', $editAllotId, $logged_in_user);
        mysqli_stmt_execute($stmtEdit);
        $resEdit = mysqli_stmt_get_result($stmtEdit);
        $editAllotment = mysqli_fetch_assoc($resEdit) ?: null;
        mysqli_stmt_close($stmtEdit);
    }
    if (!$editAllotment) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Allotment not found or not owned by you.'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
        exit();
    }
}

// -----------------------------------------------------------------
// FETCH OFFICES (Sender / Receiver dropdowns)
// -----------------------------------------------------------------
$offices = [];
$sqlOff  = "SELECT id, office_name, buffer_name
            FROM office_tbl
            WHERE buffer_name IS NOT NULL AND buffer_name <> ''
            ORDER BY office_name ASC";
if ($resOff = mysqli_query($conn, $sqlOff)) {
    while ($o = mysqli_fetch_assoc($resOff)) $offices[] = $o;
}

// -----------------------------------------------------------------
// Precompute form values (edit takes priority, else old input)
// -----------------------------------------------------------------
$formRefNo      = $editAllotment['ref_no']              ?? $oldAllot['ref_no'];
$formSender     = $editAllotment['sender']              ?? $oldAllot['sender']   ?? $logged_in_user;
$formReceiver   = $editAllotment['receiver']            ?? $oldAllot['receiver'];
$formAmount     = $editAllotment['amount']              ?? $oldAllot['amount'];
$formTotal      = $editAllotment['total_allot_amount']  ?? $oldAllot['total_allot_amount'];
$formMedium     = $editAllotment['medium']              ?? $oldAllot['medium'];

// Is the current ref_no one of the existing ones (not new)?
$isExistingRef  = ($formRefNo !== '' && isset($refNoSummary[$formRefNo]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - Marketing Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { background:#f4f6fa; }
    .summary-pill { padding:5px 12px; border-radius:14px; font-size:13px; font-weight:600; display:inline-block; margin-right:6px; }
    .pill-neutral  { background:#e2e8f0; color:#2d3748; }
    .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
    .card-header { border-bottom:1px solid #eef1f6; border-radius:14px 14px 0 0 !important; }
    .table thead th { background:#f8fafc; font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#475569; white-space:nowrap; }
    .table td { vertical-align:middle; white-space:nowrap; }
    .page-title { font-weight:700; color:#0f172a; }
    .btn-sm-icon { padding:3px 10px; font-size:12px; }
    .status-badge { padding:3px 10px; border-radius:10px; font-size:12px; font-weight:600; text-transform:capitalize; display:inline-block; }
    .status-pending   { background:#fef3c7; color:#92400e; }
    .status-approved  { background:#d1fae5; color:#065f46; }
    .status-rejected  { background:#fee2e2; color:#991b1b; }
    .status-default   { background:#e2e8f0; color:#2d3748; }
    .remaining-hint { font-size:12px; margin-top:4px; display:block; }
    .remaining-ok   { color:#059669; font-weight:600; }
    .remaining-warn { color:#b45309; font-weight:600; }
    .remaining-bad  { color:#dc2626; font-weight:600; }
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
            <small class="text-muted text-uppercase">Factory Dashboard</small>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">


<?php $dashboard = str_ends_with($_SESSION['username'], '_buffer') ? 'buffer_dashboard.php' : 'factory_dashboard.php'; ?> <a href="<?= $dashboard ?>?username=<?= urlencode($_SESSION['username']) ?>" class="btn btn-outline-secondary"> <i class="fa fa-arrow-left"></i> Back </a>        

            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= h($flash['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card" id="allotment">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-flask text-primary"></i> Urea Allotment</h5>
            <span class="summary-pill pill-neutral">Records: <?= count($allotRows); ?></span>
        </div>
        <div class="card-body">

            <form method="POST" action="<?= h($_SERVER['PHP_SELF']); ?>#allotment" class="mb-4" id="allotForm">
                <input type="hidden" name="action" value="<?= $editAllotment ? 'edit_allotment' : 'save_allotment'; ?>">
                <input type="hidden" name="al_id" value="<?= $editAllotment ? (int)$editAllotment['id'] : ''; ?>">

                <div class="row g-2">
                    <!-- Ref No dropdown + new-ref input -->
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Ref No</label>
                        <select class="form-select" name="ref_no_select" id="ref_no_select" required>
                            <option value="">-- Select Ref No --</option>
                            <?php foreach ($refNoSummary as $rn => $info): ?>
                                <option value="<?= h($rn); ?>"
                                        data-total="<?= h(number_format($info['total'], 2, '.', '')); ?>"
                                        data-used="<?= h(number_format($info['used'], 2, '.', '')); ?>"
                                        data-remaining="<?= h(number_format($info['remaining'], 2, '.', '')); ?>"
                                        <?= ($formRefNo === $rn && $isExistingRef) ? 'selected' : ''; ?>>
                                    <?= h($rn); ?> (Remaining: <?= h(number_format($info['remaining'], 2)); ?> MT)
                                </option>
                            <?php endforeach; ?>
                            <option value="__NEW__" <?= ($formRefNo !== '' && !$isExistingRef) ? 'selected' : ''; ?>>
                                + Add New Ref No
                            </option>
                        </select>

                        <input type="text"
                               class="form-control mt-2 <?= ($formRefNo !== '' && !$isExistingRef) ? '' : 'd-none'; ?>"
                               name="ref_no_new" id="ref_no_new"
                               placeholder="Enter new Ref No"
                               value="<?= ($formRefNo !== '' && !$isExistingRef) ? h($formRefNo) : ''; ?>"
                               maxlength="100">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small mb-1">Sender/Source</label>
                        <select class="form-select" name="sender" required readonly style="background:#f0f0f0;">
                            <option value="<?= h($_SESSION['username']) ?>"
                                <?= ((string)$formSender === (string)$_SESSION['username']) ? 'selected' : '' ?>>
                                <?= h($_SESSION['username']) ?>
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-1">Receiver</label>
                        <select class="form-select" name="receiver" required>
                            <option value="">-- Select Receiver --</option>
                            <?php foreach ($offices as $o): ?>
                                <option value="<?= h($o['buffer_name']); ?>"
                                    <?= ((string)$formReceiver === (string)$o['buffer_name']) ? 'selected' : ''; ?>>
                                    <?= h($o['office_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-1">Amount (MT)</label>
                        <input type="number" step="any" min="0.01" class="form-control" name="amount" id="amount"
                               value="<?= h($formAmount); ?>" required>
                        <small class="remaining-hint" id="remainingHint"></small>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-1">Medium</label>
                        <input type="text" class="form-control" name="medium"
                               value="<?= h($formMedium); ?>" required maxlength="100">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-1">Total Allotment Amount (MT)</label>
                        <input type="number" step="any" min="0.01" class="form-control"
                               name="total_allot_amount" id="total_allot_amount"  
                               value="<?= h($formTotal); ?>" required>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small mb-1">Created By</label>
                        <input type="text" class="form-control" value="<?= h($logged_in_user); ?>" readonly style="background:#f0f0f0;">
                        <input type="hidden" name="created_by" value="<?= h($logged_in_user); ?>">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa fa-save"></i> <?= $editAllotment ? 'Update Allotment' : 'Add Allotment'; ?>
                    </button>
                    <?php if ($editAllotment): ?>
                        <a href="<?= h($_SERVER['PHP_SELF']); ?>#allotment" class="btn btn-outline-secondary">
                            <i class="fa fa-times"></i> Cancel Edit
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Ref No</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th class="text-center">Amount (MT)</th>
                        <th class="text-center">Total Allot (MT)</th>
                        <th>Medium</th>
                        <th class="text-center">Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($allotRows)): ?>
                    <tr><td colspan="12" class="text-center text-muted py-4">No urea allotments found.</td></tr>
                <?php else: ?>
                    <?php foreach ($allotRows as $a):
                        $isSent = !empty($sentAllotmentIds[(int)$a['id']]);
                    ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$a['id']; ?></td>
                            <td><?= dash($a['ref_no']); ?></td>
                            <td><?= dash($a['sender']); ?></td>
                            <td><?= dash($a['receiver']); ?></td>
                            <td class="text-center fw-semibold"><?= h(number_format((float)$a['amount'], 2)); ?></td>
                            <td class="text-center"><?= $a['total_allot_amount'] !== null ? h(number_format((float)$a['total_allot_amount'], 2)) : '<span class="text-muted">—</span>'; ?></td>
                            <td><?= dash($a['medium']); ?></td>
                            <td class="text-center">
                                <span class="status-badge <?= statusClass((string)($a['status'] ?? '')); ?>">
                                    <?= h($a['status'] ?? ''); ?>
                                </span>
                            </td>
                            <td><?= dash($a['created_by']); ?></td>
                            <td class="text-muted small"><?= h($a['created_at'] ?? ''); ?></td>
                            <td class="text-muted small"><?= h($a['updated_at'] ?? ''); ?></td>
                            <td class="text-center">
                                <?php if ($isSent): ?>
                                    <button type="button" class="btn btn-outline-primary btn-sm-icon" disabled title="Already sent to buffer">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm-icon" disabled title="Already sent to buffer">
                                        <i class="fa fa-paper-plane"></i> Send
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm-icon" disabled title="Already sent to buffer">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                    <span class="badge bg-success-subtle text-success ms-1">
                                        <i class="fa fa-check-circle"></i> Sent
                                    </span>
                                <?php else: ?>
                                    <a class="btn btn-outline-primary btn-sm-icon"
                                       href="<?= h($_SERVER['PHP_SELF']); ?>?edit_allotment=<?= (int)$a['id']; ?>#allotment">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="<?= h($_SERVER['PHP_SELF']); ?>#allotment" class="d-inline"
                                          onsubmit="return confirm('Send allotment #<?= (int)$a['id']; ?> to buffer? This will create a pending buffer transaction.');">
                                        <input type="hidden" name="action" value="send_allotment">
                                        <input type="hidden" name="allotment_id" value="<?= (int)$a['id']; ?>">
                                        <button type="submit" class="btn btn-outline-success btn-sm-icon">
                                            <i class="fa fa-paper-plane"></i> Send
                                        </button>
                                    </form>
                                    <form method="POST" action="<?= h($_SERVER['PHP_SELF']); ?>#allotment" class="d-inline"
                                          onsubmit="return confirm('Delete allotment #<?= (int)$a['id']; ?>? This cannot be undone.');">
                                        <input type="hidden" name="action" value="delete_allotment">
                                        <input type="hidden" name="delete_id" value="<?= (int)$a['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm-icon">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
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

<script>
(function () {
    // Snapshot of ref_no summary from server
    var REF_SUMMARY = <?= json_encode(
        array_map(function ($v) {
            return [
                'total'     => (float)$v['total'],
                'used'      => (float)$v['used'],
                'remaining' => (float)$v['remaining'],
            ];
        }, $refNoSummary),
        JSON_UNESCAPED_UNICODE
    ); ?>;

    // The row currently being edited (0 when adding) — its own amount
    // is NOT part of REF_SUMMARY['used'] (server already excluded it).
    var EDIT_ROW_AMOUNT = <?= $editAllotment ? (float)$editAllotment['amount'] : 0; ?>;
    var IS_EDIT = <?= $editAllotment ? 'true' : 'false'; ?>;

    var refSelect      = document.getElementById('ref_no_select');
    var refNewInput    = document.getElementById('ref_no_new');
    var totalInput     = document.getElementById('total_allot_amount');
    var amountInput    = document.getElementById('amount');
    var hint           = document.getElementById('remainingHint');
    var submitBtn      = document.getElementById('submitBtn');

    function fmt(n) {
        n = Number(n);
        if (!isFinite(n)) return '0.00';
        return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateNewRefVisibility() {
        if (refSelect.value === '__NEW__') {
            refNewInput.classList.remove('d-none');
            refNewInput.required = true;
            totalInput.readOnly = false;
            totalInput.style.background = '';
        } else {
            refNewInput.classList.add('d-none');
            refNewInput.required = false;
            refNewInput.value = '';
            // Existing ref → total is locked
            if (refSelect.value !== '' && REF_SUMMARY[refSelect.value]) {
                totalInput.readOnly = true;
                totalInput.style.background = '#f0f0f0';
                totalInput.value = REF_SUMMARY[refSelect.value].total.toFixed(2);
            } else {
                totalInput.readOnly = false;
                totalInput.style.background = '';
            }
        }
    }

    function updateRemainingHint() {
        var ref = refSelect.value;
        hint.textContent = '';
        hint.className = 'remaining-hint';
        submitBtn.disabled = false;

        if (!ref || ref === '__NEW__') {
            // New ref → nothing to compare against
            return;
        }
        var info = REF_SUMMARY[ref];
        if (!info) return;

        var remaining = info.remaining;
        var amt = parseFloat(amountInput.value || '0');
        if (!isFinite(amt) || amt <= 0) {
            hint.classList.add('remaining-ok');
            hint.textContent = 'Remaining for this Ref No: ' + fmt(remaining) + ' MT';
            return;
        }

        var left = remaining - amt;
        if (left < -0.0001) {
            hint.classList.add('remaining-bad');
            hint.textContent = 'Exceeds remaining by ' + fmt(-left) + ' MT (available ' + fmt(remaining) + ' MT)';
            submitBtn.disabled = true;
        } else {
            hint.classList.add(left === 0 ? 'remaining-warn' : 'remaining-ok');
            hint.textContent = 'Remaining after this entry: ' + fmt(left) + ' MT';
        }
    }

    function refresh() {
        updateNewRefVisibility();
        updateRemainingHint();
    }

    refSelect.addEventListener('change', refresh);
    amountInput.addEventListener('input', updateRemainingHint);
    totalInput.addEventListener('input', updateRemainingHint);

    // Initial paint
    refresh();
})();
</script>

</body>
</html