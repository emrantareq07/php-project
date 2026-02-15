<?php
// user_management.php
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

// Handle actions (delete, activate, deactivate)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $user_id = intval($_GET['id']);
    $current_admin_id = $_SESSION['user_id'];
    
    // Prevent self-deletion
    if ($user_id == $current_admin_id && $action == 'delete') {
        header("Location: user_management.php?error=" . urlencode("Cannot delete your own account!"));
        exit;
    }
    
    switch ($action) {
        case 'activate':
            $sql = "UPDATE users SET status = 'active', updated_at = NOW() WHERE id = ?";
            $message = "User activated successfully";
            break;
            
        case 'deactivate':
            $sql = "UPDATE users SET status = 'inactive', updated_at = NOW() WHERE id = ?";
            $message = "User deactivated successfully";
            break;
            
        case 'delete':
            $sql = "DELETE FROM users WHERE id = ?";
            $message = "User deleted successfully";
            break;
            
        default:
            header("Location: user_management.php");
            exit;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        header("Location: user_management.php?success=" . urlencode($message));
        exit;
    } else {
        header("Location: user_management.php?error=" . urlencode("Operation failed: " . $conn->error));
        exit;
    }
}

// Handle search and filters
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? 'all';
$status_filter = $_GET['status'] ?? 'all';
$division_filter = $_GET['division'] ?? 'all';

// Build query
$sql = "SELECT * FROM users WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $sql .= " AND (emp_id LIKE ? OR full_name LIKE ? OR designation LIKE ? OR division LIKE ? OR section LIKE ?)";
    $search_term = "%$search%";
    $params = array_fill(0, 5, $search_term);
    $types = str_repeat('s', 5);
}

if ($role_filter !== 'all') {
    $sql .= " AND role = ?";
    $params[] = $role_filter;
    $types .= 's';
}

