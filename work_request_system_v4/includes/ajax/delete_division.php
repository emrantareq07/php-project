<?php
include('../db/db.php');

$id = intval($_POST['id']);
$conn->query("DELETE FROM division WHERE id=$id");
