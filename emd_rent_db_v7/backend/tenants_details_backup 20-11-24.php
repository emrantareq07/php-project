<?php 
session_name('emd_rent_db');
session_start();
include_once '../db/database.php';
include_once 'header.php';
// include_once 'tenants_details_navbar.php';
require_once("config.php");
include_once 'navbar.php';
// Check if the user is already logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
$username=$_SESSION['username']; 
$user_type=$_SESSION['user_type'];
$office=$_SESSION['office'];
$tenants_tbl = $_GET['tenants_tbl'] ?? ''; // Retrieve the tenants table from the URL
$_SESSION['tenants_tbl']=$_GET['tenants_tbl'];
// Define the valid tables array
$valid_tables = [
    'abbank_tbl', 'poton_tbl', 'mollik_tbl', 'mrtrading_tbl', 'motalibasso_tbl', 
    'romanaent_tbl', 'rafirachi_tbl', 'oyasistec_tbl', 'mehedient_tbl', 
    'multiwaysmkt_tbl', 'demano_tbl', 'beximco_tbl', 'bdg_tbl', 'creativep_tbl', 
    'pp_tbl', 'sadg_tbl', 'gp_tbl', 'carbon_tbl', 'bcicsamiti_tbl', 'deshb_tbl', 
    'hirajheelh_tbl', 'cccl_tbl', '13buf_tbl', '34buf_tbl', 'daycare_tbl', 
    'pdb_tbl', 'fahiment_tbl', 'shamin_tbl', 'chainntrad_tbl', 'nment_tbl', 
    'rajobali_tbl', 'arjufood_tbl','erres_tbl','rajuk_tbl'
];
// Check if table is valid
if (!in_array($tenants_tbl, $valid_tables)) {
    die("Invalid table name.");
}
// Set tenant name based on table using switch case
$tenants_name = '';
switch ($tenants_tbl) {
    case 'abbank_tbl':
        $tenants_name = 'AB Bank';
        break;
    case 'poton_tbl':
        $tenants_name = 'Poton Traders';
        break;
    case 'mollik_tbl':
        $tenants_name = 'Mollik Traders';
        break;

    case 'erres_tbl':
        $tenants_name = 'E. R. Resourses';
        break;

    case 'mrtrading_tbl':
        $tenants_name = 'M. R. Trading';
        break;
    case 'motalibasso_tbl':
        $tenants_name = 'Motalib & Associates';
        break;
    case 'romanaent_tbl':
        $tenants_name = 'Romana Enterprise';
        break;
    case 'rafirachi_tbl':
        $tenants_name = 'Rafi & Rachi';
        break;
    case 'oyasistec_tbl':
        $tenants_name = 'Oyasis Technologies';
        break;
    case 'mehedient_tbl':
        $tenants_name = 'Mehedi Enterprise';
        break;
    case 'multiwaysmkt_tbl':
        $tenants_name = 'Multi-Ways Marketing';
        break;
    case 'demano_tbl':
        $tenants_name = 'Demano Ltd.';
        break;
    case 'beximco_tbl':
        $tenants_name = 'BEXIMCO';
        break;
    case 'bdg_tbl':
        $tenants_name = 'Bangladesh Development Group';
        break;
    case 'creativep_tbl':
        $tenants_name = 'Creative Papers';
        break;
    case 'pp_tbl':
        $tenants_name = 'Petroleum Products';
        break;
    case 'sadg_tbl':
        $tenants_name = 'South Asia Dev. Group';
        break;
    case 'gp_tbl':
        $tenants_name = 'Grameen Phone';
        break;
    case 'carbon_tbl':
        $tenants_name = 'Carbon Holdings';
        break;
    case 'bcicsamiti_tbl':
        $tenants_name = 'BCIC Samiti';
        break;
    case 'deshb_tbl':
        $tenants_name = 'Desh Builders';
        break;
    case 'hirajheelh_tbl':
        $tenants_name = 'Hirajheel Hotel';
        break;
    case 'cccl_tbl':
        $tenants_name = 'Chatak Cement Pro';
        break;
    case '13buf_tbl':
        $tenants_name = '13 Buffer Project';
        break;
    case '34buf_tbl':
        $tenants_name = '34 Buffer Project';
        break;
    case 'daycare_tbl':
        $tenants_name = 'Day Care';
        break;
    case 'pdb_tbl':
        $tenants_name = 'BPDB';
        break;
    case 'fahiment_tbl':
        $tenants_name = 'Fahim Enterprise';
        break;
    case 'shamin_tbl':
        $tenants_name = 'Sharmin Akter';
        break;
    case 'chainntrad_tbl':
        $tenants_name = 'Chaina Traders';
        break;
    case 'nment_tbl':
        $tenants_name = 'N M Enterprise';
        break;
    case 'rajobali_tbl':
        $tenants_name = 'Rajob Ali';
        break;
    case 'arjufood_tbl':
        $tenants_name = 'Arju Food & Restaurant';
        break;
             case 'rajuk_tbl':
        $tenants_name = 'Rajuk';
        break;
    default:
        die("Invalid tenants table.");
}
// Output the tenant name (for testing purposes)
//echo $tenants_name;
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

