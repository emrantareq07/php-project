<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";

// Strong no-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: private, no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Login check
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
   $stmt = $conn->prepare("
    SELECT * FROM authority_tbl 
    WHERE batch = ?
    AND start_time != '00:00:00.000000'
    AND end_time != '00:00:00.000000'
    AND exam_date != '0000-00-00'
");
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
    }

    // Fetch participants for the selected batch
    $stmt = $conn->prepare("
        SELECT id, emp_id, name, designation, place_of_posting, email_id, mobile_no, question_all, answer_all 
        FROM users_tbl 
        WHERE batch=? 
    ");
    $stmt->bind_param("s", $selected_batch);
    $stmt->execute();
    $participants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Calculate correct counts and sort by correct answers (descending)
    foreach ($participants as &$p) {
        $correct_count = 0;
        $total = 0;
        
        if (!empty($p['question_all']) && !empty($p['answer_all'])) {
            // Parse questions and answers
            $user_questions = explode("','", trim($p['question_all'], "'"));
            $user_answers = explode("','", trim($p['answer_all'], "'"));
            
            foreach ($user_questions as $index => $q_id) {
                $qstmt = $conn->prepare("SELECT correct_option FROM question_set WHERE id=?");
                $qstmt->bind_param("i", $q_id);
                $qstmt->execute();
                $row = $qstmt->get_result()->fetch_assoc();
                $qstmt->close();
                
                if ($row) {
                    $total++;
                    $correct_letter = $row['correct_option'];
                    $user_letter = $user_answers[$index] ?? "N";
                    
                    if ($user_letter === $correct_letter) {
                        $correct_count++;
                    }
                }
            }
        }
        
        // Store calculated values in the participant array
        $p['calculated_total'] = $total;
        $p['calculated_correct'] = $correct_count;
    }
    unset($p); // Break the reference

    // Sort participants by correct count in descending order (more correct on top)
    usort($participants, function($a, $b) {
        return $b['calculated_correct'] - $a['calculated_correct'];
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Sheet - Training Certificate System</title>
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
        font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
    }

    .container {
        background-color: white;
        padding: 10px;
        border-radius: 10px;
        margin-top: 0px;
    }

    .result-table {
        font-size: 0.9rem;
        border-collapse: collapse;
        width: 100%;
    }

    .result-table th {
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

    .print-hide {
        display: none;
    }

    .combined-info {
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

    .result-cell {
        width: 60px;
        text-align: center;
        border-left: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
        vertical-align: middle;
    }

    .percentage-high {
        color: #198754;
        font-weight: bold;
    }

    .percentage-low {
        color: #dc3545;
        font-weight: bold;
    }

    /* Sorting indicator */
    .sort-indicator {
        font-size: 0.8rem;
        margin-left: 5px;
        color: #6c757d;
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

        .result-table th {
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
        <h2 class="mb-2 text-muted" id="header_title">Batch-wise Result Sheet</h2>
        
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
                <button type="submit" class="btn btn-primary"><i class="bi bi-eye"></i> Show Result Sheet</button>
                <?php if ($selected_batch && $authority_data): ?>
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
                <h4 class="text-uppercase fw-bold">Bangladesh Chemical Industries Corporation</h4>
                <h5>Training On "<?= htmlspecialchars($authority_data['training_title']) ?>"</h5>
             
                <p class="text-center">
                    <strong>Batch-</strong> <?= $authority_data['batch']; ?> ||
                    <strong>Duration:</strong> 
                    <?= date('d-m-Y', strtotime($authority_data['start_date'])) ?>
                    to
                    <?= date('d-m-Y', strtotime($authority_data['end_date'])) ?>
                </p>

                <h5 class="text-uppercase fw-bold text-center">Result Sheet Of Participants</h5>
                <p><strong>Venue:</strong> ICT Division(4th floor), BCIC Bhaban, 30‑31 Dilkusha C/A, Dhaka‑1000</p>
            </div>

            <!-- Result Sheet -->
            <div class="mb-3">
                <div class="text-center mb-3 no-print">
                    <h4 class="text-muted text-uppercase fw-bold">Bangladesh Chemical Industries Corporation</h4>
                    <h4 class="text-uppercase fw-bold">Result Sheet Of Participants</h4>
                    <h5><strong>Training Title:</strong> <?= htmlspecialchars($authority_data['training_title']) ?></h5>
                    <p>
                        <strong>Period:</strong> <?= date('d-m-Y', strtotime($authority_data['start_date'])) ?> to <?= date('d-m-Y', strtotime($authority_data['end_date'])) ?>
                    </p>
                </div>
                
                <div class="table-wrapper">
                    <table class="table table-bordered table-striped result-table">
                        <thead>
                            <tr>
                                <th rowspan="2" width="10" class="text-center align-middle">SL No</th>
                                <th rowspan="2" width="100" class="text-center align-middle">Participants List</th>
                                <th rowspan="2" width="80" class="text-center align-middle">Contact Info</th>  
                                <th colspan="4" class="text-center align-middle">
                                    Result
                                    <?php if (!empty($participants)): ?>
                                        <span class="sort-indicator no-print">(Sorted by Highest Correct ↑)</span>
                                    <?php endif; ?>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center result-cell">Total</th>
                                <th class="text-center result-cell">Correct</th>
                                <th class="text-center result-cell">Wrong</th>
                                <th class="text-center result-cell">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($participants)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No participants found for this batch.</td>
                                </tr>
                            <?php else: ?>
                                <?php $counter = 1; ?>
                                <?php foreach ($participants as $p): ?>
                                    <?php
                                    $total = $p['calculated_total'];

//   if ($total == 0) {
//     // Prepare the SQL query
//     $sql = "SELECT COUNT(ID) as total_count FROM question_set WHERE batch = ?";
    
//     // Use prepared statement to avoid SQL injection
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $selected_batch);
//     $stmt->execute();
    
//     // Get the result
//     $result = $stmt->get_result();
//     if ($row = $result->fetch_assoc()) {
//         $total = $row['total_count'];
//     }

// }

                                    $correct_count = $p['calculated_correct'];
                                    $wrong = $total - $correct_count;
                                    $percentage = ($total > 0) ? round(($correct_count / $total) * 100, 2) : 0;
                                    ?>
                                    <tr>
                                        <td class="text-center align-middle fw-bold"><?= $counter++ ?></td>
                                        <td class="combined-info">
                                            <strong><?= htmlspecialchars($p['name']) ?></strong>
                                            <br><?= htmlspecialchars($p['designation']) ?>
                                            <br><?= htmlspecialchars($p['place_of_posting']) ?>
                                        </td>
                                        <td class="combined-info">
                                            <?= htmlspecialchars($p['email_id']) ?>
                                            <br><?= htmlspecialchars($p['mobile_no']) ?>
                                        </td>
                                        <td class="text-center align-middle fw-bold result-cell"><?= $total ?></td>
                                        <td class="text-center align-middle fw-bold text-success result-cell"><?= $correct_count ?></td>
                                        <td class="text-center align-middle fw-bold text-danger result-cell"><?= $wrong ?></td>
                                        <td class="text-center align-middle result-cell">
                                            <span class="<?= ($percentage >= 50 ? 'percentage-high' : 'percentage-low') ?>">
                                                <?= $percentage ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    </table>

                         <tfoot class="table-secondary">
                            <tr>
                                <td colspan="7" class="text-end">
                                    <!-- <strong>Total Participants: <?= count($participants) ?></strong> -->
                                </td>
                            </tr>
                        </tfoot>

                </div>
            </div>
        <?php elseif ($selected_batch): ?>
            <div class="alert alert-warning no-print">
                No Exam is taken For the Batch.
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
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