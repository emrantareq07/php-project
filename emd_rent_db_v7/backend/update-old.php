<?php

include_once 'db/database.php';
 
require_once("backend/config.php");
//$conn = OpenCon();

//if (isset($_GET['id'])) {
   // $id = $_GET['id'];
     $id = '2';

    // Fetch the record with the specific ID
    $sql = "SELECT * FROM abbank_tbl WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the data
        $row = mysqli_fetch_assoc($result);

        // Assign variables to hold the form data
        $tenants_name = $row['tenants_name'];
        $month = $row['month'];
        $actual_hr = $row['actual_hr'];

        $hr_in = $row['hr_in'];

        //$hr_outs = $row['hr_outs']+$hr_in- $actual_hr;
        $hr_outs = $row['hr_outs'];

        $actual_wb = $row['actual_wb'];
        $wasa_in = $row['wasa_in'];

        //$wasa_outs = $row['wasa_outs']+$wasa_in -$actual_wb;
        $wasa_outs = $row['wasa_outs'];

        $actual_eb = $row['actual_eb'];
        $eb_in = $row['eb_in'];

       // $eb_outs = $row['eb_outs']+$eb_in-$actual_eb ;
        $eb_outs = $row['eb_outs'];

        $payorder_no = $row['payorder_no'];
        $date_payorder = $row['date_payorder'];
        $tax5 = $row['tax5'];
        $challan_no_tax = $row['challan_no_tax'];
        $date_tax = $row['date_tax'];
        $vat15 = $row['vat15'];
        $challa_no_vat = $row['challa_no_vat'];
        $date_vat = $row['date_vat'];

      $total_outs_db=$eb_outs+$hr_outs +$wasa_outs;
    } 
?>

<?php
// include_once 'db/database.php';
// require_once("backend/config.php");

// if (isset($_GET['id'])) {
//     $id = $_GET['id'];
//     // Fetch existing record
//     $sql = "SELECT * FROM abbank_tbl WHERE id = $id";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);
// }

