<?php
include('database.php');
include('function.php');
// include('includes/header.php');

session_start();
$_SESSION['emp_id']=$_GET["emp_id"];
//$emp_id=$_SESSION['emp_id'];


// if(!$_SESSION['emp_id']){

// header("location: index.php");
// }
//include('db/db.php');

//echo $_POST['emp_id'];
//echo $_SESSION['emp_id'];
?>
<?php 
//echo @$_GET['emp_id'];
//echo @$_POST['emp_id'];
?> 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BCIC PMIS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
  integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 

<link rel="icon" type="image/gif/png" href="image/bcic_logo.png">

  </head>
  <body class="p-3 pt-1 m-0 border-0 bd-example">


<div class="container-fluid p-2 my-1 border border-primary shadow-lg  bg-white rounded">
      <h4 class="page-header text-center text-muted text-uppercase "> Officer's BIO-DATA </h4><hr class="bg-warning" style="color:#1b4fa8;">
    <div class="row my-1"> 
      <div class="col-sm-5 "><!--1st column-->
        <span class="text-success text-center float-left"><?php echo "Employee ID is: <b> ".$_SESSION['emp_id']. '</b> '; ?>
        <?php echo "Seniority No. <b> ".$_GET['emp_id']. '</b>   Date:<b> '.date("d-m-Y").'</b>'; ?></span>
      </div> 
	<div class="col-sm-2 ">
		<?php 
      	include('db/db.php');
      	$emp_id=$_SESSION['emp_id'];
				 
		$sql="SELECT * from basic_info where emp_id='$emp_id'";
				
		$result = mysqli_query($conn,$sql);
		while($row = mysqli_fetch_array($result)) {

		print "<center>&nbsp;<input class='rounded-circle shadow-lg border border-danger' type=\"image\" name=\"imageField\" 
 width=\"130\" height=\"130\" src=\"".'./images/'.$row['image']."\">&nbsp; </center>";
		}
      ?>

	</div>
      
      <div class="col-sm-5 "><!--2nd column-->
      
        <span class="float-end input-group d-flex justify-content-end"><a class="btn btn-warning " href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>  View Profile </a>

        <a href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"  class="btn btn-success"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous </a>
		 <!--<a type="submit" name="create_pdf" target="_blank" href="create_pdf_bio_data_by_emp_id.php" class="btn btn-info"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </a>-->
      
	<form method="POST" action="create_pdf_bio_data_by_emp_id.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" target="_blank">  
     <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
	<!-- <button type="submit" name="create_pdf" class="btn btn-danger"><i class="fa fa-print"></i> Print </button> -->
	 <a href="print_view_bio_data_at_a_glance_pdf.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"  class="btn btn-warning" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Print </a>
     
  </form>
 <a href="logout.php" class="btn btn-danger"> <i class="fa fa-sign-out" aria-hidden="true"></i> Logout </a>

        </span>
      </div>

  </div>

