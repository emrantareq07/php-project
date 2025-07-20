<!-- service_history_info-code.php -->
<?php
session_start();
require '../db/dbcon.php';
include('../db/db.php');
$val= $_GET['val'];
$_SESSION['emp_id']=$_SESSION['emp_id'];

if(isset($_POST['delete_student'])){
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM service_history WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run){
        $_SESSION['message'] = "Service History Information Deleted Successfully";
        
        // header("Location: service_history_details.php?emp_id=".$_SESSION['emp_id']);
        header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "Service History Information Not Deleted";
        
        header("Location: service_history_details.php?emp_id=".$_SESSION['emp_id']);
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);   
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);   
    $place_of_posting = mysqli_real_escape_string($con, $_POST['place_of_posting']);
    $service_type = mysqli_real_escape_string($con, $_POST['service_type']);
    $org_name = mysqli_real_escape_string($con, $_POST['org_name']);   
    $designation = mysqli_real_escape_string($con, $_POST['designation']);    
    $nature_of_promo=mysqli_real_escape_string($con, $_POST['nature_of_promo']);
    $grade_id=$_POST['grade'];
    
     $sql_grade="SELECT grade from grade where id ='$grade_id'"; 
         $result=mysqli_query($conn,$sql_grade);
         while($row = mysqli_fetch_array($result)){
                        
                        $grade=$row['grade'];
                        
                    }

    $basic=mysqli_real_escape_string($con, $_POST['basic']);
    $scale=mysqli_real_escape_string($con, $_POST['scale']);             
//important code
     // $basic_id=$_POST['basic'];
     // $sql_basic="SELECT basic from basic where id ='$basic_id'"; 
     //     $result=mysqli_query($conn,$sql_basic);
     //     while($row = mysqli_fetch_array($result)){
                        
     //                    $basic=$row['basic'];
                        
     //                }
     // $scale_id=$_POST['scale'];
     // $sql_scale="SELECT scale from pay_scale_2015 where id ='$scale_id'"; 
     //     $result=mysqli_query($conn,$sql_scale);
     //     while($row = mysqli_fetch_array($result)){
                        
     //                    $scale=$row['scale'];
                        
     //                }

    // $sql_max_id="SELECT max(id) as maxid from service_history where emp_id ='$emp_id' and service_type= 'After Joining'"; 
    // $result_max=mysqli_query($conn,$sql_max_id);
    // $row_max = mysqli_fetch_array($result_max);                 
    // $max_id=$row_max['maxid'];                

     $query = "UPDATE service_history SET  from_date='$from_date', to_date='$to_date', place_of_posting='$place_of_posting', service_type='$service_type', org_name='$org_name', designation='$designation', grade='$grade', scale='$scale', basic='$basic', nature_of_promo='$nature_of_promo' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        
        //Important Next time use

    // if($max_id==$id){       

    // $sql="UPDATE job_desc SET nature_of_promo = '$nature_of_promo', grade = '$grade',basic = '$basic', basic_after_incr = '$basic', scale = '$scale',place_of_posting='$place_of_posting' where emp_id='$emp_id'";

    // $query_run = mysqli_query($con, $sql);

    // $sql1="UPDATE basic_info SET designation = '$designation' where emp_id='$emp_id'";

    // $query_run1 = mysqli_query($con, $sql1);

    // }

        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);

       $_SESSION['message'] = "<b class='text-success'>Service History Information Updated Successfully!!!</b>";
        $_SESSION['message_class'] = "alert-success";
        header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id']);
        // header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "Service History Information Not Updated";
        //header("Location: service_history_details.php");
        header("Location: service_history_details.php?emp_id=".$_SESSION['emp_id']);
        exit(0);
    }

}

//for add 
if(isset($_POST['save_student'])){
    
    $emp_id = $_SESSION['emp_id'];
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
     $place_of_posting = mysqli_real_escape_string($con, $_POST['place_of_posting']);
    $service_type = $_POST['service_type'];
    // $service_type = mysqli_real_escape_string($con, $_POST['service_type']);
    $org_name = mysqli_real_escape_string($con, $_POST['org_name']);
    // $location = mysqli_real_escape_string($con, $_POST['location']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);
    // $scale = mysqli_real_escape_string($con, $_POST['scale']);
    $nature_of_promo = mysqli_real_escape_string($con, $_POST['nature_of_promo']);
    // $basic = mysqli_real_escape_string($con, $_POST['basic']);
    $grade = mysqli_real_escape_string($con, $_POST['grade']);
    $basic=mysqli_real_escape_string($con, $_POST['basic']);
    $scale=mysqli_real_escape_string($con, $_POST['scale']);

    // $basic_id=$_POST['basic'];
    //  $sql_basic="SELECT basic from basic where id ='$basic_id'"; 
    //      $result=mysqli_query($conn,$sql_basic);
    //      while($row = mysqli_fetch_array($result)){
                        
    //                     $basic=$row['basic'];
                        
    //                 }


     // $scale_id=$_POST['scale'];
     // $sql_scale="SELECT scale from pay_scale_2015 where id ='$scale_id'"; 
     //     $result=mysqli_query($conn,$sql_scale);
     //     while($row = mysqli_fetch_array($result)){
                        
     //                    $scale=$row['scale'];
                        
     //                }

if($service_type=="Before Joining"){
      $query = "INSERT INTO service_history (emp_id,from_date,to_date,place_of_posting,service_type,org_name,designation,nature_of_promo,grade,scale,basic) VALUES ('$emp_id','$from_date','$to_date','$place_of_posting','$service_type','$org_name','$designation','$nature_of_promo','$grade','$scale','$basic')";

    $query_run = mysqli_query($con, $query);

    if($query_run){
       $_SESSION['message'] = "<b class='text-success'>Service History Information Inserted Successfully!!!</b>";
        $_SESSION['message_class'] = "alert-success";
        // header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id']);
        header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "Service History Information Not Created";        
        header("Location: service_history_info-create.php?emp_id=".$_SESSION['emp_id']);
        exit(0);
    }

}
else{
    $query = "INSERT INTO service_history (emp_id,from_date,to_date,place_of_posting,service_type,org_name,designation,nature_of_promo,grade,scale,basic) VALUES ('$emp_id','$from_date','$to_date','$place_of_posting','$service_type','$org_name','$designation','$nature_of_promo','$grade','$scale','$basic')";
    $query_run2 = mysqli_query($con, $query);

    if($query_run2) {

        //Important use Next time

    // $sql="UPDATE job_desc SET nature_of_promo = '$nature_of_promo', grade = '$grade',basic = '$basic', basic_after_incr = '$basic', scale = '$scale',place_of_posting='$place_of_posting' where emp_id='$emp_id'";

    // $query_run = mysqli_query($con, $sql);

    // $sql1="UPDATE basic_info SET designation = '$designation' where emp_id='$emp_id'";

    // $query_run1 = mysqli_query($con, $sql1);

        $_SESSION['message'] = "<b class='text-success'>Service History Information Inserted  Successfully!!!</b>";
        $_SESSION['message_class'] = "alert-success";
        // header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id'].urlencode($val));
        header("Location: service_history_details.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "Service History Information Not Created";        
        header("Location: service_history_info-create.php?emp_id=".$_SESSION['emp_id']);
        exit(0);
        }
    }
}
?>