
<?php

error_reporting(0);
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_POST['delete_student'])){
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM nominees WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "<h5><b class='text-danger'>Nominees Information Deleted Successfully</b></h5>";
        header("Location: nominees_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "<h5><b class='text-danger'>Nominees Information Not Deleted</b></h5>";
        header("Location: nominees_info_details.php");
        exit(0);
    }
}

if(isset($_POST['update_student']))
{
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);   
    $relation = mysqli_real_escape_string($con, $_POST['relation']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $amount_of_share = mysqli_real_escape_string($con, $_POST['amount_of_share']);
    $bank_name = mysqli_real_escape_string($con, $_POST['bank_name']);
    $account_no = mysqli_real_escape_string($con, $_POST['account_no']);
    $branch_name = mysqli_real_escape_string($con, $_POST['branch_name']);
  
    $query = "UPDATE nominees SET  name='$name', address='$address', relation='$relation', dob='$dob', amount_of_share='$amount_of_share', bank_name='$bank_name', account_no='$account_no', branch_name='$branch_name' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);
        $_SESSION['message'] = "<h5><b class='text-success'>Nominees Information Updated Successfully!!!</b></h5>";
        // header("Location: promotion_info-edit.php");
         header("Location: nominees_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Nominees Information Not Updated";
        header("Location: nominees_info_details.php");
        // header("Location: promotion_info-edit.php");
        exit(0);
    }

}


if(isset($_POST['save_student']))
{
    $emp_id = $_SESSION['emp_id'];
    // $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $address = mysqli_real_escape_string($con, $_POST['address']); 
    $relation = mysqli_real_escape_string($con, $_POST['relation']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    $amount_of_share = mysqli_real_escape_string($con, $_POST['amount_of_share']);
    $bank_name = mysqli_real_escape_string($con, $_POST['bank_name']);
    $account_no = mysqli_real_escape_string($con, $_POST['account_no']);
    $branch_name = mysqli_real_escape_string($con, $_POST['branch_name']);

    $query = "INSERT INTO nominees (emp_id,name,address,relation,dob,amount_of_share,bank_name,account_no,branch_name) VALUES ('$emp_id','$name','$address','$relation','$dob','$amount_of_share','$bank_name','$account_no,','$branch_name,')";

    $query_run = mysqli_query($con, $query);
    
     if($query_run)
    {
        $_SESSION['message'] = "<b class='text-success'>Nominees Information Created Successfully</b>";
        header("Location: nominees_info_details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Award/ Penalty Information Created Successfully";
        header("Location: nominees_info_details.php");
        exit(0);
    }


}

?>