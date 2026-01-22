<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$factory_name = $_SESSION['username'];
$date = $_POST['date'] ?? '';
$table = $_POST['table'] ?? 'officers_tbl';
$exclude_id = $_POST['exclude_id'] ?? null;

if (empty($date)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Date is required']);
    exit;
}

// Function to check monthly entry
function checkMonthlyEntry($conn, $table, $factory_name, $date, $exclude_id = null) {
    $year_month = date('Y-m', strtotime($date));
    $sql = "SELECT id, date FROM $table WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    
    $params = [$factory_name, $year_month];
    $types = 'ss';
    
    if ($exclude_id) {
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
        $types .= 'i';
    }
    
    $stmt = $conn->prepare($sql);
    if ($types === 'ss') {
        $stmt->bind_param('ss', ...$params);
    } else {
        $stmt->bind_param('ssi', ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    
    return $exists;
}

// Check if entry exists
$exists = checkMonthlyEntry($conn, $table, $factory_name, $date, $exclude_id);
$month_year = date('F Y', strtotime($date));

header('Content-Type: application/json');
echo json_encode([
    'exists' => $exists,
    'month_year' => $month_year,
    'date' => $date
]);
?>