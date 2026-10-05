<?php
// -----------------------------------------------------------------
// LOGIN HANDLER
// -----------------------------------------------------------------
session_start();
include('db/db.php');

// If already logged in, send them to their dashboard
if (isset($_SESSION['username']) && isset($_SESSION['user_type'])) {
    $dest = resolveDashboard(
        $_SESSION['user_type']   ?? '',
        $_SESSION['office_type'] ?? '',
        $_SESSION['username']    ?? ''
    );
    if ($dest !== null) {
        header("Location: $dest");
        exit();
    }
}


function resolveDashboard(string $userType, string $officeType, string $username = ''): ?string
{
    $userType   = strtolower(trim($userType));
    $officeType = strtolower(trim($officeType));
    $username   = strtolower(trim($username));

    // 1) System admins — highest priority
    if ($userType === 'sadmin') {
        return 'controller/sadmin_dashboard.php';
    }

    if ($userType === 'admin') {
        return 'controller/dashboard.php';
    }

    // 2) Special account "user"
    if ($username === 'user') {
        return 'controller/user_dashboard.php';
    }

    // 3) Office type decides the dashboard
    switch ($officeType) {

        case 'port_office':
            return 'controller/port_dashboard.php';

        case 'buffer_godown':
            return 'controller/buffer_dashboard.php';

        case 'factory_office':
            return 'controller/factory_dashboard.php';

        case 'bcic_hq':
            // Determine HQ dashboard from username
            if (str_contains($username, 'bcic_mkt')) {
                return 'controller/mkt_dashboard.php';
            }

            if (str_contains($username, 'bcic_pur')) {
                return 'controller/pur_dashboard.php';
            }

            // Default HQ dashboard
            return 'index.php';
    }

    // 4) Fallback for normal users
    if ($userType === 'user') {
        return 'controller/user_dashboard.php';
    }

    return null;
}



// -----------------------------------------------------------------
// Handle POST login
// -----------------------------------------------------------------
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Please enter both username and password.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    $stmt = mysqli_prepare($conn, "SELECT id, username, password, user_type, office_type
                                   FROM users
                                   WHERE username = ?
                                   LIMIT 1");
    if (!$stmt) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Server error. Please try again.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    $passwordOk = false;
    if ($row && !empty($row['password'])) {
        $passwordOk = password_verify($password, $row['password']);
    }

    if (!$row || !$passwordOk) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Username or password is incorrect.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Transparent rehash if needed
    if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $upd = mysqli_prepare($conn, "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        if ($upd) {
            mysqli_stmt_bind_param($upd, 'si', $newHash, $row['id']);
            mysqli_stmt_execute($upd);
            mysqli_stmt_close($upd);
        }
    }

    // Establish session
    session_regenerate_id(true);
    $_SESSION['user_id']     = (int)$row['id'];
    $_SESSION['username']    = $row['username'];
    $_SESSION['user_type']   = $row['user_type'];
    $_SESSION['office_type'] = $row['office_type'];

    // Route — pass username too
    $dest = resolveDashboard(
        $row['user_type']   ?? '',
        $row['office_type'] ?? '',
        $row['username']    ?? ''
    );
    if ($dest === null) {
        session_unset();
        session_destroy();
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Your account has no assigned dashboard. Contact the administrator.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    header("Location: $dest");
    exit();
}

// -----------------------------------------------------------------
// Pull flash for display
// -----------------------------------------------------------------
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php include_once "include/header.php"; ?>

<!-- ============================================================
     ENHANCED LOGIN STYLES — colorful, iconic, project-themed
     ============================================================ -->
