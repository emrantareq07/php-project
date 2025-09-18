<?php
// delete.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$token = $_POST['csrf_token'] ?? '';

if (empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
    header('Location: list.php?err=' . urlencode('Invalid CSRF token.'));
    exit;
}

// delete
$stmt = $conn->prepare("DELETE FROM photos WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header('Location: list.php?msg=' . urlencode('Photo deleted.'));
    exit;
} else {
    header('Location: list.php?err=' . urlencode('Delete failed.'));
    exit;
}
