<?php
include 'db/db_connection.php';

// Create
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO vehicle_tbl (vehicle_status, reg_no, vehicle_type, vehicle_source, sourcing_buying_year, driven_km, user_name, user_designation, driver_name, driver_appt_type, yearofimpairment, causeofimpairment, repair_status, action_taken, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['vehicle_status'] ?? '', 
        $_POST['reg_no'] ?? '', 
        $_POST['vehicle_type'] ?? '', 
        $_POST['vehicle_source'] ?? '', 
        $_POST['sourcing_buying_year'] ?? '', 
        $_POST['driven_km'] ?? '', 
        $_POST['user_name'] ?? '', 
        $_POST['user_designation'] ?? '', 
        $_POST['driver_name'] ?? '', 
        $_POST['driver_appt_type'] ?? '', 
        $_POST['yearofimpairment'] ?? '', 
        $_POST['causeofimpairment'] ?? '', 
        $_POST['repair_status'] ?? '', 
        $_POST['action_taken'] ?? '', 
        $_POST['remarks'] ?? ''
    ]);
}

// Read
$stmt = $conn->prepare("SELECT * FROM vehicle_tbl");
$stmt->execute();
$vehicles = $stmt->fetchAll();

// // Update
// if (isset($_POST['update'])) {
//     $stmt = $conn->prepare("UPDATE vehicle_tbl SET vehicle_status=?, reg_no=?, vehicle_type=?, vehicle_source=?, sourcing_buying_year=?, driven_km=?, user_name=?, user_designation=?, driver_name=?, driver_appt_type=?, yearofimpairment=?, causeofimpairment=?, repair_status=?, action_taken=?, remarks=? WHERE id=?");
//     $stmt->execute([
//         $_POST['vehicle_status'], $_POST['reg_no'], $_POST['vehicle_type'], $_POST['vehicle_source'], $_POST['sourcing_buying_year'], $_POST['driven_km'], $_POST['user_name'], $_POST['user_designation'], $_POST['driver_name'], $_POST['driver_appt_type'], $_POST['yearofimpairment'], $_POST['causeofimpairment'], $_POST['repair_status'], $_POST['action_taken'], $_POST['remarks'], $_POST['id']
//     ]);
// }

// Update
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE vehicle_tbl SET vehicle_status=?, reg_no=?, vehicle_type=?, vehicle_source=?, sourcing_buying_year=?, driven_km=?, user_name=?, user_designation=?, driver_name=?, driver_appt_type=?, yearofimpairment=?, causeofimpairment=?, repair_status=?, action_taken=?, remarks=? WHERE id=?");
    // $stmt->execute([
    //     $_POST['vehicle_status'], $_POST['reg_no'], $_POST['vehicle_type'], $_POST['vehicle_source'], $_POST['sourcing_buying_year'], $_POST['driven_km'], $_POST['user_name'], $_POST['user_designation'], $_POST['driver_name'], $_POST['driver_appt_type'], $_POST['yearofimpairment'], $_POST['causeofimpairment'], $_POST['repair_status'], $_POST['action_taken'], $_POST['remarks'], $_POST['id']
    // ]);
    $stmt->execute([
        $_POST['vehicle_status'] ?? '', 
        $_POST['reg_no'] ?? '', 
        $_POST['vehicle_type'] ?? '', 
        $_POST['vehicle_source'] ?? '', 
        $_POST['sourcing_buying_year'] ?? '', 
        $_POST['driven_km'] ?? '', 
        $_POST['user_name'] ?? '', 
        $_POST['user_designation'] ?? '', 
        $_POST['driver_name'] ?? '', 
        $_POST['driver_appt_type'] ?? '', 
        $_POST['yearofimpairment'] ?? '', 
        $_POST['causeofimpairment'] ?? '', 
        $_POST['repair_status'] ?? '', 
        $_POST['action_taken'] ?? '', 
        $_POST['remarks'] ?? '',
        $_POST['id'] ?? 0  // Default to 0 if ID is missing (shouldn't happen)
    ]);
    header("Location: index.php"); // Redirect to avoid resubmission
    exit();
}
// Delete
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM vehicle_tbl WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php"); // Redirect to avoid resubmission
}
?>