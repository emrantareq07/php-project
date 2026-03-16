<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}
$emp_id = $_SESSION['emp_id'];         
$fullname = $_SESSION['fullname'];
$role = $_SESSION['role'] ?? 'admin'; // Default to user if not set

  // Set charset for Bengali support
  mysqli_set_charset($conn, "utf8");
  
  // Handle Add/Edit/Delete via AJAX
  if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] == 'add') {
      $designation_bn = mysqli_real_escape_string($conn, $_POST['designation_bn']);
      $designation_en = mysqli_real_escape_string($conn, $_POST['designation_en']);
      
      // Check if designation already exists (Bengali or English)
      $check = mysqli_query($conn, "SELECT id FROM designation WHERE designation_bn = '$designation_bn' OR designation_en = '$designation_en'");
      if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Designation already exists in Bengali or English!']);
        exit;
      }
      
      $query = "INSERT INTO designation (designation_bn, designation_en) VALUES ('$designation_bn', '$designation_en')";
      if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Designation added successfully!']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)]);
      }
      exit;
    }
    
    if ($_POST['action'] == 'edit') {
      $id = mysqli_real_escape_string($conn, $_POST['id']);
      $designation_bn = mysqli_real_escape_string($conn, $_POST['designation_bn']);
      $designation_en = mysqli_real_escape_string($conn, $_POST['designation_en']);
      
      // Check if designation already exists (excluding current)
      $check = mysqli_query($conn, "SELECT id FROM designation WHERE (designation_bn = '$designation_bn' OR designation_en = '$designation_en') AND id != '$id'");
      if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Designation already exists in Bengali or English!']);
        exit;
      }
      
      $query = "UPDATE designation SET designation_bn = '$designation_bn', designation_en = '$designation_en' WHERE id = '$id'";
      if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Designation updated successfully!']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)]);
      }
      exit;
    }
    
    if ($_POST['action'] == 'delete') {
      $id = mysqli_real_escape_string($conn, $_POST['id']);
      
      // Check if designation is being used in innovations
      $check = mysqli_query($conn, "SELECT id FROM tbl_innovation WHERE designation = (SELECT designation_bn FROM designation WHERE id = '$id')");
      if (mysqli_num_rows($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete: Designation is being used in innovations!']);
        exit;
      }
      
      $query = "DELETE FROM designation WHERE id = '$id'";
      if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Designation deleted successfully!']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)]);
      }
      exit;
    }
    
    if ($_POST['action'] == 'get') {
      $id = mysqli_real_escape_string($conn, $_POST['id']);
      $query = "SELECT * FROM designation WHERE id = '$id'";
      $result = mysqli_query($conn, $query);
      if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
      }
      exit;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designation Management - BCIC Innovation Database</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .bn {
            font-family: 'Noto Sans Bengali', sans-serif;
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
            border: none;
            cursor: pointer;
        }
        
        .btn-add:hover {
            background-color: #15803d;
            color: #fff;
        }
        
        .btn-edit {
            background-color: #2563eb;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            margin-right: 5px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        
        .btn-edit:hover {
            background-color: #1d4ed8;
            color: #fff;
        }
        
        .btn-delete {
            background-color: #dc2626;
            color: #fff;
            padding: 6px 12px;
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
        
        /* Language Badges */
        .lang-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 5px;
        }
        
        .lang-bn {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .lang-en {
            background-color: #dcfce7;
            color: #166534;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
        }
        
        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e9eef2;
            padding: 20px;
        }
        
        .modal-title {
            color: #1e293b;
            font-weight: 600;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            border-top: 1px solid #e9eef2;
            padding: 15px 20px;
        }
        
        .form-label {
            color: #475569;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #e9eef2;
            border-radius: 8px;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        
        .btn-save {
            background-color: #2563eb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
        }
        
        .btn-save:hover {
            background-color: #1d4ed8;
        }
        
        .btn-cancel {
            background-color: #e9eef2;
            color: #475569;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
        }
        
        .btn-cancel:hover {
            background-color: #cbd5e1;
        }
        
        /* DataTables Customization */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e9eef2;
            border-radius: 8px;
            padding: 8px 12px;
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
        
        /* Usage count badge */
        .usage-badge {
            background-color: #e9eef2;
            color: #475569;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-left: 5px;
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
            <a href="add_designation.php" class="active">
                <i class="fas fa-briefcase"></i> Designations
            </a>
            <a href="statistics_report.php">
                <i class="fas fa-chart-bar"></i> Statistics
            </a>
            <a href="all_innovations.php">
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
                <h2>Designation Management</h2>
                <p>Manage designations in Bengali and English</p>
            </div>
            
            <div class="user-profile">
                <div class="user-info">
                    <p class="user-name"><?php echo htmlspecialchars($fullname); ?></p>
                    <p class="user-role"><?php echo htmlspecialchars($role); ?></p>
                </div>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($fullname, 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-header">
                <h4>Designations List</h4>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add New Designation
                </button>
            </div>
            
            <div class="table-responsive">
                <table id="designationTable" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Designation (Bengali)</th>
                            <th>Designation (English)</th>
                            <th>Usage Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT d.*, 
                                 (SELECT COUNT(*) FROM tbl_innovation WHERE designation = d.designation_bn) as usage_count 
                                 FROM designation d 
                                 ORDER BY d.id DESC";
                        $result = mysqli_query($conn, $query);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td class='bn'>" . htmlspecialchars($row['designation_bn']) . " <span class='lang-badge lang-bn'>BN</span></td>";
                                echo "<td>" . htmlspecialchars($row['designation_en']) . " <span class='lang-badge lang-en'>EN</span></td>";
                                echo "<td><span class='usage-badge'>Used in " . $row['usage_count'] . " innovations</span></td>";
                                echo "<td>
                                        <button onclick='editDesignation(" . $row['id'] . ")' class='btn-edit'><i class='fas fa-edit'></i> Edit</button>
                                        <button onclick='deleteDesignation(" . $row['id'] . ")' class='btn-delete'><i class='fas fa-trash'></i> Delete</button>
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

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="designationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="designationForm">
                        <input type="hidden" id="designationId" name="id">
                        
                        <div class="mb-3">
                            <label for="designation_bn" class="form-label">Designation (Bengali) <span class="lang-badge lang-bn">BN</span></label>
                            <input type="text" class="form-control bn" id="designation_bn" name="designation_bn" 
                                   placeholder="e.g., প্রধান বৈজ্ঞানিক কর্মকর্তা" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="designation_en" class="form-label">Designation (English) <span class="lang-badge lang-en">EN</span></label>
                            <input type="text" class="form-control" id="designation_en" name="designation_en" 
                                   placeholder="e.g., Chief Scientific Officer" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-save" onclick="saveDesignation()">Save</button>
                </div>
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
                    <p>Are you sure you want to delete this designation?</p>
                    <p class="text-danger" id="deleteWarning"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-delete" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast for notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('#designationTable').DataTable({
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

        // Open Add Modal
        function openAddModal() {
            $('#designationForm')[0].reset();
            $('#designationId').val('');
            $('#modalTitle').text('Add Designation');
            $('#designationModal').modal('show');
        }

        // Edit Designation
        function editDesignation(id) {
            $.ajax({
                url: 'add_designation.php',
                type: 'POST',
                data: {
                    action: 'get',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $('#designationId').val(data.id);
                    $('#designation_bn').val(data.designation_bn);
                    $('#designation_en').val(data.designation_en);
                    $('#modalTitle').text('Edit Designation');
                    $('#designationModal').modal('show');
                },
                error: function() {
                    showNotification('Error', 'Failed to load designation data', 'error');
                }
            });
        }

        // Save Designation (Add or Update)
        function saveDesignation() {
            const id = $('#designationId').val();
            const designation_bn = $('#designation_bn').val().trim();
            const designation_en = $('#designation_en').val().trim();
            
            if (!designation_bn || !designation_en) {
                showNotification('Error', 'Please fill in both Bengali and English designations', 'error');
                return;
            }
            
            const action = id ? 'edit' : 'add';
            const formData = {
                action: action,
                designation_bn: designation_bn,
                designation_en: designation_en
            };
            
            if (id) {
                formData.id = id;
            }
            
            $.ajax({
                url: 'add_designation.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#designationModal').modal('hide');
                        showNotification('Success', response.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('Error', response.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'Failed to save designation', 'error');
                }
            });
        }

        // Delete Designation
        function deleteDesignation(id) {
            $.ajax({
                url: 'add_designation.php',
                type: 'POST',
                data: {
                    action: 'delete_check',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.in_use) {
                        showNotification('Error', 'Cannot delete: Designation is being used in innovations', 'error');
                    } else {
                        $('#deleteWarning').text('');
                        $('#confirmDelete').data('id', id);
                        $('#deleteModal').modal('show');
                    }
                }
            });
        }

        // Confirm Delete
        $('#confirmDelete').click(function() {
            const id = $(this).data('id');
            
            $.ajax({
                url: 'add_designation.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    if (response.status === 'success') {
                        showNotification('Success', response.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('Error', response.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'Failed to delete designation', 'error');
                }
            });
        });

        // Show notification
        function showNotification(title, message, type) {
            const toast = new bootstrap.Toast(document.getElementById('notificationToast'));
            $('#toastTitle').text(title);
            $('#toastMessage').text(message);
            
            const toastHeader = $('.toast-header');
            toastHeader.removeClass('bg-success bg-danger bg-warning');
            
            if (type === 'success') {
                toastHeader.addClass('bg-success text-white');
            } else if (type === 'error') {
                toastHeader.addClass('bg-danger text-white');
            }
            
            toast.show();
        }

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

        // Form validation
        $('#designation_bn, #designation_en').on('input', function() {
            const value = $(this).val();
            if (value.length > 0) {
                $(this).removeClass('is-invalid');
            }
        });
    </script>
</body>
</html>
<?php 
mysqli_close($conn);

?>