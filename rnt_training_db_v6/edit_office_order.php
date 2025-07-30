<?php
session_name('rnt_training_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('db/db.php');
include('includes/header.php');

// Fetch the record to edit
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    $query = mysqli_query($conn, "SELECT * FROM office_order WHERE id = '$id'");
    $order = mysqli_fetch_assoc($query);

    if (!$order) {
        echo "Office order not found.";
        exit;
    }
} else {
    echo "Invalid ID.";
    exit;
}

// Handle form submission for updating the record
if (isset($_POST['submit'])) {
    $ref_no = mysqli_real_escape_string($conn, $_POST['ref_no']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $training_type = mysqli_real_escape_string($conn, $_POST['training_type']);
    $training_title = mysqli_real_escape_string($conn, $_POST['training_title']);
    $t_institute = mysqli_real_escape_string($conn, $_POST['t_institute']);

    $order_attachment = $order['order_attachment']; // Keep the old attachment by default

    // Handle file upload if a new file is provided
    if (!empty($_FILES['attachment']['name'])) {
        $targetDir = "uploads/";
        $newFileName = time() . "_" . basename($_FILES['attachment']['name']);
        $targetFilePath = $targetDir . $newFileName;

        // Check if the target directory exists; if not, create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFilePath)) {
            // Delete the old file if it exists
            if (!empty($order['order_attachment']) && file_exists($targetDir . $order['order_attachment'])) {
                unlink($targetDir . $order['order_attachment']); // Delete old file
            }
            $order_attachment = $newFileName; // Update to new file name
        } else {
            echo "Error: File upload failed. Please try again.";
        }
    }

    // Update the record in the database
    $sql = "UPDATE office_order 
            SET ref_no = '$ref_no', date = '$date', start_date = '$start_date', 
                end_date = '$end_date', order_attachment = '$order_attachment', 
                training_type = '$training_type', training_title = '$training_title', 
                t_institute = '$t_institute' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: add_office_order.php?updated=successfully");
        exit;
    } else {
        echo "Database error: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6 border rounded shadow-lg border-primary">
            <h2 class="text-uppercase text-center text-muted">Edit Office Order</h2>

            <?php if (@$_GET['submitted']) { ?>
                <div class="alert alert-success fw-bold" role="alert">
                    Data Inserted Successfully!!
                </div>
            <?php } ?>

            <?php if (@$_GET['updated']) { ?>
                <div class="alert alert-success fw-bold" role="alert">
                    Data Updated Successfully!!
                </div>
            <?php } ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=$id"; ?>" enctype="multipart/form-data">
                <div class="row g-2">
                    <div class="col-md-4 mb-1">
                        <div class="form-floating mt-0 mb-0">
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo $order['date']; ?>" required>
                            <label for="date">Date</label>
                        </div>
                    </div>
                    <div class="col-md-8 mb-1">
                        <div class="form-floating mt-0 mb-0">
                            <input type="text" class="form-control" id="ref_no" name="ref_no" value="<?php echo $order['ref_no']; ?>" required>
                            <label for="ref_no">Enter Reference No.</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-0">
                    <label for="training_type" class="form-label">Training Type</label>
                    <select class="form-control" id="training_type" name="training_type" required>
                        <option value="local" <?php echo ($order['training_type'] == 'local') ? 'selected' : ''; ?>>Local</option>
                        <option value="foreign" <?php echo ($order['training_type'] == 'foreign') ? 'selected' : ''; ?>>Foreign</option>
                        <option value="workshop" <?php echo ($order['training_type'] == 'workshop') ? 'selected' : ''; ?>>Workshop</option>
                        <option value="seminar" <?php echo ($order['training_type'] == 'seminar') ? 'selected' : ''; ?>>Seminar</option>
                        <option value="conference" <?php echo ($order['training_type'] == 'conference') ? 'selected' : ''; ?>>Conference</option>
                    </select>
                </div>
                <div class="mb-1">
                    <label for="training_title" class="form-label">Title/Subject</label>
                    <select class="form-select" id="training_title" name="training_title" required>
                        <option value="" disabled>Select</option>
                        <?php
                        $titles = mysqli_query($conn, "SELECT t_name FROM training_list");
                        while ($row = mysqli_fetch_array($titles)) {
                            $selected = ($row['t_name'] == $order['training_title']) ? 'selected' : '';
                            echo "<option value='{$row['t_name']}' $selected>{$row['t_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="row g-2">
                    <div class="col-md-6 mb-1">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $order['start_date']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $order['end_date']; ?>" required>
                    </div>
                </div>
                <div class="form-floating mt-0 mb-2">
                    <input type="text" class="form-control" id="t_institute" name="t_institute" value="<?php echo $order['t_institute']; ?>" required>
                    <label for="t_institute">Enter Institute</label>
                </div>
                <div class="form-floating mt-0 mb-2">
                    <input type="file" class="form-control" id="attachment" name="attachment">
                    <label for="attachment">Attachment</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Update
                </button>
            </form>
            <br>
        </div>
        <div class="col-sm-3">
            <a href="add_office_order.php" class="btn btn-outline-info float-end mt-2">
                <i class="fa fa-arrow-left"></i> Previous Page
            </a>
        </div>
    </div>
</div>