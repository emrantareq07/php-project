<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('../includes/header.php');

include('../db/db.php');
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val'];
 
  }
 
if(isset($_SESSION['emp_id'])){
	// $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * FROM basic_info where emp_id='$emp_id'";
 }
$result=mysqli_query($conn,$sql);
 //while($row=mysql_fetch_object($result)){
if (mysqli_num_rows($result) > 0) {
	while($row=mysqli_fetch_array($result)){
	$name=$row['name'];
	$name_bn=$row['name_bn']; 
	$fathersName=$row['fathersName'];
	$mothersName=$row['mothersName'];
	// $nationality=$row['nationality'];
	$nationality='Bangladeshi';
	$gender=$row['gender'];
	$blood_group=$row['blood_group'];
	$home_dist=$row['home_dist'];
	$present_add=$row['present_add'];
	$permanent_add=$row['permanent_add'];

	$religion=explode(",",$row['religion']);

	$nid=$row['nid'];
	$quota=$row['quota'];
	$mobile_no=$row['mobile_no'];

	$maritial_status=$row['maritial_status'];
	$spouse_name=$row['spouse_name'];
	$spouse_home_dist=$row['spouse_home_dist'];
	$spouse_occupation=$row['spouse_occupation'];
	$spo_present_add=$row['spo_present_add'];
	$spo_parmanent_add=$row['spo_parmanent_add'];

?>
<div class="container  mt-2 p-1 my-1 border shadow-lg rounded bg-white" onload='document.form1.fathersName.focus()'>
    <h2 class="page-header text-center text-uppercase mt-2 p-1 my-1 text-success"><b>Update Personal information</b> </h2>
    <div class="row">
    	<div class="col-sm-12">							
    	<div class="panel-body">
    		<?php
				if(@$_GET['submitted']){
					$val=1;
					?>
				<div class="alert alert-success" role="alert">
				 <b> Data Updated Successfully!!!</b>

				</div>
				<?php 
			}?>
			<!-- <form method="POST" id="form1" name="form1" action="update_personal_info.php?val=$val" enctype="multipart/form-data" accept-charset="UTF-8"> -->

        	<form method="POST" id="form1" name="form1" action="update_personal_info.php?val=<?php echo $val; ?>" enctype="multipart/form-data" accept-charset="UTF-8">
            	<fieldset>
					<!-- First Column-->
				<div class="row">
				<div class="col-sm-3"> 
					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="ssc_exam">General Information - 1</label>
					  </div>
					<div class="card-body">
						<div class="form-group">
					<label for="name_bn">Name (EN):</label>
                    <input class="form-control" placeholder="Name (BN)" type="text" name="name" id="name" value="<?=$row['name'];?>">
                	</div>
					<div class="form-group">
					<label for="name_bn">Name (BN):</label>
                    <input class="form-control" placeholder="Name (BN)" type="text" name="name_bn" id="name_bn" value="<?=$row['name_bn'];?>">
                	</div>
						<div class="form-group">
						<label for="fathersName">Fathers Name:</label>
                    	<input class="form-control" placeholder="Fathers Name" type="text" name="fathersName" id="fathersName" value="<?=$row['fathersName'];?>">
                		</div>
					<div class="form-group"><label for="mothersName">Mothers Name:</label>
                    	<input class="form-control" placeholder="Mothers Name" type="text" name="mothersName" id="mothersName" value="<?=$row['mothersName'];?>">
                	</div>
					<!-- <div class="form-group"><label for="dob">Date of Birth:</label>
                    	<input class="form-control" placeholder="Date of Birth" name="dob" type="date" required id="dob" onblur="getRetireDate();"  value="<?=$row['dob'];?>">
                	
					</div> -->
					
					<div class="form-group well">
					<label for="gender" class="">Gender:</label>
					<div class="radio-inline ">
					
					<input style="margin-right: 0" type="radio" name="gender" <?=$row['gender']=="Male" ? "checked" : ""?> value="Male">Male 
					<br><input  style="margin-right: 0" type="radio" name="gender" <?=$row['gender']=="Female" ? "checked" : ""?> value="Female"> Female
					</div>								
                	</div>
					<div class="form-group"><label for="blood_group">Blood Group:</label>
                    	<select class="form-control" id="blood_group" name="blood_group" >
						
						<option value="" disabled selected>--Select--</option>
						<option value="A+" <?=$blood_group == 'A+' ? ' selected="selected"' : '';?> >A+</option>
						 <option value="A" <?=$blood_group == 'A' ? ' selected="selected"' : '';?> >A</option>
						<option value="B+" <?=$blood_group == 'B+' ? ' selected="selected"' : '';?> >B+</option>
						  <option value="AB"  <?=$blood_group == 'AB' ? ' selected="selected"' : '';?> >AB</option>
						  <option value="AB+"  <?=$blood_group == 'AB+' ? ' selected="selected"' : '';?> >AB+</option>
						  <option value="AB-"  <?=$blood_group == 'AB-' ? ' selected="selected"' : '';?> >AB-</option>
						  <option value="O-"  <?=$blood_group == 'O-' ? ' selected="selected"' : '';?> >O-</option>
						  <option value="O+"  <?=$blood_group == 'O+' ? ' selected="selected"' : '';?> >O+</option>
				
					  </select>
                	</div>
					
				<label for="religion">Religon:</label>
				<div class="form-check ">
				 
				<input type="checkbox"  name="religion"  value="Islam"  size="17" <?php if(in_array("Islam",$religion)) { ?> checked="checked" <?php } ?> > Islam
				<input type="checkbox" name="religion"  value="Hindu" size="17" <?php if(in_array("Hindu",$religion)) { ?> checked="checked" <?php } ?> > Hindu
				<input type="checkbox" name="religion"  value="Chiristain"   size="17" <?php if(in_array("Chiristain",$religion)) { ?> checked="checked" <?php } ?> > Chiristain
				</div>
				 
					<div class="form-group">
						<label for="nationality">Nationality:</label>
                    	<input class="form-control" placeholder="Enter Nationality" type="text" name="nationality" id="nationality" value="<?=$nationality;?>" readonly>
                	
					</div>
					</div>
				
					</div>
					
				</div>
				<!-- Second Column-->
				<div class="col-sm-3"> 

					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="ssc_exam">General Information - 2</label>
					  </div>
					<div class="card-body">
						<div class="form-group"><label for="nid">National ID:</label>
                    	<input class="form-control floatNumber" pattern=".{10}|.{13}"  title="Enter NID 10/13 Digit!!" placeholder="Enter NID 10/13 Digit!!" type="text" maxlength="13" name="nid" id="nid" value="<?=$row['nid'];?>">
                	<script type="text/javascript">
					//integer value validation for medical fc pattern="^\d{10}$"   pattern=".{5,10}"   pattern="^\d{10,13}$"
					$('input.floatNumber').on('input', function() {
						this.value = this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');
					});
					</script>
					</div>								
				
				<div class="form-group">
				<label for="quotas">Qouta:</label>
				<select class="form-control" id="quota" name="quota" >
						
					<option value="" disabled selected>--Select--</option>
					 
					  <option value="Child of Freedom Fighter" <?=$quota == 'Child of Freedom Fighter' ? ' selected="selected"' : '';?> >Child of Freedom Fighter</option>
					  <option value="Grand Child of Freedom Fighter"  <?=$quota == 'Grand Child of Freedom Fighter' ? ' selected="selected"' : '';?> >Grand Child of Freedom Fighter</option>
					  <option value="Physically Handicapped"  <?=$quota == 'Physically Handicapped' ? ' selected="selected"' : '';?> >Physically Handicapped</option>
					  <option value="Orphan"  <?=$quota == 'Orphan' ? ' selected="selected"' : '';?> >Orphan</option>
					  <option value="Ethinic Minority"  <?=$quota == 'Ethinic Minority' ? ' selected="selected"' : '';?> >Ethinic Minority</option>
					  <option value="Ansar-VDP"  <?=$quota == 'Ansar-VDP' ? ' selected="selected"' : '';?> >Ansar-VDP</option>
					  <option value="Non Qoutas"  <?=$quota == 'Non Qoutas' ? ' selected="selected"' : '';?> >Non Qoutas</option>
						
				  </select>				
                  </div>
                  <div class="form-group">
				    <label for="mobile_no">Mobile Number:</label>
				    <input class="form-control form-change-element"  placeholder="Mobile Number" type="text" name="mobile_no" id="mobile_no" maxlength="11"
				        value="<?=$row['mobile_no'];?>" >
				    <span id="message"></span>
				</div>

						<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
						<script type="text/javascript">
						    // integer value validation for Mobile Number
						    // $('input[type="text"]').on('input', function() {
						    //     this.value = this.value.replace(/[^0-9]/g, '');
						    //     validateMobileNumber();
						    // });
							$('#mobile_no').on('input', function() {
							    this.value = this.value.replace(/[^0-9]/g, '');
							    validateMobileNumber();
							});

						    // check mobile number validation
						    function validateMobileNumber() {
						        var mobNum = $("#mobile_no").val();
						        var filter = /^\d{11}$/; // Adjusted the regular expression for 11 digits
						        var message = document.getElementById('message');
						        var goodColor = "#0C6";
						        var badColor = "#FF9B37";
						        
						        if (filter.test(mobNum)) {
						            message.innerHTML = "Valid";
						            message.style.color = goodColor;
						            $("#mobile-valid").removeClass("hidden");
						            $("#folio-invalid").addClass("hidden");
						        } else {
						            message.style.color = badColor;
						            message.innerHTML = "Required 11 digits!";
						            $("#folio-invalid").removeClass("hidden");
						            $("#mobile-valid").addClass("hidden");
						        }
						    }
						</script>

						<!-- <div class="form-group">
					    <label for="mobile_no">Mobile Number:</label>
					    <input class="form-control form-change-element" required placeholder="Mobile Number" type="text" name="mobile_no" id="mobile_no" maxlength="11"
					        value="<?=$row['mobile_no'];?>" required>
					    <span id="message"></span>
					</div>-->

					<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
					<script type="text/javascript"> 
					    // integer value validation for Mobile Number
					    // $('input[type="text"]').on('input', function() {
					    //     this.value = this.value.replace(/[^0-9]/g, '');
					    // });

					    // check mobile number validation
					    // $(document).ready(function() {
					    //     $("#mobile_no").on("blur", function() {
					    //         var mobNum = $(this).val();
					    //         var filter = /^\d{11}$/; // Adjusted the regular expression for 11 digits
					    //         var message = document.getElementById('message');
					    //         var goodColor = "#0C6";
					    //         var badColor = "#FF9B37";
					            
					    //         if (filter.test(mobNum)) {
					    //             message.innerHTML = "Valid";
					    //             message.style.color = goodColor;
					    //             $("#mobile-valid").removeClass("hidden");
					    //             $("#folio-invalid").addClass("hidden");
					    //         } else {
					    //             message.style.color = badColor;
					    //             message.innerHTML = "Required 11 digits!";
					    //             $("#folio-invalid").removeClass("hidden");
					    //             $("#mobile-valid").addClass("hidden");
					    //         }
					    //     });
					    // });
					</script>
 
				  
					<!--  <div class="form-group"><label for="email">Email ID:</label>
                    	<input class="form-control" placeholder="Enter email" type="email" name="email" id="email" value="<?=$row['email'];?>">
                	</div> -->
                	<div class="form-group">
				    <label for="email">Email:</label>
				    <input class="form-control form-change-element"  placeholder="Email" type="email" name="email" id="email" value="<?=$row['email'];?>">
				    <span id="email-message"></span>
				</div>

				<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
				<script type="text/javascript">
				    $('#email').on('input', function() {
				        validateEmail();
				    });

				    function validateEmail() {
				        var email = $("#email").val();
				        var filter = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				        var message = document.getElementById('email-message');
				        var goodColor = "#0C6";
				        var badColor = "#FF9B37";
				        
				        if (filter.test(email)) {
				            message.innerHTML = " Valid email address";
				            message.style.color = goodColor;
				            $("#email-valid").removeClass("hidden");
				            $("#email-invalid").addClass("hidden");
				        } else {
				            message.style.color = badColor;
				            message.innerHTML = " Invalid email address";
				            $("#email-invalid").removeClass("hidden");
				            $("#email-valid").addClass("hidden");
				        }
				    }
				</script>
					
					 <!--  <div class="form-group"><label for="home_dist">Home District:</label>
                    	<input class="form-control" placeholder="Enter Home District" type="home_dist" name="home_dist" id="home_dist" value="<?=$row['home_dist'];?>">
                	</div> -->

                	<div class="form-group"><label for="home_dist">Home District:</label>
                    <select name="home_dist" class="form-control">
					    <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT district_name FROM district";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['district_name'];
					    ?>
					    <?php
					    if(!empty($home_dist) && $home_dist== $option){
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
				<label for="present_add">Present Address:</label>
				<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="present_add"  name="present_add" ><?php echo $present_add; ?></textarea>
                    	
                </div>
				<div class="form-group">
				<label for="parmanent_add">Permanent Address:</label>
				<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="permanent_add" name="permanent_add" ><?php echo $row['permanent_add']; ?></textarea>
                	<!-- An element to toggle between password visibility -->
					<input type="checkbox" onclick="Copydata()"> Same As Present Address
					<script>
						function Copydata(){
						  $("#permanent_add").val($("#present_add").val());
						}
						</script>
	            	</div>
					</div>
					</div>
				                	
				</div>
				<!-- 3rd Column-->
				<div class="col-sm-3">
					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="ssc_exam">General Information (Spouse)</label>
					  </div>
					<div class="card-body">
						<div class="form-group">
						<label for="maritial_status">Maritial Status:</label>
						<select class="form-control" id="maritial_status" name="maritial_status" >
						
						<option value="" disabled selected>--Select--</option>
						 
						<option value="Married" <?=$maritial_status == 'Married' ? ' selected="selected"' : '';?> >Married</option>
						  <option value="Unmarried"  <?=$maritial_status == 'Unmarried' ? ' selected="selected"' : '';?> >Unmarried</option>
						  <option value="Divorcy"  <?=$maritial_status == 'Divorcy' ? ' selected="selected"' : '';?> >Divorcy</option>
						  <option value="Wedo"  <?=$maritial_status == 'Wedo' ? ' selected="selected"' : '';?> >Wedo</option>							
					  </select>				
                  </div>	
				  
				  	<div class="form-group"><label for="spouse_name">Spouse Name:</label>
                    <input class="form-control" placeholder="Enter Spouse Name" type="text" name="spouse_name" id="spouse_name" value="<?=$row['spouse_name'];?>">
                	
					</div>
					<div class="form-group">
					<label for="spouse_home_dist">Spouse Home District:</label>
                    <!-- <input class="form-control" placeholder="Enter Spaous Home District" type="text" name="spouse_home_dist" id="spouse_home_dist" value="<?=$row['spouse_home_dist'];?>"> -->
                    <select name="spouse_home_dist" class="form-control">
					    <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT district_name FROM district";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['district_name'];
					    ?>
					    <?php
					    	if(!empty($spouse_home_dist) && $spouse_home_dist== $option){
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
				<label for="spouse_occupation">Spouse Occupation:</label>
                  <input class="form-control" placeholder="Enter Spaous Occupation" type="text" name="spouse_occupation" id="spouse_occupation" value="<?=$row['spouse_occupation'];?>">
				</div>

                <div class="form-group">
				<label for="spo_present_add">Spouse Present Address:</label>
				<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="spo_present_add"  name="spo_present_add" ><?php echo $spo_present_add; ?></textarea>
                    	
                	</div>
					<div class="form-group">
				<label for="spo_parmanent_add">Spouse Permanent Address:</label>
				<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="spo_parmanent_add" name="spo_parmanent_add" ><?php echo $row['spo_parmanent_add']; ?></textarea>
                    	<!-- An element to toggle between password visibility -->
						<input type="checkbox" onclick="Copydata_spouse()"> Same As Present Address
						<script>
							function Copydata_spouse(){
							  $("#spo_parmanent_add").val($("#spo_present_add").val());
							}
							</script>
                	</div>

					</div>
				</div>			
										
				</div>
				<div class="col-sm-3"> <center><button type="submit" id="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  
				 <h4>
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
		 	
				 </h4></center> 
				</div>
				
				<div class="row"> 
				<div class="col-sm-4">  </div>
				<div class="col-sm-4"> </div>
				<div class="col-sm-4"> </div>
				
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
    	<h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Personal information</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			<div class='col-sm-4'></div>
				<div class='col-sm-4'><h5 class='btn btn-danger btn-block text-left'> Record Not Found!!!</h4>
				<h5><center>
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


<?php include('../includes/footer.php');?>