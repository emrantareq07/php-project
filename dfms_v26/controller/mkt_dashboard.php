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
$oldAllot    = $_SESSION['old_allot']  ?? ['ref_no' => '', 'sender' => '', 'receiver' => '', 'amount' => '', 'medium' => '', 'created_by' => ''];
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
//         (checks BOTH prod_allotment_id and buffer_allotment_id)
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

// =================================================================
// HANDLE: ADD / EDIT UREA ALLOTMENT
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(($_POST['action'] ?? ''), ['save_allotment', 'edit_allotment'], true)) {
    $isEdit   = ($_POST['action'] === 'edit_allotment');
    $editId   = $isEdit ? (int)($_POST['al_id'] ?? 0) : 0;
    $ref_no   = trim($_POST['ref_no']    ?? '');
    $sender   = trim($_POST['sender']    ?? '');
    $receiver = trim($_POST['receiver']  ?? '');
    $amount   = trim($_POST['amount']    ?? '');
    $medium   = trim($_POST['medium']    ?? '');
    $created_by = $logged_in_user;

    $errors = [];
    if ($isEdit && $editId <= 0)   $errors[] = 'Invalid record id for edit.';
    if ($ref_no === '')            $errors[] = 'Reference number is required.';
    if ($sender === '')            $errors[] = 'Sender is required.';
    if ($receiver === '')          $errors[] = 'Receiver is required.';
    if ($amount === '' || !is_numeric($amount) || (float)$amount <= 0) $errors[] = 'Amount must be a positive number.';
    if ($medium === '')            $errors[] = 'Medium is required.';
    if (mb_strlen($medium) > 100)  $errors[] = 'Medium must be 100 characters or fewer.';

    if (!$errors) {
        $allowedBuffers = [];
        $vq = mysqli_query($conn, "SELECT buffer_name FROM office_tbl WHERE buffer_name IS NOT NULL AND buffer_name <> ''");
        if ($vq) {
            while ($vr = mysqli_fetch_assoc($vq)) $allowedBuffers[] = $vr['buffer_name'];
        }
        if (!in_array($sender, $allowedBuffers, true))   $errors[] = 'Invalid sender selected.';
        if (!in_array($receiver, $allowedBuffers, true)) $errors[] = 'Invalid receiver selected.';
    }

    if ($isEdit && $editId > 0 && allotmentIsSent($conn, $editId)) {
        $errors[] = 'This allotment has already been sent and cannot be edited.';
    }

    if ($errors) {
        $_SESSION['flash']     = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_allot'] = [
            'ref_no' => $ref_no, 'sender' => $sender, 'receiver' => $receiver,
            'amount' => $amount, 'medium' => $medium, 'created_by' => $created_by
        ];
        $_SESSION['edit_allot_id'] = $editId;
        header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $amt = (float)$amount;

        if ($isEdit) {
            $sql = "UPDATE urea_allotment
                       SET ref_no = ?, sender = ?, receiver = ?, amount = ?, medium = ?, updated_at = NOW()
                     WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'sssdsi', $ref_no, $sender, $receiver, $amt, $medium, $editId);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
            if (mysqli_stmt_affected_rows($stmt) === 0) throw new Exception('Record not found or nothing changed.');
            mysqli_stmt_close($stmt);

            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Allotment #{$editId} updated successfully."];
        } else {
            $sql = "INSERT INTO urea_allotment
                        (ref_no, sender, receiver, amount, medium, status, created_by, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, 'pending', ?, NOW(), NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'sssdss', $ref_no, $sender, $receiver, $amt, $medium, $created_by);
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
            'amount' => $amount, 'medium' => $medium, 'created_by' => $created_by
        ];
        $_SESSION['edit_allot_id'] = $editId;
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
    exit();
}

// -----------------------------------------------------------------
// HANDLE: DELETE UREA ALLOTMENT (blocked if already sent)
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
            $stmt = mysqli_prepare($conn, "DELETE FROM urea_allotment WHERE id = ?");
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, 'i', $delId);
            if (!mysqli_stmt_execute($stmt)) throw new Exception('Delete failed: ' . mysqli_stmt_error($stmt));
            if (mysqli_stmt_affected_rows($stmt) !== 1) throw new Exception('Allotment not found.');
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
// HANDLE: SEND ALLOTMENT → buffer_transaction (status = pending)
//          and update urea_allotment.status = 'complete'
//
//   receiver is a buffer_name from office_tbl
//   → look up the receiver office's office_type
//   → if buffer_godown    : set buffer_allotment_id = urea_allotment.id
//   → if factory_office   : set prod_allotment_id   = urea_allotment.id
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
        // 1) Lock & fetch the allotment
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

        if (strtolower(trim((string)$ua['status'])) !== 'pending') {
            throw new Exception('Only pending allotments can be sent. Current status: ' . $ua['status']);
        }

        // 2) Look up the receiver office's type
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

        // 3) Decide which column to populate
        $prodAllotmentId   = null;
        $bufferAllotmentId = null;

        if ($officeType === 'buffer_godown') {
            $bufferAllotmentId = $allotId;
        } elseif ($officeType === 'factory_office') {
            $prodAllotmentId = $allotId;
        } else {
            throw new Exception('Cannot send: receiver office_type "' . $officeType . '" is not supported (expected buffer_godown or factory_office).');
        }

        // 4) Duplicate guard — already in buffer_transaction?
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

        // 5) Insert into buffer_transaction
        $insSql = "INSERT INTO buffer_transaction
                        (prod_allotment_id, buffer_allotment_id, date, amount, medium, status, created_by, created_at, updated_at)
                   VALUES (?, ?, CURDATE(), ?, ?, 'pending', ?, NOW(), NOW())";
        $stmt = mysqli_prepare($conn, $insSql);
        if (!$stmt) throw new Exception('Prepare (insert) failed: ' . mysqli_error($conn));

        $amt    = (float)$ua['amount'];
        $medium = (string)$ua['medium'];

        // types: i, i, d, s, s
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

        // 6) Mark allotment as complete
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
// FETCH UREA ALLOTMENTS
// -----------------------------------------------------------------
$allotRows = [];
$sqlAllot  = "SELECT id, ref_no, sender, receiver, amount, medium, status, created_by, created_at, updated_at
              FROM urea_allotment
              ORDER BY id DESC";
