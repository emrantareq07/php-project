<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";

// Strong no-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: private, no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch distinct batches from authority_tbl
$res = $conn->query("
    SELECT DISTINCT batch, training_title, start_date, end_date
    FROM authority_tbl 
    ORDER BY batch DESC
");

$batches = [];
while ($row = $res->fetch_assoc()) {
    $batches[] = $row;
}

$selected_batch = isset($_GET['batch']) ? (int)$_GET['batch'] : 0;
$participants = [];
$evaluation_questions = [];

if($selected_batch > 0) {
    // Get training details
    $stmt = $conn->prepare("SELECT training_title, start_date, end_date FROM authority_tbl WHERE batch = ? LIMIT 1");
    $stmt->bind_param("i", $selected_batch);
    $stmt->execute();
    $training = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Get evaluation questions for this batch
    $stmt = $conn->prepare("SELECT * FROM evaluation_set WHERE batch = ? AND evaluation_status = 'active' ORDER BY id ASC");
    $stmt->bind_param("i", $selected_batch);
    $stmt->execute();
    $evaluation_questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Get all participants for this batch
    $stmt = $conn->prepare("
        SELECT id, name, email_id, designation, place_of_posting, remarks 
        FROM users_tbl 
        WHERE batch = ? 
        ORDER BY name ASC
    ");
    $stmt->bind_param("i", $selected_batch);
    $stmt->execute();
    $participants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Score mapping
    $score_map = [
        'A' => 1, // Bad
        'B' => 2, // Good
        'C' => 3, // Better
        'D' => 4  // Best
    ];
    
    // Parse evaluation answers for each participant and calculate score
    foreach($participants as &$participant) {
        $participant['answers'] = [];
        $participant['total_score'] = 0;
        $participant['average_score'] = 0;
        $participant['obtain_marks']=0;
        
        if(!empty($participant['remarks'])) {
            // Extract answers in format (id, option)
            preg_match_all('/\((\d+),\s*([A-D])\)/', $participant['remarks'], $matches, PREG_SET_ORDER);
            $answer_count = 0;
            foreach($matches as $match) {
                $q_id = $match[1];
                $answer = $match[2];
                $participant['answers'][$q_id] = $answer;
                
                // Calculate total score
                if(isset($score_map[$answer])) {
                    $participant['total_score'] += $score_map[$answer];
                    $answer_count++;
                }
            }
            
            // Calculate average score (total_score / number of questions)
            $total_questions = count($evaluation_questions);
            if($total_questions > 0) {
              $participant['obtain_marks'] = $participant['total_score'] . ' out of ' . ($total_questions * 4);

                $participant['average_score'] = round($participant['total_score'] / $total_questions, 2);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch-wise Evaluation Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .batch-selector {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .evaluation-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .participant-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .participant-header:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .participant-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
        }
        
        .participant-details.show {
            display: block;
        }
        
        .answer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .answer-table th {
            background: #e9ecef;
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        
        .answer-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .answer-option {
            font-weight: bold;
            color: #667eea;
        }
        
        .no-answer {
            color: #dc3545;
            font-style: italic;
        }
        
        .badge-submitted {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .badge-not-submitted {
            background-color: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .collapse-icon {
            float: right;
            font-size: 20px;
        }
        
        .btn-view-full {
            background: #17a2b8;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        
        .btn-view-full:hover {
            background: #138496;
            color: white;
        }
        
        .btn-view-full i {
            margin-right: 5px;
        }
        
        .btn-show-evaluation {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 12px;
            text-decoration: none;
            margin-left: 15px;
        }
        
        .btn-show-evaluation:hover {
            background: #218838;
            color: white;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-3">
    <div class="page-header">
        <h2><i class="fas fa-chalkboard"></i> Batch-wise Evaluation Sheet</h2>
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    
    <div class="batch-selector">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Select Batch</label>
                <select name="batch" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Batch --</option>
                    <?php foreach($batches as $batch): ?>
                        <option value="<?= $batch['batch'] ?>" <?= $selected_batch == $batch['batch'] ? 'selected' : '' ?>>
                            Batch <?= $batch['batch'] ?> - <?= htmlspecialchars($batch['training_title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if($selected_batch > 0 && !empty($participants)): ?>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-success d-block" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>
    
    <?php if($selected_batch > 0): ?>
        <?php if(empty($participants)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No participants found for this batch.
            </div>
        <?php elseif(empty($evaluation_questions)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No active evaluation questions found for this batch.
            </div>
        <?php else: ?>
            <div class="evaluation-card" id="evaluationSheet">
                <h4>
                    <i class="fas fa-clipboard-list"></i> 
                    Batch <?= $selected_batch ?> - Evaluation Summary
                    <span class="badge bg-primary float-end">
                        Total Participants: <?= count($participants) ?>
                    </span>
                </h4>
                <hr>
                
                <?php foreach($participants as $index => $participant): 
                    $has_answers = !empty($participant['answers']);
                    $answer_count = count($participant['answers']);
                    $total_questions = count($evaluation_questions);
                ?>



                <div class="participant-item mb-3">


<div class="card mb-2 shadow-sm" onclick="toggleParticipant(<?= $index ?>)" style="cursor:pointer;">
    <div class="card-body py-2">

        <div class="row align-items-center">

            <!-- LEFT: Participant Info -->
            <div class="col-md-6">
                <h6 class="mb-1">
                    <i class="fas fa-user text-primary"></i>
                    <?= htmlspecialchars($participant['name']) ?>
                </h6>

                <small class="text-muted">
                    <?= htmlspecialchars($participant['designation']) ?>,
                    <?= htmlspecialchars($participant['place_of_posting']) ?>
                </small>
            </div>

            <!-- RIGHT: Score + Rating + Action -->
            <div class="col-md-6 text-md-end mt-2 mt-md-0">

                <?php if($has_answers): ?>
                    <?php
                        $ratings = [
                            1 => 'Bad',
                            2 => 'Average',
                            3 => 'Good',
                            4 => 'Excellent'
                        ];
                        $score = round($participant['average_score']);

                        // Badge color based on rating
                        $badgeClass = [
                            1 => 'bg-danger',
                            2 => 'bg-warning ',
                            3 => 'bg-info ',
                            4 => 'bg-success'
                        ];
                    ?>

                    <span class="badge <?= $badgeClass[$score] ?? 'bg-secondary'; ?> me-1">
                        <i class="fas fa-chart-line"></i>
                        <?= $participant['obtain_marks'] ?? 'N/A'; ?>
                    </span>

                    <span class="badge <?= $badgeClass[$score] ?? 'bg-secondary'; ?> me-2">
                        <i class="fas fa-star"></i>
                        <?= $ratings[$score] ?? 'N/A'; ?>
                    </span>
                <?php endif; ?>

<!--                 <a href="participant_evaluation.php?user_id=<?= $participant['id'] ?>&batch=<?= $selected_batch ?>"
                   class="btn btn-sm btn-outline-primary"
                   onclick="event.stopPropagation()">
                    <i class="fas fa-external-link-alt"></i> View
                </a> -->

<a href="participant_evaluation.php?
    user_id=<?= $participant['id'] ?>
    &batch=<?= $selected_batch ?>
    &obtain_marks=<?= urlencode($participant['obtain_marks']) ?>
    &average_score=<?= urlencode($participant['average_score']) ?>"
   class="btn btn-sm btn-outline-primary"
   onclick="event.stopPropagation()">
    <i class="fas fa-external-link-alt"></i> View
</a>

            </div>

        </div>

    </div>
</div>





                    <div class="participant-details" id="participant-<?= $index ?>">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Email:</strong> <?= htmlspecialchars($participant['email_id']) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Designation:</strong> <?= htmlspecialchars($participant['designation']) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Place of Posting:</strong> <?= htmlspecialchars($participant['place_of_posting']) ?>
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong>
                                <?php if($has_answers): ?>
                                    <span class="badge-submitted">Submitted</span>
                                <?php else: ?>
                                    <span class="badge-not-submitted">Not Submitted</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <h6><i class="fas fa-question-circle"></i> Quick Answers Summary:</h6>
                        <table class="answer-table">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="65%">Question</th>
                                    <th width="30%">Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($evaluation_questions as $q_index => $q): 
                                    $q_id = $q['id'];
                                    $answer = isset($participant['answers'][$q_id]) ? $participant['answers'][$q_id] : null;
                                    $option_text = '';
                                    if($answer) {
                                        $option_field = 'option_' . strtolower($answer);
                                        $option_text = isset($q[$option_field]) ? $q[$option_field] : '';
                                    }
                                ?>
                                <tr>
                                    <td><?= $q_index + 1 ?></td>
                                    <td><?= htmlspecialchars($q['evaluation_question_name']) ?></td>
                                    <td class="answer-option">
                                        <?php if($answer): ?>
                                            <strong><?= $answer ?>.</strong> <?= htmlspecialchars($option_text) ?>
                                        <?php else: ?>
                                            <span class="no-answer">Not Answered</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <?php if(!$has_answers): ?>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-info-circle"></i> This participant has not submitted the evaluation yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Please select a batch to view evaluation sheet.
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
function toggleParticipant(index) {
    var element = document.getElementById('participant-' + index);
    
    if(element.classList.contains('show')) {
        element.classList.remove('show');
    } else {
        element.classList.add('show');
    }
}

function exportToExcel() {
    // Create worksheet data
    const data = [];
    
    // Header row
    const headers = ['Name', 'Email', 'Designation', 'Place of Posting', 'Status'];
    <?php foreach($evaluation_questions as $q): ?>
        headers.push('<?= addslashes($q['evaluation_question_name']) ?>');
    <?php endforeach; ?>
    headers.push('Average Score');
    data.push(headers);
    
    // Data rows
    <?php foreach($participants as $participant): ?>
        const row = [
            '<?= addslashes($participant['name']) ?>',
            '<?= addslashes($participant['email_id']) ?>',
            '<?= addslashes($participant['designation']) ?>',
            '<?= addslashes($participant['place_of_posting']) ?>',
            '<?= !empty($participant['answers']) ? "Submitted" : "Not Submitted" ?>'
        ];
        
        <?php foreach($evaluation_questions as $q): ?>
            <?php 
            $answerText = '';
            if(isset($participant['answers'][$q['id']])) {
                $opt = $participant['answers'][$q['id']];
                $field = 'option_' . strtolower($opt);
                $answerText = $opt . ' - ' . (isset($q[$field]) ? addslashes($q[$field]) : '');
            } else {
                $answerText = 'Not Answered';
            }
            ?>
            row.push('<?= $answerText ?>');
        <?php endforeach; ?>
        
        row.push('<?= isset($participant['average_score']) ? $participant['average_score'] : 0 ?>');
        data.push(row);
    <?php endforeach; ?>
    
    // Create worksheet
    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Batch <?= $selected_batch ?> Evaluation');
    
    // Auto-size columns (optional)
    const maxWidth = headers.map(header => header.length);
    ws['!cols'] = maxWidth.map(w => ({wch: Math.min(w + 5, 50)}));
    
    // Export to file
    XLSX.writeFile(wb, `batch_<?= $selected_batch ?>_evaluation.xlsx`);
}
</script>

</body>
</html>