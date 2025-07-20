<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
?>
<?php include('../includes/header.php');?>
 <div class="container text-center p-3 my-3 border rounded shadow-lg">
    <h2 class="page-header text-success m-1 text-center badge bg-dark text-wrap" style="width: 80rem; font-size: 30px;">View Educational Information of <b class="text-white">[<?php echo "Employee ID: ".$_SESSION['emp_id'];?>]</b>
    
    </h2>
		<h4><?php //echo $_SESSION['emp_id']; ?></h4>
    	<div class="row">
		
    		<div class="col-sm-12 ">

<?php
include('../db/db.php');
 
 if(isset($_SESSION['emp_id'])){
	 
	 $emp_id=$_SESSION['emp_id'];
	 $sql="SELECT * from edu_info where emp_id='$emp_id'";
	 //$sql="SELECT * FROM users where emp_id='$emp_id'";
 }
 $result = mysqli_query($conn,$sql);
  if (mysqli_num_rows($result) > 0) {
  echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="thead-dark ">';
      echo  '<tr>';
      echo  '<th>Examination/Degree</th>';
		  echo  '<th>Group/Subject</th>';
		  echo  '<th class="text-center">Institute</th>';
      echo  '<th>Board/Institute</th>';
      echo  '<th>Division/CGPA</th>';
		  echo  '<th>Passing Year</th>';
		  echo  '<th>Duration</th>';
      echo  '</tr>';
    echo  '</thead>';
 
 while($row = mysqli_fetch_array($result)) {
$ssc_exam=$row['ssc_exam']; 
$ssc_group_name=$row['ssc_group_name'];
$ssc_inst_name=$row['ssc_inst_name'];
$ssc_board_or_univ=$row['ssc_board_or_univ'];

$ssc_div_or_cgpa=$row['ssc_div_or_cgpa'];
$ssc_cgpa_5=$row['ssc_cgpa_5'];

$ssc_passing_year=$row['ssc_passing_year'];

$hsc_exam=$row['hsc_exam']; 
$hsc_group_name=$row['hsc_group_name'];
$hsc_inst_name=$row['hsc_inst_name'];
$hsc_board_or_univ=$row['hsc_board_or_univ'];
$hsc_div_or_cgpa=$row['hsc_div_or_cgpa'];
$hsc_cgpa_5=$row['hsc_cgpa_5'];
$hsc_passing_year=$row['hsc_passing_year'];

$honors_exam=$row['honors_exam']; 
$honors_group_name=$row['honors_group_name'];
$honors_inst_name=$row['honors_inst_name'];
$honors_board_or_univ=$row['honors_board_or_univ'];
$honors_div_or_cgpa=$row['honors_div_or_cgpa'];
$honors_cgpa_4=$row['honors_cgpa_4'];
$honors_passing_year=$row['honors_passing_year'];	
$honors_course_duration=$row['honors_course_duration'];	

$masters=$row['masters']; 
  $ms_group_name=$row['ms_group_name'];
  $ms_inst_name=$row['ms_inst_name'];
  $ms_board_or_univ=$row['ms_board_or_univ'];
  $ms_div_or_cgpa=$row['ms_div_or_cgpa'];
  $ms_cgpa_4=$row['ms_cgpa_4'];
  //$ms_cgpa_5=$row['ms_cgpa_5'];
  $ms_passing_year=$row['ms_passing_year']; 
  $ms_course_duration=$row['ms_course_duration'];

 echo  '<tbody>';
      echo  '<tr>';
        echo  '<td>'.  $ssc_exam.'</td>';
        echo  '<td>'.  $ssc_group_name.'</td>';
        echo  '<td>'.  $ssc_inst_name.'</td>';
    		echo  '<td>'.  $ssc_board_or_univ.'</td>';
        if($ssc_cgpa_5!=''){
          echo  '<td>'.  $ssc_cgpa_5.'</td>';
        }
        else{
          echo  '<td>'.  $ssc_div_or_cgpa.'</td>';
        }
    		
    		echo  '<td>'.  $ssc_passing_year.'</td>';
    		echo  '<td>'. '' .'</td>';
      echo  '</tr>';
	  echo  '<tr>';
        echo  '<td>'.  $hsc_exam.'</td>';
        echo  '<td>'.  $hsc_group_name.'</td>';
        echo  '<td>'.  $hsc_inst_name.'</td>';
    		echo  '<td>'.  $hsc_board_or_univ.'</td>';
    		// echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
         if($hsc_cgpa_5!=''){
          echo  '<td>'.  $hsc_cgpa_5.'</td>';
        }
        else{
          echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
        }
    		echo  '<td>'.  $hsc_passing_year.'</td>';
      echo  '</tr>';
	  echo  '<tr>';
        echo  '<td>'.  $honors_exam.'</td>';
        echo  '<td>'.  $honors_group_name.'</td>';
        echo  '<td>'.  $honors_inst_name.'</td>';
    		echo  '<td>'.  $honors_board_or_univ.'</td>';
    		// echo  '<td>'.  $honors_div_or_cgpa.'</td>';
         if($honors_cgpa_4!=''){
          echo  '<td>'.  $honors_cgpa_4.'</td>';
        }
        else{
          echo  '<td>'.  $honors_div_or_cgpa.'</td>';
        }
    		echo  '<td>'.  $honors_passing_year.'</td>';
    		echo  '<td>'.  $honors_course_duration.'</td>';
      echo  '</tr>';
       echo  '<tr>';
        echo  '<td>'.  $masters.'</td>';
        echo  '<td>'.  $ms_group_name.'</td>';
        echo  '<td>'.  $ms_inst_name.'</td>';
        echo  '<td>'.  $ms_board_or_univ.'</td>';
        // echo  '<td>'.  $ms_div_or_cgpa.'</td>';
        if($ms_cgpa_4!=''){
          echo  '<td>'.  $ms_cgpa_4.'</td>';
        }
        else{
          echo  '<td>'.  $ms_div_or_cgpa.'</td>';
        }
        echo  '<td>'.  $ms_passing_year.'</td>';
        echo  '<td>'.  $ms_course_duration.'</td>';
      echo  '</tr>';
    
 }
  }
 else {
				echo "<p class='btn btn-danger btn-md text-left float-start'> Record Not Found!!!</p>";
			}
  echo  '</tbody>';
  echo  '</table>';

