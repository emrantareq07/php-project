<?php
require_once("../config/config.php");
require_once("../db/db.php");
require_once(ROOT_PATH . 'libs/function.php');

$userdata = new DB_con();

// Check Employee ID availability
if(isset($_POST['emp_id'])) {
    $emp_id = $_POST['emp_id'];
    $result = $userdata->usernameavailblty($emp_id);
    $count = mysqli_num_rows($result);
    
    if($count > 0) {
        echo "<i class='fas fa-times-circle me-1'></i> Employee ID already exists";
    } else {
        echo "<i class='fas fa-check-circle me-1'></i> Available";
    }
}

// Check Email availability
if(isset($_POST['email'])) {
    $email = $_POST['email'];
    $result = $userdata->uemailavailblty($email);
    $count = mysqli_num_rows($result);
    
    if($count > 0) {
        echo "<i class='fas fa-times-circle me-1'></i> Email already exists";
    } else {
        echo "<i class='fas fa-check-circle me-1'></i> Available";
    }
}
?>