<?php
// Start the session
//session_start();
?>
<?php include('includes/header.php');?>
<?php

include('db/db.php');
 
 // if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 // $emp_id=$_SESSION['emp_id'];
	 //$sql="SELECT * FROM users where id='$edit_id'";
 //}
//$result=mysqli_query($conn,$sql);
 //while($row=mysql_fetch_object($result)){
	 // while($row=mysqli_fetch_array($result)){
	
	// $fathersName=$row['fathersName'];
	// $mothersName=$row['mothersName'];
	// $birth_date=$row['birth_date'];
	// $gender=$row['gender'];
	// $blood_group=$row['blood_group'];
	// $home_dist=$row['home_dist'];
	// $present_add=$row['present_add'];
	// $permanent_add=$row['permanent_add'];
	
	// $religious=explode(",",$row['religious']);
	
	// //$religious=$row['religious'];
	// $nid=$row['nid'];
	// $quotas=$row['quotas'];
	// $mobile_no=$row['mobile_no'];
	
	// $maritial_status=$row['maritial_status'];
	// $spaous_name=$row['spaous_name'];
	// $spaous_occupation=$row['spaous_occupation'];
?>


    <div class="container  mt-2 p-1 my-1 border shadow-lg  bg-white" onload='document.form1.fathersName.focus()'>
    	<h2 class="page-header text-center text-info">Add Innovation Data</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			
    		    	<div class="panel-body">
    		        	<form method="POST" id="form1" name="form1" action="insert.php" enctype="multipart/form-data">
    		            	<fieldset>
								<!-- First Column-->
							<div class="row">
							<div class="col-sm-3"> </div>
							<!-- Second Column-->
							<div class="col-sm-3"> 
							<div class="form-group">
							<label for="fiscal_year">অর্থবছর </label>
    		                    	<input class="form-control" placeholder="অর্থবছর" type="text" name="fiscal_year" id="fiscal_year" required>
    		                	</div>
								<!--
								<div class="form-group"><label for="fiscal_year">অর্থবছর</label>
    		                    	<select class="form-control" id="fiscal_year" name="fiscal_year" >
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 <option value="২০২২-২০২৩">২০২২-২০২৩</option>
									 <option value="২০২১-২০২২">২০২১-২০২২</option>
									<option value="২০২০-২০২১">২০২০-২০২১</option>
									  <option value="২০১৯-২০২০">২০১৯-২০২০</option>
									  <option value="২০১৮-২০১৯">২০১৮-২০১৯</option>
									  <option value="২০১৭-২০১৮">২০১৭-২০১৮</option>
																		
									
								  </select>
    		                	</div>-->
								
								<div class="form-title_of_invention"><label for="mothersName">উদ্ভাবনের শিরোনাম</label>
    		                    	
									<textarea class="form-control" placeholder="উদ্ভাবনের শিরোনাম" rows="2" id="title_of_invention"  name="title_of_invention" required></textarea>
    		                	</div>
								<div class="form-group"><label for="inventors_name">উদ্ভাবক/উদ্ভাবকের নাম</label>
    		                    	<input class="form-control" placeholder="উদ্ভবক/উদ্ভাবকের নাম" name="inventors_name" type="text"  id="inventors_name" required>
    		                	
								</div>
								
							  <div class="form-group"><label for="inventors_designation">পদবী</label>
    		                    	<input class="form-control" placeholder="পদবী" type="text" name="inventors_designation" id="inventors_designation" required>
    		                	
								</div>
								<div class="form-group"><label for="inventors_emp_id">এমপ্লয়ী নং</label>
    		                    	<input class="form-control" placeholder="এমপ্লয়ী নং" type="text" name="inventors_emp_id" id="inventors_emp_id" required>
    		                	
								</div>
							</div>
							<!-- 3rd Column-->
							<div class="col-sm-3">
														
									<div class="form-group"><label for="proposed_workplace">প্রস্তাবকালীন কর্মস্থল</label>
    		                    	<select class="form-control" id="proposed_workplace" name="proposed_workplace" >
									
									

									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বিসিআইসি প্র: কা:">বিসিআইসি প্র: কা:</option>
									  <option value="জেএফসিএল" >জেএফসিএল</option>
									  <option value="জিপিইউএফসিএল">জিপিইউএফসিএল</option>
									  <option value="এসএফসিএল">এসএফসিএল</option>
									  <option value="এএফসিসিএল">এএফসিসিএল</option>
									  <option value="ডিএপিএফসিএল">ডিএপিএফসিএল</option>
									<option value="সিইউএফএল">সিইউএফএল</option>
									  <option value="সিসিসিএল">সিসিসিএল</option>
									  <option value="কেপিএমএল">কেপিএমএল</option>
									  <option value="টিএসপিসিএল">টিএসপিসিএল</option>
									    <option value="বিআইএসএফএল">বিআইএসএফএল</option>
																		
									
								  </select>
    		                	</div>
							
								
							 
								 <div class="form-group"><label for="des_of_invention">উদ্ভাবনের বর্নণা</label>
    		                    	<textarea class="form-control" placeholder="উদ্ভাবনের বর্নণা" rows="2" id="des_of_invention"  name="des_of_invention" ></textarea>
    		                	</div>
								
								 <!--<div class="form-group"><label for="imple_status">বাস্তাবয়নের অবস্থা</label>
    		                    	<input class="form-control" placeholder="বাস্তাবয়নের অবস্থা" type="text" name="imple_status" id="imple_status" >
    		                	</div>-->
								
										<div class="form-group"><label for="imple_status">বাস্তাবয়নের অবস্থা</label>
    		                    	<select class="form-control" id="imple_status" name="imple_status" >
									
									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বাস্তবায়িত">বাস্তবায়িত</option>
									  <option value="চলমান">চলমান</option>
									 </select>
    		                	</div>
								
									<div class="form-group"><label for="replicate_eligibility">রেপ্লিকেট যোগ্যতা</label>
    		                    	<select class="form-control" id="replicate_eligibility" name="replicate_eligibility" >
									
									

									<option value="" disabled selected>নির্বাচন করুন</option>
									 
									<option value="বিশেষায়িত"  >বিশেষায়িত</option>
									  <option value="যোগ্য"   >যোগ্য</option>
									  <option value="যোগ্য  না"  >যোগ্য  না</option>
									  
								
																		
									
								  </select>
    		                	</div>
								
								
								<!--<div class="form-group">
							<label for="parmanent_add">Permanent Address:</label>
							<textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="permanent_add" name="permanent_add" ></textarea>
    		                    	<!-- An element to toggle between password visibility 
									<input type="checkbox" onclick="Copydata()"> Same As Present Address
									<script>
										function Copydata(){
										  $("#permanent_add").val($("#present_add").val());
										}
										</script>
    		                	</div>-->
								
								
							</div>
								<div class="col-sm-3"> </div>
							</div>
							
							<div class="col-sm-3"> </div>
							<div class="col-sm-3"> <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block">
							<span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  </div>
							<div class="col-sm-3">							
							<div class="col-sm-3">  <h4><a class="btn btn-md btn-primary" href="view_profile_details.php"> Edit </a></h4></div> 
							</div>
							<div class="col-sm-3">  <h4><a class="btn btn-primary" href="view_profile_details.php"> Previous page </a></h4></div>
							
							
							
    		                	
    		            	
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
//}
?>

<?php include('includes/footer.php');?>