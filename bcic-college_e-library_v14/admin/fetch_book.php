<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_POST['accession_no'])) {
    $accession_no = mysqli_real_escape_string($conn, $_POST['accession_no']);
    
    $sql = "SELECT b.book_name, a.AuthorName, c.CategoryName 
            FROM tblbooks b
            JOIN tblauthors a ON b.author_id = a.id
            JOIN tblcategory c ON b.category_id = c.id
            WHERE b.accession_no = '$accession_no'";
    
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'book_name' => $row['book_name'] . " by " . $row['AuthorName'] . " (" . $row['CategoryName'] . ")"
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No book found with this accession number'
        ]);
    }
}
?>