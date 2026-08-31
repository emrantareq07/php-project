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

$id = intval($_POST['id']);
$mode = $_POST['mode'] ?? 'single';

try {
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
        $date = $record['date'];
        $yearMonth = date('Y-m', strtotime($date));
        
        $stmt2 = $conn->prepare("SELECT * FROM $table WHERE DATE_FORMAT(date, '%Y-%m') = ?");
        if (!$stmt2) {
            throw new Exception("SQL error: " . $conn->error);
        }

        $stmt2->bind_param("s", $yearMonth);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $records = [];
        while ($row = $result2->fetch_assoc()) {
            $records[] = $row;
        }

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

function combineRecords($records) {
    if (empty($records)) return [];
    
    $combined = [];
    $grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g9', 'g10'];
    $numSections = 14;
    
    foreach ($grades as $grade) {
        $combined[$grade . '_m'] = array_fill(0, $numSections, 0);
        $combined[$grade . '_f'] = array_fill(0, $numSections, 0);
    }
    
    $combined['date'] = $records[0]['date'];
    
    foreach ($records as $record) {
        foreach ($grades as $grade) {
            $maleKey = $grade . '_m';
            $femaleKey = $grade . '_f';
            
            if (isset($record[$maleKey]) && $record[$maleKey] !== '') {
                $maleValues = explode(',', $record[$maleKey]);
                for ($i = 0; $i < min($numSections, count($maleValues)); $i++) {
                    $value = isset($maleValues[$i]) ? intval(trim($maleValues[$i])) : 0;
                    $combined[$maleKey][$i] += (is_numeric($value) && !is_nan($value)) ? $value : 0;
                }
            }
            
            if (isset($record[$femaleKey]) && $record[$femaleKey] !== '') {
                $femaleValues = explode(',', $record[$femaleKey]);
                for ($i = 0; $i < min($numSections, count($femaleValues)); $i++) {
                    $value = isset($femaleValues[$i]) ? intval(trim($femaleValues[$i])) : 0;
                    $combined[$femaleKey][$i] += (is_numeric($value) && !is_nan($value)) ? $value : 0;
                }
            }
        }
    }
    
    foreach ($grades as $grade) {
        $maleKey = $grade . '_m';
        $femaleKey = $grade . '_f';
        
        $combined[$maleKey] = implode(',', $combined[$maleKey]);
        $combined[$femaleKey] = implode(',', $combined[$femaleKey]);
    }
    
    return $combined;
}
?>