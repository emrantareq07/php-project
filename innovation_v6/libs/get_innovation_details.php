<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $query = "SELECT * FROM tbl_innovation WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Employee ID:</h6>
                    <p><?php echo htmlspecialchars($row['emp_id']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Full Name:</h6>
                    <p><?php echo htmlspecialchars($row['fullname']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Designation:</h6>
                    <p><?php echo htmlspecialchars($row['designation']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Email:</h6>
                    <p><?php echo htmlspecialchars($row['email']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Mobile:</h6>
                    <p><?php echo htmlspecialchars($row['mobile_no']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Place of Posting:</h6>
                    <p><?php echo htmlspecialchars($row['place_of_posting']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Fiscal Year:</h6>
                    <p><?php echo htmlspecialchars($row['fiscal_year']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Title of Idea:</h6>
                    <p><?php echo htmlspecialchars($row['title_of_idea']); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Implementation Date:</h6>
                    <p><?php echo $row['idea_imp_date'] ? date('d-m-Y', strtotime($row['idea_imp_date'])) : '-'; ?></p>
                </div>
                <div class="col-12 mb-3">
                    <h6 class="fw-bold">Problem Description:</h6>
                    <p><?php echo nl2br(htmlspecialchars($row['identify_prob_desc'])); ?></p>
                </div>
                <div class="col-12 mb-3">
                    <h6 class="fw-bold">Solution Plan:</h6>
                    <p><?php echo nl2br(htmlspecialchars($row['prob_sol_plan'])); ?></p>
                </div>
                <div class="col-12 mb-3">
                    <h6 class="fw-bold">Solution Description:</h6>
                    <p><?php echo nl2br(htmlspecialchars($row['prob_sol_desc'])); ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold">Cost:</h6>
                    <p><?php echo $row['cost'] ? '৳ ' . number_format($row['cost'], 2) : '-'; ?></p>
                </div>
                <div class="col-md-8 mb-3">
                    <h6 class="fw-bold">Cost Less Description:</h6>
                    <p><?php echo htmlspecialchars($row['cost_less_desc']) ?: '-'; ?></p>
                </div>
                <div class="col-12 mb-3">
                    <h6 class="fw-bold">Value Addition:</h6>
                    <p><?php echo htmlspecialchars($row['value_add']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Time Saving:</h6>
                    <p><?php echo htmlspecialchars($row['time_saving']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Cost Effectiveness:</h6>
                    <p><?php echo htmlspecialchars($row['cost_effectiveness']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Profitability:</h6>
                    <p><?php echo htmlspecialchars($row['profitability']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Implementation Status:</h6>
                    <p><?php echo htmlspecialchars($row['imple_status']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Replicate Eligibility:</h6>
                    <p><?php echo htmlspecialchars($row['replicate_eligibility']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Remarks:</h6>
                    <p><?php echo htmlspecialchars($row['remarks']) ?: '-'; ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold">Prize:</h6>
                    <p><?php echo $row['prize'] == 'yes' ? 'Yes' : 'No'; ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold">Prize Amount:</h6>
                    <p><?php echo htmlspecialchars($row['prize_amount']) ?: '-'; ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold">Rank:</h6>
                    <p><?php echo htmlspecialchars($row['rank']) ?: '-'; ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Status:</h6>
                    <p><?php echo htmlspecialchars($row['status']) ?: '-'; ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="fw-bold">Created At:</h6>
                    <p><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="fw-bold">Updated At:</h6>
                    <p><?php echo date('d-m-Y H:i', strtotime($row['updated_at'])); ?></p>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-danger">Record not found</div>';
    }
}
?>