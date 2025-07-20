<?php
// session_start();
// include('includes/config.php');
// $id = intval($_GET['id']);
// $msg = "";
// $error = "";

// Fetch current data first
// $sql = "SELECT * FROM tblstudents WHERE id = :id";
// $query = $dbh->prepare($sql);
// $query->bindParam(':id', $id);
// $query->execute();
// $result = $query->fetch(PDO::FETCH_OBJ);

// Update logic
// if (isset($_POST['update'])) {
//     $StudentId = $_POST['StudentId'];
//     $FullName = $_POST['fullanme'];
//     $std_class = $_POST['std_class'];
//     $std_section = $_POST['std_section'];
//     $std_group = $_POST['std_group'];
//     $std_session = $_POST['std_session'];
//     $mobileno = $_POST['mobileno'];
//     $email = $_POST['email'];
    
    // Password change logic
    // $passwordChanged = false;
    // $newPassword = $_POST['newpassword'];
    // $confirmPassword = $_POST['confirmpassword'];
    
    // if (!empty($newPassword) || !empty($confirmPassword)) {
    //     if (!empty($newPassword) && !empty($confirmPassword)) {
    //         if ($newPassword === $confirmPassword) {
    //             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    //             $passwordChanged = true;
    //         } else {
    //             $error = "Passwords do not match!";
    //         }
    //     } else {
    //         $error = "Both password fields must be filled to change password!";
    //     }
    // }

    // Only proceed if there's no error with passwords
    // if (empty($error)) {
    //     if ($_FILES["student_image"]["name"]) {
    //         // Delete old image if exists and is not default
    //         if (!empty($result->Image) && file_exists($result->Image) && $result->Image !== "student_images/default.jpg") {
    //             unlink($result->Image);
    //         }

            // Save new image with unique name
            // $imageName = uniqid() . "_" . basename($_FILES["student_image"]["name"]);
            // $imageTmp = $_FILES["student_image"]["tmp_name"];
            // $imagePath = "student_images/" . $imageName;
            // move_uploaded_file($imageTmp, $imagePath);

            // if ($passwordChanged) {
                // $sql = "UPDATE tblstudents SET StudentId=:StudentId, FullName=:FullName, std_class=:std_class,
                //         std_section=:std_section, std_group=:std_group, std_session=:std_session, 
                //         MobileNumber=:mobileno, EmailId=:email, Image=:image, Password=:password WHERE id=:id";
        //         $sql = "UPDATE tblstudents SET StudentId=:StudentId, FullName=:FullName, std_class=:std_class,
        // std_section=:std_section, std_group=:std_group, std_session=:std_session, 
        // MobileNumber=:mobileno, EmailId=:email, Image=:image, Password=:password, password_changed=0 WHERE id=:id";

        //     } else {
        //         $sql = "UPDATE tblstudents SET StudentId=:StudentId, FullName=:FullName, std_class=:std_class,
        //                 std_section=:std_section, std_group=:std_group, std_session=:std_session, 
        //                 MobileNumber=:mobileno, EmailId=:email, Image=:image WHERE id=:id";
        //     }
        // } else {
        //     if ($passwordChanged) {
                // $sql = "UPDATE tblstudents SET StudentId=:StudentId, FullName=:FullName, std_class=:std_class,
                //         std_section=:std_section, std_group=:std_group, std_session=:std_session, 
                //         MobileNumber=:mobileno, EmailId=:email, Password=:password WHERE id=:id";
        //         $sql = "UPDATE tblstudents SET StudentId=:StudentId, FullName=:FullName, std_class=:std_class,
        // std_section=:std_section, std_group=:std_group, std_session=:std_session, 
        // MobileNumber=:mobileno, EmailId=:email, Image=:image, Password=:password, password_changed=0 WHERE id=:id";

        //     } else {
        //         $sql = "UPDATE tblstudents SET StudentId=:StudentId, FullName=:FullName, std_class=:std_class,
        //                 std_section=:std_section, std_group=:std_group, std_session=:std_session, 
        //                 MobileNumber=:mobileno, EmailId=:email WHERE id=:id";
        //     }
        // }

        // $query = $dbh->prepare($sql);
        // $query->bindParam(':StudentId', $StudentId);
        // $query->bindParam(':FullName', $FullName);
        // $query->bindParam(':std_class', $std_class);
        // $query->bindParam(':std_section', $std_section);
        // $query->bindParam(':std_group', $std_group);
        // $query->bindParam(':std_session', $std_session);
        // $query->bindParam(':mobileno', $mobileno);
        // $query->bindParam(':email', $email);
        // $query->bindParam(':id', $id);

        // if ($_FILES["student_image"]["name"]) {
        //     $query->bindParam(':image', $imagePath);
        // }
        
        // if ($passwordChanged) {
        //     $query->bindParam(':password', $hashedPassword);
        // }

        // if ($query->execute()) {
        //     $msg = "Student record updated successfully!";
        //     if ($passwordChanged) {
        //         $msg .= " Password has been changed.";
        //     }
            // Reload the updated record
