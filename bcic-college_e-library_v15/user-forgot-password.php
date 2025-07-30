<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['change']))
{
    //code for captach verification
    if ($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"]=='')  {
        echo "<script>alert('Incorrect verification code');</script>" ;
    } 
    else {
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $newpassword = md5($_POST['newpassword']);
        $sql = "SELECT EmailId FROM tblstudents WHERE EmailId=:email and MobileNumber=:mobile";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        if($query->rowCount() > 0) {
            $con = "update tblstudents set Password=:newpassword where EmailId=:email and MobileNumber=:mobile";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
            $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            echo "<script>alert('Your Password successfully changed');</script>";
        } else {
            echo "<script>alert('Email id or Mobile no is invalid');</script>"; 
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Password Recovery</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet" />
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <script type="text/javascript">
        function valid() {
            if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                alert("New Password and Confirm Password Field do not match!!");
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #0dcaf0;
            color: white;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
        .captcha-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .captcha-img {
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    
<?php if($_SESSION['login']) { ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="row w-100 align-items-center">
            <!-- Logo -->
            <div class="col-6 col-md-3 d-flex align-items-center">
                <a class="navbar-brand bg-white rounded" href="#">
                    <img src="assets/img/logo5556.png" height="40" alt="Logo">
                </a>
            </div>

            <!-- Menu -->
            <div class="col-6 col-md-9">
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse text-uppercase justify-content-end" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Account
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="my-profile.php">My Profile</a></li>
                                <li><a class="dropdown-item" href="change-password.php">Change Password</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="issued-books.php">Issued Books</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php } else { ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="row w-100 align-items-center">
            <!-- Logo -->
            <div class="col-6 col-md-3 d-flex align-items-center">
                <a class="navbar-brand bg-white rounded" href="#">
                    <img src="assets/img/logo5556.png" height="40" alt="Logo">
                </a>
            </div>

            <!-- Menu -->
            <div class="col-6 col-md-9">
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse text-uppercase justify-content-end" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li> -->
                        <li class="nav-item"><a class="nav-link" href="adminlogin.php">Admin Login</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="signup.php">User Signup</a></li> -->
                        <li class="nav-item"><a class="nav-link" href="index.php">User Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<?php } ?>
    <!-- MENU SECTION START-->
    <?php //include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4><i class="fas fa-key me-2"></i>User Password Recovery</h4>
                    </div>
                    <div class="card-body p-4">
                        <form role="form" name="chngpwd" method="post" onSubmit="return valid();">
                            <div class="mb-3">
                                <label for="email" class="form-label">Registered Email</label>
                                <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
                            </div>
                            
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Registered Mobile Number</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required autocomplete="off">
                            </div>
                            
                            <div class="mb-3">
                                <label for="newpassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newpassword" name="newpassword" required autocomplete="off">
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirmpassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required autocomplete="off">
                            </div>
                            
                            <div class="mb-4">
                                <label for="vercode" class="form-label">Verification Code</label>
                                <div class="captcha-container">
                                    <input type="text" class="form-control" id="vercode" name="vercode" maxlength="5" required autocomplete="off">
                                    <img src="captcha.php" class="captcha-img" alt="CAPTCHA">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <button type="submit" name="change" class="btn btn-info px-4">
                                    <i class="fas fa-sync-alt me-2"></i>Change Password
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-sign-in-alt me-2"></i>Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER SECTION -->
    <?php include('includes/footer.php');?>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script src="assets/js/custom.js"></script>
</body>
</html>