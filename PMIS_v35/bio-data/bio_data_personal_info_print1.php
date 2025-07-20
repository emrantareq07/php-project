<?php
error_reporting(0);
include('../includes/header-print.php');
include('../db/db.php');
session_start();


?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BCIC PMIS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  
  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
    integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 

    <link rel="icon" type="image/gif/png" href="../images/bcic_logo.png">
    <link rel="stylesheet" type="text/css" href="../css/print.css" media="print">
    <!-- <link rel="stylesheet" type="text/css" href="css/ab.css" > -->

    <style>
/* Hide the print button when printing */
@media print {
    #print-btn {
        display: none;
    }
}
</style>

</head>

<div class="row">
    <div class="col-sm-12 text-end"><!--12 columns, aligned to the end-->
        <button onclick="location.href='bio_data_specific_info_by_emp_id.php'" type="button" class="btn btn-info text-white" id="print-btn"><i class="fa fa-arrow-left" style="font-size:20px;color:white"></i> Previous Page</button>
        <button onclick="window.print();return false;" class="btn btn-danger ml-2" id="print-btn">Print</button>
    </div>
</div>



<?php

if(isset($_POST['submit'])){

foreach ($_POST['emp_id'] as $selectedOption){


$emp_id= $selectedOption;

    $query = "SELECT * FROM basic_info where emp_id='$emp_id'";
    $result = mysqli_query($conn, $query);
    // $query = "SELECT * FROM basic_info b inner join job_desc j on b.emp_id='$emp_id' and j.emp_id='$emp_id'";
      $sql1="SELECT dob from job_desc where emp_id='$emp_id'";
      $result1 = mysqli_query($conn,$sql1);
      $row1 = mysqli_fetch_array($result1);
      $dob=$row1['dob'];
    

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    } 
     
 if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_array($result)) {

    echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: ".$row['name']."</b><b>&nbsp;(".$row['designation'].")</b>&nbsp;</center>";
    echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: ".$row['emp_id'].";"." Seniority No : ". $row['seniority_no'] .";"." Division : ".$row['division']."</b>&nbsp; </center>";

            echo "<table class='table table-bordered border table-hovered table-striped '>";
            // echo  '<thead >';
            echo '<tbody>';

            echo '<tr>';

            echo '<tr>';
            echo '<td  class="table-success fw-bold">Father\'s Name</td>';
            echo '<td>' . $row['fathersName'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Mother\'s Name</th>';
            echo '<td>' . $row['mothersName'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Date of Birth</th>';
            echo '<td>' . $dob . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Gender</th>';
            echo '<td>' . $row['gender'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Blood Group</th>';
            echo '<td>' . $row['blood_group'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Religion</th>';
            echo '<td>' . $row['religion'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">National ID</th>';
            echo '<td>' . $row['nid'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Mobile Number</th>';
            echo '<td>' . $row['mobile_no'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Home District</th>';
            echo '<td>' . $row['home_dist'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Email</th>';
            echo '<td>' . $row['email'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Quotas</th>';
            echo '<td>' . $row['quota'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Maritial Status</th>';
            echo '<td>' . $row['maritial_status'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Present Address</th>';
            echo '<td>' . $row['present_add'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Permanent Address</th>';
            echo '<td>' . $row['permanent_add'] . '</td>';
            echo '</tr>';
      if($row['spouse_name']!=null){

         echo '<tr>';
            echo '<th class="table-light text-center" colspan="2">Spouse Information</th>';
            
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Spouse Name</th>';
            echo '<td>' . $row['spouse_name'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Home District</th>';
            echo '<td>' . $row['spouse_home_dist'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Occupation</th>';
            echo '<td>' . $row['spouse_occupation'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Present Address</th>';
            echo '<td>' . $row['spo_present_add'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Permanent Address</th>';
            echo '<td>' . $row['spo_parmanent_add'] . '</td>';
            echo '</tr>';

        }       

            echo '</tbody>';
        echo '</table>';
    } 
 }

    else {
        // echo '<p class="btn btn-danger btn-block text-left">Record Not Found!!!</p>';
    }

  $sqlchild="SELECT * FROM childs_info where emp_id='$emp_id'";
     //$sql="SELECT * FROM users u INNER JOIN childs_info c ON u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
  $resultchild = mysqli_query($conn,$sqlchild);
       if (mysqli_num_rows($resultchild) > 0) {

            echo "<table class='table table-bordered border table-hovered table-striped '>";
            
                echo  "<tbody>";
                echo "<tr>";
                  // echo "<tr style='background-color:#5f228a;color:white;'> ";
                  echo "<td class='text-center' colspan='3'><b>Children Information</b></td> ";
                  echo "</tr>";

                  echo "<tr>
                      <th class='table-success'>Name</th>
                      <th class='table-success'>Date of Birth</th>
                      <th class='table-success'> Gender </th>
                  </tr>";


            while($rowchild = mysqli_fetch_array($resultchild)) {

            //     echo '<tr>';
            // echo '<th class="table-light text-center" colspan="2">Children Information</th>';
            
            // echo '</tr>';

            echo '<tr >';
            // echo '<th class="table-success"> Name</th>';
            echo '<td>' . $rowchild['name'] . '</td>';
            // echo '</tr>';
            // echo '<tr>';
            // echo '<th class="table-success">Date of Birth</th>';
            echo '<td>' . $rowchild['dob'] . '</td>';
            // echo '</tr>';
            // echo '<tr>';
            // echo '<th class="table-success">Gender</th>';
            echo '<td>' . $rowchild['gender'] . '</td>';
            echo '</tr>';


  

            }
                          echo '</tbody>';
        echo '</table>';
        }
             
      
     else {
        // echo '<p class="btn btn-danger btn-block text-left">Record Not Found!!!</p>';
    }
 
 // for education

    $sqlchild="SELECT * FROM edu_info where emp_id='$emp_id'";
     //$sql="SELECT * FROM users u INNER JOIN childs_info c ON u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
  $resultchild = mysqli_query($conn,$sqlchild);
       if (mysqli_num_rows($resultchild) > 0) {

           echo "<table class='table table-bordered border table-hovered table-striped '>";
           echo  '<tbody>';
          echo "<tr> ";
            echo "<td class='text-center' colspan='7'><b>Educational Qualification</b></td> ";
            echo "</tr>";
    // echo  '<thead class="table-success ">';
      echo  '<tr class="table-success bg-success">';
        echo  '<th>Examination</th>';
        echo  '<th>Subject/Group</th>';
        echo  '<th>Institute</th>';
        echo  '<th>Board/Univ. </th>';
        // echo  '<th>CGPA/Division/Class</th>';
        echo  '<th>Result</th>';
        echo  '<th>Year</th>';
        echo  '<th>Duration</th>';

      echo  '</tr>';
      // echo  '</thead>';
 
  while($row = mysqli_fetch_array($resultchild)) {

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

        if($hsc_exam!=Null && $hsc_group_name!=Null && $hsc_inst_name!=Null && $hsc_board_or_univ!=Null && $hsc_div_or_cgpa!=Null && $hsc_passing_year!=Null){
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

        }

        if($honors_exam !=Null && $honors_group_name!=Null && $honors_inst_name!=Null && $honors_board_or_univ!=Null && $honors_div_or_cgpa!=Null && $honors_passing_year!=Null && $honors_course_duration!=Null){

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
      }

        if($masters !=Null && $ms_group_name!=Null && $ms_inst_name!=Null && $ms_board_or_univ!=Null && $ms_div_or_cgpa!=Null && $ms_passing_year!=Null && $ms_course_duration!=Null){

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
  
        }
  // echo  '</div>';
    }

 }
 else {
        // echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
      }
 echo  '</tbody>';
echo  '</table>';
  ?> 


<div style="break-after:page"></div>

<?php
}
   ?> 



 

<?php
include('../includes/footer-print.php');
?>

<?php
}
   ?> 




   <?php

if(isset($_POST['submit1'])){

foreach ($_POST['emp_id'] as $selectedOption){

$emp_id= $selectedOption;


    $query = "SELECT * FROM basic_info where emp_id='$emp_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    } 

 if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_array($result)) {

    echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: ".$row['name']."</b><b>&nbsp;(".$row['designation'].")</b>&nbsp;</center>";
    echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: ".$row['emp_id'].";"." Seniority No : ". $row['seniority_no'] .";"." Division : ".$row['division']."</b>&nbsp; </center>";

        

            echo '</tbody>';
        echo '</table>';
    } 
 }

    else {
    
    }

  $sqlchild="SELECT * FROM job_desc where emp_id='$emp_id'";
     
  $resultchild = mysqli_query($conn,$sqlchild);
       if (mysqli_num_rows($resultchild) > 0) {

           while($row = mysqli_fetch_array($resultchild)) {

            echo "<table class='table table-bordered border table-hovered table-striped '>";
            // echo  '<thead >';
            echo '<tbody>';

            echo '<tr>';

            echo '<tr>';
            echo '<td  class="table-success fw-bold" width="50%">Date of Birth</td>';
            echo '<td width="50%">' . $row['dob'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Date of Joining</th>';
            echo '<td>' . $row['doj'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Police Verification</th>';
            echo '<td>' . $row['police_verification'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">PRL Date</th>';
            echo '<td>' . $row['prl'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Pension Start Date</th>';
            echo '<td>' . $row['pension_start_date'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Place of Posting</th>';
            echo '<td>' . $row['place_of_posting'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">D-nothi ID</th>';
            echo '<td>' . $row['d_nothi_id'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">TIN No.</th>';
            echo '<td>' . $row['tin_no'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Joining Designation</th>';
            echo '<td>' . $row['join_designation'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Cadre</th>';
            echo '<td>' . $row['cadre'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Sub Cadre</th>';
            echo '<td>' . $row['sub_cadre'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Appointment Type</th>';
            echo '<td>' . $row['appoint_type'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Date of Last Promotion</th>';
            echo '<td>' . $row['last_promo_date'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Next Promotion Date</th>';
            echo '<td>' . $row['next_promo_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Granted Promotion Date</th>';
            echo '<td>' . $row['granted_promo_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Nature of Promotion</th>';
            echo '<td>' . $row['nature_of_promo'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Last Increment Date</th>';
            echo '<td>' . $row['last_incr_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Next Increment Date</th>';
            echo '<td>' . $row['next_incr_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Grade</th>';
            echo '<td>' . $row['grade'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Basic</th>';
            echo '<td>' . $row['basic'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Scale</th>';
            echo '<td>' . $row['scale'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Job Status</th>';
            echo '<td>' . $row['job_status'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Increment Granted (Tk.)</th>';
            echo '<td>' . $row['incr_granted'] . '</td>';
            echo '</tr>';
             echo '<tr>';
            echo '<th class="table-success">Basic After Increment (Tk.)</th>';
            echo '<td>' . $row['basic_after_incr'] . '</td>';
            echo '</tr>';

            }
            echo '</tbody>';
        echo '</table>';
        }
             
      
     else {
        
    } 




?> 



<?php
}
   ?> 

<div style="break-after:page"></div>
<?php
include('../includes/footer-print.php');
?>
<?php
}
   ?> 












<?php

if(isset($_POST['submit2'])){

foreach ($_POST['emp_id'] as $selectedOption){

    $emp_id= $selectedOption;

 $query = "SELECT * FROM basic_info where emp_id='$emp_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    } 

 if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_array($result)) {

    echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: ".$row['name']."</b><b>&nbsp;(".$row['designation'].")</b>&nbsp;</center>";
    echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: ".$row['emp_id'].";"." Seniority No : ". $row['seniority_no'] .";"." Division : ".$row['division']."</b>&nbsp; </center>";

        

            echo '</tbody>';
        echo '</table>';
    } 
 }

    else {
        // echo '<p class="btn btn-danger btn-block text-left">Record Not Found!!!</p>';
    }

  $sqlchild="SELECT * FROM service_history where emp_id='$emp_id'";
     //$sql="SELECT * FROM users u INNER JOIN childs_info c ON u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
  $resultchild = mysqli_query($conn,$sqlchild);
       if (mysqli_num_rows($resultchild) > 0) {

       echo "<table class='table table-bordered border table-hovered table-striped '>";
           echo  '<tbody>';
            // echo "<tr> ";
            // echo "<td class='text-center' colspan='7'><b>Educational Qualification</b></td> ";
            // echo "</tr>";
        // echo  '<thead class="table-success ">';
            echo  '<tr class="table-success bg-success">';
            echo '<th  width="50%">Service Type</th>';
            echo '<th >From Date</th>';
            echo '<th >To Date</th>';
            echo '<th >Currently Working</th>';
            echo '<th >Organization</th>';
            echo '<th >Office Location</th>';
            echo '<th >Place of Posting</th>';
            echo '<th >Designation</th>';
            echo '<th >Scale</th>';
            echo  '</tr>';
      // echo  '</thead>';

           while($row = mysqli_fetch_array($resultchild)) {
            echo '<tr>';
            echo '<td width="50%">' . $row['service_type'] . '</td>';
            echo '<td>' . $row['from_date'] . '</td>';
                       
            echo '<td>' . $row['to_date'] . '</td>';
            
            echo '<td>' . $row['till_now'] . '</td>';
            
            echo '<td>' . $row['org_name'] . '</td>';
           
            echo '<td>' . $row['location'] . '</td>';
        
            echo '<td>' . $row['place_of_posting'] . '</td>';
        
            echo '<td>' . $row['designation'] . '</td>';
            echo '<td>' . $row['scale'] . '</td>';
            echo '</tr>';

       }
            echo '</tbody>';
        echo '</table>';
        }   
      



 ?> 

<div style="break-after:page"></div>

<?php
}
   ?> 

<!-- <div style="break-after:page"></div>  -->

 

<?php
include('../includes/footer-print.php');
?>
<?php
}
   ?> 



     <?php

if(isset($_POST['submit3'])){

foreach ($_POST['emp_id'] as $selectedOption){
$emp_id= $selectedOption;

    $query = "SELECT * FROM basic_info where emp_id='$emp_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    } 

 if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_array($result)) {

    echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: ".$row['name']."</b><b>&nbsp;(".$row['designation'].")</b>&nbsp;</center>";
    echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: ".$row['emp_id'].";"." Seniority No : ". $row['seniority_no'] .";"." Division : ".$row['division']."</b>&nbsp; </center>";

        

            echo '</tbody>';
        echo '</table>';
    } 
 }

    else {
        // echo '<p class="btn btn-danger btn-block text-left">Record Not Found!!!</p>';
    }

  $sqlchild="SELECT * FROM training_info where emp_id='$emp_id'";
     //$sql="SELECT * FROM users u INNER JOIN childs_info c ON u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
  $resultchild = mysqli_query($conn,$sqlchild);
       if (mysqli_num_rows($resultchild) > 0) {

       echo "<table class='table table-bordered border table-hovered table-striped '>";
           echo  '<tbody>';
            // echo "<tr> ";
            // echo "<td class='text-center' colspan='7'><b>Educational Qualification</b></td> ";
            // echo "</tr>";
        // echo  '<thead class="table-success ">';
          echo  '<tr  class="table-success ">';
          // echo  '<th>Emp Id</th>';
          echo  '<th>Training Type</th>';
          echo  '<th>Title</th>';
          
          echo  '<th>Institute</th>';
          echo  '<th>Country</th>';
          echo  '<th>Period</th>';
          echo  '<th>Month/ Year</th>'; 
          echo  '</tr>';
            
      // echo  '</thead>';

           while($row = mysqli_fetch_array($resultchild)) {
           $emp_id=$row['emp_id'];
         $type=$row['type'];
         
         $title=$row['title'];
         $month_year=$row['month_year'];
         $institute=$row['institute'];
         $country=$row['country'];
         $period=$row['period'];
          // echo  '<tbody>';
          echo  '<tr>';
          // echo  '<td>'.  $emp_id.'</td>';
          echo  '<td>'.  $type.'</td>';
              
          echo  '<td>'.  $title.'</td>';
          echo  '<td>'.  $institute.'</td>';
          echo  '<td>'.  $country.'</td>';
          echo  '<td>'.  $period.'</td>';
          echo  '<td>'.  $month_year.'</td>';
          echo  '</tr>';

            }
            echo '</tbody>';
        echo '</table>';
        }
             
      
     else {
        // echo '<p class="btn btn-danger btn-block text-left">Record Not Found!!!</p>';
    } 


 ?> 


<div style="break-after:page"></div>

<?php
}
   ?> 



 

<?php
include('../includes/footer-print.php');
?>
<?php
}
   ?> 