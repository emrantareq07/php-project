<?php
session_name('bcic_e-library');
session_start();
include('includes/config.php');

if (isset($_POST['save_user'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $query = "INSERT INTO admin (username, password, role) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
    mysqli_stmt_execute($stmt);

    $_SESSION['message'] = "User added successfully!";
    header("Location: manage_user.php");
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
</head>
<body>
<div class="container my-5 ">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 rounded shadow-lg p-4"> <!-- Simple Bootstrap form -->
        <form method="POST" class="container mt-4">
            <h2 class="fw-bold text-uppercase text-center">Create User</h2>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" name="save_user" class="btn btn-primary">Save User</button>
        </form>
        </div>
        <div class="col-sm-4 my-5"> <a href="manage_user.php" class="btn btn-success"><i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>           
        </div>
   </div>
</div>

</body>
</html>