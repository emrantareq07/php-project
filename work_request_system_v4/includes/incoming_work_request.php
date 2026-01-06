<?php
// incoming_work_requests.php - DYNAMIC VERSION WITH BOOTSTRAP 5
session_name('factory_work_request_db');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'factory_work_request_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info from session and database
$user_id = $_SESSION['user_id'];
$user_division = $_SESSION['division'] ?? '';
$user_section = $_SESSION['section'] ?? '';
$user_full_name = $_SESSION['full_name'] ?? '';
$user_role = $_SESSION['role'] ?? 'user';

// Get complete user info including routine_role
$stmt = $conn->prepare("SELECT routine_role, division, section FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result()->fetch_assoc();
$routine_role = $user_result['routine_role'] ?? '';
$user_db_division = $user_result['division'] ?? $user_division;
$user_db_section = $user_result['section'] ?? $user_section;
$stmt->close();

// Handle filters
$req_type_filter = $_GET['type'] ?? 'all';
$status_filter = $_GET['status'] ?? 'all';
$division_filter = $_GET['division_filter'] ?? 'all';
$urgency_filter = $_GET['urgency'] ?? 'all';
$view_type = $_GET['view'] ?? 'my_area';

// Initialize variables
$requests = [];
$access_granted = false;
$access_reason = '';
$sql_query = '';
$params = [];
$types = '';
$base_conditions = '';

// DYNAMIC MAPPING FUNCTION
function getMappedDivisionSection($conn, $work_type) {
    $mapping = [
        'ICT' => [
            'division' => 'ICT Division',
            'section' => 'ICT',
            'division_id' => 21,
            'section_id' => 15
        ],
        'Civil' => [
            'division' => 'MTS Division',
            'section' => 'Civil',
            'division_id' => 5,
            'section_id' => 28
        ],
        'Transport' => [
            'division' => 'Transport Division',
            'section' => 'Transport',
            'division_id' => 83,
            'section_id' => 86
        ],
        'Electrical' => [
            'division' => 'MTS (Electrical)',
            'section' => 'Electrical Maintenance (EM)',
            'division_id' => 85,
            'section_id' => 82
        ],
        'Mechanical' => [
            'division' => 'MTS Division',
            'section' => 'MTS (Mechanical)',
            'division_id' => 5,
            'section_id' => 73
        ]
    ];
    
    if (isset($mapping[$work_type])) {
        return $mapping[$work_type];
    }
    
    // Fallback: Try to find in database
    $div_stmt = $conn->prepare("SELECT id, division FROM division WHERE division LIKE ? LIMIT 1");
    $search_term = '%' . $work_type . '%';
    $div_stmt->bind_param("s", $search_term);
    $div_stmt->execute();
    $div_result = $div_stmt->get_result()->fetch_assoc();
    $div_stmt->close();
    
    $sec_stmt = $conn->prepare("SELECT s.id, s.name FROM section s 
                               JOIN division d ON s.division_id = d.id 
                               WHERE s.name LIKE ? OR d.division LIKE ? LIMIT 1");
    $sec_stmt->bind_param("ss", $search_term, $search_term);
    $sec_stmt->execute();
    $sec_result = $sec_stmt->get_result()->fetch_assoc();
    $sec_stmt->close();
    
    return [
        'division' => $div_result['division'] ?? 'Unknown Division',
        'section' => $sec_result['name'] ?? 'Unknown Section',
        'division_id' => $div_result['id'] ?? 0,
        'section_id' => $sec_result['id'] ?? 0
    ];
}

// Check if user is IT Section Head (FIXED: Removed division_head from condition)
$isITSectionHead = ($user_db_division === 'ICT Division' && $routine_role === 'section_head' && $user_db_section === 'ICT');

// DYNAMIC ACCESS LOGIC BASED ON ROUTINE_ROLE
if ($routine_role === 'section_head') {
    // Get mapped division/section for user's work type capabilities
    $user_mapped_info = getMappedDivisionSection($conn, 'ICT'); // Default to ICT for IT section heads
    
    // For IT Section Heads, show only ICT requests
    if ($isITSectionHead) {
        $mapped_info = getMappedDivisionSection($conn, 'ICT');
        $sql_query = "SELECT * FROM work_request_tbl WHERE 1=1";
        $base_conditions = " WHERE 1=1";
        
        // Always filter by ICT type and mapped division/section
        $sql_query .= " AND w_req_type = 'ICT' 
                       AND (w_com_division = ? OR w_com_section = ?)";
        $base_conditions .= " AND w_req_type = 'ICT' 
                              AND (w_com_division = '" . $conn->real_escape_string($mapped_info['division']) . "' 
                                   OR w_com_section = '" . $conn->real_escape_string($mapped_info['section']) . "')";
        $params[] = $mapped_info['division'];
        $params[] = $mapped_info['section'];
        $types .= 'ss';
        
        // Apply status filter ONLY if it's not 'all'
        // When $status_filter === 'all', we don't add any status condition
        if ($status_filter !== 'all') {
            $sql_query .= " AND w_com_status = ?";
            $base_conditions .= " AND w_com_status = '" . $conn->real_escape_string($status_filter) . "'";
            $params[] = $status_filter;
            $types .= 's';
        }
        
        // Force ICT filter for display purposes
        $req_type_filter = 'ICT';
    } else {
        // Regular section head logic - get all work types assigned to this section
        $types_sql = "SELECT DISTINCT w_req_type FROM work_request_tbl 
                      WHERE w_com_section = ? OR LOWER(w_com_section) LIKE LOWER(CONCAT('%', ?, '%'))";
        $types_stmt = $conn->prepare($types_sql);
        $types_stmt->bind_param("ss", $user_db_section, $user_db_section);
        $types_stmt->execute();
        $types_result = $types_stmt->get_result();
        $available_types = [];
        while($type_row = $types_result->fetch_assoc()) {
            $available_types[] = $type_row['w_req_type'];
        }
        $types_stmt->close();
        
        // Build base query for section head
        $sql_query = "SELECT * FROM work_request_tbl WHERE 1=1";
        $base_conditions = " WHERE 1=1";
        
        // Add section condition
        $sql_query .= " AND (w_com_section = ? OR LOWER(w_com_section) LIKE LOWER(CONCAT('%', ?, '%')))";
        $base_conditions .= " AND (w_com_section = '" . $conn->real_escape_string($user_db_section) . "' 
                                   OR LOWER(w_com_section) LIKE LOWER('%" . $conn->real_escape_string($user_db_section) . "%'))";
        $params[] = $user_db_section;
        $params[] = $user_db_section;
        $types .= 'ss';
        
        // Filter by work type if specific type selected
        if ($req_type_filter !== 'all' && in_array($req_type_filter, $available_types)) {
            $sql_query .= " AND w_req_type = ?";
            $base_conditions .= " AND w_req_type = '" . $conn->real_escape_string($req_type_filter) . "'";
            $params[] = $req_type_filter;
            $types .= 's';
        }
        
        // Filter by status
        if ($status_filter !== 'all') {
            $sql_query .= " AND w_com_status = ?";
            $base_conditions .= " AND w_com_status = '" . $conn->real_escape_string($status_filter) . "'";
            $params[] = $status_filter;
            $types .= 's';
        }
    }
    
    // Filter by urgency
    if ($urgency_filter !== 'all') {
        $sql_query .= " AND status = ?";
        $base_conditions .= " AND status = '" . $conn->real_escape_string($urgency_filter) . "'";
        $params[] = $urgency_filter;
        $types .= 's';
    }
    
    // Exclude user's own requests if viewing incoming only
    if ($view_type === 'incoming') {
        $sql_query .= " AND requester_id != ?";
        $base_conditions .= " AND requester_id != " . intval($user_id);
        $params[] = $user_id;
        $types .= 'i';
    }
    
    $access_granted = true;
    $access_reason = "Section Head of '" . htmlspecialchars($user_db_section) . "' section";
    
} elseif ($routine_role === 'division_head') {
    // Get all work types that map to this division
    $available_types = ['ICT', 'Civil', 'Transport', 'Electrical', 'Mechanical'];
    $division_types = [];
    
    foreach ($available_types as $type) {
        $mapped_info = getMappedDivisionSection($conn, $type);
        if ($mapped_info['division'] === $user_db_division || 
            strpos($user_db_division, $type) !== false ||
            strpos($type, $user_db_division) !== false) {
            $division_types[] = $type;
        }
    }
    
    // Build base query for division head
    $sql_query = "SELECT * FROM work_request_tbl WHERE 1=1";
    $base_conditions = " WHERE 1=1";
    
    // Add division condition
    $sql_query .= " AND w_com_division = ?";
    $base_conditions .= " AND w_com_division = '" . $conn->real_escape_string($user_db_division) . "'";
    $params[] = $user_db_division;
    $types .= 's';
    
    // Filter by work type if specific type selected and valid for this division
    if ($req_type_filter !== 'all' && in_array($req_type_filter, $division_types)) {
        $sql_query .= " AND w_req_type = ?";
        $base_conditions .= " AND w_req_type = '" . $conn->real_escape_string($req_type_filter) . "'";
        $params[] = $req_type_filter;
        $types .= 's';
    }
    
    // Filter by status
    if ($status_filter !== 'all') {
        $sql_query .= " AND w_com_status = ?";
        $base_conditions .= " AND w_com_status = '" . $conn->real_escape_string($status_filter) . "'";
        $params[] = $status_filter;
        $types .= 's';
    }
    
    // Filter by urgency
    if ($urgency_filter !== 'all') {
        $sql_query .= " AND status = ?";
        $base_conditions .= " AND status = '" . $conn->real_escape_string($urgency_filter) . "'";
        $params[] = $urgency_filter;
        $types .= 's';
    }
    
    // Exclude user's own requests if viewing incoming only
    if ($view_type === 'incoming') {
        $sql_query .= " AND requester_id != ?";
        $base_conditions .= " AND requester_id != " . intval($user_id);
        $params[] = $user_id;
        $types .= 'i';
    }
    
    $access_granted = true;
    $access_reason = "Division Head of '" . htmlspecialchars($user_db_division) . "' division";
    
} elseif ($user_role === 'admin' || $user_role === 'sadmin') {
    // Get all distinct work types
    $types_sql = "SELECT DISTINCT w_req_type FROM work_request_tbl";
    $types_result = $conn->query($types_sql);
    $available_types = [];
    while($type_row = $types_result->fetch_assoc()) {
        $available_types[] = $type_row['w_req_type'];
    }
    
    // Get all distinct divisions
    $div_sql = "SELECT DISTINCT w_com_division FROM work_request_tbl WHERE w_com_division IS NOT NULL";
    $div_result = $conn->query($div_sql);
    $available_divisions = [];
    while($div_row = $div_result->fetch_assoc()) {
        $available_divisions[] = $div_row['w_com_division'];
    }
    
    // Build base query for admin
    $sql_query = "SELECT * FROM work_request_tbl WHERE 1=1";
    $base_conditions = " WHERE 1=1";
    
    // Filter by work type if specific type selected
    if ($req_type_filter !== 'all' && in_array($req_type_filter, $available_types)) {
        $sql_query .= " AND w_req_type = ?";
        $base_conditions .= " AND w_req_type = '" . $conn->real_escape_string($req_type_filter) . "'";
        $params[] = $req_type_filter;
        $types .= 's';
    }
    
    // Filter by status
    if ($status_filter !== 'all') {
        $sql_query .= " AND w_com_status = ?";
        $base_conditions .= " AND w_com_status = '" . $conn->real_escape_string($status_filter) . "'";
        $params[] = $status_filter;
        $types .= 's';
    }
    
    // Filter by urgency
    if ($urgency_filter !== 'all') {
        $sql_query .= " AND status = ?";
        $base_conditions .= " AND status = '" . $conn->real_escape_string($urgency_filter) . "'";
        $params[] = $urgency_filter;
        $types .= 's';
    }
    
    // Filter by division
    if ($division_filter !== 'all' && in_array($division_filter, $available_divisions)) {
        $sql_query .= " AND w_com_division = ?";
        $base_conditions .= " AND w_com_division = '" . $conn->real_escape_string($division_filter) . "'";
        $params[] = $division_filter;
        $types .= 's';
    }
    
    $access_granted = true;
    $access_reason = "Administrator";
    
} else {
    // REGULAR USER: Can only see their own requests
    $available_types = [];
    $available_divisions = [];
    $access_granted = false;
    $access_reason = "Regular User (No special access)";
}

// Execute query if access granted
if ($access_granted && !empty($sql_query)) {
    // Add ordering
    $sql_query .= " ORDER BY 
        CASE 
            WHEN status = 'very urgent' THEN 1
            WHEN status = 'urgent' THEN 2
            ELSE 3
        END,
        created_at DESC";
    
    // Prepare and execute
    $stmt = $conn->prepare($sql_query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Get type statistics for current view (without filters)
$type_stats = [];
if ($access_granted) {
    // Build type stats query based on user's access level WITHOUT current filters
    $type_stats_sql = "SELECT 
        w_req_type,
        COUNT(*) as total,
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as complete,
        SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as incomplete
        FROM work_request_tbl WHERE 1=1";
    
    // Add base conditions based on role (without current filters)
    if ($routine_role === 'section_head') {
        if ($isITSectionHead) {
            $mapped_info = getMappedDivisionSection($conn, 'ICT');
            $type_stats_sql .= " AND w_req_type = 'ICT' 
                                AND (w_com_division = '" . $conn->real_escape_string($mapped_info['division']) . "' 
                                     OR w_com_section = '" . $conn->real_escape_string($mapped_info['section']) . "')";
        } else {
            $type_stats_sql .= " AND (w_com_section = '" . $conn->real_escape_string($user_db_section) . "' 
                                   OR LOWER(w_com_section) LIKE LOWER('%" . $conn->real_escape_string($user_db_section) . "%'))";
        }
    } elseif ($routine_role === 'division_head') {
        $type_stats_sql .= " AND w_com_division = '" . $conn->real_escape_string($user_db_division) . "'";
    }
    
    $type_stats_sql .= " GROUP BY w_req_type ORDER BY w_req_type";
    
    $type_stats_result = $conn->query($type_stats_sql);
    while($type_row = $type_stats_result->fetch_assoc()) {
        $type_stats[$type_row['w_req_type']] = $type_row;
    }
}

// Get total count for the current view (with all applied filters)
$total_count = count($requests);

// Get COMPLETE statistics for ALL requests (not just filtered ones)
if ($access_granted) {
    // Get complete count for all accessible requests
    $complete_stats_sql = "SELECT 
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as total_complete,
        SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as total_incomplete,
        SUM(CASE WHEN status = 'urgent' AND w_com_status = 'incomplete' THEN 1 ELSE 0 END) as urgent_incomplete,
        SUM(CASE WHEN status = 'very urgent' AND w_com_status = 'incomplete' THEN 1 ELSE 0 END) as very_urgent_incomplete,
        COUNT(*) as total_all
        FROM work_request_tbl" . $base_conditions;
    
    $complete_stats_result = $conn->query($complete_stats_sql);
    $complete_stats = $complete_stats_result->fetch_assoc();
    
    // Use these complete statistics
    $complete_count = $complete_stats['total_complete'] ?? 0;
    $incomplete_count = $complete_stats['total_incomplete'] ?? 0;
    $urgent_count = $complete_stats['urgent_incomplete'] ?? 0;
    $very_urgent_count = $complete_stats['very_urgent_incomplete'] ?? 0;
    $total_all_requests = $complete_stats['total_all'] ?? 0;
} else {
    $complete_count = 0;
    $incomplete_count = 0;
    $urgent_count = 0;
    $very_urgent_count = 0;
    $total_all_requests = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incoming Work Requests</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .sidebar .nav-link {
            color: #495057;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: var(--primary-color);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 12px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
            cursor: pointer;
        }
        
        .stat-card:hover {
            background-color: #f8f9fa;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--secondary-color);
            padding: 15px 12px;
        }
        
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        
        .badge-type {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .badge-complete {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-incomplete {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-normal {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-urgent {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .badge-very-urgent {
            background-color: #721c24;
            color: white;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .action-view {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .action-edit {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .action-delete {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-complete {
            background-color: #d4edda;
            color: #155724;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .user-role-badge {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 20px;
            margin-left: 5px;
        }
        
        .role-section-head {
            background-color: #3498db;
            color: white;
        }
        
        .role-division-head {
            background-color: #9b59b6;
            color: white;
        }
        
        .role-admin {
            background-color: #e74c3c;
            color: white;
        }
        
        .role-user {
            background-color: #95a5a6;
            color: white;
        }
        
        .type-filter-btn {
            padding: 8px 16px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: white;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .type-filter-btn:hover, .type-filter-btn.active {
            border-color: var(--primary-color);
            background-color: var(--primary-color);
            color: white;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                margin-bottom: 20px;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }
        
        /* Mapping info styles */
        .mapping-info {
            background-color: #f0f8ff;
            border-left: 4px solid #3498db;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .mapping-info h6 {
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .mapping-info p {
            margin-bottom: 0;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tools me-2"></i>Work Request System
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 d-none d-md-block">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user_full_name); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 sidebar">
                <div class="pt-3">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-tie fs-4"></i>
                        </div>
                        <h6 class="mt-2 mb-0"><?php echo htmlspecialchars($user_full_name); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($user_role); ?></small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="work_request.php">
                                <i class="fas fa-plus-circle me-2"></i>New Request
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="incoming_work_request.php">
                                <i class="fas fa-inbox me-2"></i>Incoming Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="my_requests.php">
                                <i class="fas fa-list me-2"></i>My Requests
                            </a>
                        </li>
                        <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/reports.php">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/users.php">
                                <i class="fas fa-users me-2"></i>User Management
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 main-content">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1"><i class="fas fa-inbox me-2"></i>Incoming Work Requests</h2>
                                <p class="text-muted mb-0">Track and manage incoming work requests with dynamic mapping</p>
                            </div>
                            <a href="work_request.php" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>New Request
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Your Access Level</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Your Role</div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold"><?php echo ucfirst(str_replace('_', ' ', $routine_role ?: 'user')); ?></span>
                                    <span class="user-role-badge role-<?php echo str_replace('_', '-', $routine_role ?: 'user'); ?> ms-2">
                                        <?php echo ucfirst(str_replace('_', ' ', $routine_role ?: 'user')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Your Division</div>
                                <div class="fw-bold"><?php echo htmlspecialchars($user_division); ?></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Your Section</div>
                                <div class="fw-bold"><?php echo htmlspecialchars($user_section); ?></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Viewing</div>
                                <div class="fw-bold">
                                    <?php 
                                    if ($isITSectionHead) {
                                        echo '<span class="badge bg-info">ICT Requests';
                                        if ($status_filter === 'incomplete') {
                                            echo ' (Incomplete Only)';
                                        } elseif ($status_filter === 'complete') {
                                            echo ' (Complete Only)';
                                        } else {
                                            echo ' (All Status)';
                                        }
                                        echo '</span>';
                                    } elseif ($routine_role === 'section_head') {
                                        echo 'Requests from ' . htmlspecialchars($user_section) . ' section';
                                    } elseif ($routine_role === 'division_head') {
                                        echo 'Requests from ' . htmlspecialchars($user_division) . ' division';
                                    } elseif ($user_role === 'admin' || $user_role === 'sadmin') {
                                        echo 'All Requests (Admin View)';
                                    } else {
                                        echo 'Your own requests only';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            <?php echo $access_reason; ?>
                        </div>
                        
                        <!-- Dynamic Mapping Info -->
                        <?php if ($routine_role === 'section_head' || $routine_role === 'division_head'): ?>
                        <div class="mapping-info mt-3">
                            <h6><i class="fas fa-map-marker-alt me-1"></i>Dynamic Work Type Mapping:</h6>
                            <p class="mb-1">
                                <strong>ICT</strong> → ICT Division / ICT Section<br>
                                <strong>Civil</strong> → MTS Division / Civil Section<br>
                                <strong>Transport</strong> → Transport Division / Transport Section<br>
                                <strong>Electrical</strong> → MTS Division / MTS (Electrical) Section<br>
                                <strong>Mechanical</strong> → MTS Division / MTS (Mechanical) Section
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Special Notice for IT Section Heads -->
                <?php if ($isITSectionHead): ?>
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">Special View Mode</h5>
                            <p class="mb-0">
                                <strong>You are viewing:</strong> 
                                <?php if ($status_filter === 'incomplete'): ?>
                                    Only <span class="badge bg-primary">ICT</span> type requests that are <span class="badge bg-warning">Incomplete</span>
                                <?php elseif ($status_filter === 'complete'): ?>
                                    Only <span class="badge bg-primary">ICT</span> type requests that are <span class="badge bg-success">Complete</span>
                                <?php else: ?>
                                    All <span class="badge bg-primary">ICT</span> type requests (both complete and incomplete)
                                <?php endif; ?>
                                <br>
                                This view is enabled because you are an <strong>IT Division Section Head</strong>.
                                <?php if (!isset($_GET['status'])): ?>
                                    <br><small><i class="fas fa-lightbulb me-1"></i>Default view shows incomplete requests only</small>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Work Request Type Filter Buttons -->
                <?php if (!$isITSectionHead && !empty($type_stats)): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter by Request Type</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <?php
                            // Build URL parameters for filter links
                            $url_params = '';
                            if ($status_filter !== 'all') $url_params .= '&status=' . $status_filter;
                            if ($urgency_filter !== 'all') $url_params .= '&urgency=' . urlencode($urgency_filter);
                            if ($division_filter !== 'all') $url_params .= '&division_filter=' . urlencode($division_filter);
                            ?>
                            
                            <a href="incoming_work_request.php?type=all<?php echo $url_params; ?>" 
                               class="type-filter-btn <?php echo $req_type_filter === 'all' ? 'active' : ''; ?>">
                                All Types (<?php echo $total_all_requests; ?>)
                            </a>
                            <?php foreach ($type_stats as $type => $stats): ?>
                                <a href="incoming_work_request.php?type=<?php echo urlencode($type); ?><?php echo $url_params; ?>" 
                                   class="type-filter-btn <?php echo $req_type_filter === $type ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($type); ?> 
                                    (<?php echo $stats['total']; ?>)
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Filters Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Filters</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Completion Status</label>
                                <select class="form-select" id="statusFilter" onchange="updateFilters()">
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="complete" <?php echo $status_filter === 'complete' ? 'selected' : ''; ?>>Complete</option>
                                    <option value="incomplete" <?php echo $status_filter === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                                </select>
                            </div>
                            
                            <?php if (!$isITSectionHead): ?>
                            <div class="col-md-3">
                                <label class="form-label">Work Type</label>
                                <select class="form-select" id="typeFilter" onchange="updateFilters()">
                                    <option value="all" <?php echo $req_type_filter === 'all' ? 'selected' : ''; ?>>All Types</option>
                                    <option value="ICT" <?php echo $req_type_filter === 'ICT' ? 'selected' : ''; ?>>ICT</option>
                                    <option value="Civil" <?php echo $req_type_filter === 'Civil' ? 'selected' : ''; ?>>Civil</option>
                                    <option value="Transport" <?php echo $req_type_filter === 'Transport' ? 'selected' : ''; ?>>Transport</option>
                                    <option value="Electrical" <?php echo $req_type_filter === 'Electrical' ? 'selected' : ''; ?>>Electrical</option>
                                    <option value="Mechanical" <?php echo $req_type_filter === 'Mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="col-md-3">
                                <label class="form-label">Urgency Level</label>
                                <select class="form-select" id="urgencyFilter" onchange="updateFilters()">
                                    <option value="all" <?php echo $urgency_filter === 'all' ? 'selected' : ''; ?>>All Urgency</option>
                                    <option value="normal" <?php echo $urgency_filter === 'normal' ? 'selected' : ''; ?>>Normal</option>
                                    <option value="urgent" <?php echo $urgency_filter === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                                    <option value="very urgent" <?php echo $urgency_filter === 'very urgent' ? 'selected' : ''; ?>>Very Urgent</option>
                                </select>
                            </div>
                            
                            <?php if (($user_role === 'admin' || $user_role === 'sadmin') && !empty($available_divisions)): ?>
                            <div class="col-md-3">
                                <label class="form-label">Assigned Division</label>
                                <select class="form-select" id="divisionFilter" onchange="updateFilters()">
                                    <option value="all" <?php echo $division_filter === 'all' ? 'selected' : ''; ?>>All Divisions</option>
                                    <?php foreach ($available_divisions as $division): ?>
                                        <option value="<?php echo htmlspecialchars($division); ?>" <?php echo $division_filter === $division ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($division); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <button onclick="clearFilters()" class="btn btn-outline-danger">
                                <i class="fas fa-times me-1"></i>Clear All Filters
                            </button>
                            <div class="text-muted small">
                                Showing <?php echo count($requests); ?> request(s) with current filters
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-0">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card" onclick="applyFilter('status', 'all')">
                            <div class="stat-value text-primary"><?php echo $total_all_requests; ?></div>
                            <div class="stat-label">Total Accessible Requests</div>
                            <small class="text-muted">Click to view all</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card" onclick="applyFilter('status', 'complete')">
                            <div class="stat-value text-success"><?php echo $complete_count; ?></div>
                            <div class="stat-label">Complete Requests</div>
                            <small class="text-muted">Click to filter</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card" onclick="applyFilter('status', 'incomplete')">
                            <div class="stat-value text-warning"><?php echo $incomplete_count; ?></div>
                            <div class="stat-label">Incomplete Requests</div>
                            <small class="text-muted">Click to filter</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card" onclick="applyFilter('urgency', 'very urgent')">
                            <div class="stat-value text-danger"><?php echo $very_urgent_count; ?></div>
                            <div class="stat-label">Very Urgent (Incomplete)</div>
                            <small class="text-muted">Click to filter</small>
                        </div>
                    </div>
                </div>
                
                <!-- Requests Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Work Requests</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($requests)): ?>
                            <div class="empty-state py-5">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox fa-4x"></i>
                                </div>
                                <h3 class="mt-4 mb-3">No work requests found</h3>
                                <p class="text-muted mb-4">
                                    <?php 
                                    if ($isITSectionHead) {
                                        if ($status_filter === 'incomplete') {
                                            echo 'No incomplete ICT requests found in your section.';
                                        } elseif ($status_filter === 'complete') {
                                            echo 'No complete ICT requests found in your section.';
                                        } else {
                                            echo 'No ICT requests found in your section.';
                                        }
                                    } elseif ($routine_role === 'section_head') {
                                        echo 'No requests found in your section (' . htmlspecialchars($user_section) . ') with the current filters.';
                                    } elseif ($routine_role === 'division_head') {
                                        echo 'No requests found in your division (' . htmlspecialchars($user_division) . ') with the current filters.';
                                    } elseif ($user_role === 'admin' || $user_role === 'sadmin') {
                                        echo 'No requests found with the current filters.';
                                    } else {
                                        echo 'You have no access to view incoming requests.';
                                    }
                                    ?>
                                </p>
                                <?php if (!$isITSectionHead || $status_filter !== 'incomplete'): ?>
                                    <button onclick="clearFilters()" class="btn btn-secondary">
                                        <i class="fas fa-filter me-1"></i>Clear Filters
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Assigned Division</th>
                                            <th>Status</th>
                                            <th>Urgency</th>
                                            <th>Requester</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <?php //if($request['remarks'] || $request['w_com_div_remarks']){
                                                ?>
                                                <th>Comments</th>
                                                <?php
                                                //}
                                                 ?>
                                            
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $request): ?>
                                            <tr>
                                                <td>
                                                    <strong class="d-block">WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                                    <small class="text-muted">#<?php echo $request['id']; ?></small>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($request['date'])); ?></td>
                                                <td>
                                                    <span class="badge badge-type">
                                                        <?php echo $request['w_req_type']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($request['w_location']); ?>">
                                                        <?php echo htmlspecialchars($request['w_location']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?php echo htmlspecialchars($request['w_com_division']); ?></div>
                                                    <small class="text-muted">Sec: <?php echo htmlspecialchars($request['w_com_section']); ?></small>
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
                                                    <div class="fw-medium"><?php echo htmlspecialchars($request['full_name']); ?></div>
                                                    <small class="text-muted">ID: <?php echo htmlspecialchars($request['emp_id'] ?? 'N/A'); ?></small>
                                                </td>
                                                <td>
                                                    <div><?php echo date('d/m/Y', strtotime($request['created_at'])); ?></div>
                                                    <small class="text-muted">
                                                        <?php echo date('h:i A', strtotime($request['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div><?php echo date('d/m/Y', strtotime($request['updated_at'])); ?></div>
                                                    <small class="text-muted">
                                                        <?php echo date('h:i A', strtotime($request['updated_at'])); ?>
                                                    </small>
                                                </td>

                                                  <td>
                                                    <!-- <div><?php echo date('d/m/Y', strtotime($request['updated_at'])); ?></div> -->
                                                    <small class="text-muted">Requester:
                                                        <?php echo htmlspecialchars($request['remarks']);?>
                                                    </small>
                                                    <small class="text-muted">Work Completion Division:
                                                        <?php echo htmlspecialchars($request['w_com_div_remarks']);?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="view_w_request.php?id=<?php echo $request['id']; ?>" 
                                                           class="action-btn action-view" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        <?php if ($user_role === 'admin' || $user_role === 'sadmin' || 
                                                                 $request['requester_id'] == $user_id || 
                                                                 $routine_role === 'section_head' || 
                                                                 $routine_role === 'division_head'): ?>
                                                            <a href="add_comments.php?id=<?php echo $request['id']; ?>" 
                                                               class="action-btn action-edit" title="Add Comments">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($request['w_com_status'] === 'incomplete' && 
                                                                 ($routine_role === 'section_head' || $routine_role === 'division_head' || 
                                                                  $user_role === 'admin' || $user_role === 'sadmin')): ?>
                                                            <a href="complete_request.php?id=<?php echo $request['id']; ?>" 
                                                               class="action-btn action-complete" title="Mark as Complete"
                                                               onclick="return confirm('Mark this request as complete?')">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($request['requester_id'] == $user_id || $user_role === 'admin' || $user_role === 'sadmin'): ?>
                                                            <a href="delete_request.php?id=<?php echo $request['id']; ?>" 
                                                               class="action-btn action-delete" title="Delete"
                                                               onclick="return confirm('Are you sure you want to delete this request?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function updateFilters() {
            const status = document.getElementById('statusFilter').value;
            const type = document.getElementById('typeFilter') ? document.getElementById('typeFilter').value : 'all';
            const urgency = document.getElementById('urgencyFilter').value;
            const division = document.getElementById('divisionFilter') ? document.getElementById('divisionFilter').value : 'all';
            
            let url = 'incoming_work_request.php?';
            
            if (status !== 'all') url += 'status=' + status + '&';
            if (type !== 'all') url += 'type=' + type + '&';
            if (urgency !== 'all') url += 'urgency=' + encodeURIComponent(urgency) + '&';
            if (division !== 'all') url += 'division_filter=' + encodeURIComponent(division) + '&';
            
            // Remove trailing & or ?
            url = url.replace(/[&?]$/, '');
            
            window.location.href = url;
        }
        
        function applyFilter(filterType, value) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set(filterType, value);
            window.location.href = currentUrl.toString();
        }
        
        function clearFilters() {
            window.location.href = 'incoming_work_request.php';
        }
        
        // Auto-refresh every 60 seconds if there are incomplete urgent requests
        <?php if ($incomplete_count > 0): ?>
        setTimeout(function() {
            window.location.reload();
        }, 60000);
        <?php endif; ?>
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + F for focus on filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('statusFilter').focus();
            }
            
            // Ctrl + N for new request
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = 'work_request.php';
            }
        });
    </script>
</body>
</html>