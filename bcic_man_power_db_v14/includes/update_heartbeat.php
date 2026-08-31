<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Update last activity and ensure login_status is online
    $update_sql = "UPDATE users SET login_status = 1, updated_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();
    
    // Return current time for heartbeat
    echo json_encode(['success' => true, 'time' => date('Y-m-d H:i:s')]);
} else {
    echo json_encode(['success' => false]);
}
?>