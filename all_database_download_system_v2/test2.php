<?php
// backup.php
header('Content-Type: application/json');

// Database configuration
$dbConfig = [
    'dfms_db' => ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'dfms_db'],
    'blrr_db' => ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'blrr_db'],
    'ict_main_records_db' => ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'ict_main_records_db']
];

$backupDir = __DIR__ . '/backups/';
if (!file_exists($backupDir)) {
    if (!mkdir($backupDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Cannot create backup directory']);
        exit;
    }
}

$backupFilename = 'database_backup_' . date('Ymd_His') . '.zip';
$backupPath = $backupDir . $backupFilename;

$zip = new ZipArchive();
if ($zip->open($backupPath, ZipArchive::CREATE) !== TRUE) {
    echo json_encode(['success' => false, 'message' => 'Cannot create backup file']);
    exit;
}

$success = true;
$errorMessages = [];

foreach ($dbConfig as $dbName => $config) {
    $sqlFile = $backupDir . $dbName . '_' . date('Ymd_His') . '.sql';
    
    // Build the mysqldump command
    $command = "mysqldump --user={$config['user']}";
    
    // Add password if it's not empty
    if (!empty($config['pass'])) {
        $command .= " --password={$config['pass']}";
    }
    
    $command .= " --host={$config['host']} {$config['name']} > {$sqlFile} 2>&1";
    
    $output = [];
    $returnVar = 0;
    exec($command, $output, $returnVar);

    if ($returnVar === 0 && file_exists($sqlFile)) {
        $zip->addFile($sqlFile, basename($sqlFile));
    } else {
        $errorMessages[] = "Failed to backup {$dbName}: " . implode("\n", $output);
        $success = false;
    }
}

if (!$zip->close()) {
    $success = false;
    $errorMessages[] = "Failed to create ZIP archive";
}

// Delete temporary SQL files
foreach (glob($backupDir . "*.sql") as $file) {
    unlink($file);
}

if ($success) {
    echo json_encode([
        'success' => true,
        'filename' => $backupFilename,
        'filepath' => "backups/$backupFilename",
        'size' => filesize($backupPath)
    ]);
} else {
    // If there was an error, delete the incomplete backup file
    if (file_exists($backupPath)) {
        unlink($backupPath);
    }
    
    echo json_encode([
        'success' => false,
        'message' => implode("\n", $errorMessages)
    ]);
}