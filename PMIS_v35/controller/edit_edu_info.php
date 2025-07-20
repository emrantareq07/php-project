<?php
// Turn off error reporting
error_reporting(0);
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('../includes/header.php');

include('../db/db.php');
 
 if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 //$user_id=$_SESSION['id'];
	 // $sql="SELECT * from edu_info where user_id='$edit_id'";
	  $sql="SELECT * from edu_info where emp_id='$emp_id'";
	// $sql="SELECT * FROM users where id='$edit_id'";
 }
$result=mysqli_query($conn,$sql);
//if(!$result){
    //die("ERROR".mysqli_error($conn));
 //while($row=mysql_fetch_object($result)){
if (mysqli_num_rows($result) > 0) {
	while($row=mysqli_fetch_array($result)){
	 
	$ssc_exam=$row['ssc_exam']; 
	$ssc_group_name=$row['ssc_group_name'];

    $sql5 = "SELECT * FROM exam where name like '%$ssc_exam%'";
    $result5 = mysqli_query($conn, $sql5);
    $row5 = mysqli_fetch_array($result5);
    $ab_id=$row5['id'];

    $sql6 = "SELECT * FROM subject_ssc where name like '%$ssc_group_name%'";
    $result6 = mysqli_query($conn, $sql6);
    $row6 = mysqli_fetch_array($result6);
    $abc_id=$row6['id'] ;


	$ssc_inst_name=$row['ssc_inst_name'];
	$ssc_board_or_univ=$row['ssc_board_or_univ'];
	$ssc_div_or_cgpa=$row['ssc_div_or_cgpa'];
	$ssc_passing_year=$row['ssc_passing_year'];

	$hsc_exam=$row['hsc_exam']; 
	$hsc_group_name=$row['hsc_group_name'];
// for hsc
	$sql11 = "SELECT * FROM exam where name like '%$hsc_exam%'";
    $result11 = mysqli_query($conn, $sql11);
    $row11 = mysqli_fetch_array($result11);
    $hsc_id11=$row11['id'];

    $sql111 = "SELECT * FROM subject_ssc where name like '%$hsc_group_name%'";
    $result111 = mysqli_query($conn, $sql111);
    $row111 = mysqli_fetch_array($result111);
    $hsc_id111=$row111['id'] ;

	$hsc_inst_name=$row['hsc_inst_name'];
	$hsc_board_or_univ=$row['hsc_board_or_univ'];
	$hsc_div_or_cgpa=$row['hsc_div_or_cgpa'];
	$hsc_passing_year=$row['hsc_passing_year'];
// for honors
	$honors_exam=$row['honors_exam']; 
	$honors_group_name=$row['honors_group_name'];

	$sql_honors = "SELECT * FROM exam where name like '%$honors_exam%'";
    $result_honors = mysqli_query($conn, $sql_honors);
    $row_honors = mysqli_fetch_array($result_honors);
    $row_honors_id=$row_honors['id'];



    $sql_honors1 = "SELECT * FROM subject_graduation where name like '%$honors_group_name%'";
    $result_honors1 = mysqli_query($conn, $sql_honors1);
    $row_honors1 = mysqli_fetch_array($result_honors1);
    $row_honors_id1=$row_honors1['id'] ;

   $honors_groupname_others=$row['honors_groupname_others']; 


	$honors_inst_name=$row['honors_inst_name'];
	$honors_univer_others=$row['honors_univer_others'];

	$honors_board_or_univ=$row['honors_board_or_univ'];
	$honors_board_others=$row['honors_board_others'];
	$honors_div_or_cgpa=$row['honors_div_or_cgpa'];
	$honors_passing_year=$row['honors_passing_year'];	
	$honors_course_duration=$row['honors_course_duration'];

	$masters=$row['masters']; 
	$ms_group_name=$row['ms_group_name'];
	// for Masters
	$sql_masters = "SELECT * FROM exam where name like '%$masters%'";
    $result_masters = mysqli_query($conn, $sql_masters);
    $row_masters = mysqli_fetch_array($result_masters);
    $row_masters_id=$row_masters['id'];

    $sql_masters1 = "SELECT * FROM subject_graduation where name like '%$ms_group_name%'";
    $result_masters1 = mysqli_query($conn, $sql_masters1);
    $row_masters1 = mysqli_fetch_array($result_masters1);
    $row_masters_id1=$row_masters1['id'] ;
	
	$ms_groupname_others=$row['ms_groupname_others']; 

	$ms_inst_name=$row['ms_inst_name'];
	$ms_univer_others=$row['ms_univer_others'];
	$ms_board_or_univ=$row['ms_board_or_univ'];
	$ms_board_others=$row['ms_board_others'];
	
	$ms_div_or_cgpa=$row['ms_div_or_cgpa'];
	$ms_cgpa_4=$row['ms_cgpa_4'];
	
	$ms_passing_year=$row['ms_passing_year'];	
	$ms_course_duration=$row['ms_course_duration'];

?>
    <div class="container mt-2 p-1 my-1 border rounded shadow-lg bg-white" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-secondary mt-2 p-1 my-1 text-uppercase"><b>Update Academic / Educational information</b></h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			  <?php
				if(@$_GET['emp_id'])
				{?>
				<div class="alert alert-success " role="alert">
				  <b>Data Updated Successfully!!!</b>
				</div>
				<?php }?>

		<div class="panel-body">
			<form method="POST" id="form1" name="form1" action="update_edu_info.php" enctype="multipart/form-data">
				<fieldset>
			
	<div class="row">
		<!-- Educational Info-->
		<div class="col-sm-3"> 
			<div class="card border border-primary ">
			  <div class="card-header text-white" style="background-color: #461a75">
				<label for="ssc_exam">SSC/ Equivalent or Eight Passed</label>
			  </div>
			<div class="card-body">
				<div class="form-group">
					 <select class="form-control" id="ssc_exam" name="ssc_exam" tabindex="6">				
						<?php					
						echo "<option value='".$row5['id']."'>".$row['ssc_exam']."</option>";
                        // $sql10 = "SELECT * FROM exam where id In(2,3,4,5,6,32)";
                        $sql10 = "SELECT * FROM exam where id In(34,2,3,4,5,6,33,32)";
						$result10 = mysqli_query($conn, $sql10);
						while($row10 = mysqli_fetch_array($result10))
						{
						 echo "<option value='".$row10['id']."'>".$row10['name']."</option>";
						}?>	
					 </select>
					</div>								
						
                    <div class="form-group">
                        <label for="ssc_group_name">Subject/Group/Degree:</label>
                        <select class="form-control" id="ssc_group_name" name="ssc_group_name" tabindex="7">
                         <!-- <option value="<?php echo $abc_id?>" disabled selected><?php echo $ssc_group_name?></option> -->
                         	<?php
                         	echo "<option value='".$row6['id']."'>".$row['ssc_group_name']."</option>";

                         	?>                          
                        </select>
                       <script src="https://code.jquery.com/jquery-3.5.1.min.js"  crossorigin="anonymous"></script>
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
				<label for="ssc_inst_name">School/Institute Name:</label>
				 <input class="form-control" placeholder="Enter Institute" type="text" name="ssc_inst_name" id="ssc_inst_name" value="<?=$row['ssc_inst_name'];?>">
				</div>

				<div class="form-group">
				<label for="ssc_board_or_univ">Board:</label>	
				 <select class="form-control" id="ssc_board_or_univ" name="ssc_board_or_univ" >	
					<option value=""  selected>--Select--</option>
					  <option value="Dhaka"  <?=$ssc_board_or_univ == 'Dhaka' ? ' selected="selected"' : '';?> >Dhaka</option>
					 <option value="Jessore" <?=$ssc_board_or_univ == 'Jessore' ? ' selected="selected"' : '';?>>Jessore</option>
					  <option value="Barisal" <?=$ssc_board_or_univ == 'Barisal' ? ' selected="selected"' : '';?>>Barisal</option>
					  <option value="Sylhet" <?=$ssc_board_or_univ == 'Sylhet' ? ' selected="selected"' : '';?>>Sylhet</option>
					  <option value="Cumilla" <?=$ssc_board_or_univ == 'Cumilla' ? ' selected="selected"' : '';?>>Cumilla</option>
					  <option value="Rajshahi" <?=$ssc_board_or_univ == 'Rajshahi' ? ' selected="selected"' : '';?>>Rajshahi</option>
					  <option value="Rangpur" <?=$ssc_board_or_univ == 'Rangpur' ? ' selected="selected"' : '';?>>Rangpur</option>
					  <option value="Chittagong" <?=$ssc_board_or_univ == 'Chittagong' ? ' selected="selected"' : '';?>>Chittagong</option>
					  <option value="Madrasha" <?=$ssc_board_or_univ == 'Madrasha' ? ' selected="selected"' : '';?>>Madrasha</option>
					  <option value="Techincal (BTEB)" <?=$ssc_board_or_univ == 'Techincal (BTEB)' ? ' selected="selected"' : '';?>>Techincal (BTEB)</option>
					  <option value="Bangladesh Open University" <?=$ssc_board_or_univ == 'Bangladesh Open University' ? ' selected="selected"' : '';?>>Bangladesh Open University</option>

						  <!-- <option value="M.Com"  <?=$hsc_exam == 'M.Com' ? ' selected="selected"' : '';?> >M.Com</option> -->
					 </select>
				</div>
				<!-- <div class="form-group">
				<label for="ssc_div_or_cgpa">Division/Class/CGPA:</label>
				 <input class="form-control" placeholder="Enter Division/Class/CGPA" type="text" name="ssc_div_or_cgpa" id="ssc_div_or_cgpa" value="<?=$row['ssc_div_or_cgpa'];?>">
				</div> -->
				<div class="form-group">
					<label for="ssc_div_or_cgpa">Division/Class/CGPA:</label>
					 <select class="form-control" id="ssc_div_or_cgpa" name="ssc_div_or_cgpa" >	
							<option value="" disabled selected>--Select--</option>
							 <option value="1st Class" <?=$ssc_div_or_cgpa == '1st Class' ? ' selected="selected"' : '';?>>1st Class</option>
							 <option value="2nd Class" <?=$ssc_div_or_cgpa == '2nd Class' ? ' selected="selected"' : '';?>>2nd Class</option>
							<option value="3rd Class" <?=$ssc_div_or_cgpa == '3rd Class' ? ' selected="selected"' : '';?>>3rd Class</option>
							 <option value="CGPA (Out of 5)" <?=$ssc_div_or_cgpa == 'CGPA (Out of 5)' ? ' selected="selected"' : '';?>>CGPA (Out of 5)</option>
							 <option value="Passed" <?=$ssc_div_or_cgpa == 'Passed' ? ' selected="selected"' : '';?>>Passed</option>
							 
						 </select>
						<input class="form-control mt-2" placeholder="Enter CGPA (Out of 5)" type="text" name="ssc_cgpa_5" id="ssc_cgpa_5" value="<?=$row['ssc_cgpa_5'];?>">	
				</div>

				<div class="form-group">
				<label for="ssc_passing_year">Passing year</label>				
				  <select class="form-control" id="ssc_passing_year" name="ssc_passing_year" >
				  <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT year FROM passing_year";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['year'];
					    ?>
					    <?php
					    if(!empty($ssc_passing_year) && $ssc_passing_year== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
				</div>
				</div>
				</div>
			</div>
		<!--Second Column-->
				<div class="col-sm-3"> 
				<div class="card border border-warning">
			  <div class="card-header text-white" style="background-color: #461a75">
				<label for="hsc_exam">HSC or Equivalent Level</label>
			  </div>
			<div class="card-body">
				
				<div class="form-group">					
					  <select class="form-control" id="hsc_exam" name="hsc_exam" tabindex="6">					
						<?php					
						echo "<option value='".$row11['id']."'>".$row['hsc_exam']."</option>";
                        $sql_hsc_exam = "SELECT * FROM exam where id In(7,8,9,10,11,12,13,14,15,42,43)";
						$result_hsc_exam = mysqli_query($conn, $sql_hsc_exam);
						while($row_hsc_exam = mysqli_fetch_array($result_hsc_exam))
						{
						 echo "<option value='".$row_hsc_exam['id']."'>".$row_hsc_exam['name']."</option>";
						}?>	
					 </select>
					</div>
				 <div class="form-group">
                        <label for="hsc_group_name">Subject/Group/Degree:</label>
                        <select class="form-control" id="hsc_group_name" name="hsc_group_name" tabindex="7">
                         <!-- <option value="<?php echo $abc_id?>" disabled selected><?php echo $ssc_group_name?></option> -->
                         	<?php
                         	echo "<option value='".$row111['id']."'>".$row['hsc_group_name']."</option>";
                         	?>                          
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
				<label for="hsc_inst_name">College/Institute Name:</label>
				 <input class="form-control" placeholder="Enter Institute" type="text" name="hsc_inst_name" id="hsc_inst_name" value="<?=$row['hsc_inst_name'];?>">				
				</div>

				<div class="form-group">
				<label for="hsc_board_or_univ">Board:</label>
				 <select class="form-control" id="hsc_board_or_univ" name="hsc_board_or_univ" >
					<option value="" disabled selected>--Select--</option>
					<option value="Dhaka" <?=$hsc_board_or_univ == 'Dhaka' ? ' selected="selected"' : '';?>>Dhaka</option>
					  <option value="Jessore" <?=$hsc_board_or_univ == 'Jessore' ? ' selected="selected"' : '';?>>Jessore</option>
					  <option value="Barisal" <?=$hsc_board_or_univ == 'Barisal' ? ' selected="selected"' : '';?>>Barisal</option>
					  <option value="Sylhet" <?=$hsc_board_or_univ == 'Sylhet' ? ' selected="selected"' : '';?>>Sylhet</option>
					  <option value="Cumilla" <?=$hsc_board_or_univ == 'Cumilla' ? ' selected="selected"' : '';?>>Cumilla</option>
					  <option value="Rajshahi" <?=$hsc_board_or_univ == 'Rajshahi' ? ' selected="selected"' : '';?>>Rajshahi</option>
					  <option value="Rangpur" <?=$hsc_board_or_univ == 'Rangpur' ? ' selected="selected"' : '';?>>Rangpur</option>
					  <option value="Chittagong" <?=$hsc_board_or_univ == 'Chittagong' ? ' selected="selected"' : '';?>>Chittagong</option>
					  <option value="Madrasha" <?=$hsc_board_or_univ == 'Madrasha' ? ' selected="selected"' : '';?>>Madrasha</option>
					  <option value="Techincal (BTEB)" <?=$hsc_board_or_univ == 'Techincal (BTEB)' ? ' selected="selected"' : '';?>>Techincal (BTEB)</option>
					  <option value="Bangladesh Open University" <?=$hsc_board_or_univ == 'Bangladesh Open University' ? ' selected="selected"' : '';?>>Bangladesh Open University</option>
					 </select>
				</div>
		
				<div class="form-group">
					<label for="hsc_div_or_cgpa">Division/Class/CGPA:</label>
				
					 <select class="form-control" id="hsc_div_or_cgpa" name="hsc_div_or_cgpa" >		
						<option value="" disabled selected>--Select--</option>
						<option value="1st Class" <?=$hsc_div_or_cgpa == '1st Class' ? ' selected="selected"' : '';?>>1st Class</option>
						 <option value="2nd Class" <?=$hsc_div_or_cgpa == '2nd Class' ? ' selected="selected"' : '';?>>2nd Class</option>
						 <option value="3rd Class" <?=$hsc_div_or_cgpa == '3rd Class' ? ' selected="selected"' : '';?>>3rd Class</option>
						 <option value="CGPA (Out of 4)" <?=$hsc_div_or_cgpa == 'CGPA (Out of 4)' ? ' selected="selected"' : '';?>>CGPA (Out of 4)</option>
						 <option value="CGPA (Out of 5)" <?=$hsc_div_or_cgpa == 'CGPA (Out of 5)' ? ' selected="selected"' : '';?>>CGPA (Out of 5)</option>

						 <option value="Passed" <?=$hsc_div_or_cgpa == 'Passed' ? ' selected="selected"' : '';?>>Passed</option>							 
						 </select>                  
						<input class="form-control" placeholder="Enter CGPA (Out of 4)" type="text" name="hsc_cgpa_4" id="hsc_cgpa_4" value="<?=$row['hsc_cgpa_5'];?>">

						<input class="form-control mt-2" placeholder="Enter CGPA (Out of 5)" type="text" name="hsc_cgpa_5" id="hsc_cgpa_5" value="<?=$row['hsc_cgpa_5'];?>">
									
				</div>

				<div class="form-group">
				<label for="hsc_passing_year">Passing year</label>				
				<select class="form-control" id="hsc_passing_year" name="hsc_passing_year" >
				  <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT year FROM passing_year";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['year'];
					    ?>
					    <?php
					    if(!empty($hsc_passing_year) && $hsc_passing_year== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
				</div>
					
				</div>
				</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-3"> 
					<div class="card border border-warning">
					  <div class="card-header">
						<label for="honors_exam">Graduation or Equivalent Level</label>
					  </div>
					<div class="card-body">
						<div class="form-group">
						<select class="form-control" id="honors_exam" name="honors_exam" >					
					  <?php										
						echo "<option value='".$row_honors['id']."'>".$row['honors_exam']."</option>";
                        $sql_honors_exam = "SELECT * FROM exam where id In(16,17,18,19,20,21,22,23,36,37,38,39,41)";
						$result_honors_exam = mysqli_query($conn, $sql_honors_exam);
						while($row_honors_exam = mysqli_fetch_array($result_honors_exam))
						{
						 echo "<option value='".$row_honors_exam['id']."'>".$row_honors_exam['name']."</option>";
						}?>
					 </select>
					</div>

				 <div class="form-group">
                        <label for="honors_group_name">Subject/Group/Degree:</label>
                        <select class="form-control" id="honors_group_name" name="honors_group_name" tabindex="7">                         
                         	<?php
                         	echo "<option value='".$row_honors1['id']."'>".$row['honors_group_name']."</option>";
                         	?>                          
                        </select>
                        <input class="form-control mt-2" placeholder="Enter Subject" type="text" name="honors_groupname_others" id="honors_groupname_others" value="<?=$row['honors_groupname_others'];?>">                      
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
					    <option value="">--Select--</option>
					    <?php 
					    $query_university ="SELECT university_name FROM university_list order by university_name Asc";
					    $result_university = $conn->query($query_university);
					    if($result_university->num_rows> 0){
					        while($optionData=$result_university->fetch_assoc()){
					        $option =$optionData['university_name'];
					    ?>
					    <?php
					    if(!empty($honors_inst_name) && $honors_inst_name== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter University" type="text" name="honors_univer_others" id="honors_univer_others" value="<?=$row['honors_univer_others'];?>">
                	</div>

				<div class="form-group"><label for="honors_board_or_univ">Board/University/Institute:</label>
                    <select name="honors_board_or_univ" id="honors_board_or_univ" class="form-control">
					    <option value="">--Select--</option>
					    <?php 
					    $query_university ="SELECT university_name FROM university_list order by university_name Asc";
					    $result_university = $conn->query($query_university);
					    if($result_university->num_rows> 0){
					        while($optionData=$result_university->fetch_assoc()){
					        $option =$optionData['university_name'];
					    ?>
					    <?php
					    if(!empty($honors_board_or_univ) && $honors_board_or_univ== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter Board/University" type="text" name="honors_board_others" id="honors_board_others" value="<?=$row['honors_board_others'];?>">
                	</div>				

				<div class="form-group">
					<label for="honors_div_or_cgpa">Division/Class/CGPA:</label>
					 <select class="form-control" id="honors_div_or_cgpa" name="honors_div_or_cgpa" >
						<option value="" disabled selected>--Select--</option>
						 <option value="1st Class" <?=$honors_div_or_cgpa == '1st Class' ? ' selected="selected"' : '';?>>1st Class</option>
						 <option value="2nd Class" <?=$honors_div_or_cgpa == '2nd Class' ? ' selected="selected"' : '';?>>2nd Class</option>
						 <option value="3rd Class" <?=$honors_div_or_cgpa == '3rd Class' ? ' selected="selected"' : '';?>>3rd Class</option>
						<option value="CGPA (Out of 4)" <?=$honors_div_or_cgpa == 'CGPA (Out of 4)' ? ' selected="selected"' : '';?>>CGPA (Out of 4)</option>						 
						 <option value="Passed" <?=$honors_div_or_cgpa == 'Passed' ? ' selected="selected"' : '';?>>Passed</option>							 
						 </select>
						<input class="form-control mt-2" placeholder="Enter CGPA (Out of 4)" type="text" name="honors_cgpa_4" id="honors_cgpa_4" value="<?=$row['honors_cgpa_4'];?>">
					
				</div>

				<div class="form-group">
				<label for="honors_passing_year">Passing year</label>
				 <select class="form-control" id="honors_passing_year" name="honors_passing_year" >
				  <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT year FROM passing_year";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['year'];
					    ?>
					    <?php
					    if(!empty($honors_passing_year) && $honors_passing_year== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
				</div>
				<div class="form-group">
				<label for="honors_course_duration">Course Duration</label>
				<select class="form-control" id="honors_course_duration" name="honors_course_duration" >
						<option value="" disabled selected>Select</option>						 
						<option value="02 Years" <?=$honors_course_duration == '02 Years' ? ' selected="selected"' : '';?> >02 Years</option>
						  <option value="03 Years"  <?=$honors_course_duration == '03 Years' ? ' selected="selected"' : '';?> >03 Years</option>
						  <option value="04 Years"  <?=$honors_course_duration == '04 Years' ? ' selected="selected"' : '';?> >04 Years</option>
						  <option value="05 Years"  <?=$honors_course_duration == '05 Years' ? ' selected="selected"' : '';?> >05 Years</option>					 
					  </select>
							</div>
						</div>
					</div>				
				</div>
				
				<!--4th Column-->
				<div class="col-sm-3"> 
					<div class="card border border-warning">
					  <div class="card-header">
						<label for="masters">Masters or Equivalent Level</label>
					  </div>
					<div class="card-body">
						<div class="form-group">
						<select class="form-control" id="masters" name="masters" >					
						<?php									
						echo "<option value='".$row_masters['id']."'>".$row['masters']."</option>";
                        $sql_masters_exam = "SELECT * FROM exam where id In(24,25,26,27,28,29,30,31,40)";
						$result_masters_exam = mysqli_query($conn, $sql_masters_exam);
						while($row_masters_exam = mysqli_fetch_array($result_masters_exam))
						{
						 echo "<option value='".$row_masters_exam['id']."'>".$row_masters_exam['name']."</option>";
						}?>
					  </select>
					</div>

				<div class="form-group">
                        <label for="ms_group_name">Subject/Group/Degree:</label>
                        <select class="form-control" id="ms_group_name" name="ms_group_name" tabindex="7"><?php
                         echo "<option value='".$row_masters1['id']."'>".$row['ms_group_name']."</option>";
                         ?>                          
                        </select>
                        <input class="form-control mt-2" placeholder="Enter Others" type="text" name="ms_groupname_others" id="ms_groupname_others" value="<?=$row['ms_groupname_others'];?>">                      
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

				<div class="form-group"><label for="ms_inst_name">University/NU/Institute Name:</label>
                    <select name="ms_inst_name" id="ms_inst_name" class="form-control">
					    <option value="">--Select--</option>
					    <?php 
					    $query_university_ms ="SELECT university_name FROM university_list order by university_name Asc";
					    $result_university_ms = $conn->query($query_university_ms);
					    if($result_university_ms->num_rows> 0){
					        while($optionData=$result_university_ms->fetch_assoc()){
					        $option =$optionData['university_name'];
					    ?>
					    <?php
					    if(!empty($ms_inst_name) && $ms_inst_name== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter University" type="text" name="ms_univer_others" id="ms_univer_others" value="<?=$row['ms_univer_others'];?>">
                	</div>


				<div class="form-group"><label for="ms_board_or_univ">Board/University/Institute:</label>
                    <select name="ms_board_or_univ" id="ms_board_or_univ" class="form-control">
					    <option value="">--Select--</option>
					    <?php 
					    $query_university1 ="SELECT university_name FROM university_list order by university_name asc";
					    $result_university1 = $conn->query($query_university1);
					    if($result_university1->num_rows> 0){
					        while($optionData=$result_university1->fetch_assoc()){
					        $option1 =$optionData['university_name'];
					    ?>
					    <?php
					    if(!empty($ms_board_or_univ) && $ms_board_or_univ== $option1){
					    ?>
					    <option value="<?php echo $option1; ?>" selected><?php echo $option1; ?> </option>
					    <?php 
							continue;
					   }?>
					    <option value="<?php echo $option1; ?>" ><?php echo $option1; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
					<input class="form-control mt-2" placeholder="Enter Board/University" type="text" name="ms_board_others" id="ms_board_others" value="<?=$row['ms_board_others'];?>">
                	</div>

				<div class="form-group">
					<label for="ms_div_or_cgpa">Division/Class/CGPA:</label>
					 <select class="form-control" id="ms_div_or_cgpa" name="ms_div_or_cgpa" >
							<option value="" disabled selected>--Select--</option>
							 <option value="1st Class" <?=$ms_div_or_cgpa == '1st Class' ? ' selected="selected"' : '';?>>1st Class</option>
							 <option value="2nd Class" <?=$ms_div_or_cgpa == '2nd Class' ? ' selected="selected"' : '';?>>2nd Class</option>
							 <option value="3rd Class" <?=$ms_div_or_cgpa == '3rd Class' ? ' selected="selected"' : '';?>>3rd Class</option>
							<option value="CGPA (Out of 4)" <?=$ms_div_or_cgpa == 'CGPA (Out of 4)' ? ' selected="selected"' : '';?>>CGPA (Out of 4)</option>
							 
							 <option value="Passed" <?=$ms_div_or_cgpa == 'Passed' ? ' selected="selected"' : '';?>>Passed</option>
							 
						 </select>

						<input class="form-control mt-2" placeholder="Enter CGPA (Out of 4)" type="text" name="ms_cgpa_4" id="ms_cgpa_4" value="<?=$row['ms_cgpa_4'];?>">

						
				</div>



				<div class="form-group">
				<label for="ms_passing_year">Passing year</label>
				<!--  <input class="form-control" placeholder="Enter Passing Year" type="text" name="ms_passing_year" id="ms_passing_year" value="<?=$row['ms_passing_year'];?>"> -->

				<select class="form-control" id="ms_passing_year" name="ms_passing_year" >
				  <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT year FROM passing_year";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['year'];
					    ?>
					    <?php
					    if(!empty($ms_passing_year) && $ms_passing_year== $option){
					    ?>
					    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
					    <?php 
					continue;
					   }?>
					    <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
					   <?php
					    }}
					    ?>
					</select>
				</div>
				<div class="form-group">
				<label for="ms_course_duration">Course Duration</label>
				<select class="form-control" id="ms_course_duration" name="ms_course_duration" >
						<option value="" disabled selected>Select</option>
						 <option value="01 Years" <?=$ms_course_duration == '01 Years' ? ' selected="selected"' : '';?> >01 Years</option>
						  <option value="1.5 Years"  <?=$ms_course_duration == '1.5 Years' ? ' selected="selected"' : '';?> >1.5 Years</option>
						<option value="02 Years" <?=$ms_course_duration == '02 Years' ? ' selected="selected"' : '';?> >02 Years</option>
						  <option value="03 Years"  <?=$ms_course_duration == '03 Years' ? ' selected="selected"' : '';?> >03 Years</option>
						  <option value="04 Years"  <?=$ms_course_duration == '04 Years' ? ' selected="selected"' : '';?> >04 Years</option>
						  <option value="05 Years"  <?=$ms_course_duration == '05 Years' ? ' selected="selected"' : '';?> >05 Years</option>
					 
					  </select>
				 <!--<input class="form-control" placeholder="Enter Passing Year" type="text" name="course_duration" id="course_duration" value="<?=$row['course_duration'];?>">
				-->
				</div>
				</div>
				</div>				
				</div>
				</div>
				<div class="row">
				
				<div class="col-sm-4"> <center>
					<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary "><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a>
				</center> </div>
				<div class="col-sm-6">  </div>
				<div class="col-sm-2"></div>				
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

 <?php
		}
	}
 else {
 	?>
 	
	<div class="container mt-2 p-1 my-1 border shadow-lg bg-muted rounded" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Educational information</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			<div class='col-sm-4'></div>
				<div class='col-sm-4'><h5 class='btn btn-danger btn-block text-left'> Record Not Found!!!</h4>
				<h5><center>
					<a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a>
				</center>
				
				</h5>
				</div>
				<div class='col-sm-4'></div>
				</div>	
    		</div>	
    	</div>
    	
		
	 <?php
	}
	?>
<script type='text/javascript'>

$(document).ready(function() {
	// document.getElementById("ssc_cgpa_4").style.display="none";
	// document.getElementById("ssc_cgpa_5").style.display="none";
 // $("#ssc_cgpa_4").hide();
 // $("#ssc_cgpa_5").hide();

// var ssc_cgpa_4 = $("#ssc_cgpa_4");
  var ssc_cgpa_5 = $("#ssc_cgpa_5");

  //ssc_cgpa_4.hide();
  ssc_cgpa_5.hide();

     	if( $('#ssc_div_or_cgpa').val() == "CGPA (Out of 5)") {
       		// $('#ssc_cgpa_4').prop( "disabled", true );
       		// $("#ssc_cgpa_4").hide();
       		$('#ssc_cgpa_5').prop( "disabled", false );
       		$("#ssc_cgpa_5").show();

    }

		// else{
		// 	//$("#ssc_cgpa_4").hide();
		// 	$("#ssc_cgpa_5").hide();
		// 	}
//for ssc
 	$('#ssc_div_or_cgpa').change(function() {
  	// if( $(this).val() == "1st Class") {
   //     		// $('#ssc_cgpa_4').prop( "disabled", true );
   //     		// $("#ssc_cgpa_4").hide();
   //     		$('#ssc_cgpa_5').prop( "disabled", true );
   //     		$("#ssc_cgpa_5").hide();
   //  } 
   //  else {  
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

 	if( $('#honors_inst_name').val() == "Others") {
   		
   		$('#honors_univer_others').prop( "disabled", false );
   		$("#honors_univer_others").show();

    }

 	$('#honors_inst_name').change(function() {
   
    	if( $(this).val() == "Others") {
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

 	if( $('#honors_board_or_univ').val() == "Others") {
   		
   		$('#honors_board_others').prop( "disabled", false );
   		$("#honors_board_others").show();

    }

 	$('#honors_board_or_univ').change(function() {
   
    	if( $(this).val() == "Others") {
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

 	if( $('#ms_board_or_univ').val() == "Others") {
   		
   		$('#ms_board_others').prop( "disabled", false );
   		$("#ms_board_others").show();

    }

 	$('#ms_board_or_univ').change(function() {
   
    	if( $(this).val() == "Others") {
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

     	if( $('#ms_inst_name').val() == "Others") {
       		
       		$('#ms_univer_others').prop( "disabled", false );
       		$("#ms_univer_others").show();

    }

 	$('#ms_inst_name').change(function() {
   
    	if( $(this).val() == "Others") {
       		$('#ms_univer_others').prop( "disabled", false );
       		$("#ms_univer_others").show();
    }
		else{			
			$("#ms_univer_others").hide();
			}
      $('#textInput').prop( "disabled", false );
     
  });
 	 	//for Masters vgpa
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
<!-- <a class='btn btn-primary' href='view_profile_details.php?emp_id=<?php //echo $_SESSION['emp_id'] ?>''> Previous page </a> -->
<?php include('../includes/footer.php');?>