<?php
session_name('factory_work_request_db');
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['logged_in'])) {
    http_response_code(403);
    exit;
}

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    http_response_code(400);
    exit;
}

$stmt = $conn->prepare("INSERT INTO division (division) VALUES (?)");
$stmt->bind_param("s", $name);
$stmt->execute();

echo "success";

?>
