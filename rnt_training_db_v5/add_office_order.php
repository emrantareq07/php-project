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

if (isset($_POST['submit'])) {
    $ref_no = mysqli_real_escape_string($conn, $_POST['ref_no']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $training_type = mysqli_real_escape_string($conn, $_POST['training_type']);
    $training_title = mysqli_real_escape_string($conn, $_POST['training_title']);
    $t_institute = mysqli_real_escape_string($conn, $_POST['t_institute']);

    $order_attachment = "";  

    // Handle file upload
    if (!empty($_FILES['attachment']['name'])) {
        $targetDir = "uploads/";
        $order_attachment = time() . "_" . basename($_FILES['attachment']['name']);
        $targetFilePath = $targetDir . $order_attachment;  // Corrected variable name here

        // Check if the target directory exists; if not, create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create directory with proper permissions
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFilePath)) {
            // File successfully uploaded
        } else {
            echo "Error: File upload failed. Please check your file and try again.";
        }
    }

    // Insert data into the database
$sql = "INSERT INTO office_order (ref_no, date, start_date, end_date, order_attachment, training_type, training_title, t_institute) 
        VALUES ('$ref_no', '$date', '$start_date', '$end_date', '$order_attachment', '$training_type', '$training_title', '$t_institute')";

    if (mysqli_query($conn, $sql)) {
        header("Location: add_office_order.php?submitted=successfully");
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
            <h2 class="text-uppercase text-center text-muted">Add Office Order</h2>

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

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <div class="row g-2">
            <div class="col-md-4 mb-1">
                <div class="form-floating mt-0 mb-0">
                    <input type="date" class="form-control" id="date" placeholder="Enter Date" name="date" required>
                    <label for="date">Date</label></div>
                </div>
                <div class="col-md-8 mb-1 ">
                    <div class="form-floating mt-0 mb-0">
                    <input type="text" class="form-control" id="ref_no" placeholder="Enter Reference No." name="ref_no" required>
                    <label for="ref_no">Enter Reference No.</label></div>
                </div>
            </div>
               <div class="mb-3 mt-0">
                <label for="training_type" class="form-label">Training Type</label>
                <!-- <input type="text" class="form-control" id="training_type" name="training_type"> -->
                <select class="form-control" id="training_type" name="training_type" tabindex="" >
                  <option value="" disabled selected>--Select--</option>
                  <option value="local" >Local</option>
                  <option value="foreign" >Foreign</option> 
                  <option value="workshop" >Workshop</option> 
                  <option value="seminar" >Seminar</option> 
                  <option value="conference" >Conference</option> 
                       
              </select>
            </div>                    
            <div class="mb-1">
                <label for="training_title" class="form-label">Title/Subject</label>
                <!-- <input type="text" class="form-control" id="training_title" name="training_title"> -->
                 <select class="form-select" id="training_title" name="training_title" aria-label="Floating label select example" required>
                  <option value="" disabled selected>--Select--</option>
                  <?php
                    $sql = "SELECT t_name FROM training_list";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result)) {
                      echo "<option value='".$row['t_name']."'>".$row['t_name']."</option>";
                    }
                    ?>   
                </select>
                </div>
                <div class="row g-2">
                <div class="col-md-6 mb-1"><label for="date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" placeholder="Enter Start Date" name="start_date" required>                    
                </div>

                <div class="col-md-6 mb-2"><label for="date">End Date</label>
                    <input type="date" class="form-control" id="end_date" placeholder="Enter End Date" name="end_date" required>                    
                </div>
                </div>

               <!-- <div class="form-floating mt-3 mb-3">                    
                    <input type="text" class="form-control" id="t_institute" name="t_institute">
                    <label for="t_institute" >Institute</label>
                </div> -->
                <div class="form-floating mt-0 mb-2">
                    <input type="t_institute" class="form-control" id="t_institute" placeholder="Enter Date" name="t_institute" required>
                    <label for="t_institute">Enter Institute</label></div>

                <div class="form-floating mt-0 mb-2">
                    <input type="file" class="form-control" id="attachment" name="attachment">
                    <label for="attachment">Attachment</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Submit</button>
            </form>
            <br>
        </div>
        <div class="col-sm-3">
            <a href="dashboard.php" class="btn btn-outline-info float-end mt-2">
                <i class="fa fa-arrow-left"></i> Previous Page
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="table-wrapper">
            <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
                <thead>
                    <tr>
                        <th>ID NO</th>
                        <th>Date</th>
                        <th>Reference No.</th>
                        <th>Training Type</th>
                        <th>Training Title</th> <!-- Add this line -->
                        <th>Institute</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Office Order [Attachment]</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM office_order ORDER BY id Desc");
                    while ($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                             <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['ref_no']; ?></td>
                            <td><?php echo $row['training_type']; ?></td>
                            <td><?php echo $row['training_title']; ?></td>
                             <td><?php echo $row['t_institute']; ?></td>                           
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td>
                            <?php if (!empty($row['order_attachment'])) { ?>
                                <a href="<?php echo 'uploads/' . $row['order_attachment']; ?>" target="_blank">View</a>
                            <?php } else { ?>
                                No Attachment
                            <?php } ?>

                            </td>
                            <td>
                                <a href="edit_office_order.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                                <!-- <a href="delete_office_order.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a> -->
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
