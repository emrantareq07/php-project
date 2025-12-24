<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

date_default_timezone_set("Asia/Dhaka");

// Get parameters
$designation = isset($_GET['designation']) ? urldecode($_GET['designation']) : '';
$committee_ids = isset($_GET['committee_ids']) ? $_GET['committee_ids'] : [];

if (empty($designation)) {
    die("<div class='alert alert-danger'>Designation parameter is missing.</div>");
}

if (empty($committee_ids)) {
    die("<div class='alert alert-danger'>No committees selected. Please select at least one committee.</div>");
}

// Convert committee_ids to array if it's not already
if (!is_array($committee_ids)) {
    $committee_ids = [$committee_ids];
}

// Validate and sanitize committee IDs
$valid_committee_ids = [];
foreach ($committee_ids as $id) {
    $id = intval($id);
    if ($id > 0) {
        $valid_committee_ids[] = $id;
    }
}

if (empty($valid_committee_ids)) {
    die("<div class='alert alert-danger'>Invalid committee IDs.</div>");
}

if (count($valid_committee_ids) < 2) {
    die("<div class='alert alert-warning'>Please select at least TWO committees to combine.</div>");
}

$committee_ids_str = implode(',', $valid_committee_ids);

// First, verify all selected committees have the same designation
$verifyQuery = "SELECT COUNT(DISTINCT designation) as diff_designations FROM exam_schedule_tbl WHERE id IN ($committee_ids_str)";
$verifyResult = mysqli_query($conn, $verifyQuery);
$verifyRow = mysqli_fetch_assoc($verifyResult);

if ($verifyRow['diff_designations'] > 1) {
    die("<div class='alert alert-danger'>Selected committees have different designations. Cannot combine.</div>");
}

// Get the actual designation from database
$actualDesignationQuery = "SELECT designation FROM exam_schedule_tbl WHERE id IN ($committee_ids_str) LIMIT 1";
$actualDesignationResult = mysqli_query($conn, $actualDesignationQuery);
$actualDesignationRow = mysqli_fetch_assoc($actualDesignationResult);
$actual_designation = $actualDesignationRow['designation'];

// Fetch committee names and dates
$committee_names = [];
$committeeQuery = "SELECT id, committe_name, date, time FROM exam_schedule_tbl WHERE id IN ($committee_ids_str) ORDER BY date, time";
$committeeResult = mysqli_query($conn, $committeeQuery);
while ($row = mysqli_fetch_assoc($committeeResult)) {
    $committee_names[$row['id']] = [
        'name' => $row['committe_name'],
        'date' => $row['date'],
        'time' => $row['time']
    ];
}

// Fetch all candidates from selected committees with marks (based on your merit list query)
$query = "SELECT 
    c.*,
    est.committe_name,
    est.date as exam_date,
    COALESCE(v.written_marks, 0) as written_marks,
    COALESCE(v.total_viva, 0) as total_viva,
    COALESCE(v.avg_viva, 0) as avg_viva,
    COALESCE(v.total_marks, 0) as total_marks
FROM candidates_tbl c
JOIN exam_schedule_tbl est ON c.exam_schedule_id = est.id
LEFT JOIN (
    SELECT 
        candidate_id,
        exam_schedule_id,
        written_marks,
        SUM(viva_marks) as total_viva,
        AVG(viva_marks) as avg_viva,
        (written_marks + AVG(viva_marks)) as total_marks
    FROM (
        SELECT 
            c.id as candidate_id,
            c.exam_schedule_id,
            c.written_marks,
            COALESCE(vm.viva_marks, 0) as viva_marks
        FROM candidates_tbl c
        LEFT JOIN viva_marks_tbl vm ON c.id = vm.candidate_id
        WHERE c.exam_schedule_id IN ($committee_ids_str)
        AND (vm.exam_schedule_id IN ($committee_ids_str) OR vm.exam_schedule_id IS NULL)
    ) as marks_data
    GROUP BY candidate_id, exam_schedule_id, written_marks
) v ON c.id = v.candidate_id AND c.exam_schedule_id = v.exam_schedule_id
WHERE c.exam_schedule_id IN ($committee_ids_str)
    AND c.designation = ?
ORDER BY v.total_marks DESC, v.written_marks DESC, c.roll_no ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $actual_designation);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$candidates = [];
while ($row = mysqli_fetch_assoc($result)) {
    $candidates[] = $row;
}

