<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("No prescription ID provided for deletion.");
    }

    if (!isset($_GET['emp_id']) || empty($_GET['emp_id'])) {
        throw new Exception("No employee ID provided for redirection.");
    }

    $prescription_id = $_GET['id'];
    $emp_id = $_GET['emp_id'];

    // Begin transaction
    $conn->beginTransaction();

    try {
        // First delete prescription items
        $stmt = $conn->prepare("DELETE FROM prescription_items WHERE prescription_id = :id");
        $stmt->execute([':id' => $prescription_id]);

        // Then delete the prescription header
        $stmt = $conn->prepare("DELETE FROM prescription_tbl WHERE id = :id");
        $stmt->execute([':id' => $prescription_id]);

        $conn->commit();
        
        $_SESSION['success'] = "Prescription deleted successfully!";
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header("Location: prescription_list.php?id=" . urlencode($emp_id));
exit();
?>