<hr style="color:#4e0ba1;">
    <!-- Example Code -->
    
    <div class="container-fluid mt-2">
      <div class="accordion-item ">
        <h4 class="accordion-header">
          
            General/ Personal Information
          
        </h4>
        <div  class="" >
          <div class="accordion-body">
		  <?php
			include('db/db.php');

			 
			 if(isset($_SESSION['emp_id'])){
				 
				 $emp_id=$_SESSION['emp_id'];
				 //echo $emp_id;
				 //$sql="SELECT * FROM users where emp_id='$emp_id'";
				 //SELECT * from users u INNER JOIN job_desc j ON u.emp_id=j.emp_id where u.emp_id='5620-1';
				$sql="SELECT * from basic_info u,  job_desc j where u.emp_id=j.emp_id AND u.emp_id='$emp_id'";
				
			//$sql="SELECT * from users u INNER JOIN job_desc j ON u.emp_id=j.emp_id where u.emp_id='$emp_id'";
			
			//or die(mysqli_error());
			 }
			 $result = mysqli_query($conn,$sql);

			if ($result->num_rows > 0) {
			 
			 while($row = mysqli_fetch_array($result)) {
			 	$_SESSION['seniority_no']=$row['seniority_no'];
			$name=$row['name'];
			 $fathersName=$row['fathersName'];
			 $mothersName=$row['mothersName'];
			$home_dist=$row['home_dist'];
			 $dob=$row['dob'];
			 $doj=$row['doj'];
			 
			 $gender=$row['gender'];
			 $blood_group=$row['blood_group'];
			 $religious=$row['religious'];
			 $nid=$row['nid'];
			 $mobile_no=$row['mobile_no'];
			 
			 $home_dist=$row['home_dist'];
			 $email=$row['email'];
			 $quota=$row['quota'];
			 $maritial_status=$row['maritial_status'];
			 $spouse_name=$row['spouse_name'];
			 $spouse_occupation=$row['spouse_occupation'];
			 
			 $present_add=$row['present_add'];
			 $permanent_add=$row['permanent_add'];
			 
			 echo "<table class='table table-bordered table-striped text-center'>
			 <thead class='table-success'>
			 <tr>
			 
			 <th>Name</th>
			 <th>Fathers Name</th>
			  <th >Mothers Name</th>
			 <th>Date of Birth</th>
			 <th>Date of Joining</th>
			 <th> Gender </th>
			 <th> Blood Group </th>
			  <th>Religious</th>
			 <th> National ID </th>
			 <th> Mobile Number </th>
			 </tr>
			  </thead>";
			  echo "<tbody>";
			  echo "<tr>";
			 
			 echo "<td>" .  $name. "</td>";
			 echo "<td>" .  $fathersName. "</td>";
			 echo "<td>" .  $mothersName. "</td>";
			 echo "<td>" . $dob. "</td>";
			 echo "<td>" . $doj. "</td>";
			 echo "<td>" .  $gender. "</td>";
			 echo "<td>" .  $blood_group. "</td>";
			 echo "<td>" . $religious. "</td>";
			 echo "<td>" .  $nid. "</td>";
			 echo "<td>" .  $mobile_no. "</td>";
			  
			 echo "</tr>";
			 
			 echo "</tbody>";
			 echo "</table>";
			 
			 echo "<table class='table table-bordered table-striped text-center'>
			 <thead class='table-success'>
			 <tr>
			 <th >Home District</th>
			  <th>Email</th>
			  <th> Quotas </th>
			 <th>Maritial Status</th>
			 <th> Spaous Name </th>
			 <th> Spaous Occupation </th>
			  <th>Present Address</th>
			 <th> Permanent Address </th>
			 
			 </tr>
			  </thead>";
			  echo "<tbody>";
			  echo "<tr>";
			  echo "<td>" .  $home_dist. "</td>";
			 echo "<td>" .  $email. "</td>";
			  echo "<td>" . $quota. "</td>";
			 echo "<td>" .  $maritial_status. "</td>";
			 echo "<td>" .  $spouse_name. "</td>";
			 echo "<td>" . $spouse_occupation. "</td>";
			 echo "<td>" .  $present_add. "</td>";
			 echo "<td>" .  $permanent_add. "</td>";
			  
			 echo "</tr>";
			 
			 echo "</tbody>";
			 echo "</table>";
				}
			}
			else {
			  echo "<p class='btn btn-danger btn-xs text-white'> No Record Found!!! </p>";
			}

			//$_SESSION['seniority_no']=$row['seniority_no'];
			 mysqli_close($conn);
			 
			?>
		  </div>
        </div>
      </div>
      <div class="">
        <h4 class="accordion-header">
          
            <b>Job Description</b>
          
        </h4>
        <div class="">
          <div class="accordion-body">
	 <?php
