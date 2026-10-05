<?php
//Start the session
session_start();

// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
if(($_SESSION['username']!=="sfcl") || ($_SESSION['role']!=="user")){
   header("location: dashboard.php");
}

include('db/db.php');

if(isset($_POST['submit_form'])){

$date=$_POST['date'];

//$factory_name=$_POST['factory_name'];
$daily=$_POST['daily'];
$remarks=$_POST['remarks'];
//$month=date('F',strtotime($date));

$month=date('m',strtotime($date));

//echo $month;
$year=date('Y',strtotime($date));

if($month==7){
	$month_id=1;
}
elseif ($month==8){
	$month_id=2;
}
elseif ($month==9){
	$month_id=3;
}
elseif ($month==10){
	$month_id=4;
}
elseif ($month==11){
	$month_id=5;
}
elseif ($month==12){
	$month_id=6;
}
elseif ($month==1){
	$month_id=7;
}
elseif ($month==2){
	$month_id=8;
}
elseif ($month==3){
	$month_id=9;
}
elseif ($month==4){
	$month_id=10;
}
elseif ($month==5){
	$month_id=11;
}
elseif ($month==6){
	$month_id=12;
}

//echo $month_id;

$sql = "SELECT * FROM sfcl where `date` LIKE'{$date}%'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
  // echo "Date already taken.";
 // header("location:sfcl_urea_form.php?already_exists=successfully");
} 

else {
  
// }

   while($row = mysqli_fetch_assoc($result))
	   
 {
	 $monthly = $row['monthly'];
	 $yearly = $row['yearly'];	
	 $date_db = $row['date'];	 
 } 
$production_target=450000;
$factory_name="sfcl";

$date_month=date('m/d/Y');
$date_jan=date('01/01/Y');
$date_feb=date('02/01/Y');
$date_mar=date('03/01/Y');
$date_april=date('04/01/Y');
$date_may=date('05/01/Y');
$date_jun=date('06/01/Y');
$date_july=date('07/01/Y');
$date_aug=date('08/01/Y');
$date_sept=date('09/01/Y');
$date_oct=date('10/01/Y');
$date_nov=date('11/01/Y');
$date_dec=date('12/01/Y');

$date=str_replace('_', '-', $date);
$date_month=str_replace('_', '-', $date_month);

$date_jan=str_replace('_', '-', $date_jan);
//$date=str_replace('_', '-', $date);
//$date_year=str_replace('_', '-', $date_year);
$out_msg="Succesfully insert data into database!!!";


// $fiscalyear2=strtotime("+11 months +25 days");
 
//$fiscalYearChangeDate = '-07-01'.date('Y');
$fiscalYearChangeDate ='01-07-'.date('Y');
//$fiscalyear=date($date_july,$fiscalYearChangeDate);



 $sql = "SELECT * FROM sfcl";
	$result = mysqli_query($conn, $sql);
   while($row = mysqli_fetch_assoc($result))
	   
	 {

		 $date_db = $row['date'];	 
	 }
$date1 = new DateTime($date_db);
$now = new DateTime($date);
	

   //for every month in current fiscal year
   if(strtotime($date) == strtotime($date_jan)||strtotime($date) == strtotime($date_feb)){

	    $monthly = $daily;
		$sql = "SELECT * FROM sfcl";
		$result = mysqli_query($conn, $sql);
	 while($row = mysqli_fetch_assoc($result)) {
		 $yearly = $row['yearly'];
		 //$monthly= $row['monthly'];
		}
		//$monthly = $daily;
		$yearly= $yearly + $daily;
		$due=$production_target-$yearly;
		//$month_id=10;
$sql_for_month="INSERT INTO sfcl(month_id,factory_name,date,daily,monthly,yearly,production_target,due,plant_load,remarks) 
	VALUES('{$month_id}','{$factory_name}','{$date}','{$daily}','{$monthly}','{$yearly}','{$production_target}','{$due}','{$plant_load}','{$remarks}')";
	 //echo ''.$out_msg;
	 //$sql_for_month="INSERT INTO sfcl(daily,monthly,yearly,date,plant_load,production_target,due,factory_name) VALUES('{$daily}','{$monthly}','{$yearly}','{$date}','{$plant_load}','{$production_target}','{$due}','{$factory_name}')";
	
	  if (mysqli_query($conn, $sql_for_month)) {
	  // header("Location: success.php");
	  //header("location:sfcl_urea_form.php?submitted=successfully");
	} else {
	  echo "Error: " . $sql_for_month . "<br>" . mysqli_error($conn);
	}
	exit();
	  }

	   //for fiscal year close and it should be (daily=monthly=yearly=0)
	 elseif (strtotime($date) == strtotime($fiscalYearChangeDate)){

	 	
	 	//header("location:sfcl_urea_form.php?submitted=kjshfkjs");

		 $yearly=$daily;
		echo $yearly;
		 $monthly=$daily;
		 echo $monthly;
		 $due=$production_target-$yearly;
$sql_for_month="INSERT INTO sfcl(month_id,factory_name,date,daily,monthly,yearly,production_target,due,plant_load,remarks) 
	VALUES('{$month_id}','{$factory_name}','{$date}','{$daily}','{$monthly}','{$yearly}','{$production_target}','{$due}','{$plant_load}','{$remarks}')";
		 //echo ''.$out_msg;
	 if (mysqli_query($conn, $sql_for_month)) {
	  // header("Location: success.php");
	  // header("location:sfcl_urea_form.php?submitted=successfully");
	} else {
	  echo "Error: " . $sql_for_month . "<br>" . mysqli_error($conn);
	}
	exit();
	 }

	//when all condintion not fullfilled 
 else{ 
	$sql = "SELECT * FROM sfcl";
	$result = mysqli_query($conn, $sql);
	 while($row = mysqli_fetch_assoc($result)) 
		{
		 $yearly_db = $row['yearly'];
		  $monthly_db = $row['monthly'];
		}
	 $monthly= (int)$monthly_db + (int)$daily;
	 $yearly=(int)$yearly_db + (int)$daily;
	 $due=$production_target-$yearly;
		//$month_id=10;
$sql_for_month="INSERT INTO sfcl(month_id,factory_name,date,daily,monthly,yearly,production_target,due,remarks) 
	VALUES('{$month_id}','{$factory_name}','{$date}','{$daily}','{$monthly}','{$yearly}','{$production_target}','{$due}','{$remarks}')";
	 //echo ''.$out_msg;
	 //echo "Succesfully insert data into database!!!";
		if (mysqli_query($conn, $sql_for_month)) {
	  // header("Location: success.php");
			//header("location:sfcl_urea_form.php?submitted=successfully");
	} else {
	  echo "Error: " . $sql_for_month . "<br>" . mysqli_error($conn);
	}
	exit();
	}	
	
}
}

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 
mysqli_close($conn);
 
 ?>

 <?php 
