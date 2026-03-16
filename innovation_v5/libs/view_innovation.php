<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

echo $role=$_SESSION['role'];

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: innovation_list.php");
    exit();
}

$id = intval($_GET['id']);

$query = "SELECT * FROM tbl_innovation WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Record not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>View Innovation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to right, #eef2f3, #d7e1ec);
}

.card-custom {
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.section-title {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
    color: white;
    padding: 10px 15px;
    border-radius: 10px;
    font-weight: 600;
}

.label-title {
    font-weight: 600;
    color: #0d6efd;
}

.badge-custom {
    font-size: 0.8rem;
}
</style>

</head>

<body class="p-4">

<div class="container">
<div class="card card-custom p-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="section-title">Innovation Idea Details</h4>
    <div>
        <?php 
        if ($_SESSION['role'] === 'admin') {
        ?>
            <a href="all_innovations.php" class="btn btn-secondary btn-sm">Back</a>
            <button onclick="window.print()" class="btn btn-success btn-sm">Print</button>
        <?php 
        } else {
        ?>
            <a href="submitted_innovation_ideas.php" class="btn btn-secondary btn-sm">Back</a>
            <button onclick="window.print()" class="btn btn-success btn-sm">Print</button>
        <?php 
        }
        ?>       
    </div>
</div>

<div class="row g-4">

<!-- Personal Info -->
<div class="col-md-6">
<div class="card p-3 h-100 border-primary">
<h6 class="text-primary mb-3">Personal Information</h6>

<p><span class="label-title">Name:</span> <?= htmlspecialchars($data['fullname']) ?></p>
<p><span class="label-title">Employee ID:</span> <?= htmlspecialchars($data['emp_id']) ?></p>
<p><span class="label-title">Designation:</span> <?= htmlspecialchars($data['designation']) ?></p>
<p><span class="label-title">Email:</span> <?= htmlspecialchars($data['email']) ?></p>
<p><span class="label-title">Mobile:</span> <?= htmlspecialchars($data['mobile_no']) ?></p>
<p><span class="label-title">Place of Posting:</span> <?= htmlspecialchars($data['place_of_posting']) ?></p>

</div>
</div>

<!-- Idea Summary -->
<div class="col-md-6">
<div class="card p-3 h-100 border-success">
<h6 class="text-success mb-3">Idea Summary</h6>

<p><span class="label-title">Title:</span> <?= htmlspecialchars($data['title_of_idea']) ?></p>
<p><span class="label-title">Implementation Date:</span> <?= $data['idea_imp_date'] ?></p>

<p>
<span class="label-title">Status:</span> 
<?php if($data['status']=='submitted idea'): ?>
<span class="badge bg-success badge-custom">Submitted Idea</span>
<?php elseif($data['status']=='primarily selected'): ?>
<span class="badge bg-warning text-dark badge-custom">Primarily Selected</span>
<?php else: ?>
<span class="badge bg-danger badge-custom">Final Selected</span>
<?php endif; ?>
</p>

<p>
<span class="label-title">Implementation Status:</span>
<span class="badge bg-info text-dark badge-custom">
<?= htmlspecialchars($data['imple_status']) ?>
</span>
</p>

<p>
<span class="label-title">Rank:</span>
<?php if($data['rank']): ?>
<span class="badge bg-primary"><?= $data['rank'] ?></span>
<?php else: ?> - <?php endif; ?>
</p>

<p>
<span class="label-title">Prize:</span>
<?php if($data['prize']): ?>
<span class="badge bg-danger"><?= htmlspecialchars($data['prize']) ?></span>
<?php else: ?> - <?php endif; ?>
</p>

</div>
</div>

<!-- Problem & Solution -->
<div class="col-12">
<div class="card p-3 border-info">
<h6 class="text-info mb-3">Problem & Solution</h6>

<p><span class="label-title">Problem Description:</span><br>
<?= nl2br(htmlspecialchars($data['identify_prob_desc'])) ?></p>

<p><span class="label-title">Solution Plan:</span><br>
<?= nl2br(htmlspecialchars($data['prob_sol_plan'])) ?></p>

<p><span class="label-title">Solution Description:</span><br>
<?= nl2br(htmlspecialchars($data['prob_sol_desc'])) ?></p>

</div>
</div>

<!-- Financial & Impact -->
<div class="col-12">
<div class="card p-3 border-warning">
<h6 class="text-warning mb-3">Financial & Impact Analysis</h6>

<div class="row">

<div class="col-md-4">
<p><span class="label-title">Cost:</span><br>
<span class="badge bg-info text-dark">৳ <?= number_format($data['cost']) ?></span>
</p>
</div>

<div class="col-md-4">
<p><span class="label-title">Time Saving:</span><br>
<?= htmlspecialchars($data['time_saving']) ?>
</p>
</div>

<div class="col-md-4">
<p><span class="label-title">Profitability:</span><br>
<?= htmlspecialchars($data['profitability']) ?>
</p>
</div>

<div class="col-md-6">
<p><span class="label-title">Cost Effectiveness:</span><br>
<?= htmlspecialchars($data['cost_effectiveness']) ?>
</p>
</div>

<div class="col-md-6">
<p><span class="label-title">Value Addition:</span><br>
<?= htmlspecialchars($data['value_add']) ?>
</p>
</div>

</div>
</div>
</div>

<!-- Remarks -->
<div class="col-12">
<div class="card p-3 border-secondary">
<h6 class="text-secondary mb-3">Remarks</h6>
<p><?= nl2br(htmlspecialchars($data['remarks'])) ?></p>
</div>
</div>

<!-- Attachments -->
<div class="col-12">
<div class="card p-3 border-dark">
<h6 class="text-dark mb-3">Attachments</h6>

<div class="row">

<?php
function displayBlobFile($blobData, $title){

    if(empty($blobData)){
        echo "<div class='col-md-6 mb-3'>
                <div class='alert alert-warning'>No $title Uploaded</div>
              </div>";
        return;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($blobData);
    $base64 = base64_encode($blobData);

    echo "<div class='col-md-6 mb-4'>
            <div class='card shadow-sm p-3'>
            <h6 class='text-primary'>$title</h6>";

    // Image Preview
    if(strpos($mimeType, 'image') !== false){
        echo "<img src='data:$mimeType;base64,$base64' 
                class='img-fluid rounded border mb-2' 
                style='max-height:300px; object-fit:contain;'>";
    }

    // PDF Preview
    elseif($mimeType == 'application/pdf'){
        echo "<iframe src='data:$mimeType;base64,$base64' 
                width='100%' height='300px' 
                class='border rounded mb-2'></iframe>";
    }

    // Other Files
    else{
        echo "<div class='alert alert-info'>File uploaded (Preview not supported)</div>";
    }

    // Download Button
    echo "<a href='data:$mimeType;base64,$base64' 
            download='$title' 
            class='btn btn-sm btn-success'>
            Download
          </a>";

    echo "</div></div>";
}

displayBlobFile($data['image_befor_after_inno'], 'Before / After');
displayBlobFile($data['flowchart'], 'Flowchart');
?>

</div>
</div>
</div>

</body>
</html>