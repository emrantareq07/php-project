<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_GET['exam_schedule_id']) || !isset($_GET['committee'])) {
    die("Invalid request! Missing parameters.");
}

$exam_schedule_id = mysqli_real_escape_string($conn, $_GET['exam_schedule_id']);
$committee = mysqli_real_escape_string($conn, $_GET['committee']);

// ------------------ Fetch exam info ------------------
$infoQuery = "SELECT * FROM exam_schedule_tbl WHERE id = '$exam_schedule_id' LIMIT 1";
$infoResult = mysqli_query($conn, $infoQuery);
$info = mysqli_fetch_assoc($infoResult);

$title = $info['title'] ?? 'No Title';
$date = isset($info['date']) && $info['date'] ? date('d-m-Y', strtotime($info['date'])) : '-';
$time = isset($info['time']) && $info['time'] ? date('h:i A', strtotime($info['time'])) : '-';

// ------------------ Fetch candidates ------------------
$candidateQuery = "SELECT * FROM candidates_tbl WHERE exam_schedule_id = '$exam_schedule_id'";
$candidateResult = mysqli_query($conn, $candidateQuery);

// ------------------ Fetch committee members ------------------
$membersQuery = "SELECT * FROM committee_tbl WHERE exam_schedule_id = '$exam_schedule_id' or exam_schedule_id=''";
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
<title>Candidate List - <?= htmlspecialchars($committee) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
@media print { .no-print { display:none } }
body { background:#fff; }
.header { text-align:center;margin-bottom:20px; }
.header h4 { color:#007bff; }
.header p { margin:0; }
table th { background:#e9ecef; }
.signature-box { border-top:1px solid #000;padding-top:10px;margin-top:20px; }
.committee-header { font-size:12px;line-height:1.2;padding:5px; }
/* Font Definitions */
@font-face {
  font-family: 'Nikosh';
  src: url('fonts/Nikosh.ttf') format('truetype'),
       url('fonts/Nikosh.woff') format('woff'),
       url('fonts/Nikosh.woff2') format('woff2');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

/* Base Typography */
* {
  font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
}
table td, table th {
    font-size: 14px;
}
</style>
</head>
<body>
<div class="container mt-4">
    <div class="header">
        <button class="btn btn-primary no-print" onclick="window.print()">🖨️ Print</button>
        <button class="btn btn-secondary no-print" onclick="window.location.reload()">🔄 Refresh</button>
        <h2>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন(বিসিআইসি)</h2>
        <h3 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা।</h3>
        
        <h4><?= htmlspecialchars($title) ?></h4>
        <p><b class="float-start">Date: <?= $date ?></b> |
          <b>Committee:</b> <?= htmlspecialchars($committee) ?> |
          <b class="float-end">Time: <?= $time ?></b> 
        </p>
        
        <hr>
    </div>

    <table class="table table-bordered table-sm align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Roll No</th>
                <th>Name, Father's Name, Mother's Name</th>
                <th>District</th>
                <th>DOB</th>
                <!-- <th>Designation</th> -->
                <th>Written Marks</th>
                <th>Image</th>
                <?php foreach($committeeMembers as $member): ?>
                <th class="committee-header">
                    <strong><?= htmlspecialchars($member['name']) ?></strong><br>
                    <small>(<?= htmlspecialchars($member['designation']) ?>)</small><br>
                    <small>(<?= htmlspecialchars($member['division']) ?>)</small><br>
                    <small>(<?= htmlspecialchars($member['type']) ?>)</small>
                </th>
                <?php endforeach; ?>
                <th>Total Viva</th>
                <th>Average Viva</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
<?php 
$i = 1;
if (mysqli_num_rows($candidateResult) > 0) {
    while ($row = mysqli_fetch_assoc($candidateResult)) {
        $candidate_id = $row['id'];
        
        $total_marks = 0;
        $viva_marks_arr = [];

        // Loop through each committee member to fetch individual viva marks
        foreach($committeeMembers as $member) {
            $examinerUsername = mysqli_real_escape_string($conn, $member['name']);
            
             // Try multiple ways to find the marks
            $markQuery = "SELECT viva_marks FROM viva_marks_tbl 
                          WHERE candidate_id = '$candidate_id' 
                          AND (exam_schedule_id = '$exam_schedule_id' OR committe_name = '$committee')
                          AND examiner_username = '$examinerUsername' 
                          ";
            
            $markResult = mysqli_query($conn, $markQuery);
            
            if ($markResult && mysqli_num_rows($markResult) > 0) {
                $markRow = mysqli_fetch_assoc($markResult);
                $mark = $markRow['viva_marks'];
            } else {
                $mark = 'Pending';
            }
            
            $viva_marks_arr[] = is_numeric($mark) ? $mark : 0; // Pending counted as 0
        }

        $total_marks = array_sum($viva_marks_arr);
        $avg_marks = count($committeeMembers) > 0 ? round($total_marks / count($committeeMembers), 2) : 0;

?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['roll_no']) ?></td>
    <td>
        <?= htmlspecialchars($row['name']) ?><br>
        <?= htmlspecialchars($row['fathers_name']) ?><br>
        <?= htmlspecialchars($row['mothers_name']) ?>
    </td>
    <td><?= htmlspecialchars($row['district']) ?></td>
    <td><?= htmlspecialchars($row['dob']) ?></td>
    <!-- <td><?= htmlspecialchars($row['designation']) ?></td> -->
    <td><?= htmlspecialchars($row['written_marks']) ?></td>
    <td>
        <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
            <img src="<?= htmlspecialchars($row['image']) ?>" style="height:40px; width:40px; object-fit:cover;">
        <?php else: ?>
            <img src="default.png" style="height:40px; width:40px; object-fit:cover;">
        <?php endif; ?>
    </td>

    <?php foreach($viva_marks_arr as $vivaMark): ?>
        <td>
            <?php 
            if ($vivaMark === 0) {
                echo 'Pending';
            } elseif (is_numeric($vivaMark)) {
                echo number_format($vivaMark, 1);
            } else {
                echo htmlspecialchars($vivaMark);
            }
            ?>
        </td>
    <?php endforeach; ?>

    <td><strong><?= htmlspecialchars($total_marks) ?></strong></td>
    <td><strong><?= htmlspecialchars($avg_marks) ?></strong></td>
    <td><?= htmlspecialchars($row['remarks']) ?></td>
</tr>
<?php 
    }
} else { ?>
    <tr>
        <td colspan="<?= 7 + count($committeeMembers) + 3 ?>" class="text-center text-muted py-4">
            <i class="fas fa-user-slash fa-2x mb-3 d-block"></i>
            No candidates found for this committee.
        </td>
    </tr>
<?php } ?>
        </tbody>
    </table>

    <?php if (count($committeeMembers) > 0): ?>
    <div class="row text-center mt-5">
        <?php foreach($committeeMembers as $sign): ?>
        <div class="col-md-<?= 12 / count($committeeMembers) ?> mb-4">
            <div class="signature-box">
                <strong><?= htmlspecialchars($sign['name']) ?></strong><br>
                <small>(<?= htmlspecialchars($sign['designation']) ?>)</small><br>
                <small>(<?= htmlspecialchars($sign['division']) ?>)</small><br>
                <small>(<?= htmlspecialchars($sign['type']) ?>)</small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Debug information (hidden by default, show with CTRL+SHIFT+D) -->
    <div class="no-print mt-4" style="display: none;" id="debugInfo">
        <div class="card">
            <div class="card-header bg-warning">Debug Information</div>
            <div class="card-body">
                <p><strong>Exam Schedule ID:</strong> <?= $exam_schedule_id ?></p>
                <p><strong>Committee:</strong> <?= $committee ?></p>
                <p><strong>Committee Members Count:</strong> <?= count($committeeMembers) ?></p>
                <p><strong>Candidates Count:</strong> <?= mysqli_num_rows($candidateResult) ?></p>
                <p><strong>Committee Members:</strong></p>
                <ul>
                    <?php foreach($committeeMembers as $m): ?>
                    <li><?= $m['name'] ?> (<?= $m['designation'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
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