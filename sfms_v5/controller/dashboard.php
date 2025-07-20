<?php 
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
$office_type=$_SESSION['office_type'];
 // echo $user_type;

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include('../db/db.php');
 include('../include/header.php');
 
if($user_type=='sadmin'){
include('../include/topbar_sadmin.php');        
}
else{
	include('../include/topbar.php');
}
?>
<div class="container mt-5">
<div class="row"> 
<div class="col-sm-4 mt-5">
	<?php
echo 'user type: '. $user_type.'<br>';
echo 'office type: '.$office_type;
if($user_type=='sadmin'){   
?>
<h4><a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
</h4>
<?php
 }
?>
</div>
<div class="col-sm-4 mt-5">
	<h3 class="text-dark text-center text-uppercase">Welcome <b class="text-danger "> <?= $_SESSION['username'];?></b> Dashboard</h3>
</div>
<div class="col-sm-4 mt-5">
	<div class="col">

<h4 class="text-center"><a href="pipeline.php?val=kaliganj_buffer" class="btn btn-primary">Pipeline Data</a>
<!-- <li><a class="dropdown-item" href="urea_report_with_date_range.php?val=sfcl">SFCL</a></li> -->
</h4>                  

<h4 class="text-center"><a href="urea_report_with_date_range.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Print Report (Date Range)</a></h4>
<h4 class="text-center"><a href="show_all_urea.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Show All</a></h4>
<h4 class="text-center"><a href="yearly_target_set.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Yearly Target Set</a></h4>

<center>
<form  id="downloadForm" action="dawnload_database.php" method="post">
   <button class="btn btn-outline-danger" type="submit" name="submit" ><i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database</button>
 </form> </center>
<hr class="text-muted bg-muted btn-outline-danger">
<h4 class="text-center"><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></h4>
</div>
</div>

</div>
</div> 

<?php 
// }
include('../include/footer.php');
?>