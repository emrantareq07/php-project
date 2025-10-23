<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Create debug log function
function debug_log($message) {
    file_put_contents('debug_save.log', date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

debug_log("=== SAVE_DATA STARTED ===");

if (!isset($_SESSION['username'])) {
    debug_log("User not authenticated");
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$username = $_SESSION['username'];
$table = 'officers_tbl';

debug_log("User: $username, Table: $table");

try {
    // Check database connection
    if (!$conn) {
        throw new Exception('Database connection failed');
    }
    debug_log("Database connection OK");
    
    // Get POST data
    $factory_name = $username;
    $date = $_POST['date'] ?? date("Y-m-d");
    $id = $_POST['id'] ?? null;
    
    debug_log("Factory: $factory_name, Date: $date, ID: " . ($id ?: 'null'));
    
    if (empty($factory_name) || empty($date)) {
        throw new Exception('Factory name and date are required');
    }

    // Initialize all grade fields with default values
    $grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];
    $sections_count = 14;
    
    $data_arrays = [];
    foreach ($grades as $grade) {
        $data_arrays[$grade.'_m'] = array_fill(0, $sections_count, '0');
        $data_arrays[$grade.'_f'] = array_fill(0, $sections_count, '0');
    }
    
    // Process submitted data
    if (isset($_POST['data']) && is_array($_POST['data'])) {
        debug_log("Processing data array with " . count($_POST['data']) . " sections");
        
        foreach ($_POST['data'] as $index => $section_data) {
            foreach ($grades as $grade) {
                $male_field = $grade.'_m';
                $female_field = $grade.'_f';
                
                if (isset($section_data[$male_field]) && $section_data[$male_field] !== '') {
                    $data_arrays[$male_field][$index] = $section_data[$male_field];
                }
                
                if (isset($section_data[$female_field]) && $section_data[$female_field] !== '') {
                    $data_arrays[$female_field][$index] = $section_data[$female_field];
                }
            }
        }
    }
    
    // Prepare data for database
    $insert_data = [
        'factory_name' => $factory_name,
        'date' => $date,
        'department' => '',
        'status' => 'active',
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    foreach ($grades as $grade) {
        $insert_data[$grade.'_m'] = implode(',', $data_arrays[$grade.'_m']);
        $insert_data[$grade.'_f'] = implode(',', $data_arrays[$grade.'_f']);
        $insert_data[$grade.'_sanctioned_post'] = '';
    }
    
    debug_log("Data prepared for " . count($grades) . " grades");
    
    if ($id && $id > 0) {
        // UPDATE existing record
        debug_log("Attempting UPDATE for ID: $id");
        
        $check_sql = "SELECT id FROM $table WHERE id = ? AND factory_name = ?";
        $check_stmt = $conn->prepare($check_sql);
        if (!$check_stmt) {
            throw new Exception('Failed to prepare check statement: ' . $conn->error);
        }
        $check_stmt->bind_param("is", $id, $factory_name);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            $check_stmt->close();
            throw new Exception('Record not found or access denied');
        }
        $check_stmt->close();
        
        // Build UPDATE query
        $sql = "UPDATE $table SET factory_name=?, date=?, department=?, ";
        $params = [$factory_name, $date, ''];
        $types = "sss";
        
        foreach ($grades as $grade) {
            $sql .= "{$grade}_m=?, {$grade}_f=?, {$grade}_sanctioned_post=?, ";
            $params[] = $insert_data[$grade.'_m'];
            $params[] = $insert_data[$grade.'_f'];
            $params[] = $insert_data[$grade.'_sanctioned_post'];
            $types .= "sss";
        }
        
        $sql .= "status=?, updated_at=? WHERE id=?";
        $params[] = 'active';
        $params[] = date('Y-m-d H:i:s');
        $params[] = $id;
        $types .= "ssi";
        
        debug_log("UPDATE SQL prepared");
        
    } else {
        // INSERT new record
        debug_log("Attempting INSERT for new record");
        
        // Check for duplicate date
        $check_sql = "SELECT id FROM $table WHERE date = ? AND factory_name = ?";
        $check_stmt = $conn->prepare($check_sql);
        if (!$check_stmt) {
            throw new Exception('Failed to prepare duplicate check statement: ' . $conn->error);
        }
        $check_stmt->bind_param("ss", $date, $factory_name);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $existing_record = $result->fetch_assoc();
            $check_stmt->close();
            throw new Exception('A record for this date already exists (ID: ' . $existing_record['id'] . '). Please use edit instead.');
        }
        $check_stmt->close();
        
        // Build INSERT query
        $sql = "INSERT INTO $table (factory_name, date, department, ";
        $placeholders = "VALUES (?, ?, ?, ";
        $params = [$factory_name, $date, ''];
        $types = "sss";
        
        foreach ($grades as $grade) {
            $sql .= "{$grade}_m, {$grade}_f, {$grade}_sanctioned_post, ";
            $placeholders .= "?, ?, ?, ";
            $params[] = $insert_data[$grade.'_m'];
            $params[] = $insert_data[$grade.'_f'];
            $params[] = $insert_data[$grade.'_sanctioned_post'];
            $types .= "sss";
        }
        
        $sql .= "status, created_at, updated_at) " . $placeholders . "?, ?, ?)";
        $params[] = 'active';
        $params[] = date('Y-m-d H:i:s');
        $params[] = date('Y-m-d H:i:s');
        $types .= "sss";
        
        debug_log("INSERT SQL prepared");
    }
    
    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }
    
    debug_log("Binding parameters: $types, " . count($params) . " parameters");
    $stmt->bind_param($types, ...$params);
    
    // Execute the query
    if ($stmt->execute()) {
        if ($id) {
            debug_log("Record UPDATED successfully - ID: $id");
            echo json_encode([
                'success' => true, 
                'message' => 'Data updated successfully!',
                'id' => $id
            ]);
        } else {
            $new_id = $stmt->insert_id;
            debug_log("Record INSERTED successfully - New ID: $new_id");
            echo json_encode([
                'success' => true, 
                'message' => 'Data saved successfully!',
                'id' => $new_id
            ]);
        }
    } else {
        throw new Exception('Database execution error: ' . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $error_msg = $e->getMessage();
    debug_log("ERROR: " . $error_msg);
    echo json_encode([
        'success' => false, 
        'message' => $error_msg
    ]);
}

debug_log("=== SAVE_DATA COMPLETED ===\n");

if (isset($conn) && $conn) {
    $conn->close();
}
?>