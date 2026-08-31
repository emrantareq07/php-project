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
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$batch   = isset($_GET['batch']) ? (int)$_GET['batch'] : 0;

if($user_id <= 0 || $batch <= 0){
    setFlash('error', 'Invalid request parameters.');
    header("Location: dashboard.php");
    exit;
}

/* ========= CHECK IF EVALUATION IS ACTIVE FOR THIS BATCH ========= */
$check_active_stmt = $conn->prepare("SELECT COUNT(*) as active_count FROM evaluation_set WHERE batch=? AND evaluation_status = 'active'");
$check_active_stmt->bind_param("i", $batch);
$check_active_stmt->execute();
$active_check = $check_active_stmt->get_result()->fetch_assoc();

if($active_check['active_count'] == 0) {
    setFlash('error', 'Evaluation is not active for this training batch.');
    header("Location: dashboard.php");
    exit;
}
$check_active_stmt->close();

/* ========= CHECK IF EVALUATION ALREADY SUBMITTED ========= */
$check_stmt = $conn->prepare("SELECT remarks FROM users_tbl WHERE id = ?");
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$user_data = $check_result->fetch_assoc();

// Check if evaluation already exists in remarks for this batch
// Look for pattern like "(1, A) (2, B)" etc. for this batch's questions
$existing_remarks = $user_data['remarks'] ?? '';
$has_evaluation = false;

// Get all question IDs for this batch to check if they exist in remarks
$q_stmt = $conn->prepare("SELECT id FROM evaluation_set WHERE batch=? AND evaluation_status = 'active'");
$q_stmt->bind_param("i", $batch);
$q_stmt->execute();
$q_result = $q_stmt->get_result();
while($q_row = $q_result->fetch_assoc()) {
    if(strpos($existing_remarks, "(" . $q_row['id'] . ",") !== false) {
        $has_evaluation = true;
        break;
    }
}
$q_stmt->close();

if($has_evaluation) {
    setFlash('warning', 'You have already submitted your evaluation for this training.');
    header("Location: dashboard.php");
    exit;
}
$check_stmt->close();

/* ========= HANDLE FORM SUBMISSION ========= */
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $answers = $_POST['answer'] ?? [];
    
    // Format answers as "(id, answer_option)" for each question
    $formatted_answers = [];
    foreach($answers as $question_id => $answer_option) {
        $answer_option = trim($answer_option);
        if(!empty($answer_option)) {
            $formatted_answers[] = "(" . (int)$question_id . ", " . $answer_option . ")";
        }
    }
    
    // Create the remarks string with evaluation data (no prefix)
    $evaluation_data = implode(" ", $formatted_answers);
    
    // Check if user already has remarks, append if exists
    $current_remarks = $user_data['remarks'] ?? '';
    if(!empty($current_remarks)) {
        $new_remarks = $current_remarks . " | " . $evaluation_data;
    } else {
        $new_remarks = $evaluation_data;
    }

    $feedback = trim($_POST['feedback'] ?? '');
    
    // Update user table with evaluation answers in remarks column only
$stmt = $conn->prepare("UPDATE users_tbl SET remarks=?, feedback=? WHERE id=?");
$stmt->bind_param("ssi", $new_remarks, $feedback, $user_id);
    
    if($stmt->execute()) {
        setFlash('success', 'Your evaluation has been submitted successfully!');
        header("Location: dashboard.php");
        exit;
    } else {
        setFlash('error', 'Failed to submit evaluation. Please try again.');
    }
    $stmt->close();
}

/* ========= LOAD ACTIVE EVALUATION QUESTIONS FROM evaluation_set TABLE ========= */
$stmt = $conn->prepare("SELECT * FROM evaluation_set WHERE batch=? AND evaluation_status = 'active' ORDER BY id ASC");
$stmt->bind_param("i", $batch);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if(empty($questions)){
    die("No active evaluation questions found for this training.");
}

