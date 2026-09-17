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

<div class="container-fluid mt-5">
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">

      <div class="card shadow-lg border border-3 border-primary">
        <div class="card-header text-center text-uppercase text-white" style="background-color: #751aff;">
          <b>BCIC Fertilizer Management System</b>
        </div>
        <div class="card-body">
          <div class="imgcontainer">
            <img src="images/bcic_logo.png" alt="BCIC" class="avatar shadow-lg">
          </div>

          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="POST" class="">
            <div class="form-floating mb-3 mt-3">
              <input type="text" class="form-control" id="floatingInput"
                     placeholder="Enter Username" name="username" required autofocus>
              <label for="floatingInput">Username</label>
            </div>

            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="floatingPassword"
                     placeholder="Enter password" name="password" required>
              <label for="floatingPassword">Password</label>
            </div>

            <div class="form-check mb-3">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember"> Remember me
              </label>
            </div>

            <button type="submit" class="btn btn-primary gap-2 col-12 mx-auto" name="login">
              <i class="fa fa-sign-in"></i> Sign In
            </button>
          </form>
        </div>
        <div class="card-footer text-center text-muted">
          Design &amp; Developed by ICT Division, BCIC.
        </div>
      </div>

    </div>
    <div class="col-sm-4"></div>
  </div>
</div>

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