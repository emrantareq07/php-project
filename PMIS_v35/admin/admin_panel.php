<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// Set session variables
/* $_SESSION["favcolor"] = "green";
$_SESSION["favanimal"] = "cat";
echo "Session variables are set."; */

include('db/db.php');

if(isset($_POST['submit'])){
	$password=$_POST['password'];
	$email=$_POST['email'];

$check_user="select * from admin where admin_password='$password' AND admin_email='$email'";
$result=mysqli_query($conn,$check_user);

if(mysqli_num_rows($result)>0){

$_SESSION['email']= $email;
//header('location:welcome.php');
echo" <script> window.open('view_users.php','_self')</script>";
}
 else{
 echo" Email and password did not match";
 }
 }
  mysqli_close($conn);
?>
<center>
<h1>Admin Login Form</h1>
<table border="1" cellspacing="3" cellpadding="3">
<tr>
<form action='' method='POST'>
</tr>
<td>Admin Password:</td>
<td><input type="password" name="password"></td>
</tr>
<tr>
<td>Admin Name:</td>
<td><input type="text" name="email"></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="Login">
</td>
</tr>
</form>
</table>
</center>
</body>
</html>