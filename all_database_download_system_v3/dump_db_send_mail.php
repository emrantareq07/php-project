<?php
// dump_db.php
require_once 'config_send_mail.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    echo json_encode(['success'=>false,'message'=>'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['db']) || !isset($data['stamp'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid request']);
    exit;
}

$db = $data['db'];
$stamp = $data['stamp'];
$tempDir = sys_get_temp_dir() . '/backup_' . $stamp . '/';

if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

$outputFile = $tempDir . $db . '.sql';
$command = sprintf(
    'mysqldump -h%s -u%s -p%s --no-tablespaces --skip-lock-tables %s > "%s" 2>&1',
    escapeshellarg(DB_HOST),
    escapeshellarg(DB_USER),
    escapeshellarg(DB_PASS),
    escapeshellarg($db),
    escapeshellarg($outputFile)
);

exec($command, $output, $returnVar);

if ($returnVar === 0 && file_exists($outputFile)) {
    $size = filesize($outputFile);
    echo json_encode([
        'success' => true,
        'size' => $size,
        'output' => implode("\n", $output)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Dump failed',
        'output' => implode("\n", $output),
        'returnVar' => $returnVar
    ]);
}