<!-- Printable Area -->
<div id="printableArea">  
    <table class="table table-bordered table-striped table-hover ">
      <thead class="table-primary text-center">
          <tr rowspan="2">
          <th></th>
          <th ></th>
          <th colspan="5">Bill</th>
          <th colspan="5">Bill Received</th>
          <th colspan="5">Bill Due</th>   
          <th id="action"></th>
        </tr>
        <tr >
          <th>#</th>
          <th >Month</th>
          <th>HR<span style="font-size: 1.2em;">৳</span></th>
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
          <th id="action">ACTION</th>
        </tr>

      </thead>
      <tbody > 
      <tr class="text-center text-info fw-bold"><h4 class="text-center text-info fw-bold"><?php echo $tenants_name; ?></h4></tr>    
    <?php
    $today_date=date("Y-m-d");
    date_default_timezone_set('Asia/Dhaka'); // Set timezone to Bangladesh time
    $time = date("g:i A"); // Format time as 'hour:minute AM/PM'
    $result = mysqli_query($conn, "SELECT * FROM `$tenants_tbl`");
    if ($result) {
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
    ?>   
      <tr id="<?php echo $row["id"]; ?>" class=" text-center"> 
      <td><?php echo $row["id"]; ?></td>  
      <td style="font-size: 14px !important;"><?php echo $row["month"];?></td>       
      <td ><?php echo $row["actual_hr"];?></td>
      <td ><?php echo $row["actual_wb"];?></td>
      <td ><?php echo $row["actual_eb"];?></td>
      <td ><?php echo $row["actual_gb"];?></td>
      <td ><?php echo $row["actual_cb"];?></td>

      <td ><?php echo $row["hr_in"];?></td>
      <td ><?php echo $row["wasa_in"];?></td>
      <td ><?php echo $row["eb_in"];?></td>
      <td ><?php echo $row["gb_in"];?></td>
      <td ><?php echo $row["cb_in"];?></td>

      <td ><?php echo  $row["hr_outs"]; ?></td>
      <td ><?php echo $row["wasa_outs"]; ?></td>
      <td><?php echo $row["eb_outs"]; ?></td> 
      <td><?php echo $row["gb_outs"]; ?></td> 
      <td><?php echo $row["cb_outs"]; ?></td> 
      <td id="action_t">

    <a href="#viewEmployeeModal_tenants" class="edit" data-toggle="modal" style="text-decoration: none">
    <i class="fa fa-newspaper-o receipt_for_tenants" data-toggle="tooltip"
       data-tenants_name="<?php echo $row['tenants_name']; ?>"
       data-date="<?php echo $row['date']; ?>"
       data-month="<?php echo $row['month'];?>"
       data-actual_hr="<?php echo $row['actual_hr']; ?>"
       data-actual_wb="<?php echo $row['actual_wb']; ?>"
       data-actual_eb="<?php echo $row['actual_eb']; ?>" 
       data-actual_gb="<?php echo $row['actual_gb']; ?>" 
       data-actual_cb="<?php echo $row['actual_cb']; ?>" 

       data-hr_in="<?php echo $row['hr_in']; ?>"
       data-wasa_in="<?php echo $row['wasa_in'];?>"
       data-eb_in="<?php echo $row['eb_in']; ?>"
       data-gb_in="<?php echo $row['gb_in']; ?>"
        data-cb_in="<?php echo $row['cb_in']; ?>"

       data-hr_outs="<?php echo $row['hr_outs']; ?>"
       data-wasa_outs="<?php echo $row['wasa_outs']; ?>"
       data-eb_outs="<?php echo $row['eb_outs']; ?>"
        data-gb_outs="<?php echo $row['gb_outs']; ?>"
        data-cb_outs="<?php echo $row['cb_outs']; ?>"

        data-tax5="<?php echo $row['tax5']; ?>"
       data-challan_no_tax="<?php echo $row['challan_no_tax'];?>"
       data-date_tax="<?php echo $row['date_tax']; ?>"
       data-vat15="<?php echo $row['vat15']; ?>"
       data-challa_no_vat="<?php echo $row['challa_no_vat']; ?>"
       data-date_vat="<?php echo $row['date_vat']; ?>"
       title="Receipt for Tenants" style="font-size:20px; color:yellowgreen;"></i>
      </a>&nbsp;

      <a href="update.php?tenants_tbl=<?php echo $tenants_tbl; ?>&id=<?php echo $row['id']; ?>" style="text-decoration: none">
        <i class="fa fa-edit update" id="edit_btn" data-toggle="tooltip" title="Edit" style="font-size:20px; color:deeppink;"></i>
    </a>&nbsp;
  <a href="#viewEmployeeModal" class="edit" data-toggle="modal" style="text-decoration: none">
    <i class="fa fa-info-circle viewothers" data-toggle="tooltip"
       data-tenants_name="<?php echo $row['tenants_name']; ?>"
       data-date="<?php echo $row['date']; ?>"
       data-month="<?php echo $row['month'];?>"
       data-actual_hr="<?php echo $row['actual_hr']; ?>"
       data-actual_wb="<?php echo $row['actual_wb']; ?>"
       data-actual_eb="<?php echo $row['actual_eb']; ?>" 
       data-actual_gb="<?php echo $row['actual_gb']; ?>" 
       data-actual_cb="<?php echo $row['actual_cb']; ?>" 

       data-hr_in="<?php echo $row['hr_in']; ?>"
       data-wasa_in="<?php echo $row['wasa_in'];?>"
       data-eb_in="<?php echo $row['eb_in']; ?>"
       data-gb_in="<?php echo $row['gb_in']; ?>"
        data-cb_in="<?php echo $row['cb_in']; ?>"

       data-hr_outs="<?php echo $row['hr_outs']; ?>"
       data-wasa_outs="<?php echo $row['wasa_outs']; ?>"
       data-eb_outs="<?php echo $row['eb_outs']; ?>"
        data-gb_outs="<?php echo $row['gb_outs']; ?>"
        data-cb_outs="<?php echo $row['cb_outs']; ?>"

        data-tax5="<?php echo $row['tax5']; ?>"
       data-challan_no_tax="<?php echo $row['challan_no_tax'];?>"
       data-date_tax="<?php echo $row['date_tax']; ?>"
       data-vat15="<?php echo $row['vat15']; ?>"
       data-challa_no_vat="<?php echo $row['challa_no_vat']; ?>"
       data-date_vat="<?php echo $row['date_vat']; ?>"
       title="Receipt own" style="font-size:20px; color:yellowgreen;"></i>
      </a>&nbsp; 

     <!--  <a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["id"]; ?>" data-toggle="modal">
      
      <i class="fa fa-trash" id="delete_btn" style="font-size:20px;color:red;"></i>
        </a> -->
        </td>
      </tr> 
       <?php 
          }
        }
        else{
          echo "<p class='btn btn-danger btn-md '>  No Record Found!!!</p>";
          }
        } else {
            echo "Error: " . mysqli_error($conn);           
        }
      ?>  
      </tbody>
    </table>     
    </div>
  </div>
