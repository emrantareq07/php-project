<?php
// incoming_work_request.php - DYNAMIC VERSION
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
$status_filter = $_GET['status'] ?? 'incomplete'; // Default to incomplete
$division_filter = $_GET['division_filter'] ?? 'all';
$urgency_filter = $_GET['urgency'] ?? 'all';
$view_type = $_GET['view'] ?? 'my_area'; // 'my_area' or 'all'

// Initialize variables
$requests = [];
$access_granted = false;
$access_reason = '';
$sql_query = '';
$where_conditions = [];
$params = [];
$types = '';

// DYNAMIC ACCESS LOGIC BASED ON ROUTINE_ROLE
if ($routine_role === 'section_head') {
    // SECTION HEAD: Can see requests for their section based on work type
    
    // Get all distinct work types for this section head
    $types_sql = "SELECT DISTINCT w_req_type FROM work_request_tbl 
                  WHERE LOWER(w_com_section) = LOWER(?)";
    $types_stmt = $conn->prepare($types_sql);
    $types_stmt->bind_param("s", $user_db_section);
    $types_stmt->execute();
    $types_result = $types_stmt->get_result();
    $available_types = [];
    while($type_row = $types_result->fetch_assoc()) {
        $available_types[] = $type_row['w_req_type'];
    }
    $types_stmt->close();
    
    // Build base query for section head
    $sql_query = "SELECT * FROM work_request_tbl WHERE 1=1";
    
    // Add section condition
    $sql_query .= " AND LOWER(w_com_section) = LOWER(?)";
    $params[] = $user_db_section;
    $types .= 's';
    
    // Filter by work type if specific type selected
    if ($req_type_filter !== 'all' && in_array($req_type_filter, $available_types)) {
        $sql_query .= " AND w_req_type = ?";
        $params[] = $req_type_filter;
        $types .= 's';
    }
    
    // Filter by status
    if ($status_filter !== 'all') {
        $sql_query .= " AND w_com_status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }
    
    // Filter by urgency
    if ($urgency_filter !== 'all') {
        $sql_query .= " AND status = ?";
        $params[] = $urgency_filter;
        $types .= 's';
    }
    
    // Exclude user's own requests if viewing incoming only
    if ($view_type === 'incoming') {
        $sql_query .= " AND requester_id != ?";
        $params[] = $user_id;
        $types .= 'i';
    }
    
    $access_granted = true;
    $access_reason = "Section Head of '" . htmlspecialchars($user_db_section) . "' section";
    
} elseif ($routine_role === 'division_head') {
    // DIVISION HEAD: Can see all requests for their division
    
    // Get all distinct work types for this division
    $types_sql = "SELECT DISTINCT w_req_type FROM work_request_tbl 
                  WHERE LOWER(w_com_division) = LOWER(?)";
    $types_stmt = $conn->prepare($types_sql);
    $types_stmt->bind_param("s", $user_db_division);
    $types_stmt->execute();
    $types_result = $types_stmt->get_result();
    $available_types = [];
    while($type_row = $types_result->fetch_assoc()) {
        $available_types[] = $type_row['w_req_type'];
    }
    $types_stmt->close();
    
    // Build base query for division head
    $sql_query = "SELECT * FROM work_request_tbl WHERE 1=1";
    
    // Add division condition
    $sql_query .= " AND LOWER(w_com_division) = LOWER(?)";
    $params[] = $user_db_division;
    $types .= 's';
    
    // Filter by work type if specific type selected
    if ($req_type_filter !== 'all' && in_array($req_type_filter, $available_types)) {
        $sql_query .= " AND w_req_type = ?";
        $params[] = $req_type_filter;
        $types .= 's';
    }
    
    // Filter by status
    if ($status_filter !== 'all') {
        $sql_query .= " AND w_com_status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }
    
    // Filter by urgency
    if ($urgency_filter !== 'all') {
        $sql_query .= " AND status = ?";
        $params[] = $urgency_filter;
        $types .= 's';
    }
    
    // Exclude user's own requests if viewing incoming only
    if ($view_type === 'incoming') {
        $sql_query .= " AND requester_id != ?";
        $params[] = $user_id;
        $types .= 'i';
    }
    
    $access_granted = true;
    $access_reason = "Division Head of '" . htmlspecialchars($user_db_division) . "' division";
    
} elseif ($user_role === 'admin' || $user_role === 'sadmin') {
    // ADMIN: Can see all requests
    
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
    
    // Filter by work type if specific type selected
    if ($req_type_filter !== 'all' && in_array($req_type_filter, ['ICT', 'Civil', 'Transport', 'Electrical'])) {
        $sql_query .= " AND w_req_type = ?";
        $params[] = $req_type_filter;
        $types .= 's';
    }
    
    // Filter by status
    if ($status_filter !== 'all') {
        $sql_query .= " AND w_com_status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }
    
    // Filter by urgency
    if ($urgency_filter !== 'all') {
        $sql_query .= " AND status = ?";
        $params[] = $urgency_filter;
        $types .= 's';
    }
    
    // Filter by division
    if ($division_filter !== 'all' && in_array($division_filter, $available_divisions)) {
        $sql_query .= " AND w_com_division = ?";
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

// Get statistics for current view
$stats = [];
if ($access_granted) {
    // Remove ordering for count query
    $count_sql = preg_replace('/ORDER BY.*$/', '', $sql_query);
    $count_sql = "SELECT COUNT(*) as total_count FROM (" . $count_sql . ") as temp";
    
    if (!empty($params)) {
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param($types, ...$params);
        $count_stmt->execute();
        $stats_result = $count_stmt->get_result()->fetch_assoc();
        $stats['total'] = $stats_result['total_count'] ?? 0;
        $count_stmt->close();
    }
}

// Get counts by type for current area
$type_counts = [];
if ($access_granted) {
    $count_by_type_sql = "SELECT 
        w_req_type,
        COUNT(*) as count,
        SUM(CASE WHEN w_com_status = 'complete' THEN 1 ELSE 0 END) as complete_count,
        SUM(CASE WHEN w_com_status = 'incomplete' THEN 1 ELSE 0 END) as incomplete_count
        FROM work_request_tbl WHERE 1=1";
    
    // Add conditions based on role
    if ($routine_role === 'section_head') {
        $count_by_type_sql .= " AND LOWER(w_com_section) = LOWER('" . $conn->real_escape_string($user_db_section) . "')";
    } elseif ($routine_role === 'division_head') {
        $count_by_type_sql .= " AND LOWER(w_com_division) = LOWER('" . $conn->real_escape_string($user_db_division) . "')";
    }
    
    $count_by_type_sql .= " GROUP BY w_req_type ORDER BY w_req_type";
    
    $type_counts_result = $conn->query($count_by_type_sql);
    while($type_row = $type_counts_result->fetch_assoc()) {
        $type_counts[$type_row['w_req_type']] = $type_row;
    }
}

$conn->close();
require_once 'header_w_req.php';
?>

<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>📊 Dynamic Work Requests Dashboard</h1>
                    <p class="header-subtitle">View requests based on your role and permissions</p>
                </div>
                <div>
                    <a href="dashboard.php" style="color: white; text-decoration: none; padding: 10px 20px; background: rgba(255,255,255,0.2); border-radius: 6px; display: inline-flex; align-items: center; gap: 8px;">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <!-- Information Panel -->
            <div class="info-card">
                <div class="info-header">
                    <h2>👤 Your Access Profile</h2>
                    <span class="role-badge">
                        <?php 
                        if ($routine_role) {
                            echo strtoupper(str_replace('_', ' ', $routine_role));
                        } else {
                            echo 'REGULAR USER';
                        }
                        ?>
                    </span>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">User</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_full_name); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Division</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_db_division); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Section</div>
                        <div class="info-value"><?php echo htmlspecialchars($user_db_section); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Access Level</div>
                        <div class="info-value"><?php echo $access_reason; ?></div>
                    </div>
                </div>
            </div>

            <?php if ($access_granted): ?>
                
                <!-- Work Type Filter Badges -->
                <div class="type-badges">
                    <a href="incoming_w_request.php?type=all&status=<?php echo $status_filter; ?>&urgency=<?php echo $urgency_filter; ?>&view=<?php echo $view_type; ?>" 
                       class="type-badge <?php echo $req_type_filter === 'all' ? 'active' : ''; ?>">
                        <span class="type-icon">📦</span>
                        <span>All Types</span>
                        <span class="type-count"><?php echo $stats['total'] ?? 0; ?></span>
                    </a>
                    
                    <?php 
                    $type_icons = [
                        'ICT' => '💻',
                        'Civil' => '🏗️',
                        'Transport' => '🚚',
                        'Electrical' => '⚡'
                    ];
                    
                    foreach($type_counts as $type => $count_data): 
                        $type_lower = strtolower($type);
                    ?>
                        <a href="incoming_w_request.php?type=<?php echo $type; ?>&status=<?php echo $status_filter; ?>&urgency=<?php echo $urgency_filter; ?>&view=<?php echo $view_type; ?>" 
                           class="type-badge <?php echo $req_type_filter === $type ? 'active' : ''; ?>">
                            <span class="type-icon"><?php echo $type_icons[$type] ?? '📄'; ?></span>
                            <span><?php echo $type; ?></span>
                            <span class="type-count">
                                <?php echo $status_filter === 'all' ? $count_data['count'] : 
                                       ($status_filter === 'complete' ? $count_data['complete_count'] : $count_data['incomplete_count']); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-group">
                        <label class="filter-label">Work Request Type</label>
                        <select onchange="window.location.href='incoming_w_request.php?type='+this.value+'&status=<?php echo $status_filter; ?>&urgency=<?php echo $urgency_filter; ?>&view=<?php echo $view_type; ?>'">
                            <option value="all" <?php echo $req_type_filter === 'all' ? 'selected' : ''; ?>>All Types</option>
                            <?php foreach($type_counts as $type => $count_data): ?>
                                <option value="<?php echo $type; ?>" <?php echo $req_type_filter === $type ? 'selected' : ''; ?>>
                                    <?php echo $type; ?> (<?php echo $count_data['count']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Completion Status</label>
                        <select onchange="window.location.href='incoming_w_request.php?type=<?php echo $req_type_filter; ?>&status='+this.value+'&urgency=<?php echo $urgency_filter; ?>&view=<?php echo $view_type; ?>'">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="complete" <?php echo $status_filter === 'complete' ? 'selected' : ''; ?>>Complete</option>
                            <option value="incomplete" <?php echo $status_filter === 'incomplete' ? 'selected' : ''; ?>>Incomplete</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Urgency Level</label>
                        <select onchange="window.location.href='incoming_w_request.php?type=<?php echo $req_type_filter; ?>&status=<?php echo $status_filter; ?>&urgency='+this.value+'&view=<?php echo $view_type; ?>'">
                            <option value="all" <?php echo $urgency_filter === 'all' ? 'selected' : ''; ?>>All Urgency</option>
                            <option value="normal" <?php echo $urgency_filter === 'normal' ? 'selected' : ''; ?>>Normal</option>
                            <option value="urgent" <?php echo $urgency_filter === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                            <option value="very urgent" <?php echo $urgency_filter === 'very urgent' ? 'selected' : ''; ?>>Very Urgent</option>
                        </select>
                    </div>
                    
                    <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                        <div class="filter-group">
                            <label class="filter-label">Division Filter</label>
                            <select onchange="window.location.href='incoming_w_request.php?type=<?php echo $req_type_filter; ?>&status=<?php echo $status_filter; ?>&urgency=<?php echo $urgency_filter; ?>&division_filter='+this.value">
                                <option value="all">All Divisions</option>
                                <?php foreach($available_divisions as $division): ?>
                                    <option value="<?php echo $division; ?>" <?php echo $division_filter === $division ? 'selected' : ''; ?>>
                                        <?php echo $division; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="view-toggle">
                        <a href="incoming_w_request.php?type=<?php echo $req_type_filter; ?>&status=<?php echo $status_filter; ?>&urgency=<?php echo $urgency_filter; ?>&view=my_area" 
                           class="view-btn <?php echo $view_type === 'my_area' ? 'active' : ''; ?>">
                            My Area Requests
                        </a>
                        <a href="incoming_w_request.php?type=<?php echo $req_type_filter; ?>&status=<?php echo $status_filter; ?>&urgency=<?php echo $urgency_filter; ?>&view=incoming" 
                           class="view-btn <?php echo $view_type === 'incoming' ? 'active' : ''; ?>">
                            Incoming Only
                        </a>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
                        <div class="stat-label">Total Requests</div>
                    </div>
                    
                    <?php
                    // Calculate statistics
                    $complete_count = 0;
                    $incomplete_count = 0;
                    $urgent_count = 0;
                    $very_urgent_count = 0;
                    
                    foreach($requests as $request) {
                        if ($request['w_com_status'] === 'complete') $complete_count++;
                        if ($request['w_com_status'] === 'incomplete') $incomplete_count++;
                        if ($request['status'] === 'urgent') $urgent_count++;
                        if ($request['status'] === 'very urgent') $very_urgent_count++;
                    }
                    ?>
                    
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $complete_count; ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $incomplete_count; ?></div>
                        <div class="stat-label">Incomplete</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $urgent_count + $very_urgent_count; ?></div>
                        <div class="stat-label">Urgent + Very Urgent</div>
                    </div>
                </div>
                
                <!-- Requests Table -->
                <div class="table-container">
                    <?php if (empty($requests)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <h3>No Work Requests Found</h3>
                            <p>
                                No <?php echo $req_type_filter !== 'all' ? $req_type_filter : ''; ?> 
                                <?php echo $status_filter !== 'all' ? $status_filter : ''; ?> 
                                requests found in your area.
                            </p>
                            <p><small>Try changing your filters or check back later.</small></p>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Urgency</th>
                                    <th>Concerned Division</th>
                                    <th>Concerned Section</th>
                                    <th>Location</th>
                                    <th>Requester</th>
                                    <th>Requester Division</th>
                                    <th>Created</th>
                                    <th>Completed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($requests as $request): 
                                    // Check if request is new (within 24 hours)
                                    $is_new = (strtotime($request['created_at']) > strtotime('-24 hours'));
                                ?>
                                <tr class="<?php echo $is_new ? 'new-request' : ''; ?>">
                                    <td><strong>WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($request['w_req_type']); ?>">
                                            <?php echo $request['w_req_type']; ?>
                                        </span>
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
                                    <td><?php echo htmlspecialchars($request['w_com_division']); ?></td>
                                    <td><?php echo htmlspecialchars($request['w_com_section']); ?></td>
                                    <td title="<?php echo htmlspecialchars($request['w_location']); ?>">
                                        <?php echo htmlspecialchars(substr($request['w_location'], 0, 40)); ?>
                                        <?php echo strlen($request['w_location']) > 40 ? '...' : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($request['full_name']); ?>
                                        <div style="font-size: 11px; color: #666; margin-top: 3px;">
                                            <?php echo htmlspecialchars($request['emp_id'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($request['division']); ?></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($request['created_at'])); ?>
                                        <div style="font-size: 11px; color: #666; margin-top: 3px;">
                                            <?php echo date('h:i A', strtotime($request['created_at'])); ?>
                                        </div>
                                    </td>
                                     <td>
                                        <?php echo date('d/m/Y', strtotime($request['updated_at'])); ?>
                                        <div style="font-size: 11px; color: #666; margin-top: 3px;">
                                            <?php echo date('h:i A', strtotime($request['updated_at'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <a href="view_w_request.php?id=<?php echo $request['id']; ?>" 
                                               style="padding: 6px 12px; background: #3498db; color: white; border-radius: 4px; text-decoration: none; font-size: 12px;">
                                                👁️
                                            </a>
                                            <!-- <a href="update_status.php?id=<?php echo $request['id']; ?>" 
                                               style="padding: 6px 12px; background: #f39c12; color: white; border-radius: 4px; text-decoration: none; font-size: 12px;">
                                                🔄
                                            </a> -->
                                            <?php if ($request['w_com_status'] === 'incomplete' && 
                                                     ($routine_role === 'section_head' || $routine_role === 'division_head' || 
                                                      $user_role === 'admin' || $user_role === 'sadmin')): ?>
                                                <a href="complete_request.php?id=<?php echo $request['id']; ?>" 
                                                   style="padding: 6px 12px; background: #27ae60; color: white; border-radius: 4px; text-decoration: none; font-size: 12px;"
                                                   onclick="return confirm('Mark this request as complete?')">
                                                    ✅
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
                
                <!-- Debug Information (Optional - can be removed in production) -->
                <?php if (isset($_GET['debug']) && $_GET['debug'] === 'true'): ?>
                <div class="debug-info">
                    <div class="debug-title">SQL Query Debug Information:</div>
                    <div class="query-display">
                        <?php
                        // Highlight SQL keywords
                        $highlighted_sql = htmlspecialchars($sql_query);
                        $keywords = ['SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'ORDER BY', 'GROUP BY', 'LIMIT', 'IN', 'LIKE', 'LOWER', 'UPPER'];
                        foreach ($keywords as $keyword) {
                            $highlighted_sql = preg_replace("/\b$keyword\b/i", "<span class='sql-keyword'>$keyword</span>", $highlighted_sql);
                        }
                        echo $highlighted_sql;
                        ?>
                    </div>
                    <div><strong>Parameters:</strong> <?php echo json_encode($params); ?></div>
                    <div><strong>Types:</strong> <?php echo $types; ?></div>
                    <div><strong>Total Requests:</strong> <?php echo count($requests); ?></div>
                    <div><strong>Available Types:</strong> <?php echo implode(', ', array_keys($type_counts)); ?></div>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- Access Denied -->
                <div class="access-denied">
                    <div class="access-denied-icon">⛔</div>
                    <h3>Access Restricted</h3>
                    <p>You need to be a <strong>Section Head</strong> or <strong>Division Head</strong> to view incoming work requests.</p>
                    <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 8px; max-width: 600px; margin-left: auto; margin-right: auto;">
                        <p><strong>Your Current Status:</strong></p>
                        <ul style="text-align: left; margin-top: 10px;">
                            <li>Role: <?php echo htmlspecialchars($routine_role ?: 'None (Regular User)'); ?></li>
                            <li>Division: <?php echo htmlspecialchars($user_db_division); ?></li>
                            <li>Section: <?php echo htmlspecialchars($user_db_section); ?></li>
                            <li>System Role: <?php echo htmlspecialchars($user_role); ?></li>
                        </ul>
                        <p style="margin-top: 15px;">
                            <small>Contact your administrator if you should have access to incoming work requests.</small>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-refresh every 60 seconds if there are urgent incomplete requests
        <?php 
        $urgent_incomplete = array_filter($requests, function($r) {
            return $r['w_com_status'] === 'incomplete' && 
                   ($r['status'] === 'urgent' || $r['status'] === 'very urgent');
        });
        
        if (count($urgent_incomplete) > 0): ?>
        setTimeout(function() {
            window.location.reload();
        }, 60000);
        <?php endif; ?>
        
        // Quick filter
        function quickFilter(type, status) {
            const url = new URL(window.location.href);
            url.searchParams.set('type', type);
            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>