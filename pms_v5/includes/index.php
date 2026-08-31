<?php
// Start the session
session_name('pms_db');
session_start();

include_once "includes/header_login.php";
require_once("config/config.php");
require_once("db/db.php");

// Maps each user_type value from the `user` table to its dashboard.
// Add/rename entries here if a role or filename ever changes.
$dashboard_by_role = [
    'pharmacist'     => 'includes/pharmacist_dashboard.php',
    'doctor'         => 'includes/doctor_dashboard.php',
    'store_incharge' => 'includes/store_dashboard.php',
    'sadmin'         => 'includes/sadmin_dashboard.php',
    'user'           => 'includes/user_dashboard.php',
];

function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Handle multiple IP addresses (comma-separated, common in proxy setups)
        $ipaddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    // Convert IPv6 localhost address to IPv4 localhost
    if ($ipaddress == '::1') {
        $ipaddress = '127.0.0.1';
    }
    return trim($ipaddress);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = sha1($password);

    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['username']  = $row['username'];

        if (!empty($_POST['remember'])) {
            setcookie('user_login', $_POST['username'], time() + (10 * 365 * 24 * 60 * 60), '/');
            setcookie('userpassword', $_POST['password'], time() + (10 * 365 * 24 * 60 * 60), '/');
        } else {
            if (isset($_COOKIE['user_login'])) {
                setcookie('user_login', '');
            }
            if (isset($_COOKIE['userpassword'])) {
                setcookie('userpassword', '');
            }
        }

        // --- Log the login event ---
        $login_date_time = date('Y-m-d H:i:s');
        $Ip = getClientIP();
        $code = rand(10000, 99999);
        $_SESSION['code'] = $code;

        $query = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time, code) 
                  VALUES ('" . $_SESSION['username'] . "', '$password', '" . $_SESSION['user_type'] . "', '$Ip', '$login_date_time', '$code')";
        $query_run = mysqli_query($conn, $query);

        if (!$query_run) {
            echo "Error: " . mysqli_error($conn);
            exit();
        }

        // --- Route to the correct dashboard based on user_type ---
        $role = $_SESSION['user_type'];

        if (isset($dashboard_by_role[$role])) {
            $target = $dashboard_by_role[$role] . '?code=' . $_SESSION['code'];
            echo "<script>window.location.href='" . $target . "'</script>";
        } else {
            // Unknown/unmapped role - fail safe rather than silently redirecting nowhere
            echo '<html><head>';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '</head><body>';
            echo '<script type="text/javascript">
                    Swal.fire({
                        title: "No dashboard configured for this account type",
                        icon: "error",
                        confirmButtonColor: "#dc3545"
                    }).then(function() {
                        window.location.href = "index.php";
                    });
                  </script>';
            echo '</body></html>';
        }
        exit();
    } else {
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo '<script type="text/javascript">
                Swal.fire({
                    title: "Username and Password is Incorrect",                
                    icon: "warning",
                    confirmButtonColor: "#dc3545"
                }).then(function() {
                    window.location.href = "index.php";
                });
              </script>';
        echo '</body></html>';
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BCIC Patient Management System — Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --teal-deep:#0B3D3A;
    --teal-mid:#124F4A;
    --paper:#F6F3EC;
    --ink:#1C2B2A;
    --coral:#E8583A;
    --coral-dim:#c8492e;
    --sage:#8FA6A0;
    --line:rgba(255,255,255,0.14);
  }

  *{box-sizing:border-box;}

  html,body{
    margin:0;
    padding:0;
    height:100%;
    font-family:'Inter',sans-serif;
    background:var(--paper);
    color:var(--ink);
  }

  .screen{
    min-height:100vh;
    display:grid;
    grid-template-columns: 1.05fr 1fr;
  }

  /* ---------- LEFT PANEL ---------- */
  .brand-panel{
    position:relative;
    background:
      radial-gradient(120% 140% at 15% 10%, var(--teal-mid) 0%, var(--teal-deep) 55%, #082A27 100%);
    color:var(--paper);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:56px 64px;
    overflow:hidden;
  }

  .brand-panel::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:
      radial-gradient(circle at 85% 15%, rgba(255,255,255,0.05) 0, transparent 40%),
      radial-gradient(circle at 10% 90%, rgba(232,88,58,0.10) 0, transparent 45%);
    pointer-events:none;
  }

  .brand-mark{
    display:flex;
    align-items:center;
    gap:14px;
    z-index:2;
  }

  .brand-mark img{
    width:46px;
    height:46px;
    border-radius:10px;
    background:var(--paper);
    padding:6px;
  }

  .brand-mark span{
    font-family:'Inter',sans-serif;
    letter-spacing:0.14em;
    text-transform:uppercase;
    font-size:12.5px;
    color:var(--sage);
    font-weight:600;
  }

  .brand-copy{
    z-index:2;
    max-width:460px;
  }

  .eyebrow{
    font-size:13px;
    letter-spacing:0.16em;
    text-transform:uppercase;
    color:var(--coral);
    font-weight:600;
    margin-bottom:18px;
  }

  .brand-copy h1{
    font-family:'Fraunces',serif;
    font-weight:600;
    font-size:42px;
    line-height:1.12;
    margin:0 0 20px 0;
    letter-spacing:-0.01em;
  }

  .brand-copy p{
    font-size:15.5px;
    line-height:1.65;
    color:#CFE0DC;
    margin:0;
    max-width:400px;
  }

  /* ECG signature */
  .pulse-wrap{
    z-index:2;
    margin-top:44px;
    padding-top:28px;
    border-top:1px solid var(--line);
  }

  .pulse-wrap svg{
    width:100%;
    height:64px;
    display:block;
  }

  .pulse-line{
    fill:none;
    stroke:var(--coral);
    stroke-width:2.5;
    stroke-linecap:round;
    stroke-linejoin:round;
    stroke-dasharray:900;
    stroke-dashoffset:900;
    animation:draw-pulse 2.4s cubic-bezier(.4,0,.2,1) forwards,
              ambient-pulse 3.6s ease-in-out 2.4s infinite;
  }

  .pulse-baseline{
    stroke:rgba(255,255,255,0.16);
    stroke-width:1;
    fill:none;
  }

  @keyframes draw-pulse{
    to{ stroke-dashoffset:0; }
  }

  @keyframes ambient-pulse{
    0%, 100%{ opacity:0.85; }
    50%{ opacity:1; filter:drop-shadow(0 0 6px rgba(232,88,58,0.5)); }
  }

  .pulse-caption{
    display:flex;
    justify-content:space-between;
    margin-top:10px;
    font-size:11.5px;
    letter-spacing:0.08em;
    text-transform:uppercase;
    color:var(--sage);
  }

  .stat-row{
    z-index:2;
    display:flex;
    gap:36px;
    margin-top:40px;
  }

  .stat-row div{ }

  .stat-row strong{
    display:block;
    font-family:'Fraunces',serif;
    font-size:24px;
    font-weight:600;
    color:var(--paper);
  }

  .stat-row span{
    font-size:12px;
    color:var(--sage);
    letter-spacing:0.02em;
  }

  /* ---------- RIGHT PANEL ---------- */
  .form-panel{
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px 40px;
    background:var(--paper);
  }

  .login-card{
    width:100%;
    max-width:392px;
  }

  .login-card .top-label{
    font-size:12.5px;
    letter-spacing:0.14em;
    text-transform:uppercase;
    color:var(--coral);
    font-weight:600;
    margin-bottom:10px;
  }

  .login-card h2{
    font-family:'Fraunces',serif;
    font-size:30px;
    font-weight:600;
    margin:0 0 8px 0;
    color:var(--ink);
  }

  .login-card .sub{
    font-size:14px;
    color:#5C6E6A;
    margin:0 0 32px 0;
    line-height:1.5;
  }

  .field{
    margin-bottom:18px;
  }

  .field label{
    display:block;
    font-size:12.5px;
    font-weight:600;
    letter-spacing:0.03em;
    color:var(--ink);
    margin-bottom:7px;
  }

  .field .input-wrap{
    position:relative;
  }

  .field input[type="text"],
  .field input[type="password"]{
    width:100%;
    padding:13px 16px;
    border-radius:10px;
    border:1.5px solid #DDD7C7;
    background:#FFFEFB;
    font-family:'Inter',sans-serif;
    font-size:14.5px;
    color:var(--ink);
    outline:none;
    transition:border-color .18s ease, box-shadow .18s ease;
  }

  .field input:focus{
    border-color:var(--teal-mid);
    box-shadow:0 0 0 4px rgba(18,79,74,0.10);
  }

  .field input::placeholder{
    color:#A8A296;
  }

  .row-between{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin:4px 0 26px 0;
  }

  .remember{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:13px;
    color:#5C6E6A;
    cursor:pointer;
    user-select:none;
  }

  .remember input{
    accent-color: var(--coral);
    width:15px;
    height:15px;
  }

  .forgot{
    font-size:13px;
    color:var(--teal-mid);
    text-decoration:none;
    font-weight:600;
  }
  .forgot:hover{ text-decoration:underline; }

  button.login-btn{
    width:100%;
    padding:14px 16px;
    border:none;
    border-radius:10px;
    background:var(--coral);
    color:#FFF7F3;
    font-family:'Inter',sans-serif;
    font-size:15px;
    font-weight:700;
    letter-spacing:0.01em;
    cursor:pointer;
    transition:background .16s ease, transform .16s ease;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:9px;
  }

  button.login-btn:hover{
    background:var(--coral-dim);
  }

  button.login-btn:active{
    transform:translateY(1px);
  }

  button.login-btn:focus-visible,
  .field input:focus-visible,
  .forgot:focus-visible{
    outline:3px solid var(--teal-mid);
    outline-offset:2px;
  }

  .foot-note{
    margin-top:28px;
    text-align:center;
    font-size:12px;
    color:#8B968F;
  }

  /* ---------- RESPONSIVE ---------- */
  @media (max-width: 880px){
    .screen{
      grid-template-columns:1fr;
    }
    .brand-panel{
      padding:40px 28px;
      min-height:280px;
    }
    .brand-copy h1{ font-size:30px; }
    .stat-row{ display:none; }
    .form-panel{ padding:36px 24px 56px; }
  }

  @media (prefers-reduced-motion: reduce){
    .pulse-line{
      animation:none;
      stroke-dashoffset:0;
    }
  }
