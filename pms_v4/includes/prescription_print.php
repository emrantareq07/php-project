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
    die("Database error: " . $e->getMessage());
} catch(Exception $e) {
    die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Prescription - <?= htmlspecialchars($prescription['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            background: white;
            color: black;
        }
        .prescription-header {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .medicine-item {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            display: inline-block;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="prescription-header text-center">
        <h2>Medical Prescription</h2>
        <p class="mb-0">Date: <?= date('M d, Y', strtotime($prescription['date'])) ?></p>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Diagnosis: <?= htmlspecialchars($prescription['disease_name']) ?></h5>
            <?php if (!empty($prescription['advise'])): ?>
                <p><strong>Advise:</strong> <?= htmlspecialchars($prescription['advise']) ?></p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h5>Patient Information</h5>
            <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($prescription['name']) ?></p>
            <p class="mb-1"><strong>Employee ID:</strong> <?= htmlspecialchars($prescription['emp_id']) ?></p>
            <p class="mb-1"><strong>Designation:</strong> <?= htmlspecialchars($prescription['designation']) ?></p>
            <p class="mb-1"><strong>Division/Section:</strong> <?= htmlspecialchars($prescription['division'] . ' / ' . $prescription['section']) ?></p>
        </div>
    </div>

    <h5>Medicines Prescribed:</h5>
    
    <?php if (!empty($items)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="30%">Medicine Name</th>
                    <th width="15%">Quantity</th>
                    <th width="15%">Unit</th>
                    <th width="40%">Dosage</th>
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
    <?php else: ?>
        <p>No medicines prescribed.</p>
    <?php endif; ?>

    <div class="footer text-center">
        <div class="signature-line"></div>
        <p class="mt-2">Doctor's Signature</p>
    </div>
</div>

<script>
// Automatically trigger print when page loads
window.onload = function() {
    setTimeout(function() {
        window.print();
    }, 200);
    
    // After printing, close the window (optional)
    window.onafterprint = function() {
        window.close();
    };
};
</script>
</body>
</html>