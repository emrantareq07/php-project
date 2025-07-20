<?php 
session_name('emd_rent_db');
session_start();
include_once '../db/database.php';
include_once 'header.php';
require_once("config.php");
include_once 'update_navbar.php';
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
$tenants_tbl = $_SESSION['tenants_tbl'];
$id = $_GET['id'] ?? '';

$sql_option_match = "SELECT * FROM company WHERE table_name LIKE '%" . mysqli_real_escape_string($conn, $tenants_tbl) . "%'";
$result = mysqli_query($conn, $sql_option_match);

$rowoption_match = mysqli_fetch_assoc($result);

$tenants_name = $rowoption_match['tenants_name']; 
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure $id is an integer
    // Fetch the record with the specific ID
    $sql = "SELECT * FROM $tenants_tbl WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the data
        $row = mysqli_fetch_assoc($result);
        // Assign variables to hold the form data
        $tenants_name = $row['tenants_name'];

        $all_value = $row['all_value'];

        $month = $row['month'];
         $date = $row['date'];
        $actual_hr = $row['actual_hr'];
        $hr_in = $row['hr_in'];
        $hr_outs = $row['hr_outs'];

        $actual_wb = $row['actual_wb'];
        $wasa_in = $row['wasa_in'];
        $wasa_outs = $row['wasa_outs'];
        $actual_eb = $row['actual_eb'];
        $eb_in = $row['eb_in'];
        $eb_outs = $row['eb_outs'];
        // $payorder_no = $row['payorder_no'];
        // $date_payorder = $row['date_payorder'];
        $actual_gb = $row['actual_gb'];
        $gb_in = $row['gb_in'];
        $gb_outs = $row['gb_outs'];  

        $actual_cb = $row['actual_cb'];
        $cb_in = $row['cb_in'];
        $cb_outs = $row['cb_outs'];

        $tax5 = $row['tax5'];
        $challan_no_tax = $row['challan_no_tax'];
        $date_tax = $row['date_tax'];
        $vat15 = $row['vat15'];
        $challa_no_vat = $row['challa_no_vat'];
        $date_vat = $row['date_vat'];
        $total_outs_db = $eb_outs + $hr_outs + $wasa_outs;

     $payorder_no_hr = $row['payorder_no_hr'];
      $payorder_date_hr = $row['payorder_date_hr'];

    $payorder_no_wb = $row['payorder_no_wb'];
    $payorder_date_wb = $row['payorder_date_wb'];
    $payorder_no_eb = $row['payorder_no_eb'];
    $payorder_date_eb = $row['payorder_date_eb'];

    $payorder_no_gb = $row['payorder_no_gb'];
   $payorder_date_gb = $row['payorder_date_gb'];

   $payorder_no_cb = $row['payorder_no_cb'];
   $payorder_date_cb = $row['payorder_date_cb'];

    $payorder_no_comb = $row['payorder_no_comb'];
    $payorder_date_comb = $row['payorder_date_comb'];    
    }
}

