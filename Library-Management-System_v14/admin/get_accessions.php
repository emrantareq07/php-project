<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

header('Content-Type: application/json');

if (!isset($_GET['book_id'])) {
    echo json_encode(['error' => 'Book ID not provided']);
    exit;
}

$bookId = $_GET['book_id'];
$sql = "SELECT accession_no FROM tblbooks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

$accessions = [];
while ($row = $result->fetch_assoc()) {
    $accessions[] = $row['accession_no'];
}

if (empty($accessions)) {
    echo json_encode(['error' => 'No accession numbers found for this book']);
    exit;
}

echo json_encode($accessions);
?>