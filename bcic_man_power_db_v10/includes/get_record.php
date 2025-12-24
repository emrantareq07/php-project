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
$table = 'officers_tbl';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// $id = intval($_POST['id']);
// $mode = $_POST['mode'] ?? 'single'; // 'single' or 'combined' - default to 'single'

// try {
//     // First, get the specific record by ID
//     //$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
//     $stmt="SELECT 
//     m.*,                
//     u.factory_name 
   
// FROM 
//     officers_tbl m
// JOIN 
//     users u 
// ON 
//     m.factory_name = u.user_name
// WHERE 
//     m.id = $id;"


//     if (!$stmt) {
//         throw new Exception("SQL error: " . $conn->error);
//     }

//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows === 0) {
//         echo json_encode(['success' => false, 'message' => 'Record not found']);
//         $stmt->close();
//         exit;
//     }

//     $record = $result->fetch_assoc();
//     $stmt->close();

$id = intval($_POST['id']);
$mode = $_POST['mode'] ?? 'single'; // 'single' or 'combined'

try {

    // Prepared query
    $query = "
        SELECT 
            m.*,                
            u.factory_name 
        FROM 
            officers_tbl AS m
        JOIN 
            users AS u 
        ON 
            m.factory_name = u.username
        WHERE 
            m.id = ?
    ";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("SQL prepare error: " . $conn->error);
    }

    // Bind ID safely
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Record not found'
        ]);
        $stmt->close();
        exit;
    }

    $record = $result->fetch_assoc();
    $stmt->close();

    if ($mode === 'combined') {
        // For combined mode: get all records from the same month and combine them
        $date = $record['date'];
        $yearMonth = date('Y-m', strtotime($date));
        $likePattern = $yearMonth . '%';

        // Get all records from the same month
        $stmt2 = $conn->prepare("SELECT * FROM $table WHERE date LIKE ?");
        if (!$stmt2) {
            throw new Exception("SQL error: " . $conn->error);
        }

        $stmt2->bind_param("s", $likePattern);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $records = [];
        while ($row = $result2->fetch_assoc()) {
            $records[] = $row;
        }

        // Create combined result from all records
        $combinedData = combineRecords($records);

        echo json_encode([
            'success' => true, 
            'data' => $combinedData,
            'mode' => 'combined',
            'records_count' => count($records),
            'month' => $yearMonth,
            'message' => 'Combined record fetched successfully'
        ]);

        $stmt2->close();
    } else {
        // For single mode: return only the requested record
        echo json_encode([
            'success' => true, 
            'data' => $record,
            'mode' => 'single',
            'message' => 'Single record fetched successfully'
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
} finally {
    if (isset($conn)) $conn->close();
}

// Function to combine multiple records into one
function combineRecords($records) {
    if (empty($records)) return [];
    
    $combined = [];
    $grades = ['g1', 'g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10', 'g11', 'g12', 'g13', 'g14', 'g15', 'g16', 'g17', 'g18', 'g19', 'g20'];
    
    // Initialize combined arrays for each grade
    foreach ($grades as $grade) {
        $combined[$grade . '_m'] = array_fill(0, 15, 0); // 15 sections
        $combined[$grade . '_f'] = array_fill(0, 15, 0); // 15 sections
    }
    
    // Add date from first record
    $combined['date'] = $records[0]['date'];
    
    // Sum up all records
    foreach ($records as $record) {
        foreach ($grades as $grade) {
            $maleKey = $grade . '_m';
            $femaleKey = $grade . '_f';
            
            if (isset($record[$maleKey]) && !empty($record[$maleKey])) {
                $maleValues = explode(',', $record[$maleKey]);
                for ($i = 0; $i < min(15, count($maleValues)); $i++) {
                    $combined[$maleKey][$i] += intval($maleValues[$i] ?? 0);
                }
            }
            
            if (isset($record[$femaleKey]) && !empty($record[$femaleKey])) {
                $femaleValues = explode(',', $record[$femaleKey]);
                for ($i = 0; $i < min(15, count($femaleValues)); $i++) {
                    $combined[$femaleKey][$i] += intval($femaleValues[$i] ?? 0);
                }
            }
        }
    }
    
    // Convert arrays back to comma-separated strings
    foreach ($grades as $grade) {
        $maleKey = $grade . '_m';
        $femaleKey = $grade . '_f';
        
        $combined[$maleKey] = implode(',', $combined[$maleKey]);
        $combined[$femaleKey] = implode(',', $combined[$femaleKey]);
    }
    
    return $combined;
}
?>