<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include('../includes/header.php');
include('../db/db.php');
 
if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 $user_id=$_SESSION['id'];
	 $sql="SELECT * from emp_bank_info where emp_id='$emp_id'";
	 
	// $sql="SELECT * FROM users where id='$edit_id'";
 }
$result=mysqli_query($conn,$sql);
//if(!$result){
    //die("ERROR".mysqli_error($conn));
 //while($row=mysql_fetch_object($result)){
if (mysqli_num_rows($result) > 0) {
	 while($row=mysqli_fetch_array($result)){	 
	 $bank_name=$row['bank_name'];
	 $branch_name=$row['branch_name'];
	 $branch_add=$row['branch_add'];
	 $acc_name=$row['acc_name'];
	 $acc_no=$row['acc_no'];
	 $swift_code=$row['swift_code'];
	 $routing_no=$row['routing_no'];		 
	 // $updatde=date('d-m-Y');	 	 
?>
 <div class="container mt-2 p-1 my-1 border shadow-lg bg-white" onload='document.form1.edit_bank_info.focus()'>
    <h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Emp Bank Information</h2>
    <div class="row">
     <div class="col-sm-12">    			
		<div class="panel-body">
	<form method="POST" id="form1" name="form1" action="update_bank_info.php" enctype="multipart/form-data">
				<fieldset>
				<!--<div class="row"> 
				<div class="col-sm-4">  </div>
				<div class="col-sm-4"> </div>
				<div class="col-sm-4 mt-0 my-0 pt-0 float-end"><button type="submit" valign="right" id="submit" name="submit" class=" col-sm-4 btn btn-md btn-primary btn-block">
				<span class="glyphicon glyphicon-floppy-disk "></span> Save</button> || 
				<a class="btn btn-primary" href="view_profile_details.php"> Previous page </a></div>
				
				</div>
				 -->
	<div class="row">				
		<!-- Educational Info-->
		<div class="col-sm-4"> </div>
		<!--Second Column-->
			<div class="col-sm-4"> 
				<div class="card border border-warning">
			  <div class="card-header">
				<label>Update Emp Bank Information</label>
			  </div>
			<div class="card-body">								
				<div class="form-group"><label for="bank_name">Bank Name:</label>
					<select class="form-control" id="bank_name" name="bank_name" >
					<option value="" disabled selected>--Select--</option>						 
					<option value="Janata Bank Ltd." <?=$bank_name == 'Janata Bank Ltd.' ? ' selected="selected"' : '';?> >Janata Bank Ltd.</option>
					<option value="Sonali Bank Ltd." <?=$bank_name == 'Sonali Bank Ltd.' ? ' selected="selected"' : '';?> >Sonali Bank Ltd.</option>
					<option value="AB Bank Ltd." <?=$bank_name == 'AB Bank Ltd.' ? ' selected="selected"' : '';?> >AB Bank Ltd.</option>				
					</select>
				</div>

				<div class="form-group">
				<label for="branch_name">Branch Name:</label>
				 <input class="form-control" placeholder="Enter Branch Name" type="text" name="branch_name" id="branch_name" value="<?=$row['branch_name'];?>">
				</div>

				<div class="form-group">
				<label for="branch_add">Branch Address:</label>
				 <input class="form-control" placeholder="Enter Branch Address" type="text" name="branch_add" id="branch_add" value="<?=$row['branch_add'];?>">
				</div>

				<div class="form-group">
				<label for="acc_name">Account Name:</label>
				 <input class="form-control" placeholder="Enter Account Name" type="text" name="acc_name" id="acc_name" value="<?=$row['acc_name'];?>">
				</div>

				<div class="form-group">
				<label for="acc_no">Account No.:</label>
				 <input class="form-control" placeholder="Enter Account No." type="text" name="acc_no" id="acc_no" value="<?=$row['acc_no'];?>">
				</div>

				<div class="form-group">
				<label for="swift_code">Swift Code:</label>
				 <input class="form-control" placeholder="Enter Swift Code" type="text" name="swift_code" id="swift_code" value="<?=$row['swift_code'];?>">
				</div>

				<div class="form-group">
				<label for="routing_no">Rounting No.:</label>
				 <input class="form-control" placeholder="Enter Rounting No" type="text" name="routing_no" id="routing_no" value="<?=$row['routing_no'];?>">
				</div>
				
						</div>
					</div>
				</div>
				<!--3rd Column-->
				<div class="col-sm-4"> 	</div>
			</div>
				<div class="row">				
				<div class="col-sm-4">  </div>
				<div class="col-sm-4"><br><center>
					<button type="submit" id="submit" name="submit" class="btn btn-md btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>  
					<a href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a>
				</center>

				</div>
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

 <?php
	}
}
else {
 	?> 	
<div class="container mt-2 p-1 my-1 border shadow-lg bg-muted rounded" onload='document.form1.edu_info.focus()'>
    <h2 class="page-header text-center text-info mt-2 p-1 my-1 text-uppercase">Update Emp Bank information</h2>
   <div class="row">		
    <div class="col-sm-12">
    	<div class='col-sm-4'></div>
			<div class='col-sm-4'><h5 class='btn btn-danger btn-block text-left'> Record Not Found!!!</h4>
				<h5><center>
			<a class="btn btn-primary" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION["emp_id"] ?>"> Previous page </a>
			</center>				
				</h5>
				</div>
				<div class='col-sm-4'></div>
				</div>	
    		</div>	
    	</div> 
	 <?php
	}
	?>
<?php include('../includes/footer.php');?>