<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'sadmin') {
    header("Location: ../index.php");
    exit;
}

/* ================= GET USER ================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: manage_users.php");
    exit;
}

/* ================= OLD IDENTITY ================= */
$old_email  = $user['email_id'];
$old_emp_id = $user['emp_id'];
$old_name   = $user['name'];
$old_mobile = $user['mobile_no'];

$success = '';
$error   = '';

/* ================= UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emp_id           = $_POST['emp_id'];
    $name             = $_POST['name'];
    $designation      = $_POST['designation'];
    $division         = $_POST['division'];
    $section          = $_POST['section'];
    $place_of_posting = $_POST['place_of_posting'];
    $office           = $_POST['office'];
    $mobile_no        = $_POST['mobile_no'];
    $email_id         = $_POST['email_id'];
    $role             = $_POST['role'];
    $status           = $_POST['status'];
    $batch            = $_POST['batch'];
    $password         = trim($_POST['password']);

    /* ========== CHECK IDENTITY CHANGE ========== */
    $identity_changed = (
        $email_id  !== $old_email ||
        $emp_id    !== $old_emp_id ||
        $name      !== $old_name ||
        $mobile_no !== $old_mobile
    );

    /* ========== FETCH RELATED RECORDS ========== */
    $related_records = [];

    if ($identity_changed) {
        $stmt = $conn->prepare("
            SELECT id, emp_id, name, mobile_no, email_id
            FROM users_tbl
            WHERE email_id = ?
               OR emp_id = ?
               OR mobile_no = ?
               OR name = ?
        ");
        $stmt->bind_param("ssss", $old_email, $old_emp_id, $old_mobile, $old_name);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $related_records[] = $row;
        }
        $stmt->close();
    }

    /* ========== POPUP + MASS UPDATE ========== */
