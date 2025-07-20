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
$query = "SELECT * FROM training_info where emp_id='$emp_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
 if (mysqli_num_rows($result) > 0) {

// echo "<center><b><h2 class='text-center'> Service History</h2></b></center>";
// Create a table with data
echo "<table class='table table-bordered table-hovered table-striped text-center' border='1'>";
          echo  '<thead class="table-success">';
          echo  '<tr>';
          echo  '<th>Emp Id</th>';
          echo  '<th>Training Type</th>';
          echo  '<th>Title</th>';
          
          echo  '<th>Institute</th>';
          echo  '<th>Country</th>';
          echo  '<th>Period</th>';
          echo  '<th>Month/ Year</th>'; 
          echo  '</tr>';
          echo  '</thead>';
       while($row = mysqli_fetch_array($result)) {
        $emp_id=$row['emp_id'];
         $type=$row['type'];
         
         $title=$row['title'];
         $month_year=$row['month_year'];
         $institute=$row['institute'];
         $country=$row['country'];
         $period=$row['period'];
       echo  '<tbody>';
          echo  '<tr>';
          echo  '<td>'.  $emp_id.'</td>';
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
              // echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
            }
        echo  '</tbody>';

echo "</table>";

// Close the database connection
mysqli_close($conn);
 include('../includes/footer-print.php');
?>
