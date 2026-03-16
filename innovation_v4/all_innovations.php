<?php
session_start();  
require_once("config/config.php");
require_once("db/db.php");

if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials = new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query = "SELECT * FROM tblusers WHERE Username='$uname'";
  $result = $usercredentials->runBaseQuery($query);
  foreach ($result as $k => $v) {
    $uun = $result[$k]['Username'];
    $uup = $result[$k]['Password'];
  }
  
  // Get connection for data
  $conn = mysqli_connect('localhost', 'root', '', 'innovation_db');
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Innovations - BCIC Innovation Database</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <style>
       /* * {
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
        
        /* Table Card */
        .table-card {
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
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .table-header h4 {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
        }
        
        .btn-add {
            background-color: #16a34a;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-add:hover {
            background-color: #15803d;
            color: #fff;
        }
        
        .btn-edit {
            background-color: #2563eb;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            margin-right: 5px;
            display: inline-block;
        }
        
        .btn-edit:hover {
            background-color: #1d4ed8;
            color: #fff;
        }
        
        .btn-delete {
            background-color: #dc2626;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        
        .btn-delete:hover {
            background-color: #b91c1c;
            color: #fff;
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
        
        /* DataTables Customization */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e9eef2;
            border-radius: 8px;
            padding: 8px 12px;
        }
        
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px;
            margin: 0 3px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #2563eb;
            color: #fff !important;
            border: none;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9eef2;
            border-color: #cbd5e1;
        }
        
        /* Mobile Responsive */
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
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn-add {
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
            <a href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="add_new_innovation.php">
                <i class="fas fa-plus-circle"></i> Add Innovation
            </a>
            <a href="fiscal_year_list.php">
                <i class="fas fa-calendar"></i> Fiscal Years
            </a>
            <a href="add_designation.php">
                <i class="fas fa-briefcase"></i> Designations
            </a>
            <a href="statistics_report.php">
                <i class="fas fa-chart-bar"></i> Statistics
            </a>
            <a href="all_innovations.php" class="active">
                <i class="fas fa-list"></i> All Innovations
            </a>
            <a href="reports.php">
                <i class="fas fa-file-pdf"></i> Reports
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="page-title">
                <h2>All Innovations</h2>
                <p>View and manage all innovation records</p>
            </div>
            
            <div class="user-profile">
                <div class="user-info">
                    <p class="user-name"><?php echo htmlspecialchars($uun); ?></p>
                    <p class="user-role">Administrator</p>
                </div>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($uun, 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-header">
                <h4>Innovation Records</h4>
                <a href="add_new_innovation.php" class="btn-add">
                    <i class="fas fa-plus"></i> Add New Innovation
                </a>
            </div>
            
            <div class="table-responsive">
                <table id="innovationsTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fiscal Year</th>
                            <th>Title</th>
                            <th>Inventor Name</th>
                            <th>Designation</th>
                            <th>Employee ID</th>
                            <th>Workplace</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM innovation_tbl ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $status_class = '';
                                if($row['imple_status'] == 'বাস্তবায়িত') {
                                    $status_class = 'implemented';
                                } elseif($row['imple_status'] == 'চলমান') {
                                    $status_class = 'ongoing';
                                } else {
                                    $status_class = 'pending';
                                }
                                
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['fiscal_year']) . "</td>";
                                echo "<td>" . htmlspecialchars(substr($row['title_of_invention'], 0, 50)) . (strlen($row['title_of_invention']) > 50 ? '...' : '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['inventors_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['inventors_designation']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['inventors_emp_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['proposed_workplace']) . "</td>";
                                echo "<td><span class='status-badge " . $status_class . "'>" . htmlspecialchars($row['imple_status']) . "</span></td>";
                                echo "<td>
                                        <a href='edit_innovation.php?id=" . $row['id'] . "' class='btn-edit'><i class='fas fa-edit'></i></a>
                                        <button onclick='deleteInnovation(" . $row['id'] . ")' class='btn-delete'><i class='fas fa-trash'></i></button>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this innovation record?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('#innovationsTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    search: "Search:",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });

        // Delete functionality
        let deleteId = null;
        
        window.deleteInnovation = function(id) {
            deleteId = id;
            $('#deleteModal').modal('show');
        }

        $('#confirmDelete').click(function() {
            if (deleteId) {
                $.ajax({
                    url: 'add-edit-delete.php',
                    type: 'POST',
                    data: {
                        id: deleteId,
                        mode: 'delete'
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error deleting record');
                    }
                });
            }
        });

        // Close sidebar when clicking outside on mobile
        $(document).click(function(event) {
            const sidebar = $('#sidebar');
            const toggle = $('.menu-toggle');
            
            if ($(window).width() <= 768) {
                if (!sidebar.is(event.target) && !sidebar.has(event.target).length && 
                    !toggle.is(event.target) && !toggle.has(event.target).length) {
                    sidebar.removeClass('active');
                }
            }
        });
    </script>
</body>
</html>
<?php 
    mysqli_close($conn);
} else {
    header('location:login.php');
}
?>