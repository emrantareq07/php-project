<?php
require_once 'config_send_mail.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) { echo json_encode(['success'=>false,'message'=>'Not authenticated']); exit; }

$mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($mysqli->connect_errno) { echo json_encode(['success'=>false,'message'=>'DB connect failed']); exit; }
$res = $mysqli->query("SHOW DATABASES");
$exclude = ['information_schema','mysql','performance_schema','phpmyadmin','sys'];
$dbs = [];
while ($r = $res->fetch_array(MYSQLI_NUM)) {
    if (!in_array($r[0], $exclude)) $dbs[] = $r[0];
}
$mysqli->close();
echo json_encode(['success'=>true,'databases'=>$dbs]);
