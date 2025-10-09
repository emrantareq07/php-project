<?php
require '../db/db.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode(['error' => 'Employee ID is required']);
    exit;
}

$sql = "SELECT * FROM emp_tbl WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if ($employee) {
    echo json_encode($employee);
} else {
    echo json_encode(['error' => 'Employee not found']);
}

$stmt->close();
$conn->close();
?>