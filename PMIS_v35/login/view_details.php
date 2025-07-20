<?php
include('db.php');

// Assuming you have a "users2" table with columns: emp_id, name, designation, department, section, password, image, place_of_posting
$sql = "SELECT * FROM users2";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Access individual columns using $row['column_name']
        $emp_id = $row['emp_id'];
        $name = $row['name'];
        $designation = $row['designation'];
        $department = $row['department'];
        $section = $row['section'];
        $place_of_posting = $row['place_of_posting'];
        $image = $row['image'];

        // Output the information
        echo "<div>";
        echo "<h2>Employee ID: $emp_id</h2>";
        echo "<p>Name: $name</p>";
        echo "<p>Designation: $designation</p>";
        echo "<p>Department: $department</p>";
        echo "<p>Section: $section</p>";
        echo "<p>Place of Posting: $place_of_posting</p>";
        echo "<img src='$image' alt='Employee Image'>";
        echo "</div>";
    }
} else {
    echo "No records found.";
}

mysqli_close($connection);
?>
