
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("No employee ID provided.");
    }

    $emp_id = $_GET['id'];

    // Get employee information
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = :emp_id");
    $stmt->execute([':emp_id' => $emp_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        throw new Exception("Employee not found.");
    }

    // Fetch prescriptions directly from patient_tbl
    $stmt = $conn->prepare("
        SELECT id, emp_id,emp_designation, doctor_emp_id, doctor_designation, medicine,special_med, advice, diseases, date, created_at, updated_at
        FROM patient_tbl
        WHERE emp_id = :emp_id
        ORDER BY date DESC, id DESC
    ");
    $stmt->execute([':emp_id' => $emp_id]);
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: dashboard.php");
    exit();
} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription List - <?= htmlspecialchars($employee['name'] ?? '') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 20px; }
        }
        .prescription-card {
            transition: all 0.3s ease;
        }
        .prescription-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .badge-prescription {
            font-size: 0.9em;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2>
            <i class="fas fa-prescription-bottle-alt text-primary me-2"></i>
            Prescriptions for <?= htmlspecialchars($employee['name'] ?? '') ?>
        </h2>
        <div>
            <a href="prescription_entry.php?id=<?= urlencode($emp_id) ?>" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i> New Prescription
            </a>
            <a href="patient_mgtm.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Patient Manage
            </a>
        </div>
    </div>

    <div class="card shadow mb-4 no-print">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Employee Information
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Employee ID:</strong> <?= htmlspecialchars($employee['emp_id'] ?? '') ?></p>
                    <p><strong>Name:</strong> <?= htmlspecialchars($employee['name'] ?? '') ?></p>
                    <p><strong>Designation:</strong> <?= htmlspecialchars($employee['designation'] ?? '') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Division:</strong> <?= htmlspecialchars($employee['division'] ?? '') ?></p>
                    <p><strong>Section:</strong> <?= htmlspecialchars($employee['section'] ?? '') ?></p>
                    <p><strong>Employee Type:</strong> <?= htmlspecialchars($employee['employee_type'] ?? '') ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($prescriptions)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No prescriptions found for this employee.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($prescriptions as $prescription): ?>
                <?php 
                    // Parse medicines string into array items
                    $med_items = array_filter(array_map('trim', explode('||', $prescription['medicine'] ?? '')));
                    $item_count = count($med_items);
                ?>
                <div class="col-md-6 mb-4">
                    <div class="card prescription-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-day text-primary me-2"></i>
                                <?= !empty($prescription['date']) ? date('M d, Y', strtotime($prescription['date'])) : 'N/A' ?>
                            </h5>
                            <span class="float-end badge bg-primary badge-prescription">
                                <?= $item_count ?> item<?= $item_count != 1 ? 's' : '' ?>
                            </span>

                            <?php if (!empty(trim($prescription['special_med'] ?? ''))): ?>
                                <span class="badge bg-success badge-prescription">
                                    <?= htmlspecialchars($prescription['special_med']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-success mb-2">
                                <i class="fas fa-disease me-2"></i>
                                <?= htmlspecialchars($prescription['diseases'] ?? 'N/A') ?>
                            </h6>
                            
                            <!-- Display medicines preview -->
                            <?php if (!empty($med_items)): ?>
                                <div class="mb-3">
                                    <small class="text-muted d-block fw-bold mb-1">
                                        <i class="fas fa-pills me-1"></i> Medicines:
                                    </small>
                                    <ul class="list-unstyled mb-0 ps-2 border-start border-2 border-primary">
                                        <?php foreach ($med_items as $med): ?>
                                            <li class="small text-secondary mb-1">
                                                <i class="fas fa-angle-right me-1 text-primary"></i> <?= htmlspecialchars($med) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($prescription['advice'])): ?>
                                <p class="card-text">
                                    <strong><i class="fas fa-comment-medical me-2"></i>Advice:</strong>
                                    <?= nl2br(htmlspecialchars($prescription['advice'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="prescription_view.php?id=<?= $prescription['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </a>
                                <div class="no-print">
                                    <a href="prescription_print.php?id=<?= $prescription['id'] ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-secondary me-2">
                                        <i class="fas fa-print me-1"></i> Print
                                    </a>
                                    <a href="prescription_entry.php?id=<?= urlencode($emp_id) ?>&edit=<?= $prescription['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning me-2">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <a href="prescription_entry.php?id=<?= urlencode($emp_id) ?>&clone=<?= $prescription['id'] ?>" 
                                       class="btn btn-sm btn-outline-info me-2">
                                        <i class="fas fa-copy me-1"></i> Clone
                                    </a>
                                    <a href="prescription_delete.php?id=<?= $prescription['id'] ?>&emp_id=<?= urlencode($emp_id) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this prescription?');">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function(){
        $('.alert').alert('close');
    }, 5000);
});
</script>
</body>
</html>

```