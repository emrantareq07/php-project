<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$role = $_SESSION['role'];
$isAdmin = in_array($role, ['admin','sadmin']);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<div class="container mt-4">

<h5>Designation Management</h5>
<a href="settings.php" class="btn btn-primary float-end"><i class="fas fa-arrow-left"></i>Back</a>
<?php if ($isAdmin): ?>
<div class="row mb-3">
    <div class="col-md-8">
        <input type="text" id="designation" class="form-control" placeholder="Enter Designation">
    </div>
    <div class="col-md-4 d-grid">
        <button id="saveDesignation" class="btn btn-primary">Save</button>
    </div>
</div>
<?php endif; ?>

<table class="table table-bordered">
<thead>
<tr>
    <th width="60">SL</th>
    <th>Designation</th>
    <?php if ($isAdmin): ?><th width="150">Action</th><?php endif; ?>
</tr>
</thead>
<tbody id="designationTable"></tbody>
</table>

</div>

<script>
function loadDesignation() {
    $.get('ajax/designation_list.php', function(data) {
        $('#designationTable').html(data);
    });
}
loadDesignation();

$('#saveDesignation').click(function () {
    let designation = $('#designation').val().trim();

    if (designation === '') {
        alert('Designation required');
        return;
    }

    $.post('ajax/designation_save.php', { designation }, function(res) {
        alert(res);
        $('#designation').val('');
        loadDesignation();
    });
});

$(document).on('click', '.delete', function () {
    if (!confirm('Delete this designation?')) return;

    $.post('ajax/designation_delete.php', {
        id: $(this).data('id')
    }, function(res) {
        alert(res);
        loadDesignation();
    });
});

$(document).on('click', '.edit', function () {
    let id = $(this).data('id');
    let name = prompt('Edit Designation:');

    if (name) {
        $.post('ajax/designation_update.php', {
            id: id,
            designation: name
        }, function(res) {
            alert(res);
            loadDesignation();
        });
    }
});
</script>

</body>
</html>
