<?php
session_name('dfms');
error_reporting(0);
session_start();
//include_once('session_out.php');

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

$date = date('Y-m-d');
//echo $date;

$yesterday = date('Y-m-d', strtotime('-1 day'));
//echo $yesterday;

//$date= date('Y-m-d', strtotime('-1 day', $date));
include('../db/db_PDO.php');
include('../include/header_index.php');
$username=$_SESSION['username'];
$user_type=$_SESSION['user_type'];

?> 

<div class="container-fluid ">
<div class="container-fluid border  rounded shadow-lg " id="print-content">  
   <div id="reload-message">Reloading...</div> 
    <div class="row my-2">  
        <div class="col-sm-3 col-md-3 text-center">
            <form class="form-inline d-flex align-items-center" action="" method="post">
                <div class="form-group">
                    <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" required>
                </div>
                <div class="form-group mx-sm-3">
                    <button type="submit" class="btn btn-primary" id="search-btn" name="hit"><i class="fa fa-search"></i> Search</button>
                </div>                
            </form>     
        </div>

        <div class="col-sm-6 col-md-6 text-center">
            <h3 class="text-muted my-0 text-uppercase fw-bold no-wrap itim-regular text-center">
                <b>Bangladesh Chemical Industries Corporation</b>
            </h3>
            <h5 class="text-success my-1 text-uppercase fw-bold akaya-kanadaka-regular text-center ">
                Daily Production & Plant Status Report
            </h5>
            <?php 
            if (isset($_POST['hit'])) { ?>
                <h6 class="text-dark my-1">
                    <b>Production as On :<?php echo date('d-m-Y', strtotime($_POST['date'])); ?></b>
                </h6>
                <h6 class="text-dark my-1">
                    <b>Dated on : <?php echo date('d-m-Y'); ?></b>
                </h6>
            <?php 
            } else { ?>
                <h6 class="text-dark my-1">
                    <b>Production as On : <?php echo date('d-m-Y', strtotime('-1 day')); ?></b>
                </h6>
                <h6 class="text-dark my-1 mb-0">
                    <b>Dated on: <?php echo date('d-m-Y'); ?></b>
                </h6>
            <?php } ?>
        </div>
 
        <div class="col-sm-12  col-md-3 text-end ">     
            <span class="text-center">
                <?php 
                if($_SESSION['user_type']=='user'){
                    ?>
                <!-- <a class="btn btn-primary" id="login-btn" href="controller/dashboard.php">Login</a>                     -->
                <?php
                    }   
                ?>
            <a class="btn btn-primary" id="reload-btn" href="home.php"><i class="fa fa-refresh"></i> Reload</a> 
            <div class="btn-group ">
             <a class="nav-link dropdown-toggle btn btn-primary text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-list" style="font-size:16px"></i>
                    Factory Name
                </a>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="urea_form.php?val=sfcl&user_type=<?php echo $user_type; ?>">SFCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=jfcl&user_type=<?php echo $user_type; ?>">JFCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=afccl&user_type=<?php echo $user_type; ?>">AFCCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=cufl&user_type=<?php echo $user_type; ?>">CUFL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=gpfplc&user_type=<?php echo $user_type; ?>">GPFPLC</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=tspcl&user_type=<?php echo $user_type; ?>">TSPCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=dapfcl&user_type=<?php echo $user_type; ?>">DAPFCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=bisf&user_type=<?php echo $user_type; ?>">BISF</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=cccl&user_type=<?php echo $user_type; ?>">CCCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=ugsf&user_type=<?php echo $user_type; ?>">UGSF</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=kpml&user_type=<?php echo $user_type; ?>">KPML</a></li>
            </ul>
          </div> 
          <!-- <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn"><i class="fa fa-print"></i> Print</button>      -->
          <a class="btn btn-danger" href="#"  onclick="window.print();return false;" id="print-btn"><i class="fa fa-print"></i> Print</a>
        </span>
            <div class="d-flex justify-content-end  gap-1 my-1">
            <?php if ($user_type == 'sadmin' || $user_type == 'admin') { ?>
                <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                    <button class="btn btn-warning" type="submit" name="submit">
                        <i class="fa fa-download"> Download DB</i> 
                    </button>
                </form>
            <?php } ?> 
        <?php if ($user_type == 'sadmin') { ?>
            <a class="btn btn-info" href="set_name.php" ><i class="fa fa-edit"></i> Set Name
            </a>
 <?php } ?> 
            <button type="button" class="btn btn-success" id="print_ind_tenants_aa">
                <i class="fa fa-print" style="font-size:16px"></i> Print
            </button>          
            <a class="btn btn-danger" href="logout.php" id="logout">
                <i class="fa fa-sign-out"></i> Logout
            </a>            
            
        </div>            
        </div>
    </div> 
