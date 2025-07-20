<?php
session_name('emd_rent_db');
// session_start();
include_once '../db/database.php';
include_once 'header.php';
include_once 'bill_form_navbar.php';
require_once("config.php");

// Check if the user is already logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username']; 
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];

$tenants_tbl = $_GET['tenants_tbl'] ?? ''; // Retrieve the tenants table from the URL
$_SESSION['tenants_tbl'] = $_GET['tenants_tbl'];
$tenants_tbl = $_SESSION['tenants_tbl'];

// Check if table name is valid to prevent SQL injection
$valid_tables = ['abbank_tbl', 'poton_tbl', 'mollik_tbl', 'dir_te', 'dir_pi', 'dir_pr'];
if (!in_array($tenants_tbl, $valid_tables)) {
    die("Invalid table name.");
}

$today_date = date("Y-m-d");
$current_month = date("F"); // e.g., October
$current_year = date("Y"); // e.g., 2024

// Mapping tenants table to tenants names
$tenants_name = '';
switch ($tenants_tbl) {
    case 'abbank_tbl':
        $tenants_name = 'AB Bank';
        break;
    case 'mollik_tbl':
        $tenants_name = 'Mollik Traders';
        break;
    case 'poton_tbl':
        $tenants_name = 'Poton Traders';
        break;
    default:
        die("Invalid tenants table.");
}

// Retrieve the latest record from the tenants table
$result = mysqli_query($conn, "SELECT date,month, year FROM `$tenants_tbl` ORDER BY date DESC LIMIT 1");
$row = mysqli_fetch_array($result);
$month_db = $row['month'] ?? null; // Assuming 'month' is a column in the tenants table
$year_db = $row['year'] ?? null;   // Assuming 'year' is a column in the tenants table

$latest_date = $row['date']?? null;;

// Calculate fiscal year based on the retrieved date
$month_of_date = date('n', strtotime($latest_date)); // Get numeric month of the date
$year_of_date = date('Y', strtotime($latest_date)); // Get year of the date

if ($month_of_date >= 7) {
    // If month is July (7) or later, fiscal year starts from the current year
    $fiscal_year = $year_of_date . '-' . ($year_of_date + 1);
} else {
    // If month is before July, fiscal year starts from the previous year
    $fiscal_year = ($year_of_date - 1) . '-' . $year_of_date;
}

