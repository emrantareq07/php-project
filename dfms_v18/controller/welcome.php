<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    // header("location: login.php");
    // exit;
//}
?>
<?php
// Start the session
// session_start();
if(!$_SESSION['username']){

header("location: login.php");
}
?>

<?php
include('header.php');
?>
  
<div class="container">
  
 <div class="row">
  
    <div class="col-sm-3">
	<hr>
	  <h2><a href="#">Search by Date</a></h2>
	  <hr>
      
    </div>
	<div class="col-sm-6">
	<h2 class="text-success text-center">Welcome DFMS !!!</h2>
  <p></p> 

	<p><h4 class="text-center"><a class="btn btn-primary" href="#">Back to Previous Page</a></h4></p> 
	
	<h4 class="text-center"><a class="btn btn-primary" href="index.php">Back to Main Page</a></h4>
	<h4 class="text-center"><a class="btn btn-info" href="logout.php">Logout</a></h4>
	</div>
	
    <div class="col-sm-3">
      <h3><a href="view_report_search_by_date.php">View Report</a></h3>
    </div>
  </div>
 
</div>
<?php
include('footer.php');
?>