</div>

<!-- View Modal Customaer Copy HTML -->
<div id="viewEmployeeModal_tenants" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="">                
            <div class="modal-header d-flex justify-content-center">
              <!-- <h5 class="modal-title text-uppercase text-center text-muted flex-grow-1"><b>Rent Management System </b></h5> -->
              <!-- <h5 class="modal-title text-uppercase text-center text-muted flex-grow-1"><b>Customer Copy</b></h5> -->
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
          <div class="modal-body" id="printableArea_ind_tenants">

            <div style="text-align:center; display: flex; align-items: center; justify-content: center;" class="div2">
                <!-- Image with minimized top margin -->
                <img src="images/bcic_logo.png" alt="BCIC Logo" style="width:80px; height:auto; margin-right:10px; margin-top: -5px;">                
                <!-- Header text on the right side -->
                <div>
                    <h5 >BANGLADESH CHEMICAL INDUSTRIES CORPORATION</h5>
                    <h5 class="text-decoration-none righteous-regular" >Estate Management Department (EMD)</h5>
                    <h5 class="text-decoration-none" id="tenants_name_u"></h5>
                    <h6 class="text-decoration-none badge bg-primary" id="month_u"></h6>
                </div>

            </div>   


          <table class="table table-bordered">
              <tbody> 
                 <tr rowspan="2" class="border-0 " ><th colspan="2" class="border-0"></th> </tr> 

                <tr class="table-success" >
                <th class="text-center"> Name of Bills </th>
                <th class="text-center" colspan="2"> Amount/ Bill (TK)</th>
                <!-- <th>Received Bill</th> -->
                <!-- <th class="text-center"> Due Bill (TK)</th> -->
              </tr>
             <tr> 
            <td class="text-center">House Rent </td>
                <td class="text-center" id="actual_hr_u" colspan="2"></td>
                <!-- <th id="hr_in_u"></th> -->
                <!-- <td class="text-center" id="hr_outs_u"></td>         -->
          </tr>
          <tr> 
                <td class="text-center">Water Bill </td>
                <td class="text-center" id="actual_wb_u" colspan="2"></td>
                <!-- <th id="wasa_in_u"></th> -->
                <!-- <td class="text-center" id="wasa_outs_u"></td> -->
          </tr>
          <tr> 
                <td class="text-center">Electricity Bill </td>
                <td class="text-center" id="actual_eb_u" colspan="2"></td>
                <!-- <th id="eb_in_u"></th> -->
                <!-- <td class="text-center" id="eb_outs_u"></td> -->
          </tr>
          <tr> 
               <td class="text-center ">Gas Bill</td>
                <td class="text-center" id="actual_gb_u" colspan="2"></td>
                <!-- <th id="eb_in_u"></th> -->
                <!-- <td class="text-center" id="gb_outs_u"></td> -->
          </tr>

            <tr> 
               <td class="text-center ">Car Parking</td>
                <td class="text-center" id="actual_cb_u" colspan="2"></td>
                <!-- <th id="eb_in_u"></th> -->
                <!-- <td class="text-center" id="cb_outs_u"></td> -->
          </tr>
          <tr class=" table-secondary">
            <th class="text-center ">Total (TK): </th>   
                <th class="text-center" id="hr_total_u" colspan="2"></th>
                <!-- <th id="wasa_total_u"></th> -->
                <!-- <th class="text-center" id="eb_total_u"></th> -->
          </tr>          
        <tr class="border-0">
        <th colspan="3" class="border-0" style="height: 40px;"></th> <!-- You can adjust the height as needed -->
        </tr>
        <tr class="table-secondary ">
            <td class="text-center p-0 fw-bold">Net Payable/Grand Total (TK): </td>   
                <td class="text-center p-0 fw-bold" id="grand_total_u" colspan="2" class="border-0" ></td>
                <!-- <th id="wasa_total_u"></th> -->
                <!-- <th id="eb_total_u"></th> -->
          </tr>        
         <tr rowspan="2" class="border-0" ><th colspan="3" class="border-0"></th> </tr> 
            
       <tr class="border-0">
        <th colspan="3" class="border-0" style="height: 40px;"></th> <!-- You can adjust the height as needed -->
        </tr>           
            <tr rowspan="2" class="border-0">
              <!-- <th colspan="2" class="border-0 ">Prepared By</th>  -->
              <th  class="border-0 border-top text-center ">Prepared By</th>
                <th colspan="1" class="border-0 "></th>
             <th colspan="1" class="border-0 border-top text-center ">ACA/DCA, EMD<br>For General Manager (EMD)</th>
            </tr>

             <tr rowspan="2" class="border-0" ><th colspan="3" class="border-0"></th> </tr> 
           <tr class="border-0">
        <td colspan="3" class="border-0" style="height: 60px;"><b>CC TO: </b><br>1. Controller of Accounts, BCIC, Dhaka<br> 2. Customer Copy <br> 3. Office Copy</td> 

        </tr> 
        </tbody>
      </table> 
            </div>
        <div class="modal-footer">
            <input type="hidden" value="2" name="type">
            <input type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" value="Cancel">
            <button type="button" class="btn btn-success" id="print_ind_tenants"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // for receipt for tenants
