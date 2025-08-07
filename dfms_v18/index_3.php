<?php
// include('include/header.php');
error_reporting(0);
session_start();
// $date = date('Y-m-d', strtotime('-1 day'));
$date = date('Y-m-d');
include('db/db_PDO.php');
include('include/header_index.php');
?>  
<div class="container-fluid">
<div class="container-fluid border border-info rounded shadow-lg " id="print-content">  
    <div class="row my-4">  
        <div class="col-sm-3 col-md-3">
            <form class="d-flex align-items-center" action="" method="post">
                <div class="form-group me-3">
                    <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="search-btn" name="hit">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <div class="col-sm-6 col-md-6">
            <h3 class="text-muted text-center my-0 text-uppercase fw-bold no-wrap itim-regular"><b> Bangladesh Chemical Industries Corporation </b></h3>
            <h4 class="text-success text-center my-1 text-uppercase fw-bold akaya-kanadaka-regular"> Daily Production & Plant Status Report</h4>
            
            <?php 
           if (isset($_POST['hit'])) {
            //$_SESSION['date']=$_POST['date'];
            ?>
            <h6 class="text-dark text-center my-0 "><b>Production as on: <?php echo date('d-m-Y', strtotime($_POST['date'])); ?> </b></h6>
            <h6 class="text-dark text-center my-0 "><b>Dated on: <?php echo date('d-m-Y');?> </b></h6>
            <?php
            }else{                
             ?>
            <h6 class="text-dark text-center my-0 "><b>Production as on: <?php echo date('d-m-Y', strtotime('-1 day')); ?> </b></h6>
            <h6 class="text-dark text-center my-0 mb-0"><b>Dated on: <?php echo date('d-m-Y');?> </b></h6>
            <?php }?>
        </div>  
        <div class="col-sm-3  col-md-3 text-end my-0">     
            <span class="text-center">               
            <a class="btn btn-primary" id="reload-btn" href="index.php"><i class="fa fa-refresh"></i> Reload</a>                    
            <a class="btn btn-primary" id="login-btn" href="controller/dashboard.php"><i class="fa fa-sign-in"></i> Login</a>
                <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn"><i class="fa fa-print"></i> Print</button>               
                <!-- <form class="form-inline" action="download_report_pdf.php" method="post" style="display: inline-block; width: auto;">
                    <button type="submit" name="submit" class="btn btn-danger" id="download_pdf"><i class="fa fa-file-pdf-o"></i> Download</button> 
                </form> -->   
            </span>
        </div>
    </div> 
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<div class="row ">
    <div class="col-sm-12">
        <div class="card shadow border border-muted rounded my-0 mb-0 nowrap">
            <!-- <div class="card-header"></div> -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="table_content">
                        <thead class="table-primary">                          
                            <tr>
                                <th>#</th>
                                <th>Factory Name</th>  
                                 <th>Product</th>  
                                <th>Yearly Installed Capacity(MT)</th>
                                <th>Attainable Production Capacity(MT)</th>          
                                <th>Daily (MT)</th>
                                <th>Monthly (MT)</th>
                                <th>Yearly (MT)</th>
                                <th>Production Target (MT)</th>
                                <th>Due (MT)</th>
                                <th>Monthly Target (MT)</th>
                                <th>Due (MT)</th>
                                <th>Plant Load (%)</th>
                                <th>Previous Day Production (MT)</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody> 
   
  <?php
    include('db/db.php');
    // $today= date('Y-m-d');
    $month11=date('m',strtotime($date));
    $year11=date('Y',strtotime($date));
    if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
      $year22=$year11;
    }
    else{
      $year22=$year11-1;
    }
    $yearrange12="$year22-07-01";
    $year22=$year22+1;
    $yearrange13="$year22-06-30";



