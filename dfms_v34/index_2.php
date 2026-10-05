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

<!-- Font Awesome for Icon support -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  body {
    background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .dfms-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease;
  }
  .dfms-header {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #ffffff;
    padding: 20px 15px;
    text-align: center;
    font-weight: 700;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  .dfms-header h5 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
    text-transform: uppercase;
  }
  .avatar-container {
    text-align: center;
    margin: 20px 0 10px 0;
  }
  .avatar-container img {
    width: 90px;
    height: 90px;
    object-fit: contain;
    border-radius: 50%;
    background: #ffffff;
    padding: 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    border: 3px solid #11998e;
  }
  .input-icon-group {
    position: relative;
  }
  .input-icon-group i.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #11998e;
    z-index: 5;
  }
  .input-icon-group .form-control {
    padding-left: 45px;
    border-radius: 10px;
    border: 1px solid #ced4da;
    height: 50px;
  }
  .input-icon-group .form-control:focus {
    border-color: #11998e;
    box-shadow: 0 0 0 0.25rem rgba(17, 153, 142, 0.25);
  }
  .toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    z-index: 5;
  }
  .btn-dfms {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    height: 48px;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  .btn-dfms:hover {
    background: linear-gradient(135deg, #0e8379 0%, #2ecc71 100%);
    box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
    color: #fff;
  }
  .dfms-footer {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    font-size: 0.85rem;
    color: #6c757d;
    padding: 12px;
  }
</style>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">

      <div class="card dfms-card">
        <div class="dfms-header">
          <i class="fa-solid fa-leaf me-1"></i>
          <span>Digital Fertilizer Monitoring System</span>
          <div style="font-size: 0.8rem; font-weight: 400; opacity: 0.9;">(DFMS)</div>
        </div>

        <div class="card-body px-4 py-3">
          <div class="avatar-container">
            <img src="images/bcic_logo.png" alt="BCIC Logo">
          </div>

          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            
            <!-- Username Input -->
            <div class="mb-3 input-icon-group">
              <i class="fa-solid fa-user input-icon"></i>
              <input type="text" class="form-control" id="username"
                     placeholder="Username" name="username" required autofocus>
            </div>

            <!-- Password Input -->
            <div class="mb-3 input-icon-group">
              <i class="fa-solid fa-lock input-icon"></i>
              <input type="password" class="form-control" id="password"
                     placeholder="Password" name="password" required>
              <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
              <label class="form-check-label text-secondary" for="rememberMe">
                Remember me
              </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-dfms w-100 shadow-sm" name="login">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
            </button>
          </form>
        </div>

        <div class="card-footer dfms-footer text-center">
          <i class="fa-solid fa-code me-1"></i> Designed &amp; Developed by <strong>ICT Division, BCIC</strong>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
// Interactive password toggle capability
document.getElementById('togglePassword')?.addEventListener('click', function () {
  const passwordInput = document.getElementById('password');
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
  this.classList.toggle('fa-eye');
  this.classList.toggle('fa-eye-slash');
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