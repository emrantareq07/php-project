<?php
// navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ensure a CSRF token for forms that modify data
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">📷 Family Photo Storage</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">🏠 Home</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'upload.php') ? 'active' : '' ?>" href="upload.php">⬆ Upload</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'list.php') ? 'active' : '' ?>" href="list.php">📂 Gallery</a>
          </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle <?= ($current_page == 'profile.php') ? 'active' : '' ?>" href="#" id="userDropdown"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
              👤 <?= htmlspecialchars($_SESSION['username']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="profile.php">🔧 Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php">🚪 Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item ms-lg-3">
            <a class="btn btn-outline-light btn-sm" href="login.php">🔑 Login</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-primary btn-sm" href="register.php">📝 Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
