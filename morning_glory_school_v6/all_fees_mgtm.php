<?php
// session_name('emd_rent_db');

session_start();  
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";
      $result= $usercredentials->runBaseQuery($query);
      foreach ($result as $k => $v)      {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fee_types'])) {
        // Validate no duplicate fee types
        $fee_types = $_POST['fee_types'];
        if (count($fee_types) !== count(array_unique($fee_types))) {
            die("<div class='alert alert-danger text-center mt-2'>Error: Duplicate fee types are not allowed</div>");
        }

        $entry_date = date('Y-m-d H:i:s');
        $reg_no = $_POST['reg_no'] ?? '';
        $name = $_POST['name'] ?? '';
        $class = $_POST['class'] ?? '';
        $roll_no = $_POST['roll_no'] ?? '';
        $section = $_POST['section'] ?? '';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $actual_fees = $_POST['actual_fee'];
        $payments = $_POST['payment'];
        $initial_dues = $_POST['due'];

        $calculated_dues = [];

        for ($i = 0; $i < count($fee_types); $i++) {
            $actual_fee = is_numeric($actual_fees[$i]) ? (float)$actual_fees[$i] : 0;
            $payment = is_numeric($payments[$i]) ? (float)$payments[$i] : 0;
            $initial_due = is_numeric($initial_dues[$i]) ? (float)$initial_dues[$i] : 0;
            $remaining_due = max(0, ($actual_fee + $initial_due) - $payment);
            $calculated_dues[] = $remaining_due;
        }

        $fee_types_str = implode(',', $fee_types);
        $actual_fees_str = implode(',', $actual_fees);
        $payments_str = implode(',', $payments);
        $dues_str = implode(',', $calculated_dues);

        $sql = "INSERT INTO fee_mgtm 
            (entry_date, reg_no, name, class, roll_no, section, 
            fee_types, actual_fee, payment, due, created_at, updated_at)
            VALUES 
            (:entry_date, :reg_no, :name, :class, :roll_no, :section, 
            :fee_types, :actual_fee, :payment, :due, :created_at, :updated_at)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':entry_date', $entry_date);
        $stmt->bindValue(':reg_no', $reg_no);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':class', $class);
        $stmt->bindValue(':roll_no', $roll_no);
        $stmt->bindValue(':section', $section);
        $stmt->bindValue(':fee_types', $fee_types_str);
        $stmt->bindValue(':actual_fee', $actual_fees_str);
        $stmt->bindValue(':payment', $payments_str);
        $stmt->bindValue(':due', $dues_str);
        $stmt->bindValue(':created_at', $created_at);
        $stmt->bindValue(':updated_at', $updated_at);
        $stmt->execute();

        echo "<div class='alert alert-success text-center mt-2'>Record inserted successfully with multiple fees in a single row.</div>";
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger text-center mt-2'>Error: " . $e->getMessage() . "</div>";
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>New Morning Glory</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        .card-header {
            font-weight: bold;
        }
        .btn-action {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-uppercase bg-primary text-white">
            <i class="fa fa-money-bill"></i> Student Fee Collection
        </div>
        <div class="card-body">
            <form method="post" action="" id="feeForm">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="reg_no">Registration Number</label>
                        <input type="text" class="form-control" name="reg_no" required>
                    </div>
                    <div class="col-md-4">
                        <label for="name">Student Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="class">Class</label>
                        <input type="text" class="form-control" name="class" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="roll_no">Roll Number</label>
                        <input type="text" class="form-control" name="roll_no" required>
                    </div>
                    <div class="col-md-6">
                        <label for="section">Section</label>
                        <input type="text" class="form-control" name="section" required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dynamic_field">
                        <thead>
                            <tr>
                                <td colspan="5" class="text-end">
                                    <button type="button" name="add" id="add" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Add Fee
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Fee Type</th>
                                <th>Actual Fee</th>
                                <th>Initial Due</th>
                                <th>Pay</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1">
                                <td>
                                    <select class="form-select fee-type-select" name="fee_types[]" required>
                                        <option value="" selected disabled>Select Type</option>
                                        <?php foreach ($fee_type_options as $option): ?>
                                            <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="duplicate-error">This fee type has already been selected</div>
                                </td>
                                <td><input type="number" name="actual_fee[]" class="form-control" required></td>
                                <td><input type="number" name="due[]" class="form-control" value="0" required></td>
                                <td><input type="number" name="payment[]" class="form-control" required></td>
                                <td class="text-center">
                                    <button type="button" name="remove" id="1" class="btn btn-danger btn-sm btn_remove" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" name="submit" class="btn btn-primary btn-action">
                        <i class="fa fa-save"></i> Submit Fees
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary btn-action">
                        <i class="fa fa-home"></i> Back
                    </a>
                    <a href="all_fees_details.php" class="btn btn-info btn-action">
                        <i class="fa fa-list"></i> Details
                    </a>
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
    const feeTypeOptions = <?= json_encode($fee_type_options) ?>;
    let selectedFeeTypes = [];
    let rowCount = 1;

    // Function to update all select options
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

    // Add new fee row
    $('#add').click(function(){
        const availableOptions = feeTypeOptions.filter(option => !selectedFeeTypes.includes(option));
        
        if (availableOptions.length === 0) {
            alert('All fee types have already been selected');
            return;
        }

        rowCount++;
        $('#dynamic_field tbody').append(`
            <tr id="row${rowCount}">
                <td>
                    <select class="form-select fee-type-select" name="fee_types[]" required>
                        <option value="" selected disabled>Select Type</option>
                        ${feeTypeOptions.map(option => `
                            <option value="${option}" ${selectedFeeTypes.includes(option) ? 'disabled' : ''}>
                                ${option}${selectedFeeTypes.includes(option) ? ' (already selected)' : ''}
                            </option>
                        `).join('')}
                    </select>
                    <div class="duplicate-error">This fee type has already been selected</div>
                </td>
                <td><input type="number" name="actual_fee[]" class="form-control" required></td>
                <td><input type="number" name="due[]" class="form-control" value="0" required></td>
                <td><input type="number" name="payment[]" class="form-control" required></td>
                <td class="text-center">
                    <button type="button" name="remove" id="${rowCount}" class="btn btn-danger btn-sm btn_remove">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        // Enable remove button for first row if we have more than one row
        if (rowCount > 1) {
            $('#row1 .btn_remove').prop('disabled', false);
        }
    });

    // Remove fee row
    $(document).on('click', '.btn_remove', function(){
        const rowId = $(this).attr("id");
        const selectedType = $('#row'+rowId).find('.fee-type-select').val();
        
        if (selectedType) {
            selectedFeeTypes = selectedFeeTypes.filter(type => type !== selectedType);
            updateFeeTypeOptions();
        }
        
        $('#row'+rowId).remove();
        rowCount--;
        
        // Disable remove button for first row if only one row remains
        if (rowCount === 1) {
            $('#row1 .btn_remove').prop('disabled', true);
        }
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
        
        const feeTypes = $('[name="fee_types[]"]').map(function() {
            return $(this).val();
        }).get();
        
        if (new Set(feeTypes).size !== feeTypes.length) {
            alert('Error: Duplicate fee types are not allowed');
            e.preventDefault();
            return false;
        }
        
        return true;
    });

    // Auto-calculate due when payment changes
    $(document).on('change', 'input[name="payment[]"]', function() {
        const row = $(this).closest('tr');
        const actualFee = parseFloat(row.find('input[name="actual_fee[]"]').val()) || 0;
        const initialDue = parseFloat(row.find('input[name="due[]"]').val()) || 0;
        const payment = parseFloat($(this).val()) || 0;
        const remainingDue = Math.max(0, (actualFee + initialDue) - payment);
        row.find('input[name="due[]"]').val(remainingDue.toFixed(2));
    });
});
</script>
</body>
</html>


<?php } ?>
