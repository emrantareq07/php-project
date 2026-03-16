<?php
session_name('factory_work_request_db');
require_once '../db/config.php';

// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Get user data from session
$user_id = $_SESSION['user_id'];
$emp_id = $_SESSION['emp_id'];
$full_name = $_SESSION['full_name'];
$role = $_SESSION['role'];
$emp_type = $_SESSION['emp_type'];


$month = date("n");
$year  = date("Y");

/* TOTAL EMPLOYEES */
$total_emp_q = mysqli_query($conn,"SELECT COUNT(*) as total FROM users");
$total_emp = mysqli_fetch_assoc($total_emp_q)['total'];

/* SUBMITTED FC */
$submitted_q = mysqli_query($conn,"SELECT COUNT(*) as total 
FROM fc_tbl 
WHERE month='$month' AND YEAR(current_date)='$year'");

$submitted = mysqli_fetch_assoc($submitted_q)['total'];

/* PENDING FC */
$pending = $total_emp - $submitted;

/* TOTAL HOURS */
$hours_q = mysqli_query($conn,"SELECT total_hours FROM fc_tbl 
WHERE month='$month' AND YEAR(current_date)='$year'");

$total_hours = 0;

while($row=mysqli_fetch_assoc($hours_q)){

$hours = explode(",",$row['total_hours']);
$total_hours += array_sum($hours);

}

/* TOTAL DAYS */

$days_q = mysqli_query($conn,"SELECT date FROM fc_tbl 
WHERE month='$month' AND YEAR(current_date)='$year'");

$total_days=0;

while($row=mysqli_fetch_assoc($days_q)){

$d = explode(",",$row['date']);
$total_days += count($d);

}
?>
<!DOCTYPE html>
<html>
<head>

<title>FC Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="container mt-4">

<h3 class="mb-4">Monthly FC Dashboard</h3>
  <a href="my_fc_sheet.php" class=""><i class="fas fa-key"></i> My FC
                            
                        </a>
                        <br>
 <a href="fc_monthly_report.php" class=""><i class="fas fa-key"></i>  FC Monthly Report
                            
                        </a>                       
<div class="row">

<div class="col-md-3">
<div class="card bg-primary text-white">
<div class="card-body">
<h5>Total Employees</h5>
<h3><?php echo $total_emp; ?></h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-success text-white">
<div class="card-body">
<h5>Submitted FC</h5>
<h3><?php echo $submitted; ?></h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-danger text-white">
<div class="card-body">
<h5>Pending FC</h5>
<h3><?php echo $pending; ?></h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card bg-warning text-dark">
<div class="card-body">
<h5>Total FC Hours</h5>
<h3><?php echo $total_hours; ?></h3>
</div>
</div>
</div>

</div>

<div class="row mt-4">

<div class="col-md-6">

<div class="card">
<div class="card-header">Total FC Days</div>
<div class="card-body">
<h2><?php echo $total_days; ?></h2>
</div>
</div>

</div>

<div class="col-md-6">

<canvas id="divisionChart"></canvas>

</div>

</div>

<?php

$div_q = mysqli_query($conn,"SELECT division, COUNT(*) as total 
FROM fc_tbl 
WHERE month='$month' AND YEAR(current_date)='$year'
GROUP BY division");

$divisions=[];
$counts=[];

while($r=mysqli_fetch_assoc($div_q)){

$divisions[]=$r['division'];
$counts[]=$r['total'];

}

?>

<script>

const ctx = document.getElementById('divisionChart');

new Chart(ctx, {
type: 'bar',
data: {

labels: <?php echo json_encode($divisions); ?>,

datasets: [{

label: 'FC Submitted',

data: <?php echo json_encode($counts); ?>,

borderWidth: 1

}]

},

options: {

scales: {
y: {
beginAtZero: true
}
}

}

});

</script>