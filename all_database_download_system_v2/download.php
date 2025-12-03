<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) { header('HTTP/1.1 403 Forbidden'); exit; }
$f = $_GET['f'] ?? '';
if (!$f) { header('HTTP/1.1 400 Bad Request'); echo 'Missing'; exit; }
$clean = basename($f);
$path = rtrim(BACKUP_DIR,'/\\') . DIRECTORY_SEPARATOR . $clean;
if (!file_exists($path)) { header('HTTP/1.1 404 Not Found'); echo 'Not found'; exit; }
header('Content-Type: application/zip');
header('Content-Length: ' . filesize($path));
header('Content-Disposition: attachment; filename="' . $clean . '"');
readfile($path);
exit;
