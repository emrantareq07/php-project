

<?php
include('../db/db.php');
include('../includes/header.php');


if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully
</div>
<?php }?>


    <div class="container mt-2 p-1 my-1 border shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1">Seniority List Update Form</h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    			
		<div class="panel-body">
			
	
		<div class="col-sm-4"> </div>		
						<!-- Educational Info-->
		<div class="col-sm-4"> 
			<div class="card border border-warning">
			  <div class="card-header">
				<!-- <label for="ssc_exam">Educational Information Portion-1</label> -->
				
			  </div>
			<div class="card-body">
				
			<form method="POST" action="update_seniority_list.php" enctype="multipart/form-data">

				<div class="form-group">
					<input class="form-control" placeholder="Enter Emp ID" type="text" name="emp_id" id="emp_id" >
				</div>

					<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block">
					<span class="glyphicon glyphicon-floppy-disk"></span> Search</button>  
				
			
				
			</form>

</div>
</div>
</div>
<div class="col-sm-4"> <a href="../bio-data/bio-data_and_gradation_list.php" class="btn btn-primary"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></div>

</div>
</div>
</div>
</div>
<?php include('../includes/footer.php');?>