<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

$edit_mode = false;
$prescription = null;
$items = [];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("No employee ID provided.");
    }

    $emp_id = $_GET['id'];

    // Get employee information
    $stmt = $conn->prepare("SELECT * FROM users WHERE emp_id = :emp_id");
    $stmt->execute([':emp_id' => $emp_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception("Employee not found.");
    }

    // Check if we're editing an existing prescription
    if (isset($_GET['edit']) && !empty($_GET['edit'])) {
        $edit_mode = true;
        $prescription_id = $_GET['edit'];

        // Get prescription header
        $stmt = $conn->prepare("SELECT * FROM prescription_tbl WHERE id = :id AND emp_id = :emp_id");
        $stmt->execute([':id' => $prescription_id, ':emp_id' => $emp_id]);
        $prescription = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prescription) {
            throw new Exception("Prescription not found or doesn't belong to this employee.");
        }

        // Get prescription items with medicine names and types
        $stmt = $conn->prepare("SELECT pi.*, m.med_name as medicine_name, m.med_type as medicine_type 
                               FROM prescription_items pi
                               LEFT JOIN medicine_tbl m ON pi.medicine_id = m.id
                               WHERE pi.prescription_id = :id 
                               ORDER BY pi.id");
        $stmt->execute([':id' => $prescription_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all medicines for dropdown
    $stmt = $conn->prepare("SELECT id, med_name, med_type FROM medicine_tbl ORDER BY med_name");
    $stmt->execute();
    $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get unique medicine types for dropdown
    $stmt = $conn->prepare("SELECT DISTINCT med_type FROM medicine_tbl ORDER BY med_type");
    $stmt->execute();
    $medicine_types = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $disease_name = $_POST['disease_name'] ?? '';
        $advise = $_POST['advise'] ?? '';
        $medicine_ids = $_POST['medicine_id'] ?? [];
        $med_types = $_POST['med_type'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $units = $_POST['unit'] ?? [];
        $dosages = $_POST['dosage'] ?? [];

        // Validate required fields
        if (empty($disease_name)) {
            throw new Exception("Disease name is required.");
        }

        if (empty($medicine_ids)) {
            throw new Exception("At least one medicine is required.");
        }

        // Begin transaction
        $conn->beginTransaction();

        try {
            if ($edit_mode) {
                // Update existing prescription
                $stmt = $conn->prepare("UPDATE prescription_tbl 
                                      SET disease_name = :disease_name, advise = :advise, date = NOW()
                                      WHERE id = :id");
                $stmt->execute([
                    ':disease_name' => $disease_name,
                    ':advise' => $advise,
                    ':id' => $prescription_id
                ]);

                // Delete existing items
                $stmt = $conn->prepare("DELETE FROM prescription_items WHERE prescription_id = :id");
                $stmt->execute([':id' => $prescription_id]);
            } else {
                // Create new prescription
                $stmt = $conn->prepare("INSERT INTO prescription_tbl (emp_id, disease_name, advise, date, created_at) 
                                      VALUES (:emp_id, :disease_name, :advise, NOW(), NOW())");
                $stmt->execute([
                    ':emp_id' => $emp_id,
                    ':disease_name' => $disease_name,
                    ':advise' => $advise
                ]);
                $prescription_id = $conn->lastInsertId();
            }

            // Insert new items
            foreach ($medicine_ids as $index => $medicine_id) {
                if (!empty($medicine_id)) {
                    $stmt = $conn->prepare("INSERT INTO prescription_items 
                                          (prescription_id, medicine_id, med_type, quantity, unit, dosage) 
                                          VALUES (:prescription_id, :medicine_id, :med_type, :quantity, :unit, :dosage)");
                    $stmt->execute([
                        ':prescription_id' => $prescription_id,
                        ':medicine_id' => $medicine_id,
                        ':med_type' => $med_types[$index] ?? '',
                        ':quantity' => $quantities[$index] ?? 0,
                        ':unit' => $units[$index] ?? '',
                        ':dosage' => $dosages[$index] ?? ''
                    ]);
                }
            }

            $conn->commit();
            
            $_SESSION['success'] = $edit_mode ? "Prescription updated successfully!" : "Prescription created successfully!";
            header("Location: prescription_view.php?id=" . $prescription_id);
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: prescription_list.php?id=" . urlencode($emp_id));
    exit();
} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $redirect = $edit_mode ? "prescription_entry.php?id=" . urlencode($emp_id) . "&edit=" . $prescription_id : "prescription_list.php?id=" . urlencode($emp_id);
    header("Location: " . $redirect);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_mode ? 'Edit' : 'Create' ?> Prescription - <?= htmlspecialchars($employee['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .medicine-item {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
        }
        .btn-remove-medicine {
            margin-top: 30px;
        }
        .medicine-type-col {
            max-width: 150px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-prescription-bottle-alt text-primary me-2"></i>
            <?= $edit_mode ? 'Edit' : 'Create' ?> Prescription for <?= htmlspecialchars($employee['name']) ?>
        </h2>
        <a href="patient_mgtm.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left "></i> Back to Patient Manage
            </a>
        <a href="prescription_list.php?id=<?= urlencode($emp_id) ?>" class="btn btn-warning">
            <i class="fas fa-list me-1"></i> Prescription List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Employee Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Employee ID:</strong> <?= htmlspecialchars($employee['emp_id']) ?></p>
                    <p><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></p>
                    <p><strong>Designation:</strong> <?= htmlspecialchars($employee['designation']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Division:</strong> <?= htmlspecialchars($employee['division']) ?></p>
                    <p><strong>Section:</strong> <?= htmlspecialchars($employee['section']) ?></p>
                    <p><strong>Employee Type:</strong> <?= htmlspecialchars($employee['employee_type']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <form method="post" id="prescriptionForm">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-file-medical me-2"></i>
                    Prescription Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="disease_name" class="form-label"><i class="fas fa-disease me-2"></i>Disease Name *</label>
                            <input type="text" class="form-control" id="disease_name" name="disease_name" 
                                   value="<?= htmlspecialchars($prescription['disease_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="advise" class="form-label"><i class="fas fa-comment-medical me-2"></i>Advise</label>
                            <textarea class="form-control" id="advise" name="advise" rows="2"><?= htmlspecialchars($prescription['advise'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3"><i class="fas fa-pills me-2"></i>Medicines</h5>
                
                <div id="medicineItems">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $index => $item): ?>
                            <div class="medicine-item" data-index="<?= $index ?>">
                                <div class="row g-2">
                                    <div class="col-md-2 medicine-type-col">
                                        <div class="mb-3">
                                            <label class="form-label">Type *</label>
                                            <select class="form-select" name="med_type[]" required>
                                                <option value="">Select Type</option>
                                                <?php foreach ($medicine_types as $type): ?>
                                                    <option value="<?= htmlspecialchars($type) ?>" <?= ($item['medicine_type'] == $type) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($type) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Medicine *</label>
                                            <select class="form-select" name="medicine_id[]" required>
                                                <option value="">Select Medicine</option>
                                                <?php foreach ($medicines as $medicine): ?>
                                                    <option value="<?= $medicine['id'] ?>" <?= ($item['medicine_id'] == $medicine['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($medicine['med_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-3">
                                            <label class="form-label">Qty *</label>
                                            <input type="number" class="form-control" name="quantity[]" 
                                                   value="<?= htmlspecialchars($item['quantity']) ?>" required min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">Unit *</label>
                                            <select class="form-select" name="unit[]" required>
                                                <option value="Tablet" <?= ($item['unit'] == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
                                                <option value="Capsule" <?= ($item['unit'] == 'Capsule') ? 'selected' : '' ?>>Capsule</option>
                                                <option value="Bottle" <?= ($item['unit'] == 'Bottle') ? 'selected' : '' ?>>Bottle</option>
                                                <option value="Tube" <?= ($item['unit'] == 'Tube') ? 'selected' : '' ?>>Tube</option>
                                                <option value="Pieces" <?= ($item['unit'] == 'Pieces') ? 'selected' : '' ?>>Pieces</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Dosage *</label>
                                            <input type="text" class="form-control" name="dosage[]" 
                                                   value="<?= htmlspecialchars($item['dosage']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-medicine w-100">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="medicine-item" data-index="0">
                            <div class="row g-2">
                                <div class="col-md-2 medicine-type-col">
                                    <div class="mb-3">
                                        <label class="form-label">Type *</label>
                                        <select class="form-select" name="med_type[]" required>
                                            <option value="">Select Type</option>
                                            <?php foreach ($medicine_types as $type): ?>
                                                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Medicine *</label>
                                        <select class="form-select" name="medicine_id[]" required>
                                            <option value="">Select Medicine</option>
                                            <?php foreach ($medicines as $medicine): ?>
                                                <option value="<?= $medicine['id'] ?>"><?= htmlspecialchars($medicine['med_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label class="form-label">Qty *</label>
                                        <input type="number" class="form-control" name="quantity[]" required min="1">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">Unit *</label>
                                        <select class="form-select" name="unit[]" required>
                                            <option value="Tablet">Tablet</option>
                                            <option value="Capsule">Capsule</option>
                                            <option value="Bottle">Bottle</option>
                                            <option value="Tube">Tube</option>
                                            <option value="Pieces">Pieces</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Dosage *</label>
                                        <input type="text" class="form-control" name="dosage[]" required>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-medicine w-100">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="button" id="btnAddMedicine" class="btn btn-success mt-3">
                    <i class="fas fa-plus me-1"></i> Add Another Medicine
                </button>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> <?= $edit_mode ? 'Update' : 'Save' ?> Prescription
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Add new medicine item
    $('#btnAddMedicine').click(function() {
        const index = $('#medicineItems .medicine-item').length;
        const newItem = `
            <div class="medicine-item" data-index="${index}">
                <div class="row g-2">
                    <div class="col-md-2 medicine-type-col">
                        <div class="mb-3">
                            <label class="form-label">Type *</label>
                            <select class="form-select" name="med_type[]" required>
                                <option value="">Select Type</option>
                                <?php foreach ($medicine_types as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Medicine *</label>
                            <select class="form-select" name="medicine_id[]" required>
                                <option value="">Select Medicine</option>
                                <?php foreach ($medicines as $medicine): ?>
                                    <option value="<?= $medicine['id'] ?>"><?= htmlspecialchars($medicine['med_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label class="form-label">Qty *</label>
                            <input type="number" class="form-control" name="quantity[]" required min="1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Unit *</label>
                            <select class="form-select" name="unit[]" required>
                                <option value="Tablet">Tablet</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Bottle">Bottle</option>
                                <option value="Tube">Tube</option>
                                <option value="Pieces">Pieces</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Dosage *</label>
                            <input type="text" class="form-control" name="dosage[]" required>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-medicine w-100">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#medicineItems').append(newItem);
    });

    // Remove medicine item
    $(document).on('click', '.btn-remove-medicine', function() {
        if ($('#medicineItems .medicine-item').length > 1) {
            $(this).closest('.medicine-item').remove();
            // Re-index items
            $('#medicineItems .medicine-item').each(function(index) {
                $(this).attr('data-index', index);
            });
        } else {
            alert('At least one medicine is required.');
        }
    });

    // Form validation
    $('#prescriptionForm').submit(function() {
        let valid = true;
        
        // Check disease name
        if ($('#disease_name').val().trim() === '') {
            valid = false;
            alert('Disease name is required.');
            $('#disease_name').focus();
            return false;
        }
        
        // Check each medicine item
        $('select[name="medicine_id[]"]').each(function() {
            if ($(this).val() === '') {
                valid = false;
                alert('All medicine selections are required.');
                $(this).focus();
                return false;
            }
        });

        $('select[name="med_type[]"]').each(function() {
            if ($(this).val() === '') {
                valid = false;
                alert('All medicine types are required.');
                $(this).focus();
                return false;
            }
        });
        
        if (!valid) return false;
    });

    // Optional: Filter medicines by type when type is selected
    $(document).on('change', 'select[name="med_type[]"]', function() {
        const type = $(this).val();
        const medicineSelect = $(this).closest('.row').find('select[name="medicine_id[]"]');
        
        if (type) {
            // In a real implementation, you might want to fetch medicines of this type via AJAX
            // For now, we'll just show all medicines
            medicineSelect.val('');
        }
    });
});
</script>
</body>
</html>