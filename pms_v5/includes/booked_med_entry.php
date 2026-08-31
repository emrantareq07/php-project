<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

$edit_mode = false;
$edit_id = null;
$existing_patient_data = null;
$existing_medicines = [];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("No employee ID provided.");
    }

    $emp_id = $_GET['id'];

    // Get employee details
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = :emp_id");
    $stmt->execute([':emp_id' => $emp_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception("Employee not found.");
    }

    // Fetch medicines catalog
    $stmt = $conn->prepare("SELECT id, med_name, med_type, brand, quantity, dose, duration, current_stock FROM medicine_tbl ORDER BY med_name");
    $stmt->execute();
    $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $medicinesIndexedById = [];
    $medicinesIndexedByName = [];
    foreach ($medicines as $med) {
        $medicinesIndexedById[$med['id']] = $med;
        $medicinesIndexedByName[trim(strtolower($med['med_name']))] = $med;
    }

    // --- CHECK EDIT MODE (Fetch from special_med_tbl) ---
    if (isset($_GET['edit']) && !empty($_GET['edit'])) {
        $edit_mode = true;
        $edit_id = $_GET['edit'];

        $stmt = $conn->prepare("SELECT * FROM special_med_tbl WHERE id = :id AND emp_id = :emp_id");
        $stmt->execute([':id' => $edit_id, ':emp_id' => $emp_id]);
        $existing_patient_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_patient_data) {
            throw new Exception("Booked medicine record not found for editing.");
        }

        // Parse medicine string safely
        if (!empty($existing_patient_data['medicine'])) {
            $raw_string = trim($existing_patient_data['medicine']);

            // Detect delimiter: || is modern standard, otherwise split by || or +
            if (strpos($raw_string, '||') !== false) {
                $raw_items = array_map('trim', explode('||', $raw_string));
            } else {
                // Legacy support for single line or + separated entries
                $raw_items = [$raw_string];
            }

            foreach ($raw_items as $raw_item) {
                if (empty($raw_item)) continue;

                // Handle legacy format where fields might be separated by '+' instead of ','
                if (strpos($raw_item, '+') !== false && strpos($raw_item, ',') === false) {
                    $parts = array_map('trim', explode('+', $raw_item));
                } else {
                    $parts = array_map('trim', explode(',', $raw_item));
                }
                
                $med_name = $parts[0] ?? '';
                $brand    = $parts[1] ?? '';
                $qty      = $parts[2] ?? '';
                $unit     = $parts[3] ?? '';
                $dosage   = $parts[4] ?? '';
                $duration = $parts[5] ?? '';

                // Skip completely empty parts
                if (empty($med_name) && empty($brand) && empty($qty)) {
                    continue;
                }

                $lookup_key = trim(strtolower($med_name));
                $matched_med = $medicinesIndexedByName[$lookup_key] ?? null;

                $med_id = $matched_med['id'] ?? '';
                $curr_stock = $matched_med['current_stock'] ?? 0;

                $existing_medicines[] = [
                    'medicine_id'   => $med_id,
                    'med_name'      => $med_name,
                    'brand'         => $brand,
                    'quantity'      => $qty,
                    'unit'          => $unit,
                    'dosage'        => $dosage,
                    'duration'      => $duration,
                    'current_stock' => $curr_stock
                ];
            }
        }
    }

    // --- HANDLE FORM SUBMISSION ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn->beginTransaction();

        $diseases = $_POST['disease_name'] ?? '';
        $advice = $_POST['advise'] ?? '';

        $medicine_ids = $_POST['medicine_id'] ?? [];
        $brands       = $_POST['brand'] ?? [];
        $quantities   = $_POST['quantity'] ?? [];
        $units        = $_POST['unit'] ?? [];
        $dosages      = $_POST['dosage'] ?? [];
        $durations    = $_POST['duration'] ?? [];

        if (empty($diseases)) {
            throw new Exception("Disease name is required.");
        }

        if (empty($medicine_ids)) {
            throw new Exception("At least one medicine is required.");
        }

        $formatted_medicines = [];

        foreach ($medicine_ids as $index => $med_id) {
            if (!empty($med_id)) {
                $med_name = $medicinesIndexedById[$med_id]['med_name'] ?? 'Unknown';
                $brand    = $brands[$index] ?? '';
                $qty      = intval($quantities[$index] ?? 1);
                $unit     = $units[$index] ?? '';
                $dosage   = $dosages[$index] ?? '';
                $duration = $durations[$index] ?? '';

                $formatted_medicines[] = "{$med_name}, {$brand}, {$qty}, {$unit}, {$dosage}, {$duration}";
            }
        }

        $medicine_string = implode(' || ', $formatted_medicines);

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            $stmt = $conn->prepare("UPDATE special_med_tbl SET 
                medicine = :medicine,
                advice = :advice,
                diseases = :diseases,
                updated_at = NOW()
                WHERE id = :id AND emp_id = :emp_id");

            $stmt->execute([
                ':medicine' => $medicine_string,
                ':advice'   => $advice,
                ':diseases' => $diseases,
                ':id'       => $_POST['edit_id'],
                ':emp_id'   => $emp_id
            ]);

            $_SESSION['success'] = "Booked medicine record updated successfully!";
        } else {
            $stmt = $conn->prepare("INSERT INTO special_med_tbl 
                (emp_id, medicine, advice, diseases, date, created_at, updated_at) 
                VALUES 
                (:emp_id, :medicine, :advice, :diseases, CURDATE(), NOW(), NOW())");

            $stmt->execute([
                ':emp_id'   => $emp_id,
                ':medicine' => $medicine_string,
                ':advice'   => $advice,
                ':diseases' => $diseases
            ]);

            $_SESSION['success'] = "Booked medicine entry created successfully!";
        }

        $conn->commit();
        header("Location: booked_med_list.php?id=" . urlencode($emp_id));
        exit();
    }

} catch(PDOException $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: booked_med_list.php?id=" . urlencode($emp_id ?? ''));
    exit();
} catch(Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = $e->getMessage();
    header("Location: booked_med_list.php?id=" . urlencode($emp_id ?? ''));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_mode ? 'Edit' : 'New' ?> Booked Medicine - <?= htmlspecialchars($employee['name'] ?? '') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas <?= $edit_mode ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?> me-2"></i>
            <?= $edit_mode ? 'Edit Booked Medicine' : 'New Booked Medicine Entry' ?>
        </h2>
        <div>
            <a href="patient_mgtm.php?id=<?= urlencode($emp_id) ?>" class="btn btn-primary me-2">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
            <a href="booked_med_list.php?id=<?= urlencode($emp_id) ?>" class="btn btn-secondary">
                <i class="fas fa-list me-1"></i> Booked List
            </a>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body bg-white rounded">
            <div class="row">
                <div class="col-md-3"><strong>Employee ID:</strong> <?= htmlspecialchars($employee['emp_id']) ?></div>
                <div class="col-md-3"><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></div>
                <div class="col-md-3"><strong>Designation:</strong> <?= htmlspecialchars($employee['designation']) ?></div>
                <div class="col-md-3"><strong>Division:</strong> <?= htmlspecialchars($employee['division']) ?></div>
            </div>
        </div>
    </div>

    <form method="POST" action="">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_id) ?>">
        <?php endif; ?>

        <!-- Diagnosis Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-notes-medical me-2"></i>Diagnosis Info</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Disease Name / Diagnosis</label>
                        <input type="text" name="disease_name" class="form-control"
                               value="<?= htmlspecialchars($existing_patient_data['diseases'] ?? '') ?>" placeholder="e.g. Chronic Kidney Disease, Diabetes" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicines Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><i class="fas fa-pills me-2"></i>Prescribed Medicines</h5>
                <button type="button" class="btn btn-sm btn-success" id="add-row-btn">
                    <i class="fas fa-plus me-1"></i> Add Medicine
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="medicine-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%;">Medicine Name</th>
                                <th style="width: 15%;">Brand</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 12%;">Unit</th>
                                <th style="width: 12%;">Dosage</th>
                                <th style="width: 12%;">Duration</th>
                                <th style="width: 10%;" class="text-center">Stock</th>
                                <th style="width: 4%;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="medicine-rows">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Advice Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-comment-medical me-2"></i>Special Advice</h5>
            </div>
            <div class="card-body">
                <textarea name="advise" class="form-control" rows="3" placeholder="Enter instructions or advice..."><?= htmlspecialchars($existing_patient_data['advice'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-save me-1"></i> <?= $edit_mode ? 'Update Record' : 'Save Record' ?>
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const medicinesData = <?= json_encode($medicines) ?>;
    const existingMedicines = <?= json_encode($existing_medicines) ?>;
    const isEditMode = <?= $edit_mode ? 'true' : 'false' ?>;

    function buildMedicineOptions(selectedId = '') {
        let options = '<option value="">-- Select Medicine --</option>';
        medicinesData.forEach(med => {
            const isSelected = String(med.id) === String(selectedId) ? 'selected' : '';
            options += `<option value="${med.id}" ${isSelected}>${med.med_name}</option>`;
        });
        return options;
    }

    function createRow(data = {}) {
        const medId    = data.medicine_id || '';
        const brand    = data.brand || '';
        const qty      = data.quantity || '';
        const unit     = data.unit || '';
        const dosage   = data.dosage || data.dose || ''; 
        const duration = data.duration || '';
        const stock    = data.current_stock ?? 0;

        const rowHtml = `
            <tr>
                <td>
                    <select name="medicine_id[]" class="form-select med-select" required>
                        ${buildMedicineOptions(medId)}
                    </select>
                </td>
                <td><input type="text" name="brand[]" class="form-control brand-input" value="${brand}"></td>
                <td><input type="number" name="quantity[]" class="form-control qty-input" value="${qty}" placeholder="Qty"></td>
                <td><input type="text" name="unit[]" class="form-control unit-input" value="${unit}" placeholder="Unit"></td>
                <td><input type="text" name="dosage[]" class="form-control dose-input" value="${dosage}" placeholder="Dosage"></td>
                <td><input type="text" name="duration[]" class="form-control duration-input" value="${duration}" placeholder="Duration"></td>
                <td class="text-center fw-bold">
                    <span class="stock-display text-primary">${stock}</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
        $('#medicine-rows').append(rowHtml);
    }

    $(document).ready(function() {
        $('#medicine-rows').empty();

        // Populate saved rows on edit, filtering out empty entries
        if (isEditMode && existingMedicines.length > 0) {
            let addedCount = 0;
            existingMedicines.forEach(function(med) {
                if (med.medicine_id || med.med_name || med.brand) {
                    createRow(med);
                    addedCount++;
                }
            });
            if (addedCount === 0) {
                createRow();
            }
        } else {
            createRow(); // Fresh row for new entry
        }

        $('#add-row-btn').click(function() {
            createRow();
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#medicine-rows tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('At least one medicine row is required.');
            }
        });

        // Auto-fill values when selecting a new medicine from dropdown
        $(document).on('change', '.med-select', function() {
            const selectedId = $(this).val();
            const row = $(this).closest('tr');
            const found = medicinesData.find(m => String(m.id) === String(selectedId));

            if (found) {
                row.find('.brand-input').val(found.brand || '');
                row.find('.qty-input').val(found.quantity ?? '');
                row.find('.unit-input').val(found.med_type || '');
                row.find('.dose-input').val(found.dose || '');
                row.find('.duration-input').val(found.duration || '');
                row.find('.stock-display').text(found.current_stock || 0);
            } else {
                row.find('.brand-input').val('');
                row.find('.qty-input').val('');
                row.find('.unit-input').val('');
                row.find('.dose-input').val('');
                row.find('.duration-input').val('');
                row.find('.stock-display').text('0');
            }
        });
    });
</script>
</body>
</html>