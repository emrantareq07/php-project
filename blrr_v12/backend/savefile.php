<?php
session_name('blrr');
session_start();
//$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$table_name=$_SESSION['table_name'];
$immediate_sender_office=$_SESSION['office'];

include_once 'function.php';
include_once '../db/database.php';

function banglaToEnglishNumber($banglaNumber) {
    // Define arrays for Bengali to English numeral conversion
    $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    // Replace Bengali numerals with English numerals
    $englishNumber = str_replace($banglaDigits, $englishDigits, $banglaNumber);
    return $englishNumber;
}



if(count($_POST) > 0){
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] == 1){

        $table = "chairmanfile";

        $entry_date   = mysqli_real_escape_string($conn, $_POST['entry_date'] ?? '');
        $recipient    = mysqli_real_escape_string($conn, $_POST['recipient'] ?? '');
        $d_number     = mysqli_real_escape_string($conn, banglaToEnglishNumber($_POST['d_number'] ?? ''));
        $send_date    = mysqli_real_escape_string($conn, $_POST['send_date'] ?? '');
        $subject      = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
        $destinations = mysqli_real_escape_string($conn, $_POST['selected_destinationsfile'] ?? '');
        $comments     = mysqli_real_escape_string($conn, $_POST['comments'] ?? '');
        $status       = '';

        if(empty($recipient) || empty($d_number) || empty($send_date) || empty($subject)){
            echo json_encode(["statusCode" => 201, "error" => "Required fields are missing."]);
            exit;
        }

        $sql = "INSERT INTO `$table` 
                (`entry_date`, `recipient`, `d_number`, `send_date`, `subject`, `destination_dropfile`, `comments`, `status`)
                VALUES
                ('$entry_date', '$recipient', '$d_number', '$send_date', '$subject', '$destinations', '$comments', '$status')";

        if(mysqli_query($conn, $sql)){
            echo json_encode(["statusCode" => 200]);
        } else {
            echo json_encode(["statusCode" => 201, "error" => mysqli_error($conn)]);
        }

        mysqli_close($conn);
    }
}









if(count($_POST) > 0) {
    if($_POST['type'] == 2) {
        $id = $_POST['id'];  // Make sure you capture the ID
        $entry_date = $_POST['entry_date'];
        $recipient = $_POST['recipient'];
        $d_number = $_POST['d_number'];
        $attention = $_POST['attention'];
        $ref_number = $_POST['ref_number'];
        $send_date = $_POST['send_date'];
        $sender = $_POST['sender'];
        $div_dept_office = $_POST['div_dept_office'];
        // $subject=$_POST['subject'];
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $chairman_note = mysqli_real_escape_string($conn, $_POST['chairman_note']);
        $comments = mysqli_real_escape_string($conn, $_POST['comments']);
        $medium = $_POST['medium'];
        $destination=$_POST['destination'];
        $distribution_date=$_POST['distribution_date'];
        $destination_drop=$_POST['destination_drop'];


        $sql = "UPDATE `$table_name` SET 
            `entry_date` = '$entry_date',
            `recipient` = '$recipient',
            `d_number` = '$d_number',
            `attention` = '$attention',
            `ref_number` = '$ref_number',
            `send_date` = '$send_date',
            `sender` = '$sender',
            `div_dept_office` = '$div_dept_office',
            `subject` = '$subject',
            `chairman_note` = '$chairman_note',
            `comments` = '$comments',
            `medium` = '$medium',destination='$destination',destination_drop='$destination_drop',distribution_date='$distribution_date' WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(array("statusCode" => 200));
        } else {
            echo json_encode(array("statusCode" => 201, "error" => mysqli_error($conn)));
        }
        mysqli_close($conn);
    }
}


if(count($_POST)>0){
    if($_POST['type']==3){
        $id=$_POST['id'];
        $sql = "DELETE FROM `$$table_name` WHERE id=$id ";
        if (mysqli_query($conn, $sql)) {
            echo $id;
        } 
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}
if(count($_POST)>0){
    if($_POST['type']==4){
        $id=$_POST['id'];
        $sql = "DELETE FROM $$table_name WHERE id in ($id)";
        if (mysqli_query($conn, $sql)) {
            echo $id;
        } 
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}

?>