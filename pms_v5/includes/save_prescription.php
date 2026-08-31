<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction(); // Start transaction for multiple inserts

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $emp_id = $_POST['emp_id'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        $disease_name = $_POST['disease_name'] ?? '';
        $advise = $_POST['advise'] ?? '';
        
        // Get medicine data (arrays)
        $medicine_ids = $_POST['medicine_id'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $units = $_POST['unit'] ?? [];
        $dosages = $_POST['dosage'] ?? [];

        // Validate required fields
        if (empty($emp_id) || empty($date) || empty($disease_name) || empty($medicine_ids)) {
            throw new Exception("Please fill in all required fields.");
        }

        // Validate array lengths match
        if (count($medicine_ids) !== count($quantities) || 
            count($medicine_ids) !== count($units) || 
            count($medicine_ids) !== count($dosages)) {
            throw new Exception("Invalid medicine data submitted.");
        }

        // First insert prescription header
        $stmt = $conn->prepare("
            INSERT INTO prescription_tbl (emp_id, date, disease_name, advise)
            VALUES (:emp_id, :date, :disease_name, :advise)
        ");
        
        $stmt->execute([
            ':emp_id' => $emp_id,
            ':date' => $date,
            ':disease_name' => $disease_name,
            ':advise' => $advise
        ]);
        
        $prescription_id = $conn->lastInsertId();

        // Then insert each medicine item
        $stmt = $conn->prepare("
            INSERT INTO prescription_items 
            (prescription_id, medicine_id, quantity, unit, dosage)
            VALUES (:prescription_id, :medicine_id, :quantity, :unit, :dosage)
        ");

        // Also update medicine stock (if your system tracks inventory)
        $updateStmt = $conn->prepare("
            UPDATE medicine_tbl SET quantity = quantity - :deduct 
            WHERE id = :medicine_id AND quantity >= :deduct
        ");

        for ($i = 0; $i < count($medicine_ids); $i++) {
            $medicine_id = $medicine_ids[$i];
            $quantity = $quantities[$i];
            $unit = $units[$i];
            $dosage = $dosages[$i];
            
            // Validate numeric quantity
            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception("Invalid quantity for medicine ID: $medicine_id");
            }
            
            // Insert prescription item
            $stmt->execute([
                ':prescription_id' => $prescription_id,
                ':medicine_id' => $medicine_id,
                ':quantity' => $quantity,
                ':unit' => $unit,
                ':dosage' => $dosage
            ]);
            
            // Update medicine stock (optional)
            if (isset($updateStmt)) {
                $updateStmt->execute([
                    ':medicine_id' => $medicine_id,
                    ':deduct' => $quantity
                ]);
                
                // Check if stock was actually updated
                if ($updateStmt->rowCount() == 0) {
                    throw new Exception("Insufficient stock for medicine ID: $medicine_id");
                }
            }
        }

        $conn->commit(); // Commit transaction if all inserts succeeded
        $_SESSION['success'] = "Prescription saved successfully!";
        header("Location: prescription_list.php?id=" . urlencode($emp_id));
        exit();
        
    } else {
        throw new Exception("Invalid request method.");
    }
} catch(PDOException $e) {
    if (isset($conn)) {
        $conn->rollBack(); // Rollback on error
    }
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: prescription_entry.php?id=" . urlencode($emp_id));
    exit();
} catch(Exception $e) {
    if (isset($conn)) {
        $conn->rollBack();
    }
    $_SESSION['error'] = $e->getMessage();
    header("Location: prescription_entry.php?id=" . urlencode($emp_id));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Save Prescription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Prescription Status</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
                <p>Redirecting to dashboard in 3 seconds...</p>
                <meta http-equiv="refresh" content="3;url=prescription_list.php?id=<?= urlencode($emp_id) ?>">
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
                <p>Redirecting back in 5 seconds...</p>
                <meta http-equiv="refresh" content="5;url=prescription_entry.php?id=<?= urlencode($emp_id) ?>">
            <?php endif; ?>
            
            <div class="d-grid gap-2">
                <?php if (isset($_SESSION['success'])): ?>
                    <a href="prescription_list.php?id=<?= urlencode($emp_id) ?>" class="btn btn-success">
                        <i class="fas fa-list"></i> View Prescriptions
                    </a>
                <?php else: ?>
                    <a href="prescription_entry.php?id=<?= urlencode($emp_id) ?>" class="btn btn-warning">
                        <i class="fas fa-arrow-left"></i> Try Again
                    </a>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Return to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>