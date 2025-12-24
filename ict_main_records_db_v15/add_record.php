<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "ict_main_records_db";

// Database connection
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize and fetch input data
        $requisition_no = $conn->real_escape_string($_POST['requisition_no'] ?? '');
        $date = $conn->real_escape_string($_POST['date'] ?? '');
        $product_ids = $conn->real_escape_string($_POST['selected_product_ids'] ?? '');
        $amounts = $conn->real_escape_string($_POST['selected_amounts'] ?? '');

        // New fields
        $emp_id = $conn->real_escape_string($_POST['emp_id'] ?? '');
        $user_name = $conn->real_escape_string($_POST['user_name'] ?? '');
        $division_dept = $conn->real_escape_string($_POST['division_dept'] ?? '');
        $designation = $conn->real_escape_string($_POST['designation'] ?? '');
        $place_of_posting = 'BCIC H.O.'; // Default value        

        $srm = $conn->real_escape_string($_POST['srm'] ?? '');
        $srm_ref_no = $conn->real_escape_string($_POST['srm_ref_no'] ?? '');
        $bill_or_challan_no = $conn->real_escape_string($_POST['bill_or_challan_no'] ?? '');
        $remarks = $conn->real_escape_string($_POST['remarks'] ?? '');
        $vendor_name = $conn->real_escape_string($_POST['vendor_name'] ?? '');

        // Only keep vendor_name if remarks is "Done By Vendor"
        $vendor_name = ($remarks === "Done By Vendor") ? $vendor_name : NULL;        

        $p_sn = $conn->real_escape_string($_POST['selected_p_sn'] ?? '');

        if ($product_ids === "" || $amounts === "" || $p_sn === "") {
        throw new Exception("No products or amounts provided.");
        }

        // Check if emp_id already exists
        $check_sql = "SELECT emp_id FROM employees WHERE emp_id = '$emp_id'";
        $result = $conn->query($check_sql);

        if ($result->num_rows == 0) {
            // Insert a new employee record if emp_id doesn't exist
            $sql_employees_tbl = "INSERT INTO employees 
                (emp_id, user_name, designation, place_of_posting, division_dept) 
                VALUES ('$emp_id', '$user_name', '$designation', '$place_of_posting', '$division_dept')";

            if (!$conn->query($sql_employees_tbl)) {
                throw new Exception("Error adding employee: " . $conn->error);
            }
        }

        // Insert the product IDs into `records_tbl`
        $sql = "INSERT INTO records_tbl 
            (requisition_no, emp_id, user_name, division_dept, designation, place_of_posting, date, product_name, p_sn, srm, srm_ref_no, bill_or_challan_no, remarks, vendor_name, amounts) 
            VALUES ('$requisition_no', '$emp_id', '$user_name', '$division_dept', '$designation', '$place_of_posting', '$date', '$product_ids', '$p_sn', '$srm', '$srm_ref_no', '$bill_or_challan_no', '$remarks', " . 
            ($vendor_name ? "'$vendor_name'" : "NULL") . ", '$amounts')";

        if (!$conn->query($sql)) {
            throw new Exception("Error adding record: " . $conn->error);
        }

        echo json_encode(["status" => "success", "message" => "Record added successfully"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close the database connection
$conn->close();
?>