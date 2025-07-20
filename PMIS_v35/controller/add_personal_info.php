<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('includes/header.php');

include('db/db.php');
 
 //if(isset($_POST['submit']) && isset($_SESSION['emp_id'])){
 	if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	// $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];
	//  $sql="SELECT * FROM users where emp_id='$emp_id'";
 
	// $result=mysqli_query($conn,$sql);

	//  while($row=mysqli_fetch_array($result)){
 	
	$fathersName=$_POST['fathersName'];
	$mothersName=$_POST['mothersName'];
	$dob=$_POST['dob'];
	//$_SESSION=$dob;
	$gender=$_POST['gender'];
	$blood_group=$_POST['blood_group'];
	$home_dist=$_POST['home_dist'];
	$present_add=$_POST['present_add'];
	$permanent_add=$_POST['permanent_add'];
	
	$religious1=explode(",",$_POST['religious']);
	$religious=implode(',', $religious1) ;
	//$religious=$_POST['religious'];
	$nid=$_POST['nid'];
	$quota=$_POST['quota'];
	// $mobile_no=$_POST['mobile_no'];
	
	$maritial_status=$_POST['maritial_status'];
	$spouse_name=$_POST['spouse_name'];
	$spouse_home_dist=$_POST['spouse_home_dist'];
	$spouse_occupation=$_POST['spouse_occupation'];
	$spo_present_add=$_POST['spo_present_add'];
	$spo_parmanent_add=$_POST['spo_parmanent_add'];

	 $sql="INSERT INTO basic_info (user_id, emp_id, 
	 fathersName,mothersName,dob,gender,blood_group,home_dist,
	 present_add,permanent_add,religious,nid,quota,maritial_status,spouse_name,spouse_occupation,spouse_home_dist,spo_present_add,spo_parmanent_add) 
	 VALUES ('{$user_id}','{$emp_id}','{$fathersName}','{$mothersName}','{$dob}',
	 '{$gender}','{$blood_group}','{$home_dist}','{$present_add}','{$permanent_add}','{$religious}',
	 '{$nid}','{$quota}','{$maritial_status}','{$spouse_name}','{$spouse_occupation}','{$spouse_home_dist}','{$spo_present_add}','{$spo_parmanent_add})";
	//$result=mysqli_query($conn,$sql);
	if(mysqli_query($conn,$sql)){
			// header("location:view_profile_details.php?submitted=successfully");
			//header("location:add_personal_info.php?submitted=successfully");
			header("Location: add_personal_info.php?emp_id=".$_SESSION['emp_id']);
	}
	else
		print mysqli_error();
 }

