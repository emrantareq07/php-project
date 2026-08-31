<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$table = 'officers_tbl';

// Check if edit ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No record ID provided for editing.";
    header("Location: admin_officers.php");
    exit;
}

$edit_id = $conn->real_escape_string($_GET['id']);

// Fetch the record to edit
$sql = "SELECT * FROM $table WHERE id = '$edit_id'";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    $_SESSION['error'] = "Record not found.";
    header("Location: admin_officers.php");
    exit;
}

$record = $result->fetch_assoc();

// Departments and Grades
$sections1 = [
    'General Admin', 'Security', 'Medical', 'College', 'School', 'Library',
    'Accounts', 'ICT','Commercial', 'Production (Chemical)', 'Production (Chemist)', 
    'Engineering (Mechanical)', 'Engineering (Electrical + Instrument + Others)',
    'Engineering (Civil)', 'Forest/FRM'
];

$sections = [
    'সাধারণ প্রশাসন', 'নিরাপত্তা', 'চিকিৎসা', 'কলেজ', 'স্কুল', 'লাইব্রেরি',
    'হিসাব/অর্থ', 'আইসিটি','বাণিজ্যিক', 'প্রোডাকশন (কেমিক্যাল ইঞ্জিনিয়ারিং', 'প্রোডাকশন (কেমিস্ট)', 
    'ইঞ্জিনিয়ারিং (মেকানিক্যাল)', 'ইঞ্জিনিয়ারিং (ইলেকট্রিক্যাল + ইন্সট্রুমেন্ট + অন্যান্য)',
    'ইঞ্জিনিয়ারিং (সিভিল)', 'বন/এফআরএম'
];

$grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];

