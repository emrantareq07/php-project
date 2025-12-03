<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    echo json_encode(['success'=>false,'message'=>'Not authenticated']); exit;
}
$input = json_decode(file_get_contents('php://input'), true);
$db = $input['db'] ?? null;
$stamp = $input['stamp'] ?? null;
if (!$db || !$stamp) { echo json_encode(['success'=>false,'message'=>'Missing params']); exit; }

if (!is_dir(BACKUP_DIR)) mkdir(BACKUP_DIR, 0777, true);

// find mysqldump - common path for XAMPP
$possible = [
    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    '/usr/bin/mysqldump',
    '/usr/local/bin/mysqldump',
    'mysqldump'
];
$found = null;
foreach ($possible as $p) if (file_exists($p)) { $found = $p; break; }
if (!$found) {
    // try which
    $which = trim(@shell_exec('which mysqldump 2>/dev/null'));
    if ($which) $found = $which;
}
if (!$found) {
    echo json_encode(['success'=>false,'message'=>'mysqldump not found']); exit;
}

$sqlFile = rtrim(BACKUP_DIR, '/\\') . DIRECTORY_SEPARATOR . $db . '_' . $stamp . '.sql';

// build command using --result-file
$cmd = escapeshellcmd($found)
     . ' --user=' . escapeshellarg(DB_USER)
     . ' --password=' . escapeshellarg(DB_PASS)
     . ' --host=' . escapeshellarg(DB_HOST)
     . ' --single-transaction --quick --add-drop-table '
     . escapeshellarg($db)
     . ' --result-file=' . escapeshellarg($sqlFile)
     . ' 2>&1';

exec($cmd, $out, $rc);

// if dump succeeded (rc 0 & file size > 10)
if ($rc === 0 && file_exists($sqlFile) && filesize($sqlFile) > 10) {
    echo json_encode(['success'=>true,'sqlfile'=>basename($sqlFile),'size'=>filesize($sqlFile)]);
} else {
    // include output for debugging
    echo json_encode(['success'=>false,'message'=>'Dump failed','output'=>$out,'rc'=>$rc]);
}
