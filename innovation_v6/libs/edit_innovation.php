<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: innovation_list.php");
    exit();
}

$id = intval($_GET['id']);

$query = "SELECT * FROM tbl_innovation WHERE id=$id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Record not found");
}

/* ===============================
   UPDATE ONLY LIMITED FIELDS
=================================*/
$success = '';
if (isset($_POST['update'])) {

    foreach ($_POST as $key => $value) {
        $_POST[$key] = mysqli_real_escape_string($conn, $value);
    }

    $update = "
        UPDATE tbl_innovation SET
        imple_status='{$_POST['imple_status']}',
        replicate_eligibility='{$_POST['replicate_eligibility']}',
        remarks='{$_POST['remarks']}',
        prize='{$_POST['prize']}',
        prize_amount='{$_POST['prize_amount']}',
        rank='{$_POST['rank']}',
        status='{$_POST['status']}',
        updated_at=NOW()
        WHERE id=$id
    ";

    if (mysqli_query($conn, $update)) {
        $success = "Record Updated Successfully!";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Innovation</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(to right,#f1f4f9,#dff1ff);
}
.card-custom{
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.1);
}
.section-title{
    background: linear-gradient(45deg,#0d6efd,#6610f2);
    color:white;
    padding:10px 15px;
    border-radius:10px;
}
</style>

</head>
<body class="p-4">

<div class="container">
<div class="card card-custom p-4">

<h4 class="section-title mb-4">Edit Innovation (Limited Fields)</h4>

<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">

<div class="row g-3">

<!-- READONLY FIELDS -->
<div class="col-md-6">
<label>Full Name</label>
<input type="text" class="form-control" value="<?= htmlspecialchars($data['fullname']) ?>" readonly>
</div>

<div class="col-md-6">
<label>Designation</label>
<input type="text" class="form-control" value="<?= htmlspecialchars($data['designation']) ?>" readonly>
</div>

<div class="col-12">
<label>Idea Title</label>
<input type="text" class="form-control" value="<?= htmlspecialchars($data['title_of_idea']) ?>" readonly>
</div>

<div class="col-md-4">
<label>Cost</label>
<input type="text" class="form-control" value="<?= number_format($data['cost']) ?>" readonly>
</div>

<hr class="my-4">

<!-- EDITABLE FIELDS -->

<div class="col-md-4">
<label>Implementation Status</label>
<select name="imple_status" class="form-select" required>
<option value="বাস্তবায়িত" <?= $data['imple_status']=='বাস্তবায়িত'?'selected':'' ?>>বাস্তবায়িত</option>
<option value="চলমান" <?= $data['imple_status']=='চলমান'?'selected':'' ?>>চলমান</option>
</select>
</div>

<div class="col-md-4">
<label>Replicate Eligibility</label>
<select name="replicate_eligibility" class="form-select">
<option value="yes" <?= $data['replicate_eligibility']=='yes'?'selected':'' ?>>Yes</option>
<option value="no" <?= $data['replicate_eligibility']=='no'?'selected':'' ?>>No</option>
</select>
</div>

<div class="col-md-4">
<label>Prize</label>
<select name="prize" class="form-select">
<option value="">Select</option>
<option value="yes" <?= $data['prize']=='yes'?'selected':'' ?>>Yes</option>
<option value="no" <?= $data['prize']=='no'?'selected':'' ?>>No</option>
</select>
</div>

<div class="col-md-4">
<label>Prize Amount</label>
<input type="number" name="prize_amount" class="form-control" value="<?= htmlspecialchars($data['prize_amount']) ?>">
</div>

<div class="col-md-4">
<label>Rank</label>
<select name="rank" class="form-select">
<option value="">Select Rank</option>
<?php 
$ranks = ['1st','2nd','3rd','4th','5th'];
foreach($ranks as $r){
    $sel = ($data['rank']==$r)?'selected':'';
    echo "<option value='$r' $sel>$r</option>";
}
?>
</select>
</div>

<div class="col-md-4">
<label>Status</label>
<select name="status" class="form-select">
<option value="submitted idea" <?= $data['status']=='submitted idea'?'selected':'' ?>>Submitted Idea</option>
<option value="primarily selected" <?= $data['status']=='primarily selected'?'selected':'' ?>>Primarily Selected</option>
<option value="final selected" <?= $data['status']=='final selected'?'selected':'' ?>>Final Selected</option>
</select>
</div>

<div class="col-12">
<label>Remarks</label>
<textarea name="remarks" class="form-control" rows="3"><?= htmlspecialchars($data['remarks']) ?></textarea>
</div>

<div class="col-12 text-end mt-3">
<a href="submitted_innovation_ideas.php" class="btn btn-secondary">Back</a>
<button type="submit" name="update" class="btn btn-success">Update</button>
</div>

</div>
</form>

</div>
</div>

</body>
</html>