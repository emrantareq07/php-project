<?php

//session_start();

// Check if the user is not logged in, redirect to the login page
// if (!isset($_SESSION['emp_id'])) {
//   header("Location: login.php");
//   exit();
// }

// // Check the user role
// $role = $_SESSION['role'];

// // Display different content based on the user role
// if (($role === 'admin') || ($role === 'sadmin')) {
//   include 'db.php';

session_start();
  
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";

      $result= $usercredentials->runBaseQuery($query);

      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }


// if(isset($_POST['delete_student']))
// {
//     $id = mysqli_real_escape_string($conn, $_POST['delete_student']);

//     $query = "DELETE FROM tblusers WHERE id='$id' ";
//     $query_run = mysqli_query($conn, $query);

//     if($query_run)
//     {
//         $_SESSION['message'] = "<span class='text-success'><b>User Deleted Successfully</b></span>";
//         header("Location: manage_user.php");
//         exit(0);
//     }
//     else
//     {
//         $_SESSION['message'] = "<span class='text-danger'><User Information Not Deleted</b></span>";
//         header("Location: manage_user.php");
//         exit(0);
//     }
// }

if(isset($_POST['delete_student']))
{
    $id = mysqli_real_escape_string($conn, $_POST['delete_student']);

    $query = "DELETE FROM tblusers WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);
    
    if ($query_run) {
        // Output SweetAlert script for success
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo '<script type="text/javascript">
                Swal.fire({
                    title: "Success!",
                    text: "User Deleted Successfully",
                    icon: "success"
                }).then(function() {
                    window.location.href = "manage_user.php";
                });
              </script>';
        echo '</body></html>';
    } else {
        // Output SweetAlert script for error
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo '<script type="text/javascript">
                Swal.fire({
                    title: "Error!",
                    text: "User Information Not Deleted",
                    icon: "error"
                }).then(function() {
                    window.location.href = "manage_user.php";
                });
              </script>';
        echo '</body></html>';
    }
}

if(isset($_POST['update_student'])){
     
     $id = mysqli_real_escape_string($conn, $_POST['id']);    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = md5($password);
    
    $role = mysqli_real_escape_string($conn, $_POST['role']);
	
	
    $sql = "SELECT * FROM tblusers WHERE id='$id' ";
    $result = mysqli_query($conn, $sql);
	
	$row = mysqli_fetch_array($result);
	$password_db=$row['Password'];
	$hashedPassword_db = md5($password_db);
	
	if($hashedPassword===$hashedPassword_db){								
	 // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

    $query = "UPDATE tblusers SET  username='$username', role='$role' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['message'] = "<span class='text-success'><b>User Information Updated Successfully!!!</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
	
	 else
    {
        $_SESSION['message'] = "<span class='text-danger'><b>User Information Not Updated</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
}
else{
	 // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

     $query = "UPDATE tblusers SET  username='$username', password='$hashedPassword', role='$role' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['message'] = "<span class='text-success'><b>User Information Updated Successfully!!!</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
	
	 else
    {
        $_SESSION['message'] = "<span class='text-danger'><b>User Information Not Updated</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
	
}
  

}


if(isset($_POST['save_student'])){
            
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = md5($password);
    
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    

    $query = "INSERT INTO tblusers (Username,Password,role) VALUES ('$username','$hashedPassword','$role')";

    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Created Successfully</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "<span class='text-danger'><b>User Not Created</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
}

}

?>