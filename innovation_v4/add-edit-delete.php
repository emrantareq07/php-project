<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = "innovation_db";
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

//  if ($_POST['mode'] === 'add') {     
//  //$name = $_POST['name'];
//  //$email = $_POST['email'];
     
// $fiscal_year=$_POST['fiscal_year'];
//  $title_of_invention=$_POST['title_of_invention'];

//  $inventors_name=$_POST['inventors_name'];
//  $designation=$_POST['designation'];
//  $inventors_emp_id=$_POST['inventors_emp_id'];
//  $proposed_workplace=$_POST['proposed_workplace'];
 
//  $des_of_invention=$_POST['des_of_invention'];
//  $imple_status=$_POST['imple_status'];
//  $replicate_eligibility=$_POST['replicate_eligibility'];
 
//  $feedback=$_POST['feedback'];
//  $service_link=$_POST['service_link'];
//  $remarks=$_POST['remarks'];
     
// mysqli_query($conn, "INSERT INTO innovation_tbl (fiscal_year,title_of_invention,inventors_name,inventors_designation,
//  inventors_emp_id,proposed_workplace,des_of_invention, imple_status,replicate_eligibility,feedback,service_link,remarks) 
//  VALUES('{$fiscal_year}','{$title_of_invention}','{$inventors_name}','{$designation}',
//  '{$inventors_emp_id}','{$proposed_workplace}','{$des_of_invention}','{$imple_status}','{$replicate_eligibility}',
//  '{$feedback}','{$service_link}','{$remarks}')");
//  // Execute query
//         if ($conn->query($sql) === TRUE) {
//             echo json_encode(["status" => "success", "message" => "Record added successfully"]);
//         } else {
//             throw new Exception("Database error: " . $conn->error);
//         }

//      echo json_encode(true);
// }  

if ($_POST['mode'] === 'add') {
    $fiscal_year = $_POST['fiscal_year'];
    $title_of_invention = $_POST['title_of_invention'];
    $inventors_name = $_POST['inventors_name'];
    $designation = $_POST['designation'];
    $inventors_emp_id = $_POST['inventors_emp_id'];
    $proposed_workplace = $_POST['proposed_workplace'];
    $des_of_invention = $_POST['des_of_invention'];
    $imple_status = $_POST['imple_status'];
    $replicate_eligibility = $_POST['replicate_eligibility'];
    $feedback = $_POST['feedback'];
    $service_link = $_POST['service_link'];
    $remarks = $_POST['remarks'];

    $sql = "INSERT INTO innovation_tbl 
            (fiscal_year, title_of_invention, inventors_name, inventors_designation,
            inventors_emp_id, proposed_workplace, des_of_invention, imple_status,
            replicate_eligibility, feedback, service_link, remarks) 
            VALUES('$fiscal_year', '$title_of_invention', '$inventors_name', '$designation',
            '$inventors_emp_id', '$proposed_workplace', '$des_of_invention', '$imple_status', 
            '$replicate_eligibility', '$feedback', '$service_link', '$remarks')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Record added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
}


if ($_POST['mode'] === 'view') {    
    $result = mysqli_query($conn,"SELECT * FROM innovation_tbl WHERE id='" . $_POST['id'] . "'");
    $row= mysqli_fetch_array($result);
     echo json_encode($row);
} 

if ($_POST['mode'] === 'edit') {    
    $result = mysqli_query($conn,"SELECT * FROM innovation_tbl WHERE id='" . $_POST['id'] . "'");
    $row= mysqli_fetch_array($result);
     echo json_encode($row);
}   

if ($_POST['mode'] === 'update') {
mysqli_query($conn,"UPDATE innovation_tbl set  fiscal_year='" . $_POST['fiscal_year'] . "', title_of_invention='" . $_POST['title_of_invention'] . 
 "', inventors_name='" . $_POST['inventors_name'] . "', inventors_designation='" . $_POST['designation'] .
 "', inventors_emp_id='" . $_POST['inventors_emp_id'] . "', proposed_workplace='" . $_POST['proposed_workplace'] .
 "', des_of_invention='" . $_POST['des_of_invention'] . "', imple_status='" . $_POST['imple_status'] . 
 "', replicate_eligibility='" . $_POST['replicate_eligibility'] .
 "', feedback='" . $_POST['feedback'] .
 "', service_link='" . $_POST['service_link'] .
 "', remarks='" . $_POST['remarks'] ."' WHERE id='" . $_POST['id'] . "'");
    echo json_encode(true);
}  

if ($_POST['mode'] === 'delete') {
     mysqli_query($conn, "DELETE FROM innovation_tbl WHERE id='" . $_POST["id"] . "'");
     echo json_encode(true);
}  

?>