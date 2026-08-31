<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');
date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$username = $_SESSION['username'];
$table = 'daily_basis_tbl';



if (isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    
    $sql = "SELECT * FROM $table WHERE id = '$id'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Get full factory name from users table
        $factoryCode = $data['factory_name'];
        
        
        if (!empty($factoryCode)) {
            $userSql = "SELECT factory_name FROM users WHERE username = '$factoryCode' ";
            $userResult = $conn->query($userSql);
            
            if ($userResult && $userResult->num_rows > 0) {
                $userData = $userResult->fetch_assoc();
                $factory_name = $userData['factory_name'];
            }
        }
        
        // Add full factory name to response
        $data['factory_name'] = $factory_name;
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Record not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID not provided'
    ]);
}
?>