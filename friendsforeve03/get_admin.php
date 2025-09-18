<?php
session_name('friendsforeve03');
if(session_status()===PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

$status = $_POST['status'] ?? '';

$sql = "SELECT * FROM friends";
$params = [];

if($status==='pending' || $status==='approved'){
    $sql .= " WHERE status=?";
    $params[] = $status;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if($params){ $stmt->bind_param("s",$params[0]); }
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row=$result->fetch_assoc()){
    $data[] = [
        'id'=>$row['id'],
        'name'=>htmlspecialchars($row['name']),
        'mobile'=>htmlspecialchars($row['mobile']),
        'alt_mobile'=>htmlspecialchars($row['alt_mobile']),
        'email'=>htmlspecialchars($row['email']),
        'occupation'=>htmlspecialchars($row['occupation']),
        'jobplace'=>htmlspecialchars($row['jobplace']),
        'address'=>htmlspecialchars($row['address']),
        'status'=>$row['status']
    ];
}

echo json_encode(['data'=>$data]);
exit;
