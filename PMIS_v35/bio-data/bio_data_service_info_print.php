
<?php
error_reporting(0);
include('../includes/header-print.php');
include('../db/db.php');
session_start();

if (isset($_SESSION['emp_id'])) {
    $emp_id = $_SESSION['emp_id'];
    
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
    
}

include('../includes/footer-print.php');
?>
