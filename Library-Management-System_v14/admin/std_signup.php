<?php
session_name('bcic_e-library');
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
        $designation = $_POST['designation'];
        $division = $_POST['division']; 
        $section = $_POST['section'];
        $pabx = $_POST['pabx'];
        $mobileno = $_POST['mobileno'];
        $email = $_POST['email']; 
        //$password = md5($_POST['password']);
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
            $sql = "INSERT INTO tblstudents(StudentId, FullName, designation, division, section, pabx, MobileNumber, EmailId, Password, Status, Image, password_changed)
        VALUES(:StudentId, :fname, :designation, :division, :section, :pabx, :mobileno, :email, :password, :status, :image, 0)";

            $query = $dbh->prepare($sql);
            $query->bindParam(':StudentId', $StudentId);
            $query->bindParam(':fname', $fname);
            $query->bindParam(':designation', $designation);
            $query->bindParam(':division', $division);
            $query->bindParam(':section', $section);
            $query->bindParam(':pabx', $pabx);
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
                alert("Your Registration was successful. Your EMP ID is ' . $StudentId . '");
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
            <h4 class="header-line text-uppercase fw-bold">User Registration</h4>
        </div>
    </div>
    <hr>
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <div class="card my-2">
            <div class="card-header bg-primary text-white text-uppercase">User Registration FORM</div>
            <div class="card-body">
                <form name="signup" method="post" enctype="multipart/form-data" onSubmit="return valid();">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>EMP ID<span style="color:red;">*</span></label>
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
                                <label>Designation<span style="color:red;">*</span></label>
                                <select class="form-select" id="designation" name="designation" aria-label="Floating label select example" required>
                                <option value="" disabled selected>--Select--</option>
                                <?php
                                  $sql = "SELECT * FROM designation order by designation ASC";
                                  $result = mysqli_query($conn, $sql);
                                  while($row = mysqli_fetch_array($result)) {
                                   echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
                                  }

                                  ?>   
                              </select>
                            </div>
                        </div>
                         <div class="col">
                            <div class="form-group">
                                <label>Division<span style="color:red;">*</span></label>
                                <select class="form-select" id="division" name="division" aria-label="Floating label select example" required>
                                  <option value="" disabled selected>--Select--</option>
                                  <?php
                                    $sql = "SELECT * FROM division order by division asc";
                                    $result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_array($result)) {
                                      echo "<option value='".$row['division']."'>".$row['division']."</option>";
                                    }

                                    ?>   
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Section<span style="color:red;">*</span></label>
                                <select class="form-select" id="section" name="section" aria-label="Floating label select example" >
                              <option value="" disabled selected>--Select--</option>
                              <?php
                                $sql = "SELECT name FROM section order by name asc";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)) {
                                  echo "<option value='".$row['name']."'>".$row['name']."</option>";
                                }

                                ?>   
                            </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>PABX/Intercom</label>
                               <input class="form-control" type="text" name="pabx" id="pabx"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile Number<span style="color:red;">*</span></label>
                        <input class="form-control" type="text" name="mobileno" maxlength="11"  required />
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
                            <label>Upload User Image<span style="color:red;">*</span></label>
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
        $("#" + statusElementId).html(`<span style='color:orange'>Please enter a ${fieldId === 'StudentId' ? 'EMP ID' : 'Email'}</span>`);
    }
}
</script>

<?php } ?>
