<?php 
session_start();
// Strong no-cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: private, no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once "db.php";
require_once "flash.php";

date_default_timezone_set('Asia/Dhaka');


/* ========= INPUT VALIDATION ========= */
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$batch   = isset($_GET['batch']) ? (int)$_GET['batch'] : 0;

if($user_id <= 0 || $batch <= 0){
    header("Location: my_exams.php");
    exit;
}


/* ========= HANDLE FORM SUBMISSION ========= */
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $answers = $_POST['answer'] ?? [];

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

    $stmt = $conn->prepare("UPDATE users_tbl SET question_all=?, answer_all=? WHERE id=?");
    $stmt->bind_param("ssi",$question_all,$answer_all,$user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: result.php?id=$user_id&batch=$batch");
    exit;
}

/* ========= EXAM SCHEDULE ========= */
$stmt = $conn->prepare("SELECT exam_date, end_time FROM authority_tbl WHERE batch=? LIMIT 1");
$stmt->bind_param("i", $batch);
$stmt->execute();
$exam = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$exam){
    die("Exam schedule not found.");
}

$endTimeObj = new DateTime($exam['end_time']);
$cleanEndTime = $endTimeObj->format('H:i:s');

$examEndDateTime = $exam['exam_date'].' '.$cleanEndTime;

if(date('Y-m-d H:i:s') >= $examEndDateTime){
    die("Exam time already over.");
}

$examEndISO = date('Y-m-d\TH:i:s', strtotime($examEndDateTime));

/* ========= LOAD QUESTIONS ========= */
$stmt = $conn->prepare("SELECT * FROM question_set WHERE batch=? ORDER BY id ASC");
$stmt->bind_param("i", $batch);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if(empty($questions)){
    die("No questions found.");
}
// ===== Destroy session after fetching results =====


