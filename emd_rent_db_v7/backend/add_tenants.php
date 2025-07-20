<?php
session_name('emd_rent_db');
session_start();
ob_start(); // Start output buffering early

$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/database.php';
include_once 'header.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $table_name = mysqli_real_escape_string($conn, $_POST['table_name']);  
    $tenants_name = mysqli_real_escape_string($conn, $_POST['tenants_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if the table already exists
    $check_table_query = "SHOW TABLES LIKE '$table_name'";
    $result = mysqli_query($conn, $check_table_query);

    if (mysqli_num_rows($result) == 0) {
        // Table does not exist, create it
        $sql_table_create = "
        CREATE TABLE `$table_name` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `date` DATE NOT NULL,
            `month` VARCHAR(25) NOT NULL,
            `year` VARCHAR(10) NOT NULL,
            `fiscal_year` VARCHAR(50) NOT NULL,
            `tenants_name` VARCHAR(255) NOT NULL,
            `actual_hr` INT NOT NULL,
            `actual_eb` INT NOT NULL,
            `actual_wb` INT NOT NULL,
            `actual_gb` INT NOT NULL,
            `actual_cb` INT NOT NULL,
            `payorder_no_hr` VARCHAR(15) NOT NULL,
            `payorder_date_hr` DATE NOT NULL,
            `payorder_no_eb` VARCHAR(15) NOT NULL,
            `payorder_date_eb` DATE NOT NULL,
            `payorder_no_wb` VARCHAR(15) NOT NULL,
            `payorder_date_wb` DATE NOT NULL,
            `payorder_no_gb` VARCHAR(15) NOT NULL,
            `payorder_date_gb` DATE NOT NULL,
            `payorder_no_cb` VARCHAR(15) NOT NULL,
            `payorder_date_cb` DATE NOT NULL,
            `payorder_no_comb` VARCHAR(15) NOT NULL,
            `payorder_date_comb` DATE NOT NULL,
            `hr_in` INT NOT NULL,
            `eb_in` INT NOT NULL,
            `wasa_in` INT NOT NULL,
            `gb_in` INT NOT NULL,
            `cb_in` INT NOT NULL,
            `tax5` VARCHAR(20) NOT NULL,
            `challan_no_tax` VARCHAR(15) NOT NULL,
            `date_tax` DATE NOT NULL,
            `month_tax` VARCHAR(10) NOT NULL,
            `vat15` VARCHAR(20) NOT NULL,
            `challa_no_vat` VARCHAR(15) NOT NULL,
            `date_vat` DATE NOT NULL,
            `month_vat` VARCHAR(10) NOT NULL,
            `hr_outs` INT NOT NULL,
            `eb_outs` INT NOT NULL,
            `wasa_outs` INT NOT NULL,
            `gb_outs` INT NOT NULL,
            `cb_outs` INT NOT NULL,
            `all_value` LONGTEXT NOT NULL,
            `address` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            `updated_at` DATETIME NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ";

        if (!mysqli_query($conn, $sql_table_create)) {
            echo "Error creating table: " . mysqli_error($conn);
            exit;
        }
    } else {
        // Redirect if table exists
        header("Location: add_tenants.php?exists=exists");
        exit; // Important to stop execution after redirect
    }

    // Insert data into 'company' table
    $sql_insert = "INSERT INTO `company` (table_name, tenants_name, address) 
                   VALUES ('$table_name', '$tenants_name', '$address')";
    
    if (mysqli_query($conn, $sql_insert)) {
        // Redirect on success
        header("Location: add_tenants.php?submitted=successfully");
        exit; // Stop execution after redirect
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}

// Close the connection
mysqli_close($conn);
ob_end_flush(); // End output buffering
?>


<div class="container mt-3 ">  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h3 class="text-uppercase text-center text-muted fw-bold my-3">Add Tenatnts</h3>
    <?php
    if(@$_GET['deleted'])
    {?>
    <div class="alert alert-danger" role="alert">
      Data Deleted Successfully!!!
    </div>
    <?php }?>

     <?php
    if(@$_GET['exists'])
    {?>
    <div class="alert alert-danger" role="alert">
      already exists. Skipping table creation.
    </div>
    <?php }?>

    <?php
    if(@$_GET['submitted'])
    {?>
    <div class="alert alert-success" role="alert">
      Data Inserted Successfully!!!
    </div>
    <?php }?>
   <!-- <form method="POST" action="add_designation.php"> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-floating mb-0 mt-3">
      <input type="text" class="form-control" id="table_name" placeholder="Enter Table Name" name="table_name" required="">
      <label for="table_name "> Table Name </label>
    </div>
    <div class="form-floating mt-3 mb-3">
      <input type="tenants_name" class="form-control" id="tenants_name" placeholder="Enter Tenants Name" name="tenants_name" required>
      <label for="tenants_name">Tenants Name</label>
    </div>
      <div class="form-floating mt-3 mb-3">
      <textarea name="address" id="address" class="form-control" required></textarea>
      <label for="pwd">Enter Address</label>
    </div>
    <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
  </form>
    <br>
    </div>
    <div class="col-sm-4">
      <a href="tenants_list.php" class="btn btn-outline-primary float-end mt-5 shadow-lg"><i class="fa fa-arrow-left"></i> Previous Page</a>
    </div>

  </div>
  <br>
 <!--  <div class="row">
    <div class="table-wrapper">
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Office</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php          
          $query=mysqli_query($conn,"select * from `office`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr >
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['office']; ?></td>
              <td>
                <a href="edit_office.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                <a href="delete_office.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>
  </div> -->
  </div>
</div>
<?php 
 include_once('footer.php');
?>


