<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $entry_date = $_POST['entry_date'];
    $recipient = $_POST['recipient'];
    $immediate_sender_office = $_POST['immediate_sender_office'];
    $d_number = $_POST['d_number'];
    $attention = $_POST['attention'];
    $ref_number = $_POST['ref_number'];
    $send_date = $_POST['send_date'];
    $sender = $_POST['sender'];
    $div_dept_office = $_POST['div_dept_office'];
    $subject = $_POST['subject'];
    $destination = $_POST['destination'];
    $destination_drop = $_POST['destination_drop'];
    $distribution_date = $_POST['distribution_date'];
    $chairman_note = $_POST['chairman_note'];
    $comments = $_POST['comments'];
    $medium = $_POST['medium'];
    $status = $_POST['status'];
    $time = $_POST['time'];
    $modified = $_POST['modified'];
    $created_at = $_POST['created_at'];

    $sql = "UPDATE chairman SET 
        entry_date='$entry_date', recipient='$recipient', immediate_sender_office='$immediate_sender_office', 
        d_number='$d_number', attention='$attention', ref_number='$ref_number', send_date='$send_date', 
        sender='$sender', div_dept_office='$div_dept_office', subject='$subject', 
        destination='$destination', destination_drop='$destination_drop',
        distribution_date='$distribution_date', chairman_note='$chairman_note',
        comments='$comments', medium='$medium', status='$status',
        time='$time', modified='$modified', created_at='$created_at'
        WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 1, 'message' => 'Entry updated successfully']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Error: ' . mysqli_error($conn)]);
    }
}
