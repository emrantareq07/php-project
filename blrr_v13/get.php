<?php
include 'db.php';

// If ID is passed, return a single record
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM chairman WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    echo json_encode(mysqli_fetch_assoc($result));
    exit;
}

// Otherwise return all records for DataTables
$sql = "SELECT * FROM chairman ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
