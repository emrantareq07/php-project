<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.xls"');

// Fetch data
$sql = "SELECT id, username, full_name, factory_name, designation, role, 
        CASE WHEN status = 1 THEN 'Active' ELSE 'Inactive' END as status,
        CASE WHEN login_status = 1 THEN 'Online' ELSE 'Offline' END as login_status
        FROM users 
        WHERE role IN ('admin', 'user') 
        ORDER BY id DESC";
$result = $conn->query($sql);

// Output Excel content
echo "ID\tUsername\tFull Name\tFactory\tDesignation\tRole\tStatus\tLogin Status\n";

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t";
    echo $row['username'] . "\t";
    echo $row['full_name'] . "\t";
    echo $row['factory_name'] . "\t";
    echo $row['designation'] . "\t";
    echo $row['role'] . "\t";
    echo $row['status'] . "\t";
    echo $row['login_status'] . "\n";
}
?>