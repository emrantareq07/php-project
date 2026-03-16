<?php
session_name('factory_work_request_db');
session_start();
require_once 'db.php';

date_default_timezone_set('Asia/Dhaka');
// Get user data from session
$user_id = $_SESSION['user_id'];
$emp_id = $_SESSION['emp_id'];
$name = $_SESSION['full_name'];
$role = $_SESSION['role'];
$emp_type = $_SESSION['emp_type'];

$designation = $_SESSION['designation'] ?? '';
$division    = $_SESSION['division'] ?? '';
$section     = $_SESSION['section'] ?? '';

$current_date = date("Y-m-d");

/* FORM ARRAYS */
$dates       = $_POST['date'] ?? [];
$time_from   = $_POST['time_from'] ?? [];
$time_to     = $_POST['time_to'] ?? [];
$total_hours = $_POST['total_hours'] ?? [];
$remarks     = $_POST['remarks'] ?? [];

/* GET MONTH FROM FIRST DATE */
if (!empty($dates)) {
    $month = date("m", strtotime($dates[0]));
} else {
    echo "No dates provided!";
    exit;
}

/* FORMAT DATA */
$date_arr    = [];
$from_arr    = [];
$to_arr      = [];
$hour_arr    = [];
$remarks_arr = [];

foreach ($dates as $k => $d) {

    $date_arr[]    = date("d-m-Y", strtotime($d));
    $from_arr[]    = date("h:i A", strtotime($time_from[$k] ?? '00:00'));
    $to_arr[]      = date("h:i A", strtotime($time_to[$k] ?? '00:00'));
    $hour_arr[]    = $total_hours[$k] ?? '0';
    $remarks_arr[] = $remarks[$k] ?? '';
}

/* CONVERT ARRAY TO STRING */
$date_str    = implode(",", $date_arr);
$from_str    = implode(",", $from_arr);
$to_str      = implode(",", $to_arr);
$hour_str    = implode(",", $hour_arr);
$remarks_str = implode(",", $remarks_arr);

/* PREVENT DUPLICATE MONTH */
$emp_id_safe = mysqli_real_escape_string($conn, $emp_id);
$month_safe  = mysqli_real_escape_string($conn, $month);

$check = mysqli_query($conn, "
    SELECT id FROM fc_tbl 
    WHERE emp_id='$emp_id_safe' 
    AND `month`='$month_safe' 
    AND YEAR(current_date)=YEAR(CURDATE())
");

if (mysqli_num_rows($check) > 0) {
    echo "FC already submitted for this month!";
    exit;
}

/* INSERT */
$sql = "INSERT INTO fc_tbl
(`emp_id`, `name`, `designation`, `division`, `section`, `current_date`, `month`, `date`, `time_from`, `time_to`, `total_hours`, `remarks`, `created_at`)
VALUES
(
'$emp_id_safe',
'" . mysqli_real_escape_string($conn, $name) . "',
'" . mysqli_real_escape_string($conn, $designation) . "',
'" . mysqli_real_escape_string($conn, $division) . "',
'" . mysqli_real_escape_string($conn, $section) . "',
'$current_date',
'$month_safe',
'" . mysqli_real_escape_string($conn, $date_str) . "',
'" . mysqli_real_escape_string($conn, $from_str) . "',
'" . mysqli_real_escape_string($conn, $to_str) . "',
'" . mysqli_real_escape_string($conn, $hour_str) . "',
'" . mysqli_real_escape_string($conn, $remarks_str) . "',
NOW()
)";

if (mysqli_query($conn, $sql)) {
    echo "FC Sheet Saved Successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>