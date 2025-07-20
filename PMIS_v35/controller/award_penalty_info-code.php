<!-- award_penalty_info-code.php -->
<?php
error_reporting(0);
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];
$val= $_GET['val'];
if(isset($_POST['delete_student'])){
    $id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM award_and_penalty WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "<h5><b class='text-danger'>Award/ Penalty Information Deleted Successfully</b></h5>";
        // header("Location: award_and_penalty_info.php");
        header("Location: award_and_penalty_info.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }
    else {
        $_SESSION['message'] = "<h5><b class='text-danger'>Award/ Penalty Information Not Deleted</b></h5>";
        header("Location: award_and_penalty_info.php");
        exit(0);
    }
}

if(isset($_POST['update_student'])){
     $id = mysqli_real_escape_string($con, $_POST['id']);

    // $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $given_date = mysqli_real_escape_string($con, $_POST['given_date']);
    $ground_or_subject = mysqli_real_escape_string($con, $_POST['ground_or_subject']);
    $nature = mysqli_real_escape_string($con, $_POST['nature']);

    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    $special_increment =(int) $_POST['special_increment'];
    $special_promotion = mysqli_real_escape_string($con, $_POST['special_promotion']);
  
    $query = "UPDATE award_and_penalty SET  status='$status', given_date='$given_date', special_increment='$special_increment',special_promotion='$special_promotion',ground_or_subject='$ground_or_subject', nature='$nature', from_date='$from_date', to_date='$to_date' WHERE id='$id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        date_default_timezone_set("Asia/Dhaka");
        $today = date("Y-m-d H:i:s");

        // Update job_desc table
        $sql1 = "UPDATE job_desc SET last_update_at = '$today' WHERE emp_id = '$emp_id'";
        $result=mysqli_query($con,$sql1);
        $_SESSION['message'] = "<h5><b class='text-success'>Award/ Penalty Information Updated Successfully!!!</b></h5>";
        // header("Location: promotion_info-edit.php");
         header("Location: award_and_penalty_info.php");
        exit(0);
    }
    else{
        $_SESSION['message'] = "Award/ Penalty Information Not Updated";
        header("Location: award_and_penalty_info.php");
        // header("Location: promotion_info-edit.php");
        exit(0);
    }

}


if(isset($_POST['save_student'])){
    $emp_id = $_SESSION['emp_id'];
    // $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $given_date = mysqli_real_escape_string($con, $_POST['given_date']);
    $ground_or_subject = mysqli_real_escape_string($con, $_POST['ground_or_subject']);
    $nature = mysqli_real_escape_string($con, $_POST['nature']);

    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    //$special_increment = mysqli_real_escape_string($con, $_POST['special_increment']);
    $special_increment =(int) $_POST['special_increment'];
    $special_promotion = mysqli_real_escape_string($con, $_POST['special_promotion']);

    $query = "INSERT INTO award_and_penalty (emp_id,given_date,status,nature,from_date,to_date,ground_or_subject,special_increment) VALUES ('$emp_id','$given_date','$status','$nature','$from_date','$to_date','$ground_or_subject','$special_increment')";

    $query_run = mysqli_query($con, $query);

//Important Use next time

//     if($special_increment!=0) {

//     include('../db/db.php');
      
//     while ($special_increment!=0) {
        
//   $sql="SELECT * FROM job_desc where emp_id='$emp_id'";
 
//   $result=mysqli_query($conn,$sql);

//     $row=mysqli_fetch_assoc($result);
//     $basic_after_incr=$row['basic_after_incr'];//25480
    
//     $scale=$row['scale'];//25480

// if($scale=='66000-76490') {
//       $granted_incr=(int) (($basic_after_incr*3.75)/100);

//       $granted_incr1=(($basic_after_incr*3.75)/100);

//        if($granted_incr1==$granted_incr){
//          $granted_incr=$granted_incr;
//        }
//        else{
//         $dm=$granted_incr%10;
//         if($dm==0){
//           $granted_incr=$granted_incr+10;
//         }
//         else{
//           $granted_incr=$granted_incr;
//         }

//        }
//        }

//       elseif($scale=='56500-74400' || $scale=='50000-71200' ) {
//       $granted_incr=(int) (($basic_after_incr*4)/100);
//        $granted_incr1=(($basic_after_incr*4)/100);

//        if($granted_incr1==$granted_incr)
//        {
//          $granted_incr=$granted_incr;
//        }
//        else
//        {
//         $dm=$granted_incr%10;
//         if($dm==0)
//         {
//           $granted_incr=$granted_incr+10;
//         }
//         else
//         {
//           $granted_incr=$granted_incr;
//         }

//        }

//      }
//     elseif($scale=='43000-69850' ) {
//     $granted_incr=(int) (($basic_after_incr*4.5)/100);

//                $granted_incr1=(($basic_after_incr*4.5)/100);

//      if($granted_incr1==$granted_incr)
//      {
//        $granted_incr=$granted_incr;
//      }
//      else
//      {
//       $dm=$granted_incr%10;
//       if($dm==0)
//       {
//         $granted_incr=$granted_incr+10;
//       }
//       else
//       {
//         $granted_incr=$granted_incr;
//       }

//      }
//       //1864
//      }
//      else {
//     $granted_incr=(int) (($basic_after_incr*5)/100);
//                $granted_incr1=(($basic_after_incr*5)/100);

//      if($granted_incr1==$granted_incr)
//      {
//        $granted_incr=$granted_incr;
//      }
//      else
//      {
//       $dm=$granted_incr%10;
//       if($dm==0)
//       {
//         $granted_incr=$granted_incr+10;
//       }
//       else
//       {
//         $granted_incr=$granted_incr;
//       }

//      }
//       //1864
//      }




//     $d=$granted_incr%10;

//           if($d==0){
//               $final_basic= $granted_incr + $basic_after_incr;
//               $sql1="UPDATE job_desc SET basic='$basic_after_incr', incr_granted='$granted_incr',basic_after_incr='$final_basic' where emp_id='$emp_id' ";
//               $result1=mysqli_query($conn,$sql1);  
//             }
//           elseif($d>0){   
//                $d=(10-$d);
//                $granted_incr=$granted_incr+$d;
//                //echo '<br>'.$granted_incr;
//                $final_basic=$granted_incr + $basic_after_incr;
              
               

//               $sql2="UPDATE job_desc SET basic='$basic_after_incr', incr_granted='$granted_incr',basic_after_incr='$final_basic' where emp_id='$emp_id' ";
//             $result2=mysqli_query($conn,$sql2);  
//             }
        
//        $special_increment--; 
//     }

        $_SESSION['message'] = "<b class='text-success'>Award/ Penalty Information Created Successfully!!!</b>";
        // header("Location: award_and_penalty_info.php");
        header("Location: award_and_penalty_info.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));        
        exit(0);
    }
    else {
        $_SESSION['message'] = "Award/ Penalty Information Created Successfully";
        // header("Location: award_and_penalty_info.php");
        header("Location: award_and_penalty_info.php?emp_id=" . $_SESSION['emp_id'] . "&val=" . urlencode($val));
        exit(0);
    }


//}

?>