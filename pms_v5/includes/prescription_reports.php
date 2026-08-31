<?php
session_start();
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "pms_db";

$is_searched = isset($_GET['search']);
$start_date  = $_GET['start_date'] ?? date('Y-m-d');
$end_date    = $_GET['end_date'] ?? date('Y-m-d');
$emp_id      = trim($_GET['emp_id'] ?? '');

$prescriptions = [];
$error_msg     = null;

if ($is_searched) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch matching prescriptions along with patient and doctor details
        $sql = "
            SELECT 
                p.*, 
                e.name AS patient_name, 
                e.division, 
                e.section, 
                e.employee_type,
                u.name AS doctor_name,
                u.designation AS user_doc_designation,
                u.signature AS doctor_signature
            FROM patient_tbl p
            LEFT JOIN employees e ON p.emp_id = e.emp_id
            LEFT JOIN user u ON p.doctor_emp_id = u.emp_id
            WHERE p.date BETWEEN :start_date AND :end_date
        ";
        
        $params = [
            ':start_date' => $start_date,
            ':end_date'   => $end_date
        ];

        if (!empty($emp_id)) {
            $sql .= " AND p.emp_id = :emp_id";
            $params[':emp_id'] = $emp_id;
        }

        $sql .= " ORDER BY p.date DESC, p.id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $error_msg = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Search & Report - BCIC Medical Center</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .prescription-box {
            max-width: 850px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            position: relative;
        }

        .table-medicines th {
            background-color: #f1f5f9 !important;
            color: #334155;
        }

        .signature-img {
            max-height: 55px;
            max-width: 160px;
            object-fit: contain;
        }

        .patient-info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }

        .patient-info-table .label {
            font-weight: 600;
            color: #495057;
            width: 15%;
            white-space: nowrap;
        }

        .patient-info-table .value {
            width: 35%;
            color: #212529;
        }

        .info-block-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 10px;
        }

        /* PRINT STYLES */
        @media print {
            .no-print { 
                display: none !important; 
            }

            body, html { 
                background: #ffffff !important; 
                padding: 0 !important; 
                margin: 0 !important;
            }

            .container { 
                max-width: 100% !important; 
                width: 100% !important; 
                padding: 0 !important; 
                margin: 0 !important;
            }

            .prescription-box { 
                box-shadow: none !important; 
                border: none !important;
                margin: 0 auto !important; 
                width: 100% !important; 
                max-width: 100% !important; 
                padding: 0 !important;
                /* Forces every prescription onto a new page */
                page-break-after: always;
            }

            .prescription-box:last-child {
                page-break-after: auto;
            }

            table.table-bordered, 
            table.table-bordered th, 
            table.table-bordered td { 
                border: 1px solid #000000 !important; 
            }

            @page {
                size: A4;
                margin: 12mm;
            }
        }
    </style>
</head>
<body>

