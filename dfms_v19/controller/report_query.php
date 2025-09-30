<?php
include('../db/db.php');

$start_date = $_SESSION['start_date'] ?? null;
$end_date   = $_SESSION['end_date'] ?? null;
$table      = $_SESSION['username'] ?? '';


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

    //for BISFL
    elseif ($table == 'bisf') {
    $i = 1;
    $sql_check1 = "SELECT DISTINCT date FROM bisf WHERE date BETWEEN '$start_date' AND '$end_date' order by date asc";
    $result_check1 = mysqli_query($conn, $sql_check1);
    while ($row_check1 = mysqli_fetch_assoc($result_check1)) {
        $date = $row_check1['date'];
        // echo $date;
        $month_id = date('Y-m', strtotime($date));
        // $sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name ";
$sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name,FIELD(product_produce, 'sanitary', 'insulator', 'refractories')";

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
            <td class="date-nowrap"><?php echo $row['date']; ?></td>                
            <td class="text-uppercase text-center" style="font-size:10px;"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-uppercase text-center" style="font-size:10px;"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></td>                           
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
               <td><?php echo $row['product_produce']; ?></td>
                <td class="text-uppercase text-center" style="font-size:10px;"><?php echo 'MT'; ?></td>

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