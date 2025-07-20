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
include('db/db.php');
include('includes/header.php');

?>
<div class="container-fluid">   
<div class="container-fluid mt-1 border p-2 rounded shadow">
    <h2 class="text-center m-0 fw-bold text-muted text-uppercase "> Employees Training Database</h2>
    <h6 class="text-center m-0 fw-bold text-muted text-uppercase "> (RNT, Personnel Division, BCIC.)</h6>
    
    <div class="d-flex justify-content-between align-items-start my-0">
        <!-- Left-aligned buttons -->
        <div>
            <a href="includes/add_designation.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Designation</a>
            <a href="includes/add_training_title.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Training Title</a>
            <a href="includes/add_office_or_factory.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Office</a>
            <a href="add_office_order.php" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add Office Order</a>
        </div>

        <!-- Right-aligned buttons -->
        <div class="d-flex justify-content-end align-items-center gap-1 mb-3">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#employeeModal">
                <i class="fa fa-plus"></i> Add Training Info.
            </button>
            <a href="multi_searching.php" class="btn btn-primary">
                <i class="fa fa-search"></i> Multi-Search
            </a>
            <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                <button class="btn btn-warning" type="submit" name="submit">
                    <i class="fa fa-download"></i> Download DB
                </button>
            </form>
            <a href="includes/logout.php" class="btn btn-danger">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </div>

    </div>

    <table id="employeeTable" class="table table-striped table-hover">
    <thead>
        <tr class="table-primary">
            <th>SL No.</th>
            <th>EMP ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Place of Posting</th>
            <th>Training Type</th>
            <th>Training Title</th> <!-- Add this line -->
            <th>Ref. No.</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Institute</th>
            <th>Attachment</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
       
    </tbody>
</table>
</div>
</div>
<!-- Modal for Add/Edit -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center text-uppercase fw-bold" id="employeeModalLabel">Add Employees Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="emp_id" class="form-label">EMP ID</label>
                            <input list="emp_ids"
                                type="text" 
                                class="form-control" 
                                id="emp_id" 
                                name="emp_id" 
                                placeholder="Enter EMP ID" 
                                pattern="[0-9\-]+" 
                                title="EMP ID must contain only digits and the '-' symbol." 
                                required
                                onchange="fetchEmployeeDetails(this.value)">
                        <datalist id="emp_ids">
                            <?php
                                $sqlemp_id = "SELECT emp_id FROM employees ";
                                $resultemp_id = mysqli_query($conn, $sqlemp_id);
                                while ($rowemp_id = mysqli_fetch_array($resultemp_id)) {
                                    echo "<option value='" . $rowemp_id['emp_id'] . "'>";
                                }
                            ?>
                        </datalist>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="name" 
                                name="name" 
                                placeholder="Enter full name" 
                                required>
                        </div>
                    </div>

                    <div class="row g-2">


                        <div class="col-md-6 mb-3">
                        <label for="designation" class="form-label">Designation</label>
                        <input list="designations" id="designation" name="designation" class="form-control" required>
                        <datalist id="designations">
                            <?php
                                $sql = "SELECT * FROM designation ORDER BY designation ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $row['designation'] . "'>";
                                }
                            ?>
                        </datalist>
                    </div>

                        <div class="col-md-6 mb-3">
                            <label for="place_of_posting" class="form-label">Place of Posting</label>
                            <select class="form-select" id="place_of_posting" name="place_of_posting" aria-label="Floating label select example" required>
                                <option value="" disabled selected>--Select--</option>
                                <?php
                                  $sql = "SELECT * FROM place_of_posting ORDER BY place_of_posting ASC";
                                  $result = mysqli_query($conn, $sql);
                                  while ($row = mysqli_fetch_array($result)) {
                                      echo "<option value='" . $row['place_of_posting'] . "'>" . $row['place_of_posting'] . "</option>";
                                  }
                                ?>
                            </select>
                        </div>
                    </div>


