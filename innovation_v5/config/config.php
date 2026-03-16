<?php 
date_default_timezone_set("Asia/Dhaka"); // Bangladesh time

$file_name = basename($_SERVER['PHP_SELF'], '.php');    

// Define root path
define('ROOT_PATH', dirname(__DIR__) . '/');

// Base URL function
function base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . '/login/';
}

// Site name constant
// define('SITE_NAME', 'BCIC Innovation Database');

// Database constants (you can add these here if you want)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'innovation_db');
?>