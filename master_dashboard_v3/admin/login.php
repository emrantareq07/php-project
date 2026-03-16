<?php
session_name('project_master_dashboard');
session_start();
include "../db.php";

if($_POST){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $res = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password' AND role='admin'");
    if($res->num_rows){
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit;
    }else{
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
<h2 class="text-center mb-4">Admin Login</h2>
<form method="POST" class="mx-auto" style="max-width:400px;">
<?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
<input class="form-control mb-2" name="username" placeholder="Username" required>
<input class="form-control mb-2" type="password" name="password" placeholder="Password" required>
<button class="btn btn-primary w-100">Login</button>
</form>
</div>
</body>
</html>