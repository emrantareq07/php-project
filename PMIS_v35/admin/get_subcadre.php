<?php
// Connect to the database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'pmis_db';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Fetch subcategories
$cadre_id = $_POST['cadre_id'];
// $query = "SELECT * FROM sub_cadre WHERE cadre_id = '$cadre_id'";
$query = "SELECT id, sub_cadre FROM sub_cadre WHERE cadre_id = '$cadre_id'";
$result = mysqli_query($conn, $query);

// Generate options for subcategory dropdown list and select current value
$options = '<option value="">Select Sub-Cadre</option>';
$current_value = $_POST['current_value'];
while($row = mysqli_fetch_assoc($result)) {
  $selected = ($row['id'] == $current_value) ? 'selected' : '';
  $options .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['sub_cadre'].'</option>';
}

echo $options;

// Generate options for subcategory dropdown list
// $options = '<option value="">Select subcategory</option>';
// while($row = mysqli_fetch_assoc($result)) {
//   $options .= '<option value="'.$row['id'].'">'.$row['sub_cadre'].'</option>';
// }

// echo $options;
?>
