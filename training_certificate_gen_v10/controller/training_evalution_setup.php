<?php 
session_name('training_certificate_gen_db');
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

/* ================= GET SELECTED BATCH ================= */
$selected_batch = $_GET['batch'] ?? '';

// Get batch-level settings (date & status)
$batch_date = '';
$batch_status = 'active';

if($selected_batch) {
    // Try to get existing batch settings from the first question (they should all be same)
    $stmt = $conn->prepare("SELECT evaluation_date, evaluation_status FROM evaluation_set WHERE batch = ? LIMIT 1");
    $stmt->bind_param("i", $selected_batch);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
        $batch_date = $row['evaluation_date'];
        $batch_status = $row['evaluation_status'];
    }
    $stmt->close();
}

/* ================= UPDATE BATCH SETTINGS (updates ALL questions in batch) ================= */
if(isset($_POST['update_batch_settings'])){
    $batch = (int)$_POST['batch'];
    $eval_date = $_POST['evaluation_date'];
    $eval_status = $_POST['evaluation_status'];
    
    // Update ALL questions in this batch with the same date and status
    $stmt = $conn->prepare("UPDATE evaluation_set SET evaluation_date = ?, evaluation_status = ? WHERE batch = ?");
    $stmt->bind_param("ssi", $eval_date, $eval_status, $batch);
    $stmt->execute();
    $stmt->close();
    
    header("Location: training_evalution_setup.php?batch=".$batch);
    exit;
}

/* ================= DELETE ================= */
if(isset($_GET['delete']) && $selected_batch != ''){
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM evaluation_set WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    header("Location: training_evalution_setup.php?batch=".$selected_batch);
    exit;
}