if ($status_filter !== 'all') {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if ($division_filter !== 'all') {
    $sql .= " AND division = ?";
    $params[] = $division_filter;
    $types .= 's';
}

$sql .= " ORDER BY created_at DESC";

// Get total count for stats
$count_sql = str_replace('SELECT * FROM', 'SELECT COUNT(*) as total FROM', $sql);
$count_stmt = $conn->prepare($count_sql);

if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_users = $count_result->fetch_assoc()['total'];
$count_stmt->close();

// Get users data
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get statistics for dashboard
$stats_sql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
        SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
        SUM(CASE WHEN role = 'sadmin' THEN 1 ELSE 0 END) as sadmins,
        SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as regular_users,
        COUNT(DISTINCT division) as divisions,
        COUNT(DISTINCT section) as sections
    FROM users
";

$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();

// Get unique divisions for filter
$divisions_sql = "SELECT DISTINCT division FROM users WHERE division IS NOT NULL AND division != '' ORDER BY division";
$divisions_result = $conn->query($divisions_sql);
$divisions = $divisions_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 28px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
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
            background: white;
            color: #667eea;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Messages */
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Filters */
        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .search-box {
            grid-column: 1 / -1;
        }

        input[type="text"], select {
            padding: 10px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
        }

        input[type="text"]:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        /* Users Table */
        .table-container {
            overflow-x: auto;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e1e5eb;
            font-size: 14px;
            position: sticky;
            top: 0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e1e5eb;
            vertical-align: middle;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-user {
            background: #e2e3e5;
            color: #383d41;
        }

        .badge-admin {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-sadmin {
            background: #d4edda;
            color: #155724;
        }

        .badge-section-head {
            background: #fff3cd;
            color: #856404;
        }

        .badge-division-head {
            background: #cce5ff;
            color: #004085;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 6px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background: white;
            color: #333;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .action-btn:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        .btn-view { color: #3498db; border-color: #3498db; }
        .btn-edit { color: #f39c12; border-color: #f39c12; }
        .btn-activate { color: #27ae60; border-color: #27ae60; }
        .btn-deactivate { color: #e74c3c; border-color: #e74c3c; }
        .btn-delete { color: #c0392b; border-color: #c0392b; }
        .btn-reset { color: #9b59b6; border-color: #9b59b6; }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .page-link {
            padding: 8px 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
        }

        .page-link.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .page-link:hover:not(.active) {
            background: #f8f9fa;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            color: #333;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .close-modal:hover {
            color: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
            
            .filters {
                grid-template-columns: 1fr;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            th, td {
                padding: 10px;
                font-size: 13px;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>👥 User Management System</h1>
                    <p>Manage user accounts, roles, and permissions</p>
                </div>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Dashboard
                    </a>
                    <a href="logout.php" class=" btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i>  Logout
                </a>
                    <!-- <a href="register.php" class="btn btn-primary">
                        ➕ Add New User
                    </a> -->
                </div>
            </div>
        </div>
        
        <div class="content">
            <?php if (isset($_GET['success'])): ?>
                <div class="message success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['active']; ?></div>
                    <div class="stat-label">Active Users</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['inactive']; ?></div>
                    <div class="stat-label">Inactive Users</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['admins'] + $stats['sadmins']; ?></div>
                    <div class="stat-label">Administrators</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['regular_users']; ?></div>
                    <div class="stat-label">Regular Users</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['divisions']; ?></div>
                    <div class="stat-label">Divisions</div>
                </div>
            </div>
            
            <!-- Filters -->
            <form method="GET" class="filters">
                <div class="filter-group search-box">
                    <label for="search">Search Users</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Search by ID, Name, Designation, Division, Section..."
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="all">All Roles</option>
                        <option value="user" <?php echo $role_filter === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="sadmin" <?php echo $role_filter === 'sadmin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="division">Division</label>
                    <select id="division" name="division">
                        <option value="all">All Divisions</option>
                        <?php foreach ($divisions as $division): ?>
                            <option value="<?php echo htmlspecialchars($division['division']); ?>"
                                <?php echo $division_filter === $division['division'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($division['division']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group filter-actions">
                    <button type="submit" class="btn" style="background: #667eea; color: white;">
                        🔍 Apply Filters
                    </button>
                    <a href="user_management.php" class="btn" style="background: #6c757d; color: white;">
                        🗑️ Clear Filters
                    </a>
                </div>
            </form>
            
            <!-- Users Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emp Type</th>
                            <th>Emp ID</th>
                            <th>Full Name</th>
                            <th>Designation</th>
                            <th>Division</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Routine Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="11" style="text-align: center; padding: 40px; color: #666;">
                                    <div style="font-size: 48px; margin-bottom: 20px;">👤</div>
                                    <h3>No users found</h3>
                                    <p>Try adjusting your search or filters</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['emp_type']); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['emp_id']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['designation']); ?></td>
                                    <td><?php echo htmlspecialchars($user['division']); ?></td>
                                    <td><?php echo htmlspecialchars($user['section']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $user['status']; ?>">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $user['role']; ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['routine_role']): ?>
                                            <span class="badge badge-<?php echo str_replace('_', '-', $user['routine_role']); ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $user['routine_role'])); ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #666; font-size: 12px;">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                        <small style="display: block; color: #666;">
                                            <?php echo date('h:i A', strtotime($user['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="view_user.php?id=<?php echo $user['id']; ?>" 
                                               class="action-btn btn-view" title="View Details">
                                                👁️
                                            </a>
                                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" 
                                               class="action-btn btn-edit" title="Edit User">
                                                ✏️
                                            </a>
                                            
                                            <?php if ($user['status'] === 'inactive'): ?>
                                                <a href="user_management.php?action=activate&id=<?php echo $user['id']; ?>" 
                                                   class="action-btn btn-activate" title="Activate User"
                                                   onclick="return confirm('Activate this user?')">
                                                    ✅
                                                </a>
                                            <?php else: ?>
                                                <a href="user_management.php?action=deactivate&id=<?php echo $user['id']; ?>" 
                                                   class="action-btn btn-deactivate" title="Deactivate User"
                                                   onclick="return confirm('Deactivate this user?')">
                                                    ⏸️
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="reset_password.php?id=<?php echo $user['id']; ?>" 
                                               class="action-btn btn-reset" title="Reset Password"
                                               onclick="return confirm('Reset password for this user?')">
                                                🔑
                                            </a>
                                            
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <a href="user_management.php?action=delete&id=<?php echo $user['id']; ?>" 
                                                   class="action-btn btn-delete" title="Delete User"
                                                   onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                    🗑️
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Export Button -->
            <div style="margin-top: 20px; text-align: center;">
                <button onclick="exportToExcel()" class="btn" style="background: #27ae60; color: white;">
                    📊 Export to Excel
                </button>
                <button onclick="printUsers()" class="btn" style="background: #3498db; color: white;">
                    🖨️ Print List
                </button>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div id="userDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>User Details</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div id="userDetailsContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <script>
        // Export to Excel
        function exportToExcel() {
            // Create a simple CSV export
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('th, td');
                
                cells.forEach(cell => {
                    // Remove action buttons and badges
                    if (!cell.querySelector('.actions') && !cell.querySelector('.badge')) {
                        rowData.push(cell.innerText);
                    } else if (cell.querySelector('.badge')) {
                        rowData.push(cell.querySelector('.badge').innerText);
                    }
                });
                
                csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'users_' + new Date().toISOString().slice(0,10) + '.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
        
        // Print function
        function printUsers() {
            const printContent = document.querySelector('.content').innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div style="padding: 20px;">
                    <h1 style="text-align: center; margin-bottom: 30px;">Users List - ${new Date().toLocaleDateString()}</h1>
                    ${printContent}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
        
        // Quick view user details via AJAX
        function viewUser(id) {
            fetch(`get_user_details.php?id=${id}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('userDetailsContent').innerHTML = data;
                    document.getElementById('userDetailsModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load user details');
                });
        }
        
        function closeModal() {
            document.getElementById('userDetailsModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('userDetailsModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        
        // Auto-refresh every 30 seconds
        setTimeout(() => {
            window.location.reload();
        }, 30000);
        
        // Quick search on Enter key
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
        
        // Bulk actions
        function bulkAction(action) {
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedUsers.length === 0) {
                alert('Please select at least one user');
                return;
            }
            
            if (confirm(`Are you sure you want to ${action} ${selectedUsers.length} user(s)?`)) {
                // Implement bulk action via AJAX
                fetch('bulk_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: action,
                        users: selectedUsers
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to perform bulk action');
                });
            }
        }
    </script>
</body>
</html>