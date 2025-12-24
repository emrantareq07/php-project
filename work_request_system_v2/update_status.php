<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "work_request_db";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB error");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE work_request_tbl SET status=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "status" => $status]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
}
