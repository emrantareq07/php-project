<?php
session_name('factory_work_request_db');

require_once '../db/config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}


?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- ✅ JQUERY REQUIRED -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">
    <h5>Division Settings</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
        + Add Division
    </button>
    <a href="settings.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i>Back</a>
</div>

<table class="table table-bordered table-sm">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>Division</th>
    <th width="20%">Action</th>
</tr>
</thead>
<tbody>
<?php
$i = 1;
$res = mysqli_query($conn, "SELECT * FROM division ORDER BY division");
while ($row = mysqli_fetch_assoc($res)) {
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['division']) ?></td>
    <td>
        <button class="btn btn-sm btn-warning editDivision"
            data-id="<?= $row['id'] ?>"
            data-name="<?= htmlspecialchars($row['division']) ?>">
            Edit
        </button>
        <button class="btn btn-sm btn-danger deleteDivision"
            data-id="<?= $row['id'] ?>">
            Delete
        </button>
    </td>
</tr>
<?php } ?>
</tbody>
</table>

<!-- ADD MODAL -->
<div class="modal fade" id="addDivisionModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Add Division</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <input type="text" id="division_name" class="form-control" placeholder="Division name">
</div>
<div class="modal-footer">
    <button class="btn btn-success" id="saveDivision">Save</button>
</div>
</div>
</div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editDivisionModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Edit Division</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <input type="hidden" id="edit_id">
    <input type="text" id="edit_name" class="form-control">
</div>
<div class="modal-footer">
    <button class="btn btn-primary" id="updateDivision">Update</button>
</div>
</div>
</div>
</div>

</div>

<script>
$('#saveDivision').click(function () {
    let name = $('#division_name').val().trim();
    if (name === '') {
        alert('Division name required');
        return;
    }

    $.post('ajax/save_division.php', { name }, function () {
        location.reload();
    });
});

$('.editDivision').click(function () {
    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));
    $('#editDivisionModal').modal('show');
});

$('#updateDivision').click(function () {
    $.post('ajax/update_division.php', {
        id: $('#edit_id').val(),
        name: $('#edit_name').val()
    }, function () {
        location.reload();
    });
});

$('.deleteDivision').click(function () {
    if (!confirm('Delete this division?')) return;
    $.post('ajax/delete_division.php', {
        id: $(this).data('id')
    }, function () {
        location.reload();
    });
});
</script>

</body>
</html>
