<?php
session_start();
include('../db/db.php');

include('../includes/header-print.php');
if (isset($_SESSION['emp_ids'])) {
    $emp_ids = $_SESSION['emp_ids'];

    // Fetch data based on the selected criteria

    $sql = "SELECT * FROM job_desc j INNER JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE j.emp_id IN ('" . implode("','", $emp_ids) . "')";
   
    // $sql = "SELECT * FROM job_desc j inner JOIN basic_info b ON j.emp_id=b.emp_id  and  j.$dateColumnSelector  between '$start_date' and '$end_date' ";

    $result = mysqli_query($conn, $sql);


    echo "<table class='table table-bordered border table-hovered table-striped '>";
    echo  '<tbody>';
         echo '<tr>';
    echo '<td class="text-center text-uppercase text-muted" colspan="6"><b> Gradation List of</b></td>';
      echo '</tr>';
       
      echo  '<tr  class="table-success ">';
      
      echo  '<th>EMP ID</th>';
      echo  '<th>Name</th>';
      
      echo  '<th>Designation</th>';
      echo  '<th>DOB</th>';
       echo  '<th>PRL</th>';
      echo  '<th>Next Promotion Date</th>';
      echo  '</tr>';
 if (mysqli_num_rows($result) == 0) {
        echo "No records found";
    } else {
  while ($row = mysqli_fetch_array($result)) {

    $emp_id=$row['emp_id'];
                         
     $name=$row['name'];
     $designation=$row['designation'];
     $dob=$row['dob'];
    $prl=$row['prl'];
    $next_promo_date=$row['next_promo_date'];
     echo  '<tr>';
                         
      echo  '<td>'.  $emp_id.'</td>';
          
      echo  '<td>'.  $name.'</td>';
      echo  '<td>'.  $designation.'</td>';
      echo  '<td>'.  $dob.'</td>';
      
      echo  '<td>'.  $prl.'</td>';
      echo  '<td>'.  $next_promo_date.'</td>';

      echo  '</tr>';

        }
       echo '</tbody>';
    echo '</table>';
   }


    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>