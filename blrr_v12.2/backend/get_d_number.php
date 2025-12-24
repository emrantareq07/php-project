<?php
session_name('blrr');
session_start();
$username = $_SESSION['username']; //chairman
$user_type = $_SESSION['user_type'];//admin
$office = $_SESSION['office'];
$table_name = $_SESSION['table_name'];
$office_title = $_SESSION['office_title'];

include_once '../db/db.php';

//$table_name = 'chairmanfile'; // replace with actual table name
$year_auto1 = $_POST['year_auto1'];



// Query for entries in the selected year
$sql_d_number1 = "SELECT id FROM $table_name WHERE entry_date LIKE '$year_auto1%'";
$result_d_number1 = mysqli_query($conn, $sql_d_number1);

if (mysqli_num_rows($result_d_number1) == 0) {
    $row_d_number_max = 1;
} else {
    $sql_d_number = "SELECT MAX(d_number) AS max_d_number FROM $table_name WHERE entry_date LIKE '$year_auto1%'";
    $result_d_number = mysqli_query($conn, $sql_d_number);
    $row_d_number = mysqli_fetch_array($result_d_number);
    $row_d_number_max = $row_d_number['max_d_number'] + 1;
}

// Convert to Bengali numerals
function englishToBanglaNumber($number) {
    $bn_digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return str_replace(range(0, 9), $bn_digits, $number);
}

echo englishToBanglaNumber($row_d_number_max);
?>