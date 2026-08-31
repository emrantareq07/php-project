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

$month_key = isset($_POST['month_key']) ? $_POST['month_key'] : '';
$factory_name = isset($_POST['factory_name']) ? $_POST['factory_name'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (empty($month_key)) {
    echo json_encode(['success' => false, 'message' => 'Month key is required']);
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

// Get start and end date for the month
$start_date = $month_key . '-01';
$end_date = date('Y-m-t', strtotime($start_date));

// Build query
$sql = "SELECT * FROM vacant_statistics_tbl WHERE entry_date BETWEEN '$start_date' AND '$end_date'";
if ($factory_name) {
    $sql .= " AND factory_name = '" . $conn->real_escape_string($factory_name) . "'";
}
$sql .= " ORDER BY factory_name ASC";

$result = $conn->query($sql);
$records = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Parse CSV data
        $granted_array = csvToArray($row['granted_post']);
        $service_array = csvToArray($row['in_service']);
        $promo_array = csvToArray($row['eligible_promotion']);
        $direct_array = csvToArray($row['direct_recruit']);
        
        // Pad arrays
        while (count($granted_array) < $total_grades) $granted_array[] = 0;
        while (count($service_array) < $total_grades) $service_array[] = 0;
        while (count($promo_array) < $total_grades) $promo_array[] = 0;
        while (count($direct_array) < $total_grades) $direct_array[] = 0;
        
        $row['granted_total'] = array_sum($granted_array);
        $row['service_total'] = array_sum($service_array);
        $row['promo_total'] = array_sum($promo_array);
        $row['direct_total'] = array_sum($direct_array);
        $row['vacant_total'] = $row['promo_total'] + $row['direct_total'];
        
        $records[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'data' => $records,
    'records_count' => count($records),
    'month' => date('F Y', strtotime($start_date)),
    'factory_name' => $factory_name
]);
?>