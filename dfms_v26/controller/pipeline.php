<?php
//Start the session
session_start();
// $table=$_SESSION['username'];
// $user_type=$_SESSION['user_type'];
// $office_type=$_SESSION['office_type']; //bcic_hq

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}
include('../include/header.php');
include('../db/db.php');

$i=0;
if(isset($_GET['val'])){
        $table = $_GET['val']; //kaliganj_buffer
        //$i=1;
        echo $table;
        $_SESSION['buffer_name']=$table;
    }
   else
   {
    $table=$_SESSION['username'];
   }

    $date = date('Y-m-d');

    $sql_retrive = "SELECT * FROM $table where `date` LIKE'{$date}%'";
	$result_retrive = mysqli_query($conn, $sql_retrive);
	$row_retrive = mysqli_fetch_assoc($result_retrive);

	if(mysqli_num_rows($result_retrive) == 0){
	
	$pipeline="";
	
	}
	
	else{
	$pipeline=$row_retrive['pipeline'];
	$total_stock=  $row_retrive['total_stock'];
	
	}

?>

<div class="container my-3">
	<div class="col-sm-4 "></div>
	<div class="col-sm-4 border">
		<label for="date" class="form-check-label"> Delivery (MT)</label>
		<form action="insert_urea.php" method="POST">

		     
				<div class="form-group">
			  <label for="date" class="form-check-label"> Choose a Date</label>

		        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required value="<?php echo date("Y-m-d");?>">          
				 <span id="user-availability-status"></span> 		  
				<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>			 
			  </div>
			  
		      

		      <div class="form-group">
				  <label for="buffer_name" class="form-check-label"> Buffer Name</label>
				  <!-- <input type="text" class="form-control" placeholder="Enter Delivery (MT)" name="buffer_name" id="buffer_name" value="<?php echo $buffer_name?>" > -->
				  <select class="form-select form-select mb-3" name="buffer_name" aria-label="Default select example">
					  <option selected > Select </option>
					  <option value="kaliganj_buffer">Kaliganj Buffer</option>
					  <option value="shiromoni_buffer">Shiromoni Buffer</option>
					  <option value="jessore_buffer">Jessore Buffer</option>
					</select>
			   </div>				     

		      <div class="form-group">
				  <label for="pipeline" class="form-check-label"> Pipeline Ammount(MT)</label>
				  <input type="text" class="form-control" placeholder="Enter Delivery (MT)" name="pipeline" id="pipeline" value="<?php echo $pipeline?>" >
			   </div>
			   <div class="form-group">
				  <label for="pipeline" class="form-check-label"> Total Stock (MT)</label>
				  <input type="text" class="form-control" placeholder=" Total Stock (MT)" name="total_stock" readonly="" value="<?php echo $total_stock?>" id="total_stock" >
			   </div>

			   

			<div class="col-sm-10">	
				<div class="mb|sm-4 d-grid">
			     <!-- <button id="submit" type="submit" class="btn btn-warning btn-block rounded-pill">Register</button> -->
			   <div class="form-group">
				<button type="submit" name="submit_pipeline" class="btn btn-block btn-primary rounded-pill">Save</button>
				</div>
				</div> 
		      
		    </div>  
		    
  </form>
</div>
<div class="col-sm-4 "></div>

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