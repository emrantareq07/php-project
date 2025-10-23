<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$table = 'workers_tbl';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if all required fields are set
        $date = $_POST['date'] ?? date("Y-m-d");
        $designations = $_POST['designation'] ?? [];
        $grades = $_POST['grade'] ?? [];
        $sanctioned_posts = $_POST['sanctioned_post'] ?? [];
        $males = $_POST['male'] ?? [];
        $females = $_POST['female'] ?? [];
        
        // Validate date
        if (empty($date)) {
            throw new Exception('Date is required');
        }
        
        // Validate arrays
        if (empty($designations) || empty($grades)) {
            throw new Exception('Please add at least one worker entry');
        }
        
        // Validate arrays have same length
        if (count($designations) !== count($grades) || 
            count($designations) !== count($sanctioned_posts) ||
            count($designations) !== count($males) ||
            count($designations) !== count($females)) {
            throw new Exception('Invalid data submitted - array lengths mismatch');
        }
        
        // Prepare comma-separated values with proper escaping
        $designation_str = implode(',', array_map(function($item) use ($conn) {
            return $conn->real_escape_string(trim($item));
        }, $designations));
        
        $grade_str = implode(',', array_map(function($item) use ($conn) {
            return $conn->real_escape_string(trim($item));
        }, $grades));
        
        $sanctioned_post_str = implode(',', array_map(function($item) {
            return intval(trim($item));
        }, $sanctioned_posts));
        
        $male_str = implode(',', array_map(function($item) {
            return intval(trim($item));
        }, $males));
        
        $female_str = implode(',', array_map(function($item) {
            return intval(trim($item));
        }, $females));
        
        // Calculate totals
        $totals = [];
        for ($i = 0; $i < count($males); $i++) {
            $totals[] = intval($males[$i]) + intval($females[$i]);
        }
        $total_str = implode(',', $totals);
        
        // Check if record already exists for this factory and exact date
        $check_sql = "SELECT id FROM workers_tbl WHERE factory_name = ? AND date = ?";
        $check_stmt = $conn->prepare($check_sql);
        
        if (!$check_stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $check_stmt->bind_param('ss', $username, $date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Update existing record for exact date
            $sql = "UPDATE workers_tbl 
                    SET designation = ?, grade = ?, sanctioned_post = ?, male = ?, female = ?, total = ?, updated_at = NOW()
                    WHERE factory_name = ? AND date = ?";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception('Database error: ' . $conn->error);
            }
            
            $stmt->bind_param('ssssssss', 
                $designation_str, 
                $grade_str, 
                $sanctioned_post_str, 
                $male_str, 
                $female_str, 
                $total_str,
                $username,
                $date
            );
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Worker data updated successfully!';
            } else {
                throw new Exception('Failed to update data: ' . $stmt->error);
            }
            
            if (isset($stmt)) $stmt->close();
            
        } else {
            // Check for duplicate by month/year for new records
            $input_year = date('Y', strtotime($date));
            $input_month = date('m', strtotime($date));

            // Check if a record already exists for this factory in the same month/year
            $dup_sql = "SELECT id, date FROM workers_tbl WHERE YEAR(date) = ? AND MONTH(date) = ? AND factory_name = ?";
            $dup_stmt = $conn->prepare($dup_sql);
            
            if (!$dup_stmt) {
                throw new Exception('Database error: ' . $conn->error);
            }
            
            $dup_stmt->bind_param("iis", $input_year, $input_month, $username);
            $dup_stmt->execute();
            $dup_result = $dup_stmt->get_result();
            
            if ($dup_result->num_rows > 0) {
                $existing_record = $dup_result->fetch_assoc();
                $existing_date = $existing_record['date'];
                $existing_id = $existing_record['id'];
                
                $dup_stmt->close();
                $check_stmt->close();
                throw new Exception("A record for {$input_month}/{$input_year} already exists (Date: {$existing_date}). Please use edit instead or choose a different month.");
            }
            $dup_stmt->close();

            // Insert new record (only if no monthly duplicate found)
            $sql = "INSERT INTO workers_tbl 
                    (factory_name, date, designation, grade, sanctioned_post, male, female, total, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())";
            
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception('Database error: ' . $conn->error);
            }
            
            $stmt->bind_param('ssssssss', 
                $username,
                $date,
                $designation_str, 
                $grade_str, 
                $sanctioned_post_str, 
                $male_str, 
                $female_str, 
                $total_str
            );
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Worker data saved successfully!';
            } else {
                throw new Exception('Failed to save data: ' . $stmt->error);
            }
            
            if (isset($stmt)) $stmt->close();
        }
        
        if (isset($check_stmt)) $check_stmt->close();
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        error_log("Worker Save Error: " . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit;
?>