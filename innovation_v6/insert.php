<?php
include('db/db.php');

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
 $fiscal_year=$_POST['fiscal_year'];
 $title_of_invention=$_POST['title_of_invention'];

 $inventors_name=$_POST['inventors_name'];
 $inventors_designation=$_POST['inventors_designation'];
 $inventors_emp_id=$_POST['inventors_emp_id'];
 $proposed_workplace=$_POST['proposed_workplace'];
 
 $des_of_invention=$_POST['des_of_invention'];
 $imple_status=$_POST['imple_status'];
 $replicate_eligibility=$_POST['replicate_eligibility'];

	// $fathersName=$row['fathersName'];
	// $mothersName=$row['mothersName'];
	// $birth_date=$row['birth_date'];
	// $gender=$row['gender'];
	// $blood_group=$row['blood_group'];
	// $home_dist=$row['home_dist'];
	// $present_add=$row['present_add'];
	// $permanent_add=$row['permanent_add'];
	
	// $religious=explode(",",$row['religious']);	
	
// $emp_id=$_POST['emp_id'];
// $name=mysql_real_escape_string($_POST['name']); $designation=$_POST['designation']; 
// $department=$_POST['department']; $section=$_POST['section'];
// $password=mysql_real_escape_string($_POST['psw']);
// $password=md5($password);

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

 $sql="INSERT INTO innovation(fiscal_year,title_of_invention,inventors_name,inventors_designation,
 inventors_emp_id,proposed_workplace,des_of_invention, imple_status,replicate_eligibility) 
 VALUES('{$fiscal_year}','{$title_of_invention}','{$inventors_name}','{$inventors_designation}',
 '{$inventors_emp_id}','{$proposed_workplace}','{$des_of_invention}','{$imple_status}','{$replicate_eligibility}')";
 //$result=mysqli_query($conn,$sql);
 //echo"<script> window.open('../welcome.php','_self')</script>";
 
	//echo "<div class='alert alert-danger alert-dismissible'>
				//<button type='button' class='close' data-dismiss='alert'> × </button>
				//Whoops! some error encountered. Please try again.";

	if(mysqli_query($conn,$sql))
	{
			header("Location: home.php");
	}
	else
		print mysqli_error();
}
?>