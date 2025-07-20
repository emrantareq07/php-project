<?php
session_name('emd_rent_db');
session_start();
include_once '../db/database.php';
include_once 'header.php';
//include_once 'bill_form_navbar.php';
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
// $valid_tables = ['abbank_tbl', 'poton_tbl', 'mollik_tbl', 'dir_te', 'dir_pi', 'dir_pr'];
// if (!in_array($tenants_tbl, $valid_tables)) {
//     die("Invalid table name.");
// }
include_once 'table_list.php';
// Retrieve table_name and val from the URL query parameters
$tenants_tbl = isset($_GET['tenants_tbl']) ? urlencode($_GET['tenants_tbl']) : '';
$val = isset($_GET['val']) ? urlencode($_GET['val']) : ''; 

?>  

<div class="container mt-1 p-2 border rounded shadow-lg">  
  <div class="row"> 
    <div class="col-sm-12">
      <h2 class="text-muted text-center text-uppercase righteous-regular"><b>Rent Management System</b> </h2>
      <center><hr class="bg-muted col-sm-6 text-center"></center>
    </div> 
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        
      <!-- Form with corrected query parameters in action URL -->
      <form action="search_date_range.php?tenants_tbl=<?php echo urlencode($_GET['tenants_tbl'] ?? ''); ?>&val=<?php echo urlencode($_GET['val'] ?? ''); ?>" method="post" class="needs-validation">

        <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($_GET['tenants_tbl'] ?? ''); ?>" name="tenants_tbl">
        <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($_GET['val'] ?? ''); ?>" name="val">

        <div class="form-group row">
            <label for="date1" class="col-form-label col-sm-5">Start Date : </label>
            <div class="col-sm-7">
                <input type="date" class="form-control" id="date1" placeholder="From Date" name="date1" required>
            </div>
        </div> 
        <div class="form-group row mt-2">
            <label for="date2" class="col-form-label col-sm-5">End Date : </label>
            <div class="col-sm-7">
                <input type="date" class="form-control" id="date2" placeholder="To Date" name="date2" required>
            </div>
        </div>
        <center>
            <button type="submit" name="submit" class="btn btn-primary btn-block mb-2 mt-2 col-sm-6 offset-5">
                <i class="fa fa-search" style="font-size:16px"></i> Search
            </button>
        </center>
    </form>

    </div>
    <div class="col-sm-3 text-center">
       <a href="tenants_details.php?tenants_tbl=<?php echo $_SESSION['tenants_tbl']; ?>" class="btn btn-primary text-center"><i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>
        <hr>
      <button type="button" class="btn btn-danger" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
    </div>
  </div>
<!-- </div>
<div class="container mt-1 p-2 border rounded shadow-lg"> -->  
<!-- Printable Area -->
<div id="printableArea">
    <!-- Your table content goes here -->
    <table class="table table-bordered table-striped text-center">
        <thead class="table-primary">
            <tr>
          <th>#</th>
          <th>Month</th>
          <th>Actual Bill (HR)</th>
          <th>Actual Bill (WB)</th>
          <th>Actual Bill (EB)</th>
          <th>Receive Bill (HR)</th>
          <th>Receive Bill (WB)</th>
          <th>Receive Bill (EB)</th>
          <th>Due(HR)</th>
          <th>Due(WB)</th>
          <th>Due(EB)</th>   
               
            </tr>
        </thead>
        <tbody>
    <?php
    function englishToBanglaNumber($number) {
        $englishNumbers = range(0, 9);
        $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
    } 

    if (isset($_POST['submit'])) {
    $from_date = $_POST['date1'];
    $to_date = $_POST['date2'];
   
   //$division_bn = isset($_POST['division_bn']) ? $_POST['division_bn'] : ''; // Check if 'division_bn' exists

    $tenants_tbl = $_POST['tenants_tbl'];

    $val = $_POST['val'];    
    $base_query = "SELECT * FROM $tenants_tbl WHERE date BETWEEN '$from_date' AND '$to_date'";   

    $result1 = mysqli_query($conn, $base_query);    
    if (mysqli_num_rows($result1) > 0) {        
        while ($row = mysqli_fetch_assoc($result1)) {
        $date=$row["date"];
        $day = date("l", strtotime(str_replace('/', '-', $date)));
      ?>
      <tr id="<?php //echo $row["id"]; ?>" class=" text-center"> 
      <td><?php echo $row["id"]; ?></td>  
      <td style="font-size: 14px !important;"><?php echo $row["month"];?></td>       
      <td ><?php echo $row["actual_hr"];?></td>
      <td ><?php echo $row["actual_wb"];?></td>
      <td ><?php echo $row["actual_eb"];?></td>
      <td ><?php echo $row["hr_in"];?></td>
      <td ><?php echo $row["wasa_in"];?></td>
      <td ><?php echo $row["eb_in"];?></td>
      <td ><?php echo  $row["hr_outs"]; ?></td>
      <td ><?php echo $row["wasa_outs"]; ?></td>
      <td><?php echo $row["eb_outs"]; ?></td> 
      </tr>
        <?php        
           }
        }
   // } 
    else {
        // No records found message
        echo '<tr><td colspan="11" class="text-center text-danger">No records found for the selected date range.</td></tr>';
    }
}
mysqli_close($conn);
?>
        </tbody>
    </table>
</div>
</div>
<script>
// Print functionality
document.getElementById('print').addEventListener('click', function() {
    var printContents = document.getElementById('printableArea').innerHTML;
    var title = '<h1 class="text-center">Rent Management System</h1>';
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
        <head>
            <title>Meeting Schedule Records</title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @font-face {
                    font-family: 'Nikosh', Times, serif;
                    src: url(Nikosh.ttf);
                }

                .imgcontainer {
                    text-align: center;
                    margin: 5px 0 12px 0;
                    position: relative;
                }

                img.avatar {
                    width: 25%;
                    border-radius: 50%;
                }

                * {
                    font-family: 'Open Sans', sans-serif;
                    font-family: 'Tiro Bangla', serif;
                    font-family: 'Nikosh', sans-serif;
                }
            </style>
        </head>
        <body>
            ${title}
            ${printContents}
        </body>
        </html>
    `;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
});
</script>
<?php include('footer.php'); ?>
