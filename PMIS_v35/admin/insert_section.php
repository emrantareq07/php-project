<?php
include_once('../db/dbconn.php');

// Get data from the form
$name = $_POST['name'];
$division_id = $_POST['division_id'];

// SQL query to insert data into the "subcadre" table
$sql = "INSERT INTO section (name, division_id) VALUES ('$name', $division_id)";

if ($conn->query($sql) === TRUE) {
    // echo "Data inserted into Subcadre table successfully";
    header('location:add_division.php?submitted=successfully');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
