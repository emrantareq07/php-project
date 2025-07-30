<?php
include('db/db.php');

// Check if REF NO is set (for office_order lookup)
if (isset($_POST['ref_no'])) {
    $ref_no = mysqli_real_escape_string($conn, $_POST['ref_no']);

    $sql = "SELECT start_date, end_date, training_type, training_title, t_institute 
            FROM office_order 
            WHERE ref_no = '$ref_no'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
        echo json_encode([
            "success" => true,
            "start_date" => $employee['start_date'],
            "end_date" => $employee['end_date'],
            "training_type" => $employee['training_type'],
            "training_title" => $employee['training_title'],
            "t_institute" => $employee['t_institute']
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}

// Check if EMP ID is set (for employee detail lookup)
if (isset($_POST['emp_id'])) {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);

    // First, try to fetch from 'employees' table
    $sql_emp = "SELECT name, designation, place_of_posting 
                FROM employees 
                WHERE emp_id = '$emp_id' 
                ORDER BY id DESC 
                LIMIT 1";
    $result_emp = mysqli_query($conn, $sql_emp);

    if (mysqli_num_rows($result_emp) > 0) {
        $employee = mysqli_fetch_assoc($result_emp);
        echo json_encode([
            "success" => true,
            "name" => $employee['name'],
            "designation" => $employee['designation'],
            "place_of_posting" => $employee['place_of_posting']
        ]);
    } else {
        // If not found in 'employees', try 'bcic_staff'
        $sql_bcic = "SELECT name, designation, place_of_posting 
                     FROM bcic_staff 
                     WHERE emp_id = '$emp_id' 
                     ORDER BY id DESC 
                     LIMIT 1";
        $result_bcic = mysqli_query($conn, $sql_bcic);

        if (mysqli_num_rows($result_bcic) > 0) {
            $employee = mysqli_fetch_assoc($result_bcic);
            echo json_encode([
                "success" => true,
                "name" => $employee['name'],
                "designation" => $employee['designation'],
                "place_of_posting" => $employee['place_of_posting']
            ]);
        } else {
            echo json_encode(["success" => false]);
        }
    }
    exit;
}
?>
