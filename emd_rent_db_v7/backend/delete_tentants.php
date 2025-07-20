<?php
// include_once '../db/database.php';
// $id=$_GET['id'];
// mysqli_query($conn,"delete from `company` where id='$id'");
// header('location:tenants_list.php?deleted=successfully');

?>

<?php
include_once '../db/database.php';

// Get the ID from the URL
$id = $_GET['id'];

// Retrieve the table name associated with the ID
$query = "SELECT table_name FROM `company` WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $table_name = $row['table_name'];

    // Delete the table if it exists
    $drop_table_query = "DROP TABLE IF EXISTS `$table_name`";
    if (mysqli_query($conn, $drop_table_query)) {
        // Table deleted successfully
        // Delete the row from the `company` table
        $delete_row_query = "DELETE FROM `company` WHERE id = '$id'";
        if (mysqli_query($conn, $delete_row_query)) {
            header('Location: tenants_list.php?deleted=successfully');
            exit();
        } else {
            echo "Error deleting row from company table: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting table `$table_name`: " . mysqli_error($conn);
    }
} else {
    echo "Record not found or invalid ID.";
}

// Close the database connection
mysqli_close($conn);
?>
