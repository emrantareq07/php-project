<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');?>
<?php
include('../db/db.php');
 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	 //$edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];
	 
	 $qualification=$_POST['qualification'];
	// $ground_or_subject=$_POST['ground_or_subject'];
	//  $nature=$_POST['nature'];
	 
	// $created_at=date('d-m-Y');
	 // $till_now=date('d-m-Y');

	 //$sql="SELECT * FROM users where id='$edit_id'";
	 $sql="INSERT INTO add_prof_quali_info (user_id, emp_id,qualification) 
	 VALUES ('{$user_id}','{$emp_id}','{$qualification}')";
	//$result=mysqli_query($conn,$sql);
	if(mysqli_query($conn,$sql))
	{
			header("location:add_prof_qualification_info.php?submitted=successfully!!!.");
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

 <div class="container mt-4 p-2 my-2 border rounded shadow-lg bg-light" onload='document.form1.add_prof_qualification_info.focus()'>
    	<h2 class="page-header text-center text-success mt-2 p-2 my-2 text-uppercase"><b>Add Professional Qualification </b></h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    		
		<div class="panel-body">
		  <?php
			if(@$_GET['submitted'])
			{?>
			<div class="alert alert-success" role="alert">
			  <b>Data Inserted Successfully</b>
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
				<label>Add Professional Qualification</label>
			  </div>
			<div class="card-body">

				<!-- <div class="form-group">
					<label for="ground_or_subject">Qualification:</label>
					<textarea class="form-control" placeholder="Enter Ground/Subject...." rows="2" id="ground_or_subject"  name="ground_or_subject" ></textarea>
				</div> -->
				<div class="form-group">
				<label for="qualification">Qualification:</label>
				 <input class="form-control" placeholder="Enter Qualification" type="text" name="qualification" id="qualification" value="">
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
				
		</div>
					
    		   
    			
    		</div>
    	</div>
    </div>

<?php include('../includes/footer.php');?>