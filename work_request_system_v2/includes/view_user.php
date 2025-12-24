<?php
// view_user.php
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

// Get user activity statistics
$activity_sql = "
    SELECT 
        (SELECT COUNT(*) FROM work_request_tbl WHERE requester_id = ?) as total_requests,
        (SELECT COUNT(*) FROM work_request_tbl WHERE requester_id = ? AND w_com_status = 'complete') as completed_requests,
        (SELECT COUNT(*) FROM work_request_tbl WHERE requester_id = ? AND w_com_status = 'incomplete') as pending_requests,
        (SELECT COUNT(*) FROM security_logs WHERE user_id = ?) as security_events
";

$activity_stmt = $conn->prepare($activity_sql);
$activity_stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$activity_stmt->execute();
$activity_result = $activity_stmt->get_result();
$activity = $activity_result->fetch_assoc();
$activity_stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - <?php echo htmlspecialchars($user['full_name']); ?></title>
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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
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
            font-size: 24px;
        }

        .user-id {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .detail-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .detail-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e1e5eb;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 16px;
        }

        .badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
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
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .timestamps {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .timestamps {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>User Details</h1>
                    <p><?php echo htmlspecialchars($user['full_name']); ?></p>
                </div>
                <div class="user-id">
                    ID: <?php echo $user['id']; ?> | Emp ID: <?php echo htmlspecialchars($user['emp_id']); ?>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $activity['total_requests']; ?></div>
                    <div class="stat-label">Total Requests</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $activity['completed_requests']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $activity['pending_requests']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $activity['security_events']; ?></div>
                    <div class="stat-label">Security Events</div>
                </div>
            </div>
            
            <div class="details-grid">
                <div class="detail-section">
                    <h3>Basic Information</h3>
                    <div class="detail-row">
                        <div class="detail-label">Employee ID</div>
                        <div class="detail-value"><?php echo htmlspecialchars($user['emp_id']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Designation</div>
                        <div class="detail-value"><?php echo htmlspecialchars($user['designation']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Division</div>
                        <div class="detail-value"><?php echo htmlspecialchars($user['division']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Section</div>
                        <div class="detail-value"><?php echo htmlspecialchars($user['section']); ?></div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Account Information</h3>
                    <div class="detail-row">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value">
                            <span class="badge badge-<?php echo $user['status']; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">User Role</div>
                        <div class="detail-value">
                            <span class="badge badge-<?php echo $user['role']; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Routine Role</div>
                        <div class="detail-value">
                            <?php if ($user['routine_role']): ?>
                                <span class="badge badge-<?php echo str_replace('_', '-', $user['routine_role']); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $user['routine_role'])); ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #666;">Not assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Last Password Change</div>
                        <div class="detail-value">
                            <?php 
                            $last_change = $user['updated_at'];
                            echo $last_change ? date('F j, Y h:i A', strtotime($last_change)) : 'Never';
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>System Information</h3>
                    <div class="detail-row">
                        <div class="detail-label">User ID</div>
                        <div class="detail-value"><?php echo $user['id']; ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Database Record ID</div>
                        <div class="detail-value"><?php echo $user['id']; ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Record Created</div>
                        <div class="detail-value">
                            <?php echo date('F j, Y', strtotime($user['created_at'])); ?>
                            <small style="display: block; color: #666;">
                                <?php echo date('h:i A', strtotime($user['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">
                            <?php echo date('F j, Y', strtotime($user['updated_at'])); ?>
                            <small style="display: block; color: #666;">
                                <?php echo date('h:i A', strtotime($user['updated_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="timestamps">
                <div>
                    <strong>Account Created:</strong> 
                    <?php echo date('F j, Y h:i A', strtotime($user['created_at'])); ?>
                </div>
                <div>
                    <strong>Last Updated:</strong> 
                    <?php echo date('F j, Y h:i A', strtotime($user['updated_at'])); ?>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="user_management.php" class="btn btn-back">
                    ← Back to User Management
                </a>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">
                    ✏️ Edit User
                </a>
                <?php if ($user['status'] === 'active'): ?>
                    <a href="user_management.php?action=deactivate&id=<?php echo $user['id']; ?>" 
                       class="btn btn-secondary"
                       onclick="return confirm('Deactivate this user?')">
                        ⏸️ Deactivate
                    </a>
                <?php else: ?>
                    <a href="user_management.php?action=activate&id=<?php echo $user['id']; ?>" 
                       class="btn btn-secondary"
                       onclick="return confirm('Activate this user?')">
                        ✅ Activate
                    </a>
                <?php endif; ?>
                <a href="reset_password.php?id=<?php echo $user['id']; ?>" 
                   class="btn" style="background: #9b59b6; color: white;"
                   onclick="return confirm('Reset password for this user?')">
                    🔑 Reset Password
                </a>
                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <a href="user_management.php?action=delete&id=<?php echo $user['id']; ?>" 
                       class="btn" style="background: #e74c3c; color: white;"
                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        🗑️ Delete User
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>