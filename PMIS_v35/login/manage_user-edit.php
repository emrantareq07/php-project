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
if ($role === 'admin') {
    // if (($role === 'admin') || ($role === 'sadmin')) {
  include 'db.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Training Edit</title>
</head>
<body>
  
    <div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="manage_user.php" class="btn btn-danger float-end">BACK</a>
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
                                    $role=$student['role'];
                                ?>
                                <form action="manage_user-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                 <!--    <div class="mb-3">
                                        <label>Student Name</label>
                                        <input type="text" name="user_id" value="<?=$student['user_id'];?>" class="form-control">
                                    </div> -->
                                    <!-- <div class="mb-3">
                                        <label>Emp ID</label>
                                        <input type="emp_id" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control">
                                    </div> -->
                                    <div class="mb-3">
                                        <label>Emp ID</label>
                                        <input type="text" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control" readonly>
                                    </div>

        <div class="mb-3">
            <label>Designation</label>
            <!-- <input type="text" name="designation" value="<?=$student['designation'];?>" class="form-control"> -->
            <select class="form-control" id="designation" name="designation" >

            <option value="" disabled selected>--Select--</option>
            
                <?php               
                $sql = "SELECT * FROM designation  order by designation ASC";
                $result = mysqli_query($conn, $sql);                        
                while($basicrow = mysqli_fetch_array($result)){                     
                ?>  
                 <option value="<?php echo $basicrow['designation'];?>"                        
                   
                 <?php if($student['designation']==$basicrow['designation']){
                        echo "selected";
                 }
                 ?>
                 ><?php echo $basicrow['designation'];?></option>                     
                <?php
                }?> 
            </select>

        </div>

<div class="mb-3">
    <label for="password">Password</label>
    <input type="text" name="password" id="password" value="<?=$student['password'];?>" class="form-control">

    <button id="generateButton" class="btn1" onclick="genPassword(event)">
        Generate
    </button>

    <button id="copyButton" class="btn2" onclick="copyPassword(event)">
        Copy
    </button>
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
                    <label>Status</label>
                    <input type="text" name="status" value="<?=$student['status'];?>" class="form-control" readonly>
                <!--     <select class="form-control" id="status" name="status" required>
                <option value="" disabled selected>--Select--</option>
                <option value="approved" <?=$student == 'approved' ? ' selected="selected"' : '';?> >approved</option>
                <option value="Pending" <?=$student == 'Pending' ? ' selected="selected"' : '';?> >Pending</option> -->
                </div>
				
				 <div class="form-group mb-3">
                    <label>Role</label>
                    <input type="text" name="role" value="<?=$student['role'];?>" class="form-control" readonly>
                </div>
				
				
                <!--<div class="form-group"><label for="type"> Role:</label>
                <select class="form-control" id="role" name="role">
                    
                <option value="" disabled selected>--Select--</option>
                     
                <option value="admin" <?=$role == 'admin' ? ' selected="selected"' : '';?> >Admin</option>
                <option value="user" <?=$role == 'user' ? ' selected="selected"' : '';?> >User</option>
                
                </select>
                </div>-->

                <div class="form-group mb-3">
                    <button type="submit" name="update_student" class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>
            <?php
        }
    //}
        else
        {
            echo "<h4>No Such Id Found</h4>";
        }
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

    include 'db.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title> Edit</title>
</head>
<body>
  
    <div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="manage_user.php" class="btn btn-danger float-end">BACK</a>
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
                                    $role=$student['role'];
                                ?>
                                <form action="manage_user-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                 <!--    <div class="mb-3">
                                        <label>Student Name</label>
                                        <input type="text" name="user_id" value="<?=$student['user_id'];?>" class="form-control">
                                    </div> -->
                                    <!-- <div class="mb-3">
                                        <label>Emp ID</label>
                                        <input type="emp_id" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control">
                                    </div> -->
                                    <div class="mb-3">
                                        <label>Emp ID</label>
                                        <input type="text" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control" readonly>
                                    </div>

        <div class="mb-3">
            <label>Designation</label>
            <!-- <input type="text" name="designation" value="<?=$student['designation'];?>" class="form-control"> -->
            <select class="form-control" id="designation" name="designation" >

            <option value="" disabled selected>--Select--</option>
            
                <?php               
                $sql = "SELECT * FROM designation  order by designation ASC";
                $result = mysqli_query($conn, $sql);                        
                while($basicrow = mysqli_fetch_array($result)){                     
                ?>  
                 <option value="<?php echo $basicrow['designation'];?>"                        
                   
                 <?php if($student['designation']==$basicrow['designation']){
                        echo "selected";
                 }
                 ?>
                 ><?php echo $basicrow['designation'];?></option>                     
                <?php
                }?> 
            </select>

        </div>

<div class="mb-3">
    <label for="password">Password</label>
    <input type="text" name="password" id="password" value="<?=$student['password'];?>" class="form-control">

    <button id="generateButton" class="btn1" onclick="genPassword(event)">
        Generate
    </button>

    <button id="copyButton" class="btn2" onclick="copyPassword(event)">
        Copy
    </button>
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
    <label>Status</label>
    <select class="form-control" id="status" name="status" required>
        <option value="" disabled selected>--Select--</option>
        <option value="approved" <?= $student['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
        <option value="pending" <?= $student['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
    </select>
</div>

<div class="form-group mb-3">
    <label>Role</label>
    <select class="form-control" id="role" name="role" required>
        <option value="" disabled selected>--Select--</option>
        <option value="admin" <?= $student['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        <option value="user" <?= $student['role'] == 'user' ? 'selected' : ''; ?>>User</option>
        <option value="sadmin" disabled <?= $student['role'] == 'sadmin' ? 'selected' : ''; ?> readonly>Super Admin</option>
    </select>
</div>

            <div class="form-group mb-3">
                <button type="submit" name="update_student" class="btn btn-block btn-md btn-primary">
                    Update
                </button>
            </div>

        </form>
        <?php
        }
    //}
        else {
            echo "<h4>No Such Id Found</h4>";
        }
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
}

?>