/* ================= INSERT ================= */
if(isset($_POST['save'])){
    // Use batch-level date and status for new questions
    $eval_date = $_POST['batch_evaluation_date'] ?: date('Y-m-d');
    $eval_status = $_POST['batch_evaluation_status'] ?: 'active';
    
    $stmt = $conn->prepare("INSERT INTO evaluation_set 
    (batch, evaluation_question_name, option_a, option_b, option_c, option_d, evaluation_date, evaluation_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssssss",
        $_POST['batch'],
        $_POST['question_name'],
        $_POST['option_a'],
        $_POST['option_b'],
        $_POST['option_c'],
        $_POST['option_d'],
        $eval_date,
        $eval_status
    );

    $stmt->execute();
    header("Location: training_evalution_setup.php?batch=".$_POST['batch']);
    exit;
}

/* ================= UPDATE SINGLE QUESTION ================= */
if(isset($_POST['update'])){
    $stmt = $conn->prepare("UPDATE evaluation_set SET
    evaluation_question_name=?, option_a=?, option_b=?, option_c=?, option_d=?
    WHERE id=?");

    $stmt->bind_param("sssssi",
        $_POST['question_name'],
        $_POST['option_a'],
        $_POST['option_b'],
        $_POST['option_c'],
        $_POST['option_d'],
        $_POST['edit_id']
    );

    $stmt->execute();
    header("Location: training_evalution_setup.php?batch=".$selected_batch);
    exit;
}

/* ================= COPY ================= */
if(isset($_POST['copy_batch'])){
    $source_batch = (int) $_POST['source_batch'];
    $new_batch = (int) $_POST['new_batch'];

    if($source_batch == $new_batch){
        echo "<script>alert('Source and New Batch cannot be same!');</script>";
    } else {

        $check = $conn->prepare("SELECT id FROM evaluation_set WHERE batch=? LIMIT 1");
        $check->bind_param("i", $new_batch);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            echo "<script>alert('This batch already has questions!');</script>";
        } else {

            $copy = $conn->prepare("
                INSERT INTO evaluation_set 
                (batch, evaluation_question_name, option_a, option_b, option_c, option_d, evaluation_date, evaluation_status)
                SELECT ?, evaluation_question_name, option_a, option_b, option_c, option_d, evaluation_date, evaluation_status
                FROM evaluation_set WHERE batch=?
            ");
            $copy->bind_param("ii", $new_batch, $source_batch);
            $copy->execute();

            header("Location: training_evalution_setup.php?batch=".$new_batch);
            exit;
        }
    }
}

/* ================= FETCH QUESTIONS ================= */
$questions = [];
if($selected_batch){
    $stmt = $conn->prepare("SELECT * FROM evaluation_set WHERE batch=? ORDER BY id ASC");
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
<title>Evaluation Question Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
     <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
<style>
    * {
            font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
        }
.batch-select { display:flex; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
.card-evaluation { transition: transform 0.2s, box-shadow 0.2s; }
.card-evaluation:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
.status-badge { font-size: 0.7rem; padding: 5px 10px; border-radius: 20px; }
.date-badge { background: #f0f0f0; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; }
.question-text { font-weight: 500; background: #f8f9fa; padding: 8px 12px; border-radius: 8px; margin: 10px 0; }
.settings-panel { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 20px; margin-bottom: 25px; }
.settings-panel label { color: white; font-weight: 500; }
</style>
</head>

<body class="container mt-4">

<h3><i class="bi bi-clipboard-check"></i> Evaluation Question Management</h3>

<!-- ================= BATCH SELECT ================= -->
<div class="batch-select">
<form method="GET" style="width: 300px;">
<select name="batch" class="form-select" required onchange="this.form.submit()">
<option value="">-- Select Batch --</option>

<?php
$res = $conn->query("
    SELECT 
        a.batch,
        a.training_title,
        COUNT(q.id) as total_questions
    FROM authority_tbl a
    LEFT JOIN evaluation_set q ON a.batch = q.batch
    GROUP BY a.batch, a.training_title
    ORDER BY a.batch DESC
");

while($row = $res->fetch_assoc()){
    $isNew = ($row['total_questions'] == 0);
    $label = $isNew 
        ? "New Training - Batch-" . $row['batch'] . " (" . $row['training_title'] . ")" 
        : "Batch-" . $row['batch'] . " (" . $row['training_title'] . ") - " . $row['total_questions'] . " questions";
?>
<option value="<?= $row['batch'] ?>" <?= ($selected_batch==$row['batch'])?'selected':'' ?>>
<?= htmlspecialchars($label) ?>
</option>
<?php } ?>
</select>
</form>

<a href="dashboard.php" class="btn btn-primary">
<i class="bi bi-arrow-left me-2"></i> Back to Dashboard
</a>
</div>

<?php if($selected_batch): ?>

<!-- ================= BATCH LEVEL DATE & STATUS (SET ONCE FOR ALL MCQs) ================= -->
<div class="settings-panel">
    <form method="POST" class="row g-3 align-items-end">
        <input type="hidden" name="batch" value="<?= $selected_batch ?>">
        
        <div class="col-md-5">
            <label class="form-label"><i class="bi bi-calendar"></i> Evaluation Date (Applies to ALL questions)</label>
            <input type="date" name="evaluation_date" class="form-control" value="<?= $batch_date ?>" required>
        </div>
        
        <div class="col-md-5">
            <label class="form-label"><i class="bi bi-toggle-on"></i> Evaluation Status</label>
            <select name="evaluation_status" class="form-select">
                <option value="active" <?= $batch_status == 'active' ? 'selected' : '' ?>>✅ Active</option>
                <option value="inactive" <?= $batch_status == 'inactive' ? 'selected' : '' ?>>⏸️ Inactive</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <button type="submit" name="update_batch_settings" class="btn btn-light w-100 fw-bold">
                <i class="bi bi-save"></i> Apply to All
            </button>
        </div>
    </form>
</div>

<!-- Display current batch settings summary -->


<!-- ================= COPY ================= -->
<h5><i class="bi bi-copy"></i> Copy Existing Batch Questions</h5>
<form method="POST" class="row g-2 mb-4">

<div class="col-md-4">
<select name="source_batch" class="form-select" required>
<option value="">-- Select Source Batch --</option>

<?php
$res = $conn->query("
SELECT DISTINCT a.batch, a.training_title
FROM authority_tbl a
INNER JOIN evaluation_set q ON a.batch = q.batch
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
LEFT JOIN evaluation_set q ON a.batch = q.batch
WHERE q.batch IS NULL
ORDER BY a.batch ASC");

while($row = $res->fetch_assoc()){
    $label = "New Training - Batch-" . $row['batch'] . " (" . $row['training_title'] . ")";
?>
<option value="<?= $row['batch'] ?>"><?= htmlspecialchars($label) ?></option>
<?php } ?>
</select>
</div>

<div class="col-md-4">
<button type="submit" name="copy_batch" class="btn btn-warning w-100">
<i class="bi bi-import"></i> Import previous Questions
</button>
</div>

</form>

<hr>

<!-- ================= ADD BUTTON ================= -->
<div class="d-flex justify-content-between align-items-center my-3">
    <h5><i class="bi bi-question-circle-fill me-2"></i>MCQ Questions (Batch #<?= $selected_batch ?>)</h5>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-circle"></i> Add New Question
    </button>
</div>

<!-- ================= SHOW QUESTIONS (NO DATE/STATUS INSIDE CARDS) ================= -->
<?php if($selected_batch && !empty($questions)): ?>
<div class="row">

<?php foreach($questions as $idx => $q): ?>

<div class="col-md-6">
<div class="card p-3 mb-3 h-100 card-evaluation">

<div class="d-flex justify-content-between align-items-start">
    <strong class="badge bg-primary">Question <?= $idx+1 ?></strong>
    <div>
        <button class="btn btn-primary btn-sm editBtn"
        data-id="<?= $q['id'] ?>"
        data-question="<?= htmlspecialchars($q['evaluation_question_name']) ?>"
        data-a="<?= htmlspecialchars($q['option_a']) ?>"
        data-b="<?= htmlspecialchars($q['option_b']) ?>"
        data-c="<?= htmlspecialchars($q['option_c']) ?>"
        data-d="<?= htmlspecialchars($q['option_d']) ?>"
        data-bs-toggle="modal"
        data-bs-target="#editModal">
        <i class="bi bi-pencil"></i> Edit
        </button>

        <a href="?batch=<?= $selected_batch ?>&delete=<?= $q['id'] ?>"
        class="btn btn-danger btn-sm"
        onclick="return confirm('Delete this question permanently?')">
        <i class="bi bi-trash"></i> Delete
        </a>
    </div>
</div>

<div class="question-text mt-2">
    <?= nl2br(htmlspecialchars($q['evaluation_question_name'])) ?>
</div>

<div class="row mb-2">
    <div class="col-6"><i class="bi bi-circle"></i> A. <?= htmlspecialchars($q['option_a']) ?></div>
    <div class="col-6"><i class="bi bi-circle"></i> B. <?= htmlspecialchars($q['option_b']) ?></div>
    <div class="col-6"><i class="bi bi-circle"></i> C. <?= htmlspecialchars($q['option_c']) ?></div>
    <div class="col-6"><i class="bi bi-circle"></i> D. <?= htmlspecialchars($q['option_d']) ?></div>
</div>

</div>
</div>

<?php endforeach; ?>

</div>
<?php elseif($selected_batch && empty($questions)): ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> No questions found for this batch. Click "Add New Question" to create evaluation questions.
</div>
<?php endif; ?>

<?php else: ?>
<div class="alert alert-secondary text-center p-5">
    <i class="bi bi-folder2-open fs-1"></i>
    <h5 class="mt-3">Please select a batch to manage evaluation questions</h5>
    <p>Choose a training batch from the dropdown above.</p>
</div>
<?php endif; ?>

<!-- ================= ADD MODAL (NO DATE/STATUS FIELDS) ================= -->
<div class="modal fade" id="addModal">
<div class="modal-dialog modal-lg">
<div class="modal-content p-3">

<form method="POST">
<h5 class="mb-3"><i class="bi bi-plus-circle"></i> Add New Evaluation Question</h5>

<input type="hidden" name="batch" value="<?= $selected_batch ?>">
<input type="hidden" name="batch_evaluation_date" value="<?= $batch_date ?>">
<input type="hidden" name="batch_evaluation_status" value="<?= $batch_status ?>">

<label class="form-label fw-bold">Question / Statement</label>
<textarea name="question_name" class="form-control mb-2" placeholder="Enter evaluation question" rows="3" required></textarea>

<div class="row">
    <div class="col-md-6">
        <label class="form-label">Option A</label>
        <input type="text" name="option_a" class="form-control mb-2" placeholder="Option A" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Option B</label>
        <input type="text" name="option_b" class="form-control mb-2" placeholder="Option B" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Option C</label>
        <input type="text" name="option_c" class="form-control mb-2" placeholder="Option C" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Option D</label>
        <input type="text" name="option_d" class="form-control mb-2" placeholder="Option D" required>
    </div>
</div>

<div class="alert alert-info mt-2">
    <i class="bi bi-info-circle"></i> This question will inherit the batch date and status set above.
</div>

<button type="submit" name="save" class="btn btn-success w-100">
<i class="bi bi-save"></i> Save Question
</button>

</form>

</div>
</div>
</div>

<!-- ================= EDIT MODAL (NO DATE/STATUS FIELDS) ================= -->
<div class="modal fade" id="editModal">
<div class="modal-dialog modal-lg">
<div class="modal-content p-3">

<form method="POST">
<h5 class="mb-3"><i class="bi bi-pencil-square"></i> Edit Evaluation Question</h5>

<input type="hidden" name="edit_id" id="edit_id">

<label class="form-label fw-bold">Question / Statement</label>
<textarea name="question_name" id="edit_question" class="form-control mb-2" rows="3" required></textarea>

<div class="row">
    <div class="col-md-6">
        <label class="form-label">Option A</label>
        <input type="text" name="option_a" id="edit_a" class="form-control mb-2" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Option B</label>
        <input type="text" name="option_b" id="edit_b" class="form-control mb-2" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Option C</label>
        <input type="text" name="option_c" id="edit_c" class="form-control mb-2" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Option D</label>
        <input type="text" name="option_d" id="edit_d" class="form-control mb-2" required>
    </div>
</div>

<div class="alert alert-secondary mt-2">
    <i class="bi bi-calendar"></i> <strong>Batch Settings:</strong> Date = <?= $batch_date ?: 'Not set' ?> | Status = <?= ucfirst($batch_status) ?>
</div>

<button type="submit" name="update" class="btn btn-primary w-100">
<i class="bi bi-pencil"></i> Update Question
</button>

</form>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    $('.editBtn').click(function(){
        $('#edit_id').val($(this).data('id'));
        $('#edit_question').val($(this).data('question'));
        $('#edit_a').val($(this).data('a'));
        $('#edit_b').val($(this).data('b'));
        $('#edit_c').val($(this).data('c'));
        $('#edit_d').val($(this).data('d'));
    });
});
</script>

</body>
</html>