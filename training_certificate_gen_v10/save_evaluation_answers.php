<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$user_id = (int)$_POST['user_id'];
$batch = (int)$_POST['batch'];
$remarks = $_POST['remarks'] ?? '';

// Verify user belongs to this batch and is the logged-in user
$stmt = $conn->prepare("SELECT id, end_date FROM users_tbl WHERE id = ? AND batch = ?");
$stmt->bind_param("ii", $user_id, $batch);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Verify this is the logged-in user
if ($user_id != $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if evaluation period is still active
$today = date('Y-m-d');
if ($today > $user['end_date']) {
    echo json_encode(['success' => false, 'message' => 'Evaluation period has ended on ' . date('d M Y', strtotime($user['end_date']))]);
    exit;
}

// Check if batch evaluation is active
$stmt = $conn->prepare("SELECT evaluation_status FROM evaluation_set WHERE batch = ? LIMIT 1");
$stmt->bind_param("i", $batch);
$stmt->execute();
$status = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($status && $status['evaluation_status'] != 'active') {
    echo json_encode(['success' => false, 'message' => 'Evaluation is currently inactive']);
    exit;
}

// Update remarks in users_tbl
$stmt = $conn->prepare("UPDATE users_tbl SET remarks = ? WHERE id = ?");
$stmt->bind_param("si", $remarks, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Evaluation saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>