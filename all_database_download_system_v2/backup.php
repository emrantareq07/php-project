<?php
// backup.php - Diagnostic + Dynamic Database Backup
// Overwrite your current backup.php with this for debugging & backup
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Helper to return JSON and exit
function jsonExit($arr) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// If front-end requests download directly (optional)
if (isset($_GET['download']) && preg_match('/^[\w\-\.]+\.zip$/', $_GET['download'])) {
    $f = __DIR__ . '/backups/' . basename($_GET['download']);
    if (file_exists($f)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($f) . '"');
        header('Content-Length: ' . filesize($f));
        readfile($f);
        exit;
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo 'File not found';
        exit;
    }
}

if (!(isset($_GET['action']) && $_GET['action'] === 'backup')) {
    // Simple HTML UI when not calling action=backup
    ?>
    <!doctype html>
    <html>
    <head><meta charset="utf-8"><title>Backup Diagnostic</title></head>
    <body style="font-family:system-ui,Arial;padding:20px;">
      <h2>Backup Diagnostic & Start</h2>
      <button id="go">Start Backup</button>
      <pre id="out" style="white-space:pre-wrap;background:#f6f8fa;padding:12px;border-radius:6px;margin-top:12px;max-height:60vh;overflow:auto;"></pre>
      <script>
        document.getElementById('go').onclick = async () => {
          const out = document.getElementById('out');
          out.textContent = 'Starting...';
          try {
            const r = await fetch('?action=backup');
            const j = await r.json();
            out.textContent = JSON.stringify(j, null, 2);
            if (j.success && j.file) {
              const a = document.createElement('a');
              a.href = j.file;
              a.textContent = 'Download ' + j.filename;
              a.style.display = 'inline-block';
              a.style.marginTop = '10px';
              document.body.appendChild(a);
            }
          } catch (e) {
            out.textContent = 'Fetch failed: ' + e;
          }
        };
      </script>
    </body>
    </html>
    <?php
    exit;
}

/* ---------------------- START BACKUP ACTION ---------------------- */

// Basic DB access (change if you use different credentials)
$host = 'localhost';
$user = 'root';
$pass = '';

// diagnostic container
$diag = [
    'timestamp' => date('c'),
    'php_version' => PHP_VERSION,
    'exec_enabled' => function_exists('exec'),
    'shell_exec_enabled' => function_exists('shell_exec'),
    'proc_open_enabled' => function_exists('proc_open'),
    'zip_extension_loaded' => extension_loaded('zip'),
    'backups_dir' => realpath(__DIR__ . '/backups') ?: (__DIR__ . '/backups'),
    'mysqldump_candidates' => [],
    'mysqldump_found' => null,
    'mysqldump_version' => null,
    'databases' => [],
    'dumps' => [],
    'zip' => null,
    'errors' => []
];

