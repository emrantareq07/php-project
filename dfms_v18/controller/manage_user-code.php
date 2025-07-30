<?php 
session_name('dfms');
session_start();
// $table=$_SESSION['username'];
// $user_type=$_SESSION['user_type'];
//echo $user_type;

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}
require_once('../db/db.php');
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // Retrieve the ID from the URL query parameter
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    // Prepare the DELETE query
    $query = "DELETE FROM users WHERE id='$id'";
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


// if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
//     // Retrieve the ID from the URL query parameter
//     $id = mysqli_real_escape_string($conn, $_GET['id']);

//     // Prepare the DELETE query
//     $query = "DELETE FROM users WHERE id='$id'";
//     $query_run = mysqli_query($conn, $query);

//     // Check if the query was successful
//     if ($query_run) {
//         $_SESSION['message'] = "<span class='text-success'><b>User Deleted Successfully</b></span>";
//         header("Location: manage_user.php");
//         exit(0);
//     } else {
//         $_SESSION['message'] = "<span class='text-danger'><b>User Information Not Deleted</b></span>";
//         header("Location: manage_user.php");
//         exit(0);
//     }
// }


if(isset($_POST['update'])){     
    $id = mysqli_real_escape_string($conn, $_POST['id']);    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    //$hashedPassword = md5($password);
    $hashedPassword = sha1($password);
    
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
	$product_type = mysqli_real_escape_string($conn, $_POST['product_type']);
	
    $sql = "SELECT * FROM users WHERE id='$id' ";
    $result = mysqli_query($conn, $sql);
	
	$row = mysqli_fetch_array($result);
	$password_db=$row['password'];
	$hashedPassword_db = sha1($password_db);
	
	if($hashedPassword===$hashedPassword_db){								
	 // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";
    $query = "UPDATE users SET  username='$username',email='$email', user_type='$user_type' , product_type='$product_type' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Information Updated Successfully!!!</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }	
	 else{
        $_SESSION['message'] = "<span class='text-danger'><b>User Information Not Updated</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
}
else{
	 // $query = "UPDATE training_info SET id='$id', user_id='$user_id', emp_id='$emp_id', type='$type', title='$title', institute='$institute', country='$country', period='$period', month_year='$month_year' WHERE emp_id='$emp_id' ";
    $query = "UPDATE users SET  username='$username',email='$email', password='$hashedPassword', user_type='$user_type', product_type='$product_type' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Information Updated Successfully!!!</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }	
	 else{
        $_SESSION['message'] = "<span class='text-danger'><b>User Information Not Updated</b></span>";
        header("Location: manage_user.php");
        exit(0);
        }
    }  
}
if(isset($_POST['save'])){            
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = sha1($password);
    
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $product_type = mysqli_real_escape_string($conn, $_POST['product_type']);
    // check table already exists
    $check_exists_table="SELECT * from users where username ='$username'";
    $check_exists_table_run = mysqli_query($conn, $check_exists_table);
    if(mysqli_num_rows($check_exists_table_run)==0){
        $create_table = "CREATE TABLE $username (
        id INT AUTO_INCREMENT PRIMARY KEY,
        factory_name VARCHAR(50) DEFAULT '$username',
        month_id INT,
        date DATE,
        daily INT,
        monthly INT,
        yearly INT,
        plant_load INT,
        remarks TEXT
    )";
        $create_table_query_run = mysqli_query($conn, $create_table);  
    }

    $query = "INSERT INTO users (username,email,password,user_type,product_type) VALUES ('$username','$email','$hashedPassword','$user_type','$product_type')";
    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Created Successfully</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
    else{
        $_SESSION['message'] = "<span class='text-danger'><b>User Not Created</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
}

?>