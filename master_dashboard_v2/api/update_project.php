<?php
include "../db.php";

$id=$_POST['id'];

$name=$_POST['project_name'];

$url=$_POST['project_url'];

$conn->query("UPDATE projects SET project_name='$name',project_url='$url' WHERE id=$id");

echo json_encode(["success"=>true]);