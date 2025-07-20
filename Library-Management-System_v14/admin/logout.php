<?php
session_name('bcic_e-library');
session_start();
include('includes/config.php');

// Save username and code before destroying session
$username = isset($_SESSION['login']) ? $_SESSION['login'] : (isset($_SESSION['alogin']) ? $_SESSION['alogin'] : null);
$code = isset($_SESSION['code']) ? $_SESSION['code'] : null;

// Update logout time in log_table
if ($username && $code) {
    $logout_date_time = date('Y-m-d H:i:s');
    $updateQuery = "UPDATE log_table SET logout_date_time = '$logout_date_time' WHERE username = '$username' AND code = '$code' ORDER BY id DESC LIMIT 1";
    mysqli_query($conn, $updateQuery);
}

// Clear session
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redirect to login page
header("Location: ../index.php");
exit();
?>
