<!-- promotion_info-code.php -->
<!-- service_history_info-code.php -->
<?php
session_start();
require 'dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_POST['delete_student']))
{
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM promotion_info WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Promotion Information Deleted Successfully";
        header("Location: view_promotion_info.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Promotion Information Not Deleted";
        header("Location: view_promotion_info.php");
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);

    $date_of_promot = mysqli_real_escape_string($con, $_POST['date_of_promot']);
    $place_of_posting = mysqli_real_escape_string($con, $_POST['place_of_posting']);
    $granted_promo_date = mysqli_real_escape_string($con, $_POST['granted_promo_date']);
    $nature_of_promo = mysqli_real_escape_string($con, $_POST['nature_of_promo']);

    
    $scale = mysqli_real_escape_string($con, $_POST['scale']);
    // $created_at = 
    // $last_updated =


    // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

     $query = "UPDATE promotion_info SET  ref_no='$ref_no', designation='$designation', date_of_promot='$date_of_promot', place_of_posting='$place_of_posting', granted_promo_date='$granted_promo_date', nature_of_promo='$nature_of_promo', scale='$scale' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);

        $_SESSION['message'] = "<h5><b class='text-success'>Promotion Information Updated Successfully!!!</b></h5>";
        // header("Location: promotion_info-edit.php");
         header("Location: view_promotion_info.php");
        exit(0);
    }
    else {
        $_SESSION['message'] = "Promotion Information Not Updated";
        header("Location: view_promotion_info.php");
        // header("Location: promotion_info-edit.php");
        exit(0);
    }

}


if(isset($_POST['save_student']))
{
    $emp_id = $_SESSION['emp_id'];
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);

    $date_of_promot = mysqli_real_escape_string($con, $_POST['date_of_promot']);
    $place_of_posting = mysqli_real_escape_string($con, $_POST['place_of_posting']);
    $granted_promo_date = mysqli_real_escape_string($con, $_POST['granted_promo_date']);
    $nature_of_promo = mysqli_real_escape_string($con, $_POST['nature_of_promo']);

    
    $scale = mysqli_real_escape_string($con, $_POST['scale']);
    // $created_at = 
    // $last_updated =


    $query = "INSERT INTO promotion_info (emp_id,ref_no,date_of_promot,place_of_posting,granted_promo_date,nature_of_promo,designation,scale) VALUES ('$emp_id','$ref_no','$date_of_promot','$place_of_posting','$granted_promo_date','$nature_of_promo','$designation','$scale')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "<b class='text-success'>Promotion Information Created Successfully!!!</b>";
        header("Location: promotion_info-create.php");
        // header("Location: view_promotion_info.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Promotion Information Not Created";
        header("Location: promotion_info-create.php");
        exit(0);
    }
}

?>