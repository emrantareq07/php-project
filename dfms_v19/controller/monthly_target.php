<?php
session_name('dfms');
session_start();
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Include database connection
include('../db/db.php');

// Calculate fiscal year range
$date = date('Y-m-d');
$month11 = date('m', strtotime($date));
$year11 = date('Y', strtotime($date));

if ($month11 >= 7) {
    $year22 = $year11;
} else {
    $year22 = $year11 - 1;
}

$yearrange12 = "$year22-07-01";
$year22++;
$yearrange13 = "$year22-06-30";

// Handle record update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $target = $_POST['target'];

    $sql_update = "UPDATE monthly_target SET target = '$target' WHERE id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        header("Location: monthly_target.php?updated=successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Include header
include('../include/header.php');

if (isset($_POST['insert'])) {   
    $target = $_POST['target'];
    $product_produce = $_POST['product_produce'];
     $target_date = $_POST['target_date'];

     $month_id = date('Y-m', strtotime($target_date));

    $fiscalstart = $_POST['fiscalstart'];
    $fiscalend = $_POST['fiscalend'];
       $sql_check = "SELECT * FROM monthly_target 
              WHERE factory_name = '$table' 
              AND fiscalstart = '$fiscalstart' 
              AND product_produce = '$product_produce' 
              AND target_date LIKE '$month_id%' ";

    $result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) == 0) {    
    $sql_insert = "INSERT INTO monthly_target (factory_name, product_produce, fiscalstart, fiscalend, target,target_date) VALUES ('$table', '$product_produce', '$fiscalstart', '$fiscalend', '$target','$target_date')";

    if (mysqli_query($conn, $sql_insert)) {
        header("Location: monthly_target.php?inserted=successfully");
        exit();
    }
} else {
        header("Location: monthly_target.php?existed=successfully");
        exit();
        }
}

// Fetch all records for display
$sql_fetch = "SELECT * FROM monthly_target WHERE factory_name = '$table' order by id desc";
$result_fetch = mysqli_query($conn, $sql_fetch);
?>
<div class="container mt-5">
    <h1 class="text-center">DFMS <b>[--<?= $_SESSION['username']; ?>--]</b></h1>

<!--     <div class="row">
        <div class="col-sm-12">
            <?php //if (isset($_GET['updated'])) { ?>
                <div class="alert alert-success" role="alert">
                    <b>Yearly Target Updated Successfully!!!</b>
                </div>
            <?php //} //elseif (isset($_GET['inserted'])) { ?>
                <div class="alert alert-success" role="alert">
                    <b>Yearly Target Set Successfully!!!</b>
                </div>
            <?php //} ?>
        </div>
    </div> -->

<div class="row">
    <div class="col-sm-12">
        <?php if (isset($_GET['updated'])) { ?>
            <div class="alert alert-success text-center alert-dismissible fade show" role="alert" id="successMessage">
                <b>Yearly Target Updated Successfully!!!</b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } elseif (isset($_GET['inserted'])) { ?>
            <div class="alert alert-success text-center alert-dismissible fade show" role="alert" id="successMessage">
                <b>Yearly Target Set Successfully!!!</b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } elseif (isset($_GET['existed'])) { ?>
            <div class="alert alert-danger text-center alert-dismissible fade show" role="alert" id="successMessage">
                <b>Record Already Exists!!!</b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    // Automatically hide the alert message after 5 seconds
    setTimeout(function () {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.remove(); // Remove the success message
        }
    }, 300); // 5000ms = 5 seconds
