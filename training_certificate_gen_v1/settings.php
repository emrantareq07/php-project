<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
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
    // Create connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if it's an update or insert
        if (!empty($_POST['id'])) {
            updateRecord($pdo);
        } else {
            insertRecord($pdo);
        }
    }
    
    // Handle delete action
    if (isset($_GET['delete'])) {
        deleteRecord($pdo, $_GET['delete']);
    }
    
    // Handle edit action
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_data = getRecord($pdo, $edit_id);
    }
    
    // Fetch all records for the table
    $records = getAllRecords($pdo);
    
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Connection failed: " . $e->getMessage() . "</div>";
}

function insertRecord($pdo) {
    $sql = "INSERT INTO authority_tbl (batch, training_title,start_date, end_date, name1, designation1, office1, ministry1, signature1, name2, designation2, office2, ministry2, signature2, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Handle file uploads
    $signature1 = uploadSignature('signature1');
    $signature2 = uploadSignature('signature2');
    
    $stmt->execute([
        $_POST['batch'],
        $_POST['training_title'],
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
        $signature2
    ]);
    
    header("Location: settings.php?success=added");
    exit();
}

function updateRecord($pdo) {
    $sql = "UPDATE authority_tbl SET batch=?, training_title=?, start_date=?,end_date=?, name1=?, designation1=?, office1=?, ministry1=?, name2=?, designation2=?, office2=?, ministry2=?, updated_at=NOW()";
    
    $params = [
        $_POST['batch'],
        $_POST['training_title'],
        $_POST['start_date'],
        $_POST['end_date'],
        $_POST['name1'],
        $_POST['designation1'],
        $_POST['office1'],
        $_POST['ministry1'],
        $_POST['name2'],
        $_POST['designation2'],
        $_POST['office2'],
        $_POST['ministry2']
    ];
    
    // Handle signature updates if new files are uploaded
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

function deleteRecord($pdo, $id) {
    $sql = "DELETE FROM authority_tbl WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    header("Location: settings.php?success=deleted");
    exit();
}

function getRecord($pdo, $id) {
    $sql = "SELECT * FROM authority_tbl WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllRecords($pdo) {
    $sql = "SELECT * FROM authority_tbl ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function uploadSignature($fieldName) {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] != UPLOAD_ERR_OK) {
        return null;
    }
    
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . basename($_FILES[$fieldName]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    // Allow certain file formats
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array(strtolower($fileType), $allowTypes)) {
        if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $targetFilePath)) {
            return $targetFilePath;
        }
    }
    
    return null;
}

