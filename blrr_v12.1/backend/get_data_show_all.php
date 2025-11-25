<?php
// ----------------------------------------
// JSON + UTF-8 HEADER
// ----------------------------------------
header('Content-Type: application/json; charset=utf-8');
mb_internal_encoding("UTF-8");

include '../db/db.php';

// ----------------------------------------
// Prevent Undefined Warning
// ----------------------------------------
error_reporting(0);
ini_set('display_errors', 0);

// ----------------------------------------
// Validate table name (PREVENT SQL Injection)
// ----------------------------------------
$allowed_tables = ['chairman', 'chairman_office', 'friends_table', 'office_table']; 
// 👉 add your valid table list here

$table_name = $_GET['table_name'] ?? 'chairman';

if (!in_array($table_name, $allowed_tables)) {
    echo json_encode(['data' => [], 'error' => 'Invalid table']);
    exit;
}

// ----------------------------------------
// Fetch data
// ----------------------------------------
$sql = "
    SELECT 
        id,
        entry_date,
        recipient,
        d_number,
        ref_number,
        send_date,
        sender,
        div_dept_office,
        subject,
        destination,
        destination_drop,
        distribution_date,
        medium,
        status,
        chairman_note,
        comments
    FROM $table_name
    ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

$data = [];

// ----------------------------------------
// Process rows
// ----------------------------------------
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        // --------- FIX: Clean destination & destination_drop ----------
        $dest1 = trim($row['destination_drop'] ?? '', " ,");
        $dest2 = trim($row['destination'] ?? '', " ,");

        if ($dest1 !== '' && $dest2 !== '') {
            $final_destination = $dest1 . ", " . $dest2;
        } elseif ($dest1 !== '') {
            $final_destination = $dest1;
        } else {
            $final_destination = $dest2;
        }

        $row['destination_drop'] = $final_destination;

        // Add modified row
        $data[] = $row;
    }
}

// ----------------------------------------
// Output JSON for DataTables
// ----------------------------------------
echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
?>
