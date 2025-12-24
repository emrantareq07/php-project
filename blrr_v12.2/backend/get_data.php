<?php
header('Content-Type: application/json');
include '../db/db.php';

$today_date = date("Y-m-d");
$year_auto  = date("Y", strtotime($today_date));

error_reporting(0);
ini_set('display_errors', 0);

$table_name = $_GET['table_name'] ?? 'chairman';

$sql = "SELECT 
    id, entry_date, recipient, d_number, ref_number, send_date, 
    sender, div_dept_office, subject, destination, destination_drop, 
    distribution_date, medium, status, chairman_note, comments
FROM $table_name
WHERE (immediate_sender_office='' OR immediate_sender_office IS NULL)
AND entry_date = '$today_date'
ORDER BY CAST(d_number AS UNSIGNED) DESC";

$result = mysqli_query($conn, $sql);

$data = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        // Merge destination + destination_drop
        $destination_drop = rtrim($row['destination_drop'], ',');
        if (!empty($row['destination'])) {
            $destination_drop .= (!empty($destination_drop) ? ', ' : '') . $row['destination'];
        }
        $row['destination_drop'] = $destination_drop;

        $data[] = $row;
    }
}

echo json_encode(['data' => $data]);
?>
