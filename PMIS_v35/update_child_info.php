<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];


include('db/db.php');

  
 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id']))
{
	$emp_id=$_SESSION['emp_id'];

 	$name=$_POST['name'];
	$dob=$_POST['dob'];
	$gender=$_POST['gender'];

	$sql="UPDATE childs_info SET name = '$name', dob = '$dob',gender = '$gender' where emp_id='$emp_id'";
		//header("Location: viewprofile.php");
		//}
//}
	if(mysqli_query($conn,$sql))
	{
		//echo "Update successfully";
		header("location:edit_child_info.php?submitted=successfully");
			
	}
	else
		print mysqli_error();
	//echo 'invalid';

//}
	}
  ?>