// Update record
if (isset($_POST['submit'])) {

$month=$_POST['month']; 
$tenants_name=$_POST['tenants_name'];

$actual_hr=$_POST['actual_hr'];
$actual_eb=$_POST['actual_eb'];
$actual_wb=$_POST['actual_wb'];

$hr_in=$_POST['hr_in'];
$eb_in=$_POST['eb_in'];
$wasa_in=$_POST['wasa_in'];

$hr_outs= $_POST['hr_outs'];
$eb_outs=$_POST['eb_outs'];
$wasa_outs=$_POST['wasa_outs'];

$payorder_no=$_POST['payorder_no'];
$date_payorder=$_POST['date_payorder'];

$tax5=$_POST['tax5'];
$challan_no_tax=$_POST['challan_no_tax'];
$date_tax=$_POST['date_tax'];
$vat15=$_POST['vat15'];
$challa_no_vat =$_POST['challa_no_vat'];
$date_vat=$_POST['date_vat'];

$sql = "UPDATE abbank_tbl SET month='$month', tenants_name='$tenants_name', actual_hr='$actual_hr',
            actual_eb='$actual_eb', actual_wb='$actual_wb', payorder_no='$payorder_no', 
            date_payorder='$date_payorder', hr_in='$hr_in', eb_in='$eb_in', 
            wasa_in='$wasa_in', tax5='$tax5', challan_no_tax='$challan_no_tax', 
            date_tax='$date_tax', vat15='$vat15', challa_no_vat ='$challa_no_vat ', 
            date_vat='$date_vat', hr_outs='$hr_outs', eb_outs='$eb_outs', 
            wasa_outs='$wasa_outs' WHERE id=$id";

if (mysqli_query($conn, $sql)) {

// SQL query to select rows where id is between $id and the maximum id in the table
$sql2 = "SELECT * FROM abbank_tbl WHERE id BETWEEN $id AND (SELECT MAX(id) FROM abbank_tbl)";
$result2 = mysqli_query($conn, $sql2);

if (mysqli_num_rows($result2) > 1) {

    while ($row2 = mysqli_fetch_assoc($result2)) {
        // Get values from the current row
     $id2 = $row2['id'];

     $sql23 = "SELECT * FROM abbank_tbl WHERE id = $id2";
    $result23 = mysqli_query($conn, $sql23);
      $row23 = mysqli_fetch_assoc($result23) ;

        $hr_outs2 = $row23['hr_outs'];
       $wasa_outs2 = $row23['wasa_outs'];
        $eb_outs2 = $row23['eb_outs'];
       
        $sql3 = "SELECT * FROM abbank_tbl WHERE id > $id2 LIMIT 1";
        $result3 = mysqli_query($conn, $sql3);

        if (mysqli_num_rows($result3) > 0) {
           $row3 = mysqli_fetch_assoc($result3);
                
                $id3 = $row3['id'];
                $id9 = $row3['id'] ;

                // Calculate the new values for hr_outs, wasa_outs, and eb_outs
                $hr_outs = $hr_outs2 + $row3['actual_hr'] - $row3['hr_in'];
                $wasa_outs = $wasa_outs2 + $row3['actual_wb'] - $row3['wasa_in'];
                $eb_outs = $eb_outs2 + $row3['actual_eb'] - $row3['eb_in'];    

                // Update the next row with the new calculated values
                $sql32 = "UPDATE abbank_tbl SET hr_outs='$hr_outs', wasa_outs='$wasa_outs', 
                          eb_outs='$eb_outs' WHERE id=$id3";
                mysqli_query($conn, $sql32);            
        }
    }
}
 echo "<p class='text-success col-sm-4 p-2 bg-success text-white rounded'>Records updated successfully!!!</p>";
  
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Record</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container p-2 my-2 mt-2 ">
    <h3 class="text-center text-info text-bold text-uppercase"><?php echo  $tenants_name ?></h3>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST">
    <div class="container p-2 my-2 border rounded ">
        <div class="row ">
            <div class="col-sm-3 ">
                <label for="tenants_name">Tenants Name:</label>
                <select class="form-select" name="tenants_name" id="tenants_name" required>
                    <option value="ab_bank" <?php if ($row['tenants_name'] == 'ab_bank') echo 'selected'; ?>>AB Bank</option>
                    <option value="porto_traders" <?php if ($row['tenants_name'] == 'porto_traders') echo 'selected'; ?>>Proton Traders</option>
                </select>
            </div>
            <div class="col-sm-3 ">
                <label for="month">Month:</label>
                <select class="form-select" name="month" id="month" required>
                    <option selected disabled>Select Month</option>
                    <option value="January" <?php if ($row['month'] == 'January') echo 'selected'; ?>>January</option>
                    <option value="February" <?php if ($row['month'] == 'February') echo 'selected'; ?>>February</option>
                    <option value="March" <?php if ($row['month'] == 'March') echo 'selected'; ?>>March</option>
                    <option value="April" <?php if ($row['month'] == 'April') echo 'selected'; ?>>April</option>
                    <option value="May" <?php if ($row['month'] == 'May') echo 'selected'; ?>>May</option>
                    <option value="June" <?php if ($row['month'] == 'June') echo 'selected'; ?>>June</option>
                    <option value="July" <?php if ($row['month'] == 'July') echo 'selected'; ?>>July</option>
                    <option value="August" <?php if ($row['month'] == 'August') echo 'selected'; ?>>August</option>
                    <option value="September" <?php if ($row['month'] == 'September') echo 'selected'; ?>>September</option>
                    <option value="October" <?php if ($row['month'] == 'October') echo 'selected'; ?>>October</option>
                    <option value="November" <?php if ($row['month'] == 'November') echo 'selected'; ?>>November</option>
                    <option value="December" <?php if ($row['month'] == 'December') echo 'selected'; ?>>December</option>
                </select>
            </div>
                    <?php 
                    $hr_outs15 = $row['hr_outs']+$hr_in- $actual_hr; 
                    $hr_outs1= $actual_hr-$hr_in+$hr_outs15 ;

                    $wasa_outs15 = $row['wasa_outs']+$wasa_in- $actual_wb; 
                    $wasa_outs1= $actual_wb-$wasa_in+$wasa_outs15 ;

                    $eb_outs15 = $row['eb_outs']+$eb_in- $actual_eb; 
                    $eb_outs1= $actual_eb-$eb_in+$eb_outs15 ;
                    ?>

<table class="table">
    <thead>
        <tr>
            <th>Bill Type</th>
            <th>Actual Bill</th>
            <th>Received Bill</th>
            <th>Bill (Due)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>House Bill</th>
            <th><input type="text" class="form-control" id="actual_hr" onkeyup="calculateTotal(); calculateTotal_hr();" placeholder="Enter House Rent" name="actual_hr" value="<?php echo $row['actual_hr']; ?>" required></th>
            <th><input type="text" class="form-control" id="hr_in" onkeyup="calculateTotal(); calculateTotal_eb();" placeholder="Enter Electric Bill" name="hr_in" value="<?php echo $row['hr_in']; ?>" required></th>
            <th><input type="text" class="form-control" id="hr_outs" name="hr_outs" value="<?php echo $hr_outs1; ?>" readonly></th>
        </tr>
        <tr>
            <th>Water Bill</th>
            <th><input type="text" class="form-control" id="actual_wb" onkeyup="calculateTotal1(); calculateTotal_hr();" placeholder="Enter Water Bill" name="actual_wb" value="<?php echo $row['actual_wb']; ?>" required></th>
            <th><input type="text" class="form-control" id="wasa_in" onkeyup="calculateTotal1(); calculateTotal_eb();" placeholder="Enter Water Bill" name="wasa_in" value="<?php echo $row['wasa_in']; ?>" required></th>
            <th><input type="text" class="form-control" id="wasa_outs" name="wasa_outs" value="<?php echo $wasa_outs1; ?>" readonly></th>
        </tr>
        <tr>
            <th>Electric Bill</th>
            <th><input type="text" class="form-control" id="actual_eb" onkeyup="calculateTotal2(); calculateTotal_hr();" placeholder="Enter Electric Bill" name="actual_eb" value="<?php echo $row['actual_eb']; ?>" required></th>
            <th><input type="text" class="form-control" id="eb_in" onkeyup="calculateTotal2(); calculateTotal_eb();" placeholder="Enter Electric Bill" name="eb_in" value="<?php echo $row['eb_in']; ?>" required></th>
            <th><input type="text" class="form-control" id="eb_outs" name="eb_outs" value="<?php echo $eb_outs1; ?>" readonly></th>
        </tr>
        <tr>
            <th>Sub Total:</th>
            <th><input type="text" class="form-control" id="total_actual_hr" name="total_actual_hr" readonly></th>
            <th><input type="text" class="form-control" id="total_actual_eb" name="total_actual_eb" readonly></th>
            <th><input type="text" class="form-control" id="total_actual_outs" name="total_actual_outs" readonly></th>
        </tr>
    </tbody>
</table>

    <script>
        function calculateTotal_hr() {
            let actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;
            let actual_eb = parseFloat(document.getElementById('actual_eb').value) || 0;
            let actual_wb = parseFloat(document.getElementById('actual_wb').value) || 0;

            let total_hr = actual_hr + actual_eb + actual_wb;
            document.getElementById('total_actual_hr').value = isNaN(total_hr) ? '' : total_hr.toFixed(2);
        }

        function calculateTotal_eb() {
            let hr_in = parseFloat(document.getElementById('hr_in').value) || 0;
            let eb_in = parseFloat(document.getElementById('eb_in').value) || 0;
            let wasa_in = parseFloat(document.getElementById('wasa_in').value) || 0;

            let total_eb = hr_in + eb_in + wasa_in;
            document.getElementById('total_actual_eb').value = isNaN(total_eb) ? '' : total_eb.toFixed(2);
        }
        
        function calculateTotal_outs() {
            let hr_outs = parseFloat(document.getElementById('hr_outs').value) || 0;
            let eb_outs = parseFloat(document.getElementById('eb_outs').value) || 0;
            let wasa_outs = parseFloat(document.getElementById('wasa_outs').value) || 0;

            let total_actual_outs = hr_outs + eb_outs + wasa_outs;
            document.getElementById('total_actual_outs').value = isNaN(total_actual_outs) ? '' : total_actual_outs.toFixed(2);
        }

        function calculateTotal() {
            let actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;
            let hr_in = parseFloat(document.getElementById('hr_in').value) || 0;

             var hr_outs = parseFloat(<?php echo $hr_outs15; ?>) || 0;

            //let hr_in = parseFloat(document.getElementById('hr_in').value) || 0;

             hr_outs = actual_hr - hr_in+hr_outs;

            document.getElementById('hr_outs').value = hr_outs.toFixed(2);
            calculateTotal_outs(); // Trigger total calculation
        }

        function calculateTotal1() {
            let actual_wb = parseFloat(document.getElementById('actual_wb').value) || 0;
            let wasa_in = parseFloat(document.getElementById('wasa_in').value) || 0;
            var wasa_outs = parseFloat(<?php echo $wasa_outs15; ?>) || 0;
             wasa_outs = actual_wb - wasa_in+wasa_outs;

            document.getElementById('wasa_outs').value = wasa_outs.toFixed(2);
            calculateTotal_outs(); // Trigger total calculation
        }

        function calculateTotal2() {
            let actual_eb = parseFloat(document.getElementById('actual_eb').value) || 0;
            let eb_in = parseFloat(document.getElementById('eb_in').value) || 0;
            var eb_outs = parseFloat(<?php echo $eb_outs15; ?>) || 0;
           eb_outs = actual_eb - eb_in+eb_outs;

            document.getElementById('eb_outs').value = eb_outs.toFixed(2);
            calculateTotal_outs(); // Trigger total calculation
        }

        window.onload = function() {
            calculateTotal_hr();
            calculateTotal_eb();
            calculateTotal_outs();
        };
    </script>
            <div class="col-sm-3 ">
                <label for="payorder_no">Pay Order No:</label>
                <input type="text" class="form-control" name="payorder_no" id="payorder_no" value="<?php echo $row['payorder_no']; ?>" required>
            </div>
            <div class="col-sm-3 ">
                <label for="date_payorder">Date Pay Order:</label>
                <input type="date" class="form-control" name="date_payorder" id="date_payorder" value="<?php echo $row['date_payorder']; ?>" required>
            </div>
            <div class="col-sm-3 ">
                <label for="tax5">Tax 5%:</label>
                <input type="text" class="form-control" name="tax5" id="tax5" value="<?php echo $row['tax5']; ?>" required>
            </div>
            <div class="col-sm-3 ">
                <label for="challan_no_tax">Challan No (Tax):</label>
                <input type="text" class="form-control" name="challan_no_tax" id="challan_no_tax" value="<?php echo $row['challan_no_tax']; ?>" required>
            </div>
            <div class="col-sm-3 ">
                <label for="date_tax">Date (Tax):</label>
                <input type="date" class="form-control" name="date_tax" id="date_tax" value="<?php echo $row['date_tax']; ?>" required>
            </div>
            <div class="col-sm-3 ">
                <label for="vat15">VAT 15%:</label>
                <input type="text" class="form-control" name="vat15" id="vat15" value="<?php echo $row['vat15']; ?>" required>
            </div>
            <div class="col-sm-3 ">
                <label for="challa_no_vat ">Challan No (VAT):</label>
                <input type="text" class="form-control" name="challa_no_vat" id="challa_no_vat" value="<?php echo $row['challa_no_vat']; ?>" >
            </div>
            <div class="col-sm-3 ">
                <label for="date_vat">Date (VAT):</label>
                <input type="date" class="form-control" name="date_vat" id="date_vat" value="<?php echo $row['date_vat']; ?>" required>
            </div>
        </div>
    </div>
    <div class="text-center p-3">
        <input type="submit" name="submit" class="btn btn-info" value="Update Record">
        <a href="index.php" class="btn btn-danger">Cancel</a>
    </div>
</form>
</body>
</html>