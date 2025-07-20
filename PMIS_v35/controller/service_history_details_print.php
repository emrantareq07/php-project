<!-- service_history_details.php -->
<?php
 include('../includes/header-print.php');
// Database configuration
include('../db/db.php');

session_start();
// require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];
$emp_id=$_SESSION['emp_id'];
// Query to retrieve data from the database
$query = "SELECT * FROM service_history where emp_id='$emp_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
 if (mysqli_num_rows($result) > 0) {


// Create a table with data
echo "<table class='table table-bordered table-hovered table-striped text-center' border='1'>";
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
              // echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
            }
        echo  '</tbody>';

echo "</table>";

// Close the database connection
mysqli_close($conn);
 include('../includes/footer-print.php');
?>