// Handle form submission
if (isset($_POST['submit'])) {
    $month_input = $_POST['month'];
    $date = $_POST['date'];
    $actual_hr = $_POST['actual_hr'];
    $actual_eb = $_POST['actual_eb'];
    $actual_wb = $_POST['actual_wb'];
    $actual_gb = $_POST['actual_gb'];

    // $payorder_no = $_POST['payorder_no'];
    // $date_payorder = $_POST['date_payorder'];

    $hr_in = $_POST['hr_in'];
    $eb_in = $_POST['eb_in'];
    $wasa_in = $_POST['wasa_in'];
    $gb_in = $_POST['gb_in'];


    $hr_outs = $_POST['hr_outs'];
    $eb_outs = $_POST['eb_outs'];
    $wasa_outs = $_POST['wasa_outs'];
    $gb_outs = $_POST['gb_outs']; 

    $tax5 = $_POST['tax5'];
    $challan_no_tax = $_POST['challan_no_tax'];
    $date_tax = $_POST['date_tax'];
    $vat15 = $_POST['vat15'];
    $challa_no_vat = $_POST['challa_no_vat'];
    $date_vat = $_POST['date_vat'];

    $payorder_no_hr = $_POST['payorder_no_hr'];
    $payorder_date_hr = $_POST['payorder_date_hr'];

    $payorder_no_wb = $_POST['payorder_no_wb'];
    $payorder_date_wb = $_POST['payorder_date_wb'];
    $payorder_no_eb = $_POST['payorder_no_eb'];
    $payorder_date_eb = $_POST['payorder_date_eb'];
    $payorder_no_gb = $_POST['payorder_no_gb'];
    $payorder_date_gb = $_POST['payorder_date_gb'];

    $payorder_no_comb = $_POST['payorder_no_comb'];
    $payorder_date_comb = $_POST['payorder_date_comb'];          

    // Convert month names to numeric values for comparison
    $month_numeric_input = date('n', strtotime($month_input)); // Convert month name to a number (e.g., October -> 10)
    $month_numeric_current = date('n', strtotime($current_month)); // Current month as a number (e.g., October -> 10)
    $month_numeric_db = date('n', strtotime($month_db));

    // If previous month has no data then insert data successfully
    if ($year_db == $current_year) {
        // Prevent insertion of previous month or year
        if ($month_numeric_input < $month_numeric_db) {
            echo "<p class='text-danger col-sm-4 offset-4 p-2 my-3 fw-bold bg-danger text-white rounded text-center'>Cannot insert previous months for the current year!</p>";
        } else {
            // Check if record already exists for the same month and year
            // $check_query = "SELECT COUNT(*) as count FROM `$tenants_tbl` WHERE month = '$month_input' AND year = '$year_db'";
            $check_query = "SELECT COUNT(*) as count FROM `$tenants_tbl` WHERE month = '$month_input'";
            $check_result = mysqli_query($conn, $check_query);
            $check_row = mysqli_fetch_assoc($check_result);

            if ($check_row['count'] > 0) {
                echo "<p class='text-danger col-sm-4 offset-4 p-2 my-3 fw-bold bg-danger text-white rounded text-center'>Records Already Exist for this month and year!!!</p>";
            } else {
                // Insert the new record
                $sql = "INSERT INTO `$tenants_tbl` (date, month, year, fiscal_year, tenants_name, actual_hr, actual_eb, actual_wb, actual_gb,payorder_no_hr, payorder_date_hr,payorder_no_wb, payorder_date_wb, payorder_no_eb, payorder_date_eb,payorder_no_gb, payorder_date_gb, payorder_no_comb,payorder_date_comb,hr_in, eb_in, wasa_in,gb_in, tax5, challan_no_tax, date_tax, vat15, challa_no_vat, date_vat, hr_outs, eb_outs, wasa_outs,gb_outs) 
                        VALUES ('$date', '$month_input', '$year_db', '$fiscal_year', '$tenants_name', '$actual_hr', '$actual_eb', '$actual_wb','$actual_gb', '$payorder_no_hr', '$payorder_date_hr','$payorder_no_wb', '$payorder_date_wb','$payorder_no_eb', '$payorder_date_eb','$payorder_no_gb', '$payorder_date_gb','$payorder_no_comb', '$payorder_date_comb', '$hr_in', '$eb_in', '$wasa_in','$gb_in', '$tax5', '$challan_no_tax', '$date_tax', '$vat15', '$challa_no_vat', '$date_vat', '$hr_outs', '$eb_outs', '$wasa_outs', '$gb_outs')";

                if (mysqli_query($conn, $sql)) {
                    echo "<p class='text-success col-sm-4 offset-4 p-2 my-3 fw-bold bg-success text-white rounded text-center'>Records inserted successfully!!!</p>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }
    } elseif ($year_db < $current_year) {
        // If the year from the database is less than the current year, allow insertion for any month of the current year
        // $check_query = "SELECT COUNT(*) as count FROM `$tenants_tbl` WHERE month = '$month_input' AND year = '$current_year'";
        $check_query = "SELECT COUNT(*) as count FROM `$tenants_tbl` WHERE month = '$month_input'";
        $check_result = mysqli_query($conn, $check_query);
        $check_row = mysqli_fetch_assoc($check_result);

        if ($check_row['count'] > 0) {
            echo "<p class='text-danger col-sm-4 offset-4 p-2 my-3 fw-bold bg-danger text-white rounded text-center'>Records Already Exist for this month and year!!!</p>";
        } else {
            // Insert the new record
                $sql = "INSERT INTO `$tenants_tbl` (date, month, year, fiscal_year, tenants_name, actual_hr, actual_eb, actual_wb, actual_gb,payorder_no_hr, payorder_date_hr,payorder_no_wb, payorder_date_wb, payorder_no_eb, payorder_date_eb,payorder_no_gb, payorder_date_gb, payorder_no_comb,payorder_date_comb,hr_in, eb_in, wasa_in,gb_in, tax5, challan_no_tax, date_tax, vat15, challa_no_vat, date_vat, hr_outs, eb_outs, wasa_outs,gb_outs) 
                        VALUES ('$date', '$month_input', '$year_db', '$fiscal_year', '$tenants_name', '$actual_hr', '$actual_eb', '$actual_wb','$actual_gb', '$payorder_no_hr', '$payorder_date_hr','$payorder_no_wb', '$payorder_date_wb','$payorder_no_eb', '$payorder_date_eb','$payorder_no_gb', '$payorder_date_gb','$payorder_no_comb', '$payorder_date_comb', '$hr_in', '$eb_in', '$wasa_in','$gb_in', '$tax5', '$challan_no_tax', '$date_tax', '$vat15', '$challa_no_vat', '$date_vat', '$hr_outs', '$eb_outs', '$wasa_outs', '$gb_outs')";

            if (mysqli_query($conn, $sql)) {
                echo "<p class='text-success col-sm-4 offset-4 p-2 my-3 fw-bold bg-success text-white rounded text-center'>Records inserted successfully!!!</p>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        echo "<p class='text-danger col-sm-4 offset-4 p-2 my-3 fw-bold bg-danger text-white rounded text-center'>Cannot insert data for future months!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title> Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
</head>
<body>
  
  <div class="container ">
    <div class="card shadow-lg">
      <div class="card-header text-end"> <!-- Right-aligned header -->
        <h3 class="text-center text-secondary text-bold text-uppercase spicy-rice-regular">Tenants Name: <?php echo  $tenants_name; ?></h3>
        <!-- <h3 class="text-center text-info text-bold text-uppercase"><?php echo  $row['tenants_name']; ?></h3> -->
      </div>
      <div class="card-body">
        <!-- <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST"> -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?tenants_tbl=' . $_SESSION['tenants_tbl']; ?>" method="POST">


<div class="row">
  <div class="col-sm-3 mb-3">
    <label for="date">Bill Issue Date</label>
    <input type="date" class="form-control" id="date" name="date" required>
  </div>

  <div class="col-sm-3 mb-3">
    <label for="month">Month:</label>
    <input type="text" class="form-control" id="month" name="month" placeholder="Selected Month" readonly>
    <!-- <select class="form-select" name="month" id="month"  readonly>
      <option value="" selected disabled>Select Month</option>
      <option value="January">January</option>
      <option value="February">February</option>
      <option value="March">March</option>
      <option value="April">April</option>
      <option value="May">May</option>
      <option value="June">June</option>
      <option value="July">July</option>
      <option value="August">August</option>
      <option value="September">September</option>
      <option value="October">October</option>
      <option value="November">November</option>
      <option value="December">December</option>
    </select> -->
  </div>
</div>

<script>
  document.getElementById("date").addEventListener("change", function () {
    const dateValue = new Date(this.value);
    const month = dateValue.toLocaleString("default", { month: "long" });

    // Display the month in the readonly input field
    document.getElementById("month").value = month;
  });
</script>


        <?php 
        $hr_outs_db;    
        $eb_outs_db;    
        $wasa_outs_db;
        $gb_outs_db; 
        $sql="SELECT * FROM `$tenants_tbl` ORDER BY id DESC LIMIT 1";           
          $result=mysqli_query($conn, $sql);
          if(mysqli_num_rows($result)==0){
            $hr_outs_db='0';    
            $eb_outs_db='0';    
            $wasa_outs_db='0';
             $gb_outs_db='0';
            $total_outs_db=$hr_outs_db + $eb_outs_db + $wasa_outs_db+$gb_outs_db;
          }
          else{   
          while($row = mysqli_fetch_assoc($result)){
              $hr_outs_db=$row['hr_outs'];    
              $eb_outs_db=$row['eb_outs'];    
              $wasa_outs_db=$row['wasa_outs'];
              $gb_outs_db=$row['gb_outs'];
              $total_outs_db=$hr_outs_db + $eb_outs_db + $wasa_outs_db+$gb_outs_db;            
          }
          }
          //mysqli_close($conn);
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
            <th> <input type="text" class="form-control" id="actual_hr" onkeyup="calculateTotal_hr();calculateTotal_total(); calculateTotal_tax(); calculateTotal_vat();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter House Rent" name="actual_hr" required></th> 

           <th><input type="text" class="form-control" id="hr_in" onkeyup="calculateTotal_eb();calculateTotal_total();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Electric Bill" name="hr_in" ></th>

            <th><input type="text" class="form-control" id="hr_outs"   value="<?php echo $hr_outs_db ?>"  name="hr_outs" readonly></th>
            
            <th><input type="text" class="form-control" id="payorder_no_hr"   value=""  name="payorder_no_hr" ></th>
            <th><input type="date" class="form-control" id="payorder_date_hr"   value=""  name="payorder_date_hr" ></th>
          </tr>
           <tr>
            <th>Water Bill</th>   
            <th> <input type="text" class="form-control" id="actual_wb" onkeyup="calculateTotal_hr();calculateTotal_total1();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Water Bill" name="actual_wb" required></th> 

           <th><input type="text" class="form-control" id="wasa_in" onkeyup="calculateTotal_eb();calculateTotal_total1();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Water Bill" name="wasa_in" ></th>
            <th><input type="text" class="form-control" id="wasa_outs"     name="wasa_outs" value="<?php echo $wasa_outs_db ?>" readonly></th>
            <th><input type="text" class="form-control" id="payorder_no_wb"   value=""  name="payorder_no_wb" ></th>
            <th><input type="date" class="form-control" id="payorder_date_wb"   value=""  name="payorder_date_wb" ></th>
          </tr>


           <tr>
            <th>Electric Bill</th>   
            <th> <input type="text" class="form-control" id="actual_eb" onkeyup="calculateTotal_hr();calculateTotal_total2();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Electric Bill" name="actual_eb" required></th> 

           <th><input type="text" class="form-control" id="eb_in" onkeyup="calculateTotal_eb();calculateTotal_total2();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Electric Bill" name="eb_in" ></th>

            <th><input type="text" class="form-control" id="eb_outs"   name="eb_outs" value="<?php echo $eb_outs_db ?>" readonly></th>

            <th><input type="text" class="form-control" id="payorder_no_eb"   value=""  name="payorder_no_eb" ></th>
            <th><input type="date" class="form-control" id="payorder_date_eb"   value=""  name="payorder_date_eb" ></th>
          </tr>


                     <tr>
            <th>Gas Bill</th>   
            <th> <input type="text" class="form-control" id="actual_gb" onkeyup="calculateTotal_hr();calculateTotal_total3();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Gas Bill" name="actual_gb" required></th> 

           <th><input type="text" class="form-control" id="gb_in" onkeyup="calculateTotal_eb();calculateTotal_total3();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Gas Bill" name="gb_in" ></th>

            <th><input type="text" class="form-control" id="gb_outs"   name="gb_outs" value="<?php echo $gb_outs_db ?>" readonly></th>

            <th><input type="text" class="form-control" id="payorder_no_gb"   value=""  name="payorder_no_gb" ></th>
            <th><input type="date" class="form-control" id="payorder_date_gb"   value=""  name="payorder_date_gb" ></th>
          </tr>



          <tr>
            <th>Sub Total : </th>   
            <th> <input type="text" class="form-control" id="total_actual_hr" name="total_actual_hr" readonly></th> 
           <th><input type="text" class="form-control" id="total_actual_eb" name="total_actual_eb" readonly></th>
            <th><input type="text" class="form-control" id="total_outs" name="total_outs"   readonly></th>

            <th><input type="text" class="form-control" id="payorder_no_comb" placeholder="Pay Order No. (Combined)" name="payorder_no_comb" ></th>
            <th><input type="date" class="form-control" id="payorder_date_comb"  name="payorder_date_comb"></th>

          </tr>
            </tbody>
          </table>

          <!-- Pay Order Information -->
          <!-- <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="payorder_no">Pay Order No.</label>
              <input type="text" class="form-control" id="payorder_no" name="payorder_no">
            </div>
            <div class="col-sm-3 mb-3">
              <label for="date_payorder">Pay Order Date</label>
              <input type="date" class="form-control" id="date_payorder" name="date_payorder">
            </div>
          </div> -->

          <!-- Tax Information -->
          <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="tax5">Tax (5%)</label>
              <input type="text" class="form-control" id="tax5" name="tax5" readonly>
            </div>
            <div class="col-sm-3 mb-3">
              <label for="challan_no_tax">Challan No (Tax)</label>
              <input type="text" class="form-control" id="challan_no_tax" name="challan_no_tax">
            </div>
            <div class="col-sm-3 mb-3">
              <label for="date_tax">Challan Date (Tax)</label>
              <input type="date" class="form-control" id="date_tax" name="date_tax">
            </div>
          </div>

          <!-- VAT Information -->
          <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="vat15">VAT (15%)</label>
              <input type="text" class="form-control" id="vat15" name="vat15" readonly>
            </div>
            <div class="col-sm-3 mb-3">
              <label for="challa_no_vat">Challan No (VAT)</label>
              <input type="text" class="form-control" id="challa_no_vat" name="challa_no_vat">
            </div>
            <div class="col-sm-3 mb-3">
              <label for="date_vat">Challan Date (VAT)</label>
              <input type="date" class="form-control" id="date_vat" name="date_vat">
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row">
            <div class="col-sm-12 text-end"> <!-- Right-aligned submit button -->
              <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save" ></i> Submit</button>
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
  // function calculateTotal_tax() {
  //   var actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;   
  //   var tax = actual_hr*.05;
  //   document.getElementById('tax5').value = tax.toFixed(2);
      // calculateTotal_outs();    
  //}
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


  function calculateTotal_total() {
    var actual_hr = parseFloat(document.getElementById('actual_hr').value) || 0;
    var hr_in = parseFloat(document.getElementById('hr_in').value) || 0;
    var hr_outs = parseFloat(<?php echo $hr_outs_db; ?>) || 0;
    var total = actual_hr - hr_in + hr_outs;
    document.getElementById('hr_outs').value = total.toFixed(2);
      calculateTotal_outs();    
  }

  function calculateTotal_total1() {
    var actual_wb = parseFloat(document.getElementById('actual_wb').value) || 0;
    var wasa_in = parseFloat(document.getElementById('wasa_in').value) || 0;
    var wasa_outs = parseFloat(<?php echo $wasa_outs_db; ?>) || 0           
    var total = actual_wb - wasa_in + wasa_outs;
    document.getElementById('wasa_outs').value = total.toFixed(2);
      calculateTotal_outs();    
  }

  function calculateTotal_total2() {
    var actual_eb = parseFloat(document.getElementById('actual_eb').value) || 0;
    var eb_in = parseFloat(document.getElementById('eb_in').value) || 0;
    var eb_outs = parseFloat(<?php echo $eb_outs_db; ?>) || 0;
    var total = actual_eb - eb_in + eb_outs;
    document.getElementById('eb_outs').value = total.toFixed(2);
      calculateTotal_outs();    
  }

    function calculateTotal_total3() {
    var actual_gb = parseFloat(document.getElementById('actual_gb').value) || 0;
    var gb_in = parseFloat(document.getElementById('gb_in').value) || 0;
    var gb_outs = parseFloat(<?php echo $gb_outs_db; ?>) || 0;
    var total = actual_gb - gb_in + gb_outs;
    document.getElementById('gb_outs').value = total.toFixed(2);
      calculateTotal_outs();    
  }


  function calculateTotal_outs() {
    var hr_outs = parseFloat(document.getElementById('hr_outs').value) || 0;
    var wasa_outs = parseFloat(document.getElementById('wasa_outs').value) || 0;
    var eb_outs = parseFloat(document.getElementById('eb_outs').value) || 0;
    var gb_outs = parseFloat(document.getElementById('gb_outs').value) || 0;
    var total_outs = hr_outs + wasa_outs + eb_outs+gb_outs;
    document.getElementById('total_outs').value = total_outs.toFixed(2);
  }
</script>
      
<script>
  function calculateTotal_hr() {
    // Get the values of the three input fields
    const hr = parseFloat(document.getElementById('actual_hr').value) || 0;
    const wb = parseFloat(document.getElementById('actual_wb').value) || 0;
    const eb = parseFloat(document.getElementById('actual_eb').value) || 0;
    const gb = parseFloat(document.getElementById('actual_gb').value) || 0;
    
    // Calculate the sum
    const total = hr + wb + eb+gb;    
    // Display the total in the readonly input field
    document.getElementById('total_actual_hr').value = total;   
                
  }
  function calculateTotal_eb() {
    // Get the values of the three input fields
    const hr = parseFloat(document.getElementById('hr_in').value) || 0;
    const wb = parseFloat(document.getElementById('eb_in').value) || 0;
    const eb = parseFloat(document.getElementById('wasa_in').value) || 0;
     const gb = parseFloat(document.getElementById('gb_in').value) || 0;
    
    // Calculate the sum
    const total = hr + wb + eb+gb;    
    // Display the total in the readonly input field
    document.getElementById('total_actual_eb').value = total;       

  }
    // Trigger the calculation when the page finishes loading
    window.onload = function() {
        calculateTotal_outs();
    };
</script> 
</body>
</html>
<?php
mysqli_close($conn);
//include('backend/footer.php');
?>