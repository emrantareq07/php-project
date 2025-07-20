<?php
// Database configuration
include('../db/db.php');

// Query to retrieve data from the database
$sql = "SELECT * FROM division";
$result = $conn->query($sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Dropdown with Search</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <form>
        <input type="text" id="searchInput" placeholder="Search for a country">
        <select id="countryDropdown">
            <option value="">Select a country</option>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['division'] . '</option>';
            }
            ?>
        </select>
    </form>
</body>
</html>

<script type="text/javascript">

$(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        var searchTerm = $(this).val().toLowerCase();
        $("#countryDropdown option").each(function() {
            if ($(this).text().toLowerCase().indexOf(searchTerm) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});
</script>
