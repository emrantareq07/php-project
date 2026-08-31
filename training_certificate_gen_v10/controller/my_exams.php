<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";
require_once "flash.php";

$user_email = $_SESSION['user_email'] ?? '';

if (empty($user_email)) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Dhaka');

$current_datetime = date('Y-m-d H:i:s');

// Fetch user exams and batch info
$stmt = $conn->prepare("
    SELECT 
        u.id,
        u.batch,
        u.question_all,
        u.answer_all,  
        a.training_title,
        a.exam_date,
        a.start_time,
        a.end_time,
        a.active_exam
    FROM users_tbl u
    INNER JOIN authority_tbl a ON u.batch = a.batch
    WHERE u.email_id = ?
");

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My All Exams</title>
<meta http-equiv="refresh" content="2">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary-blue: #1976d2;
        --primary-green: #43a047;
        --primary-yellow: #ffa000;
        --primary-red: #e53935;
        --primary-pink: #d81b60;
        --bg-white: #ffffff;
        --bg-light: #f5f5f5;
        --text-dark: #333333;
        --text-gray: #757575;
        --border-color: #e0e0e0;
    }
    
    body {
        background-color: var(--bg-light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container {
        background: var(--bg-white);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    h3 {
        color: var(--primary-blue);
        font-weight: 600;
        border-left: 4px solid var(--primary-pink);
        padding-left: 15px;
        margin-bottom: 20px;
    }
    
    .btn-secondary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }
    
    .btn-secondary:hover {
        background-color: #1565c0;
        border-color: #1565c0;
    }
    
    .btn-outline-danger {
        color: var(--primary-red);
        border-color: var(--primary-red);
    }
    
    .btn-outline-danger:hover {
        background-color: var(--primary-red);
        border-color: var(--primary-red);
        color: white;
    }
    
    .btn-primary {
        background-color: var(--primary-yellow);
        border-color: var(--primary-yellow);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #f57c00;
        border-color: #f57c00;
        color: white;
    }
    
    .btn-success {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .btn-success:hover {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }
    
    .table {
        border-color: var(--border-color);
    }
    
    .table thead th {
        background-color: var(--primary-blue);
        color: white;
        border-bottom: none;
        font-weight: 600;
    }
    
    .table-bordered {
        border: 1px solid var(--border-color);
    }
    
    .table-bordered td, 
    .table-bordered th {
        border: 1px solid var(--border-color);
    }
    
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #fafafa;
    }
    
    .table-striped > tbody > tr:hover > * {
        background-color: #e3f2fd;
    }
    
    .small.text-muted {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        border-left: 3px solid var(--primary-pink);
        margin-bottom: 20px;
    }
    
    .btn-primary i, .btn-success i, .btn-secondary i {
        margin-right: 5px;
    }
    
    .instructions-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .instructions-box strong {
        color: white;
    }
    
    .badge-info-custom {
        background-color: var(--primary-blue);
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 15px !important;
        }
        
        h3 {
            font-size: 1.3rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .small.text-muted {
            font-size: 0.75rem;
        }
    }
</style>
</head>
<body>

<div class="container mt-3 p-4 shadow rounded">

    <!-- Section Title -->
    <h3 class="mb-3 pb-2">
        <i class="fa fa-file-text-o" style="color: var(--primary-pink);"></i>
        My All Exam Details
        <a href="dashboard.php" class="btn btn-secondary btn-sm me-2 float-end">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm float-end me-2">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </h3>

    <!-- Inline Instructions -->
    <div class="instructions-box mb-3">
        <strong class="me-2"><i class="fa fa-info-circle me-1"></i> General Instructions:</strong>
        <span>
            <i class="fa fa-window-minimize text-white me-1"></i> Don't minimize the browser
            <i class="fa fa-plus-square text-white ms-3 me-1"></i> Don't open a new tab
            <i class="fa fa-refresh text-white ms-3 me-1"></i> Don't reload the browser
            <i class="fa fa-copy text-white ms-3 me-1"></i> Don't try to copy questions
        </span>
    </div>

    <?php showFlash(); ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Training Title</th>
                    <th>Exam Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Batch No</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): 
                
                // Clean time (remove microseconds if exists)
                $clean_start_time = explode('.', $row['start_time'])[0];
                $clean_end_time   = explode('.', $row['end_time'])[0];
                
                // Format display time
                $start = ($clean_start_time == '00:00:00') ? '' : date("h:i A", strtotime($clean_start_time));
                $end   = ($clean_end_time == '00:00:00') ? '' : date("h:i A", strtotime($clean_end_time));
                
                // Check if exam already taken
                $exam_taken = !empty(trim($row['question_all'])) && !empty(trim($row['answer_all']));
                
                // Create full datetime for exam
                $exam_start_datetime = $row['exam_date'] . ' ' . $clean_start_time;
                $exam_end_datetime   = $row['exam_date'] . ' ' . $clean_end_time;
                
                // Determine if exam can be given
                $can_give = false;
                
                if (
                    $row['active_exam'] === 'active' &&
                    !$exam_taken &&
                    $current_datetime >= $exam_start_datetime &&
                    $current_datetime <= $exam_end_datetime
                ) {
                    $can_give = true;
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['training_title']) ?></td>
                    <td><?= htmlspecialchars($row['exam_date']) ?></td>
                    <td><?= $start ?></td>
                    <td><?= $end ?></td>
                    <td>
                        <span class="badge bg-info"><?= htmlspecialchars($row['batch']) ?></span>
                    </td>
                    <td class="text-center">
                        <?php if ($exam_taken): ?>
                            <a href="result.php?id=<?= $row['id'] ?>&batch=<?= urlencode($row['batch']) ?>" 
                               class="btn btn-success btn-sm">
                                <i class="fa fa-eye"></i> View Result
                            </a>
                        <?php elseif ($can_give): ?>
                            <a href="give_exam.php?id=<?= $row['id'] ?>&batch=<?= urlencode($row['batch']) ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-play"></i> Start Exam
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="fa fa-ban"></i> Not Available
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if ($result->num_rows == 0): ?>
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle"></i> No exams found for your account.
        </div>
    <?php endif; ?>

</div>

<!-- Bootstrap JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Disable Back & Forward
history.pushState(null, null, location.href);
window.onpopstate = function () {
   window.location.href = "reload_handler.php";
};

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