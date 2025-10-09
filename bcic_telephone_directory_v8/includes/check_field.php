<?php
session_start();
header('Content-Type: application/json');
include '../db/db.php'; // adjust path to your DB connection

// Function to sanitize input
function sanitize($data){
    return htmlspecialchars(trim($data));
}

// Get GET parameters
$field = isset($_GET['field']) ? sanitize($_GET['field']) : '';
$value = isset($_GET['value']) ? sanitize($_GET['value']) : '';

$validFields = ['emp_id','mobile','email'];
if(!in_array($field, $validFields)){
    echo json_encode(['success'=>false,'message'=>'Invalid field']);
    exit;
}

// Prepare query
$sql = "SELECT COUNT(*) as cnt FROM emp_tbl WHERE $field = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s',$value);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$exists = ($row['cnt'] > 0);

echo json_encode(['success'=>true,'exists'=>$exists]);
exit;
?>
