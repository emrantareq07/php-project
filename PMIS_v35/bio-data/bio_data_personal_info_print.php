<?php
error_reporting(0);
include('../includes/header-print.php');
include('../db/db.php');
session_start();


if (isset($_SESSION['emp_id'])) {

   $emp_id = $_SESSION['emp_id'];
    

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
   
}
// Close the database connection
//mysqli_close($conn);

include('../includes/footer-print.php');
?>