$(document).on('click', '.receipt_for_tenants', function() {
    // Retrieve the data-* attributes from the clicked icon
    var tenants_name = $(this).data("tenants_name");
    var month = $(this).data("month");
    var actual_hr = $(this).data("actual_hr");
    var actual_wb = $(this).data("actual_wb");
    var actual_eb = $(this).data("actual_eb");
    var actual_gb = $(this).data("actual_gb");
    var actual_cb = $(this).data("actual_cb");

    var hr_in = $(this).data("hr_in");
    var wasa_in = $(this).data("wasa_in");
    var eb_in = $(this).data("eb_in");
    var gb_in = $(this).data("gb_in");
    var cb_in = $(this).data("cb_in");

    var hr_outs = $(this).data("hr_outs");
    var wasa_outs = $(this).data("wasa_outs");
    var eb_outs = $(this).data("eb_outs");
    var gb_outs = $(this).data("gb_outs");
    var cb_outs = $(this).data("cb_outs");
    var date = $(this).data("date");

    var tax5 = $(this).data("tax5");
    var challan_no_tax = $(this).data("challan_no_tax");
    var date_tax = $(this).data("date_tax");
    var vat15 = $(this).data("vat15");
    var challa_no_vat = $(this).data("challa_no_vat");
    var date_vat = $(this).data("date_vat");

     var hr_total=actual_hr+actual_wb+actual_eb+actual_gb+actual_cb;
     var wasa_total=hr_in+wasa_in+eb_in+gb_in+cb_in;
     var outs_total=hr_outs+wasa_outs+eb_outs+gb_outs+cb_outs;

     var grand_total=hr_total + outs_total;
     var dateObj = new Date(date);
    // Extract the month and year
    //var month = dateObj.toLocaleString('default', { month: 'long' }); // Gets the full month name
    var year = dateObj.getFullYear(); // Gets the year
    // Update the month field in the modal
    $('#month_u').text('Bill: ' + month + ' ' + year);
    // Populate modal fields with the data
     $('#tenants_name_u').text('Tenants Name: ' + tenants_name);
  // $('#month_u').text('PaySlip of Month: ' + month);
    $('#actual_hr_u').text(actual_hr || '0');
    $('#actual_wb_u').text(actual_wb || '0');
    $('#actual_eb_u').text(actual_eb || '0');
    $('#actual_gb_u').text(actual_gb || '0');
    $('#actual_cb_u').text(actual_cb || '0');

    $('#hr_total_u').text(hr_total || '0');

    $('#hr_in_u').text(hr_in || '0');
    $('#wasa_in_u').text(wasa_in || '0');
    $('#eb_in_u').text(eb_in || '0');
    $('#gb_in_u').text(gb_in || '0');
     $('#cb_in_u').text(cb_in || '0');
    $('#wasa_total_u').text(wasa_total || '0');

    $('#hr_outs_u').text(hr_outs || '0');
    $('#wasa_outs_u').text(wasa_outs || '0');
    $('#eb_outs_u').text(eb_outs || '0');
    $('#gb_outs_u').text(gb_outs || '0');
    $('#cb_outs_u').text(cb_outs || '0');
    $('#eb_total_u').text(outs_total || '0');

    //$('#grand_total_u').text(grand_total + " (" + numberToWords(grand_total) + ")");
   // $('#grand_total_u').html(grand_total + "&nbsp;&nbsp;(" + numberToWords(grand_total) + " TK Only)");
     $('#grand_total_u').html(hr_total + " (" + numberToWords(hr_total) + " TK Only)");

   // $('#grand_total_uu').text(numberToWords(grand_total) || 'Zero');
    // $('#grand_total_u').text(grand_total || '0');    
    $('#tax5_u').text(tax5 || '0');
    $('#challan_no_tax_u').text(challan_no_tax || '0');
    $('#date_tax_u').text(date_tax || '0');
    $('#vat15_u').text(vat15 || '0');
    $('#challa_no_vat_u').text(challa_no_vat || '0');
    $('#date_vat_u').text(date_vat || '0');
    // Trigger the modal to open
    $('#viewEmployeeModal_tenants').modal('show');
});