include('db/db.php');
 
 //if(isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * from job_desc where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 //}
 $result = mysqli_query($conn,$sql);
 if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_array($result)) {
	$dob=$row['dob'];
	 $doj=$row['doj'];
	 //$dolp=$row['dolp'];
	 
	 $prl=$row['prl'];
	 $place_of_posting=$row['place_of_posting'];
	 $join_designation=$row['join_designation'];
	 
	 
	 $sub_cadre=$row['sub_cadre'];
	 $appoint_type=$row['appoint_type'];
	 
	 $last_promo_date=$row['last_promo_date'];
	 $next_promo_date=$row['next_promo_date'];
	// $last_incr_date=$row['last_incr_date'];
	 $next_incr_date=$row['next_incr_date'];
	 
	 $grade=$row['grade'];
	 $basic=$row['basic'];
	 $scale=$row['scale'];	
	 $cadre=$row['cadre'];
	 // $cadre_id=$row['cadre'];
	 // $sql_cadre="select cadre from cadre where id ='$cadre_id'"; 
		 // $result1=mysqli_query($conn,$sql_cadre);
		 // while($row = mysqli_fetch_array($result1))
					// {
						// $cadre=$row['cadre'];						
					// }
	 
echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="table-success ">';
      echo  '<tr>';
        echo  '<th>Date of Birth</th>';
		echo  '<th>Date of Joining</th>';
		//echo  '<th>Date of Last Promotion</th>';
        echo  '<th>PRL Date</th>';
        echo  '<th>Place of Posting</th>';
		echo  '<th>Joining Designation</th>';
		echo  '<th>Cadre</th>';
		echo  '<th>Sub Cadre</th>';
      echo  '</tr>';
    echo  '</thead>';
 
 
 echo  '<tbody>';
      echo  '<tr>';
        echo  '<td>'.  $dob.'</td>';
        echo  '<td>'.  $doj.'</td>';
        //echo  '<td>'.  $dolp.'</td>';
		echo  '<td>'.  $prl.'</td>';
		echo  '<td>'.  $place_of_posting.'</td>';
		echo  '<td>'.  $join_designation.'</td>';
		//echo  '<td>'. '' .'</td>';
		echo  '<td>'.  $cadre.'</td>';
		echo  '<td>'.  $sub_cadre.'</td>';
      echo  '</tr>';
	  // echo  '<tr>';
        // echo  '<td>'.  $hsc_exam.'</td>';
        // echo  '<td>'.  $hsc_group_name.'</td>';
        // echo  '<td>'.  $hsc_inst_name.'</td>';
		// echo  '<td>'.  $hsc_board_or_univ.'</td>';
		// echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
		// echo  '<td>'.  $hsc_passing_year.'</td>';
      // echo  '</tr>';
	  // echo  '<tr>';
        // echo  '<td>'.  $honors_exam.'</td>';
        // echo  '<td>'.  $honors_group_name.'</td>';
        // echo  '<td>'.  $honors_inst_name.'</td>';
		// echo  '<td>'.  $honors_board_or_univ.'</td>';
		// echo  '<td>'.  $honors_div_or_cgpa.'</td>';
		// echo  '<td>'.  $honors_passing_year.'</td>';
		// echo  '<td>'.  $honors_course_duration.'</td>';
      // echo  '</tr>';
    

  echo  '</tbody>';
  echo  '</table>';
  
  echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="table-success ">';
      echo  '<tr>';
		echo  '<th>Appointment Type</th>';
		echo  '<th>Date of Last Promotion</th>';
        echo  '<th>Next Promotion Date</th>';
        echo  '<th>Increment Due Date</th>';
		echo  '<th>Grade</th>';
		echo  '<th>Basic</th>';
		echo  '<th>Scale</th>';
      echo  '</tr>';
    echo  '</thead>';
 
 
 echo  '<tbody>';
      echo  '<tr>';
        echo  '<td>'.  $appoint_type.'</td>';
		echo  '<td>'.  $last_promo_date.'</td>';
		echo  '<td>'.  $next_promo_date.'</td>';
		//echo  '<td>'.  $last_incr_date.'</td>';
		echo  '<td>'.  $next_incr_date.'</td>';
		echo  '<td>'.  $grade.'</td>';
		echo  '<td>'.  $basic.'</td>';
		echo  '<td>'.  $scale.'</td>';
      echo  '</tr>';
	  // echo  '<tr>';
        // echo  '<td>'.  $hsc_exam.'</td>';
        // echo  '<td>'.  $hsc_group_name.'</td>';
        // echo  '<td>'.  $hsc_inst_name.'</td>';
		// echo  '<td>'.  $hsc_board_or_univ.'</td>';
		// echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
		// echo  '<td>'.  $hsc_passing_year.'</td>';
      // echo  '</tr>';
	  // echo  '<tr>';
        // echo  '<td>'.  $honors_exam.'</td>';
        // echo  '<td>'.  $honors_group_name.'</td>';
        // echo  '<td>'.  $honors_inst_name.'</td>';
		// echo  '<td>'.  $honors_board_or_univ.'</td>';
		// echo  '<td>'.  $honors_div_or_cgpa.'</td>';
		// echo  '<td>'.  $honors_passing_year.'</td>';
		// echo  '<td>'.  $honors_course_duration.'</td>';
      // echo  '</tr>';
    

  echo  '</tbody>';
  echo  '</table>';
 }
 }
 else {
				echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
			}
 
 mysqli_close($conn);
 
