<?php
include('database.php');
include('function.php');


session_start();
$_SESSION['emp_id']=$_GET["emp_id"];
//$emp_id=$_SESSION['emp_id'];


// if(!$_SESSION['emp_id']){

// header("location: index.php");
// }
//include('db/db.php');

//echo $_POST['emp_id'];
//echo $_SESSION['emp_id'];
?>
<?php 
//echo @$_GET['emp_id'];
//echo @$_POST['emp_id'];
?> 

<?php include('includes/header.php');


?>
<div class="container p-3 my-3 border shadow">
    	<h1 class="page-header text-center text-muted text-uppercase">Welcome PMIS Admin Dashboard</h1>
    	<div class="row">
		<div class="col-sm-3 "></div>
		<div class="col-sm-6">
		<h4 class="page-header text-success text-center"><?php echo "Successfully ".$_SESSION['emp_id']. '  Are Logged in!! Now Update Your Information'; ?></h4>
	<h4 class="page-header text-success text-center"><?php echo "Successfully ".$_GET['emp_id']. '  Are Logged in!! Now Update Your Information'; ?></h4>

			<?php
	//include('db/db.php');
	 //if(!isset($_GET["emp_id"])){
		//  $emp_id=$_GET['emp_id'];
		// // //$emp_id=5620-6;
		// // //echo $_POST['emp_id'];
		//  $sql="SELECT *	FROM users where emp_id=$emp_id LIMIT 1";
		
  //   		 $result = mysqli_query($connection, $sql);

		//  if (mysqli_num_rows($result) > 0) {
		// 	   //output data of each row
		// 	 while($row = mysqli_fetch_assoc($result)) {
		// 		 // $image=$row['image']
		// 		  $emp_id=$row['emp_id'];

			?>
		
<h4 class="page-header text-success text-center"><?php echo "Successfully ".$_GET['emp_id']. '  Are Logged in!! Now Update Your Information'; ?></h4>
<h4 class="page-header text-success text-center"><?php //echo "Successfully ".$row['name']. '  Are Logged in!! Now Update Your Information'; ?></h4>


<?php
			   //} 
			 //}
			 //else {
				// echo "<p class='btn btn-danger'> Record Not Found!!!</p>";
			//}
             //}?>
<center> <h4><a href="users/logout.php" class="btn btn-danger"> Logout </a></h4></center>
<center> <h4><a href="editprofile_1.php"> Edit Profile </a></h4></center>
<center> <h4><a href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> View Profile </a></h4></center>

<center><h4 class="text-center"><a href="view_details.php" class="btn btn-success"> Back Previous Page </a></h4></center>
		</div>
		</div>
</div>
		


<?php include('includes/footer.php');?>