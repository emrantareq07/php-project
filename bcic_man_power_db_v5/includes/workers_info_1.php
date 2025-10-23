<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$table = 'workers_tbl';

$today_date = date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));
$first_day_next_month = date('Y-m-01', strtotime('+1 month'));

// Check if we're editing an existing record
$editing_record_id = null;
$is_editing = false;
$edit_record = null; // Initialize to avoid undefined variable

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

// Check if we're loading a specific record for editing
if (isset($_GET['edit_id'])) {
    $editing_record_id = $_GET['edit_id'];
    $is_editing = true;
    
    // Fetch the specific record for editing
    $edit_sql = "SELECT * FROM $table WHERE id = ? AND factory_name = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param('is', $editing_record_id, $username);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_record = $edit_result->fetch_assoc();
    } else {
        $is_editing = false;
        $editing_record_id = null;
        $edit_record = null;
    }
    $edit_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .worker-section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .duplicate-error {
            color: #dc3545;
            font-size: 0.875em;
            display: none;
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
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="worker-section">
        <div class="header-info">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-hard-hat"></i> Worker Information</h5>
                </div>
                <div class="col-md-6 text-end">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Factory:</strong> <?php echo $_SESSION['username']; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong> 
                            <input type="date" class="form-control d-inline-block w-auto" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

<form id="workerForm">
    <input type="hidden" name="factory_name" value="<?php echo $_SESSION['username']; ?>">
    <input type="hidden" name="record_id" id="record_id" value="<?php echo $editing_record_id ? $editing_record_id : ''; ?>">
    
    <div class="table-responsive">
        <table class="table table-bordered" id="worker_table">
            <thead class="table-light">
                <tr>
                    <td colspan="9" class="text-end">
                        <button type="button" id="add_worker" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Add Row
                        </button>
                        
                        <!-- Separate Save and Update buttons -->
                        <button type="submit" id="save_btn" class="btn btn-primary btn-sm" <?php echo $is_editing ? 'style="display:none;"' : ''; ?>>
                            <i class="fa fa-save"></i> Save New
                        </button>
                        
                        <button type="button" id="update_btn" class="btn btn-warning btn-sm" <?php echo !$is_editing ? 'style="display:none;"' : ''; ?>>
                            <i class="fa fa-sync"></i> Update Record
                        </button>
                        
                        <button type="button" id="cancel_edit" class="btn btn-secondary btn-sm" <?php echo !$is_editing ? 'style="display:none;"' : ''; ?>>
                            <i class="fa fa-times"></i> Cancel Edit
                        </button>
                        
                        <button type="button" id="load_previous" class="btn btn-info btn-sm">
                            <i class="fa fa-history"></i> Load Previous
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
                            <input type="text" class="form-control designation" name="designation[]" value="<?php echo htmlspecialchars($designations[$i] ?? ''); ?>" required>
                        </td>
                        <td>
                            <select class="form-select grade-select" name="grade[]" required>
                                <option value="">Select Grade</option>
                                <?php for($j = 1; $j <= 16; $j++): ?>
                                <option value="Grade <?php echo $j; ?>" <?php echo ($grades[$i] ?? '') == "Grade $j" ? 'selected' : ''; ?>>Grade <?php echo $j; ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="duplicate-error">This grade has already been selected</div>
                        </td>
                        <td>
                            <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" min="0" value="<?php echo $sanctioned_posts[$i] ?? 0; ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control male" name="male[]" min="0" value="<?php echo $males[$i] ?? 0; ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control female" name="female[]" min="0" value="<?php echo $females[$i] ?? 0; ?>" required>
                        </td>
                        <td>
                            <input type="number" class="form-control total total-cell" name="total[]" min="0" value="<?php echo $totals[$i] ?? 0; ?>" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control vacant-post" name="vacant_post[]" min="0" value="<?php echo ($sanctioned_posts[$i] ?? 0) - ($totals[$i] ?? 0); ?>" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-worker" <?php echo $i == 0 ? 'disabled' : ''; ?>>
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endfor; ?>
                    
                <?php else: ?>
                    <!-- Default empty row for new entry -->
                    <tr class="worker-row">
                        <td>
                            <input type="text" class="form-control designation" name="designation[]" placeholder="Enter designation" required>
                        </td>
                        <td>
                            <select class="form-select grade-select" name="grade[]" required>
                                <option value="">Select Grade</option>
                                <?php for($i = 1; $i <= 16; $i++): ?>
                                <option value="Grade <?php echo $i; ?>">Grade <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="duplicate-error">This grade has already been selected</div>
                        </td>
                        <td>
                            <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" min="0" value="0" required>
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
                    <h5 class="mb-0"><i class="fas fa-database"></i> Worker Records</h5>
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
                            <th width="150">Actions</th>
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
                            <?php foreach ($records as $record): ?>
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
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $record['id']; ?>" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm view-btn" data-id="<?php echo $record['id']; ?>" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $record['id']; ?>" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

    // Initialize row count based on existing rows
    updateRowCount();

    // Add new row
    $('#add_worker').on('click', function() {
        const newRow = `
            <tr class="worker-row">
                <td>
                    <input type="text" class="form-control designation" name="designation[]" placeholder="Enter designation" required>
                </td>
                <td>
                    <select class="form-select grade-select" name="grade[]" required>
                        <option value="">Select Grade</option>
                        <?php for($i = 1; $i <= 16; $i++): ?>
                        <option value="Grade <?php echo $i; ?>">Grade <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <div class="duplicate-error">This grade has already been selected</div>
                </td>
                <td>
                    <input type="number" class="form-control sanctioned-post" name="sanctioned_post[]" min="0" value="0" required>
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
        checkDuplicateGrades();
    });

    // Remove row
    $(document).on('click', '.remove-worker', function() {
        if (rowCount > 1) {
            $(this).closest('tr').remove();
            updateRowCount();
            checkDuplicateGrades();
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

    // Check for duplicate grades
    $(document).on('change', '.grade-select', checkDuplicateGrades);

    function checkDuplicateGrades() {
        const selectedGrades = [];
        let hasDuplicates = false;
        
        $('.grade-select').each(function() {
            const grade = $(this).val();
            if (grade) {
                if (selectedGrades.includes(grade)) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.duplicate-error').show();
                    hasDuplicates = true;
                } else {
                    selectedGrades.push(grade);
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.duplicate-error').hide();
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.duplicate-error').hide();
            }
        });
        
        return hasDuplicates;
    }

    // Save New Record
    $('#workerForm').on('submit', function(e) {
        e.preventDefault();
        if (!validateForm()) return;
        
        if (checkDuplicateGrades()) {
            alert('Please fix duplicate grades before saving.');
            return;
        }

        const submitBtn = $('#save_btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: 'save_worker.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'workers_info_1.php'; // Reload to clear form
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Save Error:', error);
                alert('❌ Error saving data: ' + error);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Update Record
    $('#update_btn').on('click', function() {
        if (!validateForm()) return;
        
        if (checkDuplicateGrades()) {
            alert('Please fix duplicate grades before saving.');
            return;
        }

        const updateBtn = $(this);
        const originalText = updateBtn.html();
        updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

        // Add record_id to form data
        const formData = $('#workerForm').serialize();

        $.ajax({
            url: 'update_worker.php', // New file for updates
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = 'workers_info_1.php'; // Return to normal view
                } else {
                    alert('❌ Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Update Error:', error);
                alert('❌ Error updating data: ' + error);
            },
            complete: function() {
                updateBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Cancel Edit
    $('#cancel_edit').on('click', function() {
        if (confirm('Are you sure you want to cancel editing? Any unsaved changes will be lost.')) {
            window.location.href = 'workers_info_1.php';
        }
    });

    // Load previous data
    $('#load_previous').on('click', function() {
        const date = $('#date').val();
        const factoryName = '<?php echo $_SESSION['username']; ?>';
        
        if (!date) {
            alert('Please select a date first.');
            return;
        }
        
        $.ajax({
            url: 'get_worker_data.php',
            type: 'POST',
            data: { 
                date: date,
                factory_name: factoryName
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    populateForm(response.data);
                    alert('✅ Data loaded successfully!');
                    
                    // Switch to update mode if loading existing data
                    if (response.data.id) {
                        switchToUpdateMode(response.data.id);
                    }
                } else {
                    alert('ℹ️ ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Load Error:', error);
                alert('❌ Error loading data: ' + error);
            }
        });
    });

    function populateForm(data) {
        // Clear existing rows except first one
        $('.worker-row:not(:first)').remove();
        
        // Reset first row
        const firstRow = $('.worker-row:first');
        firstRow.find('.designation').val('');
        firstRow.find('.grade-select').val('');
        firstRow.find('.sanctioned-post').val(0);
        firstRow.find('.male').val(0);
        firstRow.find('.female').val(0);
        firstRow.find('.total').val(0);
        firstRow.find('.vacant-post').val(0);
        
        // Split comma-separated values
        const designations = data.designation ? data.designation.split(',') : [];
        const grades = data.grade ? data.grade.split(',') : [];
        const sanctionedPosts = data.sanctioned_post ? data.sanctioned_post.split(',') : [];
        const males = data.male ? data.male.split(',') : [];
        const females = data.female ? data.female.split(',') : [];
        
        // Populate rows
        for (let i = 0; i < designations.length; i++) {
            if (designations[i] && designations[i].trim()) {
                if (i > 0) {
                    $('#add_worker').trigger('click');
                }
                
                const currentRow = $('.worker-row').eq(i);
                currentRow.find('.designation').val(designations[i].trim());
                currentRow.find('.grade-select').val(grades[i].trim());
                currentRow.find('.sanctioned-post').val(sanctionedPosts[i].trim());
                currentRow.find('.male').val(males[i].trim());
                currentRow.find('.female').val(females[i].trim());
                
                calculateRowTotals(currentRow);
            }
        }
        
        updateRowCount();
        checkDuplicateGrades();
    }

    function switchToUpdateMode(recordId) {
        isEditing = true;
        editingRecordId = recordId;
        $('#record_id').val(recordId);
        $('#save_btn').hide();
        $('#update_btn').show();
        $('#cancel_edit').show();
    }

    function validateForm() {
        const date = $('#date').val();
        if (!date) {
            alert('Please select a date.');
            return false;
        }

        let isValid = true;
        $('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill all required fields.');
            return false;
        }
        
        return true;
    }

    // Update edit button behavior - FIXED FILE NAME
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        window.location.href = 'workers_info_1.php?edit_id=' + id;
    });

    // Add the missing CRUD functions
    $('.view-btn').on('click', function() {
        const id = $(this).data('id');
        viewRecord(id);
    });

    $('.delete-btn').on('click', function() {
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

// Add the missing functions outside document ready
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
            title = 'Male Workers';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'female':
            title = 'Female Workers';
            content = items.map((item, index) => 
                `<div class="worker-details">
                    <strong>${index + 1}.</strong> ${parseInt(item).toLocaleString()}
                </div>`
            ).join('');
            break;
            
        case 'total':
            title = 'Total Workers';
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

function viewRecord(id) {
    $.ajax({
        url: 'get_worker_record.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let content = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Date:</strong> ${data.date}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge ${data.status === 'active' ? 'badge-success' : 'badge-secondary'}">
                                ${data.status}
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Designation</th>
                                    <th>Grade</th>
                                    <th>Sanctioned Post</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Total</th>
                                    <th>Vacant</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                const designations = data.designation.split(',');
                const grades = data.grade.split(',');
                const sanctionedPosts = data.sanctioned_post.split(',');
                const males = data.male.split(',');
                const females = data.female.split(',');
                const totals = data.total.split(',');
                
                let grandTotalMale = 0;
                let grandTotalFemale = 0;
                let grandTotal = 0;
                let grandSanctioned = 0;
                
                for (let i = 0; i < designations.length; i++) {
                    if (designations[i].trim()) {
                        const male = parseInt(males[i]) || 0;
                        const female = parseInt(females[i]) || 0;
                        const total = parseInt(totals[i]) || 0;
                        const sanctioned = parseInt(sanctionedPosts[i]) || 0;
                        const vacant = sanctioned - total;
                        
                        grandTotalMale += male;
                        grandTotalFemale += female;
                        grandTotal += total;
                        grandSanctioned += sanctioned;
                        
                        content += `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${designations[i].trim()}</td>
                                <td>${grades[i].trim()}</td>
                                <td class="text-end">${sanctioned.toLocaleString()}</td>
                                <td class="text-end">${male.toLocaleString()}</td>
                                <td class="text-end">${female.toLocaleString()}</td>
                                <td class="text-end"><strong>${total.toLocaleString()}</strong></td>
                                <td class="text-end ${vacant < 0 ? 'text-danger' : ''}">
                                    <strong>${vacant.toLocaleString()}</strong>
                                </td>
                            </tr>
                        `;
                    }
                }
                
                const grandVacant = grandSanctioned - grandTotal;
                
                content += `
                            <tr class="table-primary">
                                <td colspan="3"><strong>Grand Total</strong></td>
                                <td class="text-end"><strong>${grandSanctioned.toLocaleString()}</strong></td>
                                <td class="text-end"><strong>${grandTotalMale.toLocaleString()}</strong></td>
                                <td class="text-end"><strong>${grandTotalFemale.toLocaleString()}</strong></td>
                                <td class="text-end"><strong>${grandTotal.toLocaleString()}</strong></td>
                                <td class="text-end ${grandVacant < 0 ? 'text-danger' : ''}">
                                    <strong>${grandVacant.toLocaleString()}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                `;
                
                $('#detailsModalTitle').text('Complete Worker Details - ' + data.date);
                $('#detailsModalBody').html(content);
                $('#detailsModal').modal('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error loading record details.');
        }
    });
}

function deleteRecord(id) {
    $.ajax({
        url: 'delete_worker.php',
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