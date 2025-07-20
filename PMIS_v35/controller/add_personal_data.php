<?php
// Start the session
session_start();
//$_SESSION['emp_id']=$_GET["emp_id"];

$_SESSION['emp_id']=$_SESSION['emp_id'];

include('includes/header.php');

include('db/db.php');
 
  if(isset($_SESSION['emp_id'])){
	// $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];
	 $sql="SELECT * FROM basic_info where emp_id='$emp_id'";
 
	$result=mysqli_query($conn,$sql);

	 while($row=mysqli_fetch_array($result)){
	 	$_SESSION['id']=$user_id;
 	
	// $fathersName=$_POST['fathersName'];
	// $mothersName=$_POST['mothersName'];
	// $birth_date=$_POST['birth_date'];
	// $gender=$_POST['gender'];
	// $blood_group=$_POST['blood_group'];
	// $home_dist=$_POST['home_dist'];
	// $present_add=$_POST['present_add'];
	// $permanent_add=$_POST['permanent_add'];
	
	// $religious=explode(",",$_POST['religious']);
	
	// //$religious=$_POST['religious'];
	// $nid=$_POST['nid'];
	// $quotas=$_POST['quotas'];
	// // $mobile_no=$_POST['mobile_no'];
	
	// $maritial_status=$_POST['maritial_status'];
	// $spouse_name=$_POST['spouse_name'];
	// $spouse_home_dist=$_POST['spouse_home_dist'];
	// $spouse_occupation=$_POST['spouse_occupation'];

	//  $sql="INSERT INTO job_desc (user_id, emp_id, 
	//  fathersName,mothersName,birth_date,gender,blood_group,home_dist,
	//  present_add,permanent_add,religious,nid,quotas,maritial_status,spouse_name,spouse_occupation,spouse_home_dist) 
	//  VALUES ('{$user_id}','{$emp_id}','{$fathersName}','{$mothersName}','{$birth_date}',
	//  '{$gender}','{$blood_group}','{$home_dist}',
	//  '{$present_add}','{$permanent_add}','{$religious}',
	//  '{$nid}','{$quotas}','{$maritial_status}','{$spouse_name}','{$spouse_occupation}','{$spouse_home_dist}')";
	// //$result=mysqli_query($conn,$sql);
	// if(mysqli_query($conn,$sql))
	// {
	// 		// header("location:view_profile_details.php?submitted=successfully");
	// 		header("location:add_personal_info.php?submitted=successfully");
	// }
	// else
	// 	print mysqli_error();
 // }

?>


    <div class="container  mt-2 p-1 my-1 border shadow-lg  bg-white rounded">
    	<h2 class="page-header text-center text-uppercase my-1 text-info ">Add Personal information</h2>
    	<div class="row">
			
    		<div class="col-sm-12">
							
    	<div class="panel-body">

    		<?php
				if(@$_GET['submitted'])
				{?>
				<div class="alert alert-success" role="alert">
				  Data Inserted Successfully!!!
				</div>
				<?php }?>


        		<!-- First Column-->
				<div class="row">
				
				<!-- Second Column-->
				<div class="col-sm-4"> </div>
				<div class="col-sm-4">
					<div class="card border border-warning">
					  <div class="card-header">
						<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
						<label for="" class="text-center text-uppercase"><center>Add Information</center> </label>
					  </div>
					<div class="card-body">
						<center>
						<h3><a class="btn btn-primary" href="add_personal_info.php?edit=<?php echo $emp_id;?>"> Add General Information </a></h3>

						<h3><a class="btn btn-primary" href="add_children_info.php?edit=<?php echo $emp_id;?>"> Add Children Information </a></h3>
						<h3>
						<a class="btn btn-primary" href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> Previous page </a>
						</h3>
						</center>

					
				</div>
			</div>
		</div>
				<!-- 3rd Column-->
				<div class="col-sm-4">	</div>
			
				</div>

			
				
			</div>
		</div>
				
	
			<div id="err"></div>
				<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    		
    	</div>
  </div>
    

  <?php
}
	}
  ;?>


<?php include('includes/footer.php');?>