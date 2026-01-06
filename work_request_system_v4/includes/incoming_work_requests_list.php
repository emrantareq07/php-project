<?php
// incoming_work_requests_list.php
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
$full_name = $_SESSION['full_name'] ?? '';
$designation = $_SESSION['designation'] ?? '';
$division = $_SESSION['division'] ?? '';
$section = $_SESSION['section'] ?? '';

// Get user's routine role and work type permissions
$sql_user = "SELECT routine_role, division, section FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$stmt_user->close();

$routine_role = $user_data['routine_role'] ?? null;
$user_division = $user_data['division'] ?? '';
$user_section = $user_data['section'] ?? '';

// Handle filters
$status_filter = $_GET['status'] ?? 'all';
$type_filter = $_GET['type'] ?? 'all';
$urgency_filter = $_GET['urgency'] ?? 'all';
$division_filter = $_GET['division_filter'] ?? 'all';
$section_filter = $_GET['section_filter'] ?? 'all';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Determine which work request types this user can see
$allowed_types = [];
if ($user_role === 'admin' || $user_role === 'sadmin') {
    // Admin can see all types
    $allowed_types = ['ICT', 'Civil', 'Transport', 'Electrical'];
} else {
    // Based on routine_role, determine which types to show
    if ($routine_role === 'section_head') {
        // Section Head: Can see requests for their section based on type
        $allowed_types = get_allowed_types_for_section_head($conn, $user_section);
    } elseif ($routine_role === 'division_head') {
        // Division Head: Can see requests for their division based on type
        $allowed_types = get_allowed_types_for_division_head($conn, $user_division);
    } else {
        // Regular user: No incoming requests
        $allowed_types = [];
    }
}

// Build query for incoming work requests
$sql = "SELECT wr.* FROM work_request_tbl wr WHERE 1=1";
$count_sql = "SELECT COUNT(*) as total FROM work_request_tbl wr WHERE 1=1";
$params = [];
$types = '';

// Filter by allowed work request types
if (!empty($allowed_types)) {
    $placeholders = implode(',', array_fill(0, count($allowed_types), '?'));
    $sql .= " AND wr.w_req_type IN ($placeholders)";
    $count_sql .= " AND wr.w_req_type IN ($placeholders)";
    $params = array_merge($params, $allowed_types);
    $types .= str_repeat('s', count($allowed_types));
    
    // Filter by division/section based on role
    if ($routine_role === 'section_head') {
        // Show requests for user's section
        $sql .= " AND wr.w_com_section = ?";
        $count_sql .= " AND wr.w_com_section = ?";
        $params[] = $user_section;
        $types .= 's';
    } elseif ($routine_role === 'division_head') {
        // Show requests for user's division
        $sql .= " AND wr.w_com_division = ?";
        $count_sql .= " AND wr.w_com_division = ?";
        $params[] = $user_division;
        $types .= 's';
    }
    
    // Exclude user's own requests (incoming means from others)
    $sql .= " AND wr.requester_id != ?";
    $count_sql .= " AND wr.requester_id != ?";
    $params[] = $user_id;
    $types .= 'i';
    
    // Show only incomplete requests by default (incoming work)
    $sql .= " AND wr.w_com_status = 'incomplete'";
    $count_sql .= " AND wr.w_com_status = 'incomplete'";
} else {
    // No allowed types or not a head role - show empty
    $sql .= " AND 1=0";
    $count_sql .= " AND 1=0";
}

// Apply additional filters from UI
if ($status_filter !== 'all') {
    $sql .= " AND wr.w_com_status = ?";
    $count_sql .= " AND wr.w_com_status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if ($type_filter !== 'all' && in_array($type_filter, $allowed_types)) {
    $sql .= " AND wr.w_req_type = ?";
    $count_sql .= " AND wr.w_req_type = ?";
    $params[] = $type_filter;
    $types .= 's';
}

if ($urgency_filter !== 'all') {
    $sql .= " AND wr.status = ?";
    $count_sql .= " AND wr.status = ?";
    $params[] = $urgency_filter;
    $types .= 's';
}

if ($division_filter !== 'all' && ($user_role === 'admin' || $user_role === 'sadmin')) {
    $sql .= " AND wr.w_com_division = ?";
    $count_sql .= " AND wr.w_com_division = ?";
    $params[] = $division_filter;
    $types .= 's';
}

if ($section_filter !== 'all' && ($user_role === 'admin' || $user_role === 'sadmin')) {
    $sql .= " AND wr.w_com_section = ?";
    $count_sql .= " AND wr.w_com_section = ?";
    $params[] = $section_filter;
    $types .= 's';
}

