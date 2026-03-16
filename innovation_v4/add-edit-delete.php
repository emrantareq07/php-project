<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = "innovation_db";
$conn = mysqli_connect($host, $username, $password, $dbname);

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
}

$response = array("status" => "error", "message" => "Invalid request");

if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
    
    // ADD new record
    if ($mode === 'add') {
        // Get and sanitize input data
        $fiscal_year = mysqli_real_escape_string($conn, $_POST['fiscal_year'] ?? '');
        $title_of_invention = mysqli_real_escape_string($conn, $_POST['title_of_invention'] ?? '');
        $inventors_name = mysqli_real_escape_string($conn, $_POST['inventors_name'] ?? '');
        $inventors_designation = mysqli_real_escape_string($conn, $_POST['inventors_designation'] ?? '');
        $inventors_emp_id = mysqli_real_escape_string($conn, $_POST['inventors_emp_id'] ?? '');
        $proposed_workplace = mysqli_real_escape_string($conn, $_POST['proposed_workplace'] ?? '');
        $des_of_invention = mysqli_real_escape_string($conn, $_POST['des_of_invention'] ?? '');
        $imple_status = mysqli_real_escape_string($conn, $_POST['imple_status'] ?? '');
        $replicate_eligibility = mysqli_real_escape_string($conn, $_POST['replicate_eligibility'] ?? '');
        $feedback = mysqli_real_escape_string($conn, $_POST['feedback'] ?? '');
        $service_link = mysqli_real_escape_string($conn, $_POST['service_link'] ?? '');
        $remarks = mysqli_real_escape_string($conn, $_POST['remarks'] ?? '');

        // Validate required fields
        if (empty($fiscal_year) || empty($title_of_invention) || empty($inventors_name)) {
            echo json_encode(["status" => "error", "message" => "Required fields cannot be empty"]);
            exit;
        }

        $sql = "INSERT INTO innovation_tbl 
                (fiscal_year, title_of_invention, inventors_name, inventors_designation,
                inventors_emp_id, proposed_workplace, des_of_invention, imple_status,
                replicate_eligibility, feedback, service_link, remarks, created_at) 
                VALUES('$fiscal_year', '$title_of_invention', '$inventors_name', '$inventors_designation',
                '$inventors_emp_id', '$proposed_workplace', '$des_of_invention', '$imple_status', 
                '$replicate_eligibility', '$feedback', '$service_link', '$remarks', NOW())";

        if ($conn->query($sql) === TRUE) {
            $response = ["status" => "success", "message" => "Record added successfully!"];
        } else {
            $response = ["status" => "error", "message" => "Database error: " . $conn->error];
        }
    }
    
    // EDIT - Fetch single record
    elseif ($mode === 'edit') {
        $id = mysqli_real_escape_string($conn, $_POST['id'] ?? 0);
        
        $result = mysqli_query($conn, "SELECT * FROM innovation_tbl WHERE id='$id'");
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode($row);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Record not found"]);
            exit;
        }
    }
    
    // UPDATE record
    elseif ($mode === 'update') {
        $id = mysqli_real_escape_string($conn, $_POST['id'] ?? 0);
        $fiscal_year = mysqli_real_escape_string($conn, $_POST['fiscal_year'] ?? '');
        $title_of_invention = mysqli_real_escape_string($conn, $_POST['title_of_invention'] ?? '');
        $inventors_name = mysqli_real_escape_string($conn, $_POST['inventors_name'] ?? '');
        $inventors_designation = mysqli_real_escape_string($conn, $_POST['inventors_designation'] ?? '');
        $inventors_emp_id = mysqli_real_escape_string($conn, $_POST['inventors_emp_id'] ?? '');
        $proposed_workplace = mysqli_real_escape_string($conn, $_POST['proposed_workplace'] ?? '');
        $des_of_invention = mysqli_real_escape_string($conn, $_POST['des_of_invention'] ?? '');
        $imple_status = mysqli_real_escape_string($conn, $_POST['imple_status'] ?? '');
        $replicate_eligibility = mysqli_real_escape_string($conn, $_POST['replicate_eligibility'] ?? '');
        $feedback = mysqli_real_escape_string($conn, $_POST['feedback'] ?? '');
        $service_link = mysqli_real_escape_string($conn, $_POST['service_link'] ?? '');
        $remarks = mysqli_real_escape_string($conn, $_POST['remarks'] ?? '');

        $sql = "UPDATE innovation_tbl SET 
                fiscal_year = '$fiscal_year',
                title_of_invention = '$title_of_invention',
                inventors_name = '$inventors_name',
                inventors_designation = '$inventors_designation',
                inventors_emp_id = '$inventors_emp_id',
                proposed_workplace = '$proposed_workplace',
                des_of_invention = '$des_of_invention',
                imple_status = '$imple_status',
                replicate_eligibility = '$replicate_eligibility',
                feedback = '$feedback',
                service_link = '$service_link',
                remarks = '$remarks'
                WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            $response = ["status" => "success", "message" => "Record updated successfully!"];
        } else {
            $response = ["status" => "error", "message" => "Database error: " . $conn->error];
        }
    }
    
    // DELETE record
    elseif ($mode === 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id'] ?? 0);
        
        $sql = "DELETE FROM innovation_tbl WHERE id = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            $response = ["status" => "success", "message" => "Record deleted successfully!"];
        } else {
            $response = ["status" => "error", "message" => "Database error: " . $conn->error];
        }
    }
}

echo json_encode($response);
$conn->close();
?>