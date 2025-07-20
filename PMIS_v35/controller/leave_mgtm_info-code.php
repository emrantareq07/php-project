<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_POST['delete_student'])){
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM leave_mgtm WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>Leave Information Deleted Successfully</b></span>";
        header("Location: leave_mgtm_info_details.php");
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><Leave Information Not Deleted</b></span>";
        header("Location: leave_mgtm_info_details.php");
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);

    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $leave_type = mysqli_real_escape_string($con, $_POST['leave_type']);
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    
    $date1 = new DateTime($to_date);
    $date2 = new DateTime($from_date);
    $interval = $date1->diff($date2);
    // echo $interval->format('%a days');
    //$monthsDiff = $interval->format('%m months');
    $total_days = $interval->format('%a') + 1 . ' days';

    $query = "UPDATE leave_mgtm SET  leave_type='$leave_type', ref_no='$ref_no', from_date='$from_date', to_date='$to_date', total_days='$total_days' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);
        $_SESSION['message'] = "<span class='text-success'><b>Leave Information Updated Successfully</b></span>";
        header("Location: leave_mgtm_info_details.php");
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><b>Leave Information Not Updated</b></span>";
        header("Location: leave_mgtm_info_details.php");
        exit(0);
    }

}

if(isset($_POST['save_student'])){
    $emp_id = $_SESSION['emp_id'];
    $leave_type = mysqli_real_escape_string($con, $_POST['leave_type']);
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    

    $date1 = new DateTime($to_date);
    $date2 = new DateTime($from_date);
    $interval = $date1->diff($date2);
    // echo $interval->format('%a days');
    //$monthsDiff = $interval->format('%m months');
    $total_days = $interval->format('%a') + 1 . ' days';
    
    $query = "INSERT INTO leave_mgtm (emp_id,leave_type,ref_no,from_date,to_date,total_days) VALUES ('$emp_id','$leave_type','$ref_no','$from_date','$to_date','$total_days')";

    $query_run = mysqli_query($con, $query);
    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>Leave Information Created Successfully!!!</b></span>";
        header("Location: leave_mgtm_info_details.php");
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><b>Leave Information Not Created</b></span>";
        header("Location: leave_mgtm_info_details.php");
        exit(0);
    }
}

?>