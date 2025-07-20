<?php
session_name('bcic_college_e-library');
session_start();
include('includes/config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isbn'])) {
    $isbn = trim($_POST['isbn']);
    
    try {
        $sql = "SELECT * FROM tblbooks WHERE isbn = :isbn  ORDER BY id DESC LIMIT 1";
        $query = $dbh->prepare($sql);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $book = $query->fetch(PDO::FETCH_ASSOC);
            
            $response = [
                'success' => true,
                'book_name' => $book['book_name'] ?? '',
                'category_id' => $book['category_id'] ?? '',
                'author_id' => $book['author_id'] ?? '',
                'edition' => $book['edition'] ?? '',
                'publisher' => $book['publisher'] ?? '',
                'publication_place' => $book['publication_place'] ?? '',
                'year' => $book['year'] ?? '',
                'page_no' => $book['page_no'] ?? '',
                'price' => $book['price'] ?? '',
                'source' => $book['source'] ?? '',
                'call_no' => $book['call_no'] ?? '',
                'remarks' => $book['remarks'] ?? '',
                    'volume' => $book['volume'] ?? '',
                'language' => $book['language'] ?? '',
                'self_no' => $book['self_no'] ?? '',
                'series' => $book['series'] ?? ''

            ];
        } else {
            $response = ['success' => false, 'message' => 'No book found with this ISBN.'];
        }
    } catch (PDOException $e) {
        $response = ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request.'];
}

echo json_encode($response);
exit();
?>