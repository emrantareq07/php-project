<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
?>
<?php include('../includes/header.php');?>
 <div class="container text-center p-3 my-3 border shadow">
   <h2 class="page-header text-success m-1 text-center badge bg-dark bg-outline-info text-wrap" style="width: 70rem; font-size: 30px;">View Service Information of <b class="text-white">[<?php echo "Employee ID: ".$_SESSION['emp_id'];?>]</b>
    
    </h2>
    	<!-- <h2 class="page-header text-muted">View Job Description<?php echo " Employee ID ".$_SESSION['emp_id']; ?></h2> -->
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
	 $sql="SELECT * from job_desc where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 }
 $result = mysqli_query($conn,$sql);
 if (mysqli_num_rows($result) > 0) {

 
 while($row = mysqli_fetch_array($result)) {
	$dob=$row['dob'];
	 $doj=$row['doj'];
	 //$dolp=$row['dolp'];
	 //$now_date = date('Y-m-d');
	 
	$doj1 = new DateTime($row['doj']); // Convert the string to DateTime object
	$now_date = new DateTime(); // Current date and time

	$interval = $now_date->diff($doj1);
		 
	 $prl=$row['prl'];
	 $place_of_posting=$row['place_of_posting'];
	 $join_designation=$row['join_designation'];
	 $cadre=$row['cadre'];
	 
	 $sub_cadre=$row['sub_cadre'];
	 $appoint_type=$row['appoint_type'];
	 
	 $last_promo_date=$row['last_promo_date'];

	 // if($row['last_promo_date']!=''){

	 $last_promo_date1 = new DateTime($row['last_promo_date']); // Convert the string to DateTime object
	 $now_date = new DateTime(); // Current date and time
	 $interval2 = $now_date->diff($last_promo_date1);

	 // }else{
	 	
	 // }
	 

	 $next_promo_date=$row['next_promo_date'];
	 $last_incr_date=$row['last_incr_date'];
	 $next_incr_date=$row['next_incr_date'];
	 
	 $grade=$row['grade'];
	 $basic=$row['basic'];
	 $scale=$row['scale'];	
	 $job_status=$row['job_status'];
	 $incr_granted=$row['incr_granted'];	
	 $basic_after_incr=$row['basic_after_incr'];
	 $granted_promo_date=$row['granted_promo_date'];	
	 $nature_of_promo=$row['nature_of_promo'];
	 
	$police_verification=$row['police_verification'];
	$pension_start_date=$row['pension_start_date'];
	$d_nothi_id=$row['d_nothi_id'];
	$tin_no=$row['tin_no'];
	 // $cadre_id=$row['cadre'];
	 // $sql_cadre="select cadre from cadre where id ='$cadre_id'"; 
		 // $result1=mysqli_query($conn,$sql_cadre);
		 // while($row = mysqli_fetch_array($result1))
					// {
						// $cadre=$row['cadre'];						
					// }

	 echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="thead-dark text-center">';
      echo  '<tr>';
        echo  '<th>DOB</th>';
		echo  '<th>Date of Joining</th>';
		echo  '<th>Police Verification</th>';
        echo  '<th>PRL Date</th>';
        echo  '<th>Pension Start Date</th>';
        echo  '<th>Place of Posting</th>';
        echo  '<th>D-nothi ID</th>';
        echo  '<th>TIN No.</th>';
		echo  '<th>Joining Designation</th>';
		echo  '<th>Cadre</th>';
		echo  '<th>Sub Cadre</th>';
		echo  '<th>Appointment Type</th>';

      echo  '</tr>';
    echo  '</thead>';
echo  '<tbody>';
      echo  '<tr >';
        echo  '<td>'.  $dob.'</td>';
        echo  '<td>'.  $doj.'</td>';
        echo  '<td>'.  $police_verification.'</td>';
		echo  '<td>'.  $prl.'</td>';
		echo  '<td>'.  $pension_start_date.'</td>';
		echo  '<td>'.  $place_of_posting.'</td>';
		echo  '<td>'.  $d_nothi_id.'</td>';
		echo  '<td>'.  $tin_no.'</td>';
		echo  '<td>'.  $join_designation.'</td>';
		echo  '<td>'.  $cadre.'</td>';
        echo  '<td>'.  $sub_cadre.'</td>';
        echo  '<td>'.  $appoint_type.'</td>';

        echo  '</tr>';
        echo  '</tbody>';
        echo  '</table>';

echo "<table class='table table-bordered table-striped text-center'>";
echo '<center>';      
echo  '<thead class="thead-dark ">';
      echo  '<tr class="text-center">';
        echo  '<th>Date of Last Promotion</th>';
		echo  '<th class="text-center">Next Promotion Date</th>';
        echo  '<th class="text-center">Granted Promotion Date</th>';
        echo  '<th class="text-center">Nature of Promotion</th>';
        echo  '<th class="text-center">Last Increment Date</th>';
        echo  '<th class="text-center">Next Increment Date</th>';
		echo  '<th class="text-center">Grade</th>';
		echo  '<th class="text-center">Basic</th>';
		echo  '<th class="text-center">Scale</th>';
		echo  '<th class="text-center">Job Status</th>';
		echo  '<th class="text-center">Increment Granted (Tk.)</th>';
		echo  '<th class="text-center">Basic After Increment (Tk.)</th>';
	
      echo  '</tr>';
 echo  '</thead>';
 echo '</center>';
echo  '<tbody>';

    echo  '<tr>';
		echo  '<td>'.  $last_promo_date.'</td>';
    	echo  '<td>'.  $next_promo_date.'</td>';		
		echo  '<td>'.  $granted_promo_date.'</td>';
		echo  '<td>'.  $nature_of_promo.'</td>';
		echo  '<td>'.  $last_incr_date.'</td>';
		echo  '<td>'.  $next_incr_date.'</td>';
		echo  '<td>'.  $grade.'</td>';
		echo  '<td>'.  $basic.'</td>';
		echo  '<td>'.  $scale.'</td>';
		echo  '<td>'.  $job_status.'</td>';
		echo  '<td>'.  $incr_granted.'</td>';
		echo  '<td>'.  $basic_after_incr.'</td>';
		
    echo  '</tr>';

      echo  '</tbody>';
      echo  '</table>';

      echo "<table class='table table-bordered table-striped text-center'>";
		echo '<center>';      
		echo  '<thead class="thead-dark ">';
      	echo  '<tr class="text-center">';
        
		echo  '<th class="text-center">Total Service Period</th>';
		echo  '<th class="text-center">Service Period Current Designation</th>';
      	echo  '</tr>';
		 echo  '</thead>';
		 echo '</center>';
		echo  '<tbody>';

    	echo  '<tr>';
		
		echo '<td>' . $interval->y . ' years, ' . $interval->m . ' months, ' . $interval->d . ' days </td>';
		echo '<td>' . $interval2->y . ' years, ' . $interval2->m . ' months, ' . $interval2->d . ' days </td>';

    	echo  '</tr>';

      echo  '</tbody>';
      echo  '</table>';

 }
 }
 else {
				echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
			}
  echo  '</tbody>';
  echo  '</table>';

 
 mysqli_close($conn);
 
?>



  <h4><a class="btn btn-success" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back Previous Page </a></h4>
</div>
</div>
<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4></div>
<div class="col-sm-4 "></div>

</div>
</div>
<?php include('../includes/footer.php');?>
