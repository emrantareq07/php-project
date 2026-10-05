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

// Allowed office types
$OFFICE_TYPES = [
    'buffer_godown',
    'factory_office',
    'port_office',
    'transite_godown',
    'bcic_hq',
    'moi',
    'moa',
    'pmo',
    'cabinet',
];
function officeTypeLabel(string $t): string {
    return ucwords(str_replace('_', ' ', $t));
}

// -----------------------------------------------------------------
// FLASH + modal state
// -----------------------------------------------------------------
$flash             = $_SESSION['flash']             ?? null;
$oldOffice         = $_SESSION['old_office']        ?? [
    'office_name' => '', 'buffer_name' => '', 'zone' => '', 'office_type' => '', 'address' => ''
];
$reopenOfficeModal = $_SESSION['reopen_office_modal'] ?? false;
$editOfficeId      = $_SESSION['edit_office_id']      ?? 0;

unset(
    $_SESSION['flash'],
    $_SESSION['old_office'],
    $_SESSION['reopen_office_modal'],
    $_SESSION['edit_office_id']
);

// -----------------------------------------------------------------
// HANDLE: ADD / EDIT OFFICE
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(($_POST['action'] ?? ''), ['add_office', 'edit_office'], true)) {
    $isEdit      = ($_POST['action'] === 'edit_office');
    $editId      = $isEdit ? (int)($_POST['edit_id'] ?? 0) : 0;
    $office_name = trim($_POST['office_name'] ?? '');
    $buffer_name = trim($_POST['buffer_name'] ?? '');
    $zone        = trim($_POST['zone']        ?? '');
    $office_type = trim($_POST['office_type'] ?? '');
    $address     = trim($_POST['address']     ?? '');

    $errors = [];
    if ($isEdit && $editId <= 0)   $errors[] = 'Invalid record id.';
    if ($office_name === '')       $errors[] = 'Office name is required.';
    if ($buffer_name === '')       $errors[] = 'Buffer/Factory/Port name is required.';
    if ($zone === '')              $errors[] = 'Zone is required.';
    if ($office_type === '')       $errors[] = 'Office type is required.';
    if ($office_type !== '' && !in_array($office_type, $OFFICE_TYPES, true)) {
        $errors[] = 'Invalid office type selected.';
    }
    if (mb_strlen($office_name) > 150) $errors[] = 'Office name must be 150 characters or fewer.';
    if (mb_strlen($buffer_name) > 150) $errors[] = 'Buffer name must be 150 characters or fewer.';
    if (mb_strlen($zone)        > 100) $errors[] = 'Zone must be 100 characters or fewer.';
    if (mb_strlen($address)     > 255) $errors[] = 'Address must be 255 characters or fewer.';

    // Unique office_name check
    if (!$errors) {
        $uq = mysqli_prepare($conn, "SELECT id FROM office_tbl WHERE office_name = ? AND id <> ?");
        if ($uq) {
            $skipId = $isEdit ? $editId : 0;
            mysqli_stmt_bind_param($uq, 'si', $office_name, $skipId);
            mysqli_stmt_execute($uq);
            mysqli_stmt_store_result($uq);
            if (mysqli_stmt_num_rows($uq) > 0) {
                $errors[] = 'An office with this name already exists.';
            }
            mysqli_stmt_close($uq);
        }
    }

    if ($errors) {
        $_SESSION['flash']              = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        $_SESSION['old_office']         = [
            'office_name' => $office_name, 'buffer_name' => $buffer_name,
            'zone' => $zone, 'office_type' => $office_type, 'address' => $address
        ];
        $_SESSION['reopen_office_modal'] = true;
        $_SESSION['edit_office_id']      = $editId;
    } else {
        mysqli_begin_transaction($conn);
        try {
            if ($isEdit) {
                // UPDATE — columns: office_name, buffer_name, zone, office_type, address, updated_at, id
                $sql = "UPDATE office_tbl
                           SET office_name = ?, buffer_name = ?, zone = ?, office_type = ?, address = ?, updated_at = NOW()
                         WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

                // types: s s s s s  i   → 'sssssi'
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssssi',
                    $office_name,
                    $buffer_name,
                    $zone,
                    $office_type,
                    $address,
                    $editId
                );
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);

                mysqli_commit($conn);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Office #{$editId} updated successfully."];
            } else {
                // INSERT — columns: office_name, buffer_name, zone, office_type, address, created_at, updated_at
                $sql = "INSERT INTO office_tbl
                            (office_name, buffer_name, zone, office_type, address, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
                $stmt = mysqli_prepare($conn, $sql);
                if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

                // 5 placeholders → 'sssss'
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssss',
                    $office_name,
                    $buffer_name,
                    $zone,
                    $office_type,
                    $address
                );
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Insert failed: ' . mysqli_stmt_error($stmt));
                }
                $newId = mysqli_insert_id($conn);
                mysqli_stmt_close($stmt);

                mysqli_commit($conn);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Office #{$newId} added successfully."];
            }
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash']              = ['type' => 'danger', 'msg' => ($isEdit ? 'Update failed: ' : 'Add failed: ') . $e->getMessage()];
            $_SESSION['old_office']         = [
                'office_name' => $office_name, 'buffer_name' => $buffer_name,
                'zone' => $zone, 'office_type' => $office_type, 'address' => $address
            ];
            $_SESSION['reopen_office_modal'] = true;
            $_SESSION['edit_office_id']      = $editId;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// HANDLE: DELETE OFFICE
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_office' && isset($_POST['delete_id'])) {
    $delId = (int)$_POST['delete_id'];
    if ($delId > 0) {
        mysqli_begin_transaction($conn);
        try {
            $chk = mysqli_prepare($conn, "SELECT COUNT(*) AS cnt FROM dealer_tbl WHERE office_tbl_id = ?");
            if ($chk) {
                mysqli_stmt_bind_param($chk, 'i', $delId);
                mysqli_stmt_execute($chk);
                $cres = mysqli_stmt_get_result($chk);
                $crow = mysqli_fetch_assoc($cres);
                mysqli_stmt_close($chk);
                if ((int)($crow['cnt'] ?? 0) > 0) {
                    throw new Exception('Cannot delete: dealers are assigned to this office.');
                }
            }

            $sql  = "DELETE FROM office_tbl WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

            mysqli_stmt_bind_param($stmt, 'i', $delId);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Delete failed: ' . mysqli_stmt_error($stmt));
            }
            if (mysqli_stmt_affected_rows($stmt) !== 1) {
                throw new Exception('Office not found.');
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Office #{$delId} deleted."];
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Delete failed: ' . $e->getMessage()];
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// FETCH OFFICES + dealer counts
// -----------------------------------------------------------------
$offices = [];
$sql = "SELECT o.id, o.office_name, o.buffer_name, o.zone, o.office_type, o.address,
               o.created_at, o.updated_at,
               (SELECT COUNT(*) FROM dealer_tbl d WHERE d.office_tbl_id = o.id) AS dealer_count
        FROM office_tbl o
        ORDER BY o.id DESC";
if ($res = mysqli_query($conn, $sql)) {
    while ($r = mysqli_fetch_assoc($res)) $offices[] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - Office Management</title>
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
    .badge-soft { padding:3px 10px; border-radius:10px; font-size:12px; font-weight:600; display:inline-block; }
    .badge-zone { background:#eef2ff; color:#3730a3; }
    .badge-dealers { background:#fef3c7; color:#92400e; }
    /* Office type color chips */
    .type-buffer_godown    { background:#dbeafe; color:#1e40af; }
    .type-factory_office   { background:#dcfce7; color:#166534; }
    .type-port_office      { background:#fef9c3; color:#854d0e; }
    .type-transite_godown  { background:#fee2e2; color:#991b1b; }
    .type-bcic_hq          { background:#ede9fe; color:#5b21b6; }
    .type-moi              { background:#cffafe; color:#155e75; }
    .type-moa              { background:#fce7f3; color:#9d174d; }
    .type-pmo              { background:#ffedd5; color:#9a3412; }
    .type-cabinet          { background:#e2e8f0; color:#1e293b; }
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
            <small class="text-muted text-uppercase">Marketing Dashboard — Office Management</small>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#officeModal">
                <i class="fa fa-plus"></i> Add Office
            </button>
            <a href="sadmin_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= h($flash['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- OFFICE TABLE -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-building text-primary"></i> Office List</h5>
            <span class="summary-pill pill-neutral">Records: <?= count($offices); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Office Name</th>
                        <th>Buffer / Factory / Port Name</th>
                        <th>Zone</th>
                        <th>Office Type</th>
                        <th>Address</th>
                        <th class="text-center">Dealers</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($offices)): ?>
                    <tr><td colspan="10" class="text-center text-muted py-4">No offices found.</td></tr>
                <?php else: ?>
                    <?php foreach ($offices as $o):
                        $typeRaw   = (string)($o['office_type'] ?? '');
                        $typeClass = 'type-' . preg_replace('/[^a-z0-9_]/', '', strtolower($typeRaw));
                    ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$o['id']; ?></td>
                            <td class="fw-semibold"><?= h($o['office_name']); ?></td>
                            <td><?= dash($o['buffer_name']); ?></td>
                            <td><span class="badge-soft badge-zone"><?= dash($o['zone']); ?></span></td>
                            <td>
                                <span class="badge-soft <?= h($typeClass); ?>">
                                    <?= h(officeTypeLabel($typeRaw)); ?>
                                </span>
                            </td>
                            <td><?= dash($o['address']); ?></td>
                            <td class="text-center">
                                <span class="badge-soft badge-dealers"><?= (int)$o['dealer_count']; ?></span>
                            </td>
                            <td class="text-muted small"><?= h($o['created_at'] ?? ''); ?></td>
                            <td class="text-muted small"><?= h($o['updated_at'] ?? ''); ?></td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm-icon btn-edit-office"
                                        data-id="<?= (int)$o['id']; ?>"
                                        data-name="<?= h((string)($o['office_name'] ?? '')); ?>"
                                        data-buffer="<?= h((string)($o['buffer_name'] ?? '')); ?>"
                                        data-zone="<?= h((string)($o['zone'] ?? '')); ?>"
                                        data-type="<?= h($typeRaw); ?>"
                                        data-address="<?= h((string)($o['address'] ?? '')); ?>">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form method="POST" action="" class="d-inline"
                                      onsubmit="return confirm('Delete office #<?= (int)$o['id']; ?>?');">
                                    <input type="hidden" name="action" value="delete_office">
                                    <input type="hidden" name="delete_id" value="<?= (int)$o['id']; ?>">
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

<!-- ============ MODAL: ADD / EDIT OFFICE ============ -->
<div class="modal fade" id="officeModal" tabindex="-1" aria-labelledby="officeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" action="" id="officeForm">
        <input type="hidden" name="action"  id="office_action"  value="add_office">
        <input type="hidden" name="edit_id" id="office_edit_id" value="">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="officeModalLabel">
            <i class="fa fa-plus-circle"></i> Add Office
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="office_name" class="form-label">Office Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="office_name" name="office_name"
                     value="<?= h($oldOffice['office_name']); ?>" required maxlength="150">
            </div>
            <div class="col-md-6">
              <label for="buffer_name" class="form-label">Buffer / Factory / Port Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="buffer_name" name="buffer_name"
                     value="<?= h($oldOffice['buffer_name']); ?>" required maxlength="150">
            </div>
            <div class="col-md-6">
              <label for="zone" class="form-label">Zone <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="zone" name="zone"
                     value="<?= h($oldOffice['zone']); ?>" required maxlength="100">
            </div>
            <div class="col-md-6">
              <label for="office_type" class="form-label">Office Type <span class="text-danger">*</span></label>
              <select class="form-select" id="office_type" name="office_type" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($OFFICE_TYPES as $t): ?>
                  <option value="<?= h($t); ?>" <?= ($oldOffice['office_type'] === $t) ? 'selected' : ''; ?>>
                    <?= h(officeTypeLabel($t)); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address" name="address"
                     value="<?= h($oldOffice['address']); ?>" maxlength="255">
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

    var officeModalEl = document.getElementById('officeModal');
    var officeModal   = new bootstrap.Modal(officeModalEl);
    var editingOffice = false;

    officeModalEl.addEventListener('show.bs.modal', function () {
        if (editingOffice) return;
        document.getElementById('officeForm').reset();
        document.getElementById('office_action').value  = 'add_office';
        document.getElementById('office_edit_id').value = '';
        document.getElementById('officeModalLabel').innerHTML =
            '<i class="fa fa-plus-circle"></i> Add Office';
    });

    officeModalEl.addEventListener('hidden.bs.modal', function () {
        editingOffice = false;
    });

    document.querySelectorAll('.btn-edit-office').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            editingOffice = true;

            document.getElementById('office_action').value  = 'edit_office';
            document.getElementById('office_edit_id').value = this.dataset.id || '';
            document.getElementById('office_name').value    = this.dataset.name || '';
            document.getElementById('buffer_name').value    = this.dataset.buffer || '';
            document.getElementById('zone').value           = this.dataset.zone || '';
            document.getElementById('office_type').value    = this.dataset.type || '';
            document.getElementById('address').value        = this.dataset.address || '';
            document.getElementById('officeModalLabel').innerHTML =
                '<i class="fa fa-edit"></i> Edit Office';

            officeModal.show();
        });
    });

    <?php if ($reopenOfficeModal): ?>
    (function () {
        editingOffice = true;
        document.getElementById('office_action').value  = <?= $editOfficeId > 0 ? "'edit_office'" : "'add_office'"; ?>;
        document.getElementById('office_edit_id').value = "<?= (int)$editOfficeId; ?>";
        document.getElementById('officeModalLabel').innerHTML =
            <?= $editOfficeId > 0
                ? "'<i class=\"fa fa-edit\"></i> Edit Office'"
                : "'<i class=\"fa fa-plus-circle\"></i> Add Office'"; ?>;
        officeModal.show();
    })();
    <?php endif; ?>

});
</script>

</body>
</html>