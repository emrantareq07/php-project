<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

date_default_timezone_set("Asia/Dhaka");

// Fetch all committees with their designations
$query = "SELECT 
    est.id,
    est.committe_name,
    est.designation,
    est.date,
    est.time,
    COUNT(ct.id) as total_candidates
FROM 
    exam_schedule_tbl est
LEFT JOIN 
    candidates_tbl ct ON est.id = ct.exam_schedule_id
    AND est.designation = ct.designation
GROUP BY 
    est.id, est.committe_name, est.designation
ORDER BY 
    est.date DESC, est.time DESC";

$result = mysqli_query($conn, $query);

// Fetch ALL committees for the modal
$allCommitteesQuery = "SELECT id, committe_name, designation FROM exam_schedule_tbl ORDER BY designation, committe_name";
$allCommitteesResult = mysqli_query($conn, $allCommitteesQuery);
$committeesArray = [];
while ($committee = mysqli_fetch_assoc($allCommitteesResult)) {
    $committeesArray[] = $committee;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Committee Exam Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        h3 { background: linear-gradient(45deg, #007bff, #6610f2); color: #fff; padding: 10px; border-radius: 6px; }
        .table-hover tbody tr:hover { background-color: #f1f1f1; }
        .badge-count { font-size: 0.8em; }
        .action-buttons { display: flex; gap: 5px; justify-content: center; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .committee-checkbox { margin-right: 8px; }
        .designation-card { margin-bottom: 15px; }
        .committee-item { padding: 5px 0; border-bottom: 1px solid #eee; }
        .committee-item:last-child { border-bottom: none; }
        .designation-header { background-color: #e7f1ff !important; }
        .single-committee { background-color: #fff3cd !important; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3 class="text-center mb-4">Committee Exam Schedule</h3>
    <a href="admin_dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>
    
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Committee List</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Committee Name</th>
                        <th>Designation</th>
                        <th>Candidates</th>
                        <th>Exam Date</th>
                        <th>Exam Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if (mysqli_num_rows($result) > 0) { 
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-center">
                            <td><?= $i++ ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['committe_name']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['designation']) ?></td>
                            <td>
                                <span class="badge bg-info rounded-pill badge-count">
                                    <?= $row['total_candidates'] ?> candidates
                                </span>
                            </td>
                            <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                            <td><?= date('h:i A', strtotime($row['time'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="print_candidates.php?exam_schedule_id=<?= $row['id'] ?>&committee=<?= urlencode($row['committe_name']) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-success">
                                       <i class="bi bi-list-check"></i> Candidates
                                    </a>
                                    <a href="merit_list.php?exam_schedule_id=<?= $row['id'] ?>&committee=<?= urlencode($row['committe_name']) ?>&designation=<?= urlencode($row['designation']) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-warning">
                                       <i class="bi bi-trophy"></i> Merit
                                    </a>
                                    <!-- Combine Merit List Button -->
                                    <button onclick="showCombineOptions(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['designation'])) ?>', '<?= htmlspecialchars(addslashes($row['committe_name'])) ?>')" 
                                            class="btn btn-sm btn-info">
                                        <i class="bi bi-trophy-fill"></i> Combine
                                    </button>
                                </div>
                            </td>
                        </tr>
                <?php } 
                } else { ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x display-6 d-block mb-2"></i>
                            No committee schedules found.
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Combine Merit List Modal -->
<div class="modal fade" id="combineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Combine Merit List</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <h6><i class="bi bi-info-circle"></i> Combine Committees for Same Designation</h6>
                    <p class="mb-1"><strong>Starting from:</strong> <span id="currentCommitteeName" class="badge bg-info"></span></p>
                    <p class="mb-0"><strong>Designation:</strong> <span id="currentDesignationName" class="badge bg-warning"></span></p>
                </div>
                
                <form id="combineForm" action="combine_merit_list.php" method="GET" target="_blank">
                    <input type="hidden" id="currentDesignation" name="designation">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-check2-square"></i> Select committees to combine:
                        </label>
                        
                        <div id="committeesContainer">
                            <!-- Committees will be populated by JavaScript -->
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-arrow-clockwise"></i> Select a committee first...
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Important:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Only committees with <strong>same designation</strong> can be combined</li>
                                <li>You can select 1 or more committees</li>
                                <li>If only 1 committee is selected, it will show individual merit list</li>
                                <li>If 2+ committees are selected, they will be ranked together</li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="generateBtn" onclick="submitCombineForm()" disabled>
                    <i class="bi bi-trophy-fill"></i> Generate Merit List
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Store current selection info
let currentSelection = {
    scheduleId: null,
    designation: '',
    committee: ''
};

// Store all committees data from PHP
const allCommittees = <?php echo json_encode($committeesArray); ?>;

function showCombineOptions(scheduleId, designation, committee) {
    currentSelection = {
        scheduleId: parseInt(scheduleId),
        designation: designation,
        committee: committee
    };
    
    console.log('Current selection:', currentSelection);
    
    // Update modal display
    document.getElementById('currentCommitteeName').textContent = committee;
    document.getElementById('currentDesignationName').textContent = designation;
    document.getElementById('currentDesignation').value = designation;
    
    // Load and display committees
    loadCommitteesIntoModal();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('combineModal'));
    modal.show();
}

function loadCommitteesIntoModal() {
    const container = document.getElementById('committeesContainer');
    
    if (allCommittees.length === 0) {
        container.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                No committees found in the database.
            </div>
        `;
        return;
    }
    
    // Group committees by designation
    const committeesByDesignation = {};
    allCommittees.forEach(committee => {
        if (!committeesByDesignation[committee.designation]) {
            committeesByDesignation[committee.designation] = [];
        }
        committeesByDesignation[committee.designation].push(committee);
    });
    
    let html = '';
    
    // Show ALL designations with their committees
    Object.keys(committeesByDesignation).forEach(designation => {
        const committees = committeesByDesignation[designation];
        const isCurrentDesignation = designation === currentSelection.designation;
        const isSingleCommittee = committees.length === 1;
        
        html += createDesignationSection(designation, committees, isCurrentDesignation, isSingleCommittee);
    });
    
    container.innerHTML = html;
    
    // Auto-check committees with same designation as current
    setTimeout(() => {
        const checkboxes = document.querySelectorAll('.committee-checkbox');
        
        checkboxes.forEach(cb => {
            if (cb.dataset.designation === currentSelection.designation) {
                cb.checked = true;
            }
        });
        
        // Update select-all checkboxes
        updateSelectAllCheckboxes();
        updateGenerateButton();
    }, 100);
}

function createDesignationSection(designation, committees, isCurrent = false, isSingle = false) {
    const designationId = designation.replace(/[^a-zA-Z0-9]/g, '_');
    const cardClass = isCurrent ? 'border-primary' : (isSingle ? 'single-committee' : '');
    
    return `
        <div class="designation-card card mb-3 ${cardClass}">
            <div class="card-header ${isCurrent ? 'designation-header' : (isSingle ? 'bg-warning' : 'bg-light')} py-2">
                <div class="form-check">
                    <input class="form-check-input select-all-checkbox" 
                           type="checkbox" 
                           id="selectAll_${designationId}"
                           data-designation="${designation}"
                           ${isSingle ? 'disabled' : ''}>
                    <label class="form-check-label fw-bold" for="selectAll_${designationId}">
                        ${designation} 
                        ${isCurrent ? '<span class="badge bg-primary ms-2">Current</span>' : ''}
                        ${isSingle ? '<span class="badge bg-warning text-dark ms-2">Single Committee</span>' : ''}
                        <span class="badge bg-secondary ms-2">${committees.length} committee(s)</span>
                    </label>
                </div>
            </div>
            <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                ${committees.map(committee => `
                    <div class="committee-item">
                        <div class="form-check">
                            <input class="form-check-input committee-checkbox" 
                                   type="checkbox" 
                                   name="committee_ids[]" 
                                   value="${committee.id}"
                                   id="committee_${committee.id}"
                                   data-designation="${designation}"
                                   ${committee.id == currentSelection.scheduleId ? 'checked' : ''}
                                   ${committees.length === 1 ? 'disabled' : ''}>
                            <label class="form-check-label ${committee.id == currentSelection.scheduleId ? 'fw-bold text-primary' : ''}" 
                                   for="committee_${committee.id}">
                                ${committee.committe_name}
                                ${committee.id == currentSelection.scheduleId ? '<span class="badge bg-info ms-2">Current</span>' : ''}
                                ${committees.length === 1 ? '<span class="badge bg-secondary ms-2">Only One</span>' : ''}
                            </label>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

// Event delegation for dynamically loaded checkboxes
document.addEventListener('click', function(e) {
    // Select all checkboxes for a designation
    if (e.target.classList.contains('select-all-checkbox') && !e.target.disabled) {
        const designation = e.target.dataset.designation;
        const checkboxes = document.querySelectorAll(`.committee-checkbox[data-designation="${designation}"]:not([disabled])`);
        
        checkboxes.forEach(cb => {
            cb.checked = e.target.checked;
        });
        
        updateGenerateButton();
    }
});

// Handle checkbox changes
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('committee-checkbox')) {
        updateSelectAllCheckboxes();
        updateGenerateButton();
    }
});

function updateSelectAllCheckboxes() {
    document.querySelectorAll('.select-all-checkbox:not([disabled])').forEach(selectAll => {
        const designation = selectAll.dataset.designation;
        const checkboxes = document.querySelectorAll(`.committee-checkbox[data-designation="${designation}"]:not([disabled])`);
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        
        if (checkedCount === 0) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        } else if (checkedCount === checkboxes.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        }
    });
}

function updateGenerateButton() {
    const selectedCheckboxes = document.querySelectorAll('.committee-checkbox:checked');
    const generateBtn = document.getElementById('generateBtn');
    const selectedCount = selectedCheckboxes.length;
    
    if (selectedCount >= 1) {
        generateBtn.disabled = false;
        if (selectedCount === 1) {
            generateBtn.innerHTML = `<i class="bi bi-trophy"></i> Generate Individual Merit List`;
        } else {
            generateBtn.innerHTML = `<i class="bi bi-trophy-fill"></i> Generate Combined (${selectedCount} committees)`;
        }
    } else {
        generateBtn.disabled = true;
        generateBtn.innerHTML = `<i class="bi bi-trophy-fill"></i> Generate Merit List`;
    }
}

function submitCombineForm() {
    const selectedCheckboxes = document.querySelectorAll('.committee-checkbox:checked');
    const form = document.getElementById('combineForm');
    
    // Check if at least one committee is selected
    if (selectedCheckboxes.length < 1) {
        alert('Please select at least ONE committee.');
        return;
    }
    
    // If only one committee is selected, redirect to individual merit list
    if (selectedCheckboxes.length === 1) {
        const committeeId = selectedCheckboxes[0].value;
        const designation = selectedCheckboxes[0].dataset.designation;
        const committeeName = encodeURIComponent(selectedCheckboxes[0].nextElementSibling.textContent.trim().replace('Current', '').replace('Only One', '').trim());
        
        window.open(`merit_list.php?exam_schedule_id=${committeeId}&committee=${committeeName}&designation=${encodeURIComponent(designation)}`, '_blank');
        return;
    }
    
    // For multiple committees, validate they have same designation
    const firstDesignation = selectedCheckboxes[0].dataset.designation;
    let isValid = true;
    
    selectedCheckboxes.forEach(checkbox => {
        if (checkbox.dataset.designation !== firstDesignation) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        alert('Error: You can only combine committees with the SAME designation.\n\nPlease select committees from only one designation group.');
        return;
    }
    
    // Submit the form for combined merit list
    form.submit();
}

// Initialize Bootstrap modal
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal if needed
    const combineModal = document.getElementById('combineModal');
    if (combineModal) {
        combineModal.addEventListener('hidden.bs.modal', function () {
            // Reset modal state when closed
            document.getElementById('currentCommitteeName').textContent = '';
            document.getElementById('currentDesignationName').textContent = '';
            document.getElementById('currentDesignation').value = '';
            document.getElementById('committeesContainer').innerHTML = '<div class="text-center text-muted py-3"><i class="bi bi-arrow-clockwise"></i> Select a committee first...</div>';
            document.getElementById('generateBtn').disabled = true;
            document.getElementById('generateBtn').innerHTML = '<i class="bi bi-trophy-fill"></i> Generate Merit List';
        });
    }
});
</script>
</body>
</html>