</style>
</head>
<body>

<div class="screen">

  <!-- LEFT: BRAND / SIGNATURE -->
  <div class="brand-panel">
    <div class="brand-mark">
      <img src="assets/img/bcic_logo.png" alt="BCIC Logo">
      <span>BCIC &nbsp;·&nbsp; Patient Management System</span>
    </div>

    <div class="brand-copy">
      <div class="eyebrow">Staff Portal</div>
      <h1>Every record,<br>one steady signal.</h1>
      <p>Sign in to manage patient files, prescriptions, and pharmacy stock from a single, secure dashboard.</p>
    </div>

    <div>
      <div class="pulse-wrap">
        <svg viewBox="0 0 600 80" preserveAspectRatio="none">
          <path class="pulse-baseline" d="M0,40 L600,40"/>
          <path class="pulse-line" d="M0,40 L120,40 L145,40 L160,12 L178,68 L196,18 L214,40 L240,40 L600,40"/>
        </svg>
        <div class="pulse-caption">
          <span>System status</span>
          <span>Online</span>
        </div>
      </div>

      <div class="stat-row">
        <div><strong>24/7</strong><span>Pharmacy access</span></div>
        <div><strong>Secure</strong><span>Session logging</span></div>
        <div><strong>Unified</strong><span>Patient records</span></div>
      </div>
    </div>
  </div>

  <!-- RIGHT: LOGIN FORM -->
  <div class="form-panel">
    <div class="login-card">
      <div class="top-label">Welcome back</div>
      <h2>Sign in to your account</h2>
      <p class="sub">Enter your credentials to access the BCIC patient management dashboard.</p>

      <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="login">
        <div class="field">
          <label for="username">Username</label>
          <div class="input-wrap">
            <input id="username" name="username" type="text" placeholder="Enter your username" required autocomplete="username">
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input id="password" name="password" type="password" placeholder="Enter your password" required autocomplete="current-password">
          </div>
        </div>

        <div class="row-between">
          <label class="remember">
            <input type="checkbox" name="remember" value="1">
            Remember me
          </label>
          <a href="#" class="forgot">Forgot password?</a>
        </div>

        <button class="login-btn" type="submit" name="login">Sign in</button>
      </form>

      <p class="foot-note">BCIC Patient Management System &nbsp;·&nbsp; Authorized personnel only</p>
    </div>
  </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
//include('includes/footer.php');
?>
</body>
</html>
