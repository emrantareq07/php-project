<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "rnt_training_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed');
}
?>