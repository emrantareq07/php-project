<!-- add_bank_info.php -->
<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');
include('../db/db.php');
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val'];
 echo $val;
  }

 if(isset($_REQUEST['submit']) && isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];	 

	$sql = "SELECT * FROM emp_bank_info WHERE emp_id='$emp_id'";
    $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo '<script type="text/javascript">';
    echo 'alert("Bank Information Already taken!");';
    // echo 'window.location.href="add_bank_info.php"';
    echo 'window.location.href="../login/user_dashboard.php"';
    echo '</script>';
  } 
  else {
	 $bank_name=$_POST['bank_name'];
	 $branch_name=$_POST['branch_name'];
	 $branch_add=$_POST['branch_add'];
	 $acc_name=$_POST['acc_name'];
	 $acc_no=$_POST['acc_no'];
	 $swift_code=$_POST['swift_code'];
	 $routing_no=$_POST['routing_no'];
		 
	 $created_at = date('Y-m-d');
	
	 $sql="INSERT INTO emp_bank_info (user_id, emp_id,bank_name,branch_name,branch_add,acc_name,acc_no,swift_code,routing_no,created_at) 
	 VALUES ('{$user_id}','{$emp_id}','{$bank_name}','{$branch_name}','{$branch_add}','{$acc_name}','{$acc_no}','{$swift_code}','{$routing_no}','{$created_at}')";
	
	if(mysqli_query($conn,$sql)){
		if($val==1){
			// header("Location: ../login/user_dashboard.php?emp_id=".'3');
			
			echo '<script type="text/javascript">';
		    echo 'alert("Data Inserted Successfully!!!");';
		    echo 'window.location.href="../login/user_dashboard.php"';
		    echo '</script>';

		}
		else{
			// header("Location: add_bank_info.php?emp_id=".'3');
			
		}
			
	}
	else
		print mysqli_error();
 }
}
?>
<!-- Bootstrap datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>

<!-- Bootstrap datepicker JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

 <div class="container mt-2 p-2 my-2 border shadow-lg bg-light" onload='document.form1.edu_info.focus()'>
    	<h2 class="page-header text-center text-success mt-2 p-2 my-2 text-uppercase"><b>Add EMP Bank Information</b></h2>
    	<div class="row">
		
    		<div class="col-sm-12">
	    		<?php
				if(@$_GET['3'])
				{?>
				<div class="alert alert-success" role="alert">
				  <b>Data Inserted Successfully!!!</b>
				</div>
				<?php }?>
    		
		<div class="panel-body">			
<!-- <form method="POST" id="form1" name="form1" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>"
			enctype="multipart/form-data"> -->

<form method="POST" id="form1" name="form1" action="add_bank_info.php?val=<?php echo $val; ?>" enctype="multipart/form-data">

	<div class="row">
			<!-- ist column-->
		<div class="col-sm-4"> 
			
				</div>
		<!--Second Column-->
				<div class="col-sm-4"> 
					<div class="card border border-warning">
			  <div class="card-header">
				<label>Add EMP Bank Information</label>
			  </div>
			<div class="card-body">

				<div class="form-group"><label for="bank_name">Bank Name:</label>
					<select class="form-control" id="bank_name" name="bank_name" required>
					<option value="" disabled selected>--Select--</option>					
					<option value="Janata Bank Ltd.">Janata Bank Ltd.</option>
					<option value="Sonali Bank Ltd.">Sonali Bank Ltd.</option>
					<option value="AB Bank Ltd.">AB Bank Ltd.</option>
					<option value="Uttara Bank Ltd.">Uttara Bank Ltd.</option>
					</select>
				</div>

				<div class="form-group">
				<label for="branch_name">Branch Name:</label>
				 <input class="form-control" placeholder="Enter Branch Name" type="text" name="branch_name" id="branch_name" value="" required>
				</div>

				<div class="form-group">
				<label for="branch_add">Branch Address:</label>
				 <input class="form-control" placeholder="Enter Branch Address" type="text" name="branch_add" id="branch_add" value="" required>
				</div>

				<div class="form-group">
				<label for="acc_name">Account Name:</label>
				 <input class="form-control" placeholder="Enter Account Name" type="text" name="acc_name" id="acc_name" value="" required>
				</div>

				<div class="form-group">
				<label for="acc_no">Account No.:</label> <sup  class="text-danger" style="font-size:medium">(*)</sup>
				 <input class="form-control" placeholder="Enter Account No." type="text" name="acc_no" id="acc_no" value="" required>
				 <!-- <input class="form-control" placeholder="Enter Account No." type="text" pattern="^\d{4}-\d{1}$" name="acc_no" id="acc_no" value=""> -->
				</div>

				<div class="form-group">
				<label for="swift_code">Swift Code:</label>
				 <input class="form-control" placeholder="Enter Swift Code" type="text" name="swift_code" id="swift_code" value="">
				</div>

				<div class="form-group">
				<label for="routing_no">Rounting No.:</label>
				 <input class="form-control" placeholder="Enter Rounting No" type="text" name="routing_no" id="routing_no" value="">
				</div>							
	
				</div>
				</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-4 text-center"> 
				<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary" tabindex="14">
				<span class="glyphicon glyphicon-floppy-disk"></span> Save</button> 

				<?php

                  if($val==1){
                    ?>
                    <a class="btn btn-primary " href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
                    <?php
                  }
                  else{
                    ?>
                    <a class="btn btn-primary " href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>Previous page </a>

                    <?php
                  }

                  ?>			

				</div>
				<div class="row"> 
				
				<div class="col-sm-2">  </div>
				<div class="col-sm-6">  </div>
				<div class="col-sm-4"></div>
				
				</div>
				
				</fieldset>
				
			</form>
			<div id="err"></div>
				<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		</div>			
    		   
    		</div>
    	</div>
    </div>

<?php include('../includes/footer.php');?>