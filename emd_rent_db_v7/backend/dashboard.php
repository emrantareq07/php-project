<?php 
session_name('emd_rent_db');
session_start();
$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// $_SESSION['tenants_tbl']=$_SESSION['tenants_tbl'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once '../db/database.php';
include_once 'header.php';
include_once 'navbar.php';
require_once("config.php");
?>

<div class="container-fluid">
  <div class="table-wrapper border rounded shadow p-2">
    <div class="table-title">
      <div class="row">
        <div class="col-sm-3">
         <!--  <h2 class="text-muted">DAILY <b>MEETING</b> SCHEDULE</h2>
          <span class="text-primary fw-bold"><small>[--<?php echo $office; ?>--]</small></span> -->
        </div>
        <div class="col-sm-9 text-end">
          <?php
          if($user_type=='sadmin'){   
            ?>
            <h4><a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-edit" style="font-size:15px;color:black"></i> Manage User </a>
            </h4>
              <h4>
  
            <a href="manage_user.php?username=<?=$_SESSION['username']?>" class="btn btn-warning"><i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database </a>
            </h4>
            <?php
             }
            ?>          
        </div>
      </div>
    </div> <hr> 

<!-- <div class="container mt-1 p-2 border rounded shadow-lg">   -->
  <div class="row"> 
    <!-- <div class="col-sm-12">
      <h2 class="text-muted text-center text-uppercase righteous-regular"><b>Rent Management System</b> </h2>
      <center><hr class="bg-muted col-sm-6 text-center"></center>
    </div>  -->
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        
      <!-- Form with corrected query parameters in action URL -->
      <!-- <form action="dashboard.php?tenants_tbl=<?php echo urlencode($_GET['tenants_tbl'] ?? ''); ?>&val=<?php echo urlencode($_GET['val'] ?? ''); ?>" method="post" class="needs-validation"> -->
      <form action="dashboard.php" method="post" class="needs-validation">

        <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($_GET['tenants_tbl'] ?? ''); ?>" name="tenants_tbl">
        <input type="hidden" class="form-control" value="<?php echo htmlspecialchars($_GET['val'] ?? ''); ?>" name="val">

        <div class="form-group row">
            <!-- Start Date -->
            <label for="date1" class="col-form-label col-sm-2">Start Date:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" id="date1" placeholder="From Date" name="date1" required>
            </div>

            <!-- End Date -->
            <label for="date2" class="col-form-label col-sm-2">End Date:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" id="date2" placeholder="To Date" name="date2" required>
            </div>

            <!-- Tenants List -->
            <label for="tenants_name" class="col-form-label col-sm-2">Tenants List:</label>
            <div class="col-sm-4 mt-2">
                <select class="form-select form-control" id="tenants_name" name="tenants_name" aria-label="Default select example" required>
                    <option selected disabled value="">--Select Tenants--</option>
                    <option value="all">ALL</option>    
                    <?php
                    // Fetching data from the database and populating the dropdown options
                    $sql_option = "SELECT * FROM company";
                    $result = mysqli_query($conn, $sql_option);

                    if ($result) {
                        while ($rowoption = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $rowoption['table_name'] . '">' . $rowoption['tenants_name'] . '</option>';
                        }
                    } 
                    ?>
                </select>
            </div>
            <button type="submit" name="submit"  class="btn btn-primary btn-block col-sm-4 mt-2 mb-2 float-end offset-1">
                <i class="fa fa-search" style="font-size:16px"></i> Search
            </button>            
        </div>
    </form>

    </div>
    <!-- <div class="col-sm-3 text-center">       
      <button type="button" class="btn btn-danger" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
    </div> -->
  </div>
