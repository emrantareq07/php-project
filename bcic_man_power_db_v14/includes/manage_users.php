<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>
<?php
// In your user management page, change this line:
$sql = "SELECT * FROM users ORDER BY id DESC";

// To this (exclude sadmin):
$sql = "SELECT * FROM users WHERE role IN ('admin', 'user') ORDER BY id DESC";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

     * {
               font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif;
            }
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }

        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }

        /* Header & Action Bar */
        .page-header {
            background: white;
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        /* Table Styling */
        .card { border: none; border-radius: 12px; }
        .table thead th { 
            background-color: #f1f3f5; 
            color: #495057; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 0.5px;
            border: none;
        }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(52, 152, 219, 0.03); }
        
        /* Role Badges */
        .badge-admin { background-color: var(--primary-color); color: white; }
        .badge-user { background-color: var(--accent-color); color: white; }

        /* Status Badge */
        .badge-online { background-color: var(--success-color); color: white; }
        .badge-offline { background-color: #95a5a6; color: white; }

        /* Buttons */
        .btn { border-radius: 8px; font-weight: 500; padding: 8px 16px; }
        .btn-primary { background-color: var(--accent-color); border: none; }
        .btn-primary:hover { background-color: #2980b9; }

        /* Modal Styling */
        .modal-content { border-radius: 15px; border: none; }
        .modal-header { background: var(--primary-color); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px; }
        
        /* Small Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border-left: 4px solid;
            transition: all 0.2s ease;
        }
        .stats-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .stats-card .stats-icon { font-size: 1.8rem; opacity: 0.7; }
        .stats-card .stats-number { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
        .stats-card .stats-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; }
        
        @media print {
            .no-print { display: none !important; }
            .stats-cards-container { display: none !important; }
        }
    </style>
</head>
<body>

<div class="page-header shadow-sm">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="fa fa-users-gear me-2 text-primary"></i> User Management</h4>
            <small class="text-muted">Manage system access and permissions</small>
        </div>
       <!--  <div class="d-flex gap-2">
            <a href="sadmin_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i> Back</a>
            <a href="manage_users.php" class="btn btn-warning"><i class="fa fa-refresh me-1"></i> Refresh</a>
            <button class="btn btn-success" id="print-btn"><i class="fa fa-print me-1"></i> Expert Excel</button>
            <button class="btn btn-success" id="print-btn"><i class="fa fa-print me-1"></i> Print Report</button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddModal()">
                <i class="fa fa-plus-circle me-1"></i> Add New User
            </button>
            <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
        </div> -->
        <div class="d-flex gap-2">
    <a href="sadmin_dashboard.php" class="btn btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i> Back</a>
    <a href="manage_users.php" class="btn btn-warning"><i class="fa fa-refresh me-1"></i> Refresh</a>
    <a href="export_excel.php" class="btn btn-success"><i class="fa fa-file-excel me-1"></i> Export Excel</a>
    <button class="btn btn-success" id="print-btn"><i class="fa fa-print me-1"></i> Print Report</button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddModal()">
        <i class="fa fa-plus-circle me-1"></i> Add New User
    </button>
    <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>
    </div>
</div>

<div class="container-fluid px-4">
    <!-- Stats Cards Row -->
    <div class="stats-cards-container mb-4">
        <div class="row g-3">
            <?php
            // Fetch statistics: total users (only admin & user, exclude sadmin), online count, offline count
            // First, check if login_status column exists; if not, we'll simulate based on a session heuristic.
            // For a real implementation, you'd have a `login_status` column in users table or a separate sessions table.
            // Here we assume `login_status` column exists (TINYINT 1 = online, 0 = offline) or we derive.
            // To make it work out-of-box, I'll check column existence and use a fallback method.
            
            // Method: Check if login_status column exists in users table
            $colCheck = $conn->query("SHOW COLUMNS FROM users LIKE 'login_status'");
            $hasLoginStatus = ($colCheck && $colCheck->num_rows > 0);
            
            // If column doesn't exist, we'll create it for proper tracking
            if (!$hasLoginStatus) {
                $conn->query("ALTER TABLE users ADD COLUMN login_status TINYINT(1) DEFAULT 0");
                $hasLoginStatus = true;
            }
            
            // Count total users (admin and user only, exclude sadmin)
            $totalQuery = "SELECT COUNT(*) as total FROM users WHERE role IN ('admin', 'user')";
            $totalRes = $conn->query($totalQuery);
            $totalUsers = ($totalRes && $row = $totalRes->fetch_assoc()) ? $row['total'] : 0;
            
            // Count online users (login_status = 1) and role is admin/user
            $onlineQuery = "SELECT COUNT(*) as online FROM users WHERE login_status = 1 AND role IN ('admin', 'user')";
            $onlineRes = $conn->query($onlineQuery);
            $onlineUsers = ($onlineRes && $row = $onlineRes->fetch_assoc()) ? $row['online'] : 0;
            
            // Count offline users (login_status = 0) and role is admin/user
            $offlineQuery = "SELECT COUNT(*) as offline FROM users WHERE login_status = 0 AND role IN ('admin', 'user')";
            $offlineRes = $conn->query($offlineQuery);
            $offlineUsers = ($offlineRes && $row = $offlineRes->fetch_assoc()) ? $row['offline'] : 0;
            ?>
            <!-- Total Users Card -->
            <div class="col-md-4">
                <div class="stats-card h-100" style="border-left-color: #3498db;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-number"><?= $totalUsers ?></div>
                            <div class="stats-label">Total Users</div>
                            <small class="text-muted">Admin + User only</small>
                        </div>
                        <div class="stats-icon text-primary">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Online Users Card -->
            <div class="col-md-4">
                <div class="stats-card h-100" style="border-left-color: #2ecc71;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-number text-success"><?= $onlineUsers ?></div>
                            <div class="stats-label">Online Now</div>
                            <small class="text-muted">Active session</small>
                        </div>
                        <div class="stats-icon text-success">
                            <i class="fa fa-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Offline Users Card -->
            <div class="col-md-4">
                <div class="stats-card h-100" style="border-left-color: #95a5a6;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-number text-secondary"><?= $offlineUsers ?></div>
                            <div class="stats-label">Offline</div>
                            <small class="text-muted">Inactive</small>
                        </div>
                        <div class="stats-icon text-secondary">
                            <i class="fa fa-user-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="userTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Factory</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Login Status</th>
                            <th class="text-center no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch users with login_status - only admin and user roles
                        $sql = "SELECT * FROM users WHERE role IN ('admin', 'user') ORDER BY id DESC";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()):
                            $roleClass = ($row['role'] == 'admin') ? 'badge-admin' : 'badge-user';
                            // Determine login_status badge: online if login_status==1, else offline
                            $isOnline = (isset($row['login_status']) && $row['login_status'] == 1);
                            $loginStatusBadge = $isOnline ? '<span class="badge bg-success"><i class="fa fa-circle me-1" style="font-size: 0.65rem;"></i> Online</span>' 
                                                         : '<span class="badge bg-secondary"><i class="fa fa-circle me-1" style="font-size: 0.65rem;"></i> Offline</span>';
                        ?>
                            <tr>
                                <td class="fw-bold text-muted">#<?= $row['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 me-2 text-center" style="width: 35px; height: 35px;">
                                            <i class="fa fa-user text-secondary"></i>
                                        </div>
                                        <?= htmlspecialchars($row['username']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><span class="text-truncate" style=" display: block;"><?= htmlspecialchars($row['factory_name']) ?></span></td>
                                <td><?= htmlspecialchars($row['designation'] ?: 'N/A') ?></td>
                                <td><span class="badge <?= $roleClass ?>"><?= strtoupper($row['role']) ?></span></td>
                                <td>
                                    <span class="badge <?= $roleClass ?>">
                                        <?= ($row['status'] == 0) ? 'Inactive' : 'Active'; ?>
                                    </span>
                                 </td>
                                <td><?= $loginStatusBadge ?></td>
                                <td class="text-center no-print">
                                    <button class="btn btn-sm btn-light border text-warning me-1" onclick='editUser(<?= json_encode($row) ?>)' title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="user-delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light border text-danger" 
                                       onclick="return confirm('Permanently delete this user?');" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content shadow-lg" method="POST" action="user-save.php">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fa fa-user-plus me-2"></i> User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="id">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control bg-light" name="username" id="username" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Role</label>
                        <select class="form-select bg-light" name="role" id="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-6 text-muted">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Designation</label>
                        <input type="text" class="form-control" name="designation" id="designation">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Factory Name</label>
                        <input type="text" class="form-control" name="factory_name" id="factory_name" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••">
                        <small class="text-muted">Only fill to change existing password</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "pageLength": 10,
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search users..."
            }
        });
    });

    function openAddModal() {
        $(".modal-title").html('<i class="fa fa-user-plus me-2"></i> Add New User');
        $("#userModal form")[0].reset();
        $("#id").val("");
    }

    function editUser(row) {
        $(".modal-title").html('<i class="fa fa-user-edit me-2"></i> Edit User');
        $("#id").val(row.id);
        $("#username").val(row.username);
        $("#full_name").val(row.full_name);
        $("#factory_name").val(row.factory_name);
        $("#designation").val(row.designation);
        $("#role").val(row.role);
        $("#userModal").modal("show");
    }

    // Modern Print Function
    document.getElementById('print-btn').addEventListener('click', function() {
        const currentDate = new Date().toLocaleDateString();
        const tableContent = document.getElementById('userTable').cloneNode(true);
        
        // Remove actions column from the clone
        const headerRow = tableContent.querySelector('thead tr');
        headerRow.removeChild(headerRow.lastElementChild);
        const rows = tableContent.querySelectorAll('tbody tr');
        rows.forEach(row => row.removeChild(row.lastElementChild));

        const printWin = window.open('', '', 'width=1000,height=800');
        printWin.document.write(`
            
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Attendance Sheet - </title>
                
                       <!-- Google Fonts -->
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
            <style>
                 * {
                        font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
                    }
                    body { font-family: sans-serif; padding: 40px; }
                    h2, h4 { text-align: center; margin: 5px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 30px; }
                    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                    th { background: #f4f4f4; }
                    .footer { margin-top: 50px; font-size: 10px; text-align: center; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
                </style>
            </head>
            <body>
                <h2>Bangladesh Chemical Industries Corporation</h2>
                <h4>User Management Directory</h4>
                <p><strong>Generated On:</strong> ${currentDate}</p>
                ${tableContent.outerHTML}
                <div class="footer">Design & Developed by BCIC ICT Division, BCIC - System Generated Report</div>
            </body>
            </html>
        `);
        printWin.document.close();
        printWin.print();
    });

// Send heartbeat every 2 minutes to keep session alive
setInterval(function() {
    fetch('update_heartbeat.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                window.location.href = 'index.php';
            }
        })
        .catch(error => console.log('Heartbeat error:', error));
}, 120000); // 2 minutes

// Export to Excel functionality
document.getElementById('export-excel-btn').addEventListener('click', function() {
    // Get the table data
    const table = document.getElementById('userTable');
    const rows = table.querySelectorAll('tr');
    
    // Prepare Excel data
    let excelData = [];
    
    // Get headers (excluding Actions column)
    const headers = [];
    const headerCells = rows[0].querySelectorAll('th');
    for (let i = 0; i < headerCells.length - 1; i++) { // Exclude last column (Actions)
        headers.push(headerCells[i].innerText);
    }
    excelData.push(headers);
    
    // Get body data (excluding Actions column)
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.querySelectorAll('td');
        const rowData = [];
        
        for (let j = 0; j < cells.length - 1; j++) { // Exclude last column (Actions)
            // Clean up HTML content
            let cellText = cells[j].innerText.trim();
            // Remove special characters if needed
            cellText = cellText.replace(/[^\x20-\x7E\u0980-\u09FF]/g, '');
            rowData.push(cellText);
        }
        excelData.push(rowData);
    }
    
    // Convert to worksheet
    const ws = XLSX.utils.aoa_to_sheet(excelData);
    
    // Auto-size columns (basic)
    const maxWidth = headers.map(header => ({wch: Math.max(header.length, 15)}));
    ws['!cols'] = maxWidth;
    
    // Create workbook
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Users_Report');
    
    // Generate filename with current date
    const today = new Date();
    const dateStr = today.getFullYear() + '-' + 
                    String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(today.getDate()).padStart(2, '0');
    const filename = `Users_Report_${dateStr}.xlsx`;
    
    // Export file
    XLSX.writeFile(wb, filename);
});
</script>
</body>
</html>