// Calculate position (handle ties correctly)
$position = 0;
$prev_total = null;
$same_count = 1;

foreach ($candidates as &$candidate) {
    $current_total = (float)$candidate['total_marks'];
    
    if ($prev_total !== null && $current_total != $prev_total) {
        $position = $same_count;
    }
    
    if ($prev_total === null) {
        $position = 1;
    }
    
    $candidate['merit_position'] = $position;
    $prev_total = $current_total;
    $same_count++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Combine Merit List - <?= htmlspecialchars($actual_designation) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
@media print { 
    .no-print { display:none !important; } 
    body { font-size: 12px; }
    .merit-badge { transform: scale(0.8); }
    .position-1, .position-2, .position-3, .position-top10 {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
body { background:#fff; }
.header { text-align:center; margin-bottom:20px; border-bottom: 3px double #333; padding-bottom: 15px; }
.header h2 { color:#2c3e50; margin-bottom:5px; }
.header h3 { color:#2c3e50; margin-bottom:10px; }
.merit-table th { background:#2c3e50; color:white; }
.position-1 { background:#ffd700 !important; } /* Gold */
.position-2 { background:#c0c0c0 !important; } /* Silver */
.position-3 { background:#cd7f32 !important; color:white !important; } /* Bronze */
.position-top10 { background:#d4edda !important; } /* Top 10 */
.merit-badge { font-size: 0.7em; padding: 2px 8px; }
.signature-box { border-top: 2px solid #000; padding-top: 10px; margin-top: 30px; }
.stats-box { background:#f8f9fa; border:1px solid #dee2e6; border-radius:5px; padding:15px; margin-bottom:20px; }
.committee-tag {
    background: #e9ecef;
    border-radius: 15px;
    padding: 3px 10px;
    margin: 2px;
    font-size: 0.85em;
    display: inline-block;
}
.combine-badge {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: bold;
}
</style>
</head>
<body>
<div class="container mt-3">
    <!-- Header -->
    <div class="header">
        <div class="no-print d-flex justify-content-between mb-3 flex-wrap gap-2">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print Combined Merit List
            </button>
            <button class="btn btn-secondary" onclick="history.back()">
                <i class="bi bi-arrow-left"></i> Back to Committees
            </button>
            <span class="combine-badge">
                <i class="bi bi-trophy-fill"></i> COMBINED MERIT LIST
            </span>
        </div>
        
        <h2>BANGLADESH CHEMICAL INDUSTRIES CORPORATION (BCIC)</h2>
        <h3>BCIC Bhaban, 30-31, Dilkusha C/A, Dhaka</h3>
        <h4 class="text-primary">FINAL COMBINED MERIT LIST</h4>
        
        <div class="stats-box">
            <div class="row">
                <div class="col-md-3">
                    <strong>Post/Designation:</strong><br>
                    <span class="fw-bold text-primary"><?= htmlspecialchars($actual_designation) ?></span>
                </div>
                <div class="col-md-6">
                    <strong>Combined Committees:</strong><br>
                    <?php foreach ($committee_names as $committee): ?>
                        <span class="committee-tag">
                            <i class="bi bi-building"></i> <?= htmlspecialchars($committee['name']) ?>
                            (<?= date('d/m/Y', strtotime($committee['date'])) ?>)
                        </span>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-3">
                    <strong>Total Candidates:</strong><br>
                    <span class="badge bg-primary" style="font-size: 1.1em;"><?= count($candidates) ?></span><br>
                    <small class="text-muted">From <?= count($committee_names) ?> committees</small>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        This is a combined merit list from multiple committees for the same designation.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Merit List Table -->
    <table class="table table-bordered merit-table align-middle">
        <thead>
            <tr>
                <th width="50" class="text-center">Merit</th>
                <th width="50" class="text-center">#</th>
                <th width="80" class="text-center">Roll No</th>
                <th width="250">Candidate Name</th>
                <th width="120">Father's Name</th>
                <th width="100" class="text-center">Committee</th>
                <th width="80" class="text-center">Written<br>Marks</th>
                <th width="80" class="text-center">Avg Viva<br>Marks</th>
                <th width="100" class="text-center">Total Marks<br>(Written + Viva)</th>
                <th width="100" class="text-center">Remarks</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        
        if (count($candidates) > 0) {
            foreach ($candidates as $row) {
                $merit = $row['merit_position'];
                
                $position_class = '';
                if ($merit == 1) $position_class = 'position-1';
                elseif ($merit == 2) $position_class = 'position-2';
                elseif ($merit == 3) $position_class = 'position-3';
                elseif ($merit <= 10) $position_class = 'position-top10';
                
                $badge_color = '';
                if ($merit == 1) $badge_color = 'bg-warning text-dark';
                elseif ($merit == 2) $badge_color = 'bg-secondary';
                elseif ($merit == 3) $badge_color = 'bg-danger';
                else $badge_color = 'bg-info';
        ?>
        <tr class="<?= $position_class ?>">
            <td class="text-center fw-bold">
                <span class="badge <?= $badge_color ?> merit-badge"><?= $merit ?></span>
            </td>
            <td class="text-center"><?= $i ?></td>
            <td class="text-center fw-bold"><?= htmlspecialchars($row['roll_no']) ?></td>
            <td>
                <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                <small class="text-muted">Mother: <?= htmlspecialchars($row['mothers_name']) ?></small>
            </td>
            <td><?= htmlspecialchars($row['fathers_name']) ?></td>
            <td class="text-center">
                <span class="badge bg-dark" style="font-size: 0.8em;">
                    <?= htmlspecialchars($row['committe_name']) ?>
                </span>
            </td>
            <td class="text-center fw-bold"><?= number_format($row['written_marks'], 2) ?></td>
            <td class="text-center fw-bold"><?= number_format($row['avg_viva'], 2) ?></td>
            <td class="text-center fw-bold bg-success text-white">
                <?= number_format($row['total_marks'], 2) ?>
            </td>
            <td class="text-center">
                <?php 
                if ($merit <= 5) {
                    echo '<span class="badge bg-success">Selected</span>';
                } elseif ($merit <= 10) {
                    echo '<span class="badge bg-warning">Waiting</span>';
                } else {
                    echo '<span class="badge bg-secondary">Not Selected</span>';
                }
                ?>
            </td>
        </tr>
        <?php 
                $i++;
            }
        } else { ?>
        <tr>
            <td colspan="10" class="text-center text-muted py-4">
                <i class="bi bi-people display-6 d-block mb-2"></i>
                No candidates found in the selected committees.
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Summary Statistics -->
    <div class="row no-print mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Combined Marks Statistics</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (count($candidates) > 0) {
                        $total_marks_array = array_column($candidates, 'total_marks');
                        $written_marks_array = array_column($candidates, 'written_marks');
                        $viva_marks_array = array_column($candidates, 'avg_viva');
                        
                        $max_total = max($total_marks_array);
                        $min_total = min($total_marks_array);
                        $avg_total = array_sum($total_marks_array) / count($total_marks_array);
                        
                        $max_written = max($written_marks_array);
                        $avg_written = array_sum($written_marks_array) / count($written_marks_array);
                        
                        $max_viva = max($viva_marks_array);
                        $avg_viva = array_sum($viva_marks_array) / count($viva_marks_array);
                        
                        echo "<p><strong>Highest Total Marks:</strong> " . number_format($max_total, 2) . "</p>";
                        echo "<p><strong>Lowest Total Marks:</strong> " . number_format($min_total, 2) . "</p>";
                        echo "<p><strong>Average Total Marks:</strong> " . number_format($avg_total, 2) . "</p>";
                        echo "<hr>";
                        echo "<p><strong>Highest Written:</strong> " . number_format($max_written, 2) . "</p>";
                        echo "<p><strong>Average Written:</strong> " . number_format($avg_written, 2) . "</p>";
                        echo "<p><strong>Highest Viva:</strong> " . number_format($max_viva, 2) . "</p>";
                        echo "<p><strong>Average Viva:</strong> " . number_format($avg_viva, 2) . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-award"></i> Selection Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded mb-3">
                                <h3 class="text-success"><?= count($candidates) ?></h3>
                                <p class="mb-0"><small>Total Candidates</small></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded mb-3">
                                <h3 class="text-primary"><?= count($committee_names) ?></h3>
                                <p class="mb-0"><small>Committees Combined</small></p>
                            </div>
                        </div>
                    </div>
                    
                    <p><strong>Selection Criteria:</strong></p>
                    <div class="alert alert-info py-2">
                        <i class="bi bi-check-circle"></i> <strong>Top 5:</strong> Recommended for Selection
                    </div>
                    <div class="alert alert-warning py-2">
                        <i class="bi bi-clock"></i> <strong>Rank 6-10:</strong> Waiting List
                    </div>
                    <div class="alert alert-secondary py-2">
                        <i class="bi bi-x-circle"></i> <strong>Others:</strong> Not Selected
                    </div>
                    
                    <hr>
                    <p class="text-muted"><small>
                        <i class="bi bi-info-circle"></i> 
                        In case of tie, written marks will be considered first.
                    </small></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Committee-wise Summary -->
    <?php if (count($candidates) > 0): ?>
    <div class="row no-print mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-building"></i> Committee-wise Performance</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        $committee_stats = [];
                        foreach ($committee_names as $id => $committee) {
                            $committee_candidates = array_filter($candidates, function($c) use ($id) {
                                return $c['exam_schedule_id'] == $id;
                            });
                            
                            $committee_stats[$committee['name']] = [
                                'count' => count($committee_candidates),
                                'top_merit' => null,
                                'avg_marks' => 0
                            ];
                            
                            if (count($committee_candidates) > 0) {
                                $marks = array_column($committee_candidates, 'total_marks');
                                $committee_stats[$committee['name']]['avg_marks'] = array_sum($marks) / count($marks);
                                
                                // Find best merit position from this committee
                                $best_merit = min(array_column($committee_candidates, 'merit_position'));
                                $committee_stats[$committee['name']]['top_merit'] = $best_merit;
                            }
                        }
                        
                        foreach ($committee_stats as $committee_name => $stats): 
                        ?>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-truncate" title="<?= htmlspecialchars($committee_name) ?>">
                                        <?= htmlspecialchars(substr($committee_name, 0, 20)) ?><?= strlen($committee_name) > 20 ? '...' : '' ?>
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded">
                                                <h4 class="mb-0 text-primary"><?= $stats['count'] ?></h4>
                                                <small>Candidates</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded">
                                                <h4 class="mb-0 text-success">
                                                    <?= $stats['top_merit'] ? '#' . $stats['top_merit'] : '-' ?>
                                                </h4>
                                                <small>Best Rank</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small>Avg: <?= number_format($stats['avg_marks'], 2) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="mt-4 text-center">
        <p class="text-muted">
            <i class="bi bi-calendar-check"></i> 
            Combined Merit List Generated on: <?= date('d-m-Y h:i A') ?>
        </p>
        <div class="no-print">
            <small class="text-muted">
                <i class="bi bi-shield-check"></i> 
                Official Combined Merit List - Bangladesh Chemical Industries Corporation (BCIC)
            </small>
        </div>
    </div>
</div>

<script>
// Highlight top positions
document.addEventListener('DOMContentLoaded', function() {
    const topRows = document.querySelectorAll('.position-1, .position-2, .position-3');
    topRows.forEach(row => {
        row.addEventListener('click', function() {
            this.style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.style.transform = '';
            }, 300);
        });
    });
    
    // Export to Excel
    window.exportToExcel = function() {
        let html = '<html><head><meta charset="UTF-8"><title>Combined Merit List</title></head><body>';
        html += '<h2>BCIC - Combined Merit List</h2>';
        html += '<h3>Designation: <?= htmlspecialchars($actual_designation) ?></h3>';
        html += '<p>Committees: ';
        <?php foreach ($committee_names as $committee): ?>
            html += '<?= htmlspecialchars($committee['name']) ?>, ';
        <?php endforeach; ?>
        html += '</p>';
        html += '<p>Generated: <?= date('d-m-Y h:i A') ?></p>';
        
        // Clone table
        let table = document.querySelector('.merit-table').cloneNode(true);
        // Remove any buttons or no-print elements
        let noPrintElements = table.querySelectorAll('.no-print');
        noPrintElements.forEach(el => el.remove());
        
        html += table.outerHTML;
        html += '</body></html>';
        
        let blob = new Blob([html], {type: 'application/vnd.ms-excel'});
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'BCIC_Combined_Merit_<?= date('Y-m-d') ?>.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
});

// Add print shortcut
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print();
    }
});
</script>
</body>
</html>