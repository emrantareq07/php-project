<?php
session_name('dfms');
session_start();

require_once('../db/db.php');

// Security: only sadmin can use this action
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'sadmin') {
    header("Location: access_denied.php");
    exit();
}

if (isset($_GET['id'])) {
    // Activate individual user
    $id = (int)$_GET['id'];

    // Prevent activating superadmin (not really harmful, but keep consistency)
    $sql = "UPDATE users SET user_status='active' WHERE id=$id AND user_type!='sadmin'";
    mysqli_query($conn, $sql);

    $_SESSION['msg'] = [
        "type" => "success",
        "text" => "User ID $id activated successfully."
    ];
} else {
    // Activate all users except superadmin
    $sql = "UPDATE users SET user_status='active' WHERE user_type!='sadmin'";
    mysqli_query($conn, $sql);

    $_SESSION['msg'] = [
        "type" => "success",
        "text" => "All users activated successfully."
    ];
}

header("Location: manage_user.php");
exit();