<script type="text/javascript">
document.getElementById('print_ind_tenants_aa').addEventListener('click', function () {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea_ind_tenants_aa').innerHTML;

    // Define the title
    var title = `
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
        <img src="bcic_logo.png" alt="BCIC Logo" style="max-width: 60px; margin-right: 20px;">
        <div style="text-align: center;">
            <h5 class="text-uppercase m-0" style="margin-bottom: 5px;">Bangladesh Chemical Industries Corporation</h5>
            <p class="text-uppercase" style="margin-top: 0; margin-bottom: 0px;">Daily Production & Plant Status Report</p>
            <?php if (isset($_POST['hit'])) { ?>
                <p class=" text-center m-0" style="margin-top: 0; margin-bottom: 0;">
                    Production as on: <?php echo date('d-m-Y', strtotime($_POST['date'])); ?>
                </p>
            <?php } else { ?>
                <p class=" text-center m-0" style="margin-top: 0; margin-bottom: 0;">
                    Production as on: <?php echo date('d-m-Y', strtotime('-1 day')); ?>
                </p>
            <?php } ?>
        </div>
    </div>
    `;

    // Store the original content of the page
    var originalContents = document.body.innerHTML;
    // Create a new image element to ensure it's loaded before printing
    var imageElement = new Image();
    imageElement.src = "bcic_logo.png";
    imageElement.onload = function () {
        // Once the image is loaded, update the body with the print content and custom styles
        document.body.innerHTML = `
            <html>
            <head>
                <title>Print Report</title>
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
                <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    @font-face {
                        font-family: 'Nikosh', Times, serif;
                        src: url(Nikosh.ttf);
                    }
                    * {
                        font-family: 'Open Sans', sans-serif;
                        font-family: 'Tiro Bangla', serif;
                        font-family: 'Nikosh', sans-serif;
                    }
                    #edit_btn, #action_t, #action, #status, #status_t, #print_ind_tenants_aa, #print-btn,#footer_id {
                        display: none;
                        visibility: hidden;
                    }
                    @media print {
                        @page {
                            size: A4 landscape; /* Set landscape orientation */
                            margin: 5mm 2mm; /* Custom margins */
                        }
                        html, body {
                            overflow: hidden; /* Hides the scroll bar */
                            margin: 0;
                            padding: 0;
                        }
                        
                         body {
                            margin-top: 1mm; /* Add space for header */
                            padding-top: 0; /* Remove any extra padding */
                        }

                        /* Footer styles */
                        footer {
                            position: fixed;
                            bottom: 0;
                            left: 0;
                            width: 100%;
                            text-align: center;
                            font-size: 10px;
                            margin: 0; /* Remove any margin */
                        }
                        footer::after {
                           content: "Design & Developed by ICT Division, BCIC." 
                        }                      
                    }
                </style>
            </head>
            <body>                
                ${title}
                ${printContents} 
                <footer></footer>
            </body>
            </html>
        `;
        // Trigger the print dialog
        window.print();
        // Restore the original contents of the page after printing
        document.body.innerHTML = originalContents;
        // Reload the page to reflect the original content and avoid any loss of functionality
        window.location.reload();
    };
});
</script>

<?php error_reporting (E_ALL ^ E_NOTICE); ?>
 
