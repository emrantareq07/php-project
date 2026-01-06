<?php
include_once '../db/db.php';

if (!isset($_GET['table_name'])) {
    exit;
}

$table_name = $_GET['table_name'];
date_default_timezone_set("Asia/Dhaka");
$year_auto = date("Y");

$sql_d_number1 = "SELECT id FROM $table_name WHERE entry_date LIKE '$year_auto%'";
$result_d_number1 = mysqli_query($conn, $sql_d_number1);

if (mysqli_num_rows($result_d_number1) == 0) {
    $row_d_number_max = 1;
} else {
    $sql_d_number = "SELECT MAX(d_number) AS max_d_number FROM $table_name WHERE entry_date LIKE '$year_auto%'";
    $result_d_number = mysqli_query($conn, $sql_d_number);
    $row_d_number = mysqli_fetch_array($result_d_number);
    $row_d_number_max = $row_d_number['max_d_number'] + 1;
}

// Convert English to Bangla number
function englishToBanglaNumber($number) {
    $eng = range(0, 9);
    $bng = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return str_replace($eng, $bng, $number);
}

echo englishToBanglaNumber($row_d_number_max);
?>
