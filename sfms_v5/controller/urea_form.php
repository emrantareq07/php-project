<?php 
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];

 //echo $user_type;

// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
// if(($_SESSION['username']!=="sfcl") || ($_SESSION['role']!=="user")){
//    header("location: dashboard.php");
// }
// else{

// Ensure the correct timezone is set
date_default_timezone_set('Asia/Dhaka'); // Correct timezone identifier for Dhaka, Bangladesh


// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include('../db/db.php');
 include('../include/header.php');
 include('../include/topbar.php');
 
// if($user_type=='sadmin'){
// include('../include/topbar.php');		
// }
?>  

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

<?php 
    $date = date('Y-m-d');
    $_SESSION['date']=$date;

    $sql_retrive = "SELECT * FROM $table where `date` LIKE'{$date}%'";
	$result_retrive = mysqli_query($conn, $sql_retrive);
	$row_retrive = mysqli_fetch_assoc($result_retrive);

	if(mysqli_num_rows($result_retrive) == 0){
	
	$receive_import1="";
	$receive_factory1= "";
	$delivery1= "";
	}
	
	else{
	$receive_import1=$row_retrive['receive_import'];
	$receive_factory1=  $row_retrive['receive_factory'];
	$delivery1=  $row_retrive['delivery'];
	
	}


	$sql_target_table_retrive="SELECT total_stock,pipeline from $table where id=(Select max(id) FROM $table)";
	$result = mysqli_query($conn, $sql_target_table_retrive);
	$row = mysqli_fetch_assoc($result);
	$pipeline=  $row['pipeline'];

	?>
	<br><br>
<div class="container-fluid mt-5 ">
<h1 class="text-center text-muted">SFMS<b class="text-success text-uppercase"> [--<?=$_SESSION['username'];?>--]</b></h1>

<div class="container pt-1 my-1 mt-1">
<div class="col-sm-12 border shadow rounded border-2 border-success">  
<form action="insert_urea.php" method="POST">
	<div class="row">
	<h5 class=" text-uppercase "><b> Receive Urea(MT)</b> </h5>
      <div class="col">
		<div class="form-group">
	  		<label for="date" class="form-check-label"> Choose a Date</label>
        	<input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required value="<?php echo $date?>" readonly>          
		 <span id="user-availability-status"></span> 		  
		<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>			 
	  </div>
	  </div>
      <div class="col"> 
		  <div class="form-group">
		  <label for="receive_import" class="form-check-label"> Receive Import(MT)</label>
	        <input type="text" class="form-control" placeholder="0" name="receive_import" id="receive_import" value="">			
      </div>
  </div>
	<div class="col"> 
      <div class="form-group">
	  	<label for="receive_factory" class="form-check-label"> Receive Factory(MT)</label>
        <input type="text" class="form-control" placeholder="0" name="receive_factory" id="receive_factory" value="">
    	</div>
	</div>
	<div class="col"> 	
    <div class="form-group">
		  <label for="pipeline" class="form-check-label"> Total Stock (MT)</label>
		  <input type="text" class="form-control" placeholder=" Total Stock (MT)" name="total_stock" readonly="" value="<?php echo !empty($row['total_stock'])?$row['total_stock']:''; ?>" id="total_stock" >
	   </div>
	</div>

      <div class="col"> 
	   <div class="form-group">
		  <label for="pipeline" class="form-check-label"> Pipeline (MT)</label>
		  <input type="text" class="form-control" placeholder=" Pipeline (MT)" readonly="" value="<?php echo !empty($row['pipeline'])?$row['pipeline']:''; ?>" name="pipeline" id="pipeline" >
	   </div></div>

	<div class="col">	<label for="pipeline" class="form-check-label"><br></label>
		<div class="form-group" align="middle"><label for="pipeline" class="form-check-label"> </label>
		<button type="submit" name="submit_form" class="btn btn-outline-primary btn-sm"><i class="fa fa-save"></i> Save</button>
		<a href="update_daily_record.php?username=<?php echo $_SESSION['username']; ?>&date=<?php echo $_SESSION['date']; ?>" class="btn btn-warning  btn-sm">
    		<i class="fa fa-edit"></i> Edit
		</a>
		</div>
	</div> 

    </div> 
