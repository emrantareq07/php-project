<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    ob_end_clean();
    exit;
}

$username = $_SESSION['username'];
$table = 'workers_tbl';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['date'])) {
            throw new Exception('Date is required');
        }
        
        $date = $_POST['date'];
        $factory_name = $_POST['factory_name'] ?? $username;
        $record_id = $_POST['record_id'] ?? null;

        // Validate date format
        if (!strtotime($date)) {
            throw new Exception('Invalid date format');
        }

        // Check monthly restriction - EXCLUDE current record during update
        $year_month = date('Y-m', strtotime($date));
        $check_sql = "SELECT id FROM $table WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
        
        if ($record_id) {
            // UPDATE MODE: Check for other entries excluding current record
            $check_sql .= " AND id != ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param('ssi', $factory_name, $year_month, $record_id);
        } else {
            // INSERT MODE: Check for any entries
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param('ss', $factory_name, $year_month);
        }
        
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $message = $record_id ? 
                'Another entry already exists for ' . date('F Y', strtotime($date)) : 
                'An entry already exists for ' . date('F Y', strtotime($date));
            
            echo json_encode(['success' => false, 'message' => $message]);
            $check_stmt->close();
            ob_end_flush();
            exit;
        }
        $check_stmt->close();

        // Validate array fields exist
        $required_arrays = ['designation', 'grade', 'sanctioned_post', 'male', 'female', 'total'];
        foreach ($required_arrays as $field) {
            if (!isset($_POST[$field]) || !is_array($_POST[$field])) {
                throw new Exception("Invalid data for $field");
            }
        }

        // Process array data
        $designation = implode(',', $_POST['designation']);
        $grade = implode(',', $_POST['grade']);
        $sanctioned_post = implode(',', $_POST['sanctioned_post']);
        $male = implode(',', $_POST['male']);
        $female = implode(',', $_POST['female']);
        $total = implode(',', $_POST['total']);

        if ($record_id) {
            // UPDATE existing record
            $sql = "UPDATE $table SET date = ?, designation = ?, grade = ?, sanctioned_post = ?, male = ?, female = ?, total = ? WHERE id = ? AND factory_name = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception('Database prepare error: ' . $conn->error);
            }
            $stmt->bind_param('sssssssis', $date, $designation, $grade, $sanctioned_post, $male, $female, $total, $record_id, $factory_name);
        } else {
            // INSERT new record
            $sql = "INSERT INTO $table (date, factory_name, designation, grade, sanctioned_post, male, female, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception('Database prepare error: ' . $conn->error);
            }
            $stmt->bind_param('ssssssss', $date, $factory_name, $designation, $grade, $sanctioned_post, $male, $female, $total);
        }
        
        if ($stmt->execute()) {
            $message = $record_id ? 'Worker data updated successfully!' : 'Worker data saved successfully!';
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            throw new Exception('Database error: ' . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close database connection
if (isset($conn)) {
    $conn->close();
}

// Clean output buffer and send response
ob_end_flush();
?>