<?php
include_once('../db/dbconn.php');

// Get data from the form
$subcadre = $_POST['subcadre'];
$cadre_id = $_POST['cadre_id'];

// SQL query to insert data into the "subcadre" table
$sql = "INSERT INTO sub_cadre (sub_cadre, cadre_id) VALUES ('$subcadre', $cadre_id)";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted into Subcadre table successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
