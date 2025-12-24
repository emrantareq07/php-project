<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_GET['exam_schedule_id']) || !isset($_GET['committee'])) {
    die("Invalid request! Missing parameters.");
}

$exam_schedule_id = mysqli_real_escape_string($conn, $_GET['exam_schedule_id']);
$committee = mysqli_real_escape_string($conn, $_GET['committee']);
$designation = isset($_GET['designation']) ? mysqli_real_escape_string($conn, $_GET['designation']) : '';

// Fetch exam info
$infoQuery = "SELECT * FROM exam_schedule_tbl WHERE id = '$exam_schedule_id' LIMIT 1";
$infoResult = mysqli_query($conn, $infoQuery);
$info = mysqli_fetch_assoc($infoResult);

$title = $info['title'] ?? 'No Title';
$date = isset($info['date']) && $info['date'] ? date('d-m-Y', strtotime($info['date'])) : '-';

// Fetch all candidates with their total marks
$candidateQuery = "SELECT 
    c.*,
    COALESCE(v.written_marks, 0) as written_marks,
    COALESCE(v.total_viva, 0) as total_viva,
    COALESCE(v.avg_viva, 0) as avg_viva,
    COALESCE(v.total_marks, 0) as total_marks
FROM candidates_tbl c
LEFT JOIN (
    SELECT 
        candidate_id,
        written_marks,
        SUM(viva_marks) as total_viva,
        AVG(viva_marks) as avg_viva,
        (written_marks + AVG(viva_marks)) as total_marks
    FROM (
        SELECT 
            c.id as candidate_id,
            c.written_marks,
            COALESCE(vm.viva_marks, 0) as viva_marks
        FROM candidates_tbl c
        LEFT JOIN viva_marks_tbl vm ON c.id = vm.candidate_id
        WHERE c.exam_schedule_id = '$exam_schedule_id'
        AND vm.exam_schedule_id = '$exam_schedule_id'
    ) as marks_data
    GROUP BY candidate_id, written_marks
) v ON c.id = v.candidate_id
WHERE c.exam_schedule_id = '$exam_schedule_id'
ORDER BY v.total_marks DESC, v.written_marks DESC, c.roll_no ASC";

$candidateResult = mysqli_query($conn, $candidateQuery);

// Fetch committee members for signature
$membersQuery = "SELECT * FROM committee_tbl 
                 WHERE exam_schedule_id = '$exam_schedule_id' 
                 ORDER BY FIELD(type, 'Chairman', 'Member Secretary', 'Member')";
$membersResult = mysqli_query($conn, $membersQuery);
$committeeMembers = [];
while ($member = mysqli_fetch_assoc($membersResult)) {
    $committeeMembers[] = $member;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Merit List - <?= htmlspecialchars($committee) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
@media print { 
    .no-print { display:none !important; } 
    body { font-size: 12px; }
    .merit-badge { transform: scale(0.8); }
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
</style>
</head>
<body>
<div class="container mt-3">
    <!-- Header -->
    <div class="header">
        <div class="no-print d-flex justify-content-between mb-3">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print Merit List
            </button>
            <button class="btn btn-secondary" onclick="history.back()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <a href="print_candidates.php?exam_schedule_id=<?= $exam_schedule_id ?>&committee=<?= urlencode($committee) ?>" 
               class="btn btn-info">
               <i class="bi bi-list-check"></i> View Details
            </a>
        </div>
        
        <h2>BANGLADESH CHEMICAL INDUSTRIES CORPORATION (BCIC)</h2>
        <h3>BCIC Bhaban, 30-31, Dilkusha C/A, Dhaka</h3>
        <h4 class="text-primary">FINAL MERIT LIST</h4>
        
        <div class="stats-box">
            <div class="row">
                <div class="col-md-3">
                    <strong>Post:</strong> <?= htmlspecialchars($designation) ?>
                </div>
                <div class="col-md-3">
                    <strong>Committee:</strong> <?= htmlspecialchars($committee) ?>
                </div>
                <div class="col-md-3">
                    <strong>Exam Date:</strong> <?= $date ?>
                </div>
                <div class="col-md-3">
                    <strong>Total Candidates:</strong> 
                    <span class="badge bg-primary"><?= mysqli_num_rows($candidateResult) ?></span>
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
                <th width="100" class="text-center">District</th>
                <th width="80" class="text-center">Written<br>Marks</th>
                <th width="80" class="text-center">Avg Viva<br>Marks</th>
                <th width="100" class="text-center">Total Marks<br>(Written + Viva)</th>
                <th width="100" class="text-center">Remarks</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $merit = 1;
        $prev_total = null;
        
        if (mysqli_num_rows($candidateResult) > 0) {
            while ($row = mysqli_fetch_assoc($candidateResult)) {
                $current_total = (float)$row['total_marks'];
                
                // Handle same marks (same merit position)
                if ($prev_total !== null && $current_total != $prev_total) {
                    $merit = $i;
                }
                
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
            <td class="text-center"><?= htmlspecialchars($row['district']) ?></td>
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
                $prev_total = $current_total;
            }
        } else { ?>
        <tr>
            <td colspan="10" class="text-center text-muted py-4">
                <i class="bi bi-trophy display-6 d-block mb-2"></i>
                No candidates found for merit list generation.
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
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Marks Distribution</h6>
                </div>
                <div class="card-body">
                    <?php
                    mysqli_data_seek($candidateResult, 0);
                    $marks_array = [];
                    while ($row = mysqli_fetch_assoc($candidateResult)) {
                        $marks_array[] = (float)$row['total_marks'];
                    }
                    
                    if (count($marks_array) > 0) {
                        $max_marks = max($marks_array);
                        $min_marks = min($marks_array);
                        $avg_marks = array_sum($marks_array) / count($marks_array);
                        
                        echo "<p><strong>Highest Marks:</strong> " . number_format($max_marks, 2) . "</p>";
                        echo "<p><strong>Lowest Marks:</strong> " . number_format($min_marks, 2) . "</p>";
                        echo "<p><strong>Average Marks:</strong> " . number_format($avg_marks, 2) . "</p>";
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
                    <p><strong>Top 5:</strong> Recommended for Selection</p>
                    <p><strong>Rank 6-10:</strong> Waiting List</p>
                    <p><strong>Others:</strong> Not Selected</p>
                    <hr>
                    <p class="text-muted"><small>Note: In case of tie, written marks will be considered.</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <?php if (count($committeeMembers) > 0): ?>
    <div class="row text-center mt-5">
        <div class="col-12 mb-3">
            <h5>Approved By:</h5>
        </div>
        <?php foreach($committeeMembers as $sign): ?>
        <div class="col">
            <div class="signature-box">
                <strong><?= htmlspecialchars($sign['name']) ?></strong><br>
                <small><?= htmlspecialchars($sign['designation']) ?></small><br>
                <small><?= htmlspecialchars($sign['division']) ?></small><br>
                <small><em>(<?= htmlspecialchars($sign['type']) ?>)</em></small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Footer -->
    <div class="mt-4 text-center">
        <p class="text-muted">
            <i class="bi bi-calendar-check"></i> 
            Merit List Generated on: <?= date('d-m-Y h:i A') ?>
        </p>
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
});
</script>
</body>
</html>