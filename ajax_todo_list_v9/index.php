<?php
session_name('PROJECT1SESSION');
session_start();
include_once 'db/database.php';
require_once("backend/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $rawPassword = $_POST['password'];
    $password = sha1($rawPassword); // Consider using password_hash in future

    // Prepared statement to check login
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Session variables
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['office'] = $row['office'];
        session_regenerate_id(true);

        // Remember Me Cookies
        if (!empty($_POST["remember"])) {
            setcookie("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60), "/");
            setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60), "/");
        } else {
            setcookie("user_login", "", time() - 3600, "/");
            setcookie("userpassword", "", time() - 3600, "/");
        }

        // Common logging logic
        $login_date_time = date('Y-m-d H:i:s');
        $code = rand(10000, 99999);
        $_SESSION['code'] = $code;

        // Get IP Address
        function getClientIP() {
            $keys = [
                'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
            ];
            foreach ($keys as $key) {
                if (!empty($_SERVER[$key])) {
                    $ip = explode(',', $_SERVER[$key])[0];
                    return trim($ip === '::1' ? '127.0.0.1' : $ip);
                }
            }
            return 'UNKNOWN';
        }
        $ip = getClientIP();

        // Insert login log
        $log_sql = "INSERT INTO log_table (username, password, user_type, Ip, login_date_time, code) 
                    VALUES (?, ?, ?, ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("sssssi", $_SESSION['username'], $password, $_SESSION['user_type'], $ip, $login_date_time, $code);
        $log_stmt->execute();

        // Redirect based on user_type
        if ($_SESSION['user_type'] === 'user') {
            echo "<script>window.location.href='dashboard.php?code=$code';</script>";
        } else {
            echo "<script>window.location.href='sadmin_dashboard.php?code=$code';</script>";
        }

    } else {
        // Invalid login feedback
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo '<script>
                Swal.fire({
                    title: "Username and Password are Incorrect",
                    icon: "warning",
                    confirmButtonColor: "#dc3545"
                }).then(function() {
                    window.location.href = "index.php";
                });
              </script>';
        echo '</body></html>';
    }

    $stmt->close();
}
?>

<?php include_once"backend/header.php";?>
<div class="container-fluid mt-5">
  <div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4">
  
  <div class="card shadow-lg border border-1 border-primary">
  <div class="card-header text-center text-uppercase bg-primary text-white fw-bold righteous-regular"><h4><b>DAILY MEETING SCHEDULE  </b></h4></div>
  <div class="card-body">
  <div class="imgcontainer">      
     <img src="images/bcic_logo.png" alt="BCIC" class="avatar shadow-lg">
    </div>
  <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" class="">
   <!--  <div class="form-floating mb-3">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div> -->
    <!-- <div class="mb-3 mt-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div> -->
    <div class="form-floating mb-3 mt-3 righteous-regular">      
      <input type="text" class="form-control" id="floatingInput" placeholder="Enter Username" name="username" required="">
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating mb-3 righteous-regular">      
      <input type="password" class="form-control" id="floatingPassword" placeholder="Enter password" name="password" required="">
      <label for="floatingPassword">Password</label>
    </div>
    <div class="form-check mb-3 righteous-regular">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary gap-2 col-12 mx-auto righteous-regular" name="login"><i class="fa fa-sign-in" ></i> Sign In</button>
    <!-- <button type="submit" class="btn btn-primary d-grid gap-2 col-12 mx-auto" name="login" style="font-size:24px">Sign In <i class="fa fa-sign-out"></i></button> -->
  </form>
  </div>
  <div class="card-footer text-center text-muted righteous-regular"><b>[--Design & Developed by ICT Division, BCIC.--]</b></div>
</div>  
</div>
  <div class="col-sm-4"></div>
  
</div>
</div>
<?php
include('backend/footer.php');
?>