<?php
session_name('ict_main_records_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once('../db/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee DataTable</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
    </style>
</head>
<body>

<h1 class="text-center fw-bold text-muted text-uppercase">Employee Records</h1>
<a href="../dashboard.php" class="btn btn-info"><i class="fa fa-arrow-left"></i> Previous Page</a>
<!-- Add New Employee Button -->
<button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus"></i> Add New Employee</button>

<table id="employeeTable" class="display table table-striped" style="width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>EMP ID</th>
            <th>User Name</th>
            <th>Designation</th>
            <th>Place of Posting</th>
            <th>Division/Dept</th>
            <th>Mobile/PABX</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>

<!-- Add Employee Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addForm">
            <div class="mb-3">
                <label for="add_emp_id" class="form-label">EMP ID</label>
                <input type="text" class="form-control" name="emp_id" id="add_emp_id" required>
            </div>
            <div class="mb-3">
                <label for="add_user_name" class="form-label">User Name</label>
                <input type="text" class="form-control" name="user_name" id="add_user_name" required>
            </div>
            <div class="mb-3">
                <label for="add_designation" class="form-label">Designation</label>
                <!-- <input type="text" class="form-control" name="designation" id="add_designation"> -->
                <input list="designations" 
                       id="designation" 
                       name="designation" 
                       class="form-control" placeholder="Select Designation"
                       required>
                <datalist id="designations">
                    <?php
                        $sql = "SELECT designation FROM designation ORDER BY designation ASC";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . htmlspecialchars($row['designation'], ENT_QUOTES, 'UTF-8') . "'>";
                        }
                    ?>
                </datalist>

            </div>
            <div class="mb-3">
                <label for="add_place_of_posting" class="form-label">Place of Posting</label>
                <input type="text" class="form-control" name="place_of_posting" id="add_place_of_posting" value="BCIC H.O." readonly>
            </div>
            <div class="mb-3">
                <label for="add_division_dept" class="form-label">Division/Dept</label>
                <!-- <input type="text" class="form-control" name="division_dept" id="add_division_dept"> -->
                <input list="division_depts" 
                       id="division_dept" 
                       name="division_dept" 
                       class="form-control" placeholder="Select Division/Dept"
                       required>
                <datalist id="division_depts">
                    <?php
                        $sql = "SELECT division FROM division ORDER BY division ASC";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . htmlspecialchars($row['division'], ENT_QUOTES, 'UTF-8') . "'>";
                        }
                    ?>
                </datalist>
            </div>
            <div class="mb-3">
                <label for="add_mobile_pabx" class="form-label">Mobile/PABX</label>
                <input type="text" class="form-control" name="mobile_pabx" id="add_mobile_pabx">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
                <label for="edit_emp_id" class="form-label">EMP ID</label>
                <input type="text" class="form-control" name="emp_id" id="edit_emp_id" required>
            </div>
            <div class="mb-3">
                <label for="edit_user_name" class="form-label">User Name</label>
                <input type="text" class="form-control" name="user_name" id="edit_user_name" required>
            </div>
            <div class="mb-3">
                <label for="edit_designation" class="form-label">Designation</label>
                <!-- <input type="text" class="form-control" name="designation" id="edit_designation"> -->
                 <input list="designations" 
                       id="edit_designation" 
                       name="designation" 
                       class="form-control" placeholder="Select Designation"
                       required>
                <datalist id="designations">
                    <?php
                        $sql = "SELECT designation FROM designation ORDER BY designation ASC";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . htmlspecialchars($row['designation'], ENT_QUOTES, 'UTF-8') . "'>";
                        }
                    ?>
                </datalist>
            </div>
            <div class="mb-3">
                <label for="edit_place_of_posting" class="form-label">Place of Posting</label>
                <input type="text" class="form-control" name="place_of_posting" id="edit_place_of_posting">
            </div>
            <div class="mb-3">
                <label for="edit_division_dept" class="form-label">Division/Dept</label>
                <!-- <input type="text" class="form-control" name="division_dept" id="edit_division_dept"> -->
                <input list="division_depts" 
                       id="edit_division_dept" 
                       name="division_dept" 
                       class="form-control" placeholder="Select Division/Dept"
                       required>
                <datalist id="division_depts">
                    <?php
                        $sql = "SELECT division FROM division ORDER BY division ASC";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . htmlspecialchars($row['division'], ENT_QUOTES, 'UTF-8') . "'>";
                        }
                    ?>
                </datalist>
            </div>
            <div class="mb-3">
                <label for="edit_mobile_pabx" class="form-label">Mobile/PABX</label>
                <input type="text" class="form-control" name="mobile_pabx" id="edit_mobile_pabx">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    var table = $('#employeeTable').DataTable({
        ajax: 'fetch_emp_records.php',
        columns: [
            { data: 'id' },
            { data: 'emp_id' },
            { data: 'user_name' },
            { data: 'designation' },
            { data: 'place_of_posting' },
            { data: 'division_dept' },
            { data: 'mobile_pabx' },
            {
                data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-warning" onclick="editRecord(${row.id})"><i class="fa fa-edit"></i> Edit</button>`;
                }
            }
        ]
    });

    // Handle Add Form Submission
    $('#addForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'add_emp_record.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                $('#addModal').modal('hide');
                $('#addForm')[0].reset();
                table.ajax.reload();
            }
        });
    });

    // Handle Edit Form Submission
    $('#editForm').submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'update_emp_record.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                $('#editModal').modal('hide');
                table.ajax.reload();
            }
        });
    });
});

function editRecord(id) {
    $.get('fetch_emp_records.php', function (data) {
        const record = data.data.find(item => item.id == id);
        if (record) {
            $('#edit_id').val(record.id);
            $('#edit_emp_id').val(record.emp_id);
            $('#edit_user_name').val(record.user_name);
            $('#edit_designation').val(record.designation);
            $('#edit_place_of_posting').val(record.place_of_posting);
            $('#edit_division_dept').val(record.division_dept);
            $('#edit_mobile_pabx').val(record.mobile_pabx);
            $('#editModal').modal('show');
        }
    }, 'json');
}
</script>

</body>
</html>
