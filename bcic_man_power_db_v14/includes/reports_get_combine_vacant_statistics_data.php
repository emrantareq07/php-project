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

// Check if table exists
$table_check = $conn->query("SHOW TABLES LIKE 'vacant_statistics_tbl'");
if ($table_check->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'vacant_statistics_tbl table does not exist in database']);
    exit;
}

// Query for vacant statistics data
$query = "SELECT 
            id, 
            factory_name, 
            entry_date,
            granted_post,
            in_service,
            eligible_promotion,
            direct_recruit
          FROM vacant_statistics_tbl 
          WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?)) 
          AND DATE_FORMAT(entry_date, '%Y-%m') = ? 
          ORDER BY entry_date";

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
    // Calculate vacant posts
    // Vacant = Granted Post - In Service
    $granted_post = intval($row['granted_post'] ?? 0);
    $in_service = intval($row['in_service'] ?? 0);
    $vacant = $granted_post - $in_service;
    
    $data[] = [
        'id' => $row['id'],
        'factory_name' => $row['factory_name'],
        'entry_date' => $row['entry_date'],
        'granted_post' => $granted_post,
        'in_service' => $in_service,
        'eligible_promotion' => intval($row['eligible_promotion'] ?? 0),
        'direct_recruit' => intval($row['direct_recruit'] ?? 0),
        'vacant' => $vacant
    ];
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