<?php
// Turn off error reporting
error_reporting(0);
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');

$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val']; 
  }

include('../db/db.php');
 if(isset($_POST['submit']) && isset($_SESSION['emp_id'])){
 
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];
	 
	$sql = "SELECT * FROM edu_info WHERE emp_id='$emp_id'";
    $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo '<script type="text/javascript">';
    echo 'alert("Education Information Already taken!");';
    // echo 'window.location.href="add_edu_info.php"';
    echo 'window.location.href="../login/user_dashboard.php"';
    echo '</script>';
  } else {

  	//$ssc_exam_id=$_POST['ssc_exam'];
	
    $ssc_exam_id = $_POST['ssc_exam'];
    $sql_ssc_exam="SELECT name from exam where id ='$ssc_exam_id'"; 
		 $result=mysqli_query($conn,$sql_ssc_exam);
		 $row = mysqli_fetch_array($result);
						
			$ssc_exam=$row['name'];

    if($ssc_exam=="Select"){
		$ssc_exam='';
	   }
	
 	$ssc_group_name_id=$_POST['ssc_group_name'];

	$sql_ssc_group_name="SELECT name from subject_ssc where id ='$ssc_group_name_id'"; 
	$result=mysqli_query($conn,$sql_ssc_group_name);
	$row = mysqli_fetch_array($result);
					
	$ssc_group_name=$row['name'];
	
	   if($ssc_group_name=="Select"){
		 $ssc_group_name='';
	   }
	
	$ssc_inst_name=$_POST['ssc_inst_name'];
	//if (isset($_POST['ssc_board_or_univ'])) {

	$ssc_board_or_univ=$_POST['ssc_board_or_univ'];
	if($ssc_board_or_univ==0){
		$ssc_board_or_univ="";
	}

	//}
	//$ssc_board_or_univ=mysql_real_escape_string($conn,$_POST['ssc_board_or_univ']);
	
	$ssc_div_or_cgpa=$_POST['ssc_div_or_cgpa'];
	if($ssc_div_or_cgpa==0)	{
		$ssc_div_or_cgpa="";
	}
	
	//$ssc_cgpa_4=$_POST['ssc_cgpa_4'];
	$ssc_cgpa_5=$_POST['ssc_cgpa_5'];
	if(isset($ssc_div_or_cgpa)!="CGPA (Out of 5)"){
		$ssc_cgpa_5='';
	}
	
	$ssc_passing_year_id=$_POST['ssc_passing_year'];
	if($ssc_passing_year_id==0)	{
		$ssc_passing_year="";
	}else{
    $sql_ssc_exam="SELECT year from passing_year where id ='$ssc_passing_year_id'"; 
		 $result=mysqli_query($conn,$sql_ssc_exam);
		 $row = mysqli_fetch_array($result);
						
			$ssc_passing_year=$row['year'];	
		}

	//$ssc_exam_id = $_POST['ssc_exam'];

	//$hsc_exam=$_POST['hsc_exam']; 
if (isset($_POST['hsc_exam'])) {
	$hsc_exam_id=$_POST['hsc_exam'];
	 //$basic_id=$_POST['basic'];
	$sql_hsc_exam="SELECT name from exam where id ='$hsc_exam_id'"; 
	$result_hsc=mysqli_query($conn,$sql_hsc_exam);
	$row_hsc = mysqli_fetch_array($result_hsc);
						
	$hsc_exam=$row_hsc['name'];
	 if($hsc_exam == "Select"){
		$hsc_exam='';
	   }
	}			
	//$hsc_group_name=$_POST['hsc_group_name'];
	if (isset($_POST['hsc_group_name'])) {
	 $hsc_group_name_id=$_POST['hsc_group_name'];

	$sql_hsc_group_name="SELECT name from subject_ssc where id ='$hsc_group_name_id'"; 
	$result_hsc_group_name=mysqli_query($conn,$sql_hsc_group_name);
	$row_hsc_group_name = mysqli_fetch_array($result_hsc_group_name);
					
	$hsc_group_name=$row_hsc_group_name['name'];

	 if($hsc_group_name=="Select"){
		$hsc_group_name='';
	   }
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


	//$hsc_passing_year=isset($_POST['hsc_passing_year']);
	$hsc_passing_year_id=$_POST['hsc_passing_year'];
    $sql_hsc_passing_year="SELECT year from passing_year where id ='$hsc_passing_year_id'"; 
	$result_hsc_passing_year=mysqli_query($conn,$sql_hsc_passing_year);
	$row_hsc_passing_year = mysqli_fetch_array($result_hsc_passing_year);
						
		$hsc_passing_year=$row_hsc_passing_year['year'];


	//$honors_exam=$_POST['honors_exam']; 
	//$honors_group_name=$_POST['honors_group_name'];
	if (isset($_POST['honors_exam'])) {
	$honors_exam_id=$_POST['honors_exam'];
	 //$basic_id=$_POST['basic'];
	$sql_honors_exam="SELECT name from exam where id ='$honors_exam_id'"; 
	$result_honors=mysqli_query($conn,$sql_honors_exam);
	$row_honors = mysqli_fetch_array($result_honors);
						
	$honors_exam=$row_honors['name'];
		if($honors_exam=="Select"){
		$honors_exam='';
	   }
		}
	//$hsc_group_name=$_POST['hsc_group_name'];
		if (isset($_POST['honors_group_name'])) {
	 $honors_group_name_id=$_POST['honors_group_name'];

	$sql_honors_group_name="SELECT name from subject_graduation where id ='$honors_group_name_id'"; 
	$result_honors_group_name=mysqli_query($conn,$sql_honors_group_name);
	$row_honors_group_name = mysqli_fetch_array($result_honors_group_name);
					
	$honors_group_name=$row_honors_group_name['name'];

	if($honors_group_name=="Select"){
		$honors_group_name='';
	   }
	}
	$honors_groupname_others=isset($_POST['honors_groupname_others']);
	if($honors_group_name!="Others"){
		$honors_groupname_others='';

	}

	$honors_inst_name_id=$_POST['honors_inst_name'];
	$sql_honors_inst_name="SELECT university_name from university_list where id ='$honors_inst_name_id'"; 
	$result_honors_inst_name=mysqli_query($conn,$sql_honors_inst_name);
	$row_honors_inst_name = mysqli_fetch_array($result_honors_inst_name);
						
		$honors_inst_name=$row_honors_inst_name['university_name'];

	$honors_univer_others=$_POST['honors_univer_others'];
	if($honors_inst_name!="Others"){
		$honors_univer_others='';
	}

	$honors_board_or_univ_id=$_POST['honors_board_or_univ'];
	$sql_honors_board_or_univ="SELECT university_name from university_list where id ='$honors_board_or_univ_id'"; 
	$result_honors_board_or_univ=mysqli_query($conn,$sql_honors_board_or_univ);
	$row_honors_board_or_univ = mysqli_fetch_array($result_honors_board_or_univ);
						
		$honors_board_or_univ=$row_honors_board_or_univ['university_name'];

	$honors_board_others=$_POST['honors_board_others'];
	if($honors_board_or_univ!="Others"){
		$honors_board_others='';

	}

	$honors_div_or_cgpa=$_POST['honors_div_or_cgpa'];
	$honors_cgpa_4=$_POST['honors_cgpa_4'];
	if($honors_div_or_cgpa!="CGPA (Out of 4)"){
		$honors_cgpa_4='';
	}
	
	$honors_passing_year_id=$_POST['honors_passing_year'];
    $sql_honors_passing_year="SELECT year from passing_year where id ='$honors_passing_year_id'"; 
	$result_honors_passing_year=mysqli_query($conn,$sql_honors_passing_year);
	$row_honors_passing_year = mysqli_fetch_array($result_honors_passing_year);
						
		$honors_passing_year=$row_honors_passing_year['year'];
	
	$honors_course_duration=$_POST['honors_course_duration'];

	//$masters=$_POST['masters']; 
	if (isset($_POST['masters'])) {
	$masters_exam_id=$_POST['masters'];
	 //$basic_id=$_POST['basic'];
	$sql_ms_exam="SELECT name from exam where id ='$masters_exam_id'"; 
	$result_ms=mysqli_query($conn,$sql_ms_exam);
	$row_ms = mysqli_fetch_array($result_ms);
						
	$masters=$row_ms['name'];
		if($masters=="Select"){
		$masters='';
	   		}	
	   }
	   if (isset($_POST['ms_group_name'])) {		
	$ms_group_name_id=$_POST['ms_group_name'];

	$sql_ms_group_name="SELECT name from subject_graduation where id ='$ms_group_name_id'"; 
	$result_ms_group_name=mysqli_query($conn,$sql_ms_group_name);
	$row_ms_group_name = mysqli_fetch_array($result_ms_group_name);
					
	$ms_group_name=$row_ms_group_name['name'];
	if($ms_group_name=="Select"){
		$ms_group_name='';
	   }
	}
	$ms_groupname_others=isset($_POST['ms_groupname_others']);
	if($ms_group_name!="Others"){
		$ms_groupname_others='';
	}
	$ms_inst_name_id=$_POST['ms_inst_name'];
	$sql_ms_inst_name="SELECT university_name from university_list where id ='$ms_inst_name_id'"; 
	$result_ms_inst_name=mysqli_query($conn,$sql_ms_inst_name);
	$row_ms_inst_name = mysqli_fetch_array($result_ms_inst_name);						
		
	$ms_inst_name=$row_ms_inst_name['university_name'];
	$ms_univer_others=$_POST['ms_univer_others'];
	if($ms_inst_name!="Others"){
		$ms_univer_others='';
	}
	$ms_board_or_univ_id=$_POST['ms_board_or_univ'];
	$sql_ms_board_or_univ="SELECT university_name from university_list where id ='$ms_board_or_univ_id'"; 
	$result_ms_board_or_univ=mysqli_query($conn,$sql_ms_board_or_univ);
	$row_ms_board_or_univ = mysqli_fetch_array($result_ms_board_or_univ);
						
	$ms_board_or_univ=$row_ms_board_or_univ['university_name'];
	$ms_board_others=$_POST['ms_board_others'];
	if($ms_board_or_univ!="Others"){
		$ms_board_others='';
	}
	$ms_div_or_cgpa=$_POST['ms_div_or_cgpa'];
	$ms_cgpa_4=$_POST['ms_cgpa_4'];
	if($ms_div_or_cgpa!="CGPA (Out of 4)"){
		$ms_cgpa_4='';
	}
	//$ms_passing_year=isset($_POST['ms_passing_year']);	
	$ms_passing_year_id=$_POST['ms_passing_year'];
    $sql_ms_passing_year="SELECT year from passing_year where id ='$ms_passing_year_id'"; 
	$result_ms_passing_year=mysqli_query($conn,$sql_ms_passing_year);
	$row_ms_passing_year = mysqli_fetch_array($result_ms_passing_year);
						
	$ms_passing_year=$row_ms_passing_year['year'];
	$ms_course_duration=$_POST['ms_course_duration'];
		 
	 //$sql="SELECT * FROM users where id='$edit_id'";
	 $sql="INSERT INTO edu_info (user_id, emp_id, 
	 ssc_exam,ssc_group_name,ssc_inst_name,ssc_board_or_univ,ssc_div_or_cgpa,ssc_cgpa_5,ssc_passing_year,hsc_exam,hsc_group_name,hsc_inst_name,hsc_board_or_univ,hsc_div_or_cgpa,hsc_cgpa_5,hsc_passing_year,honors_exam,honors_group_name,honors_groupname_others,honors_inst_name,honors_univer_others,honors_board_or_univ,honors_board_others,honors_div_or_cgpa,honors_cgpa_4,honors_passing_year,honors_course_duration,masters,ms_group_name,ms_groupname_others,ms_inst_name,ms_univer_others,ms_board_or_univ,ms_board_others,ms_div_or_cgpa,ms_cgpa_4,ms_passing_year,ms_course_duration) 
	 VALUES ('{$user_id}','{$emp_id}','{$ssc_exam}','{$ssc_group_name}','{$ssc_inst_name}',
	 '{$ssc_board_or_univ}','{$ssc_div_or_cgpa}','{$ssc_cgpa_5}','{$ssc_passing_year}',
	 '{$hsc_exam}','{$hsc_group_name}','{$hsc_inst_name}',
	 '{$hsc_board_or_univ}','{$hsc_div_or_cgpa}','{$hsc_cgpa_5}','{$hsc_passing_year}','{$honors_exam}','{$honors_group_name}','{$honors_groupname_others}','{$honors_inst_name}','{$honors_univer_others}','{$honors_board_or_univ}','{$honors_board_others}','{$honors_div_or_cgpa}','{$honors_cgpa_4}','{$honors_passing_year}','{$honors_course_duration}','{$masters}','{$ms_group_name}','{$ms_groupname_others}','{$ms_inst_name}','{$ms_univer_others}','{$ms_board_or_univ}','{$ms_board_others}','{$ms_div_or_cgpa}','{$ms_cgpa_4}','{$ms_passing_year}','{$ms_course_duration}')";	
	if(mysqli_query($conn,$sql)){
			
		if($val==1){
			// header("Location: ../login/user_dashboard.php?emp_id=".'3');
			// header("Location: edit_personal_info.php?submitted=" . urlencode($val));
			echo '<script type="text/javascript">';
		    echo 'alert("Data Inserted Successfully!!!");';
		    echo 'window.location.href="../login/user_dashboard.php"';
		    echo '</script>';
		}
		else{
			header("Location: add_edu_info.php?emp_id=".'3');			
		}			
	}
	else{
		print mysqli_error();
	}

  }
	
 }	