<div class="container my-3">

    <!-- Search Form (Hidden on Print) -->
    <div class="card shadow-sm mb-4 no-print">
        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Find Prescriptions</h6>
            <a href="patient_mgtm.php" class="btn btn-sm btn-light text-primary fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
        <div class="card-body py-3">
            <form method="GET" action="" class="row g-2 align-items-end">
                <input type="hidden" name="search" value="1">
                
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted mb-1">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="<?= htmlspecialchars($start_date) ?>" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="<?= htmlspecialchars($end_date) ?>" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted mb-1">Employee ID <span class="fw-normal text-muted">(Optional)</span></label>
                    <input type="text" name="emp_id" class="form-control form-control-sm" placeholder="e.g. 5620-1" value="<?= htmlspecialchars($emp_id) ?>">
                </div>
                
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <?php if ($is_searched && !empty($prescriptions)): ?>
                        <button type="button" onclick="window.print()" class="btn btn-sm btn-success w-100 fw-bold">
                            <i class="fas fa-print me-1"></i> Print All
                        </button>
                        <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Error Handling -->
    <?php if ($error_msg): ?>
        <div class="alert alert-danger shadow-sm py-2 no-print"><?= htmlspecialchars($error_msg) ?></div>
    <?php elseif (!$is_searched): ?>
        <!-- Default State Prompt -->
        <div class="card text-center py-5 shadow-sm border-0 no-print">
            <div class="card-body">
                <i class="fas fa-prescription fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Search Prescriptions</h5>
                <p class="text-muted small mb-0">Select a date range or filter by Employee ID to list and print prescriptions.</p>
            </div>
        </div>
    <?php elseif (empty($prescriptions)): ?>
        <div class="alert alert-warning text-center shadow-sm py-3 no-print">
            <i class="fas fa-exclamation-circle me-1"></i> No prescriptions found for the selected criteria.
        </div>
    <?php else: ?>

        <!-- Results Summary Bar (Screen Only) -->
        <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center no-print">
            <small class="mb-0">Found <strong><?= count($prescriptions) ?></strong> prescription(s) matching your criteria.</small>
            <button onclick="window.print()" class="btn btn-sm btn-success fw-bold">
                <i class="fas fa-print me-1"></i> Print Prescriptions
            </button>
        </div>

        <!-- Loop and Display All Found Prescriptions -->
        <?php foreach ($prescriptions as $prescription): ?>
            <?php
            // Parse Medicines String
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

            // Doctor details and signature
            $doctor_name = $prescription['doctor_name'] ?? 'Dr. Medical Officer';
            $doctor_designation = $prescription['doctor_designation'] ?? ($prescription['user_doc_designation'] ?? 'Medical Officer');
            $signature_file = $prescription['doctor_signature'] ?? '';
            $signature_path_physical = __DIR__ . '/../uploads/signatures/' . $signature_file;
            $signature_url = '../uploads/signatures/' . $signature_file;
            ?>

            <div class="prescription-box mb-4">  

                <!-- Header -->
                <div class="row pb-2 mb-2 align-items-center">
                    <div class="col-12 d-flex align-items-center justify-content-center text-center">
                        <img src="uploads/bcic_logo.png" height="60" width="auto" alt="BCIC Logo" class="me-3">
                        <div>
                            <h5 class="fw-bold text-primary mb-0">BANGLADESH CHEMICAL INDUSTRIES CORPORATION</h5>
                            <h6 class="fw-bold text-success mb-0">BCIC MEDICAL CENTER</h6>
                            <p class="text-muted mb-0 small" style="font-size: 0.75rem;">
                                BCIC Bhaban, 10th Floor, Dilkusha C/A, Motijheel, Dhaka-1000.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2" style="font-size: 0.8rem;">
                        <span class="me-3"><strong>Date:</strong> <?= date('d-M-Y', strtotime($prescription['date'])) ?></span>
                        <span><strong>Rx ID:</strong> #<?= sprintf('%05d', $prescription['id']) ?></span>
                    </div>
                </div>

                <!-- Patient Profile Card -->
                <div class="card border mb-2">
                    <div class="card-body p-1 px-2">
                        <table class="table table-borderless mb-0 patient-info-table" style="font-size: 0.78rem;">
                            <tbody>
                                <tr>
                                    <td class="label p-1">Patient Name:</td>
                                    <td class="value p-1"><strong><?= htmlspecialchars($prescription['patient_name'] ?? 'N/A') ?></strong></td>
                                    <td class="label p-1">Designation:</td>
                                    <td class="value p-1"><?= htmlspecialchars($prescription['emp_designation'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="label p-1">Employee ID:</td>
                                    <td class="value p-1"><?= htmlspecialchars($prescription['emp_id'] ?? 'N/A') ?></td>
                                    <td class="label p-1">Division/Section:</td>
                                    <td class="value p-1"><?= htmlspecialchars(trim(($prescription['division'] ?? '') . ', ' . ($prescription['section'] ?? '')) ?: 'N/A') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Diagnosis / Clinical Notes -->
                <?php if (!empty($prescription['diseases'])): ?>
                    <div class="info-block-box py-1 px-2 mb-2 small">
                        <strong class="text-primary d-block mb-0">Diagnosis / Clinical Notes:</strong>
                        <div class="text-dark"><?= nl2br(htmlspecialchars($prescription['diseases'])) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Medicine Table -->
                <div class="mb-2">
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
                                            <?= htmlspecialchars(!empty(trim($med['unit'])) ? trim($med['unit']) . ' - ' . $med['name'] : $med['name']) ?>
                                        </td>
                                        <td class="text-center fw-bold"><?= htmlspecialchars($med['qty']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($med['dosage']) ?></td>
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

                <!-- Doctor's Advice -->
                <?php if (!empty($prescription['advice'])): ?>
                    <div class="info-block-box py-1 px-2 mb-3 small" style="border-left-color: #198754;">
                        <strong class="text-success d-block mb-0">Doctor's Advice:</strong>
                        <div class="text-dark"><?= nl2br(htmlspecialchars($prescription['advice'])) ?></div>
                    </div>
                <?php endif; ?>

                <!-- Signatures -->
                <div class="row pt-4 align-items-end" style="font-size: 0.8rem;">
                    <div class="col-4 text-center">
                        <div class="border-top border-dark pt-1">
                            <strong>Medicine Receiver</strong>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="border-top border-dark pt-1">
                            <strong>Pharmacist</strong>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <?php if (!empty($signature_file) && file_exists($signature_path_physical)): ?>
                            <img src="<?= htmlspecialchars($signature_url) ?>" alt="Doctor Signature" class="signature-img mb-1 d-block mx-auto">
                        <?php else: ?>
                            <div style="height: 40px;"></div>
                        <?php endif; ?>
                        <div class="border-top border-dark pt-1">
                            <strong><?= htmlspecialchars($doctor_name) ?></strong>
                            <small class="text-muted d-block"><?= htmlspecialchars($doctor_designation) ?></small>
                            <small class="text-muted d-block">Emp ID: <?= htmlspecialchars($prescription['doctor_emp_id']) ?></small>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

</body>
</html>