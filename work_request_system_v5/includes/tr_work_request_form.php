<?php
// work_request.php
session_name('factory_work_request_db');
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$designation = $_SESSION['designation'];
$user_division = $_SESSION['division']; // Changed variable name to avoid conflict
$user_section = $_SESSION['section'];   // Changed variable name to avoid conflict
$emp_id = $_SESSION['emp_id'];
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    $date = $_POST['date'] ?? '';
    $w_req_type = $_POST['w_req_type'] ?? '';
    $w_location = trim($_POST['w_location'] ?? '');
    $w_description = trim($_POST['w_description'] ?? '');
    $w_com_division = $_POST['w_com_division'] ?? '';
    $w_com_section = trim($_POST['w_com_section'] ?? '');
    $status = $_POST['status'] ?? 'normal';
    $remarks = trim($_POST['remarks'] ?? '');
    
    // Transport specific fields
    $transport_data = [];
    if ($w_req_type === 'Transport') {
        $transport_data = [
            'date' => $_POST['date'] ?? '',            
            'contact_no' => $_POST['contact_no'] ?? '',
            'departure_date' => $_POST['departure_date'] ?? '',
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
            'no_of_visitor' => $_POST['no_of_visitor'] ?? 0,
            'visiting_place' => trim($_POST['visiting_place'] ?? ''),
            'destination' => trim($_POST['destination'] ?? ''),
            'visit_purpose' => trim($_POST['visit_purpose'] ?? ''),
            'reporting_place' => trim($_POST['reporting_place'] ?? ''),
            'visiting_type' => $_POST['visiting_type'] ?? 'Official',
            'v_provide_status' => $_POST['v_provide_status'] ?? 'Yes'
        ];
        
        // For Transport requests, use default values for hidden fields
        $w_location = "Transport Request - " . $transport_data['destination'];
        $w_description = "Transport request for " . $transport_data['visit_purpose'];
        $w_com_division = "Transport Division"; // Default division for transport
        $w_com_section = "Transport"; // Default section for transport
        $status = "normal"; // Default status for transport
        $remarks = "Transport Type: " . $transport_data['visiting_type'];
    }
    
    // Validation
    $errors = [];
    
    if (empty($date)) {
        $errors[] = "Date is required";
    } else {
        // Check if date is not in the future
        $selected_date = strtotime($date);
        $today = strtotime(date('Y-m-d'));
        if ($selected_date > $today) {
            $errors[] = "Date cannot be in the future";
        }
    }
    
    if (empty($w_req_type)) {
        $errors[] = "Work request type is required";
    }
    
    // For non-transport requests, validate standard fields
    if ($w_req_type !== 'Transport') {
        if (empty($w_location)) {
            $errors[] = "Location is required";
        } elseif (strlen($w_location) < 3) {
            $errors[] = "Location must be at least 3 characters";
        }
        
        if (empty($w_description)) {
            $errors[] = "Description is required";
        } elseif (strlen($w_description) < 10) {
            $errors[] = "Description must be at least 10 characters";
        }
        
        if (empty($w_com_division)) {
            $errors[] = "Concerned division is required";
        }
        
        if (empty($w_com_section)) {
            $errors[] = "Concerned section is required";
        }
    }
    
    // Transport specific validation
    if ($w_req_type === 'Transport') {
        // if (empty($transport_data['emp_id'])) {
        //     $errors[] = "Employee ID is required for Transport request";
        // }
        if (empty($transport_data['contact_no'])) {
            $errors[] = "Contact number is required for Transport request";
        }
        if (empty($transport_data['departure_date'])) {
            $errors[] = "Departure date is required for Transport request";
        }
        if (empty($transport_data['start_time'])) {
            $errors[] = "Start time is required for Transport request";
        }
        if (empty($transport_data['end_time'])) {
            $errors[] = "End time is required for Transport request";
        }
        if (empty($transport_data['visiting_place'])) {
            $errors[] = "Visiting place is required for Transport request";
        }
        if (empty($transport_data['destination'])) {
            $errors[] = "Destination is required for Transport request";
        }
        if (empty($transport_data['visit_purpose'])) {
            $errors[] = "Visit purpose is required for Transport request";
        }
        if ($transport_data['no_of_visitor'] <= 0) {
            $errors[] = "Number of visitors must be greater than 0";
        }
    }
    
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Prepare SQL statement for work request
            $sql = "INSERT INTO work_request_tbl (emp_id,
                date, w_req_type, w_location, w_description, 
                w_com_division, w_com_section, status, remarks,
                requester_id, full_name, designation, division, section
            ) VALUES (?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                throw new Exception("Database error: " . $conn->error);
            }
            
            $stmt->bind_param(
                "sssssssssissss",
                $emp_id,$date, $w_req_type, $w_location, $w_description,
                $w_com_division, $w_com_section, $status, $remarks,
                $user_id, $full_name, $designation, $user_division, $user_section
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to submit work request: " . $stmt->error);
            }
            
            $work_request_id = $stmt->insert_id;
            $stmt->close();
            
            // If transport request, also save to transport_w_req_tbl
            if ($w_req_type === 'Transport') {
                $sql_transport = "INSERT INTO transport_w_req_tbl (
                    work_request_id, emp_id, full_name, designation, division, section,
                    contact_no, departure_date, start_time, end_time, no_of_visitor,
                    visiting_place, destination, visit_purpose, reporting_place,
                    visiting_type, v_provide_status, requester_id, date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt_transport = $conn->prepare($sql_transport);
                
                if ($stmt_transport === false) {
                    throw new Exception("Transport table error: " . $conn->error);
                }
                
                $stmt_transport->bind_param(
                    "isssssssssissssssis",
                    $work_request_id,
                    $emp_id,
                    $full_name,
                    $designation,
                    $user_division,
                    $user_section,
                    $transport_data['contact_no'],
                    $transport_data['departure_date'],
                    $transport_data['start_time'],
                    $transport_data['end_time'],
                    $transport_data['no_of_visitor'],
                    $transport_data['visiting_place'],
                    $transport_data['destination'],
                    $transport_data['visit_purpose'],
                    $transport_data['reporting_place'],
                    $transport_data['visiting_type'],
                    $transport_data['v_provide_status'],
                    $user_id,
                    $date
                );
                
                if (!$stmt_transport->execute()) {
                    throw new Exception("Failed to save transport details: " . $stmt_transport->error);
                }
                
                $stmt_transport->close();
            }
            
            // Commit transaction
            $conn->commit();
            
            $message = "Work request submitted successfully! Request ID: WR-" . str_pad($work_request_id, 6, '0', STR_PAD_LEFT);
            
            // Clear form
            $_POST = array();
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = $e->getMessage();
        }
    }
}

// Get divisions for dropdown
$divisions = [];
$sections = [];

$sql_div = "SELECT division FROM division";
$result_div = $conn->query($sql_div);
if ($result_div && $result_div->num_rows > 0) {
    while ($row = $result_div->fetch_assoc()) {
        $divisions[] = $row['division'];
    }
}

$sql_sec = "SELECT name FROM section";
$result_sec = $conn->query($sql_sec);
if ($result_sec && $result_sec->num_rows > 0) {
    while ($row = $result_sec->fetch_assoc()) {
        $sections[] = $row['name'];
    }
}
include "header_test.php"
?>