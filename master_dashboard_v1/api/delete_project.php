<?php

require_once("../config/database.php");

$database=new Database();
$db=$database->getConnection();

$id=$_POST['id'];

$stmt=$db->prepare("UPDATE projects SET is_active=0 WHERE id=?");
$stmt->execute([$id]);

echo json_encode(["success"=>true]);