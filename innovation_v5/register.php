<?php
require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH . 'libs/function.php');

// Object creation
$userdata = new DB_con();

if(isset($_POST['submit'])) {
    // Posted Values
    $fullname = $_POST['fullname'];
    $emp_id = $_POST['emp_id'];
    $email = $_POST['email'];
    
    // IMPORTANT: MD5 encode the password before storing
    $password = md5($_POST['password']);
    
    // Check if username already exists
    $check_username = $userdata->usernameavailblty($emp_id);
    $count_username = mysqli_num_rows($check_username);
    
    // Check if email already exists
    $check_email = $userdata->uemailavailblty($email);
    $count_email = mysqli_num_rows($check_email);
    
    if($count_username > 0) {
        echo "<script>alert('Employee ID already exists. Please choose another.');</script>";
        echo "<script>window.location.href='register.php'</script>";
    } elseif($count_email > 0) {
        echo "<script>alert('Email already exists. Please use another email.');</script>";
        echo "<script>window.location.href='register.php'</script>";
    } else {
        // Function Calling for registration - password already MD5 encoded
        $sql = $userdata->registration($fullname, $emp_id, $email, $password);
        
        if($sql) {
            // Message for successful insertion
            echo "<script>alert('Registration successful! Please login.');</script>";
            echo "<script>window.location.href='login.php'</script>";
        } else {
            // Message for unsuccessful insertion
            echo "<script>alert('Something went wrong. Please try again');</script>";
            echo "<script>window.location.href='register.php'</script>";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    
    <title>Register - BCIC Innovation Database</title>
    <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            width: 100%;
            max-width: 550px;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .card-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 15px;
        }
        
        .card-header h3 {
            font-size: 24px;
            font-weight: 600;
            margin: 10px 0 5px;
        }
        
        .card-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        
        .card-body {
            padding: 40px 35px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .input-group:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: none;
            padding: 12px 15px;
            color: #6c757d;
            width: 45px;
            justify-content: center;
        }
        
        .form-control {
            border: none;
            padding: 12px 15px;
            font-size: 15px;
        }
        
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
        
        label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .status-message {
            font-size: 12px;
            margin-top: 5px;
            display: block;
            padding-left: 5px;
        }
        
        .text-success {
            color: #28a745 !important;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .back-home {
            text-align: center;
            margin-top: 15px;
        }
        
        .back-home a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-home a:hover {
            text-decoration: underline;
        }
        
        .password-hint {
            font-size: 11px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        @media (max-width: 576px) {
            .card-body {
                padding: 30px 20px;
            }
            
            .form-control {
                font-size: 16px;
            }
        }
    </style>
    
    <script>
    function check_uname() {
        var emp_id = document.getElementById("emp_id").value;
        
        if(emp_id) {
            $.ajax({
                type: 'post',
                url: 'libs/checkdata.php',
                data: {
                    emp_id: emp_id
                },
                success: function(response) {
                    $('#name_status').html(response);
                    if(response.includes("Available")) {
                        $('#name_status').removeClass('text-danger').addClass('text-success');
                    } else {
                        $('#name_status').removeClass('text-success').addClass('text-danger');
                    }
                }
            });
        } else {
            $('#name_status').html("");
        }
    }

    function checkemail() {
        var email = document.getElementById("email").value;
        
        if(email) {
            $.ajax({
                type: 'post',
                url: 'libs/checkdata.php',
                data: {
                    email: email
                },
                success: function(response) {
                    $('#email_status').html(response);
                    if(response.includes("Available")) {
                        $('#email_status').removeClass('text-danger').addClass('text-success');
                    } else {
                        $('#email_status').removeClass('text-success').addClass('text-danger');
                    }
                }
            });
        } else {
            $('#email_status').html("");
        }
    }

    function checkPassword() {
        var password = document.getElementById("password").value;
        var confirm = document.getElementById("confirm_password").value;
        
        if(password != confirm && confirm != "") {
            $('#password_status').html("Passwords do not match").removeClass('text-success').addClass('text-danger');
            return false;
        } else if(confirm != "") {
            $('#password_status').html("Passwords match").removeClass('text-danger').addClass('text-success');
            return true;
        }
    }

    function checkall() {
        var namehtml = $('#name_status').html() || "";
        var emailhtml = $('#email_status').html() || "";
        var password = $('#password').val();
        var confirm = $('#confirm_password').val();
        
        if(password != confirm) {
            alert("Passwords do not match!");
            return false;
        }
        
        if(password.length < 6) {
            alert("Password must be at least 6 characters!");
            return false;
        }
        
        if(namehtml.includes("Available") && emailhtml.includes("Available")) {
            return true;
        } else {
            if(!namehtml.includes("Available")) {
                alert("Please check Employee ID availability!");
            } else if(!emailhtml.includes("Available")) {
                alert("Please check Email availability!");
            }
            return false;
        }
    }

    $(document).ready(function() {
        $('#confirm_password').on('keyup', checkPassword);
        $('#password').on('keyup', checkPassword);
    });
    </script>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="card-header">
                <img src="images/bcic_logo.png" alt="BCIC Logo">
                <h3>BCIC Innovation Database</h3>
                <p>Create a new account</p>
            </div>
            
            <div class="card-body">
                <form action="" method="post" id="registerForm" onsubmit="return checkall();">
                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="fullname">
                            <i class="fas fa-user me-2"></i>Full Name
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="fullname" 
                                   name="fullname" 
                                   placeholder="Enter your full name"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Employee ID -->
                    <div class="form-group">
                        <label for="emp_id">
                            <i class="fas fa-id-card me-2"></i>Employee ID
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="emp_id" 
                                   name="emp_id" 
                                   placeholder="Enter your employee ID"
                                   onkeyup="check_uname()"
                                   required>
                        </div>
                        <span id="name_status" class="status-message"></span>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter your email"
                                   onkeyup="checkemail()"
                                   required>
                        </div>
                        <span id="email_status" class="status-message"></span>
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter password (min. 6 characters)"
                                   minlength="6"
                                   required>
                        </div>
                        <small class="password-hint">
                            <i class="fas fa-info-circle me-1"></i>Minimum 6 characters
                        </small>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock me-2"></i>Confirm Password
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   placeholder="Re-enter your password"
                                   required>
                        </div>
                        <span id="password_status" class="status-message"></span>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-register" id="submit" name="submit">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                    
                    <!-- Login Link -->
                    <div class="login-link">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="back-home">
            <a href="index.php"><i class="fas fa-arrow-left me-2"></i>Back to Home</a>
        </div>
    </div>
</body>
</html>