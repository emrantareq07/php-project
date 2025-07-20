<?php
session_start();
include('../db/db.php');

include('../includes/header-dashboard.php');
if (isset($_POST['submit'])) {
    // Form submitted, process the data

    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']); // Added mysqli_real_escape_string to prevent SQL injection
    $day1=date('d',strtotime($start_date));
    $month1=date('m',strtotime($start_date));
  $year1=date('Y',strtotime($start_date));


  //$start_date='$year1-$month1-$day1';
  $start_date = strftime("%F", strtotime($year1."-".$month1."-".$day1));

  $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
   
    $day2=date('d',strtotime($end_date));
    $month2=date('m',strtotime($end_date));
   $year2=date('Y',strtotime($end_date));

  //$end_date='$year2-$month2-$day2';
   $end_date = strftime("%F", strtotime($year2."-".$month2."-".$day2));

    //echo '$place_of_posting';
  $dateColumnSelector =  $_POST['dateColumnSelector'];
// $dateColumnSelector = mysqli_real_escape_string($conn, $_POST['dateColumnSelector']);
    // Fetch data based on the selected criteria
   
    $sql = "SELECT * FROM job_desc j inner JOIN basic_info b ON j.emp_id=b.emp_id  and  j.$dateColumnSelector  between '$start_date' and '$end_date' ";

    $result = mysqli_query($conn, $sql);

    ?>

    <style type="text/css">
      body{
        background-color: white;
      }
    </style>

<div class="container-fluid mt-4 ">
  <div class="row">
  <div class="col-sm-10">
    <?php

    if (mysqli_num_rows($result) == 0) {
        // echo "No records found";
        // $_SESSION['emp_ids'] = 0;
  echo ' <div class="alert alert-danger text-uppercase text-center" role="alert"> No Records Found</div>';

  session_destroy();
    }else {

    echo "<table class='table table-bordered border table-hovered table-striped shadow'>";
    echo  '<tbody>';
         echo '<tr>';
    echo '<td class="text-center text-uppercase text-muted table-muted" colspan="6"><b> No. of Employees List </b></td>';
      echo '</tr>';
       
      echo  '<tr  class="table-success ">';
      
      echo  '<th>EMP ID</th>';
      echo  '<th>Name</th>';
      
      echo  '<th>Designation</th>';
      echo  '<th>DOB</th>';
       echo  '<th>PRL</th>';
      echo  '<th>Next Promotion Date</th>';
      echo  '</tr>';
  
  while ($row = mysqli_fetch_array($result)) {

    $emp_id=$row['emp_id'];

    $emp_ids[] = $row['emp_id'];
                         
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
        $_SESSION['emp_ids'] = $emp_ids;
       echo '</tbody>';
    echo '</table>';
   }


    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>

  </div>
    

    <div class="col-sm-2">
<center>
       <a href="search_all.php" class="btn btn-primary text-white">
              <i class="fa fa-arrow-left" style="color:white"></i> Previous Page
          </a>
          <hr>
          <a href="#" id="printButton" class="btn btn-danger text-white">
              <i class="fa fa-print" style="color:white"></i> Print
          </a>
               
</center>
    </div>
  </div>
</div>
    
<?php include('../includes/footer.php'); ?>

<script>
    // Print functionality
    document.getElementById("printButton").addEventListener("click", function() {
        printContent("search_all_details_print.php", " ");
    });

    function printContent(url, title) {
        let printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');

        // Retrieve emp_ids from the session
        let emp_ids = <?php echo isset($_SESSION['emp_ids']) ? json_encode($_SESSION['emp_ids']) : 'null'; ?>;

        var xhr = new XMLHttpRequest();
        var urlToFetch = url + "?emp_ids=" + emp_ids.join(',');
        xhr.open("GET", urlToFetch, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var contentToPrint = `
                <html>
                <head>
                    <title>PMIS</title>
                </head>
                <body>
                    <center><b><h2 class='text-center text-muted text-uppercase'>${title}</h2></b></center>
                    ${xhr.responseText}
                </body>
                </html>
            `;

                printWindow.document.open();
                printWindow.document.write(contentToPrint);
                printWindow.document.close();

                printWindow.onload = function() {
                    printWindow.onafterprint = function() {
                        printWindow.close();
                    };
                    printWindow.print();
                };
            }
        };
        xhr.send();
    }
</script>

