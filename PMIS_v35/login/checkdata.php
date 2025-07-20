<?php
include('db.php');

if(isset($_POST['password'])){
    $emp_id = $_POST['emp_id'];
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = md5($password);

    $checkdata = "SELECT password FROM users WHERE emp_id='$emp_id'";
    $query = mysqli_query($conn, $checkdata);

    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);
        $stored_password = $row['password'];
        if($password == $stored_password){
            // echo "OK";
        } else {
            echo "&nbsp;&nbsp;Password does not match!!!";
        }
    } else {
        echo "User not found";
    }
    exit(0);
}

if(isset($_POST['emp_id'])){
    $emp_id = $_POST['emp_id'];

    $checkdata = "SELECT emp_id FROM users WHERE emp_id='$emp_id'";
    $query = mysqli_query($conn, $checkdata);

    if(mysqli_num_rows($query) > 0) {
        // echo "OK11";
    } else {
        echo "&nbsp;&nbsp;Employee ID Does not Exist!!!";
    }
    exit(0);
}


?>
