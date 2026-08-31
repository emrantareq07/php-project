<?php
session_start();
session_destroy();

// Remove cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login/index
header("Location: ../index.php");

session_destroy();
exit;
?>