<?php
session_start(); // Start the session at the beginning
error_reporting(0);
include('includes/config.php');

if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

if (isset($_POST['login'])) {
    // CAPTCHA verification
    // if ($_POST["vercode"] != $_SESSION["vercode"] || $_SESSION["vercode"] == '') {
    //     echo "<script>alert('Incorrect verification code');</script>";
    // } else {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $sql = "SELECT UserName, Password FROM admin WHERE UserName=:username AND Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            $_SESSION['alogin'] = $_POST['username'];
            echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
        } else {
            echo "<script>alert('Invalid Details');</script>";
        }
    // }
}
?>
<!------MENU SECTION START-->
<?php //include('includes/header.php'); ?>

<!-- MENU SECTION START -->
<?php //include('includes/header.php'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Custom Style -->
    <style>
        body {
            font-size: 16px;
        }
        .navbar-nav .nav-link {
            font-size: 16px;
        }
        .dropdown-item {
            font-size: 15px;
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
                <a class="navbar-brand" href="#">
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
<div class="container">
    <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <div class="card my-5">
            <div class="card-header bg-primary text-white text-uppercase text-center fw-bold">
                <i class="fa-regular fa-user"></i> ADMIN LOGIN FORM
            </div>

            <div class="card-body">
        <!--LOGIN PANEL START-->
            <div class="row">                
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Enter Username</label>
                            <input class="form-control" type="text" name="username" autocomplete="on" required />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control mb-4" type="password" name="password" autocomplete="off" required />
                        </div>
                        <!-- <div class="form-group">
                            <label>Verification code:</label>
                            <input type="text" name="vercode" maxlength="5" autocomplete="off" required style="width: 150px; height: 25px;" />
                            <img src="captcha.php?<?php echo time(); ?>" alt="CAPTCHA">
                        </div> -->
                        <button type="submit" name="login" class="btn btn-primary fw-bold"><i class="fa fa-sign-in"></i> LOGIN</button>
                    </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</div>
</div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php'); ?>
    
</body>
</html>