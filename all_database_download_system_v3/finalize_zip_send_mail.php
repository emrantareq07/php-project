<?php
// finalize_zip_send_mail.php
require_once 'config_send_mail.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    echo json_encode(['success'=>false,'message'=>'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['stamp'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid request']);
    exit;
}

$stamp = $data['stamp'];
$tempDir = sys_get_temp_dir() . '/backup_' . $stamp . '/';
$zipFileName = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
$zipFilePath = BACKUP_DIR . $zipFileName;

if (!is_dir($tempDir) || count(glob($tempDir . '*.sql')) === 0) {
    echo json_encode(['success'=>false,'message'=>'No SQL files found']);
    exit;
}

// Create backup directory if it doesn't exist
if (!is_dir(BACKUP_DIR)) {
    mkdir(BACKUP_DIR, 0777, true);
}

// Create ZIP file
$zip = new ZipArchive();
if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
    echo json_encode(['success'=>false,'message'=>'Cannot create ZIP']);
    exit;
}

$files = glob($tempDir . '*.sql');
foreach ($files as $file) {
    if (is_file($file)) {
        $zip->addFile($file, basename($file));
    }
}
$zip->close();

// Clean up temp directory
array_map('unlink', glob($tempDir . '*.sql'));
if (is_dir($tempDir)) {
    rmdir($tempDir);
}

// Apply retention policy
$allBackups = glob(BACKUP_DIR . '*.zip');
usort($allBackups, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

if (count($allBackups) > KEEP_LAST) {
    for ($i = KEEP_LAST; $i < count($allBackups); $i++) {
        if (is_file($allBackups[$i])) {
            unlink($allBackups[$i]);
        }
    }
}

$size = filesize($zipFilePath);
$size_text = round($size / 1024, 2) . ' KB';

echo json_encode([
    'success' => true,
    'filename' => $zipFileName,
    'file' => 'backups/' . $zipFileName,
    'size' => $size,
    'size_text' => $size_text
]);