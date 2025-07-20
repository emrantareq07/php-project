<!-- bio_data_service_info_print.php -->
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
    
}
// Close the database connection
//mysqli_close($conn);

include('../includes/footer-print.php');
?>
