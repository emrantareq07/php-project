<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
// $_SESSION['dob']=$_SESSION['dob'];
// $dob=$_SESSION['dob'];
include('../includes/header.php');

include('../db/db.php');
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val']; 
  }
  	$tomorrow = date('Y-m-d');
	$year=date('Y',strtotime($tomorrow));			
	$year1=$year+1;
	$date4=$year.'-07-01';
	$date3=$year1.'-07-01';
	 //$last_incr_date=$_POST['last_incr_date'];
	 //$incr_due_date=$_POST['incr_due_date'];
	$last_incr_date=$date4;
	$next_incr_date =$date3;

 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	 //$edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];

	 $sql = "SELECT * FROM job_desc WHERE emp_id='$emp_id'";
    $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo '<script type="text/javascript">';
    echo 'alert("Service Information Already taken!");';
    // echo 'window.location.href="add_job_desc.php"';
    echo 'window.location.href="../login/user_dashboard.php"';
    echo '</script>';
  } else {	 
	 $dob=$_POST['dob'];	 
	 $doj=$_POST['doj'];
	 $doc=$_POST['doc'];
	 // $dolp=$_POST['dolp'];	 
	 $police_verification=$_POST['police_verification'];
	//$joining_date="06-02-1987";
	//$dob2 = str_replace('/', '-', $dob);
	 
	 // $prl = date('Y-m-d', strtotime('-1 day', strtotime($joining_date. '+59 years')));
	$prl = date('Y-m-d', strtotime('-1 day', strtotime($dob. '+59 years')));
	// $prl = date('Y-m-d', strtotime(strtotime($dob. '+59 years'))'-1 day');

	$pension_start_date=date('Y-m-d', strtotime($prl. '+1 years'));
	$place_of_posting=$_POST['place_of_posting'];
	 // $place_of_posting_id=$_POST['place_of_posting'];
	 // $sql_place_of_posting="SELECT place_of_posting from office_list where id ='place_of_posting_id'"; 
		//  $result_place_of_posting=mysqli_query($conn,$sql_place_of_posting);
		//  while($row = mysqli_fetch_array($result_place_of_posting))
		// 			{						
		// 				$place_of_posting=$row['place_of_posting'];
						
		// 			}


	 $join_designation=$_POST['join_designation'];	 
	 $cadre_id=$_POST['cadre'];
	 $sql_cadre="SELECT cadre from cadre where id ='$cadre_id'"; 
		 $result=mysqli_query($conn,$sql_cadre);
		 while($row = mysqli_fetch_array($result))
					{						
						$cadre=$row['cadre'];
						
					}
	 $sub_cadre_id=$_POST['sub_cadre'];
	 $sql_sub_cadre="SELECT sub_cadre from sub_cadre where id ='$sub_cadre_id'"; 
		 $result=mysqli_query($conn,$sql_sub_cadre);
		 while($row = mysqli_fetch_array($result))
					{						
						$sub_cadre=$row['sub_cadre'];
						
					}

	$sub_cadre_header_ext=$_POST['sub_cadre_header_ext'];				
	 $appoint_type=$_POST['appoint_type'];
	 $others=$_POST['others'];
	 $last_promo_date=$_POST['last_promo_date'];
	
	if($last_promo_date == ''){
	    $next_promo_date = date('Y-m-d', strtotime($doj. '+5 years'));
	}
	else{
	    $next_promo_date = date('Y-m-d', strtotime($last_promo_date. '+5 years'));
	}
	 
	$tomorrow = date('Y-m-d');
	$year=date('Y',strtotime($tomorrow));			
	$year1=$year+1;	
	$date4=$year.'-07-01';
	$date3=$year1.'-07-01';	 
	 // $last_incr_date=$_POST['last_incr_date'];
	 // $next_incr_date=$_POST['next_incr_date'];
	
    $last_incr_date = date('Y-m-d', strtotime($date4));
    $next_incr_date = date('Y-m-d', strtotime($date3));
	 // $next_incr_date = date('Y-m-d', strtotime($last_incr_date. '+1 years'));
	 
	 $grade_id=$_POST['grade'];
	 $sql_grade="SELECT grade from grade where id ='$grade_id'"; 
	 $result=mysqli_query($conn,$sql_grade);
	 while($row = mysqli_fetch_array($result)){						
						$grade=$row['grade'];						
					}
	 $basic_id=$_POST['basic'];
	 $sql_basic="SELECT basic from basic where id ='$basic_id'"; 
	 $result=mysqli_query($conn,$sql_basic);
	 while($row = mysqli_fetch_array($result)){
						
						$basic=$row['basic'];
						
					}
	 $scale_id=$_POST['scale'];
	 $sql_scale="SELECT scale from pay_scale_2015 where id ='$scale_id'"; 
	 $result=mysqli_query($conn,$sql_scale);
	 while($row = mysqli_fetch_array($result))
				{
					
					$scale=$row['scale'];
					
				}
	 //$sql="SELECT * FROM users where id='$edit_id'";
	 $job_status=$_POST['job_status'];	
	 $deputation_org=$_POST['deputation_org'];
	 // $incr_granted=($basic*5)/100;//37280*5/100=1864
	 $incr_granted=0;
	 $basic_after_incr=$basic;
	 $granted_promo_date=$_POST['granted_promo_date'];	
	 $nature_of_promo=$_POST['nature_of_promo'];	
	 $d_nothi_id=$_POST['d_nothi_id'];
	 $tin_no=$_POST['tin_no'];	 
	 			
	 $sql="INSERT INTO job_desc (user_id, emp_id, 
	 dob,doj,doc,police_verification,prl,pension_start_date,place_of_posting,d_nothi_id,tin_no,join_designation,
	 cadre,sub_cadre,sub_cadre_header_ext,appoint_type,others,last_promo_date,next_promo_date,last_incr_date,next_incr_date,grade,basic,scale,job_status,deputation_org,incr_granted,basic_after_incr,granted_promo_date,nature_of_promo) 
	 VALUES ('{$user_id}','{$emp_id}','{$dob}','{$doj}','{$doc}','{$police_verification}',
	 '{$prl}','{$pension_start_date}','{$place_of_posting}','{$d_nothi_id}','{$tin_no}','{$join_designation}',
	 '{$cadre}','{$sub_cadre}','{$sub_cadre_header_ext}','{$appoint_type}','{$others}',
	 '{$last_promo_date}','{$next_promo_date}','{$last_incr_date}','{$next_incr_date}','{$grade}','{$basic}','{$scale}','{$job_status}','{$deputation_org}','{$incr_granted}','{$basic_after_incr}','{$granted_promo_date}','{$nature_of_promo}')";
	//$result=mysqli_query($conn,$sql);
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
			// header("Location: add_job_desc.php?emp_id=".'3');
			
		}

	//Important use Next time

		// $sql_basic_info = "SELECT designation FROM basic_info WHERE emp_id='$emp_id'";
  //   	$result_basic_info = mysqli_query($conn, $sql_basic_info);
  //   	$row_basic_info=mysqli_fetch_array($result_basic_info);
  //   	$designation=$row_basic_info['designation'];

		// $service_type='After Joining';

		// $sql="INSERT INTO service_history (emp_id,service_type,place_of_posting,designation,nature_of_promo,grade,scale,basic)VALUES ('{$emp_id}','{$service_type}','{$place_of_posting}','{$designation}','{$nature_of_promo}','{$grade}','{$scale}','{$basic_after_incr}')";
		// $result=mysqli_query($conn,$sql);

			// header("location:view_profile_details.php?submitted=successfully");
			// header("location:add_job_desc.php?submitted=successfully");
	}
	else
		print mysqli_error();
 }
}
?>

 <script type="text/javascript">
       // $(function() {
               // $("#dob").datepicker({ dateFormat: "dd-mm-yyyy" }).val()
       // });
  </script>
  <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
 <div class="container mt-2 p-1 my-1 border border-success border-2 rounded shadow-lg bg-white" onload='document.form1.edu_info.focus()'>
 	  <?php
		if(@$_GET['3'])
		{?>
		<div class="alert alert-success" role="alert">
		  <b>Data Inserted Successfully!!!</b>
		</div>
		<?php }?>

    	<h2 class="page-header text-center text-success mt-2 p-1 my-1 text-uppercase"><b>Add Service information</b></h2>
    	<div class="row">		
    		<div class="col-sm-12">    			
		<div class="panel-body">
			<!-- <form method="POST" id="form1" name="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data"> -->
	<form method="POST" id="form1" name="form1" action="add_job_desc.php?val=<?php echo $val; ?>" enctype="multipart/form-data">
				<fieldset>
			
	<div class="row">			
			<!-- 1st column-->
		<div class="col-sm-4"> 
				<div class="card border border-warning">
			  <div class="card-header">
				<label for="ssc_exam">Service Info. Portion-1</label>
			  </div>
			<div class="card-body">
				<div class="form-group">
					<label for="dob">Date of Birth:</label><i className="fa fa-star text-warning"></i>
					 <input class="form-control" placeholder="Enter DOB" type="date" name="dob" id="dob"  
					 onchange="calculateAmount(this.value)" required value="<?=$row['dob'];?>">
				</div>	
				<div class="form-group">
					<label for="doj">Date of Joining:</label>
					 <input class="form-control" placeholder="Enter DOJ" type="date" name="doj" id="doj" value="<?=$row['doj'];?>" required>
				</div>

					<div class="form-group"><label for="police_verification">Police Verification Status:</label>
					<select class="form-control" id="police_verification" name="police_verification" >
						
					<option value="" >--Select--</option>
						 
					<option value="Complete">Complete</option>
					<option value="Incomplete">Incomplete</option>
					<!-- <option value="Others">Others</option> -->
					
					</select>
				</div>


				<div class="form-group">
					<label for="doc">Date of Confiramtion:</label>
					 <input class="form-control" placeholder="Enter DOC" type="date" name="doc" id="doc" value="<?=$row['doc'];?>">
				</div>
				<!-- <div class="form-group">
					<label for="dolp">Date of Last Promotion:</label>
					 <input class="form-control" placeholder="Enter Last Promotion" type="date" name="dolp" id="dolp" value="<?=$row['dolp'];?>" tabindex="3">
				</div> -->
				<div class="form-group">
					<label for="prl">PRL Date:</label>
					 <input class="form-control" type="text" name="prl" id="prl" placeholder="DD-MM-YYYY"  value="" readonly >
				 <script type="text/javascript">
				 
				 function calculateAmount(val) {
					 var userinput = document.getElementById("dob").value;
					 var dob = new Date(userinput);

					// var date = new Date();
				  var d2 = dob.getDate();
				  var m2 = 1 + dob.getMonth();
				  //var y2 = dob.getFullYear();
				 
				  if(d2==1){

				   if(m2==1){
                    d3=31;

				var m4 = 12;
                 var y2= (dob.getFullYear()+58);
				 var datestring = d3 + "-" + m4 + "-" +y2;	
				var prl = document.getElementById('prl').innerHTML.value;
                prl = datestring;
				document.getElementById('prl').value =  prl;

				  	}

              else{

              	if(m2==3){
				  		d3=28;
				  		//m4=m2-1;

				  	}
				  	else if(m2==2||m2==4||m2==6||m2==8||m2==10||m2==12){
				  		d3=31;
				  		//m4=m2-1;
				  	}
				
				  	else{
				  		d3=30;
				  		//m4=m2-1;
				  	}
				
				var m4 = ("0" + (dob.getMonth() + 0)).slice(-2);
                 var y2= (dob.getFullYear()+59);
				 var datestring = d3 + "-" + m4 + "-" +y2;	
				var prl = document.getElementById('prl').innerHTML.value;
                prl = datestring;
				document.getElementById('prl').value =  prl;

              }
				  }
				   else{

				   	 // dob.setDate(dob.getDate()).dob.setMonth(dob.getMonth()). dob.setFullYear(dob.getFullYear() + 59);
				var datestring = ("0" + (dob.getDate()-1)).slice(-2) + "-" + ("0"+(dob.getMonth()+1)).slice(-2) + "-" + (dob.getFullYear()+59);
					// var dateFormated = dob.toISOString().substr(0,10);
				var prl = document.getElementById('prl').innerHTML.value;
                prl = datestring;
				document.getElementById('prl').value =  prl;
				
				   }					
				
				 }
				 </script>
				</div>
				<div class="form-group">
					<label for="pension_start_date">Pension Start Date:</label>
					 <input class="form-control" placeholder="DD-MM-YYYY" type="date" name="pension_start_date" id="pension_start_date" value="" readonly>

					 <script type="text/javascript">
					    function calculateDates(dob) {
					        var dobDate = new Date(dob);
					        dobDate.setFullYear(dobDate.getFullYear() + 59);
					        dobDate.setDate(dobDate.getDate() - 1);

					        var prlDate = dobDate.getFullYear() + '-' + (dobDate.getMonth() + 1).toString().padStart(2, '0') + '-' + dobDate.getDate().toString().padStart(2, '0');

					        var pensionDate = new Date(prlDate);
					        pensionDate.setFullYear(pensionDate.getFullYear() + 1);
					        var pensionStartDate = pensionDate.getFullYear() + '-' + (pensionDate.getMonth() + 1).toString().padStart(2, '0') + '-' + pensionDate.getDate().toString().padStart(2, '0');

					        //document.getElementById('prl').value = prlDate;
					        document.getElementById('pension_start_date').value = pensionStartDate;
					    }

					    // Call the function when the date of birth is changed
					    document.getElementById('dob').addEventListener('change', function() {
					        calculateDates(this.value);
					    });
					</script>

				</div> 

				<div class="form-group"><label for="place_of_posting">Place of Posting:</label>
					<select class="form-control" id="place_of_posting" name="place_of_posting">
						
					<option value="" >--Select--</option>
			
						<?php
            			$sql = "SELECT * FROM place_of_posting";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['place_of_posting']."'>".$row['place_of_posting']."</option>";
						}?>
				
					</select>
				</div>

				<div class="form-group">
				<label for="d_nothi_id">D-Nothi ID:</label>
				 <input class="form-control" placeholder="Enter D-Nothi ID" type="number" name="d_nothi_id" id="d_nothi_id"  value="" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "6">
				</div>

									
				</div>
			</div>
				
				</div>
		<!--Second Column-->
				<div class="col-sm-4"> 
				<div class="card border border-warning">
			  <div class="card-header">
				<label for="ssc_exam">Service Info. Portion-2</label>
			  </div>
			<div class="card-body">
				<div class="form-group">
				<label for="tin_no">TIN NO.:</label>
				 <input class="form-control" placeholder="Enter TIN NO." type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==12) return false;" name="tin_no" id="tin_no"  value=""  maxlength="12">
				</div>
				<div class="form-group">
				  <label for="join_designation" class="form-label">Joining Designation:</label>
				  <select class="form-control" id="join_designation" name="join_designation" required tabindex="">
					<option value="" >--Select--</option>
				 <?php
                        $sql = "SELECT * FROM designation  order by designation ASC";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
						}?>				
			  		</select>
				</div>
				
					<div class="form-group"><label for="cadre">Cadre:</label>
					<select class="form-control" id="cadre" name="cadre">
						<option value="" >--Select--</option>
						<?php                        
						$sql = "SELECT * FROM cadre";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result)){
						 echo "<option value='".$row['id']."'>".$row['cadre']."</option>";
						}?>	
					 </select>
					</div>
					<div class="form-group"><label for="sub_cadre">Sub cadre:</label>
					<select class="form-control" id="sub_cadre" name="sub_cadre">
						<option value="" >--Select--</option>						
					 </select>
					</div>

					<div class="form-group"><label for="sub_cadre_header_ext">Sub cadre Header:</label>
					<select class="form-control" id="sub_cadre_header_ext" name="sub_cadre_header_ext">
						<option value="" selected>--Select--</option>
						<?php                        
						$sql = "SELECT * FROM sub_cadre_header";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result)){
						 echo "<option value='".$row['header']."'>".$row['header']."</option>";
						}?>							
					 </select>
					</div>

				<div class="form-group">
				<label for="appoint_type">Appoinment Type:</label>				 
				 <select class="form-control" id="appoint_type" name="appoint_type" >						
					<option value="" >--Select--</option>						 
					<option value="Regular">Regular</option>					
					<option value="Probationary">Probationary</option>
					<option value="Absorb">Absorb</option>					
					<option value="Non-Absorb">Non-Absorb</option>
					<option value="Adhoc">Adhoc</option>
					<option value="Others">Others</option>					
					</select>
					<input class="form-control" placeholder="Enter Others" type="text" name="others" id="others">

					<script type='text/javascript'>
					$(document).ready(function() {					
					 var others = $("#others");					 
					  others.hide();
					//for ssc
					 	$('#appoint_type').change(function() {
					  	if( $(this).val() == "Others") {
					       		$('#others').prop( "disabled", false );
					       		$("#others").show();					       		
					    		} 
					   else{
								$("#others").hide();
								$("#others").hide();
								}					     				    
							  });
							 });					
					</script>
				 </div>	

				<div class="form-group">
				<label for="last_promo_date">Last Promotion Date:</label>
				 <input class="form-control" placeholder="Enter Last Promotion" type="date" name="last_promo_date" id="last_promo_date" 
				 onchange="calculateAmount2(this.value)"  value="<?=$row['last_promo_date'];?>">
				</div>
				<div class="form-group">
				<label for="next_promo_date">Next Promotion Date:</label>
				 <input class="form-control" placeholder="DD-MM-YYYY" type="text" data-date-format="dd-mm-yyyy" name="next_promo_date" id="next_promo_date" readonly>
				 
				 <script type="text/javascript">
				 function calculateAmount2(val) {
					 var userinput = document.getElementById("last_promo_date").value;					 
						var last_promo_date = new Date(userinput);
					 // dob.setDate(dob.getDate()).dob.setMonth(dob.getMonth()). dob.setFullYear(dob.getFullYear() + 59);
					 var datestring = ("0" + last_promo_date.getDate()).slice(-2) + "-" + ("0"+(last_promo_date.getMonth()+1)).slice(-2) + "-" + (last_promo_date.getFullYear()+5);

					// var dateFormated = dob.toISOString().substr(0,10);
					var next_promo_date = document.getElementById('next_promo_date');
	                next_promo_date.value = datestring;						 
	            	}
				 </script>				 
				</div>		

						</div>
					</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-4"> 
				<div class="card border border-warning">
			  <div class="card-header">
				<label for="ssc_exam">Service Info. Portion 3</label>
			  </div>
			<div class="card-body">
			<!-- <div class="form-group">
				<label for="last_incr_date">Last Increment Date:</label>
				 <input class="form-control" placeholder="Enter Incr. Due" type="date" name="last_incr_date" id="last_incr_date" tabindex="10"
				 onchange="calculateAmount3(this.value)" required>
				</div> -->
				<div class="form-group">
				<label for="granted_promo_date">Granted Promotion Date:</label>
				 <input class="form-control" placeholder="Enter Granted Promotion" type="date" name="granted_promo_date" id="granted_promo_date" tabindex="">
				</div>

				<div class="form-group"><label for="nature_of_promo">Nature of Promotion:</label>
					<select class="form-control" id="nature_of_promo" name="nature_of_promo" tabindex="">
					<option value="" >--Select--</option>						 
					<option value="Selection Grade">Selection Grade</option>
					<option value="Senior Scale">Senior Scale</option>
					<option value="Regular">Regular</option>
					<option value="Time Scale">Time Scale</option>
					</select>
				</div>

				<div class="form-group">
				<label for="last_incr_date">Increment Date:</label>
				 <input class="form-control" placeholder="DD-MM-YYYY" type="text" name="last_incr_date" id="last_incr_date" tabindex="" value="<?php echo $date4; ?>"  readonly>
				</div>

				<div class="form-group">
				<label for="next_incr_date">Next Increment Date:</label>
				 <input class="form-control" placeholder="DD-MM-YYYY" type="text" name="next_incr_date" id="next_incr_date" readonly value="<?php echo $date3; ?>" >
				 
				 <script type="text/javascript">
				 function calculateAmount3(val) {
					 var userinput = document.getElementById("last_incr_date").value;					 
						var last_incr_date = new Date(userinput);											
					 // dob.setDate(dob.getDate()).dob.setMonth(dob.getMonth()). dob.setFullYear(dob.getFullYear() + 59);
					 var datestring = ("0" + last_incr_date.getDate()).slice(-2) + "-" + ("0"+(last_incr_date.getMonth()+1)).slice(-2) + "-" + (last_incr_date.getFullYear()+1);

					// var dateFormated = dob.toISOString().substr(0,10);
						var next_incr_date = document.getElementById('next_incr_date');
                		next_incr_date.value = datestring;
					 
            			}
				 </script>				 
				</div>
				<div class="form-group">
				<label for="grade">Grade:</label>
				<select class="form-control" id="grade" name="grade" tabindex="">
						<option value="" disabled selected >--Select--</option>
						<?php                    
						$sql = "SELECT * FROM grade";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result)){
						 echo "<option value='".$row['id']."'>".$row['grade']."</option>";
						}?>	
					 </select>	
				</div>
				
				<div class="form-group">
					<label for="scale">Scale:</label>
					<select class="form-control" id="scale" name="scale" tabindex="" required>
						<option value="" disabled selected >--Select--</option>
						<?php
                        //require_once('db/db.php');

						// $sql = "SELECT * FROM pay_scale_2015";
						// $result = mysqli_query($conn, $sql);
						// while($row = mysqli_fetch_array($result))
						// {
						 // echo "<option value='".$row['id']."'>".$row['scale']."</option>";
						// }?>		
					 </select>
				</div>
				<div class="form-group">
					<label for="basic">Basic:</label>
					<select class="form-control" id="basic" name="basic" tabindex=""  required>
					<!-- <select class="form-control" id="basic" name="basic" tabindex="13" onchange="calculateAmount4(this.value)" required> -->
						<option value="" disabled selected >--Select--</option>						
					 </select>	
				</div>

					<div class="form-group"><label for="job_status">Job Status:</label>
					<select class="form-control" id="job_status" name="job_status" tabindex="" >
						<option value="" >--Select--</option>							 
						<option value="In Service">In Service</option>
						<option value="PRL">PRL</option>					
						<option value="Retired">Retired</option>
						<option value="Suspended">Suspended</option>
						<option value="Resignation">Resignation</option>
						<option value="Deputation">Deputation</option>
						 <option value="LPR">LPR</option>
						 <option value="Death with Services">Death with Services</option>
					
					</select>
					<input class="form-control mt-2" placeholder="Enter Deputation" type="text" name="deputation_org" id="deputation_org">
					<script type="text/javascript">
						
						$(document).ready(function() {
				 	 	 var deputation_org = $("#deputation_org");
				   			deputation_org.hide();
				     	if( $('#job_status').val() == "Deputation") {
				       		
				       		$('#deputation_org').prop( "disabled", false );
				       		$("#deputation_org").show();
				    		}

				 		$('#job_status').change(function() {
				   
				    	if( $(this).val() == "Deputation") {
				       		$('#deputation_org').prop( "disabled", false );
				       		$("#deputation_org").show();
				    		}
						else{			
							$("#deputation_org").hide();
							}
				      $('#textInput').prop( "disabled", false );
				     
				  });
				 });
					</script>
				</div>
					</div>
				</div></div>
				<div class="row mt-1"> 
				<div class="col-sm-2">  </div>
				<div class="col-sm-6"><button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block" tabindex=""><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  </div>
				<div class="col-sm-4">
					<?php

			      if($val==1){
			        ?>
			        <a class="btn btn-primary" href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
			        <?php
			      }
			      else{
			        ?>
			        <a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>

			        <?php
			      }

			      ?>
			      				
				</div>	
				</fieldset>
				
			</form>
			<div id="err"></div>

		</div>
					
    		   
    			
    		</div>
    	</div>
    </div>

<script src="../js/grade_scale_basic.js" type="text/javascript"></script> 
<script>


//for Scale and Basic
$( "select[name='scale']" ).change(function () {

    var scaleID = $(this).val();


    if(scaleID) {


        $.ajax({

            url: "../ajax/ajaxpro_for_payscale.php",

            dataType: 'Json',

            data: {'id':scaleID},

            success: function(data) {

                $('select[name="basic"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="basic"]').append('<option value="'+ key +'">'+ value +'</option>');

                });

            }

        });


    }else{

        $('select[name="basic"]').empty();

    }

});
//for cadre and Sub cadre
$( "select[name='cadre']" ).change(function () {

    var cadreID = $(this).val();


    if(cadreID) {


        $.ajax({

            url: "../ajax/ajaxpro_subcadre.php",

            dataType: 'Json',

            data: {'id':cadreID},

            success: function(data) {

                $('select[name="sub_cadre"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="sub_cadre"]').append('<option value="'+ key +'">'+ value +'</option>');

                });

            }

        });


    }else{

        $('select[name="sub_cadre"]').empty();

    }

});

</script>


<?php include('../includes/footer.php');?>