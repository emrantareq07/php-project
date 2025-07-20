<?php
session_start(); // Start session at the beginning
include('../db/db.php');
include('../includes/header-print.php');

// Check if the session variable is set
if (isset($_SESSION['emp_ids'])) {
    $emp_ids = $_SESSION['emp_ids'];

    $target_date2 = $_SESSION['target_date2'][0];
    $target_date = new DateTime($target_date2);

    // Fetch data based on the selected criteria
    $sql = "SELECT * FROM job_desc j INNER JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE j.emp_id IN ('" . implode("','", $emp_ids) . "') order by b.seniority_no asc ";

    $result = mysqli_query($conn, $sql);

    $result1 = mysqli_query($conn, $sql);

    //$result3 = mysqli_query($conn, $sql);
    //$row3 = mysqli_fetch_array($result3);
    //$cat=$row3['place_of_posting'];

    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="alert alert-danger text-uppercase text-center" role="alert">No Records Found</div>';
        session_destroy();
    } else {
        // Initialize variables
        // $grade1 = $sub_cadre1 = $cadre1 = $place_of_posting1 = '';
       // $i = 0;

        while ($row1 = mysqli_fetch_array($result)) {
            $grade1 = $row1['grade'];
            $sub_cadre1 = $row1['sub_cadre'];
            //$place_of_posting1 = $row1['place_of_posting'];
            $cadre1 = $row1['cadre'];


            // if ($cat != $place_of_posting1) {
            //     $i = 1;
            // }
        }

        // Display the appropriate table based on the condition
        // if ($i == 0) {
            // If $i is 0, display the first table
            echo "<table class='table table-bordered border table-hovered table-striped shadow'>";
            echo '<tbody>';
              echo '<tr>';
            echo '<td class="text-center text-uppercase text-muted table-muted" colspan="9"><b> <h2>Promotion Due List of </h2></b></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td class="text-center text-uppercase text-muted table-muted blockquote-footer" colspan="9">' . $grade1 . ' Grade, ' . $cadre1 . ' Cadre, ' . $sub_cadre1 . '  </td>';
            echo '</tr>';
            // Display the table column headers
            echo '<tr  class="table-success ">';
            echo '<th>SNR NO</th>';
            echo '<th>EMP ID</th>';
            echo '<th>Name</th>';
            echo '<th>Designation</th>';
            echo '<th>DOJ</th>';
            echo '<th>Next Promotion Date</th>';
            echo '<th>Place of Posting</th>';
            echo '<th>Service Period</th>';
            echo '<th>Total Service Period</th>';
          
            
            echo '</tr>';

            // Display the table rows
            $count=0;
            while ($row = mysqli_fetch_array($result1)) {
                $emp_id = $row['emp_id'];
                $seniority_no = $row['seniority_no'];
                $emp_ids[] = $row['emp_id'];
                $name = $row['name'];
                $doj = $row['doj'];
                $designation = $row['designation'];
                $sub_cadre_header = $row['sub_cadre_header'];
                $last_promo_date_db =$row['last_promo_date'];


                 $doj1 = new DateTime($row['doj']);

                $doj2 =$doj1->diff($target_date);

                $last_promo_date1 = new DateTime($row['last_promo_date']);

                if($last_promo_date_db=='0000-00-00'){
                    $last_promo_date =$doj1->diff($target_date);
                }
                else{
                    $last_promo_date =$last_promo_date1->diff($target_date);
                }                             

                if($sub_cadre_header==''){
                    $designation=$designation;
                }
                else{
                      $designation=$designation.' ('.$sub_cadre_header.')';  
                }
                
                $dob = $row['dob'];
                
                $prl = $row['prl'];
                $next_promo_date = $row['next_promo_date'];
                $place_of_posting_db = $row['place_of_posting'];
                
              $count++;
                echo '<tr>';
                echo '<td>' . $seniority_no . '</td>';
                echo '<td>' . $emp_id . '</td>';
                echo '<td>' . $name . '</td>';
                echo '<td>' . $designation . '</td>';
                echo '<td>' . $doj . '</td>';
                // echo '<td>' . $prl . '</td>';
                echo '<td>' . $next_promo_date . '</td>';
                 
                echo '<td>' . $place_of_posting_db . '</td>';
                echo '<td>' . $last_promo_date->y . ' years, ' . $last_promo_date->m . ' months, ' . $last_promo_date->d . ' days </td>';
                echo '<td>' . $doj2->y . ' years, ' . $doj2->m . ' months, ' . $doj2->d . ' days </td>'; 
                
                echo '</tr>';

            }

             echo '<tr>';
            echo '<td class="text-center text-uppercase text-muted table-muted" colspan="9"> NO.OF OFFICERS: '.$count.' </td>';
            echo '</tr>';

            $_SESSION['emp_ids'] = $emp_ids;
            echo '</tbody>';
            echo '</table>';
        } 


        // else {
            // If $i is 1, display the second table
            // $posting = "ALL";
            // echo "<table class='table table-bordered border table-hovered table-striped shadow'>";
            // echo '<tbody>';
            //             echo '<tr>';
            // echo '<td class="text-center text-uppercase text-muted table-muted" colspan="8"><b><h2> Gradation List of </h2></b></td>';
            // echo '</tr>';
            // echo '<tr>';
            // echo '<td class="text-center text-uppercase text-muted table-muted fst-italic" colspan="8">' . $grade1 . ' Grade, ' . $cadre1 . ' Cadre, ' . $sub_cadre1 . ' Sub-Cadre & Place of Posting : ' . $posting . ' </td>';
            // echo '</tr>';
            // Display the table column headers
            // echo '<tr  class="table-success ">';
            // echo '<th>SNR NO</th>';
            // echo '<th>EMP ID</th>';
            // echo '<th>Name</th>';
            // echo '<th>Designation</th>';
            // echo '<th>DOB</th>';
            // echo '<th>PRL</th>';
            // echo '<th>Next Promotion Date</th>';
            // echo '<th>Place of Posting</th>';
            // echo '</tr>';

            // Display the table rows
            // mysqli_data_seek($result, 0); // Reset the data pointer
            // while ($row = mysqli_fetch_array($result)) {
            //     $emp_id = $row['emp_id'];
            //     $seniority_no = $row['seniority_no'];
            //     $emp_ids[] = $row['emp_id'];
            //     $name = $row['name'];
            //     $sub_cadre_header = $row['sub_cadre_header'];
            //     $designation=$designation.' ('.$sub_cadre_header.')';
            //     $designation = $row['designation'];
            //     $dob = $row['dob'];
            //     $prl = $row['prl'];
            //     $next_promo_date = $row['next_promo_date'];
            //     $place_of_posting = $row['place_of_posting'];

            //     echo '<tr>';
            //     echo '<td>' . $seniority_no . '</td>';
            //     echo '<td>' . $emp_id . '</td>';
            //     echo '<td>' . $name . '</td>';
            //     echo '<td>' . $designation . '</td>';
            //     echo '<td>' . $dob . '</td>';
            //     echo '<td>' . $prl . '</td>';
            //     echo '<td>' . $next_promo_date . '</td>';
            //     echo '<td>' . $place_of_posting . '</td>';
            //     echo '</tr>';
            // }

        //     $_SESSION['emp_ids'] = $emp_ids;
        //     echo '</tbody>';
        //     echo '</table>';
        // }
    // }

    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
}

include('../includes/footer-print.php');
?>
