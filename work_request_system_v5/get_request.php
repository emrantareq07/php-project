<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "work_request_db";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB error");

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM work_request_tbl WHERE id=$id");
$data = $result->fetch_assoc();

$items = [];
$res = $conn->query("SELECT * FROM work_request_items WHERE request_id=$id");
while($row = $res->fetch_assoc()) {
    $items[] = $row;
}
$data['items'] = $items;

echo json_encode($data);
