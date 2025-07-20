<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['studentid'])) {
    $studentid = mysqli_real_escape_string($conn, $_POST['studentid']);

    $sql = "SELECT *
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
    echo "<strong>Designation:</strong> " . htmlspecialchars($row['designation']) . "<br>";
    echo "<strong>Division:</strong> " . htmlspecialchars($row['division']) . "<br>";
    echo "<strong>Section:</strong> " . htmlspecialchars($row['section']) . "<br>";
    echo "<strong>Email:</strong> " . htmlspecialchars($row['EmailId']) . "<br>";
    echo "<strong>Mobile:</strong> " . htmlspecialchars($row['MobileNumber']) . "<br>";
   echo "<strong class='text-success'>Status: " . ($row['Status'] == 1 ? 'Active' : 'Inactive') . "</strong>";
    // $statusText = $row['Status'] == 1 ? 'Active' : 'Inactive';
    // $statusClass = $row['Status'] == 1 ? 'text-success' : 'text-danger';
    // echo "<strong class='$statusClass'>Status: $statusText</strong>";
    echo '</div>';

echo '</div>';

    } else {
        //echo "<span style='color:red;'></span>";
    }
}
?>
