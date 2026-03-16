<?php
include "../../db.php";
$id=$_POST['id'];
$project_name=$_POST['project_name'];
$project_url=$_POST['project_url'];
$category=$_POST['category']?:'Other';
$status=$_POST['status']?:'Active';
$description=$_POST['description']?:'';
$icon_color=$_POST['icon_color']?:'#3498db';
$sql="UPDATE projects SET project_name='$project_name',project_url='$project_url',category='$category',status='$status',description='$description',icon_color='$icon_color'";
if(isset($_FILES['screenshot']) && $_FILES['screenshot']['name']){
$ext=pathinfo($_FILES['screenshot']['name'],PATHINFO_EXTENSION);
$screenshot='uploads/'.time().rand(100,999).'.'.$ext;
move_uploaded_file($_FILES['screenshot']['tmp_name'],$screenshot);
$sql.=",screenshot='$screenshot'";
}
$sql.=" WHERE id=$id";
$conn->query($sql);
echo json_encode(['message'=>'Project updated']);