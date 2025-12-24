<?php
// admin_requests.php
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

// Handle actions (update status, delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $request_id = intval($_GET['id']);
    $admin_id = $_SESSION['user_id'];
    
    switch ($action) {
        case 'mark_complete':
            $sql = "UPDATE work_request_tbl SET w_com_status = 'complete', updated_at = NOW() WHERE id = ?";
            $message = "Request marked as complete";
            break;
            
        case 'mark_incomplete':
            $sql = "UPDATE work_request_tbl SET w_com_status = 'incomplete', updated_at = NOW() WHERE id = ?";
            $message = "Request marked as incomplete";
            break;
            
        case 'delete':
            $sql = "DELETE FROM work_request_tbl WHERE id = ?";
            $message = "Request deleted successfully";
            break;
            
        default:
            header("Location: admin_requests.php");
            exit;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    
    if ($stmt->execute()) {
        // Log the action
        $log_sql = "INSERT INTO admin_action_logs (admin_id, action_type, request_id, action_time) 
                   VALUES (?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("isi", $admin_id, $action, $request_id);
        $log_stmt->execute();
        $log_stmt->close();
        
        header("Location: admin_requests.php?success=" . urlencode($message));
        exit;
    } else {
        header("Location: admin_requests.php?error=" . urlencode("Operation failed: " . $conn->error));
        exit;
    }
}

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && isset($_POST['selected_requests'])) {
    $bulk_action = $_POST['bulk_action'];
    $selected_requests = $_POST['selected_requests'];
    $admin_id = $_SESSION['user_id'];
    
    if (!empty($selected_requests)) {
        $ids = implode(',', array_map('intval', $selected_requests));
        
        switch ($bulk_action) {
            case 'mark_complete':
                $sql = "UPDATE work_request_tbl SET w_com_status = 'complete', updated_at = NOW() WHERE id IN ($ids)";
                $message = count($selected_requests) . " request(s) marked as complete";
                break;
                
            case 'mark_incomplete':
                $sql = "UPDATE work_request_tbl SET w_com_status = 'incomplete', updated_at = NOW() WHERE id IN ($ids)";
                $message = count($selected_requests) . " request(s) marked as incomplete";
                break;
                
            case 'delete':
                $sql = "DELETE FROM work_request_tbl WHERE id IN ($ids)";
                $message = count($selected_requests) . " request(s) deleted";
                break;
                
            default:
                header("Location: admin_requests.php");
                exit;
        }
        
        if ($conn->query($sql)) {
            // Log bulk action
            $log_sql = "INSERT INTO admin_action_logs (admin_id, action_type, request_id, action_time) 
                       VALUES (?, ?, 0, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $bulk_action_type = "bulk_" . $bulk_action;
            $log_stmt->bind_param("is", $admin_id, $bulk_action_type);
            $log_stmt->execute();
            $log_stmt->close();
            
            header("Location: admin_requests.php?success=" . urlencode($message));
            exit;
        } else {
            header("Location: admin_requests.php?error=" . urlencode("Bulk operation failed: " . $conn->error));
            exit;
        }
    }
}

// Handle search and filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? 'all';
$type_filter = $_GET['type'] ?? 'all';
$urgency_filter = $_GET['urgency'] ?? 'all';
$division_filter = $_GET['division'] ?? 'all';
$completion_filter = $_GET['completion'] ?? 'all';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$sql = "SELECT wr.*, 
               DATE_FORMAT(wr.date, '%Y-%m-%d') as request_date,
               DATE_FORMAT(wr.created_at, '%Y-%m-%d %H:%i') as created_datetime,
               DATE_FORMAT(wr.updated_at, '%Y-%m-%d %H:%i') as updated_datetime
        FROM work_request_tbl wr WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $sql .= " AND (wr.full_name LIKE ? OR wr.emp_id LIKE ? OR wr.w_location LIKE ? OR wr.w_description LIKE ?)";
    $search_term = "%$search%";
    $params = array_fill(0, 4, $search_term);
    $types = str_repeat('s', 4);
}

if ($status_filter !== 'all') {
    $sql .= " AND wr.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if ($type_filter !== 'all') {
    $sql .= " AND wr.w_req_type = ?";
    $params[] = $type_filter;
    $types .= 's';
}