if ($resA = mysqli_query($conn, $sqlAllot)) {
    while ($a = mysqli_fetch_assoc($resA)) $allotRows[] = $a;
}

// -----------------------------------------------------------------
// FETCH which allotments already have a buffer_transaction row
//   (checks BOTH columns — because Send can write to either one)
// -----------------------------------------------------------------
$sentAllotmentIds = [];
$sqlSent = "SELECT prod_allotment_id, buffer_allotment_id
            FROM buffer_transaction
            WHERE (prod_allotment_id IS NOT NULL OR buffer_allotment_id IS NOT NULL)
              AND status IN ('pending','complete','approved')";
if ($resSent = mysqli_query($conn, $sqlSent)) {
    while ($s = mysqli_fetch_assoc($resSent)) {
        if (!empty($s['prod_allotment_id']))   $sentAllotmentIds[(int)$s['prod_allotment_id']]   = true;
        if (!empty($s['buffer_allotment_id'])) $sentAllotmentIds[(int)$s['buffer_allotment_id']] = true;
    }
}

// -----------------------------------------------------------------
// Load row for edit — but refuse if it's already sent
// -----------------------------------------------------------------
$editAllotment = null;
if ($editAllotId > 0) {
    if (!empty($sentAllotmentIds[$editAllotId])) {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'This allotment has already been sent and cannot be edited.'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '#allotment');
        exit();
    }
    $stmtEdit = mysqli_prepare($conn, "SELECT * FROM urea_allotment WHERE id = ?");
    if ($stmtEdit) {
        mysqli_stmt_bind_param($stmtEdit, 'i', $editAllotId);
        mysqli_stmt_execute($stmtEdit);
        $resEdit = mysqli_stmt_get_result($stmtEdit);
        $editAllotment = mysqli_fetch_assoc($resEdit) ?: null;
        mysqli_stmt_close($stmtEdit);
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
            <small class="text-muted text-uppercase">Marketing Dashboard</small>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="dealer_manage.php" class="btn btn-outline-secondary"><i class="fa fa-plus-circle"></i> Dealer Manage</a>
            
            <a href="dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
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

            <form method="POST" action="<?= h($_SERVER['PHP_SELF']); ?>#allotment" class="mb-4">
                <input type="hidden" name="action" value="<?= $editAllotment ? 'edit_allotment' : 'save_allotment'; ?>">
                <input type="hidden" name="al_id" value="<?= $editAllotment ? (int)$editAllotment['id'] : ''; ?>">

                <div class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Ref No</label>
                        <input type="text" class="form-control" name="ref_no"
                               value="<?= h($editAllotment['ref_no'] ?? $oldAllot['ref_no']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Sender</label>
                        <select class="form-select" name="sender" required>
                            <option value="">-- Select Sender --</option>
                            <?php
                              $senderVal = $editAllotment['sender'] ?? $oldAllot['sender'];
                              foreach ($offices as $o):
                            ?>
                                <option value="<?= h($o['buffer_name']); ?>"
                                    <?= ((string)$senderVal === (string)$o['buffer_name']) ? 'selected' : ''; ?>>
                                    <?= h($o['office_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Receiver</label>
                        <select class="form-select" name="receiver" required>
                            <option value="">-- Select Receiver --</option>
                            <?php
                              $receiverVal = $editAllotment['receiver'] ?? $oldAllot['receiver'];
                              foreach ($offices as $o):
                            ?>
                                <option value="<?= h($o['buffer_name']); ?>"
                                    <?= ((string)$receiverVal === (string)$o['buffer_name']) ? 'selected' : ''; ?>>
                                    <?= h($o['office_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Amount</label>
                        <input type="number" step="any" min="0.01" class="form-control" name="amount"
                               value="<?= h($editAllotment['amount'] ?? $oldAllot['amount']); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Medium</label>
                        <input type="text" class="form-control" name="medium"
                               value="<?= h($editAllotment['medium'] ?? $oldAllot['medium']); ?>" required maxlength="100">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Created By</label>
                        <input type="text" class="form-control" value="<?= h($logged_in_user); ?>" readonly style="background:#f0f0f0;">
                        <input type="hidden" name="created_by" value="<?= h($logged_in_user); ?>">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
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
                        <th class="text-center">Amount</th>
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
                    <tr><td colspan="11" class="text-center text-muted py-4">No urea allotments found.</td></tr>
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

</body>
</html>