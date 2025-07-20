<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:../index.php');
} else { 
    if (isset($_POST['signup'])) {
        // Captcha verification (commented)
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
        // $password = md5($_POST['password']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $status = 1;

        // Image upload
        $img_name = $_FILES["student_image"]["name"];
        $img_tmp = $_FILES["student_image"]["tmp_name"];
        $img_path = "student_images/";
        $img_final = $img_path . time() . "_" . basename($img_name);

        if (move_uploaded_file($img_tmp, $img_final)) {
            // $sql = "INSERT INTO tblstudents(StudentId, FullName, std_class, std_section, std_group, std_session, MobileNumber, EmailId, Password, Status, Image)
            //         VALUES(:StudentId, :fname, :std_class, :std_section, :std_group, :std_session, :mobileno, :email, :password, :status, :image)";
            // In the registration section, modify the SQL query:
            $sql = "INSERT INTO tblstudents(StudentId, FullName, std_class, std_section, std_group, std_session, MobileNumber, EmailId, Password, Status, Image, password_changed)
        VALUES(:StudentId, :fname, :std_class, :std_section, :std_group, :std_session, :mobileno, :email, :password, :status, :image, 0)";

            $query = $dbh->prepare($sql);
            $query->bindParam(':StudentId', $StudentId);
            $query->bindParam(':fname', $fname);
            $query->bindParam(':std_class', $std_class);
            $query->bindParam(':std_section', $std_section);
            $query->bindParam(':std_group', $std_group);
            $query->bindParam(':std_session', $std_session);
            $query->bindParam(':mobileno', $mobileno);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->bindParam(':status', $status);
            $query->bindParam(':image', $img_final);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                // echo '<script>alert("Your Registration was successful. Your student ID is ' . $StudentId . '");</script>';
                echo '<script>
                alert("Your Registration was successful. Your student ID is ' . $StudentId . '");
                window.location.href = "reg-students.php";
            </script>';
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
            }
        } else {
            echo "<script>alert('Image upload failed.');</script>";
        }
        // }
    }
?>

<?php //include('includes/navbar.php'); 
include('includes/header.php');
?>
<div class="container">
    <div class="row">
        <div class="row mb-0 my-2">
        <div class="col-md-12">
            <h4 class="header-line text-uppercase fw-bold">Student Registration</h4>
        </div>
    </div>
    <hr>
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <div class="card my-2">
            <div class="card-header bg-primary text-white text-uppercase">Student Registration FORM</div>
            <div class="card-body">
                <form name="signup" method="post" enctype="multipart/form-data" onSubmit="return valid();">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Student ID<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" id="StudentId" name="StudentId" onblur="checkAvailability('StudentId', 'user-availability-status2')" required />
                                <span id="user-availability-status2"></span>
                                <span id="loaderIcon2" class="loader-icon" style="display:none">Checking...</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Full Name<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="fullanme" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Class<span style="color:red;">*</span></label>
                                <select class="form-control" name="std_class" required>
                                    <option value="">Select Class</option>
                                    <option>Six</option>
                                    <option>Seven</option>
                                    <option>Eight</option>
                                    <option>Nine</option>
                                    <option>Ten</option>
                                    <option>Eleven</option>
                                    <option>Twelve</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Section<span style="color:red;">*</span></label>
                                <select class="form-control" name="std_section" required>
                                    <option value="">Select Section</option>
                                    <option>S-1</option>
                                    <option>S-2</option>
                                    <option>S-3</option>
                                    <option>S-4</option>
                                    <option>S-5</option>
                                    <option>S-6</option>
                                    <option>S-7</option>
                                    <option>S-8</option>
                                    <option>C-1</option>
                                    <option>C-2</option>
                                    <option>C-3</option>
                                    <option>C-4</option>
                                    <option>C-5</option>
                                    <option>C-6</option>
                                    <option>C-7</option>
                                    <option>C-8</option>
                                    <option>H-1</option>
                                    <option>H-2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col">
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
                    <div class="row">
                        <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile Number<span style="color:red;">*</span></label>
                        <input class="form-control" type="text" name="mobileno" maxlength="10"  required />
                    </div></div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" id="emailid" onblur="checkAvailability('emailid', 'user-availability-status1')" />
                        <span id="user-availability-status1"></span>
                        <span id="loaderIcon1" class="loader-icon" style="display:none">Checking...</span>
                    </div></div></div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Password<span style="color:red;">*</span></label>
                                <input class="form-control" type="password" name="password" id="password" required />
                            </div>
                        </div>
                        <div class="col">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Blood Group</label>
                                <select class="form-control" name="std_group" >
                                    <option value="">Select Group</option>
                                    <option>A+</option>
                                    <option>B+</option>
                                    <option>AB+</option>
                                     <option>A-</option>
                                    <option>O+</option>
                                    <option>O-</option>
                                    <option>AB-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label>Upload Student Image<span style="color:red;">*</span></label>
                            <input class="form-control" type="file" name="student_image" accept="image/*" required />
                        </div></div></div>

                    <button type="submit" name="signup" class="btn btn-primary my-1"><i class="fas fa-table"></i>  Register Now</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-3"></div>
</div>
</div>
<?php include('includes/footer.php'); ?>
<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
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

    $(loaderIcon).show();
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
                $("#" + statusElementId).html(`<span style='color:red'>Error checking availability</span>`);
                console.error("AJAX Error:", error);
            },
            complete: function() {
                $(loaderIcon).hide();
            }
        });
    } else {
        $(loaderIcon).hide();
        $("#" + statusElementId).html(`<span style='color:orange'>Please enter a ${fieldId === 'StudentId' ? 'Student ID' : 'Email'}</span>`);
    }
}
</script>

<?php } ?>
