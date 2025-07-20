<!DOCTYPE html>
<html>
<head>
    <title>Subcadre Data Insert Form</title>
</head>
<body>
    <h2>Insert Data into Subcadre Table</h2>
    <form action="insert_subcadre.php" method="post">
        Subcadre Name: <input type="text" name="subcadre" required><br><br>
        Cadre: 
        <select name="cadre_id" required>
            <?php
            include_once('../db/dbconn.php');
            // $servername = "your_servername";
            // $username = "your_username";
            // $password = "your_password";
            // $dbname = "your_database_name";

            // // Create a connection to the database
            // $conn = new mysqli($servername, $username, $password, $dbname);

            // // Check the connection
            // if ($conn->connect_error) {
            //     die("Connection failed: " . $conn->connect_error);
            // }

            // Fetch the cadre values from the database
            $sql = "SELECT id, cadre FROM cadre";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['cadre'] . "</option>";
                }
            } else {
                echo "<option value=''>No cadre available</option>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </select><br><br>
        <input type="submit" value="Insert Data">
    </form>
</body>
</html>
