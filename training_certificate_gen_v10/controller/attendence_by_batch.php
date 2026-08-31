<?php
session_name('training_certificate_gen_db');
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch distinct batches from authority_tbl
$res = $conn->query("
    SELECT DISTINCT batch, training_title 
    FROM authority_tbl 
    ORDER BY batch DESC
");

$batches = [];
while ($row = $res->fetch_assoc()) {
    $batches[] = $row;
}

$selected_batch = $_GET['batch'] ?? '';
$authority_data = null;
$date_range = [];
$participants = [];

if ($selected_batch) {
    // Get batch details from authority_tbl
    $stmt = $conn->prepare("SELECT * FROM authority_tbl WHERE batch = ?");
    $stmt->bind_param("s", $selected_batch);
    $stmt->execute();
    $result = $stmt->get_result();
    $authority_data = $result->fetch_assoc();
    
    if ($authority_data) {
        $start_date = new DateTime($authority_data['start_date']);
        $end_date = new DateTime($authority_data['end_date']);
        
        // Generate date range
        $interval = new DateInterval('P1D');
        $date_range_obj = new DatePeriod($start_date, $interval, $end_date->modify('+1 day'));
        
        foreach ($date_range_obj as $date) {
            $date_range[] = $date->format('d-m-Y');
        }
        
        // Fetch participants for this batch
        $participant_stmt = $conn->prepare("
            SELECT emp_id, name, designation, place_of_posting, email_id, mobile_no 
            FROM users_tbl 
            WHERE batch = ? 
            ORDER BY name ASC
        ");
        $participant_stmt->bind_param("s", $selected_batch);
        $participant_stmt->execute();
        $participant_result = $participant_stmt->get_result();
        
        while ($row = $participant_result->fetch_assoc()) {
            $participants[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Sheet - Training Certificate System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
           <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
<style>
     * {
            font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
        }
    body {
        background-color: #f8f9fa;
    }

    .container {
        background-color: white;
        padding: 10px;
        border-radius: 10px;
        margin-top: 0px;
    }

    .attendance-table {
        font-size: 0.9rem;
        border-collapse: collapse;
        width: 100%;
    }

    .attendance-table th {
        background-color: #e9ecef;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-wrapper {
        max-height: 600px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
    }

    .date-header {
        white-space: nowrap;
    }

    .print-hide {
        display: none;
    }

    .attendance-cell { 
        width: 40px; 
        text-align: center;
        border-left: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
    }

    .combined-info
    {
        white-space: pre-line;
        line-height: 1.2;
        font-size: 0.85rem;
    }

    .combined-contact {
        white-space: pre-line;
        line-height: 1.2;
        font-size: 0.85rem;
    }

    .print-title {
        display: none;
    }

    /* ================= PRINT SETTINGS ================= */
    @media print {

        body {
            background: #fff;
        }

        .print-hide,
        .no-print {
            display: none !important;
        }

        .container {
            padding: 0;
            margin: 0;
        }

        .table-wrapper {
            max-height: none;
            overflow: visible;
            border: none;
        }

        .attendance-table th {
            position: static;
        }

        #header_title {
            display: none !important;
        }

        /* Print title */
        .print-title { 
            display: block; 
            text-align: center;
            margin-bottom: 15px;
        }

        .print-title h4 {
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .print-title p {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        /* Prevent table breaking & page split lines */
        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        /* Manual page break if needed */
        .page-break {
            page-break-before: always;
        }


.combined-info {
    white-space: nowrap;
    overflow: visible;
    font-size: 0.85rem;
    line-height: 1.2;
    
}

    }

    /* ================= PAGE NUMBER (CHROME SAFE) ================= */
    @page {
        margin: 7mm;

        @bottom-right {
            content: "Page " counter(page);
            font-size: 12px;
        }
    }
</style>
</head>
<body>
    <?php require_once "includes/header.php"; ?>
    
    <div class="container">
        <h2 class="mb-2 text-muted" id="header_title">Batch-wise Attendance Sheet</h2>
        
        <!-- Batch Select Form -->
        <form method="get" class="row g-3 mb-4 print-hide">
            <div class="col-auto">
                <label for="batch" class="col-form-label fw-bold">Select Batch:</label>
            </div>
<div class="col-auto">
    <select name="batch" id="batch" class="form-select" required>
        <option value="">-- Choose Batch --</option>

        <?php foreach ($batches as $row): ?>
            <option value="<?= htmlspecialchars($row['batch']) ?>"
                <?= ($selected_batch==$row['batch']?'selected':'') ?>>

          <?= htmlspecialchars('Batch '.$row['batch'].' - '.$row['training_title']) ?>

            </option>
        <?php endforeach; ?>

    </select>
</div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-eye"></i> Show Attendance Sheet</button>
                <?php if ($selected_batch && $authority_data): ?>
                    <button type="button" class="btn btn-success" onclick="downloadAttendanceSheet()">
                        <i class="bi bi-download"></i> Download Excel
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-info"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </form>

        <?php if ($selected_batch && $authority_data): ?>
            <!-- Batch Information -->
            <div class="card mb-4 no-print">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Batch Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Batch No:</strong> <?= htmlspecialchars($authority_data['batch']) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Training Title:</strong> <?= htmlspecialchars($authority_data['training_title']) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Start Date:</strong> <?= date('d-m-Y', strtotime($authority_data['start_date'])) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>End Date:</strong> <?= date('d-m-Y', strtotime($authority_data['end_date'])) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Print-only title (only shows when printing) -->
            <div class="print-title">
                 <h4 class=" text-uppercase fw-bold">Bangladesh Chemical Industries Corporation</h4>
                    
                    <!-- <h5>Batch: <?= htmlspecialchars($selected_batch) ?></h5> -->
                    <h5>Training On "<?= htmlspecialchars($authority_data['training_title']) ?>"</h5>
                    <p class="text-center">
                        <!-- <strong>Training Title:</strong> <?= htmlspecialchars($authority_data['training_title']) ?> |  -->
                        <strong>Duration:</strong> <?= date('d-m-Y', strtotime($authority_data['start_date'])) ?> to <?= date('d-m-Y', strtotime($authority_data['end_date'])) ?>
                        
                    </p>

                    
                    <h5 class="text-uppercase fw-bold text-center">Attendance Sheet Of Participants</h5>
                    <p> <strong>Venue:</strong> ICT Division(4th floor), BCIC Bhaban, 30‑31 Dilkusha C/A, Dhaka‑1000</p>

            </div>

            <!-- Attendance Sheet (Printable Version) -->
            <div class="mb-3">
                <div class="text-center mb-3 no-print">
                    <h4 class="text-muted text-uppercase fw-bold">Bangladesh Chemical Industries Corporation</h4>
                    <h4 class="text-uppercase fw-bold">Attendance Sheet Of Participants</h4>
                    <!-- <h5>Batch: <?= htmlspecialchars($selected_batch) ?></h5> -->
                    <h5><strong>Training Title:</strong> <?= htmlspecialchars($authority_data['training_title']) ?></h5>
                    <p>
                        <!-- <strong>Training Title:</strong> <?= htmlspecialchars($authority_data['training_title']) ?> |  -->
                        <strong>Period:</strong> <?= date('d-m-Y', strtotime($authority_data['start_date'])) ?> to <?= date('d-m-Y', strtotime($authority_data['end_date'])) ?>
                    </p>
                </div>
                
                <div class="table-wrapper">
    <table class="table table-bordered table-striped attendance-table">
        <thead>
            <tr>
                <th rowspan="2" width="10" class="text-center align-middle">SL No</th>
                <th rowspan="2" width="100" class="text-center align-middle">Participants List</th>
                <th rowspan="2" width="80" class="text-center align-middle">Contact Info</th>  
                <th colspan="<?= count($date_range) ?>" class="text-center align-middle">Signature</th>
            </tr>
            <tr>
                <?php foreach ($date_range as $date): ?>
                    <th class="text-center date-header" style="min-width: 50px;">
                        <?= $date ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($participants)): ?>
                <tr>
                    <td colspan="<?= 4 + count($date_range) ?>" class="text-center">No participants found for this batch.</td>
                </tr>
            <?php else: ?>
                <?php $counter = 1; ?>
                <?php foreach ($participants as $participant): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $counter++ ?></td>
                        <td class="combined-info">
                            <strong><?= htmlspecialchars($participant['name']) ?></strong>
                            <br><?= htmlspecialchars($participant['emp_id']) ?>
                            <br><?= htmlspecialchars($participant['designation']) ?>
                            <br><?= htmlspecialchars($participant['place_of_posting']) ?>
                        </td>
                        <td class="combined-info">
                            <?= htmlspecialchars($participant['email_id']) ?>
                            <br><?= htmlspecialchars($participant['mobile_no']) ?>
                        </td>

                        
                        <?php foreach ($date_range as $date): ?>
                            <td class="attendance-cell text-center">
                                <!-- Empty cell for manual marking -->
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
            </div>
        <?php elseif ($selected_batch): ?>
            <div class="alert alert-warning">
                No batch details found for the selected batch.
            </div>
        <?php endif; ?>
    </div>

    <!-- Include SheetJS for Excel download -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    
    <script>
    // Download attendance sheet as Excel
    function downloadAttendanceSheet() {
        // Create workbook
        const wb = XLSX.utils.book_new();
        
        // Prepare data for Excel
        const excelData = [];
        
        // Add header row
        const headerRow = [
            'SL No', 
            'Name / Designation / Office', 
            'Email / Mobile'
        ];
        
        // Add date columns to header
        <?php foreach ($date_range as $date): ?>
            headerRow.push('<?= $date ?>');
        <?php endforeach; ?>
        
        excelData.push(headerRow);
        
        // Add participant rows (empty for manual filling)
        <?php $counter = 1; ?>
        <?php foreach ($participants as $participant): ?>
            const rowData<?= $counter ?> = [
                <?= $counter ?>,
                '<?= addslashes($participant['name']) . "\n" . addslashes($participant['designation']) . "\n" . addslashes($participant['place_of_posting']) ?>',
                '<?= $participant['email_id'] . "\n" . $participant['mobile_no'] ?>'
            ];
            
            // Add empty cells for each date
            <?php foreach ($date_range as $date): ?>
                rowData<?= $counter ?>.push('');
            <?php endforeach; ?>
            
            excelData.push(rowData<?= $counter ?>);
            <?php $counter++; ?>
        <?php endforeach; ?>
        
        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(excelData);
        
        // Set column widths
        const wscols = [
            {wch: 5},   // SL No
            {wch: 35},  // Combined Name/Designation/Office
            {wch: 25},  // Combined Email/Mobile
        ];
        
        // Add date column widths
        <?php foreach ($date_range as $date): ?>
            wscols.push({wch: 12});
        <?php endforeach; ?>
        
        ws['!cols'] = wscols;
        
        // Add workbook properties
        XLSX.utils.book_append_sheet(wb, ws, 'Attendance');
        
        // Generate file name
        const fileName = 'Attendance_Sheet_Batch_<?= $selected_batch ?>_<?= date('Ymd_His') ?>.xlsx';
        
        // Save file
        XLSX.writeFile(wb, fileName);
    }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script>
// Disable Back & Forward
history.pushState(null, null, location.href);
window.onpopstate = function () {
   window.location.href = "reload_handler.php";
};


// If page reloads, destroy session and redirect
// Prevent reload
if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.location.href = "reload_handler.php";
}



window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        window.location.href = "../index.php";
    }
});
</script>