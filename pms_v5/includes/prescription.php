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

    // Get user information
    $stmt = $conn->prepare("SELECT * FROM users WHERE emp_id = :emp_id");
    $stmt->execute(['emp_id' => $emp_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Employee not found.");
    }

    // Get all medicines from medicine_tbl
    $stmt = $conn->query("SELECT id, med_name, brand FROM medicine_tbl ORDER BY med_name");
    $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Entry - <?= htmlspecialchars($user['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
        }
        .duplicate-error {
            color: #dc3545;
            font-size: 0.875em;
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h4>Prescription Entry for <?= htmlspecialchars($user['name']) ?></h4>
        <div>
            <button onclick="window.print();" class="btn btn-primary me-2">
                <i class="fas fa-print"></i> Print Page
            </button>
            <a href="javascript:history.back();" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="prescription_list.php?id=<?= urlencode($emp_id) ?>" class="btn btn-secondary">
                <i class="fas fa-list"></i> Prescription List
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <strong>Patient Information</strong>
        </div>
        <div class="card-body">
            <p><strong>Employee ID:</strong> <?= htmlspecialchars($user['emp_id']) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Designation:</strong> <?= htmlspecialchars($user['designation']) ?></p>
            <p><strong>Division:</strong> <?= htmlspecialchars($user['division']) ?></p>
            <p><strong>Section:</strong> <?= htmlspecialchars($user['section']) ?></p>
            <p><strong>Employee Type:</strong> <?= htmlspecialchars($user['employee_type']) ?></p>
        </div>
    </div>

    <div class="card mt-4 shadow">
        <div class="card-header bg-success text-white">
            <strong>Enter Prescription Details</strong>
        </div>
        <div class="card-body">
            <form method="post" action="save_prescription.php" id="prescriptionForm">
                <input type="hidden" name="emp_id" value="<?= htmlspecialchars($user['emp_id']) ?>">

                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="disease_name" class="form-label">Disease Name</label>
                    <input type="text" class="form-control" id="disease_name" name="disease_name" required>
                </div>

                <div class="mb-3">
                    <label for="advise" class="form-label">Advise</label>
                    <textarea class="form-control" id="advise" name="advise" rows="2"></textarea>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="medicineTable">
                        <thead>
                            <tr>
                                <td colspan="5" class="text-end">
                                    <button type="button" name="add" id="addMedicine" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Add Medicine
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Medicine Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Dosage Instructions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="medicineRow1">
                                <td>
                                    <select class="form-select medicine-select" name="medicine_id[]" required>
                                        <option value="" selected disabled>Select Medicine</option>
                                        <?php foreach ($medicines as $med): ?>
                                            <option value="<?= $med['id'] ?>">
                                                <?= htmlspecialchars($med['med_name']) ?> (<?= htmlspecialchars($med['brand']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="duplicate-error">This medicine has already been selected</div>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" class="form-control" min="1" required>
                                </td>
                                <td>
                                    <select class="form-control" name="unit[]" required>
                                        <option value="" disabled selected>--Select--</option> 
                                        <option value="Pcs">Pcs</option>
                                        <option value="Pata">Pata</option>
                                        <option value="Nos">Nos</option>
                                        <option value="Dorzon">Dorzon</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="dosage[]" class="form-control" placeholder="e.g., 1-0-1 after meal" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" name="remove" class="btn btn-danger btn-sm btn-remove" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3 no-print">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Save Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    let medicineRowCount = 1;
    const medicineOptions = <?= json_encode($medicines) ?>;
    let selectedMedicines = [];

    // Add new medicine row
    $('#addMedicine').click(function(){
        const availableMedicines = medicineOptions.filter(med => 
            !selectedMedicines.includes(med.id.toString())
        );
        
        if (availableMedicines.length === 0) {
            alert('All available medicines have already been added');
            return;
        }

        medicineRowCount++;
        $('#medicineTable tbody').append(`
            <tr id="medicineRow${medicineRowCount}">
                <td>
                    <select class="form-select medicine-select" name="medicine_id[]" required>
                        <option value="" selected disabled>Select Medicine</option>
                        ${medicineOptions.map(med => `
                            <option value="${med.id}" ${selectedMedicines.includes(med.id.toString()) ? 'disabled' : ''}>
                                ${med.med_name} (${med.brand})
                            </option>
                        `).join('')}
                    </select>
                    <div class="duplicate-error">This medicine has already been selected</div>
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control" min="1" required>
                </td>
                <td>
                    <select class="form-control" name="unit[]" required>
                        <option value="" disabled selected>--Select--</option> 
                        <option value="Pcs">Pcs</option>
                        <option value="Pata">Pata</option>
                        <option value="Nos">Nos</option>
                        <option value="Dorzon">Dorzon</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="dosage[]" class="form-control" placeholder="e.g., 1-0-1 after meal" required>
                </td>
                <td class="text-center">
                    <button type="button" name="remove" class="btn btn-danger btn-sm btn-remove">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        // Enable remove button for first row if we have more than one row
        if (medicineRowCount > 1) {
            $('#medicineRow1 .btn-remove').prop('disabled', false);
        }
    });

    // Remove medicine row
    $(document).on('click', '.btn-remove', function(){
        const row = $(this).closest('tr');
        const selectedMedicineId = row.find('.medicine-select').val();
        
        if (selectedMedicineId) {
            selectedMedicines = selectedMedicines.filter(id => id !== selectedMedicineId);
            updateMedicineOptions();
        }
        
        row.remove();
        medicineRowCount--;
        
        // Disable remove button for first row if only one row remains
        if (medicineRowCount === 1) {
            $('#medicineRow1 .btn-remove').prop('disabled', true);
        }
    });

    // Handle medicine selection change
    $(document).on('change', '.medicine-select', function() {
        const selectedValue = $(this).val();
        const previousValue = $(this).data('previous-value');
        
        // Update previous value in tracking array
        if (previousValue) {
            selectedMedicines = selectedMedicines.filter(id => id !== previousValue);
        }
        
        // Update new value in tracking array
        if (selectedValue) {
            selectedMedicines.push(selectedValue);
        }
        
        $(this).data('previous-value', selectedValue);
        updateMedicineOptions();
        
        // Show/hide error message
        const isDuplicate = $(this).find('option:selected').prop('disabled');
        $(this).toggleClass('is-invalid', isDuplicate);
        $(this).next('.duplicate-error').toggle(isDuplicate);
    });

    // Update medicine select options based on selected medicines
    function updateMedicineOptions() {
        $('.medicine-select').each(function() {
            const currentValue = $(this).val();
            $(this).find('option').each(function() {
                const optionValue = $(this).val();
                if (optionValue && optionValue !== currentValue) {
                    $(this).prop('disabled', selectedMedicines.includes(optionValue));
                    $(this).text(
                        $(this).text().replace(' (already selected)', '') + 
                        (selectedMedicines.includes(optionValue) ? ' (already selected)' : '')
                    );
                }
            });
        });
    }

    // Form submission validation
    $('#prescriptionForm').on('submit', function(e) {
        let hasErrors = false;
        
        // Check for duplicate medicine selections
        $('.medicine-select').each(function() {
            if ($(this).find('option:selected').prop('disabled')) {
                $(this).addClass('is-invalid');
                $(this).next('.duplicate-error').show();
                hasErrors = true;
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fix the duplicate medicine selections before submitting');
            return false;
        }
        
        return true;
    });
});
</script>
</body>
</html>