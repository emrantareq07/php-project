<?php
include_once 'db/database.php';
require_once("backend/config.php");

if(isset($_POST['submit'])){
    $month = $_POST['month']; 
    $tenants_name = $_POST['tenants_name'];
    $actual_hr = $_POST['actual_hr'];
    $actual_eb = $_POST['actual_eb'];
    $actual_wb = $_POST['actual_wb'];
    $payorder_no = $_POST['payorder_no'];
    $date_payorder = $_POST['date_payorder'];
    $hr_in = $_POST['hr_in'];
    $eb_in = $_POST['eb_in'];
    $wasa_in = $_POST['wasa_in'];
    $hr_outs = $_POST['hr_outs'];
    $eb_outs = $_POST['eb_outs'];
    $wasa_outs = $_POST['wasa_outs'];
    $tax5 = $_POST['tax5'];
    $challan_no_tax = isset($_POST['challan_no_tax']) ? $_POST['challan_no_tax'] : '';
    $date_tax = $_POST['date_tax'];
    $vat15 = $_POST['vat15'];
    $challa_no_vat = $_POST['challa_no_vat'];
    $date_vat = $_POST['date_vat'];

    $sql = "INSERT INTO abbank_tbl (month, tenants_name, actual_hr, actual_eb, actual_wb, payorder_no, date_payorder, hr_in, eb_in, wasa_in, tax5, challan_no_tax, date_tax, vat15, challa_no_vat, date_vat, hr_outs, eb_outs, wasa_outs) 
            VALUES ('$month', '$tenants_name', '$actual_hr', '$actual_eb', '$actual_wb', '$payorder_no', '$date_payorder', '$hr_in', '$eb_in', '$wasa_in', '$tax5', '$challan_no_tax', '$date_tax', '$vat15', '$challa_no_vat', '$date_vat', '$hr_outs', '$eb_outs', '$wasa_outs')";

    if (mysqli_query($conn, $sql)) {
        echo "<p class='text-success col-sm-4 p-2 bg-success text-white rounded'>Records inserted successfully!</p>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>AB Bank Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
</head>
<body>
  <div class="container p-2 my-2 mt-2 ">
    
  </div>

  <div class="container mt-3">
    <div class="card">
      <div class="card-header text-end"> <!-- Right-aligned header -->
        <h3 class="text-center text-info text-bold text-uppercase"><?php echo  $tenants_name ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
          <div class="row">
            <!-- Tenants Name Field -->
            <div class="col-sm-3 mb-3">
              <label for="tenants_name">Tenants Name:</label>
              <select class="form-select" id="tenants_name" name="tenants_name" required>
                <option value="" disabled selected>Select Tenants</option>
                <option value="ab_bank">AB Bank</option>
                <option value="porto_traders">Proton Traders</option>
            </select>
            </div>

            <!-- Month Field -->
            <div class="col-sm-3 mb-3">
              <label for="month">Month:</label>
              <select class="form-select" name="month" id="month" required>
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
              </select>
            </div>
          </div>

        <?php 
        $hr_outs_db;    
        $eb_outs_db;    
        $wasa_outs_db;
        $sql="SELECT * FROM abbank_tbl ORDER BY id DESC LIMIT 1";           
          $result=mysqli_query($conn, $sql);
          if(mysqli_num_rows($result)==0){
            $hr_outs_db='0';    
            $eb_outs_db='0';    
            $wasa_outs_db='0';
            $total_outs_db=$hr_outs_db + $eb_outs_db + $wasa_outs_db;
          }
          else{   
          while($row = mysqli_fetch_assoc($result)){
              $hr_outs_db=$row['hr_outs'];    
              $eb_outs_db=$row['eb_outs'];    
              $wasa_outs_db=$row['wasa_outs'];
              $total_outs_db=$hr_outs_db + $eb_outs_db + $wasa_outs_db;            
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
              </tr>
            </thead>
            <tbody>
              <tr>          
            <th>House Bill</th>
            <th> <input type="text" class="form-control" id="actual_hr" onkeyup="calculateTotal_hr();calculateTotal_total();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter House Rent" name="actual_hr" required></th> 

           <th><input type="text" class="form-control" id="hr_in" onkeyup="calculateTotal_eb();calculateTotal_total();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Electric Bill" name="hr_in" ></th>

            <th><input type="text" class="form-control" id="hr_outs"   value="<?php echo $hr_outs_db ?>"  name="hr_outs" readonly></th>
          </tr>
           <tr>
            <th>Water Bill</th>   
            <th> <input type="text" class="form-control" id="actual_wb" onkeyup="calculateTotal_hr();calculateTotal_total1();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Water Bill" name="actual_wb" required></th> 

           <th><input type="text" class="form-control" id="wasa_in" onkeyup="calculateTotal_eb();calculateTotal_total1();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Water Bill" name="wasa_in" ></th>
            <th><input type="text" class="form-control" id="wasa_outs"     name="wasa_outs" value="<?php echo $wasa_outs_db ?>" readonly></th>
          </tr>
           <tr>
            <th>Electric Bill</th>   
            <th> <input type="text" class="form-control" id="actual_eb" onkeyup="calculateTotal_hr();calculateTotal_total2();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Electric Bill" name="actual_eb" required></th> 

           <th><input type="text" class="form-control" id="eb_in" onkeyup="calculateTotal_eb();calculateTotal_total2();" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Enter Electric Bill" name="eb_in" ></th>

            <th><input type="text" class="form-control" id="eb_outs"   name="eb_outs" value="<?php echo $eb_outs_db ?>" readonly></th>
          </tr>
          <tr>
            <th>Sub Total : </th>   
            <th> <input type="text" class="form-control" id="total_actual_hr" name="total_actual_hr" readonly></th> 
           <th><input type="text" class="form-control" id="total_actual_eb" name="total_actual_eb" readonly></th>
            <th><input type="text" class="form-control" id="total_outs" name="total_outs"   readonly></th>
          </tr>
            </tbody>
          </table>

          <!-- Pay Order Information -->
          <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="payorder_no">Pay Order No.</label>
              <input type="text" class="form-control" id="payorder_no" name="payorder_no">
            </div>
            <div class="col-sm-3 mb-3">
              <label for="date_payorder">Pay Order Date</label>
              <input type="date" class="form-control" id="date_payorder" name="date_payorder">
            </div>
          </div>

          <!-- Tax Information -->
          <div class="row">
            <div class="col-sm-3 mb-3">
              <label for="tax5">Tax (5%)</label>
              <input type="text" class="form-control" id="tax5" name="tax5">
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
              <input type="text" class="form-control" id="vat15" name="vat15">
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
              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-footer text-end"> <!-- Right-aligned footer -->
        Footer
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
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

  function calculateTotal_outs() {
    var hr_outs = parseFloat(document.getElementById('hr_outs').value) || 0;
    var wasa_outs = parseFloat(document.getElementById('wasa_outs').value) || 0;
    var eb_outs = parseFloat(document.getElementById('eb_outs').value) || 0;
    var total_outs = hr_outs + wasa_outs + eb_outs;
    document.getElementById('total_outs').value = total_outs.toFixed(2);
  }
</script>
      
<script>
  function calculateTotal_hr() {
    // Get the values of the three input fields
    const hr = parseFloat(document.getElementById('actual_hr').value) || 0;
    const wb = parseFloat(document.getElementById('actual_wb').value) || 0;
    const eb = parseFloat(document.getElementById('actual_eb').value) || 0;
    
    // Calculate the sum
    const total = hr + wb + eb;    
    // Display the total in the readonly input field
    document.getElementById('total_actual_hr').value = total;   
                
  }
  function calculateTotal_eb() {
    // Get the values of the three input fields
    const hr = parseFloat(document.getElementById('hr_in').value) || 0;
    const wb = parseFloat(document.getElementById('eb_in').value) || 0;
    const eb = parseFloat(document.getElementById('wasa_in').value) || 0;
    
    // Calculate the sum
    const total = hr + wb + eb;    
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