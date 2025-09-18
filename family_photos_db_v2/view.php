<?php
include 'db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT photo, filename FROM photos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($photo, $filename);
$stmt->fetch();

header("Content-Type: image/jpeg"); // You can enhance by detecting file type
echo $photo;
?>
