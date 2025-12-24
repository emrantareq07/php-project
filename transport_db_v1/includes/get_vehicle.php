<?php
 session_name('transport_db');
// include '../db/db_connection.php';

// if (isset($_GET['id'])) {
//     $id = $_GET['id'];
//     $stmt = $conn->prepare("SELECT * FROM vehicle_tbl WHERE id = ?");
//     $stmt->execute([$id]);
//     $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
//     echo json_encode($vehicle);
// }
?>
<?php
session_start();
include '../db/db_connection.php';

// Get vehicle ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM vehicle_tbl WHERE id = ?");
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($vehicle) {
        header('Content-Type: application/json');
        echo json_encode($vehicle);
    } else {
        echo json_encode(['error' => 'Vehicle not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid ID']);
}