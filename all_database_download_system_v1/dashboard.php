<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header('Location: login.php'); exit;
}

// Load list of existing backup zip files for history
$backups = [];
if (!is_dir(BACKUP_DIR)) mkdir(BACKUP_DIR, 0777, true);
$files = glob(BACKUP_DIR . '*.zip');
rsort($files);
foreach ($files as $f) {
    $backups[] = [
        'file' => basename($f),
        'path' => 'backups/' . basename($f),
        'size' => filesize($f),
        'mtime' => filemtime($f),
    ];
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Database Backup Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root { --bg:#f6f7fb; --card:#fff; --text:#212529; }
[data-theme="dark"]{ --bg:#0f1724; --card:#0b1220; --text:#e6eef8; }
body { background:var(--bg); color:var(--text); transition:background .25s; }
.card { background:var(--card); border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,.08); }
#floatingLoader { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:9999; align-items:center; justify-content:center; }
#floatingLoader .box { background:#fff; padding:20px; border-radius:12px; min-width:260px; text-align:center; }
[data-theme="dark"] #floatingLoader .box { background:#07202c; color:var(--text); }
.progress { height:20px; border-radius:10px; }
.toggle { cursor:pointer; }
.small-note { font-size:0.9rem; color: #9aa4b2; }
</style>
</head>
<body>
<div id="floatingLoader"><div class="box"><div class="spinner-border" role="status"></div><div id="floatingText" class="mt-2">Preparing backup...</div></div></div>

<nav class="navbar navbar-expand-lg" style="background:transparent">
  <div class="container">
    <a class="navbar-brand text-primary" href="#"><i class="fa fa-database"></i> Backup System</a>
    <div class="d-flex gap-2">
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="themeSwitch">
        <label class="form-check-label small-note" for="themeSwitch">Dark mode</label>
      </div>
      <a href="login.php?logout=1" class="btn btn-outline-secondary">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card p-3">
        <h5>Start Full Backup</h5>
        <p class="small-note">This will dump each database and create a ZIP. You will get an email when done.</p>

        <div id="statusBox" class="mb-2" style="display:none;">
          <div class="fw-bold" id="statusTitle"></div>
          <div class="small text-muted" id="statusMsg"></div>
        </div>

        <div class="progress mb-3" id="progressOuter" style="display:none;">
          <div class="progress-bar progress-bar-striped progress-bar-animated" id="prog" role="progressbar" style="width:0%">0%</div>
        </div>

        <button id="startBtn" class="btn btn-primary w-100 mb-2"><i class="fa fa-download me-2"></i> Start Backup</button>
        <button id="cancelBtn" class="btn btn-outline-danger w-100 mb-2" style="display:none;">Cancel</button>

        <div class="small-note">Databases will be processed one-by-one — progress is live.</div>
      </div>

      <div class="card p-4 mt-3">
        <h6>Databases Preview</h6>
        <div id="dbList" class="small-note">Click Start to fetch databases from server.</div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Backup History</h5>
          <button id="refreshHistory" class="btn btn-sm btn-outline-secondary">Refresh</button>
        </div>
        <hr>
        <div class="table-responsive">
          <table class="table table-sm table-borderless">
            <thead><tr><th>File</th><th>Size</th><th>Created</th><th></th></tr></thead>
            <tbody id="historyBody">
            <?php foreach($backups as $b): ?>
              <tr>
                <td><?php echo htmlspecialchars($b['file']); ?></td>
                <td><?php echo number_format($b['size']/1024,2) . ' KB'; ?></td>
                <td><?php echo date('Y-m-d H:i', $b['mtime']); ?></td>
                <td><a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($b['path']); ?>" download>Download</a></td>
              </tr>
            <?php endforeach; ?>
            <?php if(empty($backups)): ?>
              <tr><td colspan="4" class="small-note">No backups yet.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card p-3 mt-3">
        <h6>Settings</h6>
        <div class="small-note">Keep last <strong><?php echo KEEP_LAST; ?></strong> backups. Change settings in <code>config.php</code>.</div>
      </div>
    </div>
  </div>
</div>

<script>
const floating = document.getElementById('floatingLoader');
const floatingText = document.getElementById('floatingText');
const statusBox = document.getElementById('statusBox');
const statusTitle = document.getElementById('statusTitle');
const statusMsg = document.getElementById('statusMsg');
const prog = document.getElementById('prog');
const progressOuter = document.getElementById('progressOuter');
const startBtn = document.getElementById('startBtn');
const cancelBtn = document.getElementById('cancelBtn');
const dbList = document.getElementById('dbList');

let cancelRequested = false;

// Theme toggle
const themeSwitch = document.getElementById('themeSwitch');
function applyTheme(t) {
  if (t === 'dark') document.documentElement.setAttribute('data-theme','dark');
  else document.documentElement.removeAttribute('data-theme');
  themeSwitch.checked = (t === 'dark');
  localStorage.setItem('theme', t);
}
themeSwitch.addEventListener('change', () => applyTheme(themeSwitch.checked ? 'dark' : 'light'));
// init
applyTheme(localStorage.getItem('theme') || 'light');

// Helper to format bytes
function humanSize(bytes){ if(bytes<1024) return bytes+' B'; if(bytes<1024*1024) return (bytes/1024).toFixed(2)+' KB'; return (bytes/1024/1024).toFixed(2)+' MB'; }

// Fetch DB list (for preview)
async function fetchDbList(){
  dbList.textContent = 'Loading databases...';
  try{
    const res = await fetch('backup_list.php');
    const j = await res.json();
    if (!j.success) { dbList.textContent = 'Error: '+(j.message||''); return; }
    const n = j.databases.length;
    dbList.innerHTML = '<strong>' + n + ' databases</strong><ul class="mt-2 mb-0">';
    j.databases.forEach(d => dbList.innerHTML += '<li>' + d + '</li>');
    dbList.innerHTML += '</ul>';
    return j.databases;
  }catch(e){ dbList.textContent = 'Failed to load DB list.'; }
}

// Live backup process: per-database dump, then finalize ZIP
startBtn.addEventListener('click', async () => {
  cancelRequested = false;
  startBtn.disabled = true;
  cancelBtn.style.display = 'inline-block';
  progressOuter.style.display = 'block';
  prog.style.width = '0%'; prog.textContent = '0%';
  statusBox.style.display = 'block';
  statusTitle.textContent = 'Starting backup';
  statusMsg.textContent = 'Fetching DB list...';
  floating.style.display = 'flex';
  floatingText.textContent = 'Preparing backup...';

  const stamp = Date.now().toString();
  try{
    const resList = await fetch('backup_list.php');
    const j = await resList.json();
    if (!j.success) throw new Error(j.message || 'Failed to get DB list');
    const dbs = j.databases;
    const total = dbs.length;
    let done = 0;
    let failed = 0;
    statusTitle.textContent = 'Dumping databases';
    statusMsg.textContent = 'Processing 0 of ' + total;

    // per-db loop
    for (const db of dbs){
      if (cancelRequested) break;
      floatingText.textContent = 'Dumping: ' + db;
      statusMsg.textContent = 'Dumping ' + db + ' (' + (done+1) + '/' + total + ')';

      // call dump endpoint
      const resp = await fetch('dump_db.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({db: db, stamp: stamp})
      });
      const rj = await resp.json();
      if (rj.success){
        done++;
      } else {
        failed++;
        console.warn('Failed dump', db, rj.message);
      }

      // update progress based on done
      const pct = Math.round(((done + failed) / total) * 100);
      prog.style.width = pct + '%'; prog.textContent = pct + '%';
    }

    if (cancelRequested) {
      statusTitle.textContent = 'Cancelled';
      floatingText.textContent = 'Backup cancelled';
      startBtn.disabled = false;
      cancelBtn.style.display = 'none';
      floating.style.display = 'none';
      return;
    }

    // finalize zip
    statusTitle.textContent = 'Creating ZIP';
    floatingText.textContent = 'Compressing files...';
    prog.style.width = '90%'; prog.textContent = '90%';

    const fin = await fetch('finalize_zip.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({stamp: stamp})
    });
    const fj = await fin.json();
    if (!fj.success) throw new Error(fj.message || 'Zip failed');

    prog.style.width = '100%'; prog.textContent = '100%';
    statusTitle.textContent = 'Completed';
    statusMsg.textContent = 'Backup created: ' + fj.filename + ' (' + fj.size_text + ')';

    // show download link and refresh history
    setTimeout(()=> {
      window.location = fj.file; // triggers download
    }, 800);

    // refresh history table
    setTimeout(()=> location.reload(), 1500);

  }catch(err){
    statusTitle.textContent = 'Error';
    statusMsg.textContent = err.message || String(err);
    console.error(err);
  } finally {
    startBtn.disabled = false;
    cancelBtn.style.display = 'none';
    floating.style.display = 'none';
  }
});

cancelBtn.addEventListener('click', () => { cancelRequested = true; });

document.getElementById('refreshHistory').addEventListener('click', () => location.reload());

// initial load
fetchDbList();
</script>
</body>
</html>