?>
    <div class="container mt-2 p-1 my-1 border shadow-lg bg-white" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-info text-uppercase text-shadow mt-2 p-1 my-1"><b>Add Educational information</b></h2>
    	<div class="row">		
    		<div class="col-sm-12">
    			  <?php
					if(@$_GET['3'])
					{?>
					<div class="alert alert-success" role="alert">
					  <b>Data Inserted Successfully!!!</b>
					</div>
					<?php }?>
		<div class="panel-body">
			<form method="POST" id="form1" name="form1" action="add_edu_info.php?val=<?php echo $val; ?>" enctype="multipart/form-data">
<fieldset>				
	<div class="row">				
		<!-- Educational Info-->
		<div class="col-sm-3"> 
			<div class="card border border-warning">
			  <div class="card-header">
				<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
				<label for="ssc_exam">SSC/ Equivalent or Eight Passed</label>
			  </div>
			<div class="card-body">
				<div class="form-group">
					 <select class="form-control" id="ssc_exam" name="ssc_exam" tabindex="6" required>
						<option value="1" >Select Examination </option>
						<?php                                                   
						//$sql = "SELECT * FROM exam where id between 1 and 6";
						$sql = "SELECT * FROM exam where id In(34,2,3,4,5,6,33,32)";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result)){
						 echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}?>	
					 </select>
					</div>
				<div class="form-group">
				<label for="ssc_group_name">Subject/Group/Degree:</label>
				 <select class="form-control" id="ssc_group_name" name="ssc_group_name" tabindex="7">
						<option value="1" >--Select--</option>						
					 </select>
					 <script>
					 	$(document).ready(function() {
				                $('#ssc_exam').on('change', function() {
				                    var category_id = this.value;
				                    $.ajax({
				                        url: "../ajax/ajaxpro_for_ssc_exam_1.php",
				                        type: "POST",
				                        data: {
				                            category_id: category_id
				                        },
				                        cache: false,
				                        success: function(result) {
				                            $("#ssc_group_name").html(result);
				                        }
				                    });
				                });
				            });

					</script>
				</div>

				<div class="form-group">
				<label for="ssc_inst_name">Institute Name:</label>
				 <input class="form-control" placeholder="Enter Institute" type="text" name="ssc_inst_name" id="ssc_inst_name">
				</div>

				<div class="form-group">
				<label for="ssc_board_or_univ">Board/Institute:</label>		
					 <select class="form-control" id="ssc_board_or_univ" name="ssc_board_or_univ" >
						<option value="0" >--Select--</option>						 
						<option value="Dhaka">Dhaka</option>
						  <option value="Jessore">Jessore</option>
						  <option value="Barisal">Barisal</option>
						  <option value="Sylhet">Sylhet</option>
						  <option value="Cumilla">Cumilla</option>
						  <option value="Rajshahi">Rajshahi</option>
						  <option value="Rangpur">Rangpur</option>
						  <option value="Chittagong">Chittagong</option>
						  <option value="Madrasha">Madrasha</option>
						  <option value="Techincal (BTEB)">Techincal (BTEB)</option>
						  <option value="Bangladesh Open University">Bangladesh Open University</option>
					 </select>
				</div>
				<div class="form-group">
					<label for="ssc_div_or_cgpa">Division/Class/CGPA:</label>					 
					 <select class="form-control" id="ssc_div_or_cgpa" name="ssc_div_or_cgpa" >	
							<option value="0" >--Select--</option>							 
							<option value="1st Class">1st Class</option>
							  <option value="2nd Class">2nd Class</option>
							  <option value="3rd Class">3rd Class</option>
							  <option value="CGPA (Out of 5)">CGPA (Out of 5)</option>
							  <option value="Passed">Passed</option>
						 </select>
						<input class="form-control mt-2" placeholder="Enter CGPA (Out of 5)" type="text" name="ssc_cgpa_5" id="ssc_cgpa_5">
				</div>

				<div class="form-group">
					 <label for="ssc_passing_year">Passing year</label>
					 <select class="form-control" id="ssc_passing_year" name="ssc_passing_year" tabindex="6">
						<option value="0" >--Select--</option>
						<?php
                        //require_once('db/db.php');
						$sql = "SELECT * FROM passing_year";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result)){
						 echo "<option value='".$row['id']."'>".$row['year']."</option>";
						}?>	
					 </select>
					</div>				
				</div>
			</div>
				</div>
		<!--Second Column-->
				<div class="col-sm-3"> 

				<div class="card border border-warning">
				  <div class="card-header">
					<!-- <label for="ssc_exam">Educational Information Portion-2</label> -->
					<label for="hsc_exam">HSC or Equivalent Level</label>
				  </div>
				<div class="card-body">
					<div class="form-group">
					<select class="form-control" id="hsc_exam" name="hsc_exam" >
						<option value="1" >Select Examination </option>
						<?php
                        //require_once('db/db.php');  
						$sql = "SELECT * FROM exam where id In(7,8,9,10,11,12,13,14,15,42,43)";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}?>	
						
					 </select>
					</div>
				<div class="form-group">
				<label for="hsc_group_name">Subject/Group/Degree:</label>
				  <select class="form-control" id="hsc_group_name" name="hsc_group_name" tabindex="7">
					<option value="1" >--Select--</option>						
					 </select>
					 <script>
							$(document).ready(function() {
				                $('#hsc_exam').on('change', function() {
				                    var hsc_examid = this.value;
				                    $.ajax({
				                        url: "../ajax/ajaxpro_for_ssc_exam_1.php",
				                        type: "POST",
				                        data: {
				                            hsc_examid: hsc_examid
				                        },
				                        cache: false,
				                        success: function(result) {
				                            $("#hsc_group_name").html(result);
				                        }
				                    });
				                });
				            });
					</script>
				</div>	

				<div class="form-group">
				<label for="hsc_inst_name">Institute Name:</label>
				 <input class="form-control" placeholder="Enter Institute" type="text" name="hsc_inst_name" id="hsc_inst_name" >
				</div>

				<div class="form-group">
				<label for="hsc_board_or_univ">Board/Institute:</label>			
				 <select class="form-control" id="hsc_board_or_univ" name="hsc_board_or_univ" >
						<option value="" disabled selected>--Select--</option>						 
						<option value="Dhaka">Dhaka</option>
						  <option value="Jessore">Jessore</option>
						  <option value="Barisal">Barisal</option>
						  <option value="Sylhet">Sylhet</option>
						  <option value="Cumilla">Cumilla</option>
						  <option value="Rajshahi">Rajshahi</option>
						  <option value="Rangpur">Rangpur</option>
						  <option value="Chittagong">Chittagong</option>
						  <option value="Madrasha">Madrasha</option>
						  <option value="Techincal (BTEB)">Techincal (BTEB)</option>
						  <option value="Bangladesh Open University">Bangladesh Open University</option>
					 </select>
				</div>

				<div class="form-group">
				<label for="hsc_div_or_cgpa">Division/Class/CGPA:</label>
				 <select class="form-control" id="hsc_div_or_cgpa" name="hsc_div_or_cgpa" >
						<option value="" disabled selected>--Select--</option>						 
						<option value="1st Class">1st Class</option>
						  <option value="2nd Class">2nd Class</option>
						  <option value="3rd Class">3rd Class</option>
						  <option value="CGPA (Out of 5)">CGPA (Out of 5)</option>
						  <option value="CGPA (Out of 4)">CGPA (Out of 4)</option>
						  <option value="Passed">Passed</option>
					 </select>

					<input class="form-control mt-2" placeholder="Enter CGPA (Out of 4)" type="text" name="hsc_cgpa_4" id="hsc_cgpa_4">

					<input class="form-control mt-2" placeholder="Enter CGPA (Out of 5)" type="text" name="hsc_cgpa_5" id="hsc_cgpa_5">
				</div>

				<div class="form-group">
				<label for="hsc_passing_year">Passing year</label>
				  <select class="form-control" id="hsc_passing_year" name="hsc_passing_year" tabindex="6">
						<option value="" disabled selected>--Select--</option>
						<?php
                        //require_once('db/db.php');
						$sql = "SELECT * FROM passing_year";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['year']."</option>";
						}?>	
					 </select>
				</div>
				</div>
			</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-3"> 
				<div class="card border border-warning">
				  <div class="card-header">
					<!-- <label for="ssc_exam">Educational Information Portion-3</label> -->
					<label for="honors_exam">Graduation or Equivalent Level</label>
				  </div>
				<div class="card-body">
				<div class="form-group">
					<select class="form-control" id="honors_exam" name="honors_exam" >
						<!-- <option value="" disabled selected>Select Examination</option> -->
						<option value="1" >Select Examination </option>
						<?php
                        //require_once('db/db.php');
						$sql = "SELECT * FROM exam where id In(16,17,18,19,20,21,22,23,36,37,38,39,41)";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}?>	
											
					 </select>
					</div>
					<div class="form-group">
					<label for="honors_group_name">Subject/Group/Degree:</label>
					 <select class="form-control" id="honors_group_name" name="honors_group_name" tabindex="7">
					<option value="1" >--Select--</option>						
					 </select>
					 <input class="form-control mt-2" placeholder="Enter Subject" type="text" name="honors_groupname_others" id="honors_groupname_others" >
					 <script>
							$(document).ready(function() {
				                $('#honors_exam').on('change', function() {
				                    var honors_examid = this.value;
				                    $.ajax({
				                        url: "../ajax/ajaxpro_for_ssc_exam_1.php",
				                        type: "POST",
				                        data: {
				                            honors_examid: honors_examid
				                        },
				                        cache: false,
				                        success: function(result) {
				                            $("#honors_group_name").html(result);
				                        }
				                    });
				                });		
				            });
					</script>
				</div>	

			<div class="form-group"><label for="honors_inst_name">University/NU/Institute Name:</label>
            <select name="honors_inst_name" id="honors_inst_name" class="form-control">
					<option value="" disabled selected>--Select--</option>						
					    <?php 
					    $query_university ="SELECT * FROM university_list";
					    $result_univ = mysqli_query($conn, $query_university);
						while($row_univ = mysqli_fetch_array($result_univ))	{
						 echo "<option value='".$row_univ['id']."'>".$row_univ['university_name']."</option>";
						}
					 ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter University" type="text" name="honors_univer_others" id="honors_univer_others" >
                	</div>

				<div class="form-group">
					<label for="honors_board_or_univ">Board/University/Institute:</label>
					<select name="honors_board_or_univ" id="honors_board_or_univ" class="form-control">
					   <option value="" disabled selected>--Select--</option>						
					    <?php 
					    $query_honors_board_or_univ ="SELECT * FROM university_list";
					    $result_honors_board_or_univ = mysqli_query($conn, $query_honors_board_or_univ);
						while($row_honors_board_or_univ = mysqli_fetch_array($result_honors_board_or_univ)){
						 echo "<option value='".$row_honors_board_or_univ['id']."'>".$row_honors_board_or_univ['university_name']."</option>";
						}
					 ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter Board/University" type="text" name="honors_board_others" id="honors_board_others" >
                	</div>
				<div class="form-group">
				<label for="honors_div_or_cgpa">Division/Class/CGPA:</label>
				 <select class="form-control" id="honors_div_or_cgpa" name="honors_div_or_cgpa" >			
					<option value="" disabled selected>--Select--</option>						 
					<option value="1st Class">1st Class</option>
					  <option value="2nd Class">2nd Class</option>
					  <option value="3rd Class">3rd Class</option>
					  <option value="CGPA (Out of 4)">CGPA (Out of 4)</option>						  
					  <option value="Passed">Passed</option>
					 </select>
					<input class="form-control mt-2"  placeholder="Enter CGPA (Out of 4)" type="text" name="honors_cgpa_4" id="honors_cgpa_4">					
				</div>

				<div class="form-group">
				<label for="honors_passing_year">Passing year</label>
				 <select class="form-control" id="honors_passing_year" name="honors_passing_year" tabindex="6">
						<option value="" disabled selected>--Select--</option>
						<?php
                        //require_once('db/db.php');
						$sql = "SELECT * FROM passing_year";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['year']."</option>";
						}?>	
					 </select>
				</div>
				
				<div class="form-group">
				<label for="honors_course_duration">Course Duration</label>				
				<select class="form-control" id="honors_course_duration" name="honors_course_duration" >
						<option value="" disabled selected>Select</option>						 
						<option value="02 Years">02 Years</option>
						  <option value="03 Years">03 Years</option>
						  <option value="04 Years">04 Years</option>
						  <option value="05 Years">05 Years</option>
					 </select>
				</div>
				</div>
			</div>
		</div>
		<!--4th Column for Masters-->
				<div class="col-sm-3"> 
				<div class="card border border-warning">
				  <div class="card-header">
					<!-- <label for="ssc_exam">Educational Information Portion-4</label> -->
					<label for="masters">Masters or Equivalent Level</label>
				  </div>
				<div class="card-body">
				<div class="form-group">
					<select class="form-control" id="masters" name="masters" >
					<option value="1" >Select Examination </option>
						 <?php
                        //require_once('db/db.php');
						$sql = "SELECT * FROM exam where id In(24,25,26,27,28,29,30,31,40)";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}?>											
					 </select>
					</div>
				<div class="form-group">
				<label for="ms_group_name">Subject/Group/Degree:</label>
				 <select class="form-control" id="ms_group_name" name="ms_group_name" tabindex="7">
					<option value="1" >--Select--</option>						
				</select>
					 <input class="form-control mt-2" placeholder="Enter Subject" type="text" name="ms_groupname_others" id="ms_groupname_others" >
					 <script>
							$(document).ready(function() {
				                $('#masters').on('change', function() {
				                    var masters_examid = this.value;
				                    $.ajax({
				                        url: "../ajax/ajaxpro_for_ssc_exam_1.php",
				                        type: "POST",
				                        data: {
				                            masters_examid: masters_examid
				                        },
				                        cache: false,
				                        success: function(result) {
				                            $("#ms_group_name").html(result);
				                        }
				                    });
				                });		
				            });
					</script>
				</div>	

			<div class="form-group">
				<label for="ms_inst_name">University/NU/Institute Name:</label>
              <select name="ms_inst_name" id="ms_inst_name" class="form-control">
                <option value="" disabled selected>--Select--</option>						
					    <?php 
					    $query_ms_univ ="SELECT * FROM university_list";
					    $result_ms_univ = mysqli_query($conn, $query_ms_univ);
						while($row_ms_univ = mysqli_fetch_array($result_ms_univ))	{
						 echo "<option value='".$row_ms_univ['id']."'>".$row_ms_univ['university_name']."</option>";
						}
					 ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter University" type="text" name="ms_univer_others" id="ms_univer_others" >
                	</div>
			<div class="form-group"><label for="ms_board_or_univ">Board/University/Institute:</label>
              <select name="ms_board_or_univ" id="ms_board_or_univ" class="form-control">
					    <option value="" disabled selected>--Select--</option>
					<?php
         			$sql_ms_board_or_univ = "SELECT * FROM university_list";
					$result_ms_board_or_univ = mysqli_query($conn, $sql_ms_board_or_univ);
					while($row_ms_board_or_univ = mysqli_fetch_array($result_ms_board_or_univ))
					{
					 echo "<option value='".$row_ms_board_or_univ['id']."'>".$row_ms_board_or_univ['university_name']."</option>";
					}?>	
					</select>
					<input class="form-control mt-2" placeholder="Enter Board/University" type="text" name="ms_board_others" id="ms_board_others" >
                	</div>
				<div class="form-group">
				<label for="ms_div_or_cgpa">Division/Class/CGPA:</label>
				 <!-- <input class="form-control" placeholder="Enter Division/Class/CGPA" type="text" name="ms_div_or_cgpa" id="ms_div_or_cgpa" > -->
				 <select class="form-control" id="ms_div_or_cgpa" name="ms_div_or_cgpa" >					
						<option value="" disabled selected>--Select--</option>						 
						<option value="1st Class">1st Class</option>
						  <option value="2nd Class">2nd Class</option>
						  <option value="3rd Class">3rd Class</option>
						  <option value="CGPA (Out of 4)">CGPA (Out of 4)</option>						  
						  <option value="Passed">Passed</option>
					 </select>

					<input class="form-control mt-2" placeholder="Enter CGPA (Out of 4)" type="text" name="ms_cgpa_4" id="ms_cgpa_4">
					
				</div>

				<div class="form-group">
				<label for="ms_passing_year">Passing year</label>
				<!--  <input class="form-control" placeholder="Enter Passing Year" type="text" name="ms_passing_year" id="ms_passing_year"> -->
				 <select class="form-control" id="ms_passing_year" name="ms_passing_year" tabindex="6">
						<option value="" disabled selected>--Select--</option>
						<?php
                        //require_once('db/db.php');

						$sql = "SELECT * FROM passing_year";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['year']."</option>";
						}?>	
					 </select>
				</div>
				
				<div class="form-group">
				<label for="ms_course_duration">Course Duration</label>
				
				<select class="form-control" id="ms_course_duration" name="ms_course_duration" >
						<option value="" disabled selected>Select</option>
						 <option value="01 Years">01 Years</option>
						 <option value="1.5 Years">1.5 Years</option>
						<option value="02 Years">02 Years</option>
						  <option value="03 Years">03 Years</option>
						  <option value="04 Years">04 Years</option>
						  <option value="05 Years">05 Years</option>
					 </select>
				 <!--<input class="form-control" placeholder="Enter Passing Year" type="text" name="course_duration" id="course_duration" value="<?=$row['course_duration'];?>">
				-->
				</div>
				</div>
			</div>
		</div>
					
		<div class="row"> 
		<div class="col-sm-4">  </div>
		<div class="col-sm-4"><br>
			<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  </div>
		<div class="col-sm-4"><br>
			<?php

			      if($val==1){
			        ?>
			        <a class="btn btn-primary" href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a>
			        <?php
			      }
			      else{
			        ?>
			        <a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a>

			        <?php
			      }

			      ?>		
		</div>			
				
				</fieldset>
				
			</form>
			<div id="err"></div>
				<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		</div>  		   
    			
    		</div>
    	</div>
    </div>

