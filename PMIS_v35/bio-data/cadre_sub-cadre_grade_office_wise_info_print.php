<?php
session_start(); // Start session at the beginning
include('../db/db.php');
include('../includes/header-print.php');

// if(isset($_POST['submit'])){
// Form submitted, process the data
if (isset($_SESSION['emp_ids'])) {
    $emp_ids = $_SESSION['emp_ids'];
    $last_update_at;  
    // Fetch data based on the selected criteria
    $sql = "SELECT * FROM job_desc j INNER JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE j.emp_id IN ('" . implode("','", $emp_ids) . "')";

    $result = mysqli_query($conn, $sql);

    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 0) {
        echo "No records found";
    } else {
        // Process the fetched data as needed

        // Display the title only once at the beginning
        while ($row = mysqli_fetch_assoc($result)) {

           echo "<center><b><h2 class='text-center text-muted text-uppercase'>Employees Bio-Data Sheet</h2></b></center>";

            echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: " . $row['name'] . "</b><b>&nbsp;(" . $row['designation'] . ")</b>&nbsp;</center>";
            echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: " . $row['emp_id'] . ";" . " Seniority No : " . $row['seniority_no'] . ";" . " Division : " . $row['division'] . "</b>&nbsp; </center>";

            echo "<table class='table table-bordered border table-hovered table-striped text-muted '>";
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
            echo '<td>' . $row['dob'] . '</td>';
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
            echo '<th class="table-success">Marital Status</th>';
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

            // echo '</tbody>';
            // echo '</table>';

            // Display spouse information if available

            if ($row['spouse_name'] != null) {
                // echo '<table class="table table-bordered border table-hovered table-striped">';
                // echo '<tbody>';
                echo '<tr>';
                echo '<th class="table-light text-uppercase text-muted text-center" colspan="2">Spouse Information</th>';

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
                echo '</tbody>';
                echo '</table>';
            }

            // Display child information

            $sqlchild = "SELECT * FROM childs_info WHERE emp_id='{$row['emp_id']}'";
            $resultchild = mysqli_query($conn, $sqlchild);

            if ($resultchild && mysqli_num_rows($resultchild) > 0) {
                echo '<table class="table table-bordered border table-hovered table-striped">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td class="text-center text-uppercase text-muted table-success" colspan="3"><b>Children Information</b></td>';
                echo '</tr>';
                echo '<tr>
                      <th class="table-success">Name</th>
                      <th class="table-success">Date of Birth</th>
                      <th class="table-success">Gender</th>
                  </tr>';

                while ($rowchild = mysqli_fetch_array($resultchild)) {
                    echo '<tr>';
                    echo '<td>' . $rowchild['name'] . '</td>';
                    echo '<td>' . $rowchild['dob'] . '</td>';
                    echo '<td>' . $rowchild['gender'] . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }



            // Display education information

            $sqledu = "SELECT * FROM edu_info WHERE emp_id='{$row['emp_id']}'";
            $resultedu = mysqli_query($conn, $sqledu);

            if ($resultedu && mysqli_num_rows($resultedu) > 0) {
         echo "<table class='table table-bordered border table-hovered table-striped '>";
           echo  '<tbody>';
                echo "<tr> ";
                    echo "<td class='text-center text-uppercase text-muted' colspan='7'><b>Educational Qualification</b></td> ";
                echo "</tr>";
  
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
    

                while ($rowedu = mysqli_fetch_array($resultedu)) {
                      $ssc_exam=$rowedu['ssc_exam'];
                       $ssc_group_name=$rowedu['ssc_group_name'];
                       $ssc_inst_name=$rowedu['ssc_inst_name'];
                       $ssc_board_or_univ=$rowedu['ssc_board_or_univ'];
                       $ssc_div_or_cgpa=$rowedu['ssc_div_or_cgpa'];
                       
                       $ssc_passing_year=$rowedu['ssc_passing_year'];

                       $hsc_exam=$rowedu['hsc_exam'];
                       $hsc_group_name=$rowedu['hsc_group_name'];
                       $hsc_inst_name=$rowedu['hsc_inst_name'];
                       $hsc_board_or_univ=$rowedu['hsc_board_or_univ'];
                       $hsc_div_or_cgpa=$rowedu['hsc_div_or_cgpa'];
                       $hsc_passing_year=$rowedu['hsc_passing_year'];
                       
                       $honors_exam=$rowedu['honors_exam'];  
                       $honors_group_name=$rowedu['honors_group_name'];
                       $honors_inst_name=$rowedu['honors_inst_name'];
                       $honors_board_or_univ=$rowedu['honors_board_or_univ'];
                       $honors_div_or_cgpa=$rowedu['honors_div_or_cgpa'];  
                       $honors_passing_year=$rowedu['honors_passing_year'];
                       $honors_course_duration=$rowedu['honors_course_duration'];

                       $masters=$rowedu['masters'];
                       $ms_group_name=$rowedu['ms_group_name'];
                       $ms_inst_name=$rowedu['ms_inst_name'];  
                       $ms_board_or_univ=$rowedu['ms_board_or_univ'];
                       $ms_div_or_cgpa=$rowedu['ms_div_or_cgpa'];
                       $ms_passing_year=$rowedu['ms_passing_year'];
                       $ms_course_duration=$rowedu['ms_course_duration'];

                       $ssc_cgpa_5=$rowedu['ssc_cgpa_5'];
                        $hsc_cgpa_5=$rowedu['hsc_cgpa_5'];
                        $honors_cgpa_4=$rowedu['honors_cgpa_4'];
                        $ms_cgpa_4=$rowedu['ms_cgpa_4'];

                    // echo '<tr>';
                    // echo '<td>' . $rowedu['degree'] . '</td>';
                    // echo '<td>' . $rowedu['institute'] . '</td>';
                    // echo '<td>' . $rowedu['year'] . '</td>';
                    // echo '<td>' . $rowedu['result'] . '</td>';
                    // echo '</tr>';

                     echo  '<tr>';
                      echo  '<td>'.  $ssc_exam.'</td>';
                      echo  '<td>'.  $ssc_group_name.'</td>';
                      //echo  '<td>'.  $dolp.'</td>';
                      echo  '<td>'.  $ssc_inst_name.'</td>';
                      echo  '<td>'.  $ssc_board_or_univ.'</td>';
                      // echo  '<td>'.  $ssc_div_or_cgpa.'</td>';
                        if($ssc_cgpa_5!=''){
                          echo  '<td>'.  $ssc_cgpa_5.'</td>';
                        }
                        else{
                          echo  '<td>'.  $ssc_div_or_cgpa.'</td>';
                        }
                      
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
          // echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
            if($hsc_cgpa_5!=''){
              echo  '<td>'.  $hsc_cgpa_5.'</td>';
            }
            else{
              echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
            }
          
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
      }

        if($masters !=Null && $ms_group_name!=Null && $ms_inst_name!=Null && $ms_board_or_univ!=Null && $ms_div_or_cgpa!=Null && $ms_passing_year!=Null && $ms_course_duration!=Null){

          echo  '<tr>';
            echo  '<td>'.  $masters.'</td>';
            echo  '<td>'.  $ms_group_name.'</td>';
            //echo  '<td>'.  $dolp.'</td>';
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

                echo '</tbody>';
                echo '</table>';
            }



                        // Display service history

            $sqlchild = "SELECT * FROM service_history WHERE emp_id='{$row['emp_id']}'";
            $resultchild = mysqli_query($conn, $sqlchild);

            if ($resultchild && mysqli_num_rows($resultchild) > 0) {
                echo '<table class="table table-bordered border table-hovered table-striped">';
                echo '<tbody>';
                echo '<tr class="bg-success">';
                echo '<td class="text-center text-uppercase text-muted" colspan="9"><b>Service History</b></td>';
                echo '</tr>';
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

                while ($rowServic_hitory = mysqli_fetch_array($resultchild)) {
                    echo '<tr>';
                    echo '<td width="50%">' . $rowServic_hitory['service_type'] . '</td>';
                    echo '<td>' . $rowServic_hitory['from_date'] . '</td>';
                               
                    echo '<td>' . $rowServic_hitory['to_date'] . '</td>';
                    
                    echo '<td>' . $rowServic_hitory['till_now'] . '</td>';
                    
                    echo '<td>' . $rowServic_hitory['org_name'] . '</td>';
                   
                    echo '<td>' . $rowServic_hitory['location'] . '</td>';
                
                    echo '<td>' . $rowServic_hitory['place_of_posting'] . '</td>';
                
                    echo '<td>' . $rowServic_hitory['designation'] . '</td>';
                    echo '<td>' . $rowServic_hitory['scale'] . '</td>';
                    echo '</tr>';

                }

                echo '</tbody>';
                echo '</table>';
            }




            // Display Service Record/Job information

            $sqljob_desc = "SELECT * FROM job_desc WHERE emp_id='{$row['emp_id']}'";
            $resultjob_desc = mysqli_query($conn, $sqljob_desc);

            if ($resultjob_desc && mysqli_num_rows($resultjob_desc) > 0) {

                while($rowjob_desc = mysqli_fetch_array($resultjob_desc)) {

            echo "<table class='table table-bordered border table-hovered table-striped '>";
            
            echo '<tbody>';

                echo '<tr>';
                echo '<td class="text-center text-uppercase text-muted" colspan="3"><b>Service/Job Record</b></td>';
                echo '</tr>';

            echo '<tr>';

            echo '<tr>';
            echo '<td  class="table-success fw-bold" width="50%">Date of Birth</td>';
            echo '<td width="50%">' . $rowjob_desc['dob'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Date of Joining</th>';
            echo '<td>' . $rowjob_desc['doj'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Police Verification</th>';
            echo '<td>' . $rowjob_desc['police_verification'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">PRL Date</th>';
            echo '<td>' . $rowjob_desc['prl'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Pension Start Date</th>';
            echo '<td>' . $rowjob_desc['pension_start_date'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Place of Posting</th>';
            echo '<td>' . $rowjob_desc['place_of_posting'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">D-nothi ID</th>';
            echo '<td>' . $rowjob_desc['d_nothi_id'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">TIN No.</th>';
            echo '<td>' . $rowjob_desc['tin_no'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Joining Designation</th>';
            echo '<td>' . $rowjob_desc['join_designation'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Cadre</th>';
            echo '<td>' . $rowjob_desc['cadre'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Sub Cadre</th>';
            echo '<td>' . $rowjob_desc['sub_cadre'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Appointment Type</th>';
            echo '<td>' . $rowjob_desc['appoint_type'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Date of Last Promotion</th>';
            echo '<td>' . $rowjob_desc['last_promo_date'] . '</td>';
            echo '</tr>';

             echo '<tr>';
            echo '<th class="table-success">Next Promotion Date</th>';
            echo '<td>' . $rowjob_desc['next_promo_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Granted Promotion Date</th>';
            echo '<td>' . $rowjob_desc['granted_promo_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Nature of Promotion</th>';
            echo '<td>' . $rowjob_desc['nature_of_promo'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Last Increment Date</th>';
            echo '<td>' . $rowjob_desc['last_incr_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Next Increment Date</th>';
            echo '<td>' . $rowjob_desc['next_incr_date'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Grade</th>';
            echo '<td>' . $rowjob_desc['grade'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Basic</th>';
            echo '<td>' . $rowjob_desc['basic'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Scale</th>';
            echo '<td>' . $rowjob_desc['scale'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Job Status</th>';
            echo '<td>' . $rowjob_desc['job_status'] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Increment Granted (Tk.)</th>';
            echo '<td>' . $rowjob_desc['incr_granted'] . '</td>';
            echo '</tr>';
             echo '<tr>';
            echo '<th class="table-success">Basic After Increment (Tk.)</th>';
            echo '<td>' . $rowjob_desc['basic_after_incr'] . '</td>';
            echo '</tr>';

            }
            echo '</tbody>';
        echo '</table>';

            }

             // Display Training information

            $sql_training_info = "SELECT * FROM training_info WHERE emp_id='{$row['emp_id']}'";
            $result_training_info = mysqli_query($conn, $sql_training_info);

            if ($result_training_info && mysqli_num_rows($result_training_info) > 0) {

                echo "<table class='table table-bordered border table-hovered table-striped '>";
                   echo  '<tbody>';
                     echo '<tr>';
                echo '<td class="text-center text-uppercase text-muted table-success" colspan="6"><b>Training Information</b></td>';
                echo '</tr>';
                   
                  echo  '<tr  class="table-success ">';
                  
                  echo  '<th>Training Type</th>';
                  echo  '<th>Title</th>';
                  
                  echo  '<th>Institute</th>';
                  echo  '<th>Country</th>';
                  echo  '<th>Period</th>';
                  echo  '<th>Month/ Year</th>'; 
                  echo  '</tr>';

                while($row_training_info = mysqli_fetch_array($result_training_info)) {

                         $type=$row_training_info['type'];
                         
                         $title=$row_training_info['title'];
                         $month_year=$row_training_info['month_year'];
                         $institute=$row_training_info['institute'];
                         $country=$row_training_info['country'];
                         $period=$row_training_info['period'];
                          
                          echo  '<tr>';
                         
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

           // Display prof./Membership information

            $sqlprof_membership = "SELECT * FROM prof_membership WHERE emp_id='{$row['emp_id']}'";
            $resultprof_membership = mysqli_query($conn, $sqlprof_membership);

            if ($resultprof_membership && mysqli_num_rows($resultprof_membership) > 0) {
                echo '<table class="table table-bordered border table-hovered table-striped">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td class="text-center text-uppercase text-muted table-success" colspan="6"><b>Prof./Membership Information</b></td>';
                echo '</tr>';
                echo '<tr>
                      <th class="table-success">Mem_no</th>
                      <th class="table-success">type</th>
                      <th class="table-success">Institute</th>
                        <th class="table-success">Country</th>
                      <th class="table-success">Validity</th>
                      <th class="table-success">Year</th>
                  </tr>';

                while ($rowprof_membership= mysqli_fetch_array($resultprof_membership)) {
                    echo '<tr>';
                    echo '<td>' . $rowprof_membership['mem_no'] . '</td>';
                    echo '<td>' . $rowprof_membership['type'] . '</td>';
                    echo '<td>' . $rowprof_membership['institute'] . '</td>';

                    echo '<td>' . $rowprof_membership['country'] . '</td>';
                    echo '<td>' . $rowprof_membership['validity'] . '</td>';
                    echo '<td>' . $rowprof_membership['month_year'] . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }

                    //award_and_penalty info 
             $sqlaward_and_penalty = "SELECT * FROM award_and_penalty WHERE emp_id='{$row['emp_id']}'";
            $resultsqlaward_and_penalty = mysqli_query($conn, $sqlaward_and_penalty);

            if ($resultsqlaward_and_penalty && mysqli_num_rows($resultsqlaward_and_penalty) > 0) {
                echo '<table class="table table-bordered border table-hovered table-striped">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td class="text-center text-uppercase text-muted table-success" colspan="8"><b>Award/Penalty Information</b></td>';
                echo '</tr>';
                echo '<tr>
                      <th class="table-success">Given date</th>
                      <th class="table-success">Status</th>
                      <th class="table-success">Nature</th>
                         <th class="table-success">From Date</th>
                      <th class="table-success">To Date</th>
                      <th class="table-success">Ground/Subject</th>
                         <th class="table-success">Special Increment</th>
                      <th class="table-success">Special Promotion</th>
                      
                  </tr>';

                while ($rowaward_and_penalty= mysqli_fetch_array($resultsqlaward_and_penalty)) {
                    echo '<tr>';
                    echo '<td>' . $rowaward_and_penalty['given_date'] . '</td>';
                    echo '<td>' . $rowaward_and_penalty['status'] . '</td>';
                    echo '<td>' . $rowaward_and_penalty['nature'] . '</td>';

                    echo '<td>' . $rowaward_and_penalty['from_date'] . '</td>';
                    echo '<td>' . $rowaward_and_penalty['to_date'] . '</td>';
                    echo '<td>' . $rowaward_and_penalty['ground_or_subject'] . '</td>';

                      echo '<td>' . $rowaward_and_penalty['special_increment'] . '</td>';
                    echo '<td>' . $rowaward_and_penalty['special_promotion'] . '</td>';
                
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }


           


            echo "<style> @media print { .page-break { page-break-before: always; } } </style>";
            echo "<div class='page-break'></div>";
        }
    }
}

include('../includes/footer-print.php');
?>
