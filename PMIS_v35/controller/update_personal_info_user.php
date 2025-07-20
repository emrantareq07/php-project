<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('../db/db.php');
  
if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
$emp_id=$_SESSION['emp_id'];
	
// $name=mysql_real_escape_string($_POST['name']); 
// $designation=$_POST['designation']; $department=$_POST['department']; $section=$_POST['section'];
// $password=$_POST['password'];
$name=$_POST['name']; 
$fathersName=$_POST['fathersName']; $mothersName=$_POST['mothersName']; 
$nationality=$_POST['nationality']; 
$gender=$_POST['gender'];
$blood_group=$_POST['blood_group'];
$religion=$_POST['religion']; 
$nid=$_POST['nid']; 
$quota=$_POST['quota']; 
$mobile_no=$_POST['mobile_no'];

$maritial_status=$_POST['maritial_status'];
$spouse_name=$_POST['spouse_name'];
$spouse_home_dist=$_POST['spouse_home_dist'];
$spouse_occupation=$_POST['spouse_occupation'];
$spo_present_add=$_POST['spo_present_add'];
$spo_parmanent_add=$_POST['spo_parmanent_add'];

$email=$_POST['email'];
$home_dist=$_POST['home_dist'];
$present_add=$_POST['present_add']; $permanent_add=$_POST['permanent_add']; 

$name_bn=$_POST['name_bn']; 

		//$dp = "signature/".$_FILES['signature']["name"];nationality
		//move_uploaded_file($_FILES['signature']["tmp_name"],$dp);
$sql="UPDATE basic_info SET name = '$name',name_bn = '$name_bn',fathersName = '$fathersName', mothersName = '$mothersName', 
	 gender = '$gender', blood_group = '$blood_group', religion = '$religion', nationality = '$nationality',
	nid = '$nid', quota = '$quota', mobile_no = '$mobile_no', email = '$email', home_dist= '$home_dist',
	maritial_status = '$maritial_status', spouse_name= '$spouse_name',spouse_home_dist= '$spouse_home_dist',spouse_occupation= '$spouse_occupation',spo_present_add= '$spo_present_add',spo_parmanent_add= '$spo_parmanent_add',
	present_add = '$present_add', permanent_add = '$permanent_add' where emp_id='$emp_id'";

if(mysqli_query($conn,$sql)){
	//echo "Update successfully";
	date_default_timezone_set("Asia/Dhaka");
	$today = date("Y-m-d H:i:s");

	// Update job_desc table
	$sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
	$result=mysqli_query($conn,$sql1);

	header("location:edit_personal_info.php?submitted=successfully");
		
}
else
	print mysqli_error();

}
  ?>