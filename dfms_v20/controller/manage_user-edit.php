<?php 
session_name('dfms');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

require_once('../db/db.php');

?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>DFMS - Edit User</title>
</head>
<body>  
<div class="container mt-4">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="card shadow border border-success border-2">
        <div class="card-header">
          <h4 class="text-uppercase">Edit User
            <a href="manage_user.php" class="btn btn-danger float-end">BACK</a>
          </h4>
        </div>
        <div class="card-body">
          <?php
          if (isset($_GET['id'])) {
              $id = mysqli_real_escape_string($conn, $_GET['id']);
              $query = "SELECT * FROM users WHERE id='$id'";
              $query_run = mysqli_query($conn, $query);

              if (mysqli_num_rows($query_run) > 0) {
                  $user = mysqli_fetch_array($query_run);
                  ?>
                  <form action="manage_user-code.php" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id']; ?>">

                    <div class="mb-3">
                      <label for="username">User Name</label>
                      <input type="text" name="username" id="username" value="<?= $user['username']; ?>" class="form-control">                            
                    </div>    

                    <div class="mb-3">
                      <label for="email">Email</label>
                      <input type="text" name="email" id="email" value="<?= $user['email']; ?>" class="form-control">                            
                    </div>

                    <div class="mb-3">
                      <label for="password">Password</label>
                      <div class="input-group">
                        <input type="text" name="password" id="password" value="<?= $user['password']; ?>" class="form-control">
                        <button class="btn btn-outline-secondary" onclick="genPassword(event)">Generate</button>
                        <button class="btn btn-outline-secondary" onclick="copyPassword(event)">Copy</button>
                      </div>
                    </div>

                    <script type="text/javascript">
                      var passwordInput = document.getElementById("password");
                      function genPassword(event) {
                          event.preventDefault();
                          var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                          var passwordLength = 8;
                          var newPassword = "";
                          for (var i = 0; i < passwordLength; i++) {
                              var randomNumber = Math.floor(Math.random() * chars.length);
                              newPassword += chars.substring(randomNumber, randomNumber + 1);
                          }
                          passwordInput.value = newPassword;
                      }

                      function copyPassword(event) {
                          event.preventDefault();
                          passwordInput.select();
                          passwordInput.setSelectionRange(0, 999);
                          document.execCommand("copy");
                      }
                    </script>               

                    <div class="form-group mb-3">
                      <label>User Type</label>
                      <select class="form-control" id="user_type" name="user_type">                                        
                        <option value="user" <?= $user['user_type'] == 'user' ? 'selected' : ''; ?>>User</option>  
                        <option value="admin" <?= $user['user_type'] == 'admin' ? 'selected' : ''; ?>>Admin</option>   
                      </select>
                    </div>

                    <div class="form-group mb-3">
                      <label>Product Type</label>
                      <select class="form-control" id="product_type" name="product_type">                                        
                        <option value="urea" <?= $user['product_type'] == 'urea' ? 'selected' : ''; ?>>Urea</option>  
                        <option value="non-urea" <?= $user['product_type'] == 'non-urea' ? 'selected' : ''; ?>>Non-Urea</option>   
                      </select>                   
                    </div>

                    <div class="form-group mb-3">
                      <label>User Status</label>
                      <select class="form-control" name="user_status">
                        <option value="active" <?= $user['user_status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= $user['user_status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                      </select>
                    </div>                 

                    <div class="mb-3">
                      <label for="office_title">Office Title</label>
                      <input type="text" name="office_title" id="office_title" value="<?= $user['office_title']; ?>" class="form-control">                            
                    </div>

                    <div class="mb-3">
                      <label for="product">Product</label>
                      <input type="text" name="product" id="product" value="<?= $user['product']; ?>" class="form-control">                            
                    </div>

                    <div class="mb-3">
                      <label for="created_at">Created At</label>
                      <input type="text" name="created_at" id="created_at" value="<?= $user['created_at']; ?>" class="form-control" readonly>                            
                    </div>

                    <div class="form-group mb-3">
                      <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>                
                  </form>
                  <?php
              } else {
                  echo "<h4>No Such Id Found</h4>";
              }
          } else {
              echo "<h4>Invalid Request</h4>";
          }
          ?>
        </div>
      </div>
    </div> 
    <div class="col-md-3"></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
