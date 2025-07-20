<!-- view_bank_info.php -->
<?php
// Start the session
session_start();
?>
<?php include('../includes/header.php');?>
 <div class="container text-center p-3 my-3 border shadow">

 	<h2 class="page-header text-success m-1 text-center badge bg-dark bg-outline-info text-wrap" style="width: 70rem; font-size: 30px;">View Emp Bank Information of <b class="text-white">[<?php echo "Employee ID: ".$_SESSION['emp_id'];?>]</b>
    
    </h2>
    	<!-- <h2 class="page-header text-muted">View Award Information of<?php echo " Employee ID ".$_SESSION['emp_id']; ?></h2> -->
		<h4><?php //echo $_SESSION['emp_id']; ?></h4>
    	<div class="row">
		
    		<div class="col-sm-12 ">
			<?php
			if(@$_GET['submitted'])
			{?>
			<div class="alert alert-success" role="alert">
			  <b>Data Update Successfully!!!</b>
			</div>
			<?php }?>

<?php
include('../db/db.php');
 
 if(isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * from emp_bank_info where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 }
 $result = mysqli_query($conn,$sql);
 if (mysqli_num_rows($result) > 0) {
	echo "<table class='table table-bordered table-striped text-center'>";
	    echo  '<thead class="thead-dark text-center">';
	      echo  '<tr>';
	      echo  '<th class="text-center">Bank Name</th>';
		  echo  '<th class="text-center">Branch Name</th>';
	      echo  '<th class="text-center">Branch Address</th>';
	      echo  '<th class="text-center">Account Name</th>';
		  echo  '<th class="text-center">Account No.</th>';
	      echo  '<th class="text-center">Swift Code</th>';
	      echo  '<th class="text-center">Routing No.</th>';
	      echo  '<th class="text-center">Created At</th>';
	      echo  '<th class="text-center">Updated </th>';
		  echo  '</tr>';
	    echo  '</thead>';
 
 while($row = mysqli_fetch_array($result)) {
	
	 $bank_name=$row['bank_name'];
	 $branch_name=$row['branch_name'];
	 $branch_add=$row['branch_add'];
	 $acc_name=$row['acc_name'];
	 $acc_no=$row['acc_no'];
	 $swift_code=$row['swift_code'];
	 $routing_no=$row['routing_no'];
	 $created_at=$row['created_at'];
	 $updated=$row['updated'];
	 
 echo  '<tbody>';
      echo  '<tr>';
	  	echo  '<td>'.  $bank_name.'</td>';
        echo  '<td>'.  $branch_name.'</td>';
        echo  '<td>'.  $branch_add.'</td>';
        echo  '<td>'.  $acc_name.'</td>';
        echo  '<td>'.  $acc_no.'</td>';
        echo  '<td>'.  $swift_code.'</td>';
        echo  '<td>'.  $routing_no.'</td>';
        echo  '<td>'.  $created_at.'</td>';
        echo  '<td>'.  $updated.'</td>';
        
      echo  '</tr>';
	      
	}
 }
 else {
				// echo "<p class=' btn btn-danger btn-xs text-left'> Record Not Found!!!</p>";
 	echo "<p class=' btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
			}
  echo  '</tbody>';
  echo  '</table>';

 
 mysqli_close($conn);
 
?>



    <h4><a class="btn btn-success" href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous Page </a></h4>
</div>
</div>
<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4></div>
<div class="col-sm-4 "></div>

</div>
</div>
<?php include('../includes/footer.php');?>
