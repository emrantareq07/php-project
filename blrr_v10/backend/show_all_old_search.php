<?php
session_name('blrr');
header('Content-Type: text/html; charset=utf-8');
include_once '../db/database_old.php';
include_once '../db/database.php';
session_start(); 
$table_name = isset($_GET['table_name']) ? urlencode($_GET['table_name']) : '';

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLRR</title>
    
    <!-- jQuery and jQuery UI for Datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Font Styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
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
    </style>
</head>
<body>

<div class="container-fluid border rounded shadow-lg">  
    <div class="row"> 
        <div class="col-sm-12">
            <h2 class="text-muted text-center my-2"><b>বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</b></h2>
            <center><hr class="bg-muted col-sm-6 text-center"></center>
        </div> 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form action="show_all_old.php?table_name=<?=$_GET['table_name']?>" method="post" class="needs-validation">    
                <input type="hidden" class="form-control" value="<?php echo $_GET['table_name']; ?>" name="table_name">
                
                <!-- Date Input Fields -->
                <div class="form-group row">
                    <label for="date1" class="col-form-label col-sm-5">শুরু তারিখ : </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="date1" placeholder="From Date" name="date1" required>
                    </div>
                </div> 
                <div class="form-group row mt-2">
                    <label for="date2" class="col-form-label col-sm-5">তারিখ অনুযায়ী /শেষ তারিখ : </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="date2" placeholder="To Date" name="date2" required>
                    </div>
                </div>

                <!-- Division Select Dropdown -->
                <div class="form-group row mt-2">
                    <label for="division_bn" class="col-form-label col-sm-5">বিভাগ অনুযায়ী পাঠানোর তালিকা : </label>
                    <div class="col-sm-7">
                        <select class="form-select form-control" id="division_bn" name="division_bn" aria-label="Default select example">
                            <option selected disabled value="">--Select--</option>
                            <?php
                                $sql = "SELECT designation FROM designation_old";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
                                }
                            ?>   
                        </select>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <center>
                    <button type="submit" name="submit" class="btn btn-primary btn-block mb-2 mt-2 col-sm-6 offset-5">
                        <i class="fa fa-search" style="font-size:16px"></i> Search
                    </button>
                </center>       
            </form>    
        </div>
        
        <!-- Back and Print Buttons -->
        <div class="col-sm-3 text-center">
            <a href="show_all_old.php" class="btn btn-primary"><i class="fa fa-arrow-left" style="font-size:16px"></i> <span>Back</span></a>
            <hr>
            <button type="button" class="btn btn-danger" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
        </div>
    </div>
</div>

<!-- Printable Area -->
<div id="printableArea">
    <!-- <table class="table table-bordered table-striped text-center"> -->
<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
  <table class='table table-hover table-striped table-bordered text-center' id="form-tbl">
        <thead class="table-dark">
            <tr>
                <th>ক্রম</th>
                <th>পত্র প্রাপ্তি তারিখ</th>
                <th>প্রাপক</th>
                <th>ডকেট নং</th>
                <th>স্মারক নং</th>                    
                <th>পাঠানোর তারিখ</th>    
                <th>পত্র প্রেরক</th>        
                <th>বিষয়বস্তু</th>
                <th>গন্তব্য</th>                    
                <th>বিতরণ তারিখ</th>
                <th>মাধ্যম</th>        
            </tr>
        </thead>
        <tbody>
            <!-- Fetch and Display Records here -->
            <?php
            include_once '../db/database_old.php';
            function englishToBanglaNumber($number) {
                $englishNumbers = range(0, 9);
                $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
                return str_replace($englishNumbers, $banglaNumbers, $number);
            } 

            if (isset($_POST['submit'])) {
                $from_date = $_POST['date1'];
                $to_date = $_POST['date2'];
                //echo $from_date;
                $division_bn = isset($_POST['division_bn']) ? $_POST['division_bn'] : '';

                $table_name = $_POST['table_name'];

                // Base query construction
                if($division_bn){
                    // echo $from_date;
                $base_query = "
                SELECT * FROM rri 
                WHERE `entry_date` BETWEEN '$from_date' AND '$to_date' 
                AND ('$division_bn' IN (dt, dc, df, dp, dpl, sacretry, cop, marketing, audit, emd, saksha, law, company, purchase, ict, other_des)) ";

        
                } else {
                    $base_query = "SELECT * FROM rri WHERE `entry_date` BETWEEN '$from_date' AND '$to_date'";
                }

                $result1 = mysqli_query($conn_old, $base_query);
                $i = 1;
                if (mysqli_num_rows($result1) > 0) { 
                    while ($row = mysqli_fetch_assoc($result1)) {
                $entry_date = $row["entry_date"];
                $send_date = $row["send_date"];
                $dis_date = $row["dis_date"];
                // $destination = $row["destination"];
                $div_dept = $row["div_dept"];
                $from = $row["from"];
                if ($div_dept != '') {
                    $from = $from . ', ' . $div_dept;
                }
                $result_destination = implode(', ', array_filter([$row['dt'], $row['dc'], $row['df'],$row['dp'],$row['dpl'],$row['sacretry'],$row['cop'],$row['marketing'], $row['audit'], $row['emd'],$row['saksha'],$row['law'],$row['company'],$row['purchase'],$row['ict'],$row['other_des']], fn($value) => $value !== ''));
                        ?>         
                        <tr class="text-center">             
                            <td><?php echo englishToBanglaNumber($i); ?></td>
                            <td><?php echo englishToBanglaNumber($entry_date); ?></td>
                            <td><?php echo $row["to_l"]; ?></td>
                            <td><?php echo $row["d_number"]; ?></td>
                            <td><?php echo $row["ref_number"]; ?></td>                 
                            <td><?php echo englishToBanglaNumber($send_date); ?></td>
                            <td><?php echo $from; ?></td>
                            <td><?php echo $row["subject"]; ?></td> 
                            <td><?php echo $result_destination; ?></td>
                            <td><?php echo englishToBanglaNumber($dis_date); ?></td>
                            <td><?php echo $row["by"]; ?></td>               
                        </tr>
                        <?php
                        $i++;
                    }
                } else {
                    echo '<tr><td colspan="11" class="text-center text-danger">No records found for the selected date range.</td></tr>';
                }
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>
</div>

<!-- jQuery Datepicker Initialization -->
<script>
  $(function() {
    $("#date1, #date2").datepicker({
      dateFormat: 'yy-mm-dd',
      // dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true
    });
  });
</script>

<!-- Print Functionality -->
<script>
document.getElementById('print').addEventListener('click', function() {
    var printContents = document.getElementById('printableArea').innerHTML;
    var title = '<h1 class="text-center">পত্র প্রাপ্তি রেজিস্টার</h1>';
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = title + printContents;
    window.print();
    document.body.innerHTML = originalContents;
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<?php //include('footer.php'); ?>
</body>
</html>