if (isset($_POST['hit'])) {
    $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $_SESSION['date'] = $date;
    $month_id = date('Y-m', strtotime($date));

    $month11 = date('m', strtotime($date));
    $year11 = date('Y', strtotime($date));

    if ($month11 >= 7 && $month11 <= 12) {
        $year22 = $year11;
    } else {
        $year22 = $year11 - 1;
    }

    $yearrange12 = "$year22-07-01";
    $yearrange13 = ($year22 + 1) . "-06-30";

    $tables = ['gpfplc', 'sfcl', 'jfcl', 'cufl', 'afccl'];  // Array of table names
    $i = 1; // Initialize $i outside the tables loop to keep it consistent across all tables

    // Initialize total variables for calculating the sum of columns
    $total_installed_capacity = 0;
    $total_attain_capacity = 0;
    $total_daily = 0;
    $total_month_m = 0;
    $total_month_y = 0;
    $total_year_target = 0;
    $total_month_target = 0;

    // Loop through each table in $tables array
    foreach ($tables as $table) {
        // Fetch data for the current table
        $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
        $result_check = mysqli_query($conn, $sql_check);

        // Collect data for rowspan calculation
        $data = [];
        while ($row = mysqli_fetch_assoc($result_check)) {
            $data[$row['factory_name']][] = $row;
        }

        foreach ($data as $factory_name => $rows) {
            $rowspan = count($rows); // Calculate how many rows for this factory
            $is_first_row = true; // To track the first row for each factory

            foreach ($rows as $row) {
                $daily = $row['daily'];
                $month_code = $row['month_code'];
                $year_code = $row['year_code'];
                $product_produce = $row['product_produce'];

                // Collect yearly target
                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                $result_year = mysqli_query($conn, $sql_year);
                $row_year = mysqli_fetch_assoc($result_year);
                $year_target = $row_year['target'];

                // Collect monthly target
                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                $result_month = mysqli_query($conn, $sql_month);
                $row_month = mysqli_fetch_assoc($result_month);
                $month_target = $row_month['target'];

                // Monthly calculation
                $sql_m = "SELECT * FROM $table 
                          WHERE date LIKE '$month_id%' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_m = mysqli_query($conn, $sql_m);

                $month_m = 0;
                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                    $month_m += (float)$row_m['daily'];
                }

                // Yearly calculation
                $sql_y = "SELECT * FROM $table 
                          WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_y = mysqli_query($conn, $sql_y);

                $month_y = 0;
                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                    $month_y += (float)$row_y['daily'];
                }

                // Update total variables
                $total_installed_capacity += (int)$row['installed_capacity'];
                $total_attain_capacity += (int)$row['attain_capacity'];
                $total_daily += $daily;
                $total_month_m += $month_m;
                $total_month_y += $month_y;
                $total_year_target += $year_target;
                $total_month_target += $month_target;
                ?>
                <tr>
                    <?php if ($is_first_row): ?>
                        <td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo $i++; ?>
                        </td>

                        <td class="text-uppercase text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                    <?php endif; ?>
                    <td class="text-uppercase"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <td><?php echo (int)$row['attain_capacity']; ?></td>
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <td><?php echo $month_target - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $month_m - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                $is_first_row = false; // After the first row, do not display the factory_name again
            }
        }
    }

    // After processing all tables, display the total row for each column
echo '<tr>';
echo '<td colspan="2" class="text-center"><strong>Total</strong></td>';
echo '<td style="font-weight: bold;">' . htmlspecialchars($product_produce, ENT_QUOTES, 'UTF-8') . '</td>';
 // Corrected the echo for product_produce

echo '<td>' . $total_installed_capacity . '</td>';
echo '<td>' . $total_attain_capacity . '</td>';
echo '<td>' . $total_daily . '</td>';
echo '<td>' . $total_month_m . '</td>';
echo '<td>' . $total_month_y . '</td>';
echo '<td>' . $total_year_target . '</td>';
echo '<td>' . ($total_year_target - $total_month_y) . '</td>';
echo '<td>' . $total_month_target . '</td>';
echo '<td>' . ($total_month_target - $total_daily) . '</td>';
echo '<td></td>'; // Empty column for plant_load
echo '<td>' . ($total_month_m - $total_daily) . '</td>';
echo '<td></td>'; // Empty column for remarks
echo '</tr>';

