<?php 
session_name('bcic_e-library');
session_start();
error_reporting(0);
require_once("includes/config.php");

if (!empty($_POST["bookid"])) {
    $bookid = $_POST["bookid"];

    $sql = "SELECT book_name, id FROM tblbooks WHERE isbn = :bookid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        echo '<option value="">Select Book</option>';
        foreach ($results as $result) {
            echo '<option value="' . htmlentities($result->id) . '">' . htmlentities($result->book_name) . '</option>';
        }
        echo "<script>$('#submit').prop('disabled', false);</script>";
    } else {
        echo '<option value="">Invalid ISBN Number</option>';
        echo "<script>$('#submit').prop('disabled', true);</script>";
    }
}
?>
