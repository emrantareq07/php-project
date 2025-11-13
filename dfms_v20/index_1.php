<?php
error_reporting(0);
$date = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

include('db/db_PDO.php');
include('include/header_index.php');
$username=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
?>  

<div class="container-fluid" id="print-content"> 
<!-- <div id="reload-message">Reloading...</div>  -->
<div class="row my-3 align-items-center">
    <!-- Date Search Form -->
    <div class="col-12 col-md-3 mb-2 mb-md-0">
        <form class="row g-2 align-items-center" action="" method="post">
            <div class="col-8 col-sm-8">
                <input type="date" class="form-control" name="date" id="date" required>
            </div>
            <div class="col-4 col-sm-4">
                <button type="submit" class="btn btn-primary w-100" id="search-btn" name="hit">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>

    <!-- Title Section -->
    <div class="col-12 col-md-6 text-center">
        <h3 class="text-muted text-uppercase fw-bold itim-regular mb-1">
            Bangladesh Chemical Industries Corporation
        </h3>
        <h4 class="text-success text-uppercase fw-bold akaya-kanadaka-regular mb-1">
            Daily Production & Plant Status Report
        </h4>
        <?php 
        if (isset($_POST['hit'])) {
        ?>
            <h6 class="text-dark mb-0">
                <b>Production as on: <?php echo date('d-m-Y', strtotime($_POST['date'])); ?> </b>
            </h6>
            <h6 class="text-dark mb-0">
                <b>Dated on: <?php echo date('d-m-Y');?> </b>
            </h6>
        <?php
        } else {                
        ?>
            <h6 class="text-dark mb-0">
                <b>Production as on: <?php echo date('d-m-Y', strtotime('-1 day')); ?> </b>
            </h6>
            <h6 class="text-dark mb-0">
                <b>Dated on: <?php echo date('d-m-Y');?> </b>
            </h6>
        <?php } ?>
    </div>

    <!-- Action Buttons -->
    <div class="col-12 col-md-3 text-md-end text-center mt-2 mt-md-0">
        <a class="btn btn-primary mb-1" id="reload-btn" href="index.php">
            <i class="fa fa-refresh"></i> Reload
        </a>
        <a class="btn btn-primary mb-1" id="login-btn" href="controller/dashboard.php">
            <i class="fa fa-sign-in"></i> Login
        </a>
        <button onclick="window.print();return false;" class="btn btn-danger mb-1" id="print-btn">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
</div>

<style>
/* Mobile Card Styles */
.factory-card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.factory-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.factory-header {
    background: linear-gradient(135deg, #2c3e50, #4a6491);
    color: white;
    padding: 12px 15px;
    border-bottom: none;
    position: relative;
}

.factory-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #3498db, #2ecc71);
}

.factory-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    color: white;
}

.factory-body {
    padding: 15px;
    background-color: #f8f9fa;
}

.product-item {
    background: white;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    border-left: 4px solid #3498db;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    margin-bottom: 8px;
}

.data-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    margin-bottom: 8px;
}

.data-item {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    border-bottom: 1px dashed #e9ecef;
}

.data-label {
    font-weight: 500;
    color: #6c757d;
    font-size: 0.8rem;
}

.data-value {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.85rem;
}

.remarks {
    background: #fff8e1;
    border-radius: 6px;
    padding: 8px;
    margin-top: 8px;
    font-size: 0.8rem;
    color: #856404;
    border-left: 3px solid #ffc107;
}

/* Color coding for values */
.positive {
    color: #28a745;
}

.negative {
    color: #dc3545;
}

.neutral {
    color: #6c757d;
}

.highlight {
    color: #e74c3c;
    font-weight: 700;
}

/* View Toggle */
.view-toggle {
    display: flex;
    justify-content: center;
    margin: 15px 0;
}

.view-toggle-btn {
    padding: 8px 16px;
    border: 1px solid #dee2e6;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
}

.view-toggle-btn.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.view-toggle-btn:first-child {
    border-radius: 6px 0 0 6px;
}

.view-toggle-btn:last-child {
    border-radius: 0 6px 6px 0;
}

/* Print styles for cards */
@media print {
    .factory-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .view-toggle, #reload-btn, #login-btn, #print-btn, #search-btn {
        display: none !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .data-grid {
        grid-template-columns: 1fr;
    }
    
    .factory-title {
        font-size: 1rem;
    }
    
    .product-name {
        font-size: 0.9rem;
    }
}

/* Animation for loading */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.factory-card {
    animation: fadeIn 0.5s ease;
}
</style>

