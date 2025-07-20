<?php
session_start();
  
require_once("config/config.php");
require_once("db/db.php");
$val=0;
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";

      $result= $usercredentials->runBaseQuery($query);

      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }

if(isset($_GET['val'])){
 $val= $_GET['val']; 
  }

 ob_start(); // Start output buffering
include_once"includes/header_user-dashboard.php";
?> 
<!-- <center>
        <h4>[--বিসিআইসি মামলার ডাটাবেস--]</h4>
    </center> -->

  <div class="container-fluid box border border-rounded shadow mt-1">
   <h2 align="center" class="text-center text-muted">   
    বিসিআইসি মামলার ডাটাবেস 
    <span class="float-end">

     <!-- <span class="float-end"><a href="../print11.php" class="btn btn-primary ">Print</a>     -->
      <?php 
       ob_start(); // Start output buffering
      if($val=='STAREK!%b@IWORNE**4'){
        ?>
        <a href="user-dashboard_details.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a><a href="logout.php" class="btn btn-danger text-center"> <i class="fa fa-sign-out" style="font-size:16px"></i>Logout</a></span>
        <?php
      }
      elseif($val=='d001563085fc35165329ea'){
        ?>
        <button id="reset" class="btn btn-danger text-white"> <i class="fa fa-refresh" style="font-size:14px"></i> Reset</button>
        <a href="admin-dashboard.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>
        <a href="logout.php" class="btn btn-danger text-center">  <i class="fa fa-sign-out" style="font-size:16px"></i>Logout</a></span>
        <?php
      }

      else{
        session_destroy();
        ob_end_flush(); // Flush output buffer and turn off output buffering
        header("Location: index.php");
        exit(); // Terminate the script immediately after redirection
      }

      ?>    
       </h2>
   <hr>
   <div class="row">
    <div class="col-md-12">
     <div class="table-responsive">
        <table id="customersTable" class="table display border table-hover table-stripped" width="100%" cellspacing="0">
            <!-- <table id="customersTable" class="table display nowrap table-hover table-stripped" width="100%" cellspacing="0"> -->
      <thead class="table-success">
        <tr>
            <th>ক্রঃ নং</th>
            <!-- <th>ক্রঃ নং</th> -->
            <th>প্রতিষ্ঠানের নাম</th>
            <th>মামলা নম্বর</th>
            <th>বাদী</th>
            <th>বিবাদী</th>
            <th>মামলার বিষয়বস্তু</th>
            <th>বিচারাধীন মহামান্য আদালত</th>
            <th>নিয়োজিত বিজ্ঞ আইনজীবীর নাম ও ঠিকানা</th>
            <th>মন্ত্রণালয়/দপ্তর /সংস্থা কর্তৃক গৃহীত কার্যক্রম</th>
            <th>মামলার বর্তমান অবস্থা</th> 
                               
          </tr>

                     <!-- <tr>
                    <th>(০2)</th>
                    <th>(০২)</th>
                    <th>(০৩)</th>
                    <th>(০৪)</th>
                    <th>(০৫)</th>
                    <th>(০৬)</th>
                    <th>(০৭)</th>
                    <th>(০৮)</th>
                    <th>(০৯)</th>
                    <th>(১০)</th> 
                               
                </tr> -->
      </thead>
      <tbody></tbody>
      <tfoot>
            <tr>
                <th>ক্রঃ নং</th>
                <!-- <th>ক্রঃ নং</th> -->
                <th>প্রতিষ্ঠানের নাম</th>
                <th>মামলা নম্বর</th>
                 <th>বাদী</th>
                <th>বিবাদী</th>
                <th>মামলার বিষয়বস্তু</th>
                <th>বিচারাধীন আদালত</th>
                <th>নিয়োজিত আইনজীবীর নাম ও ঠিকানা</th>
                <th>মন্ত্রণালয়/দপ্তর /সংস্থা কর্তৃক গৃহীত কার্যক্রম</th>
                <th>মামলার বর্তমান অবস্থা</th> 
            </tr>
        </tfoot>
  </table>
   </div>
    </div>
</div>
</div>
<script src="datatables.js"></script>

<script>
// ----------------------------
$(document).ready(function() {
    var table = $('#customersTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6'B><'col-sm-12 col-md-3'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
            'copy', 'csv', 'excel', {
                extend: 'print',
                text: 'Print',
                customize: function(win) {
                    $(win.document.body)
                        .prepend('<div style="text-align:center;margin-bottom:10px;"><h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3><h6>৩০-৩১ দিলকুশা, বা/এ ঢাকা-১০০০।</h6><h5 class="text-decoration-underline"">বিভিন্ন আদালতে চলমান মামলার তথ্যাবলী</h5><h5 class="text-decoration-none">[--বিসিআইসি মামলার ডাটাবেস--]</h5></div>');
                    $(win.document.body).addClass('landscape');
                    $(win.document).find('title').remove(); // Remove title tag
                }
            },
            'colvis',
            {
                text: 'Reset',
                class: 'btn btn-warning',
                header: true,
                action: function(e, dt, node, config) {
                    $("input[type='text']").val('');
                    dt.columns().visible(true); // Reset column visibility
                    dt.search('').draw(); // Clear search
                }
            },
        ],

        lengthMenu: [
            [ 10, 25, 50, -1],
            [ 10, 25, 50, 'All'],
        ],

        processing: true,
        responsive: true,
        colReorder: true,
        "ajax": "user-fetch.php",

        order: [
            [1, "asc"]
        ],

        initComplete: function() {
            this.api()
                .columns()
                .every(function() {
                    var column = this;
                    var select = $('<select><option value=""></option>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                });
        },
    });

    $(document).on("click", "#reset", function(e) {
        e.preventDefault();
        $("input[type='text']").val('');
        table.columns().visible(true); // Reset column visibility
        table.search('').draw(); // Clear search
    });
});


    </script>
    <!-- <footer>
        <p>This is the footer of your document.</p>
    </footer> -->
 </body>
</html>

<?php } else{
  // header('location:login/logout.php');
   header('location:index.php');
  } 
?>