$tables1 = ['tspcl','dapfcl','kpml','cccl','ugsf'];  

    foreach ($tables1 as $table1) {
      
        $sql_check = "SELECT * FROM $table1 WHERE date = '$date' ORDER BY factory_name";
        $result_check = mysqli_query($conn, $sql_check);

    
        $data1 = [];
        while ($row = mysqli_fetch_assoc($result_check)) {
            $data1[$row['factory_name']][] = $row;
        }

        foreach ($data1 as $factory_name => $rows) {
            $rowspan = count($rows); 
            $is_first_row = true; 

            foreach ($rows as $row) {
                $daily = $row['daily'];
                $month_code = $row['month_code'];
                $year_code = $row['year_code'];
                $product_produce = $row['product_produce'];

                // Collect yearly target
                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                $result_year = mysqli_query($conn, $sql_year);
                $row_year = mysqli_fetch_assoc($result_year);
                $year_target = $row_year['target'];

                // Collect monthly target
                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                $result_month = mysqli_query($conn, $sql_month);
                $row_month = mysqli_fetch_assoc($result_month);
                $month_target = $row_month['target'];

                // Monthly calculation
                $sql_m = "SELECT * FROM $table1 
                          WHERE date LIKE '$month_id%' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_m = mysqli_query($conn, $sql_m);

                $month_m = 0;
                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                    $month_m += (float)$row_m['daily'];
                }

                // Yearly calculation
                $sql_y = "SELECT * FROM $table1 
                          WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_y = mysqli_query($conn, $sql_y);

                $month_y = 0;
                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                    $month_y += (float)$row_y['daily'];
                }

                // Update total variables
                $total_installed_capacity += (int)$row['installed_capacity'];
                $total_attain_capacity += (int)$row['attain_capacity'];
                $total_daily += $daily;
                $total_month_m += $month_m;
                $total_month_y += $month_y;
                $total_year_target += $year_target;
                $total_month_target += $month_target;
                ?>
                <tr>
                    <?php if ($is_first_row): ?>
                        <td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo $i++; ?>
                        </td>

                        <td class="text-uppercase text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                    <?php endif; ?>
                    <td class="text-uppercase"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <td><?php echo (int)$row['attain_capacity']; ?></td>
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <td><?php echo $month_target - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $month_m - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                $is_first_row = false; 
            }
        }
    }

/////only for bisf
   $sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name";
    $result_check = mysqli_query($conn, $sql_check);

    // Collect data for rowspan calculation
    $data = [];
    while ($row = mysqli_fetch_assoc($result_check)) {
        $data[$row['factory_name']][] = $row;
    }


    foreach ($data as $factory_name => $rows) {
        $rowspan = count($rows); // Calculate how many rows for this factory
        $is_first_row = true; // To track the first row for each factory

        foreach ($rows as $row) {
            $daily = $row['daily'];
            $month_code = $row['month_code'];
            $year_code = $row['year_code'];
            $product_produce = $row['product_produce'];

            // Collect yearly target
            $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
            $result_year = mysqli_query($conn, $sql_year);
            $row_year = mysqli_fetch_assoc($result_year);
            $year_target = $row_year['target'];

            // Collect monthly target
            $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
            $result_month = mysqli_query($conn, $sql_month);
            $row_month = mysqli_fetch_assoc($result_month);
            $month_target = $row_month['target'];

            // Monthly calculation
            $sql_m = "SELECT * FROM bisf 
                      WHERE date LIKE '$month_id%' 
                      AND date <= '$date' 
                      AND product_produce = '$product_produce'";
            $result_fetch_m = mysqli_query($conn, $sql_m);

            $month_m = 0;
            while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                $month_m += (float)$row_m['daily'];
            }

            // Yearly calculation
            $sql_y = "SELECT * FROM bisf 
                      WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                      AND date <= '$date' 
                      AND product_produce = '$product_produce'";
            $result_fetch_y = mysqli_query($conn, $sql_y);

            $month_y = 0;
            while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                $month_y += (float)$row_y['daily'];
            }
            ?>
            <tr>
                <?php if ($is_first_row): ?>
<td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
    <?php echo $i++; ?>
