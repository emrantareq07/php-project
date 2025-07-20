<?php
session_name('ict_main_records_db');
session_start();
// $username=$_SESSION['username']; //chairman

// $user_type=$_SESSION['user_type'];//admin
// $office=$_SESSION['office'];
// $code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('db/db.php');
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Font Awesome (optional, only include if needed) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"  />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
</head>
<body>

<div class="container mt-3 p-1 my-1 border border-primary shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
  <!-- <h3 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b> Multi-Searching </b></h3>   -->
    <div class="row mb-4">
    <!-- Centered Content -->
    <div class="col-sm-3"> </div>
    <div class="col-sm-6">
        <div class="card border border-warning mt-2 p-1 my-1">
            <div class="card-header" style="background-color: #8b32a8;">
                <h5 class="text-uppercase text-white text-center">
                    <b>Training Title, Type, Designtation, Place of Posting</b>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="searching_info.php" enctype="multipart/form-data">
                    <!-- Place of Posting -->
                    <div class="form-group mb-3">
                        <label for="place_of_posting">Place of Posting:</label>
                        <select class="form-control" id="place_of_posting" name="place_of_posting">
                            <option value="" disabled selected>--Select--</option>
                            <option value="All">All</option>
                            <?php
                                $sql = "SELECT * FROM place_of_posting ORDER BY place_of_posting ASC";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)) {
                                    echo "<option value='".$row['place_of_posting']."'>".$row['place_of_posting']."</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Designation -->
                    <div class="form-group mb-3">
                        <label for="designation">Designation:</label>
                        <select class="form-control" id="designation" name="designation">
                            <option value="" disabled selected>--Select--</option>
                            <?php
                                $sql = "SELECT * FROM designation ORDER BY designation ASC";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)) {
                                    echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Training Title -->
                    <div class="form-group mb-3">
                        <label for="training_title">Division/Department:</label>
                        <select class="form-control" id="training_title" name="training_title" required>
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

                    <!-- Training Type -->
                    <div class="form-group mb-4">
                        <label for="training_type">Training Type:</label>
                        <select class="form-control" id="training_type" name="training_type">
                            <option value="" disabled selected>--Select--</option>
                            <option value="local">Local</option>
                            <option value="foreign">Foreign</option>
                        </select>
                    </div>
                                        <!-- Training Type -->
                    <div class="form-group mb-4">
                        <label for="training_do_or_not">Training DO or Do Not:</label>
                        <select class="form-control" id="training_do_or_not" name="training_do_or_not">
                            <!-- <option value="" disabled selected>--Select--</option> -->
                            <option value="do">DO</option>
                            <option value="donot">Do Not</option>
                        </select>
                    </div>
                    

                    <!-- Search Button -->
                    <button type="submit" id="submit" name="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-search"></i> Search
                    </button>
                </form>

            </div>
        </div>
    </div>
                        <!-- Previous Page Button -->
    <div class="col-sm-3 mt-2 p-1 my-1">
        <a href="dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Previous Page</a>
    </div>
</div>


</div>
 </body>
 </html> 
</script>
<?php //include('../includes/footer.php');?>