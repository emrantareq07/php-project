<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "ict_main_records_db"; // Replace with your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}
?>