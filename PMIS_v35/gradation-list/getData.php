<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pmis_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT basic_info.emp_id, basic_info.name, basic_info.designation, job_desc.dob AS job_dob, job_desc.doj, job_desc.prl, job_desc.place_of_posting
        FROM basic_info
        INNER JOIN job_desc ON basic_info.emp_id = job_desc.emp_id";

$result = $conn->query($sql);

// Prepare data for DataTables
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'emp_id' => $row['emp_id'],
        'name' => $row['name'],
        'designation' => $row['designation'],
        'dob' => $row['job_dob'], // Use the alias for clarity
        'doj' => $row['doj'],
        'prl' => $row['prl'],
        'place_of_posting' => $row['place_of_posting'],
    );
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode(array('data' => $data));
?>
