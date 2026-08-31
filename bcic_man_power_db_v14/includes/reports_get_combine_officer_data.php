<?php
// Start session properly
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

// Get the logged-in username (which is also the factory name)
$logged_in_user = trim($_SESSION['username']);
$month_key = $_POST['month_key'] ?? '';

if (empty($month_key)) {
    echo json_encode(['success' => false, 'message' => 'Month parameter is required.']);
    exit;
}

// Query to get data only for the logged-in user's factory
// We match username with factory_name in database
$query = "SELECT 
            id, 
            factory_name, 
            date, 
            department,
            COALESCE(g2_m, 0) as g2_m,
            COALESCE(g2_f, 0) as g2_f,
            COALESCE(g3_m, 0) as g3_m,
            COALESCE(g3_f, 0) as g3_f,
            COALESCE(g4_m, 0) as g4_m,
            COALESCE(g4_f, 0) as g4_f,
            COALESCE(g5_m, 0) as g5_m,
            COALESCE(g5_f, 0) as g5_f,
            COALESCE(g6_m, 0) as g6_m,
            COALESCE(g6_f, 0) as g6_f,
            COALESCE(g7_m, 0) as g7_m,
            COALESCE(g7_f, 0) as g7_f,
            COALESCE(g8_m, 0) as g8_m,
            COALESCE(g8_f, 0) as g8_f,
            COALESCE(g9_m, 0) as g9_m,
            COALESCE(g9_f, 0) as g9_f,
            COALESCE(g10_m, 0) as g10_m,
            COALESCE(g10_f, 0) as g10_f
          FROM officers_tbl 
          WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?)) 
          AND DATE_FORMAT(date, '%Y-%m') = ? 
          ORDER BY department";

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

// If no data found with exact match, try partial match
if (count($data) == 0) {
    $query2 = "SELECT 
                id, 
                factory_name, 
                date, 
                department,
                COALESCE(g2_m, 0) as g2_m,
                COALESCE(g2_f, 0) as g2_f,
                COALESCE(g3_m, 0) as g3_m,
                COALESCE(g3_f, 0) as g3_f,
                COALESCE(g4_m, 0) as g4_m,
                COALESCE(g4_f, 0) as g4_f,
                COALESCE(g5_m, 0) as g5_m,
                COALESCE(g5_f, 0) as g5_f,
                COALESCE(g6_m, 0) as g6_m,
                COALESCE(g6_f, 0) as g6_f,
                COALESCE(g7_m, 0) as g7_m,
                COALESCE(g7_f, 0) as g7_f,
                COALESCE(g8_m, 0) as g8_m,
                COALESCE(g8_f, 0) as g8_f,
                COALESCE(g9_m, 0) as g9_m,
                COALESCE(g9_f, 0) as g9_f,
                COALESCE(g10_m, 0) as g10_m,
                COALESCE(g10_f, 0) as g10_f
              FROM officers_tbl 
              WHERE LOWER(TRIM(factory_name)) LIKE LOWER(TRIM(CONCAT('%', ?, '%'))) 
              AND DATE_FORMAT(date, '%Y-%m') = ? 
              ORDER BY department";
    
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("ss", $logged_in_user, $month_key);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    while ($row = $result2->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt2->close();
}

echo json_encode([
    'success' => true,
    'records_count' => count($data),
    'data' => $data,
    'logged_in_user' => $logged_in_user,
    'month' => $month_key,
    'match_type' => count($data) > 0 ? 'found' : 'not_found'
]);
exit;
?>