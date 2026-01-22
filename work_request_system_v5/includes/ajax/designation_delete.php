<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

if (!in_array($_SESSION['role'], ['admin','sadmin'])) {
    exit('Access Denied');
}

$id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM designation WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Designation deleted";
