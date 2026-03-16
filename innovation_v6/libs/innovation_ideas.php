<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

$success_message = '';
$error_message = '';

$today = date("Y-m-d");

/* ===============================
   AUTO STATUS UPDATE
=================================*/

// Expired ideas → inactive
mysqli_query($conn, "
    UPDATE tbl_innovation_idea
    SET idea_status = 'inactive'
    WHERE end_date IS NOT NULL
    AND end_date < '$today'
");

// Running ideas → active
mysqli_query($conn, "
    UPDATE tbl_innovation_idea
    SET idea_status = 'active'
    WHERE start_date IS NOT NULL
    AND end_date IS NOT NULL
    AND '$today' BETWEEN start_date AND end_date
");


/* ===============================
   ADD RECORD
=================================*/
if (isset($_POST['add_record'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $fiscal_year = mysqli_real_escape_string($conn, $_POST['fiscal_year']);
    $start_date = !empty($_POST['start_date']) ? "'" . $_POST['start_date'] . "'" : "NULL";
    $end_date = !empty($_POST['end_date']) ? "'" . $_POST['end_date'] . "'" : "NULL";
    $idea_status = mysqli_real_escape_string($conn, $_POST['idea_status']);

    $query = "INSERT INTO tbl_innovation_idea 
              (title, fiscal_year, start_date, end_date, idea_status) 
              VALUES ('$title', '$fiscal_year', $start_date, $end_date, '$idea_status')";

    if (mysqli_query($conn, $query)) {
        $success_message = "Record added successfully!";
    } else {
        $error_message = mysqli_error($conn);
    }
}


/* ===============================
   EDIT FETCH
=================================*/
if (isset($_POST['action']) && $_POST['action'] == 'edit') {

    $id = intval($_POST['id']);
    $result = mysqli_query($conn, "SELECT * FROM tbl_innovation_idea WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($result));
    exit;
}


/* ===============================
   UPDATE
=================================*/
if (isset($_POST['action']) && $_POST['action'] == 'update') {

    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $fiscal_year = mysqli_real_escape_string($conn, $_POST['fiscal_year']);
    $start_date = !empty($_POST['start_date']) ? "'" . $_POST['start_date'] . "'" : "NULL";
    $end_date = !empty($_POST['end_date']) ? "'" . $_POST['end_date'] . "'" : "NULL";
    $idea_status = mysqli_real_escape_string($conn, $_POST['idea_status']);

    $query = "UPDATE tbl_innovation_idea SET
                title = '$title',
                fiscal_year = '$fiscal_year',
                start_date = $start_date,
                end_date = $end_date,
                idea_status = '$idea_status',
                updated_at = NOW()
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','message'=>mysqli_error($conn)]);
    }
    exit;
}


/* ===============================
   DELETE
=================================*/
if (isset($_POST['action']) && $_POST['action'] == 'delete') {

    $id = intval($_POST['id']);

    if (mysqli_query($conn, "DELETE FROM tbl_innovation_idea WHERE id = $id")) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error']);
    }
    exit;
}


/* ===============================
   FETCH ALL
=================================*/
$records = mysqli_query($conn, "SELECT * FROM tbl_innovation_idea ORDER BY id DESC");

/* ===============================
   Fiscal Year Dropdown
=================================*/
$fiscal_years = mysqli_query($conn, "SELECT * FROM fiscal_year ORDER BY id DESC");
$default_fy = ['২০২৪-২০২৫','২০২৩-২০২৪','২০২২-২০২৩','২০২১-২০২২','২০২০-২০২১'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Innovation Ideas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light p-4">

<div class="container">
<div class="card p-4 shadow">

<h4>Innovation Ideas</h4>

<?php if($success_message): ?>
<div class="alert alert-success"><?= $success_message ?></div>
<?php endif; ?>

<?php if($error_message): ?>
<div class="alert alert-danger"><?= $error_message ?></div>
<?php endif; ?>

<button class="btn btn-primary mb-3" onclick="openAdd()">Add New</button>

<a href="../dashboard.php" class=" btn btn-primary" >
                      <i class="fas fa-info-circle"></i> Back
                      
                  </a>
<table class="table table-bordered">
<thead>
<tr>
<th>ID</th>
<th>Title</th>
<th>Fiscal Year</th>
<th>Start</th>
<th>End</th>
<th>Status</th>
<th>Created</th>
<th>Updated</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($records)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= $row['fiscal_year'] ?></td>
<td><?= $row['start_date'] ?></td>
<td><?= $row['end_date'] ?></td>
<td>
<?php if($row['idea_status'] == 'active'): ?>
<span class="badge bg-success">Active</span>
<?php else: ?>
<span class="badge bg-danger">Inactive</span>
<?php endif; ?>
</td>
<td><?= $row['created_at'] ?></td>
<td><?= $row['updated_at'] ?></td>
<td>
<button class="btn btn-sm btn-warning" onclick="editRecord(<?= $row['id'] ?>)">Edit</button>
<button class="btn btn-sm btn-danger" onclick="deleteRecord(<?= $row['id'] ?>)">Delete</button>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="ideaModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Idea Form</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST" id="ideaForm">

<input type="hidden" id="id">

<div class="mb-2">
<label>Title</label>
<input type="text" name="title" id="title" class="form-control" required>
</div>

<div class="mb-2">
<label>Fiscal Year</label>
<select class="form-select" name="fiscal_year" id="fiscal_year" required>
<option value="">Select Fiscal Year</option>
<?php 
if($fiscal_years && mysqli_num_rows($fiscal_years) > 0){
    while($fy = mysqli_fetch_assoc($fiscal_years)){
        echo "<option value='{$fy['fiscal_year']}'>{$fy['fiscal_year']}</option>";
    }
}else{
    foreach($default_fy as $fy){
        echo "<option value='$fy'>$fy</option>";
    }
}
?>
</select>
</div>

<div class="mb-2">
<label>Start Date</label>
<input type="date" name="start_date" id="start_date" class="form-control">
</div>

<div class="mb-2">
<label>End Date</label>
<input type="date" name="end_date" id="end_date" class="form-control">
</div>

<div class="mb-2">
<label>Status</label>
<select class="form-control" name="idea_status" id="idea_status" required>
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
</div>

<button type="submit" name="add_record" id="saveBtn" class="btn btn-success">Save</button>

</form>

</div>
</div>
</div>
</div>

<script>

function openAdd(){
    $('#ideaForm')[0].reset();
    $('#id').val('');
    $('#saveBtn').attr('name','add_record').text('Save');
    $('#ideaModal').modal('show');
}

function editRecord(id){
    $.post('', {action:'edit', id:id}, function(data){
        let obj = JSON.parse(data);
        $('#id').val(obj.id);
        $('#title').val(obj.title);
        $('#fiscal_year').val(obj.fiscal_year);
        $('#start_date').val(obj.start_date);
        $('#end_date').val(obj.end_date);
        $('#idea_status').val(obj.idea_status);
        $('#saveBtn').removeAttr('name').text('Update');
        $('#ideaModal').modal('show');
    });
}

$('#ideaForm').submit(function(e){

    if($('#saveBtn').attr('name') === 'add_record'){
        return true;
    }

    e.preventDefault();

    $.post('', {
        action:'update',
        id:$('#id').val(),
        title:$('#title').val(),
        fiscal_year:$('#fiscal_year').val(),
        start_date:$('#start_date').val(),
        end_date:$('#end_date').val(),
        idea_status:$('#idea_status').val()
    }, function(){
        location.reload();
    });
});

function deleteRecord(id){
    if(confirm('Are you sure?')){
        $.post('', {action:'delete', id:id}, function(){
            location.reload();
        });
    }
}

</script>

</body>
</html>