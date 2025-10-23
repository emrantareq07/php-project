<?php
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $factory_name = $_SESSION['username'];
    $record_id = $_POST['record_id'] ?? null;
    
    if (!$record_id) {
        echo json_encode(['success' => false, 'message' => 'Record ID is required for update']);
        exit;
    }
    
    // Get form data
    $date = $_POST['date'] ?? date('Y-m-d');
    $designations = $_POST['designation'] ?? [];
    $grades = $_POST['grade'] ?? [];
    $sanctioned_posts = $_POST['sanctioned_post'] ?? [];
    $males = $_POST['male'] ?? [];
    $females = $_POST['female'] ?? [];
    
    // Validate data
    if (empty($designations) {
        echo json_encode(['success' => false, 'message' => 'No worker data provided']);
        exit;
    }
    
    // Prepare arrays for database
    $designation_str = implode(',', $designations);
    $grade_str = implode(',', $grades);
    $sanctioned_post_str = implode(',', $sanctioned_posts);
    $male_str = implode(',', $males);
    $female_str = implode(',', $females);
    
    // Calculate totals
    $totals = [];
    for ($i = 0; $i < count($males); $i++) {
        $totals[] = ($males[$i] ?? 0) + ($females[$i] ?? 0);
    }
    $total_str = implode(',', $totals);
    
    // Update record
    $sql = "UPDATE workers_tbl SET 
            date = ?, 
            designation = ?, 
            grade = ?, 
            sanctioned_post = ?, 
            male = ?, 
            female = ?, 
            total = ?,
            updated_at = NOW()
            WHERE id = ? AND factory_name = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssis', 
        $date, 
        $designation_str, 
        $grade_str, 
        $sanctioned_post_str, 
        $male_str, 
        $female_str, 
        $total_str,
        $record_id,
        $factory_name
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>