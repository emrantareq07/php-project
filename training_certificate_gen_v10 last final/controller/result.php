<?php

session_start();
// Strong no-cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: private, no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


require_once "db.php";
require_once "flash.php";

$user_id = $_GET['id'] ?? '';
$batch   = $_GET['batch'] ?? '';

if(empty($user_id) || empty($batch)){
    die("Invalid request.");
}


// Fetch user answers
$stmt = $conn->prepare("SELECT question_all, answer_all FROM users_tbl WHERE id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$stmt->bind_result($q_all,$a_all);
$stmt->fetch();
$stmt->close();

if(empty($q_all) || empty($a_all)){
    die("No answers submitted.");
}

// Split submissions
$user_questions = explode("','", trim($q_all,"'"));
$user_answers   = explode("','", trim($a_all,"'"));

$correct_count = 0;
$questions_detail = [];

foreach($user_questions as $index => $q_id){

    $stmt = $conn->prepare("SELECT * FROM question_set WHERE id=?");
    $stmt->bind_param("i",$q_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($row){
        $user_letter = $user_answers[$index] ?? "N";
        $correct_letter = $row['correct_option'];

        $options = [
            'A'=>$row['option_a'],
            'B'=>$row['option_b'],
            'C'=>$row['option_c'],
            'D'=>$row['option_d']
        ];

        $user_text = ($user_letter === "N") ? "Not Answered" : ($options[$user_letter] ?? "Not Answered");
        $correct_text = $options[$correct_letter];

        if($user_letter === $correct_letter){
            $correct_count++;
        }

        $questions_detail[] = [
            'question'=>$row['question_name'],
            'answer'=>$user_text,
            'correct'=>$correct_text
        ];
    }
}

$total = count($questions_detail);
$wrong = $total - $correct_count;
$percentage = ($total > 0) ? round(($correct_count / $total) * 100,2) : 0;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4 bg-white p-4 shadow rounded">
    <h3>Exam Result (Batch <?= htmlspecialchars($batch) ?>)</h3>

    <div class="mb-3">
        <strong>Total Questions:</strong> <?= $total ?> |
        <strong>Correct:</strong> <?= $correct_count ?> |
        <strong>Wrong:</strong> <?= $wrong ?>
    </div>

    <div class="text-center my-4">
        <p class="fs-4 fw-semibold text-success">
            Examination Completed Successfully
        </p>

        <span class="display-2 text-success fw-bold">
            <?= $percentage ?>%
        </span>

        <p class="fs-5 text-muted">
            Overall Performance
        </p>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr class="text-center align-middle">
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
            </tr>
        </thead>
        <tbody>
            <?php if($total > 0): ?>
                <?php foreach($questions_detail as $q): ?>
                <tr class="text-center align-middle">
                    <td><?= htmlspecialchars($q['question']) ?></td>
                    <td><?= htmlspecialchars($q['answer']) ?></td>
                    <td><?= htmlspecialchars($q['correct']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No Questions Found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="my_exams.php" class="btn btn-secondary">Back to My Exams</a>
</div>

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


// Disable Right Click
document.addEventListener("contextmenu", function (e) {
    e.preventDefault();
});

window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        window.location.href = "../index.php";
    }
});
</script>