document.getElementById('print_ind_tenants').addEventListener('click', function () {
    // Define the labels for each copy
    const labels = ["Customer Copy", "Accounts Copy", "Office Copy"];    
    // Store the original content of the printable area
    const originalContent = document.getElementById('printableArea_ind_tenants').innerHTML;
    // Prepare the printable content by duplicating the sections with different labels
    // Format date as '14 Nov 2024'
    const formattedDate = new Date().toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });

    let printContents = '';
    labels.forEach(label => {
        // Insert the label into the original content, along with a footer
        const copyContent = `
            <div style="page-break-after: always; margin: 20px 0 0 0; padding: 15px; border: 2px solid gray; border-radius: 10px;">
                <div style="text-align:right; margin-bottom: 10px;">
                    <h6 class="mb-0 badge bg-danger">${label}</h6>
                </div>
                ${originalContent}
                <div style="text-align: center; margin-top: 80px; margin-bottom: 20px; font-size: 0.9rem;">
                    <hr>
                    <p>Generated on ${formattedDate} | Designed & Developed By ICT Division, BCIC.</p>
                </div>
            </div>
        `;
        printContents += copyContent;
    });
    // Store the original body content to restore later
    const originalBody = document.body.innerHTML;
    // Replace the body's content with the three copies for printing
    document.body.innerHTML = `
        <html>
        <head>
          <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
          <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
              .badge {
                  font-size: 1rem;
                  margin-bottom: 10px;
              }
          </style>
        </head>
        <body>${printContents}</body>
        </html>
    `;

    // Trigger the print dialog
    window.print();

    // Restore the original content after printing
    document.body.innerHTML = originalBody;

    // Reload the page to restore any JavaScript functionality
    window.location.reload();
});

