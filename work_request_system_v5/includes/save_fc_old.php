<?php
session_name('factory_work_request_db');
require_once '../db/config.php';
// include "db.php";
// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}


$emp_id=$_SESSION['emp_id'];
$full_name=$_SESSION['full_name'];
$designation=$_SESSION['designation'];
$division=$_SESSION['division'];
$section=$_SESSION['section'];

$date=$_POST['date'];
$time_from=$_POST['time_from'];
$time_to=$_POST['time_to'];
$total_hours=$_POST['total_hours'];
$remarks=$_POST['remarks'];

foreach($date as $key=>$d){

$from=$time_from[$key];
$to=$time_to[$key];
$hours=$total_hours[$key];
$rmk=$remarks[$key];

$sql="INSERT INTO fc_tbl
(emp_id,full_name,designation,division,section,work_date,time_from,time_to,total_hours,remarks)
VALUES
('$emp_id','$full_name','$designation','$division','$section','$d','$from','$to','$hours','$rmk')
ON DUPLICATE KEY UPDATE
time_from='$from',
time_to='$to',
total_hours='$hours',
remarks='$rmk'";

mysqli_query($conn,$sql);

}

echo "FC Saved Successfully";