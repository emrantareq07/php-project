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
        throw new Exception("Prescription ID is required.");
    }

    $prescription_id = $_GET['id'];

    // 1. Fetch Prescription along with Employee Data
    $stmt = $conn->prepare("
        SELECT p.*, 
               e.name AS patient_name, 
               e.division, 
               e.section, 
               e.employee_type,
               e.dob,
               e.gender
        FROM patient_tbl p
        LEFT JOIN employees e ON p.emp_id = e.emp_id
        WHERE p.id = :id
    ");
    $stmt->execute([':id' => $prescription_id]);
    $prescription = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prescription) {
        throw new Exception("Prescription not found.");
    }

    // 2. Fetch Doctor Details & Signature from users table
    $stmtDoctor = $conn->prepare("
        SELECT name, designation, signature 
        FROM user 
        WHERE emp_id = :doctor_emp_id 
        LIMIT 1
    ");
    $stmtDoctor->execute([':doctor_emp_id' => $prescription['doctor_emp_id']]);
    $doctor = $stmtDoctor->fetch(PDO::FETCH_ASSOC);

    // Doctor information fallbacks
    $doctor_name = $doctor['name'] ?? 'Dr. Medical Officer';
    $doctor_designation = $prescription['doctor_designation'] ?? ($doctor['designation'] ?? 'Medical Officer');
    
    // Signature Paths
    $signature_file = $doctor['signature'] ?? '';
    $signature_path_physical = __DIR__ . '/../uploads/signatures/' . $signature_file;
    $signature_url = '../uploads/signatures/' . $signature_file;

    // 3. Parse Medicines String into structured array
    $medicines = [];
    $raw_medicines = $prescription['medicine'] ?? '';
    
    if (!empty($raw_medicines)) {
        $items = explode('||', $raw_medicines);
        foreach ($items as $item) {
            $parts = array_map('trim', explode(',', $item));
            if (!empty($parts[0])) {
                $medicines[] = [
                    'name'     => $parts[0] ?? '',
                    'brand'    => $parts[1] ?? '',
                    'qty'      => $parts[2] ?? '1',
                    'unit'     => $parts[3] ?? '',
                    'dosage'   => $parts[4] ?? '',
                    'duration' => $parts[5] ?? ''
                ];
            }
        }
    }

} catch (Exception $e) {
    die("<div style='color:red; font-family:sans-serif; padding:20px;'>Error: " . htmlspecialchars($e->getMessage()) . "</div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription_<?= htmlspecialchars($prescription['emp_id']) ?>_<?= date('Ymd', strtotime($prescription['date'])) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .prescription-box {
            max-width: 850px;
            margin: 20px auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
        }
        .rx-symbol {
            font-size: 2.5rem;
            font-weight: bold;
            font-family: serif;
            color: #0d6efd;
        }
        .table-medicines th {
            background-color: #f1f5f9 !important;
            color: #334155;
        }
        .signature-img {
            max-height: 70px;
            max-width: 180px;
            object-fit: contain;
        }

        /* Clean Patient Info Formatting */
        .patient-info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
     

        /* Section Block Style for Diagnosis & Advice */
        .info-block-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
            padding: 5px 6px;
            margin-bottom: 0px;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: #fff;
            }
            .no-print {
                display: none !important;
            }
            .prescription-box {
                box-shadow: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>
<body>

<!-- Control Header for Screen Viewing -->
<div class="container text-center mt-3 no-print">
    <button onclick="window.print()" class="btn btn-primary me-2">
        <i class="fas fa-print me-1"></i> Print Prescription
    </button>
    <button onclick="window.close()" class="btn btn-secondary">
        <i class="fas fa-times me-1"></i> Close
    </button>
</div>

<div class="prescription-box">  

<div class="row pb-3 mb-0 align-items-center">
    <!-- Left Section: Logo & Organization Info -->
    <div class="col-md-12 d-flex align-items-center mb-1 mb-md-0">
        <img src="uploads/bcic_logo.png" height="70" width="auto" alt="BCIC Logo" class="me-3">
        <div>
            <h4 class="fw-bold text-primary mb-1 fs-4 text-center">Bangladesh Chemical Industries Corporation (BCIC)</h4>
            <h5 class="fw-bold text-success mb-1 fs-6 text-center">BCIC MEDICAL CENTER</h5>
            <p class="text-muted mb-0 small text-center">
                <i class="fas fa-map-marker-alt me-1"></i> BCIC Bhaban, 10th Floor, Dilkusha, C/A-1000, Motijheel, Dhaka.
            </p>
        </div>
    </div>

    <!-- Right Section: Date & Rx ID (Without bottom border line) -->
    <div class=" text-center ms-auto" style="font-size: 0.75rem; color: #000;">
        <div class="d-flex justify-content-center align-items-center gap-1 text-dark">
            <span><strong class="fw-bold text-dark">Date:</strong> <?= !empty($prescription['date']) ? date('d-M-Y', strtotime($prescription['date'])) : date('d-M-Y') ?></span>
            <span class="fw-bold text-dark">|</span>
            <span><strong class="fw-bold text-dark">Rx ID:</strong> #<?= sprintf('%05d', $prescription['id']) ?></span>
        </div>
    </div>
</div>

    <!-- Patient Information (Structured Clean Horizontal Grid) -->
 <div class="card border mb-1">
    <div class="card-body p-1 px-2">
        <table class="table table-borderless mb-0 patient-info-table" style="font-size: 0.75rem; color: #000;">
            <tbody>
                <tr>
                    <td class="label p-1" style="color: #000;">Name:</td>
                    <td class="value p-1" style=" color: #000;"><strong><?= htmlspecialchars($prescription['patient_name'] ?? 'N/A') ?></strong></td>
                    <td class="label p-1" style=" color: #000;">Designation:</td>
                    <td class="value p-1" style=" color: #000;"><?= htmlspecialchars($prescription['emp_designation'] ?? 'N/A') ?></td>
                    <td class="label p-1" style=" color: #000;">Gender:</td>
                    <td class="value p-1" style=" color: #000;"><?= htmlspecialchars($prescription['gender'] ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td class="label p-1" style="color: #000;">Employee ID:</td>
                    <td class="value p-1" style="color: #000;"><?= htmlspecialchars($prescription['emp_id'] ?? 'N/A') ?></td>

                    <td class="label p-1" style="color: #000;">Office:</td>
                    <td class="value p-1" style="color: #000;"><?= htmlspecialchars(trim(($prescription['division'] ?? '') . ', ' . ($prescription['section'] ?? '')) ?: 'N/A') ?></td>
                    <td class="label p-1" style="color: #000;">Age:</td>
                    <td class="value p-1" style="color: #000;">
                    <?php
                    $dob_str = $prescription['dob'] ?? null;
                    // Use prescription date if available, otherwise default to current date
                    $ref_date_str = $prescription['date'] ?? null; 

                    if (!empty($dob_str)) {
                        try {
                            $dob = new DateTime($dob_str);
                            $ref_date = !empty($ref_date_str) ? new DateTime($ref_date_str) : new DateTime();
                            
                            // Calculate the difference between DOB and Reference Date
                            $diff = $dob->diff($ref_date);
                            
                            echo "{$diff->y}Y, {$diff->m}M, {$diff->d}D"; 
                        } catch (Exception $e) {
                            echo "N/A";
                        }
                    } else {
                        echo "N/A";
                    }
                    ?>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>

    <?php if (!empty($prescription['diseases'])): ?>
    <div class="info-block-box py-1 px-1 mb-2 small">
        <strong class="text-primary d-block mb-0">
             Diagnosis / Clinical Notes:
        </strong>
        <div class="text-dark">
            <?= nl2br(htmlspecialchars($prescription['diseases'])) ?>
        </div>
    </div>
<?php endif; ?>
<div class="mb-0">
    <table class="table table-sm table-bordered align-middle table-medicines small w-100">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">SL</th>
                <th style="width: 45%;">Medicine Name</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 20%;" class="text-center">Dosage</th>
                <th style="width: 20%;" class="text-center">Duration</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($medicines)): ?>
                <?php foreach ($medicines as $index => $med): ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $index + 1 ?></td>
                        <td class="fw-bold text-primary">
                            <?= htmlspecialchars(
                                !empty(trim($med['unit'] ?? '')) 
                                    ? trim($med['unit']) . ' - ' . ($med['name'] ?? '') 
                                    : ($med['name'] ?? '')
                            ) ?>
                        </td>
                        <td class="text-center fw-bold"><?= htmlspecialchars($med['qty']) ?></td>
                        <td class="text-center"><span class=" "><?= htmlspecialchars($med['dosage']) ?></span></td>
                        <td class="text-center"><?= htmlspecialchars($med['duration']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No medicines prescribed.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <!-- Doctor's Advice Section Box -->
    <?php if (!empty($prescription['advice'])): ?>
        <div class="info-block-box py-1 px-1 mb-2 small mb-0" style="border-left-color: #198754;">
            <strong class="text-success d-block mb-0">
             Doctor's Advice:
            </strong>
            <!-- <i class="fas fa-comment-medical me-1"></i>  --> 
            <div class="text-dark">
                <?= nl2br(htmlspecialchars($prescription['advice'])) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Signature Section -->
    <div class="row pt-2 mt-0 align-items-end">
        
        <div class="col-4 text-end">
            <div class="d-inline-block text-center" style="min-width: 200px;">
                                
                <div class="border-top border-dark pt-1">
                    <strong class="d-block mb-0">Medicine Receiver</strong>
                    
                </div>
            </div>
        </div>
        <div class="col-4 text-end">
            <div class="d-inline-block text-center" style="min-width: 200px;">
                <!-- Pharmasist Signature Check -->
                <?php if (!empty($signature_file) && file_exists($signature_path_physical)): ?>
                    <img src="<?= htmlspecialchars($signature_url) ?>" alt="Doctor Signature" class="signature-img mb-1">
                <?php else: ?>
                    <div style="height: 50px;"></div>
                <?php endif; ?>
                
                <div class="border-top border-dark pt-1">
                    <strong class="d-block mb-0"><?//= htmlspecialchars($doctor_name) ?> Pharmasist</strong>
                    <small class="text-muted d-block"><?= htmlspecialchars($doctor_designation) ?></small>
                    <small class="text-muted d-block">Emp ID: <?= htmlspecialchars($prescription['doctor_emp_id']) ?></small>
                </div>
            </div>
        </div>
        <div class="col-4 text-end mb-2">
            <div class="d-inline-block text-center" style="min-width: 200px;">
                <!-- Doctor Signature Check -->
                <?php if (!empty($signature_file) && file_exists($signature_path_physical)): ?>
                    <img src="<?= htmlspecialchars($signature_url) ?>" alt="Doctor Signature" class="signature-img mb-1">
                <?php else: ?>
                    <div style="height: 50px;"></div>
                <?php endif; ?>
                
                <div class="border-top border-dark pt-1">
                    <strong class="d-block mb-0"><?= htmlspecialchars($doctor_name) ?></strong>
                    <small class="text-muted d-block"><?= htmlspecialchars($doctor_designation) ?></small>
                    <small class="text-muted d-block">Emp ID: <?= htmlspecialchars($prescription['doctor_emp_id']) ?></small>
                </div>
            </div>
        </div>
        <div class="col-12 pt-1 text-center ">
            <small class="text-muted d-block">Generated on: <?= date('d-M-Y h:i A') ?> | This is an official system-generated prescription.</small>
            
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>