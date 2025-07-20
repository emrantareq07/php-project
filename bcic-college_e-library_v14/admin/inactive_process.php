<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(E_ALL); // For development, set to 0 in production
ini_set('display_errors', 1);
include('includes/config.php');

// Initialize session message variables if they don't exist
if (!isset($_SESSION['msg'])) {
    $_SESSION['msg'] = '';
}
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = '';
}

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit();
}

if(isset($_POST['inactive'])) {
    // Validate and sanitize input
    $std_session = mysqli_real_escape_string($conn, $_POST['std_session']);
    
    // Reset session messages
    $_SESSION['msg'] = '';
    $_SESSION['error'] = '';
    
    // Check if student exists first
    $sql = "SELECT * FROM tblstudents WHERE std_session='$std_session'";
    $result = mysqli_query($conn, $sql);
    
    if(!$result) {
        $_SESSION['error'] = "Database error: " . mysqli_error($conn);
        header('location:inactive_process.php');
        exit();
    }
    
    if(mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "No student found with session: " . htmlspecialchars($std_session);
        header('location:inactive_process.php');
        exit();
    }
    
    // Use prepared statement for update
    $sql_inactive = "UPDATE tblstudents SET status = 0 WHERE std_session = ?";
    $stmt = mysqli_prepare($conn, $sql_inactive);
    
    if(!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . mysqli_error($conn);
        header('location:inactive_process.php');
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "s", $std_session);
    $result_inactive = mysqli_stmt_execute($stmt);
    
    if($result_inactive) {
        $_SESSION['msg'] = "Successfully inactivated student with session: " . htmlspecialchars($std_session);
    } else {
        $_SESSION['error'] = "Update failed: " . mysqli_stmt_error($stmt);
    }
    
    mysqli_stmt_close($stmt);
    header('location:inactive_process.php');
    exit();
}

if(isset($_POST['active'])) {
    // Validate and sanitize input
    $std_session = mysqli_real_escape_string($conn, $_POST['std_session']);
    
    // Reset session messages
    $_SESSION['msg'] = '';
    $_SESSION['error'] = '';
    
    // Check if student exists first
    $sql = "SELECT * FROM tblstudents WHERE std_session='$std_session'";
    $result = mysqli_query($conn, $sql);
    
    if(!$result) {
        $_SESSION['error'] = "Database error: " . mysqli_error($conn);
        header('location:inactive_process.php');
        exit();
    }
    
    if(mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "No student found with session: " . htmlspecialchars($std_session);
        header('location:inactive_process.php');
        exit();
    }
    
    // Use prepared statement for update
    $sql_inactive = "UPDATE tblstudents SET status = 1 WHERE std_session = ?";
    $stmt = mysqli_prepare($conn, $sql_inactive);
    
    if(!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . mysqli_error($conn);
        header('location:inactive_process.php');
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "s", $std_session);
    $result_inactive = mysqli_stmt_execute($stmt);
    
    if($result_inactive) {
        $_SESSION['msg'] = "Successfully Activated student with session: " . htmlspecialchars($std_session);
    } else {
        $_SESSION['error'] = "Update failed: " . mysqli_stmt_error($stmt);
    }
    
    mysqli_stmt_close($stmt);
    header('location:inactive_process.php');
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Add Author</title>
    <!-- Bootstrap 5.3.3 CSS (latest as of June 2024) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.1 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
     <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold text-uppercase">Student Iactive </h2>
                    <hr>
                </div>
            </div>
             <?php if($_SESSION['msg']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Success :</strong> 
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php echo htmlentities($_SESSION['msg']=""); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php } ?>
                <?php if($_SESSION['error']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error :</strong> 
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php echo htmlentities($_SESSION['error']=""); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php } ?>
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card  shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Student Inactive Process</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label>Session<span style="color:red;">*</span></label>
                                        <select class="form-control" name="std_session" required>
                                            <option value="">Select Session</option>
                                            <?php
                                            $currentYear = date("Y");
                                            for ($i = 0; $i < 6; $i++) {
                                                $session = ($currentYear - $i) . "-" . ($currentYear - $i + 1);
                                                echo "<option value='$session'>$session</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="inactive" class="btn btn-danger">
                                        <i class="fas fa-spinner me-2"></i>Inactive
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
                        <div class="row justify-content-center">
                                            <div class="col-12">
                    <h2 class="fw-bold text-uppercase">Student Active </h2>
                    <hr>
                </div>
                <div class="col-lg-6 col-md-8">
                    <div class="card  shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Student Active Process</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label>Session<span style="color:red;">*</span></label>
                                        <select class="form-control" name="std_session" required>
                                            <option value="">Select Session</option>
                                            <?php
                                            $currentYear = date("Y");
                                            for ($i = 0; $i < 6; $i++) {
                                                $session = ($currentYear - $i) . "-" . ($currentYear - $i + 1);
                                                echo "<option value='$session'>$session</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="active" class="btn btn-success">
                                        <i class="fas fa-spinner me-2"></i>Active
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    
    <!-- Bootstrap 5.3.3 JS Bundle with Popper (latest as of June 2024) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    
</body>
</html>
<?php //} ?>