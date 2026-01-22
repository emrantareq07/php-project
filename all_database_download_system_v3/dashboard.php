<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header('Location: index.php'); exit;
}

// prepare backups list
if (!is_dir(BACKUP_DIR)) mkdir(BACKUP_DIR, 0777, true);
$files = glob(BACKUP_DIR . '*.zip');
usort($files, function($a,$b){ return filemtime($b) - filemtime($a); });
$backups = [];
foreach ($files as $f) {
    $backups[] = [
        'file' => basename($f),
        'path' => 'backups/' . basename($f),
        'size' => filesize($f),
        'mtime' => filemtime($f)
    ];
}
?>
<!doctype html>
<html><head>
<meta charset="utf-8"><title>Backup Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
:root{--bg:#f6f8fb;--card:#fff;--text:#111} [data-theme="dark"]{--bg:#071223;--card:#071928;--text:#dbefff}
body{background:var(--bg);color:var(--text);transition:.2s}
.card{background:var(--card);border-radius:12px;box-shadow:0 8px 30px rgba(2,6,23,.08)}
#overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center}
#overlay .box{background:#fff;padding:18px;border-radius:12px;text-align:center}
[data-theme="dark"] #overlay .box{background:#05202a;color:var(--text)}
.progress{height:18px;border-radius:10px}
.small-note{font-size:0.9rem;color:#7b8aa3}
</style>
</head><body>
<div id="overlay"><div class="box"><div class="spinner-border" role="status"></div><div id="overlayText" class="mt-2">Preparing...</div></div></div>

<nav class="navbar navbar-expand-lg mb-3">
  <div class="container">
    <a class="navbar-brand text-primary" href="#"><i class="fa fa-database"></i> Backup System</a>
    <div class="d-flex gap-2">
      <div class="form-check form-switch align-items-center">
        <input class="form-check-input" id="themeSwitch" type="checkbox">
        <label class="form-check-label small-note ms-2" for="themeSwitch">Dark mode</label>
      </div>
      <a class="btn btn-primary" href="main_dashboard.php"><i class="fa fa-arrow-left"></i> Back</a>
      <a class="btn btn-outline-secondary" href="index.php?logout=1"><i class="fa fa-sign-out"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container pb-4">
  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card p-3">
        <h5>Start Full Backup</h5>
        <p class="small-note">Backs up each DB into SQL files, then zips them. Email will be sent when complete.</p>

        <div id="dbPreview" class="mb-2 small-note">Click Start to load DB list.</div>

        <div id="progressWrap" style="display:none;" class="mb-2">
          <div class="d-flex justify-content-between mb-1">
            <div id="procTitle" class="fw-bold">Progress</div>
            <div id="procStatus" class="small-note">0 / 0</div>
          </div>
          <div class="progress"><div id="procBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%">0%</div></div>
        </div>

        <div class="mt-3">
          <button id="startBtn" class="btn btn-primary w-100"><i class="fa fa-play me-2"></i> Start Backup</button>
          <button id="cancelBtn" class="btn btn-outline-danger w-100 mt-2" style="display:none;">Cancel</button>
        </div>

        <div class="mt-3">
          <div id="currentDb" class="small-note">Current DB: —</div>
        </div>
      </div>

      <div class="card p-3 mt-3">
        <h6>Live Logs</h6>
        <pre id="logArea" style="height:200px;overflow:auto;background:#f8fafc;border-radius:8px;padding:10px;"></pre>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Backup History</h5>
          <button id="refreshHistory" class="btn btn-sm btn-outline-secondary">Refresh</button>
        </div>
        <hr>
        <div class="table-responsive" style="max-height:420px;overflow:auto">
          <table class="table table-sm table-borderless">
            <thead><tr><th>File</th><th>Size</th><th>Created</th><th></th></tr></thead>
            <tbody id="historyBody">
<?php if(empty($backups)): ?>
  <tr><td colspan="4" class="small-note">No backups yet.</td></tr>
<?php else: ?>
  <?php foreach($backups as $b): ?>
    <tr>
      <td><?php echo htmlspecialchars($b['file']); ?></td>
      <td><?php echo round($b['size']/1024,2) . ' KB'; ?></td>
      <td><?php echo date('Y-m-d H:i', $b['mtime']); ?></td>
      <td><a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($b['path']); ?>" download>Download</a></td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card p-3 mt-3">
        <h6>Settings</h6>
        <div class="small-note">Keep last <strong><?php echo KEEP_LAST; ?></strong> backups. Edit <code>config.php</code> to change email/SMPP settings.</div>
      </div>
    </div>
  </div>
</div>

<script>
const overlay = document.getElementById('overlay');
const overlayText = document.getElementById('overlayText');
const startBtn = document.getElementById('startBtn');
const cancelBtn = document.getElementById('cancelBtn');
const procWrap = document.getElementById('progressWrap');
const procBar = document.getElementById('procBar');
const procStatus = document.getElementById('procStatus');
const procTitle = document.getElementById('procTitle');
const currentDb = document.getElementById('currentDb');
const logArea = document.getElementById('logArea');
const dbPreview = document.getElementById('dbPreview');
const refreshHistory = document.getElementById('refreshHistory');

let cancelled = false;
let running = false;
let stamp = null;

// theme
const themeSwitch = document.getElementById('themeSwitch');
function setTheme(t){ if(t==='dark'){ document.documentElement.setAttribute('data-theme','dark'); themeSwitch.checked=true; } else { document.documentElement.removeAttribute('data-theme'); themeSwitch.checked=false; } localStorage.setItem('theme',t); }
themeSwitch.addEventListener('change', ()=> setTheme(themeSwitch.checked ? 'dark' : 'light'));
setTheme(localStorage.getItem('theme') || 'light');

// load DB list preview
async function loadDbPreview(){
  dbPreview.textContent = 'Loading databases...';
  try{
    const r = await fetch('backup_list.php');
    const j = await r.json();
    if (!j.success) { dbPreview.textContent = 'Error: ' + (j.message||''); return; }
    dbPreview.innerHTML = '<strong>' + j.databases.length + ' databases</strong><ul class="mb-0 mt-2">' + j.databases.slice(0,50).map(d=>'<li>'+d+'</li>').join('') + '</ul>';
  }catch(e){ dbPreview.textContent = 'Failed to load DB list.'; }
}
loadDbPreview();

// helper
function logLine(s){ logArea.textContent += s + "\n"; logArea.scrollTop = logArea.scrollHeight; }

// MAIN process (live progress)
startBtn.addEventListener('click', async () => {
  if (running) return;
  running = true;
  cancelled = false;
  stamp = Date.now().toString();

  Swal.fire({title:'Starting backup', html:'Preparing...', didOpen: ()=> Swal.showLoading(), allowOutsideClick:false});
  startBtn.disabled = true; overlay.style.display = 'flex'; overlayText.textContent = 'Preparing...';
  cancelBtn.style.display = 'inline-block';
  procWrap.style.display = 'block';
  procBar.style.width = '0%'; procBar.textContent = '0%';
  procStatus.textContent = '0 / 0';
  currentDb.textContent = 'Current DB: —';
  logArea.textContent = '';

  // fetch db list
  let dbs = [];
  try {
    const res = await fetch('backup_list.php');
    const j = await res.json();
    if (!j.success) throw new Error(j.message || 'No DB list');
    dbs = j.databases;
    if (dbs.length === 0) throw new Error('No databases to backup.');
  } catch (e) {
    Swal.close();
    Swal.fire('Error','Failed to get databases: '+e.message,'error');
    running=false; startBtn.disabled=false; overlay.style.display='none'; cancelBtn.style.display='none';
    return;
  }

  const total = dbs.length;
  let done = 0, failed = 0;
  procStatus.textContent = `${done} / ${total}`;
  Swal.update({title:'Dumping databases', html:`0 of ${total} completed`});

  // per-db dumps
  for (const db of dbs) {
    if (cancelled) break;
    currentDb.textContent = 'Current DB: ' + db;
    overlayText.textContent = 'Dumping: ' + db;
    logLine('Dumping: ' + db);

    try {
      const r = await fetch('dump_db.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({db:db, stamp:stamp})
      });
      const j = await r.json();
      if (j.success) {
        done++;
        logLine('OK: ' + db + ' (' + (j.size?Math.round(j.size/1024)+' KB':'') + ')');
      } else {
        failed++;
        logLine('FAILED: ' + db + ' — ' + (j.message || j.output || 'error'));
      }
    } catch (e) {
      failed++;
      logLine('FAILED: ' + db + ' — ' + e.message);
    }

    const progressPct = Math.round(((done+failed)/total)*100);
    procBar.style.width = progressPct + '%';
    procBar.textContent = progressPct + '%';
    procStatus.textContent = (done+failed) + ' / ' + total;
    Swal.update({html: `${done+failed} of ${total} processed`});
  }

  if (cancelled) {
    Swal.close();
    Swal.fire('Cancelled','Backup was cancelled','info');
    running=false; startBtn.disabled=false; overlay.style.display='none'; cancelBtn.style.display='none';
    return;
  }

  // finalize zip
  overlayText.textContent = 'Creating ZIP...';
  Swal.update({title:'Finalizing', html:'Compressing files...'});
  procBar.style.width = '90%'; procBar.textContent = '90%';

  try {
    const r = await fetch('finalize_zip.php', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({stamp:stamp})
    });
    const j = await r.json();
    if (!j.success) throw new Error(j.message || 'Zip failed');

    procBar.style.width = '100%'; procBar.textContent = '100%';
    overlayText.textContent = 'Completed';
    Swal.close();
    Swal.fire({
      icon: 'success',
      title: 'Backup Complete',
      html: `File: <b>${j.filename}</b><br>Size: ${j.size_text}<br><a href="${j.file}" class="btn btn-sm btn-primary mt-2" target="_blank">Download</a>`,
      showConfirmButton: true
    });

    // trigger download
    setTimeout(()=> { window.location = j.file; }, 700);
    // refresh history
    setTimeout(()=> location.reload(), 1400);

  } catch (e) {
    Swal.close();
    Swal.fire('Error','Finalize failed: '+(e.message||e),'error');
    logLine('Finalize error: ' + (e.message||JSON.stringify(e)));
  } finally {
    running=false; startBtn.disabled=false; overlay.style.display='none'; cancelBtn.style.display='none';
  }
});

cancelBtn.addEventListener('click', ()=>{
  cancelled = true;
  Swal.fire('Cancel requested','Cancelling after current DB completes...','info');
});

refreshHistory.addEventListener('click', ()=> location.reload());
</script>
</body></html>
