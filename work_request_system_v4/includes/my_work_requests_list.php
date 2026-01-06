<?php
// work_requests_list.php
session_name('factory_work_request_db');
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
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

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';

// Handle status filter
$status_filter = $_GET['status'] ?? 'all';
$type_filter = $_GET['type'] ?? 'all';
$urgency_filter = $_GET['urgency'] ?? 'all';

// Build query based on user role and filters
if ($user_role === 'admin' || $user_role === 'sadmin') {
    // Admin can see all requests
    $sql = "SELECT * FROM work_request_tbl WHERE 1=1";
    $count_sql = "SELECT COUNT(*) as total FROM work_request_tbl WHERE 1=1";
} else {
    // Regular users can only see their own requests
    $sql = "SELECT * FROM work_request_tbl WHERE requester_id = $user_id";
    $count_sql = "SELECT COUNT(*) as total FROM work_request_tbl WHERE requester_id = $user_id";
}

// Apply filters
$params = [];
if ($status_filter !== 'all') {
    $sql .= " AND w_com_status = ?";
    $count_sql .= " AND w_com_status = ?";
    $params[] = $status_filter;
}

if ($type_filter !== 'all') {
    $sql .= " AND w_req_type = ?";
    $count_sql .= " AND w_req_type = ?";
    $params[] = $type_filter;
}

if ($urgency_filter !== 'all') {
    $sql .= " AND status = ?";
    $count_sql .= " AND status = ?";
    $params[] = $urgency_filter;
}

$sql .= " ORDER BY created_at DESC";

// Get total count
$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$count_result = $stmt->get_result();
$total_count = $count_result->fetch_assoc()['total'];
$stmt->close();

