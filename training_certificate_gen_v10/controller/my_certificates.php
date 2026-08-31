<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";
require_once "flash.php";

$user_name = $_SESSION['user_name'] ?? '';
$user_role = $_SESSION['user_role'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';

$email = $_GET['email'] ?? $user_email;

if ($email !== $user_email) {
    setFlash('error', 'Invalid request.');
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT u.id, u.name, u.designation, u.place_of_posting,
           u.remarks, u.batch,
           a.training_title, a.start_date, a.end_date
    FROM users_tbl u
    INNER JOIN authority_tbl a ON u.batch = a.batch
    WHERE u.email_id = ?
    ORDER BY u.id DESC
");

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Certificates & Evaluations</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    }
    
    .btn-info {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }
    
    .btn-info:hover {
        background-color: #1565c0;
        border-color: #1565c0;
    }
    
    .btn-warning {
        background-color: var(--primary-yellow);
        border-color: var(--primary-yellow);
        color: white;
    }
    
    .btn-warning:hover {
        background-color: #f57c00;
        border-color: #f57c00;
        color: white;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
        margin-left: 8px;
    }
    
    .evaluation-status {
        font-size: 12px;
        margin-top: 5px;
    }
    
    .evaluated-badge {
        background-color: var(--primary-green);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        font-weight: 500;
    }
    
    .expired-badge {
        background-color: var(--primary-red);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        font-weight: 500;
    }
    
    .upcoming-badge {
        background-color: var(--primary-blue);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        font-weight: 500;
    }
    
    .date-info {
        font-size: 11px;
        color: var(--text-gray);
        margin-top: 3px;
    }
    
    .no-evaluation {
        color: var(--text-gray);
        font-size: 12px;
        font-style: italic;
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
    
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #fafafa;
    }
    
    .table-striped > tbody > tr:hover > * {
        background-color: #e3f2fd;
    }
    
    .card-footer {
        background-color: transparent;
        border-top: 1px solid var(--border-color);
        padding-top: 15px;
    }
    
    .card-footer h6 {
        color: var(--text-gray);
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        color: var(--text-dark);
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: white !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--primary-pink);
        border-color: var(--primary-pink);
        color: white !important;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 5px 10px;
    }
    
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--primary-blue);
        outline: none;
        box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.1);
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 4px;
    }
    
    /* SweetAlert customization */
    .swal2-popup {
        font-size: 0.9rem;
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
    }
</style>
</head>

<body>

<div class="container mt-3 shadow rounded p-4">

<?php showFlash(); ?>

<h3 class="mb-4 pb-2">
    <i class="fa fa-certificate" style="color: var(--primary-pink);"></i>
    My Certificates & Training Evaluations

    <a href="dashboard.php" class="btn btn-secondary float-end ms-2">
        <i class="fa fa-arrow-left"></i> Back
    </a>

    <a href="logout.php" class="btn btn-outline-danger float-end ms-2">
        <i class="fa fa-sign-out"></i> Logout
    </a>
</h3>

<table id="userTable" class="table table-bordered table-striped">

<thead>
<tr>
<th>Name</th>
<th>Designation</th>
<th>Place of Posting</th>
<th>Training Title</th>
<th>Duration</th>
<th>Certificate</th>
<th>Evaluation</th>
</tr>
</thead>

<tbody>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <?php 
        // Check if evaluation already submitted
        $is_evaluated = false;
        if(!empty($row['remarks'])) {
            // Get all active evaluation questions for this batch
            $eval_check_stmt = $conn->prepare("SELECT id FROM evaluation_set WHERE batch=? AND evaluation_status = 'active'");
            $eval_check_stmt->bind_param("i", $row['batch']);
            $eval_check_stmt->execute();
            $eval_result = $eval_check_stmt->get_result();
            
            while($eval_row = $eval_result->fetch_assoc()) {
                if(strpos($row['remarks'], "(" . $eval_row['id'] . ",") !== false) {
                    $is_evaluated = true;
                    break;
                }
            }
            $eval_check_stmt->close();
        }
        
        // Check if current date is between start_date and end_date
        $current_date = date('Y-m-d');
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        
        $is_within_date_range = ($current_date >= $start_date && $current_date <= $end_date);
        $is_before_start = ($current_date < $start_date);
        $is_after_end = ($current_date > $end_date);
        
        // Check if evaluation is active (has questions)
        $has_active_questions = false;
        $active_check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM evaluation_set WHERE batch=? AND evaluation_status = 'active'");
        $active_check_stmt->bind_param("i", $row['batch']);
        $active_check_stmt->execute();
        $active_count = $active_check_stmt->get_result()->fetch_assoc();
        $has_active_questions = ($active_count['count'] > 0);
        $active_check_stmt->close();
        
        // Check if evaluation button should be shown
        $show_evaluation_button = (!$is_evaluated && $is_within_date_range && $has_active_questions);
        
        // Get evaluation status message for non-available cases
        $status_message = '';
        $status_class = '';
        
        if($is_evaluated) {
            $status_message = '✓ Evaluation Submitted';
            $status_class = 'evaluated-badge';
        } elseif($is_before_start) {
            $status_message = '⏰ Available from ' . date('d M Y', strtotime($start_date));
            $status_class = 'upcoming-badge';
        } elseif($is_after_end) {
            $status_message = '❌ Evaluation Closed on ' . date('d M Y', strtotime($end_date));
            $status_class = 'expired-badge';
        } elseif(!$has_active_questions) {
            $status_message = '📋 No Evaluation Available';
            $status_class = 'expired-badge';
        }
    ?>
    <tr>
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['designation']); ?></td>
        <td><?= htmlspecialchars($row['place_of_posting']); ?></td>
        <td><?= htmlspecialchars($row['training_title']); ?></td>
        
        <td>
            <?= date('d M Y', strtotime($row['start_date'])) . " to " . date('d M Y', strtotime($row['end_date'])); ?>
        </td>
        
        <td class="text-center">
            <a href="user_certificate_pdf.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">
                <i class="fa fa-eye"></i> View Certificate
            </a>
        </td>
        
        <td class="text-center">
            <?php if($show_evaluation_button): ?>
                <a href="training_evaluation_sheet.php?batch=<?= $row['batch'] ?>&user_id=<?= $row['id'] ?>" 
                   class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i> Submit Evaluation
                </a>
            <?php else: ?>
                <?php if(!empty($status_message)): ?>
                    <div class="<?= $status_class ?>">
                        <i class="fa <?= $is_evaluated ? 'fa-check-circle' : ($is_before_start ? 'fa-clock-o' : 'fa-times-circle') ?>"></i>
                        <?= $status_message ?>
                    </div>
                <?php else: ?>
                    <div class="no-evaluation">
                        <i class="fa fa-minus-circle"></i> Not Available
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center">No training records found.</td>
    </tr>
<?php endif; ?>

</tbody>

</table>

<div class="card-footer mt-4">
    <h6 class="text-end">
        <i class="fa fa-code"></i> Design & Developed By ICT Division
    </h6>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#userTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                previous: "<",
                next: ">"
            }
        }
    });
});
</script>

<script>
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