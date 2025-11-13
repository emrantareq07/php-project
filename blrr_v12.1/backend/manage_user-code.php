<?php 
session_name('blrr');
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
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = sha1($password);
    
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
	$office = mysqli_real_escape_string($conn, $_POST['office']);
    $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);
	$office_title = mysqli_real_escape_string($conn, $_POST['office_title']);

    $sql = "SELECT * FROM users WHERE id='$id' ";    
    $result = mysqli_query($conn, $sql);	
	$row = mysqli_fetch_array($result);
	$password_db=$row['password'];
	$hashedPassword_db = sha1($password_db);
    
    $table_name_db=$row['table_name'];

    if($table_name!=$table_name_db && $user_type=='user'){
        $query_change_username = "ALTER TABLE $table_name_db RENAME TO $table_name";
        // Execute the query
        $query_run = mysqli_query($conn, $query_change_username);
    }
	
	if($hashedPassword===$hashedPassword_db){								
	$query = "UPDATE users SET  username='$username',user_type='$user_type', office='$office', office_title='$office_title',table_name='$table_name'  WHERE id='$id' ";
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
    $query = "UPDATE users SET  username='$username',user_type='$user_type', office='$office', office_title='$office_title',table_name='$table_name'  WHERE id='$id' ";
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
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = sha1($password);
    
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $office = mysqli_real_escape_string($conn, $_POST['office']);
    $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);
    $office_title = mysqli_real_escape_string($conn, $_POST['office_title']);
    
    if($user_type == 'user'){

        // Check if the table already exists in the database
        $check_table_query = "SHOW TABLES LIKE '$table_name'";
        $result_table_name = mysqli_query($conn, $check_table_query);

        if(mysqli_num_rows($result_table_name) == 0){
            // If the table does not exist, create it
        $sql2 = "CREATE TABLE $table_name (
            id INT(11) PRIMARY KEY AUTO_INCREMENT PRIMARY KEY,
            `unique_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,           
            `entry_date` date NOT NULL,
            `recipient` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `immediate_sender_office` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `d_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `attention` varchar(255) NOT NULL,
            `ref_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `send_date` date NOT NULL,
            `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `div_dept_office` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `destination` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `destination_drop` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,           
            `distribution_date` date NOT NULL,
            `chairman_note` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,            
            `medium` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
            `time` datetime NOT NULL,
            `modified` timestamp NOT NULL DEFAULT current_timestamp(),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $query_run = mysqli_query($conn, $sql2);
            // $_SESSION['message'] = "<span class='text-danger'><b>Table Name Already Exists.</b></span>";
            // header("Location: manage_user.php");
            // exit(0);
        }

        // Insert the user into the users table
    $query = "INSERT INTO users (username, password, user_type, office, office_title,table_name) VALUES ('$username', '$hashedPassword', '$user_type', '$office','$office_title', '$table_name')";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Created Successfully</b></span>";
        header("Location: manage_user.php");
        exit(0);
    } else {
        $_SESSION['message'] = "<span class='text-danger'><b>User Not Created</b></span>";
        header("Location: manage_user.php");
        exit(0);
    }
        
    }    
}


?>