<div id="printableArea_ind_tenants_aa" >
<div class="row">
    <div class="col-sm-12">
        <div class="card border-0 ">
            <!-- <div class="card-header"></div> -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table_content" style="font-size: 0.8rem;">
                <thead class="table-primary text-center p-0 m-0">
                    <tr>
                        <th>#</th>
                        <th>Factory Name</th>
                        <th>Product</th>
                        <th>Unit</th>
                        <th>Installed Capacity</th>
                        <!-- <th>Attainable Production Capacity(MT)</th> -->
                        <th>Daily</th>
                        <th>Monthly</th>
                        <th>Yearly</th>
                        <th>Yearly Production Target</th>
                        <th>Due</th>
                        <th>Monthly Target </th>
                        <!-- <th>Due</th> -->
                        <th>Plant Load (%)</th>
                        <!-- <th>Previous Day Production (MT)</th> -->
                        <th>Remarks</th>
                    </tr>
                </thead>
            <tbody class=" text-center align-middle">
   
    <?php
    include('../db/db.php');
    // $date = date('Y-m-d');
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
    
    $counttable=0;
    // Loop through each table in $tables array
    foreach ($tables as $table) {
        // Fetch data for the current table
        $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
        $result_check = mysqli_query($conn, $sql_check);      

        if (mysqli_num_rows($result_check) > 0) {
        $counttable++;
        }
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
                    <td class="text-uppercase text-center" style="font-size:10px;"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <!-- <td><?php echo (int)$row['attain_capacity']; ?></td> -->
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <!-- <td><?php echo $month_target - $daily; ?></td> -->
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <!-- <td><?php echo $month_m - $daily; ?></td> -->
                    <!-- <td><?php //echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td> -->
                    <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                $is_first_row = false; // After the first row, do not display the factory_name again
            }
        }
    }

if ($counttable > 1) {
    // After processing all tables, display the total row for each column
    echo '<tr>';
    echo '<td colspan="2" class="text-center"><strong>Total</strong></td>';
    // Corrected the echo for product_produce with strong tag
    echo '<td class="text-center text-uppercase" style="font-size:10px;"><strong>' . htmlspecialchars($product_produce ?? '', ENT_QUOTES, 'UTF-8') . '</strong></td>';
    // Corrected the echo for product_produce with strong tag
    echo '<td class="text-center"><strong>' . (($row['product_produce'] ?? null) != 'Sheet Glass' ? 'MT' : 'L.Sq.M') . '</strong></td>';
      echo '<td><strong>' . $total_installed_capacity . '</strong></td>';
    // echo '<td>' . $total_attain_capacity . '</td>';
    // Apply strong tag to the total columns as well
    echo '<td><strong>' . $total_daily . '</strong></td>';
    echo '<td><strong>' . $total_month_m . '</strong></td>';
    echo '<td><strong>' . $total_month_y . '</strong></td>';
    echo '<td><strong>' . $total_year_target . '</strong></td>';
    echo '<td><strong>' . ($total_year_target - $total_month_y) . '</strong></td>';
    echo '<td><strong>' . $total_month_target . '</strong></td>';
    // echo '<td><strong>' . ($total_month_target - $total_daily) . '</strong></td>';
    echo '<td></td>'; // Empty column for plant_load
    echo '<td></td>'; // Empty column for remarks
    echo '</tr>';
}
//for non-urea
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
                   $month_m = round($month_m + (float)$row_m['daily'], 2);

                }
                // Yearly calculation
                $sql_y = "SELECT * FROM $table1 
                          WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                          AND date <= '$date' 
                          AND product_produce = '$product_produce'";
                $result_fetch_y = mysqli_query($conn, $sql_y);

                $month_y = 0;
                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                    $month_y += round((float)$row_y['daily'], 2);
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
                    <td class="text-uppercase text-center" style="font-size:10px;"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>
                    <td><?php echo $row['installed_capacity']; ?></td>
                    <!-- <td><?php echo (int)$row['attain_capacity']; ?></td> -->
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <!-- <td><?php echo $month_target - $daily; ?></td> -->
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <!-- <td><?php echo $month_m - $daily; ?></td> -->
                    <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
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

            //abc=$row['installed_capacity'];

            if ($product_produce == "sanitary") {
                $row['installed_capacity'] = $row['sanitary_installed_capacity'];
            } elseif ($product_produce == "insulator") {
                $row['installed_capacity'] = $row['insulator_installed_capacity'];
            } elseif ($product_produce == "refractories") {
                $row['installed_capacity'] = $row['refractories_installed_capacity'];
            }

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
              $month_m = round($month_m + (float)$row_m['daily'], 2);
            }

            // Yearly calculation
            $sql_y = "SELECT * FROM bisf 
                      WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                      AND date <= '$date' 
                      AND product_produce = '$product_produce'";
            $result_fetch_y = mysqli_query($conn, $sql_y);

            $month_y = 0;
            while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
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
                <td class="text-uppercase text-center" style="font-size:10px;"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>
                <td><?php echo (int)$row['installed_capacity']; ?></td>
                <!-- <td><?php echo (int)$row['attain_capacity']; ?></td> -->
                <td><?php echo $daily; ?></td>
                <td><?php echo $month_m; ?></td>
                <td><?php echo $month_y; ?></td>
                <td><?php echo $year_target; ?></td>
                <td><?php echo $year_target - $month_y; ?></td>
                <td><?php echo $month_target; ?></td>
                <!-- <td><?php echo $month_target - $daily; ?></td> -->
                <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                <!-- <td><?php echo $month_m - $daily; ?></td> -->
                <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>

            </tr>
            <?php
            $is_first_row = false; // After the first row, do not display the factory_name again
        }
    }

}
else{
    //$date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
    $date=$yesterday;
    $_SESSION['date'] = $date;

    // $date_on_prdate('d-m-Y', strtotime('-1 day'));
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
    $counttable=0;
    // Loop through each table in $tables array
    foreach ($tables as $table) {
        // Fetch data for the current table
        $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
        $counttable++;
        }

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
                    <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <!-- <td><?php echo (int)$row['attain_capacity']; ?></td> -->
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <!-- <td><?php echo $month_target - $daily; ?></td> -->
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <!-- <td><?php echo $month_m - $daily; ?></td> -->
                    <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
                $is_first_row = false; // After the first row, do not display the factory_name again
            }
        }
    }
