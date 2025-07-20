

<?php
// $emp_id = '5620-0';

// // Explode the string based on the hyphen
// $parts = explode('-', $emp_id);

// // Get the first part of the exploded array
// $numeric_portion = $parts[0]+1;
// // Output the result
// echo $numeric_portion ."<br>";
// Separate the four digits
// $digit1 = substr($numeric_portion, 0, 1);
// $digit2 = substr($numeric_portion, 1, 1);
// $digit3 = substr($numeric_portion, 2, 1);
// $digit4 = substr($numeric_portion, 3, 1);

// // Output the result
// echo "Digit 1: $digit1<br>";
// echo "Digit 2: $digit2<br>";
// echo "Digit 3: $digit3<br>";
// echo "Digit 4: $digit4<br>";


// ---------------------------

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

$explodeEmpId1 = explode("-", $emp_id);
 

// $first_digits = array(); // Initialize an array to store first digits
// $second_digits = array();
// $third_digits = array();
// $fourth_digits = array();

// // Loop through the exploded values and store them in separate arrays
// for ($i = 0; $i < sizeof($explodeEmpId) - 1; $i++) {
//     $k = $explodeEmpId[$i];
//     //$k = (int)$k1 + 1;

//     // Output each part for debugging
//     echo "Emp id : ".$k . "<br>";
//     // Increment the entire value by 1
//     $k[] = (int)$k + 1;
//     // Extract the digits and store them in separate arrays
//     $first_digits[] = isset($k[0]) ? $k[0] : '';
//     $second_digits[] = isset($k[1]) ? $k[1] : '';
//     $third_digits[] = isset($k[2]) ? $k[2] : '';
//     $fourth_digits[] = isset($k[3]) ? $k[3] : '';


// Get the first part of the exploded array
$explodeEmpId = $explodeEmpId1[0]+1;
// Output the result
echo $explodeEmpId ."<br>";
// Separate the four digits
$first_digits = substr($explodeEmpId, 0, 1);
$second_digits = substr($explodeEmpId, 1, 1);
$third_digits = substr($explodeEmpId, 2, 1);
$fourth_digits = substr($explodeEmpId, 3, 1);
    // Output concatenated digits for debugging
    echo $first_digits. "<br>" . $second_digits . "<br>" . $third_digits . "<br>" . $fourth_digits. "<br>";
// }

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
// mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Bootstrap 5 Modal Example</title>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formatted String</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Your PHP code for generating the formatted string
                $formattedString = $first_digits[0] . $second_digits[0] . $third_digits[0] . $fourth_digits[0] . '-' . $last_digit;
                echo $formattedString;
                // Close the database connection if needed
                 mysqli_close($conn);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModalButton" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Use JavaScript to handle close button click
    document.addEventListener('DOMContentLoaded', function () {
        var closeModalButton = document.getElementById('closeModalButton');
        closeModalButton.addEventListener('click', function () {
            // Redirect to index.php after closing the modal
            window.location.href = 'utilities.php';
        });
        
        // Show the modal on page load
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
    });
</script>

</body>
</html>

