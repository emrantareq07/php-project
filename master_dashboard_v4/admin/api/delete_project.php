<?php
include "../../db.php";

$id=$_POST['id'];

$conn->query("UPDATE projects SET is_active=0 WHERE id=$id");

echo json_encode(["success"=>true]);