?>
    <div class="container  mt-2 p-1 my-1 border shadow-lg  bg-white rounded" onload='document.form1.fathersName.focus()'>
    	<h2 class="page-header text-center text-uppercase my-1 text-success "><b>Add Personal information</b></h2>
    	<div class="row">
			
    		<div class="col-sm-12">
							
    	<div class="panel-body">

    		<?php
				if(@$_GET['emp_id'])
				{?>
				<div class="alert alert-success" role="alert">
				  Data Inserted Successfully!!!
				</div>
				<?php }?>

        	<form method="POST" id="form1" name="form1" action="" enctype="multipart/form-data">
            	<fieldset>
					<!-- First Column-->
				<div class="row">
				
				<!-- Second Column-->
				<div class="col-sm-4"> 
					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="ssc_exam">General Information</label>
					  </div>
					<div class="card-body">

					<div class="form-group">
					<label for="name_bn">Name (BN):</label>
                    	<input class="form-control" placeholder="Name (BN)" type="text" name="name_bn" id="name_bn" value="">
                	</div>
				<div class="form-group">
					<label for="fathersName">Fathers Name:</label>
                    	<input class="form-control" placeholder="Fathers Name" type="text" name="fathersName" id="fathersName" value="">
                	</div>
					<div class="form-group"><label for="mothersName">Mothers Name:</label>
                    	<input class="form-control" placeholder="Mothers Name" type="text" name="mothersName" id="mothersName" value="">
                	</div>
					<div class="form-group"><label for="birth_date">Date of Birth:</label>
                    	<input class="form-control" placeholder="Date of Birth" name="dob" type="date" required id="dob" onblur="getRetireDate();"  value="">
                	
					</div>
					
					<div class="form-group well">
					<label for="gender" class="">Gender:</label>
					<div class="radio-inline ">
					
					<input style="margin-right: 0" type="radio" name="gender"  value="Male" required>Male 
					<br><input  style="margin-right: 0" type="radio" name="gender"  value="Female" required> Female
					</div>								
                	</div>
					<div class="form-group"><label for="blood_group">Blood Group:</label>
                    	<select class="form-control" id="blood_group" name="blood_group" required>
						<option value="" disabled selected>Select Blood Group</option>
							 
						<option value="B+">B+</option>
						<option value="B-">B-</option>
						  <option value="AB">AB</option>
						  <option value="AB+">AB+</option>
						  <option value="AB-">AB-</option>

						  <option value="O+">O+</option>
						 <option value="O-">O-</option>				
					  </select>
                	</div>
					
				<label for="religious">Religon:</label>
				<div class="form-check ">
				 
				<input type="checkbox"  name="religious"  value="Islam"  size="17" > Islam
				<input type="checkbox" name="religious"  value="Hindu" size="17" > Hindu
				<input type="checkbox" name="religious"  value="Chiristain"   size="17" > Chiristain
				</div>
					
								  
				</div>
			</div>
		</div>
				<div class="col-sm-4">
					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="ssc_exam">General Information</label>
					  </div>
					<div class="card-body">

					<div class="form-group"><label for="nid">National ID:</label>
                    	<input class="form-control floatNumber" pattern=".{10}|.{13}" required title="Enter NID 10/13 Digit!!" placeholder="Enter NID 10/13 Digit!!" type="text" name="nid" id="nid" value="" maxlength="13">
                	<script type="text/javascript">
					//integer value validation for medical fc pattern="^\d{10}$"   pattern=".{5,10}"   pattern="^\d{10,13}$"
					$('input.floatNumber').on('input', function() {
						this.value = this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');
					});
					</script>
					</div>
					
                	<div class="form-group">
				<label for="quota">Qouta:</label>
				<select class="form-control" id="quota" name="quota" required>
						
					<option value="" disabled selected>--Select--</option>
					 
					  <option value="Child of Freedom Fighter" >Child of Freedom Fighter</option>
					  <option value="Grand Child of Freedom Fighter">Grand Child of Freedom Fighter</option>
					  <option value="Physically Handicapped">Physically Handicapped</option>
					  <option value="Orphan">Orphan</option>
					  <option value="Ethinic Minority">Ethinic Minority</option>
					  <option value="Ansar-VDP">Ansar-VDP</option>
					  <option value="Non Qoutas">Non Qoutas</option>
						
				  </select>
				   </div>
					<div class="form-group">
						<label for="home_dist">Home District:</label>
                    	<!-- <input class="form-control" placeholder="Enter Home District" type="text" name="home_dist" id="home_dist" value=""> -->
                    	<select class="form-control" id="home_dist" name="home_dist" required tabindex="5">
							<option value="" disabled selected>--Select--</option>
						 		<?php
		                        //require_once('db/db.php');

								$sql = "SELECT * FROM district";
								$result = mysqli_query($conn, $sql);
								while($row = mysqli_fetch_array($result)){
								 echo "<option value='".$row['district_name']."'>".$row['district_name']."</option>";
								}?>								
						
					  </select>
                	</div>
                 
                  <div class="form-group">
				<label for="present_add">Present Address:</label>
				<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="present_add"  name="present_add" ></textarea>
                 </div>
					<div class="form-group">
					<label for="parmanent_add">Permanent Address:</label>
					<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="permanent_add" name="permanent_add" ></textarea>
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
				<div class="col-sm-4">
					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="ssc_exam">General Information</label>
					  </div>
					<div class="card-body">
					<div class="form-group">
					<label for="quotas">Maritial Status:</label>
					<select class="form-control" id="maritial_status" name="maritial_status" required>
						
						<option value="" disabled selected>Select Status</option>
						 <option value="Married" >Married</option>
						  <option value="Unmarried">Unmarried</option>
						  <option value="Divorcy">Divorcy</option>
						  <option value="Wedo">Wedo</option>
							
					  </select>
				
                  </div>
						<div class="form-group"><label for="spouse_name">Spouse Name:</label>
                    	<input class="form-control" placeholder="Enter Spaous Name" type="text" name="spouse_name" id="spouse_name" value="">
                	
					</div>
					<div class="form-group">
				<label for="spaous_occupation">Spouse Home District:</label>
                    <!-- <input class="form-control" placeholder="Enter Spaous Home District" type="text" name="spouse_home_dist" id="spouse_home_dist" value=""> -->
                    <select class="form-control" id="spouse_home_dist" name="spouse_home_dist" required tabindex="">
					<option value="" disabled selected>--Select--</option>
				 		<?php
                        
						$sql = "SELECT * FROM district";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result)){
						 echo "<option value='".$row['district_name']."'>".$row['district_name']."</option>";
						}?>								
						
					  </select>
                	
				</div>
				<div class="form-group">
				<label for="spouse_occupation">Spouse Occupation:</label>
                    <input class="form-control" placeholder="Enter Spaous Occupation" type="text" name="spouse_occupation" id="spouse_occupation" value="">
                	
				</div>
				<div class="form-group">
				<label for="spo_present_add">Spouse Present Address:</label>
				<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="spo_present_add"  name="spo_present_add" ></textarea>
                 </div>
				<div class="form-group">
					<label for="spo_parmanent_add">Spouse Permanent Address:</label>
					<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="spo_parmanent_add" name="spo_parmanent_add" ></textarea>
	                    	<!-- An element to toggle between password visibility -->
						<input type="checkbox" onclick="Copydata()"> Same As Present Address
						<script>
							function Copydata(){
							  $("#spo_parmanent_add").val($("#spo_present_add").val());
							}
							</script>
                	</div>
									
                	</div>
					
				</div>
				</div>
			</div>
						
		</div>	

		<div class="row"> 
			<div class="col-sm-4">  </div>
			<div class="col-sm-4"><center><button type="submit" id="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> 
			<a class="btn btn-primary" href="add_personal_data.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a></center><br> </div>

			<div class="col-sm-4">   </div>
		
		</div>				               	
            	
		</fieldset>
				
        	</form>
			<div id="err"></div>

    		</div>
    	</div>
    </div>

 <?php
 // }
?>

<?php include('includes/footer.php');?>