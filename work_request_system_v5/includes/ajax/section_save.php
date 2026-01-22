<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

if (!in_array($_SESSION['role'], ['admin','sadmin'])) {
    exit('Access Denied');
}

$division_id = intval($_POST['division_id']);
$name = trim($_POST['name']);

// Duplicate check
$chk = $conn->prepare("SELECT id FROM section WHERE division_id=? AND name=?");
$chk->bind_param("is", $division_id, $name);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    exit('Section already exists');
}

$stmt = $conn->prepare("INSERT INTO section (division_id, name) VALUES (?,?)");
$stmt->bind_param("is", $division_id, $name);
$stmt->execute();

echo "Section saved successfully";
