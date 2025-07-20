<?php include('../includes/header-dashboard.php');?>

<div class="container mt-1">
  
  <?php
if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully
</div>
<?php }?>
<div class="card">
  <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">PMIS Admin (--Increment & Decrement--)</h3></div>
  <div class="card-body bg-white">
  
 <div class="row">
	
	
		<!--1st col-->
	<!-- <div class="col-sm-1 ">	</div> -->
	<!--2nd col-->
	<div class="col-sm-12">
    
 <img class="mx-auto d-block" src="../images/bcic.jpg" width="130" height="130"/>
  <h3 class="mt-2 text-center"></h3>
  <h4 class="text-center">
    
	<hr class="">
  <a href="increment_final.php" class="btn btn-primary text-white"><i class="fa fa-spinner" style="color:white"></i> Increment Process (All)</a>
  <a href="increment_single.php" class="btn btn-primary text-white" ><i class="fa fa-group" style="color:white"></i> Increment Process (Single+)</a>
  <a href="increment_letter_single_and_multiple.php" class="btn btn-primary text-white" ><i class="fa fa-credit-card" style="color:white"></i> Increment Letter (Single+)</a>
  <hr class="">
   <a href="increment_letter_all.php" class="btn btn-primary text-white" ><i class="fa fa-credit-card" style="color:white"></i> Increment Letter All</a>
<a href="decrement.php" class="btn btn-primary text-white" ><i class="fa fa-spinner" style="color:white"></i> Decrement Process</a>

<a href="all_decrement_process.php" class="btn btn-primary text-white" ><i class="fa fa-spinner" style="color:white"></i> Decrement ALL</a>

 <hr class="">
<a href="../dashboard.php" class="btn btn-primary text-white" ><i class="fa fa-dashboard" style="color:white"></i> Dashboard</a>

	</h4>
  
  
  </div>
  <!--3rd col-->
  <!-- <div class="col-sm-1 "> </div> -->
	
	</div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by, BCIC.</h6></div>
</div>
 
</div>

<?php include('../includes/footer-dashboard.php');?>