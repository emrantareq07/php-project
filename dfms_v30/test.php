<?php
include('../include/header_index.php');
session_start();


// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}
$i=0;
if(isset($_GET['val'])){
        $table = $_GET['val']; 
        $i=1;
    }
   else
   {
    $table=$_SESSION['username'];
   }

if($table=='sfcl'){
    $table1='Shahjalal Fertilizer Company Ltd. (SFCL)';
}
elseif($table=='jfcl'){
    $table1='Jamuna Fertilizer Company Ltd. (JFCL)';
}
elseif($table=='afccl'){
    $table1='Ashuganj Fertilizer Company Ltd. (AFCCL)';
}

if(isset($_POST['hit'])){ 
$_SESSION['start_date']= $_POST['start_date'];
$_SESSION['end_date']= $_POST['end_date'];
}
?>
   
<!-- <div class="container">  
    <div class="row my-2">  
        <div class="col-sm-6 col-md-6">
            <form class="form-inline" action="" method="post">
                <div class="form-group mr-2">
                    <input type="date" class="form-control" placeholder="Enter Start Date" name="start_date" id="start_date" required>
                      
                    <input type="date" class="form-control" placeholder="Enter End Date" name="end_date" id="end_date" required>
                    <button type="submit" class="btn btn-primary" id="search-btn" name="hit">Search</button>
                </div>      
            </form>     
        </div>
        <div class="col-sm-6 col-md-4">
            <h4 class="text-success text-center my-1 text-uppercase"><b>Urea Production Report [--All Factory--]</b></h4>

        </div>  
        <div class="col-sm-6  col-md-6 float-end">     
            <span class=" float-end">
                <a class="btn btn-primary" id="login-btn" href="controller/dashboard.php">Login</a>
                <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn"><i class="fa fa-print"></i> Print</button>
                <form class="form-inline" action="download_report_pdf.php" method="post" style="display: inline-block; width: auto;">
                    <button type="submit" name="submit" class="btn btn-danger" id="download_pdf"><i class="fa fa-file-pdf-o"></i> Download</button> 
                </form>   
            </span>
        </div>
    </div>  -->
<div class="container">  
    <div class="row my-2">  
        <div class="col-md-6">
            <form class="form-inline" action="" method="post">
                <div class="form-group mr-md-2">
                    <input type="date" class="form-control" placeholder="Enter Start Date" name="start_date" id="start_date" required>
                </div>
                <div class="form-group mr-md-2">
                    <input type="date" class="form-control" placeholder="Enter End Date" name="end_date" id="end_date" required>
                </div>
                <button type="submit" class="btn btn-primary" id="search-btn" name="hit"> <i class="fa fa-search"></i> Search</button>
            </form>     
        </div>
        <!-- <div class="col-md-4"></div> -->
        <div class="col-md-6 text-md-end">     
            <span class="text-center">
                <?php 
                if($i==1){
                    ?>
                    <a class="btn btn-primary" id="login-btn" href="home.php"><i class="fa fa-arrow-left"></i> Previous Page</a>
                <?php
                } else{

                    ?>
                <a class="btn btn-primary" id="login-btn" href="urea_form.php"><i class="fa fa-arrow-left"></i> Previous Page</a> 
                <?php
                    }                
                ?>               

                <button onclick="window.print();return false;" class="btn btn-danger" id="print-btn"><i class="fa fa-print"></i> Print</button>
                <form class="form-inline" action="download_report_pdf.php" method="post" style="display: inline-block; width: auto;">
                    <a class="btn btn-danger" href="logout.php" id="logout"><i class="fa fa-sign-out"></i>Logout</a>
                    <!-- <button type="submit" name="submit" class="btn btn-danger" id="download_pdf"><i class="fa fa-file-pdf-o"></i> Download</button -->
                </form>   
            </span>
        </div>
    </div> 

<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center text-uppercase"><b>Daily Production & Plant Status Report</h4></b> </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_content">
                        <thead>
                            <tr>  
                            <!-- Content to be displayed at the top of the printed page -->
                            <div id="print-header" style="display: none;">
                                <div style="text-align:center;margin-bottom:10px; margin-top:0px;">
                                    <!-- <h3>Urea Production Report</h3> -->
                                    <h6 class="text-center text-uppercase "><b><?=$table1;?></b></h6>
                                    <h5 class="text-decoration-underline">From  <?php echo $_SESSION['start_date'];?> to  <?php echo $_SESSION['end_date'];?></h5>
                                    <h5 class="text-decoration-none"><?php //echo ?></h5>
                                </div>
                            </div>                             
                                <th>Date</th>
                                <th>Factory Name</th>            
                                <th>Daily (MT)</th>
                                <th>Monthly (MT)</th>
                                <th>Yearly (MT)</th>
                                <th>Production Target (MT)</th>
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
      echo'Plz enter Running Fiscal Year';
      //header("Location: print_urea_report_date_range.php");
    }
    else{
         $sql = "SELECT * FROM $table 
        JOIN target_table ON $table.factory_name = target_table.factory_name 
        WHERE $table.`date` between '$start_date' AND '$end_date'
        AND target_table.fiscalstart = '$yearrange12'         
         ";     
     
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
          $total_daily=0;
          $total_monthly=0;
          $total_yearly=0;
              //output data of each row
        while($row = mysqli_fetch_assoc($result)) {

            $total_daily= $total_daily+ (int)$row['daily'];
            $total_monthly= $total_monthly+ (int)$row['monthly'];
            $total_yearly= $total_yearly+ (int)$row['yearly'];
            ?>
             <tr>
            
                <td ><?php echo $row['date']; ?></td>
                <td class="text-uppercase"><?php echo $row['factory_name']; ?></td>
                <td><?php echo $row['daily']; ?></td>
                <td><?php echo $row['monthly']; ?></td>
                <td><?php echo $row['yearly']; ?></td>
                <td><?php echo $row['target']; ?></td>
                <td><?php echo $row['target'] - $row['yearly']; ?></td>
                <td><?php echo $row['plant_load']; ?></td>
                <td><?php echo $row['remarks']; ?></td>
            </tr> 
        
            <?php   
                }
           
                echo '<tr>
                <td ></td>
                <td >Total Production:</td>
                <td >'. $total_daily .'</td>
                <td >'. $total_monthly .'</td>
                <td >'. $total_yearly .'</td>
                <td ></td>
                <td ></td>
                <td ></td>
                <td ></td>
                </tr>';
            }
            else {
                echo "<p class='text-center text-danger'><b>No Record Found!!!</b></p>";
            }

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