<!-- </div>
<div class="container mt-1 p-2 border rounded shadow-lg"> -->  
<!-- Printable Area -->
<div id="printableArea">
    <!-- Your table content goes here -->
    <table class="table table-bordered table-striped text-center">
        <thead class="table-primary">
          <tr rowspan="2" class="table-dark">
            <th ></th>
            <th ></th>    
            <th colspan="5">Bill</th>
            <th colspan="5">Bill Received</th>
            <th colspan="5">Bill Due</th>
        </tr>
        <tr>
          <!-- <th>#</th> -->
          <th>Tenants Name</th>
          <th>Month</th>

          <th>HR</th>
          <th>WB</th>
          <th>EB</th>
          <th>GB</th>
          <th>CPB</th>

          <th>HR</th>
          <th>WB</th>
          <th>EB</th>
          <th>GB</th>
          <th>CPB</th>

          <th>HR</th>
          <th>WB</th>
          <th>EB</th>   
         <th>GB</th>
          <th>CPB</th> 
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

    $fromDate1 = new DateTime($from_date);
    $toDate1 = new DateTime($to_date);
    // if ($fromDate1->format('Y-m') === $toDate1->format('Y-m')) 
    // {
    // }
    $tenants_name = isset($_POST['tenants_name']) ? $_POST['tenants_name'] : '';     
    $tenants_name_for_grand_total=$tenants_name;
    
    if ($tenants_name == 'all') {   
    $sql_AA1 = "SELECT `table_name` FROM `company`";
    $result11a = mysqli_query($conn, $sql_AA1);

    $tenants_name_array = [];
    if ($result11a && mysqli_num_rows($result11a) > 0) {
        while ($row11a = mysqli_fetch_assoc($result11a)) {
            $tenants_name_array[] = $row11a['table_name'];
        }
    } 

    $queries = [];
    foreach ($tenants_name_array as $tenant_table) {
        // Sanitize table name to avoid SQL injection
        $tenant_table = mysqli_real_escape_string($conn, $tenant_table); 
        $queries[] = "SELECT '$tenant_table', tenants_name, id, date, month, actual_hr, actual_wb, actual_eb, actual_gb, actual_cb, 
                      hr_in, wasa_in, eb_in, gb_in, cb_in, hr_outs, wasa_outs, eb_outs, gb_outs, cb_outs 
                      FROM $tenant_table 
                      WHERE date BETWEEN '$from_date' AND '$to_date'";
    }

    // Combine all queries with UNION ALL
    $query_all = implode(' UNION ALL ', $queries);

    } else {
        // For a specific tenant, run the query on the selected tenant's table
        $query_all = "SELECT * FROM $tenants_name WHERE date BETWEEN '$from_date' AND '$to_date'";
    }
    $result1 = mysqli_query($conn, $query_all);

    if (mysqli_num_rows($result1) > 0) {
        // Grand totals for all tenants
        $grand_total_hr = 0;
        $grand_total_eb = 0;
        $grand_total_wb = 0;
        $grand_total_gb = 0;
        $grand_total_cb = 0;

        $grand_total_hr_in = 0;
        $grand_total_eb_in = 0;
        $grand_total_wb_in = 0;
        $grand_total_gb_in = 0;
        $grand_total_cb_in = 0;

        $grand_total_hr_outs = 0;
        $grand_total_eb_outs = 0;
        $grand_total_wb_outs = 0;
        $grand_total_gb_outs = 0;
        $grand_total_cb_outs = 0;

        $previous_tenant_name = null;
        $sub_total_hr = $sub_total_eb = $sub_total_wb =$sub_total_gb=$sub_total_cb= 0;
        $sub_total_hr_in = $sub_total_eb_in = $sub_total_wb_in =$sub_total_gb_in=$sub_total_cb_in= 0;
        $sub_total_hr_outs = $sub_total_eb_outs = $sub_total_wb_outs =$sub_total_gb_outs=$sub_total_cb_outs= 0;

        while ($row = mysqli_fetch_assoc($result1)) {
            // Reset subtotals if the tenant changes
            if ($previous_tenant_name !== null && $previous_tenant_name !== $row["tenants_name"]) {
                // Display subtotals for the previous tenant
                if ($fromDate1->format('Y-m') != $toDate1->format('Y-m')) {
                ?>
                <tr class="table-success">
                    <!-- <td><?php //echo $sub_total_hr; ?></td> -->
                    <td colspan="2" >Sub-Total <?php //echo $previous_tenant_name; ?>:</td>
                    <td><?php echo $sub_total_hr; ?></td>
                    <td><?php echo $sub_total_wb; ?></td>
                    <td><?php echo $sub_total_eb; ?></td>
                    <td><?php echo $sub_total_gb; ?></td>
                     <td><?php echo $sub_total_cb; ?></td>

                    <td><?php echo $sub_total_hr_in; ?></td>
                    <td><?php echo $sub_total_wb_in; ?></td>
                    <td><?php echo $sub_total_eb_in; ?></td>
                    <td><?php echo $sub_total_gb_in; ?></td>
                    <td><?php echo $sub_total_cb_in; ?></td>

                    <td><?php echo $sub_total_hr_outs; ?></td>
                    <td><?php echo $sub_total_wb_outs; ?></td>
                    <td><?php echo $sub_total_eb_outs; ?></td>
                    <td><?php echo $sub_total_gb_outs; ?></td>
                    <td><?php echo $sub_total_cb_outs; ?></td>

                </tr>
                <?php
                }
                // Reset subtotals for the new tenant
        $sub_total_hr = $sub_total_eb = $sub_total_wb =$sub_total_gb=$sub_total_cb= 0;
        $sub_total_hr_in = $sub_total_eb_in = $sub_total_wb_in =$sub_total_gb_in=$sub_total_cb_in= 0;
        $sub_total_hr_outs = $sub_total_eb_outs = $sub_total_wb_outs =$sub_total_gb_outs=$sub_total_cb_outs= 0;
            }
            // Accumulate subtotals for the current tenant
            $sub_total_hr += $row["actual_hr"];
            $sub_total_eb += $row["actual_eb"];
            $sub_total_wb += $row["actual_wb"];
            $sub_total_gb += $row["actual_gb"];
            $sub_total_cb += $row["actual_cb"];

            $sub_total_hr_in += $row["hr_in"];
            $sub_total_eb_in += $row["eb_in"];
            $sub_total_wb_in += $row["wasa_in"];
            $sub_total_gb_in += $row["gb_in"];
            $sub_total_cb_in += $row["cb_in"];

            $sub_total_hr_outs += $row["hr_outs"];
            $sub_total_eb_outs += $row["eb_outs"];
            $sub_total_wb_outs += $row["wasa_outs"];
            $sub_total_gb_outs += $row["gb_outs"];
            $sub_total_cb_outs += $row["cb_outs"];

            // Accumulate grand totals across all tenants
            $grand_total_hr += $row["actual_hr"];
            $grand_total_eb += $row["actual_eb"];
            $grand_total_wb += $row["actual_wb"];
            $grand_total_gb += $row["actual_gb"];
            $grand_total_cb += $row["actual_cb"];

            $grand_total_hr_in += $row["hr_in"];
            $grand_total_eb_in += $row["eb_in"];
            $grand_total_wb_in += $row["wasa_in"];
            $grand_total_gb_in += $row["gb_in"];
            $grand_total_cb_in += $row["cb_in"];

            $grand_total_hr_outs += $row["hr_outs"];
            $grand_total_eb_outs += $row["eb_outs"];
            $grand_total_wb_outs += $row["wasa_outs"];
            $grand_total_gb_outs += $row["gb_outs"];
            $grand_total_cb_outs += $row["cb_outs"];

            $previous_tenant_name = $row["tenants_name"];
            ?>
            <tr class="text-center"> 
                <!-- <td><?php //echo $row["id"]; ?></td>   -->
                <td><?php echo $row["tenants_name"]; ?></td>
                <td><?php echo $row["month"]; ?></td> 

                <td><?php echo $row["actual_hr"]; ?></td>
                <td><?php echo $row["actual_wb"]; ?></td>
                <td><?php echo $row["actual_eb"]; ?></td>
                <td><?php echo $row["actual_gb"]; ?></td>
                <td><?php echo $row["actual_cb"]; ?></td>

                <td><?php echo $row["hr_in"]; ?></td>
                <td><?php echo $row["wasa_in"]; ?></td>
                <td><?php echo $row["eb_in"]; ?></td>
                <td><?php echo $row["gb_in"]; ?></td>
                <td><?php echo $row["cb_in"]; ?></td>

                <td><?php echo $row["hr_outs"]; ?></td>
                <td><?php echo $row["wasa_outs"]; ?></td>
                <td><?php echo $row["eb_outs"]; ?></td>
               <td><?php echo $row["gb_outs"]; ?></td>
               <td><?php echo $row["cb_outs"]; ?></td>
            </tr>
            <?php
             }
            // Display the last tenant's subtotal
             //if ($fromDate1->format('Y-m') === $toDate1->format('Y-m')) {
            ?>
        <tr class="table-success">
            <!-- <td><?php //echo $sub_total_hr; ?></td> -->
            <?php  
               if ($tenants_name_for_grand_total=='all') { 
        if ($fromDate1->format('Y-m') != $toDate1->format('Y-m')) {
            ?>
            <td colspan="2" >Sub-Total  <?php //echo  $previous_tenant_name;  ?>:</td> 
             <td><?php echo $sub_total_hr; ?></td>
            <td><?php echo $sub_total_wb; ?></td>
            <td><?php echo $sub_total_eb; ?></td>
            <td><?php echo $sub_total_gb; ?></td>
            <td><?php echo $sub_total_cb; ?></td>

            <td><?php echo $sub_total_hr_in; ?></td>
            <td><?php echo $sub_total_wb_in; ?></td>
            <td><?php echo $sub_total_eb_in; ?></td>
            <td><?php echo $sub_total_gb_in; ?></td>
            <td><?php echo $sub_total_cb_in; ?></td>

            <td><?php echo $sub_total_hr_outs; ?></td>
            <td><?php echo $sub_total_wb_outs; ?></td>
            <td><?php echo $sub_total_eb_outs; ?></td>
            <td><?php echo $sub_total_gb_outs; ?></td>
            <td><?php echo $sub_total_cb_outs; ?></td>
            <?php 
                } 
            }
            else{ 
            ?> 
            <td colspan="2" >Total for <?php echo $previous_tenant_name; ?>:</td>  
             <td><?php echo $sub_total_hr; ?></td>
            <td><?php echo $sub_total_wb; ?></td>
            <td><?php echo $sub_total_eb; ?></td>
            <td><?php echo $sub_total_gb; ?></td>
            <td><?php echo $sub_total_cb; ?></td>

            <td><?php echo $sub_total_hr_in; ?></td>
            <td><?php echo $sub_total_wb_in; ?></td>
            <td><?php echo $sub_total_eb_in; ?></td>
            <td><?php echo $sub_total_gb_in; ?></td>
            <td><?php echo $sub_total_cb_in; ?></td>

            <td><?php echo $sub_total_hr_outs; ?></td>
            <td><?php echo $sub_total_wb_outs; ?></td>
            <td><?php echo $sub_total_eb_outs; ?></td>
            <td><?php echo $sub_total_gb_outs; ?></td>
            <td><?php echo $sub_total_cb_outs; ?></td>
            
            <?Php } ?>  
            </tr>  
            <?php
           //}
            if ($tenants_name_for_grand_total=='all') {
             ?>
        <!-- Display grand totals -->
        <tr class="table-primary">
            <!-- <td><?php //echo $sub_total_hr; ?></td> -->
            <td colspan="2" class="fw-bold">Grand Total:</td>
            <td><?php echo $grand_total_hr; ?></td>
            <td><?php echo $grand_total_wb; ?></td>
            <td><?php echo $grand_total_eb; ?></td>
            <td><?php echo $grand_total_gb; ?></td>
            <td><?php echo $grand_total_cb; ?></td>

            <td><?php echo $grand_total_hr_in; ?></td>
            <td><?php echo $grand_total_wb_in; ?></td>
            <td><?php echo $grand_total_eb_in; ?></td>
            <td><?php echo $grand_total_gb_in; ?></td>
            <td><?php echo $grand_total_cb_in; ?></td>

            <td><?php echo $grand_total_hr_outs; ?></td>
            <td><?php echo $grand_total_wb_outs; ?></td>
            <td><?php echo $grand_total_eb_outs; ?></td>
            <td><?php echo $grand_total_gb_outs; ?></td>
            <td><?php echo $grand_total_cb_outs; ?></td>
        </tr>
        <?php
            }
        } else {
        echo '<tr><td colspan="11" class="text-center text-danger">No records found for the selected date range.</td></tr>';
    }
}
mysqli_close($conn);
?>
        </tbody>
    </table>
</div>
<!-- </div> -->
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