</td>

                    <td class="text-uppercase text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                        <?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                <?php endif; ?>
                <td class="text-uppercase"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo (int)$row['installed_capacity']; ?></td>
                <td><?php echo (int)$row['attain_capacity']; ?></td>
                <td><?php echo $daily; ?></td>
                <td><?php echo $month_m; ?></td>
                <td><?php echo $month_y; ?></td>
                <td><?php echo $year_target; ?></td>
                <td><?php echo $year_target - $month_y; ?></td>
                <td><?php echo $month_target; ?></td>
                <td><?php echo $month_target - $daily; ?></td>
                <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $month_m - $daily; ?></td>
                <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php
            $is_first_row = false; // After the first row, do not display the factory_name again
        }
    }

}
else{


        //$date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $_SESSION['date'] = $date;
    $month_id = date('Y-m', strtotime($date));

    $month11 = date('m', strtotime($date));
    $year11 = date('Y', strtotime($date));

    if ($month11 >= 7 && $month11 <= 12) {
        $year22 = $year11;
    } else {
        $year22 = $year11 - 1;
    }

    $yearrange12 = "$year22-07-01";
    $yearrange13 = ($year22 + 1) . "-06-30";

    $tables = ['gpfplc', 'sfcl', 'jfcl', 'cufl', 'afccl'];  // Array of table names
    $i = 1; // Initialize $i outside the tables loop to keep it consistent across all tables

    // Initialize total variables for calculating the sum of columns
    $total_installed_capacity = 0;
    $total_attain_capacity = 0;
    $total_daily = 0;
    $total_month_m = 0;
    $total_month_y = 0;
    $total_year_target = 0;
    $total_month_target = 0;

    // Loop through each table in $tables array
    foreach ($tables as $table) {
        // Fetch data for the current table
        $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
        $result_check = mysqli_query($conn, $sql_check);

        // Collect data for rowspan calculation
        $data = [];
        while ($row = mysqli_fetch_assoc($result_check)) {
            $data[$row['factory_name']][] = $row;
        }

        foreach ($data as $factory_name => $rows) {
            $rowspan = count($rows); // Calculate how many rows for this factory
            $is_first_row = true; // To track the first row for each factory

            foreach ($rows as $row) {
                $daily = $row['daily'];
                $month_code = $row['month_code'];
                $year_code = $row['year_code'];
                $product_produce = $row['product_produce'];

                // Collect yearly target
                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                $result_year = mysqli_query($conn, $sql_year);
                $row_year = mysqli_fetch_assoc($result_year);
                $year_target = $row_year['target'];

                // Collect monthly target
                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                $result_month = mysqli_query($conn, $sql_month);
                $row_month = mysqli_fetch_assoc($result_month);
                $month_target = $row_month['target'];

                // Monthly calculation
                $sql_m = "SELECT * FROM $table 
                          WHERE date LIKE '$month_id%' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_m = mysqli_query($conn, $sql_m);

                $month_m = 0;
                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                    $month_m += (float)$row_m['daily'];
                }

                // Yearly calculation
                $sql_y = "SELECT * FROM $table 
                          WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_y = mysqli_query($conn, $sql_y);

                $month_y = 0;
                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                    $month_y += (float)$row_y['daily'];
                }

                // Update total variables
                $total_installed_capacity += (int)$row['installed_capacity'];
                $total_attain_capacity += (int)$row['attain_capacity'];
                $total_daily += $daily;
                $total_month_m += $month_m;
                $total_month_y += $month_y;
                $total_year_target += $year_target;
                $total_month_target += $month_target;
                ?>
                <tr>
                    <?php if ($is_first_row): ?>
                        <td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo $i++; ?>
                        </td>

                        <td class="text-uppercase text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                    <?php endif; ?>
                    <td class="text-uppercase"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <td><?php echo (int)$row['attain_capacity']; ?></td>
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <td><?php echo $month_target - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $month_m - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                $is_first_row = false; // After the first row, do not display the factory_name again
            }
        }
    }

    // After processing all tables, display the total row for each column
echo '<tr>';
echo '<td colspan="2" class="text-center"><strong>Total</strong></td>';
echo '<td style="font-weight: bold;">' . htmlspecialchars($product_produce, ENT_QUOTES, 'UTF-8') . '</td>';
 // Corrected the echo for product_produce

echo '<td>' . $total_installed_capacity . '</td>';
echo '<td>' . $total_attain_capacity . '</td>';
echo '<td>' . $total_daily . '</td>';
echo '<td>' . $total_month_m . '</td>';
echo '<td>' . $total_month_y . '</td>';
echo '<td>' . $total_year_target . '</td>';
echo '<td>' . ($total_year_target - $total_month_y) . '</td>';
echo '<td>' . $total_month_target . '</td>';
echo '<td>' . ($total_month_target - $total_daily) . '</td>';
echo '<td></td>'; // Empty column for plant_load
echo '<td>' . ($total_month_m - $total_daily) . '</td>';
echo '<td></td>'; // Empty column for remarks
echo '</tr>';

