<?php 
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
// echo $user_type;

// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
// if(($_SESSION['username']!=="sfcl") || ($_SESSION['role']!=="user")){
//    header("location: dashboard.php");
// }
// else{


// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

include('../include/header.php');
 
if($user_type=='sadmin'){
include('../include/topbar.php');		
}
?>

<div class="container mt-4 ">
  <h1 class="text-center">DFMS<b class="text-success text-uppercase"> [--<?=$_SESSION['username'];?>--]</b></h1>
  
<div class="row">

<div class="col-sm-4">
<!-- <?php
//if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully!!!
</div>
<?php }?> -->

<?php
//session_start(); // Start the session

if(isset($_GET['past_dated']) && $_GET['past_dated'] === "Back date error") {
    $_SESSION['back_date_error'] = true; // Set a session variable for back date error
    header("Location: urea_form.php");
    exit(); // Stop further execution
}

if(isset($_GET['advance_dated']) && $_GET['advance_dated'] === "Advanced date error") {
    $_SESSION['advance_date_error'] = true; // Set a session variable for advance date error
    header("Location: urea_form.php");
    exit(); // Stop further execution
}
if(isset($_GET['already_exists']) && $_GET['already_exists'] === "already_exists") {
    $_SESSION['already_exists'] = true; // Set a session variable for advance date error
    header("Location: urea_form.php");
    exit(); // Stop further execution
}
if(isset($_GET['submitted']) && $_GET['submitted'] === "successfully") {
    $_SESSION['submitted'] = true; // Set a session variable for advance date error
    header("Location: urea_form.php");
    exit(); // Stop further execution
}

// Check if there's an error stored in the session
if(isset($_SESSION['advance_date_error'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Date is Advance!!!
    </div>
    <?php
    unset($_SESSION['advance_date_error']); // Clear the error after displaying it
}
elseif(isset($_SESSION['back_date_error'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Date is Back2024!!!
    </div>
    <?php
    unset($_SESSION['back_date_error']); // Clear the error after displaying it
}
elseif(isset($_SESSION['already_exists'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Date is Already Exists!!!
    </div>
    <?php
    unset($_SESSION['already_exists']); // Clear the error after displaying it
}
elseif(isset($_SESSION['submitted'])) {
    ?>
    <div class="alert alert-success" role="alert">
        Data Inserted Successfully!!!
    </div>
    <?php
    unset($_SESSION['submitted']); // Clear the error after displaying it
}
?>
<!-- <?php
//if(@$_GET['past_dated'])
{?>
<div class="alert alert-danger" role="alert">
  Date is Past !!!
</div>
<?php }?> -->

<!-- <?php
//if(@$_GET['already_exists'])
{?>
<div class="alert alert-danger" role="alert">
  Date Already Exists !!!
</div>
<?php }?> -->

</div>
<div class="col-sm-4 border pt-2 my-2 mt-2 shadow rounded border-2 border-success">
<form action="insert_urea.php" method="POST">

<fieldset class="">

      <div class="col-sm-10">
		<div class="form-group">
	  <label for="date" class="form-check-label"> Choose a Date</label>

        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required value="<?php echo date("Y-m-d");?>">
          
		 <span id="user-availability-status"></span> 
		  
		<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>

			 
	  </div>
	  </div>
      <div class="col-sm-10"> 
	

      <div class="form-group">
		  <label for="delivery" class="form-check-label"> Delivery (MT)</label>
		  <input type="text" class="form-control" placeholder="Enter Delivery (MT)" name="delivery" id="delivery" >
	   </div>
	   <div class="form-group">
		  <label for="pipeline" class="form-check-label"> Pipeline (MT)</label>
		  <input type="text" class="form-control" placeholder="Enter Pipeline (MT)" name="pipeline" id="pipeline" >
	   </div>

	<div class="col-sm-10">	
		<div class="mb|sm-4 d-grid">
	     <!-- <button id="submit" type="submit" class="btn btn-warning btn-block rounded-pill">Register</button> -->
	   <div class="form-group">
		<button type="submit" name="submit_form" class="btn btn-block btn-primary rounded-pill">Insert</button>
		</div>
		</div> 
      
    </div>  
    </fieldset>
  </form>
</div>

	
<div class="col-sm-4">
	<?php 
	if($user_type=='sadmin'){
		?>
		<h4 class="text-center">
		<a href="view_urea_report.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Edit </a>

		<a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
		</h4>
                     

	<?php
	}else{
		?>
	<h4 class="text-center">
		<a href="view_urea_report.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Edit </a>
	</h4>
		<?php

	}

?>


<h4 class="text-center"><a href="urea_report_with_date_range.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Print Report (Date Range)</a></h4>
<h4 class="text-center"><a href="show_all_urea.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Show All</a></h4>
<h4 class="text-center"><a href="yearly_target_set.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Yearly Target Set</a></h4>

<?php 
	if($user_type=='admin'){
		?>
		<center>
			<form  id="downloadForm" action="dawnload_database.php" method="post">
        <button class="btn btn-warning" type="submit" name="submit" > Download Database</button>
      </form> 
		</center>
                     

	<?php
	}

?>

<h4 class="text-center"><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></h4>
</div>
  
  </div>	   
  </div>
<script type="text/javascript">     
function checkAvailability() {
	var table1 = "<?php echo $table; ?>";
    $("#loaderIcon").show();
    $.ajax({
        url: "check_avail.php",
        data: { 
        	date: $("#date").val(),
        table1: table1
    },

        type: "POST",
        success: function(data) {
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
        },
        error: function() {
            // Handle error
        }
    });
}

</script>	
<?php 
// }
include('../include/footer.php');
?>