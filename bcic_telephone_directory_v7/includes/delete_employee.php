<?php
session_start();
require '../db/db.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    die('Employee ID is required');
}

// Get image path before deletion
$selectSql = "SELECT image FROM emp_tbl WHERE id = ?";
$selectStmt = $conn->prepare($selectSql);
$selectStmt->bind_param("i", $id);
$selectStmt->execute();
$result = $selectStmt->get_result();
$row = $result->fetch_assoc();
$image_path = $row['image'] ?? '';
$selectStmt->close();

// Delete employee record
$deleteSql = "DELETE FROM emp_tbl WHERE id = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("i", $id);

if ($deleteStmt->execute()) {
    // Delete associated image file
    if ($image_path && file_exists('../' . $image_path)) {
        unlink('../' . $image_path);
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    echo "Error deleting employee: " . $deleteStmt->error;
}

$deleteStmt->close();
$conn->close();
?>