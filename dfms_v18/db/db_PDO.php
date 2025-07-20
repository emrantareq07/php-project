<?php
// Include this section at the beginning of your script to set up the PDO connection

// Database configuration
$host = 'localhost';
$db = 'dfms_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Data Source Name (DSN)
$conn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($conn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>