//             $sql = "SELECT * FROM tblstudents WHERE id = :id";
//             $query = $dbh->prepare($sql);
//             $query->bindParam(':id', $id);
//             $query->execute();
//             $result = $query->fetch(PDO::FETCH_OBJ);
//         } else {
//             $error = "Something went wrong. Please try again.";
//         }
//     }
// }
?>

<?php
session_name('bcic_college_e-library');
session_start();
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:../index.php');
}
else{
$id = intval($_GET['id']);
$msg = "";
$error = "";

// Fetch current data first
$sql = "SELECT * FROM tblstudents WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $id);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

// Update logic
if (isset($_POST['update'])) {
    $StudentId = $_POST['StudentId'];
    $FullName = $_POST['fullanme'];
    $std_class = $_POST['std_class'];
    $std_section = $_POST['std_section'];
    $std_group = $_POST['std_group'];
    $std_session = $_POST['std_session'];
    $mobileno = $_POST['mobileno'];
    $email = $_POST['email'];

    // Password change logic
    $passwordChanged = false;
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];

    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (!empty($newPassword) && !empty($confirmPassword)) {
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $passwordChanged = true;
            } else {
                $error = "Passwords do not match!";
            }
        } else {
            $error = "Both password fields must be filled to change password!";
        }
    }

    // Only proceed if there's no error
    if (empty($error)) {
        $imageUploaded = !empty($_FILES["student_image"]["name"]);
        $imagePath = $result->Image; // fallback to old image

        if ($imageUploaded) {
            // Delete old image if exists and not default
            if (!empty($result->Image) && file_exists($result->Image) && $result->Image !== "student_images/default.jpg") {
                unlink($result->Image);
            }

            $imageName = uniqid() . "_" . basename($_FILES["student_image"]["name"]);
            $imageTmp = $_FILES["student_image"]["tmp_name"];
            $imagePath = "student_images/" . $imageName;
            move_uploaded_file($imageTmp, $imagePath);
        }

        // Build SQL query
        $sql = "UPDATE tblstudents SET 
                    StudentId = :StudentId,
                    FullName = :FullName,
                    std_class = :std_class,
                    std_section = :std_section,
                    std_group = :std_group,
                    std_session = :std_session,
                    MobileNumber = :mobileno,
                    EmailId = :email";

        if ($imageUploaded) {
            $sql .= ", Image = :image";
        }

        if ($passwordChanged) {
            $sql .= ", Password = :password, password_changed = 0";
        }

        $sql .= " WHERE id = :id";

        // Prepare and bind
        $query = $dbh->prepare($sql);
        $query->bindParam(':StudentId', $StudentId);
        $query->bindParam(':FullName', $FullName);
        $query->bindParam(':std_class', $std_class);
        $query->bindParam(':std_section', $std_section);
        $query->bindParam(':std_group', $std_group);
        $query->bindParam(':std_session', $std_session);
        $query->bindParam(':mobileno', $mobileno);
        $query->bindParam(':email', $email);
        $query->bindParam(':id', $id);

        if ($imageUploaded) {
            $query->bindParam(':image', $imagePath);
        }

        if ($passwordChanged) {
            $query->bindParam(':password', $hashedPassword);
        }

        // Execute and respond
        if ($query->execute()) {
            $msg = "Student record updated successfully!";
            if ($passwordChanged) {
                $msg .= " Password has been changed.";
            }

            // Reload updated data
            $sql = "SELECT * FROM tblstudents WHERE id = :id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>


<?php include('includes/header.php'); ?>
<div class="container mt-1">
    <div class="row mb-0 my-4">
        <div class="col-md-12">
            <h4 class="header-line text-uppercase fw-bold">Edit Student info.</h4>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-uppercase fw-bold"> <i class="fa fa-edit"></i> Edit Student Information</div>
                <div class="card-body">
                    <?php if ($msg) { ?>
                        <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
                    <?php } elseif ($error) { ?>
                        <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
                    <?php } ?>

                    <form method="POST" enctype="multipart/form-data" onsubmit="return validatePasswords()">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Student ID<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="StudentId" value="<?php echo htmlentities($result->StudentId); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label>Full Name<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->FullName); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Class<span class="text-danger">*</span></label>
                                <select class="form-control" name="std_class" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    $classes = ["Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve"];
                                    foreach ($classes as $class) {
                                        echo "<option value='$class'" . ($result->std_class == $class ? " selected" : "") . ">$class</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Section<span class="text-danger">*</span></label>
                                <select class="form-control" name="std_section" required>
                                    <option value="">Select Section</option>
                                    <?php
                                    $sections = ["A", "B", "C"];
                                    foreach ($sections as $section) {
                                        echo "<option value='$section'" . ($result->std_section == $section ? " selected" : "") . ">$section</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Group<span class="text-danger">*</span></label>
                                <select class="form-control" name="std_group" required>
                                    <option value="">Select Group</option>
                                    <?php
                                    $groups = ["Science", "Commerce", "Arts"];
                                    foreach ($groups as $group) {
                                        echo "<option value='$group'" . ($result->std_group == $group ? " selected" : "") . ">$group</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Session<span class="text-danger">*</span></label>
                                <select class="form-control" name="std_session" required>
                                    <option value="">Select Session</option>
                                    <?php
                                    $currentYear = date("Y");
                                    for ($i = 0; $i < 6; $i++) {
                                        $session = ($currentYear - $i) . "-" . ($currentYear - $i + 1);
                                        echo "<option value='$session'" . ($result->std_session == $session ? " selected" : "") . ">$session</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Mobile Number</label>
                                    <input class="form-control" type="text" name="mobileno" maxlength="11" value="<?php echo htmlentities($result->MobileNumber); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-0">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" value="<?php echo htmlentities($result->EmailId); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Password (leave blank to keep current)</label>
                                    <input class="form-control" type="password" name="newpassword" id="newpassword" autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" autocomplete="new-password">
                                    <small id="passwordHelp" class="text-danger" style="display:none">Passwords don't match!</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label></label><br>
                            <?php if ($result->Image && file_exists($result->Image)) { ?>
                                <img src="<?php echo htmlentities($result->Image); ?>" class="student-img-preview" alt="Student Image">
                            <?php } else { ?>
                                <img src="student_images/default.jpg" class="student-img-preview" alt="Default Image">
                            <?php } ?>
                        </div>

                        <div class="mb-3">
                            <label>Change Image:</label>
                            <input class="form-control" type="file" name="student_image" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" name="update" class="btn btn-primary">  <i class="fa fa-save"></i> Update Student</button>
                            <a href="reg-students.php" class="btn btn-secondary"> <i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validatePasswords() {
    const newPassword = document.getElementById('newpassword').value;
    const confirmPassword = document.getElementById('confirmpassword').value;
    const passwordHelp = document.getElementById('passwordHelp');
    
    // If both fields are empty, no password change is requested
    if (newPassword === '' && confirmPassword === '') {
        return true;
    }
    
    // If only one field is filled
    if (newPassword === '' || confirmPassword === '') {
        passwordHelp.textContent = "Both password fields must be filled to change password!";
        passwordHelp.style.display = 'block';
        return false;
    }
    
    // If passwords don't match
    if (newPassword !== confirmPassword) {
        passwordHelp.textContent = "Passwords don't match!";
        passwordHelp.style.display = 'block';
        return false;
    }
    
    return true;
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('newpassword');
    const confirmPassword = document.getElementById('confirmpassword');
    const passwordHelp = document.getElementById('passwordHelp');

    function checkPasswords() {
        if (newPassword.value !== confirmPassword.value) {
            passwordHelp.textContent = "Passwords don't match!";
            passwordHelp.style.display = 'block';
        } else {
            passwordHelp.style.display = 'none';
        }
    }

    newPassword.addEventListener('input', checkPasswords);
    confirmPassword.addEventListener('input', checkPasswords);
});
</script>

</body>
</html>
<?php 
}
include('includes/footer.php'); 
?>