<style>
  :root {
    --bcic-primary:   #751aff;
    --bcic-primary-2: #4a0fb8;
    --bcic-accent:    #00c2a8;
    --bcic-accent-2:  #00a693;
    --bcic-warm:      #ff8a00;
    --bcic-warm-2:    #e67600;
    --bcic-ink:       #0f172a;
    --bcic-muted:     #64748b;
  }

  /* ---------- Page background ---------- */
  body.login-page {
    min-height: 100vh;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(117,26,255,.35), transparent 60%),
      radial-gradient(1000px 500px at 110% 10%, rgba(0,194,168,.30), transparent 55%),
      radial-gradient(900px 500px at 50% 120%, rgba(255,138,0,.25), transparent 60%),
      linear-gradient(135deg, #0b1020 0%, #131a35 50%, #0b1020 100%);
    background-attachment: fixed;
    overflow-x: hidden;
    position: relative;
  }

  /* Subtle floating blobs */
  body.login-page::before,
  body.login-page::after {
    content: "";
    position: fixed;
    border-radius: 50%;
    filter: blur(90px);
    opacity: .45;
    pointer-events: none;
    z-index: 0;
  }
  body.login-page::before {
    width: 420px; height: 420px;
    background: linear-gradient(135deg, #751aff, #00c2a8);
    top: -120px; left: -120px;
    animation: floatBlob 14s ease-in-out infinite;
  }
  body.login-page::after {
    width: 480px; height: 480px;
    background: linear-gradient(135deg, #ff8a00, #751aff);
    bottom: -160px; right: -140px;
    animation: floatBlob 18s ease-in-out infinite reverse;
  }
  @keyframes floatBlob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50%      { transform: translate(30px, -25px) scale(1.05); }
  }

  .login-wrap {
    position: relative;
    z-index: 2;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 15px;
  }

  /* ---------- Brand panel (left) ---------- */
  .brand-panel {
    color: #fff;
    padding: 30px 25px;
  }
  .brand-logo-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: linear-gradient(135deg, rgba(255,255,255,.12), rgba(255,255,255,.05));
    border: 1px solid rgba(255,255,255,.18);
    backdrop-filter: blur(8px);
    padding: 10px 18px;
    border-radius: 999px;
    font-weight: 700;
    letter-spacing: .5px;
    font-size: 14px;
    margin-bottom: 22px;
  }
  .brand-logo-badge i { color: var(--bcic-accent); }

  .brand-title {
    font-weight: 900;
    font-size: 42px;
    line-height: 1.15;
    margin: 0 0 14px 0;
    background: linear-gradient(135deg, #ffffff 0%, #c4b5fd 45%, #67e8f9 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 4px 30px rgba(117,26,255,.35);
  }
  .brand-subtitle {
    font-size: 16px;
    color: #cbd5e1;
    max-width: 460px;
    line-height: 1.6;
    margin-bottom: 26px;
  }

  .feature-list {
    list-style: none;
    padding: 0;
    margin: 0 0 10px 0;
    display: grid;
    gap: 12px;
  }
  .feature-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #e2e8f0;
    font-size: 14.5px;
  }
  .feature-list li .f-icon {
    width: 38px; height: 38px;
    border-radius: 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,.35);
    flex-shrink: 0;
  }
  .f-icon.g1 { background: linear-gradient(135deg, #751aff, #4a0fb8); }
  .f-icon.g2 { background: linear-gradient(135deg, #00c2a8, #00a693); }
  .f-icon.g3 { background: linear-gradient(135deg, #ff8a00, #e67600); }

  /* ---------- Login card ---------- */
  .login-card {
    border: 0;
    border-radius: 22px;
    overflow: hidden;
    background: #ffffff;
    box-shadow:
      0 30px 60px -20px rgba(15,23,42,.55),
      0 0 0 1px rgba(255,255,255,.06);
    position: relative;
    animation: cardIn .6s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes cardIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to   { opacity: 1; transform: translateY(0)    scale(1); }
  }

  .login-card .card-header-gradient {
    padding: 22px 22px 60px 22px;
    background:
      radial-gradient(600px 200px at 10% -40%, rgba(255,255,255,.25), transparent 60%),
      linear-gradient(135deg, var(--bcic-primary) 0%, var(--bcic-primary-2) 55%, #2a0a75 100%);
    color: #fff;
    position: relative;
    text-align: center;
  }
  .login-card .card-header-gradient .sys-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 11.5px;
    letter-spacing: 1.6px;
    text-transform: uppercase;
    font-weight: 700;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.25);
    padding: 6px 12px;
    border-radius: 999px;
    margin-bottom: 14px;
  }
  .login-card .card-header-gradient h4 {
    font-weight: 900;
    margin: 0;
    font-size: 22px;
    letter-spacing: .3px;
  }
  .login-card .card-header-gradient small {
    display: block;
    margin-top: 6px;
    color: #ddd6fe;
    font-size: 12.5px;
    letter-spacing: .4px;
  }

  /* Floating circular logo */
  .avatar-wrap {
    margin-top: -50px;
    display: flex;
    justify-content: center;
    position: relative;
    z-index: 2;
  }
  .avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: #ffffff;
    padding: 10px;
    object-fit: contain;
    box-shadow:
      0 12px 30px rgba(117,26,255,.35),
      0 0 0 6px rgba(255,255,255,.9),
      0 0 0 8px rgba(117,26,255,.18);
    animation: pulseRing 2.6s ease-in-out infinite;
  }
  @keyframes pulseRing {
    0%, 100% { box-shadow: 0 12px 30px rgba(117,26,255,.35), 0 0 0 6px rgba(255,255,255,.9), 0 0 0 8px rgba(117,26,255,.18); }
    50%      { box-shadow: 0 12px 30px rgba(117,26,255,.55), 0 0 0 6px rgba(255,255,255,.9), 0 0 0 12px rgba(0,194,168,.25); }
  }

  .login-card .card-body { padding: 24px 26px 26px 26px; }

  /* ---------- Inputs ---------- */
  .form-floating > .form-control {
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    padding-left: 48px;
    height: 56px;
    background: #f8fafc;
    transition: all .2s ease;
    font-weight: 500;
  }
  .form-floating > .form-control:focus {
    border-color: var(--bcic-primary);
    box-shadow: 0 0 0 4px rgba(117,26,255,.14);
    background: #ffffff;
  }
  .form-floating > label {
    padding-left: 48px;
    color: #94a3b8;
    font-weight: 500;
  }
  .input-icon {
    position: absolute;
    top: 50%;
    left: 16px;
    transform: translateY(-50%);
    font-size: 17px;
    color: var(--bcic-primary);
    pointer-events: none;
    z-index: 5;
  }
  .form-floating { position: relative; }

  /* Password eye toggle */
  .toggle-pass {
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: #94a3b8;
    font-size: 16px;
    cursor: pointer;
    z-index: 5;
    padding: 6px;
    border-radius: 8px;
    transition: color .15s ease, background .15s ease;
  }
  .toggle-pass:hover { color: var(--bcic-primary); background: rgba(117,26,255,.08); }

  .form-check-input:checked {
    background-color: var(--bcic-primary);
    border-color: var(--bcic-primary);
  }
  .form-check-label { color: var(--bcic-muted); font-size: 14px; }

  /* ---------- Sign-in button ---------- */
  .btn-signin {
    width: 100%;
    border: 0;
    border-radius: 14px;
    padding: 14px 18px;
    font-weight: 800;
    letter-spacing: .5px;
    color: #fff;
    background: linear-gradient(135deg, var(--bcic-primary) 0%, var(--bcic-primary-2) 50%, var(--bcic-accent) 130%);
    background-size: 180% 180%;
    box-shadow: 0 12px 26px -8px rgba(117,26,255,.65);
    transition: transform .15s ease, box-shadow .2s ease, background-position .5s ease;
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  .btn-signin:hover {
    color: #fff;
    transform: translateY(-2px);
    background-position: 100% 0;
    box-shadow: 0 18px 34px -10px rgba(0,194,168,.55);
  }
  .btn-signin:active { transform: translateY(0); }
  .btn-signin .fa { transition: transform .3s ease; }
  .btn-signin:hover .fa { transform: translateX(3px); }

  /* ---------- Card footer ---------- */
  .login-card .card-footer {
    background: #f8fafc;
    border-top: 1px solid #eef2f7;
    text-align: center;
    color: var(--bcic-muted);
    font-size: 12.5px;
    padding: 14px 18px;
    letter-spacing: .3px;
  }
  .login-card .card-footer i { color: var(--bcic-primary); }

  /* ---------- Responsive ---------- */
  @media (max-width: 991.98px) {
    .brand-panel { text-align: center; padding: 10px 15px 26px; }
    .brand-title { font-size: 32px; }
    .brand-subtitle { margin-left: auto; margin-right: auto; }
    .feature-list { justify-content: center; }
    .feature-list li { justify-content: center; }
  }

  /* ---------- Small "trust" strip under card ---------- */
  .trust-strip {
    margin-top: 16px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px 18px;
    color: #cbd5e1;
    font-size: 12.5px;
  }
  .trust-strip span { display: inline-flex; align-items: center; gap: 6px; }
  .trust-strip i { color: var(--bcic-accent); }
</style>

<div class="login-page">
  <div class="login-wrap">
    <div class="container">
      <div class="row align-items-center justify-content-center g-4">

        <!-- ===================== LEFT: BRAND PANEL ===================== -->
        <div class="col-lg-6 col-xl-6">
          <div class="brand-panel">
            <div class="brand-logo-badge">
              <i class="fa fa-industry"></i>
              <span>BCIC &middot; DFMS</span>
            </div>

            <h1 class="brand-title">
              Digital Fertilizer<br>Monitoring System
            </h1>

            <p class="brand-subtitle">
              Real-time monitoring of urea fertilizer — production, buffer stock,
              port transit, and dealer distribution — under one unified platform
              for Bangladesh Chemical Industries Corporation.
            </p>

            <ul class="feature-list">
              <li>
                <span class="f-icon g1"><i class="fa fa-line-chart"></i></span>
                <span>Live production &amp; stock analytics across all factories</span>
              </li>
              <li>
                <span class="f-icon g2"><i class="fa fa-truck"></i></span>
                <span>Track buffer transfers &amp; fertilizer in transit</span>
              </li>
              <li>
                <span class="f-icon g3"><i class="fa fa-anchor"></i></span>
                <span>Import allotment, port stock &amp; dealer delivery reports</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- ===================== RIGHT: LOGIN CARD ===================== -->
        <div class="col-lg-5 col-xl-5">
          <div class="card login-card">

            <!-- Gradient header -->
            <div class="card-header-gradient">
              <div class="sys-tag">
                <i class="fa fa-shield"></i> Secure Sign In
              </div>
              <h4>Digital Fertilizer Monitoring System</h4>
              <small>Bangladesh Chemical Industries Corporation (BCIC)</small>
            </div>

            <!-- Floating logo -->
            <div class="avatar-wrap">
              <img src="images/bcic_logo.png" alt="BCIC" class="avatar">
            </div>

            <div class="card-body">

              <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>"
                    method="POST" class="needs-validation" novalidate>

                <!-- Username -->
                <div class="form-floating mb-3 mt-2">
                  <i class="fa fa-user-circle-o input-icon"></i>
                  <input type="text" class="form-control" id="floatingInput"
                         placeholder="Enter Username" name="username" required autofocus
                         autocomplete="username">
                  <label for="floatingInput">Username</label>
                </div>

                <!-- Password with eye toggle -->
                <div class="form-floating mb-3">
                  <i class="fa fa-lock input-icon"></i>
                  <input type="password" class="form-control" id="floatingPassword"
                         placeholder="Enter password" name="password" required
                         autocomplete="current-password">
                  <label for="floatingPassword">Password</label>
                  <button type="button" class="toggle-pass" id="togglePassBtn"
                          aria-label="Show password">
                    <i class="fa fa-eye" id="togglePassIcon"></i>
                  </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <span class="text-muted" style="font-size:12.5px;">
                    <i class="fa fa-lock text-success"></i> Encrypted
                  </span>
                </div>

                <button type="submit" class="btn btn-signin" name="login">
                  <i class="fa fa-sign-in"></i>
                  <span>Sign In</span>
                </button>
              </form>

              <div class="trust-strip">
                <span><i class="fa fa-check-circle"></i> Authorized Access Only</span>
                <span><i class="fa fa-shield"></i> SSL Protected</span>
                <span><i class="fa fa-clock-o"></i> 24/7 Monitoring</span>
              </div>
            </div>

            <div class="card-footer">
              <i class="fa fa-code"></i>
              Design &amp; Developed by <b>ICT Division, BCIC</b>.
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- ============================================================
     Password visibility toggle (UI only — no functional change)
     ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var btn  = document.getElementById('togglePassBtn');
  var icon = document.getElementById('togglePassIcon');
  var inp  = document.getElementById('floatingPassword');
  if (btn && icon && inp) {
    btn.addEventListener('click', function () {
      var showing = inp.type === 'text';
      inp.type = showing ? 'password' : 'text';
      icon.className = showing ? 'fa fa-eye' : 'fa fa-eye-slash';
      btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });
  }
});
</script>

<?php if ($flash): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var toastConfig = {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: function (toast) {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    Swal.fire(Object.assign({
        icon:  <?= json_encode($flash['type']); ?>,
        title: <?= json_encode($flash['msg']); ?>
    }, toastConfig));
});
</script>
<?php endif; ?>