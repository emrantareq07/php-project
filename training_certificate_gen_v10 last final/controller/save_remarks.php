<?php

require_once "db.php";

$id = $_POST['user_id'];
$remarks = $_POST['remarks'];

$stmt = $conn->prepare("UPDATE users_tbl SET remarks=? WHERE id=?");
$stmt->bind_param("si",$remarks,$id);

$stmt->execute();

echo "success";