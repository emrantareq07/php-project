<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

// Check if form has been submitted
$is_searched = isset($_GET['search']);

$start_date = $_GET['start_date'] ?? date('Y-m-d'); // Default to today
$end_date   = $_GET['end_date'] ?? date('Y-m-d');    // Default to today
$emp_id     = trim($_GET['emp_id'] ?? '');

$registered_report   = [];
$custom_report       = [];
$total_prescriptions = 0;
$total_reg_qty       = 0;
$total_custom_qty    = 0;
$patient_info        = null;

if ($is_searched) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 1. Get all registered medicine names
        $stmtMeds = $conn->query("SELECT LOWER(TRIM(med_name)) AS med_name FROM medicine_tbl");
        $registered_db_meds = $stmtMeds->fetchAll(PDO::FETCH_COLUMN);
        $registered_db_set  = array_flip($registered_db_meds);

        // 2. Fetch Patient Profile details if filtered by Employee ID
        if (!empty($emp_id)) {
            $stmtEmp = $conn->prepare("
                SELECT 
                    e.name AS patient_name, 
                    e.employee_type, 
                    e.division, 
                    p.emp_designation
                FROM patient_tbl p
                LEFT JOIN employees e ON p.emp_id = e.emp_id
                WHERE p.emp_id = :emp_id
                LIMIT 1
            ");
            $stmtEmp->execute([':emp_id' => $emp_id]);
            $patient_info = $stmtEmp->fetch(PDO::FETCH_ASSOC);
        }

        // 3. Build Main Query
        $sql = "SELECT medicine FROM patient_tbl WHERE date BETWEEN :start_date AND :end_date";
        $params = [
            ':start_date' => $start_date,
            ':end_date'   => $end_date
        ];

        if (!empty($emp_id)) {
            $sql .= " AND emp_id = :emp_id";
            $params[':emp_id'] = $emp_id;
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_prescriptions = count($rows);

        // 4. Parse Delimited Medicines and Aggregate Quantities
        foreach ($rows as $row) {
            $raw_medicines = $row['medicine'] ?? '';
            if (empty(trim($raw_medicines))) continue;

            $items = explode('||', $raw_medicines);

            foreach ($items as $item) {
                $parts = array_map('trim', explode(',', $item));
                
                $med_name = $parts[0] ?? '';
                if ($med_name === '') continue;

                $qty  = (int)($parts[2] ?? 1);
                $unit = !empty($parts[3]) ? $parts[3] : 'N/A';

                $med_key = mb_strtolower($med_name);

                if (isset($registered_db_set[$med_key])) {
                    $group_key = $med_key . '|' . mb_strtolower($unit);
                    if (!isset($registered_report[$group_key])) {
                        $registered_report[$group_key] = [
                            'name'      => $med_name,
                            'unit'      => $unit,
                            'total_qty' => 0
                        ];
                    }
                    $registered_report[$group_key]['total_qty'] += $qty;
                    $total_reg_qty += $qty;
                } else {
                    if (!isset($custom_report[$med_key])) {
                        $custom_report[$med_key] = [
                            'name'      => $med_name,
                            'unit'      => $unit,
                            'total_qty' => 0
                        ];
                    }
                    $custom_report[$med_key]['total_qty'] += $qty;
                    $total_custom_qty += $qty;
                }
            }
        }

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
    <title>Medicine Consumption Report - BCIC Medical Center</title>
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

        .report-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            background: #ffffff;
        }

        .table-compact th, .table-compact td {
            padding: 6px 12px;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .patient-card-info td {
            padding: 4px 10px;
            font-size: 0.825rem;
        }

        .metric-badge {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
        }

        /* Print Optimization Rules */
        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                background: #fff !important; 
                padding: 0 !important; 
                margin: 0 !important;
                font-size: 11pt;
            }
            .container { 
                max-width: 100% !important; 
                width: 100% !important; 
                padding: 0 !important; 
            }
            .card { 
                border: none !important; 
                box-shadow: none !important; 
            }
            .table-bordered th, .table-bordered td { 
                border: 1px solid #000 !important; 
            }
            .text-primary, .text-success, .text-warning, .text-secondary { 
                color: #000 !important; 
            }
            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: transparent !important;
            }
        }
    </style>
</head>
<body>

