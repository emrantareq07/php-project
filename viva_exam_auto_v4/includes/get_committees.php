<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Fetch all committees
$query = "SELECT id, committe_name as name, designation FROM exam_schedule_tbl ORDER BY designation, committe_name";
$result = mysqli_query($conn, $query);

$committees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $committees[] = [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'designation' => $row['designation']
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'committees' => $committees,
    'total' => count($committees)
]);
?>