</script>

<!-- View Modal for Office HTML -->
<div id="viewEmployeeModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="">                
            <div class="modal-header d-flex justify-content-center">
              <!-- <h4 class="modal-title text-uppercase text-center text-muted flex-grow-1"><b>Rent Management System </b></h4> -->
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
          <div class="modal-body" id="printableArea_ind">

<!-- 
            <div style="text-align:center;" class="div2">
              <h5>BANGLADESH CHEMICAL INDUSTRIES CORPORATION</h5>
              <h5 class="text-decoration-none" id="tenants_name_uo"> </h5>
              <h6 class="text-decoration-none" id="month_uo"></h6>
              <h6 class="mb-0 badge bg-success">Office Copy</h6>
              <h6 class="mb-0"></h6>
           
            </div> --> 

                        <div style="text-align:center; display: flex; align-items: center; justify-content: center;" class="div2">
                <!-- Image with minimized top margin -->
                <img src="images/bcic_logo.png" alt="BCIC Logo" style="width:80px; height:auto; margin-right:10px; margin-top: -5px;">                
                <!-- Header text on the right side -->
                <div>
                    <h5 >BANGLADESH CHEMICAL INDUSTRIES CORPORATION</h5>
                    <h5 class="text-decoration-none righteous-regular" >Estate Management Department (EMD)</h5>
                    <h5 class="text-decoration-none" id="tenants_name_uo"></h5>
                    <h6 class="text-decoration-none  " id="month_uo"></h6><br>
               
                </div>
                
            </div> 
          <table class="table table-bordered">


              <tbody> 
                 <tr rowspan="2" class="border-0" ><th colspan="3" class="border-0"></th> </tr> 
                <tr class="table-primary">
<th class="text-center">Name of Bills</th>
<th class="text-center">Amount/ Bill</th>
<th class="text-center">Received Bill</th>
<th class="text-center">Due</th>

              </tr>

<tr> 
    <td class="text-center">House Rent (TK)</td>
    <td id="actual_hr_uo" class="text-center"></td>
    <td id="hr_in_uo" class="text-center"></td>
    <td id="hr_outs_uo" class="text-center"></td>        
</tr>
<tr> 
    <td class="text-center">Water Bill (TK)</td>
    <td id="actual_wb_uo" class="text-center"></td>
    <td id="wasa_in_uo" class="text-center"></td>
    <td id="wasa_outs_uo" class="text-center"></td>
</tr>
<tr> 
    <td class="text-center">Electricity Bill (TK)</td>
    <td id="actual_eb_uo" class="text-center"></td>
    <td id="eb_in_uo" class="text-center"></td>
    <td id="eb_outs_uo" class="text-center"></td>
</tr>
<tr> 
    <td class="text-center">Gas Bill (TK)</td>
    <td id="actual_gb_uo" class="text-center"></td>
    <td id="gb_in_uo" class="text-center"></td>
    <td id="gb_outs_uo" class="text-center"></td>
