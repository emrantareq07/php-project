<?php
session_name('training_certificate_gen_db');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Database configuration
$host = 'localhost';
$dbname = 'training_certificate_gen_db';
$username = 'root';
$password = '321';

// Initialize variables
$edit_data = [];
$edit_id = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['id'])) {
            updateRecord($pdo);
        } else {
            insertRecord($pdo);
        }
    }

    // Handle delete
    if (isset($_GET['delete'])) {
        deleteRecord($pdo, $_GET['delete']);
    }

    // Handle edit
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_data = getRecord($pdo, $edit_id);
    }

    // Fetch all records
    $records = getAllRecords($pdo);

    // Determine next batch number
    $stmt = $pdo->query("SELECT MAX(batch) as max_batch FROM authority_tbl");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_batch = $row['max_batch'] ? $row['max_batch'] + 1 : 1;

} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Connection failed: " . $e->getMessage() . "</div>";
}

// Insert record
function insertRecord($pdo) {
    $sql = "INSERT INTO authority_tbl (batch, training_title,organized_by, start_date, end_date, name1, designation1, office1, ministry1, signature1, name2, designation2, office2, ministry2, signature2, active_status,tr_link_status, created_at) 
            VALUES (?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, NOW())";

    $stmt = $pdo->prepare($sql);

    $signature1 = uploadSignature('signature1');
    $signature2 = uploadSignature('signature2');

    // Determine next batch
    $batchStmt = $pdo->query("SELECT MAX(batch) as max_batch FROM authority_tbl");
    $batchRow = $batchStmt->fetch(PDO::FETCH_ASSOC);
    $batch = $batchRow['max_batch'] ? $batchRow['max_batch'] + 1 : 1;

    $stmt->execute([
        $batch,
        $_POST['training_title'],
        $_POST['organized_by'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['name1'],
        $_POST['designation1'],
        $_POST['office1'],
        $_POST['ministry1'],
        $signature1,
        $_POST['name2'],
        $_POST['designation2'],
        $_POST['office2'],
        $_POST['ministry2'],
        $signature2,
        $_POST['active_status'] ?? 'active',
        $_POST['tr_link_status'] ?? 'active'
    ]);

    header("Location: settings.php?success=added");
    exit();
}

// Update record
function updateRecord($pdo) {
    $sql = "UPDATE authority_tbl SET batch=?, training_title=?,organized_by=?, start_date=?, end_date=?, name1=?, designation1=?, office1=?, ministry1=?, name2=?, designation2=?, office2=?, ministry2=?, active_status=?,tr_link_status=?, updated_at=NOW()";
    $params = [
        $_POST['batch'],
        $_POST['training_title'],
        $_POST['organized_by'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['name1'],
        $_POST['designation1'],
        $_POST['office1'],
        $_POST['ministry1'],
        $_POST['name2'],
        $_POST['designation2'],
        $_POST['office2'],
        $_POST['ministry2'],
        $_POST['active_status'] ?? 'active',
        $_POST['tr_link_status'] ?? 'active'
    ];

    if (!empty($_FILES['signature1']['name'])) {
        $signature1 = uploadSignature('signature1');
        $sql .= ", signature1=?";
        $params[] = $signature1;
    }

    if (!empty($_FILES['signature2']['name'])) {
        $signature2 = uploadSignature('signature2');
        $sql .= ", signature2=?";
        $params[] = $signature2;
    }

    $sql .= " WHERE id=?";
    $params[] = $_POST['id'];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    header("Location: settings.php?success=updated");
    exit();
}

// Delete record
function deleteRecord($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM authority_tbl WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: settings.php?success=deleted");
    exit();
}

// Fetch single record
function getRecord($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM authority_tbl WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all records
function getAllRecords($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM authority_tbl ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Upload signature
function uploadSignature($fieldName) {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] != UPLOAD_ERR_OK) return null;

    $targetDir = "uploads/";
    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = uniqid() . '_' . basename($_FILES[$fieldName]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];

    if (in_array(strtolower($fileType), $allowTypes)) {
        if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $targetFilePath)) {
            return $targetFilePath;
        }
    }
    return null;
}

// Show success messages
if (isset($_GET['success'])) {
    $msg = [
        'added' => 'Record added successfully!',
        'updated' => 'Record updated successfully!',
        'deleted' => 'Record deleted successfully!'
    ];
    if (isset($msg[$_GET['success']])) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                {$msg[$_GET['success']]}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Training Records Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.card { border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); margin-bottom: 30px; }
