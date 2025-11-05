<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_GET['committee'])) {
    die("Committee not provided.");
}

$committee = $_GET['committee'];

date_default_timezone_set("Asia/Dhaka");

// ✅ Fetch candidates with viva marks
// $query = "
//     SELECT 
//         c.id,
//         c.roll_no,
//         c.name,
//         c.fathers_name,
//         c.mothers_name,
//         c.ssc,
//         c.hsc,
//         c.honors,
//         c.masters,
//         c.written_marks,
//         IFNULL(v.viva_marks, 0) AS viva_marks
//     FROM candidates_tbl c
//     LEFT JOIN viva_marks_tbl v ON v.candidate_id = c.id
//     AND exam_schedule_tbl e on e.committe_name=c.committe_name
//     WHERE c.committe_name = ?
// ";

$query = "
    SELECT 
        c.id,
        c.roll_no,
        c.name,
        c.fathers_name,
        c.mothers_name,
        c.ssc,
        c.hsc,
        c.honors,
        c.masters,
        c.written_marks,
        e.marks,
        IFNULL(v.viva_marks, 0) AS viva_marks
    FROM candidates_tbl c
    LEFT JOIN exam_schedule_tbl e ON e.committe_name = c.committe_name
    LEFT JOIN viva_marks_tbl v ON v.candidate_id = c.id
    WHERE c.committe_name = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $committee);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Process marks and total
$candidates = [];
$total_sum = 0;

while ($row = $result->fetch_assoc()) {
    $ssc_marks     = ($row['ssc'] >= 5) ? 3 : 0;
    $hsc_marks     = ($row['hsc'] >= 5) ? 3 : 0;
    $honors_marks  = ($row['honors'] >= 3) ? 3.5 : 0;
    $masters_marks = ($row['masters'] >= 3) ? 0.5 : 0;

    $declare_viva_marks = $row['marks']; //that come from exam_schedule_tbl

    $total_marks   = $ssc_marks + $hsc_marks + $honors_marks + $masters_marks + $row['written_marks'] + $row['viva_marks'];
    $total_sum += $total_marks;

    $row['ssc_marks'] = $ssc_marks;
    $row['hsc_marks'] = $hsc_marks;
    $row['honors_marks'] = $honors_marks;
    $row['masters_marks'] = $masters_marks;
    $row['total_marks'] = $total_marks;

    $candidates[] = $row;
}
$viva_marks_arr = [];
// calculate viva marks ecach candidate which puted by multiple examiner
$examinemarks[]=$mark = $markRow['viva_marks']
$examinemarks[] = is_numeric($mark) ? $mark : 0;
$total_marks = array_sum($examinemarks);
$final_viva_marks=$examinemarks*$declare_viva_marks/(count($committeeMembers)*$declare_viva_marks)


// ✅ Sort by total marks (descending)
usort($candidates, fn($a, $b) => $b['total_marks'] <=> $a['total_marks']);

// ✅ Assign ranks
$rank = 1;
$previous_total = null;
foreach ($candidates as $key => $c) {
    if ($previous_total !== null && $c['total_marks'] < $previous_total) {
        $rank++;
    }
    $candidates[$key]['rank'] = $rank;
    $previous_total = $c['total_marks'];
}

// ✅ Summary values
$total_candidates = count($candidates);
$average_marks = $total_candidates > 0 ? ($total_sum / $total_candidates) : 0;


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Result Sheet - <?= htmlspecialchars($committee) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #fff; font-family: 'Segoe UI', sans-serif; }
    h4 { background: linear-gradient(45deg,#007bff,#6610f2); color: #fff; padding: 10px; border-radius: 6px; text-align:center; }
    .table th, .table td { vertical-align: middle; text-align: center; font-size: 14px; }
    .rank-1 { background: #d4edda !important; font-weight: bold; } /* 🥇 Top 1 */
    .rank-2 { background: #fff3cd !important; } /* 🥈 Top 2 */
    .rank-3 { background: #f8d7da !important; } /* 🥉 Top 3 */
    tfoot td { font-weight: bold; background: #f1f1f1; }
    @media print {
        .no-print { display: none; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h4 class="flex-grow-1">Result Sheet - <?= htmlspecialchars($committee) ?></h4>
        <button class="btn btn-primary" onclick="window.print()">🖨 Print</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-primary text-center align-middle">
            <tr>
                <th>Rank</th>
                <th>Roll No</th>
                <th>Name</th>
                <th>SSC</th>
                <th>HSC</th>
                <th>Honors</th>
                <th>Masters</th>
                <th>SSC Marks</th>
                <th>HSC Marks</th>
                <th>Honors Marks</th>
                <th>Masters Marks</th>
                <th>Written Marks</th>
                <th>Viva Marks</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($candidates)): ?>
                <?php foreach ($candidates as $c): 
                    $rank_class = ($c['rank'] == 1) ? 'rank-1' : (($c['rank'] == 2) ? 'rank-2' : (($c['rank'] == 3) ? 'rank-3' : '')); ?>
                    <tr class="<?= $rank_class ?>">
                        <td><?= $c['rank'] ?></td>
                        <td><?= htmlspecialchars($c['roll_no']) ?></td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['ssc']) ?></td>
                        <td><?= htmlspecialchars($c['hsc']) ?></td>
                        <td><?= htmlspecialchars($c['honors']) ?></td>
                        <td><?= htmlspecialchars($c['masters']) ?></td>
                        <td><?= $c['ssc_marks'] ?></td>
                        <td><?= $c['hsc_marks'] ?></td>
                        <td><?= $c['honors_marks'] ?></td>
                        <td><?= $c['masters_marks'] ?></td>
                        <td><?= $c['written_marks'] ?></td>
                        <td><?= $c['viva_marks'] ?></td>
                        <td class="fw-bold text-success"><?= number_format($c['total_marks'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="14" class="text-center text-muted">No candidates found for this committee.</td></tr>
            <?php endif; ?>
        </tbody>
        <?php if ($total_candidates > 0): ?>
        <tfoot>
            <tr>
                <td colspan="13" class="text-end">Total Candidates:</td>
                <td><?= $total_candidates ?></td>
            </tr>
            <tr>
                <td colspan="13" class="text-end">Average Total Marks:</td>
                <td><?= number_format($average_marks, 2) ?></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
