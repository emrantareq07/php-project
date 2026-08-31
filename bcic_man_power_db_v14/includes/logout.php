<?php
// session_name('man_power_db');
// session_start();
// date_default_timezone_set("Asia/Dhaka");
// include('../db/db.php');

// if (isset($_SESSION['username'])) {
//     $username = $_SESSION['username'];
//     $ip = $_SERVER['REMOTE_ADDR'];
//     $user_agent = $_SERVER['HTTP_USER_AGENT'];
//     $status = 'success';
//     $event_type = 'logout';
//     $logout_time = date('Y-m-d H:i:s');

//     $stmt = $conn->prepare("INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
//                             VALUES (?, ?, ?, ?, ?, ?)");
//     $stmt->bind_param("ssssss", $username, $event_type, $ip, $user_agent, $status, $logout_time);
//     $stmt->execute();
//     $stmt->close();
// }

// // Destroy session and redirect
// session_destroy();
// header("Location: ../index.php");
// exit;
?>
<?php
session_name('man_power_db');
session_start();
date_default_timezone_set("Asia/Dhaka");
include('../db/db.php');

// Get user info before destroying session
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // ***** UPDATE LOGIN STATUS TO OFFLINE (0) *****
    $update_sql = "UPDATE users SET login_status = 0 WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();
}
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $status = 'success';
    $event_type = 'logout';
    $logout_time = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $event_type, $ip, $user_agent, $status, $logout_time);
    $stmt->execute();
    $stmt->close();
}

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit;
?>