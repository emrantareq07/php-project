<?php 
// Secure session initialization
session_name('dfms');
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  
    exit();
}

// Only sadmin can access this page
if ($_SESSION['user_type'] !== 'sadmin') {
    header("Location: access_denied.php");
    exit();
}

require_once('../db/db.php');

// Escape session variables for output
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES);
$user_type = htmlspecialchars($_SESSION['user_type'], ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Users - DFMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Digital Fertilizer Monitoring System (DFMS), BCIC</a>
  </div>
</nav> 

<div class="container p-3 my-3 border rounded">
  <div class="row">
    <div class="col-12">
      <h1 class="text-dark text-center">Manage Users</h1>
      <h4 class="text-center">Welcome, <span class="text-danger fw-bold"><?= $username ?></span></h4>
    </div>
  </div>

  <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['type']; ?> alert-dismissible fade show" role="alert">
      <?= $_SESSION['msg']['text']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <!-- Action Buttons -->
  <div class="row mb-3">    
    <div class="col-md-6">
      <h3 class="text-uppercase">Users List</h3>
    </div>
    <div class="col-md-6 text-md-end">
      <div class="btn-group" role="group">
        <a href="urea_form.php" class="btn btn-primary">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <a href="user_inactive.php" 
           class="btn btn-warning" 
           onclick="return confirm('Deactivate ALL users (except superadmin)?');">
          <i class="fas fa-user-slash me-1"></i> All Inactive
        </a>
        <a href="user_active.php" 
           class="btn btn-success" 
           onclick="return confirm('Activate ALL users (except superadmin)?');">
          <i class="fas fa-user-check me-1"></i> All Active
        </a>
        <a href="add_user.php" class="btn btn-info">
          <i class="fas fa-user-plus me-1"></i> Add User
        </a>
        <a href="logout.php" class="btn btn-danger">
          <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
      </div>
    </div>
  </div>

  <hr>

  <!-- Users Table -->
  <div class="table-responsive">
    <table class="table table-hover table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Email</th>
          <th>User Type</th>
          <th>Product Type</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $query = "SELECT * FROM users ORDER BY id DESC";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $id = (int)$row['id'];
              $status = $row['user_status'];
              $type = $row['user_type'];

              echo '<tr>';
              echo '<td>' . $id . '</td>';
              echo '<td>' . htmlspecialchars($row['username']) . '</td>';
              echo '<td>' . htmlspecialchars($row['email']) . '</td>';
              echo '<td>' . htmlspecialchars($type) . '</td>';
              echo '<td>' . htmlspecialchars($row['product_type']) . '</td>';

              // Status badge
              echo '<td>';
              echo $status === 'active' 
                  ? '<span class="badge bg-success">Active</span>' 
                  : '<span class="badge bg-danger">Inactive</span>';
              echo '</td>';

              // Action buttons
              echo '<td class="text-center"><div class="btn-group">';

              // Edit
              echo '<a href="manage_user-edit.php?id=' . $id . '" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit"></i> Edit
                    </a>';

              // Delete
              if ($type !== 'superadmin') {
                  echo '<a href="manage_user-code.php?id=' . $id . '&action=delete" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm(\'Delete this user?\')">
                          <i class="fas fa-trash-alt"></i> Delete
                        </a>';
              } else {
                  echo '<button class="btn btn-secondary btn-sm" disabled>Protected</button>';
              }

              // Activate/Deactivate
              if ($type !== 'superadmin') {
                  if ($status === 'active') {
                      echo '<a href="user_inactive.php?id=' . $id . '" class="btn btn-warning btn-sm">
                              <i class="fas fa-user-slash"></i> Deactivate
                            </a>';
                  } else {
                      echo '<a href="user_active.php?id=' . $id . '" class="btn btn-success btn-sm">
                              <i class="fas fa-user-check"></i> Activate
                            </a>';
                  }
              }

              echo '</div></td>';
              echo '</tr>';
          }
      } else {
          echo '<tr><td colspan="7" class="text-center text-danger fw-bold">No users found</td></tr>';
      }
      ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
