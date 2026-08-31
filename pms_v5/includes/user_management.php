
<?php
session_name('pms_db');
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../db/db.php';

$required_role = 'sadmin';
require_once __DIR__ . '/auth_guard.php';

$roles = include __DIR__ . '/roles_config.php';

$edit_mode = false;
$edit_user = null;

// --- DELETE ---
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $del_id = intval($_GET['delete']);

    if ($del_id === intval($_SESSION['uid'] ?? -1)) {
        $_SESSION['error'] = "You can't delete the account you're currently logged in as.";
    } else {
        // Fetch signature file to delete from server
        $stmt = mysqli_prepare($conn, "SELECT signature FROM user WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $del_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        if ($user && !empty($user['signature']) && file_exists(__DIR__ . '/../uploads/signatures/' . $user['signature'])) {
            unlink(__DIR__ . '/../uploads/signatures/' . $user['signature']);
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM user WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $del_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "User account deleted.";
        } else {
            $_SESSION['error'] = "Could not delete user: " . mysqli_error($conn);
        }
    }
    header("Location: user_management.php");
    exit();
}

// --- LOAD FOR EDIT ---
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, "SELECT id, emp_id, username, user_type, name, designation, office, signature FROM user WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $edit_user = mysqli_fetch_assoc($res);

    if ($edit_user) {
        $edit_mode = true;
    } else {
        $_SESSION['error'] = "User not found.";
        header("Location: user_management.php");
        exit();
    }
}

// --- CREATE / UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? '';
    $emp_id    = trim($_POST['emp_id'] ?? '');
    $name      = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $office    = trim($_POST['office'] ?? '');
    $post_id   = intval($_POST['user_id'] ?? 0);
    $existing_signature = $_POST['existing_signature'] ?? '';

    if ($username === '' || $user_type === '' || $emp_id === '' || $name === '') {
        $_SESSION['error'] = "Username, Employee ID, Name, and Role are required.";
        header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
        exit();
    }

    if (!isset($roles[$user_type])) {
        $_SESSION['error'] = "Unknown role selected.";
        header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
        exit();
    }

    // Username uniqueness check
    $check = mysqli_prepare($conn, "SELECT id FROM user WHERE username = ? AND id != ?");
    mysqli_stmt_bind_param($check, "si", $username, $post_id);
    mysqli_stmt_execute($check);
    $dupe = mysqli_stmt_get_result($check);
    if (mysqli_fetch_assoc($dupe)) {
        $_SESSION['error'] = "That username is already taken.";
        header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
        exit();
    }

    // Employee ID uniqueness check
    $check_emp = mysqli_prepare($conn, "SELECT id FROM user WHERE emp_id = ? AND id != ?");
    mysqli_stmt_bind_param($check_emp, "si", $emp_id, $post_id);
    mysqli_stmt_execute($check_emp);
    $dupe_emp = mysqli_stmt_get_result($check_emp);
    if (mysqli_fetch_assoc($dupe_emp)) {
        $_SESSION['error'] = "That Employee ID is already in use.";
        header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
        exit();
    }

    // --- Signature file upload handling ---
    $signature_filename = $existing_signature;
    $upload_dir = __DIR__ . '/../uploads/signatures/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['signature_file']['tmp_name'];
        $file_name = basename($_FILES['signature_file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        
        if (!in_array($file_ext, $allowed)) {
            $_SESSION['error'] = "Invalid signature file type. Allowed: JPG, PNG, GIF, SVG.";
            header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
            exit();
        }
        
        // Generate unique filename
        $new_filename = 'sig_' . time() . '_' . uniqid() . '.' . $file_ext;
        $destination = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file_tmp, $destination)) {
            // Delete old signature file if exists
            if (!empty($existing_signature) && file_exists($upload_dir . $existing_signature)) {
                unlink($upload_dir . $existing_signature);
            }
            $signature_filename = $new_filename;
        } else {
            $_SESSION['error'] = "Failed to upload signature image.";
            header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
            exit();
        }
    } elseif (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $_SESSION['error'] = "Error uploading signature: " . $_FILES['signature_file']['error'];
        header("Location: user_management.php" . ($post_id ? "?edit=$post_id" : ""));
        exit();
    }

    if ($post_id > 0) {
        // UPDATE existing user
        if ($password !== '') {
            $hashed = sha1($password);
            $stmt = mysqli_prepare($conn, "UPDATE user SET emp_id = ?, username = ?, password = ?, user_type = ?, name = ?, designation = ?, office = ?, signature = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssssssssi", $emp_id, $username, $hashed, $user_type, $name, $designation, $office, $signature_filename, $post_id);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE user SET emp_id = ?, username = ?, user_type = ?, name = ?, designation = ?, office = ?, signature = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssssssi", $emp_id, $username, $user_type, $name, $designation, $office, $signature_filename, $post_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "User account updated.";
        } else {
            $_SESSION['error'] = "Update failed: " . mysqli_error($conn);
        }
    } else {
        // CREATE new user
        if ($password === '') {
            $_SESSION['error'] = "Password is required for a new account.";
            header("Location: user_management.php");
            exit();
        }
        $hashed = sha1($password);
        $stmt = mysqli_prepare($conn, "INSERT INTO user (emp_id, username, password, user_type, name, designation, office, signature) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssssss", $emp_id, $username, $hashed, $user_type, $name, $designation, $office, $signature_filename);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "User account created.";
        } else {
            $_SESSION['error'] = "Could not create user: " . mysqli_error($conn);
        }
    }

    header("Location: user_management.php");
    exit();
}