// Parse the existing data for the form
$male_values = !empty($record['male']) ? explode(',', $record['male']) : array_fill(0, count($sections1) * count($grades), '0');
$female_values = !empty($record['female']) ? explode(',', $record['female']) : array_fill(0, count($sections1) * count($grades), '0');

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $factory_name = $conn->real_escape_string($_POST['factory_name']);
    $date = $conn->real_escape_string($_POST['date']);
    $designation = $conn->real_escape_string($_POST['designation']);
    $grade_list = $conn->real_escape_string($_POST['grade']);
    $sanctioned_post = $conn->real_escape_string($_POST['sanctioned_post']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Collect data from the table form
    $male_data = [];
    $female_data = [];
    
    foreach ($sections1 as $section_index => $section) {
        foreach ($grades as $grade) {
            $male_key = "data[{$section_index}][{$grade}_m]";
            $female_key = "data[{$section_index}][{$grade}_f]";
            
            $male_value = isset($_POST['data'][$section_index][$grade . '_m']) ? intval($_POST['data'][$section_index][$grade . '_m']) : 0;
            $female_value = isset($_POST['data'][$section_index][$grade . '_f']) ? intval($_POST['data'][$section_index][$grade . '_f']) : 0;
            
            $male_data[] = $male_value;
            $female_data[] = $female_value;
        }
    }
    
    $male_csv = implode(',', $male_data);
    $female_csv = implode(',', $female_data);
    
    // Calculate total
    $male_total = array_sum($male_data);
    $female_total = array_sum($female_data);
    $total = $male_total + $female_total;

    // Update query
    $update_sql = "UPDATE $table SET 
                  factory_name = '$factory_name',
                  date = '$date',
                  designation = '$designation',
                  grade = '$grade_list',
                  sanctioned_post = '$sanctioned_post',
                  male = '$male_csv',
                  female = '$female_csv',
                  total = '$total',
                  status = '$status',
                  updated_at = NOW()
                  WHERE id = '$edit_id'";

    if ($conn->query($update_sql)) {
        $_SESSION['message'] = "Record updated successfully!";
        header("Location: admin_officers.php");
        exit;
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Officer | Man Power Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        .has-data {
            background-color: #e8f5e8 !important;
            border-color: #28a745 !important;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .male-col { background-color: #e3f2fd !important; }
        .female-col { background-color: #fce4ec !important; }
        .total-col { background-color: #f5f5f5 !important; }
        .department-column { min-width: 200px; background-color: #f8f9fa !important; }
        .column-header { background-color: #e9ecef !important; }
        .input-comfort { 
            font-size: 1.1rem !important; 
            padding: 0.5rem 0.3rem !important;
            text-align: center !important;
        }
        .table-responsive { max-height: 65vh; }
        .sticky-top { position: sticky; top: 0; z-index: 10; }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Officer Record</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Current Data Preview -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Current Data Preview</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Factory:</strong> <?php echo htmlspecialchars($record['factory_name']); ?>
                </div>
                <div class="col-md-3">
                    <strong>Date:</strong> <?php echo date('d-m-Y', strtotime($record['date'])); ?>
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong> 
                    <span class="badge bg-<?php echo $record['status'] == 'active' ? 'success' : 'secondary'; ?>">
                        <?php echo ucfirst($record['status']); ?>
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Last Updated:</strong> <?php echo date('d-m-Y H:i', strtotime($record['updated_at'])); ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <strong>Grades:</strong> <?php echo htmlspecialchars($record['grade']); ?>
                </div>
                <div class="col-md-4">
                    <strong>Male Values:</strong> <?php echo htmlspecialchars($record['male']); ?>
                </div>
                <div class="col-md-4">
                    <strong>Female Values:</strong> <?php echo htmlspecialchars($record['female']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Record Details</h5>
        </div>
        
        <form method="POST" action="">
            <!-- Basic Information -->
            <div class="card-header bg-light py-3">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="factory_name" class="form-label"><strong>Factory Name</strong></label>
                        <input type="text" class="form-control" id="factory_name" name="factory_name" 
                               value="<?php echo htmlspecialchars($record['factory_name']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="form-label"><strong>Date</strong></label>
                        <input type="date" class="form-control" id="date" name="date" 
                               value="<?php echo $record['date']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label"><strong>Status</strong></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active" <?php echo $record['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $record['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label for="designation" class="form-label"><strong>Designation</strong></label>
                        <input type="text" class="form-control" id="designation" name="designation" 
                               value="<?php echo htmlspecialchars($record['designation']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="grade" class="form-label"><strong>Grade List</strong></label>
                        <input type="text" class="form-control" id="grade" name="grade" 
                               value="<?php echo htmlspecialchars($record['grade']); ?>" 
                               placeholder="e.g., Grade 11,Grade 13">
                    </div>
                    <div class="col-md-4">
                        <label for="sanctioned_post" class="form-label"><strong>Sanctioned Post</strong></label>
                        <input type="text" class="form-control" id="sanctioned_post" name="sanctioned_post" 
                               value="<?php echo htmlspecialchars($record['sanctioned_post']); ?>">
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="department-column">Department</th>
                                <?php foreach($grades as $grade): ?>
                                <th colspan="3" class="text-center border-start column-header">
                                    <span class="badge grade-badge bg-dark">Grade <?php echo substr($grade, 1); ?></span>
                                </th>
                                <?php endforeach; ?>
                                <th colspan="3" class="text-center border-start bg-light column-header">Department Total</th>
                            </tr>
                            <tr>
                                <th class="department-column"></th>
                                <?php foreach($grades as $grade): ?>
                                <th class="male-col text-center column-header"><i class="fas fa-male me-1"></i>Male</th>
                                <th class="female-col text-center column-header"><i class="fas fa-female me-1"></i>Female</th>
                                <th class="total-col text-center border-end column-header">Total</th>
                                <?php endforeach; ?>
                                <th class="male-col text-center column-header">Male</th>
                                <th class="female-col text-center column-header">Female</th>
                                <th class="total-col text-center column-header">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $data_index = 0;
                            foreach ($sections1 as $index => $section): 
                            ?>
                            <tr class="section-row">
                                <td class="section-name department-column">
                                    <strong><?php echo $section; ?></strong>
                                </td>
                                
                                <?php foreach($grades as $grade): 
                                    $current_male = isset($male_values[$data_index]) ? $male_values[$data_index] : '0';
                                    $current_female = isset($female_values[$data_index]) ? $female_values[$data_index] : '0';
                                    $data_index++;
                                ?>
                                <td class="male-col">
                                    <input type="number" class="form-control input-comfort input-highlight <?php echo $grade; ?>_m" 
                                           name="data[<?php echo $index; ?>][<?php echo $grade; ?>_m]" 
                                           min="0" value="<?php echo $current_male; ?>" 
                                           data-grade="<?php echo $grade; ?>" 
                                           data-section="<?php echo $index; ?>"
                                           placeholder="0">
                                </td>
                                <td class="female-col">
                                    <input type="number" class="form-control input-comfort input-highlight <?php echo $grade; ?>_f" 
                                           name="data[<?php echo $index; ?>][<?php echo $grade; ?>_f]" 
                                           min="0" value="<?php echo $current_female; ?>" 
                                           data-grade="<?php echo $grade; ?>" 
                                           data-section="<?php echo $index; ?>"
                                           placeholder="0">
                                </td>
                                <td class="total-col text-center border-end">
                                    <span class="total-display <?php echo $grade; ?>_total"><?php echo $current_male + $current_female; ?></span>
                                </td>
                                <?php endforeach; ?>
                                
                                <td class="male-col text-center">
                                    <span class="total-display section_male">0</span>
                                </td>
                                <td class="female-col text-center">
                                    <span class="total-display section_female">0</span>
                                </td>
                                <td class="total-col text-center">
                                    <span class="total-display section_total" style="color: #2c5aa0;">0</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <!-- Totals Row -->
                            <tr class="table-active">
                                <td class="department-column">
                                    <strong class="h6">GRAND TOTALS</strong>
                                </td>
                                
                                <?php foreach($grades as $grade): ?>
                                <td class="male-col text-center">
                                    <strong class="total-display total_<?php echo $grade; ?>_m">0</strong>
                                </td>
                                <td class="female-col text-center">
                                    <strong class="total-display total_<?php echo $grade; ?>_f">0</strong>
                                </td>
                                <td class="total-col text-center border-end">
                                    <strong class="total-display total_<?php echo $grade; ?>" style="color: #2c5aa0;">0</strong>
                                </td>
                                <?php endforeach; ?>
                                
                                <td class="male-col text-center bg-primary text-white">
                                    <strong class="total-display" id="finalMaleTotal"><?php 
                                        $male_total = 0;
                                        if (!empty($record['male'])) {
                                            $male_numbers = explode(',', $record['male']);
                                            foreach ($male_numbers as $num) {
                                                $male_total += intval(trim($num));
                                            }
                                        }
                                        echo $male_total;
                                    ?></strong>
                                </td>
                                <td class="female-col text-center bg-danger text-white">
                                    <strong class="total-display" id="finalFemaleTotal"><?php 
                                        $female_total = 0;
                                        if (!empty($record['female'])) {
                                            $female_numbers = explode(',', $record['female']);
                                            foreach ($female_numbers as $num) {
                                                $female_total += intval(trim($num));
                                            }
                                        }
                                        echo $female_total;
                                    ?></strong>
                                </td>
                                <td class="total-col text-center bg-success text-white">
                                    <strong class="total-display" id="finalGrandTotal"><?php echo $record['total']; ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-light py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>Update the values and click "Update Record" to save changes.</small>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="show_all_officer.php" class="btn btn-outline-secondary btn-lg px-4 me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        <button class="btn btn-warning btn-lg px-4" type="submit">
                            <i class="fas fa-sync me-1"></i> Update Record
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Calculate totals in real-time
$(document).ready(function() {
    function calculateTotals() {
        let finalMaleTotal = 0;
        let finalFemaleTotal = 0;
        let gradeTotals = {};
        
        // Initialize grade totals
        <?php foreach($grades as $grade): ?>
        gradeTotals['<?php echo $grade; ?>_m'] = 0;
        gradeTotals['<?php echo $grade; ?>_f'] = 0;
        gradeTotals['<?php echo $grade; ?>'] = 0;
        <?php endforeach; ?>
        
        // Calculate section and grade totals
        $('.section-row').each(function() {
            let sectionMale = 0;
            let sectionFemale = 0;
            
            <?php foreach($grades as $grade): ?>
            const <?php echo $grade; ?>_m = parseInt($(this).find('.<?php echo $grade; ?>_m').val()) || 0;
            const <?php echo $grade; ?>_f = parseInt($(this).find('.<?php echo $grade; ?>_f').val()) || 0;
            const <?php echo $grade; ?>_total = <?php echo $grade; ?>_m + <?php echo $grade; ?>_f;
            
            // Update grade display
            $(this).find('.<?php echo $grade; ?>_total').text(<?php echo $grade; ?>_total);
            
            // Accumulate grade totals
            gradeTotals['<?php echo $grade; ?>_m'] += <?php echo $grade; ?>_m;
            gradeTotals['<?php echo $grade; ?>_f'] += <?php echo $grade; ?>_f;
            gradeTotals['<?php echo $grade; ?>'] += <?php echo $grade; ?>_total;
            
            // Accumulate section totals
            sectionMale += <?php echo $grade; ?>_m;
            sectionFemale += <?php echo $grade; ?>_f;
            <?php endforeach; ?>
            
            const sectionTotal = sectionMale + sectionFemale;
            
            // Update section totals
            $(this).find('.section_male').text(sectionMale);
            $(this).find('.section_female').text(sectionFemale);
            $(this).find('.section_total').text(sectionTotal);
            
            // Accumulate final totals
            finalMaleTotal += sectionMale;
            finalFemaleTotal += sectionFemale;
        });
        
        // Update grade totals in footer
        <?php foreach($grades as $grade): ?>
        $('.total_<?php echo $grade; ?>_m').text(gradeTotals['<?php echo $grade; ?>_m']);
        $('.total_<?php echo $grade; ?>_f').text(gradeTotals['<?php echo $grade; ?>_f']);
        $('.total_<?php echo $grade; ?>').text(gradeTotals['<?php echo $grade; ?>']);
        <?php endforeach; ?>
        
        // Update final totals
        const finalGrandTotal = finalMaleTotal + finalFemaleTotal;
        $('#finalMaleTotal').text(finalMaleTotal);
        $('#finalFemaleTotal').text(finalFemaleTotal);
        $('#finalGrandTotal').text(finalGrandTotal);
    }
    
    // Calculate on input change
    $('input[type="number"]').on('input', calculateTotals);
    
    // Initial calculation
    calculateTotals();
});
</script>

</body>
</html>