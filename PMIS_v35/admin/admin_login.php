<?php
// Start the session
//session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
include('db/db.php');

if(isset($_POST['admin_login'])){
	$password=$_POST['password'];
	$name=$_POST['name'];

$sql="select * from admin where admin_name='$name' AND admin_password='$password'";
$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

//$_SESSION['email']= $email;
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
<form action='admin_login.php' method='POST'>
</tr>
<td>Admin Password:</td>
<td><input type="password" name="password"></td>
</tr>
<tr>
<td>Admin Name:</td>
<td><input type="text" name="name"></td>
</tr>
<tr>
<td><input type="submit" name="admin_login" value="Login">
</td>
</tr>
</form>
</table>
</center>
</body>
</html>