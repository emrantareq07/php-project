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

// Get the POST ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = intval($_POST['id']);
$table = 'ansar_tbl';

// Fetch the record by ID only
$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();

    // Only process male/female arrays
    $maleArr = isset($record['male']) ? explode(',', $record['male']) : [];
    $femaleArr = isset($record['female']) ? explode(',', $record['female']) : [];

    // Compute total for each row
    $totalArr = [];
    $len = max(count($maleArr), count($femaleArr));
    for ($i = 0; $i < $len; $i++) {
        $m = intval($maleArr[$i] ?? 0);
        $f = intval($femaleArr[$i] ?? 0);
        $totalArr[] = $m + $f;
    }

    if (!empty($totalArr)) {
        $record['total'] = implode(',', $totalArr);
    }

    // Only include designation/grade if they have value
    if (isset($record['designation']) && trim($record['designation']) !== '') {
        $record['designation'] = $record['designation'];
    }
    if (isset($record['grade']) && trim($record['grade']) !== '') {
        $record['grade'] = $record['grade'];
    }

    echo json_encode([
        'success' => true,
        'data'    => $record
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Record not found']);
}

$stmt->close();
$conn->close();
?>
