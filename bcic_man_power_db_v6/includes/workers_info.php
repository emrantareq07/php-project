<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch all worker records for this factory
$records = [];
$sql = "SELECT * FROM workers_tbl WHERE factory_name = ? ORDER BY date DESC, created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Worker Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .table-responsive {
            max-height: 70vh;
        }
        .badge-success { background-color: #28a745; }
        .badge-secondary { background-color: #6c757d; }
        .action-buttons .btn {
            margin: 2px;
        }
        .worker-details {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .summary-card {
            transition: transform 0.2s;
        }
        .summary-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fas fa-hard-hat"></i> Worker Data Records</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Factory: <?php echo htmlspecialchars($username); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card summary-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Records</h6>
                            <h3 class="mb-0"><?php echo count($records); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-database fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summary-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Records</h6>
                            <h3 class="mb-0">
                                <?php 
                                $active_count = array_filter($records, function($record) {
                                    return $record['status'] === 'active';
                                });
                                echo count($active_count);
                                ?>
                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summary-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Dates Covered</h6>
                            <h3 class="mb-0">
                                <?php 
                                $dates = array_unique(array_column($records, 'date'));
                                echo count($dates);
                                ?>
                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card summary-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Last Updated</h6>
                            <h6 class="mb-0">
                                <?php 
                                if (!empty($records)) {
                                    echo date('M j, Y', strtotime($records[0]['updated_at']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </h6>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">All Worker Records</h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="worker_form.php" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Add New Record
                    </a>
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
                            <th>Created</th>
                            <th>Updated</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($records)): ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    No worker records found. <a href="worker_form.php">Add your first record</a>.
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
                                <td><?php echo date('M j, Y', strtotime($record['created_at'])); ?></td>
                                <td><?php echo date('M j, Y', strtotime($record['updated_at'])); ?></td>
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
    // View details
    $('.view-btn').on('click', function() {
        const id = $(this).data('id');
        viewRecord(id);
    });

    // Edit record
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        // Redirect to edit page or load in modal
        window.location.href = 'worker_form.php?edit=' + id;
    });

    // Delete record
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
        url: 'get_worker_data.php',
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