if ($counttable > 1) {
    // After processing all tables, display the total row for each column
    echo '<tr>';
    echo '<td colspan="2" class="text-center"><strong>Total</strong></td>';
    // Corrected the echo for product_produce with strong tag
    echo '<td class="text-center text-uppercase" style="font-size:10px;"><strong>' . htmlspecialchars($product_produce ?? '', ENT_QUOTES, 'UTF-8') . '</strong></td>';
    // Corrected the echo for product_produce with strong tag
    echo '<td class="text-center"><strong>' . (($row['product_produce'] ?? null) != 'Sheet Glass' ? 'MT' : 'L.Sq.M') . '</strong></td>';
      echo '<td>' . $total_installed_capacity . '</td>';
    // echo '<td>' . $total_attain_capacity . '</td>';
    // Apply strong tag to the total columns as well
    echo '<td><strong>' . $total_daily . '</strong></td>';
    echo '<td><strong>' . $total_month_m . '</strong></td>';
    echo '<td><strong>' . $total_month_y . '</strong></td>';
    echo '<td><strong>' . $total_year_target . '</strong></td>';
    echo '<td><strong>' . ($total_year_target - $total_month_y) . '</strong></td>';
    echo '<td><strong>' . $total_month_target . '</strong></td>';
    // echo '<td><strong>' . ($total_month_target - $total_daily) . '</strong></td>';
    echo '<td></td>'; // Empty column for plant_load
    echo '<td style="font-size:10px; text-align: left;"></td>'; // Empty column for remarks
    echo '</tr>';
}

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
                    <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>
                    <td><?php echo (int)$row['installed_capacity']; ?></td>
                    <!-- <td><?php echo (int)$row['attain_capacity']; ?></td> -->
                    <td><?php echo $daily; ?></td>
                    <td><?php echo $month_m; ?></td>
                    <td><?php echo $month_y; ?></td>
                    <td><?php echo $year_target; ?></td>
                    <td><?php echo $year_target - $month_y; ?></td>
                    <td><?php echo $month_target; ?></td>
                    <!-- <td><?php echo $month_target - $daily; ?></td> -->
                    <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <!-- <td><?php echo $month_m - $daily; ?></td> -->
                     <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
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

        if ($product_produce == "sanitary") {
            $row['installed_capacity'] = $row['sanitary_installed_capacity'];
        } elseif ($product_produce == "insulator") {
            $row['installed_capacity'] = $row['insulator_installed_capacity'];
        } elseif ($product_produce == "refractories") {
            $row['installed_capacity'] = $row['refractories_installed_capacity'];
        }

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
                // $month_m += (float)$row_m['daily'];
                $month_m = round($month_m + (float)$row_m['daily'], 2);
            }

            // Yearly calculation
            $sql_y = "SELECT * FROM bisf 
                      WHERE date BETWEEN '$yearrange12' AND '$yearrange13' 
                      AND date <= '$date' 
                      AND product_produce = '$product_produce'";
            $result_fetch_y = mysqli_query($conn, $sql_y);

            $month_y = 0;
            while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                // $month_y += (float)$row_y['daily'];
                $month_y = round($month_y + (float)$row_y['daily'], 2);
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
                <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>
                <td><?php echo (int)$row['installed_capacity']; ?></td>
                <!-- <td><?php echo (int)$row['attain_capacity']; ?></td> -->
                <td><?php echo $daily; ?></td>
                <td><?php echo $month_m; ?></td>
                <td><?php echo $month_y; ?></td>
                <td><?php echo $year_target; ?></td>
                <td><?php echo $year_target - $month_y; ?></td>
                <td><?php echo $month_target; ?></td>
                <!-- <td><?php echo $month_target - $daily; ?></td> -->
                <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                <!-- <td><?php echo $month_m - $daily; ?></td> -->
                 <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php
            $is_first_row = false; // After the first row, do not display the factory_name again
        }
    }
}
?>
    </tbody>