// Update record when form is submitted
if (isset($_POST['submit'])) {
    // Sanitize inputs
    $date = $_POST['date'];
    $month = $_POST['month'];
   $all_value = $_POST['all_value'];   

    $actual_hr = mysqli_real_escape_string($conn, $_POST['actual_hr']);
    $actual_eb = mysqli_real_escape_string($conn, $_POST['actual_eb']);
    $actual_wb = mysqli_real_escape_string($conn, $_POST['actual_wb']);

    $actual_gb = mysqli_real_escape_string($conn, $_POST['actual_gb']);
    $actual_cb = mysqli_real_escape_string($conn, $_POST['actual_cb']);

    $hr_in = mysqli_real_escape_string($conn, $_POST['hr_in']);
    $eb_in = mysqli_real_escape_string($conn, $_POST['eb_in']);
    $wasa_in = mysqli_real_escape_string($conn, $_POST['wasa_in']);

    $gb_in = mysqli_real_escape_string($conn, $_POST['gb_in']);
    $cb_in = mysqli_real_escape_string($conn, $_POST['cb_in']);


    $hr_outs = mysqli_real_escape_string($conn, $_POST['hr_outs']);
    $eb_outs = mysqli_real_escape_string($conn, $_POST['eb_outs']);
    $wasa_outs = mysqli_real_escape_string($conn, $_POST['wasa_outs']);

    $gb_outs = mysqli_real_escape_string($conn, $_POST['gb_outs']);
    $cb_outs = mysqli_real_escape_string($conn, $_POST['cb_outs']);

    $tax5 = mysqli_real_escape_string($conn, $_POST['tax5']);
    $challan_no_tax = mysqli_real_escape_string($conn, $_POST['challan_no_tax']);
    $date_tax = mysqli_real_escape_string($conn, $_POST['date_tax']);
    $vat15 = mysqli_real_escape_string($conn, $_POST['vat15']);
    $challa_no_vat = mysqli_real_escape_string($conn, $_POST['challa_no_vat']);
    $date_vat = mysqli_real_escape_string($conn, $_POST['date_vat']);

    $payorder_no_hr = mysqli_real_escape_string($conn,$_POST['payorder_no_hr']);
    $payorder_date_hr = mysqli_real_escape_string($conn,$_POST['payorder_date_hr']);

    $payorder_no_wb = mysqli_real_escape_string($conn,$_POST['payorder_no_wb']);
    $payorder_date_wb = mysqli_real_escape_string($conn,$_POST['payorder_date_wb']);
    $payorder_no_eb =mysqli_real_escape_string($conn, $_POST['payorder_no_eb']);
    $payorder_date_eb =mysqli_real_escape_string($conn, $_POST['payorder_date_eb']);

    $payorder_no_gb = mysqli_real_escape_string($conn, $_POST['payorder_no_gb']);
    $payorder_date_gb = mysqli_real_escape_string($conn, $_POST['payorder_date_gb']);

    $payorder_no_cb = mysqli_real_escape_string($conn, $_POST['payorder_no_cb']);
    $payorder_date_cb = mysqli_real_escape_string($conn, $_POST['payorder_date_cb']);

    $payorder_no_comb = mysqli_real_escape_string($conn,$_POST['payorder_no_comb']);
    $payorder_date_comb =mysqli_real_escape_string($conn, $_POST['payorder_date_comb']); 


$sql23 = "SELECT date, month FROM $tenants_tbl WHERE id=$id";
$result23 = mysqli_query($conn, $sql23);

$row56 = mysqli_fetch_assoc($result23);
        $datesave = $row56['date'];  
        $monthsave = $row56['month'];
$sql4 = "UPDATE $tenants_tbl 
         SET date='', month='' 
         WHERE id=$id";
mysqli_query($conn, $sql4);    

   $year_of_date = date('Y', strtotime($date)); // Get year of the date
   $formatted_date = substr($date, 0, 7);

    $check_query = "SELECT COUNT(*) as count FROM `$tenants_tbl` WHERE date LIKE '$formatted_date%'";

    $check_result = mysqli_query($conn, $check_query);
    $check_row = mysqli_fetch_assoc($check_result);

    if ($check_row['count'] ==1) {
    $sql5 = "UPDATE $tenants_tbl 
    SET date='$datesave',month='$monthsave'
    WHERE id=$id";

    mysqli_query($conn, $sql5); 
    echo "<p class='fw-bold text-dark col-sm-4 offset-4 p-2 my-3 bg-warning  rounded text-center'> Records Already Exist for this month and year!!!</p>";  

    // Meta tag to refresh the page after 5 seconds
    echo "<meta http-equiv='refresh' content='1;url=update.php?tenants_tbl=$tenants_tbl&id=$id'>";
    }
else{
    // Ensure $id is valid
    if ($id > 0) {
        // Update the tenant record
$sql = "UPDATE $tenants_tbl 
        SET date='$date',month='$month', tenants_name='$tenants_name',
            payorder_no_hr='$payorder_no_hr', payorder_date_hr='$payorder_date_hr',
            payorder_no_wb='$payorder_no_wb', payorder_date_wb='$payorder_date_wb',
            payorder_no_eb='$payorder_no_eb', payorder_date_eb='$payorder_date_eb',
            payorder_no_comb='$payorder_no_comb', payorder_date_comb='$payorder_date_comb',
            actual_hr='$actual_hr', actual_eb='$actual_eb', actual_wb='$actual_wb',
            hr_in='$hr_in', eb_in='$eb_in', 
            wasa_in='$wasa_in', tax5='$tax5', challan_no_tax='$challan_no_tax',
            date_tax='$date_tax', vat15='$vat15', challa_no_vat='$challa_no_vat', 
            date_vat='$date_vat', hr_outs='$hr_outs', eb_outs='$eb_outs', 
            wasa_outs='$wasa_outs',
            actual_gb='$actual_gb', actual_cb='$actual_cb',
            gb_in='$gb_in', cb_in='$cb_in',
            gb_outs='$gb_outs', cb_outs='$cb_outs',all_value='$all_value',
            payorder_no_gb='$payorder_no_gb', payorder_date_gb='$payorder_date_gb',
            payorder_no_cb='$payorder_no_cb', payorder_date_cb='$payorder_date_cb'
        WHERE id=$id";

        // Execute the update query
        if (mysqli_query($conn, $sql)) {
            // Fetch next rows and update their calculations
            $sql2 = "SELECT * FROM $tenants_tbl WHERE id BETWEEN $id AND (SELECT MAX(id) FROM $tenants_tbl)";
            $result2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($result2) > 1) {
                while ($row233 = mysqli_fetch_assoc($result2)) {
                    $id2 = intval($row233['id']);
                    $sql233 = "SELECT * FROM $tenants_tbl WHERE id=$id2";
                    $result233 = mysqli_query($conn, $sql233);
                    $row2 = mysqli_fetch_assoc($result233);

                    $hr_outs2 = $row2['hr_outs'];
                    $wasa_outs2 = $row2['wasa_outs'];
                    $eb_outs2 = $row2['eb_outs'];

                    $gb_outs2 = $row2['gb_outs'];
                    $cb_outs2 = $row2['cb_outs'];

                    // Fetch the next row
                    $sql3 = "SELECT * FROM $tenants_tbl WHERE id > $id2 LIMIT 1";
                    $result3 = mysqli_query($conn, $sql3);

                    if (mysqli_num_rows($result3) > 0) {
                        $row3 = mysqli_fetch_assoc($result3);
                        $id3 = intval($row3['id']);
                        echo "$id3";
                        // Calculate new values for the next row
                        $hr_outs = $hr_outs2 + $row3['actual_hr'] - $row3['hr_in'];
                        $wasa_outs = $wasa_outs2 + $row3['actual_wb'] - $row3['wasa_in'];
                        $eb_outs = $eb_outs2 + $row3['actual_eb'] - $row3['eb_in'];

                        $gb_outs = $gb_outs2 + $row3['actual_gb'] - $row3['gb_in'];
                        $cb_outs = $cb_outs2 + $row3['actual_cb'] - $row3['cb_in'];

                        // Update the next row
                      $sql32 = "UPDATE $tenants_tbl 
                      SET hr_outs='$hr_outs', wasa_outs='$wasa_outs', eb_outs='$eb_outs', 
                          gb_outs='$gb_outs', cb_outs='$cb_outs' 
                      WHERE id=$id3";

                        mysqli_query($conn, $sql32);
                    }
                }
            }
            // Success message
            echo "<p class='fw-bold text-dark col-sm-4 offset-4 p-2 my-3 bg-warning  rounded text-center'> Update successfully!!!</p>";  

            // Meta tag to refresh the page after 5 seconds
            echo "<meta http-equiv='refresh' content='1;url=update.php?tenants_tbl=$tenants_tbl&id=$id'>";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid ID provided.";
    }

}
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

  <div class="container">
    <div class="card shadow-lg">
<!--       <div class="card-header text-end"> 
        <h3 class="text-center text-secondary text-bold text-uppercase spicy-rice-regular">Tenants Name: <?php echo  $tenants_name ?></h3>
      </div> -->
      <div class="card-body">
        <!-- <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"> -->
            <!-- <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST"> -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?tenants_tbl=' . $_SESSION['tenants_tbl']. '&id=' . $id; ?>" method="POST">
          <div class="row">   
<!--               <div class="col-sm-3 mb-3">
              <label for="fiscal_year">Fiscal Year:</label>
              <select class="form-select" id="fiscal_year" name="fiscal_year" required>
                <option value="" disabled selected>Select Fiscal Year</option>
                <option value="2024-2025" disabled <?php if ($row['fiscal_year'] == '2024-2025') echo 'selected'; ?>>2024-2025</option>
                <option value="2025-2026" disabled <?php if ($row['fiscal_year'] == '2025-2026') echo 'selected'; ?>>2025-2026</option>
            </select>
            </div> -->

       <!--          <div class="col-sm-3 mb-3">
              <label for="date">Bill Issue Date</label>
              <input type="date" class="form-control" id="date" value="<?php echo $date; ?>"  name="date" required>
            </div> -->
            <!-- Month Field -->
            <!-- <div class="col-sm-3 mb-3">
              <label for="month">Month:</label> -->
  <!--             <select class="form-select" name="month" id="month" required>
                    <option selected disabled>Select Month</option>
                    <option value="January"  <?php if ($row['month'] == 'January') echo 'selected'; ?>>January</option>
                    <option value="February"  <?php if ($row['month'] == 'February') echo 'selected'; ?>>February</option>
                    <option value="March"  <?php if ($row['month'] == 'March') echo 'selected'; ?>>March</option>
                    <option value="April"  <?php if ($row['month'] == 'April') echo 'selected'; ?>>April</option>
                    <option value="May"  <?php if ($row['month'] == 'May') echo 'selected'; ?>>May</option>
                    <option value="June"  <?php if ($row['month'] == 'June') echo 'selected'; ?>>June</option>
                    <option value="July"  <?php if ($row['month'] == 'July') echo 'selected'; ?>>July</option>
                    <option value="August"  <?php if ($row['month'] == 'August') echo 'selected'; ?>>August</option>
                    <option value="September"  <?php if ($row['month'] == 'September') echo 'selected'; ?>>September</option>
                    <option value="October"  <?php if ($row['month'] == 'October') echo 'selected'; ?>>October</option>
                    <option value="November"  <?php if ($row['month'] == 'November') echo 'selected'; ?>>November</option>
                    <option value="December"  <?php if ($row['month'] == 'December') echo 'selected'; ?>>December</option>
                </select>
            </div>
          </div> -->

<div class="col-sm-3 mb-3">
    <label for="date">Bill Issue Date</label>
    <input type="date" class="form-control" id="date" value="<?php echo $date; ?>" name="date" required>
</div>

<div class="col-sm-3 mb-3">
    <label for="month">Month:</label>
    <input type="text" class="form-control" id="month" name="month" placeholder="Selected Month" readonly>
</div>

<script>
  // This function will update the month when the date is selected
  document.getElementById("date").addEventListener("change", function () {
    const dateValue = new Date(this.value);
    const month = dateValue.toLocaleString("default", { month: "long" });

    // Display the month in the readonly input field
    document.getElementById("month").value = month;
  });

  // Optional: If you want to pre-populate the month field when the page loads with an existing date
  document.addEventListener('DOMContentLoaded', function () {
    const dateValue = document.getElementById("date").value;
    if (dateValue) {
      const date = new Date(dateValue);
      const month = date.toLocaleString("default", { month: "long" });
      document.getElementById("month").value = month;
    }
  });
</script>
          <?php 
              $hr_outs15 = $row['hr_outs']+$hr_in- $actual_hr; 
              $hr_outs1= $actual_hr-$hr_in+$hr_outs15 ;

              $wasa_outs15 = $row['wasa_outs']+$wasa_in- $actual_wb; 
              $wasa_outs1= $actual_wb-$wasa_in+$wasa_outs15 ;

              $eb_outs15 = $row['eb_outs']+$eb_in- $actual_eb; 
              $eb_outs1= $actual_eb-$eb_in+$eb_outs15 ;

              $gb_outs15 = $row['gb_outs'] + $gb_in - $actual_gb; 
              $gb_outs1 = $actual_gb - $gb_in + $gb_outs15;
              $cb_outs15 = $row['cb_outs'] + $cb_in - $actual_cb; 
            $cb_outs1 = $actual_cb - $cb_in + $cb_outs15;
            ?>
          <!-- Table for Bills -->
          <table class="table">
            <thead>
              <tr>
                <th>Bill Type</th>
                <th>Actual Bill</th>
                <th>Received Bill</th>
                <th>Bill (Due)</th>
                 <th>Pay Order No.</th>
                <th>Pay Order Date</th>
              </tr>
            </thead>
            <tbody>
          <tr>
            <th>House Bill</th>
            <th><input type="text" class="form-control" id="actual_hr" onkeyup="calculateTotal(); calculateTotal_hr();calculateTotal_tax(); calculateTotal_vat();" placeholder="Enter House Rent" name="actual_hr" value="<?php echo $row['actual_hr']; ?>" required></th>
            <th><input type="text" class="form-control" id="hr_in" onkeyup="calculateTotal(); calculateTotal_eb();" placeholder="Enter Electric Bill" name="hr_in" value="<?php echo $row['hr_in']; ?>" required></th>
            <th><input type="text" class="form-control" id="hr_outs" name="hr_outs" value="<?php echo $hr_outs1; ?>" ></th>
                        <th><input type="text" class="form-control" id="payorder_no_hr"   value="<?php echo $payorder_no_hr; ?>"  name="payorder_no_hr" ></th>
            <th><input type="date" class="form-control" id="payorder_date_hr"   value="<?php echo $payorder_date_hr; ?>"  name="payorder_date_hr" ></th>
        </tr>
        <tr>
            <th>Water Bill</th>
            <th><input type="text" class="form-control" id="actual_wb" onkeyup="calculateTotal1(); calculateTotal_hr();" placeholder="Enter Water Bill" name="actual_wb" value="<?php echo $row['actual_wb']; ?>" required></th>
            <th><input type="text" class="form-control" id="wasa_in" onkeyup="calculateTotal1(); calculateTotal_eb();" placeholder="Enter Water Bill" name="wasa_in" value="<?php echo $row['wasa_in']; ?>" required></th>
            <th><input type="text" class="form-control" id="wasa_outs" name="wasa_outs" value="<?php echo $wasa_outs1; ?>" ></th>
                        <th><input type="text" class="form-control" id="payorder_no_wb"   value="<?php echo $payorder_no_wb; ?>"  name="payorder_no_wb" ></th>
            <th><input type="date" class="form-control" id="payorder_date_wb"   value="<?php echo $payorder_date_wb; ?>"  name="payorder_date_wb" ></th>
        </tr>

        <tr>
            <th>Electric Bill</th>
            <th><input type="text" class="form-control" id="actual_eb" onkeyup="calculateTotal2(); calculateTotal_hr();" placeholder="Enter Electric Bill" name="actual_eb" value="<?php echo $row['actual_eb']; ?>" required></th>
            <th><input type="text" class="form-control" id="eb_in" onkeyup="calculateTotal2(); calculateTotal_eb();" placeholder="Enter Electric Bill" name="eb_in" value="<?php echo $row['eb_in']; ?>" required></th>
            <th><input type="text" class="form-control" id="eb_outs" name="eb_outs" value="<?php echo $eb_outs1; ?>" ></th>

            <th><input type="text" class="form-control" id="payorder_no_eb"   value="<?php echo $payorder_no_eb; ?>"  name="payorder_no_eb" ></th>
            <th><input type="date" class="form-control" id="payorder_date_eb"   value="<?php echo $payorder_date_eb; ?>"  name="payorder_date_eb" ></th>
        </tr>
        <tr>

    <th>Gas Bill </th>
    <th><input type="text" class="form-control" id="actual_gb" onkeyup="calculateTotal3(); calculateTotal_hr();" placeholder="Enter Gas Bill" name="actual_gb" value="<?php echo $row['actual_gb']; ?>" required></th>
    <th><input type="text" class="form-control" id="gb_in" onkeyup="calculateTotal3(); calculateTotal_eb();" placeholder="Enter Gas Bill In" name="gb_in" value="<?php echo $row['gb_in']; ?>" required></th>
    <th><input type="text" class="form-control" id="gb_outs" name="gb_outs" value="<?php echo $gb_outs1; ?>" ></th>
    <th><input type="text" class="form-control" id="payorder_no_gb" value="<?php echo $payorder_no_gb; ?>" name="payorder_no_gb"></th>
    <th><input type="date" class="form-control" id="payorder_date_gb" value="<?php echo $payorder_date_gb; ?>" name="payorder_date_gb"></th>
</tr>

<tr>
    <th>Car Parking Bill </th>
    <th><input type="text" class="form-control" id="actual_cb" onkeyup="calculateTotal4(); calculateTotal_hr();" placeholder="Enter Parking Bill" name="actual_cb" value="<?php echo $row['actual_cb']; ?>" required></th>
    <th><input type="text" class="form-control" id="cb_in" onkeyup="calculateTotal4(); calculateTotal_eb();"  placeholder="Enter Parking Bill In" name="cb_in" value="<?php echo $row['cb_in']; ?>" required></th>
    <th><input type="text" class="form-control" id="cb_outs" name="cb_outs" value="<?php echo $cb_outs1; ?>" ></th>
    <th><input type="text" class="form-control" id="payorder_no_cb" value="<?php echo $payorder_no_cb; ?>" name="payorder_no_cb"></th>
    <th><input type="date" class="form-control" id="payorder_date_cb" value="<?php echo $payorder_date_cb; ?>" name="payorder_date_cb"></th>
</tr>
    <tr>
        <th>Sub Total:</th>
        <th><input type="text" class="form-control" id="total_actual_hr" name="total_actual_hr" readonly></th>
        <th><input type="text" class="form-control" id="total_actual_eb" name="total_actual_eb" readonly></th>
        <th><input type="text" class="form-control" id="total_actual_outs" name="total_actual_outs" readonly></th>
       <th><input type="text" class="form-control" id="payorder_no_comb" value="<?php echo $payorder_no_comb; ?>"  placeholder="Pay Order No. (Combined)" name="payorder_no_comb" ></th>
        <th><input type="date" class="form-control" id="payorder_date_comb" value="<?php echo $payorder_date_comb; ?>"  name="payorder_date_comb"></th>
    </tr>
        </tbody>
      </table>

          <div class="row">
    <div class="col-sm-12 mb-3">
<textarea name="all_value" id="all_value"><?php echo htmlspecialchars($all_value); ?></textarea>
    </div>
</div>

<!-- Load CKEditor script -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<!-- Suppress the specific warning -->
<script>
    CKEDITOR.on('instanceReady', function () {
        // Override the default notification system
        if (CKEDITOR.plugins.notification) {
            CKEDITOR.plugins.notification.prototype.show = function () {
                if (
                    this.message &&
                    this.message.includes('not secure') &&
                    this.message.includes('Consider upgrading')
                ) {
                    return; // Suppress the "not secure" message
                }
                // Call the original `show` method for other notifications
                CKEDITOR.plugins.notification.prototype.show.apply(this, arguments);
            };
        }
    });

    // Initialize CKEditor
    CKEDITOR.replace('all_value');
</script>

          <!-- Pay Order Information -->
 <!--          <div class="row">
            <div class="col-sm-3 mb-3">
                <label for="payorder_no">Pay Order No:</label>
                <input type="text" class="form-control" name="payorder_no" id="payorder_no" value="<?php echo $row['payorder_no']; ?>" >
            </div>
            <div class="col-sm-3 mb-3">
                <label for="date_payorder">Date Pay Order:</label>
                <input type="date" class="form-control" name="date_payorder" id="date_payorder" value="<?php echo $row['date_payorder']; ?>" >
            </div>
          </div> -->

          <!-- Tax Information -->
          <div class="row">
            <div class="col-sm-3 mb-3">
                <label for="tax5">Tax 5%:</label>
                <input type="text" class="form-control" name="tax5" id="tax5" value="<?php echo $row['tax5']; ?>" >
            </div>
            <div class="col-sm-3 mb-3">
                <label for="challan_no_tax">Challan No (Tax):</label>
                <input type="text" class="form-control" name="challan_no_tax" id="challan_no_tax" value="<?php echo $row['challan_no_tax']; ?>" >
            </div>
            <div class="col-sm-3 mb-3">
                <label for="date_tax">Date (Tax):</label>
                <input type="date" class="form-control" name="date_tax" id="date_tax" value="<?php echo $row['date_tax']; ?>" >
            </div>
          </div>

          <!-- VAT Information -->
          <div class="row">
            <div class="col-sm-3 mb-3">
                <label for="vat15">VAT 15%:</label>
                <input type="text" class="form-control" name="vat15" id="vat15" value="<?php echo $row['vat15']; ?>" >
            </div>
            <div class="col-sm-3 mb-3">
                <label for="challa_no_vat ">Challan No (VAT):</label>
                <input type="text" class="form-control" name="challa_no_vat" id="challa_no_vat" value="<?php echo $row['challa_no_vat']; ?>" >
            </div>
            <div class="col-sm-3 mb-3">
                <label for="date_vat">Date (VAT):</label>
                <input type="date" class="form-control" name="date_vat" id="date_vat" value="<?php echo $row['date_vat']; ?>" >
            </div>
          </div>
          <!-- Submit Button -->
          <div class="row">
          <div class="p-3 text-end">
                  <button type="submit" name="submit" class="btn btn-primary" value=""><i class="fa fa-save" ></i> Update</button>
                  <!-- <a href="../index.php" class="btn btn-danger">Cancel</a> -->
              </div>
          </div>
        </form>
      </div>
    <div class="card-footer text-end text-muted"> <!-- Right-aligned footer -->
        <b>[--Design & Developed by ICT Division, BCIC.--]</b>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
 function calculateTotal_tax() {
    var actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;   
    var tax = actual_hr * 0.05;

    // Extract fractional part by splitting at the decimal point
    var fractionalPart = tax - Math.floor(tax);
    
    // If the last digit after decimal is greater than or equal to 0.06, use ceiling, else floor
    if (fractionalPart >= 0.5) {
        tax = Math.ceil(tax);
    } else {
        tax = Math.floor(tax);
    }
    document.getElementById('tax5').value = tax.toFixed(2);
    // calculateTotal_outs();
}

    function calculateTotal_vat() {
    var actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;   
    var vat = actual_hr* 0.15;

     // Extract fractional part by splitting at the decimal point
    var fractionalPart = vat - Math.floor(vat);
    
    // If the last digit after decimal is greater than or equal to 0.06, use ceiling, else floor
    if (fractionalPart >= 0.5) {
        vat = Math.ceil(vat);
    } else {
        vat = Math.floor(vat);
    }
    document.getElementById('vat15').value = vat.toFixed(2);          
  }    

function calculateTotal_hr() {
    let actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;
    let actual_eb = parseFloat(document.getElementById('actual_eb').value) || 0;
    let actual_wb = parseFloat(document.getElementById('actual_wb').value) || 0;
    let actual_gb = parseFloat(document.getElementById('actual_gb').value) || 0;
    let actual_cb = parseFloat(document.getElementById('actual_cb').value) || 0;
    let total_hr = actual_hr + actual_eb + actual_wb + actual_gb + actual_cb;
    document.getElementById('total_actual_hr').value = isNaN(total_hr) ? '' : total_hr.toFixed(2);
}

function calculateTotal_eb() {
    let hr_in = parseFloat(document.getElementById('hr_in').value) || 0;
    let eb_in = parseFloat(document.getElementById('eb_in').value) || 0;
    let wasa_in = parseFloat(document.getElementById('wasa_in').value) || 0;
    let gb_in = parseFloat(document.getElementById('gb_in').value) || 0;
    let cb_in = parseFloat(document.getElementById('cb_in').value) || 0;
    let total_eb = hr_in + eb_in + wasa_in + gb_in + cb_in;
    document.getElementById('total_actual_eb').value = isNaN(total_eb) ? '' : total_eb.toFixed(2);
}        
function calculateTotal_outs() {
    let hr_outs = parseFloat(document.getElementById('hr_outs').value) || 0;
    let eb_outs = parseFloat(document.getElementById('eb_outs').value) || 0;
    let wasa_outs = parseFloat(document.getElementById('wasa_outs').value) || 0;
    let gb_outs = parseFloat(document.getElementById('gb_outs').value) || 0;
    let cb_outs = parseFloat(document.getElementById('cb_outs').value) || 0;
    let total_actual_outs = hr_outs + eb_outs + wasa_outs + gb_outs + cb_outs;
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

function calculateTotal3() {
    let actual_gb = parseFloat(document.getElementById('actual_gb').value) || 0;
    let gb_in = parseFloat(document.getElementById('gb_in').value) || 0;
    var gb_outs = parseFloat(<?php echo $gb_outs15; ?>) || 0;
    gb_outs = actual_gb - gb_in + gb_outs;
    document.getElementById('gb_outs').value = gb_outs.toFixed(2);
    calculateTotal_outs(); // Trigger total calculation
}

function calculateTotal4() {
    let actual_cb = parseFloat(document.getElementById('actual_cb').value) || 0;
    let cb_in = parseFloat(document.getElementById('cb_in').value) || 0;
    var cb_outs = parseFloat(<?php echo $cb_outs15; ?>) || 0;
    cb_outs = actual_cb - cb_in + cb_outs;

    document.getElementById('cb_outs').value = cb_outs.toFixed(2);
    calculateTotal_outs(); // Trigger total calculation
}
window.onload = function() {
    calculateTotal_hr();
    calculateTotal_eb();
    calculateTotal_outs();
};
</script> 
</body>
</html>
<?php
mysqli_close($conn);
//include('backend/footer.php');
?>