session_start();

// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
if(($_SESSION['username']!=="sfcl") || ($_SESSION['role']!=="user")){
   header("location: dashboard.php");
}
else{


?>


<?php
include('include/header.php');
?>

<div class="container mt-5 ">
  <h1 class="text-center">DFMS<b> [--SFCL--]</b></h1>
  
<div class="row">

<div class="col-sm-4">
<?php
if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully kjshfkjs!!!
</div>
<?php }?>

<?php
if(@$_GET['past_dated'])
{?>
<div class="alert alert-danger" role="alert">
  Date is Past !!!
</div>
<?php }?>

<?php
if(@$_GET['already_exists'])
{?>
<div class="alert alert-danger" role="alert">
  Date Already Exists !!!
</div>
<?php }?>

</div>
<div class="col-sm-4 border pt-2 my-2 mt-2">
<form action="test.php" method="POST">

<fieldset >

      <div class="col-sm-10">
		<div class="form-group">
	  <label for="date" class="form-check-label"> Choose a Date</label>
        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required>
          
		  
		  
		  <span id="user-availability-status"></span> 
		  
			<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>

			 
	  </div>
	  </div>
      <div class="col-sm-10"> 
	  <div class="form-group">
	  <label for="daily" class="form-check-label"> Daily Production(MT)</label>
        <input type="text" class="form-control" placeholder="Enter Daily Production" name="daily" id="daily" required>
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
      </div>

      <div class="form-group">
	  <label for="remarks" class="form-check-label"> Causes of Shutdown</label>
	  <textarea class="form-control" rows="5" placeholder="Enter Causes of Shutdown" name="remarks" id="remarks" ></textarea>
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
<h4 class="text-center"><a href="view_urea_report_sfcl.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">View Report</a></h4>
<h4 class="text-center"><a href="view_urea_report_sfcl.php?username=<?=$_SESSION['username']?>" class="btn btn-primary">Yearly Target Set</a></h4>
<h4 class="text-center"><a class="btn btn-success" href="sfcl_shutdown_info.php">Insert Shutdown Info. </a></h4>
<h4 class="text-center"><a class="btn btn-danger" href="logout.php">Logout</a></h4>
</div>
  
  </div>	   
  </div>
  <script type="text/javascript">     
// $('#submit').click(function(){
			function checkAvailability() {
			$("#loaderIcon").show();
			jQuery.ajax({
			url: "check_avail_sfcl.php",
			data:'date='+$("#date").val(),
			type: "POST",
			success:function(data){
			$("#user-availability-status").html(data);
			$("#loaderIcon").hide();
			},
			error:function (){

			}
			});
			}
	
			</script>	
<?php }
include('include/footer.php');
?>