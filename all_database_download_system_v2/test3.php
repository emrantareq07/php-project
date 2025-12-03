<?php
// backup.php  (single file solution)
// ------------------------------------------------------------------
// If called with ?action=backup, run the backup and return JSON.
// Otherwise, render the HTML page.
// ------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'backup') {
    header('Content-Type: application/json');

    // --- CONFIGURE YOUR DATABASES HERE ---
    $dbConfig = [
        'dfms_db' => ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'dfms_db'],
        'blrr_db' => ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'blrr_db'],
        'ict_main_records_db' => ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'ict_main_records_db'],
    ];

    // --- Ensure ZipArchive exists ---
    if (!class_exists('ZipArchive')) {
        echo json_encode(['success' => false, 'message' => 'PHP ZipArchive extension is not enabled.']);
        exit;
    }

    // --- Make /backups folder (web-accessible) ---
    $backupDir = __DIR__ . '/backups/';
    if (!is_dir($backupDir)) {
        if (!mkdir($backupDir, 0775, true)) {
            echo json_encode(['success' => false, 'message' => 'Cannot create backups directory (permissions).']);
            exit;
        }
    }

    // --- Find mysqldump binary ---
    function findMysqldump(): string {
        $candidates = [
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.6\\bin\\mysqldump.exe',
        ];
        foreach ($candidates as $c) {
            if (file_exists($c)) return escapeshellcmd($c);
        }
        $which = trim(@shell_exec('which mysqldump 2>/dev/null'));
        if ($which) return escapeshellcmd($which);
        return 'mysqldump'; // hope it's in PATH
    }
    $mysqldump = findMysqldump();

    // --- Create zip archive name ---
    $backupFilename = 'database_backup_' . date('Ymd_His') . '.zip';
    $backupPath = $backupDir . $backupFilename;

    $zip = new ZipArchive();
    if ($zip->open($backupPath, ZipArchive::CREATE) !== true) {
        echo json_encode(['success' => false, 'message' => 'Cannot create zip archive in backups directory.']);
        exit;
    }

    $errors = [];
    $sqlFiles = [];

    foreach ($dbConfig as $key => $cfg) {
        $sqlFile = $backupDir . $key . '_' . date('Ymd_His') . '.sql';
        // Use --result-file to avoid shell redirection issues. Quote args safely.
        $cmd = $mysqldump
            . ' --user=' . escapeshellarg($cfg['user'])
            . ' --password=' . escapeshellarg($cfg['pass'])
            . ' --host=' . escapeshellarg($cfg['host'])
            . ' --single-transaction --quick --add-drop-table'
            . ' ' . escapeshellarg($cfg['name'])
            . ' --result-file=' . escapeshellarg($sqlFile)
            . ' 2>&1';

        $output = [];
        $code = 0;
        exec($cmd, $output, $code);

        if ($code === 0 && file_exists($sqlFile) && filesize($sqlFile) > 0) {
            $zip->addFile($sqlFile, basename($sqlFile));
            $sqlFiles[] = $sqlFile;
        } else {
            $errors[] = "Failed to dump {$key}. " . implode("\n", $output);
        }
    }

    $zip->close();

    // Cleanup temp .sql files
    foreach ($sqlFiles as $f) {
        @unlink($f);
    }

    if (!file_exists($backupPath) || filesize($backupPath) === 0) {
        $msg = 'Backup archive not created. ';
        if ($errors) $msg .= implode(' | ', $errors);
        echo json_encode(['success' => false, 'message' => $msg]);
        exit;
    }

    echo json_encode([
        'success'   => true,
        'message'   => $errors ? ('Completed with warnings: ' . implode(' | ', $errors)) : 'Backup completed successfully.',
        'filename'  => $backupFilename,
        'filepath'  => 'backups/' . $backupFilename, // relative URL
        'size'      => filesize($backupPath)
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Database Backup System</title>
<style>
/* (your styles unchanged) */
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
body { background:linear-gradient(135deg,#6a11cb 0%,#2575fc 100%); color:#333; line-height:1.6; padding:20px; min-height:100vh; display:flex; justify-content:center; align-items:center;}
.container { width:90%; max-width:800px; background:#fff; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,.2); padding:30px; position:relative; overflow:hidden;}
.container::before { content:''; position:absolute; top:0; left:0; width:100%; height:5px; background:linear-gradient(90deg,#6a11cb,#2575fc);}
header { text-align:center; margin-bottom:30px;}
h1 { color:#2c3e50; margin-bottom:10px; font-size:2.2rem;}
.description { color:#7f8c8d; margin-bottom:20px; font-size:1.1rem;}
.databases-list { margin-bottom:30px;}
.databases-list h2 { color:#2c3e50; margin-bottom:15px; padding-bottom:5px; border-bottom:2px solid #eee;}
.database-item { background:#f8f9fa; padding:15px; margin-bottom:10px; border-radius:8px; border-left:4px solid #3498db; display:flex; justify-content:space-between; align-items:center; transition:transform .2s;}
.database-item:hover { transform:translateX(5px); background:#e8f4fc;}
.database-name { font-weight:bold; color:#2c3e50; font-size:1.1rem;}
.backup-btn { background:linear-gradient(90deg,#3498db,#2980b9); color:#fff; border:none; padding:16px 32px; border-radius:8px; cursor:pointer; font-size:18px; font-weight:bold; display:block; width:100%; transition:all .3s; box-shadow:0 4px 15px rgba(52,152,219,.3);}
.backup-btn:hover { background:linear-gradient(90deg,#2980b9,#2573a7); transform:translateY(-2px); box-shadow:0 6px 20px rgba(52,152,219,.4);}
.backup-btn:disabled { background:#95a5a6; cursor:not-allowed; transform:none; box-shadow:none;}
.progress-container { margin-top:25px; display:none;}
.progress-bar { height:22px; background:#ecf0f1; border-radius:11px; overflow:hidden; margin-bottom:12px; box-shadow:inset 0 2px 5px rgba(0,0,0,.1);}
.progress { height:100%; background:linear-gradient(90deg,#2ecc71,#27ae60); width:0%; transition:width .5s; border-radius:11px;}
.status { text-align:center; color:#7f8c8d; font-style:italic; margin-bottom:15px;}
.download-section { text-align:center; margin-top:20px; padding:20px; background:#f8f9fa; border-radius:8px; display:none;}
.download-link { display:inline-block; padding:12px 25px; background:linear-gradient(90deg,#2ecc71,#27ae60); color:#fff; text-decoration:none; border-radius:8px; font-weight:bold; transition:all .3s; box-shadow:0 4px 15px rgba(46,204,113,.3);}
.download-link:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(46,204,113,.4);}
.save-path { margin-top:15px; color:#7f8c8d; font-size:.9rem;}
.notification { padding:15px; margin-bottom:20px; border-radius:8px; display:none; text-align:center;}
.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb;}
.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;}
footer { text-align:center; margin-top:30px; color:#7f8c8d; font-size:14px;}
.backup-info { display:flex; justify-content:space-between; margin-top:15px; color:#7f8c8d; font-size:.9rem;}
@media (max-width:600px){ .container{padding:20px;} h1{font-size:1.8rem;} .backup-btn{padding:14px 20px; font-size:16px;}}
</style>
</head>
<body>
<div class="container">
    <header>
        <h1>Database Backup System</h1>
        <p class="description">Download all your databases with a single click. Backups will be saved to the <code>backups/</code> folder.</p>
    </header>

    <div class="notification success" id="success-notification">Backup completed successfully!</div>
    <div class="notification error" id="error-notification">An error occurred during the backup process.</div>

    <div class="databases-list">
        <h2>Databases to be backed up:</h2>
        <div class="database-item">
            <span class="database-name">blrr_db</span><span class="database-size">~2.4 MB</span>
        </div>
        <div class="database-item">
            <span class="database-name">dfms_db</span><span class="database-size">~3.1 MB</span>
        </div>
        <div class="database-item">
            <span class="database-name">ict_main_records_db</span><span class="database-size">~5.2 MB</span>
        </div>
    </div>

    <button class="backup-btn" id="backup-btn">Download All Databases Backup</button>

    <div class="progress-container" id="progress-container">
        <div class="progress-bar"><div class="progress" id="progress-bar"></div></div>
        <div class="status" id="status-text">Initializing backup process...</div>
    </div>

    <div class="download-section" id="download-section">
        <a href="#" class="download-link" id="download-link" download>Download Backup File</a>
        <div class="save-path" id="save-path"></div>
    </div>

    <div class="backup-info">
        <span>Total size: dynamic</span>
        <span>Last backup: —</span>
    </div>

    <footer><p>Database Backup System &copy; <?= date('Y') ?></p></footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const backupBtn = document.getElementById('backup-btn');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const statusText = document.getElementById('status-text');
    const downloadSection = document.getElementById('download-section');
    const downloadLink = document.getElementById('download-link');
    const savePath = document.getElementById('save-path');
    const successNotification = document.getElementById('success-notification');
    const errorNotification = document.getElementById('error-notification');

    function animateProgressTo(target, step = 5, delay = 120) {
        return new Promise(resolve => {
            let current = parseInt(progressBar.style.width || '0');
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                progressBar.style.width = current + '%';
                if (current >= target) {
                    clearInterval(timer);
                    resolve();
                }
            }, delay);
        });
    }

    backupBtn.addEventListener('click', async function () {
        // Reset UI
        progressBar.style.width = '0%';
        successNotification.style.display = 'none';
        errorNotification.style.display = 'none';
        downloadSection.style.display = 'none';
        progressContainer.style.display = 'block';
        backupBtn.disabled = true;
        statusText.textContent = 'Connecting to database servers...';

        try {
            await animateProgressTo(20);
            statusText.textContent = 'Preparing dump commands...';

            // Trigger the real backup on the server
            const resp = await fetch('?action=backup', { method: 'POST' });
            const data = await resp.json();

            await animateProgressTo(80);
            statusText.textContent = 'Compressing backup files...';

            if (data.success) {
                await animateProgressTo(100);
                statusText.textContent = 'Backup completed successfully!';
                successNotification.style.display = 'block';
                downloadSection.style.display = 'block';
                downloadLink.href = data.filepath; // e.g., backups/database_backup_YYYYMMDD_HHMMSS.zip
                downloadLink.setAttribute('download', data.filename);
                downloadLink.setAttribute('target', '_blank');
                savePath.textContent = 'Saved to: ' + data.filepath + ' (' + (data.size || 0) + ' bytes)';
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        } catch (err) {
            progressBar.style.width = '100%';
            statusText.textContent = 'Backup failed.';
            errorNotification.textContent = 'Error: ' + err.message;
            errorNotification.style.display = 'block';
        } finally {
            backupBtn.disabled = false;
        }
    });
});
</script>
</body>
</html>
