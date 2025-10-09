<?php
session_start();
header('Content-Type: application/json');

include 'db.php'; // your database connection file

if (!isset($_POST['mobiles']) || !is_array($_POST['mobiles'])) {
    echo json_encode([]);
    exit;
}

$mobiles = $_POST['mobiles'];

// Prepare placeholders for the SQL IN clause
$placeholders = implode(',', array_fill(0, count($mobiles), '?'));

// Prepare statement
$sql = "SELECT mobile FROM friends WHERE mobile IN ($placeholders) AND status='approved'";
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$types = str_repeat('s', count($mobiles));
$stmt->bind_param($types, ...$mobiles);

$stmt->execute();
$result = $stmt->get_result();

$approved = [];
while($row = $result->fetch_assoc()) {
    $approved[] = $row['mobile'];
}

echo json_encode($approved);
