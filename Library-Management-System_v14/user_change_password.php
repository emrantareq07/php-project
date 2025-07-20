<!-- user_change_password.php -->
<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
}

$sid = $_SESSION['stdid'];
$msg = '';
$error = '';

if(isset($_POST['change'])) {
    $currentPassword = $_POST['current'];
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];
    
    // Verify current password
    $sql = "SELECT Password FROM tblstudents WHERE StudentId = :sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    if(password_verify($currentPassword, $result->Password)) {
        if($newPassword == $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $updateSql = "UPDATE tblstudents SET Password = :password, password_changed = 1 WHERE StudentId = :sid";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $updateQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
            
            if($updateQuery->execute()) {
                $msg = "Password changed successfully!";
                header("Location: user_dashboard.php");
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } else {
            $error = "New password and confirm password don't match!";
        }
    } else {
        $error = "Current password is incorrect!";
    }
}

// Check if password needs to be changed
$checkSql = "SELECT password_changed FROM tblstudents WHERE StudentId = :sid";
$checkQuery = $dbh->prepare($checkSql);
$checkQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
$checkQuery->execute();
$checkResult = $checkQuery->fetch(PDO::FETCH_OBJ);

if($checkResult->password_changed == 1) {
    header("Location: user_dashboard.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-key"></i> Change Default Password</h4>
                </div>
                <div class="card-body">
                    <?php if($msg): ?>
                    <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif; ?>
                    <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <p class="text-danger">You must change your default password before continuing.</p>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="newpassword" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirmpassword" required>
                        </div>
                        <button type="submit" name="change" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
