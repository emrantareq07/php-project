<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

if (!in_array($_SESSION['role'], ['admin','sadmin'])) {
    exit('Access Denied');
}

$designation = trim($_POST['designation']);

// Duplicate check
$chk = $conn->prepare("SELECT id FROM designation WHERE designation=?");
$chk->bind_param("s", $designation);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    exit('Designation already exists');
}

$stmt = $conn->prepare("INSERT INTO designation (designation) VALUES (?)");
$stmt->bind_param("s", $designation);
$stmt->execute();

echo "Designation saved successfully";
