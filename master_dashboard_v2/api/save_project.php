<?php
include "../db.php";

$name = $_POST['project_name'];
$url = $_POST['project_url'];
$cat = $_POST['category'];
$status = $_POST['status'];
$color = $_POST['icon_color'];
$desc = $_POST['description'];

$stmt = $conn->prepare("INSERT INTO projects (project_name, project_url, category, status, icon_color, description) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $url, $cat, $status, $color, $desc);
$stmt->execute();

echo json_encode(["status" => "success"]);