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
     ENHANCED LOGIN STYLES — high-contrast, colorful, project-themed
     ============================================================ -->
<style>
  :root {
    --bcic-primary:   #7c3aed;
    --bcic-primary-2: #5b21b6;
    --bcic-accent:    #14b8a6;
    --bcic-accent-2:  #0f766e;
    --bcic-warm:      #f97316;
    --bcic-warm-2:    #c2410c;
    --bcic-ink:       #0b1220;
    --bcic-text:      #e5e7eb;   /* high-contrast body text on dark bg */
    --bcic-heading:   #ffffff;
  }

  /* ---------- Page background (slightly deeper so text pops) ---------- */
  body.login-page {
    min-height: 100vh;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(124,58,237,.35), transparent 60%),
      radial-gradient(1000px 500px at 110% 10%, rgba(20,184,166,.28), transparent 55%),
      radial-gradient(900px 500px at 50% 120%, rgba(249,115,22,.22), transparent 60%),
      linear-gradient(135deg, #070b18 0%, #0f1530 50%, #070b18 100%);
    background-attachment: fixed;
    overflow-x: hidden;
    position: relative;
    font-family: "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
  }

  /* Floating blobs */
  body.login-page::before,
  body.login-page::after {
    content: "";
    position: fixed;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .40;
    pointer-events: none;
    z-index: 0;
  }
  body.login-page::before {
    width: 440px; height: 440px;
    background: linear-gradient(135deg, #7c3aed, #14b8a6);
    top: -140px; left: -140px;
    animation: floatBlob 14s ease-in-out infinite;
  }
  body.login-page::after {
    width: 480px; height: 480px;
    background: linear-gradient(135deg, #f97316, #7c3aed);
    bottom: -170px; right: -150px;
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
    color: var(--bcic-text);
    padding: 30px 25px;
  }

  /* Brand badge — clearer border + brighter text */
  .brand-logo-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,.10);
    border: 1.5px solid rgba(255,255,255,.28);
    backdrop-filter: blur(10px);
    padding: 10px 18px;
    border-radius: 999px;
    font-weight: 800;
    letter-spacing: 1.2px;
    font-size: 13px;
    color: #ffffff;
    text-transform: uppercase;
    margin-bottom: 22px;
    box-shadow: 0 6px 20px rgba(0,0,0,.35);
  }
  .brand-logo-badge i {
    color: #5eead4; /* brighter teal for contrast */
    font-size: 15px;
  }

  /* Big title — brighter gradient, stronger shadow */
  .brand-title {
    font-weight: 900;
    font-size: 44px;
    line-height: 1.12;
    margin: 0 0 16px 0;
    background: linear-gradient(135deg, #ffffff 0%, #ddd6fe 40%, #99f6e4 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: ;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 6px 20px rgba(124,58,237,.45));
  }

  /* Subtitle — brighter text for readability */
  .brand-subtitle {
    font-size: 15.5px;
    color: #cbd5e1;
    max-width: 470px;
    line-height: 1.65;
    margin-bottom: 26px;
    font-weight: 400;
  }

  /* Feature list — brighter text + clearer icons */
  .feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 14px;
  }
  .feature-list li {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #f1f5f9;                    /* was #e2e8f0, now brighter */
    font-size: 14.5px;
    font-weight: 500;
    line-height: 1.45;
  }
  .feature-list li .f-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
    box-shadow: 0 8px 20px rgba(0,0,0,.40);
    flex-shrink: 0;
  }
  .f-icon.g1 { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
  .f-icon.g2 { background: linear-gradient(135deg, #14b8a6, #0f766e); }
  .f-icon.g3 { background: linear-gradient(135deg, #f97316, #c2410c); }

  /* ---------- Login card ---------- */
  .login-card {
    border: 0;
    border-radius: 22px;
    overflow: hidden;
    background: #ffffff;
    box-shadow:
      0 30px 60px -20px rgba(0,0,0,.70),
      0 0 0 1px rgba(255,255,255,.08);
    position: relative;
    animation: cardIn .6s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes cardIn {
    from { opacity: 0; transform: translateY(14px) scale(.98); }
    to   { opacity: 1; transform: translateY(0)    scale(1); }
  }

  /* Card header — darker gradient for stronger white-text contrast */
  .login-card .card-header-gradient {
    padding: 24px 22px 62px 22px;
    background:
      radial-gradient(600px 200px at 10% -40%, rgba(255,255,255,.18), transparent 60%),
      linear-gradient(135deg, #5b21b6 0%, #4c1d95 55%, #2e1065 100%);
    color: #ffffff;
    position: relative;
    text-align: center;
  }
  .login-card .card-header-gradient .sys-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    font-weight: 800;
    color: #ffffff;
    background: rgba(255,255,255,.16);
    border: 1.5px solid rgba(255,255,255,.35);
    padding: 6px 14px;
    border-radius: 999px;
    margin-bottom: 14px;
  }
  .login-card .card-header-gradient .sys-tag i {
    color: #5eead4;
  }
  .login-card .card-header-gradient h4 {
    font-weight: 900;
    margin: 0;
    font-size: 21px;
    letter-spacing: .3px;
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0,0,0,.35);
  }
  .login-card .card-header-gradient small {
    display: block;
    margin-top: 8px;
    color: #ddd6fe;                    /* brighter lavender */
    font-size: 12.5px;
    letter-spacing: .5px;
    font-weight: 500;
  }

  /* Floating circular logo */
  .avatar-wrap {
    margin-top: -52px;
    display: flex;
    justify-content: center;
    position: relative;
    z-index: 2;
  }
  .avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #ffffff;
    padding: 10px;
    object-fit: contain;
    box-shadow:
      0 12px 30px rgba(91,33,182,.50),
      0 0 0 6px rgba(255,255,255,.95),
      0 0 0 8px rgba(124,58,237,.28);
    animation: pulseRing 2.6s ease-in-out infinite;
  }
  @keyframes pulseRing {
    0%, 100% { box-shadow: 0 12px 30px rgba(91,33,182,.50), 0 0 0 6px rgba(255,255,255,.95), 0 0 0 8px rgba(124,58,237,.28); }
    50%      { box-shadow: 0 12px 30px rgba(91,33,182,.60), 0 0 0 6px rgba(255,255,255,.95), 0 0 0 12px rgba(20,184,166,.35); }
  }

  .login-card .card-body { padding: 26px 28px 28px 28px; }

  /* ---------- Inputs ---------- */
  .form-floating > .form-control {
    border-radius: 14px;
    border: 1.5px solid #dbe2ea;
    padding-left: 50px;
    height: 58px;
    background: #f8fafc;
    transition: all .2s ease;
    font-weight: 500;
    color: #0f172a;
    font-size: 15px;
  }
  .form-floating > .form-control::placeholder { color: transparent; }
  .form-floating > .form-control:focus {
    border-color: var(--bcic-primary);
    box-shadow: 0 0 0 4px rgba(124,58,237,.16);
    background: #ffffff;
  }
  .form-floating > label {
    padding-left: 50px;
    color: #64748b;
    font-weight: 500;
    font-size: 14.5px;
  }
  .form-floating > .form-control:focus ~ label,
  .form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: var(--bcic-primary);
    font-weight: 600;
  }
  .input-icon {
    position: absolute;
    top: 50%;
    left: 18px;
    transform: translateY(-50%);
    font-size: 18px;
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
    color: #64748b;
    font-size: 17px;
    cursor: pointer;
    z-index: 5;
    padding: 8px;
    border-radius: 10px;
    transition: color .15s ease, background .15s ease;
  }
  .toggle-pass:hover { color: var(--bcic-primary); background: rgba(124,58,237,.10); }

  /* ---------- Remember me + Encrypted ---------- */
  .form-check-input {
    border: 1.5px solid #cbd5e1;
    width: 18px;
    height: 18px;
    margin-top: 2px;
  }
  .form-check-input:checked {
    background-color: var(--bcic-primary);
    border-color: var(--bcic-primary);
  }
  .form-check-input:focus {
    box-shadow: 0 0 0 4px rgba(124,58,237,.18);
    border-color: var(--bcic-primary);
  }
  .form-check-label {
    color: #334155;                   /* darker, clearer */
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
  }
  .enc-tag {
    color: #475569;                   /* darker */
    font-size: 12.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .enc-tag i { color: #059669; }

  /* ---------- Sign-in button ---------- */
  .btn-signin {
    width: 100%;
    border: 0;
    border-radius: 14px;
    padding: 15px 18px;
    font-weight: 800;
    letter-spacing: .6px;
    font-size: 15px;
    color: #ffffff;
    background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 50%, #14b8a6 130%);
    background-size: 180% 180%;
    box-shadow: 0 14px 30px -8px rgba(124,58,237,.65);
    transition: transform .15s ease, box-shadow .2s ease, background-position .5s ease;
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-shadow: 0 1px 2px rgba(0,0,0,.20);
  }
  .btn-signin:hover {
    color: #ffffff;
    transform: translateY(-2px);
    background-position: 100% 0;
    box-shadow: 0 20px 36px -10px rgba(20,184,166,.60);
  }
  .btn-signin:active { transform: translateY(0); }
  .btn-signin .fa { transition: transform .3s ease; }
  .btn-signin:hover .fa { transform: translateX(3px); }

  /* ---------- Card footer ---------- */
  .login-card .card-footer {
    background: #f1f5f9;
    border-top: 1px solid #e2e8f0;
    text-align: center;
    color: #475569;                    /* darker for readability */
    font-size: 12.5px;
    padding: 14px 18px;
    letter-spacing: .3px;
    font-weight: 500;
  }
  .login-card .card-footer i { color: var(--bcic-primary); margin-right: 4px; }
  .login-card .card-footer b { color: #1e293b; }

  /* ---------- Responsive ---------- */
  @media (max-width: 991.98px) {
    .brand-panel { text-align: center; padding: 10px 15px 26px; }
    .brand-title { font-size: 34px; }
    .brand-subtitle { margin-left: auto; margin-right: auto; }
    .feature-list { justify-content: center; }
    .feature-list li { justify-content: center; }
  }
  @media (max-width: 575.98px) {
    .brand-title { font-size: 28px; }
    .login-card .card-header-gradient h4 { font-size: 18px; }
  }

  /* ---------- Trust strip ---------- */
  .trust-strip {
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px dashed #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px 18px;
    color: #475569;                    /* darker, more readable */
    font-size: 12.5px;
    font-weight: 600;
  }
  .trust-strip span { display: inline-flex; align-items: center; gap: 6px; }
  .trust-strip i { color: #059669; font-size: 13px; }
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
                  <span class="enc-tag">
                    <i class="fa fa-lock"></i> Encrypted
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