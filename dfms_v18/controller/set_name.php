<?php
session_name('dfms');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Debug connection
try {
    // Replace this with the actual values or test your connection manually
    $conn = new PDO("mysql:host=localhost;dbname=dfms_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected successfully!<br>";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
include('../include/header_index.php');
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

$head_name = ''; // Default value

if ($user_type == 'sadmin' || $user_type == 'admin') {
    $query = "SELECT * FROM head_name LIMIT 1"; // Adjust the LIMIT as needed
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $head_name = $row['head_name']; // Assign the fetched value
        echo "Fetched Head Name: " . htmlspecialchars($head_name) . "<br>";
    } else {
        echo "No data found in the head_name table.<br>";
    }
}

if (isset($_POST['save'])) {
    $updated_name = $_POST['head_name'];

    // Update the database with the new head_name
    $update_query = "UPDATE head_name SET head_name = ? LIMIT 1";
    $update_stmt = $conn->prepare($update_query);
    if ($update_stmt->execute([$updated_name])) {
        echo "Head name updated successfully!";
        $_SESSION['message'] = "Head name updated successfully!";
        header("Location: home.php");
        exit();
    } else {
        echo "Failed to update the head name.";
    }
}
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="text-uppercase fw-bold fw-bolder text-muted">
                        <b>Edit Name (Divisional Head)</b>
                        <a href="home.php" class="btn btn-danger float-end">
                            <i class="fa fa-arrow-left"></i> BACK
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="head_name">User Name</label>
                            <input 
                                type="text" 
                                name="head_name" 
                                id="head_name" 
                                class="form-control" 
                                value="<?= htmlspecialchars($head_name); ?>" 
                                required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="save" class="btn btn-block btn-primary">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
    </div>
</div>

<?php include('../include/footer.php'); ?>
