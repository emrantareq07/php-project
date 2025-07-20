<?php
session_name('blrr');
include_once '../db/database.php';
include_once 'function.php';
session_start();
$table_name=$_SESSION['table_name'];
$immediate_sender_office=$_SESSION['office'];
// if(isset($_POST['submit']) && isset($_SESSION['id'])) {
//     $id = $_SESSION['id'];
function banglaToEnglishNumber($banglaNumber) {
    // Define arrays for Bengali to English numeral conversion
    $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    // Replace Bengali numerals with English numerals
    $englishNumber = str_replace($banglaDigits, $englishDigits, $banglaNumber);
    return $englishNumber;
}

if (isset($_POST['submit'])) {
    $record_id = $_POST['record_id'];
    $entry_date = $_POST['entry_date'];
    $recipient = $_POST['recipient'];
    $d_number=banglaToEnglishNumber($_POST['d_number']);
    $attention = $_POST['attention'];
    $ref_number = $_POST['ref_number'];
    $send_date = $_POST['send_date'];
    $sender = $_POST['sender'];
    $div_dept_office = $_POST['div_dept_office'];
    // $subject = $_POST['subject'];
    // $chairman_note = $_POST['chairman_note'];
    // $comments = $_POST['comments'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $chairman_note = mysqli_real_escape_string($conn, $_POST['chairman_note']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);
    $medium = $_POST['medium'];
    $destination=$_POST['destination'];
    $distribution_date=$_POST['distribution_date'];

    $unique_id=$table_name.$record_id;

    // Fetch the selected EMP IDs from the form
    // $destination_drops = $_POST['destination_drop'];
    // if($destination_drops){
    //       // Convert the array of EMP IDs into a single string separated by commas
    // $destination_drop = implode(',', $destination_drops);  
    // }
      // Ensure `destination_drop` is always defined
    $destination_drops = isset($_POST['destination_drop']) ? $_POST['destination_drop'] : [];
    $destination_drop = !empty($destination_drops) ? implode(',', $destination_drops) : "";

    // Check if the record exists in the table
        $sql_dest_drop = "SELECT destination_drop FROM $table_name WHERE id='$record_id'";  
        $result_dest_drop = mysqli_query($conn, $sql_dest_drop);
        $row_dest_drop = mysqli_fetch_assoc($result_dest_drop);
        $destination_drop1 = $row_dest_drop['destination_drop'];

        if($destination_drop1){
        $destination_array1 = explode(",", $destination_drop1); // Correct variable name
        
        foreach ($destination_array1 as $dest_drop) {
            $table_name2= table_name_find($dest_drop);

        // Check if the table already exists in the database
        $check_table_query = "SHOW TABLES LIKE '$table_name2'";
        $result_table_name = mysqli_query($conn, $check_table_query);

        if(mysqli_num_rows($result_table_name) == 1){
                   // Check if the record exists in the table
        $sql_check = "SELECT * FROM `$table_name2` WHERE unique_id='$unique_id'";  // Enclose unique_id in quotes
        $result_check = mysqli_query($conn, $sql_check);

         if (mysqli_num_rows($result_check) > 0) {
        $sql_delete = "DELETE FROM `$table_name2` WHERE unique_id='$unique_id'";  // Enclose unique_id in quotes
        mysqli_query($conn, $sql_delete);
                }

             }

            }
        }
   
    // Update the record in the database
    // $sql = "UPDATE chairman SET destination_drop = '$destination_drop' WHERE id = $record_id";
    $sql = "UPDATE `$table_name` SET 
    `unique_id` = '$unique_id',
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
    `medium` = '$medium',destination='$destination',destination_drop='$destination_drop',distribution_date='$distribution_date' WHERE id = $record_id";

    if ($destination_drops) {
    $destination_array = explode(",", $destination_drop); // Correct variable name
    foreach ($destination_array as $recipient) {

         $table_name1= table_name_find($recipient);        

        // Check if the table already exists in the database
        $check_table_query = "SHOW TABLES LIKE '$table_name1'";
        $result_table_name = mysqli_query($conn, $check_table_query);

        if(mysqli_num_rows($result_table_name) == 1){

        // Prepare and execute the SQL query to insert
        $sql_for_dir = "INSERT INTO `$table_name1`(`unique_id`, `recipient`,`immediate_sender_office`, `ref_number`, `send_date`, `sender`, `div_dept_office`, `subject`, `chairman_note`, `comments`, `medium`) 
                VALUES ('$unique_id','$recipient','$immediate_sender_office','$ref_number','$send_date','$sender','$div_dept_office','$subject','$chairman_note','$comments','$medium')";
        mysqli_query($conn, $sql_for_dir);
        }
         
    }
}
 
    if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Data updated successfully!');
            window.location.href = 'show_all.php';
          </script>";
    // Alternatively, for a different redirect:
    // echo "<script>
    //         alert('Data updated successfully!');
    //         window.location.href = 'edit_legal_data.php?submitted=" . $_SESSION['id'] . "';
    //       </script>";
    exit();
} else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
