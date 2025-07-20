<?php
// Start the session
session_start();
?>
<?php include('../includes/header.php');?>
 <div class="container text-center p-3 my-3 border shadow">
 	<h2 class="page-header text-success m-1 text-center badge bg-dark bg-outline-info text-wrap" style="width: 70rem; font-size: 30px;">View Transfer Info. of <b class="text-white">[<?php echo "Employee ID: ".$_SESSION['emp_id'];?>]</b>
    
    </h2>
    	<!-- <h2 class="page-header text-muted">View Service History of<?php echo " Employee ID ".$_SESSION['emp_id']; ?></h2> -->
		<h4><?php //echo $_SESSION['emp_id']; ?></h4>
    	<div class="row">
		
    		<div class="col-sm-12 ">
			<?php
			if(@$_GET['submitted'])
			{?>
			<div class="alert alert-success" role="alert">
			  Data Update Successfully!!!
			</div>
			<?php }?>

<?php
include('../db/db.php');
 
 if(isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * from service_history where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 }
 $result = mysqli_query($conn,$sql);
 if (mysqli_num_rows($result) > 0) {
echo "<table class='table table-bordered table-hover table-striped '>";
     echo  '<thead class="table-dark">';
      echo  '<tr>';
      
	  echo  '<th class="text-center">From Date</th>';
        echo  '<th class="text-center">To Date</th>';
		echo  '<th class="text-center">Till Now</th>';
		echo  '<th class="text-center">Place of Posting/ Office</th>';
                
        echo  '<th class="text-center">Designation</th>';
        
		echo  '<th class="text-center">Scale</th>';	

      echo  '</tr>';
     echo  '</thead>';
 
 while($row = mysqli_fetch_array($result)) {
	
	 $service_type=$row['service_type'];
	$org_name=$row['org_name'];
	 $from_date=$row['from_date'];
	 
	 $to_date=$row['to_date'];
	 $till_now=$row['till_now'];
	 $place_of_posting=$row['place_of_posting'];
	 	 
	 $designation=$row['designation'];
	 $scale=$row['scale'];

 echo  '<tbody>';
      echo  '<tr>';
	  	
        echo  '<td>'.  $from_date.'</td>';
        echo  '<td>'.  $to_date.'</td>';
        echo  '<td>'.  $till_now.'</td>';
        
		echo  '<td>'.  $place_of_posting.'</td>';
		
		echo  '<td>'.  $designation.'</td>';
		echo  '<td>'.  $scale.'</td>';
      echo  '</tr>';
	      
	}
	  echo  '</tbody>';
  echo  '</table>';
 }
 else {
			
		echo "<p class=' btn btn-danger btn-xs text-left'> Record Not Found!!!</p>";
	}


 
 mysqli_close($conn);
 
?>



    <h4><a class="btn btn-success" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous Page </a></h4>
</div>
</div>
<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4></div>
<div class="col-sm-4 "></div>

</div>
</div>
<?php include('../includes/footer.php');?>