</tr>
<tr> 
    <td class="text-center">Car Parking (TK)</td>
    <td id="actual_cb_uo" class="text-center"></td>
    <td id="cb_in_uo" class="text-center"></td>
    <td id="cb_outs_uo" class="text-center"></td>
</tr>
<tr class="table-success">
    <th class="text-center">Total (TK):</th>   
    <th id="hr_total_uo" class="text-center"></th>
    <th id="wasa_total_uo" class="text-center"></th>
    <th id="eb_total_uo" class="text-center"></th>
</tr>
<tr rowspan="2" class="border-0"><th colspan="4" class="border-0"></th></tr> 
<tr>
    <td class="text-center">Tax (5%):</td>
    <td id="tax5_uo" class="text-center"></td>
    <td class="text-center">VAT (15%):</td>
    <td id="vat15_uo" class="text-center"></td>
</tr>
<tr>
    <td class="text-center">Challan No (Tax):</td>
    <td id="challan_no_tax_uo" class="text-center"></td>
    <td class="text-center">Challan No (VAT):</td>
    <td id="challa_no_vat_uo" class="text-center"></td>
</tr>
<tr>
    <td class="text-center">Challan Date (Tax):</td>
    <td id="date_tax_uo" class="text-center"></td>
    <td class="text-center">Challan Date (VAT):</td>
    <td id="date_vat_uo" class="text-center"></td>
</tr>



       <tr class="border-0">
        <th colspan="4" class="border-0" style="height: 40px;"></th> <!-- You can adjust the height as needed -->
        </tr>           
            <tr rowspan="2" class="border-0">
              <th colspan="3" class="border-0"></th> 
              <th class="border-0">Signature</th> 
            </tr> 
        </tbody>
      </table> 
            </div>
        <div class="modal-footer">
            <input type="hidden" value="2" name="type">
            <input type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" value="Cancel">
            <button type="button" class="btn btn-danger" id="print_ind"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script >
function numberToWords(number) {
    const words = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve',
        'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    ];
    const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    const levels = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];

    if (number === 0) return 'Zero';

    let result = '';
    let level = 0;

    while (number > 0) {
        let part = number % 1000;
        if (part > 0) {
            let wordsPart = '';

            if (part > 99) {
                wordsPart += words[Math.floor(part / 100)] + ' Hundred ';
                part %= 100;
            }

            if (part > 0) {
                if (part < 20) {
                    wordsPart += words[part];
                } else {
                    wordsPart += tens[Math.floor(part / 10)];
                    if (part % 10 > 0) {
                        wordsPart += ' ' + words[part % 10];
                    }
                }
            }

            result = wordsPart.trim() + ' ' + levels[level] + ' ' + result;
        }

        number = Math.floor(number / 1000);
        level++;
    }

    return result.trim();
}

</script>

<script type="text/javascript">
  //  viewothers for office
