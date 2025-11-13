<?php
include('../db/db.php');

if (isset($_POST['committe_name'])) {
    $committe_name = mysqli_real_escape_string($conn, $_POST['committe_name']);
    $query = "SELECT DISTINCT designation FROM candidates_tbl WHERE committe_name = '$committe_name'";
    $result = mysqli_query($conn, $query);

    // echo "<option value=''>Select Designation</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['designation']) . "'>" . htmlspecialchars($row['designation']) . "</option>";
    }
}
?>