<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH . '/libs/function.php');

// Redirect if not authenticated
if (!isset($_SESSION["uid"])) {
    header('Location: login.php');
    exit();
}

$usercredentials = new DB_con();

// Get notice ID from URL
$notice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($notice_id > 0) {
    // Get filename
    $query = "SELECT filename FROM notice_board WHERE id = $notice_id";
    $result = $usercredentials->runBaseQuery($query);

    if (!empty($result)) {
        $filename = $result[0]['filename'];
        $filepath = "../pdf/" . $filename;

        // Delete physical file if exists
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        // Delete record
        $deleteQuery = "DELETE FROM notice_board WHERE id = $notice_id";
        $deleteResult = $usercredentials->executeQuery($deleteQuery);

        if ($deleteResult) {
            $_SESSION['success'] = "Notice deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete notice. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Notice not found.";
    }
} else {
    $_SESSION['error'] = "Invalid notice ID.";
}

header("Location: add_new_notice.php");
exit();
?>