$(document).on('click', '.viewothers', function() {
    // Retrieve the data-* attributes from the clicked icon
    var tenants_name = $(this).data("tenants_name");
    var month = $(this).data("month");
    var actual_hr = $(this).data("actual_hr");
    var actual_wb = $(this).data("actual_wb");
    var actual_eb = $(this).data("actual_eb");
    var actual_gb = $(this).data("actual_gb");
    var actual_cb = $(this).data("actual_cb");

    var hr_in = $(this).data("hr_in");
    var wasa_in = $(this).data("wasa_in");
    var eb_in = $(this).data("eb_in");
    var gb_in = $(this).data("gb_in");
    var cb_in = $(this).data("cb_in");

    var hr_outs = $(this).data("hr_outs");
    var wasa_outs = $(this).data("wasa_outs");
    var eb_outs = $(this).data("eb_outs");
    var gb_outs = $(this).data("gb_outs");
    var cb_outs = $(this).data("cb_outs");
    var date = $(this).data("date");

    var tax5 = $(this).data("tax5");
    var challan_no_tax = $(this).data("challan_no_tax");
    var date_tax = $(this).data("date_tax");
    var vat15 = $(this).data("vat15");
    var challa_no_vat = $(this).data("challa_no_vat");
    var date_vat = $(this).data("date_vat");


     var hr_total=actual_hr+actual_wb+actual_eb+actual_gb+actual_cb;
     var wasa_total=hr_in+wasa_in+eb_in+gb_in+cb_in;
     var eb_total=hr_outs+wasa_outs+eb_outs+gb_outs+cb_outs;

     var grand_total=hr_total + eb_total;
     var dateObj = new Date(date);
    // Extract the month and year
    //var month = dateObj.toLocaleString('default', { month: 'long' }); // Gets the full month name
    var year = dateObj.getFullYear(); // Gets the year

    // Update the month field in the modal
   $('#month_uo').text('Bill: ' + month + ' ' + year);
// Populate modal fields with the data
$('#tenants_name_uo').text('Tenants Name: ' + tenants_name);
// $('#month_uo').text('PaySlip of Month: ' + month);
$('#actual_hr_uo').text(actual_hr || '0');
$('#actual_wb_uo').text(actual_wb || '0');
$('#actual_eb_uo').text(actual_eb || '0');
$('#actual_gb_uo').text(actual_gb || '0');
$('#actual_cb_uo').text(actual_cb || '0');

$('#hr_total_uo').text(hr_total || '0');

$('#hr_in_uo').text(hr_in || '0');
$('#wasa_in_uo').text(wasa_in || '0');
$('#eb_in_uo').text(eb_in || '0');
$('#gb_in_uo').text(gb_in || '0');
$('#cb_in_uo').text(cb_in || '0');
$('#wasa_total_uo').text(wasa_total || '0');

$('#hr_outs_uo').text(hr_outs || '0');
$('#wasa_outs_uo').text(wasa_outs || '0');
$('#eb_outs_uo').text(eb_outs || '0');
$('#gb_outs_uo').text(gb_outs || '0');
$('#cb_outs_uo').text(cb_outs || '0');
$('#eb_total_uo').text(eb_total || '0');

// $('#grand_total_uo').text(grand_total + " (" + numberToWords(grand_total) + ")");
$('#grand_total_uo').html(grand_total + " (" + numberToWords(grand_total) + " TK Only)");

// $('#grand_total_uoo').text(numberToWords(grand_total) || 'Zero');
// $('#grand_total_uo').text(grand_total || '0');
$('#tax5_uo').text(tax5 || '0');
$('#challan_no_tax_uo').text(challan_no_tax || '0');
$('#date_tax_uo').text(date_tax || '0');
$('#vat15_uo').text(vat15 || '0');
$('#challa_no_vat_uo').text(challa_no_vat || '0');
$('#date_vat_uo').text(date_vat || '0');


    // Trigger the modal to open
    $('#viewEmployeeModal').modal('show');
});

    document.getElementById('print_ind').addEventListener('click', function() {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea_ind').innerHTML;
    //var title = '<h1 class="text-center">পত্র প্রাপ্তি রেজিস্টার</h1>';
    // Create a container for the content to be printed
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = `
        <html>
        <head>            
          <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
          <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
            ${printContents}
        </body>
        </html>
    `;
    // Trigger the print dialog
    window.print();
    // Restore the original contents of the page after printing
    document.body.innerHTML = originalContents;    
    // Reload the page to reflect the original content and avoid any loss of functionality
    window.location.reload();
});



</script>  
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>        
              <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title text-uppercase">Delete Meeting</h4>
              <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
        <div class="modal-body">
          <input type="hidden" id="id_d" name="id" class="form-control">          
          <p>Are you sure you want to delete these Records?</p>
          <p class="text-warning"><small>This action cannot be undone.</small></p>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-outline-secondary" data-dismiss="modal" value="Cancel">
          <button type="button" class="btn btn-danger" id="delete">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- for print all page info -->
<script type="text/javascript">
document.getElementById('print').addEventListener('click', function() {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea').innerHTML;
    var title = '<h1 class="text-center text-uppercase">Rent Management System</h1>';
    // Create a container for the content to be printed
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = `
        <html>
        <head>
            <title></title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                #edit_btn{
                  display: none;
                  visibility: none;
                }
                #action_t{
                  display: none;
                  visibility: none;
                }
                #action{
                  display: none;
                  visibility: none;
                }
                #status{
                  display: none;
                  visibility: none;
                }
                #status_t{
                  display: none;
                  visibility: none;
                }
            </style>
        </head>
        <body>
            ${title}
            ${printContents}
        </body>
        </html>
    `;

    // Trigger the print dialog
    window.print();
    // Restore the original contents of the page after printing
    document.body.innerHTML = originalContents;    
    // Reload the page to reflect the original content and avoid any loss of functionality
    window.location.reload();
});
</script>
<?php 
include_once 'footer.php';
?>