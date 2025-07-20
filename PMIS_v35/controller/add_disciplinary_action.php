<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');

include('../db/db.php');
	if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
 
 $emp_id=$_SESSION['emp_id'];
 $user_id=$_SESSION['id'];
 
 $date=$_POST['date'];
 $punishment=$_POST['punishment'];
 $nat_of_offense=$_POST['nat_of_offense'];
 $remarks=$_POST['remarks'];
// $created_at=date('d-m-Y');
 // $till_now=date('d-m-Y');

 //$sql="SELECT * FROM users where id='$edit_id'";
 $sql="INSERT INTO disciplinary_action (user_id, emp_id,date,punishment,nat_of_offense) 
 VALUES ('{$user_id}','{$emp_id}','{$date}','{$punishment}','{$nat_of_offense}')";
//$result=mysqli_query($conn,$sql);
if(mysqli_query($conn,$sql))
{
		header("location:add_disciplinary_action.php?submitted=successfully");
		//header("Location: view_profile_details.php");
}
else
	print mysqli_error();
 }
?>
 <!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> -->
 
 <script type="text/javascript">
       // $(function() {
               // $("#dob").datepicker({ dateFormat: "dd-mm-yyyy" }).val()
       // });


  </script>
  <script type="text/javascript">
    $(document).ready(function () {
        // $('#datepicker').datepicker({ format: "dd-mm-yyyy" }).val();
        // $("#datepicker").datepicker("option", "dateFormat", "dd/mm/yy");
        //format: 'dd/mm/yyyy'
  //       $( "#datepicker" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
		// $( "#datepicker" ).datepicker( "setDate", new Date());    
		// $('.ui-datepicker-calendar').hide();
    });
</script>

  <!-- Bootstrap datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>

<!-- Bootstrap datepicker JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
 <div class="container mt-2 p-1 my-1 border shadow-lg bg-white" onload='document.form1.edu_info.focus()'>
    <h2 class="page-header text-center text-success mt-2 p-1 my-1 text-uppercase">Add Disciplinary Action</h2>
    <div class="row">
		<div class="col-sm-12">
    		
		<div class="panel-body">
		  <?php
			if(@$_GET['submitted'])
			{?>
			<div class="alert alert-success" role="alert">
			  <b>Data Inserted Successfully!!! </b>
			</div>
			<?php }?>	
			
	<form method="POST" id="form1" name="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>"
			enctype="multipart/form-data">
							
	<div class="row">
			<!-- ist column-->
		<div class="col-sm-4"> </div>
		<!--Second Column-->
			<div class="col-sm-4"> 
				<div class="card border border-warning">
			  	<div class="card-header">
					<label>Add Disciplinary Action</label>
			  	</div>
			<div class="card-body">

				<div class="form-group">
				<label for="date">Date:</label>
				 <input class="form-control" placeholder="" type="date" name="date" id="date" value="">
				
				</div>
				<div class="form-group">
					<label for="punishment">Punishment:</label>
					 <input class="form-control" placeholder="Enter Punishment" type="text" name="punishment" id="punishment" value="">
				</div>
				
				<div class="form-group"><label for="nat_of_offense">Nature of Offence:</label>
					<textarea class="form-control" placeholder="Enter Nature of Offence...." rows="2" id="nat_of_offense"  name="nat_of_offense" ></textarea>
				</div>

				
				<div class="form-group">
				<label for="remarks">Remarks:</label>
				 <input class="form-control" placeholder="Enter Remarks" type="text" name="remarks" id="remarks" value="" >
				</div>		

				
				</div>
				</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-4 text-center"> 
				<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary float-center " tabindex="14">
				<span class="glyphicon glyphicon-floppy-disk"></span> Save</button> 
				
				<a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous Page </a>

				</div>
				<div class="row"> 
				
				<div class="col-sm-2">  </div>
				<div class="col-sm-6">  </div>
				<div class="col-sm-4"></div>
				
				</div>
				
								
			</form>
			<div id="err"></div>
				<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		</div>
					
    		   
    			
    		</div>
    	</div>
    </div>

<?php include('../includes/footer.php');?>