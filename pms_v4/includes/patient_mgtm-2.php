<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all patients with prescription count
    $stmt = $conn->prepare("
        SELECT u.*, COUNT(p.id) as prescription_count 
        FROM users u
        LEFT JOIN prescription_tbl p ON u.emp_id = p.emp_id
        GROUP BY u.id
        ORDER BY u.id DESC
    ");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: ../dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
        }
        .action-buttons {
            white-space: nowrap;
        }
        .badge-prescription {
            font-size: 0.85em;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .card-header {
            background-color: #f8f9fa;
        }
        .search-box {
            max-width: 300px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i> Patient Management
            </h5>
            <div class="no-print">
                <a href="../dashboard.php" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="patientTable" class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Division</th>
                            <th>Section</th>
                            <th>Prescriptions</th>
                            <th class="no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $index => $patient): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($patient['emp_id']) ?></td>
                            <td><?= htmlspecialchars($patient['name']) ?></td>
                            <td><?= htmlspecialchars($patient['designation']) ?></td>
                            <td><?= htmlspecialchars($patient['division']) ?></td>
                            <td><?= htmlspecialchars($patient['section']) ?></td>
                            <td>
                                <span class="badge bg-primary badge-prescription">
                                    <?= $patient['prescription_count'] ?> 
                                    <i class="fas fa-prescription ms-1"></i>
                                </span>
                            </td>
                            <td class="no-print action-buttons">
                                <a href="prescription_entry.php?id=<?= urlencode($patient['emp_id']) ?>" 
                                   class="btn btn-sm btn-success me-1"
                                   title="Create Prescription">
                                    <i class="fas fa-notes-medical"></i>
                                </a>
                                <a href="prescription_list.php?id=<?= urlencode($patient['emp_id']) ?>" 
                                   class="btn btn-sm btn-info me-1"
                                   title="View Prescriptions">
                                    <i class="fas fa-list"></i>
                                </a>
                                <a href="patient_details.php?id=<?= $patient['id'] ?>" 
                                   class="btn btn-sm btn-primary"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end no-print">
            <button onclick="window.print();" class="btn btn-sm btn-primary me-2">
                <i class="fas fa-print me-1"></i> Print
            </button>
            <button id="exportExcel" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable with export buttons
    var table = $('#patientTable').DataTable({
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                title: 'Patient_Management_Report',
                exportOptions: {
                    columns: ':not(.no-print)'
                }
            }
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search patients...",
            lengthMenu: "Show _MENU_ patients per page",
            info: "Showing _START_ to _END_ of _TOTAL_ patients",
            infoEmpty: "No patients found",
            infoFiltered: "(filtered from _MAX_ total patients)"
        },
        initComplete: function() {
            // Custom styling after table initialization
            $('.dataTables_filter input').addClass('form-control form-control-sm search-box');
            $('.dataTables_length select').addClass('form-select form-select-sm');
        }
    });

    // Trigger Excel export when the button is clicked
    $('#exportExcel').click(function() {
        table.button('.buttons-excel').trigger();
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
</body>
</html>