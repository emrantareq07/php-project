<?php
// Connect to the database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'pmis_db';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Get values from POST data
$cadre_id = $_POST['cadre_id'];
$sub_cadre_id = $_POST['sub_cadre_id'];
$new_subcategory = $_POST['new_subcategory'];

// Update subcategory in the database
$query = "UPDATE sub_cadre SET sub_cadre = '$new_subcategory' WHERE id = '$sub_cadre_id' AND cadre_id = '$cadre_id'";
$result = mysqli_query($conn, $query);

if($result) {
  echo 'Subcategory updated successfully';
} else {
  echo 'Error updating subcategory: ' . mysqli_error($conn);
}
?>
