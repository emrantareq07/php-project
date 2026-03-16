<?php
session_name('factory_work_request_db');
session_start();
include "db.php";

$emp_id=$_SESSION['emp_id'];
$month=$_POST['month'];
$year=$_POST['year'];

$sql="SELECT * FROM fc_tbl
WHERE emp_id='$emp_id'
AND MONTH(work_date)='$month'
AND YEAR(work_date)='$year'
ORDER BY work_date";

$result=mysqli_query($conn,$sql);

$data=[];

while($row=mysqli_fetch_assoc($result)){
$data[]=$row;
}

echo json_encode($data);