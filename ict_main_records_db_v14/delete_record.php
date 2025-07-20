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

// Check if the request is POST and the ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize the ID input

    // Delete query
    $sql = "DELETE FROM records_tbl WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting record: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request. No ID provided"]);
}

// Close connection
$conn->close();
?>
