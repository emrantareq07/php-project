<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
// Include your database connection
include('includes/config.php');

header('Content-Type: application/json');

if(isset($_GET['bookid'])) {
    $bookId = intval($_GET['bookid']);
    
    try {
        // Fetch book details with category and author names if available
        $sql = "SELECT b.*, 
                (SELECT CategoryName FROM tblcategory WHERE id = b.category_id) as category_name,
                (SELECT AuthorName FROM tblauthors WHERE id = b.author_id) as author_name
                FROM tblbooks b
                WHERE b.id = :bookid";
                
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookid', $bookId, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if($result) {
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Book not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Book ID not provided'
    ]);
}
?>