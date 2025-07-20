<?php
session_start(); // Start session at the beginning
error_reporting(0);
include('../db/db.php');
include('../includes/header-dashboard.php');

if (isset($_POST['submit'])) {
    // Form submitted, process the data

    $biodata_gradation = mysqli_real_escape_string($conn, $_POST['biodata_gradation']); //

    // if($biodata_gradation=='Biodata'){

    // }
    // echo $biodata_gradation;

    $grade = mysqli_real_escape_string($conn, $_POST['grade']); // Added mysqli_real_escape_string to prevent SQL injection
     $sub_cadre_header = mysqli_real_escape_string($conn, $_POST['sub_cadre_header']);
    // //echo '$place_of_posting';

    // $place_of_postings = array();
    // $place_of_postings[] = $place_of_posting;
    // $_SESSION['place_of_postings'] = $place_of_postings;

    $place_of_posting = mysqli_real_escape_string($conn, $_POST['place_of_posting']);

// Validate $place_of_posting based on your requirements

    $place_of_postings = array();
    $place_of_postings[] = $place_of_posting;
    $_SESSION['place_of_postings'] = $place_of_postings;


    $cadre_id = $_POST['cadre'];
    $sql_cadre = "SELECT cadre FROM cadre WHERE id ='$cadre_id'";
    $result_cadre = mysqli_query($conn, $sql_cadre);

    // Check for errors in the SQL query
    if (!$result_cadre) {
        die("Query failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_array($result_cadre)) {
        $cadre = $row['cadre'];
    }

    $sub_cadre_id = $_POST['sub_cadre'];
    $sql_sub_cadre = "SELECT sub_cadre FROM sub_cadre WHERE id ='$sub_cadre_id'";
    $result_sub_cadre = mysqli_query($conn, $sql_sub_cadre);

    // Check for errors in the SQL query
    if (!$result_sub_cadre) {
        die("Query failed: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_array($result_sub_cadre)) {
        $sub_cadre = $row['sub_cadre'];
    }

    // Fetch data based on the selected criteria
    if($place_of_posting=='All'){
       if($sub_cadre_header==''){

        $sql = "SELECT * FROM job_desc j inner JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE grade = '$grade'
            AND cadre = '$cadre'
            AND sub_cadre = '$sub_cadre'
            -- AND sub_cadre_header_ext='$sub_cadre_header'
            -- AND place_of_posting = '$place_of_posting'";

    $result = mysqli_query($conn, $sql);


       } 
       else{
            $sql = "SELECT * FROM job_desc j inner JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE grade = '$grade'
            AND cadre = '$cadre'
            AND sub_cadre = '$sub_cadre'
            AND sub_cadre_header_ext='$sub_cadre_header'
            -- AND place_of_posting = '$place_of_posting'";

    $result = mysqli_query($conn, $sql);

       }

 
}

else{
    if($sub_cadre_header==''){
        $sql = "SELECT * FROM job_desc j inner JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE grade = '$grade'
            AND cadre = '$cadre'
            AND sub_cadre = '$sub_cadre'
            -- AND sub_cadre_header_ext='$sub_cadre_header'
            AND place_of_posting = '$place_of_posting'";
            $result = mysqli_query($conn, $sql);

    }else{
        $sql = "SELECT * FROM job_desc j inner JOIN basic_info b ON j.emp_id=b.emp_id
            WHERE grade = '$grade'
            AND cadre = '$cadre'
            AND sub_cadre = '$sub_cadre'
            AND sub_cadre_header_ext='$sub_cadre_header'
            AND place_of_posting = '$place_of_posting'";
            $result = mysqli_query($conn, $sql);

            }
     
        }
 //    }

 // }   
    // Check for errors in the SQL query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

   if (mysqli_num_rows($result) == 0) {
echo '<div class="modal" id="noRecordsModal" tabindex="-1" aria-labelledby="noRecordsModalLabel" aria-hidden="true">';
    echo '  <div class="modal-dialog">';
    echo '    <div class="modal-content">';
    echo '      <div class="modal-header">';
    echo '        <h5 class="modal-title" id="noRecordsModalLabel">No Records Found</h5>';
    echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '      </div>';
    echo '      <div class="modal-body">';
    echo '        <p>No records were found.</p>';
    echo '      </div>';
    echo '      <div class="modal-footer">';
    echo '        <button type="button" class="btn btn-primary" id="okButton">OK</button>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '  var myModal = new bootstrap.Modal(document.getElementById("noRecordsModal"));';
    echo '  myModal.show();';
    echo '  document.getElementById("okButton").addEventListener("click", function() {';
    echo '    window.location.href = "cadre_sub-cadre_grade_office_wise.php";';
    echo '  });';
    echo '});';
    echo '</script>';
}

    // if (mysqli_num_rows($result) == 0) {
    //     //echo "No records found";
    //     alert("No records found");
    // } 
    else {
?>
        <div class="container mt-1 ">
            <div class="card shadow-lg">
                <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center"><?php
                    // Process the fetched data as needed
                    $no_of_officers = mysqli_num_rows($result);
                    echo  "Total Found : " . $no_of_officers . "<br>"; ?></h3></div>
                <div class="card-body bg-white">
                    <div class="row">
                        <!--1st col-->
                        <div class="col-sm-4 "> </div>
                        <!--2nd col-->
                        <div class="col-sm-4">
                            <?php
                            // Initialize the array to store emp_ids
                            $emp_ids = array();

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "SNR NO: " . $row['seniority_no'] . "<br>";
                                echo "EMP ID: " . $row['emp_id'] . "<br>";
                                // Store emp_id in the session for later use
                                $emp_ids[] = $row['emp_id'];
                                // echo "Name: " . $row['name'] . "<br>";
                                // echo "Designation: " . $row['designation'] . "<br>";
                                // echo "Section: " . $row['section'] . "<br>";
                                // echo "Cadre: " . $row['cadre'] . "<br>";
                                // echo "Sub Cadre: " . $row['sub_cadre'] . "<br>";
                                // echo "Grade: " . $row['grade'] . "<br>";
                                // echo "Place of Posting: " . $row['place_of_posting'] . "<br>";
                                echo "<br>";
                            }

                            // Store emp_ids in the session
                            $_SESSION['emp_ids'] = $emp_ids;
                            ?>
                        </div>
                        <!-- Add a print button -->
                        <div class="col-sm-4">
                            <center>
                                <a href="#" id="printButton" class="btn btn-danger text-white">
                                    <i class="fa fa-print" style="color:white"></i> Print
                                </a>
                                <a href="cadre_sub-cadre_grade_office_wise.php" class="btn btn-primary text-white">
                                    <i class="fa fa-left-arrow" style="color:white"></i> Previous Page
                                </a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}

?>
<?php include('../includes/footer.php'); ?>

<script>

    document.getElementById("printButton").addEventListener("click", function() {
        // Check if $_POST['biodata_gradation'] is set and not empty
        let biodata_gradation = <?php echo isset($_POST['biodata_gradation']) ? json_encode($_POST['biodata_gradation']) : 'null'; ?>;

        // Call the appropriate printContent function based on the value
        if (biodata_gradation === 'Bio-data') {
            printContent("cadre_sub-cadre_grade_office_wise_info_print.php", " ");
        } else {
            printContent("cadre_sub-cadre_grade_office_gradation_list_print.php", " ");
        }
    });

    function printContent(url, title) {
        let printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');

        let emp_ids = <?php echo isset($_SESSION['emp_ids']) ? json_encode($_SESSION['emp_ids']) : 'null'; ?>;
        let place_of_postings = <?php echo isset($_SESSION['place_of_postings']) ? json_encode($_SESSION['place_of_postings'][0]) : 'null'; ?>;

        var xhr = new XMLHttpRequest();
        var urlToFetch = url + "?emp_ids=" + emp_ids.join(',') + "&place_of_postings=" + place_of_postings;
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