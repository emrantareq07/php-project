<?php
// incoming_work_request.php - SIMPLE VERSION
session_name('factory_work_request_db');
session_start();
require_once '../db/config.php';


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Get user info from session
$user_id = $_SESSION['user_id'];
$user_division = $_SESSION['division'] ?? '';
$user_section = $_SESSION['section'] ?? '';
$user_full_name = $_SESSION['full_name'] ?? '';
$user_role = $_SESSION['role'] ?? 'user';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Update the completion status
    $stmt = $conn->prepare("UPDATE work_request_tbl SET w_com_status = 'complete' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Success - redirect back
        header("Location: incoming_work_request.php?msg=completed");
    } else {
        // Error - redirect back with error
        header("Location: incoming_work_request.php?error=update_failed");
    }
    exit;
} else {
    // No ID provided - redirect back
    header("Location: incoming_work_request.php?error=no_id");
    exit;
}
?>