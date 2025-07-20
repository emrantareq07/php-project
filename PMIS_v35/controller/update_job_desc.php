<?php
// Start the session
session_start();
include('../db/db.php');
  
if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	$emp_id=$_SESSION['emp_id'];
	// $cadrewise_snr_no=$_POST['cadrewise_snr_no'];
$dob=$_POST['dob']; 
$doj=$_POST['doj'];
$doc=$_POST['doc'];
//$dolp=$_POST['dolp'];

$police_verification=$_POST['police_verification'];
//$dob2 = str_replace('/', '-', $dob);
//$prl = date('dd-mm-yyyy', strtotime('-1 day', strtotime($dob. '+59 years')));
$prl = date('Y-m-d', strtotime('-1 day', strtotime($dob. '+59 years')));
	
$place_of_posting=$_POST['place_of_posting'];
$join_designation=$_POST['join_designation'];

$cadre=$_POST['cadre']; 
$sub_cadre=$_POST['sub_cadre'];
$sub_cadre_header_ext=$_POST['sub_cadre_header_ext'];
$appoint_type=$_POST['appoint_type'];
$others=$_POST['others'];

$granted_promo_date=$_POST['granted_promo_date'];

if($granted_promo_date!=''){
	$last_promo_date=$granted_promo_date;
	$next_promo_date = date('Y-m-d', strtotime($last_promo_date. '+5 years'));
}
else{
	$last_promo_date=$_POST['last_promo_date'];

//$last_promo_date=$granted_promo_date;
//$next_promo_date=$_POST['next_promo_date'];
$next_promo_date = date('Y-m-d', strtotime($last_promo_date. '+5 years'));
}

$last_incr_date=$_POST['last_incr_date'];
	 //$incr_due_date=$_POST['incr_due_date'];
$next_incr_date = date('Y-m-d', strtotime($last_incr_date. '+1 years'));

// $grade=$_POST['grade']; 
// $basic=$_POST['basic'];
// $scale=$_POST['scale'];
$grade_id=$_POST['grade'];
	 $sql_grade="SELECT grade from grade where id ='$grade_id'"; 
		 $result=mysqli_query($conn,$sql_grade);
		 while($row = mysqli_fetch_array($result))
					{
						
						$grade=$row['grade'];
						
					}
	 $basic_id=$_POST['basic'];
	 $sql_basic="SELECT basic from basic where id ='$basic_id'"; 
		 $result=mysqli_query($conn,$sql_basic);
		 while($row = mysqli_fetch_array($result))
					{
						
						$basic=$row['basic'];
						
					}
	 $scale_id=$_POST['scale'];
	 $sql_scale="SELECT scale from pay_scale_2015 where id ='$scale_id'"; 
		 $result=mysqli_query($conn,$sql_scale);
		 while($row = mysqli_fetch_array($result))
					{
						
						$scale=$row['scale'];
						
					}

$job_status=$_POST['job_status'];

$nature_of_promo=$_POST['nature_of_promo'];

// $incr_granted=($basic*5)/100;//37280*5/100=1864
// $basic_after_incr=$basic+$incr_granted;
$incr_granted=0;
$basic_after_incr=$basic;
$d_nothi_id=$_POST['d_nothi_id'];
$tin_no=$_POST['tin_no'];
$pension_start_date=date('Y-m-d', strtotime($prl. '+1 years'));

$sql="UPDATE job_desc SET  dob = '$dob', doj = '$doj',police_verification='$police_verification', doc = '$doc',  
prl = '$prl',pension_start_date = '$pension_start_date',place_of_posting = '$place_of_posting',d_nothi_id = '$d_nothi_id',tin_no = '$tin_no',join_designation = '$join_designation',
cadre = '$cadre', sub_cadre = '$sub_cadre',sub_cadre_header_ext = '$sub_cadre_header_ext', appoint_type = '$appoint_type', others = '$others',
last_promo_date = '$last_promo_date',next_promo_date = '$next_promo_date',last_incr_date = '$last_incr_date',
next_incr_date = '$next_incr_date', grade = '$grade', basic = '$basic', scale = '$scale', job_status='$job_status' , incr_granted = '$incr_granted', basic_after_incr='$basic_after_incr', granted_promo_date='$granted_promo_date', nature_of_promo='$nature_of_promo' where emp_id='$emp_id'";
		//header("Location: viewprofile.php");
		//}
//}
	if(mysqli_query($conn,$sql))	{
			//header("Location: view_profile_details.php");
	date_default_timezone_set("Asia/Dhaka");
	$today = date("Y-m-d H:i:s");

	// Update job_desc table
	$sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
	$result=mysqli_query($conn,$sql1);
			header("location:edit_job_desc.php?submitted=successfully");
	}
	else
		print mysqli_error();
	//echo 'invalid';

//}
	}
  ?>