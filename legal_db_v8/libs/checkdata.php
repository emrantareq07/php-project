<?php
// include Function  file
include_once('function.php');
// Object creation
$u_p_obj=new DB_con();

if(isset($_POST['username']))
{
 $uname= $_POST["username"];		

	// Calling function for username
	$sql=$u_p_obj->usernameavailblty($uname);
	$num=mysqli_num_rows($sql);

 if($num>0)
 {
  echo "<span class='text-danger'>User Name Already Exist</span>";
 }
 else
 {
  echo "Available";
 }
 exit();
}

if(isset($_POST['email']))
{
 $email= $_POST["email"];
    // Calling function for email
	$sql2=$u_p_obj->uemailavailblty($email);

	$num2=mysqli_num_rows($sql2);

 if($num2>0)
 {
  echo "<span class='text-danger'>Email Already Exist</span>";
 }
 else
 {
  echo "Available";
 }
 exit();
}
?>