<?php
include "../../db.php";
$project_name=$_POST['project_name'];
$project_url=$_POST['project_url'];
$category=$_POST['category']?:'Other';
$status=$_POST['status']?:'Active';
$description=$_POST['description']?:'';
$icon_color=$_POST['icon_color']?:'#3498db';
$screenshot='';
if(isset($_FILES['screenshot']) && $_FILES['screenshot']['name']){
$ext=pathinfo($_FILES['screenshot']['name'],PATHINFO_EXTENSION);
$screenshot='uploads/'.time().rand(100,999).'.'.$ext;
move_uploaded_file($_FILES['screenshot']['tmp_name'],$screenshot);
}
$conn->query("INSERT INTO projects(project_name,project_url,category,status,description,icon_color,screenshot) VALUES('$project_name','$project_url','$category','$status','$description','$icon_color','$screenshot')");
echo json_encode(['message'=>'Project added']);