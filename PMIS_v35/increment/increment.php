<?php
//increment.php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('includes/header.php');
include('db/db.php');

  
if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 // $user_id=$_SESSION['id'];
	 // $sql="SELECT * from job_desc where user_id='$edit_id'";
	 
	 $sql="SELECT * FROM job_desc where emp_id='$emp_id'";
 }
	$result=mysqli_query($conn,$sql);

	 while($row=mysqli_fetch_array($result)){
	 
	 $basic=$row['basic'];
	 $next_incr_date=$row['next_incr_date'];

	}
?>

<div><?php
			if(@$_GET['submitted'])
			{?>
			<div class="alert alert-success" role="alert">
			  Data Update Successfully!!!
			</div>
			<?php }?>
	<!--  <a href="increment_update.php" class="btn btn-success">Increment Process</a> -->
	 <form method="POST" action="">  
 <center>
<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
<button type="submit" name="submit" class="btn btn-success text-center"><i class="fa fa-print"></i>  Increment Process </button>
 </center>
          
  </form>
</div>