<div class="container my-3">

    <!-- Action Bar / Filter Form (Hidden on Print) -->
    <div class="card shadow-sm mb-3 no-print">
        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Report Parameters</h6>
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
                        <i class="fas fa-search me-1"></i> Generate
                    </button>
                    <?php if ($is_searched): ?>
                        <button type="button" onclick="window.print()" class="btn btn-sm btn-success w-100 fw-bold">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                        <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Error Alert -->
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger shadow-sm py-2"><?= htmlspecialchars($error_msg) ?></div>
    <?php elseif (!$is_searched): ?>
        <!-- Initial Prompt State -->
        <div class="card text-center py-5 shadow-sm border-0 no-print">
            <div class="card-body">
                <i class="fas fa-file-medical-alt fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Generate Medicine Consumption Report</h5>
                <p class="text-muted small mb-0">Select your date parameters above to view real-time issued medicine data.</p>
            </div>
        </div>
    <?php else: ?>

    <!-- Main Printable Report Container -->
    <div class="report-card shadow-sm p-4">
        
        <!-- Document Header -->
        <div class="row align-items-center border-bottom pb-3 mb-3">
            <div class="col-2 text-center">
                <img src="uploads/bcic_logo.png" height="75" width="auto" alt="BCIC Logo">
            </div>
            <div class="col-8 text-center">
                <h5 class="fw-bold text-primary mb-0">BANGLADESH CHEMICAL INDUSTRIES CORPORATION</h5>
                <h6 class="fw-bold text-success mb-1">BCIC MEDICAL CENTER</h6>
                <p class="text-muted mb-1 style-small" style="font-size: 0.78rem;">
                    <i class="fas fa-map-marker-alt me-1"></i> BCIC Bhaban, 10th Floor, Dilkusha C/A, Motijheel, Dhaka-1000.
                </p>
                <h5 class="fw-bold text-dark text-decoration-underline mb-0 mt-2">MEDICINE CONSUMPTION REPORT</h5>
            </div>
            <div class="col-2 text-end">
                <div class="border rounded p-2 bg-light text-center small">
                    <span class="d-block text-muted" style="font-size: 0.7rem;">REPORT PERIOD</span>
                    <strong style="font-size: 0.75rem;"><?= date('d-M-Y', strtotime($start_date)) ?></strong>
                    <span class="d-block text-muted" style="font-size: 0.68rem;">to</span>
                    <strong style="font-size: 0.75rem;"><?= date('d-M-Y', strtotime($end_date)) ?></strong>
                </div>
            </div>
        </div>

        <!-- Summary Metrics Bar (Hidden on print or adapted) -->
        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
            <div class="d-flex gap-2">
                <span class="badge bg-primary metric-badge">
                    <i class="fas fa-file-prescription me-1"></i> Prescriptions: <strong><?= $total_prescriptions ?></strong>
                </span>
                <span class="badge bg-success metric-badge">
                    <i class="fas fa-boxes me-1"></i> Inventory Meds Issued: <strong><?= $total_reg_qty ?></strong>
                </span>
                <span class="badge bg-warning text-dark metric-badge">
                    <i class="fas fa-pills me-1"></i> Custom Meds Issued: <strong><?= $total_custom_qty ?></strong>
                </span>
            </div>
            <span class="text-muted small">Generated on: <?= date('d-M-Y h:i A') ?></span>
        </div>

        <!-- Employee Info Card (Appears only when searching by Employee ID) -->
        <?php if (!empty($emp_id)): ?>
            <div class="card border mb-3">
                <div class="card-body p-2">
                    <strong class="text-primary d-block mb-1 style-small" style="font-size: 0.8rem;">
                        <i class="fas fa-id-card me-1"></i> Employee Profile Details
                    </strong>
                    <table class="table table-borderless mb-0 patient-card-info">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted" style="width: 15%;">Employee ID:</td>
                                <td class="fw-bold text-dark" style="width: 35%;"><?= htmlspecialchars($emp_id) ?></td>
                                <td class="fw-bold text-muted" style="width: 15%;">Designation:</td>
                                <td class="text-dark" style="width: 35%;"><?= htmlspecialchars($patient_info['emp_designation'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Patient Name:</td>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($patient_info['patient_name'] ?? 'N/A') ?></td>
                                <td class="fw-bold text-muted">Division:</td>
                                <td class="text-dark"><?= htmlspecialchars($patient_info['division'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Employee Type:</td>
                                <td class="text-dark"><?= htmlspecialchars($patient_info['employee_type'] ?? 'N/A') ?></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Table 1: Registered Inventory Medicines -->
        <div class="mb-4">
            <h6 class="fw-bold text-success mb-2">
                <i class="fas fa-check-circle me-1"></i> 1. Registered Inventory Medicines Summary
            </h6>
            <table class="table table-bordered table-striped align-middle table-compact mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">#</th>
                        <th style="width: 55%;">Medicine Name</th>
                        <th class="text-center" style="width: 20%;">Unit</th>
                        <th class="text-center" style="width: 20%;">Total Quantity Issued</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($registered_report)): ?>
                        <?php $i = 1; foreach ($registered_report as $item): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $i++ ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['unit']) ?></span></td>
                                <td class="text-center fw-bold text-primary fs-6"><?= number_format($item['total_qty']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Total Row -->
                        <tr class="table-light fw-bold">
                            <td colspan="3" class="text-end">Total Registered Quantity:</td>
                            <td class="text-center text-primary fs-6"><?= number_format($total_reg_qty) ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No registered inventory medicines were issued within this date range.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Table 2: Custom / Unregistered Medicines -->
        <div class="mb-3">
            <h6 class="fw-bold text-danger mb-2">
                <i class="fas fa-exclamation-triangle me-1"></i> 2. Custom / Non-Inventory Medicines Summary
            </h6>
            <table class="table table-bordered table-striped align-middle table-compact mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">#</th>
                        <th style="width: 55%;">Custom Medicine Name</th>
                        <th class="text-center" style="width: 20%;">Unit</th>
                        <th class="text-center" style="width: 20%;">Total Quantity Issued</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($custom_report)): ?>
                        <?php $j = 1; foreach ($custom_report as $item): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $j++ ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['unit']) ?></span></td>
                                <td class="text-center fw-bold text-danger fs-6"><?= number_format($item['total_qty']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Total Row -->
                        <tr class="table-light fw-bold">
                            <td colspan="3" class="text-end">Total Custom Quantity:</td>
                            <td class="text-center text-danger fs-6"><?= number_format($total_custom_qty) ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No custom or non-inventory medicines recorded in this date range.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Signature Footer (Visible mainly on print) -->
        <div class="row mt-5 pt-4 text-center d-none d-print-flex">
            <div class="col-4">
                <div class="border-top border-dark pt-1 small">Prepared By</div>
            </div>
            <div class="col-4">
                <div class="border-top border-dark pt-1 small">Verified By</div>
            </div>
            <div class="col-4">
                <div class="border-top border-dark pt-1 small">Authorized Signatory</div>
            </div>
        </div>

    </div>

    <?php endif; ?>
</div>

</body>
</html>