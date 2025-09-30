<?php
session_start(); // Start the session

// Set the inactivity timeout in seconds (10 minutes = 600 seconds)
$timeout_duration = 600;

// Check if the last activity timestamp is set in the session
if (isset($_SESSION['last_activity'])) {
    // Calculate the elapsed time since the last activity
    $elapsed_time = time() - $_SESSION['last_activity'];

    // If the elapsed time exceeds the timeout duration, destroy the session
    if ($elapsed_time > $timeout_duration) {
        session_unset(); // Clear session data
        session_destroy(); // Destroy the session
        header("Location: logout.php"); // Redirect to a logout or login page
        exit();
    }
}

// Update the last activity timestamp
$_SESSION['last_activity'] = time();

// Example: Storing some session data
// if (!isset($_SESSION['user'])) {
//     $_SESSION['user'] = 'JohnDoe'; // Example username
// }

// echo "Hello, " . $_SESSION['user'] . "! You are logged in.";
?>
