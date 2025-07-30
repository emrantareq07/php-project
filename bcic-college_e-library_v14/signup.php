<?php 
session_start();
include('includes/config.php');
error_reporting(0);

if (isset($_POST['signup'])) {
    // Captcha verification
    // if ($_POST["vercode"] != $_SESSION["vercode"] || $_SESSION["vercode"] == '') {
    //     echo "<script>alert('Incorrect verification code');</script>";
    // } else {
        // Fetch form data
        $StudentId = $_POST['StudentId'];   
        $fname = $_POST['fullanme'];
        $std_class = $_POST['std_class'];
        $std_section = $_POST['std_section']; 
        $std_group = $_POST['std_group'];
        $std_session = $_POST['std_session'];
        $mobileno = $_POST['mobileno'];
        $email = $_POST['email']; 
        $password = md5($_POST['password']);  // For better security, use password_hash()
        $status = 1;

        // Image upload handling
        $img_name = $_FILES["student_image"]["name"];
        $img_tmp = $_FILES["student_image"]["tmp_name"];
        $img_path = "student_images/";
        $img_final = $img_path . time() . "_" . basename($img_name);

        if (move_uploaded_file($img_tmp, $img_final)) {
            // SQL Insert
            $sql = "INSERT INTO tblstudents(StudentId, FullName, std_class, std_section, std_group, std_session, MobileNumber, EmailId, Password, Status, Image)
                    VALUES(:StudentId, :fname, :std_class, :std_section, :std_group, :std_session, :mobileno, :email, :password, :status, :image)";
            
            $query = $dbh->prepare($sql);
            $query->bindParam(':StudentId', $StudentId, PDO::PARAM_STR);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':std_class', $std_class, PDO::PARAM_STR);
            $query->bindParam(':std_section', $std_section, PDO::PARAM_STR);
            $query->bindParam(':std_group', $std_group, PDO::PARAM_STR);
            $query->bindParam(':std_session', $std_session, PDO::PARAM_STR);
            $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':image', $img_final, PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                echo '<script>alert("Your Registration was successful. Your student ID is ' . $StudentId . '");</script>';
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
            }
        } else {
            echo "<script>alert('Image upload failed.');</script>";
        }
    // }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Online Library Management System | Student Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12"><h4 class="header-line">User Signup</h4></div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-info">
                    <div class="panel-heading"><b>USER SIGNUP FORM</b></div>
                    <div class="panel-body">
                        <form name="signup" method="post" enctype="multipart/form-data" onSubmit="return valid();">
                            <div class="form-group">
                                <label>Student ID<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" id="StudentId" name="StudentId" 
                                       onblur="checkAvailability('StudentId', 'user-availability-status2')" required />
                                <span id="user-availability-status2" style="font-size:12px;"></span>
                                <span id="loaderIcon2" class="loader-icon" style="display:none">Checking...</span>
                            </div>

                            <div class="form-group">
                                <label>Full Name<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="fullanme" required />
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Class<span style="color:red;">*</span></label>
                                        <select class="form-control" name="std_class" required>
                                            <option value="">Select Class</option>
                                            <option>Six</option>
                                            <option>Seven</option>
                                            <option>Eight</option>
                                            <option>Nine</option>
                                            <option>Ten</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Section<span style="color:red;">*</span></label>
                                        <select class="form-control" name="std_section" required>
                                            <option value="">Select Section</option>
                                            <option>A</option>
                                            <option>B</option>
                                            <option>C</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Group and Session in second row -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Group<span style="color:red;">*</span></label>
                                        <select class="form-control" name="std_group" required>
                                            <option value="">Select Group</option>
                                            <option>Science</option>
                                            <option>Commerce</option>
                                            <option>Arts</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
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
                            </div>

                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="text" name="mobileno" maxlength="10" required />
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" id="emailid" 
                                       onblur="checkAvailability('emailid', 'user-availability-status1')" required />
                                <span id="user-availability-status1" style="font-size:12px;"></span>
                                <span id="loaderIcon1" class="loader-icon" style="display:none">Checking...</span>
                            </div>
<!-- 
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password" required />
                            </div>

                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input class="form-control" type="password" name="confirmpassword" required />
                            </div> -->

                           <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password<span style="color:red;">*</span></label>
                                        <input class="form-control" type="password" name="password" id="password" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password<span style="color:red;">*</span></label>
                                        <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" required />
                                        <small id="passwordHelp" class="text-danger" style="display:none">Passwords don't match!</small>
                                    </div>
                                </div>
                            </div>

                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const password = document.getElementById('password');
                                const confirmPassword = document.getElementById('confirmpassword');
                                const passwordHelp = document.getElementById('passwordHelp');

                                function validatePassword() {
                                    if(password.value !== confirmPassword.value) {
                                        passwordHelp.style.display = 'block';
                                        confirmPassword.setCustomValidity("Passwords don't match");
                                    } else {
                                        passwordHelp.style.display = 'none';
                                        confirmPassword.setCustomValidity('');
                                    }
                                }

                                password.addEventListener('input', validatePassword);
                                confirmPassword.addEventListener('input', validatePassword);
                            });
                            </script>

                            <div class="form-group">
                                <label>Upload Student Image<span style="color:red;">*</span></label>
                                <input class="form-control" type="file" name="student_image" accept="image/*" required />
                            </div>

                            <!-- <div class="form-group">
                                <label>Verification Code</label><br>
                                <input type="text" name="vercode" maxlength="5" required style="width: 150px; height: 25px;" />
                                <img src="captcha.php" alt="captcha">
                            </div>
 -->
                            <button type="submit" name="signup" class="btn btn-info">Register Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
function valid() {
    if (document.signup.password.value != document.signup.confirmpassword.value) {
        alert("Password and Confirm Password do not match!");
        document.signup.confirmpassword.focus();
        return false;
    }
    return true;
}

function checkAvailability(fieldId, statusElementId) {
    const value = $("#" + fieldId).val().trim();
    const loaderIcon = fieldId === 'StudentId' ? "#loaderIcon2" : "#loaderIcon1";
    
    // Show loading indicator
    $(loaderIcon).show();
    
    // Clear previous status messages
    $("#" + statusElementId).html("");
    
    if (value) {
        jQuery.ajax({
            url: "check_availability.php",
            data: {
                field: fieldId,
                value: value
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                $("#" + statusElementId).html(data);
            },
            error: function(xhr, status, error) {
                $("#" + statusElementId).html(
                    `<span style='color:red'>Error checking availability</span>`
                );
                console.error("AJAX Error:", error);
            },
            complete: function() {
                $(loaderIcon).hide();
            }
        });
    } else {
        $(loaderIcon).hide();
        $("#" + statusElementId).html(
            `<span style='color:orange'>Please enter a ${fieldId === 'StudentId' ? 'Student ID' : 'Email'}</span>`
        );
    }
}


</script>
</body>
</html>
