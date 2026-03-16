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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Work Request System</title>     
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

	</head>
<body>
<a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#fcModal">
<i class="fas fa-list-check"></i>
<span>My FC</span>
</a>
<a href="fc_monthly_report.php" class="nav-link" >
<i class="fas fa-list-check"></i>
<span>Monthly Report</span>
</a>
<div class="modal fade" id="fcModal">
<div class="modal-dialog modal-xl">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">My FC Sheet</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="row mb-3">

<div class="col-md-3">
<label>Month</label>
<select id="month" name="month" class="form-control">
<option value="">Select Month</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>

<div class="col-md-3">
<label>Year</label>
<input type="number" id="year" class="form-control" value="2026">
</div>

<div class="col-md-3 mt-4">
<button class="btn btn-primary" onclick="generateTable()">Generate</button>
</div>

</div>

<form id="fcForm">

<input type="hidden" name="month" id="formMonth">
<input type="hidden" name="year" id="formYear">

<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>SL</th>
<th>Date</th>
<th>From Time</th>
<th>To Time</th>
<th>Total Hours</th>
<th>Remarks</th>
<th>Action</th>
</tr>
</thead>

<tbody id="fcTableBody"></tbody>

<tfoot>
<tr>
<td colspan="7" class="text-end">
<b>Total Days = <span id="totalDays">0</span></b>
</td>
</tr>
</tfoot>

</table>

<button type="submit" class="btn btn-success">Save</button>
<button onclick="window.print()" class="btn btn-primary">Print FC Sheet</button>

</form>

</div>
</div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">

function generateTable(){

let month=document.getElementById("month").value;
let year=document.getElementById("year").value;

if(month=="" || year==""){
alert("Select month & year");
return;
}

/* ADD THIS */
document.getElementById("formMonth").value = month;
document.getElementById("formYear").value = year;

let days=new Date(year,month,0).getDate();
let tbody=document.getElementById("fcTableBody");

tbody.innerHTML="";

for(let i=1;i<=days;i++){

let date=year+"-"+("0"+month).slice(-2)+"-"+("0"+i).slice(-2);

let row=`
<tr>

<td>${i}</td>

<td>
<input type="date" name="date[]" value="${date}" class="form-control" readonly>
</td>

<td>
<input type="time" name="time_from[]" value="14:00" class="form-control" onchange="calcHours(this)">
</td>

<td>
<input type="time" name="time_to[]" value="17:00" class="form-control" onchange="calcHours(this)">
</td>

<td>
<input type="text" name="total_hours[]" value="3" class="form-control total">
</td>

<td>
<input type="text" name="remarks[]" class="form-control">
</td>

<td>
<button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button>
</td>

</tr>
`;

tbody.innerHTML+=row;

}

document.getElementById("totalDays").innerText=days;

}

function calcHours(el){

let row=el.closest("tr");

let from=row.querySelector('[name="time_from[]"]').value;
let to=row.querySelector('[name="time_to[]"]').value;

if(from && to){

let start=new Date("2000-01-01T"+from);
let end=new Date("2000-01-01T"+to);

let diff=(end-start)/(1000*60*60);

row.querySelector(".total").value=diff.toFixed(2);

}

}

function deleteRow(btn){

btn.closest("tr").remove();

renumberRows();

}

function renumberRows(){

let rows=document.querySelectorAll("#fcTableBody tr");

rows.forEach((row,index)=>{
row.cells[0].innerText=index+1;
});

document.getElementById("totalDays").innerText=rows.length;

}

document.getElementById("fcForm").addEventListener("submit",function(e){

e.preventDefault();

let formData=new FormData(this);

fetch("save_fc.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(data=>{
alert(data);
location.reload();
});

});
</script>
</body>
</html>