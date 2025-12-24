<?php
include('db/db.php');

// Check if EMP ID is set
if (isset($_POST['ref_no'])) {
    $ref_no = mysqli_real_escape_string($conn, $_POST['ref_no']);

    // Query to fetch employee details
    $sql = "SELECT start_date, end_date,training_type,training_title,t_institute FROM office_order WHERE ref_no = '$ref_no'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);

        // Respond with employee details
        echo json_encode([
            "success" => true,
            "start_date" => $employee['start_date'],

            "end_date" => $employee['end_date'],
             "training_type" => $employee['training_type'],
             "training_title" => $employee['training_title'],
             "t_institute" => $employee['t_institute']
        ]);
    } else {
        // Respond with failure if no employee is found
        echo json_encode(["success" => false]);
    }
    exit;
}


// Check if EMP ID is set

if (isset($_POST['emp_id'])) {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);

    // Query to fetch employee details
    $sql = "SELECT user_name, designation, division_dept, place_of_posting 
            FROM employees 
            WHERE emp_id = '$emp_id' LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
        echo json_encode([
            "success" => true,
            "user_name" => $employee['user_name'],
            "designation" => $employee['designation'],
            "division_dept" => $employee['division_dept'],
            "place_of_posting" => $employee['place_of_posting']
        ]);
    } else {
        // If no employee is found
       echo json_encode(["success" => false, "message" => "Employee not found."]);
    }
} else {
    // If EMP ID is not set in the request
    echo json_encode(["success" => false, "message" => "EMP ID not provided."]);
}


?>

