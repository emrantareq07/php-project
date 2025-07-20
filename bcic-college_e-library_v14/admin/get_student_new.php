<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['studentid'])) {
    $studentid = mysqli_real_escape_string($conn, $_POST['studentid']);

    $sql = "SELECT FullName, std_class, std_section, std_group, std_session, MobileNumber, Status,Image 
            FROM tblstudents 
            WHERE StudentId = '$studentid'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo '<div class="d-flex align-items-start gap-4">';

    // Left side: Image
    echo '<div class="mt-3">';
    echo '<img src="' . htmlspecialchars($row['Image']) . '" alt="Student Image" class="img-thumbnail" style="max-width:100px; max-height:100px;">';
    echo '</div>';

    // Right side: Student Info
    echo '<div class="mt-3">';
    echo "<strong>Name:</strong> " . htmlspecialchars($row['FullName']) . "<br>";
    echo "<strong>Class:</strong> " . htmlspecialchars($row['std_class']) . "<br>";
    echo "<strong>Section:</strong> " . htmlspecialchars($row['std_section']) . "<br>";
    echo "<strong>Group:</strong> " . htmlspecialchars($row['std_group']) . "<br>";
    echo "<strong>Session:</strong> " . htmlspecialchars($row['std_session']) . "<br>";
    echo "<strong>Mobile:</strong> " . htmlspecialchars($row['MobileNumber']) . "<br>";
    echo "<strong>Status:</strong> " . ($row['Status'] == 1 ? 'Active' : 'Inactive');
    echo '</div>';

echo '</div>';

    } else {
        //echo "<span style='color:red;'></span>";
    }
}
?>
