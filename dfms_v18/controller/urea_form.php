<?php
session_name('dfms');
session_start();
ob_start(); // Start output buffering

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

if(isset($_GET['val'])){
$table = $_GET['val']; 
$user_type = $_GET['user_type'];
$_SESSION['username']=$table ;
$_SESSION['user_type']=$user_type;
}
else {
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
}

if ($user_type == 'sadmin') {
  include('../include/topbar.php');	
  include('../include/header_sadmin.php');	
} else {
include('../include/header.php');
  include('../include/topbar_user.php');
}

?>
<div class="container mt-5 ">
  <!-- <h1 class="text-center">DFMS<b class="text-success text-uppercase"> [--<?=$_SESSION['username'];?>--]</b></h1>   -->
<div class="row">
<div class="col-sm-3">
<?php
if(isset($_GET['past_dated']) && $_GET['past_dated'] === "Back date error") {
    $_SESSION['back_date_error'] = true;
    header("Location: urea_form.php");
    exit();
}

if(isset($_GET['advance_dated']) && $_GET['advance_dated'] === "Advanced date error") {
    $_SESSION['advance_date_error'] = true;
    header("Location: urea_form.php");
    exit();
}
if(isset($_GET['already_exists']) && $_GET['already_exists'] === "already_exists") {
    $_SESSION['already_exists'] = true;
    header("Location: urea_form.php");
    exit();
}
if(isset($_GET['submitted']) && $_GET['submitted'] === "successfully") {
    $_SESSION['submitted'] = true;
    header("Location: urea_form.php");
    exit();
}

// Check if there's an error stored in the session
if(isset($_SESSION['advance_date_error'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Date is Advance!!!
    </div>
    <?php
    unset($_SESSION['advance_date_error']);
}
elseif(isset($_SESSION['back_date_error'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Date is Back2024!!!
    </div>
    <?php
    unset($_SESSION['back_date_error']);
}
elseif(isset($_SESSION['already_exists'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Date is Already Exists!!!
    </div>
    <?php
    unset($_SESSION['already_exists']);
}
elseif(isset($_SESSION['submitted'])) {
    ?>
    <div class="alert alert-success" role="alert">
        Data Inserted Successfully!!!
    </div>
    <?php
    unset($_SESSION['submitted']);
}
?>
</div>
<?php 
//if ($user_type != 'sadmin') { 	 
?>
<div class="col-sm-6 border shadow rounded border-2 border-success">	
	<form action="insert_urea.php" method="POST">      	
	<div class="mt-4"></div>
		<div class="form-group">
			<label for="date" class="form-check-label">Choose a Date</label>
			<input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required value="<?php echo date('Y-m-d', strtotime('-1 day'));?>">
			<span id="user-availability-status"></span>
			<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>
		</div>

   <div class="form-group">
    <label for="product_produce" class="form-check-label">Product</label>    
    <select class="form-control" name="product_produce" id="product_produce">
        <?php
        if ($table == "sfcl" || $table == "jfcl" || $table == "afccl" || $table == "gpfplc" || $table == "cufl") {
            echo '<option value="Urea">Urea</option>';
        } elseif ($table == "tspcl") {
            echo '<option value="TSP">TSP</option>';
        } elseif ($table == "dapfcl") {
            echo '<option value="DAP">DAP</option>';
        } elseif ($table == "kpml") {
            echo '<option value="Paper">Paper</option>';
        } elseif ($table == "cccl") {
            echo '<option value="Cement">Cement</option>';
        } elseif ($table == "ugsf") {
            echo '<option value="Sheet Glass">Sheet Glass</option>';
        } else {
            echo '<option value="sanitary">Sanitary</option>';
            echo '<option value="insulator">Insulator</option>';
            echo '<option value="refractories">Refractories</option>';
        }
        ?>
    </select>
   </div>	   
		<div class="form-group">
			<label for="daily" class="form-check-label">Daily Production (MT)</label>
			<input type="text" class="form-control"  name="daily" id="daily">
		</div>

		<div class="mt-1"></div> 
		<div class="form-group">
			<label for="plant_load" class="form-check-label">Plant Load (%)</label>
			<input type="text" class="form-control"  name="plant_load" id="plant_load">
		</div>

		<div class="mt-1"></div> 
		<div class="form-group">
			<label for="remarks" class="form-check-label">Remarks</label>
			<textarea class="form-control" rows="3"  name="remarks" id="remarks"></textarea>
			<!-- placeholder="Enter Causes of Shutdown" 
                 placeholder="Enter Plant Load (%)"
                 placeholder="Enter Daily Production"
			-->
		</div>	
		<hr>
		<div class="form-group">
		<button type="submit" name="submit_form" class="btn btn-primary" ><i class="fa fa-save" style="font-size:15px;"></i> Insert</button>
		</div>   
	</form><br>
</div>
<div class="col-sm-3">
	<?php 
	//}
	if($user_type=='sadmin'){
		?>
		<h4 class="text-center">
            <a href="view_urea_report.php?username=<?= urlencode($_SESSION['username']) ?>&user_type=<?= urlencode($_SESSION['user_type']) ?>" 
               class="btn btn-warning">
                <i class="fa fa-edit" style="font-size:15px; color:black;"></i> Edit Record
            </a>
            <br>
            <a href="manage_user.php?username=<?= urlencode($_SESSION['username']) ?>" 
               class="btn btn-warning">
                <i class="fa fa-edit" style="font-size:15px; color:black;"></i> Manage User
            </a>
            <br>        
            <a class="btn btn-info" href="set_name.php">
                <i class="fa fa-edit"></i> Set Head Name
            </a>
        </h4>

	<?php
	}
    elseif ($user_type == 'admin') {
    ?>
    <h4 class="text-center">
        <a href="home.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Previous Page
        </a>
    </h4>

    <h4 class="text-center">
        <a href="view_urea_report.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" class="btn btn-warning">
            <i class="fa fa-edit" style="font-size:15px;color:black"></i> Edit Record
        </a>
    </h4>
    <?php
} 
else {
    ?>
    <h4 class="text-center">
        <a href="view_urea_report.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" class="btn btn-warning">
            <i class="fa fa-edit" style="font-size:15px;color:black"></i> view Record
        </a>
    </h4>
    <?php
}
?>
<h4 class="text-center">
    <a href="urea_report_with_date_range.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" class="btn btn-danger">
        <i class="fa fa-print"></i> Print Report
    </a>
</h4>
<h4 class="text-center">    <a href="yearly_target_set.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" class="btn btn-primary">
        <i class="fa fa-calendar"></i> Yearly Target Set
    </a>
</h4>
<h4 class="text-center">
    <a href="monthly_target.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" class="btn btn-primary">
        <i class="fa fa-calendar"></i> Monthly Target Set
    </a></h4>


<hr>
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
include('../include/footbar_user.php');
ob_end_flush(); // Flush the output buffer and turn off output buffering
?>
