<?php
header('Content-Type: application/json');
date_default_timezone_set("Asia/Dhaka");   //India time (GMT+6)
session_name('blrr');
session_start();

include '../db/db.php';
include_once 'function.php'; // Ensure table_name_find() exists

error_reporting(0);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $table_name = $_POST['table_name'] ?? 'chairman';
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 0, 'message' => 'Invalid ID']);
        exit;
    }

    // Sanitize inputs
    $entry_date = mysqli_real_escape_string($conn, $_POST['entry_date'] ?? '');
    $recipient = mysqli_real_escape_string($conn, $_POST['recipient'] ?? '');
    $d_number = mysqli_real_escape_string($conn, $_POST['d_number'] ?? '');
    $attention = mysqli_real_escape_string($conn, $_POST['attention'] ?? '');
    $ref_number = mysqli_real_escape_string($conn, $_POST['ref_number'] ?? '');
    $send_date = mysqli_real_escape_string($conn, $_POST['send_date'] ?? '');
    $sender = mysqli_real_escape_string($conn, $_POST['sender'] ?? '');
    $div_dept_office = mysqli_real_escape_string($conn, $_POST['div_dept_office'] ?? '');
    $subject = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $medium = mysqli_real_escape_string($conn, $_POST['medium'] ?? '');
    $distribution_date = mysqli_real_escape_string($conn, $_POST['distribution_date'] ?? '');
    $chairman_note = mysqli_real_escape_string($conn, $_POST['chairman_note'] ?? '');
    $comments = mysqli_real_escape_string($conn, $_POST['comments'] ?? '');
    $destination = mysqli_real_escape_string($conn, $_POST['destination'] ?? '');
    $destination_drop = isset($_POST['selected_destinations']) ? mysqli_real_escape_string($conn, $_POST['selected_destinations']) : '';
    $modified = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($recipient) || empty($sender) || empty($subject)) {
        echo json_encode(['status' => 0, 'message' => 'Required fields are missing']);
        exit;
    }

    // Generate unique_id if needed
    $unique_id = $table_name . $id;
    $immediate_sender_office = $_SESSION['office'] ?? '';

   // Remove previous destination entries from other tables
    $sql_old_dest = "SELECT destination_drop FROM $table_name WHERE id='$id'";
    $result_old_dest = mysqli_query($conn, $sql_old_dest);
    if ($row_old = mysqli_fetch_assoc($result_old_dest)) {
        $old_destinations = explode(',', $row_old['destination_drop']);
        foreach ($old_destinations as $old_dest) {
            $old_dest = trim($old_dest);
            $old_table = table_name_find($old_dest);
            if ($old_table) {
                $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$old_table'");
                if (mysqli_num_rows($check_table) == 1) {
                    mysqli_query($conn, "DELETE FROM `$old_table` WHERE unique_id='$unique_id'");
                }
            }
        }
    }


    // Update main table
    $sql_update = "UPDATE $table_name SET 
        entry_date='$entry_date',
        recipient='$recipient',
        d_number='$d_number',
        attention='$attention',
        ref_number='$ref_number',
        send_date='$send_date',
        sender='$sender',
        div_dept_office='$div_dept_office',
        subject='$subject',
        medium='$medium',
        distribution_date='$distribution_date',
        chairman_note='$chairman_note',
        comments='$comments',
        destination='$destination',
        destination_drop='$destination_drop',
        modified='$modified'
        WHERE id='$id'";

    if (!mysqli_query($conn, $sql_update)) {
        echo json_encode(['status' => 0, 'message' => 'Database error: ' . mysqli_error($conn)]);
        exit;
    }

    // Insert into destination tables
    // Now $destination_drop may be empty; that's fine — we will not insert anything
    if (!empty($destination_drop)) {
        $destinations = explode(',', $destination_drop);
        foreach ($destinations as $dest) {
            $dest = trim($dest);
            $dest_table = table_name_find($dest);
            if ($dest_table) {
                $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$dest_table'");
                if (mysqli_num_rows($check_table) == 1) {
                    $sql_insert = "INSERT INTO `$dest_table`(`unique_id`, `recipient`, `immediate_sender_office`, `ref_number`, `send_date`, `sender`, `div_dept_office`, `subject`, `chairman_note`, `comments`, `medium`) 
                        VALUES ('$unique_id','$dest','$immediate_sender_office','$ref_number','$send_date','$sender','$div_dept_office','$subject','$chairman_note','$comments','$medium')";
                    mysqli_query($conn, $sql_insert);
                }
            }
        }
    }

    echo json_encode(['status' => 1, 'message' => 'Entry updated successfully']);
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid request method']);
}
?>
