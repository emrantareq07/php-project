<?php
include('../db/db.php');

if (isset($_POST['grade_id'])) {
    $grade_id = $_POST['grade_id'];
    $query = "SELECT * FROM pay_scale_2015 WHERE grade_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $grade_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<option value=''>Select Scale</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['scale'] . "</option>";
        }
    }
}

if (isset($_POST['scale_id'])) {
    $scale_id = $_POST['scale_id'];
    $query = "SELECT * FROM basic WHERE scale_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $scale_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<option value=''>Select Basic</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['basic'] . "</option>";
        }
    }
}
?>
