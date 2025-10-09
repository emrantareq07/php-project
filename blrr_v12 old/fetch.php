<?php
header('Content-Type: application/json');

include 'db.php';

// Disable error output in production; keep minimal reporting for dev if needed
error_reporting(0);
ini_set('display_errors', 0);

$id = intval($_GET['id'] ?? 0);
$table_name = $_GET['table_name'] ?? 'chairman';

if ($id <= 0) {
    echo json_encode([]);
    exit;
}

// NOTE: table name comes from trusted source in your app (session). If you ever accept arbitrary table names from user, validate/whitelist it.
$sql = "SELECT * FROM `$table_name` WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Normalize destination field: prefer 'destination' but fallback to 'destination_drop'
        if (!isset($row['destination']) && isset($row['destination_drop'])) {
            $row['destination'] = $row['destination_drop'];
        }
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode([]);
}
?>
