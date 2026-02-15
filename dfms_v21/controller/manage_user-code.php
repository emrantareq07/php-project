<?php 
session_name('dfms');
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

require_once('../db/db.php');

/* ---------------- DELETE USER ---------------- */
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Get username before deleting
    $get_user = mysqli_query($conn, "SELECT username FROM users WHERE id='$id'");
    $user_row = mysqli_fetch_assoc($get_user);
    $username = $user_row['username'];

    // Delete user from users table
    $query = "DELETE FROM users WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Drop the user's table if it exists
        if (!empty($username)) {
            $drop_query = "DROP TABLE IF EXISTS `$username`";
            mysqli_query($conn, $drop_query);
        }

        echo '<html><head><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head><body>';
        echo '<script>Swal.fire({title:"Success!",text:"User Deleted Successfully",icon:"success"}).then(()=>{window.location.href="manage_user.php";});</script>';
        echo '</body></html>';
    } else {
        echo '<html><head><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head><body>';
        echo '<script>Swal.fire({title:"Error!",text:"User Information Not Deleted",icon:"error"}).then(()=>{window.location.href="manage_user.php";});</script>';
        echo '</body></html>';
    }
    exit();
}


/* ---------------- UPDATE USER ---------------- */
if (isset($_POST['update'])) {     
    $id = mysqli_real_escape_string($conn, $_POST['id']);    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);    
    $hashedPassword = sha1($password);
    
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $product_type = mysqli_real_escape_string($conn, $_POST['product_type']);
    $user_status = mysqli_real_escape_string($conn, $_POST['user_status']);
    $office_title = mysqli_real_escape_string($conn, $_POST['office_title']);
    $product = mysqli_real_escape_string($conn, $_POST['product']);

    // get password from db
    $sql = "SELECT password FROM users WHERE id='$id' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $password_db = $row['password'];
    $hashedPassword_db = sha1($password_db);
    
    if($hashedPassword===$hashedPassword_db){
        // update without password
        $query = "UPDATE users 
                  SET username='$username', email='$email', user_type='$user_type',
                      product_type='$product_type', user_status='$user_status',
                      office_title='$office_title', product='$product'
                  WHERE id='$id' ";
    } else {
        // update with new password
        $query = "UPDATE users 
                  SET username='$username', email='$email', password='$hashedPassword',
                      user_type='$user_type', product_type='$product_type',
                      user_status='$user_status', office_title='$office_title', product='$product'
                  WHERE id='$id' ";
    }

    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Information Updated Successfully!!!</b></span>";
    } else {
        $_SESSION['message'] = "<span class='text-danger'><b>User Information Not Updated</b></span>";
    }
    header("Location: manage_user.php");
    exit();
}

/* ---------------- ADD USER ---------------- */
if (isset($_POST['save'])) {            
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashedPassword = sha1($password);

    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $product_type = mysqli_real_escape_string($conn, $_POST['product_type']);
    $user_status = mysqli_real_escape_string($conn, $_POST['user_status']);
    $office_title = mysqli_real_escape_string($conn, $_POST['office_title']);
    $product = mysqli_real_escape_string($conn, $_POST['product']);
    $created_at = date('Y-m-d H:i:s'); // auto set created_at

    // check table already exists
    $check_exists_table = "SELECT * FROM users WHERE username ='$username'";
    $check_exists_table_run = mysqli_query($conn, $check_exists_table);

    if (mysqli_num_rows($check_exists_table_run) == 0) {
        $create_table = "CREATE TABLE `$username` (
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
        mysqli_query($conn, $create_table);  
    }

    // Insert into users table
    $query = "INSERT INTO users 
        (username, email, password, user_type, product_type, user_status, office_title, product, created_at) 
        VALUES 
        ('$username','$email','$hashedPassword','$user_type','$product_type','$user_status','$office_title','$product','$created_at')";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['message'] = "<span class='text-success'><b>User Created Successfully</b></span>";
    } else {
        $_SESSION['message'] = "<span class='text-danger'><b>User Not Created</b></span>";
    }
    header("Location: manage_user.php");
    exit();
}
?>
