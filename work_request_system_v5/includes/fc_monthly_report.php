<?php
session_name('factory_work_request_db');
session_start();
require_once 'db.php';

$month = $_GET['month'] ?? '';
$year  = $_GET['year'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>

<title>Monthly FC Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>

<body class="container mt-4">

<h4 class="mb-3">Monthly FC Report</h4>

<form method="GET">

<div class="row mb-3">

<div class="col-md-3">
<select name="month" class="form-control">

<option value="">Select Month</option>

<?php
for($m=1;$m<=12;$m++){
$sel = ($month==$m)?'selected':'';
echo "<option value='$m' $sel>".date("F",mktime(0,0,0,$m,1))."</option>";
}
?>

</select>
</div>

<div class="col-md-3">
<!-- <input type="number" name="year" value="<?= $year ?>" class="form-control" placeholder="Year"> -->
<select name="year" class="form-control">

<option value="">Select Year</option>

<?php

$year_query = mysqli_query($conn,"SELECT DISTINCT YEAR(curent_date) AS yr 
FROM fc_tbl 
ORDER BY yr DESC");

while($yr = mysqli_fetch_assoc($year_query)){

$selected = ($year == $yr['yr']) ? 'selected' : '';

echo "<option value='".$yr['yr']."' $selected>".$yr['yr']."</option>";

}

?>

</select>
</div>

<div class="col-md-3">
<button class="btn btn-primary">Generate</button>
<button type="button" onclick="window.print()" class="btn btn-success">Print</button>
</div>

</div>

</form>
<?php

if($month && $year){

$query="SELECT * FROM fc_tbl 
WHERE MONTH(curent_date)='$month' 
AND YEAR(curent_date)='$year'
ORDER BY name";

$result=mysqli_query($conn,$query);

?>

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>SL</th>
<th>EMP ID</th>
<th>Name</th>
<th>Designation</th>
<th>Division</th>
<th>Total Days</th>
<th>Total Hours</th>
</tr>

</thead>

<tbody>

<?php

$sl=1;

while($row=mysqli_fetch_assoc($result)){

$days = explode(",",$row['date']);
$total_days = count($days);

$hours = explode(",",$row['total_hours']);
$total_hours = array_sum($hours);

?>

<tr>

<td><?= $sl++ ?></td>
<td><?= $row['emp_id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['designation'] ?></td>
<td><?= $row['division'] ?></td>
<td><?= $total_days ?></td>
<td><?= $total_hours ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

<script>
$(document).ready(function(){
$('table').DataTable();
});
</script>