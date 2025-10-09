<?php
session_start();
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

$id = $_POST['id'] ?? '';
$status = $_POST['status'] ?? '';

if (empty($id) || empty($status)) {
    die('Missing required parameters');
}

$sql = "UPDATE emp_tbl SET status = ?, updated_at = NOW() WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo "Employee status updated to " . ($status === 'active' ? 'active' : 'inactive');
} else {
    echo "Error updating status: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>