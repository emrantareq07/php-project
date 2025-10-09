<?php
require '../db/db.php';

$department = $_POST['department'] ?? '';
$status = $_POST['status'] ?? '';
$system_status = $_POST['system_status'] ?? '';

$sql = "SELECT * FROM emp_tbl WHERE 1=1";
$types = "";
$params = [];

if (!empty($department)) {
    $sql .= " AND department = ?";
    $types .= "s";
    $params[] = $department;
}

if (!empty($status)) {
    $sql .= " AND status = ?";
    $types .= "s";
    $params[] = $status;
}
if (!empty($system_status)) {
    $sql .= " AND system_status = ?";
    $types .= "s";
    $params[] = $system_status;
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$employees = [];

while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

echo json_encode(['data' => $employees]);

$stmt->close();
$conn->close();
?>