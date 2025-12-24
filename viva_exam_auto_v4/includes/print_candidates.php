<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_GET['exam_schedule_id']) || !isset($_GET['committee'])) {
    die("Invalid request! Missing parameters.");
}

$exam_schedule_id = mysqli_real_escape_string($conn, $_GET['exam_schedule_id']);
$committee = mysqli_real_escape_string($conn, $_GET['committee']);

// Fetch exam info with designation
$infoQuery = "SELECT * FROM exam_schedule_tbl WHERE id = '$exam_schedule_id' LIMIT 1";
$infoResult = mysqli_query($conn, $infoQuery);
$info = mysqli_fetch_assoc($infoResult);

if (!$info) {
    die("Exam schedule not found!");
}

$title = $info['title'] ?? 'No Title';
$date = isset($info['date']) && $info['date'] ? date('d-m-Y', strtotime($info['date'])) : '-';
$time = isset($info['time']) && $info['time'] ? date('h:i A', strtotime($info['time'])) : '-';
$designation = isset($info['designation']) ? htmlspecialchars($info['designation']) : '';

// Fetch candidates for THIS specific exam schedule ID AND designation
$candidateQuery = "SELECT * FROM candidates_tbl 
                   WHERE exam_schedule_id = '$exam_schedule_id' 
                   AND designation = '$designation'
                   ORDER BY CAST(written_marks AS DECIMAL(10,2)) DESC";
$candidateResult = mysqli_query($conn, $candidateQuery);

// Debug: Check if candidates are found
if (mysqli_num_rows($candidateResult) === 0) {
    // Try alternative query without designation filter
    $candidateQuery = "SELECT * FROM candidates_tbl 
                       WHERE exam_schedule_id = '$exam_schedule_id'
                       ORDER BY CAST(written_marks AS DECIMAL(10,2)) DESC";
    $candidateResult = mysqli_query($conn, $candidateQuery);
}

// Fetch committee members - FIXED: Use exam_schedule_id instead of committee name
$membersQuery = "SELECT * FROM committee_tbl 
                 WHERE committe_name = '$committee' 
                 ORDER BY FIELD(type, 'Chairman', 'Member Secretary', 'Member')";
$membersResult = mysqli_query($conn, $membersQuery);
$committeeMembers = [];
while ($member = mysqli_fetch_assoc($membersResult)) {
    $committeeMembers[] = $member;
}

