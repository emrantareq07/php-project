<?php 
session_name('training_certificate_gen_db');
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

/* ================= GET SELECTED BATCH ================= */
$selected_batch = $_GET['batch'] ?? '';

/* ================= DELETE QUESTION ================= */
if(isset($_GET['delete']) && $selected_batch != ''){
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM question_set WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    header("Location: exam_set.php?batch=".$selected_batch);
    exit;
}

/* ================= INSERT QUESTION ================= */
if(isset($_POST['save'])){
    $stmt = $conn->prepare("INSERT INTO question_set 
    (batch, question_name, option_a, option_b, option_c, option_d, correct_option)
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss",
        $_POST['batch'],
        $_POST['question_name'],
        $_POST['option_a'],
        $_POST['option_b'],
        $_POST['option_c'],
        $_POST['option_d'],
        $_POST['correct_option']
    );
    $stmt->execute();
    header("Location: exam_set.php?batch=".$_POST['batch']);
    exit;
}

/* ================= UPDATE QUESTION ================= */
if(isset($_POST['update'])){
    $stmt = $conn->prepare("UPDATE question_set SET
    question_name=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?
    WHERE id=?");
    $stmt->bind_param("ssssssi",
        $_POST['question_name'],
        $_POST['option_a'],
        $_POST['option_b'],
        $_POST['option_c'],
        $_POST['option_d'],
        $_POST['correct_option'],
        $_POST['edit_id']
    );
    $stmt->execute();
    header("Location: exam_set.php?batch=".$selected_batch);
    exit;
}

/* ================= COPY BATCH QUESTIONS ================= */
if(isset($_POST['copy_batch'])){
    $source_batch = (int) $_POST['source_batch'];
    $new_batch = (int) $_POST['new_batch'];

    if($source_batch == $new_batch){
        echo "<script>alert('Source and New Batch cannot be same!');</script>";
    } else {

        $check = $conn->prepare("SELECT id FROM question_set WHERE batch=? LIMIT 1");
        $check->bind_param("i", $new_batch);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            echo "<script>alert('This batch already has questions!');</script>";
        } else {

            $copy = $conn->prepare("
                INSERT INTO question_set 
                (batch, question_name, option_a, option_b, option_c, option_d, correct_option)
                SELECT ?, question_name, option_a, option_b, option_c, option_d, correct_option
                FROM question_set WHERE batch=?
            ");
            $copy->bind_param("ii", $new_batch, $source_batch);
            $copy->execute();

            header("Location: exam_set.php?batch=".$new_batch);
            exit;
        }
    }
}

/* ================= SAVE EXAM SCHEDULE ================= */
if(isset($_POST['save_exam_schedule']) && $selected_batch){

    $stmt = $conn->prepare("
        UPDATE authority_tbl 
        SET exam_date=?, start_time=?, end_time=?, active_exam=? 
        WHERE batch=?
    ");

    $stmt->bind_param("ssssi",
        $_POST['exam_date'],
        $_POST['start_time'],
        $_POST['end_time'],
        $_POST['active_exam'],
        $selected_batch
    );

    $stmt->execute();
    $stmt->close();

    header("Location: exam_set.php?batch=".$selected_batch);
    exit;
}

/* ================= FETCH EXAM SCHEDULE ================= */
$exam_date = $start_time = $end_time = $active_exam = '';

if($selected_batch){
    $stmt = $conn->prepare("
        SELECT exam_date, start_time, end_time, active_exam 
        FROM authority_tbl 
        WHERE batch=? 
        LIMIT 1
    ");
    $stmt->bind_param("i", $selected_batch);
    $stmt->execute();
    $stmt->bind_result($exam_date, $start_time_raw, $end_time_raw, $active_exam);
    $stmt->fetch();
    $stmt->close();

    // $start_time = $start_time_raw ? substr($start_time_raw, 0, 5) : '';
    // $end_time   = $end_time_raw ? substr($end_time_raw, 0, 5) : '';
    // Remove microseconds
$start_time_clean = $start_time_raw ? substr($start_time_raw, 0, 8) : '';
$end_time_clean   = $end_time_raw ? substr($end_time_raw, 0, 8) : '';

// If time is 00:00:00 → make blank
if($start_time_clean == '00:00:00'){
    $start_time = '';
} else {
    $start_time = substr($start_time_clean, 0, 5); // HH:MM
}

if($end_time_clean == '00:00:00'){
    $end_time = '';
} else {
    $end_time = substr($end_time_clean, 0, 5);
}

}

/* ================= FETCH QUESTIONS ================= */
$questions = [];
if($selected_batch){
    $stmt = $conn->prepare("SELECT * FROM question_set WHERE batch=? ORDER BY id DESC");
    $stmt->bind_param("i", $selected_batch);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $questions[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Exam Question Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
.batch-select { display:flex; justify-content:flex-end; margin-bottom:20px; }
</style>
</head>

<body class="container mt-4">

<h3>Exam Question Management</h3>

<!-- ================= BATCH SELECT ================= -->
<div class="batch-select">
<form method="GET" class="w-25">
<select name="batch" class="form-select" required onchange="this.form.submit()">
<option value="">-- Select Batch --</option>

<?php
$res = $conn->query("
    SELECT 
        a.batch,
        a.training_title,
        COUNT(q.id) as total_questions
    FROM authority_tbl a
    LEFT JOIN question_set q ON a.batch = q.batch
    GROUP BY a.batch, a.training_title
    ORDER BY a.batch DESC
");

while($row = $res->fetch_assoc()){
    $isNew = ($row['total_questions'] == 0);
    $label = $isNew 
        ? "New Training - Batch-" . $row['batch'] . " (" . $row['training_title'] . ")" 
        : "Batch-" . $row['batch'] . " (" . $row['training_title'] . ")";
?>
<option value="<?= $row['batch'] ?>" <?= ($selected_batch==$row['batch'])?'selected':'' ?>>
<?= htmlspecialchars($label) ?>
</option>
<?php } ?>
</select>
</form>

<a href="dashboard.php" class="btn btn-light float-end mb-3">
<i class="bi bi-arrow-left me-2"></i> Back to Dashboard
</a>
</div>

<hr>

<!-- ================= EXAM SCHEDULE ================= -->
<?php if($selected_batch): ?>
<div class="d-flex justify-content-center mb-4">
<div class="card p-4" style="width: 90%; max-width: 1000px;">
<form method="POST" class="row g-3 align-items-end">

<div class="col-md-2">
<label>Exam Date</label>
<input type="date" name="exam_date" class="form-control"
value="<?= htmlspecialchars($exam_date) ?>" required>
</div>

<div class="col-md-2">
<label>Start Time</label>
<input type="time" name="start_time" class="form-control"
value="<?= htmlspecialchars($start_time) ?>" required>
</div>

<div class="col-md-2">
<label>End Time</label>
<input type="time" name="end_time" class="form-control"
value="<?= htmlspecialchars($end_time) ?>" required>
</div>

<div class="col-md-3">
<label>Active Exam</label>
<select name="active_exam" class="form-select <?= ($active_exam=='active') ? 'bg-success text-white' : 'bg-danger text-white' ?>" required>
<option value="inactive" <?= ($active_exam=='inactive')?'selected':'' ?>>Inactive</option>
<option value="active" <?= ($active_exam=='active')?'selected':'' ?>>Active</option>
</select>
</div>

<div class="col-md-3">
<button type="submit" name="save_exam_schedule" class="btn btn-primary btn-sm mt-4">
Update schedule
</button>
</div>

</form>
</div>
</div>
<?php endif; ?>


<!-- ================= COPY QUESTIONS ================= -->
<h5>Copy Existing Batch Questions</h5>
<form method="POST" class="row g-2 mb-4">
<div class="col-md-4">
<select name="source_batch" class="form-select" required>
<option value="">-- Select Source Batch --</option>
<?php
$res = $conn->query("SELECT DISTINCT a.batch, a.training_title
FROM authority_tbl a
INNER JOIN question_set q ON a.batch = q.batch
ORDER BY a.batch DESC");
while($row = $res->fetch_assoc()){
    $label = "Batch-" . $row['batch'] . " (" . $row['training_title'] . ")";
?>
<option value="<?= $row['batch'] ?>"><?= htmlspecialchars($label) ?></option>
<?php } ?>
</select>
</div>

<div class="col-md-4">
<select name="new_batch" class="form-select" required>
<option value="">-- Select New Batch --</option>
<?php
$res = $conn->query("
    SELECT a.batch, a.training_title
    FROM authority_tbl a
    LEFT JOIN question_set q ON a.batch = q.batch
    WHERE q.batch IS NULL
    ORDER BY a.batch ASC
");
while($row = $res->fetch_assoc()){
    $label = "New Training - Batch-" . $row['batch'] . " (" . $row['training_title'] . ")";
?>
<option value="<?= $row['batch'] ?>"><?= htmlspecialchars($label) ?></option>
<?php } ?>
</select>
</div>

<div class="col-md-4">
<button type="submit" name="copy_batch" class="btn btn-warning w-100">Import previous Questions</button>
</div>
</form>

<hr>

<!-- ================= ADD QUESTION BUTTON ================= -->
<?php if($selected_batch): ?>
<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
Add Question
</button>
<?php endif; ?>

<!-- ================= SHOW QUESTIONS ================= -->
<?php if($selected_batch && !empty($questions)): ?>
<div class="row">
<?php foreach($questions as $idx => $q): ?>
<div class="col-md-6">
<div class="card p-3 mb-3 h-100">
<strong>Q<?= $idx+1 ?>.</strong> <?= htmlspecialchars($q['question_name']) ?><br><br>
A. <?= htmlspecialchars($q['option_a']) ?><br>
B. <?= htmlspecialchars($q['option_b']) ?><br>
C. <?= htmlspecialchars($q['option_c']) ?><br>
D. <?= htmlspecialchars($q['option_d']) ?><br>
<strong>Correct:</strong> <?= $q['correct_option'] ?><br><br>

<button class="btn btn-primary btn-sm editBtn"
data-id="<?= $q['id'] ?>"
data-question="<?= htmlspecialchars($q['question_name']) ?>"
data-a="<?= htmlspecialchars($q['option_a']) ?>"
data-b="<?= htmlspecialchars($q['option_b']) ?>"
data-c="<?= htmlspecialchars($q['option_c']) ?>"
data-d="<?= htmlspecialchars($q['option_d']) ?>"
data-correct="<?= $q['correct_option'] ?>"
data-bs-toggle="modal"
data-bs-target="#editModal">
Edit
</button>

<a href="?batch=<?= $selected_batch ?>&delete=<?= $q['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this question?')">
Delete
</a>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ================= ADD MODAL ================= -->
<div class="modal fade" id="addModal">
<div class="modal-dialog">
<div class="modal-content p-3">
<form method="POST">
<input type="hidden" name="batch" value="<?= $selected_batch ?>">
<textarea name="question_name" class="form-control mb-2" placeholder="Question" required></textarea>
<input type="text" name="option_a" class="form-control mb-2" placeholder="Option A" required>
<input type="text" name="option_b" class="form-control mb-2" placeholder="Option B" required>
<input type="text" name="option_c" class="form-control mb-2" placeholder="Option C" required>
<input type="text" name="option_d" class="form-control mb-2" placeholder="Option D" required>
<select name="correct_option" class="form-control mb-2">
<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
</select>
<button type="submit" name="save" class="btn btn-success w-100">Save Question</button>
</form>
</div>
</div>
</div>

<!-- ================= EDIT MODAL ================= -->
<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content p-3">
<form method="POST">
<input type="hidden" name="edit_id" id="edit_id">
<textarea name="question_name" id="edit_question" class="form-control mb-2"></textarea>
<input type="text" name="option_a" id="edit_a" class="form-control mb-2">
<input type="text" name="option_b" id="edit_b" class="form-control mb-2">
<input type="text" name="option_c" id="edit_c" class="form-control mb-2">
<input type="text" name="option_d" id="edit_d" class="form-control mb-2">
<select name="correct_option" id="edit_correct" class="form-control mb-2">
<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
</select>
<button type="submit" name="update" class="btn btn-primary w-100">Update Question</button>
</form>
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ================= EDIT QUESTION MODAL =================
$('.editBtn').click(function(){
    $('#edit_id').val($(this).data('id'));
    $('#edit_question').val($(this).data('question'));
    $('#edit_a').val($(this).data('a'));
    $('#edit_b').val($(this).data('b'));
    $('#edit_c').val($(this).data('c'));
    $('#edit_d').val($(this).data('d'));
    $('#edit_correct').val($(this).data('correct'));
});
</script>

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