<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

if (!in_array($_SESSION['role'], ['admin','sadmin'])) {
    exit('Access Denied');
}

$id = intval($_POST['id']);
$name = trim($_POST['name']);

$stmt = $conn->prepare("UPDATE section SET name=? WHERE id=?");
$stmt->bind_param("si", $name, $id);
$stmt->execute();

echo "Section updated";
