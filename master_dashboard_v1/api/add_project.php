<?php
require_once("../config/database.php");

$database = new Database();
$db = $database->getConnection();

$name=$_POST['project_name'];
$url=$_POST['project_url'];
$category=$_POST['category'];
$status=$_POST['status'];
$color=$_POST['icon_color'];
$desc=$_POST['description'];

$screenshot="";

if(!empty($_FILES['screenshot']['name'])){

$target="../uploads/".time().$_FILES['screenshot']['name'];
move_uploaded_file($_FILES['screenshot']['tmp_name'],$target);

$screenshot=basename($target);

}

$query="INSERT INTO projects
(project_name,project_url,category,status,icon_color,description,screenshot)
VALUES (?,?,?,?,?,?,?)";

$stmt=$db->prepare($query);

$stmt->execute([
$name,$url,$category,$status,$color,$desc,$screenshot
]);

echo json_encode(["success"=>true]);