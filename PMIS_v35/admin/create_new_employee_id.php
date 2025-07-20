<?php
include('../db/db.php');

$sql = "SELECT MAX(emp_id) AS emp_id FROM basic_info";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// Check if a record is found before extracting emp_id
if ($row && isset($row['emp_id'])) {
    $emp_id = $row['emp_id'];

} else {
    $emp_id = '5620-0';
    echo "No record found in the database. Using default emp_id: $emp_id<br>";
}

$explodeEmpId = explode("-", $emp_id);
 

$first_digits = array(); // Initialize an array to store first digits
$second_digits = array();
$third_digits = array();
$fourth_digits = array();

// Loop through the exploded values and store them in separate arrays
for ($i = 0; $i < sizeof($explodeEmpId) - 1; $i++) {
    $k = $explodeEmpId[$i];
    //$k = (int)$k1 + 1;

    // Output each part for debugging
    echo "Emp id : ".$k . "<br>";
    // Increment the entire value by 1
    // $k[] = (int)$k + 1;
    // Extract the digits and store them in separate arrays
    $first_digits[] = isset($k[0]) ? $k[0] : '';
    $second_digits[] = isset($k[1]) ? $k[1] : '';
    $third_digits[] = isset($k[2]) ? $k[2] : '';
    $fourth_digits[] = isset($k[3]) ? $k[3] : '';

    // Output concatenated digits for debugging
    echo $first_digits[$i] . "<br>" . $second_digits[$i] . "<br>" . $third_digits[$i] . "<br>" . $fourth_digits[$i] . "<br>";
}

// 2nd step
$first_digit_2nd_step = (int)$first_digits[0]; // Assuming you want the first element
echo $first_digit_2nd_step . "<br>";

$second_digit_2nd_step = (int)$second_digits[0] * 2; // Assuming you want the first element
echo $second_digit_2nd_step . "<br>";

$third_digit_2nd_step = (int)$third_digits[0]; // Assuming you want the first element
echo $third_digit_2nd_step . "<br>";

$fourth_digit_2nd_step = (int)$fourth_digits[0] * 2; // Assuming you want the first element
echo $fourth_digit_2nd_step . "<br>";

function sum($num) {
    $sum = 0;
    for ($i = 0; $i < strlen($num); $i++) {
        $sum += $num[$i];
    }
    return $sum;
}

$num = "$second_digit_2nd_step";
$sum_2nd_digit = sum($num);

function sum1($num1) {
    $sum = 0;
    for ($i = 0; $i < strlen($num1); $i++) {
        $sum += $num1[$i];
    }
    return $sum;
}

$num1 = "$fourth_digit_2nd_step";
$sum_4th_digit = sum1($num1);

$result = (int)$first_digit_2nd_step + $sum_2nd_digit + (int)$third_digit_2nd_step + $sum_4th_digit;

if ($result <= 10) {
    $last_digit = 10 - $result;
} elseif ($result <= 20) {
    $last_digit = 20 - $result;
} elseif ($result <= 30) {
    $last_digit = 30 - $result;
} elseif ($result <= 40) {
    $last_digit = 40 - $result;
}

echo $first_digits[0] . $second_digits[0] . $third_digits[0] . $fourth_digits[0] . '-' . $last_digit;

// Close the database connection if needed
mysqli_close($conn);
?>