.card-header { border-radius: 10px 10px 0 0 !important; background: linear-gradient(45deg, #0d6efd, #0dcaf0); color: white; }
.btn-primary { background: linear-gradient(to right, #0d6efd, #0dcaf0); border: none; }
.btn-primary:hover { background: linear-gradient(to right, #0b5ed7, #0ba6c8); }
.signature-preview { max-width: 150px; max-height: 80px; border: 1px solid #ddd; border-radius: 5px; padding: 5px; margin-top: 5px; }
.required-field::after { content: "*"; color: red; margin-left: 4px; }
.section-title { border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; color: #0d6efd; }
</style>
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4 text-uppercase text-secondary">Training Records Management System</h1>

            <!-- Form Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0"><?= isset($edit_id) ? 'Edit Training Record' : 'Add New Training Record'; ?></h3>
                </div>
                <div class="card-body">
                    <form id="trainingForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $edit_id ?? ''; ?>">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="batch" class="form-label required-field">Batch</label>
                                <input type="number" class="form-control" id="batch" name="batch" readonly
                                       value="<?= isset($edit_data['batch']) ? $edit_data['batch'] : $next_batch; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="training_title" class="form-label required-field">Training Title</label>
                                <input type="text" class="form-control" id="training_title" name="training_title" required
                                       value="<?= htmlspecialchars($edit_data['training_title'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label required-field">Start Date</label>
                                <input type="date" class="form-control" name="start_date" required
                                       value="<?= htmlspecialchars($edit_data['start_date'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-field">End Date</label>
                                <input type="date" class="form-control" name="end_date" required
                                       value="<?= htmlspecialchars($edit_data['end_date'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label required-field">Certificate Status</label>
                                <select name="active_status" class="form-select" required>
                                        <option value="Inactive" <?= (isset($edit_data['active_status']) && $edit_data['active_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="active" <?= (isset($edit_data['active_status']) && $edit_data['active_status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label required-field">Training Link Status</label>
                                <select name="tr_link_status" class="form-select" required>
                                        <option value="Inactive" <?= (isset($edit_data['tr_link_status']) && $edit_data['tr_link_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="active" <?= (isset($edit_data['tr_link_status']) && $edit_data['tr_link_status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                
                                </select>
                            </div>

                                <div class="col-md-6 mt-3">
                                <label for="organized_by" class="form-label required-field">Organised BY</label>
                                <input type="text" class="form-control" id="organized_by" name="organized_by" required
                                       value="<?= htmlspecialchars($edit_data['organized_by'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- First Person Details -->
                        <h4 class="section-title">First Person Details</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required-field">Name</label>
                                <input type="text" class="form-control" name="name1" required
                                       value="<?= htmlspecialchars($edit_data['name1'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-field">Designation</label>
                                <input type="text" class="form-control" name="designation1" required
                                       value="<?= htmlspecialchars($edit_data['designation1'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required-field">Office</label>
                                <input type="text" class="form-control" name="office1" required
                                       value="<?= htmlspecialchars($edit_data['office1'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ministry</label>
                                <input type="text" class="form-control" name="ministry1" 
                                       value="<?= htmlspecialchars($edit_data['ministry1'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label required-field">Signature</label>
                                <input type="file" class="form-control" name="signature1" accept="image/*">
                                <?php if (!empty($edit_data['signature1'])): ?>
                                    <img src="<?= htmlspecialchars($edit_data['signature1']); ?>" class="signature-preview mt-2" required>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Second Person Details -->
                        <h4 class="section-title">Second Person Details</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label  required-field">Name</label>
                                <input type="text" class="form-control" name="name2"
                                       value="<?= htmlspecialchars($edit_data['name2'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label  required-field">Designation</label>
                                <input type="text" class="form-control" name="designation2"
                                       value="<?= htmlspecialchars($edit_data['designation2'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label  required-field">Office</label>
                                <input type="text" class="form-control" name="office2"
                                       value="<?= htmlspecialchars($edit_data['office2'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ministry</label>
                                <input type="text" class="form-control" name="ministry2"
                                       value="<?= htmlspecialchars($edit_data['ministry2'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label required-field">Signature</label>
                                <input type="file" class="form-control" name="signature2" accept="image/*">
                                <?php if (!empty($edit_data['signature2'])): ?>
                                    <img src="<?= htmlspecialchars($edit_data['signature2']); ?>" class="signature-preview mt-2" required>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary me-md-2">
                                <i class="fas fa-save me-1"></i> <?= isset($edit_id) ? 'Update Record' : 'Add Record'; ?>
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Training Records</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="recordsTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Batch</th>
                                    <th>Training Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Certificate Status</th>
                                    <th>Tr. Link Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($records)): ?>
                                    <?php foreach ($records as $record): ?>
                                        <tr>
                                            <td><?= $record['id']; ?></td>
                                            <td><?= $record['batch']; ?></td>
                                            <td><?= htmlspecialchars($record['training_title']); ?></td>
                                            <td><?= htmlspecialchars($record['start_date']); ?></td>
                                            <td><?= htmlspecialchars($record['end_date']); ?></td>
                                            <td><?= $record['active_status']; ?></td>
                                            <td><?= $record['tr_link_status']; ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="settings.php?edit=<?= $record['id']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="settings.php?delete=<?= $record['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- <tr><td colspan="8" class="text-center">No records found</td></tr> -->
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#recordsTable').DataTable({ responsive:true, ordering:true, searching:true, paging:true });
});
</script>
</body>
</html>
