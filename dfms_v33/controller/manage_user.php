<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once('../db/db.php');

$logged_in_user = $_SESSION['username'];
$logged_in_type = $_SESSION['user_type'] ?? '';

function h($val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}
function dash($val) {
    $v = trim((string)($val ?? ''));
    return $v !== '' ? h($v) : '<span class="text-muted">—</span>';
}

$USER_TYPES = ['user', 'admin', 'sadmin'];
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

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// -----------------------------------------------------------------
// HANDLE: UPDATE USER
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_user') {
    $userId       = (int)($_POST['id'] ?? 0);
    $username     = trim($_POST['username']    ?? '');
    $email        = trim($_POST['email']       ?? '');
    $fullName     = trim($_POST['full_name']   ?? '');
    $designation  = trim($_POST['designation'] ?? '');
    $mobileNo     = trim($_POST['mobile_no']   ?? '');
    $division     = trim($_POST['division']    ?? '');
    $officeName   = trim($_POST['office_name'] ?? '');
    $userType     = trim($_POST['user_type']   ?? '');
    $officeType   = trim($_POST['office_type'] ?? '');
    $officeTblId  = (int)($_POST['office_tbl_id'] ?? 0);
    $newPassword  = trim($_POST['new_password'] ?? '');

    $errors = [];
    if ($userId <= 0)                $errors[] = 'Invalid user id.';
    if ($username === '')            $errors[] = 'Username is required.';
    if (mb_strlen($username) > 100)  $errors[] = 'Username must be 100 characters or fewer.';
    if (!in_array($userType, $USER_TYPES, true)) $errors[] = 'Invalid user type.';
    if ($officeType !== '' && !in_array($officeType, $OFFICE_TYPES, true)) {
        $errors[] = 'Invalid office type.';
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email format is invalid.';
    }
    if ($newPassword !== '' && mb_strlen($newPassword) < 6) {
        $errors[] = 'New password must be at least 6 characters.';
    }

    if (!$errors) {
        $uq = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? AND id <> ?");
        if ($uq) {
            mysqli_stmt_bind_param($uq, 'si', $username, $userId);
            mysqli_stmt_execute($uq);
            mysqli_stmt_store_result($uq);
            if (mysqli_stmt_num_rows($uq) > 0) {
                $errors[] = 'A user with this username already exists.';
            }
            mysqli_stmt_close($uq);
        }
    }

    if ($errors) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => implode(' ', $errors)];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    mysqli_begin_transaction($conn);
    try {
        $sql = "UPDATE users
                   SET username    = ?,
                       email       = ?,
                       full_name   = ?,
                       designation = ?,
                       mobile_no   = ?,
                       division    = ?,
                       office_name = ?,
                       user_type   = ?,
                       office_type = ?,
                       office_tbl_id = ?,
                       updated_at  = NOW()
                 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));

        mysqli_stmt_bind_param(
            $stmt,
            'sssssssssii',
            $username,
            $email,
            $fullName,
            $designation,
            $mobileNo,
            $division,
            $officeName,
            $userType,
            $officeType,
            $officeTblId,
            $userId
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Update failed: ' . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);

        if ($newPassword !== '') {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $ps = mysqli_prepare($conn, "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            if (!$ps) throw new Exception('Prepare (password) failed: ' . mysqli_error($conn));
            mysqli_stmt_bind_param($ps, 'si', $hash, $userId);
            if (!mysqli_stmt_execute($ps)) {
                throw new Exception('Password update failed: ' . mysqli_stmt_error($ps));
            }
            mysqli_stmt_close($ps);
        }

        mysqli_commit($conn);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => "User #{$userId} updated successfully."];
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Update failed: ' . $e->getMessage()];
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// HANDLE: DELETE USER
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_user' && isset($_POST['id'])) {
    $delId = (int)$_POST['id'];

    if ($delId > 0 && $delId === (int)($_SESSION['user_id'] ?? 0)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'You cannot delete your own account.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    if ($delId > 0) {
        mysqli_begin_transaction($conn);
        try {
            $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
            if (!$stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, 'i', $delId);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Delete failed: ' . mysqli_stmt_error($stmt));
            }
            if (mysqli_stmt_affected_rows($stmt) !== 1) {
                throw new Exception('User not found.');
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($conn);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "User #{$delId} deleted."];
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Delete failed: ' . $e->getMessage()];
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// -----------------------------------------------------------------
// SEARCH
// -----------------------------------------------------------------
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT u.*, o.office_name AS office_tbl_label
        FROM users u
        LEFT JOIN office_tbl o ON o.id = u.office_tbl_id
        WHERE 1";
$params = [];
$types  = '';

if ($search !== '') {
    $sql .= " AND (u.username LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?
                   OR u.designation LIKE ? OR u.division LIKE ? OR u.office_name LIKE ?)";
    $like = "%$search%";
    for ($i = 0; $i < 6; $i++) $params[] = $like;
    $types = str_repeat('s', 6);
}
$sql .= " ORDER BY u.id DESC";

$users = [];
if ($stmt = mysqli_prepare($conn, $sql)) {
    if ($types !== '') mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($r = mysqli_fetch_assoc($res)) $users[] = $r;
    mysqli_stmt_close($stmt);
}

$offices = [];
$resOff = mysqli_query($conn, "SELECT id, office_name, buffer_name FROM office_tbl ORDER BY office_name ASC");
if ($resOff) {
    while ($o = mysqli_fetch_assoc($resOff)) $offices[] = $o;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS - User Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { background:#f4f6fa; }
    .role-badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: capitalize; display:inline-block; }
    .role-user   { background: #e2e8f0; color: #2d3748; }
    .role-admin  { background: #bee3f8; color: #2a4365; }
    .role-sadmin { background: #fed7d7; color: #822727; }
    #form-tbl th, #form-tbl td { vertical-align: middle; }
    .card { border:0; border-radius:14px; box-shadow:0 4px 14px rgba(15,23,42,.06); }
    .table thead th { background:#f8fafc; font-size:11px; text-transform:uppercase; letter-spacing:.4px; color:#475569; white-space:nowrap; }
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

    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h3 class="mb-0">Welcome <b class="text-danger"><?= h($logged_in_user); ?></b></h3>
            <small class="text-muted text-uppercase">User Management</small>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="sadmin_dashboard.php" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Back</a>
            <!-- <a href="add_user.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a> -->
            <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= h($flash['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- SEARCH -->
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" class="d-flex" style="max-width:480px;">
                <input type="text" name="q" class="form-control me-2"
                       placeholder="Search username, name, email, division..."
                       value="<?= h($search); ?>">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                <?php if ($search !== ''): ?>
                    <a href="?" class="btn btn-outline-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- USERS TABLE -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
            <h5 class="mb-0"><i class="fa fa-users text-primary"></i> Users List</h5>
            <span class="badge bg-secondary"><?= count($users); ?> records</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle" id="form-tbl">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="text-center">User Type</th>
                        <th>Office Type</th>
                        <th>Office Name</th>
                        <th>Full Name</th>
                        <th>Designation</th>
                        <th>Mobile No</th>
                        <th>Division</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="12" class="text-center text-danger py-4"><b>No Record Found !!!</b></td></tr>
                <?php else: ?>
                    <?php foreach ($users as $row): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= (int)$row['id']; ?></td>
                            <td class="fw-semibold"><?= h($row['username']); ?></td>
                            <td><?= dash($row['email']); ?></td>
                            <td class="text-center">
                                <span class="role-badge role-<?= h($row['user_type']); ?>">
                                    <?= h($row['user_type']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($row['office_type'])): ?>
                                    <?= h(officeTypeLabel($row['office_type'])); ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= dash($row['office_name']); ?></td>
                            <td><?= dash($row['full_name']); ?></td>
                            <td><?= dash($row['designation']); ?></td>
                            <td><?= dash($row['mobile_no']); ?></td>
                            <td><?= dash($row['division']); ?></td>
                            <td class="text-muted small"><?= h($row['created_at'] ?? ''); ?></td>
                            <td class="text-center text-nowrap">
                                <button type="button"
                                        class="btn btn-warning btn-sm btn-sm-icon btn-edit-user"
                                        data-id="<?= (int)$row['id']; ?>"
                                        data-username="<?= h((string)$row['username']); ?>"
                                        data-email="<?= h((string)($row['email'] ?? '')); ?>"
                                        data-full_name="<?= h((string)($row['full_name'] ?? '')); ?>"
                                        data-designation="<?= h((string)($row['designation'] ?? '')); ?>"
                                        data-mobile_no="<?= h((string)($row['mobile_no'] ?? '')); ?>"
                                        data-division="<?= h((string)($row['division'] ?? '')); ?>"
                                        data-office_name="<?= h((string)($row['office_name'] ?? '')); ?>"
                                        data-user_type="<?= h((string)($row['user_type'] ?? 'user')); ?>"
                                        data-office_type="<?= h((string)($row['office_type'] ?? '')); ?>"
                                        data-office_tbl_id="<?= (int)($row['office_tbl_id'] ?? 0); ?>">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-sm-icon btn-delete-user"
                                        data-id="<?= (int)$row['id']; ?>"
                                        data-username="<?= h((string)$row['username']); ?>">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
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

<!-- ============ MODAL: EDIT USER ============ -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" action="" id="editUserForm">
        <input type="hidden" name="action" value="update_user">
        <input type="hidden" name="id" id="edit_user_id" value="">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editUserModalLabel"><i class="fa fa-edit"></i> Edit User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="username" id="edit_username" required maxlength="100">
            </div>
            <div class="col-md-6">
              <label class="form-label">User Type <span class="text-danger">*</span></label>
              <select class="form-select" name="user_type" id="edit_user_type" required>
                <?php foreach ($USER_TYPES as $ut): ?>
                  <option value="<?= h($ut); ?>"><?= h(ucfirst($ut)); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="full_name" id="edit_full_name" maxlength="150">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="edit_email" maxlength="150">
            </div>

            <div class="col-md-6">
              <label class="form-label">Designation</label>
              <input type="text" class="form-control" name="designation" id="edit_designation" maxlength="100">
            </div>
            <div class="col-md-6">
              <label class="form-label">Mobile No</label>
              <input type="text" class="form-control" name="mobile_no" id="edit_mobile_no" maxlength="20">
            </div>

            <div class="col-md-6">
              <label class="form-label">Division</label>
              <input type="text" class="form-control" name="division" id="edit_division" maxlength="100">
            </div>
            <div class="col-md-6">
              <label class="form-label">Office Name</label>
              <input type="text" class="form-control" name="office_name" id="edit_office_name" maxlength="150">
            </div>

            <div class="col-md-6">
              <label class="form-label">Office Type</label>
              <select class="form-select" name="office_type" id="edit_office_type">
                <option value="">-- Select Office Type --</option>
                <?php foreach ($OFFICE_TYPES as $t): ?>
                  <option value="<?= h($t); ?>"><?= h(officeTypeLabel($t)); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Linked Office (office_tbl)</label>
              <select class="form-select" name="office_tbl_id" id="edit_office_tbl_id">
                <option value="0">-- None --</option>
                <?php foreach ($offices as $o): ?>
                  <option value="<?= (int)$o['id']; ?>">
                    <?= h($o['office_name']); ?> (<?= h($o['buffer_name']); ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12"><hr></div>

            <div class="col-md-6">
              <label class="form-label">New Password</label>
              <input type="text" class="form-control" name="new_password" id="edit_new_password" autocomplete="new-password" minlength="6">
              <div class="form-text">Leave blank to keep the existing password.</div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------- EDIT ----------
    var editModalEl = document.getElementById('editUserModal');
    var editModal   = new bootstrap.Modal(editModalEl);

    document.querySelectorAll('.btn-edit-user').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('edit_user_id').value       = this.dataset.id || '';
            document.getElementById('edit_username').value      = this.dataset.username || '';
            document.getElementById('edit_email').value         = this.dataset.email || '';
            document.getElementById('edit_full_name').value     = this.dataset.full_name || '';
            document.getElementById('edit_designation').value   = this.dataset.designation || '';
            document.getElementById('edit_mobile_no').value     = this.dataset.mobile_no || '';
            document.getElementById('edit_division').value      = this.dataset.division || '';
            document.getElementById('edit_office_name').value   = this.dataset.office_name || '';
            document.getElementById('edit_user_type').value     = this.dataset.user_type || 'user';
            document.getElementById('edit_office_type').value   = this.dataset.office_type || '';
            document.getElementById('edit_office_tbl_id').value = this.dataset.office_tbl_id || '0';
            document.getElementById('edit_new_password').value  = '';

            editModal.show();
        });
    });

    // ---------- DELETE ----------
    document.querySelectorAll('.btn-delete-user').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var username = this.dataset.username;

            Swal.fire({
                title: 'Delete this user?',
                html: 'This will permanently remove <b>' + username + '</b>. This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                confirmButtonColor: '#e53e3e',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (!result.isConfirmed) return;

                var f = document.createElement('form');
                f.method = 'POST';
                f.action = '';
                f.style.display = 'none';

                var a1 = document.createElement('input');
                a1.type = 'hidden'; a1.name = 'action'; a1.value = 'delete_user';
                var a2 = document.createElement('input');
                a2.type = 'hidden'; a2.name = 'id'; a2.value = id;

                f.appendChild(a1);
                f.appendChild(a2);
                document.body.appendChild(f);
                f.submit();
            });
        });
    });

});
</script>

</body>
</html>