<?php
// include('include/header.php');
// error_reporting(0);
 session_start();
 
 include('../db/db_PDO.php');
 include('../include/header_index.php');
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
echo $user_type.'<br>';
echo $table;

?> 
   
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
        <div class="col-sm-6 col-md-4">
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
                <a class="btn btn-primary" href="urea_form.php?username=<?=$_SESSION['username']?>" id="previous"><i class="fa fa-arrow-left"></i> Previous</a>
                <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn"><i class="fa fa-print"></i> Print</button>
                <a class="btn btn-danger" href="logout.php" id="logout"><i class="fa fa-sign-out"></i> Logout</a>
                <a class="btn btn-warning" href="#" id="refersh"><i class="fa fa-reload"></i> Refresh</a>
                <!-- <form class="form-inline" action="download_report_pdf.php" method="post" style="display: inline-block; width: auto;">
                    <button type="submit" name="submit" class="btn btn-danger" id="download_pdf"><i class="fa fa-file-pdf-o"></i> Download</button> 
                </form> -->   
            </span>
        </div>
    </div> 


<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card shadow border border-success">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="table_content">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Name of Buffer</th> 
                                <th>Opening Stock </th>            
                                <th>Receive Import </th>
                                <th>Receive Factory </th>
                                <th>Total Receive </th>
                                
                                <th>Delivery</th>
                                <th>Closing Stock</th>
                                <th>Monthly Demand </th>
                                <th>Monthly Delivery</th>
                                <th>Rest of Delivery</th>
                                <th>Pipeline</th>
                            </tr>
                        </thead>
                        <tbody> 
   
  <?php
    include('../db/db.php');
    // $date = date('Y-m-d');
    // $month11=date('m',strtotime($date));
    // $year11=date('Y',strtotime($date));

    // if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
    //   $year22=$year11;
    // }
    // else{
    //   $year22=$year11-1;
    // }

    // $yearrange12="$year22-07-01";
    // $year22=$year22+1;
    // $yearrange13="$year22-06-30";
 


if (isset($_POST['hit'])) {
    // Sanitize the input
    $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $_SESSION['date'] = $date;

     $selectQueries = "SELECT {$table}.*, demand_amount 
                            FROM {$table} 
                            JOIN monthly_demand ON {$table}.buffer_name = monthly_demand.office_name 
                            WHERE {$table}.`date` LIKE '{$date}%' 
                            ";
    $results = mysqli_query($conn, $selectQueries);                            
     
if(mysqli_num_rows($results) > 0){
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

        // $total_stock = 0;
        // $total_yearly = 0;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-uppercase"><?php echo htmlspecialchars($row['buffer_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $opening_stock; ?></td>
                
                <td><?php echo (int)$row['receive_import']; ?></td>
                <td><?php echo (int)$row['receive_factory']; ?></td>
                <td><?php echo (int)$row['receive_import'] + (int)$row['receive_factory']; ?></td>
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
              <td>Total :</td>
              <td>' . $total_daily . '</td>
              <td>' . $total_monthly . '</td>
              <td>' . $total_yearly . '</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              </tr>';
    } else {
        echo "<p class='text-center text-danger'><b>No Record Found!!!</b></p>";
    }
}

// hit else part
else { 

$date = date('Y-m-d');
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

  $selectQueries = "SELECT {$table}.*, demand_amount 
                            FROM {$table} 
                            JOIN monthly_demand ON {$table}.buffer_name = monthly_demand.office_name 
                            WHERE {$table}.`date` LIKE '{$date}%' 
                            ";
    $results = mysqli_query($conn, $selectQueries);                            

    // Check if there are results
    // if (count($results) > 0) {      
if(mysqli_num_rows($results) > 0){
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

        // $total_stock = 0;
        // $total_yearly = 0;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-uppercase"><?php echo htmlspecialchars($row['buffer_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $opening_stock; ?></td>
                
                <td><?php echo (int)$row['receive_import']; ?></td>
                <td><?php echo (int)$row['receive_factory']; ?></td>
                <td><?php echo (int)$row['receive_import'] + (int)$row['receive_factory']; ?></td>
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
              <td>Total :</td>
              <td>' . $total_daily . '</td>
              <td>' . $total_monthly . '</td>
              <td>' . $total_yearly . '</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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
</div> 


    </div>
    
    <div class="col-sm-0">
      <!--<h3><a href="report_view.php">View Report</a></h3>-->
    </div>
  </div>
 
</div>

<?php
include('../include/footer.php');
?>
