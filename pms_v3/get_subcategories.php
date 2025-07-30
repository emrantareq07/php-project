<?php
require_once("config/config.php");
require_once("db/db.php");

$db = new DB_con();

header('Content-Type: text/html');

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = mysqli_real_escape_string($db->dbh, $_GET['category']);
    $query = "SELECT DISTINCT sub_category FROM photo_gallary WHERE category = '$category' ORDER BY sub_category";
} else {
    $query = "SELECT DISTINCT sub_category FROM photo_gallary ORDER BY sub_category";
}

$result = $db->runBaseQuery($query);

$options = '';
foreach ($result as $row) {
    $options .= '<option value="' . htmlspecialchars($row['sub_category']) . '">' . htmlspecialchars($row['sub_category']) . '</option>';
}

echo $options;
?>