?>

		  
		  </div>
        </div>
      </div>
      <div class="">
        <h4 class="accordion-header" >
         
            <b>Professional Bodies/ Membership Information </b>
      
        </h4>
        <div class="" >
          <div class="accordion-body">
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
	    	echo  '<thead class="table-success">';
	      	echo  '<tr>';
		  	echo  '<th>Membership No.</th>';
        	echo  '<th>Membership Type</th>';
		
			echo  '<th>Institute</th>';
        	echo  '<th>Country</th>';
        	echo  '<th>Validity</th>';
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
				echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
			}
  echo  '</tbody>';
  echo  '</table>';

 
 mysqli_close($conn);
 
?>


		  </div>
      </div>
            <div class="">
        <h4 class="accordion-header" >
          
            <b>Job History Before Joining</b>
          
        </h4>
        <div  class="" >
          <div class="accordion-body">

          	<?php
		include('db/db.php');
		 
		 if(isset($_SESSION['emp_id'])){
			 
			 $emp_id=$_SESSION['emp_id'];
			 $sql="SELECT * from service_history where emp_id='$emp_id' AND service_type='Before Joining'";
			 //$sql="SELECT * FROM users where emp_id='$emp_id'";
		 }
		 $result = mysqli_query($conn,$sql);
		 if (mysqli_num_rows($result) > 0) {
				echo "<table class='table table-bordered table-hovered table-striped text-center'>";
		    	echo  '<thead class="table-success">';
		      	echo  '<tr>';
			  	echo  '<th>Organization</th>';
		        echo  '<th>From Date</th>';
				
				echo  '<th>To Date</th>';
		        echo  '<th>Job Location</th>';
		        echo  '<th>Designation</th>';
				echo  '<th>Scale</th>';	
		      echo  '</tr>';
		    echo  '</thead>';
		 
		 while($row = mysqli_fetch_array($result)) {
			$org_name=$row['org_name'];
			 $from_date=$row['from_date'];
			 
			 $to_date=$row['to_date'];
			 
			 $location=$row['location'];
			 $designation=$row['designation'];
			 $scale=$row['scale'];
		 		echo  '<tbody>';
      			echo  '<tr>';
	 			echo  '<td>'.  $org_name.'</td>';
        		echo  '<td>'.  $from_date.'</td>';
        
		        echo  '<td>'.  $to_date.'</td>';
				echo  '<td>'.  $location.'</td>';
				echo  '<td>'.  $designation.'</td>';
				echo  '<td>'.  $scale.'</td>';
		
      			echo  '</tr>';
	      
	}
 }
 		else {
				echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
			}
			  echo  '</tbody>';
			  echo  '</table>';

 
 mysqli_close($conn);
 
