<?php
include('../includes/header-bio-increment.php');
include('../db/db.php');?>
<div id="nonPrintableContent"  class="container mt-4 p-4 my-4  ">
	<!--  <a href="increment_update.php" class="btn btn-success">Increment Process</a> -->
<div class="row">
	<div class="col-md-12 col-sm-12 border shadow rounded bg-light">
		<h1 class="text-center text-info text-uppercase"><b>BCIC PMIS Increment System</b></h1>
	<h3 class="text-center text-muted text-uppercase"> Click button for Increment</h3>
	<div class="col-md-4 col-sm-4 "></div>
	<div class="col-md-4 col-sm-4  mt-4 p-4 my-4">

		<div class="form-group">
				<label for="grade">Grade:</label>
				<select class="form-control" id="grade" name="grade" tabindex="">
						<option value="" disabled selected>--Select--</option>
						<?php
                    
						$sql = "SELECT * FROM grade";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['grade']."</option>";
						}?>	
					 </select>
				
				
				</div>
				
				<div class="form-group">
					<label for="scale">Scale:</label>
					<select class="form-control" id="scale" name="scale" tabindex="" required>
						<option value="" disabled selected>--Select--</option>
						<?php
                        //require_once('db/db.php');

						// $sql = "SELECT * FROM pay_scale_2015";
						// $result = mysqli_query($conn, $sql);
						// while($row = mysqli_fetch_array($result))
						// {
						 // echo "<option value='".$row['id']."'>".$row['scale']."</option>";
						// }?>		
					 </select>
				
				
				</div>

				<div class="form-group">
					<label for="basic">Basic:</label>
					<select class="form-control" id="basic" name="basic" tabindex=""  required>
					<!-- <select class="form-control" id="basic" name="basic" tabindex="13" onchange="calculateAmount4(this.value)" required> -->
						<option value="" disabled selected>--Select--</option>
						
					 </select>
				
				
				</div>



		
		<form method="POST" action="">  
		 <center>
		<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
		<button type="submit" name="submit" class="btn btn-success text-center"><i class="fa fa-line-chart"></i>  Increment Process </button>
		 </center>
          
		</form>

	</div>
	<div class="col-md-4 col-sm-4  mt-4 p-4 my-4 ">
		<form method="POST" action="">  
		 <center>
		<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
		<button type="submit" name="submit" class="btn btn-danger text-center"><i class="fa fa-print"></i>  Print All (In-service) </button>
		 </center>
          
		</form>
		<hr>
		<form method="POST" action="">  
		 <center>
		<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
		<button type="submit" name="submit" class="btn btn-danger text-center"><i class="fa fa-print"></i>  Print All (PRL) </button>
		 </center>
          
		</form>
		<hr>
		<center><a class="btn btn-primary" href="increment_details.php"><i class="fa fa-arrow-left"></i> Previous page </a></center></div>

	</div>

</div>


</div>
<?php include('../includes/footer.php'); ?>