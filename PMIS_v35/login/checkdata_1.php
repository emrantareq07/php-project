<?php
include('db.php');

if (isset($_POST['emp_id'])) {
  $emp_id = $_POST['emp_id'];

  // Perform the necessary checks for emp_id
  // For example, you can check if the emp_id exists in the database
  $sql = "SELECT * FROM users WHERE emp_id = '$emp_id'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // emp_id is valid
    echo "OK";
  } else {
    // emp_id is invalid
    echo "Invalid Employee ID";
  }
} elseif (isset($_POST['password'])) {
  $password = $_POST['password'];

  // Perform the necessary checks for password
  // For example, you can check if the password meets certain requirements
  if (strlen($password) >= 6) {
    // password is valid
    echo "OK";
  } else {
    // password is invalid
    echo "Invalid Password";
  }
}

mysqli_close($conn);
?>
