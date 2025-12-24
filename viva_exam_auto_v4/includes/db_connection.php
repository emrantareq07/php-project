<?php
// Database connection settings
$host     = "localhost";
$username = "root";      // your MySQL username
$password = "";          // your MySQL password
$database = "viva_exam_db"; // your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set UTF-8 encoding (important for Bangla)
$conn->set_charset("utf8mb4");
?>
