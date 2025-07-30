<?php 
// Secure session initialization
session_name('dfms');
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Should redirect to login, not dashboard
    exit();
}

// Strict access control - only 'sadmin' can proceed
if ($_SESSION['user_type'] !== 'sadmin') {
    header("Location: access_denied.php");  // Create this page to show permission error
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
  <title>Admin Dashboard - DFMS</title>
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
    <a class="navbar-brand" href="#">Digital Fertilizer Monitoring System (DFMS), BCIC.</a>
  </div>
</nav> 

<div class="container p-3 my-3 border rounded">
  <div class="row">
    <div class="col-12">
      <h1 class="text-dark text-center">Welcome <span class="text-danger fw-bold"><?= $username ?></span></h1>
    </div>
  </div>
  
  <!-- Action Buttons -->
  <div class="row mb-3">    
    <div class="col-md-6">
      <h3 class="text-center text-uppercase">Users List</h3>
    </div>
    <div class="col-md-6 text-md-end">
      <div class="btn-group" role="group">
        <a href="urea_form.php" class="btn btn-primary">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <a href="add_user.php" class="btn btn-success">
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
    <table class="table table-hover table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Email</th>
          <th>User Type</th>
          <th>Product Type</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT * FROM users ORDER BY id DESC";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '
                <tr>
                  <td>'.htmlspecialchars($row['id']).'</td>
                  <td>'.htmlspecialchars($row['username']).'</td>
                  <td>'.htmlspecialchars($row['email']).'</td>
                  <td>'.htmlspecialchars($row['user_type']).'</td>
                  <td>'.htmlspecialchars($row['product_type']).'</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="manage_user-edit.php?id='.(int)$row['id'].'" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <a href="manage_user-code.php?id='.(int)$row['id'].'&action=delete" 
                         class="btn btn-danger btn-sm" 
                         onclick="return confirm(\'Are you sure you want to delete this user?\')">
                        <i class="fas fa-trash-alt"></i> Delete
                      </a>
                    </div>
                  </td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="6" class="text-center text-danger fw-bold">No users found</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Optional: Add confirmation for delete actions -->
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        if (!confirm('Are you sure you want to delete this user?')) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>