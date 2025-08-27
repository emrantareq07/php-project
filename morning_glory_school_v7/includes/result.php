<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

if (isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
    include_once(ROOT_PATH.'/libs/function.php');
    $usercredentials = new DB_con();

    // Fetch username
    $uname = $_SESSION["uname"] ?? ($_COOKIE['user_login'] ?? ''); 
    $query = "SELECT * FROM tblusers WHERE Username='$uname'";
    $result = $usercredentials->runBaseQuery($query);

    foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
    }

    // PDO connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "morning_glory_db";

    // ---------- Helpers ----------
    function grade_gpa_from_mark($mark) {
        // consistent with your prior scale
        if (!is_numeric($mark)) return ['N/A', null];
        $m = floatval($mark);
        if ($m >= 80) return ['A+', 5.00];
        if ($m >= 70) return ['A',  4.00];
        if ($m >= 60) return ['B',  3.00];
        if ($m >= 50) return ['C',  2.00];
        if ($m >= 40) return ['D',  1.00];
        return ['F', 0.00];
    }
    function grade_from_gpa($gpa) {
        if (!is_numeric($gpa)) return 'N/A';
        $g = floatval($gpa);
        if ($g >= 5.00) return 'A+';
        if ($g >= 4.00) return 'A';
        if ($g >= 3.00) return 'B';
        if ($g >= 2.00) return 'C';
        if ($g >= 1.00) return 'D';
        return 'F';
    }

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Initialize variables
        $class = $_POST['class'] ?? '';
        $exam_term = $_POST['exam_term'] ?? '';
        $search_type = $_POST['single_multi_serach'] ?? '';
        $reg_no = $_POST['single_search'] ?? '';
        $students = [];
        $subjects = [];
        $student = null;

        // If registration number is provided, get student details first
        if ($reg_no) {
            $stmt = $pdo->prepare("SELECT * FROM std_tbl WHERE reg_no = ?");
            $stmt->execute([$reg_no]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                $class = $student['class'];
            } else {
                die("<div class='alert alert-danger'>Student not found with registration number: $reg_no</div>");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($class)) {
            // Determine table name
            $table_name = strtolower($class) . '_tbl';

            // Verify table exists
            $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table_name]);
            if (!$stmt->fetch()) {
                die("<div class='alert alert-danger'>Error: Table '$table_name' doesn't exist!</div>");
            }

            // Fetch students
            if ($search_type == 'Single' && !empty($reg_no)) {
                $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE reg_no = ?");
                $stmt->execute([$reg_no]);
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($search_type == 'Multiple') {
                $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE status='Active' ORDER BY reg_no");
                $stmt->execute();
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Get subjects for the specific class
            $sub_code_map = [
                'play_tbl'    => 0,
                'nursery_tbl' => 1,
                'kg_tbl'      => 2,
                'one_tbl'     => 11,
                'two_tbl'     => 22,
                'three_tbl'   => 33,
                'four_tbl'    => 44,
                'five_tbl'    => 55,
            ];

            if (isset($sub_code_map[$table_name])) {
                $sub_code = $sub_code_map[$table_name];
                $stmt = $pdo->prepare("SELECT * FROM subjects WHERE sub_code = ? ORDER BY id");
                $stmt->execute([$sub_code]);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM subjects ORDER BY id");
                $stmt->execute();
            }

            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch(PDOException $e) {
        die("<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jsPDF + html2canvas for PDF download (Multiple summary) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        .search-field { display: none; }
        .result-table { margin-top: 20px; }
        .highlight { background-color: #f8f9fa; }
        .subject-col { min-width: 150px; }
        .grade-col { width: 120px; }
        .remarks-col { min-width: 150px; }
        .marks-col { width: 110px; }
        .nowrap { white-space: nowrap; }

        /* Print clean for summary */
        @media print {
            .no-print { display: none !important; }
            .card, .container { box-shadow: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }
    </style>
</head>
<body>
    <div class="container mt-4 ">
        <div class="card">
  <div class="card-header">
    <h2 class="text-center mb-0 text-uppercase">Student Result System</h2>
  </div>
  <div class="card-body">        

        <form method="post" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Class*</label>
                    <select class="form-select" name="class" id="class" required>
                        <option value="">Select Class</option>
                        <option value="Play" <?= $class == 'Play' ? 'selected' : '' ?>>Play</option>
                        <option value="Nursery" <?= $class == 'Nursery' ? 'selected' : '' ?>>Nursery</option>
                        <option value="KG" <?= $class == 'KG' ? 'selected' : '' ?>>KG</option>
                        <option value="One" <?= $class == 'One' ? 'selected' : '' ?>>One</option>
                        <option value="Two" <?= $class == 'Two' ? 'selected' : '' ?>>Two</option>
                        <option value="Three" <?= $class == 'Three' ? 'selected' : '' ?>>Three</option>
                        <option value="Four" <?= $class == 'Four' ? 'selected' : '' ?>>Four</option>
                        <option value="Five" <?= $class == 'Five' ? 'selected' : '' ?>>Five</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Exam Term*</label>
                    <select class="form-select exam-term-select" name="exam_term" required>
                        <option value="">Select Term</option>
                        <option value="1st Term" <?= $exam_term == '1st Term' ? 'selected' : '' ?>>1st Term</option>
                        <option value="2nd Term" <?= $exam_term == '2nd Term' ? 'selected' : '' ?>>2nd Term</option>
                        <option value="3rd Term" <?= $exam_term == '3rd Term' ? 'selected' : '' ?>>3rd Term</option>
                        <option value="Final"   <?= $exam_term == 'Final'   ? 'selected' : '' ?>>Final</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Search Type*</label>
                    <select class="form-select search-type-select" name="single_multi_serach" required>
                        <option value="">Select Type</option>
                        <option value="Single"   <?= $search_type == 'Single'   ? 'selected' : '' ?>>Single</option>
                        <option value="Multiple" <?= $search_type == 'Multiple' ? 'selected' : '' ?>>Multiple</option>
                    </select>
                    <input type="text" class="form-control mt-2 search-field" name="single_search" id="single_search" 
                           value="<?= htmlspecialchars($reg_no) ?>" placeholder="Enter Registration Number">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="../dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
                </div>
            </div>
        </form>

        </div>
</div>

        <?php if (!empty($students) && !empty($subjects) && !empty($exam_term)): ?>

            <?php if ($search_type === 'Multiple'): ?>
                <!-- ===================== SUMMARY SHEET (Multiple) ===================== -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Summary Result Sheet — Class: <?= htmlspecialchars($class) ?> (<?= htmlspecialchars($exam_term) ?>)</h4>
                        <div class="no-print">
                            <a href="../dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="window.print()">🖨 Print</button>
                            <button class="btn btn-success btn-sm" onclick="downloadPDF('#summarySheet')">⬇ Download PDF</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="summarySheet">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="nowrap">Reg. No</th>
                                            <th>Student Name</th>
                                            <?php foreach ($subjects as $subject): ?>
                                                <th><?= htmlspecialchars($subject['subject']) ?><br><small>(Grade / GPA)</small></th>
                                            <?php endforeach; ?>
                                            <th>Total GPA</th>
                                            <th>Final Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($students as $st): 
                                        $ft = !empty($st['first_term'])  ? explode(',', $st['first_term'])  : [];
                                        $sd = !empty($st['second_term']) ? explode(',', $st['second_term']) : [];
                                        $tt = !empty($st['third_term'])  ? explode(',', $st['third_term'])  : [];

                                        $sum_gpa = 0.0;
                                        $count_sub = 0;
                                        $has_fail = false;
                                    ?>
                                        <tr>
                                            <td class="nowrap"><?= htmlspecialchars($st['reg_no']) ?></td>
                                            <td class="text-start"><?= htmlspecialchars($st['name'] ?? '') ?></td>

                                            <?php foreach ($subjects as $i => $sub): 
                                                // pick mark per term or avg for Final
                                                if ($exam_term === 'Final') {
                                                    $m1 = $ft[$i] ?? null;
                                                    $m2 = $sd[$i] ?? null;
                                                    $m3 = $tt[$i] ?? null;
                                                    $nums = [];
                                                    if (is_numeric($m1)) $nums[] = floatval($m1);
                                                    if (is_numeric($m2)) $nums[] = floatval($m2);
                                                    if (is_numeric($m3)) $nums[] = floatval($m3);
                                                    $mark = count($nums) ? array_sum($nums)/count($nums) : null;
                                                } else {
                                                    if ($exam_term === '1st Term') $mark = $ft[$i] ?? null;
                                                    elseif ($exam_term === '2nd Term') $mark = $sd[$i] ?? null;
                                                    else $mark = $tt[$i] ?? null;
                                                }

                                                [$gLetter, $gGpa] = grade_gpa_from_mark($mark);
                                                if (!is_null($gGpa)) {
                                                    $sum_gpa += $gGpa;
                                                    $count_sub++;
                                                    if ($gGpa == 0.00) $has_fail = true;
                                                }
                                            ?>
                                                <td><?= htmlspecialchars($gLetter) . " / " . (is_null($gGpa) ? '-' : number_format($gGpa, 2)) ?></td>
                                            <?php endforeach; ?>

                                            <?php 
                                                $final_gpa = ($count_sub > 0)
                                                    ? ($has_fail ? 0.00 : round($sum_gpa / $count_sub, 2))
                                                    : null;
                                                $final_grade = is_null($final_gpa) ? 'N/A' : ($has_fail ? 'F' : grade_from_gpa($final_gpa));
                                            ?>
                                            <td><?= is_null($final_gpa) ? '-' : number_format($final_gpa, 2) ?></td>
                                            <td><?= $final_grade ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="text-center mt-3 no-print">
                            <button class="btn btn-outline-primary me-2" onclick="window.print()">🖨 Print Summary</button>
                            <button class="btn btn-success" onclick="downloadPDF('#summarySheet')">⬇ Download Summary PDF</button>
                        </div>
                    </div>
                </div>
                <!-- =================== /SUMMARY SHEET (Multiple) ===================== -->

            <?php else: ?>
                <!-- ===================== DETAILED (Single) ===================== -->
                <div class="result-table">
                    <?php foreach ($students as $student): ?>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Result for Reg. No. <?= htmlspecialchars($student['reg_no']) ?> - <?= htmlspecialchars($student['name'] ?? '') ?> (<?= htmlspecialchars($exam_term) ?>)</h4>
                                <div class="no-print">
                                    <button class="btn btn-outline-primary btn-sm" onclick="window.print()">🖨 Print</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="subject-col">Subject</th>
                                                <?php if ($exam_term == 'Final'): ?>
                                                    <th class="marks-col">First Term</th>
                                                    <th class="marks-col">Second Term</th>
                                                    <th class="marks-col">Third Term</th>
                                                <?php else: ?>
                                                    <th class="marks-col">Marks</th>
                                                <?php endif; ?>
                                                <th class="grade-col">Grade</th>
                                                <th class="grade-col">GPA</th>
                                                <th class="remarks-col">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $first_term_marks = !empty($student['first_term']) ? explode(',', $student['first_term']) : [];
                                            $second_term_marks = !empty($student['second_term']) ? explode(',', $student['second_term']) : [];
                                            $third_term_marks  = !empty($student['third_term']) ? explode(',', $student['third_term']) : [];

                                            $total_marks = 0;
                                            $subject_count = 0;

                                            foreach ($subjects as $index => $subject) {
                                                $subject_name = $subject['subject'];

                                                if ($exam_term == 'Final') {
                                                    $mark1 = $first_term_marks[$index] ?? 'N/A';
                                                    $mark2 = $second_term_marks[$index] ?? 'N/A';
                                                    $mark3 = $third_term_marks[$index] ?? 'N/A';
                                                    $nums = [];
                                                    if (is_numeric($mark1)) $nums[] = floatval($mark1);
                                                    if (is_numeric($mark2)) $nums[] = floatval($mark2);
                                                    if (is_numeric($mark3)) $nums[] = floatval($mark3);
                                                    $average_mark = !empty($nums) ? array_sum($nums) / count($nums) : 0;
                                                    $total_marks  += $average_mark;
                                                    $subject_count++;
                                                } else {
                                                    switch ($exam_term) {
                                                        case '1st Term': $mark = $first_term_marks[$index] ?? 'N/A'; break;
                                                        case '2nd Term': $mark = $second_term_marks[$index] ?? 'N/A'; break;
                                                        case '3rd Term': $mark = $third_term_marks[$index] ?? 'N/A'; break;
                                                    }
                                                    $average_mark = is_numeric($mark) ? floatval($mark) : 0;
                                                    $total_marks  += $average_mark;
                                                    $subject_count++;
                                                }

                                                // Grade, GPA & Remarks
                                                [$grade, $gpa] = grade_gpa_from_mark($average_mark);
                                                $remarks = 'N/A';
                                                if (is_numeric($average_mark)) {
                                                    if ($average_mark >= 80) { $remarks = 'Excellent'; }
                                                    elseif ($average_mark >= 70) { $remarks = 'Very Good'; }
                                                    elseif ($average_mark >= 60) { $remarks = 'Good'; }
                                                    elseif ($average_mark >= 50) { $remarks = 'Satisfactory'; }
                                                    elseif ($average_mark >= 40) { $remarks = 'Pass'; }
                                                    else { $remarks = 'Fail'; }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($subject_name) ?></td>
                                                    <?php if ($exam_term == 'Final'): ?>
                                                        <td><?= is_numeric($mark1 ?? null) ? $mark1 : 'N/A' ?></td>
                                                        <td><?= is_numeric($mark2 ?? null) ? $mark2 : 'N/A' ?></td>
                                                        <td><?= is_numeric($mark3 ?? null) ? $mark3 : 'N/A' ?></td>
                                                    <?php else: ?>
                                                        <td><?= isset($mark) && is_numeric($mark) ? $mark : 'N/A' ?></td>
                                                    <?php endif; ?>
                                                    <td><?= $grade ?></td>
                                                    <td><?= is_null($gpa) ? '-' : number_format($gpa, 2) ?></td>
                                                    <td><?= $remarks ?></td>
                                                </tr>
                                                <?php
                                            }

                                            $percentage = ($subject_count > 0) ? round($total_marks / $subject_count, 2) : 0;

                                            // Overall Grade (by percentage) for single view
                                            $overall_grade = 'N/A';
                                            $overall_remarks = 'N/A';
                                            if ($subject_count > 0) {
                                                if ($percentage >= 80) { $overall_grade = 'A+'; $overall_remarks = 'Excellent'; }
                                                elseif ($percentage >= 70) { $overall_grade = 'A'; $overall_remarks = 'Very Good'; }
                                                elseif ($percentage >= 60) { $overall_grade = 'B'; $overall_remarks = 'Good'; }
                                                elseif ($percentage >= 50) { $overall_grade = 'C'; $overall_remarks = 'Satisfactory'; }
                                                elseif ($percentage >= 40) { $overall_grade = 'D'; $overall_remarks = 'Pass'; }
                                                else { $overall_grade = 'F'; $overall_remarks = 'Fail'; }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="highlight">
                                            <tr>
                                                <th>Total Marks</th>
                                                <?php if ($exam_term == 'Final'): ?>
                                                    <th colspan="3"><?= number_format($total_marks, 2) ?></th>
                                                <?php else: ?>
                                                    <th><?= number_format($total_marks, 2) ?></th>
                                                <?php endif; ?>
                                                <th colspan="2">Out of <?= $subject_count * 100 ?></th>
                                            </tr>
                                            <tr>
                                                <th>Percentage</th>
                                                <?php if ($exam_term == 'Final'): ?>
                                                    <th colspan="3"><?= $percentage ?>%</th>
                                                <?php else: ?>
                                                    <th><?= $percentage ?>%</th>
                                                <?php endif; ?>
                                                <th><?= $overall_grade ?></th>
                                                <th><?= $overall_remarks ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- =================== /DETAILED (Single) ===================== -->
            <?php endif; ?>

        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="alert alert-warning">No students found matching your criteria.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide search field
        (function(){
            const sel = document.querySelector('.search-type-select');
            const searchField = document.querySelector('.search-field');
            function toggle() {
                if (sel.value === 'Single') {
                    searchField.style.display = 'block';
                    searchField.setAttribute('required', 'required');
                } else {
                    searchField.style.display = 'none';
                    searchField.removeAttribute('required');
                }
            }
            sel.addEventListener('change', toggle);
            window.addEventListener('DOMContentLoaded', toggle);
        })();

        // Download PDF for a container (summary)
        async function downloadPDF(selector) {
            const { jsPDF } = window.jspdf;
            const node = document.querySelector(selector);
            if (!node) return;

            // Expand table to full width for a cleaner capture
            const prevWidth = node.style.width;
            node.style.width = '100%';

            const canvas = await html2canvas(node, { scale: 2, useCORS: true });
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('l', 'pt', 'a4'); // landscape for wide tables

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const imgProps = pdf.getImageProperties(imgData);
            const imgWidth = pageWidth;
            const imgHeight = (imgProps.height * imgWidth) / imgProps.width;

            let y = 0;
            if (imgHeight <= pageHeight) {
                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            } else {
                // Split across pages if long
                let sY = 0;
                const onePageCanvas = document.createElement('canvas');
                const pWidth  = canvas.width;
                const pHeight = Math.floor(canvas.width * (pageHeight / pageWidth));
                onePageCanvas.width = pWidth;
                onePageCanvas.height = pHeight;
                const ctx = onePageCanvas.getContext('2d');

                while (sY < canvas.height) {
                    ctx.clearRect(0, 0, pWidth, pHeight);
                    ctx.drawImage(canvas, 0, sY, pWidth, pHeight, 0, 0, pWidth, pHeight);
                    const pageData = onePageCanvas.toDataURL('image/png');
                    if (sY === 0) {
                        pdf.addImage(pageData, 'PNG', 0, 0, imgWidth, pageHeight);
                    } else {
                        pdf.addPage();
                        pdf.addImage(pageData, 'PNG', 0, 0, imgWidth, pageHeight);
                    }
                    sY += pHeight;
                }
            }

            node.style.width = prevWidth || '';
            pdf.save('Summary_Result_Sheet.pdf');
        }
        window.downloadPDF = downloadPDF;
    </script>
</body>
</html>
<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>
