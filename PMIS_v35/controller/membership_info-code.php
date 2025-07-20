<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];
$val= $_GET['val'];
if(isset($_POST['delete_student'])){
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM prof_membership WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>Membership Information Deleted Successfully</b></span>";
        // header("Location: membership_info_details.php");
        header("Location: membership_info_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><bMembership Information Not Deleted</b></span>";
        header("Location: membership_info_details.php");
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $mem_no = mysqli_real_escape_string($con, $_POST['mem_no']);
    $institute = mysqli_real_escape_string($con, $_POST['institute']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $validity = mysqli_real_escape_string($con, $_POST['validity']);

    $query = "UPDATE prof_membership SET  type='$type', mem_no='$mem_no', institute='$institute', country='$country', validity='$validity' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);
        $_SESSION['message'] = "<span class='text-success'><b>Membership Information Updated Successfully</b></span>";
        header("Location: membership_info_details.php");
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><b>Membership Information Not Updated</b></span>";
        header("Location: membership_info_details.php");
        exit(0);
    }

}

if(isset($_POST['save_student'])){
    $emp_id = $_SESSION['emp_id'];
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $mem_no = mysqli_real_escape_string($con, $_POST['mem_no']);
    $institute = mysqli_real_escape_string($con, $_POST['institute']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $validity = mysqli_real_escape_string($con, $_POST['validity']);
    
    $query = "INSERT INTO prof_membership (emp_id,type,mem_no,institute,country,validity) VALUES ('$emp_id','$type','$mem_no','$institute','$country','$validity')";

    $query_run = mysqli_query($con, $query);
    if($query_run){
        $_SESSION['message'] = "<span class='text-success'><b>Membership Information Created Successfully!!!</b></span>";
        // header("Location: membership_info_details.php");
         header("Location: membership_info_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><b>Membership Information Not Created</b></span>";
        header("Location: membership_info_details.php");
        exit(0);
    }
}

?>