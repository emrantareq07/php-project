<?php
// Start the session
session_start();
?>
<?php include('includes/header.php');?>
 <div class="container text-center p-3 my-3 border shadow">

 	<h2 class="page-header text-success m-1 text-center badge bg-dark bg-outline-info text-wrap" style="width: 70rem; font-size: 30px;">View Prof./ Membership Information of <b class="text-white">[<?php echo "Employee ID: ".$_SESSION['emp_id'];?>]</b>
    
    </h2>
    	<!-- <h2 class="page-header text-muted">View Professional/ Membership information of<?php echo " Employee ID ".$_SESSION['emp_id']; ?></h2> -->
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
include('db/db.php');
 
 if(isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * from prof_membership where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 }
 $result = mysqli_query($conn,$sql);
 if (mysqli_num_rows($result) > 0) {
echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="thead-dark text-center">';
      echo  '<tr>';
	  echo  '<th class="text-center">Membership No.</th>';
        echo  '<th class="text-center">Membership Type</th>';
		
		echo  '<th class="text-center">Institute</th>';
        echo  '<th class="text-center">Country</th>';
        echo  '<th class="text-center">Validity</th>';
		//echo  '<th>Month/ Year</th>';	
      echo  '</tr>';
    echo  '</thead>';
 
 while($row = mysqli_fetch_array($result)) {
	$mem_no=$row['mem_no'];
	 $type=$row['type'];
	 
	 $institute=$row['institute'];
	 
	 $country=$row['country'];
	 	
	 $validity=$row['validity'];

 echo  '<tbody>';
      echo  '<tr>';
	  echo  '<td>'.  $mem_no.'</td>';
        echo  '<td>'.  $type.'</td>';
        
        echo  '<td>'.  $institute.'</td>';
		echo  '<td>'.  $country.'</td>';
		echo  '<td>'.  $validity.'</td>';
		//echo  '<td>'.  $month_year.'</td>';
		
      echo  '</tr>';
	      
	}
 }
 else {
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
<?php include('includes/footer.php');?>