<!-- <script>
function fetchEmployeeDetails(empId) {
    if (empId.trim() === "") return;

    $.ajax({
        url: "fetch_employee.php", // PHP file to handle the request
        type: "POST",
        data: { emp_id: empId },
        success: function(response) {
            // Parse the JSON response from PHP
            const data = JSON.parse(response);

            if (data.success) {
                // Update fields with the fetched data
                $('#name').val(data.name);
                $('#designation').val(data.designation);
                $('#place_of_posting').val(data.place_of_posting);
            } else {
                // Clear the fields if no record is found
                $('#name').val("");
                $('#designation').val("");
                $('#place_of_posting').val("");
                alert("No employee found with the given EMP ID.");
            }
        },
        error: function() {
            alert("An error occurred while fetching employee details.");
        }
    });
}
</script> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Trigger AJAX request when the EMP ID field value changes
    $('#emp_id').on('change', function () {
        const empId = $(this).val().trim();

        // Do not proceed if the input is empty
        if (empId === "") {
            $('#name').val("");
            $('#designation').val("");
            $('#place_of_posting').val("");
            return;
        }

        // AJAX request to fetch employee details based on EMP ID
        $.ajax({
            url: "fetch_employee.php", // PHP file to handle the request
            type: "POST",
            data: { emp_id: empId },
            success: function (response) {
                // Parse the JSON response from PHP
                const data = JSON.parse(response);

                if (data.success) {
                    // Update fields with the fetched data
                    $('#name').val(data.name);
                    $('#designation').val(data.designation);
                    $('#place_of_posting').val(data.place_of_posting);
                } else {
                    // Clear the fields if no record is found
                    $('#name').val("");
                    $('#designation').val("");
                    $('#place_of_posting').val("");
                }
            },
            error: function () {
                alert("An error occurred while fetching employee details.");
            }
        });
    });

        // Trigger AJAX request when the Reference No. field value changes
        $('#ref_no').on('change', function () {
            const refNo = $(this).val().trim();
            // Do not proceed if the input is empty
            if (refNo === "") {
                $('#start_date').val("");
                $('#end_date').val("");
                $('#training_type').val("");
                $('#training_title').val("");
                $('#t_institute').val("");
                return;
            }

            // AJAX request to fetch office order details based on Reference No.
            $.ajax({
                url: "fetch_employee.php", // PHP file to handle the request
                type: "POST",
                data: { ref_no: refNo },
                success: function (response) {
                    // Parse the JSON response from PHP
                    const data = JSON.parse(response);

                    if (data.success) {
                        // Update fields with the fetched data
                        $('#start_date').val(data.start_date);
                        $('#end_date').val(data.end_date);
                         $('#training_type').val(data.training_type);
                           $('#training_title').val(data.training_title);
                             $('#t_institute').val(data.t_institute);
                    } else {
                        // Clear the fields if no record is found
                        $('#start_date').val("");
                        $('#end_date').val("");
                        $('#training_type').val("");
                         $('#training_title').val("");
                          $('#t_institute').val("");
                    }
                },
                error: function () {
                    alert("An error occurred while fetching office order details.");
                    }
                });
            });
        });
        </script>
                 <div class="mb-3">
                    <label for="training_title" class="form-label">Reference No.</label>
                    <!-- <input type="text" class="form-control" id="training_title" name="training_title"> -->
                     <select class="form-select" id="ref_no" name="ref_no" aria-label="Floating label select example" readonly >
                      <option value="" disabled selected>--Select--</option>
                      <?php
                        $sql = "SELECT ref_no FROM office_order ORDER by id desc";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_array($result)) {
                          echo "<option value='".$row['ref_no']."'>".$row['ref_no']."</option>";
                        }
                        ?>   
                    </select>
                </div>
                <div class="mb-3">
                        <label for="training_type" class="form-label">Training Type</label>
                        <input type="text" class="form-control" id="training_type" name="training_type" readonly>   
                    </div>
                <div class="mb-3">
                        <label for="training_title" class="form-label">Title/Subject</label>
                        <input type="text" class="form-control" id="training_title" name="training_title" readonly>             
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="t_institute" class="form-label">Institute</label>
                        <input type="text" class="form-control" id="t_institute" name="t_institute" readonly>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="script.js"></script>
</body>
</html>