if ($identity_changed && count($related_records) > 1) {

    // Prepare the text-based table
    $popup = "The following records will be updated:\\n\\n";

    foreach ($related_records as $r) {
        $popup .= "ID: {$r['id']}\tEMP: {$r['emp_id']}\tName: {$r['name']}\tMobile: {$r['mobile_no']}\tEmail: {$r['email_id']}\\n";
    }

    // Escape for JavaScript
    $js_popup = addslashes($popup);

    // Show alert with table and ask confirmation
    echo "<script>
        var proceed = confirm('{$js_popup}');
        if (!proceed) {
            window.location.href='manage_users.php';
        }
    </script>";

    // Mass update if confirmed
    $stmt = $conn->prepare("
        UPDATE users_tbl
        SET emp_id = ?, name = ?, mobile_no = ?, email_id = ?
        WHERE email_id = ? OR emp_id = ? OR mobile_no = ? OR name = ?
    ");
    $stmt->bind_param(
        "ssssssss",
        $emp_id, $name, $mobile_no, $email_id,
        $old_email, $old_emp_id, $old_mobile, $old_name
    );
    $stmt->execute();
    $stmt->close();
}

    /* ========== UPDATE CURRENT RECORD ========== */
    if ($password !== '') {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users_tbl SET
                emp_id=?, name=?, designation=?, division=?, section=?,
                place_of_posting=?, office=?, mobile_no=?, email_id=?,
                role=?, status=?, batch=?, password=?, updated_at=NOW()
            WHERE id=?
        ");
        $stmt->bind_param(
            "sssssssssssssi",
            $emp_id, $name, $designation, $division, $section,
            $place_of_posting, $office, $mobile_no, $email_id,
            $role, $status, $batch, $hashed_password, $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE users_tbl SET
                emp_id=?, name=?, designation=?, division=?, section=?,
                place_of_posting=?, office=?, mobile_no=?, email_id=?,
                role=?, status=?, batch=?, updated_at=NOW()
            WHERE id=?
        ");
        $stmt->bind_param(
            "ssssssssssssi",
            $emp_id, $name, $designation, $division, $section,
            $place_of_posting, $office, $mobile_no, $email_id,
            $role, $status, $batch, $id
        );
    }

    /* ========== EXECUTE + RELOAD USER ========== */
    if ($stmt->execute()) {

        $success = "User updated successfully!";

        /* 🔁 RELOAD USER DATA */
        $reload = $conn->prepare("SELECT * FROM users_tbl WHERE id = ?");
        $reload->bind_param("i", $id);
        $reload->execute();
        $user = $reload->get_result()->fetch_assoc();
        $reload->close();

    } else {
        $error = "Failed to update user.";
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User - Admin Panel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-light: #d1fae5;
            --primary-dark: #059669;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --text-color: #374151;
            --light-bg: #f9fafb;
            --card-bg: #ffffff;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .header-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
        }
        
        .form-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .form-card h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }
        
        .btn-outline-secondary {
            border-radius: 10px;
            padding: 11px 25px;
            border-width: 2px;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            font-weight: bold;
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .info-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin: 0 5px;
        }
        
        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-admin {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        
        .badge-user {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .alert-success-custom {
            background-color: #d1fae5;
            border-color: #a7f3d0;
            color: #065f46;
            border-radius: 10px;
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger-custom {
            background-color: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
            border-radius: 10px;
            border-left: 4px solid var(--danger-color);
        }
        
        .password-field {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
        }
        
        .form-label {
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 8px;
        }
        
        .section-divider {
            border-bottom: 2px dashed #e5e7eb;
            margin: 30px 0;
        }
        
        .user-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .info-card {
            background: #f9fafb;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #e5e7eb;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            color: #1f2937;
        }
        
        @media (max-width: 768px) {
            .header-card {
                padding: 20px;
            }
            
            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <!-- Header Card -->
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="manage_users.php" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
            </a>
            <div>
                <span class="info-badge <?php echo $user['role'] == 'sadmin' ? 'badge-admin' : 'badge-user'; ?>">
                    <i class="bi bi-person-badge me-1"></i> <?php echo ucfirst($user['role']); ?>
                </span>
                <span class="info-badge <?php echo $user['status'] == 'active' ? 'badge-active' : 'badge-inactive'; ?>">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> <?php echo ucfirst($user['status']); ?>
                </span>
            </div>
        </div>
        
        <div class="text-center">
            <div class="user-avatar">
                <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
            </div>
            <h3 class="mb-2">Edit User Profile</h3>
            <p class="mb-0">Employee ID: <strong><?php echo htmlspecialchars($user['emp_id']); ?></strong></p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success-custom alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger-custom alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- User Quick Info -->
    <div class="user-info-grid mb-4">
        <div class="info-card">
            <div class="info-label">Designation</div>
            <div class="info-value"><?php echo htmlspecialchars($user['designation'] ?: 'Not set'); ?></div>
        </div>
        <div class="info-card">
            <div class="info-label">Division</div>
            <div class="info-value"><?php echo htmlspecialchars($user['division'] ?: 'Not set'); ?></div>
        </div>
        <div class="info-card">
            <div class="info-label">Batch</div>
            <div class="info-value"><?php echo htmlspecialchars($user['batch'] ?: 'Not set'); ?></div>
        </div>
        <div class="info-card">
            <div class="info-label">Last Updated</div>
            <div class="info-value"><?php echo date('d M Y, h:i A', strtotime($user['updated_at'])); ?></div>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="post" action="" id="editUserForm">
        <!-- Personal Information -->
        <div class="form-card">
            <h5><i class="bi bi-person me-2"></i> Personal Information</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="emp_id" class="form-label">Employee ID *</label>
                    <input type="text" class="form-control" id="emp_id" name="emp_id" 
                           value="<?php echo htmlspecialchars($user['emp_id']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?php echo htmlspecialchars($user['name']); ?>" required
                           oninput="this.value = this.value.toUpperCase()">
                </div>
                
                <div class="col-md-6">
                    <label for="email_id" class="form-label">Email Address *</label>
                    <input type="email" class="form-control" id="email_id" name="email_id" 
                           value="<?php echo htmlspecialchars($user['email_id']); ?>" required>
                    <div class="form-text">Make sure this email is not used by another user</div>
                </div>
                <div class="col-md-6">
                    <label for="mobile_no" class="form-label">Mobile Number</label>
                    <input type="tel" class="form-control" id="mobile_no" name="mobile_no" 
                           value="<?php echo htmlspecialchars($user['mobile_no']); ?>">
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="form-card">
            <h5><i class="bi bi-briefcase me-2"></i> Professional Information</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="designation" class="form-label">Designation</label>
                    <input type="text" class="form-control" id="designation" name="designation" 
                           value="<?php echo htmlspecialchars($user['designation']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="division" class="form-label">Division</label>
                    <input type="text" class="form-control" id="division" name="division" 
                           value="<?php echo htmlspecialchars($user['division']); ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="section" class="form-label">Section</label>
                    <input type="text" class="form-control" id="section" name="section" 
                           value="<?php echo htmlspecialchars($user['section']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="place_of_posting" class="form-label">Place of Posting</label>
                    <input type="text" class="form-control" id="place_of_posting" name="place_of_posting" 
                           value="<?php echo htmlspecialchars($user['place_of_posting']); ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="office" class="form-label">Office</label>
                    <input type="text" class="form-control" id="office" name="office" 
                           value="<?php echo htmlspecialchars($user['office']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="batch" class="form-label">Batch</label>
                    <select name="batch" class="form-select" id="batch">
                        <option value="">Select Batch</option>
                        <option value="1" <?php echo $user['batch'] == '1' ? 'selected' : ''; ?>>1st Batch</option>
                        <option value="2" <?php echo $user['batch'] == '2' ? 'selected' : ''; ?>>2nd Batch</option>
                        <option value="3" <?php echo $user['batch'] == '3' ? 'selected' : ''; ?>>3rd Batch</option>
                        <option value="4" <?php echo $user['batch'] == '4' ? 'selected' : ''; ?>>4th Batch</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="form-card">
            <h5><i class="bi bi-gear me-2"></i> Account Settings</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="role" class="form-label">User Role *</label>
                    <select name="role" class="form-select" id="role" required>
                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Account Status *</label>
                    <select name="status" class="form-select" id="status" required>
                        <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="pending" <?php echo $user['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <div class="password-field">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Leave blank to keep current password">
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="form-text mt-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i> Enter new password to update
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="password-field">
                        <input type="password" class="form-control" id="confirm_password" 
                               placeholder="Confirm new password">
                        <button type="button" class="toggle-password" data-target="confirm_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-2"></i> Update User
            </button>
            <a href="manage_users.php" class="btn btn-outline-secondary px-4">
                <i class="bi bi-x-circle me-2"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    });

    // Password confirmation check
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    function checkPasswordMatch() {
        if (passwordInput.value && confirmPasswordInput.value) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('is-invalid');
                return false;
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                return true;
            }
        }
        return true;
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // Form validation
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        if (!checkPasswordMatch()) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
    });

    // Auto-capitalize name
    document.getElementById('name').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });

    // Show success message animation
    <?php if ($success): ?>
        setTimeout(() => {
            const alert = document.querySelector('.alert-success-custom');
            if (alert) {
                alert.style.transform = 'translateY(-5px)';
                alert.style.boxShadow = '0 5px 15px rgba(16, 185, 129, 0.3)';
                setTimeout(() => {
                    alert.style.transform = 'translateY(0)';
                    alert.style.boxShadow = 'none';
                }, 300);
            }
        }, 100);
    <?php endif; ?>
</script>

</body>
</html>