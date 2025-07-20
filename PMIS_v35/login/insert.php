<?php
include('db.php');

if (isset($_POST['register'])) {
    $emp_id = $_POST['emp_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $designation = $_POST['designation'];
    $division = $_POST['department'];
    $section = $_POST['section'];
    $place_of_posting = $_POST['place_of_posting'];
    $password = mysqli_real_escape_string($conn, $_POST['psw']);
    $password2 = md5($password);

    $dp = "image/" . $_FILES['image']['name'];

    $sql = "SELECT * FROM users WHERE emp_id='$emp_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<script type="text/javascript">';
        echo 'alert("Emp ID Already taken!");';
        echo 'window.location.href="register.php"';
        echo '</script>';
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dp)) {
            $sql = "INSERT INTO users(emp_id, name, designation, division, section, password, image, place_of_posting) 
                    VALUES('{$emp_id}', '{$name}', '{$designation}', '{$division}', '{$section}', '{$password2}', '{$dp}', '{$place_of_posting}')";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "<script> window.open('welcome.php','_self')</script>";
            } else {
                echo "<div class='alert alert-danger alert-dismissible'>
                    <button type='button' class='close' data-dismiss='alert'>×</button>
                    Whoops! Some error encountered. Please try again.";
            }
        } else {
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert'>×</button>
                Whoops! Some error encountered. Please try again.";
        }

        echo '<script type="text/javascript">';
        echo 'alert("Your Account is now pending for Approval!");';
        echo 'window.location.href="register.php"';
        echo '</script>';
    }
}
?>
