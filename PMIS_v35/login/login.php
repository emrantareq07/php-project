<?php
session_start();

// Check if the user is already logged in, redirect to the dashboard
if (isset($_SESSION['emp_id'])) {
  header("Location: user_dashboard.php");
  exit();
}

include('db.php');

if (isset($_POST['login'])) {
  $password = $_POST['password'];
  $hashedPassword = md5($password);
  //$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $emp_id = $_POST['emp_id'];

  $sql = "SELECT * FROM users WHERE emp_id='$emp_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  if ($row !== null) {
    $status = $row['status'];
    $storedHashedPassword = $row['password'];

    if ($hashedPassword === $storedHashedPassword) {
      if ($status == 'approved') {
        $_SESSION['status'] = $status;
        $_SESSION['emp_id'] = $row['emp_id'];
        $_SESSION['password'] = $storedHashedPassword;
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
          // Redirect to the admin dashboard
          header("Location: admin_dashboard.php");
          exit();
        } else {
          // Redirect to the user dashboard
          header("Location: super-admin_dashboard.php");
          exit();
        }
      } elseif ($status == 'pending') {
        echo '<script type="text/javascript">';
        echo 'alert("Your Account is still pending for approval!");';
        echo 'window.location.href="index.php"';
        echo '</script>';
      }
    } else {
      header("location: index.php?submitted=wrong");
    }
  } else {
    header("location: index.php?submitted=wrong");
  }
}
?>
