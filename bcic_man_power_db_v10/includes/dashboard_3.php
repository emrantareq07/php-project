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
  <style>
    .male-col { background-color: #e3f2fd !important; }
    .female-col { background-color: #fce4ec !important; }
    .total-col { background-color: #f5f5f5 !important; font-weight: bold; }
    .section-row:hover { background-color: #f8f9fa !important; }
    .input-comfort { 
        height: 45px; 
        padding: 8px 12px; 
        font-size: 1rem; 
        text-align: center;
        font-weight: 500;
    }
    .compact-table th, .compact-table td { padding: 10px 12px !important; }
    .sticky-header { position: sticky; top: 0; background: white; z-index: 100; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .summary-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .quick-total { font-size: 1rem; padding: 12px 16px; }
    .grade-badge { font-size: 0.8rem; padding: 4px 8px; }
    .section-name { font-weight: 600; font-size: 1rem; }
    .input-highlight:focus { 
        border-color: #0d6efd; 
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        background-color: #fff;
    }
    .total-display {
        font-size: 1.1rem;
        font-weight: bold;
        padding: 8px 4px;
        display: block;
        text-align: center;
    }
    .column-header {
        min-width: 90px;
    }
    .department-column {
        min-width: 220px;
        position: sticky;
        left: 0;
        background: #f8f9fa !important;
        z-index: 5;
        border-right: 2px solid #dee2e6;
    }
  </style>
</head>

<body class="bg-light">
<div class="container-fluid py-3">

  <!-- Header -->
  <div class="sticky-header bg-white p-4 rounded shadow-sm mb-4">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h4 class="mb-1 text-primary">
          <i class="fas fa-users me-2"></i>Employee Data Entry
        </h4>
        <small class="text-muted">Enter employee counts by department and grade</small>
      </div>
      <div class="col-md-6 text-end">
        <div class="btn-group">
          <button class="btn btn-outline-primary btn-md" id="clearAll" title="Clear all inputs">
            <i class="fas fa-eraser me-1"></i>Clear All
          </button>
          <button class="btn btn-outline-secondary btn-md" id="fillZeros" title="Fill all with zero">
            <i class="fas fa-sync me-1"></i>Fill Zeros
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Summary -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card bg-primary text-white quick-total">
        <div class="card-body py-3">
          <h6 class="mb-1">Total Male</h6>
          <h3 class="mb-0" id="quickMaleTotal">0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-danger text-white quick-total">
        <div class="card-body py-3">
          <h6 class="mb-1">Total Female</h6>
          <h3 class="mb-0" id="quickFemaleTotal">0</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-success text-white quick-total">
        <div class="card-body py-3">
          <h6 class="mb-1">Grand Total</h6>
          <h3 class="mb-0" id="quickGrandTotal">0</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Form -->
  <form id="employeeForm" class="card shadow-sm mb-4">
    <input type="hidden" name="id" id="id">
    
    <div class="card-header bg-light py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <strong class="text-dark h6">Factory:</strong> 
          <span class="text-muted h6"><?php echo $username; ?></span>
        </div>
        <div class="col-md-6 text-end">
          <strong class="text-dark h6 me-2">Date:</strong>
          <input type="date" name="date" class="form-control d-inline-block w-auto" id="date" value="<?php echo $today_date; ?>">
        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive" style="max-height: 65vh;">
        <table class="table table-bordered mb-0">
          <thead class="table-light sticky-top" style="top: 0;">
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
            <?php foreach ($sections as $index => $section): ?>
            <tr class="section-row">
              <td class="section-name department-column">
                <strong><?php echo $section; ?></strong>
              </td>
              
              <?php foreach($grades as $grade): ?>
              <td class="male-col">
                <input type="number" class="form-control input-comfort input-highlight <?php echo $grade; ?>_m" 
                       name="data[<?php echo $index; ?>][<?php echo $grade; ?>_m]" 
                       min="0" value="0" data-grade="<?php echo $grade; ?>" 
                       placeholder="0" style="font-size: 1.1rem;">
              </td>
              <td class="female-col">
                <input type="number" class="form-control input-comfort input-highlight <?php echo $grade; ?>_f" 
                       name="data[<?php echo $index; ?>][<?php echo $grade; ?>_f]" 
                       min="0" value="0" data-grade="<?php echo $grade; ?>" 
                       placeholder="0" style="font-size: 1.1rem;">
              </td>
              <td class="total-col text-center border-end">
                <span class="total-display <?php echo $grade; ?>_total">0</span>
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
              <td class="department-column bg-dark text-white">
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
                <strong class="total-display" id="finalMaleTotal">0</strong>
              </td>
              <td class="female-col text-center bg-danger text-white">
                <strong class="total-display" id="finalFemaleTotal">0</strong>
              </td>
              <td class="total-col text-center bg-success text-white">
                <strong class="total-display" id="finalGrandTotal">0</strong>
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
            <small>Enter numbers in the input fields. All totals update automatically.</small>
          </div>
        </div>
        <div class="col-md-6 text-end">
          <button class="btn btn-outline-secondary btn-lg px-4 me-2" type="button" id="cancelBtn" style="display:none;">
            <i class="fas fa-times me-1"></i>Cancel
          </button>
          <button class="btn btn-success btn-lg px-4" type="submit" id="submitBtn">
            <i class="fas fa-save me-1"></i>Save Data
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- Records Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-light py-3">
      <h5 class="mb-0"><i class="fas fa-history me-2"></i>Previous Records</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0" id="recordsTable">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              <?php foreach($grades as $grade): ?>
              <th>G<?php echo substr($grade, 1); ?> M</th>
              <th>G<?php echo substr($grade, 1); ?> F</th>
              <?php endforeach; ?>
              <th>Total</th>
              <th style="width: 140px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($records as $record): 
              $record_date = $record['date'];
              $is_editable = ($record_date >= $first_day_next_month);
            ?>
            <tr class="<?php echo $is_editable ? '' : 'disabled-record'; ?>">
              <td><strong><?php echo $record['date']; ?></strong></td>
              <?php 
              $total_employees = 0;
              foreach($grades as $grade): 
                $grade_m = array_sum(explode(',', $record[$grade.'_m']));
                $grade_f = array_sum(explode(',', $record[$grade.'_f']));
                $total_employees += $grade_m + $grade_f;
              ?>
              <td class="text-center"><?php echo $grade_m; ?></td>
              <td class="text-center"><?php echo $grade_f; ?></td>
              <?php endforeach; ?>
              <td class="text-center"><strong><?php echo $total_employees; ?></strong></td>
              <td class="text-center">
                <?php if($is_editable): ?>
                <button class="btn btn-warning btn-sm edit-btn me-1" data-id="<?php echo $record['id']; ?>" title="Edit">
                  <i class="fas fa-edit me-1"></i>Edit
                </button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $record['id']; ?>" title="Delete">
                  <i class="fas fa-trash me-1"></i>Delete
                </button>
                <?php else: ?>
                <span class="badge bg-secondary p-2">Locked</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
$(document).ready(function() {
  // Initialize DataTable
  $('#recordsTable').DataTable({
    pageLength: 8,
    order: [[0, 'desc']],
    language: {
      search: "Search records:",
      lengthMenu: "Show _MENU_ records"
    }
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
    
    $(row).find('.<?php echo $grade; ?>_total').text(<?php echo $grade; ?>_total);
    section_male += <?php echo $grade; ?>_m;
    section_female += <?php echo $grade; ?>_f;
    section_total += <?php echo $grade; ?>_total;
    <?php endforeach; ?>

    $(row).find('.section_male').text(section_male);
    $(row).find('.section_female').text(section_female);
    $(row).find('.section_total').text(section_total);
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
    $('#employeeForm tbody tr:not(.table-active)').each(function() {
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
    $('.total_<?php echo $grade; ?>_m').text(grade_totals['<?php echo $grade; ?>_m']);
    $('.total_<?php echo $grade; ?>_f').text(grade_totals['<?php echo $grade; ?>_f']);
    $('.total_<?php echo $grade; ?>').text(grade_totals['<?php echo $grade; ?>_total']);
    <?php endforeach; ?>

    // Update final totals
    $('#finalMaleTotal').text(total_male);
    $('#finalFemaleTotal').text(total_female);
    $('#finalGrandTotal').text(grand_total);

    // Update quick summary
    $('#quickMaleTotal').text(total_male);
    $('#quickFemaleTotal').text(total_female);
    $('#quickGrandTotal').text(grand_total);
  }
  
  // Calculate totals when any input changes
  $('#employeeForm').on('input', 'input[type="number"]', function() {
    calculateAllTotals();
  });
  
  // Initialize totals on page load
  calculateAllTotals();

  // Quick Actions
  $('#clearAll').on('click', function() {
    $('#employeeForm input[type="number"]').val('');
    calculateAllTotals();
  });

  $('#fillZeros').on('click', function() {
    $('#employeeForm input[type="number"]').val('0');
    calculateAllTotals();
  });

  // Reset form function
  function resetForm() {
    $('#id').val('');
    $('#date').val('<?php echo $today_date; ?>');
    $('#submitBtn').html('<i class="fas fa-save me-1"></i>Save Data');
    $('#cancelBtn').hide();
    $('#employeeForm input[type="number"]').val('0');
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
            $('#id').val(record.data.id);
            $('#date').val(record.data.date);
            
            // Populate the table inputs for each grade
            $('#employeeForm tbody tr:not(.table-active)').each(function(index) {
              <?php foreach($grades as $grade): ?>
              const <?php echo $grade; ?>_m = record.data.<?php echo $grade; ?>_m.split(',');
              const <?php echo $grade; ?>_f = record.data.<?php echo $grade; ?>_f.split(',');
              $(this).find('.<?php echo $grade; ?>_m').val(<?php echo $grade; ?>_m[index] || 0);
              $(this).find('.<?php echo $grade; ?>_f').val(<?php echo $grade; ?>_f[index] || 0);
              <?php endforeach; ?>
            });
            
            calculateAllTotals();
            $('#submitBtn').html('<i class="fas fa-sync me-1"></i>Update Data');
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