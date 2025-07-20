<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
//include('includes/db.php'); // Include your database connection file
include('includes/config.php');
header('Content-Type: application/json');

if (isset($_POST['book_name']) && isset($_POST['author_id']) && isset($_POST['category_id'])) {
    $bookName = mysqli_real_escape_string($conn, $_POST['book_name']);
    $authorId = intval($_POST['author_id']);
    $categoryId = intval($_POST['category_id']);
    
    $sql = "SELECT accession_no, call_no,self_no,book_no
            FROM tblbooks 
            WHERE book_name = '$bookName' 
            AND author_id = $authorId 
            AND category_id = $categoryId 
            AND status=0"
            ;
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $formattedEntries = [];
       //  while ($row = mysqli_fetch_assoc($result)) {
       // $formattedEntries[] = "Accession No. - " . $row['accession_no'] . 
       //                " [Call No - " . $row['call_no'] . 
       //                "] [Self No - " . $row['self_no'] . "]\n";

       //  }

        while ($row = mysqli_fetch_assoc($result)) {
            $entry = "Accession No. - " . $row['accession_no'];


             if (!empty($row['book_no'])) {
                $entry .= " [Book No- " . $row['book_no'] . "]";
            }
            
            // Add Call No only if it has a value
            if (!empty($row['call_no'])) {
                $entry .= " [Call No - " . $row['call_no'] . "]";
            }
            
            // Add Self No only if it has a value
            if (!empty($row['self_no'])) {
                $entry .= " [Self No - " . $row['self_no'] . "]";
            }
            
            $formattedEntries[] = $entry . "\n";
        }
                    
        echo json_encode([
            'success' => true,
            'accession_numbers' => $formattedEntries
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}

mysqli_close($conn);
?>