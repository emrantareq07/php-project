<?php
// manage_users.php
session_start();
require_once "db.php"; // include your db connection (must use MySQLi)

// Delete User
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php?msg=deleted");
    exit;
}

// Fetch Users
$result = $conn->query("SELECT * FROM users_tbl ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h3 class="mb-3">Manage Users</h3>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
  <?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted"): ?>
    <div class="alert alert-success">User deleted successfully.</div>
  <?php endif; ?>
  
  <table id="userTable" class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Emp ID</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Division</th>
        <th>Section</th>
        <th>Place of Posting</th>
        <th>Office</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id']; ?></td>
        <td><?= htmlspecialchars($row['emp_id']); ?></td>
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['designation']); ?></td>
        <td><?= htmlspecialchars($row['division']); ?></td>
        <td><?= htmlspecialchars($row['section']); ?></td>
        <td><?= htmlspecialchars($row['place_of_posting']); ?></td>
        <td><?= htmlspecialchars($row['office']); ?></td>
        <td><?= htmlspecialchars($row['mobile_no']); ?></td>
        <td><?= htmlspecialchars($row['email_id']); ?></td>
        <td><?= htmlspecialchars($row['role']); ?></td>
        <td>
        <?php if (strtolower($row['status']) == "active"): ?>
            <span class="badge bg-success">Active</span>
        <?php elseif (strtolower($row['status']) == "inactive"): ?>
            <span class="badge bg-danger">Inactive</span>
        <?php elseif (strtolower($row['status']) == "pending"): ?>
            <span class="badge bg-warning">Pending</span>
        <?php else: ?>
            <span class="badge bg-secondary">Unknown</span>
        <?php endif; ?>
    </td>

        <td>
             <a href="download_user.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info">download</a>
          <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info">Edit</a>
          <a href="manage_users.php?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
  $('#userTable').DataTable();
});
</script>

</body>
</html>
