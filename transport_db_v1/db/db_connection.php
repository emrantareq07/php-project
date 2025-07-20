<?php
$host = 'localhost';
$dbname = 'transport_db';
$username = 'root'; // Default username for localhost
$password = '';     // Default password for localhost

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>