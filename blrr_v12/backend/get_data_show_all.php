<?php
// Set headers first to prevent any output
header('Content-Type: application/json');

include '../db/db.php';
$today_date=date("Y-m-d");
$year_auto = date("Y", strtotime($today_date));
// Enable error reporting for debugging (remove in production)
error_reporting(0);
ini_set('display_errors', 0);

$table_name = $_GET['table_name'] ?? 'chairman';

// Validate table name to prevent SQL injection
// $allowed_tables = ['chairman', 'division', 'director'];
// if (!in_array($table_name, $allowed_tables)) {
//     echo json_encode(['data' => []]);
//     exit;
// }

$sql = "SELECT 
    id, entry_date, recipient, d_number, ref_number, send_date, 
    sender, div_dept_office, subject,destination, destination_drop, distribution_date, 
    medium, status, chairman_note, comments 
FROM $table_name 
ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

$data = array();
// if ($result) {
//     while ($row = mysqli_fetch_assoc($result)) {
//         $data[] = $row;
//     }
// }
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $destination_drop = rtrim($row['destination_drop'], ',');
        if(!empty($row['destination'])){
            $destination_drop .= (!empty($destination_drop) ? ', ' : '') . $row['destination'];
        }
        $row['destination_drop'] = $destination_drop;
        $data[] = $row;
    }
}
echo json_encode(['data' => $data]);
?>