<?php
// Start the session
error_reporting(0);
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('../db/db.php');
include('../includes/header.php');
  
 if(isset($_POST['submit']) && isset($_SESSION['emp_id'])){
 	//if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	$emp_id=$_SESSION['emp_id'];
	
	//for SSC 
	$ssc_exam_id=$_POST['ssc_exam'];
	 $sql_ssc_exam="SELECT name from exam where id ='$ssc_exam_id'"; 
	$result_ssc_exam=mysqli_query($conn,$sql_ssc_exam);
	$row_ssc_exam = mysqli_fetch_array($result_ssc_exam);						
	$ssc_exam=$row_ssc_exam['name'];

	 if($ssc_exam == "Select"){
		$ssc_exam='';
	   }
 	$ssc_group_name_id=$_POST['ssc_group_name'];
	$sql_ssc_group_name="SELECT name from subject_ssc where id ='$ssc_group_name_id'"; 
	$result_ssc_group_name=mysqli_query($conn,$sql_ssc_group_name);
	$row_ssc_group_name = mysqli_fetch_array($result_ssc_group_name);					
	
	$ssc_group_name=$row_ssc_group_name['name'];
	if($ssc_group_name == "Select"){
		$ssc_group_name='';
	   }			
	
	$ssc_inst_name=$_POST['ssc_inst_name'];
	$ssc_board_or_univ=$_POST['ssc_board_or_univ'];
	$ssc_div_or_cgpa=$_POST['ssc_div_or_cgpa'];
	//$ssc_cgpa_4=$_POST['ssc_cgpa_4'];
	$ssc_cgpa_5=$_POST['ssc_cgpa_5'];
	if($ssc_div_or_cgpa!="CGPA (Out of 5)"){
		$ssc_cgpa_5='';
	}
	$ssc_passing_year=$_POST['ssc_passing_year'];

	//For HSC
	$hsc_exam_id=$_POST['hsc_exam'];
	$sql_hsc_exam="SELECT name from exam where id ='$hsc_exam_id'"; 
	$result_hsc=mysqli_query($conn,$sql_hsc_exam);
	$row_hsc = mysqli_fetch_array($result_hsc);
						
	$hsc_exam=$row_hsc['name'];
	 if($hsc_exam == "Select"){
		$hsc_exam='';
	   }
	//$hsc_group_name=$_POST['hsc_group_name'];
	$hsc_group_name_id=$_POST['hsc_group_name'];
	$sql_hsc_group_name="SELECT name from subject_ssc where id ='$hsc_group_name_id'"; 
	$result_hsc_group_name=mysqli_query($conn,$sql_hsc_group_name);
	$row_hsc_group_name = mysqli_fetch_array($result_hsc_group_name);					
	$hsc_group_name=$row_hsc_group_name['name'];

	 if($hsc_group_name=="Select"){
		$hsc_group_name='';
	   }

	$hsc_inst_name=$_POST['hsc_inst_name'];
	$hsc_board_or_univ=$_POST['hsc_board_or_univ'];
	$hsc_div_or_cgpa=$_POST['hsc_div_or_cgpa'];
	$hsc_cgpa_5=$_POST['hsc_cgpa_5'];
	$hsc_cgpa_4=$_POST['hsc_cgpa_4'];

	if ($hsc_div_or_cgpa != "CGPA (Out of 5)" && $hsc_div_or_cgpa != "CGPA (Out of 4)") {
	    $hsc_cgpa_5 = ''; 
	}
	if ($hsc_div_or_cgpa == "CGPA (Out of 5)") {
	    $hsc_cgpa_5 = $_POST['hsc_cgpa_5'];
	}
	// Check if the value is "CGPA (Out of 4)"
	if ($hsc_div_or_cgpa == "CGPA (Out of 4)") {
	    $hsc_cgpa_5 = $_POST['hsc_cgpa_4']; 
	}
	$hsc_passing_year=$_POST['hsc_passing_year'];

	//For Honors
	$honors_exam_id=$_POST['honors_exam'];	 
	$sql_honors_exam="SELECT name from exam where id ='$honors_exam_id'"; 
	$result_honors=mysqli_query($conn,$sql_honors_exam);
	$row_honors = mysqli_fetch_array($result_honors);						
	$honors_exam=$row_honors['name'];
	if($honors_exam == "Select"){
		$honors_exam='';
	   }			

	$honors_group_name_id=$_POST['honors_group_name'];
	$sql_honors_group_name="SELECT name from subject_graduation where id ='$honors_group_name_id'"; 
	$result_honors_group_name=mysqli_query($conn,$sql_honors_group_name);
	$row_honors_group_name = mysqli_fetch_array($result_honors_group_name);					
	$honors_group_name=$row_honors_group_name['name'];
	if($honors_group_name == "Select"){
		$honors_group_name='';
	   }

	$honors_groupname_others=$_POST['honors_groupname_others'];
	if($honors_group_name!="Others"){
		$honors_groupname_others='';
	}

	$honors_inst_name=$_POST['honors_inst_name'];
	$honors_univer_others=$_POST['honors_univer_others'];
	if($honors_inst_name!="Others"){
		$honors_univer_others='';
	}

	$honors_board_or_univ=$_POST['honors_board_or_univ'];
	$honors_board_others=$_POST['honors_board_others'];
	if($honors_board_or_univ!="Others"){
		$honors_board_others='';
	}

	$honors_div_or_cgpa=$_POST['honors_div_or_cgpa'];
	$honors_cgpa_4=$_POST['honors_cgpa_4'];
	if($honors_div_or_cgpa!="CGPA (Out of 4)"){
		$honors_cgpa_4='';
	}
	$honors_passing_year=$_POST['honors_passing_year'];
	$honors_course_duration=$_POST['honors_course_duration'];

	//for Masters
	$masters_exam_id=$_POST['masters'];
	$sql_ms_exam="SELECT name from exam where id ='$masters_exam_id'"; 
	$result_ms=mysqli_query($conn,$sql_ms_exam);
	$row_ms = mysqli_fetch_array($result_ms);						
	$masters=$row_ms['name'];
	 if($masters == "Select"){
		$masters='';
	   }
				
	$ms_group_name_id=$_POST['ms_group_name'];
	$sql_ms_group_name="SELECT name from subject_graduation where id ='$ms_group_name_id'"; 
	$result_ms_group_name=mysqli_query($conn,$sql_ms_group_name);
	$row_ms_group_name = mysqli_fetch_array($result_ms_group_name);					
	$ms_group_name=$row_ms_group_name['name'];
		 if($ms_group_name == "Select"){
		$ms_group_name='';
	   }

	$ms_groupname_others=$_POST['ms_groupname_others'];
	if($ms_group_name!="Others"){
		$ms_groupname_others='';
	}

	$ms_inst_name=$_POST['ms_inst_name'];
	$ms_board_or_univ=$_POST['ms_board_or_univ'];
	$ms_board_others=$_POST['ms_board_others'];
	if($ms_board_or_univ!="Others"){
		$ms_board_others='';
	}
	$ms_univer_others=$_POST['ms_univer_others'];
	$ms_div_or_cgpa=$_POST['ms_div_or_cgpa'];
	$ms_cgpa_4=$_POST['ms_cgpa_4'];
	if($ms_div_or_cgpa!="CGPA (Out of 4)"){
		$ms_cgpa_4='';
	}

	$ms_passing_year=$_POST['ms_passing_year'];	
	$ms_course_duration=$_POST['ms_course_duration'];
		
$sql="UPDATE edu_info SET ssc_exam = '$ssc_exam', ssc_group_name = '$ssc_group_name', ssc_inst_name = '$ssc_inst_name', ssc_board_or_univ = '$ssc_board_or_univ',ssc_div_or_cgpa = '$ssc_div_or_cgpa',ssc_passing_year = '$ssc_passing_year',ssc_cgpa_5 = '$ssc_cgpa_5',
hsc_exam = '$hsc_exam', hsc_group_name = '$hsc_group_name', hsc_inst_name = '$hsc_inst_name' , 
hsc_board_or_univ = '$hsc_board_or_univ',hsc_div_or_cgpa = '$hsc_div_or_cgpa',hsc_passing_year = '$hsc_passing_year',hsc_cgpa_5 = '$hsc_cgpa_5',
honors_exam = '$honors_exam', honors_group_name = '$honors_group_name',honors_groupname_others = '$honors_groupname_others', honors_inst_name = '$honors_inst_name', honors_univer_others = '$honors_univer_others' ,honors_board_or_univ = '$honors_board_or_univ',honors_board_others = '$honors_board_others',honors_div_or_cgpa = '$honors_div_or_cgpa',honors_cgpa_4 = '$honors_cgpa_4',honors_passing_year = '$honors_passing_year',honors_course_duration = '$honors_course_duration',
masters = '$masters', ms_group_name = '$ms_group_name', ms_groupname_others = '$ms_groupname_others',ms_inst_name = '$ms_inst_name',ms_board_or_univ = '$ms_board_or_univ',ms_univer_others = '$ms_univer_others',ms_board_others = '$ms_board_others',ms_div_or_cgpa = '$ms_div_or_cgpa',ms_cgpa_4 = '$ms_cgpa_4',ms_passing_year = '$ms_passing_year',ms_course_duration = '$ms_course_duration'
where emp_id='$emp_id'";

	if(mysqli_query($conn,$sql)){
		date_default_timezone_set("Asia/Dhaka");
		$today = date("Y-m-d H:i:s");
		// Update job_desc table
		$sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
		$result=mysqli_query($conn,$sql1);
			header("Location: edit_edu_info.php?emp_id=".$_SESSION['emp_id']);
	}
	else
		print mysqli_error();	
}	
  ?>