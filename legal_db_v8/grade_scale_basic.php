<?php
include('db/db.php');

if (isset($_POST['court_typeid'])) {
    $court_typeid = $_POST['court_typeid'];
    $query = "SELECT * FROM court_division WHERE auto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $court_typeid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<option value=''>বিভাগ</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['court_division_name'] . "</option>";
        }
    }
}

if (isset($_POST['court_divisionid'])) {
    $court_divisionid = $_POST['court_divisionid'];
    $query = "SELECT * FROM case_type WHERE auto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $court_divisionid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<option value=''>মামলার ধরণ</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['type'] . "</option>";
        }
    }
}
?>
