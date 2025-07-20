<?php
include_once('../db/dbconn.php');

// Get data from the form
$cadre = $_POST['cadre'];

// SQL query to insert data into the "cadre" table
$sql = "INSERT INTO cadre (cadre) VALUES ('$cadre')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted into Cadre table successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
