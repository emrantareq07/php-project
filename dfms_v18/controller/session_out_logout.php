<?php
session_start();
session_unset();
session_destroy();
header("Location: dashboard.php"); // Redirect to the login page
exit();
?>
