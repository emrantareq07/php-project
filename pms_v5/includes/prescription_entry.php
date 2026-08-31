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
$booked_medicines = [];

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

    $emp_designation = $employee['designation'] ?? '';

    // Fetch medicines with current stock
    $stmt = $conn->prepare("SELECT id, med_name, med_type, brand, quantity, dose, duration, current_stock FROM medicine_tbl ORDER BY med_name");
    $stmt->execute();
    $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $medicinesIndexedById = [];
    $medicinesIndexedByName = [];
    foreach ($medicines as $med) {
        $medicinesIndexedById[$med['id']] = $med;
        $medicinesIndexedByName[trim(strtolower($med['med_name']))] = $med;
    }

    // --- HELPER FUNCTION TO PARSE MEDICINE STRINGS ---
    function parseMedicineString($raw_string, $medicinesIndexedByName) {
        $parsed = [];
        if (empty($raw_string)) return $parsed;

        if (strpos($raw_string, '||') !== false) {
            $raw_items = explode('||', $raw_string);
        } elseif (strpos($raw_string, '+') !== false && strpos($raw_string, ',') === false) {
            $raw_items = explode('+', $raw_string);
        } else {
            $raw_items = [$raw_string];
        }

        foreach ($raw_items as $raw_item) {
            $raw_item = trim($raw_item);
            if (empty($raw_item)) continue;

            $parts = array_map('trim', explode(',', $raw_item));

            $med_name = $parts[0] ?? '';
            $brand    = $parts[1] ?? '';
            $qty      = $parts[2] ?? '1';
            $unit     = $parts[3] ?? '';
            $dosage   = $parts[4] ?? '';
            $duration = $parts[5] ?? '';

            if (empty($med_name)) continue;

            $lookup_key = strtolower($med_name);
            $med_id = $medicinesIndexedByName[$lookup_key]['id'] ?? '';
            $curr_stock = $medicinesIndexedByName[$lookup_key]['current_stock'] ?? 0;

            $parsed[] = [
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
        return $parsed;
    }

    // --- FETCH BOOKED MEDICINES FROM special_med_tbl ---
    $stmt = $conn->prepare("SELECT * FROM special_med_tbl WHERE emp_id = :emp_id ORDER BY date DESC, id DESC");
    $stmt->execute([':emp_id' => $emp_id]);
    $special_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($special_records as $s_rec) {
        if (!empty($s_rec['medicine'])) {
            $items = parseMedicineString($s_rec['medicine'], $medicinesIndexedByName);
            foreach ($items as $item) {
                $item['diseases'] = $s_rec['diseases'] ?? '';
                $item['advice']   = $s_rec['advice'] ?? '';
                $booked_medicines[] = $item;
            }
        }
    }

    // --- CHECK EDIT OR CLONE MODE ---
    if (isset($_GET['edit']) && !empty($_GET['edit'])) {
        $edit_mode = true;
        $edit_id = $_GET['edit'];

        $stmt = $conn->prepare("SELECT * FROM patient_tbl WHERE id = :id AND emp_id = :emp_id");
        $stmt->execute([':id' => $edit_id, ':emp_id' => $emp_id]);
        $existing_patient_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_patient_data) {
            throw new Exception("Prescription record not found for editing.");
        }

        if (!empty($existing_patient_data['medicine'])) {
            $existing_medicines = parseMedicineString($existing_patient_data['medicine'], $medicinesIndexedByName);
        }
    } elseif (isset($_GET['clone']) && !empty($_GET['clone'])) {
        $clone_id = $_GET['clone'];

        $stmt = $conn->prepare("SELECT * FROM patient_tbl WHERE id = :id AND emp_id = :emp_id");
        $stmt->execute([':id' => $clone_id, ':emp_id' => $emp_id]);
        $existing_patient_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_patient_data) {
            throw new Exception("Prescription record to clone was not found.");
        }

        if (!empty($existing_patient_data['medicine'])) {
            $existing_medicines = parseMedicineString($existing_patient_data['medicine'], $medicinesIndexedByName);
        }
    }

    // --- HANDLE FORM SUBMISSION ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn->beginTransaction();

        $doctor_emp_id = $_POST['doctor_emp_id'] ?? '5620-0';
        $doctor_designation = $_POST['doctor_designation'] ?? 'Medical officer';
        $diseases = $_POST['disease_name'] ?? '';
        $advice = $_POST['advise'] ?? '';
        
        $special_med = !empty($_POST['special_med']) ? $_POST['special_med'] : NULL;

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

        $old_quantities_by_med_id = [];

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            $stmt = $conn->prepare("SELECT medicine FROM patient_tbl WHERE id = :id");
            $stmt->execute([':id' => $_POST['edit_id']]);
            $old_record = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($old_record['medicine'])) {
                $old_parsed = parseMedicineString($old_record['medicine'], $medicinesIndexedByName);
                foreach ($old_parsed as $old_item) {
                    if (!empty($old_item['medicine_id'])) {
                        $old_quantities_by_med_id[$old_item['medicine_id']] = intval($old_item['quantity']);
                    }
                }
            }
        }

        $formatted_medicines = [];

        foreach ($medicine_ids as $index => $med_val) {
            if (!empty($med_val)) {
                // Check if $med_val is numeric (ID from medicine_tbl) or string (Manual entry)
                if (is_numeric($med_val) && isset($medicinesIndexedById[$med_val])) {
                    $med_name = $medicinesIndexedById[$med_val]['med_name'];
                    $med_id   = $med_val;
                } else {
                    $med_name = trim($med_val);
                    $med_id   = null;
                }

                $brand    = $brands[$index] ?? '';
                $new_qty  = intval($quantities[$index] ?? 1);
                $unit     = $units[$index] ?? '';
                $dosage   = $dosages[$index] ?? '';
                $duration = $durations[$index] ?? '';

                // Stock management only applies to database medicines (with valid ID)
                if ($med_id) {
                    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
                        $old_qty = $old_quantities_by_med_id[$med_id] ?? 0;

                        $updateStockStmt = $conn->prepare("
                            UPDATE medicine_tbl 
                            SET current_stock = current_stock + :old_qty - :new_qty 
                            WHERE id = :id
                        ");
                        $updateStockStmt->execute([
                            ':old_qty' => $old_qty,
                            ':new_qty' => $new_qty,
                            ':id'      => $med_id
                        ]);

                        unset($old_quantities_by_med_id[$med_id]);
                    } else {
                        $deductStmt = $conn->prepare("UPDATE medicine_tbl SET current_stock = current_stock - :new_qty WHERE id = :id");
                        $deductStmt->execute([':new_qty' => $new_qty, ':id' => $med_id]);
                    }
                }

                $formatted_medicines[] = "{$med_name}, {$brand}, {$new_qty}, {$unit}, {$dosage}, {$duration}";
            }
        }

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            foreach ($old_quantities_by_med_id as $deleted_med_id => $restored_qty) {
                if ($restored_qty > 0) {
                    $restoreStmt = $conn->prepare("UPDATE medicine_tbl SET current_stock = current_stock + :qty WHERE id = :id");
                    $restoreStmt->execute([':qty' => $restored_qty, ':id' => $deleted_med_id]);
                }
            }
        }

        $medicine_string = implode(' || ', $formatted_medicines);

        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            $stmt = $conn->prepare("UPDATE patient_tbl SET 
                emp_designation = :emp_designation,
                doctor_emp_id = :doctor_emp_id,
                doctor_designation = :doctor_designation,
                medicine = :medicine,
                special_med = :special_med,
                advice = :advice,
                diseases = :diseases,
                updated_at = NOW()
                WHERE id = :id AND emp_id = :emp_id");

            $stmt->execute([
                ':emp_designation'    => $emp_designation,
                ':doctor_emp_id'      => $doctor_emp_id,
                ':doctor_designation' => $doctor_designation,
                ':medicine'           => $medicine_string,
                ':special_med'        => $special_med,
                ':advice'             => $advice,
                ':diseases'           => $diseases,
                ':id'                 => $_POST['edit_id'],
                ':emp_id'             => $emp_id
            ]);

            $_SESSION['success'] = "Prescription updated successfully!";
        } else {
            $stmt = $conn->prepare("INSERT INTO patient_tbl 
                (emp_id, emp_designation, doctor_emp_id, doctor_designation, medicine, special_med, advice, diseases, date, created_at, updated_at) 
                VALUES 
                (:emp_id, :emp_designation, :doctor_emp_id, :doctor_designation, :medicine, :special_med, :advice, :diseases, CURDATE(), NOW(), NOW())");

            $stmt->execute([
                ':emp_id'             => $emp_id,
                ':emp_designation'    => $emp_designation,
                ':doctor_emp_id'      => $doctor_emp_id,
                ':doctor_designation' => $doctor_designation,
                ':medicine'           => $medicine_string,
                ':special_med'        => $special_med,
                ':advice'             => $advice,
                ':diseases'           => $diseases
            ]);

            $_SESSION['success'] = "Prescription created successfully!";
        }

        $conn->commit();
        header("Location: prescription_list.php?id=" . urlencode($emp_id));
        exit();
    }

} catch(PDOException $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: prescription_list.php?id=" . urlencode($emp_id ?? ''));
    exit();
} catch(Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = $e->getMessage();
    header("Location: prescription_list.php?id=" . urlencode($emp_id ?? ''));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_mode ? 'Edit' : 'New' ?> Prescription - <?= htmlspecialchars($employee['name'] ?? '') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Select2 CSS & Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas <?= $edit_mode ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?> me-2"></i>
            <?= $edit_mode ? 'Edit Prescription' : 'New Prescription Entry' ?>
        </h2>
        <div>
            <a href="patient_mgtm.php?id=<?= urlencode($emp_id) ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <a href="prescription_list.php?id=<?= urlencode($emp_id) ?>" class="btn btn-secondary">
                <i class="fas fa-list me-1"></i> Prescription List
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
        <input type="hidden" name="special_med" id="special_med_input" value="<?= htmlspecialchars($existing_patient_data['special_med'] ?? '') ?>">
        
        <!-- Doctor & Diagnosis Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-stethoscope me-2"></i>Doctor & Diagnosis Info</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Doctor Emp ID</label>
                        <input type="text" name="doctor_emp_id" class="form-control" 
                               value="<?= htmlspecialchars($existing_patient_data['doctor_emp_id'] ?? '5620-0') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Doctor Designation</label>
                        <input type="text" name="doctor_designation" class="form-control" 
                               value="<?= htmlspecialchars($existing_patient_data['doctor_designation'] ?? 'Medical officer') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Disease Name / Diagnosis</label>
                        <input type="text" name="disease_name" id="disease_name" class="form-control" 
                               value="<?= htmlspecialchars($existing_patient_data['diseases'] ?? '') ?>" placeholder="e.g. Fever, Gastritis" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicines Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><i class="fas fa-pills me-2"></i>Prescribed Medicines</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-info text-white me-2" id="check-booked-btn">
                        <i class="fas fa-search me-1"></i> Check Booked Medicine
                    </button>
                    <button type="button" class="btn btn-sm btn-success" id="add-row-btn">
                        <i class="fas fa-plus me-1"></i> Add Medicine
                    </button>
                </div>
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
                                <th style="width: 20%;">Dosage</th>
                                <th style="width: 8%;">Duration</th>
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
                <h5 class="mb-0 text-primary"><i class="fas fa-comment-medical me-2"></i>Doctor's Advice</h5>
            </div>
            <div class="card-body">
                <textarea name="advise" id="advise" class="form-control" rows="3" placeholder="Enter advice for patient..."><?= htmlspecialchars($existing_patient_data['advice'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-save me-1"></i> <?= $edit_mode ? 'Update Prescription' : 'Save Prescription' ?>
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

    $(document).on('select2:open', () => {
    document.querySelector('.select2-container--open .select2-search__field').focus();
});


    const medicinesData = <?= json_encode($medicines) ?>;
    const existingMedicines = <?= json_encode($existing_medicines) ?>;
    const bookedMedicinesData = <?= json_encode($booked_medicines) ?>;

    function buildMedicineOptions(selectedVal = '', medName = '') {
        let options = '<option value="">-- Search / Type Custom Medicine --</option>';
        let isExistingSelected = false;

        medicinesData.forEach(med => {
            const isSelected = String(med.id) === String(selectedVal) ? 'selected' : '';
            if (isSelected) isExistingSelected = true;
            options += `<option value="${med.id}" ${isSelected}>${med.med_name}</option>`;
        });

        if (!isExistingSelected && medName) {
            options += `<option value="${medName}" selected>${medName}</option>`;
        }

        return options;
    }

    function createRow(data = {}) {
        const medId    = data.medicine_id || '';
        const medName  = data.med_name || '';
        const brand    = data.brand || '';
        const qty      = data.quantity || '';
        const unit     = data.unit || '';
        const dosage   = data.dosage || '';
        const duration = data.duration || '';
        const stock    = data.current_stock ?? 'N/A';

        const rowHtml = `
            <tr>
                <td>
                    <select name="medicine_id[]" class="form-select med-select" required style="width: 100%;">
                        ${buildMedicineOptions(medId, medName)}
                    </select>
                </td>
                <td><input type="text" name="brand[]" class="form-control brand-input" value="${brand}"></td>
                <td><input type="number" name="quantity[]" class="form-control qty-input" value="${qty}" min="1" placeholder="Qty"></td>
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
        
        const $newRow = $(rowHtml);
        $('#medicine-rows').append($newRow);

        // Initialize Select2 with Tagging support enabled
        $newRow.find('.med-select').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Search or Type Custom Medicine --',
            allowClear: true,
            tags: true,
            width: '100%',
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                }
            }
        });

        if (medId || medName) {
            $newRow.find('.med-select').trigger('change');
        }

        updateStockWarning($newRow);
    }

    function updateStockWarning(row) {
        const selectedVal = row.find('.med-select').val();
        const enteredQty = parseInt(row.find('.qty-input').val()) || 0;
        const found = medicinesData.find(m => String(m.id) === String(selectedVal));

        if (found) {
            const availableStock = parseInt(found.current_stock) || 0;
            const stockDisplay = row.find('.stock-display');

            if (enteredQty > availableStock) {
                stockDisplay.removeClass('text-primary text-success').addClass('text-danger');
                stockDisplay.html(`${availableStock} <i class="fas fa-exclamation-triangle" title="Low Stock"></i>`);
            } else {
                stockDisplay.removeClass('text-danger').addClass('text-primary');
                stockDisplay.text(availableStock);
            }
        } else {
            row.find('.stock-display').removeClass('text-danger').addClass('text-muted').text('N/A');
        }
    }

    $(document).ready(function() {
        $('#medicine-rows').empty();

        if (Array.isArray(existingMedicines) && existingMedicines.length > 0) {
            existingMedicines.forEach(item => {
                if (item.med_name || item.medicine_id) {
                    createRow(item);
                }
            });
        } else {
            createRow();
        }

        $('#add-row-btn').click(function() {
            createRow();
        });

        // "Check Booked Medicine" Button Event
        $('#check-booked-btn').click(function() {
            if (!Array.isArray(bookedMedicinesData) || bookedMedicinesData.length === 0) {
                alert('No special booked medicine found for this employee ID.');
                return;
            }

            $('#special_med_input').val('Booked Medicine');

            const firstRowSelect = $('#medicine-rows tr:first .med-select').val();
            if ($('#medicine-rows tr').length === 1 && (!firstRowSelect || firstRowSelect === '')) {
                $('#medicine-rows tr:first .med-select').select2('destroy');
                $('#medicine-rows').empty();
            }

            let loadedCount = 0;
            bookedMedicinesData.forEach(item => {
                createRow(item);
                loadedCount++;

                if (item.diseases && $('#disease_name').val().trim() === '') {
                    $('#disease_name').val(item.diseases);
                }
                if (item.advice && $('#advise').val().trim() === '') {
                    $('#advise').val(item.advice);
                }
            });

            alert(`${loadedCount} booked medicine record(s) loaded into prescription successfully!`);
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#medicine-rows tr').length > 1) {
                $(this).closest('tr').find('.med-select').select2('destroy');
                $(this).closest('tr').remove();
            } else {
                alert('At least one medicine row is required.');
            }
        });

        // AUTOMATICALLY FOCUS AND BLINK CURSOR ON SEARCH INPUT WHEN SELECT2 OPENS
        $(document).on('select2:open', () => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });

        $(document).on('change', '.med-select', function() {
            const selectedVal = $(this).val();
            const row = $(this).closest('tr');
            const found = medicinesData.find(m => String(m.id) === String(selectedVal));

            if (found) {
                row.find('.brand-input').val(found.brand || '');
                row.find('.qty-input').val(found.quantity ?? '');
                row.find('.unit-input').val(found.med_type || 'Tablet');
                row.find('.dose-input').val(found.dose || '1+0+1');
                row.find('.duration-input').val(found.duration || '7 days');
                row.find('.stock-display').removeClass('text-muted').addClass('text-primary').text(found.current_stock || 0);
            } else {
                if (selectedVal && selectedVal !== '') {
                    row.find('.unit-input').val('Tablet');
                    row.find('.dose-input').val('1+0+1');
                    row.find('.duration-input').val('7 days');
                } else {
                    row.find('.brand-input').val('');
                    row.find('.qty-input').val('');
                    row.find('.unit-input').val('');
                    row.find('.dose-input').val('');
                    row.find('.duration-input').val('');
                }
                row.find('.stock-display').removeClass('text-danger text-primary').addClass('text-muted').text('N/A');
            }

            updateStockWarning(row);
        });

        $(document).on('input change', '.qty-input', function() {
            const row = $(this).closest('tr');
            updateStockWarning(row);
        });
    });
</script>

</body>
</html>