?>

          </div>
      </div>
            <div class="">
        <h4 class="accordion-header" id="flush-headingFive">
          
            <b>Job History After Joining</b>
         
        </h4>
        <div class="">
          <div class="accordion-body">
          	

          	<?php
			include('db/db.php');
			 
			 if(isset($_SESSION['emp_id'])){
				 
				 $emp_id=$_SESSION['emp_id'];
				 $sql="SELECT * from service_history where emp_id='$emp_id' AND service_type='After Joining'";
				 //$sql="SELECT * FROM users where emp_id='$emp_id'";
			 }
			 $result = mysqli_query($conn,$sql);
			 if (mysqli_num_rows($result) > 0) {
			echo "<table class='table table-bordered table-hovered table-striped text-center'>";
			    echo  '<thead class="table-success">';
			      echo  '<tr>';
				  echo  '<th>Organization</th>';
			        echo  '<th>From Date</th>';
					
					echo  '<th>To Date</th>';
			        echo  '<th>Job Location</th>';
			        echo  '<th>Designation</th>';
					echo  '<th>Scale</th>';	
			      echo  '</tr>';
			    echo  '</thead>';
			 
			 while($row = mysqli_fetch_array($result)) {
				$org_name=$row['org_name'];
				 $from_date=$row['from_date'];
				 
				 $to_date=$row['to_date'];
				 
				 $location=$row['location'];
				 $designation=$row['designation'];
				 $scale=$row['scale'];
			 echo  '<tbody>';
			      echo  '<tr>';
				  echo  '<td>'.  $org_name.'</td>';
			        echo  '<td>'.  $from_date.'</td>';
			        
			        echo  '<td>'.  $to_date.'</td>';
					echo  '<td>'.  $location.'</td>';
					echo  '<td>'.  $designation.'</td>';
					echo  '<td>'.  $scale.'</td>';
					
			      echo  '</tr>';
				      
				}
			 }
			 else {
							echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
						}
			  echo  '</tbody>';
			  echo  '</table>';

			 
			 mysqli_close($conn);
 
			?>
          </div>
      </div>
        <div class="">
        <h4 class="accordion-header">
          
            <b>Educational/ Academic Qualification</b>
          
        </h4>
        <div class="">
          <div class="accordion-body">
          	<?php
          	include('db/db.php');
 
 //if(isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * from edu_info where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 //}
 $result = mysqli_query($conn,$sql);
 if (mysqli_num_rows($result) > 0) {
 
while($row = mysqli_fetch_array($result)) {

	$ssc_exam=$row['ssc_exam'];
	 $ssc_group_name=$row['ssc_group_name'];
	 $ssc_inst_name=$row['ssc_inst_name'];
	 $ssc_board_or_univ=$row['ssc_board_or_univ'];
	 $ssc_div_or_cgpa=$row['ssc_div_or_cgpa'];
	 
	 $ssc_passing_year=$row['ssc_passing_year'];

	 $hsc_exam=$row['hsc_exam'];
	 $hsc_group_name=$row['hsc_group_name'];
	 $hsc_inst_name=$row['hsc_inst_name'];
	 $hsc_board_or_univ=$row['hsc_board_or_univ'];
	 $hsc_div_or_cgpa=$row['hsc_div_or_cgpa'];
	 $hsc_passing_year=$row['hsc_passing_year'];
	 
	 $honors_exam=$row['honors_exam'];	
	 $honors_group_name=$row['honors_group_name'];
	 $honors_inst_name=$row['honors_inst_name'];
	 $honors_board_or_univ=$row['honors_board_or_univ'];
	 $honors_div_or_cgpa=$row['honors_div_or_cgpa'];	
	 $honors_passing_year=$row['honors_passing_year'];
	 $honors_course_duration=$row['honors_course_duration'];

	 $masters=$row['masters'];
	 $ms_group_name=$row['ms_group_name'];
	 $ms_inst_name=$row['ms_inst_name'];	
	 $ms_board_or_univ=$row['ms_board_or_univ'];
	 $ms_div_or_cgpa=$row['ms_div_or_cgpa'];
	 $ms_passing_year=$row['ms_passing_year'];
	 $ms_course_duration=$row['ms_course_duration'];
	 
echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="table-success ">';
      echo  '<tr>';
        echo  '<th>Examination</th>';
		echo  '<th>Subject/ Group</th>';
		echo  '<th>Institute Name</th>';
        echo  '<th>Board</th>';
        echo  '<th>CGPA/Division/Class</th>';
		echo  '<th>Passing Year</th>';
		echo  '<th>Course Duration</th>';

      echo  '</tr>';
    echo  '</thead>';
  
 
 echo  '<tbody>';
      echo  '<tr>';
        echo  '<td>'.  $ssc_exam.'</td>';
        echo  '<td>'.  $ssc_group_name.'</td>';
        //echo  '<td>'.  $dolp.'</td>';
		echo  '<td>'.  $ssc_inst_name.'</td>';
		echo  '<td>'.  $ssc_board_or_univ.'</td>';
		echo  '<td>'.  $ssc_div_or_cgpa.'</td>';
		
		echo  '<td>'.  $ssc_passing_year.'</td>';
		echo  '<td>'. '' .'</td>';
		echo  '</tr>';

	   echo  '<tr>';
        echo  '<td>'.  $hsc_exam.'</td>';
        echo  '<td>'.  $hsc_group_name.'</td>';
        //echo  '<td>'.  $dolp.'</td>';
		echo  '<td>'.  $hsc_inst_name.'</td>';
		echo  '<td>'.  $hsc_board_or_univ.'</td>';
		echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
		
		echo  '<td>'.  $hsc_passing_year.'</td>';
		echo  '<td>'. '' .'</td>';
		echo  '</tr>';

      	echo  '<tr>';
        echo  '<td>'.  $honors_exam.'</td>';
        echo  '<td>'.  $honors_group_name.'</td>';
        //echo  '<td>'.  $dolp.'</td>';
		echo  '<td>'.  $honors_inst_name.'</td>';
		echo  '<td>'.  $honors_board_or_univ.'</td>';
		echo  '<td>'.  $honors_div_or_cgpa.'</td>';
		
		echo  '<td>'.  $honors_passing_year.'</td>';
		echo  '<td>'.  $honors_course_duration.'</td>';
      echo  '</tr>';

      echo  '<tr>';
        echo  '<td>'.  $masters.'</td>';
        echo  '<td>'.  $ms_group_name.'</td>';
        //echo  '<td>'.  $dolp.'</td>';
		echo  '<td>'.  $ms_inst_name.'</td>';
		echo  '<td>'.  $ms_board_or_univ.'</td>';
		echo  '<td>'.  $ms_div_or_cgpa.'</td>';
		
		echo  '<td>'.  $ms_passing_year.'</td>';
		echo  '<td>'.  $ms_course_duration.'</td>';
      echo  '</tr>';

  echo  '</tbody>';
  echo  '</table>';

 	}
 }
 else {
				echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
			}
 
 mysqli_close($conn);
 