// check exec() availability early
if (!$diag['exec_enabled'] && !$diag['shell_exec_enabled'] && !$diag['proc_open_enabled']) {
    $diag['errors'][] = "No available functions to run external commands (exec, shell_exec, proc_open all disabled).";
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Try to connect to MySQL server
$mysqli = @new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
    $diag['errors'][] = "MySQL connection failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Fetch databases
$res = $mysqli->query("SHOW DATABASES");
if (!$res) {
    $diag['errors'][] = "SHOW DATABASES failed: " . $mysqli->error;
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}
$exclude = ['information_schema','mysql','performance_schema','phpmyadmin','sys'];
while ($r = $res->fetch_array(MYSQLI_NUM)) {
    $db = $r[0];
    if (!in_array($db, $exclude)) $diag['databases'][] = $db;
}
$mysqli->close();

if (empty($diag['databases'])) {
    $diag['errors'][] = "No user databases found (after excluding system DBs).";
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Ensure backups dir exists and writable
$backupDir = __DIR__ . '/backups/';
if (!is_dir($backupDir)) {
    if (!@mkdir($backupDir, 0777, true)) {
        $diag['errors'][] = "Cannot create backups directory: $backupDir (check permissions).";
        jsonExit(['success' => false, 'diagnostic' => $diag]);
    }
}
if (!is_writable($backupDir)) {
    $diag['errors'][] = "Backups directory exists but is not writable: $backupDir";
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}
$diag['backups_dir'] = realpath($backupDir);

// Find mysqldump binary (candidates)
$candidates = [
    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    '/usr/bin/mysqldump',
    '/usr/local/bin/mysqldump',
    '/usr/local/mysql/bin/mysqldump',
    'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
    'mysqldump' // last hope (in PATH)
];
foreach ($candidates as $c) {
    $diag['mysqldump_candidates'][] = $c;
    // if it's an absolute path, check file_exists, otherwise try "which"
    if (strpos($c, '/') === 0 || preg_match('/^[A-Za-z]:\\\\/', $c)) {
        if (file_exists($c) && is_executable($c)) {
            $diag['mysqldump_found'] = $c;
            break;
        }
    } else {
        // try which only if shell_exec available
        if (function_exists('shell_exec')) {
            $which = trim(@shell_exec("which $c 2>/dev/null"));
            if ($which) {
                $diag['mysqldump_found'] = $which;
                break;
            }
        }
        // otherwise try running "$c --version" and see if it exists
        if (function_exists('exec')) {
            @exec("$c --version 2>&1", $outTmp, $rcTmp);
            if ($rcTmp === 0 || !empty($outTmp)) {
                $diag['mysqldump_found'] = $c;
                break;
            }
        }
    }
}

// If not found, still attempt with 'mysqldump' (may be in PATH)
if (!$diag['mysqldump_found']) {
    $diag['errors'][] = "Could not locate a mysqldump binary. Please ensure mysqldump is installed and executable, or provide its full path in the script.";
    // Continue to return diagnostic (do not attempt dumps)
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Get mysqldump version (best-effort)
if (function_exists('exec')) {
    @exec(escapeshellcmd($diag['mysqldump_found']) . " --version 2>&1", $verout, $vercode);
    $diag['mysqldump_version'] = $verout ? implode("\n", $verout) : null;
}

// Check ZipArchive
if (!extension_loaded('zip')) {
    $diag['errors'][] = "PHP Zip extension not loaded. Install/enable the zip extension.";
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Prepare zip
$zipName = 'database_backup_' . date('Ymd_His') . '.zip';
$zipPath = $backupDir . $zipName;
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
    $diag['errors'][] = "Cannot create zip archive at: $zipPath";
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Dump each DB
$sqlFiles = [];
foreach ($diag['databases'] as $dbName) {
    $sqlFile = $backupDir . $dbName . '_' . date('Ymd_His') . '.sql';

    // Build safe command. Use --result-file to avoid shell redirection.
    // For password we use --password=... (no space) to be safe.
    $cmd = escapeshellcmd($diag['mysqldump_found'])
         . ' --user=' . escapeshellarg($user)
         . ' --password=' . escapeshellarg($pass)
         . ' --host=' . escapeshellarg($host)
         . ' --single-transaction --quick --add-drop-table'
         . ' ' . escapeshellarg($dbName)
         . ' --result-file=' . escapeshellarg($sqlFile)
         . ' 2>&1';

    $out = [];
    $rc = null;

    if (function_exists('exec')) {
        @exec($cmd, $out, $rc);
    } elseif (function_exists('shell_exec')) {
        $sout = @shell_exec($cmd);
        $out = $sout === null ? [] : explode("\n", trim($sout));
        $rc = ($sout === null) ? 1 : 0;
    } else {
        $diag['dumps'][$dbName] = ['ok' => false, 'message' => 'No exec/shell_exec available'];
        continue;
    }

    $dumpInfo = [
        'cmd' => $cmd,
        'output' => $out,
        'return_code' => $rc,
        'sql_file' => file_exists($sqlFile) ? realpath($sqlFile) : null,
        'sql_file_size' => file_exists($sqlFile) ? filesize($sqlFile) : 0,
        'ok' => ($rc === 0 && file_exists($sqlFile) && filesize($sqlFile) > 10)
    ];

    if ($dumpInfo['ok']) {
        $zip->addFile($sqlFile, basename($sqlFile));
        $sqlFiles[] = $sqlFile;
    } else {
        $diag['errors'][] = "Dump failed for DB: $dbName — return_code={$rc} — output: " . implode("\n", $out);
    }

    $diag['dumps'][$dbName] = $dumpInfo;
}

// Close zip
$zipClose = $zip->close();
if (!$zipClose) {
    $diag['errors'][] = "ZipArchive->close() returned false.";
    jsonExit(['success' => false, 'diagnostic' => $diag]);
}

// Remove temp .sql files (only those added)
foreach ($sqlFiles as $sf) {
    @unlink($sf);
}

$diag['zip'] = [
    'path' => realpath($zipPath),
    'relative' => 'backups/' . basename($zipPath),
    'size' => file_exists($zipPath) ? filesize($zipPath) : 0
];

$success = empty($diag['errors']);
jsonExit([
    'success' => $success,
    'filename' => basename($zipPath),
    'filepath' => $diag['zip']['relative'],
    'size' => $diag['zip']['size'],
    'diagnostic' => $diag
]);
