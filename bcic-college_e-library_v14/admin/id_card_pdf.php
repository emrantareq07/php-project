<?php
session_start();
include('includes/config.php');

// Get 'id' safely
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare query
$sql = "SELECT StudentId, FullName, std_class, std_section, std_group, std_session, Image FROM tblstudents WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $students = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = [
            "id" => $row['StudentId'],
            "name" => $row['FullName'],
            "designation" => "Class: " . $row['std_class'] .
                             ", Section: " . $row['std_section'] .
                             ", Group: " . $row['std_group'],
            "session" =>$row['std_session'], // <-- Session instead of address
            "phone" => "",
            "image" => !empty($row['Image']) ? $row['Image'] : 'https://www.pngitem.com/pimgs/m/30-307416_profile-icon-png-image-free-download-searchpng-employee.png'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($students);

    mysqli_stmt_close($stmt);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => "Query preparation failed: " . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>
