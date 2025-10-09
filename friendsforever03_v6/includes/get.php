<?php
session_start();
require '../db/db.php';

// Enable error reporting for debugging (remove in production)
error_reporting(0);

// Validate ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM friends WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

$friend = $result->fetch_assoc();

// Optional: convert image path to full URL
if (!empty($friend['image'])) {
    $friend['image'] = $friend['image']; // e.g., "uploads/profile_123.jpg"
}

echo json_encode($friend);
?>
