<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "morning_glory_db";

// Define fee type options
$fee_type_options = [
    'Admission Fee',
    'Tuition Fee',
    'Exam Fee',
    'Library Fee',
    'Transport Fee'
];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID exists in URL
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    // Fetch fee record if ID exists
    $fee = null;
    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM fee_mgtm WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $fee = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$fee) {
            die("Record not found");
        }
    }

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate no duplicate fee types
        $fee_types = $_POST['fee_type'];
        if (count($fee_types) !== count(array_unique($fee_types))) {
            die("Error: Duplicate fee types are not allowed");
        }

        $entry_date = $_POST['entry_date'];
        $reg_no = $_POST['reg_no'];
        $name = $_POST['name'];
        $class = $_POST['class'];
        $roll_no = $_POST['roll_no'];
        $section = $_POST['section'];
        $fee_types_str = implode(',', $fee_types);
        $actual_fee = implode(',', $_POST['actual_fee']);
        $payment = implode(',', $_POST['payment']);
        $due = implode(',', $_POST['due']);

        if ($id) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE fee_mgtm SET 
                entry_date = :entry_date,
                reg_no = :reg_no,
                name = :name,
                class = :class,
                roll_no = :roll_no,
                section = :section,
                fee_types = :fee_types,
                actual_fee = :actual_fee,
                payment = :payment,
                due = :due
                WHERE id = :id");
                
            $stmt->bindParam(':id', $id);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO fee_mgtm 
                (entry_date, reg_no, name, class, roll_no, section, fee_types, actual_fee, payment, due)
                VALUES 
                (:entry_date, :reg_no, :name, :class, :roll_no, :section, :fee_types, :actual_fee, :payment, :due)");
        }

        $stmt->bindParam(':entry_date', $entry_date);
        $stmt->bindParam(':reg_no', $reg_no);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':roll_no', $roll_no);
        $stmt->bindParam(':section', $section);
        $stmt->bindParam(':fee_types', $fee_types_str);
        $stmt->bindParam(':actual_fee', $actual_fee);
        $stmt->bindParam(':payment', $payment);
        $stmt->bindParam(':due', $due);

        $stmt->execute();

        // Redirect back to the main page after saving
        header("Location: all_fees_details.php");
        exit();
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Edit' : 'Add' ?> Fee Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .fee-item {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .add-fee-btn {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .duplicate-error {
            color: red;
            font-size: 0.875em;
            display: none;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        option:disabled {
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $id ? 'Edit' : 'Add' ?> Fee Record</h2>
        <a href="all_fees_details.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form method="POST" action="" id="feeForm">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Student Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="entry_date" class="form-label required-field">Entry Date</label>
                        <input type="date" class="form-control" id="entry_date" name="entry_date" 
                            value="<?= isset($fee['entry_date']) ? htmlspecialchars($fee['entry_date']) : date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="reg_no" class="form-label required-field">Registration No</label>
                        <input type="text" class="form-control" id="reg_no" name="reg_no" 
                            value="<?= isset($fee['reg_no']) ? htmlspecialchars($fee['reg_no']) : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label required-field">Student Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                            value="<?= isset($fee['name']) ? htmlspecialchars($fee['name']) : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="class" class="form-label required-field">Class</label>
                        <input type="text" class="form-control" id="class" name="class" 
                            value="<?= isset($fee['class']) ? htmlspecialchars($fee['class']) : '' ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="roll_no" class="form-label required-field">Roll No</label>
                        <input type="text" class="form-control" id="roll_no" name="roll_no" 
                            value="<?= isset($fee['roll_no']) ? htmlspecialchars($fee['roll_no']) : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="section" class="form-label required-field">Section</label>
                        <input type="text" class="form-control" id="section" name="section" 
                            value="<?= isset($fee['section']) ? htmlspecialchars($fee['section']) : '' ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Fee Details</h5>
            </div>
            <div class="card-body">
                <div id="feeItemsContainer">
                    <?php
                    $fee_types = isset($fee['fee_types']) ? explode(',', $fee['fee_types']) : [''];
                    $actual_fees = isset($fee['actual_fee']) ? explode(',', $fee['actual_fee']) : [''];
                    $payments = isset($fee['payment']) ? explode(',', $fee['payment']) : [''];
                    $dues = isset($fee['due']) ? explode(',', $fee['due']) : [''];
                    
                    for ($i = 0; $i < count($fee_types); $i++):
                        $selected_types = array_slice($fee_types, 0, $i);
                    ?>
                    <div class="fee-item">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label required-field">Fee Type</label>
                                <select class="form-select fee-type-select" name="fee_type[]" required>
                                    <option value="" disabled <?= empty($fee_types[$i]) ? 'selected' : '' ?>>Select Type</option>
                                    <?php foreach ($fee_type_options as $option): 
                                        // Disable already selected options (except current one)
                                        $disabled = in_array($option, $selected_types) && $option !== $fee_types[$i];
                                        $selected = ($fee_types[$i] === $option) ? 'selected' : '';
                                    ?>
                                        <option value="<?= htmlspecialchars($option) ?>" <?= $selected ?> <?= $disabled ? 'disabled' : '' ?>>
                                            <?= htmlspecialchars($option) ?>
                                            <?= $disabled ? ' (already selected)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="duplicate-error">This fee type has already been selected</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required-field">Actual Fee</label>
                                <input type="number" step="0.01" class="form-control" name="actual_fee[]" 
                                    value="<?= htmlspecialchars($actual_fees[$i]) ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required-field">Payment</label>
                                <input type="number" step="0.01" class="form-control" name="payment[]" 
                                    value="<?= htmlspecialchars($payments[$i]) ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required-field">Due</label>
                                <input type="number" step="0.01" class="form-control" name="due[]" 
                                    value="<?= htmlspecialchars($dues[$i]) ?>" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <?php if ($i > 0): ?>
                                <button type="button" class="btn btn-danger btn-sm remove-fee-btn">
                                    <i class="fas fa-trash"></i> Remove Item
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <button type="button" id="addFeeBtn" class="btn btn-primary add-fee-btn mb-4">
                    <i class="fas fa-plus"></i> Add Another Fee Item
                </button>
            </div>
        <div class="d-grid gap-1 d-md-flex justify-content-md-end mb-0 p-1">
            <a href="all_fees_details.php" class="btn btn-secondary me-md-2">
                <i class="fas fa-times"></i> Back
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?= $id ? 'Update' : 'Save' ?> Record
            </button>
        </div>

        </div>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Define fee type options
    const feeTypeOptions = <?= json_encode($fee_type_options) ?>;
    let selectedFeeTypes = [];
    
    // Initialize with currently selected fee types
    $('.fee-type-select').each(function() {
        if ($(this).val()) {
            selectedFeeTypes.push($(this).val());
            $(this).data('previous-value', $(this).val());
        }
    });

    // Add new fee item
    $('#addFeeBtn').click(function() {
        const availableOptions = feeTypeOptions.filter(option => !selectedFeeTypes.includes(option));
        
        if (availableOptions.length === 0) {
            alert('All fee types have already been selected');
            return;
        }

        let optionsHtml = '<option value="" disabled selected>Select Type</option>';
        
        feeTypeOptions.forEach(option => {
            const disabled = selectedFeeTypes.includes(option);
            optionsHtml += `
                <option value="${option}" ${disabled ? 'disabled' : ''}>
                    ${option}${disabled ? ' (already selected)' : ''}
                </option>
            `;
        });

        const newItem = `
            <div class="fee-item">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label required-field">Fee Type</label>
                        <select class="form-select fee-type-select" name="fee_type[]" required>
                            ${optionsHtml}
                        </select>
                        <div class="duplicate-error">This fee type has already been selected</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required-field">Actual Fee</label>
                        <input type="number" step="0.01" class="form-control" name="actual_fee[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required-field">Payment</label>
                        <input type="number" step="0.01" class="form-control" name="payment[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required-field">Due</label>
                        <input type="number" step="0.01" class="form-control" name="due[]" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-fee-btn">
                            <i class="fas fa-trash"></i> Remove Item
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#feeItemsContainer').append(newItem);
    });

    // Remove fee item
    $(document).on('click', '.remove-fee-btn', function() {
        const item = $(this).closest('.fee-item');
        const selectedType = item.find('.fee-type-select').val();
        
        if (selectedType) {
            selectedFeeTypes = selectedFeeTypes.filter(type => type !== selectedType);
            updateFeeTypeOptions();
        }
        
        item.remove();
    });

    // Handle fee type selection change
    $(document).on('change', '.fee-type-select', function() {
        const selectedValue = $(this).val();
        const previousValue = $(this).data('previous-value');
        
        // Update previous value in tracking array
        if (previousValue) {
            selectedFeeTypes = selectedFeeTypes.filter(type => type !== previousValue);
        }
        
        // Update new value in tracking array
        if (selectedValue) {
            selectedFeeTypes.push(selectedValue);
        }
        
        $(this).data('previous-value', selectedValue);
        updateFeeTypeOptions();
        
        // Show/hide error message
        const isDuplicate = $(this).find('option:selected').prop('disabled');
        $(this).toggleClass('is-invalid', isDuplicate);
        $(this).next('.duplicate-error').toggle(isDuplicate);
    });

    // Update all select options based on selected fee types
    function updateFeeTypeOptions() {
        $('.fee-type-select').each(function() {
            const currentValue = $(this).val();
            $(this).find('option').each(function() {
                const optionValue = $(this).val();
                if (optionValue && optionValue !== currentValue) {
                    $(this).prop('disabled', selectedFeeTypes.includes(optionValue));
                    $(this).text(
                        optionValue + (selectedFeeTypes.includes(optionValue) ? ' (already selected)' : '')
                    );
                }
            });
        });
    }

    // Form submission validation
    $('#feeForm').on('submit', function(e) {
        let hasErrors = false;
        
        // Check for duplicate selections
        $('.fee-type-select').each(function() {
            if ($(this).find('option:selected').prop('disabled')) {
                $(this).addClass('is-invalid');
                $(this).next('.duplicate-error').show();
                hasErrors = true;
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fix the duplicate fee type selections before submitting');
            return false;
        }
        
        const feeTypes = $('[name="fee_type[]"]').map(function() {
            return $(this).val();
        }).get();
        
        if (new Set(feeTypes).size !== feeTypes.length) {
            alert('Error: Duplicate fee types are not allowed');
            e.preventDefault();
            return false;
        }
        
        return true;
    });

    // Calculate due automatically when payment changes
    $(document).on('change', 'input[name="payment[]"]', function() {
        const feeItem = $(this).closest('.fee-item');
        const actualFee = parseFloat(feeItem.find('input[name="actual_fee[]"]').val()) || 0;
        const payment = parseFloat($(this).val()) || 0;
        const due = actualFee - payment;
        feeItem.find('input[name="due[]"]').val(due.toFixed(2));
    });

    // Calculate due automatically when actual fee changes
    $(document).on('change', 'input[name="actual_fee[]"]', function() {
        const feeItem = $(this).closest('.fee-item');
        const actualFee = parseFloat($(this).val()) || 0;
        const payment = parseFloat(feeItem.find('input[name="payment[]"]').val()) || 0;
        const due = actualFee - payment;
        feeItem.find('input[name="due[]"]').val(due.toFixed(2));
    });
});
</script>
</body>
</html>