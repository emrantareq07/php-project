
<?php
include('../db/db.php');
include('../includes/header.php');
	
?>

<div class="container mt-2 p-1 my-1 border shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b>Decrement Process</b></h2>
    	<div class="row">
		
    		<div class="col-sm-12">
    		<?php if(@$_GET['submitted'])
			{?>
			<div class="alert alert-success" role="alert">
			 <b> Basic Decrement Successfully!!!</b>
			</div>
			<?php }?>
    			
		<div class="panel-body">
			
	
		<div class="col-sm-4"> </div>		
					
		<div class="col-sm-4"> 
			<div class="card border border-warning">
			  <div class="card-header">
				
			  </div>
			<div class="card-body">
				
			<form method="POST" action="decrement_process.php" enctype="multipart/form-data">

				<div class="form-group">
					<input class="form-control" placeholder="Enter Emp ID" type="text" name="emp_id" id="emp_id" required="">
				</div>

					<button type="submit" id="submit" name="submit" class="btn btn-md btn-danger btn-block"><i class="fa fa-sort-amount-desc"></i> </span> Decrement Process</button>  
				
			
				
			</form>

</div>
</div>
</div>
<div class="col-sm-4"> <a href="increment_details.php" class="btn btn-primary"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></div>



</div>
</div>
</div>
</div>
<?php include('../includes/footer.php');?>