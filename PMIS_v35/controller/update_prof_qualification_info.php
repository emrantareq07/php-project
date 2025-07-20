<!-- update_prof_qualification_info.php -->
<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
?>
<?php

include('db/db.php');

  
 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	$emp_id=$_SESSION['emp_id'];
	 
	 $qualification=$_POST['qualification'];
	 // $ground_or_subject=$_POST['ground_or_subject'];
	 
	 // $nature=$_POST['nature'];
	 
	$sql="UPDATE prof_quali_info SET qualification = '$qualification' where emp_id='$emp_id'";
		//header("Location: viewprofile.php");
		//}
//}
	if(mysqli_query($conn,$sql)){
			//header("Location: view_profile_details.php");
		date_default_timezone_set("Asia/Dhaka");
		$today = date("Y-m-d H:i:s");

		// Update job_desc table
		$sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
		$result=mysqli_query($conn,$sql1);

		header("location:view_prof_qualification_info.php?submitted=successfully");
	}
	else
		print mysqli_error();
	//echo 'invalid';

//}
	}
  ?>