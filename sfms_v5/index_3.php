<?php
include('include/header.php');
session_start();
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = "";
$dbname = 'dfms_db';

$date = date('Y-m-d');
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

?>   
            
<div class="container">  
 <div class="row my-2">  
    <div class="col-sm-3">
        <form class="form" action="" method="post">
                <div class="col-sm-9">
                    <div class="form-group">
                        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" required>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="submit" value="Search" class="btn btn-primary" name="hit">
                    </div>
                </div>
                <!-- <div class="col-sm-5">
                    <center>
                        <a class="btn btn-primary text-center float-end" href="dashboard.php">Login</a>
                        <a class="btn btn-primary text-center float-end" href="print_report.php">Print</a>
                    </center>
                </div> -->
            </form>     
      
    </div>
    <div class="col-sm-6">
    <h3 class="text-success text-center my-1 "> <b>Urea Production Report [--All Factory--] </b></h3>
    </div>  
   <div class="col-sm-3">     
      <span class="text-center float-end">
        <a class="btn btn-primary text-center float-end" href="dashboard.php">Login</a>
        <a class="btn btn-primary text-center float-end" href="print_report.php">Print</a>
      
      
    </div>
 </div> 
 <?php //error_reporting (E_ALL ^ E_NOTICE); ?>
  
<div class="row">
  <div class="col-sm-12">
  <div class="card">
  <div class="card-header"> </div>
  <div class="card-body">
  <table class="table table-bordered table-striped table-hover" id="table_content">
    <thead>
        <tr>
            
            <th>Date</th>
            <th>Factory Name</th>
            <th>Daily</th>
            <th>Monthly</th>
            <th>Yearly</th>
            <th>Production Target</th>
            <th>Due</th>
            <th>Plant Load(%)</th>
            <th>Causes of Shutdown</th>
      </tr>
    </thead>
  
    <?php

    if (isset($_POST['hit'])) {

        $date = $_POST['date'];

        $sql = "SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'
                UNION ALL
                SELECT * FROM afccl WHERE `date` LIKE '{$date}%'
                UNION ALL
                SELECT * FROM jfcl WHERE `date` LIKE '{$date}%'";

        $result = mysqli_query($link, $sql);

        if ($result === false) {
            // Query failed
            echo "Error: " . mysqli_error($link);
        } else {

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    $table = $row['factory_name'];
                    $date = $row['date'];
                    $target = $row['production_target'];
                    $daily = $row['daily'];
                    $monthly = $row['monthly'];
                    $yearly = $row['yearly'];
                    $due = $target - $yearly;
                    $remarks = $row['remarks'];

                    echo "<tr>";
                    echo "<td> $date </td>";
                    echo "<td class='text-uppercase'> $table </td>";
                    echo "<td> $daily</td>";
                    echo "<td>$monthly </td>";
                    echo "<td>$yearly</td>";
                    echo "<td>$target </td>";
                    echo "<td>$due </td>";
                    echo "<td>$remarks </td>";
                    echo "</tr>";
                }

            } else {
                echo "<p class='text-center text-danger'>No Record Found!!!</p>";
            }
        }
    } else {
        // Show default records
        $sql = "SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'
                UNION ALL
                SELECT * FROM afccl WHERE `date` LIKE '{$date}%'
                UNION ALL
                SELECT * FROM jfcl WHERE `date` LIKE '{$date}%'";

        $result = mysqli_query($link, $sql);

        if ($result === false) {
            // Query failed
            echo "Error: " . mysqli_error($link);
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $table = $row['factory_name'];
                $date = $row['date'];
                $target = $row['production_target'];
                $daily = $row['daily'];
                $monthly = $row['monthly'];
                $yearly = $row['yearly'];
                $due = $target - $yearly;
                $remarks = $row['remarks'];

                echo "<tbody>";
                echo "<tr>";
                echo "<td> $date </td>";
                echo "<td class='text-uppercase'> $table </td>";
                echo "<td> $daily</td>";
                echo "<td>$monthly </td>";
                echo "<td>$yearly</td>";
                echo "<td>$target </td>";
                echo "<td>$due </td>";
                echo "<td>$remarks </td>";
                echo "</tr>";
                echo "</tbody>";
            }
        }
    }

    ?>
    
</table>
  </div>
  <div class="card-footer text-right"><i>Design & Developed By ICT Division, BCIC.</i></div>
</div>

    
    </div>
    
    <div class="col-sm-0">
      <!--<h3><a href="report_view.php">View Report</a></h3>-->
    </div>
  </div>
 

<?php
include('include/footer.php');
?>
