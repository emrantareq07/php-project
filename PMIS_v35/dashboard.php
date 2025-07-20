<?php include('includes/header-dashboard.php');

session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: index.php");
  exit();
}

// Check the user role
$role = $_SESSION['role'];

// Display different content based on the user role
if ($role === 'admin'||$role === 'sadmin') {
  //echo "<h1 class='text-center my-2 text-uppercase text-secondary'>Welcome, Admin!</h1>";
  // Display admin-specific content
  //header("Location: admin_approval.php");
include 'db/db.php';

?>

<div class="container mt-1">
  
  <?php
if(@$_GET['submitted'])
	{?>
	<div class="alert alert-success" role="alert">
	  Data Inserted Successfully
	</div>
	<?php }?>
<div class="card">
  <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">PMIS Admin (--Dashboard--)
<a class="btn btn-danger float-end" href="logout.php"><i class="fa fa-sign-out" style="font-size:18px"></i> Logout</a>
  </h3></div>
  <div class="card-body bg-white">
  
 <div class="row">	
	
		<!--1st col-->
	<!-- <div class="col-sm-1 ">	</div> -->
	<!--2nd col-->
	<div class="col-sm-12">
    
 <img class="mx-auto d-block" src="images/bcic.jpg" width="130" height="130"/>
  <h3 class="mt-2 text-center"></h3>
  <h4 class="text-center"></h4>
    <h4 class="float-center text-center">
<?php

  if($role=='admin'){
    ?>
   <a href="view/view_users.php?role=<?php echo $_SESSION['role'] ?>" class="btn btn-success"><i class="fa fa-eye" style="color:white"></i> View Details All (DataTable)</a>
    <?php
  }
  else{
    ?>
   <a href="view/view_users.php?role=<?php echo $_SESSION['role'] ?>" class="btn btn-success"><i class="fa fa-eye" style="color:white"></i> View Details All (DataTable)</a>

    <?php
  }

  ?>

	<!-- <a href="view/view_users.php?role=<?php echo $_SESSION['role'] ?>" class="btn btn-success"><i class="fa fa-eye" style="color:white"></i> View Details All (DataTable)</a> -->

	<a href="#" class="btn btn-success">User Mangage</a>
   
	<a href="admin/utilities.php" class="btn btn-success text-white"><i class="fa fa-cog" style="color:white"></i> Settings</a>
	<hr class=""><a href="search/search_prl.php" class="btn btn-dark text-white"><i class="fa fa-search" style="color:red"></i> Search (PRL)</a>
  <a href="search/search_promotion_due.php" class="btn btn-secondary text-white">Search (Promotion Due)</a>
  <a href="controller/multiple_searching.php" class="btn btn-secondary text-white">Multi-Searching</a>
  <a href="controller/multiple_search.php" class="btn btn-secondary text-white">Multi-Search</a>
	<a href="gradation-list/search_with_district.php" class="btn btn-dark text-white"><i class="fa fa-search" style="color:red"></i> Search by Dist.</a>
  	
	<hr class="">
  <a href="bio-data/bio-data_and_gradation_list.php" class="btn btn-warning text-dark"><i class="fa fa-book" style="color:black"></i> Bio-Data & Gradation List</a>
   <a href="controller/promotion_due_search.php" class="btn btn-warning">Promotion Due List (5y/+)</a>

  <!--  <a href="controller/dawnload_database.php" class="btn btn-warning">Download database</a>
	<hr class=""> -->
  <!-- <hr class="">
	<form id="downloadForm" action="controller/dawnload_database.php" method="post">
  <button type="submit" name="submit" class="btn btn-warning" >Download Database</button>
</form> -->
<hr class="">

 <a href="increment/increment_details.php" class="btn btn-primary text-white"><i class="fa fa-spinner" style="color:white"></i> Increment & Decrement Details</a>
<hr class="">
<?php

  if($role=='admin'){
    ?>
   <a class="btn btn-primary" href="login/admin_dashboard.php?role=<?php echo $_SESSION['role'] ?>"><i class="fa fa-arrow-left" style="font-size:16px"></i> Previous page </a>
    <?php
  }
  else{
    ?>
    <a class="btn btn-primary" href="login/super-admin_dashboard.php?role=<?php echo $_SESSION['role'] ?>"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous page </a>

    <?php
  }
  ?>
</h4> 
  
  </div>
  <!--3rd col-->
  <!-- <div class="col-sm-1 "> </div> -->
	
	</div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by ICT Division, BCIC.</h6></div>
</div>
 
</div>

<?php include('includes/footer-dashboard.php');?>

<?php
} else {
  echo "<h1>Welcome, User!</h1>";
  // Display user-specific content
  header("Location: user_dashboard.php");
}
?>