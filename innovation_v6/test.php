<?php
session_start();

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['fullname']; ?></h2>

<p><strong>Employee ID:</strong> <?php echo $_SESSION['emp_id']; ?></p>
<p><strong>Designation:</strong> <?php echo $_SESSION['designation']; ?></p>
<p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
<p><strong>Mobile:</strong> <?php echo $_SESSION['mobile_no']; ?></p>
<p><strong>Place of Posting:</strong> <?php echo $_SESSION['place_of_posting']; ?></p>
<p><strong>Role:</strong> <?php echo $_SESSION['role']; ?></p>

<a href="logout.php">Logout</a>

</body>
</html>