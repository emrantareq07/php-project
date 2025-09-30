<?php 
session_name('dfms');
session_start();
include('../db/db.php');
// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Make sure the user is logged in before logging out
if (isset($_SESSION['username'])) {
    $username   = $_SESSION['username'];
    $user_type  = $_SESSION['user_type'];
    $logout_time = date('Y-m-d H:i:s');

    // Update only the latest log entry of this user (assuming log_table has an id or session column)
    $log_query = "
        UPDATE log_table 
        SET logout_date_time = '$logout_time' 
        WHERE username = '$username' 
        ORDER BY id DESC 
        LIMIT 1
    ";
    mysqli_query($conn, $log_query);
}

// Destroy session safely
session_unset();
session_destroy();

// Redirect to login page
header("Location: dashboard.php");
exit();
?>
