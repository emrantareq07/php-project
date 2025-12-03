<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$table = 'officers_tbl';

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));

// Calculate first day of next month for edit restrictions
$first_day_next_month = date('Y-m-01', strtotime('+1 month'));

// Fetch existing records for the table
$records = [];
$sql = "SELECT * FROM $table ORDER BY date DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

// Departments and Grades
$sections = [
    'General Admin', 'Security', 'Medical', 'College', 'School', 'Library',
    'Accounts', 'ICT', 'Production (Chemical)', 'Production (Chemist)', 
    'Engineering (Mechanical)', 'Engineering (Electrical + Instrument + Others)',
    'Engineering (Civil)', 'Forest/FRM'
];

$grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Management Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .male-col {
      background-color: #d1ecf1 !important;
    }
    .female-col {
      background-color: #f8d7da !important;
    }
    .total-col {
      background-color: #e9ecef !important;
      font-weight: bold;
    }
    .grade-total-row {
      background-color: #fff3cd !important;
      font-weight: bold;
    }
    .grand-total-row {
      background-color: #d4edda !important;
      font-weight: bold;
    }
    .section-header {
      background-color: #4a6572 !important;
      color: white;
      font-weight: bold;
    }
    .grade-header {
      background-color: #344955 !important;
      color: white;
    }
    .table-fixed-header thead th {
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .table-container {
      max-height: 70vh;
      overflow-y: auto;
    }
    .input-highlight:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    .quick-actions {
      position: sticky;
      top: 0;
      background: white;
      z-index: 100;
      padding: 10px;
      border-bottom: 1px solid #dee2e6;
    }
    .disabled-record {
      background-color: #f8f9fa !important;
      opacity: 0.7;
    }
    .sticky-col {
      position: sticky;
      left: 0;
      background: white;
      z-index: 5;
      border-right: 2px solid #dee2e6;
    }
  </style>
</head>

<body class="bg-light">
<div class="container-fluid py-4">

  <h3 class="text-center mb-4"><i class="fas fa-users me-2"></i>Employee Management Dashboard</h3>

  <!-- Quick Actions -->
  <div class="quick-actions card mb-4">
    <div class="card-body py-2">
      <div class="row align-items-center">
        <div class="col-md-6">
          <button class="btn btn-outline-primary btn-sm me-2" id="clearAll">
            <i class="fas fa-eraser me-1"></i>Clear All
          </button>
          <button class="btn btn-outline-success btn-sm me-2" id="fillZeros">
            <i class="fas fa-sync me-1"></i>Fill Zeros
          </button>
          <button class="btn btn-outline-info btn-sm" id="copyPrevious">
            <i class="fas fa-copy me-1"></i>Copy Previous Month
          </button>
        </div>
        <div class="col-md-6 text-end">
          <span class="badge bg-primary me-2">Male: <span id="quickMaleTotal">0</span></span>
          <span class="badge bg-danger me-2">Female: <span id="quickFemaleTotal">0</span></span>
          <span class="badge bg-success">Total: <span id="quickGrandTotal">0</span></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Form -->
  <form id="employeeForm" class="card p-4 shadow-sm mb-4">
    <input type="hidden" name="id" id="id">
    <div class="row g-3">
      <div class="row g-3 align-items-center mb-4">
        <div class="col-auto">
          <label for="factory_name" class="col-form-label fw-bold">Factory Name: </label>
        </div>
        <div class="col-auto">
          <input type="text" name="factory_name" class="form-control" value="<?php echo $username; ?>" readonly>
        </div>    
        <div class="col-auto">
          <label for="date" class="col-form-label fw-bold">Date: </label>
        </div>
        <div class="col-auto">
          <input type="date" name="date" class="form-control" id="date" value="<?php echo $today_date; ?>">
        </div>    
      </div>
    
      <div class="fee-section">
        <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Officers Data</h5>
        <div class="table-container">
          <table class="table table-bordered table-hover" id="exam_fees_table">
            <thead class="table-fixed-header">
              <tr class="section-header">
                <th class="sticky-col">Administration</th>
                <?php foreach($grades as $grade): ?>
                <th colspan="3" class="text-center grade-header">Grade-<?php echo strtoupper(substr($grade, 1)); ?></th>
                <?php endforeach; ?>
                <th colspan="3" class="text-center bg-primary text-white">Grand Total</th>
              </tr>
              <tr class="section-header">
                <th class="sticky-col"></th>
                <?php foreach($grades as $grade): ?>
                <th class="male-col">Male</th>
                <th class="female-col">Female</th>
                <th class="total-col">Total</th>
                <?php endforeach; ?>
                <th class="male-col">Male</th>
                <th class="female-col">Female</th>
                <th class="total-col">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sections as $index => $section): ?>
              <tr>
                <td class="sticky-col fw-bold"><?php echo $section; ?></td>
                <?php foreach($grades as $grade): ?>
                <!-- Grade Inputs -->
                <td class="male-col">
                  <input type="number" class="form-control input-highlight <?php echo $grade; ?>_m" 
                         name="data[<?php echo $index; ?>][<?php echo $grade; ?>_m]" 
                         step="1" min="0" value="0" data-grade="<?php echo $grade; ?>">
                </td>
                <td class="female-col">
                  <input type="number" class="form-control input-highlight <?php echo $grade; ?>_f" 
                         name="data[<?php echo $index; ?>][<?php echo $grade; ?>_f]" 
                         step="1" min="0" value="0" data-grade="<?php echo $grade; ?>">
                </td>
                <td class="total-col">
                  <input type="number" class="form-control <?php echo $grade; ?>_total" 
                         name="data[<?php echo $index; ?>][<?php echo $grade; ?>_total]" 
                         step="1" min="0" value="0" readonly>
                </td>
                <?php endforeach; ?>
                <!-- Section Total -->
                <td class="male-col">
                  <input type="number" class="form-control section_male" 
                         name="data[<?php echo $index; ?>][section_male]" value="0" readonly>
                </td>
                <td class="female-col">
                  <input type="number" class="form-control section_female" 
                         name="data[<?php echo $index; ?>][section_female]" value="0" readonly>
                </td>
                <td class="total-col">
                  <input type="number" class="form-control section_total" 
                         name="data[<?php echo $index; ?>][section_total]" value="0" readonly>
                </td>
              </tr>
              <?php endforeach; ?>
              
              <!-- Grade-wise Total Row -->
              <tr class="grade-total-row">
                <td class="sticky-col"><strong>Total</strong></td>
                <?php foreach($grades as $grade): ?>
                <td class="male-col">
                  <input type="number" class="form-control total_<?php echo $grade; ?>_m" 
                         name="totals[<?php echo $grade; ?>_m]" value="0" readonly>
                </td>
                <td class="female-col">
                  <input type="number" class="form-control total_<?php echo $grade; ?>_f" 
                         name="totals[<?php echo $grade; ?>_f]" value="0" readonly>
                </td>
                <td class="total-col">
                  <input type="number" class="form-control total_<?php echo $grade; ?>" 
                         name="totals[<?php echo $grade; ?>_total]" value="0" readonly>
                </td>
                <?php endforeach; ?>
                <!-- Overall Totals -->
                <td class="male-col">
                  <strong>Total Male: <span class="display_total_male">0</span></strong>
                </td>
                <td class="female-col">
                  <strong>Total Female: <span class="display_total_female">0</span></strong>
                </td>
                <td class="total-col">
                  <strong>Overall: <span class="display_grand_total">0</span></strong>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12 text-center mt-4">
        <button class="btn btn-success px-5 btn-lg" type="submit" id="submitBtn">
          <i class="fas fa-save me-2"></i>Save Data
        </button>
        <button class="btn btn-secondary px-5 btn-lg" type="button" id="cancelBtn" style="display:none;">
          <i class="fas fa-times me-2"></i>Cancel
        </button>
      </div>
    </div>
  </form>

  <!-- Records Table -->
  <div class="card p-4 shadow-sm">
    <h5 class="mb-3"><i class="fas fa-history me-2"></i>Saved Records</h5>
    <div class="table-responsive">
      <table class="table table-bordered" id="recordsTable">
        <thead>
          <tr>
            <th>Date</th>
            <?php foreach($grades as $grade): ?>
            <th>G<?php echo substr($grade, 1); ?> Male</th>
            <th>G<?php echo substr($grade, 1); ?> Female</th>
            <?php endforeach; ?>
            <th>Total Employees</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $record): 
            // Check if record is editable (only current and future months)
            $record_date = $record['date'];
            $is_editable = ($record_date >= $first_day_next_month);
          ?>
          <tr class="<?php echo $is_editable ? '' : 'disabled-record'; ?>">
            <td><?php echo $record['date']; ?></td>
            <?php 
            $total_employees = 0;
            foreach($grades as $grade): 
              $grade_m = array_sum(explode(',', $record[$grade.'_m']));
              $grade_f = array_sum(explode(',', $record[$grade.'_f']));
              $total_employees += $grade_m + $grade_f;
            ?>
            <td><?php echo $grade_m; ?></td>
            <td><?php echo $grade_f; ?></td>
            <?php endforeach; ?>
            <td><strong><?php echo $total_employees; ?></strong></td>
            <td>
              <?php if($is_editable): ?>
              <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $record['id']; ?>">
                <i class="fas fa-edit me-1"></i>Edit
              </button>
              <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $record['id']; ?>">
                <i class="fas fa-trash me-1"></i>Delete
              </button>
              <?php else: ?>
              <span class="text-muted"><i class="fas fa-lock me-1"></i>Locked</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
