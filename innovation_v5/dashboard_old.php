<?php
session_name('innovation_db');
session_start();
require_once("db/db.php");
if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

    // $_SESSION['id']               = $user['id'];
              $emp_id=   $_SESSION['emp_id'];         
               $fullname= $_SESSION['fullname'];
                // $_SESSION['designation']      = $user['designation'];
                // $_SESSION['email']            = $user['email'];
                // $_SESSION['mobile_no']        = $user['mobile_no'];
                // $_SESSION['place_of_posting'] = $user['place_of_posting'];
                $role=$_SESSION['role'];



  // Total innovations
  $total_innovations_query = "SELECT COUNT(*) as total FROM innovation_tbl";
  $total_innovations_result = mysqli_query($conn, $total_innovations_query);
  $total_innovations = $total_innovations_result ? mysqli_fetch_assoc($total_innovations_result)['total'] : 0;
  
  // Total fiscal years
  $total_fiscal_query = "SELECT COUNT(DISTINCT fiscal_year) as total FROM innovation_tbl";
  $total_fiscal_result = mysqli_query($conn, $total_fiscal_query);
  $total_fiscal = $total_fiscal_result ? mysqli_fetch_assoc($total_fiscal_result)['total'] : 0;
  
  // Total unique inventors
  $total_inventors_query = "SELECT COUNT(DISTINCT inventors_emp_id) as total FROM innovation_tbl";
  $total_inventors_result = mysqli_query($conn, $total_inventors_query);
  $total_inventors = $total_inventors_result ? mysqli_fetch_assoc($total_inventors_result)['total'] : 0;
  
  // Recent innovations
  $recent_query = "SELECT * FROM innovation_tbl ORDER BY id DESC LIMIT 5";
  $recent_result = mysqli_query($conn, $recent_query);
  
  // Implementation status counts
  $status_query = "SELECT imple_status, COUNT(*) as count FROM innovation_tbl GROUP BY imple_status";
  $status_result = mysqli_query($conn, $status_query);
  $status_data = [];
  $implemented_count = 0;
  $ongoing_count = 0;
  $pending_count = 0;
  
  if ($status_result) {
    while($row = mysqli_fetch_assoc($status_result)) {
      $status_data[$row['imple_status']] = $row['count'];
      if ($row['imple_status'] == 'বাস্তবায়িত') {
        $implemented_count = $row['count'];
      } elseif ($row['imple_status'] == 'চলমান') {
        $ongoing_count = $row['count'];
      } else {
        $pending_count += $row['count'];
      }
    }
  }
  
  // Fiscal year wise data for chart
  $fiscal_query = "SELECT fiscal_year, COUNT(*) as count FROM innovation_tbl GROUP BY fiscal_year ORDER BY fiscal_year DESC LIMIT 5";
  $fiscal_result = mysqli_query($conn, $fiscal_query);
  $fiscal_labels = [];
  $fiscal_data = [];
  
  if ($fiscal_result) {
    while($row = mysqli_fetch_assoc($fiscal_result)) {
      $fiscal_labels[] = $row['fiscal_year'];
      $fiscal_data[] = $row['count'];
    }
  }
  
  // Reverse to show chronological order
  $fiscal_labels = array_reverse($fiscal_labels);
  $fiscal_data = array_reverse($fiscal_data);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Innovation Database</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
        /** {
            font-family: 'Inter', sans-serif;
        }*/
        * {
  font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
}
        
        body {
            background-color: #f1f5f9;

        }
        
        /* Sidebar */
        .sidebar {
            background-color: #1e293b;
            min-height: 100vh;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            background-color: #0f172a;
            text-align: center;
            border-bottom: 1px solid #334155;
        }
        
        .sidebar-header h3 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .sidebar-header p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 25px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s;
            margin: 4px 10px;
            border-radius: 8px;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #2d3a4f;
            color: #fff;
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        
        /* Top Navigation */
        .top-nav {
            background-color: #fff;
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title h2 {
            color: #1e293b;
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
        }
        
        .page-title p {
            color: #64748b;
            margin: 5px 0 0;
            font-size: 0.9rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .user-role {
            color: #64748b;
            font-size: 0.85rem;
            margin: 0;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            background-color: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: transform 0.3s;
            border: 1px solid #e9eef2;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .stat-icon i {
            font-size: 1.5rem;
            color: #fff;
        }
        
        .stat-icon.blue {
            background-color: #2563eb;
        }
        
        .stat-icon.green {
            background-color: #16a34a;
        }
        
        .stat-icon.purple {
            background-color: #9333ea;
        }
        
        .stat-icon.orange {
            background-color: #ea580c;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            margin: 5px 0 0;
        }
        
        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-card {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #e9eef2;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-header h5 {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
        }
        
        .chart-header .badge {
            background-color: #e9eef2;
            color: #475569;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        
        /* Recent Table */
        .recent-table {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #e9eef2;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-header h5 {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
        }
        
        .view-all {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        
        .view-all:hover {
            text-decoration: underline;
        }
        
        .table {
            margin: 0;
        }
        
        .table th {
            border-bottom: 2px solid #e9eef2;
            color: #64748b;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 12px;
        }
        
        .table td {
            padding: 12px;
            color: #1e293b;
            vertical-align: middle;
            border-bottom: 1px solid #e9eef2;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-badge.implemented {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-badge.ongoing {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-badge.pending {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary-solid {
            background-color: #2563eb;
            color: #fff;
            border: none;
        }
        
        .btn-primary-solid:hover {
            background-color: #1d4ed8;
            color: #fff;
        }
        
        .btn-success-solid {
            background-color: #16a34a;
            color: #fff;
            border: none;
        }
        
        .btn-success-solid:hover {
            background-color: #15803d;
            color: #fff;
        }
        
        .btn-info-solid {
            background-color: #0891b2;
            color: #fff;
            border: none;
        }
        
        .btn-info-solid:hover {
            background-color: #0e7490;
            color: #fff;
        }
        
        .btn-danger-solid {
            background-color: #dc2626;
            color: #fff;
            border: none;
        }
        
        .btn-danger-solid:hover {
            background-color: #b91c1c;
            color: #fff;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .quick-action-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            border: 1px solid #e9eef2;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .quick-action-card:hover {
            background-color: #f8fafc;
            transform: translateY(-3px);
        }
        
        .quick-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: #dbeafe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        
        .quick-action-card h6 {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .quick-action-card p {
            color: #64748b;
            font-size: 0.8rem;
            margin: 5px 0 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .charts-row {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-action {
                width: 100%;
            }
        }
        
        /* Mobile menu toggle */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background-color: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
        }
        
        .no-data-message {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }
        
        .no-data-message i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>BCIC</h3>
            <p>Innovation Database</p>
        </div>
        
        <div class="sidebar-menu">  
            
            <?php 
            if ($_SESSION['role'] === 'admin') {
            ?><a href="#" class="active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
                <a href="add_new_innovation.php">
                <i class="fas fa-plus-circle"></i> Add Innovation
            </a>

            <a href="fiscal_year_list.php">
                <i class="fas fa-calendar"></i>Add Fiscal Years
            </a>
            <a href="add_designation.php">
                <i class="fas fa-briefcase"></i>Add Designations
            </a>
            <a href="statistics_report.php">
                <i class="fas fa-chart-bar"></i> Statistics
            </a>
            <a href="libs/all_innovations.php">
                <i class="fas fa-list"></i> All Innovations
            </a>
            <a href="reports.php">
                <i class="fas fa-file-pdf"></i> Reports
            </a>
            <a href="libs/innovation_ideas.php">
                <i class="fas fa-cog"></i> Idea Settings
            </a>
            <?php
               
                /* ===============================
               GET ACTIVE FISCAL YEAR
            =================================*/
            // $recent_query_fetch = "
            //     SELECT fiscal_year 
            //     FROM tbl_innovation_idea 
            //     WHERE idea_status='active'
            //     ORDER BY id DESC
            //     LIMIT 1
            // ";

            // $recent_result_fetch = mysqli_query($conn, $recent_query_fetch);
            // $row_fiscal_year = mysqli_fetch_assoc($recent_result_fetch);
            // $fiscal_year = $row_fiscal_year['fiscal_year'] ?? '';

            // $result_count = mysqli_query($conn,"SELECT COUNT(*) AS submitted_idea_count FROM tbl_innovation where fiscal_year='$fiscal_year'"); 
            // $row_count = mysqli_fetch_assoc($result_count);
            // $submitted_idea_count = $row_count['submitted_idea_count'];

                ?>

            <!-- <a href="libs/submitted_innovation_ideas.php">
                <i class="fas fa-info-circle"></i> Sumitted Idea <span class="badge text-bg-secondary"><?php echo $submitted_idea_count; ?></span>
            </a> -->
            <?php
        /* ===============================
           GET ACTIVE FISCAL YEAR
           =============================== */
        $recent_query = "
            SELECT fiscal_year 
            FROM tbl_innovation_idea 
            WHERE idea_status = 'active'
            ORDER BY id DESC
            LIMIT 1
        ";

        $recent_result = mysqli_query($conn, $recent_query);

        $fiscal_year = '';
        if ($recent_result && mysqli_num_rows($recent_result) > 0) {
            $row_fiscal_year = mysqli_fetch_assoc($recent_result);
            $fiscal_year = trim($row_fiscal_year['fiscal_year'] ?? '');
        }

        /* ===============================
           COUNT SUBMITTED IDEAS
           =============================== */
        $submitted_idea_count = 0;
        if (!empty($fiscal_year)) {
            $stmt = $conn->prepare("
                SELECT COUNT(*) AS submitted_idea_count 
                FROM tbl_innovation 
                WHERE TRIM(fiscal_year) = ?
            ");
            $stmt->bind_param("s", $fiscal_year);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $submitted_idea_count = $row['submitted_idea_count'] ?? 0;
            }
            $stmt->close();
        }
        ?>

        <a href="libs/submitted_innovation_ideas.php">
            <i class="fas fa-info-circle"></i> Submitted Idea 
            <span class="badge text-bg-success">
                <?php echo $submitted_idea_count; ?>
            </span>
        </a>
            <?php 
            }else{
                ?>
                <a href="#" class="active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
                <a href="#">
                <i class="fas fa-plus-circle"></i> My All Innovation
            </a>

                <?php
            } 
            ?>
            
        <a href="logout.php" class="btn-action btn-danger-solid">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="page-title">
                <h2>Dashboard</h2>
                <p>Welcome back to your innovation database</p>
            </div>
            
            <div class="user-profile">
                <div class="user-info">
                    <p class="user-name"><?php echo htmlspecialchars($fullname); ?></p>
                    <p class="user-role text-uppercase"><?php echo htmlspecialchars($role); ?></p>
                </div>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($role, 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3 class="stat-value"><?php echo $total_innovations; ?></h3>
                <p class="stat-label">Total Innovations</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="stat-value"><?php echo $total_fiscal; ?></h3>
                <p class="stat-label">Fiscal Years</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="stat-value"><?php echo $total_inventors; ?></h3>
                <p class="stat-label">Unique Inventors</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="stat-value"><?php echo $implemented_count; ?></h3>
                <p class="stat-label">Implemented</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <div class="chart-card">
                <div class="chart-header">
                    <h5>Innovations Overview</h5>
                    <span class="badge">Last 5 years</span>
                </div>
                <div class="chart-container">
                    <canvas id="innovationsChart"></canvas>
                </div>
                <?php if (empty($fiscal_labels)): ?>
                <div class="no-data-message">
                    <i class="fas fa-chart-line"></i>
                    <p>No data available for chart</p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h5>Status Distribution</h5>
                    <span class="badge">Current</span>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
                <?php if ($implemented_count == 0 && $ongoing_count == 0 && $pending_count == 0): ?>
                <div class="no-data-message">
                    <i class="fas fa-chart-pie"></i>
                    <p>No data available for chart</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="quick-action-card" onclick="location.href='add_new_innovation.php'">
                <div class="quick-action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <h6>Add Innovation</h6>
                <p>Create new entry</p>
            </div>
            
            <div class="quick-action-card" onclick="location.href='fiscal_year_list.php'">
                <div class="quick-action-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <h6>Fiscal Years</h6>
                <p>Manage fiscal years</p>
            </div>
            
            <div class="quick-action-card" onclick="location.href='add_designation.php'">
                <div class="quick-action-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h6>Designations</h6>
                <p>Manage designations</p>
            </div>
            
            <div class="quick-action-card" onclick="location.href='statistics_report.php'">
                <div class="quick-action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h6>Statistics</h6>
                <p>View reports</p>
            </div>
        </div>

        <!-- Recent Innovations Table -->
        <div class="recent-table">
            <div class="table-header">
                <h5>Recent Innovations</h5>
                <a href="all_innovations.php" class="view-all">View All <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Fiscal Year</th>
                            <th>Inventor</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_result && mysqli_num_rows($recent_result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($recent_result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($row['title_of_invention'], 0, 200) . '...'); ?></td>
                                <td><?php echo htmlspecialchars($row['fiscal_year']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['inventors_name'], 0, 100)); ?></td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    $status_text = $row['imple_status'];
                                    
                                    if($status_text == 'বাস্তবায়িত') {
                                        $status_class = 'implemented';
                                    } elseif($status_text == 'চলমান') {
                                        $status_class = 'ongoing';
                                    } else {
                                        $status_class = 'pending';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($row['imple_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_innovation.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary-solid" style="padding: 5px 10px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No recent innovations found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="logout.php" class="btn-action btn-danger-solid">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Charts Script -->
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Initialize charts only if there's data
        document.addEventListener('DOMContentLoaded', function() {
            
            <?php if (!empty($fiscal_labels)): ?>
            // Innovations Chart
            const ctx1 = document.getElementById('innovationsChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($fiscal_labels); ?>,
                    datasets: [{
                        label: 'Number of Innovations',
                        data: <?php echo json_encode($fiscal_data); ?>,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e9eef2',
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            <?php endif; ?>

            <?php if ($implemented_count > 0 || $ongoing_count > 0 || $pending_count > 0): ?>
            // Status Chart
            const ctx2 = document.getElementById('statusChart').getContext('2d');
            
            // Filter out zero values
            const statusLabels = [];
            const statusData = [];
            const statusColors = [];
            
            <?php if ($implemented_count > 0): ?>
            statusLabels.push('Implemented');
            statusData.push(<?php echo $implemented_count; ?>);
            statusColors.push('#16a34a');
            <?php endif; ?>
            
            <?php if ($ongoing_count > 0): ?>
            statusLabels.push('Ongoing');
            statusData.push(<?php echo $ongoing_count; ?>);
            statusColors.push('#eab308');
            <?php endif; ?>
            
            <?php if ($pending_count > 0): ?>
            statusLabels.push('Pending');
            statusData.push(<?php echo $pending_count; ?>);
            statusColors.push('#ef4444');
            <?php endif; ?>
            
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: statusColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1'
                        }
                    },
                    cutout: '70%'
                }
            });
            <?php endif; ?>
        });
    </script>
</body>
</html>
<?php 
// } else {
//     header('location:login.php');
// }
?>