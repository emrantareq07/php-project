<?php
// Set headers first to prevent any output
header('Content-Type: application/json');
date_default_timezone_set("Asia/Dhaka");   //India time (GMT+6)
session_name('blrr');
session_start();

include '../db/db.php';
include_once 'function.php'; // Add this line

$user_type = $_SESSION['user_type'] ?? '';
$office = $_SESSION['office'] ?? '';
$table_name = $_SESSION['table_name'] ?? 'chairman';
$immediate_sender_office = $_SESSION['office'] ?? '';

// Enable error reporting for debugging
error_reporting(0);
ini_set('display_errors', 0);

function banglaToEnglishNumber($banglaNumber) {
    $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($banglaDigits, $englishDigits, $banglaNumber);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_name = $_POST['table_name'] ?? $table_name;

    if($table_name != 'chairman'){
        $entry_date = date("Y-m-d");
    } else {
        $entry_date = $_POST['entry_date'] ?? date("Y-m-d");
    }
    
    // Get and sanitize all inputs
    $unique_id = uniqid();
    $recipient = mysqli_real_escape_string($conn, $_POST['recipient'] ?? '');
    $d_number = mysqli_real_escape_string($conn, $_POST['d_number'] ?? '');
    $attention = mysqli_real_escape_string($conn, $_POST['attention'] ?? '');
    $ref_number = mysqli_real_escape_string($conn, $_POST['ref_number'] ?? '');
    $send_date = mysqli_real_escape_string($conn, $_POST['send_date'] ?? '');
    $sender = mysqli_real_escape_string($conn, $_POST['sender'] ?? '');
    $div_dept_office = mysqli_real_escape_string($conn, $_POST['div_dept_office'] ?? '');
    $subject = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $medium = mysqli_real_escape_string($conn, $_POST['medium'] ?? '');
    $destination = mysqli_real_escape_string($conn, $_POST['destination'] ?? '');
    $destination_drop = mysqli_real_escape_string($conn, $_POST['selected_destinations'] ?? ''); // Fixed variable name
    $distribution_date = mysqli_real_escape_string($conn, $_POST['distribution_date'] ?? '');
    $chairman_note = mysqli_real_escape_string($conn, $_POST['chairman_note'] ?? '');
    $comments = mysqli_real_escape_string($conn, $_POST['comments'] ?? '');
    $status = ''; // Set default status
    $created_at = date('Y-m-d H:i:s');

    // Convert Bengali numbers to English for d_number
    $d_number_english = banglaToEnglishNumber($d_number);

    // Validate required fields
    if (empty($recipient) || empty($sender) || empty($d_number_english) || empty($medium) || empty($send_date) || empty($subject)) {
        echo json_encode(['status' => 0, 'message' => 'Required fields are missing']);
        exit;
    }

    // Fix SQL query - added missing unique_id and proper escaping
    $sql = "INSERT INTO `$table_name`(`unique_id`, `entry_date`, `recipient`, `d_number`, `attention`, `ref_number`, `send_date`, `sender`, `div_dept_office`, `subject`, `destination`,`destination_drop`, `distribution_date`, `chairman_note`, `comments`, `medium`, `status`, `created_at`) 
            VALUES ('$unique_id', '$entry_date', '$recipient', '$d_number_english', '$attention', '$ref_number', '$send_date', '$sender', '$div_dept_office', '$subject', '$destination','$destination_drop', '$distribution_date', '$chairman_note', '$comments', '$medium', '$status', '$created_at')";

    if (mysqli_query($conn, $sql)) {
        $record_id = mysqli_insert_id($conn);
        $unique_id = $table_name . $record_id;

        // Update the unique_id with the actual record ID
        $update_sql = "UPDATE `$table_name` SET `unique_id` = '$unique_id' WHERE `id` = '$record_id'";
        mysqli_query($conn, $update_sql);

        if ($destination_drop) {
            $destination_array = explode(",", $destination_drop);
            foreach ($destination_array as $dest_recipient) {
                $dest_recipient = trim($dest_recipient);
                if (!empty($dest_recipient)) {
                    $table_name1 = table_name_find($dest_recipient);        

                    // Check if the table exists in the database
                    $check_table_query = "SHOW TABLES LIKE '$table_name1'";
                    $result_table_name = mysqli_query($conn, $check_table_query);

                    if(mysqli_num_rows($result_table_name) == 1){
                        // Prepare and execute the SQL query to insert
                        $sql_for_dir = "INSERT INTO `$table_name1`(`unique_id`, `recipient`, `immediate_sender_office`, `ref_number`, `send_date`, `sender`, `div_dept_office`, `subject`, `chairman_note`, `comments`, `medium`, `created_at`) 
                                VALUES ('$unique_id', '$dest_recipient', '$immediate_sender_office', '$ref_number', '$send_date', '$sender', '$div_dept_office', '$subject', '$chairman_note', '$comments', '$medium', '$created_at')";
                        mysqli_query($conn, $sql_for_dir);
                    }
                }
            }
        }

        echo json_encode(['status' => 1, 'message' => 'Entry added successfully']);
    } else {
        $error = mysqli_error($conn);
        // Log the error for debugging
        error_log("Database error in save.php: " . $error);
        echo json_encode(['status' => 0, 'message' => 'Database error: ' . $error]);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>