<script type='text/javascript'>

$(document).ready(function() {

  var ssc_cgpa_5 = $("#ssc_cgpa_5");
	ssc_cgpa_5.hide();
     	if( $('#ssc_div_or_cgpa').val() == "CGPA (Out of 5)") {
       		// $('#ssc_cgpa_4').prop( "disabled", true );
       		// $("#ssc_cgpa_4").hide();
       		$('#ssc_cgpa_5').prop( "disabled", false );
       		$("#ssc_cgpa_5").show();
    }
//for ssc
 	$('#ssc_div_or_cgpa').change(function() {  	 
    	if( $(this).val() == "CGPA (Out of 5)") {
       		// $('#ssc_cgpa_4').prop( "disabled", true );
       		// $("#ssc_cgpa_4").hide();
       		$('#ssc_cgpa_5').prop( "disabled", false );
       		$("#ssc_cgpa_5").show();
    }
		else{
			//$("#ssc_cgpa_4").hide();
			$("#ssc_cgpa_5").hide();
			}
      $('#textInput').prop( "disabled", false );
      //$("#ssc_cgpa_5").show();
  }  
  );

 //for hsc 
$(document).ready(function() {
  var hsc_cgpa_5 = $("#hsc_cgpa_5");
  var hsc_cgpa_4 = $("#hsc_cgpa_4");

  hsc_cgpa_5.hide();
  hsc_cgpa_4.hide();

  if ($('#hsc_div_or_cgpa').val() == "CGPA (Out of 5)") {
    hsc_cgpa_5.prop("disabled", false);
    hsc_cgpa_5.show();
  }

  if ($('#hsc_div_or_cgpa').val() == "CGPA (Out of 4)") {
    hsc_cgpa_4.prop("disabled", false);
    hsc_cgpa_4.show();
  }

  $('#hsc_div_or_cgpa').change(function() {
    if ($(this).val() == "CGPA (Out of 5)") {
      hsc_cgpa_5.prop("disabled", false);
      hsc_cgpa_5.show();
      hsc_cgpa_4.hide();
    } else if ($(this).val() == "CGPA (Out of 4)") {
      hsc_cgpa_4.prop("disabled", false);
      hsc_cgpa_4.show();
      hsc_cgpa_5.hide();
    } else {
      hsc_cgpa_5.hide();
      hsc_cgpa_4.hide();
    }

    $('#textInput').prop("disabled", false);
  });
});

 //for Honors cgpa
 var honors_cgpa_4 = $("#honors_cgpa_4");
   honors_cgpa_4.hide();
     	if( $('#honors_div_or_cgpa').val() == "CGPA (Out of 4)") {       		
       		$('#honors_cgpa_4').prop( "disabled", false );
       		$("#honors_cgpa_4").show();
		    }
 	$('#honors_div_or_cgpa').change(function() {   
    	if( $(this).val() == "CGPA (Out of 4)") {
       		$('#honors_cgpa_4').prop( "disabled", false );
       		$("#honors_cgpa_4").show();
    		}
		else{			
			$("#honors_cgpa_4").hide();
			}
      $('#textInput').prop( "disabled", false );     
  });

 	//for Honors others university name
 var honors_univer_others = $("#honors_univer_others");
   honors_univer_others.hide();
     	if( $('#honors_inst_name').val() == "99") {
       		
       		$('#honors_univer_others').prop( "disabled", false );
       		$("#honors_univer_others").show();
    }

 	$('#honors_inst_name').change(function() {   
    	if( $(this).val() == "99") {
       		$('#honors_univer_others').prop( "disabled", false );
       		$("#honors_univer_others").show();
    }
		else{			
			$("#honors_univer_others").hide();
			}
      $('#textInput').prop( "disabled", false );     
  });


//for Honors  honors_board_others ms_board_others
 var honors_board_others = $("#honors_board_others");
 honors_board_others.hide();

 	if( $('#honors_board_or_univ').val() == "99") {
   		
   		$('#honors_board_others').prop( "disabled", false );
   		$("#honors_board_others").show();
    }

 	$('#honors_board_or_univ').change(function() {   
    	if( $(this).val() == "99") {
       		$('#honors_board_others').prop( "disabled", false );
       		$("#honors_board_others").show();
    }
		else{			
			$("#honors_board_others").hide();
			}
      $('#textInput').prop( "disabled", false );
     
  });

 	//for Honors others subject or group name
		 var honors_groupname_others = $("#honors_groupname_others");
		   honors_groupname_others.hide();
		     	if( $('#honors_group_name').val() =="136" || $('#honors_group_name').val() =="39") {
		       		$('#honors_groupname_others').prop( "disabled", false );
		       		$("#honors_groupname_others").show();
		    }	  

		    $('#honors_exam').change(function() {
  	          $('#honors_groupname_others').prop( "disabled", true );
		      $("#honors_groupname_others").hide();
		    });
			

		 	$('#honors_group_name').change(function() {		  	
		    	if( $(this).val() == "39" || $(this).val() == "136") {
		       		
		       		$('#honors_groupname_others').prop( "disabled", false );
		       		$("#honors_groupname_others").show();
		    }
		    else{			
				$("#honors_groupname_others").hide();
			}
		      $('#textInput').prop( "disabled", false );
		     });

//for Masters ms_board_others
 var ms_board_others = $("#ms_board_others");
 ms_board_others.hide();

 	if( $('#ms_board_or_univ').val() == "99") {
   		
   		$('#ms_board_others').prop( "disabled", false );
   		$("#ms_board_others").show();
    }

 	$('#ms_board_or_univ').change(function() {   
    	if( $(this).val() == "99") {
       		$('#ms_board_others').prop( "disabled", false );
       		$("#ms_board_others").show();
    }
		else{			
			$("#ms_board_others").hide();
			}
      $('#textInput').prop( "disabled", false );
     
  });
	//for Masters others university name 
 var ms_univer_others = $("#ms_univer_others");
   ms_univer_others.hide();
     	if( $('#ms_inst_name').val() == "99") {
       		
       		$('#ms_univer_others').prop( "disabled", false );
       		$("#ms_univer_others").show();
    }

 	$('#ms_inst_name').change(function() {
   
    	if( $(this).val() == "99") {
       		$('#ms_univer_others').prop( "disabled", false );
       		$("#ms_univer_others").show();
    }
		else{			
			$("#ms_univer_others").hide();
			}
      $('#textInput').prop( "disabled", false );
     
  });

 	//for Masters cgpa
 	var ms_cgpa_4 = $("#ms_cgpa_4");
   	ms_cgpa_4.hide();
     	if( $('#ms_div_or_cgpa').val() == "CGPA (Out of 4)") {       		
       		$('#ms_cgpa_4').prop( "disabled", false );
       		$("#ms_cgpa_4").show();
    }

 	$('#ms_div_or_cgpa').change(function() {
   
    	if( $(this).val() == "CGPA (Out of 4)") {
       		$('#ms_cgpa_4').prop( "disabled", false );
       		$("#ms_cgpa_4").show();
    }
		else{			
			$("#ms_cgpa_4").hide();
			}
      $('#textInput').prop( "disabled", false );
     
  });

 	//for Masters others subject or group name
	 var ms_groupname_others = $("#ms_groupname_others");
	   ms_groupname_others.hide();
     	if( $('#honors_group_name').val() =="136" || $('#honors_group_name').val() =="39") {
       		$('#ms_groupname_others').prop( "disabled", false );
       		$("#ms_groupname_others").show();
	    }	  
	    $('#masters').change(function() {
	          $('#ms_groupname_others').prop( "disabled", true );
	       		$("#ms_groupname_others").hide();
	    });			

	 	$('#ms_group_name').change(function() {
	  	
	    	if( $(this).val() == "39" || $(this).val() == "136") {
	       		
	       		$('#ms_groupname_others').prop( "disabled", false );
	       		$("#ms_groupname_others").show();
	    }
	    else{			
			$("#ms_groupname_others").hide();
		}
	      $('#textInput').prop( "disabled", false );
	     }); 	
});
</script>
<?php include('../includes/footer.php');?>