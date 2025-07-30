<?php
include('includes/config.php'); // adjust path if needed

if (!empty($_POST['accession_no'])) {
    $accession_no = $_POST['accession_no'];
    
    $sql = "SELECT accession_no FROM tblbooks WHERE accession_no = :accession_no";
    $query = $dbh->prepare($sql);
    $query->bindParam(':accession_no', $accession_no, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        echo '<span style="color:red;">Accession No. already exists.</span>';
    } else {
        echo '<span style="color:green;">Accession No. is available.</span>';
    }
}
?>