// If no committee members found, try to fetch by committee name
if (empty($committeeMembers)) {
    $membersQuery = "SELECT * FROM committee_tbl 
                     WHERE committe_name = '$committee' 
                     AND exam_schedule_id = '$exam_schedule_id'
                     ORDER BY FIELD(type, 'Chairman', 'Member Secretary', 'Member')";
    $membersResult = mysqli_query($conn, $membersQuery);
    while ($member = mysqli_fetch_assoc($membersResult)) {
        $committeeMembers[] = $member;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Candidate List - <?= htmlspecialchars($committee) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
@media print { 
    .no-print { display:none !important; } 
    body { font-size: 12px; }
    table { font-size: 11px; }
    .header h2 { font-size: 18px; }
    .header h3 { font-size: 16px; }
    .header h4 { font-size: 14px; }
}
body { background:#fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
.header { text-align:center; margin-bottom:20px; border-bottom: 2px solid #007bff; padding-bottom: 15px; }
.header h2 { color:#2c3e50; margin-bottom:5px; }
.header h3 { color:#34495e; margin-bottom:10px; }
.header h4 { color:#007bff; background:#f8f9fa; padding:10px; border-radius:5px; }
.info-bar { background:#e9ecef; padding:10px; border-radius:5px; margin-bottom:15px; }
.table th { background:#2c3e50; color:white; vertical-align:middle; }
.table td { vertical-align:middle; }
.committee-header { background:#3498db; color:white; padding:5px; border-radius:3px; font-size:11px; }
.signature-box { border-top:2px solid #000; padding-top:10px; margin-top:30px; text-align:center; }
.img-thumb { height:50px; width:50px; object-fit:cover; border:1px solid #ddd; border-radius:4px; }
.stats-card { background:#f8f9fa; border-left:4px solid #007bff; padding:10px; margin-bottom:15px; }
.text-pending { color:#e74c3c; font-weight:bold; }
.text-completed { color:#27ae60; font-weight:bold; }
</style>
</head>
<body>
<div class="container mt-3">
    <!-- Header -->
    <div class="header">
        <div class="no-print d-flex justify-content-between mb-3">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>
            <button class="btn btn-success" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
            <a href="merit_list.php?exam_schedule_id=<?= $exam_schedule_id ?>&committee=<?= urlencode($committee) ?>&designation=<?= urlencode($designation) ?>" 
               class="btn btn-warning">
               <i class="bi bi-trophy"></i> View Merit List
            </a>
        </div>
        
        <h2>Bangladesh Chemical Industries Corporation (BCIC)</h2>
        <h3>BCIC Bhaban, 30-31, Dilkusha C/A, Dhaka.</h3>
        
        <div class="info-bar">
            <div class="row">
                <div class="col-md-4"><strong>Post:</strong> <?= $designation ?></div>
                <div class="col-md-4"><strong>Committee:</strong> <?= htmlspecialchars($committee) ?></div>
                <div class="col-md-4"><strong>Exam Date:</strong> <?= $date ?></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4"><strong>Exam Title:</strong> <?= htmlspecialchars($title) ?></div>
                <div class="col-md-4"><strong>Exam Time:</strong> <?= $time ?></div>
                <div class="col-md-4"><strong>Total Candidates:</strong> <?= mysqli_num_rows($candidateResult) ?></div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row no-print mb-3">
        <div class="col-md-3">
            <div class="stats-card">
                <h6><i class="bi bi-people"></i> Total Candidates</h6>
                <h4><?= mysqli_num_rows($candidateResult) ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color:#27ae60;">
                <h6><i class="bi bi-check-circle"></i> Viva Completed</h6>
                <h4 id="completedCount">0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color:#e74c3c;">
                <h6><i class="bi bi-clock"></i> Pending Viva</h6>
                <h4 id="pendingCount">0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color:#f39c12;">
                <h6><i class="bi bi-calendar"></i> Exam Date</h6>
                <h4><?= $date ?></h4>
            </div>
        </div>
    </div>

    <!-- Candidates Table -->
    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th width="40">#</th>
                <th width="80">Roll No</th>
                <th width="250">Candidate Details</th>
                <th width="100">District</th>
                <th width="90">DOB</th>
                <th width="80">Written</th>
                <th width="70">Photo</th>
                
                <?php foreach($committeeMembers as $member): ?>
                <th width="100" class="committee-header">
                    <strong><?= htmlspecialchars($member['name']) ?></strong><br>
                    <small><?= htmlspecialchars($member['designation']) ?></small><br>
                    <small>(<?= htmlspecialchars($member['type']) ?>)</small>
                </th>
                <?php endforeach; ?>
                
                <th width="80">Total Viva</th>
                <th width="80">Avg Viva</th>
                <th width="80">Total (W+V)</th>
                <th width="80">Remarks</th>
                <th width="60" class="no-print">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $completed = 0;
        $pending = 0;
        
        if (mysqli_num_rows($candidateResult) > 0) {
            mysqli_data_seek($candidateResult, 0); // Reset pointer
            
            while ($row = mysqli_fetch_assoc($candidateResult)) {
                $candidate_id = $row['id'];
                $written_marks = (float)$row['written_marks'];
                $total_marks = 0;
                $viva_marks_arr = [];
                $all_marks_entered = true;

                // Fetch viva marks from each committee member - FIXED QUERY
                foreach($committeeMembers as $member) {
                    $examinerUsername = mysqli_real_escape_string($conn, $member['mobile_no'] ?? $member['name']);
                    
                    // FIXED: Use exam_schedule_id AND committee_name to uniquely identify
                    $markQuery = "SELECT viva_marks FROM viva_marks_tbl 
                                  WHERE candidate_id = '$candidate_id' 
                                  AND exam_schedule_id = '$exam_schedule_id'
                                  AND committe_name = '$committee'
                                  AND examiner_username = '$examinerUsername'
                                  LIMIT 1";
                    
                    $markResult = mysqli_query($conn, $markQuery);
                    
                    if ($markResult && mysqli_num_rows($markResult) > 0) {
                        $markRow = mysqli_fetch_assoc($markResult);
                        $mark = (float)$markRow['viva_marks'];
                    } else {
                        // Try alternative query without committee_name
                        $markQuery2 = "SELECT viva_marks FROM viva_marks_tbl 
                                      WHERE candidate_id = '$candidate_id' 
                                      AND exam_schedule_id = '$exam_schedule_id'
                                      AND examiner_username = '$examinerUsername'
                                      LIMIT 1";
                        $markResult2 = mysqli_query($conn, $markQuery2);
                        
                        if ($markResult2 && mysqli_num_rows($markResult2) > 0) {
                            $markRow2 = mysqli_fetch_assoc($markResult2);
                            $mark = (float)$markRow2['viva_marks'];
                        } else {
                            $mark = 0;
                            $all_marks_entered = false;
                        }
                    }
                    
                    $viva_marks_arr[] = $mark;
                    $total_marks += $mark;
                }

                // Update statistics
                if ($all_marks_entered && $total_marks > 0) {
                    $completed++;
                } else {
                    $pending++;
                }

                $avg_viva = count($committeeMembers) > 0 ? round($total_marks / count($committeeMembers), 2) : 0;
                $total_all = $written_marks + $avg_viva;
        ?>
        <tr>
            <td class="text-center"><?= $i++ ?></td>
            <td class="text-center fw-bold"><?= htmlspecialchars($row['roll_no']) ?></td>
            <td>
                <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                <small class="text-muted">Father: <?= htmlspecialchars($row['fathers_name']) ?></small><br>
                <small class="text-muted">Mother: <?= htmlspecialchars($row['mothers_name']) ?></small>
            </td>
            <td><?= htmlspecialchars($row['district']) ?></td>
            <td><?= date('d-m-Y', strtotime($row['dob'])) ?></td>
            <td class="text-center fw-bold"><?= number_format($written_marks, 2) ?></td>
            <td class="text-center">
                <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                    <img src="<?= htmlspecialchars($row['image']) ?>" class="img-thumb">
                <?php else: ?>
                    <img src="../assets/default.png" class="img-thumb">
                <?php endif; ?>
            </td>
            
            <?php foreach($viva_marks_arr as $index => $vivaMark): ?>
                <td class="text-center <?= $vivaMark > 0 ? 'table-success' : 'table-warning' ?>">
                    <?= $vivaMark > 0 ? number_format($vivaMark, 2) : 'Pending' ?>
                </td>
            <?php endforeach; ?>
            
            <td class="text-center fw-bold bg-light"><?= number_format($total_marks, 2) ?></td>
            <td class="text-center fw-bold bg-info text-white"><?= number_format($avg_viva, 2) ?></td>
            <td class="text-center fw-bold bg-success text-white"><?= number_format($total_all, 2) ?></td>
            <td><?= htmlspecialchars($row['remarks']) ?></td>
            <td class="no-print text-center">
                <?php if ($all_marks_entered && $total_marks > 0): ?>
                    <span class="badge bg-success">Completed</span>
                <?php else: ?>
                    <span class="badge bg-warning">Pending</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php 
            }
        } else { ?>
        <tr>
            <td colspan="<?= 7 + count($committeeMembers) + 6 ?>" class="text-center text-muted py-4">
                <i class="bi bi-person-x display-6 d-block mb-2"></i>
                No candidates found for this committee.
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Signatures Section -->
    <?php if (count($committeeMembers) > 0): ?>
    <div class="row text-center mt-5">
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
    
    <!-- Debug Information (Visible with CTRL+SHIFT+D) -->
    <div class="no-print mt-4" style="display: none;" id="debugInfo">
        <div class="card">
            <div class="card-header bg-warning">Debug Information</div>
            <div class="card-body">
                <p><strong>Exam Schedule ID:</strong> <?= $exam_schedule_id ?></p>
                <p><strong>Committee:</strong> <?= $committee ?></p>
                <p><strong>Designation:</strong> <?= $designation ?></p>
                <p><strong>Committee Members Count:</strong> <?= count($committeeMembers) ?></p>
                <p><strong>Candidates Count:</strong> <?= mysqli_num_rows($candidateResult) ?></p>
                <p><strong>SQL Query for Candidates:</strong> <?= htmlspecialchars($candidateQuery) ?></p>
                <p><strong>Committee Members:</strong></p>
                <ul>
                    <?php foreach($committeeMembers as $m): ?>
                    <li><?= $m['name'] ?> (<?= $m['designation'] ?>) - <?= $m['type'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="mt-4 text-center no-print">
        <p class="text-muted">
            <i class="bi bi-info-circle"></i> 
            Report Generated on: <?= date('d-m-Y h:i A') ?>
        </p>
    </div>
</div>

<script>
// Update statistics
document.getElementById('completedCount').textContent = '<?= $completed ?>';
document.getElementById('pendingCount').textContent = '<?= $pending ?>';

// Auto-refresh every 30 seconds
setTimeout(function() {
    window.location.reload();
}, 30000);

// Show debug info with CTRL+SHIFT+D
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.shiftKey && e.key === 'D') {
        document.getElementById('debugInfo').style.display = 'block';
    }
});

// Hide debug info on click
document.getElementById('debugInfo').addEventListener('click', function() {
    this.style.display = 'none';
});
</script>
</body>
</html>