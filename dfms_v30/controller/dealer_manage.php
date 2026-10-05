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

// -----------------------------------------------------------------
// FLASH + modal state
// -----------------------------------------------------------------
$flash             = $_SESSION['flash']             ?? null;
$oldDealer         = $_SESSION['old_dealer']        ?? [
    'name' => '', 'nid' => '', 'mobile_no' => '', 'address' => '', 'office_tbl_id' => '', 'dealer_code' => ''
];
$reopenDealerModal = $_SESSION['reopen_dealer_modal'] ?? false;
$editDealerId      = $_SESSION['edit_dealer_id']      ?? 0;

unset(
    $_SESSION['flash'],
    $_SESSION['old_dealer'],
    $_SESSION['reopen_dealer_modal'],
    $_SESSION['edit_dealer_id']
);

// -----------------------------------------------------------------
// FETCH OFFICES (for the dropdown)
// -----------------------------------------------------------------
$offices = [];
$officeSql = "SELECT id, office_name FROM office_tbl ORDER BY office_name ASC";
if ($res = mysqli_query($conn, $officeSql)) {
    while ($o = mysqli_fetch_assoc($res)) $offices[] = $o;
}

// -----------------------------------------------------------------
// HANDLE: ADD / EDIT DEALER
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(($_POST['action'] ?? ''), ['add_dealer', 'edit_dealer'], true)) {
    $isEdit      = ($_POST['action'] === 'edit_dealer');
    $editId      = $isEdit ? (int)($_POST['edit_id'] ?? 0) : 0;
    $name        = trim($_POST['name']         ?? '');
    $nid         = trim($_POST['nid']          ?? '');
    $mobile_no   = trim($_POST['mobile_no']    ?? '');
    $address     = trim($_POST['address']      ?? '');
    $officeId    = trim($_POST['office_tbl_id']?? '');
    $dealer_code = trim($_POST['dealer_code']  ?? '');

    $errors = [];
    if ($isEdit && $editId <= 0)         $errors[] = 'Invalid record id.';
    if ($name === '')                    $errors[] = 'Dealer name is required.';
    if ($mobile_no === '')               $errors[] = 'Mobile number is required.';
    if (!preg_match('/^[0-9+\-\s]{6,20}$/', $mobile_no)) $errors[] = 'Mobile number format is invalid.';
    if ($nid !== '' && !preg_match('/^[0-9]{10,20}$/', $nid)) $errors[] = 'NID must be 10–20 digits.';
    if ($officeId === '' || !ctype_digit((string)$officeId))  $errors[] = 'Please select an office.';
    if (mb_strlen($address)     > 255)   $errors[] = 'Address must be 255 characters or fewer.';
    if (mb_strlen($dealer_code) > 50)    $errors[] = 'Dealer code must be 50 characters or fewer.';

    // Validate office exists
    if (!$errors && $officeId !== '') {
        $chk = mysqli_prepare($conn, "SELECT 1 FROM office_tbl WHERE id = ?");
        if ($chk) {
            $oid = (int)$officeId;
            mysqli_stmt_bind_param($chk, 'i', $oid);
            mysqli_stmt_execute($chk);
            mysqli_stmt_store_result($chk);
            if (mysqli_stmt_num_rows($chk) === 0) {
                $errors[] = 'Selected office does not exist.';
            }
            mysqli_stmt_close($chk);
        }
    }

    // Optional: unique dealer_code check
    if (!$errors && $dealer_code !== '') {
        $uq = mysqli_prepare($conn, "SELECT id FROM dealer_tbl WHERE dealer_code = ? AND id <> ?");
        if ($uq) {
            $skipId = $isEdit ? $editId : 0;
            mysqli_stmt_bind_param($uq, 'si', $dealer_code, $skipId);
            mysqli_stmt_execute($uq);
            mysqli_stmt_store_result($uq);
            if (mysqli_stmt_num_rows($uq) > 0) {
                $errors[] = 'Dealer code already exists.';
            }
            mysqli_stmt_close($uq);
        }
    }

    if ($errors) {
        $_SESSION['flash']              = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_dealer']         = [
            'name' => $name, 'nid' => $nid, 'mobile_no' => $mobile_no,
            'address' => $address, 'office_tbl_id' => $officeId, 'dealer_code' => $dealer_code
        ];
        $_SESSION['reopen_dealer_modal'] = true;
        $_SESSION['edit_dealer_id']      = $editId;
    } else {
        mysqli_begin_transaction($conn);
        try {
            $oid = (int)$officeId;
            $nidVal = ($nid === '') ? null : $nid;

            if ($isEdit) {
                $sql = "UPDATE dealer_tbl
                           SET name=?, nid=?, mobile_no=?, address=?, office_tbl_id=?, dealer_code=?, updated_at=NOW()
                         WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);
                if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

                mysqli_stmt_bind_param($stmt, 'ssssisi',
                    $name, $nidVal, $mobile_no, $address, $oid, $dealer_code, $editId);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);

                mysqli_commit($conn);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Dealer #{$editId} updated successfully."];
            } else {
                $sql = "INSERT INTO dealer_tbl
                            (name, nid, mobile_no, address, office_tbl_id, dealer_code, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
                $stmt = mysqli_prepare($conn, $sql);
                if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

                mysqli_stmt_bind_param($stmt, 'ssssis',
                    $name, $nidVal, $mobile_no, $address, $oid, $dealer_code);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
                }
                $newId = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);

                mysqli_commit($conn);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Dealer #{$newId} added successfully."];
            }
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash']              = ['type' => 'danger', 'msg' => ($isEdit ? 'Update failed: ' : 'Add failed: ') . $e->getMessage()];
            $_SESSION['old_dealer']         = [
                'name' => $name, 'nid' => $nid, 'mobile_no' => $mobile_no,
                'address' => $address, 'office_tbl_id' => $officeId, 'dealer_code' => $dealer_code
            ];
            $_SESSION['reopen_dealer_modal'] = true;
            $_SESSION['edit_dealer_id']      = $editId;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// HANDLE: DELETE DEALER
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_dealer' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];
    if ($delId > 0) {
        mysqli_begin_transaction($conn);
        try {
            $sql  = "DELETE FROM dealer_tbl WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'i', $delId);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Delete failed: ' . mysqli_stmt_error($stmt));
            }
            if (mysqli_stmt_affected_rows($stmt) !== 1) {
                throw new Exception('Dealer not found.');
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Dealer #{$delId} deleted."];
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Delete failed: ' . $e->getMessage()];
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// FETCH DEALERS (with office name via LEFT JOIN)
// -----------------------------------------------------------------
$dealers = [];
$sql = "SELECT d.id, d.name, d.nid, d.mobile_no, d.address,
               d.office_tbl_id, d.dealer_code, d.created_at, d.updated_at,
               o.office_name
        FROM dealer_tbl d
        LEFT JOIN office_tbl o ON o.id = d.office_tbl_id
        ORDER BY d.id DESC";
if ($res = mysqli_query($conn, $sql)) {
    while ($r = mysqli_fetch_assoc($res)) $dealers[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - Dealer Management</title>
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

    <!-- Header -->
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h3 class="page-title mb-0">Welcome <b class="text-danger"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">Marketing Dashboard — Dealer Management</small>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dealerModal">
                <i class="fa fa-plus"></i> Add Dealer
            </button>
            
            <a href="mkt_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= h($flash['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- DEALER TABLE -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-users text-primary"></i> Dealer List</h5>
            <span class="summary-pill pill-neutral">Records: <?= count($dealers); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Name</th>
                        <th>NID</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                        <th>Office</th>
                        <th>Dealer Code</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($dealers)): ?>
                    <tr><td colspan="10" class="text-center text-muted py-4">No dealers found.</td></tr>
                <?php else: ?>
                    <?php foreach ($dealers as $d): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$d['id']; ?></td>
                            <td class="fw-semibold"><?= h($d['name']); ?></td>
                            <td><?= dash($d['nid']); ?></td>
                            <td><?= dash($d['mobile_no']); ?></td>
                            <td><?= dash($d['address']); ?></td>
                            <td><?= dash($d['office_name']); ?></td>
                            <td><?= dash($d['dealer_code']); ?></td>
                            <td class="text-muted small"><?= h($d['created_at'] ?? ''); ?></td>
                            <td class="text-muted small"><?= h($d['updated_at'] ?? ''); ?></td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm-icon btn-edit-dealer"
                                        data-id="<?= (int)$d['id']; ?>"
                                        data-name="<?= h((string)($d['name'] ?? '')); ?>"
                                        data-nid="<?= h((string)($d['nid'] ?? '')); ?>"
                                        data-mobile="<?= h((string)($d['mobile_no'] ?? '')); ?>"
                                        data-address="<?= h((string)($d['address'] ?? '')); ?>"
                                        data-office="<?= h((string)($d['office_tbl_id'] ?? '')); ?>"
                                        data-code="<?= h((string)($d['dealer_code'] ?? '')); ?>">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form method="POST" action="" class="d-inline"
                                      onsubmit="return confirm('Delete dealer #<?= (int)$d['id']; ?>?');">
                                    <input type="hidden" name="action" value="delete_dealer">
                                    <input type="hidden" name="delete_id" value="<?= (int)$d['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm-icon">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
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

<!-- ============ MODAL: ADD / EDIT DEALER ============ -->
<div class="modal fade" id="dealerModal" tabindex="-1" aria-labelledby="dealerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" action="" id="dealerForm">
        <input type="hidden" name="action"  id="dealer_action"  value="add_dealer">
        <input type="hidden" name="edit_id" id="dealer_edit_id" value="">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="dealerModalLabel">
            <i class="fa fa-plus-circle"></i> Add Dealer
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="dealer_name" class="form-label">Dealer Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="dealer_name" name="name"
                     value="<?= h($oldDealer['name']); ?>" required maxlength="150">
            </div>
            <div class="col-md-6">
              <label for="dealer_nid" class="form-label">NID</label>
              <input type="text" class="form-control" id="dealer_nid" name="nid"
                     value="<?= h($oldDealer['nid']); ?>" maxlength="20">
            </div>
            <div class="col-md-6">
              <label for="dealer_mobile" class="form-label">Mobile No <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="dealer_mobile" name="mobile_no"
                     value="<?= h($oldDealer['mobile_no']); ?>" required maxlength="20">
            </div>
            <div class="col-md-6">
              <label for="dealer_code" class="form-label">Dealer Code</label>
              <input type="text" class="form-control" id="dealer_code" name="dealer_code"
                     value="<?= h($oldDealer['dealer_code']); ?>" maxlength="50">
            </div>
            <div class="col-md-6">
              <label for="office_tbl_id" class="form-label">Office <span class="text-danger">*</span></label>
              <select class="form-select" id="office_tbl_id" name="office_tbl_id" required>
                <option value="">-- Select Office --</option>
                <?php foreach ($offices as $o): ?>
                  <option value="<?= (int)$o['id']; ?>"
                    <?= ((string)$oldDealer['office_tbl_id'] === (string)$o['id']) ? 'selected' : ''; ?>>
                    <?= h($o['office_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="dealer_address" class="form-label">Address</label>
              <input type="text" class="form-control" id="dealer_address" name="address"
                     value="<?= h($oldDealer['address']); ?>" maxlength="255">
            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    var dealerModalEl = document.getElementById('dealerModal');
    var dealerModal   = new bootstrap.Modal(dealerModalEl);
    var editingDealer = false;

    // Reset to ADD mode when opened normally
    dealerModalEl.addEventListener('show.bs.modal', function () {
        if (editingDealer) return;
        document.getElementById('dealerForm').reset();
        document.getElementById('dealer_action').value  = 'add_dealer';
        document.getElementById('dealer_edit_id').value = '';
        document.getElementById('dealerModalLabel').innerHTML =
            '<i class="fa fa-plus-circle"></i> Add Dealer';
    });

    dealerModalEl.addEventListener('hidden.bs.modal', function () {
        editingDealer = false;
    });

    // EDIT buttons
    document.querySelectorAll('.btn-edit-dealer').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            editingDealer = true;

            document.getElementById('dealer_action').value  = 'edit_dealer';
            document.getElementById('dealer_edit_id').value = this.dataset.id || '';
            document.getElementById('dealer_name').value    = this.dataset.name || '';
            document.getElementById('dealer_nid').value     = this.dataset.nid || '';
            document.getElementById('dealer_mobile').value  = this.dataset.mobile || '';
            document.getElementById('dealer_address').value = this.dataset.address || '';
            document.getElementById('dealer_code').value    = this.dataset.code || '';
            document.getElementById('office_tbl_id').value  = this.dataset.office || '';
            document.getElementById('dealerModalLabel').innerHTML =
                '<i class="fa fa-edit"></i> Edit Dealer';

            dealerModal.show();
        });
    });

    // Reopen after validation / DB error
    <?php if ($reopenDealerModal): ?>
    (function () {
        editingDealer = true;
        document.getElementById('dealer_action').value  = <?= $editDealerId > 0 ? "'edit_dealer'" : "'add_dealer'"; ?>;
        document.getElementById('dealer_edit_id').value = "<?= (int)$editDealerId; ?>";
        document.getElementById('dealerModalLabel').innerHTML =
            <?= $editDealerId > 0
                ? "'<i class=\"fa fa-edit\"></i> Edit Dealer'"
                : "'<i class=\"fa fa-plus-circle\"></i> Add Dealer'"; ?>;
        dealerModal.show();
    })();
    <?php endif; ?>

});
</script>

</body>
</html>