?>

          </div>
        </div>
      </div>
        <div class="">
        <h4 class="accordion-header" >
         
           <b> Training Information</b>
          
        </h4>
        <div class="" >
          <div class="accordion-body">
          	 <?php
			include('db/db.php');
			 
			 if(isset($_SESSION['emp_id'])){
				 
				 $emp_id=$_SESSION['emp_id'];
				 $sql="SELECT * from training_info where emp_id='$emp_id'";
				 //$sql="SELECT * FROM users where emp_id='$emp_id'";
			 }
			 $result = mysqli_query($conn,$sql);
			 if (mysqli_num_rows($result) > 0) {
			echo "<table class='table table-bordered table-striped text-center'>";
			    echo  '<thead class="table-success">';
			      echo  '<tr>';
				  echo  '<th>Training Type</th>';
			        echo  '<th>Title</th>';
					
					echo  '<th>Institute</th>';
			        echo  '<th>Country</th>';
			        echo  '<th>Period</th>';
					echo  '<th>Month/ Year</th>';	
			      echo  '</tr>';
			    echo  '</thead>';
			 
			 while($row = mysqli_fetch_array($result)) {
				
				 $type=$row['type'];
				 $title=$row['title'];
				 $institute=$row['institute'];
				 
				 $country=$row['country'];
				 	
				 $period=$row['period'];
				 $month_year=$row['month_year'];

			 echo  '<tbody>';
			      echo  '<tr>';
				  
			        echo  '<td>'.  $type.'</td>';
			        echo  '<td>'.  $title.'</td>';
			        echo  '<td>'.  $institute.'</td>';
					echo  '<td>'.  $country.'</td>';
					echo  '<td>'.  $period.'</td>';
					echo  '<td>'.  $month_year.'</td>';
					
			      echo  '</tr>';
				      
				}
			 }
			 else {
							echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
						}
			  echo  '</tbody>';
			  echo  '</table>';

			 
			 mysqli_close($conn);
 
?>


          </div>
        </div>
      </div>
              <div class="accordion-item">
        <h4 class="accordion-header" >
          
            Training Info. (Foreign)
          
        </h4>
        <div class="accordion-collapse collapse" >
          <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
        </div>
      </div>
    </div>
    </div>
    <!-- End Example Code -->
  </body>
</html>