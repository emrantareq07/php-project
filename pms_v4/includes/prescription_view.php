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
        throw new Exception("No prescription ID provided.");
    }

    $prescription_id = $_GET['id'];

    // Get prescription header information
    $stmt = $conn->prepare("SELECT p.*, u.name, u.emp_id, u.designation, u.division, u.section 
                           FROM prescription_tbl p
                           JOIN users u ON p.emp_id = u.emp_id
                           WHERE p.id = :id");
    $stmt->execute([':id' => $prescription_id]);
    $prescription = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prescription) {
        throw new Exception("Prescription not found.");
    }

    // Get prescription items with medicine names
    $stmt = $conn->prepare("SELECT pi.*, m.med_name as medicine_name 
                           FROM prescription_items pi
                           LEFT JOIN medicine_tbl m ON pi.medicine_id = m.id
                           WHERE pi.prescription_id = :id 
                           ORDER BY pi.id");
    $stmt->execute([':id' => $prescription_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>View Prescription - <?= htmlspecialchars($prescription['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .medicine-item {
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
            margin-bottom: 15px;
        }
        .medicine-name {
            font-weight: 600;
            color: #0d6efd;
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-prescription-bottle-alt text-primary me-2"></i>
            Prescription Details
        </h2>
        <div>
            <a href="prescription_list.php?id=<?= urlencode($prescription['emp_id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
            <a href="prescription_print.php?id=<?= $prescription_id ?>" target="_blank" class="btn btn-danger ms-2">
                <i class="fas fa-print me-1"></i> Print Prescription
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Prescription Information
                </h5>
                <span class="badge bg-light text-dark">
                    <?= date('M d, Y', strtotime($prescription['date'])) ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-success mb-3">
                        <i class="fas fa-disease me-2"></i>
                        <?= htmlspecialchars($prescription['disease_name']) ?>
                    </h5>
                    <?php if (!empty($prescription['advise'])): ?>
                        <p class="mb-3">
                            <strong><i class="fas fa-comment-medical me-2"></i>Advise:</strong>
                            <?= htmlspecialchars($prescription['advise']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-user me-2"></i>Patient Information</h6>
                            <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($prescription['name']) ?></p>
                            <p class="mb-1"><strong>Employee ID:</strong> <?= htmlspecialchars($prescription['emp_id']) ?></p>
                            <p class="mb-1"><strong>Designation:</strong> <?= htmlspecialchars($prescription['designation']) ?></p>
                            <p class="mb-1"><strong>Division/Section:</strong> <?= htmlspecialchars($prescription['division'] . ' / ' . $prescription['section']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3"><i class="fas fa-pills me-2"></i>Medicines Prescribed</h5>
            
            <?php if (empty($items)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No medicines prescribed in this prescription.
                </div>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Dosage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars(
                                        ($item['med_type'] ?? '') .". ". ($item['medicine_name'] ?? 'Unknown')
                                    ) ?>
                                </td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td><?= htmlspecialchars($item['unit']) ?></td>
                                <td><?= htmlspecialchars($item['dosage']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="prescription_entry.php?id=<?= urlencode($prescription['emp_id']) ?>&edit=<?= $prescription_id ?>" 
               class="btn btn-primary me-2">
                <i class="fas fa-send me-1"></i> Send Pharmacist
            </a>
             <a href="prescription_entry.php?id=<?= urlencode($prescription['emp_id']) ?>&edit=<?= $prescription_id ?>" 
               class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Edit Prescription
            </a>
            <a href="prescription_delete.php?id=<?= $prescription_id ?>&emp_id=<?= urlencode($prescription['emp_id']) ?>" 
               class="btn btn-danger"
               onclick="return confirm('Are you sure you want to delete this prescription?');">
                <i class="fas fa-trash-alt me-1"></i> Delete Prescription
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    setTimeout(function(){
        $('.alert').alert('close');
    }, 5000);
});
</script>
</body>
</html>