?>
<!DOCTYPE html>
<html>
<head>
<title>Give Exam</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* BODY BACKGROUND */
body{
    background: linear-gradient(135deg,#eef2f7,#ffffff);
    font-family: 'Inter', sans-serif;
}

/* HEADER WITH TITLE + TIMER */
.exam-header{
    position:relative;
    display:flex;
    align-items:center;
    justify-content: center; /* center content horizontally */
    margin-bottom:20px;
    padding:15px 10px;
    background-color: #667eea; /* single color header */
    color: #fff; /* text color */
    border-radius: 10px; /* slightly rounded corners */
    box-shadow: 0 6px 18px rgba(0,0,0,0.15); /* soft shadow */
}

/* Title inside header */
.exam-title{
    position:relative;
    margin:0;
    font-weight:600;
    font-size:24px;
    color: #fff; /* white text */
}

/* Batch name styling */
.batch-name{
    font-weight:700;
    color: #fff; /* same color as header text */
}

/* TIMER CONTAINER inside header (HH:MM:SS) */
.timer-container{
    display:flex;
    align-items:center;
    position:absolute;
    right:20px;
    top:50%;
    transform: translateY(-50%);
    gap:4px;
    font-family: 'Courier New', monospace;
}

.time-box{
    background: linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    font-size:18px;
    font-weight:bold;
    text-align:center;
    min-width:35px;
}

.time-separator{
    font-size:18px;
    font-weight:bold;
    color:#fff;
}

/* TIMER BOX (for external use, keep your original) */
.timer-box{
    background: linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    padding:8px 14px;
    border-radius:8px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
    width:160px;
    margin-left:auto;
    text-align:center;
}

.timer-value{
    font-size:18px;
    font-weight:bold;
}

.timer-value.danger{
    animation: blink 1s infinite;
}

@keyframes blink{
    50%{opacity:0.5;}
}

/* QUESTION CARD */
.question-card{
    border:none;
    border-radius:12px;
    padding:18px;
    box-shadow:0 3px 12px rgba(0,0,0,0.05);
    margin-bottom:18px;
    background:#fff;
}

/* STICKY SUBMIT BUTTON AREA */
.sticky{
    position:sticky;
    bottom:0;
    background:#fff;
    padding:15px;
    box-shadow:0 -3px 10px rgba(0,0,0,0.05);
    text-align:center;
}

.submit-btn{
    font-size:18px;
    padding:10px 30px;
}

/* RADIO BUTTON STYLE */
.form-check-input:checked{
    background-color:#667eea;
    border-color:#667eea;
}

.form-check-label{
    cursor:pointer;
}

/* RESPONSIVE */
@media (max-width:768px){
    .exam-title{
        position:static;
        transform:none;
        text-align:center;
        margin-bottom:10px;
    }
    .timer-container{
        position:static;
        transform:none;
        margin-top:10px;
        justify-content:center;
    }
    .timer-box{
        width:100%;
        margin-left:0;
        margin-bottom:15px;
    }
}
</style>
</head>
<body>

<div class="container mt-4 bg-white p-4 shadow rounded">

<!-- HEADER -->
<div class="exam-header">
    <h4 class="exam-title">
        Exam - Batch <span class="batch-name"><?= htmlspecialchars($batch) ?></span>
    </h4>

    <!-- 3-PART TIMER -->
    <div class="timer-container">
        <div class="time-box" id="hours">00</div>
        <div class="time-separator">:</div>
        <div class="time-box" id="minutes">00</div>
        <div class="time-separator">:</div>
        <div class="time-box" id="seconds">00</div>
    </div>
</div>

<form method="POST" id="examForm">
<input type="hidden" name="user_id" value="<?= $user_id ?>">
<input type="hidden" name="batch" value="<?= $batch ?>">

<?php foreach($questions as $index => $q): ?>
<div class="question-card">
    <strong>Q<?= $index+1 ?>. <?= htmlspecialchars($q['question_name']) ?></strong>

    <?php foreach(['A','B','C','D'] as $opt): ?>
    <div class="form-check mt-2">
        <input class="form-check-input"
               type="radio"
               name="answer[<?= $q['id'] ?>]"
               value="<?= $opt ?>"
               id="q<?= $q['id'].$opt ?>">
        <label class="form-check-label" for="q<?= $q['id'].$opt ?>">
            <?= htmlspecialchars($q['option_'.strtolower($opt)]) ?>
        </label>
    </div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<div class="sticky">
    <button type="submit" class="btn btn-primary submit-btn">Submit Exam</button>
</div>
</form>
</div>

<script>
// 🔹 Countdown for 3-box timer
const examEnd = new Date("<?= $examEndISO ?>+06:00").getTime();
const form = document.getElementById("examForm");

let submitted = false;

function autoSubmit(){
    if(!submitted){
        submitted = true;
        form.submit();
    }
}

function updateCountdown(){
    const now = new Date().getTime();
    const diff = examEnd - now;

    if(diff <= 0){
        document.getElementById("hours").innerText = "00";
        document.getElementById("minutes").innerText = "00";
        document.getElementById("seconds").innerText = "00";
        autoSubmit();
        return;
    }

    const hours = Math.floor(diff/(1000*60*60));
    const minutes = Math.floor((diff%(1000*60*60))/(1000*60));
    const seconds = Math.floor((diff%(1000*60))/1000);

    document.getElementById("hours").innerText = String(hours).padStart(2,'0');
    document.getElementById("minutes").innerText = String(minutes).padStart(2,'0');
    document.getElementById("seconds").innerText = String(seconds).padStart(2,'0');
}

updateCountdown();
setInterval(updateCountdown, 1000);



document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('copy', e => e.preventDefault());
document.addEventListener('cut', e => e.preventDefault());
document.addEventListener('paste', e => e.preventDefault());
document.addEventListener('selectstart', e => e.preventDefault());

document.addEventListener('keydown', function(e){
    if(e.ctrlKey && ['c','v','x','s','u','p'].includes(e.key.toLowerCase())) e.preventDefault();
    if(e.key === "F12") e.preventDefault();
});

document.addEventListener("visibilitychange", function(){
    if(document.hidden) autoSubmit();
});

window.addEventListener("beforeunload", function(e){
    if(!submitted){
        submitted = true;
        form.submit();
    }
});
</script>

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


</body>
</html>