<?php
session_start();
require_once "db.php";

$user_id = $_POST['user_id'] ?? '';
$batch   = $_POST['batch'] ?? '';
$answers = $_POST['answer'] ?? [];

if(empty($user_id) || empty($batch)){
    header("Location: my_exams.php");
    exit;
}


/* Fetch ALL questions */
$stmt = $conn->prepare("SELECT id, correct_option FROM question_set WHERE batch=? ORDER BY id ASC");
$stmt->bind_param("i", $batch);
$stmt->execute();
$result = $stmt->get_result();

$question_ids = [];
$user_answers = [];
$correct_count = 0;

while($row = $result->fetch_assoc()){

    $q_id = $row['id'];
    $correct = $row['correct_option'];

    $question_ids[] = $q_id;

    if(isset($answers[$q_id]) && !empty($answers[$q_id])){
        $selected = $answers[$q_id];
        $user_answers[] = $selected;

        if($selected === $correct){
            $correct_count++;
        }
    }else{
        $user_answers[] = "N";
    }
}

$stmt->close();

$question_all = "'" . implode("','",$question_ids) . "'";
$answer_all   = "'" . implode("','",$user_answers) . "'";

/* Save */
$stmt = $conn->prepare("UPDATE users_tbl SET question_all=?, answer_all=? WHERE id=?");
$stmt->bind_param("ssi",$question_all,$answer_all,$user_id);
$stmt->execute();
$stmt->close();



header("Location: result.php?id=$user_id&batch=$batch");
exit;