// Get training details
$stmt = $conn->prepare("SELECT training_title, start_date, end_date FROM authority_tbl WHERE batch=? LIMIT 1");
$stmt->bind_param("i", $batch);
$stmt->execute();
$training = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get user details
$stmt = $conn->prepare("SELECT name, designation FROM users_tbl WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Evaluation Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        
        .evaluation-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .evaluation-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .user-info {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .training-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        
        .active-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 10px;
        }
        
        .question-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }
        
        .question-number {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .question-text {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        
        .options-container {
            margin-left: 20px;
        }
        
        .option-item {
            margin-bottom: 12px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }
        
        .option-item:hover {
            background: #f8f9fa;
        }
        
        .option-item input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
            transform: scale(1.1);
        }
        
        .option-item label {
            cursor: pointer;
            font-size: 16px;
            color: #555;
            margin-bottom: 0;
        }
        
        .sticky-submit {
            position: sticky;
            bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 -3px 15px rgba(0,0,0,0.1);
            text-align: center;
            z-index: 100;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 40px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .progress-bar-container {
            background: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            background: linear-gradient(90deg, #667eea, #764ba2);
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }
        
        .required-star {
            color: red;
            margin-left: 5px;
        }
        
        .evaluation-info {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #0c5460;
        }
        
        @media (max-width: 768px) {
            .question-card {
                padding: 15px;
            }
            
            .options-container {
                margin-left: 10px;
            }
            
            .btn-submit {
                width: 100%;
            }
        }
        
        /* Animation */
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .question-card {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Radio button custom style */
        input[type="radio"] {
            accent-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="evaluation-container">
        <div class="evaluation-header">
            <div class="user-info">
                <h4><i class="fas fa-user-graduate"></i> <?= htmlspecialchars($user['name'] ?? 'Participant') ?></h4>
                <p class="mb-0"><i class="fas fa-briefcase"></i> <?= htmlspecialchars($user['designation'] ?? '') ?></p>
            </div>
            <h3>
                <i class="fas fa-clipboard-list"></i> Training Evaluation Form
                <span class="active-badge"><i class="fas fa-check-circle"></i> Active</span>
            </h3>
            <div class="training-info">
                <strong><i class="fas fa-chalkboard-user"></i> Training Title:</strong> <?= htmlspecialchars($training['training_title']) ?><br>
                <strong><i class="fas fa-calendar-alt"></i> Duration:</strong> <?= date('d M Y', strtotime($training['start_date'])) ?> - <?= date('d M Y', strtotime($training['end_date'])) ?><br>
                <strong><i class="fas fa-hashtag"></i> Batch:</strong> <?= htmlspecialchars($batch) ?>
            </div>
            <div class="evaluation-info mt-2">
                <i class="fas fa-info-circle"></i> Please select the appropriate option for each question. All questions are mandatory. Your feedback is valuable for improving future training programs.
            </div>
        </div>
        
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBar"></div>
        </div>
        
        <form method="POST" id="evaluationForm" onsubmit="return confirmSubmission()">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" name="batch" value="<?= $batch ?>">
            
            <?php foreach($questions as $index => $q): ?>
            <div class="question-card" data-question="<?= $index ?>">
                <span class="question-number">Question <?= $index+1 ?> of <?= count($questions) ?></span>
                <div class="question-text">
                    <?= htmlspecialchars($q['evaluation_question_name']) ?>
                    <span class="required-star">*</span>
                </div>
                
                <div class="options-container">
                    <?php 
                    $options = ['A', 'B', 'C', 'D'];
                    foreach($options as $opt):
                        $option_field = 'option_' . strtolower($opt);
                        $option_value = $q[$option_field] ?? '';
                        if(!empty($option_value)):
                    ?>
                    <div class="option-item">
                        <input type="radio" 
                               name="answer[<?= $q['id'] ?>]" 
                               value="<?= $opt ?>" 
                               id="q<?= $q['id'] ?>_<?= $opt ?>"
                               data-question="<?= $index ?>"
                               required>
                        <label for="q<?= $q['id'] ?>_<?= $opt ?>">
                            <strong><?= $opt ?>.</strong> <?= htmlspecialchars($option_value) ?>
                        </label>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
            <?php endforeach; ?>

<div class="card mt-3 shadow-sm">
    <div class="card-body">
<label for="feedback" class="form-label fw-bold fs-4 text-primary">
    <i class="fas fa-comment-dots me-2"></i> Additional Feedback
</label>

        <textarea 
            name="feedback" 
            id="feedback" 
            class="form-control" 
            rows="4" 
            placeholder="Write your feedback here (optional)..."></textarea>
    </div>
</div>
            
            <div class="sticky-submit">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Submit Evaluation
                </button>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    // Progress bar update
    function updateProgress() {
        const totalQuestions = <?= count($questions) ?>;
        let answeredQuestions = 0;
        
        <?php foreach($questions as $q): ?>
            const radios_<?= $q['id'] ?> = document.querySelectorAll('input[name="answer[<?= $q['id'] ?>]"]');
            let answered = false;
            radios_<?= $q['id'] ?>.forEach(radio => {
                if(radio.checked) answered = true;
            });
            if(answered) answeredQuestions++;
        <?php endforeach; ?>
        
        const percentage = (answeredQuestions / totalQuestions) * 100;
        document.getElementById('progressBar').style.width = percentage + '%';
        
        // Change color when all questions answered
        if(percentage === 100) {
            document.getElementById('progressBar').style.background = 'linear-gradient(90deg, #28a745, #20c997)';
        }
    }
    
    // Add event listeners to all radio buttons
    <?php foreach($questions as $q): ?>
        document.querySelectorAll('input[name="answer[<?= $q['id'] ?>]"]').forEach(radio => {
            radio.addEventListener('change', updateProgress);
        });
    <?php endforeach; ?>
    
    // Confirmation before submit
    function confirmSubmission() {
        const totalQuestions = <?= count($questions) ?>;
        let answeredQuestions = 0;
        
        <?php foreach($questions as $q): ?>
            const radios_<?= $q['id'] ?> = document.querySelectorAll('input[name="answer[<?= $q['id'] ?>]"]');
            let answered = false;
            radios_<?= $q['id'] ?>.forEach(radio => {
                if(radio.checked) answered = true;
            });
            if(answered) answeredQuestions++;
        <?php endforeach; ?>
        
        if(answeredQuestions < totalQuestions) {
            Swal.fire({
                title: 'Incomplete Evaluation!',
                text: `You have answered ${answeredQuestions} out of ${totalQuestions} questions. Do you still want to submit?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'No, continue'
            }).then((result) => {
                if(result.isConfirmed) {
                    document.getElementById('evaluationForm').submit();
                }
            });
            return false;
        }
        
        Swal.fire({
            title: 'Submit Evaluation?',
            text: 'Are you sure you want to submit your evaluation? You cannot modify it after submission.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                document.getElementById('evaluationForm').submit();
            }
        });
        return false;
    }
    
    // Initial progress update
    updateProgress();
    
    // Disable right click and copy
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('copy', e => e.preventDefault());
    document.addEventListener('cut', e => e.preventDefault());
    document.addEventListener('paste', e => e.preventDefault());
    
    // Prevent F12 and Ctrl+Shift+I
    document.addEventListener('keydown', function(e) {
        if(e.key === "F12" || (e.ctrlKey && e.shiftKey && e.key === 'I') || 
           (e.ctrlKey && e.key === 'u')) {
            e.preventDefault();
        }
    });
    
    // Prevent page refresh on back/forward
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        window.location.href = "dashboard.php";
    };
    
    // Warn before leaving page
    let formChanged = false;
    document.querySelectorAll('input[type="radio"]').forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });
    

    </script>


    <script>

// Disable Back & Forward
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
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


</body>
</html>