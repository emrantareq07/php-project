<?php

require_once("../config/database.php");

$database=new Database();
$db=$database->getConnection();

$id=$_POST['id'];
$name=$_POST['project_name'];
$url=$_POST['project_url'];
$category=$_POST['category'];
$status=$_POST['status'];
$desc=$_POST['description'];

$query="UPDATE projects
SET project_name=?,
project_url=?,
category=?,
status=?,
description=?
WHERE id=?";

$stmt=$db->prepare($query);

$stmt->execute([
$name,$url,$category,$status,$desc,$id
]);

echo json_encode(["success"=>true]);