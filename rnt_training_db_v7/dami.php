<?php
// Database connection (update with your credentials)
$conn = new mysqli('localhost', 'root', '', 'pmis_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from job_desc
$sql = "SELECT * FROM job_desc";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emp_id = $row['emp_id'];
        $place_of_posting = $row['place_of_posting'];

        // Fetch name and designation from basic_info
        $sql2 = "SELECT name, designation FROM basic_info WHERE emp_id = '$emp_id'";
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
            $row1 = $result2->fetch_assoc();
            $name = $row1['name'];
            $designation = $row1['designation'];

            // Insert into dami_tbl
            $sql3 = "INSERT INTO dami_tbl (emp_id, name, designation, place_of_posting) 
                     VALUES ('$emp_id', '$name', '$designation', '$place_of_posting')";
            if ($conn->query($sql3) === TRUE) {
                echo "Record inserted successfully for emp_id: $emp_id<br>";
            } else {
                echo "Error inserting record for emp_id: $emp_id - " . $conn->error . "<br>";
            }
        } else {
            echo "No data found in basic_info for emp_id: $emp_id<br>";
        }
    }
} else {
    echo "No records found in job_desc";
}

// Close the connection
$conn->close();
?>
