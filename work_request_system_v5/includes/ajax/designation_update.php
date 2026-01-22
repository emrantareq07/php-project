<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

if (!in_array($_SESSION['role'], ['admin','sadmin'])) {
    exit('Access Denied');
}

$id = intval($_POST['id']);
$designation = trim($_POST['designation']);

$stmt = $conn->prepare("UPDATE designation SET designation=? WHERE id=?");
$stmt->bind_param("si", $designation, $id);
$stmt->execute();

echo "Designation updated";
