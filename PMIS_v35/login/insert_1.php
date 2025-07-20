<?php
include('db.php');

// if (isset($_POST['emp_id_check'])) {
	// //if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['emp_id_check']) && $_POST['emp_id_check'] == 1) {
	
  	// $emp_id = mysql_real_escape_string($_POST['emp_id']);
  	// $sql = "SELECT emp_id FROM users WHERE emp_id='$emp_id'";
  	// $results = mysql_query($sql,$con);
  	// if (mysql_num_rows($results) > 0) {
  	  // echo "taken";	
  	// }else{
  	  // echo 'not_taken';
  	// }
  // exit();
  // }


if(isset($_POST['submit'])){
$emp_id=$_POST['emp_id'];
// $name=mysql_real_escape_string($_POST['name']); 
$name = mysqli_real_escape_string($conn, $_POST['name']);
$designation=$_POST['designation']; 
$division=$_POST['department']; 
$section=$_POST['section'];
$place_of_posting=$_POST['place_of_posting'];
$password = mysqli_real_escape_string($conn, $_POST['psw']);
// $password=mysql_real_escape_string($_POST['psw']);
$password=md5($password);

//$image=$_POST['image'];

// if($name==''){
// echo"<script> alert('Plz enter Your Name') </script>";
// exit();
// }
// if($password==''){
// echo"Plz enter Your Password.</br>";
// exit();
// }
// if($email==''){
// echo"Plz enter Your Email";
// exit();
// }
// $check_email="select * from users where email='$email'";
// $row = mysql_query($check_email);
// if(mysql_num_rows($row)>0){
// echo"<script>alert('Email $email already exists ,plz enter another email')</script>";
// exit();
// }

// $check_emap_id="select * from users where emp_id='$emp_id'";
// $row = mysql_query($check_emap_id);
// if(mysql_num_rows($row)>0){
// echo"<script>alert('Employee ID $emp_id already exists ,plz enter another Employee ID')</script>";
// exit();
// }
$dp = "image/".$_FILES['image']["name"];
 if(move_uploaded_file($_FILES['image']["tmp_name"],$dp)) {
 $sql="INSERT INTO users2(emp_id,name,designation,division,section,password,image,place_of_posting) 
 VALUES('{$emp_id}','{$name}','{$designation}','{$division}','{$section}','{$password}','{$dp}')";
 $result=mysqli_query($conn,$sql);
 echo"<script> window.open('welcome.php','_self')</script>";
 }
 else
	 {
	echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'> × </button>
				Whoops! some error encountered. Please try again.";
	}
// if (!mysql_query($sql))  {  
		 		// die('Error: ' . mysql_error());  
				// }
				
			//echo "Success!";
			//echo"<script> window.open('welcome.php','_self')</script>";

}
?>