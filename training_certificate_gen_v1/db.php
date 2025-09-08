<?php
// db.php (MySQLi version)

$host = "localhost";   // Database host
$user = "root";        // Database username
$pass = "";            // Database password
$db   = "training_certificate_gen_db"; // Change this to your actual database name

// Create MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to UTF-8 (good for Bengali data, etc.)
$conn->set_charset("utf8mb4");
?>