if ($urgency_filter !== 'all') {
    $sql .= " AND wr.status = ?";
    $params[] = $urgency_filter;
    $types .= 's';
}

if ($division_filter !== 'all') {
    $sql .= " AND wr.division = ?";
    $params[] = $division_filter;
    $types .= 's';
}

if ($completion_filter !== 'all') {
    $sql .= " AND wr.w_com_status = ?";
    $params[] = $completion_filter;
    $types .= 's';
}

if (!empty($date_from)) {
    $sql .= " AND wr.date >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if (!empty($date_to)) {
    $sql .= " AND wr.date <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$sql .= " ORDER BY 
    CASE WHEN wr.status = 'very urgent' THEN 1
         WHEN wr.status = 'urgent' THEN 2
         ELSE 3
    END,
    wr.created_at DESC";

// Get total count for stats
$count_sql = str_replace('SELECT wr.*, DATE_FORMAT(wr.date, \'%Y-%m-%d\') as request_date, DATE_FORMAT(wr.created_at, \'%Y-%m-%d %H:%i\') as created_datetime, DATE_FORMAT(wr.updated_at, \'%Y-%m-%d %H:%i\') as updated_datetime', 'SELECT COUNT(*) as total', $sql);
$count_stmt = $conn->prepare($count_sql);

if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_requests = $count_result->fetch_assoc()['total'];
$count_stmt->close();

// Get filtered data
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get statistics for dashboard
$stats_sql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as complete,
        SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as incomplete,
        SUM(CASE WHEN status = 'very urgent' THEN 1 ELSE 0 END) as very_urgent,
        SUM(CASE WHEN status = 'urgent' THEN 1 ELSE 0 END) as urgent,
        SUM(CASE WHEN status = 'normal' THEN 1 ELSE 0 END) as normal,
        COUNT(DISTINCT w_req_type) as types,
        COUNT(DISTINCT division) as divisions,
        COUNT(DISTINCT requester_id) as requesters
    FROM work_request_tbl
";

$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();

// Get unique divisions for filter
$divisions_sql = "SELECT DISTINCT division FROM work_request_tbl WHERE division IS NOT NULL AND division != '' ORDER BY division";
$divisions_result = $conn->query($divisions_sql);
$divisions = $divisions_result->fetch_all(MYSQLI_ASSOC);

// Get unique request types
$types_sql = "SELECT DISTINCT w_req_type FROM work_request_tbl ORDER BY w_req_type";
$types_result = $conn->query($types_sql);
$request_types = $types_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Work Requests Management</title>
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
            max-width: 1800px;
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

        .date-range {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        input[type="text"], select, input[type="date"] {
            padding: 10px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
        }

        input[type="text"]:focus, select:focus, input[type="date"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            grid-column: 1 / -1;
        }

        /* Bulk Actions */
        .bulk-actions {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .bulk-select-all {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bulk-select-all input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .bulk-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Requests Table */
        .table-container {
            overflow-x: auto;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
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
        .btn-complete { color: #27ae60; border-color: #27ae60; }
        .btn-incomplete { color: #e74c3c; border-color: #e74c3c; }
        .btn-delete { color: #c0392b; border-color: #c0392b; }

        /* Request Details */
        .request-details {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .request-location {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Empty State */
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

        /* Export Section */
        .export-section {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
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
            max-width: 600px;
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
        @media (max-width: 1200px) {
            .container {
                padding: 10px;
            }
            
            .filters {
                grid-template-columns: repeat(2, 1fr);
            }
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
                grid-template-columns: 1fr;
            }
            
            .date-range {
                grid-template-columns: 1fr;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .bulk-buttons {
                width: 100%;
            }
            
            .bulk-buttons .btn {
                flex: 1;
                justify-content: center;
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
            
            .export-section {
                flex-direction: column;
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
                    <h1>📋 Work Requests Management</h1>
                    <p>Admin Panel - View and manage all work requests</p>
                </div>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Dashboard
                    </a>
                    <a href="work_request.php" class="btn btn-primary">
                        ➕ New Request
                    </a>
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
                    <div class="stat-label">Total Requests</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['complete']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['incomplete']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['very_urgent']; ?></div>
                    <div class="stat-label">Very Urgent</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['urgent']; ?></div>
                    <div class="stat-label">Urgent</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['types']; ?></div>
                    <div class="stat-label">Request Types</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['divisions']; ?></div>
                    <div class="stat-label">Divisions</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['requesters']; ?></div>
                    <div class="stat-label">Requesters</div>
                </div>
            </div>
            
            <!-- Filters -->
            <form method="GET" class="filters">
                <div class="filter-group search-box">
                    <label for="search">Search Requests</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Search by requester name, employee ID, location, description..."
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="type">Request Type</label>
                    <select id="type" name="type">
                        <option value="all">All Types</option>
                        <?php foreach ($request_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['w_req_type']); ?>"
                                <?php echo $type_filter === $type['w_req_type'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['w_req_type']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="urgency">Urgency Level</label>
                    <select id="urgency" name="urgency">
                        <option value="all">All Urgency</option>
                        <option value="normal" <?php echo $urgency_filter === 'normal' ? 'selected' : ''; ?>>Normal</option>
                        <option value="urgent" <?php echo $urgency_filter === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                        <option value="very urgent" <?php echo $urgency_filter === 'very urgent' ? 'selected' : ''; ?>>Very Urgent</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="division">Requester Division</label>
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
                
                <div class="filter-group">
                    <label for="completion">Completion Status</label>
                    <select id="completion" name="completion">
                        <option value="all">All Status</option>
                        <option value="complete" <?php echo $completion_filter === 'complete' ? 'selected' : ''; ?>>Complete</option>
                        <option value="incomplete" <?php echo $completion_filter === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                    </select>
                </div>
                
                <div class="date-range">
                    <div class="filter-group">
                        <label for="date_from">Date From</label>
                        <input type="date" id="date_from" name="date_from" 
                               value="<?php echo htmlspecialchars($date_from); ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_to">Date To</label>
                        <input type="date" id="date_to" name="date_to" 
                               value="<?php echo htmlspecialchars($date_to); ?>">
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn" style="background: #667eea; color: white; flex: 1;">
                        🔍 Apply Filters
                    </button>
                    <a href="admin_requests.php" class="btn" style="background: #6c757d; color: white; flex: 1;">
                        🗑️ Clear Filters
                    </a>
                </div>
            </form>
            
            <!-- Bulk Actions -->
            <form method="POST" id="bulkActionForm" class="bulk-actions">
                <div class="bulk-select-all">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                    <label for="selectAll" style="font-weight: 600;">Select All</label>
                </div>
                
                <div class="bulk-buttons">
                    <select name="bulk_action" class="btn" style="background: #f8f9fa; color: #333; border: 1px solid #dee2e6;">
                        <option value="">Bulk Actions</option>
                        <option value="mark_complete">Mark as Complete</option>
                        <option value="mark_incomplete">Mark as Incomplete</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    
                    <button type="button" onclick="submitBulkAction()" class="btn" style="background: #3498db; color: white;">
                        Apply
                    </button>
                    
                    <span style="color: #666; margin-left: auto;">
                        <?php echo count($requests); ?> request(s) found
                    </span>
                </div>
            </form>
            
            <!-- Requests Table -->
            <div class="table-container">
                <?php if (empty($requests)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>No work requests found</h3>
                        <p>Try adjusting your search or filters</p>
                        <a href="admin_requests.php" class="btn" style="margin-top: 20px; background: #667eea; color: white;">
                            Clear Filters
                        </a>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30px;">
                                    <input type="checkbox" id="selectAllHeader" onchange="toggleSelectAll(this)">
                                </th>
                                <th>Request ID</th>
                                <th>Requester</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Concerned Division</th>
                                <th>Completion</th>
                                <th>Urgency</th>
                                <th>Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="request-checkbox" name="selected_requests[]" 
                                               value="<?php echo $request['id']; ?>">
                                    </td>
                                    <td>
                                        <strong>WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                        <div class="request-details">
                                            Emp ID: <?php echo htmlspecialchars($request['emp_id']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($request['full_name']); ?></div>
                                        <div class="request-details">
                                            <?php echo htmlspecialchars($request['designation']); ?> | 
                                            <?php echo htmlspecialchars($request['division']); ?> - 
                                            <?php echo htmlspecialchars($request['section']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-type">
                                            <?php echo $request['w_req_type']; ?>
                                        </span>
                                    </td>
                                    <td class="request-location" title="<?php echo htmlspecialchars($request['w_location']); ?>">
                                        <?php echo htmlspecialchars(substr($request['w_location'], 0, 30)); ?>
                                        <?php echo strlen($request['w_location']) > 30 ? '...' : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($request['w_com_division']); ?>
                                        <div class="request-details">
                                            Sec: <?php echo htmlspecialchars($request['w_com_section']); ?>
                                        </div>
                                    </td>
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
                                        <?php echo date('d/m/Y', strtotime($request['date'])); ?>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($request['created_at'])); ?>
                                        <div class="request-details">
                                            <?php echo date('h:i A', strtotime($request['created_at'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="view_request.php?id=<?php echo $request['id']; ?>" 
                                               class="action-btn btn-view" title="View Details">
                                                👁️
                                            </a>
                                            
                                            <?php if ($request['w_com_status'] === 'incomplete'): ?>
                                                <a href="admin_requests.php?action=mark_complete&id=<?php echo $request['id']; ?>" 
                                                   class="action-btn btn-complete" title="Mark as Complete"
                                                   onclick="return confirm('Mark this request as complete?')">
                                                    ✅
                                                </a>
                                            <?php else: ?>
                                                <a href="admin_requests.php?action=mark_incomplete&id=<?php echo $request['id']; ?>" 
                                                   class="action-btn btn-incomplete" title="Mark as Incomplete"
                                                   onclick="return confirm('Mark this request as incomplete?')">
                                                    ⏸️
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="admin_requests.php?action=delete&id=<?php echo $request['id']; ?>" 
                                               class="action-btn btn-delete" title="Delete Request"
                                               onclick="return confirm('Are you sure you want to delete this request? This action cannot be undone.')">
                                                🗑️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <!-- Export Options -->
            <div class="export-section">
                <button onclick="exportToExcel()" class="btn" style="background: #27ae60; color: white;">
                    📊 Export to Excel
                </button>
                <button onclick="printRequests()" class="btn" style="background: #3498db; color: white;">
                    🖨️ Print Report
                </button>
                <button onclick="generatePDF()" class="btn" style="background: #e74c3c; color: white;">
                    📄 Generate PDF
                </button>
                <button onclick="showStatistics()" class="btn" style="background: #9b59b6; color: white;">
                    📈 View Statistics
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Modal -->
    <div id="statisticsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Request Statistics</h2>
                <button class="close-modal" onclick="closeStatisticsModal()">&times;</button>
            </div>
            <div id="statisticsContent">
                <!-- Statistics will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Bulk Actions
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.request-checkbox');
            const selectAllHeader = document.getElementById('selectAllHeader');
            const selectAllLabel = document.getElementById('selectAll');
            
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            
            if (selectAllHeader) selectAllHeader.checked = checkbox.checked;
            if (selectAllLabel) selectAllLabel.checked = checkbox.checked;
        }
        
        function submitBulkAction() {
            const selected = document.querySelectorAll('.request-checkbox:checked');
            const bulkAction = document.querySelector('select[name="bulk_action"]').value;
            
            if (selected.length === 0) {
                alert('Please select at least one request');
                return;
            }
            
            if (!bulkAction) {
                alert('Please select a bulk action');
                return;
            }
            
            let confirmMessage = '';
            switch (bulkAction) {
                case 'mark_complete':
                    confirmMessage = `Mark ${selected.length} request(s) as complete?`;
                    break;
                case 'mark_incomplete':
                    confirmMessage = `Mark ${selected.length} request(s) as incomplete?`;
                    break;
                case 'delete':
                    confirmMessage = `Delete ${selected.length} request(s)? This action cannot be undone.`;
                    break;
            }
            
            if (confirm(confirmMessage)) {
                document.getElementById('bulkActionForm').submit();
            }
        }
        
        // Export to Excel
        function exportToExcel() {
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            // Remove the checkbox column from export
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('th, td');
                
                cells.forEach((cell, index) => {
                    // Skip first column (checkbox) and last column (actions)
                    if (index !== 0 && index !== cells.length - 1) {
                        // Remove badges and extract text
                        const clone = cell.cloneNode(true);
                        const badges = clone.querySelectorAll('.badge');
                        badges.forEach(badge => {
                            badge.parentNode.replaceChild(document.createTextNode(badge.textContent), badge);
                        });
                        rowData.push(clone.innerText.trim());
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
            a.setAttribute('download', `work_requests_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
        
        // Print function
        function printRequests() {
            const printContent = document.querySelector('.content').innerHTML;
            const originalContent = document.body.innerHTML;
            
            // Remove bulk actions and filters for print
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = printContent;
            tempDiv.querySelector('.bulk-actions')?.remove();
            tempDiv.querySelector('.filters')?.remove();
            tempDiv.querySelector('.export-section')?.remove();
            
            // Remove checkboxes and action buttons from table
            const table = tempDiv.querySelector('table');
            if (table) {
                // Remove first column (checkboxes)
                table.querySelectorAll('th:first-child, td:first-child').forEach(el => el.remove());
                // Remove last column (actions)
                table.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());
            }
            
            document.body.innerHTML = `
                <div style="padding: 20px;">
                    <h1 style="text-align: center; margin-bottom: 10px;">Work Requests Report</h1>
                    <p style="text-align: center; color: #666; margin-bottom: 30px;">
                        Generated on ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}
                    </p>
                    ${tempDiv.innerHTML}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
        
        // Generate PDF (simplified version - would need a proper PDF library)
        function generatePDF() {
            alert('PDF generation would require a server-side library like TCPDF or mPDF. This feature can be implemented separately.');
            // For actual implementation, you would need to:
            // 1. Create a PHP script that generates PDF using TCPDF/mPDF
            // 2. Pass the filtered data to that script
            // 3. Return the PDF file for download
        }
        
        // Show Statistics Modal
        function showStatistics() {
            // Fetch statistics via AJAX
            fetch('get_request_statistics.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('statisticsContent').innerHTML = data;
                    document.getElementById('statisticsModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load statistics');
                });
        }
        
        function closeStatisticsModal() {
            document.getElementById('statisticsModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('statisticsModal');
            if (event.target === modal) {
                closeStatisticsModal();
            }
        }
        
        // Auto-refresh every 60 seconds for urgent requests
        setTimeout(() => {
            // Check if there are urgent incomplete requests
            const urgentBadges = document.querySelectorAll('.badge-very-urgent, .badge-urgent');
            const hasUrgentIncomplete = Array.from(urgentBadges).some(badge => {
                const row = badge.closest('tr');
                const statusBadge = row.querySelector('.badge-incomplete');
                return statusBadge !== null;
            });
            
            if (hasUrgentIncomplete) {
                window.location.reload();
            }
        }, 60000);
        
        // Quick search on Enter key
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
        
        // Date range validation
        document.getElementById('date_from').addEventListener('change', function() {
            const dateTo = document.getElementById('date_to');
            if (dateTo.value && this.value > dateTo.value) {
                alert('"Date From" cannot be later than "Date To"');
                this.value = '';
            }
        });
        
        document.getElementById('date_to').addEventListener('change', function() {
            const dateFrom = document.getElementById('date_from');
            if (dateFrom.value && this.value < dateFrom.value) {
                alert('"Date To" cannot be earlier than "Date From"');
                this.value = '';
            }
        });
        
        // Initialize date inputs with default range (last 30 days)
        document.addEventListener('DOMContentLoaded', function() {
            const dateTo = document.getElementById('date_to');
            const dateFrom = document.getElementById('date_from');
            
            if (!dateTo.value) {
                const today = new Date();
                dateTo.value = today.toISOString().split('T')[0];
            }
            
            if (!dateFrom.value) {
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                dateFrom.value = thirtyDaysAgo.toISOString().split('T')[0];
            }
        });
        
        // Quick status update with confirmation
        function updateRequestStatus(requestId, action) {
            let message = '';
            switch (action) {
                case 'mark_complete':
                    message = 'Mark this request as complete?';
                    break;
                case 'mark_incomplete':
                    message = 'Mark this request as incomplete?';
                    break;
                case 'delete':
                    message = 'Are you sure you want to delete this request? This action cannot be undone.';
                    break;
            }
            
            if (confirm(message)) {
                window.location.href = `admin_requests.php?action=${action}&id=${requestId}`;
            }
        }
    </script>
</body>
</html>