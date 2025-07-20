<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:../index.php');
    exit;
}

if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT * FROM admin WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $input_password = $_POST['password'];
    $role = $_POST['role'];

    // Fetch the current password from DB
    $stmt = mysqli_prepare($conn, "SELECT password FROM admin WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $current_password = $user['password'];

    if ($input_password === $current_password) {
        // Password not changed, don't update it
        $query = "UPDATE admin SET username=?, role=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $user_id);
    } else {
        // Password changed, hash and update it
        $hashed_password = md5($input_password);
        $query = "UPDATE admin SET username=?, password=?, role=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $hashed_password, $role, $user_id);
    }

    mysqli_stmt_execute($stmt);

    $_SESSION['message'] = "User updated successfully!";
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
            <!-- Edit Form -->
            <form method="POST" class="container mt-4">
                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= $user['username']; ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Username</label>
                    <input type="password" name="password" value="<?= $user['password']; ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                    </select>
                </div>
                <button type="submit" name="update_user" class="btn btn-success">Update User</button>
            </form>
            </div>
        <div class="col-sm-4 my-5"> <a href="manage_user.php" class="btn btn-success"><i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>            
        </div>
   </div>
</div>

</body>
</html>
