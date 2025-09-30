<?php
session_name('friendsforeve03');
if(session_status()===PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../db/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id<=0){ echo json_encode(['error'=>'Invalid ID']); exit; }

$stmt = $conn->prepare("SELECT * FROM friends WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($row){
    echo json_encode([
        'id'=>$row['id'],
        'name'=>htmlspecialchars($row['name']),
        'mobile'=>htmlspecialchars($row['mobile']),
        'alt_mobile'=>htmlspecialchars($row['alt_mobile']),
        'email'=>htmlspecialchars($row['email']),
        'occupation'=>htmlspecialchars($row['occupation']),
        'jobplace'=>htmlspecialchars($row['jobplace']),
        'address'=>htmlspecialchars($row['address']),
        'status'=>$row['status']
    ]);
}else{
    echo json_encode(['error'=>'Record not found']);
}
exit;
