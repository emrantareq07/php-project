<?php
session_name('blrr'); 
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
echo $user_type;
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once '../db/database.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title> Edit</title>
</head>
<body>  
    <div class="container mt-4">
        <?php //include('message.php'); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="manage_user.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left" style="font-size:16px"></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM users WHERE id='$id'";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $user_type=$student['user_type'];
                                    $office=$student['office'];  
                                    $table_name=$student['table_name']; 
                                    $office_title=$student['office_title'];
                                ?>
                        <form action="manage_user-code.php" method="POST">
                        <input type="hidden" name="id" value="<?= $student['id']; ?>">

                        <div class="mb-3">
                            <label for="username">User Name</label>
                            <input type="text" name="username" id="username" value="<?=$student['username'];?>" class="form-control">                            
                        </div>    
                        <!--  <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" value="<?=$student['email'];?>" class="form-control">                            
                        </div> -->
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="text" name="password" id="password" value="<?=$student['password'];?>" class="form-control">
                        </div>

                        <script type="text/javascript">
                            var passwordInput = document.getElementById("password");
                            function genPassword(event) {
                                event.preventDefault(); // Prevent the default form submission behavior
                                var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                                var passwordLength = 6;
                                var newPassword = "";
                                for (var i = 0; i < passwordLength; i++) {
                                    var randomNumber = Math.floor(Math.random() * chars.length);
                                    newPassword += chars.substring(randomNumber, randomNumber + 1);
                                }
                                passwordInput.value = newPassword;
                            }

                            function copyPassword(event) {
                                event.preventDefault(); // Prevent the default form submission behavior
                                passwordInput.select();
                                passwordInput.setSelectionRange(0, 999);
                                document.execCommand("copy");
                            }
                        </script>               
				
				 <div class="form-group mb-3">
                    <label>User Type</label>
                    <select class="form-control" id="user_type" name="user_type" >
                   <!-- <option  disabled selected></option> -->
                    <option value="user" <?=$student['user_type'] == 'user' ? ' selected="selected"' : '';?>>User</option>  
                    <option value="admin" <?=$student['user_type'] == 'admin' ? ' selected="selected"' : '';?>>Admin</option> 
                    </select>
                    <!-- <input type="text" name="role" value="<?=$student['role'];?>" class="form-control"> -->
                </div>
                 <div class="mb-3">
                     <div class="form-group">
                        <label>Office</label>
                        <select class="form-select form-select mb-3 " name="office" aria-label=".form-select example" required>
                        <option value="">--Select--</option>
                        <?php 
                        $query ="SELECT division FROM division";
                        $result = $conn->query($query);
                        if($result->num_rows> 0){
                            while($optionData=$result->fetch_assoc()){
                            $option =$optionData['division'];
                        ?>
                        <?php
                            if(!empty($office) && $office== $option){
                        ?>
                        <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
                        <?php 
                            continue;
                       }?>
                        <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
                       <?php
                        }}
                        ?>
                        </select>
                    </div>
                </div> 

                 <div class="mb-3">
                    <label for="office_title">Office Title</label>
                    <input type="text" name="office_title" id="office_title" value="<?=$student['office_title'];?>" class="form-control">
                </div> 

                <div class="mb-3">
                     <div class="form-group">
                        <label>Table Name</label>
                        <select class="form-select form-select mb-3 " name="table_name" aria-label=".form-select example" required>
                        <option value="">--Select--</option>
                        <?php 
                        $query ="SELECT table_name FROM division";
                        $result = $conn->query($query);
                        if($result->num_rows> 0){
                            while($optionData=$result->fetch_assoc()){
                            $option =$optionData['table_name'];
                        ?>
                        <?php
                            if(!empty($table_name) && $table_name== $option){
                        ?>
                        <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
                        <?php 
                            continue;
                       }?>
                        <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
                       <?php
                        }}
                        ?>
                        </select>
                    </div>
                </div> 
				
                <div class="form-group mb-3">
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fa fa-save" style="font-size:16px"></i> Update
                    </button>
                </div>
            </form>
            <?php
        }    //}
        else {
            echo "<h4>No Such Id Found</h4>";
        }
    
    ?>
                    </div>
                </div>
            </div> 
            <div class="col-md-3"></div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
}else{   
}
?>