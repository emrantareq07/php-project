<!-- update_bank_info.php -->
<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
?>
<?php

include('db/db.php');

  
 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	$emp_id=$_SESSION['emp_id'];
	 
	 $bank_name=$_POST['bank_name'];
	 $branch_name=$_POST['branch_name'];
	 $branch_add=$_POST['branch_add'];
	 $acc_name=$_POST['acc_name'];
	 $acc_no=$_POST['acc_no'];
	 $swift_code=$_POST['swift_code'];
	 $routing_no=$_POST['routing_no'];
		 
	 $updated = date('Y-m-d H:i:s');

	$sql="UPDATE emp_bank_info SET bank_name = '$bank_name', branch_name = '$branch_name', 
	branch_add = '$branch_add',acc_name = '$acc_name', acc_no = '$acc_no', 
	swift_code = '$swift_code',routing_no = '$routing_no',updated = '$updated' where emp_id='$emp_id'";
		//header("Location: viewprofile.php");
		//}
//}
	if(mysqli_query($conn,$sql)){
		date_default_timezone_set("Asia/Dhaka");
		$today = date("Y-m-d H:i:s");

		// Update job_desc table
		$sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
		$result=mysqli_query($conn,$sql1);
			//header("Location: view_profile_details.php");
			header("location:view_bank_info.php?submitted=successfully");
	}
	else
		print mysqli_error();
	//echo 'invalid';

//}
	}
  ?>