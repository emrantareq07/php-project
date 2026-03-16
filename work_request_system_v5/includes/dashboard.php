<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Get user data from session
$user_id = $_SESSION['user_id'];
$emp_id = $_SESSION['emp_id'];
$full_name = $_SESSION['full_name'];
$role = $_SESSION['role'];
$emp_type = $_SESSION['emp_type'];
// Define work request types
$work_types = ['ICT', 'Civil', 'Transport', 'Electrical', 'Mechanical'];

// Fetch complete user data from database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$_SESSION['designation'] = $user['designation'];
$_SESSION['division']  = $user['division'];
$_SESSION['section']  = $user['section'];
$_SESSION['emp_id']  = $user['emp_id'];
$_SESSION['emp_type']  = $user['emp_type'];
$routine_role = $user['routine_role'];

$stmt->close();

// Initialize arrays for work type counts
$work_type_counts = [];
$total_incomplete_requests = 0;

// Get counts for section heads and division heads only
if ($routine_role === 'section_head' || $routine_role === 'division_head') {
    
    // Get the user's division and section
    $user_division = $user['division'];
    $user_section = $user['section'];
    
    // Query to count incomplete requests by work type for the user's division/section
    if ($routine_role === 'section_head') {
        // Section heads see requests for their specific section
        $sql_count = "SELECT w_req_type, COUNT(*) as count 
                     FROM work_request_tbl 
                     WHERE w_com_status = 'incomplete' 
                     AND w_com_division = ? 
                     AND w_com_section = ?
                     GROUP BY w_req_type";
        $stmt = $conn->prepare($sql_count);
        $stmt->bind_param("ss", $user_division, $user_section);
    } else {
        // Division heads see requests for their entire division
        $sql_count = "SELECT w_req_type, COUNT(*) as count 
                     FROM work_request_tbl 
                     WHERE w_com_status = 'incomplete' 
                     AND w_com_division = ?
                     GROUP BY w_req_type";
        $stmt = $conn->prepare($sql_count);
        $stmt->bind_param("s", $user_division);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Initialize all work types with 0 count
    foreach ($work_types as $type) {
        $work_type_counts[$type] = 0;
    }
    
    // Fill with actual counts from database
    while ($row = $result->fetch_assoc()) {
        $work_type = $row['w_req_type'];
        if (in_array($work_type, $work_types)) {
            $work_type_counts[$work_type] = $row['count'];
            $total_incomplete_requests += $row['count'];
        }
    }
    $stmt->close();
    
    // Get total incomplete count for notification badge
    $sql_total = "SELECT COUNT(*) as total 
                 FROM work_request_tbl 
                 WHERE w_com_status = 'incomplete'";
    
    if ($routine_role === 'section_head') {
        $sql_total .= " AND w_com_division = ? AND w_com_section = ?";
        $stmt = $conn->prepare($sql_total);
        $stmt->bind_param("ss", $user_division, $user_section);
    } else {
        $sql_total .= " AND w_com_division = ?";
        $stmt = $conn->prepare($sql_total);
        $stmt->bind_param("s", $user_division);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $total_row = $result->fetch_assoc();
    $notification_count = $total_row['total'] ?? 0;
    $stmt->close();
} else {
    // For non-section/division heads, set notification count to 0
    $notification_count = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Work Request System</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #3a0ca3;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --gray-color: #6b7280;
            --border-color: #e5e7eb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background-color: #f8fafc;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .user-profile {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-role {
            font-size: 0.875rem;
            opacity: 0.9;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .nav-item {
            padding: 0;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }
        
        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 0;
            transition: all 0.3s ease;
        }
        
        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .top-navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }
        
        .page-title p {
            color: var(--gray-color);
            margin: 0.15rem 0 0;
            font-size: 0.875rem;
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-btn {
            background: none;
            border: none;
            color: var(--gray-color);
            font-size: 1.25rem;
            position: relative;
            padding: 0.5rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .notification-btn:hover {
            color: var(--primary-color);
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger-color);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-btn {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        
        /* Content Container */
        .content-container {
            padding: 2rem;
        }
        
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 16px;
            padding: 2.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .welcome-card h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            position: relative;
            z-index: 1;
        }
        
        .welcome-card p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            position: relative;
            z-index: 1;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-card:nth-child(1) .stat-icon { background: #dbeafe; color: #1d4ed8; }
        .stat-card:nth-child(2) .stat-icon { background: #dcfce7; color: #047857; }
        .stat-card:nth-child(3) .stat-icon { background: #fef3c7; color: #b45309; }
        .stat-card:nth-child(4) .stat-icon { background: #e0e7ff; color: #3730a3; }
        
        .stat-title {
            font-size: 0.875rem;
            color: var(--gray-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.3rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        /* Work Type Counts Section */
        .work-type-counts {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }
        
        .work-type-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .work-type-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .work-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .work-type-card {
            background: var(--primary-light);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .work-type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        
        .work-type-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: var(--primary-color);
        }
        
        .work-type-name {
            font-size: 0.9rem;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .work-type-count {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .work-type-count.zero {
            color: var(--gray-color);
            opacity: 0.6;
        }
        
        .total-count-badge {
            background: var(--danger-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        /* Profile Info Card */
        .profile-info-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }
        
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .profile-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .edit-profile-btn {
            background: var(--primary-light);
            color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .edit-profile-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .info-group {
            margin-bottom: 1.5rem;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-label i {
            width: 20px;
        }
        
        .info-value {
            font-size: 1.125rem;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .badge-primary {
            background: var(--primary-light);
            color: var(--primary-color);
        }
        
        .badge-success {
            background: #d1fae5;
            color: #047857;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #b45309;
        }
        
        /* Quick Actions */
        .quick-actions-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }
        
        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
        }
        
        .actions-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .view-all-btn {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .action-card {
            background: var(--primary-light);
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: var(--dark-color);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .action-card:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .action-icon {
            font-size: 2rem;
            color: var(--primary-color);
        }
        
        .action-card:hover .action-icon {
            color: white;
        }
        
        .action-title {
            font-weight: 600;
            font-size: 1rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar-header .logo-text,
            .user-profile .user-name,
            .user-profile .user-role,
            .nav-link span {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .welcome-card h2 {
                font-size: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .top-navbar {
                padding: 1rem;
            }
            
            .content-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="logo-text">Work Request System</span>
            </div>
        </div>
        
        <div class="user-profile">
            <div class="avatar">
                <?php echo substr($full_name, 0, 1); ?>
            </div>
            <div class="user-name"><?php echo htmlspecialchars($full_name); ?></div>
            <div class="user-role"><?php echo ucfirst($role); ?></div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <?php if($role === 'user'): ?>
                <div class="nav-item">
                    <a href="work_request.php" class="nav-link">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Work Request</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="my_work_requests_list.php" class="nav-link">
                        <i class="fas fa-list-check"></i>
                        <span>My Requests</span>
                    </a>
                </div>
                   <div class="nav-item">
                    <a href="my_fc_sheet.php" class="nav-link">
                        <i class="fas fa-list-check"></i>
                        <span>My FC</span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if($role === 'admin' || $role === 'sadmin'): ?>
                <div class="nav-item">
                    <a href="user_management.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="admin_requests.php" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <span>All Requests</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reports.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </div>

                  <div class="nav-item">
                    <a href="fc_dashboard.php" class="nav-link">
                        <i class="fas fa-list-check"></i>
                        <span>FC Dashboard</span>
                    </a>
                </div>
            <?php endif; ?>
            
               <?php
// Only show the link if user has a routine_role (not empty)
if (!empty($user['routine_role'])) {
    if (
        ($user['division'] === "Administration Division" || $user['section'] === "Transport") &&
        ($user['routine_role'] === 'section_head' || $user['routine_role'] === 'division_head')
    ) {
        // Transport-specific page
        ?>
        <div class="nav-item">
            <a href="incoming_w_req_transport.php" class="nav-link">
                <i class="fas fa-inbox"></i>
                <span>Incoming Requests</span>
            </a>
        </div>
        <?php
    } else if($user['routine_role'] === 'section_head' || $user['routine_role'] === 'division_head') {
        // General page
        ?>
        <div class="nav-item">
            <a href="incoming_work_request.php" class="nav-link">
                <i class="fas fa-inbox"></i>
                <span>Incoming Requests</span>
            </a>
        </div>
        <?php
    }
}
// If routine_role is empty, nothing will be displayed
?>
            
            <div class="nav-item">
                <a href="change_password.php" class="nav-link">
                    <i class="fas fa-key"></i>
                    <span>Change Password</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="update_profile.php" class="nav-link">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="top-navbar-content">
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <p>Welcome back, <?php echo htmlspecialchars($full_name); ?>!</p>
                </div>
                
                <div class="nav-actions">
                    <?php if($routine_role === 'section_head' || $routine_role === 'division_head'): ?>
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            <?php if($notification_count > 0): ?>
                                <span class="notification-badge"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                    <a href="logout.php" class="logout-btn" style="text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Content Container -->
        <div class="content-container">           
            <!-- Welcome Card -->
            <div class="welcome-card">
                <h2>Welcome to Work Request System!</h2>
                <p>
                    You're logged in as 
                    <?php 
                    // Choose badge color based on role
                    $roleClass = '';
                    switch ($role) {
                        case 'admin':
                            $roleClass = 'badge bg-success';
                            break;
                        case 'sadmin':
                            $roleClass = 'badge bg-danger';
                            break;
                        case 'user':
                        default:
                            $roleClass = 'badge bg-warning';
                            break;
                    }
                    ?>
                    <span class="<?php echo $roleClass; ?>">
                        <?php echo ucfirst($role); ?>
                    </span>
                    <span class="text-white badge bg-info">
                        <?php echo ucfirst($_SESSION['emp_type'] ); ?>
                    </span>
                    <span class="text-white badge bg-primary">
                        <?php 
                            if ($routine_role === 'section_head') {
                                echo 'Section Head';
                            } elseif ($routine_role === 'division_head') {
                                echo 'Division Head';
                            } else {
                                // show nothing
                                echo 'Employee';
                            }
                        ?>
                    </span>
                    <?php if($role === 'admin' || $role === 'sadmin'): ?>
                        You have administrative privileges to manage users and requests.
                    <?php endif; ?>
                    Last login: <?php echo date('F j, Y, g:i a'); ?>
                </p>
            </div>
            
            <!-- Work Type Counts (Only for Section Heads and Division Heads) -->
            <?php if($routine_role === 'section_head' || $routine_role === 'division_head'): ?>
                <div class="work-type-counts">
                    <div class="work-type-header">
                        <h3>
                            <i class="fas fa-chart-pie me-2"></i>
                            Incomplete Incoming Requests by Work Type
                            <?php if($total_incomplete_requests > 0): ?>
                                <span class="total-count-badge ms-2">Total: <?php echo $total_incomplete_requests; ?></span>
                            <?php endif; ?>
                        </h3>
                        <span class="text-muted">
                            <?php echo $routine_role === 'section_head' ? 'Your Section' : 'Your Division'; ?>
                        </span>
                    </div>
                    
                    <div class="work-type-grid">
                        <?php 
                        // Define icons for each work type
                        $work_type_icons = [
                            'ICT' => 'fas fa-laptop',
                            'Civil' => 'fas fa-hard-hat',
                            'Transport' => 'fas fa-truck',
                            'Electrical' => 'fas fa-bolt',
                            'Mechanical' => 'fas fa-cogs'
                        ];
                        
                        foreach ($work_types as $type): 
                            $count = $work_type_counts[$type];
                        ?>
                            <div class="work-type-card">
                                <div class="work-type-icon">
                                    <i class="<?php echo $work_type_icons[$type]; ?>"></i>
                                </div>
                                <div class="work-type-name"><?php echo $type; ?></div>
                                <div class="work-type-count <?php echo $count == 0 ? 'zero' : ''; ?>">
                                    <?php echo $count; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="stat-title">Employee ID</div>
                    <div class="stat-value"><?php echo htmlspecialchars($emp_id); ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-title">Designation</div>
                    <div class="stat-value"><?php echo htmlspecialchars($user['designation']); ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-title">Division</div>
                    <div class="stat-value"><?php echo htmlspecialchars($user['division']); ?></div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-title">Status</div>
                    <div class="stat-value">
                        <span class="badge <?php echo $user['status'] === 'active' ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Profile Information -->
            <div class="profile-info-card">
                <div class="profile-header">
                    <h3>Profile Information</h3>
                    <a href="update_profile.php" class="edit-profile-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
                
                <div class="profile-grid">
                    <div>
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-user"></i>
                                Full Name
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-id-badge"></i>
                                Employee ID
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['emp_id']); ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-user-tag"></i>
                                Designation
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['designation']); ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-sitemap"></i>
                                Division
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['division']); ?></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-layer-group"></i>
                                Section
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($user['section']); ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-circle"></i>
                                Account Status
                            </div>
                            <div class="info-value">
                                <span class="badge <?php echo $user['status'] === 'active' ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-user-shield"></i>
                                User Role
                            </div>
                            <div class="info-value">
                                <span class="badge badge-primary"><?php echo ucfirst($user['role']); ?></span>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-tasks"></i>
                                Routine Role
                            </div>
                            <div class="info-value">
                                <?php 
                                if($user['routine_role']) {
                                    echo '<span class="badge badge-primary">' . ucfirst(str_replace('_', ' ', $user['routine_role'])) . '</span>';
                                } else {
                                    echo '<span class="text-muted">Not assigned</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions-card">
                <div class="actions-header">
                    <h3>Quick Actions</h3>
                    <a href="#" class="view-all-btn">View All <i class="fas fa-chevron-right"></i></a>
                </div>
                
                <div class="actions-grid">
                    <?php if($role === 'user'): ?>
                        <a href="work_request.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="action-title">New Work Request</div>
                        </a>
                        
                        <a href="my_work_requests_list.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div class="action-title">My Requests</div>
                        </a>
                        
                        <a href="change_password.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="action-title">Change Password</div>
                        </a>
                        
                        <a href="update_profile.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="action-title">Edit Profile</div>
                        </a>
                        
                    <?php elseif($role === 'admin' || $role === 'sadmin'): ?>
                        <a href="user_management.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="action-title">User Management</div>
                        </a>
                        
                        <a href="admin_requests.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="action-title">All Requests</div>
                        </a>
                        
                        <a href="admin_requests.php?completion=incomplete" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="action-title">Pending Requests</div>
                        </a>
                        
                        <a href="admin_requests.php?urgency=very+urgent" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="action-title">Very Urgent</div>
                        </a>
                        
                        <a href="admin_requests.php?type=ICT" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="action-title">ICT Requests</div>
                        </a>
                        <!-- <a href="settings.php" class="action-btn">⚙️ System Settings</a> -->
                        
                        <a href="reports.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-title">System Reports</div>
                        </a>
                         <a href="settings.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="action-title">⚙️ System Settings</div>
                        </a>

                    <?php endif; ?>

                  


                    
                    <?php
                    if (
                        ($user['division'] === "Administration Division" || $user['section'] === "Transport") &&
                        ($user['routine_role'] === 'section_head' || $user['routine_role'] === 'division_head')
                    ) {
                        // Transport-specific page
                        ?>
                        <a href="incoming_w_req_transport.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="action-title">Incoming Requests</div>
                        </a>
                         <?php
                    } else if($user['routine_role'] === 'section_head' || $user['routine_role'] === 'division_head'){
                        // General page
                        ?>
                        <a href="incoming_work_request.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="action-title">Incoming Requests</div>
                        </a>
                       <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animate stat cards on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });
            
            // Trigger animation after a short delay
            setTimeout(() => {
                statCards.forEach((card, index) => {
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            }, 300);
            
            // Mobile sidebar toggle (if needed)
            const sidebarToggle = document.createElement('button');
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            sidebarToggle.className = 'btn btn-primary d-lg-none position-fixed';
            sidebarToggle.style.cssText = 'top: 20px; left: 20px; z-index: 1001; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;';
            
            document.body.appendChild(sidebarToggle);
            
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });
        });
    </script>
</body>
</html>