<?php
session_name('pms_db');
session_start();

$required_role = 'doctor';
require_once __DIR__ . '/auth_guard.php';

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$status_options = ['Active', 'Inactive'];
$blood_groups   = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
$emp_types = ['Staff', 'Officer'];

// --- DELETE ---
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['success'] = "Employee record deleted.";
    header("Location: employee_management.php");
    exit();
}

// --- CREATE / UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = intval($_POST['id'] ?? 0);
    $employee_type  = trim($_POST['employee_type'] ?? '');
    $emp_id         = trim($_POST['emp_id'] ?? '');
    $name           = trim($_POST['name'] ?? '');
    $designation    = trim($_POST['designation'] ?? '');
    $division       = trim($_POST['division'] ?? '');
    $section        = trim($_POST['section'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $emp_username   = trim($_POST['username'] ?? '');
    $emp_password   = $_POST['password'] ?? '';
    $role           = trim($_POST['role'] ?? '');
    $dob            = $_POST['dob'] ?? null;
    $mobile_no      = trim($_POST['mobile_no'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $blood_group    = $_POST['blood_group'] ?? '';
    $status         = $_POST['status'] ?? 'Active';

    if ($emp_id === '' || $name === '') {
        $_SESSION['error'] = "Employee ID and Name are required.";
        header("Location: employee_management.php");
        exit();
    }

    // Uniqueness check: emp_id (and username, if provided) must not collide with another record
    $dupe_stmt = $conn->prepare("SELECT id FROM employees WHERE (emp_id = :emp_id OR (username <> '' AND username = :username)) AND id <> :id");
    $dupe_stmt->execute([':emp_id' => $emp_id, ':username' => $emp_username, ':id' => $id]);
    if ($dupe_stmt->fetch()) {
        $_SESSION['error'] = "Another employee already uses this Emp ID or Username.";
        header("Location: employee_management.php");
        exit();
    }

    if ($id > 0) {
        // UPDATE
        if ($emp_password !== '') {
            $hashed = sha1($emp_password);
            $stmt = $conn->prepare("UPDATE employees SET
                employee_type = :employee_type, emp_id = :emp_id, name = :name, designation = :designation,
                division = :division, section = :section, address = :address, username = :username,
                password = :password, role = :role, dob = :dob, mobile_no = :mobile_no, email = :email,
                blood_group = :blood_group, status = :status, updated_at = NOW()
                WHERE id = :id");
            $stmt->execute([
                ':employee_type' => $employee_type, ':emp_id' => $emp_id, ':name' => $name, ':designation' => $designation,
                ':division' => $division, ':section' => $section, ':address' => $address, ':username' => $emp_username,
                ':password' => $hashed, ':role' => $role, ':dob' => $dob ?: null, ':mobile_no' => $mobile_no,
                ':email' => $email, ':blood_group' => $blood_group, ':status' => $status, ':id' => $id
            ]);
        } else {
            $stmt = $conn->prepare("UPDATE employees SET
                employee_type = :employee_type, emp_id = :emp_id, name = :name, designation = :designation,
                division = :division, section = :section, address = :address, username = :username,
                role = :role, dob = :dob, mobile_no = :mobile_no, email = :email,
                blood_group = :blood_group, status = :status, updated_at = NOW()
                WHERE id = :id");
            $stmt->execute([
                ':employee_type' => $employee_type, ':emp_id' => $emp_id, ':name' => $name, ':designation' => $designation,
                ':division' => $division, ':section' => $section, ':address' => $address, ':username' => $emp_username,
                ':role' => $role, ':dob' => $dob ?: null, ':mobile_no' => $mobile_no,
                ':email' => $email, ':blood_group' => $blood_group, ':status' => $status, ':id' => $id
            ]);
        }
        $_SESSION['success'] = "Employee record updated.";
    } else {
        // CREATE
        $hashed = $emp_password !== '' ? sha1($emp_password) : '';
        $stmt = $conn->prepare("INSERT INTO employees
            (employee_type, emp_id, name, designation, division, section, address, username, password, role, dob, mobile_no, email, blood_group, status, created_at, updated_at)
            VALUES
            (:employee_type, :emp_id, :name, :designation, :division, :section, :address, :username, :password, :role, :dob, :mobile_no, :email, :blood_group, :status, NOW(), NOW())");
        $stmt->execute([
            ':employee_type' => $employee_type, ':emp_id' => $emp_id, ':name' => $name, ':designation' => $designation,
            ':division' => $division, ':section' => $section, ':address' => $address, ':username' => $emp_username,
            ':password' => $hashed, ':role' => $role, ':dob' => $dob ?: null, ':mobile_no' => $mobile_no,
            ':email' => $email, ':blood_group' => $blood_group, ':status' => $status
        ]);
        $_SESSION['success'] = "Employee record created.";
    }

    header("Location: employee_management.php");
    exit();
}

// --- LIST ---
$employees = $conn->query("SELECT * FROM employees ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Management - BCIC PMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>
    .table-card{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:16px;
        padding:22px;
        box-shadow:0 1px 3px rgba(11,61,58,0.06);
    }
    table.dataTable thead th{
        background:var(--teal-deep) !important;
        color:var(--paper) !important;
        border-color:var(--teal-deep) !important;
        font-size:12px;
        letter-spacing:0.03em;
        text-transform:uppercase;
        font-weight:600;
        white-space:nowrap;
    }
    table.dataTable tbody tr:nth-child(even){ background:rgba(18,79,74,0.035); }
    table.dataTable tbody td{ vertical-align:middle; font-size:13.5px; border-color:var(--border) !important; }

    .status-pill{
        display:inline-block;
        padding:3px 11px;
        border-radius:20px;
        font-size:11.5px;
        font-weight:600;
    }
    .status-active{ background:rgba(18,79,74,0.10); color:var(--teal-mid); }
    .status-inactive{ background:rgba(232,88,58,0.10); color:var(--coral-dim); }

    .row-actions a{
        font-size:12px;
        font-weight:600;
        text-decoration:none;
        padding:5px 10px;
        border-radius:7px;
        white-space:nowrap;
    }
    .edit-link{ background:rgba(18,79,74,0.10); color:var(--teal-mid); }
    .edit-link:hover{ background:var(--teal-mid); color:#fff; }
    .delete-link{ background:rgba(232,88,58,0.10); color:var(--coral-dim); margin-left:4px; }
    .delete-link:hover{ background:var(--coral); color:#fff; }

    .modal-content{ border-radius:16px; border:none; }
    .modal-header{ background:var(--teal-deep); color:var(--paper); border-bottom:none; }
    .modal-header .btn-close{ filter:invert(1) brightness(2); }
    .modal-title{ font-family:'Fraunces',serif; font-weight:600; }
    .modal-footer{ border-top:1px solid var(--border); }

    .form-label{ font-size:12.5px; font-weight:600; color:var(--ink); }
    .form-control, .form-select{
        border-radius:9px;
        border:1.5px solid var(--border);
        font-size:13.5px;
    }
    .form-control:focus, .form-select:focus{
        border-color:var(--teal-mid);
        box-shadow:0 0 0 3px rgba(18,79,74,0.10);
    }

    .alert{ border-radius:10px; font-size:13.5px; }
</style>
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <div class="eyebrow">System Admin Panel</div>
                <h1>Employee Management</h1>
            </div>
            <button type="button" class="btn btn-primary" style="background:var(--coral); border-color:var(--coral); font-weight:700; border-radius:9px;"
                    data-bs-toggle="modal" data-bs-target="#employeeModal" onclick="resetForm()">
                <i class="fas fa-plus me-1"></i> Add Employee
            </button>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="table-card">
            <table id="empTable" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Emp Type</th>
                        <th>Emp ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Division</th>
                        <th>Section</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Blood Grp</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?= (int)$emp['id'] ?></td>
                        <td><?= htmlspecialchars($emp['employee_type']) ?></td>
                        <td><?= htmlspecialchars($emp['emp_id']) ?></td>
                        <td><?= htmlspecialchars($emp['name']) ?></td>
                        <td><?= htmlspecialchars($emp['designation']) ?></td>
                        <td><?= htmlspecialchars($emp['division']) ?></td>
                        <td><?= htmlspecialchars($emp['section']) ?></td>
                        <td><?= htmlspecialchars($emp['mobile_no']) ?></td>
                        <td><?= htmlspecialchars($emp['email']) ?></td>
                        <td><?= htmlspecialchars($emp['blood_group']) ?></td>
                        <td>
                            <span class="status-pill <?= strtolower($emp['status']) === 'active' ? 'status-active' : 'status-inactive' ?>">
                                <?= htmlspecialchars($emp['status']) ?>
                            </span>
                        </td>
                        <td><?= !empty($emp['created_at']) ? date('M d, Y', strtotime($emp['created_at'])) : '' ?></td>
                        <td class="row-actions">
                            <a href="javascript:void(0)" class="edit-link edit-btn"
                               data-emp='<?= htmlspecialchars(json_encode($emp), ENT_QUOTES) ?>'>
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="employee_management.php?delete=<?= (int)$emp['id'] ?>" class="delete-link"
                               onclick="return confirm('Delete this employee record? This cannot be undone.');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add/Edit Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <form method="POST" action="employee_management.php" id="employeeForm">
        <div class="modal-header">
          <h5 class="modal-title" id="employeeModalLabel">Add Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="f_id" value="">

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Employee Type</label>
              <!-- <input type="text" class="form-control" name="employee_type" id="f_employee_type" placeholder="e.g. Staff, Officer"> -->
               <select class="form-select" name="employee_type" id="f_employee_type">
                <option value="">-- Select --</option>
                <?php foreach ($emp_types as $bg): ?>
                  <option value="<?= $bg ?>"><?= $bg ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Emp ID <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="emp_id" id="f_emp_id" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" id="f_name" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">Designation</label>
              <input type="text" class="form-control" name="designation" id="f_designation">
            </div>
            <div class="col-md-4">
              <label class="form-label">Division</label>
              <input type="text" class="form-control" name="division" id="f_division">
            </div>
            <div class="col-md-4">
              <label class="form-label">Section</label>
              <input type="text" class="form-control" name="section" id="f_section">
            </div>

            <div class="col-md-12">
              <label class="form-label">Address</label>
              <input type="text" class="form-control" name="address" id="f_address">
            </div>

            <div class="col-md-3">
              <label class="form-label">DOB</label>
              <input type="date" class="form-control" name="dob" id="f_dob">
            </div>
            <div class="col-md-3">
              <label class="form-label">Mobile No</label>
              <input type="text" class="form-control" name="mobile_no" id="f_mobile_no">
            </div>
            <div class="col-md-3">
              <label class="form-label">Blood Group</label>
              <select class="form-select" name="blood_group" id="f_blood_group">
                <option value="">-- Select --</option>
                <?php foreach ($blood_groups as $bg): ?>
                  <option value="<?= $bg ?>"><?= $bg ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status</label>
              <select class="form-select" name="status" id="f_status">
                <?php foreach ($status_options as $st): ?>
                  <option value="<?= $st ?>"><?= $st ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="f_email">
            </div>
            <div class="col-md-6">
              <label class="form-label">Role</label>
              <input type="text" class="form-control" name="role" id="f_role" placeholder="e.g. Doctor, Pharmacist, Staff">
            </div>

            <div class="col-md-6">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" name="username" id="f_username" autocomplete="off">
            </div>
            <div class="col-md-6">
              <label class="form-label" id="f_password_label">Password</label>
              <input type="password" class="form-control" name="password" id="f_password" autocomplete="new-password">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" style="background:var(--coral); border-color:var(--coral); font-weight:700;">
            <i class="fas fa-save me-1"></i> Save Employee
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#empTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: { search: "_INPUT_", searchPlaceholder: "Search employees..." },
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length select').addClass('form-select form-select-sm');
        }
    });

    // Populate modal when Edit is clicked
    $(document).on('click', '.edit-btn', function() {
        const emp = $(this).data('emp');

        $('#employeeModalLabel').text('Edit Employee: ' + emp.name);
        $('#f_id').val(emp.id);
        $('#f_employee_type').val(emp.employee_type);
        $('#f_emp_id').val(emp.emp_id);
        $('#f_name').val(emp.name);
        $('#f_designation').val(emp.designation);
        $('#f_division').val(emp.division);
        $('#f_section').val(emp.section);
        $('#f_address').val(emp.address);
        $('#f_dob').val(emp.dob ? emp.dob.substring(0,10) : '');
        $('#f_mobile_no').val(emp.mobile_no);
        $('#f_blood_group').val(emp.blood_group);
        $('#f_status').val(emp.status);
        $('#f_email').val(emp.email);
        $('#f_role').val(emp.role);
        $('#f_username').val(emp.username);
        $('#f_password').val('').attr('placeholder', 'Leave blank to keep current password');
        $('#f_password_label').text('Password (leave blank to keep current)');

        var modal = new bootstrap.Modal(document.getElementById('employeeModal'));
        modal.show();
    });
});

// Reset form when "Add Employee" is clicked
function resetForm() {
    document.getElementById('employeeForm').reset();
    document.getElementById('f_id').value = '';
    document.getElementById('employeeModalLabel').innerText = 'Add Employee';
    document.getElementById('f_password').setAttribute('placeholder', '');
    document.getElementById('f_password_label').innerText = 'Password';
}
</script>
</body>
</html>
