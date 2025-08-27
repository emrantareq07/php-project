<?php
session_name('dfms');
session_start();
include('../include/header_index.php');

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$table = $_SESSION['username'];

if ($table == 'sfcl') {
    $table1 = 'Shahjalal Fertilizer Company Ltd. (SFCL)';
} elseif ($table == 'jfcl') {
    $table1 = 'Jamuna Fertilizer Company Ltd. (JFCL)';
} elseif ($table == 'afccl') {
    $table1 = 'Ashuganj Fertilizer Company Ltd. (AFCCL)';
} elseif ($table == 'gpfplc') {
    $table1 = 'Ghorashal Polash Fertilizer PLC (GPFPLC)';
} elseif ($table == 'cufl') {
    $table1 = 'Chittagong Urea Fertilizer Ltd. (CUFL)';
} elseif ($table == 'tspcl') {
    $table1 = 'TSP Complex Limited (TSPCL)';
} elseif ($table == 'dapfcl') {
    $table1 = 'DAP Fertilizer Company Limited (DAPFCL)';
} elseif ($table == 'bisf') {
    $table1 = 'Bangladesh Industrial Salt Factory (BISF)';
} elseif ($table == 'cccl') {
    $table1 = 'Chatak Cement Company Limited (CCCL)';
} elseif ($table == 'ugsf') {
    $table1 = 'Osmania Glass Sheet Factory Limited (UGSFL)';
} elseif ($table == 'kpml') {
    $table1 = 'Karnaphuli Paper Mills Limited (KPML)';
}

if (isset($_POST['hit'])) {
    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date'] = $_POST['end_date'];
}
?>

<style>
/* Hide elements by default */
.print-only {
    display: none;
    text-align: center;
    margin-bottom: .5rem;
}

/* Print styles */
@media print {
    body {
        margin: 0;
        padding: 0;
    }

    /* Show print header */
    .print-only {
        display: block !important;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    /* Hide buttons */
    #search-btn, #login-btn, #print-btn, #logout {
        display: none !important;
    }

    /* Remove borders and shadow in print */
    #print-content, 
    #print-content .card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Reduce top margin for print */
    #print-content {
        margin-top: 5px;
    }

    /* Optional: reduce spacing in table */
    #table_content {
        font-size: 12px;
    }
}
</style>

<div class="container-fluid">
    <div class="container-fluid border border-info rounded shadow-lg" id="print-content">  
        <div class="row my-2 align-items-center">  
            <!-- Search Form -->
            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <form class="row g-2 align-items-center" action="" method="post">
                    <div class="col-12 col-sm-6">
                        <input type="date" class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <input type="date" class="form-control" name="end_date" id="end_date" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100 w-sm-auto" id="search-btn" name="hit">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>     
            </div>

            <!-- Action Buttons -->
            <div class="col-12 col-md-6 text-md-end text-center mt-2 mt-md-0">     
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                    <a class="btn btn-primary" id="login-btn" href="urea_form.php">
                        <i class="fa fa-arrow-left"></i> Previous Page
                    </a> 
                    <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <a class="btn btn-danger" href="logout.php" id="logout">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </div>
            </div>
        </div> 

<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php if(isset($_POST['hit'])): ?>
    <div class="print-only">
        <img src="../images/logo.png" alt="Factory Logo" style="height:80px;"><br>
        <h3><?php echo $table1; ?></h3>
        <p>
            Date Range: <?php echo htmlspecialchars($_POST['start_date']); ?> 
            to <?php echo htmlspecialchars($_POST['end_date']); ?>
        </p>
    </div>
