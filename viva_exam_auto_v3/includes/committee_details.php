<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');


// Handle create/update
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
        $attachment = $targetDir . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment);
    }

    // ------------------- Check Duplicate Committee -------------------
    $checkCommittee = $conn->prepare("SELECT id FROM committee_tbl WHERE committe_name=?");
    $checkCommittee->bind_param("s", $committe_name);
    $checkCommittee->execute();
    $checkCommittee->store_result();
    if ($checkCommittee->num_rows > 0 && !$id) {
        echo "<script>alert('Committee name already exists!');window.history.back();</script>";
        exit;
    }

    // If editing, remove old records for this committee
    if ($id) {
        $conn->query("DELETE FROM committee_tbl WHERE committe_name='$committe_name'");
    }

    // Track if Chairman / Member Secretary already exists
    $roleTracker = ['Chairman' => 0, 'Member Secretary' => 0];

    if (!empty($_POST['name'])) {
        foreach ($_POST['name'] as $index => $name) {
            $name = trim($name);
            if (empty($name)) continue;

            $mobile_no = trim($_POST['mobile_no'][$index] ?? '');
            $designation = trim($_POST['designation'][$index] ?? '');
            $office = trim($_POST['office_ministry'][$index] ?? '');
            $division = trim($_POST['division'][$index] ?? '');
            $type = trim($_POST['type'][$index] ?? 'Member');
            $status = 'Active'; // default status

            // ------------------- Enforce only one Chairman / Secretary -------------------
            if (in_array($type, ['Chairman', 'Member Secretary'])) {
                if ($roleTracker[$type] > 0) {
                    echo "<script>alert('Only one $type allowed per committee.');window.history.back();</script>";
                    exit;
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
                $username = strtolower(preg_replace('/\s+/', '', $name));
                $password = password_hash('123', PASSWORD_DEFAULT);
                $role = 'user';
                $email = '';
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                $checkUser = $conn->prepare("SELECT id FROM users WHERE username=? OR phone=?");
                $checkUser->bind_param("ss", $username, $mobile_no);
                $checkUser->execute();
                $checkUser->store_result();

                if ($checkUser->num_rows == 0) {
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
                }
                $checkUser->close();
            }
        }

        echo "<script>alert('Committee and members saved successfully!');window.location='committee_details.php';</script>";
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
<style>
body { background-color: #f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.add-row-btn { background: linear-gradient(45deg,#0d6efd,#0dcaf0); color:white; border:none; }
.remove-row-btn { background: #dc3545; color:white; border:none; }
</style>
</head>
<body>

<div class="container py-4">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add / Edit Committee</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                    <div class="col-md-4">
                        <label>Committee Name</label>
                        <input type="text" class="form-control" name="committe_name" id="committe_name" required>
                    </div>
                    <div class="col-md-4">
                        <label>Reference No</label>
                        <input type="text" class="form-control" name="ref_no" id="ref_no">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Attachment (PDF/Image)</label>
                        <input type="file" class="form-control" name="attachment">
                    </div>
                    <div class="col-md-6">
                        <label>Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks">
                    </div>
                </div>             

               
                <h5 class="mt-4 mb-3 text-primary">Examiner List</h5>

                <table class="table table-bordered" id="examinerTable">
                    <thead class="table-light">
                        <tr>
                            
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Designation</th>
                            <th>Office/Ministry</th>
                            <th>Division</th>
                            <th>Member Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <td><input type="text" name="name[]" class="form-control" required></td>
                            <td><input type="text" name="mobile_no[]" class="form-control"></td>
                            <td><input type="text" name="designation[]" class="form-control"></td>
                            <td><input type="text" name="office_ministry[]" class="form-control"></td>
                            <td><input type="text" name="division[]" class="form-control"></td>
                            <td>
                                <select name="type[]" class="form-select">
                                    <option value="Chairman">Chairman</option>
                                    <option value="Member Secretary">Member Secretary</option>
                                    <option value="Member">Member</option>
                                </select>
                            </td>
                            <td>
                                <select name="status[]" class="form-select">                                    
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </td>
                            <td><button type="button" class="btn remove-row-btn btn-sm">X</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="addRow" class="btn add-row-btn btn-sm mb-3">+ Add Examiner</button>

                <div class="text-end">
                    <button class="btn btn-outline-primary btn-md" id="clearAll" title="Clear all inputs">
            <i class="fas fa-eraser me-1"></i>Clear All
          </button>
                    <button type="submit" name="save" class="btn btn-success">Save Committee</button>
                    <a href="admin_dashboard.php" class="btn btn-primary">Back</a>

                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">All Committees</h4>
        </div>
        <div class="card-body">
            <table id="committeTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Committee Name</th>
                        <th>Ref No</th>
                       
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $committees = [];
                $res = $conn->query("SELECT * FROM committee_tbl ORDER BY id DESC");
                while ($row = $res->fetch_assoc()) {
                    $committees[$row['committe_name']][] = $row;
                }
                $i = 1;
                foreach ($committees as $name => $members) {
                    $main = $members[0];
                    echo "<tr>
                        <td>{$i}</td>
                        <td>{$main['date']}</td>
                        <td>{$main['committe_name']}</td>
                        <td>{$main['ref_no']}</td>
                        
                        <td>";
                        if ($main['attachment']) echo "<a href='{$main['attachment']}' target='_blank'>View</a>";
                    echo "</td>
                        <td class='text-center'>
                            <button class='btn btn-sm btn-info editBtn' data-members='" . htmlspecialchars(json_encode($members), ENT_QUOTES, 'UTF-8') . "' data-main='" . htmlspecialchars(json_encode($main), ENT_QUOTES, 'UTF-8') . "'>Edit</button>
                            <a href='?delete={$main['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this record?')\">Delete</a>
                        </td>
                    </tr>";
                    $i++;
                }
                ?>
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
    $('#committeTable').DataTable();

    // Add new examiner row dynamically
    $('#addRow').click(function() {
        const row = $('#examinerTable tbody tr:first').clone();
        row.find('input').val('');
        row.find('select').val('Member');
        $('#examinerTable tbody').append(row);
    });

    // Remove examiner row
    $(document).on('click', '.remove-row-btn', function() {
        $(this).closest('tr').remove();
    });

      // Quick Actions
  $('#clearAll').on('click', function() {
    if (confirm('Are you sure you want to clear all data?')) {
      $('#employeeForm input[type="number"]').val('').removeClass('has-data');
      calculateAllTotals();
      showAlert('All fields cleared successfully!', 'success');
    }
  });

    // Edit button logic
    $(document).on('click', '.editBtn', function() {
        const main = JSON.parse($(this).attr('data-main'));
        const members = JSON.parse($(this).attr('data-members'));

        $('#id').val(main.id);
        $('#date').val(main.date);
        $('#committe_name').val(main.committe_name);
        $('#ref_no').val(main.ref_no);
        $('#remarks').val(main.remarks);

        const tbody = $('#examinerTable tbody');
        tbody.empty();

        members.forEach(m => {
            const row = `<tr>                
                <td><input type="text" name="name[]" class="form-control" value="${m.name || ''}" required></td>
                <td><input type="text" name="mobile_no[]" class="form-control" value="${m.mobile_no || ''}"></td>
                <td><input type="text" name="designation[]" class="form-control" value="${m.designation || ''}"></td>
                <td><input type="text" name="office_ministry[]" class="form-control" value="${m.office_ministry || ''}"></td>
                <td><input type="text" name="division[]" class="form-control" value="${m.division || ''}"></td>
                <td>
                    <select name="type[]" class="form-select">
                        <option value="Chairman" ${m.type === 'Chairman' ? 'selected' : ''}>Chairman</option>
                        <option value="Member Secretary" ${m.type === 'Member Secretary' ? 'selected' : ''}>Member Secretary</option>
                        <option value="Member" ${m.type === 'Member' ? 'selected' : ''}>Member</option>
                    </select>
                </td>
                <td>
                    <select name="status[]" class="form-select">
                        <option value="Pending" ${m.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Active" ${m.status === 'Active' ? 'selected' : ''}>Active</option>
                        <option value="Inactive" ${m.status === 'Inactive' ? 'selected' : ''}>Inactive</option>
                    </select>
                </td>
                <td><button type="button" class="btn remove-row-btn btn-sm">X</button></td>
            </tr>`;
            tbody.append(row);
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>
</body>
</html>
