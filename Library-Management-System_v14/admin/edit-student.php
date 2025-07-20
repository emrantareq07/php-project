<?php
session_name('bcic_e-library');
session_start();
include('includes/config.php');
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
    $designation = $_POST['designation'];  // Fixed variable name (removed extra $)
    $division = $_POST['division']; 
    $section = $_POST['section'];
    $pabx = $_POST['pabx'];
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
                    designation = :designation,
                    division = :division,
                    section = :section,
                    pabx = :pabx,
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
        $query->bindParam(':designation', $designation);
        $query->bindParam(':division', $division);
        $query->bindParam(':section', $section);
        $query->bindParam(':pabx', $pabx);
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
                                <label>Designation<span style="color:red;">*</span></label>
                                <select class="form-select" id="designation" name="designation" aria-label="Floating label select example" required>
                                    <option value="" disabled selected>--Select--</option>
                                    <?php
                                    $sql = "SELECT * FROM designation ORDER BY designation ASC";
                                    $designation_result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_array($designation_result)) {
                                        $selected = ($row['designation'] == $result->designation) ? 'selected' : '';
                                        echo "<option value='".htmlentities($row['designation'])."' $selected>".htmlentities($row['designation'])."</option>";
                                    }
                                    ?>   
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Division<span style="color:red;">*</span></label>
                                <select class="form-select" id="division" name="division" aria-label="Floating label select example" required>
                                    <option value="" disabled selected>--Select--</option>
                                    <?php
                                    $sql = "SELECT * FROM division ORDER BY division ASC";
                                    $division_result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_array($division_result)) {
                                        $selected = ($row['division'] == $result->division) ? 'selected' : '';
                                        echo "<option value='".htmlentities($row['division'])."' $selected>".htmlentities($row['division'])."</option>";
                                    }
                                    ?>   
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Section<span style="color:red;">*</span></label>
                                <select class="form-select" id="section" name="section" aria-label="Floating label select example">
                                    <option value="" disabled selected>--Select--</option>
                                    <?php
                                    $sql = "SELECT name FROM section ORDER BY name ASC";
                                    $section_result = mysqli_query($conn, $sql);
                                    while($row = mysqli_fetch_array($section_result)) {
                                        $selected = ($row['name'] == $result->section) ? 'selected' : '';
                                        echo "<option value='".htmlentities($row['name'])."' $selected>".htmlentities($row['name'])."</option>";
                                    }
                                    ?>   
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>PABX/Intercom</label>
                                <input class="form-control" type="text" name="pabx" id="pabx" required value="<?php echo htmlentities($result->pabx); ?>">
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

<?php include('includes/footer.php'); ?>