<?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase text-muted">
                            <b>Daily Production & Plant Status Report</b>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center small" id="table_content">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Factory</th>        
                                        <th>Daily (MT)</th>
                                        <th>Monthly (MT)</th>
                                        <th>Yearly (MT)</th>
                                        <th>Production Target (MT)</th>
                                        <th>Due (MT)</th>
                                        <th>Monthly Target (MT)</th>
                                        <th>Due (MT)</th>
                                        <th>Plant Load (%)</th>                             
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody> 
            <?php
            include('../db/db.php');

            if(isset($_POST['hit'])){
                
                $start_date=$_POST['start_date'];
                $end_date=$_POST['end_date'];
                
                if($start_date > $end_date){
                  //echo'Plz enter Running Fiscal Year';
                  echo "<span class='text-danger text-center'><b>Date Incorrect!!!</b></span>";
              
                }
             
                $month=date('m',strtotime($start_date));
                $year=date('Y',strtotime($start_date));

                if($month==7 || $month==8 || $month==9 || $month==10 || $month==11 || $month==12 ){
                  $year1=$year;
                }
                else{
                  $year1=$year-1;
                }
                $xyz=$year1;
                $yearrange12="$year1-07-01";
                $year1=$year1+1;
                $yearrange13="$year1-06-30";

                $month11=date('m',strtotime($end_date));
                $year11=date('Y',strtotime($end_date));

                if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
                  $year22=$year11;
                }
                else{
                  $year22=$year11-1;
                }
                if($xyz!=$year22){
                  //echo'Plz enter Running Fiscal Year';
                  echo "<span class='text-danger text-center'><b>Plz Enter Running Fiscal Year!!!</b></span>";  
                }

                elseif ($table == 'bisf') {
                $i = 1;
                $sql_check1 = "SELECT DISTINCT date FROM bisf WHERE date BETWEEN '$start_date' AND '$end_date'";
                $result_check1 = mysqli_query($conn, $sql_check1);
                while ($row_check1 = mysqli_fetch_assoc($result_check1)) {
                    $date = $row_check1['date'];
                    echo $date;
                    $month_id = date('Y-m', strtotime($date));
                    $sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name";
                    $result_check = mysqli_query($conn, $sql_check);
                    // Collect rows for the current date
                    $rows = [];
                    while ($row = mysqli_fetch_assoc($result_check)) {
                        $rows[] = $row;
                    }

                    $rowspan = count($rows); 
                    $is_first_row = true;
                    foreach ($rows as $row) {
                        $daily = $row['daily'];
                        $month_code = $row['month_code'];
                        $year_code = $row['year_code'];
                        $product_produce = $row['product_produce'];
                        $factory_name=$row['factory_name'];

                        if ($product_produce == "sanitary") {
                            $row['installed_capacity'] = $row['sanitary_installed_capacity'];
                        } elseif ($product_produce == "insulator") {
                            $row['installed_capacity'] = $row['insulator_installed_capacity'];
                        } elseif ($product_produce == "refractories") {
                            $row['installed_capacity'] = $row['refractories_installed_capacity'];
                        }

                        // Collect yearly target
                        $sql_year = "SELECT target FROM target_table WHERE id = '$year_code'";
                        $result_year = mysqli_query($conn, $sql_year);
                        $row_year = mysqli_fetch_assoc($result_year);
                        $year_target = $row_year['target'];

                        // Collect monthly target
                        $sql_month = "SELECT target FROM monthly_target WHERE id = '$month_code'";
                        $result_month = mysqli_query($conn, $sql_month);
                        $row_month = mysqli_fetch_assoc($result_month);
                        $month_target = $row_month['target'];

                        // Monthly calculation
                        $sql_m = "SELECT daily FROM bisf 
                                  WHERE date LIKE '$month_id%' 
                                  AND date <= '$date' 
                                  AND product_produce = '$product_produce'";
                        $result_fetch_m = mysqli_query($conn, $sql_m);
                        $month_m = 0;
                        while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                            // $month_m += (float)$row_m['daily'];
                            $month_m = round($month_m + (float)$row_m['daily'], 2);
                        }
                        // Yearly calculation
                        $sql_y = "SELECT daily FROM bisf 
                                  WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                                  AND date <= '$date' 
                                  AND product_produce = '$product_produce'";
                        $result_fetch_y = mysqli_query($conn, $sql_y);
                        $month_y = 0;
                        while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                            // $month_y += (float)$row_y['daily'];
                            $month_y += round((float)$row_y['daily'], 2);
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
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $daily; ?></td>
                            <td><?php echo $month_m; ?></td>
                            <td><?php echo $month_y; ?></td>
                            <td><?php echo $year_target; ?></td>
                            <td><?php echo $year_target - $month_y; ?></td>
                            <td><?php echo $month_target; ?></td>
                            <td><?php echo $month_target - $daily; ?></td>
                            <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php
                        $is_first_row = false; // Reset after the first row
                    }
                }  
            }
                else{
                //for all
                $i = 1;
                $sql_check1 = "SELECT date FROM $table WHERE date BETWEEN '$start_date' AND '$end_date'";
                $result_check1 = mysqli_query($conn, $sql_check1);

                while ($row_check1 = mysqli_fetch_assoc($result_check1)) {
                    $date = $row_check1['date'];       
                    $month_id = date('Y-m', strtotime($date));
                $sql_check = "SELECT * FROM $table WHERE date = '$date' ";
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
                            // $month_m += (float)$row_m['daily'];
                            $month_m = round($month_m + (float)$row_m['daily'], 2);
                        }
                        // Yearly calculation
                        $sql_y = "SELECT * FROM $table 
                                  WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                                  AND date <= '$date' 
                                  AND product_produce = '$product_produce'";
                        $result_fetch_y = mysqli_query($conn, $sql_y);
                        $month_y = 0;
                        while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                            // $month_y += (float)$row_y['daily'];
                            $month_y += round((float)$row_y['daily'], 2);
                         }
                        ?>
                        <tr>                
                        <?php if ($is_first_row): ?>
                        <td class="text-center align-middle" rowspan="<?php echo $rowspan; ?>">
                            <?php echo $i++; ?>
                        </td>
                        <?php endif; ?>
                            <td class="text-uppercase text-center align-middle" rowspan="<?php //echo $rowspan; ?>">
                                <?php echo htmlspecialchars($row['factory_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                           <td><?php echo $row['date']; ?></td>
                            <td><?php echo $daily; ?></td>
                            <td><?php echo $month_m; ?></td>
                            <td><?php echo $month_y; ?></td>
                            <td><?php echo $year_target; ?></td>
                            <td><?php echo $year_target - $month_y; ?></td>
                            <td><?php echo $month_target; ?></td>
                            <td><?php echo $month_target - $daily; ?></td>
                            <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>   
                            <td><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php
                        $is_first_row = false;
                        }
                    }
                } //for year range

              }//for else   

             }  //for hit      

            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end text-muted">
                        <i>Design & Developed By ICT Division, BCIC.</i>
                    </div>
                </div>  
            </div> 
        </div>
    </div>
</div>

<?php
include('../include/footer.php');
?>
