<?php
session_name('transport_db');
session_start();
date_default_timezone_set("Asia/Dhaka");

include('../db/db_connection.php'); // PDO connection

if (isset($_SESSION['username'])) {

    $username    = $_SESSION['username'];
    $ip          = $_SERVER['REMOTE_ADDR'];
    $user_agent  = $_SERVER['HTTP_USER_AGENT'];
    $status      = 'success';
    $event_type  = 'logout';
    $logout_time = date('Y-m-d H:i:s');

    // PDO insert query
    $stmt = $conn->prepare("
        INSERT INTO log_table (username, event_type, ip_address, user_agent, status, login_time)
        VALUES (:username, :event_type, :ip, :user_agent, :status, :logout_time)
    ");

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':event_type', $event_type);
    $stmt->bindParam(':ip', $ip);
    $stmt->bindParam(':user_agent', $user_agent);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':logout_time', $logout_time);

    // Execute
    $stmt->execute();
}

// Destroy session and redirect
session_destroy();
header("Location: ../index.php");
exit;
?>
