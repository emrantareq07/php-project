<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Reset session login value if set
if ($_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

// Handle form submission
if (isset($_POST['login'])) {
    // Captcha verification
    if ($_POST["vercode"] != $_SESSION["vercode"] || $_SESSION["vercode"] == '') {
        echo "<script>alert('Incorrect verification code');</script>";
    } else {
        $email = $_POST['emailid'];
        $password = md5($_POST['password']);

        $sql = "SELECT EmailId, Password, StudentId, Status FROM tblstudents WHERE EmailId = :email AND Password = :password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $_SESSION['stdid'] = $result->StudentId;

                if ($result->Status == 1) {
                    $_SESSION['login'] = $_POST['emailid'];
                    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
                } else {
                    echo "<script>alert('Your Account Has been blocked. Please contact admin');</script>";
                }
            }
        } else {
            echo "<script>alert('Invalid Details');</script>";
        }
    }
}
?>

<!-- MENU SECTION START -->
<?php include('includes/header.php'); ?>

<!-- LOGIN FORM UI -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-5">
            <div class="card my-5 shadow">
                <div class="card-header bg-primary text-white text-uppercase text-center">
                    User LOGIN FORM
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="text" name="emailid" class="form-control" placeholder="Enter Email ID" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter Password" required autocomplete="off">
                            <div class="form-text">
                                <a href="user-forgot-password.php">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verification Code</label>
                            <div class="d-flex align-items-center">
                                <input type="text" name="vercode" class="form-control me-2" maxlength="5" required style="height: 38px;" autocomplete="off">
                                <img src="captcha.php" alt="captcha">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" name="login" class="btn btn-primary">
                                <i class="fa fa-sign-in"></i> LOGIN
                            </button>
                            <a href="signup.php">Not Registered Yet?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER SECTION -->
<?php include('includes/footer.php'); ?>
