<?php
$host = "localhost";   // Database host
$user = "root";        // Database username
$pass = "";            // Database password
$db   = "friendsforeve03"; // Database name

// Create MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 (good for Bengali data, etc.)
$conn->set_charset("utf8mb4");