$tables1 = ['tspcl','dapfcl','kpml','cccl','ugsf'];  

    foreach ($tables1 as $table1) {
      
        $sql_check = "SELECT * FROM $table1 WHERE date = '$date' ORDER BY factory_name";
        $result_check = mysqli_query($conn, $sql_check);

    
        $data1 = [];
        while ($row = mysqli_fetch_assoc($result_check)) {
            $data1[$row['factory_name']][] = $row;
        }

        foreach ($data1 as $factory_name => $rows) {
            $rowspan = count($rows); 
            $is_first_row = true; 

            foreach ($rows as $row) {
                $daily = $row['daily'];
                $month_code = $row['month_code'];
                $year_code = $row['year_code'];
                $product_produce = $row['product_produce'];

                // Collect yearly target
                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                $result_year = mysqli_query($conn, $sql_year);
                $row_year = mysqli_fetch_assoc($result_year);
                $year_target = $row_year['target'];

                // Collect monthly target
                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                $result_month = mysqli_query($conn, $sql_month);
                $row_month = mysqli_fetch_assoc($result_month);
                $month_target = $row_month['target'];

                // Monthly calculation
                $sql_m = "SELECT * FROM $table1 
                          WHERE date LIKE '$month_id%' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_m = mysqli_query($conn, $sql_m);

                $month_m = 0;
                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                    $month_m += (float)$row_m['daily'];
                }

                // Yearly calculation
                $sql_y = "SELECT * FROM $table1 
                          WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_y = mysqli_query($conn, $sql_y);

                $month_y = 0;
                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                    $month_y += (float)$row_y['daily'];
                }

                // Update total variables
                $total_installed_capacity += (int)$row['installed_capacity'];
                $total_attain_capacity += (int)$row['attain_capacity'];
                $total_daily += $daily;
                $total_month_m += $month_m;
                $total_month_y += $month_y;
                $total_year_target += $year_target;
                $total_month_target += $month_target;
                ?>
                <tr>
                    <?php if ($is_first_row): ?>
                        <td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo $i++; ?>
                        </td>

                        <td class="text-uppercase text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                    <?php endif; ?>
                    <td class="text-uppercase"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <td><?php echo (int)$row['attain_capacity']; ?></td>
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <td><?php echo $month_target - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $month_m - $daily; ?></td>
                    <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                $is_first_row = false; 
            }
        }
    }

/////only for bisf
   $sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name";
    $result_check = mysqli_query($conn, $sql_check);

    // Collect data for rowspan calculation
    $data = [];
    while ($row = mysqli_fetch_assoc($result_check)) {
        $data[$row['factory_name']][] = $row;
    }


    foreach ($data as $factory_name => $rows) {
        $rowspan = count($rows); // Calculate how many rows for this factory
        $is_first_row = true; // To track the first row for each factory

        foreach ($rows as $row) {
            $daily = $row['daily'];
            $month_code = $row['month_code'];
            $year_code = $row['year_code'];
            $product_produce = $row['product_produce'];

            // Collect yearly target
            $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
            $result_year = mysqli_query($conn, $sql_year);
            $row_year = mysqli_fetch_assoc($result_year);
            $year_target = $row_year['target'];

            // Collect monthly target
            $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
            $result_month = mysqli_query($conn, $sql_month);
            $row_month = mysqli_fetch_assoc($result_month);
            $month_target = $row_month['target'];

            // Monthly calculation
            $sql_m = "SELECT * FROM bisf 
                      WHERE date LIKE '$month_id%' 
                      AND date <= '$date' 
                      AND product_produce = '$product_produce'";
            $result_fetch_m = mysqli_query($conn, $sql_m);

            $month_m = 0;
            while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                $month_m += (float)$row_m['daily'];
            }

            // Yearly calculation
            $sql_y = "SELECT * FROM bisf 
                      WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                      AND date <= '$date' 
                      AND product_produce = '$product_produce'";
            $result_fetch_y = mysqli_query($conn, $sql_y);

            $month_y = 0;
            while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                $month_y += (float)$row_y['daily'];
            }
            ?>
            <tr>
                <?php if ($is_first_row): ?>
<td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
    <?php echo $i++; ?>
</td>

                    <td class="text-uppercase text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                        <?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                <?php endif; ?>
                <td class="text-uppercase"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo (int)$row['installed_capacity']; ?></td>
                <td><?php echo (int)$row['attain_capacity']; ?></td>
                <td><?php echo $daily; ?></td>
                <td><?php echo $month_m; ?></td>
                <td><?php echo $month_y; ?></td>
                <td><?php echo $year_target; ?></td>
                <td><?php echo $year_target - $month_y; ?></td>
                <td><?php echo $month_target; ?></td>
                <td><?php echo $month_target - $daily; ?></td>
                <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $month_m - $daily; ?></td>
                <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php
            $is_first_row = false; // After the first row, do not display the factory_name again
        }
    }



}




?>
    </tbody>
</table>
</div>
</div>
<div class="card-footer text-end text-muted mb-0"><i>Design & Developed By ICT Division, BCIC.</i></div>
</div><br>  
</div> 
 
</div> 
</div>
</div>
</div>
<?php
include('include/footer.php');
?>
