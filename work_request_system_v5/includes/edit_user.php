<?php
// edit_user.php
session_name('factory_work_request_db');
session_start();

// Check if user is logged in and is admin/sadmin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Check if user has admin privileges
$user_role = $_SESSION['role'] ?? 'user';
if ($user_role !== 'admin' && $user_role !== 'sadmin') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: user_management.php");
    exit;
}

$user_id = intval($_GET['id']);

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: user_management.php");
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $emp_id = trim($_POST['emp_id'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $division = trim($_POST['division'] ?? '');
    $section = trim($_POST['section'] ?? '');
    $status = $_POST['status'] ?? 'active';
    $role = $_POST['role'] ?? 'user';
    $routine_role = $_POST['routine_role'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($emp_id)) {
        $errors[] = "Employee ID is required";
    } else {
        // Check if emp_id already exists (excluding current user)
        $check_sql = "SELECT id FROM users WHERE emp_id = ? AND id != ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $emp_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Employee ID already exists";
        }
        $check_stmt->close();
    }
    
    if (empty($designation)) {
        $errors[] = "Designation is required";
    }
    
    if (empty($division)) {
        $errors[] = "Division is required";
    }
    
    if (empty($section)) {
        $errors[] = "Section is required";
    }
    
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Update user
        $update_sql = "UPDATE users SET 
                      emp_id = ?, 
                      full_name = ?, 
                      designation = ?, 
                      division = ?, 
                      section = ?, 
                      status = ?, 
                      role = ?, 
                      routine_role = ?,
                      updated_at = NOW()
                      WHERE id = ?";
        
        $update_stmt = $conn->prepare($update_sql);
        $routine_role = empty($routine_role) ? NULL : $routine_role;
        $update_stmt->bind_param(
            "ssssssssi", 
            $emp_id, $full_name, $designation, $division, 
            $section, $status, $role, $routine_role, $user_id
        );
        
        if ($update_stmt->execute()) {
            $message = "User updated successfully!";
            
            // Log the update
            $log_sql = "INSERT INTO user_edit_logs (admin_id, user_id, changes, edited_at) 
                       VALUES (?, ?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $changes = json_encode([
                'admin' => $_SESSION['emp_id'],
                'user' => $emp_id,
                'changes' => $_POST
            ]);
            $log_stmt->bind_param("iis", $_SESSION['user_id'], $user_id, $changes);
            $log_stmt->execute();
            $log_stmt->close();
            
            // Refresh user data
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
        } else {
            $error = "Failed to update user: " . $conn->error;
        }
        $update_stmt->close();
    }
}

// Get dropdown data BEFORE closing connection
$designations = [];
$divisions = [];
$sections = [];

// Get designations
$sql_desg = "SELECT designation FROM designation";
$result_desg = $conn->query($sql_desg);
if ($result_desg && $result_desg->num_rows > 0) {
    while ($row_desg = $result_desg->fetch_assoc()) {
        $designations[] = $row_desg['designation'];
    }
}

// Get divisions
$sql_div = "SELECT division FROM division";
$result_div = $conn->query($sql_div);
if ($result_div && $result_div->num_rows > 0) {
    while ($row_div = $result_div->fetch_assoc()) {
        $divisions[] = $row_div['division'];
    }
}

// Get sections
$sql_section = "SELECT name FROM section";
$result_section = $conn->query($sql_section);
if ($result_section && $result_section->num_rows > 0) {
    while ($row_section = $result_section->fetch_assoc()) {
        $sections[] = $row_section['name'];
    }
}

// Close connection after getting all data
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - <?php echo htmlspecialchars($user['full_name']); ?></title>
    <style>
        /* Similar styles to registration form */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
        }

        .form-container {
            padding: 30px;
        }

        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .required:after {
            content: " *";
            color: #e74c3c;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit User</h1>
            <p>Update user information</p>
        </div>
        
        <div class="form-container">
            <?php if ($message): ?>
                <div class="message success">
                    <?php echo $message; ?>
                    <br>
                    <small>User has been updated successfully.</small>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="emp_id" class="required">Employee ID</label>
                        <input type="text" id="emp_id" name="emp_id" 
                               value="<?php echo htmlspecialchars($user['emp_id']); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name" class="required">Full Name</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="designation" class="required">Designation</label>
                        <select id="designation" name="designation" required>
                            <option value="">Select Designation</option>
                            <?php 
                            if (!empty($designations)) {
                                foreach ($designations as $designation) {
                                    $selected = ($user['designation'] == $designation) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($designation) . "\" $selected>" . htmlspecialchars($designation) . "</option>";
                                }
                            } else {
                                echo "<option disabled>No Designation found</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="division" class="required">Division</label>
                        <select id="division" name="division" required>
                            <option value="">Select Division</option>
                            <?php 
                            if (!empty($divisions)) {
                                foreach ($divisions as $division) {
                                    $selected = ($user['division'] == $division) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($division) . "\" $selected>" . htmlspecialchars($division) . "</option>";
                                }
                            } else {
                                echo "<option disabled>No division found</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="section" class="required">Section</label>
                        <select id="section" name="section" required>
                            <option value="">Select Section</option>
                            <?php 
                            if (!empty($sections)) {
                                foreach ($sections as $section) {
                                    $selected = ($user['section'] == $section) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($section) . "\" $selected>" . htmlspecialchars($section) . "</option>";
                                }
                            } else {
                                echo "<option disabled>No section found</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status" class="required">Account Status</label>
                        <select id="status" name="status" required>
                            <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
    <label for="role" class="required">User Role</label>
    <select id="role" name="role" required>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <!-- Admins can only assign User -->
            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
        <?php else: ?>
            <!-- Super Admin (or other higher roles) can assign all -->
            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="sadmin" <?php echo $user['role'] == 'sadmin' ? 'selected' : ''; ?>>Super Admin</option>
        <?php endif; ?>
    </select>
</div>
                    
                    <div class="form-group">
                        <label for="routine_role">Routine Role (Optional)</label>
                        <select id="routine_role" name="routine_role">
                            <option value="">None</option>
                            <option value="section_head" <?php echo $user['routine_role'] == 'section_head' ? 'selected' : ''; ?>>Section Head</option>
                            <option value="division_head" <?php echo $user['routine_role'] == 'division_head' ? 'selected' : ''; ?>>Division Head</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        💾 Save Changes
                    </button>
                    <a href="user_management.php" class="btn btn-secondary">
                        ← Cancel
                    </a>
                </div>
            </form>
            
            <div class="back-link">
                <a href="view_user.php?id=<?php echo $user_id; ?>">👁️ View User Details</a>
            </div>
        </div>
    </div>
</body>
</html>