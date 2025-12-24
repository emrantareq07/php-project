<?php
require_once '../../db/config.php';

$id = intval($_POST['id']);
$name = trim($_POST['name']);

$stmt = $conn->prepare("UPDATE division SET division=? WHERE id=?");
$stmt->bind_param("si", $name, $id);
$stmt->execute();
