<?php 
require_once("config/config.php");
require_once("db/db.php");

if (isset($_GET['code'])) {
    // Retrieve the code from the URL
    $code = $_GET['code'];
    // Get current date and time
    $date_time = date('Y-m-d H:i:s');
    // Prepare the query
    $query = "UPDATE log_table SET logout_date_time='$date_time' WHERE code = $code";
    // Run the query
    $query_run = mysqli_query($conn, $query);
    // Check if query was successful
    if ($query_run) {
        // Destroy the session
        session_destroy();        
        // Redirect to login page or home page
        header("Location: logout.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Redirect to login page if code parameter is not set
    header("Location: logout.php");
    exit();
}
?>