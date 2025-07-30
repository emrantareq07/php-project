<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Dhaka');
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
}

// 1. Verify database connection
if (!$conn) {
    die("<p style='color:red;'>Database connection failed: " . mysqli_connect_error() . "</p>");
}

// Current date
$currentDate = date('Y-m-d');
echo "<h3>Overdue Book Notification System</h3>";
echo "<p>Current Date: $currentDate</p>";

// 2. Find overdue books with prepared statement
$sql_fetch = "SELECT ib.StudentId, ib.ReturnDate, ib.RetrunStatus, 
                     s.FullName, s.EmailId, s.MobileNumber,
                     b.book_name
              FROM tblissuedbookdetails ib
              JOIN tblstudents s ON ib.StudentId = s.StudentId
              JOIN tblbooks b ON ib.accession_no = b.accession_no
              WHERE ib.RetrunStatus = 0 
              AND STR_TO_DATE(ib.ReturnDate, '%Y-%m-%d') < STR_TO_DATE(?, '%Y-%m-%d')";

$stmt = mysqli_prepare($conn, $sql_fetch);
mysqli_stmt_bind_param($stmt, "s", $currentDate);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);

if (!$results) {
    die("<p style='color:red;'>Query failed: " . mysqli_error($conn) . "</p>");
}

$overdueCount = mysqli_num_rows($results);
echo "<p>Found $overdueCount overdue books</p>";

if ($overdueCount > 0) {
    // Prepare the insert statement once
    $insertStmt = mysqli_prepare($conn, 
        "INSERT INTO student_notifications 
        (student_id, message, is_read, created_at) 
        VALUES (?, ?, 0, NOW())");
    
    if (!$insertStmt) {
        die("<p style='color:red;'>Prepare failed: " . mysqli_error($conn) . "</p>");
    }

    $notificationCount = 0;
    
    while ($row = mysqli_fetch_assoc($results)) {
        $studentId = $row['StudentId'];
        $returnDate = $row['ReturnDate'];
        $bookName = $row['book_name'];
        
        // Create message - no need to escape since we're using prepared statement
        $message = "Your book '$bookName' is overdue (due date: $returnDate). Please return it immediately.";
        
        // Bind parameters and execute
        mysqli_stmt_bind_param($insertStmt, "ss", $studentId, $message);
        
        if (mysqli_stmt_execute($insertStmt)) {
            $notificationCount++;
            echo "<p style='color:green;'>✓ Notification sent to {$row['FullName']} (ID: $studentId)</p>";
        } else {
            echo "<p style='color:red;'>✗ Failed to notify {$row['FullName']}: " . mysqli_stmt_error($insertStmt) . "</p>";
        }
    }
    
    mysqli_stmt_close($insertStmt);
    echo "<p style='color:green;'>Successfully processed $notificationCount notifications</p>";
    
    // Verification
    $verifyResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM student_notifications");
    $verifyRow = mysqli_fetch_assoc($verifyResult);
    echo "<p>Total notifications in system: {$verifyRow['count']}</p>";
} else {
    echo "<p style='color:blue;'>No overdue books found</p>";
}

mysqli_close($conn);
?>