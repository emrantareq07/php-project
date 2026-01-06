<?php
session_name('factory_work_request_db');

require_once '../db/config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}
$role = $_SESSION['role'];
$isAdmin = in_array($role, ['admin', 'sadmin']);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container mt-4">
<h5>Section Management</h5>
<a href="settings.php" class="btn btn-primary float-end"><i class="fas fa-arrow-left"></i>Back</a>
<?php if ($isAdmin): ?>
<div class="row">
    <div class="col-md-5">
        <label>Division</label>
        <select id="division_id" class="form-control">
            <option value="">Select Division</option>
            <?php
            $res = mysqli_query($conn, "SELECT id, division FROM division");
            while ($d = mysqli_fetch_assoc($res)) {
                echo "<option value='{$d['id']}'>{$d['division']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-5">
        <label>Section Name</label>
        <input type="text" id="section_name" class="form-control">

    </div>

    <div class="col-md-2 d-grid">
        <label>&nbsp;</label>
        <button id="saveSection" class="btn btn-primary">Save</button>
    </div>

</div>
<?php endif; ?>

<hr>

<table class="table table-bordered">
<thead>
<tr>
    <th>SL</th>
    <th>Division</th>
    <th>Section</th>
    <?php if ($isAdmin): ?><th>Action</th><?php endif; ?>
</tr>
</thead>
<tbody id="sectionTable"></tbody>
</table>

</div>

<script>
function loadSections() {
    $.get('ajax/section_list.php', function(data) {
        $('#sectionTable').html(data);
    });
}
loadSections();

$('#saveSection').click(function () {
    let division = $('#division_id').val();
    let name = $('#section_name').val();

    if (!division || !name) {
        alert('All fields required');
        return;
    }

    $.post('ajax/section_save.php', {
        division_id: division,
        name: name
    }, function(res) {
        alert(res);
        $('#section_name').val('');
        loadSections();
    });
});

$(document).on('click', '.delete', function () {
    if (!confirm('Delete this section?')) return;

    $.post('ajax/section_delete.php', {
        id: $(this).data('id')
    }, function(res) {
        alert(res);
        loadSections();
    });
});

$(document).on('click', '.edit', function () {
    let id = $(this).data('id');
    let name = prompt('Edit Section Name:');

    if (name) {
        $.post('ajax/section_update.php', {
            id: id,
            name: name
        }, function(res) {
            alert(res);
            loadSections();
        });
    }
});
</script>
</body>
</html>
