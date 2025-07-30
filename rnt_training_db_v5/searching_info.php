<?php
session_name('rnt_training_db');
session_start();
$username=$_SESSION['username']; //chairman

$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
// error_reporting(0);

include('db/db.php');
include('includes/header.php');

if (isset($_POST['submit'])) {

$conditions = [];

$training_do_or_not = mysqli_real_escape_string($conn, $_POST['training_do_or_not']);
//$training_title = mysqli_real_escape_string($conn, $_POST['training_title']);


if ($training_do_or_not === 'do') {
// Filter by training title
if (!empty($_POST['training_title'])) {
    $training_title = mysqli_real_escape_string($conn, $_POST['training_title']);
    $conditions[] = "o.training_title = '$training_title'";
    }
    if (!empty($_POST['training_type'])) {
    $training_type = mysqli_real_escape_string($conn, $_POST['training_type']);
    $conditions[] = "o.training_type = '$training_type'";
}
}
else{
  // Filter by training title
if (!empty($_POST['training_title'])) {
    $training_title = mysqli_real_escape_string($conn, $_POST['training_title']);
    $conditions[] = "o.training_title != '$training_title'";
    }  
    if (!empty($_POST['training_type'])) {
    $training_type = mysqli_real_escape_string($conn, $_POST['training_type']);
    $conditions[] = "o.training_type != '$training_type'";
}
}

// Filter by training type


// Filter by place of posting
if (!empty($_POST['place_of_posting'])) {
    $place_of_posting = mysqli_real_escape_string($conn, $_POST['place_of_posting']);
    if ($place_of_posting !== 'All') {
        $conditions[] = "e.place_of_posting = '$place_of_posting'";
    }
}

// Filter by designation
if (!empty($_POST['designation'])) {
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $conditions[] = "e.designation = '$designation'";
}

// Combine conditions into a WHERE clause
$where_clause = !empty($conditions) ? "WHERE " . implode(' AND ', $conditions) : "";

if ($training_do_or_not === 'do') {
// Construct the query
$sql = "
SELECT 
    e.emp_id,
    e.name,
    e.designation,
    e.place_of_posting,
    o.training_type,
    o.training_title,
    COALESCE(o.start_date, 'N/A') AS start_date,
    COALESCE(o.end_date, 'N/A') AS end_date,
    o.order_attachment,
    o.t_institute 
FROM 
    employees e
LEFT JOIN 
    office_order o
ON 
    e.ref_id = o.id
$where_clause

";
}
else {
    // Construct the query
$sql = "
SELECT distinct
    e.emp_id,
    e.name,
    e.designation,
    e.place_of_posting,
    o.training_type,
    o.training_title,
    COALESCE(o.start_date, 'N/A') AS start_date,
    COALESCE(o.end_date, 'N/A') AS end_date,
    o.order_attachment,
    o.t_institute 
FROM 
    employees e
LEFT JOIN 
    office_order o
ON 
    e.ref_id = o.id
$where_clause

";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
        // echo '<div class="container mt-5">
        //     <div class="alert alert-warning text-center" role="alert">
        //         No records found for the selected criteria.
        //     </div>
        // </div>';

        //echo "test";
        // No records found modal
        echo '<div class="modal" id="noRecordsModal" tabindex="-1" aria-labelledby="noRecordsModalLabel" aria-hidden="true">';
        echo '  <div class="modal-dialog">';
        echo '    <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '        <h5 class="modal-title" id="noRecordsModalLabel">No Records Found</h5>';
        echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '        <p>No records were found for the selected criteria.</p>';
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
        echo '    window.location.href = "multi_searching.php";';
        echo '  });';
        echo '});';
        echo '</script>';
    } else {
        // Records found
        $sl_no = 1; // Initialize SL No
        ?>
        <div class="container-fluid mt-1">
            <div class="card shadow-lg">
                <div class="card-header bg-primary">
                    <h3 class="page-header text-white text-uppercase text-center">
                        <?php
                        $no_of_officers = mysqli_num_rows($result);
                        echo "Total Found: " . $no_of_officers;
                        ?>
                    </h3>
                    <div class="text-end">
                        <a href="#" id="printButton" class="btn btn-danger text-white">
                            <i class="fa fa-print"></i> Print
                        </a>
                        <a href="multi_searching.php?username=<?php echo urlencode($_SESSION['username']); ?>" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Previous Page
                        </a>
                    </div>
                </div>

                </div>
                <div class="card-body bg-white">
                    <table id="employeeTable" class="table table-striped table-hover">
                        <thead>
                            <tr class="table-success">
                                <th>SL No.</th>
                                <th>EMP ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Place of Posting</th>
                                <th>Training Type</th>
                                <th>Training Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Institute</th>
                                <th>Attachment</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sl_no++ . "</td>";
                                echo "<td>" . $row['emp_id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['designation'] . "</td>";
                                echo "<td>" . $row['place_of_posting'] . "</td>";
                                echo "<td>" . $row['training_type'] . "</td>";
                                echo "<td>" . $row['training_title'] . "</td>";
                                echo "<td>" . ($row['start_date'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($row['end_date'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($row['t_institute'] ?? 'N/A') . "</td>";
                                if (!empty($row['order_attachment'])) {
                                    echo "<td><a href='uploads/" . $row['order_attachment'] . "' target='_blank'>View</a></td>";
                                } else {
                                    echo "<td>No Attachment</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <a href="#" id="printButton" class="btn btn-danger text-white">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <a href="multi_searching.php?username=<?php echo urlencode($_SESSION['username']); ?>" class="btn btn-primary text-white">
                        <i class="fa fa-arrow-left"></i> Previous Page
                    </a>
                </div>
                <br>
            </div>
        </div>
        <?php
    }
}

?>
<?php include('includes/footer.php'); ?>

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