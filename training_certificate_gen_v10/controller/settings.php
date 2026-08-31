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
$password = '';

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
    $stmt = $pdo->prepare("SELECT * FROM authority_tbl ORDER BY id DESC");
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
        echo "<div class='alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3' style='z-index: 9999; min-width: 300px;' role='alert'>
                <i class='fas fa-check-circle me-2'></i> {$msg[$_GET['success']]}
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
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-blue: #1976d2;
        --primary-green: #43a047;
        --primary-yellow: #ffa000;
        --primary-red: #e53935;
        --primary-pink: #d81b60;
        --bg-white: #ffffff;
        --bg-light: #f5f5f5;
        --text-dark: #333333;
        --text-gray: #757575;
        --border-color: #e0e0e0;
    }
    
    * {
        font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
    }
    
    body {
        background-color: var(--bg-light);
    }
    
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        border: none;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        background-color: var(--primary-blue);
        color: white;
        padding: 15px 20px;
        border: none;
    }
    
    .card-header h3 {
        color: white;
        margin: 0;
        font-weight: 600;
    }
    
    .btn-primary {
        background-color: var(--primary-yellow);
        border-color: var(--primary-yellow);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #f57c00;
        border-color: #f57c00;
        color: white;
    }
    
    .btn-info {
        background-color: var(--primary-pink);
        border-color: var(--primary-pink);
        color: white;
    }
    
    .btn-info:hover {
        background-color: #c2185b;
        border-color: #c2185b;
        color: white;
    }
    
    .btn-secondary {
        background-color: var(--text-gray);
        border-color: var(--text-gray);
    }
    
    .btn-danger {
        background-color: var(--primary-red);
        border-color: var(--primary-red);
    }
    
    .signature-preview {
        max-width: 150px;
        max-height: 80px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 5px;
        margin-top: 5px;
    }
    
    .required-field::after {
        content: "*";
        color: var(--primary-red);
        margin-left: 4px;
    }
    
    .section-title {
        border-left: 4px solid var(--primary-pink);
        padding-left: 15px;
        margin-bottom: 20px;
        margin-top: 20px;
        color: var(--primary-blue);
        font-weight: 600;
        font-size: 1.2rem;
    }
    
    .form-control, .form-select {
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 8px 12px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 0.2rem rgba(255, 160, 0, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 5px;
    }
    
    .table {
        border-color: var(--border-color);
    }
    
    .table thead th {
        background-color: var(--primary-blue);
        color: white;
        border-bottom: none;
        font-weight: 600;
    }
    
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #fafafa;
    }
    
    .table-striped > tbody > tr:hover > * {
        background-color: #e3f2fd;
    }
    
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        color: var(--text-dark);
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--primary-pink);
        border-color: var(--primary-pink);
        color: white !important;
    }
    
    h1.text-center {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 30px;
        position: relative;
    }
    
    h1.text-center:after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: var(--primary-pink);
        margin: 10px auto 0;
        border-radius: 2px;
    }
    
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }
        
        .btn-group {
            display: flex;
            gap: 5px;
        }
        
        .section-title {
            font-size: 1rem;
        }
    }
</style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">
                <i class="fas fa-cog" style="color: var(--primary-pink);"></i>
                Training Records Management System
            </h1>

            <!-- Form Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-<?= isset($edit_id) ? 'edit' : 'plus'; ?> me-2"></i>
                        <?= isset($edit_id) ? 'Edit Training Record' : 'Add New Training Record'; ?>
                    </h3>
                    <a href="dashboard.php" class="btn btn-info">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
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
                        
                        <div class="row mb-3">
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
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required-field">Certificate Status</label>
                                <select name="active_status" class="form-select" required>
                                    <option value="Inactive" <?= (isset($edit_data['active_status']) && $edit_data['active_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="active" <?= (isset($edit_data['active_status']) && $edit_data['active_status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-field">Training Link Status</label>
                                <select name="tr_link_status" class="form-select" required>
                                    <option value="Inactive" <?= (isset($edit_data['tr_link_status']) && $edit_data['tr_link_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="active" <?= (isset($edit_data['tr_link_status']) && $edit_data['tr_link_status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="organized_by" class="form-label required-field">Organised By</label>
                                <input type="text" class="form-control" id="organized_by" name="organized_by" required
                                       value="<?= htmlspecialchars($edit_data['organized_by'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- First Person Details -->
                        <h4 class="section-title">
                            <i class="fas fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                            First Person Details
                        </h4>
                        
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
                                    <img src="<?= htmlspecialchars($edit_data['signature1']); ?>" class="signature-preview mt-2">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Second Person Details -->
                        <h4 class="section-title">
                            <i class="fas fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                            Second Person Details
                        </h4>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required-field">Name</label>
                                <input type="text" class="form-control" name="name2"
                                       value="<?= htmlspecialchars($edit_data['name2'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-field">Designation</label>
                                <input type="text" class="form-control" name="designation2"
                                       value="<?= htmlspecialchars($edit_data['designation2'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required-field">Office</label>
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
                                    <img src="<?= htmlspecialchars($edit_data['signature2']); ?>" class="signature-preview mt-2">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> <?= isset($edit_id) ? 'Update Record' : 'Add Record'; ?>
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-table me-2"></i> Training Records
                        </h3>
                        <a href="dashboard.php" class="btn btn-info">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
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
                                            <td>
                                                <span class="badge <?= $record['active_status'] == 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?= $record['active_status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $record['tr_link_status'] == 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                                    <?= $record['tr_link_status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="settings.php?edit=<?= $record['id']; ?>" class="btn btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="settings.php?delete=<?= $record['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
    $('#recordsTable').DataTable({ 
        responsive: true,
        ordering: true,
        searching: true,
        paging: true,
        order: [[0, "desc"]],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                previous: "<",
                next: ">"
            }
        }
    });
});
</script>
</body>
</html>