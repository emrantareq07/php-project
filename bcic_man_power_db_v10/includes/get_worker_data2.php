<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No record ID provided']);
    exit;
}

$id = $_POST['id'];
$username = $_SESSION['username'];

$sql = "SELECT * FROM workers_tbl WHERE id = ? AND factory_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();
    
    // Process the data for the report
    $designations = explode(',', $record['designation']);
    $grades = explode(',', $record['grade']);
    $males = explode(',', $record['male']);
    $females = explode(',', $record['female']);
    
    // Group data by grade
    $groupedData = [];
    for ($i = 0; $i < count($designations); $i++) {
        if (!empty($designations[$i]) && !empty($grades[$i])) {
            $grade = trim($grades[$i]);
            $designation = trim($designations[$i]);
            $male = intval($males[$i] ?? 0);
            $female = intval($females[$i] ?? 0);
            
            if (!isset($groupedData[$grade])) {
                $groupedData[$grade] = [];
            }
            
            $groupedData[$grade][] = [
                'designation' => $designation,
                'male' => $male,
                'female' => $female,
                'total' => $male + $female
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $record,
        'groupedData' => $groupedData
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Record not found']);
}

$stmt->close();
$conn->close();
?>