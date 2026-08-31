<?php
/**
 * AUTH GUARD
 * -----------
 * Include this at the top of every protected page, AFTER session_start().
 *
 * Usage:
 *   session_name('pms_db');
 *   session_start();
 *   $required_role = 'doctor';           // lock this page to one role
 *   require_once __DIR__ . '/auth_guard.php';
 *
 * If $required_role is left unset, any logged-in user (any role) may view the page.
 * If it's set and doesn't match the session's user_type, the visitor is bounced
 * to their OWN dashboard rather than shown an error - this stops a store_incharge
 * from ending up on the sadmin user-management screen, for example.
 */

if (!isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
    header("Location: ../index.php");
    exit();
}

$roles = include __DIR__ . '/roles_config.php';
$current_role = $_SESSION['user_type'];

if (!isset($roles[$current_role])) {
    // Role exists in session but not in config - treat as logged out for safety
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

if (isset($required_role) && $current_role !== $required_role) {
    $own_dashboard = $roles[$current_role]['dashboard'];
    header("Location: " . $own_dashboard);
    exit();
}
