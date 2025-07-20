<?php
// Include database connection
include('db/db.php');

// // Read operation - fetch all employees
if ($_GET['action'] == 'read') {   
    $result = $conn->query("
        SELECT 
            e.id, 
            e.emp_id, 
            e.name, 
            e.designation, 
            e.place_of_posting, 
            o.training_type, 
            o.training_title, 
            o.ref_no, 
            o.start_date, 
            o.end_date, 
            o.t_institute, 
            o.order_attachment
        FROM employees e
        LEFT JOIN office_order o ON e.ref_id = o.id         
    ");
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($employees);
}

if ($_GET['action'] == 'get') {
    $id = intval($_GET['id']);  // Sanitize the ID
    $sql1 = "SELECT ref_id FROM employees WHERE id = '$id'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $ref_id = $row1['ref_id'];//2

$sql = "SELECT e.id,e.emp_id, e.name, e.designation, e.place_of_posting, o.ref_no, 
               o.training_type, o.training_title, o.start_date, o.end_date, o.t_institute
        FROM employees e
        LEFT JOIN office_order o ON e.ref_id = o.id
        WHERE e.id = $id AND o.id = $ref_id" ;
    $result = $conn->query($sql);
    echo json_encode($result->fetch_assoc());
}


// Get a single employee for editing
// if ($_GET['action'] == 'get') {
//     $id = intval($_GET['id']);  // Sanitize the ID

//     // Fetch the ref_id from the employees table
//     $sql1 = "SELECT ref_id FROM employees WHERE id = '$id'";
//     $result1 = mysqli_query($conn, $sql1);
//     $row1 = mysqli_fetch_assoc($result1);
    
//     // Assign ref_id correctly
//     $ref_id = $row1['ref_id'];

//     // Fetch employee details and join with office_order table
//     $result = $conn->query("SELECT * 
//                             FROM employees e
//                             LEFT JOIN office_order o ON e.ref_id = o.id 
//                             WHERE e.id = $id");

//     // Return the result as JSON
//     echo json_encode($result->fetch_assoc());
// }


// Save (Add/Update) Employee
if ($_GET['action'] == 'save') {
    $id = intval($_POST['id']);
    $emp_id = $_POST['emp_id'];
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $place_of_posting = $_POST['place_of_posting'];
    $ref_no = $_POST['ref_no'];


    $sql1 = "SELECT id FROM office_order WHERE ref_no = '$ref_no'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $ref_id = $row1['id'];

    if ($id == 0) {
        // INSERT Logic (Add new employee)
        $sql = "INSERT INTO employees (emp_id, name, designation, place_of_posting, ref_id) 
                VALUES ('$emp_id', '$name', '$designation', '$place_of_posting', '$ref_id')";
        if ($conn->query($sql)) {
            echo 'Employee added successfully';
        } else {
            echo 'Error: ' . $conn->error;
        }
    } else {
        $sql = "UPDATE employees 
                SET emp_id='$emp_id', name='$name', designation='$designation', place_of_posting='$place_of_posting', ref_id='$ref_id'";   

        $sql .= " WHERE id=$id";

        if ($conn->query($sql)) {
            echo 'Employee updated successfully';
        } else {
            echo 'Error: ' . $conn->error;
        }
    }
}
// Delete Employee
if ($_GET['action'] == 'delete') {
    $id = intval($_POST['id']); 
 $conn->query("DELETE FROM employees WHERE id = $id");
}

$conn->close();
?>