</table>

<br>
<div> 
</div>
<div class="d-flex justify-content-between" style="font-size: 0.8rem;">
  <div class="text-muted" style="margin-right: 0.8rem; padding-top: 1rem;">
    <b style="text-decoration: underline;">C.C TO (Not on the basis of seniority):</b><br>
    1. Sr. Secretary, MoInd, GOD, Dhaka.<br>
    2. Chairman (Grade-1), BCIC, Dhaka.<br>
    3. Addl. Secretary, MoInd, GOD, Dhaka.<br>
    4. PS to Honorable Advisor, MoInd, GOD, Dhaka.<br>
    5. Director (), BCIC, Dhaka.<br>
    6. Senior General Manager (Admin), BCIC, Dhaka.<br>
    7. Head of Marketing/CA/Chief Auditors, BCIC, Dhaka.<br>
    8. O/C.
  </div>

  <div class="text-muted text-center me-5" style="padding-top: 1rem;">
    General Manager (Production)<br>
    Production Division, BCIC.<br>
    Phone No: 02223388176<br>
    Email: productionbcic@gmail.com<br>
  </div>
</div>

</div>
  <div class="card-footer text-end text-muted me-0 m-0 mb-0" id="footer_id" style="font-size: 0.8rem; margin-bottom: 0px;"><i>Design & Developed By ICT Division, BCIC.</i></div>
</div>  
</div> 
 <br>
</div> 
</div>
</div>
</div>
</div>
<script>
        // Show the reloading message and reload the page every 10 seconds
        setTimeout(() => {
            const reloadMessage = document.getElementById('reload-message');
            if (reloadMessage) {
                reloadMessage.style.display = 'block'; // Show the message
            }
            setTimeout(() => {
                location.reload();
            }, 2000); // Wait 2 seconds before reloading
        }, 10000); // 10 seconds = 10000 milliseconds
    </script>
<!--  <script>
        // Show the reloading message and reload the page every 10 seconds
        setTimeout(() => {
            const reloadMessage = document.getElementById('reload-message');
            reloadMessage.style.display = 'block'; // Show the message
            setTimeout(() => {
                location.reload();
            }, 2000); // Wait 2 seconds before reloading
        }, 10000); // 10 seconds = 10000 milliseconds
    </script> -->
<!-- <script>
    // Automatically reload the page every 10 seconds
    setTimeout(() => {
        location.reload();
    }, 10000); // 10 seconds = 10000 milliseconds
</script> -->
<?php
include('../include/footer.php');
?>
