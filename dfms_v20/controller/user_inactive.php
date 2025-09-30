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
    // Deactivate individual user
    $id = (int)$_GET['id'];

    // Prevent deactivating superadmin
    $sql = "UPDATE users SET user_status='inactive' WHERE id=$id AND user_type!='sadmin'";
    mysqli_query($conn, $sql);

    $_SESSION['msg'] = [
        "type" => "warning",
        "text" => "User ID $id deactivated successfully."
    ];
} else {
    // Deactivate all users except superadmin
    $sql = "UPDATE users SET user_status='inactive' WHERE user_type!='sadmin'";
    mysqli_query($conn, $sql);

    $_SESSION['msg'] = [
        "type" => "warning",
        "text" => "All users deactivated successfully."
    ];
}

header("Location: manage_user.php");
exit();
