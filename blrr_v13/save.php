<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $unique_id = uniqid();
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

    $sql = "INSERT INTO chairman 
        (unique_id, entry_date, recipient, immediate_sender_office, d_number, attention, ref_number, send_date, sender, div_dept_office, subject, destination, destination_drop, distribution_date, chairman_note, comments, medium, status, time, modified, created_at)
        VALUES 
        ('$unique_id','$entry_date','$recipient','$immediate_sender_office','$d_number','$attention','$ref_number','$send_date','$sender','$div_dept_office','$subject','$destination','$destination_drop','$distribution_date','$chairman_note','$comments','$medium','$status','$time','$modified','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 1, 'message' => 'Entry added successfully']);
    } else {
        echo json_encode(['status' => 0, 'message' => 'Error: ' . mysqli_error($conn)]);
    }
}
