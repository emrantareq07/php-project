<?php 
session_start();
ob_start(); // Start output buffering
//error_reporting(E_ALL);
error_reporting(0);
//ini_set('display_errors', 1);
$table=$_SESSION['username'];
// Ensure the correct timezone is set
date_default_timezone_set('Asia/Dhaka'); // Correct timezone identifier for Dhaka, Bangladesh

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

include('../db/db.php');
include('../include/header.php');
$date = date('Y-m-d');
$day=date('d',strtotime($date));
$month=date('m',strtotime($date));
//echo $month;
$year=date('Y',strtotime($date));
if($month==7){
	$month_id=$year.'1';
}
elseif ($month==8){
	$month_id=$year.'2';
}
elseif ($month==9){
	$month_id=$year.'3';
}
elseif ($month==10){
	$month_id=$year.'4';
}
elseif ($month==11){
	$month_id=$year.'5';
}
elseif ($month==12){
	$month_id=$year.'6';
}
elseif ($month==1){
	$month_id=$year.'7';
}
elseif ($month==2){
	$month_id=$year.'8';
}
elseif ($month==3){
	$month_id=$year.'9';
}
elseif ($month==4){
	$month_id=$year.'10';
}
elseif ($month==5){
	$month_id=$year.'11';
}
elseif ($month==6){
	$month_id=$year.'12';
}

$sql_target_table_retrive="SELECT  * FROM monthly_demand where month_id = '$month_id' And office_name='$table'";
$result = mysqli_query($conn, $sql_target_table_retrive);
	$row = mysqli_fetch_assoc($result);
   	$date=$row['date'];
	$office_name=$row['office_name'];
	$demand_amount1=  $row['demand_amount'];    
	$id=  $row['id'];

if(isset($_POST['insert'])){

if(mysqli_num_rows($result) == 0){
 $demand_amount=$_POST['demand_amount'];
$date=date('Y-m-d');
$sql_monthly_demand1="INSERT INTO monthly_demand (date,office_name,demand_amount,month_id) values('{$date}','{$table}','{$demand_amount}','{$month_id}')";
$result_monthly_demand1  = mysqli_query($conn, $sql_monthly_demand1);
header("location:monthly_demand_set.php?inserted=successfully");
exit();
}
else{  
	$demand_amount=$_POST['demand_amount'];
	$id=$_POST['id'];

$sql="UPDATE monthly_demand SET demand_amount='$demand_amount' where id='$id'";
$result=mysqli_query($conn, $sql);
header("location:monthly_demand_set.php?updated=successfully");
exit();
	}
}
?>

<div class="container mt-5 ">
  <h1 class="text-center text-muted text-uppercase">DFMS<b class="text-danger"> [--<?=$_SESSION['username'];?>--]</b></h1>
  
<div class="row">
<div class="col-sm-4">
<?php
if(@$_GET['inserted'])
{?>
<div class="alert alert-success" role="alert">
  <b>Monthly Demand Set Successfully!!!</b>
</div>
<?php }?>
<?php
if(@$_GET['updated'])
{?>
<div class="alert alert-success" role="alert">
  <b>Monthly Demand Updated Successfully!!!</b>
</div>
<?php }?>

</div>
<div class="col-sm-4 border pt-2 my-2 mt-2 rounded shadow">
<form action="" method="POST">
<fieldset >
      <div class="col-sm-10">
		<div class="form-group">
		<input type="text" class="form-control" placeholder="Enter Production Target(MT)" name="id" value="<?php echo !empty($row['id'])?$row['id']:''; ?>" id="id" hidden>

	  	<label for="date" class="form-check-label">  Date: </label>
        <input type="date" class="form-control" placeholder="Enter Production Target(MT)" name="date" value="<?php echo date('Y-m-d') ?>" id="date" readonly> 		 
	  </div>
	  <div class="form-group">		

	  	<label for="demand_amount" class="form-check-label">  Monthly Demand Set(MT): </label>
        <input type="text" class="form-control" placeholder="Enter Monthly Demand" name="demand_amount" value="<?php echo $demand_amount1 ?>" id="demand_amount">

	  </div>
	  </div>  

	<div class="col-sm-6 mt-3">
	
		<div class="mb|sm-4 d-grid">
	        <!-- <button id="submit" type="submit" class="btn btn-warning btn-block rounded-pill">Register</button> -->
	    
	 	<div class="form-group">
		<button type="submit" name="insert" class="btn btn-block btn-primary rounded-pill">Update</button>
		<!-- <button type="submit" name="update" class="btn btn-block btn-primary rounded-pill">Edit</button> -->
		 <!-- <p><a class="btn btn-primary text-center float-end" href="view_yearly_target_set.php">Edit</a></p> -->
		</div>
		</div>
	 
      
    </div>
  
    </fieldset>
  </form>
</div>

	
<div class="col-sm-4">
<h4 class="text-center"><a href="view_urea_report.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">View Report</a></h4>

<h4 class="text-center"><a href="urea_form.php?username=<?=$_SESSION['username']?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Previous Page</a></h4>

<h4 class="text-center"><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></h4>
</div>
  
  </div>	   
  </div>
  
<?php 
// }
include('../include/footer.php');
?>
<?php ob_end_flush(); // End output buffering and flush the buffer ?>