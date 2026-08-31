<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$table = 'staffs_tbl';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = intval($_POST['id']);

$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Record not found']);
    $stmt->close();
    exit;
}

$row = $result->fetch_assoc();
$stmt->close();

/*
VERY IMPORTANT:
fields MUST match exactly what JS expects:
    designation
    grade
    sanctioned_post
    male
    female
    total
*/

$data = [
    "date"            => $row['date'],
    "factory_name"    => $row['factory_name'],

    "designation"     => $row['designation'],
    "grade"           => $row['grade'],
    "sanctioned_post" => $row['sanctioned_post'],
    "male"            => $row['male'],
    "female"          => $row['female'],
    "total"           => $row['total']
];

echo json_encode([
    "success" => true,
    "data"    => $data
]);

$conn->close();
?>
