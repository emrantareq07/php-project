<?php
// include('include/header.php');
// error_reporting(0);
 session_start();
 
 include('../db/db_PDO.php');
 include('../include/header_index.php');
// $username=$_SESSION['username'];
// $user_type=$_SESSION['user_type'];
// echo $user_type;
// $date1;
?> 
<style>
    @media print {
        body {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .container {
            width: 100%;
        }
        .table {
            width: 100%;
            table-layout: fixed; /* Fixed table layout */
        }
        .table th, .table td {
            word-wrap: break-word; /* Break words to fit within the cell */
            overflow: hidden; /* Hide overflow */
        }
        .btn, .form-inline {
            display: none; /* Hide buttons and footer */
        }
    }
</style>
   
<div class="container">  
    <div class="row my-2">  
        <div class="col-sm-3 col-md-4 text-center">
            <form class="form-inline" action="" method="post">
                <div class="form-group">
                    <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" required>
                </div>
                <div class="form-group mx-sm-3">
                    <button type="submit" class="btn btn-primary" id="search-btn" name="hit">Search</button>
                </div>                
            </form>     
        </div>
        <div class="col-sm-6 col-md-4" id="title-header">
            
            <h4 class="text-success text-center my-1 text-uppercase"><b>Fertilizer Daily Statement </b></h4>
        </div>  
        <div class="col-sm-12  col-md-4 text-end">     
            <span class="text-center">

                <?php 

                // if($_SESSION['user_type']=='user'){

                    ?>
                    <!-- <a class="btn btn-primary" id="login-btn" href="controller/dashboard.php">Login</a> -->

                    
                    <?php
                    // }   
                    ?>

           <!--  <div class="btn-group ">
             <a class="nav-link dropdown-toggle btn btn-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Factory Name
                </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="urea_report_with_date_range.php?val=sfcl">SFCL</a></li>
              <li><a class="dropdown-item" href="urea_report_with_date_range.php?val=jfcl">JFCL</a></li>
              <li><a class="dropdown-item" href="urea_report_with_date_range.php?val=afccl">AFCCL</a></li>
               <li><a class="dropdown-item" href="urea_report_with_date_range.php?val=cufl">CUFL</a></li>
              <li><a href="urea_report_with_date_range.php?val=gpfplc" class="dropdown-item ">GPFPLC</a></li> -->
             <!--  <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Separated link</a></li> -->
          <!--   </ul>
          </div> -->
                <a class="btn btn-primary" href="user_dashboard.php" id="previous"><i class="fa fa-arrow-left"></i> Previous</a>
                <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn"><i class="fa fa-print"></i> Print</button>
                <a class="btn btn-danger" href="logout.php" id="logout"><i class="fa fa-sign-out"></i> Logout</a>
                <a class="btn btn-warning" href="#" id="refresh"><i class="fa fa-reload"></i> Refresh</a>
                <!-- <form class="form-inline" action="download_report_pdf.php" method="post" style="display: inline-block; width: auto;">
                    <button type="submit" name="submit" class="btn btn-danger" id="download_pdf"><i class="fa fa-file-pdf-o"></i> Download</button> 
                </form> -->   
            </span>
        </div>
    </div> 


<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<div class="row">
    <div class="col-sm-12">
        <!-- <div class="card shadow border border-success">
            <div class="card-header"></div>
            <div class="card-body"> -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="table_content">
                        <thead class="text-success table-dark">
                            <tr>
                                  <div id="print-header" style="text-align:center;margin-bottom:10px; display: none">
                                <h3>Bangladesh Chemical Industries Corporation (BCIC)</h3>
                                <h6>30-31 Dilkusha, B/A Dhaka-1000.</h6>
                                <h5 class="text-decoration-underline">Daily Statement of Urea Fertilizer</h5>
                                <?php //if(isset($_SESSION['date'])) {
                                if (isset($_POST['hit'])) {
                                // Sanitize the input
                                $date2 = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
                                $date1 = date('Y-m-d', strtotime($date2 . ' -1 day')); 

                                 ?>
                                    <h5 class="text-decoration-none">Opening Stock, Receive, Delivery & Closing Stock (Provisional) as On <?php echo $date1; ?></h5>
                                    <h5 class="text-decoration-none">Reporting Date: <?php echo $date2 ;?></h5>
                                <?php }

                                if (!isset($_POST['hit'])) {
                                
                                ?>
                                 <h5 class="text-decoration-none">Opening Stock, Receive, Delivery & Closing Stock (Provisional) as On <?php echo $_SESSION['date']; ?></h5>
                                <h5 class="text-decoration-none">Reporting Date: <?php echo $_SESSION['date_else'];?></h5>

                                <?php
                                    }

                                ?>
                            </div>
                                <!--  <div id="print-header" style="text-align:center;margin-bottom:10px; display: none">
                                <h3>Bangladesh Chemical Industries Corporation (BCIC)</h3>
                                <h6>30-31 Dilkusha, B/A Dhaka-1000.</h6>
                                <h5 class="text-decoration-underline">Daily Statement of Urea Fertilizer</h5>
                                <h5 class="text-decoration-none">Opening Stock, Receive, Delivery & Closing Stock (Provisional) as On <?php echo $_SESSION['date']; ?></h5>
                                <h5 class="text-decoration-none">Reporting Date: <?php echo date('Y-m-d', strtotime($_SESSION['date'] . ' +1 day'));?></h5>
                            </div> -->
                                <th class="text-center">#</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Name of Buffer</th> 
                                <th class="text-center">Opening Stock </th>            
                                <th class="text-center">Receive Import </th>
                                <th class="text-center">Receive Factory </th>
                                <th class="text-center">Total Receive </th>
                                
                                <th class="text-center">Delivery</th>
                                <th class="text-center">Closing Stock</th>
                                <th class="text-center">Monthly Demand </th>
                                <th class="text-center">Monthly Delivery</th>
                                <th class="text-center">Rest of Delivery</th>
                                <th class="text-center">Pipeline</th>
                            </tr>
                        </thead>
                        <tbody class="text-center"> 
   
<?php
include('../db/db.php');
  
if (isset($_POST['hit'])) {
    // Sanitize the input
    $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $date = date('Y-m-d', strtotime($date . ' -1 day'));    
   // $_SESSION['date'] = $date;
//$date = date('Y-m-d', strtotime('-1 day'));
$day=date('d',strtotime($date));

$month=date('m',strtotime($date));

//echo $month;
$year=date('Y',strtotime($date));


if($month==7){
    $month_id=$year.'1';
}
elseif ($month==8){
    $month_id=$year.'2';
}
elseif ($month==9){
    $month_id=$year.'3';
}
elseif ($month==10){
    $month_id=$year.'4';
}
elseif ($month==11){
    $month_id=$year.'5';
}
elseif ($month==12){
    $month_id=$year.'6';
}
elseif ($month==1){
    $month_id=$year.'7';
}
elseif ($month==2){
    $month_id=$year.'8';
}
elseif ($month==3){
    $month_id=$year.'9';
}
elseif ($month==4){
    $month_id=$year.'10';
}
elseif ($month==5){
    $month_id=$year.'11';
}
elseif ($month==6){
    $month_id=$year.'12';
}   

    // Define an array of table names
    // $tables = ['sfcl', 'afccl', 'jfcl'];
    $tables = [];
    $table_distrinct = "SELECT Distinct username from users where office_type='buffer'";
   
    $result_distrinct = mysqli_query($conn, $table_distrinct);  

    while($row1 = mysqli_fetch_assoc($result_distrinct)) {

       $tables[]= $row1['username'];

    }
    //$tables = mysqli_fetch_assoc($result_distrinct);
    $selectQueries = [];
    // Loop through each table to build the SELECT queries
    foreach ($tables as $table) {
        $selectQueries[] = "SELECT {$table}.*, demand_amount 
                            FROM {$table} 
                            JOIN monthly_demand ON {$table}.buffer_name = monthly_demand.office_name 
                            WHERE {$table}.`date` LIKE '{$date}%' 
                            AND monthly_demand.month_id  = '{$month_id}'";
    }

    // Join all SELECT queries with UNION
    $sql = implode(" UNION ", $selectQueries);

    // Append the ORDER BY clause
    // $sql .= " ORDER BY `date` DESC";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are results
    if (count($results) > 0) {   
        $total_opening_stock = 0;
         $total_receive_import = 0;
         $total_receive_factory = 0;
         $total_receive = 0;
         $total_delivery = 0;
         $total_closing_stock = 0;
         $total_monthly_demand = 0;
         $total_monthly_delivery = 0;
         $total_rest_of_delivery = 0;
         $total_pipeline = 0;   
         $sl_no=1;

        // Output data of each row
        foreach ($results as $row) {
            // $total_daily += (int)$row['daily'];
            // $total_monthly += (int)$row['monthly'];
            // $total_yearly += (int)$row['yearly'];
        $buffer_name=$row['buffer_name'];


        $opening_stock = (int)$row['total_stock'] - ((int)$row['receive_import'] + (int)$row['receive_factory'] - (int)$row['delivery']);

        $sql_monthly_demand="SELECT sum(delivery) as rest_of_delivery from $buffer_name where month_id='$month_id' AND date <='$date'";
        $result_monthly_demand = mysqli_query($conn, $sql_monthly_demand);
        $row_monthly_demand = mysqli_fetch_assoc($result_monthly_demand);

        $monthly_delivery=$row_monthly_demand['rest_of_delivery'];
        $rest_of_delivery=$row['demand_amount']-$monthly_delivery;
        $total_receive1=(int)$row['receive_import'] + (int)$row['receive_factory'];

        $total_opening_stock = $total_opening_stock+$opening_stock;
         $total_receive_import = $total_receive_import + (int)$row['receive_import'];
         $total_receive_factory = $total_receive_factory+ (int)$row['receive_factory'];
         $total_receive = $total_receive+$total_receive1;
         $total_delivery = $total_delivery + (int)$row['delivery'];
         $total_closing_stock = $total_closing_stock+(int)$row['total_stock'];
         $total_monthly_demand = $total_monthly_demand + (int)$row['demand_amount'];
         $total_monthly_delivery = $total_monthly_delivery + $monthly_delivery;
         $total_rest_of_delivery = $total_rest_of_delivery + $rest_of_delivery;
         $total_pipeline = $total_pipeline + (int)$row['pipeline'];

        $buffer_parts = explode("_", $buffer_name);
        $buffer_name = $buffer_parts[0];
         
            ?>
            <tr>
                <td><?php echo htmlspecialchars($sl_no ++, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-uppercase"><?php echo htmlspecialchars($buffer_name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $opening_stock; ?></td>
                
                <td><?php echo (int)$row['receive_import']; ?></td>
                <td><?php echo (int)$row['receive_factory']; ?></td>
                <td><?php echo $total_receive1 ?></td>
                <td><?php echo (int)$row['delivery']; ?></td>
                <td><?php echo (int)$row['total_stock']; ?></td>
                <td><?php echo (int)$row['demand_amount']; ?></td>
                <td><?php echo $monthly_delivery; ?></td>
                <td><?php echo $rest_of_delivery; ?></td>
                <td><?php echo (int)$row['pipeline']; ?></td>
               <!--  <td><?php //echo (int)$row['target'] - (int)$row['yearly']; ?></td>
                <td><?php //echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php //echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
 -->            </tr>
            <?php
        }

        // Display the total production row
        echo '<tr>
              <td></td>
              <td></td>
              <td>Total:</td>
              <td>' . $total_opening_stock . '</td>
              <td>' . $total_receive_import . '</td>
              <td>' . $total_receive_factory . '</td>
              <td>' . $total_receive . '</td>
              <td>' . $total_delivery . '</td>
              <td>' . $total_closing_stock . '</td>
              <td>' . $total_monthly_demand . '</td>
              <td>' . $total_monthly_delivery . '</td>
              <td>' . $total_rest_of_delivery . '</td>
              <td>' . $total_pipeline . '</td>
              
              </tr>';
              


    } else {
        
        echo "<p class='text-center text-danger'><b>No Record Found!!!</b></p>";
    }

}


// hit else part
else { 

 $date = date('Y-m-d', strtotime('-1 day')); // Get the date for one day ago
    $_SESSION['date'] = $date;

 $date_else = date('Y-m-d'); // Get the current date
    $_SESSION['date_else'] = $date_else;

$day=date('d',strtotime($date));

$month=date('m',strtotime($date));

//echo $month;
$year=date('Y',strtotime($date));


if($month==7){
    $month_id=$year.'1';
}
elseif ($month==8){
    $month_id=$year.'2';
}
elseif ($month==9){
    $month_id=$year.'3';
}
elseif ($month==10){
    $month_id=$year.'4';
}
elseif ($month==11){
    $month_id=$year.'5';
}
elseif ($month==12){
    $month_id=$year.'6';
}
elseif ($month==1){
    $month_id=$year.'7';
}
elseif ($month==2){
    $month_id=$year.'8';
}
elseif ($month==3){
    $month_id=$year.'9';
}
elseif ($month==4){
    $month_id=$year.'10';
}
elseif ($month==5){
    $month_id=$year.'11';
}
elseif ($month==6){
    $month_id=$year.'12';
}   

    // Define an array of table names
    // $tables = ['sfcl', 'afccl', 'jfcl'];
    $tables = [];
    $table_distrinct = "SELECT Distinct username from users where office_type='buffer'";
   
    $result_distrinct = mysqli_query($conn, $table_distrinct);  

    while($row1 = mysqli_fetch_assoc($result_distrinct)) {

       $tables[]= $row1['username'];

    }
    //$tables = mysqli_fetch_assoc($result_distrinct);
    $selectQueries = [];
    // Loop through each table to build the SELECT queries
    foreach ($tables as $table) {
        $selectQueries[] = "SELECT {$table}.*, demand_amount 
                            FROM {$table} 
                            JOIN monthly_demand ON {$table}.buffer_name = monthly_demand.office_name 
                            WHERE {$table}.`date` LIKE '{$date}%' 
                            AND monthly_demand.month_id  = '{$month_id}'";
    }

    // Join all SELECT queries with UNION
    $sql = implode(" UNION ", $selectQueries);

    // Append the ORDER BY clause
    // $sql .= " ORDER BY `date` DESC";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are results
    if (count($results) > 0) {   
        $total_opening_stock = 0;
         $total_receive_import = 0;
         $total_receive_factory = 0;
         $total_receive = 0;
         $total_delivery = 0;
         $total_closing_stock = 0;
         $total_monthly_demand = 0;
         $total_monthly_delivery = 0;
         $total_rest_of_delivery = 0;
         $total_pipeline = 0;   
         $sl_no=1;

        // Output data of each row
        foreach ($results as $row) {
            // $total_daily += (int)$row['daily'];
            // $total_monthly += (int)$row['monthly'];
            // $total_yearly += (int)$row['yearly'];
        $buffer_name=$row['buffer_name'];


        $opening_stock = (int)$row['total_stock'] - ((int)$row['receive_import'] + (int)$row['receive_factory'] - (int)$row['delivery']);

        $sql_monthly_demand="SELECT sum(delivery) as rest_of_delivery from $buffer_name where month_id='$month_id' AND date <='$date'";
        $result_monthly_demand = mysqli_query($conn, $sql_monthly_demand);
        $row_monthly_demand = mysqli_fetch_assoc($result_monthly_demand);

        $monthly_delivery=$row_monthly_demand['rest_of_delivery'];
        $rest_of_delivery=$row['demand_amount']-$monthly_delivery;
        $total_receive1=(int)$row['receive_import'] + (int)$row['receive_factory'];

        $total_opening_stock = $total_opening_stock+$opening_stock;
         $total_receive_import = $total_receive_import + (int)$row['receive_import'];
         $total_receive_factory = $total_receive_factory+ (int)$row['receive_factory'];
         $total_receive = $total_receive+$total_receive1;
         $total_delivery = $total_delivery + (int)$row['delivery'];
         $total_closing_stock = $total_closing_stock+(int)$row['total_stock'];
         $total_monthly_demand = $total_monthly_demand + (int)$row['demand_amount'];
         $total_monthly_delivery = $total_monthly_delivery + $monthly_delivery;
         $total_rest_of_delivery = $total_rest_of_delivery + $rest_of_delivery;
         $total_pipeline = $total_pipeline + (int)$row['pipeline'];

        $buffer_parts = explode("_", $buffer_name);
        $buffer_name = $buffer_parts[0];
         
            ?>
            <tr>
                <td><?php echo htmlspecialchars($sl_no ++, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-uppercase"><?php echo htmlspecialchars($buffer_name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $opening_stock; ?></td>
                
                <td><?php echo (int)$row['receive_import']; ?></td>
                <td><?php echo (int)$row['receive_factory']; ?></td>
                <td><?php echo $total_receive1 ?></td>
                <td><?php echo (int)$row['delivery']; ?></td>
                <td><?php echo (int)$row['total_stock']; ?></td>
                <td><?php echo (int)$row['demand_amount']; ?></td>
                <td><?php echo $monthly_delivery; ?></td>
                <td><?php echo $rest_of_delivery; ?></td>
                <td><?php echo (int)$row['pipeline']; ?></td>
               <!--  <td><?php //echo (int)$row['target'] - (int)$row['yearly']; ?></td>
                <td><?php //echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php //echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
 -->            </tr>
            <?php
        }

        // Display the total production row
        echo '<tr>
              <td></td>
              <td></td>
              <td>Total:</td>
              <td>' . $total_opening_stock . '</td>
              <td>' . $total_receive_import . '</td>
              <td>' . $total_receive_factory . '</td>
              <td>' . $total_receive . '</td>
              <td>' . $total_delivery . '</td>
              <td>' . $total_closing_stock . '</td>
              <td>' . $total_monthly_demand . '</td>
              <td>' . $total_monthly_delivery . '</td>
              <td>' . $total_rest_of_delivery . '</td>
              <td>' . $total_pipeline . '</td>
              
              </tr>';
              
    } else {
        echo "<p class='text-center text-danger'><b>No Record Found!!!</b></p>";
    }
   
}

             ?>
    </tbody>
</table>
  </div>
  <div class="card-footer text-right"><i>Design & Developed By ICT Division, BCIC.</i></div>
<!-- </div> 


    </div> -->
    
    <div class="col-sm-0">
      <!--<h3><a href="report_view.php">View Report</a></h3>-->
    </div>
  </div>
 
</div>

<?php
include('../include/footer.php');
?>
