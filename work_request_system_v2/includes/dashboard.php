<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

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

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 28px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
        }

        .user-role {
            font-size: 14px;
            opacity: 0.9;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        .welcome-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .welcome-section h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .welcome-text {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-icon {
            font-size: 40px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 14px;
            color: #4CAF50;
        }

        .profile-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            background: #e3f2fd;
            color: #1565c0;
        }

        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .quick-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .quick-actions h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .last-login {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                height: auto;
                padding: 15px 0;
            }

            .user-menu {
                margin-top: 15px;
                width: 100%;
                justify-content: space-between;
            }

            .container {
                margin-top: 140px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Icons */
        .icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .icon-user { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'); }
        .icon-id { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>'); }
        .icon-designation { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>'); }
        .icon-division { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>'); }
        .icon-section { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>'); }
        .icon-status { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'); }
        .icon-role { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23667eea"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'); }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i>👤</i>
                <span>User Dashboard</span>
            </div>
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($full_name); ?></div>
                    <div class="user-role"><?php echo ucfirst($role); ?></div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i>🚪</i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($full_name); ?>! 👋</h1>
            <p class="welcome-text">
                You are logged in as <?php echo ucfirst($role); ?>. 
                <?php if($role === 'admin' || $role === 'sadmin'): ?>
                    You have administrative privileges.
                <?php endif; ?>
            </p>
            <div class="last-login">
                Last login: <?php echo date('F j, Y, g:i a'); ?>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-title">Employee ID</div>
                <div class="stat-value"><?php echo htmlspecialchars($emp_id); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👔</div>
                <div class="stat-title">Designation</div>
                <div class="stat-value"><?php echo htmlspecialchars($user['designation']); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🏢</div>
                <div class="stat-title">Division</div>
                <div class="stat-value"><?php echo htmlspecialchars($user['division']); ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-title">Status</div>
                <div class="stat-value">
                    <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo ucfirst($user['status']); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="profile-section">
            <h2 class="section-title">Profile Information</h2>
            
            <div class="profile-grid">
                <div class="profile-column">
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-user"></span>
                            Full Name
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-id"></span>
                            Employee ID
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user['emp_id']); ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-designation"></span>
                            Designation
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user['designation']); ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-division"></span>
                            Division
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user['division']); ?></div>
                    </div>
                </div>
                
                <div class="profile-column">
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-section"></span>
                            Section
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($user['section']); ?></div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-status"></span>
                            Account Status
                        </div>
                        <div class="info-value">
                            <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-role"></span>
                            User Role
                        </div>
                        <div class="info-value">
                            <span class="role-badge"><?php echo ucfirst($user['role']); ?></span>
                        </div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-label">
                            <span class="icon icon-role"></span>
                            Routine Role
                        </div>
                        <div class="info-value">
                            <?php 
                            if($user['routine_role']) {
                                echo '<span class="role-badge">' . ucfirst(str_replace('_', ' ', $user['routine_role'])) . '</span>';
                            } else {
                                echo '<span style="color:#666;">Not assigned</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <?php if($role === 'user'): ?>
                        <a href="update_profile.php" class="action-btn">✏️ Edit Profile</a>
                        <a href="change_password.php" class="action-btn">🔒 Change Password</a>
                        <a href="my_reports.php" class="action-btn">📊 View Reports</a>
                         <a href="work_request.php" class="action-btn">📝 New Work Request</a>
                        <a href="work_requests_list.php" class="action-btn">📋 My Requests</a>
                        <a href="work_request_app.php" class="action-btn">📅 Apply Work Request</a>

                    <?php elseif($role === 'admin' || $role === 'sadmin'): ?>
                         <a href="user_management.php" class="action-btn">👥 User Management</a>
                        <a href="admin_requests.php" class="action-btn">📋 All Work Requests</a>
                        <a href="system_logs.php" class="action-btn">📊 System Logs</a>
                        <a href="reports.php" class="action-btn">📈 Reports</a>

                        <a href="admin_requests.php" class="action-btn">📋 All Requests</a>
        <a href="admin_requests.php?completion=incomplete" class="action-btn">⏳ Pending Requests</a>
        <a href="admin_requests.php?urgency=very+urgent" class="action-btn">🚨 Very Urgent</a>
        <a href="admin_requests.php?type=ICT" class="action-btn">💻 ICT Requests</a>




                        <a href="admin_requests.php" class="action-btn">👥 All Requests</a>
                        <a href="user_management.php" class="action-btn">👥 Manage Users</a>
                        <a href="reports.php" class="action-btn">📈 System Reports</a>
                        <a href="settings.php" class="action-btn">⚙️ System Settings</a>
                        <a href="audit_logs.php" class="action-btn">📋 View Logs</a>
                    <?php endif; ?>
                    <?php if($user['routine_role'] === 'section_head' || $user['routine_role'] === 'division_head'): ?>
                        <a href="approvals.php" class="action-btn">✅ Approvals</a>
                        <a href="team_management.php" class="action-btn">👥 Team Management</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Actions -->
            <div class="actions">
                <?php if($role === 'admin' || $role === 'sadmin'): ?>
                    <a href="admin_dashboard.php" class="btn btn-primary">
                        <i>⚡</i> Admin Dashboard
                    </a>
                <?php endif; ?>
                
                <a href="update_profile.php" class="btn btn-secondary">
                    <i>✏️</i> Edit Profile
                </a>
                
                <a href="change_password.php" class="btn btn-secondary">
                    <i>🔒</i> Change Password
                </a>
            </div>
        </div>
    </div>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat cards on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Logout confirmation
            document.querySelector('.logout-btn').addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to logout?')) {
                    e.preventDefault();
                }
            });

            // Update last login time every minute
            function updateTime() {
                const now = new Date();
                const options = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true 
                };
                const timeString = now.toLocaleDateString('en-US', options);
                
                const timeElement = document.querySelector('.last-login');
                if (timeElement) {
                    timeElement.textContent = 'Last login: ' + timeString;
                }
            }

            // Update time initially and every minute
            updateTime();
            setInterval(updateTime, 60000);
        });
    </script>
</body>
</html>