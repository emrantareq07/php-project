<?php
session_name('man_power_db');
session_start();

include('../db/db.php');

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check authentication
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please login again.']);
    exit;
}

$logged_in_user = trim($_SESSION['username']);
$month_key = $_POST['month_key'] ?? '';

if (empty($month_key)) {
    echo json_encode(['success' => false, 'message' => 'Month parameter is required.']);
    exit;
}

// Query for staff data from staffs_tbl
$query = "SELECT 
            id, 
            factory_name, 
            date, 
            designation,
            grade,
            sanctioned_post,
            COALESCE(male, 0) as male,
            COALESCE(female, 0) as female,
            COALESCE(total, 0) as total
          FROM staffs_tbl 
          WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?)) 
          AND DATE_FORMAT(date, '%Y-%m') = ? 
          ORDER BY grade, designation";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $logged_in_user, $month_key);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();

echo json_encode([
    'success' => true,
    'records_count' => count($data),
    'data' => $data,
    'logged_in_user' => $logged_in_user,
    'month' => $month_key
]);
exit;
?>