<?php
header('Content-Type: application/json');

if (isset($_POST['reg_no'])) {
    require_once("../db/db.php"); // Your DB connection file

    $regNo = $_POST['reg_no'];
    
    $stmt = $conn->prepare("SELECT reg_no FROM std_tbl WHERE reg_no = ?");
    $stmt->bind_param("s", $regNo);
    $stmt->execute();
    $stmt->store_result();
    
    $exists = ($stmt->num_rows > 0);
    
    echo json_encode(["exists" => $exists]);
    
    $stmt->close();
    $conn->close();
}
?>