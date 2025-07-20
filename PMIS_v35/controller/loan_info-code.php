<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_POST['delete_student']))
{
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM loan_info WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Loan Information Deleted Successfully!!!";
        header("Location: loan_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Loan Information Not Deleted";
        header("Location: loan_info_details.php");
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $granted_date = mysqli_real_escape_string($con, $_POST['granted_date']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $fiscal_year = mysqli_real_escape_string($con, $_POST['fiscal_year']);
   
    $updated = date('Y-m-d H:i:s');
    // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

     $query = "UPDATE loan_info SET  ref_no='$ref_no', granted_date='$granted_date',type='$type', amount='$amount', fiscal_year='$fiscal_year' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);
        $_SESSION['message'] = "<b class='text-success'>Loan Information Updated Successfully!!!</b>";
        header("Location: loan_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Loan Information Not Updated";
        header("Location: loan_info_details.php");
        exit(0);
    }

}


if(isset($_POST['save_student']))
{
    $emp_id = mysqli_real_escape_string($con, $_SESSION['emp_id']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $granted_date = mysqli_real_escape_string($con, $_POST['granted_date']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $fiscal_year = mysqli_real_escape_string($con, $_POST['fiscal_year']);
   
    $created_at = date('Y-m-d');

    $query = "INSERT INTO loan_info (emp_id,ref_no,granted_date,type,amount,fiscal_year,created_at) VALUES ('$emp_id','$ref_no','$granted_date','$type','$amount','$fiscal_year','$created_at')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "<b class='text-success'>Loan Information Created Successfully!!!</b>";
        header("Location: loan_info-create.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Loan Information Not Created";
        header("Location: loan_info-create.php");
        exit(0);
    }
}

?>