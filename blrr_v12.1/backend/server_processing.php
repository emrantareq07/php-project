<?php
include_once '../db/database_old.php';

// Define columns for DataTables
$columns = [
    'id',
    'entry_date',
    'to_l',
    'd_number',
    'ref_number',
    'send_date',
    'from',
    'subject',
    'destination',
    'dis_date',
    'by'
];

// Get parameters from DataTables
$draw = $_GET['draw'];
$start = $_GET['start'];
$length = $_GET['length'];
$searchValue = $_GET['search']['value'];

// Search filter
$searchQuery = "";
if (!empty($searchValue)) {
    $searchValue = $conn_old->real_escape_string($searchValue);
    $searchQuery = "WHERE 
        to_l LIKE '%$searchValue%' OR 
        subject LIKE '%$searchValue%' OR 
        ref_number LIKE '%$searchValue%'";
}

// Total records count
$totalQuery = "SELECT COUNT(*) as total FROM rri";
$totalResult = $conn_old->query($totalQuery);
$totalRecords = $totalResult->fetch_assoc()['total'];

// Filtered records count
$filteredQuery = "SELECT COUNT(*) as total FROM rri $searchQuery";
$filteredResult = $conn_old->query($filteredQuery);
$totalFilteredRecords = $filteredResult->fetch_assoc()['total'];

// Fetch records with limit and search
$dataQuery = "SELECT * FROM rri $searchQuery ORDER BY id DESC LIMIT $start, $length";
$dataResult = $conn_old->query($dataQuery);

// Prepare data for DataTables
$data = [];
while ($row = $dataResult->fetch_assoc()) {
    $row['from'] = htmlspecialchars($row['from']);
    $row['destination'] = implode(', ', array_filter([
        $row['dt'], $row['dc'], $row['df'], $row['dp'], $row['dpl'], $row['sacretry'],
        $row['cop'], $row['marketing'], $row['audit'], $row['emd'], $row['saksha'],
        $row['law'], $row['company'], $row['purchase'], $row['ict'], $row['other_des']
    ], fn($value) => $value !== ''));

    $data[] = $row;
}

// Output JSON
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFilteredRecords),
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>
