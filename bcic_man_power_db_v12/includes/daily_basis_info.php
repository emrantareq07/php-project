<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$back=1;

if ($_SESSION['role'] == 'admin' && $_SESSION['username'] == 'admin') {
  $role = $_SESSION['role'] ?? ''; // ensure role exists
  $username = $conn->real_escape_string($_GET['factory_name']);
  $_SESSION['username']=$username;

}else{
  $role = $_SESSION['role'] ?? ''; // ensure role exists
  $username = $_SESSION['username'];
  
}

//$username = $_SESSION['username'];
$table = 'daily_basis_tbl';
$today_date = date("Y-m-d");

// Function to check if entry exists for selected month (excluding current record during edit)
function checkMonthlyEntry($conn, $table, $username, $date, $exclude_id = null) {
    $year_month = date('Y-m', strtotime($date));
    $sql = "SELECT id FROM $table WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    
    if ($exclude_id) {
        $sql .= " AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $username, $year_month, $exclude_id);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $year_month);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Initialize variables
$editing_record_id = null;
$is_editing = false;
$edit_record = null;
$monthly_entry_exists = false;
$monthly_entry_message = '';

// Check if we're loading/editing an existing record
if (isset($_GET['load_id']) || isset($_GET['edit_id'])) {
    // Use load_id if present, otherwise use edit_id
    $editing_record_id = isset($_GET['load_id']) ? $_GET['load_id'] : $_GET['edit_id'];
    $is_editing = true;
    
    // Fetch the specific record for editing
    $edit_sql = "SELECT * FROM $table WHERE id = ? AND factory_name = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param('is', $editing_record_id, $username);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_record = $edit_result->fetch_assoc();
        // Check if other entries exist for the same month (excluding current record)
        $monthly_entry_exists = checkMonthlyEntry($conn, $table, $username, $edit_record['date'], $editing_record_id);
        if ($monthly_entry_exists) {
            $monthly_entry_message = "Warning: Another entry exists for " . date('F Y', strtotime($edit_record['date']));
        }
    } else {
        $is_editing = false;
        $editing_record_id = null;
        $edit_record = null;
    }
    $edit_stmt->close();
} else {
    // New entry mode - check if any entry exists for current month
    $selected_date = $today_date;
    $monthly_entry_exists = checkMonthlyEntry($conn, $table, $username, $selected_date);
    if ($monthly_entry_exists) {
        $monthly_entry_message = "An entry already exists for " . date('F Y', strtotime($selected_date)) . ". Please edit the existing entry.";
    }
}

// Fetch existing records
$records = [];
$sql = "SELECT * FROM $table WHERE factory_name = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Basis Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .worker-section {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .worker-row td {
            vertical-align: middle;
        }
        .vacant-post {
            background-color: #fff3cd;
            font-weight: bold;
        }
        .total-cell {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .header-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .records-table {
            margin-top: 30px;
        }
        .action-buttons .btn {
            margin: 2px;
        }
        .badge-success { background-color: #28a745; }
        .badge-secondary { background-color: #6c757d; }
        .monthly-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .btn-disabled-override {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .mode-indicator {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .mode-new { background-color: #d4edda; color: #155724; }
        .mode-edit { background-color: #fff3cd; color: #856404; }
        .mode-load { background-color: #cce7ff; color: #004085; }
    </style>
</head>
<body>
<div class="container-fluid py-0">
    <div class="worker-section">
        <!-- Mode Indicator -->
        <div class="row mb-3">
            <div class="col-md-12">
                <?php if ($is_editing): ?>
                    <?php if (isset($_GET['load_id'])): ?>
                        <span class="mode-indicator mode-load">
                            <i class="fas fa-hourglass-half"></i> MODE: LOADING RECORD (ID: <?php echo $editing_record_id; ?>)
                        </span>
                    <?php else: ?>
                        <span class="mode-indicator mode-edit">
                            <i class="fas fa-edit"></i> MODE: EDITING RECORD (ID: <?php echo $editing_record_id; ?>)
                        </span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="mode-indicator mode-new">
                        <i class="fas fa-plus"></i> MODE: CREATING NEW RECORD
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Monthly Restriction Warning -->
        <?php if ($monthly_entry_message): ?>
        <div class="monthly-warning alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php echo $monthly_entry_message; ?>
        </div>
        <?php endif; ?>

        <div class="header-info">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-hard-hat"></i> Daily Basis Information</h5>
                </div>
                <div class="col-md-6 text-end">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Factory:</strong> <?php echo $_SESSION['username']; ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Date:</strong> 
                            <?php echo $is_editing && isset($edit_record['date']) ? htmlspecialchars($edit_record['date']) : date('Y-m-d'); ?>
                        </div>
                        <!-- <div class="col-md-4">
                            <a href="dashboard.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                        </div> -->
                         <div class="col-md-4">
                        <?php if ($role == 'admin') { ?>
         
                          <a href="daily_basis_details.php" class="btn btn-primary btn-sm">
                              <i class="fa fa-arrow-left me-1"></i>Back
                          </a>
                      <?php } else { ?>
                          <a href="dashboard.php" class="btn btn-primary btn-sm">
                              <i class="fa fa-arrow-left me-1"></i>Back
                          </a>
                      <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM STARTS HERE -->
        <form id="workerForm">
            <input type="hidden" name="factory_name" value="<?php echo $_SESSION['username']; ?>">
            <input type="hidden" name="record_id" id="record_id" value="<?php echo $editing_record_id ? $editing_record_id : ''; ?>">
            
            <!-- Date Input -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="date" class="form-label"><strong>Date *</strong></label>
                    <input type="date" class="form-control" id="date" name="date" 
                        value="<?php echo $is_editing && isset($edit_record['date']) ? $edit_record['date'] : date('Y-m-d'); ?>"
                        <?php echo ($is_editing && isset($_GET['edit_id'])) ? 'readonly' : ''; ?> required>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="worker_table">
                    <thead class="table-light">
                        <tr>
                            <td colspan="9" class="text-end">
                                <!-- Add Row Button -->
                                <button type="button" id="add_worker" class="btn btn-success btn-sm" 
                                    <?php echo ($monthly_entry_exists && !$is_editing) || ($is_editing && isset($_GET['edit_id'])) ? 'enable' : ''; ?>>
                                    <i class="fa fa-plus"></i> Add Row
                                </button>
                                
                                <!-- Save New Button -->
                                <button type="submit" id="save_btn" class="btn btn-primary btn-sm" 
                                    <?php echo ($is_editing || $monthly_entry_exists) ? 'style="display:none;"' : ''; ?>
                                    <?php echo $monthly_entry_exists ? 'disabled' : ''; ?>>
                                    <i class="fa fa-save"></i> Save New
                                </button>

                                <!-- Save Loaded Record Button -->
                                <button type="button" id="load_save_btn" class="btn btn-primary btn-sm" 
                                    <?php echo !(isset($_GET['load_id'])) ? 'style="display:none;"' : ''; ?>>
                                    <i class="fa fa-save"></i> Save as New Record
                                </button>

                                <!-- Update Record Button -->
                                <button type="button" id="update_btn" class="btn btn-warning btn-sm" 
                                    <?php echo !(isset($_GET['edit_id'])) ? 'style="display:none;"' : ''; ?>>
                                    <i class="fa fa-sync"></i> Update Record
                                </button>

                                <!-- Cancel Button -->
                                <button type="button" id="cancel_edit" class="btn btn-secondary btn-sm" 
                                    <?php echo !$is_editing ? 'style="display:none;"' : ''; ?>>
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <th>Grade</th>
                            <th>Sanctioned Post</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Total</th>
                            <th>Vacant Post</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="worker_body">
                        <?php if ($is_editing && isset($edit_record)): ?>
                            <!-- Populate form with edit data -->
                            <?php 
                            $designations = explode(',', $edit_record['designation']);
                            $grades = explode(',', $edit_record['grade']);
                            $sanctioned_posts = explode(',', $edit_record['sanctioned_post']);
                            $males = explode(',', $edit_record['male']);
                            $females = explode(',', $edit_record['female']);
                            $totals = explode(',', $edit_record['total']);
                            ?>
                            
                            <?php for($i = 0; $i < count($designations); $i++): ?>
                            <tr class="worker-row">
                                <td>
                                    <input type="text" class="form-control designation" name="designation[]" 
                                           value="<?php echo htmlspecialchars($designations[$i] ?? ''); ?>" >
                                </td>
                                <td>
                                <input type="text" class="form-control grade" name="grade[]" placeholder="Enter Grade" >
                                </td>
                                <td>
                                    <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" 
                                           min="0" value="<?php echo $sanctioned_posts[$i] ?? 0; ?>" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control male" name="male[]" 
                                           min="0" value="<?php echo $males[$i] ?? 0; ?>" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control female" name="female[]" 
                                           min="0" value="<?php echo $females[$i] ?? 0; ?>" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control total total-cell" name="total[]" 
                                           min="0" value="<?php echo $totals[$i] ?? 0; ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control vacant-post" name="vacant_post[]" 
                                           min="0" value="<?php echo ($sanctioned_posts[$i] ?? 0) - ($totals[$i] ?? 0); ?>" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-worker" 
                                            <?php echo ($i == 0 && isset($_GET['edit_id'])) ? 'disabled' : ''; ?>>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endfor; ?>
                            
                        <?php else: ?>
                            <!-- Default empty row for new entry -->
                            <tr class="worker-row">
                                <td>
                                    <input type="text" class="form-control designation" name="designation[]" 
                                           placeholder="Enter designation" >
                                </td>
                                <td>
                                    <input type="text" class="form-control grade" name="grade[]" placeholder="Enter Grade" >
                                </td>
                                <td>
                                    <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" 
                                           min="0" value="0" >
                                </td>
                                <td>
                                    <input type="number" class="form-control male" name="male[]" min="0" value="0" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control female" name="female[]" min="0" value="0" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control total total-cell" name="total[]" min="0" value="0" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control vacant-post" name="vacant_post[]" min="0" value="0" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-worker" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- CRUD Operations Table -->
    <div class="card records-table">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-database"></i> Daily Basis Records</h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-info btn-sm" onclick="location.reload()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Date</th>
                            <th>Designations</th>
                            <th>Grades</th>
                            <th>Sanctioned Posts</th>
                            <th>Male Workers</th>
                            <th>Female Workers</th>
                            <th>Total Workers</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="records_body">
                        <?php if (empty($records)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    No worker records found.
                                </td>
                            </tr>
                        <?php else: ?>
                            
                                      <?php 
            $current_year_month = date('Y-m');
            
            foreach ($records as $record): 
                $record_date = $record['date'];
                $record_year_month = date('Y-m', strtotime($record_date));
                
                // Check if record is from current or future month
                $is_current_or_future = ($record_year_month >= $current_year_month);
            ?>

                            <tr>
                                <td><strong><?php echo htmlspecialchars($record['date']); ?></strong></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="showDetails('designation', '<?php echo htmlspecialchars($record['designation']); ?>')">
                                        <i class="fas fa-list"></i> View (<?php echo count(explode(',', $record['designation'])); ?>)
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="showDetails('grade', '<?php echo htmlspecialchars($record['grade']); ?>')">
                                        <i class="fas fa-tags"></i> View (<?php echo count(explode(',', $record['grade'])); ?>)
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning" onclick="showDetails('sanctioned_post', '<?php echo htmlspecialchars($record['sanctioned_post']); ?>')">
                                        <i class="fas fa-bullseye"></i> View (<?php echo count(explode(',', $record['sanctioned_post'])); ?>)
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showDetails('male', '<?php echo htmlspecialchars($record['male']); ?>')">
                                        <i class="fas fa-male"></i> View (<?php echo count(explode(',', $record['male'])); ?>)
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger" onclick="showDetails('female', '<?php echo htmlspecialchars($record['female']); ?>')">
                                        <i class="fas fa-female"></i> View (<?php echo count(explode(',', $record['female'])); ?>)
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success" onclick="showDetails('total', '<?php echo htmlspecialchars($record['total']); ?>')">
                                        <i class="fas fa-calculator"></i> View (<?php echo count(explode(',', $record['total'])); ?>)
                                    </button>
                                </td>
                                <td>
                                    <span class="badge <?php echo $record['status'] === 'active' ? 'badge-success' : 'badge-secondary'; ?>">
                                        <?php echo htmlspecialchars($record['status']); ?>
                                    </span>
                                </td>
<td class="action-buttons">
    <?php if ($role == 'admin' || $is_current_or_future) { ?>
        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $record['id']; ?>" title="Edit">
            <i class="fas fa-edit"></i>
        </button>
    <?php } ?>
    
    <button class="btn btn-info btn-sm view-btn" data-id="<?php echo $record['id']; ?>" title="View Report">
        <i class="fas fa-print"></i>
    </button>
    
    <button class="btn btn-primary btn-sm load-btn" data-id="<?php echo $record['id']; ?>" title="Load for Modification">
        <i class="fas fa-hourglass-half"></i>
    </button>
    
    <?php if ($role == 'admin') { ?>
        <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $record['id']; ?>" title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    <?php } ?>
</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalTitle">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this worker record? This action cannot be undone.</p>
                <p><strong>Date:</strong> <span id="deleteDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let rowCount = $('#worker_body .worker-row').length;
    let isEditing = <?php echo $is_editing ? 'true' : 'false'; ?>;
    let editingRecordId = <?php echo $editing_record_id ? $editing_record_id : 'null'; ?>;
    let monthlyEntryExists = <?php echo $monthly_entry_exists ? 'true' : 'false'; ?>;
    let isLoadMode = <?php echo isset($_GET['load_id']) ? 'true' : 'false'; ?>;
    let isEditMode = <?php echo isset($_GET['edit_id']) ? 'true' : 'false'; ?>;

    // Initialize row count based on existing rows
    updateRowCount();

    // Add new row
    $('#add_worker').on('click', function() {
        if (monthlyEntryExists && !isEditing) {
            alert('Cannot add new entry. An entry already exists for this month.');
            return;
        }

        // if (isEditMode) {
        //     alert('Cannot add new rows while editing an existing record.');
        //     return;
        // }

        const newRow = `
            <tr class="worker-row">
                <td>
                    <input type="text" class="form-control designation" name="designation[]" placeholder="Enter designation" >
                </td>
                <td>
                <input type="text" class="form-control grade" name="grade[]" placeholder="Enter Grade" >
                    
                </td>
                <td>
                    <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" min="0" value="0" >
                </td>
                <td>
                    <input type="number" class="form-control male" name="male[]" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" class="form-control female" name="female[]" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" class="form-control total total-cell" name="total[]" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" class="form-control vacant-post" name="vacant_post[]" min="0" value="0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-worker">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#worker_body').append(newRow);
        updateRowCount();
    });

    // Remove row
    $(document).on('click', '.remove-worker', function() {
        if (rowCount > 1) {
            $(this).closest('tr').remove();
            updateRowCount();
        } else {
            alert('Cannot remove the last row.');
        }
    });

    // Update row count and button states
    function updateRowCount() {
        rowCount = $('#worker_body .worker-row').length;
        
        // Enable/disable remove buttons
        if (rowCount > 1) {
            $('.remove-worker').prop('disabled', false);
        } else {
            $('.remove-worker:first').prop('disabled', true);
        }
    }

    // Calculate totals and vacant posts
    $(document).on('input', '.male, .female, .sanctioned-post', function() {
        const row = $(this).closest('tr');
        calculateRowTotals(row);
    });

    function calculateRowTotals(row) {
        const male = parseFloat(row.find('.male').val()) || 0;
        const female = parseFloat(row.find('.female').val()) || 0;
        const sanctioned = parseFloat(row.find('.sanctioned-post').val()) || 0;
        
        const total = male + female;
        const vacant = sanctioned - total;
        
        row.find('.total').val(total);
        row.find('.vacant-post').val(vacant);
        
        if (vacant < 0) {
            row.find('.vacant-post').addClass('text-danger');
        } else {
            row.find('.vacant-post').removeClass('text-danger');
        }
    }

    // Save New Record
    $('#workerForm').on('submit', function(e) {
        e.preventDefault();
        
        if (monthlyEntryExists && !isEditing) {
            alert('An entry already exists for this month. Please edit the existing entry.');
            return;
        }
        
        if (!validateForm()) return;

        const submitBtn = $('#save_btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        console.log('Form Data:', $(this).serialize());

        $.ajax({
            url: 'save_daily_basis.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'daily_basis_info.php';
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Save Error:', error);
                console.error('Response:', xhr.responseText);
                alert('❌ Error saving data. Check console for details.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Save Loaded Record as New
    $('#load_save_btn').on('click', function() {
        if (!validateForm()) return;

        const submitBtn = $(this);
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        // Remove record_id to save as new record
        const formData = $('#workerForm').serialize().replace(/record_id=[^&]*&?/, '');
        
        console.log('Load Save Form Data:', formData);

        $.ajax({
            url: 'save_daily_basis.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'daily_basis_info.php';
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Load Save Error:', error);
                console.error('Response:', xhr.responseText);
                alert('❌ Error saving data. Check console for details.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Update Record
    $('#update_btn').on('click', function() {
        if (!validateForm()) return;

        const updateBtn = $(this);
        const originalText = updateBtn.html();
        updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

        console.log('Update Form Data:', $('#workerForm').serialize());

        $.ajax({
            url: 'update_daily_basis.php',
            type: 'POST',
            data: $('#workerForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'daily_basis_info.php';
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Update Error:', error);
                console.error('Response:', xhr.responseText);
                
                if (xhr.responseText.includes('<!DOCTYPE') || xhr.responseText.includes('<br />')) {
                    alert('❌ Server error occurred. Please check the console for details.');
                } else {
                    alert('❌ Error: ' + error);
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Cancel Edit/Load
    $('#cancel_edit').on('click', function() {
        if (confirm('Are you sure you want to cancel?')) {
            window.location.href = 'daily_basis_info.php';
        }
    });

    // Date change handler
    $('#date').on('change', function() {
        if (!isEditing) {
            checkMonthlyRestriction();
        }
    });

    // Check monthly restriction via AJAX
    function checkMonthlyRestriction() {
        const date = $('#date').val();
        const factoryName = '<?php echo $_SESSION['username']; ?>';
        
        if (!date) return;
        
        $.ajax({
            url: 'check_monthly_entry_daily_basis.php',
            type: 'POST',
            data: { 
                date: date,
                factory_name: factoryName
            },
            dataType: 'json',
            success: function(response) {
                monthlyEntryExists = response.exists;
                
                if (monthlyEntryExists) {
                    $('#save_btn').prop('disabled', true).addClass('btn-disabled-override');
                    $('#add_worker').prop('disabled', true);
                    $('.monthly-warning').remove();
                    
                    const warningMsg = `An entry already exists for ${response.month_year}. Please edit the existing entry.`;
                    $('.worker-section').prepend(`
                        <div class="monthly-warning alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> ${warningMsg}
                        </div>
                    `);
                } else {
                    $('#save_btn').prop('disabled', false).removeClass('btn-disabled-override');
                    $('#add_worker').prop('disabled', false);
                    $('.monthly-warning').remove();
                }
            },
            error: function(xhr, status, error) {
                console.error('Monthly check error:', error);
            }
        });
    }

    function validateForm() {
        const date = $('#date').val();
        if (!date) {
            alert('Please select a date.');
            $('#date').addClass('is-invalid');
            return false;
        } else {
            $('#date').removeClass('is-invalid');
        }

        let isValid = true;
        let hasData = false;
        
        $('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
                if ($(this).val() && $(this).val() !== '0') {
                    hasData = true;
                }
            }
        });
        
        if (!isValid) {
            alert('Please fill all required fields.');
            return false;
        }
        
        if (!hasData) {
            alert('Please enter at least one worker record.');
            return false;
        }
        
        return true;
    }

    // Edit button behavior
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        window.location.href = 'daily_basis_info.php?edit_id=' + id;
    });

    // Load button behavior
    $(document).on('click', '.load-btn', function() {
        const id = $(this).data('id');
        window.location.href = 'daily_basis_info.php?load_id=' + id;
    });

    // View button behavior
    // $(document).on('click', '.view-btn', function() {
    //     const id = $(this).data('id');
    //     viewRecord(id);
    // });

    // Delete button behavior
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const date = $(this).closest('tr').find('td:first').text().trim();
        
        $('#deleteDate').text(date);
        $('#deleteModal').data('id', id).modal('show');
    });

    // Confirm delete
    $('#confirmDelete').on('click', function() {
        const id = $('#deleteModal').data('id');
        deleteRecord(id);
    });
});

// View button behavior
$(document).on('click', '.view-btn', function() {
    const id = $(this).data('id');
    viewRecord(id);
});

// View Record function (for print report)
function viewRecord(id) {
    console.log('View Record called with ID:', id);

    $.ajax({
        url: 'get_daily_basis_data.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            console.log('AJAX Success Response:', response);

            if (response.success) {
                const data = response.data;

                // Function to convert numbers to Bangla
                function englishToBanglaNumber(number) {
                    if (number === null || number === undefined) return '০';
                    const banglaDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                    return number.toString().replace(/\d/g, d => banglaDigits[parseInt(d)]);
                }

                // // Determine which columns exist
                // const hasDesignation = data.designation && data.designation.trim() !== '';
                // const hasGrade       = data.grade && data.grade.trim() !== '';
                
                // // Split CSV data
                // const designations = hasDesignation ? data.designation.split(',') : [];
                // const grades       = hasGrade ? data.grade.split(',') : [];
                // const sanctionedPosts = data.sanctioned_post ? data.sanctioned_post.split(',') : [];
                // const males        = data.male ? data.male.split(',') : [];
                // const females      = data.female ? data.female.split(',') : [];
                // const totals       = data.total ? data.total.split(',') : [];

                // helper function
                const isOnlyComma = (v) => v && v.replace(/,/g,'').trim() === '';

                // Determine which columns exist
                const hasDesignation = data.designation && !isOnlyComma(data.designation);
                const hasGrade       = data.grade && !isOnlyComma(data.grade);

                // Split CSV data
                const designations     = hasDesignation ? data.designation.split(',') : [];
                const grades           = hasGrade ? data.grade.split(',') : [];
                const sanctionedPosts  = data.sanctioned_post ? data.sanctioned_post.split(',') : [];
                const males            = data.male ? data.male.split(',') : [];
                const females          = data.female ? data.female.split(',') : [];
                const totals           = data.total ? data.total.split(',') : [];


                // Build table header dynamically
                let tableHeader = `
                    <tr>
                        <th>ক্রমিক</th>
                        ${hasDesignation ? '<th class="designation-cell">পদের নাম</th>' : ''}
                        ${hasGrade ? '<th>গ্রেড</th>' : ''}
                        <th>অনুমোদিত পদ</th>
                       <th class="male-col">পুরুষ (কর্মরত)</th>
                        <th class="female-col">মহিলা (কর্মরত)</th>
                        <th class="grade-total">মোট (কর্মরত)</th>
                        <th>শূন্য পদ</th>
                    </tr>
                `;

                // Build table body
                let tableBody = '';
                let detailedGrandMale = 0, detailedGrandFemale = 0, detailedGrandTotal = 0, detailedGrandSanctioned = 0, detailedGrandVacant = 0;

                const rowsCount = Math.max(designations.length, grades.length, sanctionedPosts.length, males.length, females.length, totals.length);

                for (let i = 0; i < rowsCount; i++) {
                    const designation = hasDesignation ? (designations[i] || '') : '';
                    const grade       = hasGrade ? (grades[i] || '') : '';
                    const sanctioned  = parseInt(sanctionedPosts[i] || 0);
                    const male        = parseInt(males[i] || 0);
                    const female      = parseInt(females[i] || 0);
                    const total       = parseInt(totals[i] || 0);
                    const vacant      = sanctioned - total;

                    detailedGrandMale += male;
                    detailedGrandFemale += female;
                    detailedGrandTotal += total;
                    detailedGrandSanctioned += sanctioned;
                    detailedGrandVacant += vacant;

                    tableBody += `<tr>
                        <td>${englishToBanglaNumber(i+1)}</td>
                        ${hasDesignation ? `<td class="designation-cell">${designation}</td>` : ''}
                        ${hasGrade ? `<td>${grade}</td>` : ''}
                        <td>${englishToBanglaNumber(sanctioned)}</td>
                        <td class="male-col">${englishToBanglaNumber(male)}</td>
                        <td class="female-col">${englishToBanglaNumber(female)}</td>
                        <td class="grade-total">${englishToBanglaNumber(total)}</td>
                        <td class="${vacant < 0 ? 'text-danger' : ''}">${englishToBanglaNumber(vacant)}</td>
                    </tr>`;
                }

                let tableGrandTotal = `
                    <tr class="grand-total">
                        <td colspan="${1 + (hasDesignation ? 1 : 0) + (hasGrade ? 1 : 0)}"><strong>সর্বমোট</strong></td>
                        <td><strong>${englishToBanglaNumber(detailedGrandSanctioned)}</strong></td>
                        <td class="male-col"><strong>${englishToBanglaNumber(detailedGrandMale)}</strong></td>
                        <td class="female-col"><strong>${englishToBanglaNumber(detailedGrandFemale)}</strong></td>
                        <td class="grade-total"><strong>${englishToBanglaNumber(detailedGrandTotal)}</strong></td>
                        <td><strong>${englishToBanglaNumber(detailedGrandVacant)}</strong></td>
                    </tr>
                `;

                // Build the full HTML content
                let content = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Employee Data Report - ${data.date}</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                        <style>
                            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                            body { font-family: 'Noto Sans Bengali', sans-serif; margin: 20px; }
                            .print-header { text-align: center; margin-bottom: 20px; }
                            .print-table { width: 100%; border-collapse: collapse; }
                            .print-table th, .print-table td { border: 1px solid #dee2e6; padding: 8px; text-align: center; }
                            .print-table th { background-color: #e9ecef; font-weight: bold; }
                            .designation-cell { text-align: left; font-weight: bold; background-color: #f8f9fa; }
                            .male-col { background-color: #e3f2fd; color: black;}
                            .female-col { background-color: #fce4ec; color: black; }
                            .grade-total { background-color: #f5f5f5; color: black;}
                            .grand-total { background-color: #495057; color: white; }
                            @media print { .no-print { display: none !important; } }
                      .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }
                        </style>
                    </head>
                    <body>
                        <div class="container-fluid">
                            <div class="print-header">
                                <h2 class="mb-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                            <h5 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h5>
                            <h4 class="mb-1">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : ${data.factory_name}</h4>
                            <h5 class="mb-0">বিদ্যমান জনবলের পরিসংখ্যান : (${englishToBanglaNumber(data.date)} তারিখে)</h5>
                            </div>
                            <h5 class="text-center">দৈনিক ভিক্তিক জনবলের তালিকা</h5>
                            <hr>
                            <table class="print-table">
                                <thead>${tableHeader}</thead>
                                <tbody>${tableBody}${tableGrandTotal}</tbody>
                            </table>
                         <div class="row mt-1">
                    <div class="col-md-12 text-center">
                        <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                            <strong><small>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</small></strong>
                        </div>
                    </div>
                </div>
                <div class="print-footer">
                    <strong>Design & Developed by ICT Division, BCIC.</strong>
                </div>
                            <div class="text-center no-print mt-4">
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i>প্রিন্ট করুন
                                </button>
                                <button class="btn btn-secondary" onclick="window.close()">
                                    <i class="fas fa-times me-1"></i>বন্ধ করুন
                                </button>
                               
                            </div>
                    <div class="text-center">
                    <small class="text-muted bangla-number">প্রতিবেদন তৈরির তারিখ: ${englishToBanglaNumber(new Date().toISOString().split('T')[0])}</small>
                        </div></div>
                        
                    </body>
                    </html>
                `;

                // Open in new window
                const printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=1');
                if (printWindow) {
                    printWindow.document.write(content);
                    printWindow.document.close();
                    printWindow.focus();
                } else {
                    alert('Popup blocked! Please allow popups for this site.');
                }

            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error, status, xhr.responseText);
            alert('Error loading record details. Check console.');
        }
    });
}


// Show details in modal
function showDetails(type, data) {
    const items = data.split(',');
    let title = '';
    let content = '';
    
    switch(type) {
        case 'designation':
            title = 'Designations';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${item.trim()}
                </div>`
            ).join('');
            break;
            
        case 'grade':
            title = 'Grades';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${item.trim()}
                </div>`
            ).join('');
            break;
            
        case 'sanctioned_post':
            title = 'Sanctioned Posts';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'male':
            title = 'Male Daily Basis';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'female':
            title = 'Female Daily Basis';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'total':
            title = 'Total Daily Basis';
            const total = items.reduce((sum, item) => sum + parseInt(item), 0);
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('') + 
            `<div class="worker-details bg-primary text-white mt-2">
                <strong>Grand Total:</strong> ${total.toLocaleString()}
            </div>`;
            break;
    }
    
    $('#detailsModalTitle').text(title);
    $('#detailsModalBody').html(content);
    $('#detailsModal').modal('show');
}

function deleteRecord(id) {
    $.ajax({
        url: 'delete_daily_basis.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('✅ ' + response.message);
                location.reload();
            } else {
                alert('❌ Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error deleting record.');
        },
        complete: function() {
            $('#deleteModal').modal('hide');
        }
    });
}
</script>
</body>
</html>