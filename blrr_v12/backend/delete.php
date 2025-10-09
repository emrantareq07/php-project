<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $table_name = $_POST['table_name'] ?? 'chairman';
    
    // Validate table name
    $allowed_tables = ['chairman', 'division', 'director'];
    if (!in_array($table_name, $allowed_tables)) {
        echo json_encode(['status' => 0, 'message' => 'Invalid table name']);
        exit;
    }

    $sql = "DELETE FROM $table_name WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 1, 'message' => 'Entry deleted successfully']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Error deleting entry']);
    }
}
?>