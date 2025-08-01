<?php
session_start(); // Start session at the beginning
include('../db/db.php');
include('../includes/header-print.php');

// if(isset($_POST['submit'])){
// Form submitted, process the data
if (isset($_SESSION['emp_ids'])) {
    $emp_ids = $_SESSION['emp_ids'];

    // Fetch data based on the selected criteria
    $sql = "SELECT * FROM job_desc j INNER JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE j.emp_id IN ('" . implode("','", $emp_ids) . "')";

    $result = mysqli_query($conn, $sql);
     $result1 = mysqli_query($conn, $sql);
     $result3 = mysqli_query($conn, $sql);
$row3 = mysqli_fetch_array($result3);
$cat=$row3['place_of_posting'];
    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 0) {
        echo ' <div class="alert alert-danger text-uppercase text-center" role="alert"> No Records Found</div>';
        session_destroy();
    } else {

               $i=0; 

            while ($row1 = mysqli_fetch_array($result1)) {
            
            $grade1 = $row1['grade'];
            $sub_cadre1 = $row1['sub_cadre'];
            $place_of_posting1 = $row1['place_of_posting'];
            $cadre1= $row1['cadre'];
            
            if('$cat'!='$place_of_posting1')
            {
              $i=1;
             
            }

          }
if($i==1)
{
  $place_of_posting1="ALL";
}
      // Initialize variables
        // $grade = $cadre = $sub_cadre = $place_of_posting = '';
        // Display the table header outside the loop
        echo "<table class=' table table-bordered border table-hovered table-striped shadow'>";
        echo '<tbody>';
        echo '<tr>';
        echo '<td class="text-center text-uppercase text-muted table-muted" colspan="7"><b> Gradation List of ' . $grade1 . ' Grade, ' . $cadre1 . ' Cadre, ' . $sub_cadre1. ' Sub-Cadre, ' . $place_of_posting1 . '. </b></td>';
        echo '</tr>';

        // Display the table column headers
        echo '<tr  class="table-success ">';
        echo '<th>SNR NO</th>';
        echo '<th>EMP ID</th>';
        echo '<th>Name</th>';
        echo '<th>Designation</th>';
        echo '<th>DOB</th>';
        echo '<th>PRL</th>';
        echo '<th>Next Promotion Date</th>';
//
       
        echo '</tr>';

        while ($row = mysqli_fetch_array($result)) {
            $emp_id = $row['emp_id'];
            $seniority_no = $row['seniority_no'];
            $emp_ids[] = $row['emp_id'];

            $name = $row['name'];
            $designation = $row['designation'];
            $dob = $row['dob'];
            $prl = $row['prl'];
            $next_promo_date = $row['next_promo_date'];
            // $grade = $row['grade'];
            // $sub_cadre = $row['sub_cadre'];
            // $place_of_posting = $row['place_of_posting'];
            // $cadre = $row['cadre'];

            // Display the table rows
            echo '<tr>';
            echo '<td>' . $seniority_no . '</td>';
            echo '<td>' . $emp_id . '</td>';
            echo '<td>' . $name . '</td>';
            echo '<td>' . $designation . '</td>';
            echo '<td>' . $dob . '</td>';
            echo '<td>' . $prl . '</td>';
            echo '<td>' . $next_promo_date . '</td>';
            echo '</tr>';
        }


        $_SESSION['emp_ids'] = $emp_ids;
        echo '</tbody>';
        echo '</table>';
    }

    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}

include('../includes/footer-print.php');
?>