</script>
    <!-- Form for Insert/Update -->
    <div class="row">
        <div class="col-sm-4 "></div>
        <div class="col-sm-4 border border-success rounded shadow pt-2 my-2">
            <form action="" method="POST" id="targetForm">
                <input type="hidden" name="id" id="recordId" value="">
                <div class="form-group">
                    <label for="product_produce" class="form-check-label">Product</label>
                    <select class="form-control" name="product_produce" id="product_produce">
                        <?php
                        if (in_array($table, ["sfcl", "jfcl", "afccl", "gpfplc", "cufl"])) {
                            echo '<option value="Urea">Urea</option>';
                        } elseif ($table == "tspcl") {
                            echo '<option value="TSP">TSP</option>';
                        } elseif ($table == "daplcl") {
                            echo '<option value="DAP">DAP</option>';
                        } elseif ($table == "kpml") {
                            echo '<option value="Paper">Paper</option>';
                        } elseif ($table == "cccl") {
                            echo '<option value="Cement">Cement</option>';
                        } elseif ($table == "ugsf") {
                            echo '<option value="Sheet Glass">Sheet Glass</option>';
                        } else {
                            echo '<option value="sanitary">Sanitary</option>';
                            echo '<option value="insulator">Insulator</option>';
                            echo '<option value="refractories">Refractories</option>';
                        }
                        ?>
                    </select>
                </div>

                  <div class="form-group">
                    <label for="target_date" class="form-check-label"><b>Set Month and date  :</b></label>
                    <input type="date" class="form-control" placeholder="Enter Production Target (MT)" name="target_date" id="target_date" required>
                </div>

                <div class="form-group">
                    <label for="target" class="form-check-label"><b>Set Production Target (MT):</b></label>
                    <input type="text" class="form-control" placeholder="Enter Production Target (MT)" name="target" id="target" required>
                </div>

                <div class="col-sm-12">
                    <div class="row">
                        <label for="fiscalstart" class="form-check-label"><b>Fiscal Year Range:</b></label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="fiscalstart" class="form-check-label">Start</label>
                                <input type="date" class="form-control" name="fiscalstart" id="fiscalstart" value="<?= $yearrange12; ?>" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="fiscalend" class="form-check-label">End</label>
                                <input type="date" class="form-control" name="fiscalend" id="fiscalend" value="<?= $yearrange13; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="form-group">
                    <button type="submit" name="insert" id="formSubmitBtn" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Insert</button>
                     <a class="btn btn-primary" id="login-btn" href="urea_form.php"><i class="fa fa-arrow-left"></i> Back</a> 
                </div><br>
            </form>
        </div>
    </div>

    <!-- Table Display -->
    <div class="row">
        <div class="col-sm-12">
            <h3 class="text-center">Yearly Targets</h3>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                     <th>ID</th>
                    <th>Product</th>
                    <th>Target (MT)</th>
                    <th>Target Set Date</th>
                    <th>Fiscal Start</th>
                    <th>Fiscal End</th>
            <?php
                if ($_SESSION['user_type'] == 'admin') {?>
                  <th>Action</th>
                     <?php
                }
                ?>
                </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_fetch)) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['product_produce']; ?></td>
                        <td><?= $row['target']; ?></td>
                        <td><?= $row['target_date']; ?></td>
                        <td><?= $row['fiscalstart']; ?></td>
                        <td><?= $row['fiscalend']; ?></td> 
                    <?php
                    if ($_SESSION['user_type'] == 'admin') {?>
                    <td>
                        <button type="button" class="btn btn-success btn-sm editBtn" 
                            data-id="<?= $row['id']; ?>" 
                            data-product="<?= $row['product_produce']; ?>" 
                            data-target="<?= $row['target']; ?>"
                            data-date="<?= $row['target_date']; ?>"
                            data-fiscalstart="<?= $row['fiscalstart']; ?>"
                            data-fiscalend="<?= $row['fiscalend']; ?>">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                    </td>
                    <?php
                    }
                    ?>                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Handle Edit Button Click
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const product = this.dataset.product;
            const target = this.dataset.target;
             const date = this.dataset.date;

            const fiscalstart = this.dataset.fiscalstart;
            const fiscalend = this.dataset.fiscalend;

            // Populate form fields
            document.getElementById('recordId').value = id;
            document.getElementById('product_produce').value = product;
            document.getElementById('target').value = target;
            document.getElementById('target_date').value = date;

            document.getElementById('fiscalstart').value = fiscalstart;
            document.getElementById('fiscalend').value = fiscalend;

            // Make product_produce field readonly
            document.getElementById('product_produce').setAttribute('disabled', true); // Use 'disabled' instead of 'readonly' for <select>

            // Change form button text
            document.getElementById('formSubmitBtn').innerText = "Update";
            document.getElementById('formSubmitBtn').setAttribute("name", "update");
        });
    });

    // Reset form to default state
    document.getElementById('targetForm').addEventListener('reset', function () {
        // Remove readonly/disabled attribute for product_produce
        document.getElementById('product_produce').removeAttribute('disabled'); // Use 'disabled' instead of 'readonly' for <select>

        // Reset button text to Insert
        document.getElementById('formSubmitBtn').innerText = "Insert";
        document.getElementById('formSubmitBtn').setAttribute("name", "insert");
    });
</script>
<?php include('../include/footer.php'); ?>
