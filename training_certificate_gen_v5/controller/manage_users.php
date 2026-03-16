<?php
// manage_users.php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'sadmin') {
    header("Location: ../index.php");
    exit;
}

// Delete User
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_users.php?msg=deleted&type=success");
    } else {
        header("Location: manage_users.php?msg=delete_failed&type=error");
    }
    $stmt->close();
    exit;
}

// Fetch Users with role='user'
$result = $conn->query("SELECT * FROM users_tbl WHERE role='user' ORDER BY id DESC");
$total_users = $result->num_rows;

// Get statistics
$active_count = $conn->query("SELECT COUNT(*) as count FROM users_tbl WHERE status='active' AND role='user'")->fetch_assoc()['count'];
$inactive_count = $conn->query("SELECT COUNT(*) as count FROM users_tbl WHERE status='inactive' AND role='user'")->fetch_assoc()['count'];
$pending_count = $conn->query("SELECT COUNT(*) as count FROM users_tbl WHERE status='pending' AND role='user'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Users - Admin Panel</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    :root {
        --primary-color: #10b981;
        --primary-light: #d1fae5;
        --primary-dark: #059669;
        --secondary-color: #3b82f6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --light-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-color: #374151;
        --text-muted: #6b7280;
    }
    
    body {
        background-color: var(--light-bg);
        color: var(--text-color);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        min-height: 100vh;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 30px 0;
        border-radius: 0 0 20px 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
    }
    
    .stats-card {
        background: var(--card-bg);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }
    
    .icon-total { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; }
    .icon-active { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
    .icon-inactive { background: linear-gradient(135deg, #ef4444, #f87171); color: white; }
    .icon-pending { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 5px;
    }
    
    .stats-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .main-card {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        color: white;
    }
    
    .btn-outline-custom {
        border: 2px solid #e5e7eb;
        color: var(--text-color);
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-custom:hover {
        background: var(--light-bg);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .badge-active { background-color: #d1fae5; color: #065f46; }
    .badge-inactive { background-color: #fee2e2; color: #991b1b; }
    .badge-pending { background-color: #fef3c7; color: #92400e; }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .btn-view { background-color: #dbeafe; color: #1e40af; border: none; }
    .btn-view:hover { background-color: #bfdbfe; }
    
    .btn-edit { background-color: #fef3c7; color: #92400e; border: none; }
    .btn-edit:hover { background-color: #fde68a; }
    
    .btn-delete { background-color: #fee2e2; color: #991b1b; border: none; }
    .btn-delete:hover { background-color: #fecaca; }
    
    .dataTables_wrapper {
        padding: 0;
    }
    
    table.dataTable {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    table.dataTable thead th {
        background-color: #f8fafc;
        color: var(--text-color);
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
        padding: 15px;
    }
    
    table.dataTable tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    table.dataTable tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .alert-custom {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .alert-success { background-color: #d1fae5; color: #065f46; border-left: 4px solid var(--success-color); }
    .alert-danger { background-color: #fee2e2; color: #991b1b; border-left: 4px solid var(--danger-color); }
    .alert-info { background-color: #dbeafe; color: #1e40af; border-left: 4px solid var(--info-color); }
    
    .user-avatar-small {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        margin-right: 10px;
    }
    
    .search-box {
        position: relative;
        max-width: 300px;
    }
    
    .search-box input {
        padding-left: 45px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        height: 45px;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        z-index: 10;
    }
    
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 20px 0;
            border-radius: 0 0 15px 15px;
        }
        
        .main-card {
            padding: 20px;
            border-radius: 15px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
            margin-bottom: 5px;
        }
    }
  </style>
</head>
<body>

<!-- Header Section -->
<div class="dashboard-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2"><i class="bi bi-people me-2"></i> User Management</h1>
                <p class="mb-0 opacity-75">Manage all registered users in the system</p>
            </div>
            <a href="dashboard.php" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-total">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-number"><?php echo $total_users; ?></div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-active">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo $active_count; ?></div>
                    <div class="stats-label">Active Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-inactive">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo $inactive_count; ?></div>
                    <div class="stats-label">Inactive Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-pending">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stats-number"><?php echo $pending_count; ?></div>
                    <div class="stats-label">Pending Users</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Messages -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success alert-custom alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i> User deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'delete_failed'): ?>
            <div class="alert alert-danger alert-custom alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i> Failed to delete user.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Main Content Card -->
    <div class="main-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0"><i class="bi bi-table me-2"></i> Users List</h4>
                <p class="text-muted mb-0">View and manage all user accounts</p>
            </div>
            <div class="d-flex gap-3">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search users...">
                </div>
                <a href="add_user.php" class="btn btn-primary-custom">
                    <i class="bi bi-person-plus me-2"></i> Add New User
                </a>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table id="userTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Emp ID</th>
                        <th>Designation</th>
                        <th>Division</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    while ($row = $result->fetch_assoc()): 
                        $initials = strtoupper(substr($row['name'], 0, 2));
                    ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-small">
                                    <?php echo $initials; ?>
                                </div>
                                <div>
                                    <div class="fw-medium"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['designation'] ?: 'Not specified'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['emp_id']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['designation'] ?: '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['division'] ?: '-'); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile_no'] ?: '-'); ?></td>
                        <td>
                            <small><?php echo htmlspecialchars($row['email_id']); ?></small>
                        </td>
                        <td>
                            <?php if (strtolower($row['status']) == "active"): ?>
                                <span class="badge-status badge-active">
                                    <i class="bi bi-check-circle me-1"></i> Active
                                </span>
                            <?php elseif (strtolower($row['status']) == "inactive"): ?>
                                <span class="badge-status badge-inactive">
                                    <i class="bi bi-x-circle me-1"></i> Inactive
                                </span>
                            <?php elseif (strtolower($row['status']) == "pending"): ?>
                                <span class="badge-status badge-pending">
                                    <i class="bi bi-clock me-1"></i> Pending
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="download_user.php?id=<?php echo $row['id']; ?>" 
                                   class="btn-action btn-view" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" 
                                   class="btn-action btn-edit" title="Edit User">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="#" 
                                   onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['name'])); ?>')" 
                                   class="btn-action btn-delete" title="Delete User">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="mt-4 pt-4 border-top text-center text-muted">
            <small>
                <i class="bi bi-cpu me-1"></i> User Management System | 
                <span class="text-primary">Total: <?php echo $total_users; ?> Users</span> |
                <?php echo date('d M Y'); ?>
            </small>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#userTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search users...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users available",
            infoFiltered: "(filtered from _MAX_ total users)"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    });
    
    // Custom search input
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// SweetAlert2 for delete confirmation
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Delete User?',
        html: `Are you sure you want to delete <strong>${userName}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'border-radius-15'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `manage_users.php?delete=${userId}`;
        }
    });
}

// Show success message with animation
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.style.transform = 'translateY(-5px)';
            alert.style.boxShadow = '0 5px 15px rgba(16, 185, 129, 0.3)';
            setTimeout(() => {
                alert.style.transform = 'translateY(0)';
                alert.style.boxShadow = 'none';
            }, 300);
        }
    }, 100);
<?php endif; ?>

// Export functionality
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#userTable tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) {
            if (j !== 8) { // Skip actions column
                row.push(cols[j].innerText);
            }
        }
        csv.push(row.join(","));        
    }

    // Download CSV file
    var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    var downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}
</script>

</body>
</html>