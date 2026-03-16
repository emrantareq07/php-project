<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

/* ===============================
   GET ACTIVE FISCAL YEAR
=================================*/
$recent_query = "
    SELECT fiscal_year 
    FROM tbl_innovation_idea 
    WHERE idea_status='active'
    ORDER BY id DESC
    LIMIT 1
";

$recent_result = mysqli_query($conn, $recent_query);
$row_fiscal_year = mysqli_fetch_assoc($recent_result);
$fiscal_year = $row_fiscal_year['fiscal_year'] ?? '';

/* ===============================
   FETCH INNOVATION DATA
=================================*/
// $sql = "SELECT * FROM tbl_innovation WHERE fiscal_year='$fiscal_year' && (status='submitted idea'|| status='primarily idea') ORDER BY id DESC";
// $result = mysqli_query($conn, $sql);

/* ===============================
   FETCH INNOVATION DATA
=================================*/
$sql = "SELECT * 
        FROM tbl_innovation 
        WHERE fiscal_year = '$fiscal_year' 
          AND (status = 'submitted idea' OR status = 'primarily selected') 
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
include_once 'includes/header_new.php';
?>

<div class="container-fluid-custom">
        
<!-- Header Card -->
<div class="header-card" data-aos="fade-down">
    <div class="title-section">
        <h2><i class="fas fa-lightbulb me-2" style="color:#667eea;"></i>Submitted Innovation Ideas</h2>
        <p>Manage and review all innovation submissions</p>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="fiscal-badge">
            <i class="fas fa-calendar-alt"></i>
            Active Fiscal Year: <?= htmlspecialchars($fiscal_year) ?>
        </div>

        <a href="../dashboard.php" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
    </div>
</div>


<div class="main-card" data-aos="fade-up">
<!-- Statistics Row --> <?php $total_count = mysqli_num_rows($result); $approved_count = 0; $pending_count = 0; $total_cost = 0; if($result && $total_count > 0) { mysqli_data_seek($result, 0); while($row = mysqli_fetch_assoc($result)) { if($row['status'] == 'Approved') $approved_count++; if($row['status'] == 'Pending') $pending_count++; $total_cost += $row['cost']; } mysqli_data_seek($result, 0); } ?> <div class="stats-row"> <div class="stat-item" data-aos="fade-right" data-aos-delay="100"> <i class="fas fa-clipboard-list"></i> <div class="label">Total Submissions</div> <div class="value"><?= $total_count ?></div> </div> <div class="stat-item" data-aos="fade-right" data-aos-delay="200"> <i class="fas fa-check-circle"></i> <div class="label">Approved</div> <div class="value"><?= $approved_count ?></div> </div> <div class="stat-item" data-aos="fade-right" data-aos-delay="300"> <i class="fas fa-clock"></i> <div class="label">Pending</div> <div class="value"><?= $pending_count ?></div> </div> <div class="stat-item" data-aos="fade-right" data-aos-delay="400"> <i class="fas fa-coins"></i> <div class="label">Total Cost (BDT)</div> <div class="value">৳ <?= number_format($total_cost) ?></div> </div> </div>    

<?php 
$total_count = mysqli_num_rows($result);
?>

<!-- FORM START -->
<form method="post" action="final_select_process.php">

<div class="table-container">
<div class="table-responsive">

<table class="table">

<thead>
<tr>

<th>
<input type="checkbox" id="select_all">
</th>

<th>#</th>
<th>Employee</th>
<th>Designation</th>
<th>Idea Title</th>
<th>Cost</th>
<th>Status</th>
<th>Rank</th>
<th>Prize</th>
<th>Actions</th>

</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($result) > 0): ?>

<?php $i=1; while($row = mysqli_fetch_assoc($result)): ?>

<tr data-aos="fade-up" data-aos-delay="<?= $i*50 ?>">

<td>
<input type="checkbox" 
       name="final_ids[]" 
       value="<?= $row['id'] ?>" 
       class="idea_checkbox">
</td>

<td><?= $i++ ?></td>

<td>
<div class="employee-info">
<span class="employee-name"><?= htmlspecialchars($row['fullname']) ?></span>
<span class="employee-id"><?= htmlspecialchars($row['emp_id']) ?></span>
</div>
</td>

<td><?= htmlspecialchars($row['designation']) ?></td>

<td class="idea-title">
<?= htmlspecialchars($row['title_of_idea']) ?>
</td>

<td>
<span class="badge-cost">
৳ <?= number_format($row['cost']) ?>
</span>
</td>

<td>

<?php
$status = $row['status'];

if($status == 'submitted idea'){
echo '<span class="badge-status badge-pending">Submitted</span>';
}
elseif($status == 'primarily selected'){
echo '<span class="badge-status badge-approved">Primarily Selected</span>';
}
elseif($status == 'final selected'){
echo '<span class="badge-status badge-approved">Final Selected</span>';
}
else{
echo '<span class="badge-status badge-rejected">'.$status.'</span>';

}
?>

</td>

<td>
<?php if(!empty($row['rank'])){ ?>
<span class="badge-rank"><?= $row['rank'] ?></span>
<?php } else { echo "-"; } ?>
</td>

<td>
<?php if(!empty($row['prize'])){ ?>
<span class="badge-prize">
<?= $row['prize'] ?>

<?php if(!empty($row['prize_amount'])){ ?>
<br>
<small>৳ <?= number_format($row['prize_amount']) ?></small>
<?php } ?>

</span>
<?php } else { echo "-"; } ?>
</td>

<td>

<a href="view_innovation.php?id=<?= $row['id'] ?>" 
class="btn-action btn-view text-decoration-none">

<i class="fas fa-eye"></i> View

</a>

<a href="edit_innovation.php?id=<?= $row['id'] ?>" 
class="btn-action btn-edit text-decoration-none">

<i class="fas fa-edit"></i> Edit

</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="10">

<div class="empty-state">

<i class="fas fa-clipboard"></i>

<h4>No Innovations Found</h4>

<p>
There are no innovation submissions for the active fiscal year:
<strong><?= htmlspecialchars($fiscal_year) ?></strong>
</p>
</div>
</td>
</tr>

<?php endif; ?>
</tbody>
</table>
</div>
</div>
<!-- FINAL SELECT BUTTON -->

<?php if(mysqli_num_rows($result) > 0): ?>
<div class="mt-4">
<button type="submit" class="btn btn-success">
<i class="fas fa-check-circle me-2"></i>
Final Select Ideas

</button>
</div>
<?php endif; ?>

</form>
<!-- FORM END -->
</div>
<!-- Footer -->
<div class="footer-note">

<p>
Design & Developed by 
<a href="#">Md. Tareq Emran</a>,
Programmer, ICT Division, BCIC
</p>

</div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });

        // Add tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        document.getElementById("select_all").addEventListener("click", function(){

let checkboxes = document.querySelectorAll(".idea_checkbox");

checkboxes.forEach(function(cb){

cb.checked = document.getElementById("select_all").checked;

});

});
    </script>
</body>
</html>