// --- LIST ---
$users = [];
if ($res = mysqli_query($conn, "SELECT id, emp_id, username, user_type, name, designation, office, signature, created_at FROM user ORDER BY username")) {
    while ($row = mysqli_fetch_assoc($res)) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management - BCIC PMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>
  * { box-sizing: border-box; margin: 0; }
  body {
    background: #f4f8f6;
    font-family: 'Inter', sans-serif;
    color: #1a2d27;
  }
  .app-shell { display: flex; min-height: 100vh; }
  .main-content {
    flex: 1;
    padding: 32px 40px;
    background: #f4f8f6;
  }
  .topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    flex-wrap: wrap;
  }
  .topbar .eyebrow {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #5b756d;
  }
  .topbar h1 {
    font-family: 'Fraunces', serif;
    font-weight: 600;
    font-size: 32px;
    color: #0b3d38;
    margin-top: 2px;
  }
  .topbar .date-badge {
    background: white;
    padding: 8px 20px;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 500;
    color: #124f4a;
    border: 1px solid #d6e3df;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
  }
  .panel {
    background: #ffffff;
    border: 1px solid #e2ebe7;
    border-radius: 20px;
    padding: 28px 30px;
    margin-bottom: 32px;
    box-shadow: 0 6px 16px rgba(18, 79, 74, 0.04);
  }
  .panel h3 {
    font-family: 'Fraunces', serif;
    font-weight: 600;
    font-size: 20px;
    color: #124f4a;
    margin-bottom: 22px;
    letter-spacing: -0.2px;
  }
  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 18px 22px;
    align-items: end;
  }
  .form-grid .field {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }
  .form-grid label {
    font-size: 12.5px;
    font-weight: 600;
    color: #2b463e;
    letter-spacing: 0.02em;
  }
  .form-grid input, .form-grid select {
    padding: 11px 14px;
    border-radius: 12px;
    border: 1.5px solid #dde8e3;
    background: #fbfdfc;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #1a2d27;
    transition: 0.15s;
    outline: none;
    width: 100%;
  }
  .form-grid input:focus, .form-grid select:focus {
    border-color: #1d6d66;
    box-shadow: 0 0 0 4px rgba(18, 79, 74, 0.08);
    background: #ffffff;
  }
  .form-grid input[type="file"] {
    padding: 8px 10px;
    background: white;
    border: 1.5px dashed #d0dfda;
    cursor: pointer;
  }
  .form-grid input[type="file"]:hover {
    border-color: #1d6d66;
  }
  .form-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
    flex-wrap: wrap;
  }
  .btn-primary {
    background: #e8573a;
    color: #fff;
    border: none;
    padding: 12px 28px;
    border-radius: 40px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: 0.15s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 8px rgba(232, 88, 58, 0.15);
  }
  .btn-primary:hover {
    background: #cf4a30;
    transform: scale(1.01);
    box-shadow: 0 6px 12px rgba(232, 88, 58, 0.2);
  }
  .btn-secondary {
    background: transparent;
    color: #1e3d35;
    border: 1.5px solid #d0dfda;
    padding: 12px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: 0.1s;
  }
  .btn-secondary:hover {
    border-color: #1d6d66;
    background: #f3f9f6;
  }
  .alert {
    padding: 14px 20px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 24px;
    border-left: 6px solid transparent;
  }
  .alert-success {
    background: #e2f0ec;
    color: #0b4a42;
    border-left-color: #1d8a7a;
  }
  .alert-error {
    background: #fcedea;
    color: #a63f2a;
    border-left-color: #e8573a;
  }
  .table-wrapper { overflow-x: auto; margin-top: 4px; }
  .user-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }
  .user-table th {
    text-align: left;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #5d7a70;
    font-weight: 700;
    padding: 12px 12px 10px 12px;
    border-bottom: 2px solid #e2ebe7;
  }
  .user-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #eaf1ed;
    color: #1d332c;
    vertical-align: middle;
  }
  .user-table tr:last-child td { border-bottom: none; }
  .user-table tr:hover td { background: #f6fbf9; }
  .role-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 40px;
    font-size: 12px;
    font-weight: 600;
    background: #e2f0ec;
    color: #0d5e54;
  }
  .row-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }
  .row-actions a {
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    padding: 5px 14px;
    border-radius: 30px;
    transition: 0.1s;
  }
  .edit-link {
    background: #e2f0ec;
    color: #0d5e54;
  }
  .edit-link:hover {
    background: #124f4a;
    color: white;
  }
  .delete-link {
    background: #fcedea;
    color: #c44a31;
  }
  .delete-link:hover {
    background: #e8573a;
    color: white;
  }
  .empty-row td { padding: 40px 12px; text-align: center; color: #6a8a7e; font-weight: 500; }
  .signature-preview {
    max-width: 80px;
    max-height: 40px;
    display: inline-block;
  }
  .signature-preview img {
    max-width: 100%;
    max-height: 40px;
    border-radius: 4px;
    border: 1px solid #e2ebe7;
    padding: 2px;
    background: white;
  }
  .signature-text {
    font-size: 12px;
    color: #5d7a70;
    background: #f0f5f2;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
    word-break: break-all;
    max-width: 100px;
  }
  .current-sig {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 4px;
  }
  .current-sig img {
    max-height: 40px;
    border-radius: 4px;
    border: 1px solid #dde8e3;
    padding: 3px;
    background: white;
  }
  .current-sig .label {
    font-size: 12px;
    color: #5d7a70;
    font-weight: 400;
  }
  .created-at { font-size: 12px; color: #5d7a70; white-space: nowrap; }
  @media (max-width: 700px) {
    .main-content { padding: 20px 16px; }
    .panel { padding: 20px; }
    .form-grid { grid-template-columns: 1fr; }
    .topbar h1 { font-size: 26px; }
  }
</style>
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <div class="topbar">
            <div>
                <div class="eyebrow">System Admin Panel</div>
                <h1>User Management</h1>
            </div>
            <div class="date-badge"><?= date('l, F j, Y') ?></div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="panel">
            <h3><?= $edit_mode ? '✎ Edit User Account' : '✚ Create New User Account' ?></h3>
            <form method="POST" action="user_management.php" enctype="multipart/form-data">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="user_id" value="<?= (int)$edit_user['id'] ?>">
                    <input type="hidden" name="existing_signature" value="<?= htmlspecialchars($edit_user['signature'] ?? '') ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="field">
                        <label for="emp_id">Employee ID <span style="color:#c44a31;">*</span></label>
                        <input type="text" id="emp_id" name="emp_id" required
                               value="<?= htmlspecialchars($edit_user['emp_id'] ?? '') ?>"
                               placeholder="e.g. EMP-001">
                    </div>
                    <div class="field">
                        <label for="username">Username <span style="color:#c44a31;">*</span></label>
                        <input type="text" id="username" name="username" required
                               value="<?= htmlspecialchars($edit_user['username'] ?? '') ?>"
                               placeholder="login name">
                    </div>
                    <div class="field">
                        <label for="password">
                            Password <?= $edit_mode ? '<span style="font-weight:400;color:#5d7a70;">(leave blank to keep)</span>' : '<span style="color:#c44a31;">*</span>' ?>
                        </label>
                        <input type="password" id="password" name="password" autocomplete="new-password"
                               <?= $edit_mode ? '' : 'required' ?>
                               placeholder="<?= $edit_mode ? '••••••••' : 'strong password' ?>">
                    </div>
                    <div class="field">
                        <label for="user_type">Role <span style="color:#c44a31;">*</span></label>
                        <select id="user_type" name="user_type" required>
                            <option value="">— Select role —</option>
                            <?php foreach ($roles as $role_key => $role_data): ?>
                                <option value="<?= htmlspecialchars($role_key) ?>"
                                    <?= (($edit_user['user_type'] ?? '') === $role_key) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role_data['label'] ?? ucfirst($role_key)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="name">Full Name <span style="color:#c44a31;">*</span></label>
                        <input type="text" id="name" name="name" required
                               value="<?= htmlspecialchars($edit_user['name'] ?? '') ?>"
                               placeholder="John Doe">
                    </div>
                    <div class="field">
                        <label for="designation">Designation</label>
                        <input type="text" id="designation" name="designation"
                               value="<?= htmlspecialchars($edit_user['designation'] ?? '') ?>"
                               placeholder="e.g. Manager">
                    </div>
                    <div class="field">
                        <label for="office">Office</label>
                        <input type="text" id="office" name="office"
                               value="<?= htmlspecialchars($edit_user['office'] ?? '') ?>"
                               placeholder="e.g. Dhaka">
                    </div>
                    <div class="field">
                        <label for="signature_file">Signature Image</label>
                        <input type="file" id="signature_file" name="signature_file" accept="image/*">
                        <?php if ($edit_mode && !empty($edit_user['signature'])): ?>
                            <div class="current-sig">
                                <span class="label">Current:</span>
                                <img src="../uploads/signatures/<?= htmlspecialchars($edit_user['signature']) ?>" alt="Signature">
                                <span style="font-size:11px;color:#5d7a70;">(upload new to replace)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <?= $edit_mode ? 'Update Account' : 'Create Account' ?>
                    </button>
                    <?php if ($edit_mode): ?>
                        <a href="user_management.php" class="btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel">
            <h3>All User Accounts <span style="font-weight:400;font-size:15px;color:#5d7a70;font-family:'Inter';">(<?= count($users) ?>)</span></h3>
            <div class="table-wrapper">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Emp ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Designation</th>
                            <th>Office</th>
                            <th>Signature</th>
                            <th>Created</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr class="empty-row"><td colspan="9">No user accounts found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($u['emp_id']) ?></strong></td>
                                    <td><?= htmlspecialchars($u['username']) ?></td>
                                    <td><?= htmlspecialchars($u['name']) ?></td>
                                    <td><span class="role-badge"><?= htmlspecialchars($roles[$u['user_type']]['label'] ?? $u['user_type']) ?></span></td>
                                    <td><?= htmlspecialchars($u['designation'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($u['office'] ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($u['signature']) && file_exists(__DIR__ . '/../uploads/signatures/' . $u['signature'])): ?>
                                            <div class="signature-preview">
                                                <img src="../uploads/signatures/<?= htmlspecialchars($u['signature']) ?>" alt="Signature">
                                            </div>
                                        <?php elseif (!empty($u['signature'])): ?>
                                            <span class="signature-text"><?= htmlspecialchars(substr($u['signature'], 0, 15)) ?>…</span>
                                        <?php else: ?>
                                            <span style="color:#a4b9b2;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="created-at"><?= date('d M Y', strtotime($u['created_at'] ?? 'now')) ?></td>
                                    <td class="row-actions">
                                        <a href="user_management.php?edit=<?= (int)$u['id'] ?>" class="edit-link">Edit</a>
                                        <a href="user_management.php?delete=<?= (int)$u['id'] ?>" class="delete-link"
                                           onclick="return confirm('Delete this user account? This cannot be undone.');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;font-size:13px;color:#5b756d;border-top:1px solid #e2ebe7;padding-top:16px;">
                <span>🔒 Passwords are hashed with SHA‑1. Signature images are stored in <code>uploads/signatures/</code></span>
            </div>
        </div>
    </main>
</div>
</body>
</html>
```