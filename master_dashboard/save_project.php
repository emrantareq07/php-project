<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {

$database = new Database();
$db = $database->getConnection();

$project_name = $_POST['project_name'];
$project_url  = $_POST['project_url'];
$category     = $_POST['category'];
$status       = $_POST['status'];
$icon_color   = $_POST['icon_color'];
$description  = $_POST['description'];

$sql = "INSERT INTO projects
(project_name, project_url, category, status, icon_color, description)
VALUES
(:project_name, :project_url, :category, :status, :icon_color, :description)";

$stmt = $db->prepare($sql);

$stmt->bindParam(":project_name",$project_name);
$stmt->bindParam(":project_url",$project_url);
$stmt->bindParam(":category",$category);
$stmt->bindParam(":status",$status);
$stmt->bindParam(":icon_color",$icon_color);
$stmt->bindParam(":description",$description);

$stmt->execute();

echo json_encode([
"status"=>"success"
]);

} catch(Exception $e){

echo json_encode([
"status"=>"error",
"message"=>$e->getMessage()
]);

}
?>