$(document).ready(function() {
  // Initialize DataTable
  $('#recordsTable').DataTable({
    pageLength: 10,
    order: [[0, 'desc']]
  });

  // Function to calculate totals for a row
  function calculateRowTotals(row) {
    let section_male = 0;
    let section_female = 0;
    let section_total = 0;

    // Calculate each grade total and accumulate section totals
    <?php foreach($grades as $grade): ?>
    const <?php echo $grade; ?>_m = parseFloat($(row).find('.<?php echo $grade; ?>_m').val()) || 0;
    const <?php echo $grade; ?>_f = parseFloat($(row).find('.<?php echo $grade; ?>_f').val()) || 0;
    const <?php echo $grade; ?>_total = <?php echo $grade; ?>_m + <?php echo $grade; ?>_f;
    
    $(row).find('.<?php echo $grade; ?>_total').val(<?php echo $grade; ?>_total);
    section_male += <?php echo $grade; ?>_m;
    section_female += <?php echo $grade; ?>_f;
    section_total += <?php echo $grade; ?>_total;
    <?php endforeach; ?>

    $(row).find('.section_male').val(section_male);
    $(row).find('.section_female').val(section_female);
    $(row).find('.section_total').val(section_total);
  }

  // Function to calculate all totals
  function calculateAllTotals() {
    let grade_totals = {};
    let total_male = 0;
    let total_female = 0;
    let grand_total = 0;

    // Initialize grade totals
    <?php foreach($grades as $grade): ?>
    grade_totals['<?php echo $grade; ?>_m'] = 0;
    grade_totals['<?php echo $grade; ?>_f'] = 0;
    grade_totals['<?php echo $grade; ?>_total'] = 0;
    <?php endforeach; ?>

    // Calculate row totals and accumulate
    $('#exam_fees_table tbody tr:not(.grade-total-row)').each(function() {
      calculateRowTotals(this);
      
      <?php foreach($grades as $grade): ?>
      const <?php echo $grade; ?>_m = parseFloat($(this).find('.<?php echo $grade; ?>_m').val()) || 0;
      const <?php echo $grade; ?>_f = parseFloat($(this).find('.<?php echo $grade; ?>_f').val()) || 0;
      
      grade_totals['<?php echo $grade; ?>_m'] += <?php echo $grade; ?>_m;
      grade_totals['<?php echo $grade; ?>_f'] += <?php echo $grade; ?>_f;
      <?php endforeach; ?>
    });

    // Calculate grade totals and overall totals
    <?php foreach($grades as $grade): ?>
    grade_totals['<?php echo $grade; ?>_total'] = grade_totals['<?php echo $grade; ?>_m'] + grade_totals['<?php echo $grade; ?>_f'];
    total_male += grade_totals['<?php echo $grade; ?>_m'];
    total_female += grade_totals['<?php echo $grade; ?>_f'];
    <?php endforeach; ?>
    
    grand_total = total_male + total_female;

    // Update grade total row
    <?php foreach($grades as $grade): ?>
    $('.total_<?php echo $grade; ?>_m').val(grade_totals['<?php echo $grade; ?>_m']);
    $('.total_<?php echo $grade; ?>_f').val(grade_totals['<?php echo $grade; ?>_f']);
    $('.total_<?php echo $grade; ?>').val(grade_totals['<?php echo $grade; ?>_total']);
    <?php endforeach; ?>

    // Update grand total row display
    $('.display_total_male').text(total_male);
    $('.display_total_female').text(total_female);
    $('.display_grand_total').text(grand_total);

    // Update quick action totals
    $('#quickMaleTotal').text(total_male);
    $('#quickFemaleTotal').text(total_female);
    $('#quickGrandTotal').text(grand_total);
  }
  
  // Calculate totals when any input changes
  $('#exam_fees_table').on('input', 'input[type="number"]:not([readonly])', function() {
    calculateAllTotals();
  });
  
  // Initialize totals on page load
  calculateAllTotals();

  // Quick Actions
  $('#clearAll').on('click', function() {
    $('#exam_fees_table input[type="number"]:not([readonly])').val('');
    calculateAllTotals();
  });

  $('#fillZeros').on('click', function() {
    $('#exam_fees_table input[type="number"]:not([readonly])').val('0');
    calculateAllTotals();
  });

  $('#copyPrevious').on('click', function() {
    if (confirm('Copy data from previous month?')) {
      // This would need additional implementation to fetch previous month's data
      alert('This feature would fetch and populate data from the previous month');
    }
  });

  // Reset form function
  function resetForm() {
    $('#id').val('');
    $('#date').val('<?php echo $today_date; ?>');
    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Data');
    $('#cancelBtn').hide();
    $('#exam_fees_table input[type="number"]:not([readonly])').val('0');
    calculateAllTotals();
  }

  // Cancel edit
  $('#cancelBtn').on('click', resetForm);

  // Edit button click
  $(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    
    $.ajax({
      url: 'get_record.php',
      type: 'POST',
      data: { id: id },
      success: function(response) {
        try {
          const record = JSON.parse(response);
          if (record.success) {
            // Populate form with record data
            $('#id').val(record.data.id);
            $('#date').val(record.data.date);
            
            // Populate the table inputs for each grade
            $('#exam_fees_table tbody tr:not(.grade-total-row)').each(function(index) {
              <?php foreach($grades as $grade): ?>
              const <?php echo $grade; ?>_m = record.data.<?php echo $grade; ?>_m.split(',');
              const <?php echo $grade; ?>_f = record.data.<?php echo $grade; ?>_f.split(',');
              $(this).find('.<?php echo $grade; ?>_m').val(<?php echo $grade; ?>_m[index] || 0);
              $(this).find('.<?php echo $grade; ?>_f').val(<?php echo $grade; ?>_f[index] || 0);
              <?php endforeach; ?>
            });
            
            calculateAllTotals();
            $('#submitBtn').html('<i class="fas fa-sync me-2"></i>Update Data');
            $('#cancelBtn').show();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
          } else {
            alert('Error loading record: ' + record.message);
          }
        } catch (e) {
          alert('Error parsing response');
        }
      },
      error: function() {
        alert('Error loading record');
      }
    });
  });

  // Delete button click
  $(document).on('click', '.delete-btn', function() {
    if (!confirm('Are you sure you want to delete this record?')) {
      return;
    }
    
    const id = $(this).data('id');
    
    $.ajax({
      url: 'delete_record.php',
      type: 'POST',
      data: { id: id },
      success: function(response) {
        try {
          const result = JSON.parse(response);
          if (result.success) {
            alert('Record deleted successfully!');
            location.reload();
          } else {
            alert('Error deleting record: ' + result.message);
          }
        } catch (e) {
          alert('Error parsing response');
        }
      },
      error: function() {
        alert('Error deleting record');
      }
    });
  });
  
  // Handle form submission
  $('#employeeForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    const isEdit = $('#id').val() !== '';
    
    $.ajax({
      url: 'save_data.php',
      type: 'POST',
      data: formData,
      success: function(response) {
        try {
          const result = JSON.parse(response);
          if (result.success) {
            alert(isEdit ? 'Data updated successfully!' : 'Data saved successfully!');
            location.reload();
          } else {
            alert('Error saving data: ' + result.message);
          }
        } catch (e) {
          alert('Error parsing response');
        }
      },
      error: function() {
        alert('Error saving data');
      }
    });
  });
});
</script>
</body>
</html>