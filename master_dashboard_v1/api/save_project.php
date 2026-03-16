<?php

require_once("../config/database.php");

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['project_name'];
$url = $data['project_url'];
$category = $data['category'] ?? "Other";
$status = $data['status'] ?? "Active";
$color = $data['icon_color'] ?? "#3498db";
$desc = $data['description'] ?? "";

$query = "INSERT INTO projects
(project_name,project_url,category,status,icon_color,description)
VALUES
(:name,:url,:category,:status,:color,:desc)";

$stmt = $db->prepare($query);

$stmt->bindParam(":name",$name);
$stmt->bindParam(":url",$url);
$stmt->bindParam(":category",$category);
$stmt->bindParam(":status",$status);
$stmt->bindParam(":color",$color);
$stmt->bindParam(":desc",$desc);

if($stmt->execute()){

echo json_encode([
"success"=>true
]);

}else{

echo json_encode([
"success"=>false
]);

}