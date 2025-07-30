<?php
session_name('bcic_college_e-library');
session_start();
include('includes/config.php');

if (isset($_POST['delete_student'])) {
    $id = $_POST['delete_student'];
    $query = "DELETE FROM admin WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $_SESSION['message'] = "User deleted successfully!";
    header("Location: manage_user.php");
    exit();
}
?>
<?php
session_start();
include('includes/config.php');

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];

    // Prevent accidental admin deletion (optional)
    if ($user_id == $_SESSION['admin_id']) {
        $_SESSION['message'] = "You cannot delete your own admin account!";
        header("Location: manage_user.php");
        exit();
    }

    $query = "DELETE FROM admin WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt)) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Something went wrong. User not deleted.";
    }

    header("Location: manage_user.php");
    exit();
}
?>
