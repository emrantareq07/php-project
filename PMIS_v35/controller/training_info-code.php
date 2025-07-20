<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];
$val= $_GET['val'];
if(isset($_POST['delete_student'])){
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM training_info WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        $_SESSION['message'] = "Training Information Deleted Successfully";
        // header("Location: training_info_details.php");
        header("Location: training_info_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else{
        $_SESSION['message'] = "Training Information Not Deleted";
        header("Location: training_info_details.php");
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $title = mysqli_real_escape_string($con, $_POST['title']);

    $institute = mysqli_real_escape_string($con, $_POST['institute']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $period = mysqli_real_escape_string($con, $_POST['period']);
    $month_year = mysqli_real_escape_string($con, $_POST['month_year']);

    // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

     $query = "UPDATE training_info SET  type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);

        $_SESSION['message'] = "Training Information Updated Successfully";
        header("Location: training_info_details.php");
        exit(0);
    }
    else{
        $_SESSION['message'] = "Training Information Not Updated";
        header("Location: training_info_details.php");
        exit(0);
    }

}


if(isset($_POST['save_student'])){
    $emp_id = $_SESSION['emp_id'];
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $institute = mysqli_real_escape_string($con, $_POST['institute']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $period = mysqli_real_escape_string($con, $_POST['period']);
    $month_year = mysqli_real_escape_string($con, $_POST['month_year']);

    $query = "INSERT INTO training_info (emp_id,type,title,institute,country,period,month_year) VALUES ('$emp_id','$type','$title','$institute','$country','$period','$month_year')";

    $query_run = mysqli_query($con, $query);
    if($query_run) {
        $_SESSION['message'] = "Training Information Created Successfully!!!";
        // header("Location: training_info_details.php");
        header("Location: training_info_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else{
        $_SESSION['message'] = "Training Information Not Created";
        header("Location: training_info_details.php");
        exit(0);
    }
}

?>