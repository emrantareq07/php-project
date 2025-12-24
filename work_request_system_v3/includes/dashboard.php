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
$routine_role = $user['routine_role'];

$stmt->close();
require_once 'header_dashboard.php';
?>

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
                        <a href="incoming_work_request.php" class="action-btn">📅 Incoming Work Request</a>
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
            // document.querySelector('.logout-btn').addEventListener('click', function(e) {
            //     if (!confirm('Are you sure you want to logout?')) {
            //         e.preventDefault();
            //     }
            // });

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