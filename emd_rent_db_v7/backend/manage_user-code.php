<?php 
session_name('PROJECT1SESSION');
session_start();
// $table=$_SESSION['username'];
// $user_type=$_SESSION['user_type'];
//echo $user_type;

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once '../db/database.php';

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

if(isset($_POST['update'])){     
    $id = mysqli_real_escape_string($conn, $_POST['id']);    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = sha1($password);
    
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
	$office = mysqli_real_escape_string($conn, $_POST['office']);
	
    $sql = "SELECT * FROM users WHERE id='$id' ";    
    $result = mysqli_query($conn, $sql);	
	$row = mysqli_fetch_array($result);
	$password_db=$row['password'];

	$hashedPassword_db = sha1($password_db);

    $username_db=$row['username'];

    if($username!=$username_db && $user_type=='user'){
        $query_change_username = "ALTER TABLE $username_db RENAME TO $username";
        // Execute the query
        $query_run = mysqli_query($conn, $query_change_username);
    }
	
	if($hashedPassword===$hashedPassword_db){								
	$query = "UPDATE users SET  username='$username',email='$email', user_type='$user_type', office='$office' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);
    if($query_run){
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
    $query = "UPDATE users SET  username='$username',email='$email', password='$hashedPassword', user_type='$user_type' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);
    if($query_run){
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
    $office = mysqli_real_escape_string($conn, $_POST['office']);

    if($user_type=='user'){
    $sql2 = "CREATE TABLE $username (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type VARCHAR(100) NOT NULL,
    office_type VARCHAR(100) NOT NULL,
    office VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $query_run = mysqli_query($conn, $sql2);
     }

    // if($user_type == 'user'){

        // Check if the table already exists in the database
        // $check_table_query = "SHOW TABLES LIKE '$username'";
        // $result_table_name = mysqli_query($conn, $check_table_query);

        // if(mysqli_num_rows($result_table_name) == 0){
            // If the table does not exist, create it
        // $sql2 = "CREATE TABLE $username (
        //     id INT(11) PRIMARY KEY AUTO_INCREMENT PRIMARY KEY,                       
        //     `date` date NOT NULL,
        //     `time` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `place` varchar(255) NOT NULL,
        //     `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `zoom_id` date NOT NULL,
        //     `zoom_passcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `zoom_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `month` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,           
        //     `distribution_date` date NOT NULL,
        //     `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,            
        //     `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        //     `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
        //     `time` datetime NOT NULL,
        //     `modified` timestamp NOT NULL DEFAULT current_timestamp(),
        //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        // $query_run = mysqli_query($conn, $sql2);
            // $_SESSION['message'] = "<span class='text-danger'><b>Table Name Already Exists.</b></span>";
            // header("Location: manage_user.php");
            // exit(0);
        // }

    $query = "INSERT INTO users (username,email,password,user_type,office) VALUES ('$username','$email','$hashedPassword','$user_type','$office')";
    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Created Successfully</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
    else {
        $_SESSION['message'] = "<span class='text-danger'><b>User Not Created</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
}

?>