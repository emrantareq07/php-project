<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-container, .print-container * {
                visibility: visible;
            }
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .print-table {
                width: 100%;
                border-collapse: collapse;
            }
            .print-table th, .print-table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .print-table th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            .print-header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }
        }
    </style>

</head>
<body class="bg-light">

<div class="container-fluid py-4 p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>User Management</h3>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddModal()">
                <i class="fa fa-plus"></i> Add User
            </button>
            <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
            <a href="sadmin_dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left me-2"></i> Back</a>
            <button class="btn btn-success" id="print-btn">
                <i class="fa fa-print me-2"></i> Print List
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="userTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Factory</th>
                    <th>Full Name</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <!-- <th>Default Password</th> -->
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $sql = "SELECT * FROM users ORDER BY id DESC";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['factory_name'] ?></td>
                        <td><?= $row['full_name'] ?></td>
                        <td><?= $row['designation'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <!-- <td><?= $row['password'] ?></td> -->
                        <td><?= ucfirst($row['role']) ?></td>
                        <td class="no-print">
                            <button class="btn btn-sm btn-warning" onclick='editUser(<?= json_encode($row) ?>)'>
                                <i class="fa fa-edit"></i>
                            </button>

                            <a href="user-delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this user?');">
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

<!-- ADD / EDIT MODAL -->
<div class="modal fade" id="userModal">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="user-save.php">

            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" name="id" id="id">

                <div class="mb-2">
                    <label>Username:</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>

                <div class="mb-2">
                    <label>Password: <small>(Leave blank to keep unchanged)</small></label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>

                <div class="mb-2">
                    <label>Full Name:</label>
                    <input type="text" class="form-control" name="full_name" id="full_name" required>
                </div>

                <div class="mb-2">
                    <label>Factory Name:</label>
                    <input type="text" class="form-control" name="factory_name" id="factory_name" required>
                </div>

                <div class="mb-2">
                    <label>Designation:</label>
                    <input type="text" class="form-control" name="designation" id="designation">
                </div>

                <div class="mb-2">
                    <label>Email:</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>

                <div class="mb-2">
                    <label>Phone:</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>

                <div class="mb-2">
                    <label>Role:</label>
                    <select class="form-select" name="role" id="role">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Save</button>
            </div>

        </form>
    </div>
</div>

<!-- JS CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $("#userTable").DataTable();

    function openAddModal() {
        $(".modal-title").text("Add User");
        $("#userModal input").val("");
        $("#role").val("user");
    }

    function editUser(row) {
        $(".modal-title").text("Edit User");

        $("#id").val(row.id);
        $("#username").val(row.username);
        $("#full_name").val(row.full_name);
        $("#factory_name").val(row.factory_name);
        $("#designation").val(row.designation);
        $("#email").val(row.email);
        $("#phone").val(row.phone);
        $("#role").val(row.role);

        $("#password").val("");

        $("#userModal").modal("show");
    }

    // Print button functionality
    document.getElementById('print-btn').addEventListener('click', function() {
        // Get current date
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Get table data
        const table = document.getElementById('userTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        // Create print content
        let printContent = `
            <html>
            <head>
                <title>User List Report</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
                    .print-header h2 { margin: 0; color: #333; }
                    .print-header h4 { margin: 5px 0; color: #666; }
                    .report-info { margin-bottom: 20px; text-align: center; }
                    .print-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    .print-table th { background-color: #f2f2f2; font-weight: bold; padding: 10px; border: 1px solid #ddd; text-align: left; }
                    .print-table td { padding: 8px; border: 1px solid #ddd; }
                    .print-footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                    @media print {
                        @page { size: landscape; margin: 0.5cm; }
                        body { margin: 0; }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h2>Bangladesh Chemical Industries Corporation</h2>
                    <h4>BCIC Building, 30-31, Dilkusha C/A, Dhaka-1000</h4>
                    <h3>User Management Report</h3>
                </div>
                
                <div class="report-info">
                    <p><strong>Report Date:</strong> ${currentDate}</p>
                    <p><strong>Total Users:</strong> ${rows.length}</p>
                </div>
                
                <table class="print-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Factory</th>
                            <th>Full Name</th>
                            <th>Designation</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Default Password (123)</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Add table rows
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            printContent += '<tr>';
            for (let j = 0; j < cells.length - 1; j++) { // Skip actions column (last column)
                printContent += `<td>${cells[j].innerText}</td>`;
            }
            printContent += '</tr>';
        }

        printContent += `
                    </tbody>
                </table>
                
                <div class="print-footer">
                    <p>Report generated on: ${currentDate}</p>
                    <p>Design & Developed By ICT Division, Bangladesh Chemical Industries Corporation - User Management System</p>
                </div>
            </body>
            </html>
        `;

        // Open print window
        const printWindow = window.open('', '_blank', 'width=1200,height=800');
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Trigger print after content loads
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
        };
    });
</script>

</body>
</html>