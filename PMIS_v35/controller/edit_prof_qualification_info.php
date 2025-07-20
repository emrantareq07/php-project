<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('../includes/header.php');
include('../db/db.php');
 
if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];
	 $sql="SELECT * from prof_quali_info where emp_id='$emp_id'";
	 
	// $sql="SELECT * FROM users where id='$edit_id'";
 }
$result=mysqli_query($conn,$sql);
//if(!$result){
    //die("ERROR".mysqli_error($conn));
 //while($row=mysql_fetch_object($result)){
if (mysqli_num_rows($result) > 0) {
	 while($row=mysqli_fetch_array($result)){
	 
	 $qualification=$row['qualification'];
	 // $ground_or_subject=$row['ground_or_subject'];
	 
	 // $nature=$row['nature'];
	 	 
?>
    <div class="container mt-2 p-1 my-1 border shadow-lg bg-white" onload='document.form1.edit_prof_qualification_info.focus()'>
    	<h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Professional Qualification Information</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			
		<div class="panel-body">
		
		  	
			<form method="POST" id="form1" name="form1" action="update_prof_qualification_info.php" enctype="multipart/form-data">
				<fieldset>
				<!--<div class="row"> 
				<div class="col-sm-4">  </div>
				<div class="col-sm-4"> </div>
				<div class="col-sm-4 mt-0 my-0 pt-0 float-end"><button type="submit" valign="right" id="submit" name="submit" class=" col-sm-4 btn btn-md btn-primary btn-block">
				<span class="glyphicon glyphicon-floppy-disk "></span> Save</button> || 
				<a class="btn btn-primary" href="view_profile_details.php"> Previous page </a></div>
				
				</div>
				 -->

	<div class="row">
				
						<!-- Educational Info-->
		<div class="col-sm-4"> </div>
		<!--Second Column-->
				<div class="col-sm-4"> 
				<div class="card border border-warning">
			  <div class="card-header">
				<label>Update Professional Qualification Information</label>
			  </div>
			<div class="card-body">
				
				<!-- <div class="form-group">
					<label for="ground_or_subject">Ground/Subject:</label>
					<textarea class="form-control" placeholder="Enter Ground/Subject...." rows="2" id="ground_or_subject"  name="ground_or_subject" value=""><?=$row['ground_or_subject'];?></textarea>
				</div> -->

				<div class="form-group">
				<label for="qualification">Qualification:</label>
				 <input class="form-control" placeholder="Enter Qualification" type="text" name="qualification" id="qualification" value="<?=$row['qualification'];?>">
				</div>
				
				
				
						</div>
					</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-4"> 	</div>
			</div>
				<div class="row">
				
				<div class="col-sm-4">  </div>
				<div class="col-sm-4"><br><center>
					<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  
					<a href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a>
				</center>

				</div>
				<div class="col-sm-4"></div>
				
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
    	<h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Professional Qualification information</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			<div class='col-sm-4'></div>
				<div class='col-sm-4'><h5 class='btn btn-danger btn-block text-left'> Record Not Found!!!</h4>
				<h5><center>
					<a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION["emp_id"] ?>"> Previous page </a>
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