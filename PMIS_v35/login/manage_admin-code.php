<?php

session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: login.php");
  exit();
}

// Check the user role
$role = $_SESSION['role'];

// Display different content based on the user role
if($role === 'sadmin') {
  include 'db.php';

if(isset($_POST['delete_student']))
{
    $id = mysqli_real_escape_string($conn, $_POST['delete_student']);

    $query = "DELETE FROM users WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['message'] = "<span class='text-success'><b>Admin Information Deleted Successfully</b></span>";
        header("Location: manage_admin.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "<span class='text-danger'><Admin Information Not Deleted</b></span>";
        header("Location: manage_admin.php");
        exit(0);
    }
}

if(isset($_POST['update_student']))
{
     $id = mysqli_real_escape_string($conn, $_POST['id']);

     $name = mysqli_real_escape_string($conn, $_POST['name']);
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = md5($password);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
	
	
                            $sql = "SELECT * FROM users WHERE id='$id' ";
                            $result = mysqli_query($conn, $sql);
							
							$row = mysqli_fetch_array($result);
							$password_db=$row['password'];
							$hashedPassword_db = md5($password_db);
							
							if($hashedPassword===$hashedPassword_db){
								
	 // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

    $query = "UPDATE users SET  emp_id='$emp_id',name='$name', designation='$designation', status='$status', role='$role' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['message'] = "<span class='text-success'><b>Admin Information Updated Successfully!!!</b></span>";
        header("Location: manage_admin.php");
        exit(0);
    }
	
	 else
    {
        $_SESSION['message'] = "<span class='text-danger'><b>Admin Information Not Updated</b></span>";
        header("Location: manage_admin.php");
        exit(0);
    }
}
else{
	 // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";

     $query = "UPDATE users SET  emp_id='$emp_id',name='$name', designation='$designation', password='$hashedPassword', status='$status', role='$role' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $_SESSION['message'] = "<span class='text-success'><b>Admin Information Updated Successfully!!!</b></span>";
        header("Location: manage_admin.php");
        exit(0);
    }
	
	 else
    {
        $_SESSION['message'] = "<span class='text-danger'><b>UsAdmin Information Not Updated</b></span>";
        header("Location: manage_admin.php");
        exit(0);
    }
	
}
  

}


if(isset($_POST['save_student']))
{
    $emp_id = $_SESSION['emp_id'];
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    // $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    

    $query = "INSERT INTO users (emp_id,designation,password,status,role) VALUES ('$emp_id','$designation','$password','$status','$role')";

    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "<span class='text-success'><b>Membership Information Created Successfully</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "<span class='text-danger'><b>Membership Information Not Created</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
}

}

?>