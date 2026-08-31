<?php
session_name('man_power_db');
session_start();
header('Content-Type: application/json');
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid record ID']);
    exit;
}

// Helper function to convert CSV to array
function csvToArray($csv) {
    if (empty($csv)) return [];
    return array_map('intval', explode(',', $csv));
}

// Structure definition
$structure = [
    'প্রথম শ্রেণী' => ['grades' => range(1, 9)],
    'দ্বিতীয় শ্রেণী' => ['grades' => [10]],
    'তৃতীয় শ্রেণী' => ['grades' => range(11, 16)],
    'চতুর্থ শ্রেণী' => ['grades' => range(17, 20)],
    'শ্রমিক' => ['grades' => range(1, 20)]
];

$total_grades = 0;
foreach ($structure as $classData) {
    $total_grades += count($classData['grades']);
}

// Fetch record
$sql = "SELECT * FROM vacant_statistics_tbl WHERE id = $id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $record = $result->fetch_assoc();
    
    // Parse CSV data
    $granted_array = csvToArray($record['granted_post']);
    $service_array = csvToArray($record['in_service']);
    $promo_array = csvToArray($record['eligible_promotion']);
    $direct_array = csvToArray($record['direct_recruit']);
    
    // Pad arrays
    while (count($granted_array) < $total_grades) $granted_array[] = 0;
    while (count($service_array) < $total_grades) $service_array[] = 0;
    while (count($promo_array) < $total_grades) $promo_array[] = 0;
    while (count($direct_array) < $total_grades) $direct_array[] = 0;
    
    $record['granted_array'] = $granted_array;
    $record['service_array'] = $service_array;
    $record['promo_array'] = $promo_array;
    $record['direct_array'] = $direct_array;
    $record['granted_total'] = array_sum($granted_array);
    $record['service_total'] = array_sum($service_array);
    $record['promo_total'] = array_sum($promo_array);
    $record['direct_total'] = array_sum($direct_array);
    $record['vacant_total'] = $record['promo_total'] + $record['direct_total'];
    $record['structure'] = $structure;
    $record['total_grades'] = $total_grades;
    
    echo json_encode([
        'success' => true,
        'data' => $record,
        'records_count' => 1,
        'month' => date('F Y', strtotime($record['entry_date']))
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Record not found']);
}
?>