</form>
</div>
</div>

<div class="container mt-3">
<div class="col-sm-12 border pt-2 my-2 mt-2 shadow rounded border-2 border-success"> 
<h5 for="date" class="text-uppercase"> Delivery Urea(MT)</h5>
<form action="insert_urea.php" method="POST">
	<div class="row">
      <div class="col">
		<div class="form-group">
	  	<label for="date" class="form-check-label"> Choose a Date</label>

        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required value="<?php echo date("Y-m-d");?>" readonly>          
		 <span id="user-availability-status"></span> 		  
		<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>			 
	  </div>
	  </div>
      <div class="col">
      <div class="form-group">
		  <label for="delivery" class="form-check-label"> Delivery (MT)</label>
		  <input type="text" class="form-control" placeholder="0" name="delivery" id="delivery" value="" >
	   </div>
	</div>
	<div class="col">
	   <div class="form-group">
		  <label for="pipeline" class="form-check-label"> Total Stock (MT)</label>
		  <input type="text" class="form-control" placeholder=" Total Stock (MT)" name="total_stock" readonly="" value="<?php echo !empty($row['total_stock'])?$row['total_stock']:''; ?>" id="total_stock" >
	   </div>

	   </div>

	<div class="col">
		<label for="pipeline" class="form-check-label"><br></label>
	   <div class="form-group">

		<button type="submit" name="submit_delivery" class="btn  btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
		<a href="update_daily_record.php?username=<?php echo $_SESSION['username']; ?>&date=<?php echo $_SESSION['date']; ?>" class="btn btn-warning btn-sm">
    		<i class="fa fa-edit"></i> Edit
		</a>
		</div>
		</div>   
    
    </div>
  </form>
</div>
</div>

<div class="container mt-3">
<div class="row">	
<div class="col">
	<?php 
	if($user_type=='sadmin'){
		?>
		<h4 class="text-center">
		<!-- <a href="view_urea_report.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Edit </a> -->

		<a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
		</h4>
                     

	<?php
	}else{
		?>
	<!-- <h4 class="text-center">
		<a href="view_urea_report.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Edit </a>
	</h4> -->
		<?php

	}

?>
<!-- 
<h4 class="text-center"><a href="pipeline_details.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Pipeline Data</a></h4>
<h4 class="text-center"><a href="urea_report_with_date_range.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Print Report (Date Range)</a></h4>
<h4 class="text-center"><a href="show_all_urea.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Show All</a></h4>
<h4 class="text-center"><a href="yearly_target_set.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Yearly Target Set</a></h4> -->

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

<!-- <h4 class="text-center"><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i>Logout</a></h4> -->
</div>
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
<script>		
	  
	  // Restricts input for each element in the set of matched elements to the given inputFilter.
	(function($) {
	  $.fn.inputFilter = function(callback, errMsg) {
		return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
		  if (callback(this.value)) {
			// Accepted value
			if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
			  $(this).removeClass("input-error");
			  this.setCustomValidity("");
			}
			this.oldValue = this.value;
			this.oldSelectionStart = this.selectionStart;
			this.oldSelectionEnd = this.selectionEnd;
		  } else if (this.hasOwnProperty("oldValue")) {
			// Rejected value - restore the previous one
			$(this).addClass("input-error");
			this.setCustomValidity(errMsg);
			this.reportValidity();
			this.value = this.oldValue;
			this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
		  } else {
			// Rejected value - nothing to restore
			this.value = "";
		  }
		});
	  };
	}(jQuery));


	// Install input filters.
	$("#daily").inputFilter(function(value) {
	  return /^-?\d*$/.test(value); }, "Must be an integer");
	

	</script>	
<?php 
// }
include('../include/footer.php');
?>