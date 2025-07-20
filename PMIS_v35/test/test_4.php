<?php
// Database configuration
include('../db/db.php');

// Query to retrieve data from the database
$query = "SELECT * FROM basic_info";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Example</title>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <!--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
</head>
<body>


<?php

// Create a table with data
echo "<table class='table table-bordered table-hovered table-striped text-center'>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Designation</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['designation']}</td>
        </tr>";
}

echo "</table>";

// Close the database connection
mysqli_close($conn);
?>
</body>
</html>