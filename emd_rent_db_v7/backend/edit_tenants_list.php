<?php 
session_name('emd_rent_db');
session_start();
include_once '../db/database.php';
include_once 'header.php';
require_once("config.php");

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username']; 
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];

// Retrieve tenants table from URL
$tenants_tbl = $_GET['tenants_tbl'] ?? '';
$_SESSION['tenants_tbl'] = $tenants_tbl;

$id = $_GET['id'] ?? '';
if (empty($id)) {
    die('Error: Tenant ID is missing.');
}

// Fetch existing tenant details
$sql_option_match = "SELECT * FROM company WHERE id = ?";
$stmt = $conn->prepare($sql_option_match);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Error: Tenant not found.');
}

$row = $result->fetch_assoc();
$table_name = $row['table_name'];
$tenants_name = $row['tenants_name'];
$address = $row['address'];

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);
    $tenants_name = mysqli_real_escape_string($conn, $_POST['tenants_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Use prepared statements for the update query
    $sql_update = "UPDATE company SET table_name = ?, tenants_name = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssi", $table_name, $tenants_name, $address, $id);

    if ($stmt->execute()) {        
        echo "<p class='fw-bold text-dark col-sm-4 offset-4 p-2 my-3 bg-warning  rounded text-center'> Tenant details updated successfully!!!</p>"; 
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rent Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
</head>
<body> 
  <div class="container my-4">
    <div class="card shadow-lg">
      <div class="card-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST">
          <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="table_name">Table Name</label>
              <input type="text" class="form-control" id="table_name" value="<?php echo $table_name; ?>" name="table_name" readonly>
            </div>     

            <div class="col-sm-3 mb-3">
              <label for="tenants_name">Tenants Name</label>
              <input type="text" class="form-control" id="tenants_name" value="<?php echo $tenants_name; ?>" name="tenants_name" required>
            </div>

            <div class="col-sm-3 mb-3">
              <label for="address">Address:</label>
              <textarea name="address" id="address" class="form-control" required><?php echo $address; ?></textarea>
            </div>

            <div class="col-sm-3 mb-3">
              <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
              <a href="tenants_list.php" class="btn btn-primary position-relative me-2 concert-one-regular fw-bold">
            <i class="fa fa-arrow-left" ></i> Back             
          </a>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</body>
</html>
