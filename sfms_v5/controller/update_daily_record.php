<?php
session_start();
error_reporting(0);
require_once("../db/db.php");

$table = $_SESSION['username'];
$date = $_SESSION['date'];

$sql1 = "SELECT * FROM $table WHERE date ='$date'";
$query_run1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($query_run1);

$total_stock_db = $row['total_stock']; // 50 
$id_db = $row['id'];
$receive_import_db = $row['receive_import'];
$receive_factory_db = $row['receive_factory'];
$pipeline_db = $row['pipeline'];
$concat_receive_db = $row['concat_receive'];

$temp_total_stock = $total_stock_db - ($receive_import_db + $receive_factory_db);
$temp_pipeline = $pipeline_db + ($receive_import_db + $receive_factory_db);

$data = $row['concat_receive'];
$array = explode('+', $data);

$delivery = $row['delivery'];
$concat_delivery = $row['concat_delivery'];
$array_delivery = explode('+', $concat_delivery);

if (isset($_POST['update_delivery'])) {
    $submitted_values = [];
    $concatenated_values = '';
    $sum = 0;

    foreach ($_POST as $key => $value) {
        if ($key !== 'update_delivery') { 
            $sanitized_value = htmlspecialchars($value); 
            $submitted_values[] = $sanitized_value;
            $concatenated_values .= $sanitized_value . '+';
        }
    }

    $concatenated_values = rtrim($concatenated_values, '+');

    foreach ($submitted_values as $submitted_value) {
        $sum += $submitted_value;
    }

    $temp_stock = $total_stock_db + $delivery;
    $total_stock = $temp_stock - $sum; 
    $concatenated_values .= '+';

    $query = "UPDATE $table SET delivery='$sum', total_stock='$total_stock', concat_delivery='$concatenated_values' WHERE id='$id_db'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['msg'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
        header("Location: update_daily_record.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $submitted_values = [];
    $concatenated_values = '';
    $sumevenposition = 0;
    $sumoddposition = 0;
    $sum = 0;

    foreach ($_POST as $key => $value) {
        if ($key !== 'update') { 
            $sanitized_value = htmlspecialchars($value); 
            $submitted_values[] = $sanitized_value;
            $concatenated_values .= $sanitized_value . '+';
        }
    }

    $concatenated_values = rtrim($concatenated_values, '+');

    foreach ($submitted_values as $index => $submitted_value) {
        $sum += $submitted_value;
        if ($index % 2 == 0) {
            $sumevenposition += (int)$submitted_value;
        } else {
            $sumoddposition += (int)$submitted_value;
        }
    }

    $total_stock = $temp_total_stock + $sumevenposition + $sumoddposition;
    $pipeline = $temp_pipeline - ($sumevenposition + $sumoddposition);

    $concatenated_values .= '+';

    $query = "UPDATE $table SET receive_import='$sumevenposition', receive_factory='$sumoddposition', total_stock='$total_stock', pipeline='$pipeline', concat_receive='$concatenated_values' WHERE id='$id_db'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['msg'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
        header("Location: update_daily_record.php");
        exit(0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid shadow border m-1 rounded my-2">
    <div class="container-fluid">
        <div class="clearfix">
        <?php if(isset($_SESSION['msg'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['msg']; ?>
        </div>
        <?php unset($_SESSION['msg']); // Clear the message ?>
        <?php } ?>
        <div class="row mb-2">
            <div class="col-5">
                <h3 class="text-uppercase">Update Daily Receive Data</h3>
                <div class="row mb-2">
                    <div class="col">
                        <input class="form-control" type="text" placeholder="Input Serial No." readonly>
                    </div>
                    <div class="col">
                        <input class="form-control" type="text" placeholder="From Port (MT)" readonly>
                    </div>
                    <div class="col">
                        <input class="form-control" type="text" placeholder="From Factory (MT)" readonly>
                    </div>
                </div>
                <form action="" method="post">
                    <?php for ($i = 0; $i < count($array) - 1; $i++) { ?>
                    <div class="row mb-2">
                        <div class="col">
                            <input class="form-control" type="text" name="" value="Input <?php echo $i; ?>" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control" type="text" name="value<?php echo $i; ?>" value="<?php echo $array[$i]; ?>">
                        </div>
                        <?php if ($i + 1 < count($array) - 1) { ?>
                        <div class="col">
                            <input class="form-control" type="text" name="value<?php echo $i + 1; ?>" value="<?php echo $array[$i + 1]; ?>">
                        </div>
                        <?php $i++; ?>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <button type="submit" name="update" class="btn btn-primary">Submit</button>
                </form>
            </div>

<!-- 2nd col -->    
            <div class="col-5">
                <h3 class="text-uppercase">Update Delivery Data</h3>
                <div class="row mb-2">
                    <div class="col">
                        <input class="form-control" type="text" placeholder="Input Serial No." readonly>
                    </div>
                    <div class="col">
                        <input class="form-control" type="text" placeholder="Delivery Amount (MT)" readonly>
                    </div>
                </div>
                <form action="" method="post">
                    <?php for ($i = 0; $i < count($array_delivery) - 1; $i++) { ?>
                    <div class="row mb-2">
                        <div class="col">
                            <input class="form-control" type="text" name="" value="Input <?php echo $i; ?>" readonly>
                        </div>
                        <div class="col">
                            <input class="form-control" type="text" name="value<?php echo $i; ?>" value="<?php echo $array_delivery[$i]; ?>">
                        </div>
                    </div>
                    <?php } ?>
                    <button type="submit" name="update_delivery" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <!-- 3rd col -->
            <div class="col-2 pt-5">
                <div class="clearfix">
                   <div class="form-group">
                    <span class="">
                        <a href="urea_form.php" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Previous</a>
                        <a class="btn btn-danger btn-sm" href="logout.php">Logout <i class="fa fa-sign-out"></i></a>
                    </span>
                </div>
                </div>

               
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
