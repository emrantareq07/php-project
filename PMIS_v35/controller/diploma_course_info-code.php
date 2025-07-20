<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_POST['delete_student']))
{
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM diploma_course_info WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Diploma Training Information Deleted Successfully";
        header("Location: diploma_course_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Diploma Training Information Not Deleted";
        header("Location: diploma_course_info_details.php");
        exit(0);
    }
}

if(isset($_POST['update_student']))
{
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $institute = mysqli_real_escape_string($con, $_POST['institute']);
    $grade = mysqli_real_escape_string($con, $_POST['grade']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $period = mysqli_real_escape_string($con, $_POST['period']);
    $month_year = mysqli_real_escape_string($con, $_POST['month_year']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $updated = date('Y-m-d H:i:s');
    // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

     $query = "UPDATE diploma_course_info SET  type='$type', title='$title', institute='$institute',grade='$grade', country='$country', period='$period', month_year='$month_year',year='$year',updated='$updated' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);
        $_SESSION['message'] = "<b class='text-success'>Diploma Training Information Updated Successfully</b>";
        header("Location: diploma_course_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Diploma Training Information Not Updated";
        header("Location: diploma_course_info_details.php");
        exit(0);
    }

}


if(isset($_POST['save_student']))
{
    $emp_id = mysqli_real_escape_string($con, $_SESSION['emp_id']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $institute = mysqli_real_escape_string($con, $_POST['institute']);
    $grade = mysqli_real_escape_string($con, $_POST['grade']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $period = mysqli_real_escape_string($con, $_POST['period']);
    $month_year = mysqli_real_escape_string($con, $_POST['month_year']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $created_at = date('Y-m-d');

    $query = "INSERT INTO diploma_course_info (emp_id,type,title,institute,grade,country,period,month_year,year,created_at) VALUES ('$emp_id','$type','$title','$institute','$grade','$country','$period','$month_year','$year','$created_at')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Diploma Training Information Created Successfully";
        header("Location: diploma_course_info-create.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Diploma Training Information Not Created";
        header("Location: diploma_course_info-create.php");
        exit(0);
    }
}

?>