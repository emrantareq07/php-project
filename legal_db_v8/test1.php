<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show/Hide Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/about.css">
    <style>
        .input-group {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10; /* Ensure it is always on top */
        }

        * {
            font-family: 'Open Sans', sans-serif;
            font-family: 'Tiro Bangla', serif;
            font-family: 'Nikosh', sans-serif;
            font-family: 'Nikosh', serif;
        }

        .bs-example {
            margin: 10px;
        }

        /* Apply the font to the placeholder */
        .form-control::placeholder {
            font-family: 'Nikosh', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row my-2">
            <div class="col-md-2"></div>
            <div class="col-md-8 text-center">
                <img src="images/1.png" alt="BCIC Logo" class="mb-0 rounded" style="max-width: 120px; max-height: 120px;">
                <h2 class="my-2">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h2>
            </div>
            <div class="col-md-2"></div>
        </div>
        
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4 p-4 bg-light border shadow border-primary rounded">
                <form action="" method="post" id="login" autocomplete="off">
                    <h4 class="text-center text-muted text-uppercase mb-4">বিসিআইসি মামলার ডাটাবেস সিস্টেমে</h4>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input name="username" type="text" value="<?php if(isset($_COOKIE['user_login'])) { echo $_COOKIE['user_login']; } ?>" required class="form-control" id="username" placeholder="Username" aria-label="Username" autofocus>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input name="password" type="password" class="form-control" id="password" placeholder="Password" required aria-label="Password">
                        <span class="toggle-password"><i class="fas fa-eye"></i></span>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" <?php if(isset($_COOKIE['user_login'])) { ?> checked <?php } ?> class="form-check-input" id="remember_me">
                        <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>
                    
                    <div class="pt-3">
                        <button class="btn btn-primary btn-block" type="submit" name="signin">Login</button>
                    </div>

                    <?php include_once "includes/developer_info.php"; ?>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const toggleIcon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
