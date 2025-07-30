<?php
session_name('pms_db');
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$dbname = 'pms_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle Add
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO medicine_tbl (med_name, brand, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['med_name'], $_POST['brand'], $_POST['quantity']]);
    header("Location: medicine_mgtm.php");
    exit;
}

// Handle Edit
if (isset($_POST['edit'])) {
    $stmt = $conn->prepare("UPDATE medicine_tbl SET med_name=?, brand=?, quantity=? WHERE id=?");
    $stmt->execute([$_POST['med_name'], $_POST['brand'], $_POST['quantity'], $_POST['id']]);
    header("Location: medicine_mgtm.php");
    exit;
}

// Handle Delete
if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM medicine_tbl WHERE id=?");
    $stmt->execute([$_POST['id']]);
    header("Location: medicine_mgtm.php");
    exit;
}

// Fetch medicines
$medicines = $conn->query("SELECT * FROM medicine_tbl ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4">Medicine Management</h2>

    <!-- Add Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Medicine</button>
<a href="../dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
    <!-- Table -->
    <table id="medicineTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($medicines as $medicine): ?>
            <tr>
                <td><?= $medicine['id'] ?></td>
                <td><?= htmlspecialchars($medicine['med_name']) ?></td>
                <td><?= htmlspecialchars($medicine['brand']) ?></td>
                <td><?= htmlspecialchars($medicine['quantity']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning editBtn" 
                            data-id="<?= $medicine['id'] ?>"
                            data-med_name="<?= htmlspecialchars($medicine['med_name']) ?>"
                            data-brand="<?= htmlspecialchars($medicine['brand']) ?>"
                            data-quantity="<?= htmlspecialchars($medicine['quantity']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn"
                            data-id="<?= $medicine['id'] ?>"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input name="med_name" class="form-control mb-2" placeholder="Medicine Name" required>
                <input name="brand" class="form-control mb-2" placeholder="Brand" required>
                <input name="quantity" type="number" class="form-control mb-2" placeholder="Quantity" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" name="add">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editId">
                <input name="med_name" id="editName" class="form-control mb-2" placeholder="Medicine Name" required>
                <input name="brand" id="editBrand" class="form-control mb-2" placeholder="Brand" required>
                <input name="quantity" id="editQuantity" type="number" class="form-control mb-2" placeholder="Quantity" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" name="edit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this medicine?
                <input type="hidden" name="id" id="deleteId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" name="delete">Delete</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#medicineTable').DataTable();

    $('.editBtn').click(function() {
        $('#editId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
        $('#editBrand').val($(this).data('brand'));
        $('#editQuantity').val($(this).data('quantity'));
    });

    $('.deleteBtn').click(function() {
        $('#deleteId').val($(this).data('id'));
    });
});
</script>

</body>
</html>