// Get filtered data
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Requests</title>
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
            max-width: 1400px;
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

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        select {
            padding: 8px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            font-size: 14px;
            min-width: 150px;
        }

        .stats {
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

        .table-container {
            overflow-x: auto;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e1e5eb;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e1e5eb;
            vertical-align: top;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-complete {
            background: #d4edda;
            color: #155724;
        }

        .badge-incomplete {
            background: #fff3cd;
            color: #856404;
        }

        .badge-normal {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-urgent {
            background: #ffeaa7;
            color: #856404;
        }

        .badge-very-urgent {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-type {
            background: #e2e3e5;
            color: #383d41;
        }

        .actions {
            display: flex;
            gap: 8px;
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

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

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
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            select {
                width: 100%;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            th, td {
                padding: 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>Work Requests</h1>
                    <p>Track and manage your work requests</p>
                </div>
                <div class="header-actions">
                    <a href="work_request.php" class="btn btn-primary">
                        ➕ New Request
                    </a>
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div class="filters">
                <div class="filter-group">
                    <label>Status:</label>
                    <select id="statusFilter" onchange="updateFilters()">
                        <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="complete" <?php echo $status_filter === 'complete' ? 'selected' : ''; ?>>Complete</option>
                        <option value="incomplete" <?php echo $status_filter === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Type:</label>
                    <select id="typeFilter" onchange="updateFilters()">
                        <option value="all" <?php echo $type_filter === 'all' ? 'selected' : ''; ?>>All Types</option>
                        <option value="ICT" <?php echo $type_filter === 'ICT' ? 'selected' : ''; ?>>ICT</option>
                        <option value="Civil" <?php echo $type_filter === 'Civil' ? 'selected' : ''; ?>>Civil</option>
                        <option value="Transport" <?php echo $type_filter === 'Transport' ? 'selected' : ''; ?>>Transport</option>
                        <option value="Electrical" <?php echo $type_filter === 'Electrical' ? 'selected' : ''; ?>>Electrical</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Urgency:</label>
                    <select id="urgencyFilter" onchange="updateFilters()">
                        <option value="all" <?php echo $urgency_filter === 'all' ? 'selected' : ''; ?>>All Urgency</option>
                        <option value="normal" <?php echo $urgency_filter === 'normal' ? 'selected' : ''; ?>>Normal</option>
                        <option value="urgent" <?php echo $urgency_filter === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                        <option value="very urgent" <?php echo $urgency_filter === 'very urgent' ? 'selected' : ''; ?>>Very Urgent</option>
                    </select>
                </div>
                
                <div style="margin-left: auto;">
                    <button onclick="clearFilters()" class="btn" style="background: #e74c3c; color: white;">
                        Clear Filters
                    </button>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_count; ?></div>
                    <div class="stat-label">Total Requests</div>
                </div>
                
                <?php
                // Count by status
                $complete_count = 0;
                $incomplete_count = 0;
                foreach ($requests as $request) {
                    if ($request['w_com_status'] === 'complete') {
                        $complete_count++;
                    } else {
                        $incomplete_count++;
                    }
                }
                ?>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $complete_count; ?></div>
                    <div class="stat-label">Complete</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $incomplete_count; ?></div>
                    <div class="stat-label">Incomplete</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">
                        <?php echo count(array_filter($requests, function($r) {
                            return $r['status'] === 'very urgent';
                        })); ?>
                    </div>
                    <div class="stat-label">Very Urgent</div>
                </div>
            </div>
            
            <div class="table-container">
                <?php if (empty($requests)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>No work requests found</h3>
                        <p>Start by creating your first work request.</p>
                        <a href="work_request.php" class="btn btn-primary" style="margin-top: 20px;">
                            Create New Request
                        </a>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Concerned Division</th>
                                <th>Status</th>
                                <th>Urgency</th>
                                <th>Created</th>
                                <th>Completed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td>
                                        <strong>WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($request['date'])); ?></td>
                                    <td>
                                        <span class="badge badge-type">
                                            <?php echo $request['w_req_type']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="max-width: 200px;">
                                            <?php echo htmlspecialchars(substr($request['w_location'], 0, 50)); ?>
                                            <?php echo strlen($request['w_location']) > 50 ? '...' : ''; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($request['w_com_division']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $request['w_com_status']; ?>">
                                            <?php echo ucfirst($request['w_com_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo str_replace(' ', '-', $request['status']); ?>">
                                            <?php echo ucfirst($request['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($request['created_at'])); ?>
                                        <small style="display: block; color: #666;">
                                            <?php echo date('h:i A', strtotime($request['created_at'])); ?>
                                        </small>
                                    </td>
                                   <td>
    <?php if ($request['w_com_status'] != 'incomplete') { ?>
        <?php echo date('d/m/Y', strtotime($request['updated_at'])); ?>
        <small style="display: block; color: #666;">
            <?php echo date('h:i A', strtotime($request['updated_at'])); ?>
        </small>
    <?php } ?>
</td>
                                    <td>
                                        <div class="actions">
                                            <a href="view_request.php?id=<?php echo $request['id']; ?>" 
                                               class="action-btn" title="View">
                                                👁️
                                            </a>
                                            <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                                                <a href="edit_request.php?id=<?php echo $request['id']; ?>" 
                                                   class="action-btn" title="Edit">
                                                    ✏️
                                                </a>
                                                <a href="update_status.php?id=<?php echo $request['id']; ?>" 
                                                   class="action-btn" title="Update Status">
                                                    🔄
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($request['requester_id'] == $user_id || $user_role === 'admin' || $user_role === 'sadmin'): ?>
                                                <a href="delete_request.php?id=<?php echo $request['id']; ?>" 
                                                   class="action-btn" title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this request?')">
                                                    🗑️
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function updateFilters() {
            const status = document.getElementById('statusFilter').value;
            const type = document.getElementById('typeFilter').value;
            const urgency = document.getElementById('urgencyFilter').value;
            
            let url = 'my_work_requests_list.php?';
            if (status !== 'all') url += 'status=' + status + '&';
            if (type !== 'all') url += 'type=' + type + '&';
            if (urgency !== 'all') url += 'urgency=' + urgency;
            
            // Remove trailing & or ?
            url = url.replace(/[&?]$/, '');
            
            window.location.href = url;
        }
        
        function clearFilters() {
            window.location.href = 'my_work_requests_list.php';
        }
        
        // Auto-refresh every 60 seconds if there are incomplete urgent requests
        <?php if ($incomplete_count > 0): ?>
        setTimeout(function() {
            window.location.reload();
        }, 60000);
        <?php endif; ?>
    </script>
</body>
</html>