if (!empty($date_from)) {
    $sql .= " AND wr.date >= ?";
    $count_sql .= " AND wr.date >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if (!empty($date_to)) {
    $sql .= " AND wr.date <= ?";
    $count_sql .= " AND wr.date <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$sql .= " ORDER BY 
    CASE 
        WHEN wr.status = 'very urgent' THEN 1
        WHEN wr.status = 'urgent' THEN 2
        ELSE 3
    END,
    wr.created_at DESC";

// Get total count
$total_count = 0;
if (!empty($allowed_types)) {
    $stmt = $conn->prepare($count_sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $count_result = $stmt->get_result();
    $total_count_row = $count_result->fetch_assoc();
    $total_count = $total_count_row['total'] ?? 0;
    $stmt->close();
}

// Get filtered data
$requests = [];
if (!empty($allowed_types)) {
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Get statistics for each work request type (incoming only)
$type_stats_sql = "SELECT 
    w_req_type,
    COUNT(*) as total,
    SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as complete,
    SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as incomplete
    FROM work_request_tbl WHERE 1=1";

if (!empty($allowed_types)) {
    $placeholders = implode(',', array_fill(0, count($allowed_types), '?'));
    $type_stats_sql .= " AND w_req_type IN ($placeholders)";
    
    if ($routine_role === 'section_head') {
        $type_stats_sql .= " AND w_com_section = ? AND requester_id != ?";
        $type_stats_params = array_merge($allowed_types, [$user_section, $user_id]);
    } elseif ($routine_role === 'division_head') {
        $type_stats_sql .= " AND w_com_division = ? AND requester_id != ?";
        $type_stats_params = array_merge($allowed_types, [$user_division, $user_id]);
    } else {
        $type_stats_params = $allowed_types;
    }
    
    $type_stats_sql .= " GROUP BY w_req_type ORDER BY w_req_type";
    
    $stmt_stats = $conn->prepare($type_stats_sql);
    $types_str = str_repeat('s', count($allowed_types));
    if ($routine_role === 'section_head' || $routine_role === 'division_head') {
        $types_str .= 'si';
    }
    $stmt_stats->bind_param($types_str, ...$type_stats_params);
    $stmt_stats->execute();
    $type_stats_result = $stmt_stats->get_result();
    $type_stats = $type_stats_result->fetch_all(MYSQLI_ASSOC);
    $stmt_stats->close();
} else {
    $type_stats = [];
}

// Get unique divisions and sections for admin filters
$divisions = [];
$sections = [];
if ($user_role === 'admin' || $user_role === 'sadmin') {
    $div_sql = "SELECT DISTINCT division FROM users WHERE division IS NOT NULL AND division != '' ORDER BY division";
    $div_result = $conn->query($div_sql);
    $divisions = $div_result->fetch_all(MYSQLI_ASSOC);
    
    $sec_sql = "SELECT DISTINCT section FROM users WHERE section IS NOT NULL AND section != '' ORDER BY section";
    $sec_result = $conn->query($sec_sql);
    $sections = $sec_result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();

// Helper function to get allowed types for section head
function get_allowed_types_for_section_head($conn, $section) {
    // Default: section head can see ICT requests for their section
    // You can modify this based on your business rules
    return ['ICT']; // Example: Section heads handle ICT requests
}

// Helper function to get allowed types for division head
function get_allowed_types_for_division_head($conn, $division) {
    // Default: division head can see all types for their division
    // You can modify this based on your business rules
    return ['ICT', 'Civil', 'Transport', 'Electrical'];
}

require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incoming Work Requests</title>
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

        .info-panel {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .info-header h2 {
            font-size: 22px;
            margin: 0;
        }

        .role-badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .info-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .info-item {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .info-label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
        }

        .type-filter-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .type-btn {
            padding: 10px 20px;
            border: 2px solid #e1e5eb;
            border-radius: 6px;
            background: white;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .type-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .type-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .type-count {
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
        }

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

        .badge-new {
            background: #d4edda;
            color: #155724;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

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
        .btn-assign { color: #9b59b6; border-color: #9b59b6; }
        .btn-complete { color: #27ae60; border-color: #27ae60; }
        .btn-update { color: #f39c12; border-color: #f39c12; }

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

        .urgent-alert {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: pulse 2s infinite;
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
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            th, td {
                padding: 10px;
                font-size: 13px;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>📥 Incoming Work Requests</h1>
                    <p>Requests assigned to your section/division</p>
                </div>
                <div class="header-actions">
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Dashboard
                    </a>
                    <a href="work_requests_list.php" class="btn btn-primary">
                        📋 My Requests
                    </a>
                </div>
            </div>
        </div>
        
        <div class="content">
            <!-- Information Panel -->
            <div class="info-panel">
                <div class="info-header">
                    <h2>Your Responsibility Area</h2>
                    <span class="role-badge">
                        <?php 
                        if ($routine_role) {
                            echo ucfirst(str_replace('_', ' ', $routine_role));
                        } else {
                            echo 'Regular User';
                        }
                        ?>
                    </span>
                </div>
                <div class="info-content">
                    <div class="info-item">
                        <div class="info-label">Your Division</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_division); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Your Section</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_section); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Allowed Request Types</div>
                        <div class="info-value">
                            <?php 
                            if (!empty($allowed_types)) {
                                echo implode(', ', $allowed_types);
                            } else {
                                echo 'None';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Viewing Requests From</div>
                        <div class="info-value">
                            <?php 
                            if ($routine_role === 'section_head') {
                                echo 'Your Section (' . htmlspecialchars($user_section) . ')';
                            } elseif ($routine_role === 'division_head') {
                                echo 'Your Division (' . htmlspecialchars($user_division) . ')';
                            } elseif ($user_role === 'admin' || $user_role === 'sadmin') {
                                echo 'All Divisions';
                            } else {
                                echo 'Not applicable';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Urgent Alert -->
            <?php 
            $urgent_count = count(array_filter($requests, function($r) {
                return $r['status'] === 'very urgent' && $r['w_com_status'] === 'incomplete';
            }));
            if ($urgent_count > 0): ?>
                <div class="urgent-alert">
                    <span style="font-size: 24px;">🚨</span>
                    <div>
                        <strong>URGENT ATTENTION NEEDED!</strong><br>
                        You have <strong><?php echo $urgent_count; ?></strong> very urgent pending requests that require immediate action.
                    </div>
                </div>
            <?php endif; ?>

            <!-- Work Request Type Filter Buttons -->
            <?php if (!empty($type_stats)): ?>
                <div class="type-filter-buttons">
                    <a href="incoming_work_requests_list.php" 
                       class="type-btn <?php echo !isset($_GET['type']) || $_GET['type'] === 'all' ? 'active' : ''; ?>">
                        📦 All Types
                        <span class="type-count"><?php echo array_sum(array_column($type_stats, 'total')); ?></span>
                    </a>
                    <?php foreach ($type_stats as $type_stat): ?>
                        <a href="incoming_work_requests_list.php?type=<?php echo $type_stat['w_req_type']; ?>" 
                           class="type-btn <?php echo (isset($_GET['type']) && $_GET['type'] == $type_stat['w_req_type']) ? 'active' : ''; ?>">
                            <?php 
                            $icons = [
                                'ICT' => '💻',
                                'Civil' => '🏗️',
                                'Transport' => '🚚',
                                'Electrical' => '⚡'
                            ];
                            echo ($icons[$type_stat['w_req_type']] ?? '📄') . ' ' . $type_stat['w_req_type'];
                            ?>
                            <span class="type-count"><?php echo $type_stat['total']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Filters -->
            <form method="GET" class="filters">
                <div class="filter-group search-box">
                    <label for="search">Search Requests</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Search by requester, location, description..."
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="status">Completion Status</label>
                    <select id="status" name="status">
                        <option value="all">All Status</option>
                        <option value="complete" <?php echo $status_filter === 'complete' ? 'selected' : ''; ?>>Complete</option>
                        <option value="incomplete" <?php echo $status_filter === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
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
                
                <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                    <div class="filter-group">
                        <label for="division_filter">Division</label>
                        <select id="division_filter" name="division_filter">
                            <option value="all">All Divisions</option>
                            <?php foreach ($divisions as $div): ?>
                                <option value="<?php echo htmlspecialchars($div['division']); ?>"
                                    <?php echo $division_filter === $div['division'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($div['division']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="section_filter">Section</label>
                        <select id="section_filter" name="section_filter">
                            <option value="all">All Sections</option>
                            <?php foreach ($sections as $sec): ?>
                                <option value="<?php echo htmlspecialchars($sec['section']); ?>"
                                    <?php echo $section_filter === $sec['section'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($sec['section']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                
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
                    <a href="incoming_work_requests_list.php" class="btn" style="background: #6c757d; color: white; flex: 1;">
                        🗑️ Clear Filters
                    </a>
                </div>
            </form>
            
            <!-- Statistics -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $total_count; ?></div>
                    <div class="stat-label">Total Incoming</div>
                </div>
                
                <?php
                // Count by status
                $complete_count = 0;
                $incomplete_count = 0;
                $urgent_count = 0;
                foreach ($requests as $request) {
                    if ($request['w_com_status'] === 'complete') {
                        $complete_count++;
                    } else {
                        $incomplete_count++;
                    }
                    if ($request['status'] === 'very urgent') {
                        $urgent_count++;
                    }
                }
                ?>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $incomplete_count; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $complete_count; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?php echo $urgent_count; ?></div>
                    <div class="stat-label">Very Urgent</div>
                </div>
            </div>
            
            <!-- Requests Table -->
            <div class="table-container">
                <?php if (empty($requests)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <?php 
                            if (empty($allowed_types)) {
                                echo '🔒';
                            } else {
                                echo '📭';
                            }
                            ?>
                        </div>
                        <h3>
                            <?php 
                            if (empty($allowed_types)) {
                                echo 'No Access to Incoming Requests';
                            } else {
                                echo 'No Incoming Work Requests Found';
                            }
                            ?>
                        </h3>
                        <p>
                            <?php 
                            if (empty($allowed_types)) {
                                echo 'You do not have permission to view incoming work requests.';
                            } elseif ($routine_role === 'section_head') {
                                echo 'No incoming work requests found for your section (' . htmlspecialchars($user_section) . ').';
                            } elseif ($routine_role === 'division_head') {
                                echo 'No incoming work requests found for your division (' . htmlspecialchars($user_division) . ').';
                            } else {
                                echo 'There are no work requests assigned to your area of responsibility.';
                            }
                            ?>
                        </p>
                        <?php if (!empty($allowed_types)): ?>
                            <a href="work_request.php" class="btn" style="margin-top: 20px; background: #667eea; color: white;">
                                Create New Request
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Requester</th>
                                <th>Location</th>
                                <th>Concerned Division</th>
                                <th>Status</th>
                                <th>Urgency</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): 
                                // Check if request is new (created within last 24 hours)
                                $is_new = (strtotime($request['created_at']) > strtotime('-24 hours'));
                            ?>
                                <tr>
                                    <td>
                                        <strong>WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                        <?php if ($is_new): ?>
                                            <span class="badge badge-new" style="margin-left: 5px; font-size: 10px;">NEW</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($request['date'])); ?></td>
                                    <td>
                                        <span class="badge badge-type">
                                            <?php echo $request['w_req_type']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($request['full_name']); ?></div>
                                        <div style="font-size: 12px; color: #666;">
                                            <?php echo htmlspecialchars($request['designation']); ?> |
                                            <?php echo htmlspecialchars($request['division']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 200px;">
                                            <?php echo htmlspecialchars(substr($request['w_location'], 0, 50)); ?>
                                            <?php echo strlen($request['w_location']) > 50 ? '...' : ''; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($request['w_com_division']); ?>
                                        <div style="font-size: 12px; color: #666;">
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
                                        <?php echo date('d/m/Y', strtotime($request['created_at'])); ?>
                                        <small style="display: block; color: #666;">
                                            <?php echo date('h:i A', strtotime($request['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="view_request.php?id=<?php echo $request['id']; ?>" 
                                               class="action-btn btn-view" title="View Details">
                                                👁️ View
                                            </a>
                                            <a href="update_status.php?id=<?php echo $request['id']; ?>" 
                                               class="action-btn btn-update" title="Update Status">
                                                🔄 Update
                                            </a>
                                            <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                                                <a href="assign_request.php?id=<?php echo $request['id']; ?>" 
                                                   class="action-btn btn-assign" title="Assign to Staff">
                                                    👥 Assign
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($request['w_com_status'] === 'incomplete' && 
                                                     ($routine_role === 'section_head' || $routine_role === 'division_head' || 
                                                      $user_role === 'admin' || $user_role === 'sadmin')): ?>
                                                <a href="complete_request.php?id=<?php echo $request['id']; ?>" 
                                                   class="action-btn btn-complete" title="Mark as Complete"
                                                   onclick="return confirm('Mark this request as complete?')">
                                                    ✅ Complete
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
            const form = document.querySelector('form');
            form.submit();
        }
        
        function clearFilters() {
            window.location.href = 'incoming_work_requests_list.php';
        }
        
        // Auto-refresh every 30 seconds if there are incomplete urgent requests
        <?php 
        $incomplete_urgent = count(array_filter($requests, function($r) {
            return $r['w_com_status'] === 'incomplete' && 
                   ($r['status'] === 'urgent' || $r['status'] === 'very urgent');
        }));
        if ($incomplete_urgent > 0): ?>
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        <?php endif; ?>
        
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
        
        // Initialize date inputs with default range (last 7 days)
        document.addEventListener('DOMContentLoaded', function() {
            const dateTo = document.getElementById('date_to');
            const dateFrom = document.getElementById('date_from');
            
            if (!dateTo.value) {
                const today = new Date();
                dateTo.value = today.toISOString().split('T')[0];
            }
            
            if (!dateFrom.value) {
                const sevenDaysAgo = new Date();
                sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                dateFrom.value = sevenDaysAgo.toISOString().split('T')[0];
            }
        });
        
        // Quick status update
        function updateRequestStatus(requestId, action) {
            if (confirm(`Are you sure you want to ${action} this request?`)) {
                window.location.href = `update_status.php?id=${requestId}&action=${action}`;
            }
        }
    </script>
</body>
</html>