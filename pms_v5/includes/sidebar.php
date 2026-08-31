<?php
/**
 * Shared sidebar. Include from inside <body> on any dashboard page,
 * after auth_guard.php has already run in that page.
 */
$roles         = include __DIR__ . '/roles_config.php';
$current_role  = $_SESSION['user_type'] ?? '';
$menu          = $roles[$current_role]['menu'] ?? [];
$role_label    = $roles[$current_role]['label'] ?? 'User';
$current_file  = basename($_SERVER['PHP_SELF']);
$display_name  = $_SESSION['username'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/img/bcic_logo.png" alt="BCIC" onerror="this.style.display='none'">
        <div class="sidebar-brand-text">
            <strong>BCIC PMS</strong>
            <span><?= htmlspecialchars($role_label) ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($menu as $item): ?>
            <a href="<?= htmlspecialchars($item['href']) ?>"
               class="nav-item<?= ($current_file === $item['href']) ? ' active' : '' ?>">
                <span class="nav-dot"></span>
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <span class="avatar"><?= strtoupper(substr($display_name, 0, 1) ?: 'U') ?></span>
            <div class="sidebar-user-text">
                <strong><?= htmlspecialchars($display_name) ?></strong>
                <span><?= htmlspecialchars($role_label) ?></span>
            </div>
        </div>
        <a href="logout.php" class="sidebar-logout">Log out</a>
    </div>
</aside>