// Show success message if any
if (isset($_GET['success'])) {
    $action = $_GET['success'];
    $message = '';
    
    switch ($action) {
        case 'added':
            $message = 'Record added successfully!';
            break;
        case 'updated':
            $message = 'Record updated successfully!';
            break;
        case 'deleted':
            $message = 'Record deleted successfully!';
            break;
    }
    
    if (!empty($message)) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                $message
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
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(to right, #0d6efd, #0dcaf0);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #0b5ed7, #0ba6c8);
        }
        .signature-preview {
            max-width: 150px;
            max-height: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            margin-top: 5px;
        }
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
        .section-title {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #0d6efd;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Training Records Management System</h1>
                
                <!-- Form Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0"><?php echo isset($edit_id) ? 'Edit Training Record' : 'Add New Training Record'; ?></h3>
                    </div>
                    <div class="card-body">
                        <form id="trainingForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="recordId" value="<?php echo isset($edit_id) ? $edit_id : ''; ?>">
                            
                            <!-- Training Information -->
                            <h4 class="section-title">Training Information</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="batch" class="form-label required-field">Batch</label>
                                    <select name="batch" id="batch" class="form-select" required>
                                        <option value="">-- Select Batch --</option>
                                        <option value="1st" <?= (isset($edit_data['batch']) && $edit_data['batch'] == "1st") ? "selected" : ""; ?>>1st</option>
                                        <option value="2nd" <?= (isset($edit_data['batch']) && $edit_data['batch'] == "2nd") ? "selected" : ""; ?>>2nd</option>
                                        <option value="3rd" <?= (isset($edit_data['batch']) && $edit_data['batch'] == "3rd") ? "selected" : ""; ?>>3rd</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="training_title" class="form-label required-field">Training Title</label>
                                    <input type="text" class="form-control" id="training_title" name="training_title" required 
                                           value="<?php echo isset($edit_data['training_title']) ? htmlspecialchars($edit_data['training_title']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="duration" class="form-label required-field">Duration</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required 
                                           value="<?php echo isset($edit_data['start_date']) ? htmlspecialchars($edit_data['start_date']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="duration" class="form-label required-field">Duration</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required 
                                           value="<?php echo isset($edit_data['end_date']) ? htmlspecialchars($edit_data['end_date']) : ''; ?>">
                                </div>
                            </div>
                            
                            <!-- First Person Details -->
                            <h4 class="section-title">First Person Details</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name1" class="form-label required-field">Name</label>
                                    <input type="text" class="form-control" id="name1" name="name1" required 
                                           value="<?php echo isset($edit_data['name1']) ? htmlspecialchars($edit_data['name1']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="designation1" class="form-label required-field">Designation</label>
                                    <input type="text" class="form-control" id="designation1" name="designation1" required 
                                           value="<?php echo isset($edit_data['designation1']) ? htmlspecialchars($edit_data['designation1']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="office1" class="form-label required-field">Office</label>
                                    <input type="text" class="form-control" id="office1" name="office1" required 
                                           value="<?php echo isset($edit_data['office1']) ? htmlspecialchars($edit_data['office1']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="ministry1" class="form-label required-field">Ministry</label>
                                    <input type="text" class="form-control" id="ministry1" name="ministry1" required 
                                           value="<?php echo isset($edit_data['ministry1']) ? htmlspecialchars($edit_data['ministry1']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="signature1" class="form-label">Signature</label>
                                    <input type="file" class="form-control" id="signature1" name="signature1" accept="image/*">
                                    <?php if (isset($edit_data['signature1']) && !empty($edit_data['signature1'])): ?>
                                        <img src="<?php echo htmlspecialchars($edit_data['signature1']); ?>" class="signature-preview mt-2" alt="Signature 1">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Second Person Details -->
                            <h4 class="section-title">Second Person Details</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name2" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name2" name="name2" 
                                           value="<?php echo isset($edit_data['name2']) ? htmlspecialchars($edit_data['name2']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="designation2" class="form-label">Designation</label>
                                    <input type="text" class="form-control" id="designation2" name="designation2" 
                                           value="<?php echo isset($edit_data['designation2']) ? htmlspecialchars($edit_data['designation2']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="office2" class="form-label">Office</label>
                                    <input type="text" class="form-control" id="office2" name="office2" 
                                           value="<?php echo isset($edit_data['office2']) ? htmlspecialchars($edit_data['office2']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="ministry2" class="form-label">Ministry</label>
                                    <input type="text" class="form-control" id="ministry2" name="ministry2" 
                                           value="<?php echo isset($edit_data['ministry2']) ? htmlspecialchars($edit_data['ministry2']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="signature2" class="form-label">Signature</label>
                                    <input type="file" class="form-control" id="signature2" name="signature2" accept="image/*">
                                    <?php if (isset($edit_data['signature2']) && !empty($edit_data['signature2'])): ?>
                                        <img src="<?php echo htmlspecialchars($edit_data['signature2']); ?>" class="signature-preview mt-2" alt="Signature 2">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary me-md-2">
                                    <i class="fas fa-save me-1"></i> <?php echo isset($edit_id) ? 'Update Record' : 'Add Record'; ?>
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
                                        <th>End date</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($records)) {
                                        foreach ($records as $record) {
                                            echo "<tr>
                                                    <td>{$record['id']}</td>
                                                    <td>{$record['batch']}</td>
                                                    <td>" . htmlspecialchars($record['training_title']) . "</td>
                                                    <td>" . htmlspecialchars($record['start_date']) . "</td>
                                                    <td>" . htmlspecialchars($record['end_date']) . "</td>
                                                    <td>{$record['created_at']}</td>
                                                    <td>
                                                        <div class='btn-group btn-group-sm'>
                                                            <a href='settings.php?edit={$record['id']}' class='btn btn-primary'><i class='fas fa-edit'></i></a>
                                                            <a href='settings.php?delete={$record['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='fas fa-trash'></i></a>
                                                        </div>
                                                    </td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#recordsTable').DataTable({
                responsive: true,
                ordering: true,
                searching: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50]
            });
            
            // Form validation
            $('#trainingForm').on('submit', function(e) {
                let isValid = true;
                
                // Check required fields
                $(this).find('[required]').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
            
            // Preview signature images before upload
            $('#signature1, #signature2').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    // Remove any existing preview
                    $(this).siblings('img').remove();
                    
                    reader.onload = function(e) {
                        $(this).after('<img src="' + e.target.result + '" class="signature-preview mt-2" alt="Signature Preview">');
                    }.bind(this);
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>