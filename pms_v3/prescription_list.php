<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("No employee ID provided.");
    }
    $emp_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE emp_id = :emp_id");
    $stmt->execute(['emp_id' => $emp_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        die("Employee not found.");
    }

    $stmt = $conn->prepare("SELECT * FROM prescription_tbl WHERE emp_id = :emp_id ORDER BY date DESC, id DESC");
    $stmt->execute(['emp_id' => $emp_id]);
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM prescription_tbl WHERE id = :id");
    $stmt->execute(['id' => $delete_id]);

    header("Location: prescription_list.php?id=" . urlencode($emp_id));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription List - <?= htmlspecialchars($user['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Prescriptions for <?= htmlspecialchars($user['name']) ?></h4>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <table id="prescriptionTable" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Disease Name</th>
                <th>Medicine</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Advise</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($prescriptions as $index => $prescription): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($prescription['date']) ?></td>
                <td><?= htmlspecialchars($prescription['disease_name']) ?></td>
                <td><?= htmlspecialchars($prescription['medicine']) ?></td>
                <td><?= htmlspecialchars($prescription['quantity']) ?></td>
                <td><?= nl2br(htmlspecialchars($prescription['description'])) ?></td>
                <td><?= nl2br(htmlspecialchars($prescription['advise'])) ?></td>
                <td>
                    <a href="edit_prescription.php?id=<?= $prescription['id'] ?>" class="btn btn-sm btn-primary mb-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $prescription['id'] ?>">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this prescription?
      </div>
      <div class="modal-footer">
        <input type="hidden" name="delete_id" id="delete_id">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Yes, Delete</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
        $('#prescriptionTable').DataTable({
            pageLength: 10,
            order: [[1, 'desc']]
        });

        // Set delete id on modal show
        $('#deleteModal').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);
            var id = button.data('id');
            $('#delete_id').val(id);
        });
    });
</script>
</body>
</html>