if(isset($_SESSION['emp_id'])){
   
   $emp_id=$_SESSION['emp_id'];
   $sql1="SELECT * from other_qualification_info where emp_id='$emp_id'";
   //$sql="SELECT * FROM users where emp_id='$emp_id'";
 }
 $result1 = mysqli_query($conn,$sql1);
  if (mysqli_num_rows($result1) > 0) {
  echo "<table class='table table-bordered table-striped text-center'>";
    echo  '<thead class="thead-dark ">';

    echo  '<tr>';
    echo  '<th colspan="6" class="bg-primary">Other Qualification</th>';
    echo  '</tr>';

      echo  '<tr>';
      echo  '<th>Degree Name</th>';
      echo  '<th class="text-center">Subject</th>';
      echo  '<th class="text-center">University</th>';
      echo  '<th class="text-center">Country</th>';
      echo  '<th class="text-center">Course Duration</th>';

      echo  '</tr>';
    echo  '</thead>';
 
 while($row = mysqli_fetch_array($result1)) {
$degree_name=$row['degree_name']; 
$subject=$row['subject'];
$university=$row['university'];
$country=$row['country'];
$course_duration=$row['course_duration'];

 echo  '<tbody>';
      echo  '<tr>';
        echo  '<td>'.  $degree_name.'</td>';
        echo  '<td>'.  $subject.'</td>';
        echo  '<td>'.  $university.'</td>';
        echo  '<td>'.  $country.'</td>';
        echo  '<td>'.  $course_duration.'</td>';
        
      echo  '</tr>';
 
    }
  }
 else {
        // echo "<p class='btn btn-danger btn-md text-left float-start'> Record Not Found!!!</p>";
      }
  echo  '</tbody>';
  echo  '</table>';
 mysqli_close($conn);
 
?>



  <h4><a class="btn btn-success" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous Page </a></h4>
</div>
</div>
<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4></div>
<div class="col-sm-4 "></div>

</div>
</div>
<?php include('../includes/footer.php');?>
