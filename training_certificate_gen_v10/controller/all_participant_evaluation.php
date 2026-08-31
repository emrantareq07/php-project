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

$selected_batch = isset($_GET['batch']) ? (int)$_GET['batch'] : 0;

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
        SELECT id, name, email_id, designation, place_of_posting, remarks, feedback 
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
        'B' => 2, // Average
        'C' => 3, // Good
        'D' => 4  // Excellent
    ];
    
    $ratings = [
        1 => 'Bad',
        2 => 'Average',
        3 => 'Good',
        4 => 'Excellent'
    ];
    
    // Function to convert A/B/C/D to 1/2/3/4
    function convertAnswerToNumber($answer) {
        $mapping = [
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4
        ];
        return isset($mapping[$answer]) ? $mapping[$answer] : null;
    }
    
    // Parse evaluation answers for each participant and calculate score
    foreach($participants as &$participant) {
        $participant['answers'] = [];
        $participant['total_score'] = 0;
        $participant['average_score'] = 0;
        $participant['obtain_marks'] = '';
        $participant['score_value'] = 0;
        
        if(!empty($participant['remarks'])) {
            // Extract answers in format (id, option)
            preg_match_all('/\((\d+),\s*([A-D])\)/', $participant['remarks'], $matches, PREG_SET_ORDER);
            $total_questions = count($evaluation_questions);
            
            foreach($matches as $match) {
                $q_id = $match[1];
                $answer = $match[2];
                $participant['answers'][$q_id] = $answer;
                
                // Calculate total score
                if(isset($score_map[$answer])) {
                    $participant['total_score'] += $score_map[$answer];
                }
            }
            
            // Calculate average score and obtain marks
            if($total_questions > 0) {
                $participant['average_score'] = round($participant['total_score'] / $total_questions, 2);
                $participant['obtain_marks'] = $participant['total_score'] . ' out of ' . ($total_questions * 4);
                $participant['score_value'] = round($participant['average_score']);
            }
        }
    }
    unset($participant);
} else {
    die("Invalid batch selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
       <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    
 <style>
        * {
            font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
        }
   
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
            padding: 30px 0;
        }
        
        .evaluation-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .header-card {
            background: white;
            border-radius: 20px;
            border-color: white;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.0);
        }
        
        .user-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        
        .user-info h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .user-info .row {
            font-size: 14px;
        }
        
        .user-info i {
            margin-right: 8px;
            opacity: 0.9;
        }
        
        .training-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 5px solid #667eea;
        }
        
        .training-info h5 {
            color: #667eea;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .status-submitted {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-not-submitted {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        
        .stat-item {
            flex: 1;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        .stat-item:last-child {
            border-right: none;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
        }
        
        .stat-label {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        /* Rating Legend Box */
        .rating-legend {
            background: #f0f4ff;
            border: 1px solid #667eea;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: inline-block;
            width: 100%;
        }
        
        .rating-legend h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .legend-items {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-number {
            width: 32px;
            height: 32px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .legend-text {
            font-size: 14px;
            color: #333;
        }
        
        /* Question Table Styles */
        .questions-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        
        .questions-table th {
            background: #667eea;
            color: white;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            border: 1px solid #7b8cde;
            text-align: center;
        }
        
        .questions-table td {
            padding: 15px;
            vertical-align: middle;
            border: 1px solid #e9ecef;
            background: white;
        }
        
        .questions-table tr:nth-child(even) td {
            background: #f8f9fa;
        }
        
        .question-cell {
            font-weight: 500;
            color: #333;
            line-height: 1.4;
        }
        
        /* Answer Boxes Container */
        .answer-boxes {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .answer-box {
            width: 55px;
            height: 55px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.2s;
        }
        
        /* Removed green background from selected answer box */
        .answer-box.selected {
            border-color: #28a745;
            font-weight: bold;
            /* background color removed */
        }
        
        .answer-box .box-number {
            font-size: 20px;
            font-weight: bold;
        }
        
        .answer-box .box-label {
            font-size: 10px;
            margin-top: 3px;
        }
        
        /* Marks Card */
        .marks-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
        
        .marks-card h4 {
            color: #667eea;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .marks-row {
            display: flex;
            justify-content: space-around;
            text-align: center;
            padding: 20px 0;
        }
        
        .marks-item {
            flex: 1;
            border-right: 1px solid #e9ecef;
        }
        
        .marks-item:last-child {
            border-right: none;
        }
        
        .marks-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .marks-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }
        
        .feedback-card {
            background: white;
            border-radius:2px;
            padding: 0px;
            margin-bottom: 0px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }
        
        .feedback-card h6 {
            color: #000000;
            margin-bottom: 5px;
            font-weight: 10;
            border-bottom: 1px solid #667eea;
            padding-bottom: 1px;
            display: inline-block;
        }
        
        .feedback-content {
            background: #f1f5f9;
            padding: 1px;
            border-radius: 5px;
            font-size: 10px;
           
            color: #333;
        }
        
        .btn-back {
            background: white;
            color: #667eea;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: #667eea;
        }
        
        .btn-print-all {
            background: white;
            color: #667eea;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-print-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: #667eea;
        }
        
        .footer-note {
            background: white;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }
        
        /* ========== PRINT STYLES ========== */
        @media print {
            .header-card,
            .user-info,
            .summary-card,
            .marks-card,
            .feedback-card,
            .questions-table,
            .answer-box {
                page-break-inside: avoid;
                page-break-after: auto;
                page-break-before: auto;
            }
            
            table, tr, td, th {
                page-break-inside: avoid;
            }
            
            body {
                font-size: 12px;
                line-height: 1.2;
            }

            .header-card, 
            .summary-card, 
            .marks-card, 
            .feedback-card {
                padding: 10px !important;
                margin-bottom: 10px !important;
            }

            .questions-table th,
            .questions-table td {
                padding: 6px !important;
            }

            .answer-box {
                width: 30px !important;
                height: 30px !important;
                font-size: 10px !important;
            }
            
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .evaluation-container {
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .header-card, 
            .summary-card,
            .marks-card,
            .feedback-card {
                box-shadow: none;
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
            
            .user-info {
                background: #667eea;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .summary-card {
                background: #667eea;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .user-info, 
            .summary-card,
            .user-info *,
            .summary-card * {
                color: white !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .questions-table th {
                background: #667eea;
                color: white;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            /* Removed green background from print styles */
            .answer-box.selected {
          
                font-weight: bold;
                /* background removed */

                -webkit-print-color-adjust: exact;
            }


            
            .rating-legend {
                border: 1px solid #667eea;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .legend-number {
                background: #667eea;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .status-submitted {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            @page {
                margin: 5mm;
                @bottom-center {
                    content: "Page " counter(page) " of " counter(pages);
                    font-size: 10px;
                    color: #666;
                }
            }
            
            .questions-table {
                page-break-inside: avoid;
            }
            
            .answer-box {
                border: 1px solid #333;
            }
        }
        
        @media (max-width: 768px) {
            .evaluation-container {
                padding: 0 15px;
            }
            
            .user-info h2 {
                font-size: 22px;
            }
            
            .stat-number {
                font-size: 24px;
            }
            
            .questions-table th,
            .questions-table td {
                padding: 10px;
            }
            
            .answer-box {
                width: 45px;
                height: 45px;
            }
            
            .answer-box .box-number {
                font-size: 16px;
            }
            
            .marks-value {
                font-size: 24px;
            }
            
            .marks-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .marks-item {
                border-right: none;
                border-bottom: 1px solid #e9ecef;
                padding-bottom: 15px;
            }
            
            .legend-items {
                gap: 8px;
            }
            
            .legend-number {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
            
            .legend-text {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<div class="evaluation-container">
    <!-- Header with buttons (hidden when printing) -->
    <div class="header-card no-print">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="evaluation_by_batch.php?batch=<?= $selected_batch ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Batch Sheet
            </a>
            <button onclick="window.print()" class="btn-print-all">
                <i class="fas fa-print"></i> Print All Evaluation Sheets
            </button>
        </div>
    </div>
    
    <!-- Loop through each participant and generate evaluation sheet -->
    <?php foreach($participants as $participant): ?>
    <!-- Main Content -->
    <div class="header-card">
        <!-- Top Trainer Evaluation Sheet Title -->
        <div class="text-center mb-4">
            <h2 style="color: #667eea; font-weight: 700;">
                <i class="fas fa-certificate"></i> Training Evaluation Sheet (Batch-<?= htmlspecialchars($selected_batch) ?>)
            </h2>
            <hr style="border-top: 2px solid #667eea; width: 100px; margin: 10px auto;">
        </div>

        <div style="margin-bottom:8px;">
            <strong>Training Title:</strong> <?= htmlspecialchars($training['training_title']) ?>
        </div>

        <div style="margin-bottom:8px;">
            <strong>Venue:</strong> ICT Division (4th floor), BCIC Bhaban, 30-31 Dilkusha C/A, Dhaka-1000
        </div>

        <div style="margin-bottom:8px;">
            <strong>Duration:</strong> <?= date('d M Y', strtotime($training['start_date'])) ?> - <?= date('d M Y', strtotime($training['end_date'])) ?>
        </div>

        <div style="margin-bottom:8px;">
            <strong>Participant:</strong> <?= htmlspecialchars($participant['name']) ?>, <?= htmlspecialchars($participant['designation']) ?>, <?= htmlspecialchars($participant['place_of_posting']) ?>
        </div> 

        <br><br>

        <div> 
            <h6 class="text-center fw-bold"> Rating Scale: 1=Bad, 2=Average, 3=Good, 4=Excellent</h6>
        </div>
        
        <table class="questions-table table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="8%" class="text-center">Sl/No</th>
                    <th width="55%">Evaluation Question</th>
                    <th width="37%" class="text-center">Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sl_no = 1;
                foreach($evaluation_questions as $q): 
                    $q_id = $q['id'];
                    $selected_answer = $participant['answers'][$q_id] ?? null;
                    $selected_number = convertAnswerToNumber($selected_answer);
                ?>
                <tr>
                    <td class="text-center align-middle fw-bold"><?= $sl_no++ ?></td>
                    <td class="question-cell"><?= htmlspecialchars($q['evaluation_question_name']) ?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <?php for($i=1; $i<=4; $i++): ?>
                                <div class="answer-box p-2 border rounded <?= ($selected_number == $i) ? 'selected' : '' ?>" style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                    <?= ($selected_number == $i) ? "$i&#10003;" : $i ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(empty($evaluation_questions)): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle"></i> No evaluation questions found for this batch.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Participant Feedback -->
        <?php if(!empty($participant['feedback'])): ?>
        <div class="feedback-card">
            <h6> Participant Feedback</h6>
            <div class="feedback-content">
                <?= nl2br(htmlspecialchars($participant['feedback'])) ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Marks Section (Obtain Marks & Average Score) -->
        <?php if($participant['obtain_marks']): ?>
        <div class="marks-card p-3 mb-4 bg-light rounded shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-star text-warning fa-lg"></i>
                <div class="marks-label">
                    <strong>Obtain Marks:</strong> <?= $participant['obtain_marks'] ?> <br>
                    <strong>Overall Rating:</strong> <?= $ratings[$participant['score_value']] ?? 'N/A'; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>  
        
        <!-- Footer Note -->
        <div class="footer-note no-print">
            <small class="text-muted">
                <i class="fas fa-print"></i> Click the Print button above for a printer-friendly version.
                This report was generated on <?= date('d M Y, h:i A') ?>.
            </small>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if(empty($participants)): ?>
    <div class="header-card text-center">
        <div class="alert alert-warning mb-0">
            <i class="fas fa-exclamation-triangle"></i> No participants found for this batch.
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>