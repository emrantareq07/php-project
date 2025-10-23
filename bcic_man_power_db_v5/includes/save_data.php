<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');
date_default_timezone_set('Asia/Dhaka');

// === Error & Debug Setup ===
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

function debug_log($msg) {
    file_put_contents(__DIR__ . '/debug_save.log', date('Y-m-d H:i:s') . " - " . $msg . "\n", FILE_APPEND);
}

function json_response($success, $message, $extra = []) {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

debug_log("=== SAVE_DATA STARTED ===");

if (!isset($_SESSION['username'])) {
    debug_log("Authentication failed.");
    json_response(false, 'Not authenticated');
}

$username = $_SESSION['username'];
$table = 'officers_tbl';

if (!$conn) {
    debug_log("Database connection failed.");
    json_response(false, 'Database connection failed');
}

try {
    $factory_name = trim($username);
    $date = $_POST['date'] ?? date("Y-m-d");
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : null;

    // if (empty($factory_name) || empty($date)) {
    //     throw new Exception('Factory name and date are required');
    // }

    $grades = ['g2', 'g3', 'g4', 'g5', 'g6', 'g7', 'g8', 'g9', 'g10'];
    $sections_count = 14;
    $data_arrays = [];

    foreach ($grades as $grade) {
        $data_arrays[$grade.'_m'] = array_fill(0, $sections_count, '0');
        $data_arrays[$grade.'_f'] = array_fill(0, $sections_count, '0');
    }

    if (!empty($_POST['data']) && is_array($_POST['data'])) {
        debug_log("Processing " . count($_POST['data']) . " section entries");
        foreach ($_POST['data'] as $index => $section_data) {
            foreach ($grades as $grade) {
                $m = $grade.'_m';
                $f = $grade.'_f';
                if (!empty($section_data[$m])) $data_arrays[$m][$index] = $section_data[$m];
                if (!empty($section_data[$f])) $data_arrays[$f][$index] = $section_data[$f];
            }
        }
    }

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

    if ($id) {
        // --- UPDATE ---
        debug_log("Preparing UPDATE for ID $id");

        $check_stmt = $conn->prepare("SELECT id FROM $table WHERE id = ? AND factory_name = ?");
        $check_stmt->bind_param("is", $id, $factory_name);
        $check_stmt->execute();
        $res = $check_stmt->get_result();
        if ($res->num_rows === 0) {
            throw new Exception('Record not found or access denied');
        }
        $check_stmt->close();

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
        array_push($params, 'active', $insert_data['updated_at'], $id);
        $types .= "ssi";

    } else {
        // --- INSERT ---
        debug_log("Preparing INSERT for new record");

        // Extract year and month from input date
        $input_year = date('Y', strtotime($date));
        $input_month = date('m', strtotime($date));

        // Check duplicate by month/year
        $dup_stmt = $conn->prepare("
            SELECT id 
            FROM $table 
            WHERE YEAR(date) = ? AND MONTH(date) = ? AND factory_name = ?
        ");
        $dup_stmt->bind_param("iis", $input_year, $input_month, $factory_name);
        $dup_stmt->execute();
        $dup_res = $dup_stmt->get_result();
        if ($dup_res->num_rows > 0) {
            $exist = $dup_res->fetch_assoc();
            throw new Exception("A record for this month already exists (ID: {$exist['id']}). Please use edit instead.");
        }
        $dup_stmt->close();


        $sql = "INSERT INTO $table (factory_name, date, department, ";
        $placeholders = "VALUES (?, ?, ?, ";
        $params = [$factory_name, $date, ''];
        $types = "sss";

        foreach ($grades as $grade) {
            $sql .= "{$grade}_m, {$grade}_f, {$grade}_sanctioned_post, ";
            $placeholders .= "?, ?, ?, ";
            array_push($params, $insert_data[$grade.'_m'], $insert_data[$grade.'_f'], $insert_data[$grade.'_sanctioned_post']);
            $types .= "sss";
        }

        $sql .= "status, created_at, updated_at) $placeholders ?, ?, ?)";
        array_push($params, 'active', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
        $types .= "sss";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        if ($id) {
            debug_log("Updated ID $id successfully");
            json_response(true, "Data updated successfully!", ['id' => $id]);
        } else {
            $new_id = $stmt->insert_id;
            debug_log("Inserted new record ID $new_id");
            json_response(true, "Data saved successfully!", ['id' => $new_id]);
        }
    } else {
        throw new Exception("Execution failed: " . $stmt->error);
    }

} catch (Exception $e) {
    debug_log("ERROR: " . $e->getMessage());
    json_response(false, $e->getMessage());
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    debug_log("=== SAVE_DATA COMPLETED ===");
}
?>
