<?php
session_start();

// Check if the user is already logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

require_once('../db/db.php');

$logged_in_user = $_SESSION['username'];
$logged_in_type = $_SESSION['user_type'];

// -----------------------------------------------------------------
// OPTIONAL: restrict this page to admin / sadmin only.
// Uncomment if regular "user" accounts should never see this page.
// -----------------------------------------------------------------
// if (!in_array($logged_in_type, ['admin', 'sadmin'], true)) {
//     header("Location: dashboard.php");
//     exit();
// }

// -----------------------------------------------------------------
// CONFIG — adjust to match your office_tbl schema
// -----------------------------------------------------------------
$OFFICE_TBL       = 'office_tbl';
$OFFICE_VALUE_COL = 'id';
$OFFICE_LABEL_COL = 'office_name';

// -----------------------------------------------------------------
// SEARCH (optional simple filter)
// -----------------------------------------------------------------
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// -----------------------------------------------------------------
// FETCH USERS  (office_type resolved to a label via office_tbl)
// -----------------------------------------------------------------
$sql = "SELECT u.*, o.`$OFFICE_LABEL_COL` AS office_type_label
        FROM users u
        LEFT JOIN `$OFFICE_TBL` o ON o.`$OFFICE_VALUE_COL` = u.office_type
        WHERE 1";

$params = [];
$types  = '';

if ($search !== '') {
    $sql .= " AND (u.username LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?
                   OR u.designation LIKE ? OR u.division LIKE ? OR u.office_name LIKE ?)";
    $like = "%$search%";
    for ($i = 0; $i < 6; $i++) {
        $params[] = $like;
    }
    $types = str_repeat('s', 6);
}

$sql .= " ORDER BY u.id DESC";

$stmt = mysqli_prepare($conn, $sql);
if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$query_run = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC SFMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .role-badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
    .role-user   { background: #e2e8f0; color: #2d3748; }
    .role-admin  { background: #bee3f8; color: #2a4365; }
    .role-sadmin { background: #fed7d7; color: #822727; }
    #form-tbl th, #form-tbl td { vertical-align: middle; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid text-center">
    <a class="navbar-brand" href="#">Smart Fertilizer Monitoring System (SFMS), BCIC.</a>
  </div>
</nav>

<div class="container-fluid p-1 my-1 border rounded">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="text-dark text-center">Welcome <b class="text-danger"><?= htmlspecialchars($logged_in_user); ?></b> Dashboard</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6"><h3 class="text-center text-uppercase">Users List</h3></div>
        <div class="col-sm-6">
            <span class="float-end">
                <a href="dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Previous Page</a>
                <a href="add_user.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</a>
                <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out"></i></a>
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-12"><hr></div>

        <div class="col-12 mb-2">
            <form action="" method="GET" class="d-flex" style="max-width:420px;">
                <input type="text" name="q" class="form-control me-2" placeholder="Search username, name, email, division..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                <?php if ($search !== ''): ?>
                    <a href="?" class="btn btn-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="col-12">
            <div class="table-responsive">
            <table class='table table-hover table-striped table-bordered align-middle' id="form-tbl">
                <thead>
                    <tr>
                        <th class="text-center p-1">#</th>
                        <th class="text-center p-1">Username</th>
                        <th class="text-center p-1">Full Name</th>
                        <th class="text-center p-1">Email</th>
                        <th class="text-center p-1">User Type</th>
                        <th class="text-center p-1">Office Type</th>
                        <th class="text-center p-1">Designation</th>
                        <th class="text-center p-1">Mobile No</th>
                        <th class="text-center p-1">Division</th>
                        <th class="text-center p-1">Office Name</th>
                        <th class="text-center p-1">Created At</th>
                        <th class="text-center p-1">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($query_run && mysqli_num_rows($query_run) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query_run)): ?>
                        <tr>
                            <td class="text-center"><?= (int)$row['id']; ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['full_name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td class="text-center">
                                <span class="role-badge role-<?= htmlspecialchars($row['user_type']); ?>">
                                    <?= htmlspecialchars($row['user_type']); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['office_type_label'] ?? $row['office_type']); ?></td>
                            <td><?= htmlspecialchars($row['designation']); ?></td>
                            <td><?= htmlspecialchars($row['mobile_no']); ?></td>
                            <td><?= htmlspecialchars($row['division']); ?></td>
                            <td><?= htmlspecialchars($row['office_name']); ?></td>
                            <td><?= htmlspecialchars($row['created_at']); ?></td>
                            <td class="text-center text-nowrap">
                                <a href="manage_user-edit.php?id=<?= (int)$row['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-delete-user"
                                        data-id="<?= (int)$row['id']; ?>"
                                        data-username="<?= htmlspecialchars($row['username']); ?>">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="12" class="text-center"><h5 class="text-danger"><b>No Record Found !!!</b></h5></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-user').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = btn.getAttribute('data-id');
            const username = btn.getAttribute('data-username');

            Swal.fire({
                title: 'Delete this user?',
                html: 'This will permanently remove <b>' + username + '</b>. This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                confirmButtonColor: '#e53e3e',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.location.href = 'manage_user-code.php?id=' + encodeURIComponent(id) + '&action=delete';
                }
            });
        });
    });
});
</script>

</body>
</html>
<?php
mysqli_stmt_close($stmt);
?>