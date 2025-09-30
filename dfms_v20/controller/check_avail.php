<?php
if(isset($_POST['date']) && $_POST['date']!="") {
    $table = $_POST['table1'];

    include('../db/db.php');

    if ($stmt = $conn->prepare("SELECT date FROM $table WHERE date = ?")) {
        $date = $_POST['date'];
        $stmt->bind_param('s', $date);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;
        if ($numRows > 0) {
            echo "<span class='text text-danger'> Date Already Exists!!!.</span>";
        } else {
            echo "<span class='text text-success'> Date Available.</span>";
        }
    }
    $conn->close();
}
?>

