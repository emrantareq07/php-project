<?php
header('Content-Type: application/json');
include '../db/db.php';

$table_name = $_GET['table_name'] ?? 'chairman';

// Validate table name
// $allowed_tables = ['chairman','division','director','chairman'];
// if(!in_array($table_name, $allowed_tables)){
//     echo json_encode(['data'=>[]]);
//     exit;
// }

$sql = "SELECT 
    id, immediate_sender_office, entry_date, recipient, d_number, ref_number, send_date,
    sender, div_dept_office, subject, destination, destination_drop, distribution_date, medium, status, chairman_note, comments
    FROM $table_name
    WHERE immediate_sender_office != ''
    ORDER BY FIELD(status,'pending','complete'), id DESC";

$result = mysqli_query($conn, $sql);
$data = [];

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

echo json_encode(['data'=>$data]);
?>
