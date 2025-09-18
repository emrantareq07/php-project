<?php
// edit_user.php
session_start();
require_once "db.php"; // MySQLi connection

// Get user ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Redirect if user not found
if (!$user) {
    header("Location: manage_users.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id          = $_POST['emp_id'];
    $name            = $_POST['name'];
    $designation     = $_POST['designation'];
    $division        = $_POST['division'];
    $section         = $_POST['section'];
    $place_of_posting= $_POST['place_of_posting'];
    $office          = $_POST['office'];
    $mobile_no       = $_POST['mobile_no'];
    $email_id        = $_POST['email_id'];
    $role            = $_POST['role'];
    $status          = $_POST['status'];

    $stmt = $conn->prepare("UPDATE users_tbl SET emp_id=?, name=?, designation=?, division=?, section=?, place_of_posting=?, office=?, mobile_no=?, email_id=?, role=?, status=? WHERE id=?");
    $stmt->bind_param("sssssssssssi", $emp_id, $name, $designation, $division, $section, $place_of_posting, $office, $mobile_no, $email_id, $role, $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_users.php?msg=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h3>Edit User</h3>
  <form method="post">
    <div class="row mb-3">
      <div class="col">
        <label>Emp ID</label>
        <input type="text" name="emp_id" class="form-control" value="<?= htmlspecialchars($user['emp_id']); ?>" required>
      </div>
      <div class="col">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label>Designation</label>
        <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($user['designation']); ?>">
      </div>
      <div class="col">
        <label>Division</label>
        <input type="text" name="division" class="form-control" value="<?= htmlspecialchars($user['division']); ?>">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label>Section</label>
        <input type="text" name="section" class="form-control" value="<?= htmlspecialchars($user['section']); ?>">
      </div>
      <div class="col">
        <label>Place of Posting</label>
        <input type="text" name="place_of_posting" class="form-control" value="<?= htmlspecialchars($user['place_of_posting']); ?>">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label>Office</label>
        <input type="text" name="office" class="form-control" value="<?= htmlspecialchars($user['office']); ?>">
      </div>
      <div class="col">
        <label>Mobile</label>
        <input type="text" name="mobile_no" class="form-control" value="<?= htmlspecialchars($user['mobile_no']); ?>">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label>Email</label>
        <input type="email" name="email_id" class="form-control" value="<?= htmlspecialchars($user['email_id']); ?>">
      </div>
      <div class="col">
        <label>Role</label>
        <select name="role" class="form-select">
          <option value="admin" <?= $user['role']=="admin"?"selected":""; ?>>Admin</option>
          <option value="user" <?= $user['role']=="user"?"selected":""; ?>>User</option>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="active" <?= $user['status']=="active"?"selected":""; ?>>Active</option>
          <option value="pending" <?= $user['status']=="pending"?"selected":""; ?>>Pending</option>
          <option value="inactive" <?= $user['status']=="inactive"?"selected":""; ?>>Inactive</option>
        </select>
      </div>
      <div class="col">
        <label>Batch</label>
        <select name="batch" class="form-select">
          <option value="1st" <?= $user['batch']=="1st"?"selected":""; ?>>1st</option>
          <option value="2nd" <?= $user['batch']=="2nd"?"selected":""; ?>>2nd</option>
          <option value="3rd" <?= $user['batch']=="3rd"?"selected":""; ?>>3rd</option>
        </select>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
    <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
