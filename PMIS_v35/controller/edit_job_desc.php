<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');?>
<?php

include('../db/db.php');
 
 if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
		 
	 $sql="SELECT * FROM job_desc where emp_id='$emp_id'";
 }
$result=mysqli_query($conn,$sql);

if (mysqli_num_rows($result) > 0) {
	 while($row=mysqli_fetch_array($result)){
	
	 $dob=$row['dob'];
	 $doj=$row['doj'];
	 $doc=$row['doc'];
	 //$dolp=$row['dolp'];
	 $police_verification=$row['police_verification'];
	 $prl_db=$row['prl'];
	 
	 $prl=date('d-m-Y',strtotime($prl_db));
	//$prl = date('Y-m-d', strtotime('-1 day', strtotime($dob. '+59 years')));
	
	 $place_of_posting=$row['place_of_posting'];
	 $join_designation=$row['join_designation'];
	 
	 $cadre=$row['cadre'];
	 $sub_cadre=$row['sub_cadre'];
	 $sub_cadre_header_ext=$row['sub_cadre_header_ext'];
	 $appoint_type=$row['appoint_type']; 
	 $others=$row['others'];

	 $last_promo_date=$row['last_promo_date'];
	 $next_promo_date=$row['next_promo_date'];

	 $tomorrow = date('Y-m-d');
	$year=date('Y',strtotime($tomorrow));
			
	$year1=$year+1;

	$date4='01/07/'.$year;

	$date3='01/07/'.$year1;
	 
	 //$last_incr_date=$_POST['last_incr_date'];
	 //$incr_due_date=$_POST['incr_due_date'];
	$last_incr_date=$date4;
	$next_incr_date =$date3;


	 $last_incr_date=$row['last_incr_date'];
	 $next_incr_date=$row['next_incr_date'];
	 
	 $grade=$row['grade'];
	 $basic=$row['basic'];
	 $scale=$row['scale'];	
	 $job_status=$row['job_status'];

	 $granted_promo_date=$row['granted_promo_date'];	
	 $nature_of_promo=$row['nature_of_promo'];
 	$d_nothi_id=$row['d_nothi_id'];
	 $tin_no=$row['tin_no'];
	 
?>
    <div class="container mt-2 p-1 my-1 border shadow-lg bg-white" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">
    		<b>Update Service information</b></h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			    		<?php
				if(@$_GET['submitted'])
				{?>
				<div class="alert alert-success" role="alert">
				<b>  Data Updated Successfully!!!</b>
				</div>
				<?php }?>
    			
		<div class="panel-body">
			<form method="POST" id="form1" name="form1" action="update_job_desc.php" enctype="multipart/form-data">
				<fieldset>
							
		<!-- Educational Info-->
		<div class="col-sm-4"> 
			<div class="card border border-primary">
			  <div class="card-header">
				<label for="ssc_exam">Service Info. Portion-1</label>
			  </div>
			<div class="card-body">
							
				<div class="form-group">
				<label for="dob">Date of Birth:</label>
				 <input class="form-control" placeholder="Enter DOB" type="date" name="dob" id="dob" onchange="calculateAmount(this.value)" 
				 value="<?=$row['dob'];?>">
				</div>	

				<div class="form-group">
				<label for="doj">Date of Joining:</label>
				 <input class="form-control" placeholder="Enter DOJ" type="date" name="doj" id="doj" value="<?=$row['doj'];?>">
				</div>

				<div class="form-group"><label for="police_verification">Police Verification Status:</label>
					<select class="form-control" id="police_verification" name="police_verification" tabindex="4">
						
					<option value="" disabled selected>--Select--</option>
						 
					<option value="Complete"  <?=$police_verification == 'Complete' ? ' selected="selected"' : '';?>>Complete</option>
					<option value="Incomplete"  <?=$police_verification == 'Incomplete' ? ' selected="selected"' : '';?>>Incomplete</option>
								
					</select>
				</div>


				<div class="form-group">
					<label for="doc">Date of Confiramtion:</label>
					 <input class="form-control" placeholder="Enter DOC" type="date" name="doc" id="doc" value="<?=$row['doc'];?>" tabindex="2">
				</div>

				<div class="form-group">
				<label for="prl">PRL Date:</label>
				 <input class="form-control" placeholder="Enter PRL" type="text" name="prl" id="prl" value="<?=$row['prl'];?>" readonly>
				 <script type="text/javascript">
				 
				 function calculateAmount(val) {
					 var userinput = document.getElementById("dob").value;
					 var dob = new Date(userinput);
												
					 var datestring = ("0" + (dob.getDate()-1)).slice(-2) + "-" + ("0"+(dob.getMonth()+1)).slice(-2) + "-" + (dob.getFullYear()+59);
					// var dateFormated = dob.toISOString().substr(0,10);
				var prl = document.getElementById('prl').innerHTML.value;
                prl = datestring;
				document.getElementById('prl').value =  prl;
					 }
				 </script>
				 
				</div>

				<div class="form-group">
					<label for="pension_start_date">Pension Start Date:</label>
					 <input class="form-control" placeholder="DD-MM-YYYY" type="text" name="pension_start_date" id="pension_start_date" value="<?=$row['pension_start_date'];?>" readonly>

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
					<select class="form-control" id="place_of_posting" name="place_of_posting" >
						
					<option value="" disabled selected>--Select--</option>
						 
					    <?php 
					    $query ="SELECT place_of_posting FROM place_of_posting";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['place_of_posting'];
					    ?>
					    <?php
					    if(!empty($place_of_posting) && $place_of_posting== $option){
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
				<label for="d_nothi_id">D-Nothi ID:</label>
				 <input class="form-control" placeholder="Enter D-Nothi ID" type="number" name="d_nothi_id" id="d_nothi_id"  value="<?=$row['d_nothi_id'];?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "6">
				</div>
			
				
				</div>
				</div>
			</div>
		<!--Second Column-->
				<div class="col-sm-4"> 
				<div class="card border border-warning">
			  <div class="card-header">
				<label for="hsc_exam">Service Info. Portion 2</label>
			  </div>
			<div class="card-body">
					<div class="form-group">
				<label for="tin_no">TIN NO.:</label>
				 <input class="form-control" placeholder="Enter TIN NO." type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==12) return false;" name="tin_no" id="tin_no"  value="<?=$row['tin_no'];?>"  maxlength="12">
				</div>

			<div class="form-group">
			<label for="join_designation" class="form-label">Joining Designation:</label>
				<select class="form-control" id="join_designation" name="join_designation" >
						
					<option value="" disabled selected>--Select--</option>

					<!-- <option value="">--Select--</option> -->
					    <?php 
					    $query ="SELECT designation FROM designation order by designation asc";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['designation'];
					    ?>
					    <?php
					    if(!empty($join_designation) && $join_designation== $option){
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
				 <label for="cadre" class="form-label">Cadre:</label>
				  <select class="form-control" id="cadre" name="cadre" required>
					<option value="" disabled selected>--Select--</option>
					<option value="Senior GM"  <?=$cadre == 'Senior GM' ? ' selected="selected"' : '';?> >Senior GM</option>
					<option value="General Manager"  <?=$cadre == 'General Manager' ? ' selected="selected"' : '';?> >General Manager</option>
					<option value="Administrative" <?=$cadre == 'Administrative' ? ' selected="selected"' : '';?> >Administrative</option>
					<option value="Technical"  <?=$cadre == 'Technical' ? ' selected="selected"' : '';?> >Technical</option>
					<option value="Finance"  <?=$cadre == 'Finance' ? ' selected="selected"' : '';?> >Finance</option>
					<option value="Commercial"  <?=$cadre == 'Commercial' ? ' selected="selected"' : '';?> >Commercial</option>
				</select> 
			</div>
				<div class="form-group">
					<label for="sub_cadre" class="form-label">Sub Cadre:</label>

					<select class="form-control" id="sub_cadre" name="sub_cadre" >
						
					<option value="" disabled selected>--Select--</option>

					<!-- <option value="">--Select--</option> -->
					    <?php 
					    $query ="SELECT sub_cadre FROM sub_cadre";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['sub_cadre'];
					    ?>
					    <?php
					    if(!empty($sub_cadre) && $sub_cadre== $option){
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
					<label for="sub_cadre_header_ext">Sub cadre Header:</label>
				 
					<select class="form-control" id="sub_cadre_header_ext" name="sub_cadre_header_ext" >
						
					<option value="" >--Select--</option>

					<!-- <option value="">--Select--</option> -->
					    <?php 
					    $query ="SELECT header FROM sub_cadre_header";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['header'];
					    ?>
					    <?php
					    if(!empty($sub_cadre_header_ext) && $sub_cadre_header_ext== $option){
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
					<label for="appoint_type">Appoinment Type:</label>
				 
					<select class="form-control" id="appoint_type" name="appoint_type" >
						
					<option value="" disabled selected>--Select--</option>
						 
					<option value="Regular" <?=$appoint_type == 'Regular' ? ' selected="selected"' : '';?> >Regular</option>				
					
					<option value="Probationary" <?=$appoint_type == 'Probationary' ? ' selected="selected"' : '';?> >Probationary</option>
					<option value="Others" <?=$appoint_type == 'Others' ? ' selected="selected"' : '';?> >Others</option>
					</select>
					<input class="form-control" placeholder="Enter Others" type="text" name="others" id="others">

					<script type='text/javascript'>

					$(document).ready(function() {
					
					 var others = $("#others");
					 
					  others.hide();
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
				 <input class="form-control" placeholder="DD-MM-YYYY" type="text" name="next_promo_date" id="next_promo_date" readonly value="<?=$row['next_promo_date'];?>">
				 
				 <script type="text/javascript">
				 function calculateAmount2(val) {
					 var userinput = document.getElementById("last_promo_date").value;
					 
						var last_promo_date = new Date(userinput);
						
					 var datestring = ("0" + last_promo_date.getDate()).slice(-2) + "-" + ("0"+(last_promo_date.getMonth()+1)).slice(-2) + "-" + (last_promo_date.getFullYear()+5);

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
				<label for="honors_exam">Service Info. Portion-3</label>
			  </div>
			<div class="card-body">

				<div class="form-group">
					<label for="granted_promo_date">Granted Promotion Date:</label>
					 <input class="form-control" placeholder="Enter Granted Promotion" type="date" name="granted_promo_date" id="granted_promo_date" tabindex="" value="<?=$row['granted_promo_date'];?>">
					</div>
					
				<div class="form-group"><label for="nature_of_promo">Nature of Promotion:</label>
					<select class="form-control" id="nature_of_promo" name="nature_of_promo" >
						
					<option value="" disabled selected>--Select--</option>
						 
					<option value="Selection Grade" <?=$nature_of_promo == 'Selection Grade' ? ' selected="selected"' : '';?> >Selection Grade</option>
					<option value="Senior Scale"  <?=$nature_of_promo == 'Senior Scale' ? ' selected="selected"' : '';?> >Senior Scale</option>
					<option value="Time Scale"  <?=$nature_of_promo == 'Time Scale' ? ' selected="selected"' : '';?> >Time Scale</option>
					<option value="Regular"  <?=$nature_of_promo == 'Regular' ? ' selected="selected"' : '';?> >Regular</option>
					</select>
				</div>


			<div class="form-group">
				<label for="last_incr_date">Last Increment Date:</label>
				 <input class="form-control" placeholder="Enter Incr. Due" type="date" name="last_incr_date" id="last_incr_date" value="<?php echo $last_incr_date; ?>" readonly>
				</div>
			<div class="form-group">
				<label for="next_incr_date">Next Increment Date:</label>
				 <input class="form-control" placeholder="Enter Incr. Due" type="date" name="next_incr_date" id="next_incr_date" value="<?php echo $next_incr_date; ?>" readonly>
				</div>
				<div class="form-group">
				<label for="grade">Grade:</label>
				<select class="form-control" name="grade" >
					<option value="" disabled selected>--Select--</option>
						<?php	            
						$sql = "SELECT grade FROM grade";
						$result = mysqli_query($conn, $sql);						
						while($basicrow = mysqli_fetch_array($result)){						
						?>	
						 <option value="<?php echo $basicrow['grade'];?>"						 
						   
						 <?php if($row['grade']==$basicrow['grade']){
								echo "selected";
						 }
						 ?>
						 ><?php echo $basicrow['grade'];?></option>						
						<?php
						}?>	
										
					 </select>				
				
				</div>
				
				<div class="form-group">
				<label for="scale">Scale:</label>
				<select class="form-control" id="scale" name="scale" >
					<option value="" disabled selected>--Select--</option>
					<?php                    
						// $sql = "SELECT * FROM pay_scale_2015";
						// $result = mysqli_query($conn, $sql);
						// while($row = mysqli_fetch_array($result))
						// {
						//  echo "<option value='".$row['id']."'>".$row['scale']."</option>";
						// }
						?>	
						<?php
				        $sql = "SELECT * FROM pay_scale_2015";
				        $result = mysqli_query($conn, $sql);

				        while ($scalerow = mysqli_fetch_array($result)) {
				            $selected = ($row['scale'] == $scalerow['scale']) ? "selected" : "";
				            ?>
				            <option value="<?php echo $scalerow['id']; ?>" <?php echo $selected; ?>>
				                <?php echo $scalerow['scale']; ?>
				            </option>
				        <?php
				        }
				        ?>
            
					 </select>				
				
				</div>

				<div class="form-group">
				<label for="basic">Basic:</label>
				<select class="form-control" id="basic" name="basic" >
					<option value="" disabled selected>--Select--</option>

					<?php
				        $sql = "SELECT * FROM basic";
				        $result = mysqli_query($conn, $sql);

				        while ($basicrow = mysqli_fetch_array($result)) {
				            $selected = ($row['basic_after_incr'] == $basicrow['basic']) ? "selected" : "";
				            ?>
				            <option value="<?php echo $basicrow['id']; ?>" <?php echo $selected; ?>>
				                <?php echo $basicrow['basic']; ?>
				            </option>
				        <?php
				        }
				        ?>					       
						
					 </select>	
				
				</div>

				<div class="form-group">
				<label for="job_status">Job Status:</label>
				<select class="form-control" id="job_status" name="job_status" >
						<option value="" >--Select--</option>
					<option value="In Service" <?=$job_status == 'In Service' ? ' selected="selected"' : '';?> >In Service</option>
					<option value="PRL" <?=$job_status == 'PRL' ? ' selected="selected"' : '';?> >PRL</option>
					<option value="Suspended" <?=$job_status == 'Suspended' ? ' selected="selected"' : '';?> >Suspended</option>
					<option value="Retired" <?=$job_status == 'Retired' ? ' selected="selected"' : '';?> >Retired</option>
					<option value="LPR" <?=$job_status == 'LPR' ? ' selected="selected"' : '';?> >LPR</option>
					<option value="Death with Services" <?=$job_status == 'Death with Services' ? ' selected="selected"' : '';?> >Death with Services</option>
				<option value="Deputation" <?=$job_status == 'Deputation' ? ' selected="selected"' : '';?> >Deputation</option>
					<option value="Resignation" <?=$job_status == 'Resignation' ? ' selected="selected"' : '';?> >Resignation</option>
					
					 </select>
				
				
								</div>
									
							</div>
						</div>				
					</div>
				</div>				
				
				<div class="row">
				
					<div class="col-sm-4">  </div>
					<div class="col-sm-4">
						<center><button type="submit" id="submit" name="submit" class="btn  btn-primary "><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  
					<a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a></center>
						
					</div>
					<div class="col-sm-4">
						<!-- <a class="btn btn-primary" href="view_profile_details.php"> Previous page </a> -->
						
					</div>
				
				</div>
				
				</fieldset>
				
			</form>
			<div id="err"></div>
				
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
    	<h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Job information</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			<div class='col-sm-4'></div>
				<div class='col-sm-4'><h5 class='btn btn-danger btn-block text-left'> Record Not Found!!!</h4>
				<h5><center>
					<a class="btn btn-primary" href="../view_profile_details.php?emp_id=<?php echo $_SESSION["emp_id"] ?>"> Previous page </a>
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
<script type="text/javascript">
   $(document).ready(function() {
    // $('#cadre').on('change', function() {
      var cadre_id = $('#cadre').val();
      var sub_cadre_id = $('#sub_cadre').val();
      if(cadre_id && sub_cadre_id) {
        $.ajax({
          url: 'edit_subcadre.php',
          type: 'POST',
          data: {cadre_id: cadre_id, sub_cadre_id: sub_cadre_id},
          success: function(data) {
            // Do something on success
          }
        });
      } else {
        // alert('Please select a category and subcategory');
      }
    });

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
</script>
<?php include('../includes/footer.php');?>