<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

// Handle create/update
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? '';
    $date = $_POST['date'];
    $committe_name = trim($_POST['committe_name']);
    $ref_no = $_POST['ref_no'];
    $remarks = $_POST['remarks'];    

    // Handle file upload
    $attachment = '';
    if (!empty($_FILES['attachment']['name'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES["attachment"]["name"]);
        $attachment = $targetDir . $fileName;
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment);
    }

    // ------------------- Check Duplicate Committee (only for new committee) -------------------
    if (!$id) { // Only check for new committees, not when editing
        $checkCommittee = $conn->prepare("SELECT id FROM committee_tbl WHERE committe_name=?");
        $checkCommittee->bind_param("s", $committe_name);
        $checkCommittee->execute();
        $checkCommittee->store_result();
        if ($checkCommittee->num_rows > 0) {
            echo "<script>alert('Committee name already exists!');window.history.back();</script>";
            exit;
        }
    }

    // Start transaction for data consistency
    $conn->begin_transaction();
    try {
        // If editing, remove old records for this committee (except we keep it and update)
        if ($id) {
            // Instead of deleting, we'll update existing records
            // First, delete all members of this committee
            $deleteStmt = $conn->prepare("DELETE FROM committee_tbl WHERE committe_name=?");
            $deleteStmt->bind_param("s", $committe_name);
            $deleteStmt->execute();
        }

        // Track if Chairman / Member Secretary already exists
        $roleTracker = ['Chairman' => 0, 'Member Secretary' => 0];
        $userInsertedCount = 0;

        if (!empty($_POST['name'])) {
            foreach ($_POST['name'] as $index => $name) {
                $name = trim($name);
                if (empty($name)) continue;

                $mobile_no = trim($_POST['mobile_no'][$index] ?? '');
                $designation = trim($_POST['designation'][$index] ?? '');
                $office = trim($_POST['office_ministry'][$index] ?? '');
                $division = trim($_POST['division'][$index] ?? '');
                $type = trim($_POST['type'][$index] ?? 'Member');
                $status = trim($_POST['status'][$index] ?? 'Active');

                // ------------------- Enforce only one Chairman / Secretary -------------------
                if (in_array($type, ['Chairman', 'Member Secretary'])) {
                    if ($roleTracker[$type] > 0) {
                        throw new Exception("Only one $type allowed per committee.");
                    }
                    $roleTracker[$type]++;
                }

                // ------------------- Insert into committee_tbl -------------------
                $stmt = $conn->prepare("
                    INSERT INTO committee_tbl 
                    (date, committe_name, ref_no, attachment, mobile_no, name, designation, office_ministry, division, type, status, remarks)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param(
                    "ssssssssssss",
                    $date, $committe_name, $ref_no, $attachment,
                    $mobile_no, $name, $designation, $office,
                    $division, $type, $status, $remarks
                );
                $stmt->execute();

                // ------------------- Insert into users if not exists -------------------
                if (!empty($mobile_no)) {
                    // Generate unique username from name + mobile last 4 digits
                    // $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $name);
                    // $mobileLast4 = substr($mobile_no, -4);
                    $username = strtolower($mobile_no);
                    
                    // Check if username already exists, if so, add sequence
                    //$counter = 1;
                    $originalUsername = $username;
                    while (true) {
                        $checkUser = $conn->prepare("SELECT id FROM users WHERE username=?");
                        $checkUser->bind_param("s", $username);
                        $checkUser->execute();
                        $checkUser->store_result();
                        
                        if ($checkUser->num_rows == 0) {
                            break;
                        }
                        // $username = $originalUsername . $counter;
                        // $counter++;
                    }
                    
                    $password = password_hash('123', PASSWORD_DEFAULT);
                    $role = 'user';
                    $email = '';
                    $created_at = date('Y-m-d H:i:s');
                    $updated_at = date('Y-m-d H:i:s');

                    // Check if user with this phone already exists
                    $checkPhone = $conn->prepare("SELECT id FROM users WHERE phone=?");
                    $checkPhone->bind_param("s", $mobile_no);
                    $checkPhone->execute();
                    $checkPhone->store_result();
                    
                    if ($checkPhone->num_rows == 0) {
                        $insertUser = $conn->prepare("
                            INSERT INTO users 
                            (username, password, full_name, designation, email, phone, role, created_at, updated_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ");
                        $insertUser->bind_param(
                            "sssssssss",
                            $username, $password, $name, $designation, $email, $mobile_no, $role, $created_at, $updated_at
                        );
                        $insertUser->execute();
                        $userInsertedCount++;
                    }
                }
            }

            $conn->commit();
            
            $message = "Committee and members saved successfully!";
            if ($userInsertedCount > 0) {
                $message .= " $userInsertedCount new user(s) created with default password '123'.";
            }
            
            echo "<script>alert('$message');window.location='committee_details.php';</script>";
        } else {
            throw new Exception("Please add at least one committee member.");
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');window.history.back();</script>";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get committee name before deleting
    $getCommittee = $conn->query("SELECT committe_name FROM committee_tbl WHERE id=$id");
    if ($getCommittee->num_rows > 0) {
        $committeeData = $getCommittee->fetch_assoc();
        $committeeName = $committeeData['committe_name'];
        
        // Start transaction
        $conn->begin_transaction();
        try {
            // Delete from committee_tbl
            $conn->query("DELETE FROM committee_tbl WHERE committe_name='$committeeName'");
            
            // Note: We DON'T delete from users table as users might be used elsewhere
            
            $conn->commit();
            echo "<script>alert('Committee deleted successfully!');window.location='committee_details.php';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error deleting committee: " . addslashes($e->getMessage()) . "');window.history.back();</script>";
        }
    }
}

// Fetch all committees for display
$committees = [];
$res = $conn->query("SELECT DISTINCT committe_name FROM committee_tbl ORDER BY date DESC");
while ($row = $res->fetch_assoc()) {
    $committeeName = $row['committe_name'];
    
    // Get first member details for display
    $firstMember = $conn->query("SELECT * FROM committee_tbl WHERE committe_name='$committeeName' ORDER BY 
        CASE type 
            WHEN 'Chairman' THEN 1
            WHEN 'Member Secretary' THEN 2
            ELSE 3
        END, id LIMIT 1")->fetch_assoc();
    
    if ($firstMember) {
        $committees[$committeeName] = $firstMember;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Committee Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background-color: #f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.add-row-btn { background: linear-gradient(45deg,#0d6efd,#0dcaf0); color:white; border:none; }
.remove-row-btn { background: #dc3545; color:white; border:none; }
.table th { background-color: #f1f5fd; }
</style>
</head>
<body>

<div class="container py-1">
    <div class="card mb-1">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Add / Edit Committee</h4>
            <span id="formStatus" class="badge bg-light text-dark">New Committee</span>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" id="committeeForm">
                <input type="hidden" name="id" id="id" value="">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" id="date" required 
                               value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Committee Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="committe_name" id="committe_name" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reference No</label>
                        <input type="text" class="form-control" name="ref_no" id="ref_no">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Attachment</label>
                        <input type="file" class="form-control" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="2"></textarea>
                    </div>
                </div>             

                <h5 class="mt-4 mb-3 text-primary">
                    <i class="fas fa-users me-2"></i>Committee Members
                    <small class="text-muted fs-6">(Add at least one member)</small>
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered" id="examinerTable">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Name <span class="text-danger">*</span></th>
                                <th width="15%">Mobile No</th>
                                <th width="20%">Designation</th>
                                <th width="15%">Office/Ministry</th>
                                <th width="10%">Division</th>
                                <th width="10%">Member Type</th>
                                <th width="15%">Status</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="name[]" class="form-control" placeholder="Enter full name" required></td>
                                <td><input type="text" name="mobile_no[]" class="form-control" placeholder="01XXXXXXXXX"></td>
                                <td><input type="text" name="designation[]" class="form-control" placeholder="Designation"></td>
                                <td><input type="text" name="office_ministry[]" class="form-control" placeholder="Office/Ministry"></td>
                                <td><input type="text" name="division[]" class="form-control" placeholder="Division"></td>
                                <td>
                                    <select name="type[]" class="form-select">
                                        <option value="Chairman">Chairman</option>
                                        <option value="Member Secretary">Member Secretary</option>
                                        <option value="Member" selected>Member</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="status[]" class="form-select">
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row-btn" title="Remove row">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button type="button" id="addRow" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Member
                    </button>
                    <div class="text-muted small" id="memberCount">1 member added</div>
                </div>

                <div class="text-end border-top pt-3">
                    <button type="button" class="btn btn-outline-secondary btn-md" id="clearAll">
                        <i class="fas fa-eraser me-1"></i>Clear All
                    </button>
                    <button type="submit" name="save" class="btn btn-success ">
                        <i class="fas fa-save me-1"></i>Save Committee
                    </button>
                    <a href="admin_dashboard.php" class="btn btn-primary ">
                        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-list me-2"></i>All Committees</h4>
            <span class="badge bg-light text-dark"><?= count($committees) ?> Committees</span>
        </div>
        <div class="card-body">
            <table id="committeeTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Committee Name</th>
                        <th>Ref No</th>
                        <th>Chairman</th>
                        <th>Members</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($committees as $committeeName => $main) {
                    // Count total members in this committee
                    $memberCount = $conn->query("SELECT COUNT(*) as total FROM committee_tbl WHERE committe_name='$committeeName'")->fetch_assoc()['total'];
                    
                    // Get chairman name
                    $chairmanQuery = $conn->query("SELECT name FROM committee_tbl WHERE committe_name='$committeeName' AND type='Chairman' LIMIT 1");
                    $chairman = $chairmanQuery->num_rows > 0 ? $chairmanQuery->fetch_assoc()['name'] : 'Not Assigned';
                    
                    // Get all members for edit button
                    $membersQuery = $conn->query("SELECT * FROM committee_tbl WHERE committe_name='$committeeName' ORDER BY 
                        CASE type 
                            WHEN 'Chairman' THEN 1
                            WHEN 'Member Secretary' THEN 2
                            ELSE 3
                        END, id");
                    $allMembers = [];
                    while ($member = $membersQuery->fetch_assoc()) {
                        $allMembers[] = $member;
                    }
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= date('d/m/Y', strtotime($main['date'])) ?></td>
                        <td><strong><?= htmlspecialchars($committeeName) ?></strong></td>
                        <td><?= htmlspecialchars($main['ref_no']) ?></td>
                        <td><?= htmlspecialchars($chairman) ?></td>
                        <td>
                            <span class="badge bg-info"><?= $memberCount ?> members</span>
                        </td>
                        <td>
                            <?php if ($main['attachment']): ?>
                                <a href="<?= $main['attachment'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No file</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class='btn btn-sm btn-info editBtn me-1' 
                                    data-members='<?= htmlspecialchars(json_encode($allMembers), ENT_QUOTES, 'UTF-8') ?>'
                                    data-main='<?= htmlspecialchars(json_encode($main), ENT_QUOTES, 'UTF-8') ?>'
                                    title="Edit Committee">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <a href='?delete=<?= $main['id'] ?>' 
                               class='btn btn-sm btn-danger' 
                               onclick="return confirm('Are you sure you want to delete this committee?\n\nCommittee: <?= addslashes($committeeName) ?>\n\nThis will remove all committee members but will NOT delete user accounts.')"
                               title="Delete Committee">
                                <i class="fas fa-trash me-1"></i>Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                
                if (empty($committees)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                No committees found. Create your first committee above.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#committeeTable').DataTable({
        "pageLength": 10,
        "order": [[0, 'asc']]
    });

    // Function to update member count
    function updateMemberCount() {
        const count = $('#examinerTable tbody tr').length;
        $('#memberCount').text(count + ' member' + (count !== 1 ? 's' : '') + ' added');
    }

    // Add new member row dynamically
    $('#addRow').click(function() {
        const row = $('#examinerTable tbody tr:first').clone();
        row.find('input').val('');
        row.find('select').each(function() {
            if ($(this).attr('name') === 'type[]') {
                $(this).val('Member');
            } else if ($(this).attr('name') === 'status[]') {
                $(this).val('Active');
            }
        });
        $('#examinerTable tbody').append(row);
        updateMemberCount();
    });

    // Remove member row
    $(document).on('click', '.remove-row-btn', function() {
        if ($('#examinerTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateMemberCount();
        } else {
            alert('At least one member is required!');
        }
    });

    // Clear All button
    $('#clearAll').on('click', function() {
        if (confirm('Are you sure you want to clear all data? This will reset the form.')) {
            // Reset form
            $('#id').val('');
            $('#date').val('<?= date('Y-m-d') ?>');
            $('#committe_name').val('');
            $('#ref_no').val('');
            $('#remarks').val('');
            $('#attachment').val('');
            
            // Reset table to one row
            $('#examinerTable tbody').html(`
                <tr>
                    <td><input type="text" name="name[]" class="form-control" placeholder="Enter full name" required></td>
                    <td><input type="text" name="mobile_no[]" class="form-control" placeholder="01XXXXXXXXX"></td>
                    <td><input type="text" name="designation[]" class="form-control" placeholder="Designation"></td>
                    <td><input type="text" name="office_ministry[]" class="form-control" placeholder="Office/Ministry"></td>
                    <td><input type="text" name="division[]" class="form-control" placeholder="Division"></td>
                    <td>
                        <select name="type[]" class="form-select">
                            <option value="Chairman">Chairman</option>
                            <option value="Member Secretary">Member Secretary</option>
                            <option value="Member" selected>Member</option>
                        </select>
                    </td>
                    <td>
                        <select name="status[]" class="form-select">
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row-btn" title="Remove row">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            $('#formStatus').text('New Committee').removeClass('bg-warning').addClass('bg-light text-dark');
            updateMemberCount();
            
            // Show success message
            showAlert('Form cleared successfully!', 'success');
        }
    });

    // Edit button logic
    $(document).on('click', '.editBtn', function() {
        const main = JSON.parse($(this).attr('data-main'));
        const members = JSON.parse($(this).attr('data-members'));

        // Fill main form fields
        $('#id').val(main.id);
        $('#date').val(main.date);
        $('#committe_name').val(main.committe_name);
        $('#ref_no').val(main.ref_no);
        $('#remarks').val(main.remarks);

        // Clear and repopulate member table
        const tbody = $('#examinerTable tbody');
        tbody.empty();

        members.forEach(m => {
            const row = `<tr>
                <td><input type="text" name="name[]" class="form-control" value="${escapeHtml(m.name || '')}" required></td>
                <td><input type="text" name="mobile_no[]" class="form-control" value="${escapeHtml(m.mobile_no || '')}"></td>
                <td><input type="text" name="designation[]" class="form-control" value="${escapeHtml(m.designation || '')}"></td>
                <td><input type="text" name="office_ministry[]" class="form-control" value="${escapeHtml(m.office_ministry || '')}"></td>
                <td><input type="text" name="division[]" class="form-control" value="${escapeHtml(m.division || '')}"></td>
                <td>
                    <select name="type[]" class="form-select">
                        <option value="Chairman" ${m.type === 'Chairman' ? 'selected' : ''}>Chairman</option>
                        <option value="Member Secretary" ${m.type === 'Member Secretary' ? 'selected' : ''}>Member Secretary</option>
                        <option value="Member" ${m.type === 'Member' ? 'selected' : ''}>Member</option>
                    </select>
                </td>
                <td>
                    <select name="status[]" class="form-select">
                        <option value="Active" ${m.status === 'Active' ? 'selected' : ''}>Active</option>
                        <option value="Inactive" ${m.status === 'Inactive' ? 'selected' : ''}>Inactive</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row-btn" title="Remove row">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>`;
            tbody.append(row);
        });

        // Update form status
        $('#formStatus').text('Editing: ' + main.committe_name).removeClass('bg-light text-dark').addClass('bg-warning text-white');
        updateMemberCount();

        // Scroll to form
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Show alert
        showAlert('Now editing: ' + main.committe_name, 'info');
    });

    // Form validation before submit
    $('#committeeForm').on('submit', function(e) {
        // Check for duplicate Chairman/Member Secretary
        const chairmanCount = $('select[name="type[]"]').filter(function() {
            return $(this).val() === 'Chairman';
        }).length;
        
        const secretaryCount = $('select[name="type[]"]').filter(function() {
            return $(this).val() === 'Member Secretary';
        }).length;
        
        if (chairmanCount > 1) {
            alert('Error: Only one Chairman is allowed per committee.');
            e.preventDefault();
            return false;
        }
        
        if (secretaryCount > 1) {
            alert('Error: Only one Member Secretary is allowed per committee.');
            e.preventDefault();
            return false;
        }
        
        // Check for at least one member
        const memberCount = $('#examinerTable tbody tr').length;
        if (memberCount === 0) {
            alert('Error: Please add at least one committee member.');
            e.preventDefault();
            return false;
        }
        
        return true;
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Helper function to show alerts
    function showAlert(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        // Remove any existing alerts
        $('.custom-alert').remove();
        
        // Create alert
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show custom-alert position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            $('.custom-alert').alert('close');
        }, 3000);
    }

    // Initialize member count
    updateMemberCount();
});
</script>
</body>
</html>