<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

// Check if user is logged in
if(!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// User is logged in, proceed with the page
include_once(ROOT_PATH.'/libs/function.php');
$usercredentials = new DB_con();

// Fetch username from either session or cookies
$uname = $uun = $uup = "";
if (isset($_SESSION["uname"])) {
    $uname = $_SESSION['uname'];
} elseif (isset($_COOKIE['user_login'])) {
    $uname = $_COOKIE['user_login'];
}

// Fetch user data from database
$query = "SELECT * FROM tblusers WHERE Username='$uname'";
$result = $usercredentials->runBaseQuery($query);
foreach ($result as $k => $v) {
    $uun = $result[$k]['Username'];
    $uup = $result[$k]['Password'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Info Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
</head>
<body>
<div class="container mt-2 rounded border">
    <h3 class="text-center">Teacher Info Management</h3>
    <button class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addModal">Add Teacher</button>
    <a href="../dashboard.php" class="btn btn-secondary float-end">
                <i class="fas fa-arrow-left"></i> Back
            </a>
    <br>
    <table id="teacherTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Photo</th><th>Name</th><th>Designation</th><th>DOB</th><th>Joining</th><th>Mobile</th><th>Email</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM teacher_info ORDER BY id DESC");
            while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Teacher Image" width="100" class="rounded">
                    <?php else: ?>
                        <span class="text-muted">No Image</span>
                    <?php endif; ?>
                </td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['designation'] ?></td>
                <td><?= $row['dob'] ?></td>
                <td><?= $row['joining_date'] ?></td>
                <td><?= $row['mobile'] ?></td>
                <td><?= $row['email'] ?></td>
                <td>
                    <button class="btn btn-sm btn-warning editBtn" data-id="<?= $row['id'] ?>">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $row['id'] ?>">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addForm" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-2">
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Designation</label>
                    <input type="text" name="designation" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>DOB</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Last Education Info</label>
                    <input type="text" name="last_edu_info" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>University</label>
                    <input type="text" name="university" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Mobile</label>
                    <input type="text" name="mobile" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Maritial Status</label>
                    <input type="text" name="maritial_status" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Religion</label>
                    <input type="text" name="religon" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Blood Group</label>
                    <input type="text" name="blood_group" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Address</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="col-md-6">
                    <label>NID</label>
                    <input type="text" name="nid" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editForm" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-2">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Designation</label>
                    <input type="text" name="designation" id="edit_designation" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>DOB</label>
                    <input type="date" name="dob" id="edit_dob" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" id="edit_joining_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Last Education Info</label>
                    <input type="text" name="last_edu_info" id="edit_last_edu_info" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>University</label>
                    <input type="text" name="university" id="edit_university" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Mobile</label>
                    <input type="text" name="mobile" id="edit_mobile" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Maritial Status</label>
                    <input type="text" name="maritial_status" id="edit_maritial_status" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Religion</label>
                    <input type="text" name="religon" id="edit_religon" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Blood Group</label>
                    <input type="text" name="blood_group" id="edit_blood_group" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Address</label>
                    <textarea name="address" id="edit_address" class="form-control"></textarea>
                </div>
                <div class="col-md-6">
                    <label>NID</label>
                    <input type="text" name="nid" id="edit_nid" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Change Image (optional)</label>
                    <input type="file" name="image" class="form-control">
                    <div id="current_image" class="mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function(){
    // Initialize DataTable
    $('#teacherTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print']
    });

    // Handle Add Form Submission
    $('#addForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: 'teacher_action.php',
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(res){
                alert(res);
                location.reload();
            }
        });
    });

    // Handle Delete Button Click
    $(document).on('click', '.deleteBtn', function(){
        if(confirm('Are you sure you want to delete this teacher?')){
            let id = $(this).data('id');
            $.post('teacher_action.php', {delete_id: id}, function(res){
                alert(res);
                location.reload();
            });
        }
    });

    // Handle Edit Button Click
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');
        $.post('teacher_action.php', {fetch_id: id}, function(data){
            let obj = JSON.parse(data);
            $('#edit_id').val(obj.id);
            $('#edit_name').val(obj.name);
            $('#edit_designation').val(obj.designation);
            $('#edit_dob').val(obj.dob);
            $('#edit_joining_date').val(obj.joining_date);
            $('#edit_last_edu_info').val(obj.last_edu_info);
            $('#edit_university').val(obj.university);
            $('#edit_mobile').val(obj.mobile);
            $('#edit_email').val(obj.email);
            $('#edit_maritial_status').val(obj.maritial_status);
            $('#edit_religon').val(obj.religon);
            $('#edit_blood_group').val(obj.blood_group);
            $('#edit_address').val(obj.address);
            $('#edit_nid').val(obj.nid);
            
            if(obj.image){
                $('#current_image').html(`<img src="uploads/${obj.image}" width="80" class="rounded">`);
            } else {
                $('#current_image').html(`<span class="text-muted">No Image</span>`);
            }
            $('#editModal').modal('show');
        });
    });

    // Handle Edit Form Submission
    $('#editForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: 'teacher_action.php',
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(res){
                alert(res);
                location.reload();
            }
        });
    });
});
</script>
</body>
</html>