<script type="text/javascript">
document.getElementById('print_ind_tenants_aa').addEventListener('click', function () {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea_ind_tenants_aa').innerHTML;

    // Define the title
    var title = `
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 0px;">
        <img src="bcic_logo.png" alt="BCIC Logo" style="max-width: 60px; margin-right: 20px;">
        <div style="text-align: center;">
            <h5 class="text-uppercase mb-0 m-0" style="margin-bottom: 5px;">Bangladesh Chemical Industries Corporation</h5>
            <p class="text-uppercase mb-0" style="margin-top: 0; margin-bottom: 0px;">Daily Production & Plant Status Report</p>
            <?php if (isset($_POST['hit'])) { ?>
                <p class=" text-center mb-0 m-0" style="margin-top: 0; margin-bottom: 0;">
                    Production as on: <?php echo date('d-m-Y', strtotime($_POST['date'])); ?>
                </p>
            <?php } else { ?>
                <p class=" text-center mb-0 m-0" style="margin-top: 0; margin-bottom: 0;">
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

// Toggle between table and card view
function toggleView(viewType) {
    if (viewType === 'cards') {
        document.getElementById('table-view').style.display = 'none';
        document.getElementById('card-view').style.display = 'block';
        document.getElementById('table-btn').classList.remove('active');
        document.getElementById('card-btn').classList.add('active');
    } else {
        document.getElementById('table-view').style.display = 'block';
        document.getElementById('card-view').style.display = 'none';
        document.getElementById('card-btn').classList.remove('active');
        document.getElementById('table-btn').classList.add('active');
    }
}
</script>

<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<div id="printableArea_ind_tenants_aa" class="mb-0 m-0 p-0">
    
    <!-- View Toggle Buttons -->
    <div class="view-toggle">
        <button id="table-btn" class="view-toggle-btn active" onclick="toggleView('table')">
            <i class="fa fa-table"></i> Table View
        </button>
        <button id="card-btn" class="view-toggle-btn" onclick="toggleView('cards')">
            <i class="fa fa-th-large"></i> Card View
        </button>
    </div>
    
    <!-- Table View (Default) -->
    <div id="table-view">
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0 ">
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
                                <th>Daily</th>
                                <th>Monthly</th>
                                <th>Yearly</th>
                                <th>Yearly Production Target</th>
                                <th>Due</th>
                                <th>Monthly Target </th>
                                <th>Plant Load (%)</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                    <tbody class=" text-center align-middle">
           
            <?php
            include('db/db.php');
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

            // Initialize a flag to check if any data is found
            $dataFound = false;

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
                    $dataFound = true;
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
                            <td><?php echo $daily; ?></td>
                            <td><?php echo $month_m; ?></td>
                            <td><?php echo $month_y; ?></td>
                            <td><?php echo $year_target; ?></td>
                            <td><?php echo $year_target - $month_y; ?></td>
                            <td><?php echo $month_target; ?></td>
                            <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
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
            echo '<td class="text-center text-uppercase" style="font-size:10px;"><strong>' . htmlspecialchars($product_produce ?? '', ENT_QUOTES, 'UTF-8') . '</strong></td>';
            echo '<td class="text-center"><strong>' . (($row['product_produce'] ?? null) != 'Sheet Glass' ? 'MT' : 'L.Sq.M') . '</strong></td>';
              echo '<td><strong>' . $total_installed_capacity . '</strong></td>';
            echo '<td><strong>' . $total_daily . '</strong></td>';
            echo '<td><strong>' . $total_month_m . '</strong></td>';
            echo '<td><strong>' . $total_month_y . '</strong></td>';
            echo '<td><strong>' . $total_year_target . '</strong></td>';
            echo '<td><strong>' . ($total_year_target - $total_month_y) . '</strong></td>';
            echo '<td><strong>' . $total_month_target . '</strong></td>';
            echo '<td></td>';
            echo '<td></td>';
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
                    $dataFound = true;
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
                            <td><?php echo $daily; ?></td>
                            <td><?php echo $month_m; ?></td>
                            <td><?php echo $month_y; ?></td>
                            <td><?php echo $year_target; ?></td>
                            <td><?php echo $year_target - $month_y; ?></td>
                            <td><?php echo $month_target; ?></td>
                            <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
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
                $dataFound = true;
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
                        <td><?php echo $daily; ?></td>
                        <td><?php echo $month_m; ?></td>
                        <td><?php echo $month_y; ?></td>
                        <td><?php echo $year_target; ?></td>
                        <td><?php echo $year_target - $month_y; ?></td>
                        <td><?php echo $month_target; ?></td>
                        <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php
                    $is_first_row = false; // After the first row, do not display the factory_name again
                }
            }

        }else{   
            $date=$yesterday;
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
                    $dataFound = true;
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
                            <td><?php echo $daily; ?></td>
                            <td><?php echo $month_m; ?></td>
                            <td><?php echo $month_y; ?></td>
                            <td><?php echo $year_target; ?></td>
                            <td><?php echo $year_target - $month_y; ?></td>
                            <td><?php echo $month_target; ?></td>
                            <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
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
            echo '<td class="text-center text-uppercase" style="font-size:10px;"><strong>' . htmlspecialchars($product_produce ?? '', ENT_QUOTES, 'UTF-8') . '</strong></td>';
            echo '<td class="text-center"><strong>' . (($row['product_produce'] ?? null) != 'Sheet Glass' ? 'MT' : 'L.Sq.M') . '</strong></td>';
              echo '<td>' . $total_installed_capacity . '</td>';
            echo '<td><strong>' . $total_daily . '</strong></td>';
            echo '<td><strong>' . $total_month_m . '</strong></td>';
            echo '<td><strong>' . $total_month_y . '</strong></td>';
            echo '<td><strong>' . $total_year_target . '</strong></td>';
            echo '<td><strong>' . ($total_year_target - $total_month_y) . '</strong></td>';
            echo '<td><strong>' . $total_month_target . '</strong></td>';
            echo '<td></td>';
            echo '<td style="font-size:10px; text-align: left;"></td>';
            echo '</tr>';
        }

        $tables1 = ['tspcl','dapfcl','kpml','cccl','ugsf'];  
            foreach ($tables1 as $table1) {      
                $sql_check = "SELECT * FROM $table1 WHERE date = '$date' ORDER BY factory_name";
                $result_check = mysqli_query($conn, $sql_check);
                $data1 = [];
                while ($row = mysqli_fetch_assoc($result_check)) {
                    $data1[$row['factory_name']][] = $row;
                    $dataFound = true;
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
                            <td><?php echo $daily; ?></td>
                            <td><?php echo $month_m; ?></td>
                            <td><?php echo $month_y; ?></td>
                            <td><?php echo $year_target; ?></td>
                            <td><?php echo $year_target - $month_y; ?></td>
                            <td><?php echo $month_target; ?></td>
                            <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
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
                $dataFound = true;
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
                        <td><?php echo $daily; ?></td>
                        <td><?php echo $month_m; ?></td>
                        <td><?php echo $month_y; ?></td>
                        <td><?php echo $year_target; ?></td>
                        <td><?php echo $year_target - $month_y; ?></td>
                        <td><?php echo $month_target; ?></td>
                        <td><?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?></td>
                         <td style="font-size:10px; text-align: left;"><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php
                    $is_first_row = false; // After the first row, do not display the factory_name again
                }
            }
        }

        // Check if no data was found for both current date and yesterday
        if (!$dataFound) {
            echo '<tr>';
            echo '<td colspan="13" class="text-center text-muted py-4">';
            echo '<i class="fa fa-database fa-2x mb-2"></i><br>';
            echo '<strong>No Record Found</strong><br>';
            echo '<small>No production data available for ';
            if (isset($_POST['hit'])) {
                echo date('d-m-Y', strtotime($_POST['date']));
            } else {
                echo date('d-m-Y', strtotime('-1 day'));
            }
            echo '</small>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
            </tbody>
        </table>
            <div> 
        </div>

        </div>
        <div class="card-footer text-end text-muted me-0 m-0 mb-0" id="footer_id" style="font-size: 0.8rem; margin-bottom: 0px;"><i>Design & Developed By ICT Division, BCIC.</i></div>
        </div>  
        </div> 
         <br>
        </div> 
        </div>
    </div>
    
    <!-- Card View (Hidden by default) -->
    <div id="card-view" style="display: none;">
        <div class="row">
            <?php
            // Reset data for card view
            include('db/db.php');
            
            if (isset($_POST['hit'])) {
                $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
            } else {
                $date = $yesterday;
            }
            
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

            // Collect all factories data for card view
            $allFactories = [];
            
            // Process all tables
            $tables = ['gpfplc', 'sfcl', 'jfcl', 'cufl', 'afccl', 'tspcl', 'dapfcl', 'kpml', 'cccl', 'ugsf', 'bisf'];
            
            foreach ($tables as $table) {
                $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
                $result_check = mysqli_query($conn, $sql_check);
                
                while ($row = mysqli_fetch_assoc($result_check)) {
                    $factory_name = $row['factory_name'];
                    $product_produce = $row['product_produce'];
                    
                    // For BISF, handle special installed capacity
                    if ($table == 'bisf') {
                        if ($product_produce == "sanitary") {
                            $row['installed_capacity'] = $row['sanitary_installed_capacity'];
                        } elseif ($product_produce == "insulator") {
                            $row['installed_capacity'] = $row['insulator_installed_capacity'];
                        } elseif ($product_produce == "refractories") {
                            $row['installed_capacity'] = $row['refractories_installed_capacity'];
                        }
                    }
                    
                    // Calculate monthly and yearly production
                    $month_code = $row['month_code'];
                    $year_code = $row['year_code'];
                    
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
                    
                    // Get targets
                    $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                    $result_year = mysqli_query($conn, $sql_year);
                    $row_year = mysqli_fetch_assoc($result_year);
                    $year_target = $row_year['target'];

                    $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                    $result_month = mysqli_query($conn, $sql_month);
                    $row_month = mysqli_fetch_assoc($result_month);
                    $month_target = $row_month['target'];
                    
                    // Add to factories array
                    if (!isset($allFactories[$factory_name])) {
                        $allFactories[$factory_name] = [];
                    }
                    
                    $allFactories[$factory_name][] = [
                        'product' => $product_produce,
                        'unit' => ($product_produce != 'Sheet Glass') ? 'MT' : 'L.Sq.M',
                        'installed_capacity' => $row['installed_capacity'],
                        'daily' => $row['daily'],
                        'monthly' => $month_m,
                        'yearly' => $month_y,
                        'year_target' => $year_target,
                        'due' => $year_target - $month_y,
                        'month_target' => $month_target,
                        'plant_load' => $row['plant_load'],
                        'remarks' => $row['remarks']
                    ];
                }
            }
            
            // Display factory cards
            $cardIndex = 1;
            foreach ($allFactories as $factory_name => $products) {
                ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card factory-card">
                        <div class="card-header factory-header">
                            <h5 class="factory-title"><?php echo $cardIndex . '. ' . htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?></h5>
                        </div>
                        <div class="card-body factory-body">
                            <?php foreach ($products as $product) { ?>
                                <div class="product-item">
                                    <div class="product-name">
                                        <?php echo htmlspecialchars($product['product'], ENT_QUOTES, 'UTF-8'); ?>
                                        <small class="text-muted">(<?php echo $product['unit']; ?>)</small>
                                    </div>
                                    <div class="data-grid">
                                        <div class="data-item">
                                            <span class="data-label">Installed Capacity:</span>
                                            <span class="data-value"><?php echo $product['installed_capacity']; ?></span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Daily:</span>
                                            <span class="data-value highlight"><?php echo $product['daily']; ?></span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Monthly:</span>
                                            <span class="data-value"><?php echo $product['monthly']; ?></span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Yearly:</span>
                                            <span class="data-value"><?php echo $product['yearly']; ?></span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Year Target:</span>
                                            <span class="data-value"><?php echo $product['year_target']; ?></span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Due:</span>
                                            <span class="data-value <?php echo ($product['due'] >= 0) ? 'positive' : 'negative'; ?>">
                                                <?php echo $product['due']; ?>
                                            </span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Month Target:</span>
                                            <span class="data-value"><?php echo $product['month_target']; ?></span>
                                        </div>
                                        <div class="data-item">
                                            <span class="data-label">Plant Load:</span>
                                            <span class="data-value"><?php echo $product['plant_load']; ?>%</span>
                                        </div>
                                    </div>
                                    <?php if (!empty($product['remarks'])) { ?>
                                        <div class="remarks">
                                            <strong>Remarks:</strong> <?php echo htmlspecialchars($product['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
                $cardIndex++;
            }
            
            if (empty($allFactories)) {
                echo '<div class="col-12 text-center py-5">';
                echo '<i class="fa fa-database fa-3x text-muted mb-3"></i>';
                echo '<h5 class="text-muted">No Production Data Available</h5>';
                echo '<p class="text-muted">No production data found for ';
                if (isset($_POST['hit'])) {
                    echo date('d-m-Y', strtotime($_POST['date']));
                } else {
                    echo date('d-m-Y', strtotime('-1 day'));
                }
                echo '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<script>
// Automatically reload the page every 10 seconds
// setTimeout(() => {
//     location.reload();
// }, 10000); // 10 seconds = 10000 milliseconds

// Initialize view toggle
document.addEventListener('DOMContentLoaded', function() {
    // Check screen size and set default view
    if (window.innerWidth < 768) {
        toggleView('cards');
    }
});
</script>

<?php
include('include/footer.php');
?>