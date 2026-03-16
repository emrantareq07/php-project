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
$sql = "SELECT * FROM tbl_innovation WHERE fiscal_year='$fiscal_year' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
<title>Innovation List</title>

<!-- Latest Bootstrap 5.3.2 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background: linear-gradient(to right, #e3f2fd, #ffffff);
    }

    .card-custom {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .table thead {
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f1f8ff;
        transition: 0.3s;
    }

    .badge-status {
        font-size: 0.75rem;
    }

</style>

</head>
<body class="p-4">

<div class="container-fluid">

<div class="card card-custom p-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-primary text-center">
       Submitted Innovation Idea List - Fiscal Year Wise: 
        <span class="badge bg-success"><?= htmlspecialchars($fiscal_year) ?></span>
    </h4>
     <a href="../dashboard.php" class="btn-back btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
</div>

<div class="table-responsive">
<table class="table table-bordered table-hover align-middle text-center">

<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>Designation</th>
<th>Idea Title</th>
<th>Cost</th>
<th>Status</th>
<th>Rank</th>
<th>Prize</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($result) > 0): ?>
<?php $i=1; while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $i++ ?></td>

<td class="fw-semibold text-primary">
    <?= htmlspecialchars($row['fullname']) ?><br>
    <small class="text-muted"><?= htmlspecialchars($row['emp_id']) ?></small>
</td>

<td><?= htmlspecialchars($row['designation']) ?></td>

<td class="text-start">
    <?= htmlspecialchars($row['title_of_idea']) ?>
</td>

<td>
    <span class="badge bg-info text-dark">
        ৳ <?= number_format($row['cost']) ?>
    </span>
</td>

<td>
<?php if($row['status'] == 'Approved'): ?>
    <span class="badge bg-success badge-status">Approved</span>
<?php elseif($row['status'] == 'Pending'): ?>
    <span class="badge bg-warning text-dark badge-status">Pending</span>
<?php else: ?>
    <span class="badge bg-secondary badge-status">
        <?= htmlspecialchars($row['status']) ?>
    </span>
<?php endif; ?>
</td>

<td>
<?php if($row['rank']): ?>
    <span class="badge bg-primary"><?= $row['rank'] ?></span>
<?php else: ?>
    -
<?php endif; ?>
</td>

<td>
<?php if($row['prize']): ?>
    <span class="badge bg-danger"><?= htmlspecialchars($row['prize']) ?></span>
<?php else: ?>
    -
<?php endif; ?>
</td>

<td>
    <a href="view_innovation.php?id=<?= $row['id'] ?>" 
       class="btn btn-sm btn-outline-info">
       View
    </a>

    <a href="edit_innovation.php?id=<?= $row['id'] ?>" 
       class="btn btn-sm btn-outline-warning">
       Edit
    </a>
</td>

</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="9" class="text-danger fw-bold">
No Innovation Found for Active Fiscal Year
</td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>
</div>

</body>
</html>