<?php
// Database configuration
$dbConfig = [
    'dfms_db' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'dfms_db'
    ],
    'blrr_db' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'blrr_db'
    ],
    'ict_main_records_db' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'name' => 'ict_main_records_db'
    ]
];

// Backup directory
$backupDir = __DIR__ . '/backups/';
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Create a unique filename for the backup
$backupFilename = 'database_backup_' . date('Y-m-d_H-i-s') . '.zip';
$backupPath = $backupDir . $backupFilename;

// Create zip archive
$zip = new ZipArchive();
if ($zip->open($backupPath, ZipArchive::CREATE) !== TRUE) {
    die("Cannot create backup file");
}

// Backup each database
foreach ($dbConfig as $dbName => $config) {
    $sqlFile = $backupDir . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Command to export database
    $command = "mysqldump --user={$config['user']} --password={$config['pass']} --host={$config['host']} {$config['name']} > {$sqlFile}";
    system($command);
    
    // Add SQL file to zip
    if (file_exists($sqlFile)) {
        $zip->addFile($sqlFile, basename($sqlFile));
    }
}

// Close zip file
$zip->close();

// Clean up individual SQL files
foreach (glob($backupDir . "*.sql") as $file) {
    unlink($file);
}

// Send headers for download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $backupFilename . '"');
header('Content-Length: ' . filesize($backupPath));
readfile($backupPath);

// Optionally delete the backup file after download
// unlink($backupPath);

exit;
?>