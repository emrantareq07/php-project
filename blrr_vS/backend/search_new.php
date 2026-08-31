<?php
session_name('blrr');
include('header.php');
include_once '../db/database.php';
session_start();
$office_title = $_SESSION['office_title'];
// echo $office_title;
$table_name = isset($_GET['table_name']) ? urlencode($_GET['table_name']) : '';

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
$val = isset($_GET['val']) ? urlencode($_GET['val']) : '';

$table=$table_name;

if ($table == 'chairman') {
    $table1 = 'চেয়ারম্যান সচিবালয়';
} elseif ($table == 'dirfin') {
    $table1 = 'Jamuna Fertilizer Company Ltd. (JFCL)';
} elseif ($table == 'dirpi') {
    $table1 = 'Ashuganj Fertilizer Company Ltd. (AFCCL)';
} elseif ($table == 'dirpr') {
    $table1 = 'Ghorashal Polash Fertilizer PLC (GPFPLC)';
} elseif ($table == 'dirte') {
    $table1 = 'Chittagong Urea Fertilizer Ltd. (CUFL)';
} elseif ($table == 'dircom') {
    $table1 = 'TSP Complex Limited (TSPCL)';
} elseif ($table == 'cop') {
    $table1 = 'DAP Fertilizer Company Limited (DAPFCL)';
} elseif ($table == 'ps') {
    $table1 = 'Bangladesh Industrial Salt Factory (BISF)';
} elseif ($table == 'accounts') {
    $table1 = 'Chatak Cement Company Limited (CCCL)';
} elseif ($table == 'ugsf') {
    $table1 = 'Osmania Glass Sheet Factory Limited (UGSFL)';
} elseif ($table == 'kpml') {
    $table1 = 'Karnaphuli Paper Mills Limited (KPML)';
}

?>  
<div class="container mt-1 p-2 border rounded shadow-lg">  
 <div class="row"> 
    <div class="col-sm-12">
        <h2 class="text-muted text-center"><b>বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</b> </h2>
        <center><hr class="bg-muted col-sm-6 text-center"></center>
    </div> 
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <form action="search_new.php?table_name=<?= $_GET['table_name'] ?>&val=<?= $_GET['val'] ?>" method="post" class="needs-validation">
    
            <input type="hidden" class="form-control" value="<?php echo $_GET['table_name']; ?>" name="table_name" >
            <input type="hidden" class="form-control" value="<?php echo $_GET['val']; ?>" name="val" >
            <div class="form-group row">
                <label for="date1" class="col-form-label col-sm-5">শুরু তারিখ : </label>
                <div class="col-sm-7">
                    <input type="date" class="form-control" id="date1" placeholder="From Date" name="date1" required>
                </div>
            </div> 
            <div class="form-group row mt-2">
                <label for="date2" class="col-form-label col-sm-5">তারিখ অনুযায়ী /শেষ তারিখ : </label>
                <div class="col-sm-7">
                   <input type="date" class="form-control" id="date2"  placeholder="To Date" name="date2" required>
                </div>
            </div>
            <div class="form-group row mt-2">
                <label for="division_bn" class="col-form-label col-sm-5">বিভাগ অনুযায়ী পাঠানোর তালিকা : </label>
                <div class="col-sm-7">
                    <select class="form-select form-control" id="division_bn" name="division_bn"  aria-label="Default select example">
                        <option selected disabled value="">--Select--</option>
                        <?php
                          $sql = "SELECT division_bn FROM division where id not in(2,3,4)";
                          $result = mysqli_query($conn, $sql);
                          while($row = mysqli_fetch_array($result)) {
                              echo "<option value='".$row['division_bn']."'>".$row['division_bn']."</option>";
                          }
                        ?>   
                    </select>
                </div>
            </div>
            <center><button type="submit" name="submit" class="btn btn-primary btn-block  mb-2 mt-2 col-sm-6 offset-5"> <i class="fa fa-search" style="font-size:16px"></i> Search</button></center>
            <!-- <center><button type="submit" name="submit" class="btn btn-primary btn-block w-100 mb-2 mt-2"> <i class="fa fa-search" style="font-size:16px"></i> Search</button></center> -->       
        </form>    
    </div>
    <div class="col-sm-3 text-center">
    <?php 
    if($val=='456'){
    ?>
      <a href="incoming_letter.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>  
      <?php } else if($val=='987'){ ?> 
      <a href="../dashboard.php" class="btn btn-primary text-center"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>
      <?php } ?> 

        <!-- <a href="../dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left" style="font-size:16px"></i> <span>Back</span></a>
 -->
        <hr>
        <button type="button" class="btn btn-danger" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
    </div>
</div>

<!-- Printable Area -->
<div id="printableArea">
    <table class="table table-bordered table-striped text-center">
        <thead class="thead-dark">
            <tr>
                <th>ক্রম</th>
                 <?php 
                if($val!='987'){
                ?>
                <th>প্রেরিত দপ্তর</th>
                <?php }
                ?>
                <?php 
                if( ($table_name == 'chairman' &&$val=='987') || ($table_name == 'chairman' &&$val=='456') || $val=='456') {
                ?>
                <th>পত্র প্রাপ্তি তারিখ</th>
                <?php } ?>
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
        <?php
        function englishToBanglaNumber($number) {
            $englishNumbers = range(0, 9);
            $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
            return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
        } 

if (isset($_POST['submit'])) {
    $from_date = $_POST['date1'];
    $to_date = $_POST['date2'];
    $division_bn = isset($_POST['division_bn']) ? $_POST['division_bn'] : ''; // Check if 'division_bn' exists
    $table_name = $_POST['table_name'];    
    // Fetch val from GET (since it's passed through URL)
    // $val = isset($_GET['val']) ? $_GET['val'] : '';
    $val = $_POST['val'];
    if ($val == '987') {
        // Query if val == 987
        $base_query = "SELECT * FROM $table_name WHERE `entry_date` BETWEEN '$from_date' AND '$to_date' AND immediate_sender_office = ''";
    } else {
        // Query if val != 987
        $base_query = "SELECT * FROM $table_name WHERE `entry_date` BETWEEN '$from_date' AND '$to_date' AND immediate_sender_office != ''";
    }    
    // Add division_bn filter if it is provided
    if ($division_bn != '') {
        $base_query .= " AND FIND_IN_SET('$division_bn', destination_drop) > 0";
    }

    $result1 = mysqli_query($conn, $base_query);    
    if (mysqli_num_rows($result1) > 0) {
        $i = 1;
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $destination_drops = $row1['destination_drop'];
            $id = $row1['id'];
            $destination_drop = array_filter(explode(',', $destination_drops));
            $result = mysqli_query($conn, "SELECT * FROM $table_name WHERE id='$id'");
            while ($row = mysqli_fetch_assoc($result)) {
                $entry_date = $row["entry_date"];
                $send_date = $row["send_date"];
                $distribution_date = $row["distribution_date"];
                $destination = $row["destination"];
                $div_dept_office = $row["div_dept_office"];
                $sender = $row["sender"];
                if ($div_dept_office != '') {
                    $sender = $sender . ', ' . $div_dept_office;
                }
                // Convert array to string before applying rtrim
                $destination_drop = implode(', ', $destination_drop);
                $destination_drop = rtrim($destination_drop, ',');

                if ($destination != '') {
                    $destination_drop = $destination_drop . ', ' . $destination;
                }
                ?>            
                <tr id="<?php echo $row["id"]; ?>" class="text-center">             
                    <td><?php echo englishToBanglaNumber($i); ?></td>
                    <?php 
                    if($val!='987'){
                    ?>
                    <td><?php echo $row["immediate_sender_office"]; ?></td><?php }  ?>
                    <?php 
                 if( ($table_name == 'chairman' &&$val=='987') || ($table_name == 'chairman' &&$val=='456') || $val=='456') {
                      ?>
                    <td><?php echo englishToBanglaNumber($entry_date); ?></td><?php }  ?> 
                    <td><?php echo $row["recipient"]; ?></td>
                    <td><?php echo englishToBanglaNumber($row["d_number"]); ?></td>
                    <td><?php echo englishToBanglaNumber($row["ref_number"]); ?></td>                 
                    <td><?php echo englishToBanglaNumber($send_date); ?></td>
                    <td><?php echo $sender; ?></td>
                    <td><?php echo $row["subject"]; ?></td>                 
                    <td><?php echo $destination_drop; ?></td>
                    <td><?php echo englishToBanglaNumber($distribution_date); ?></td>
                    <td><?php echo $row["medium"]; ?></td>               
                </tr>
                <?php
                $i++;
            }
        }
    } else {
        // No records found message
        echo '<tr><td colspan="11" class="text-center text-danger">No records found for the selected date range.</td></tr>';
    }
}
mysqli_close($conn);
?>
        </tbody>
    </table>
</div>

<script> 
document.getElementById('print').addEventListener('click', function() {
    // Get the content to be printed
    var printContents = document.getElementById('printableArea').innerHTML;
    // Define the title
    var title = `
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
        <img src="bcic_logo.png" alt="BCIC Logo" style="max-width: 60px; margin-right: 20px;">
        <div style="text-align: center;">
            <h4 class="text-uppercase m-0" style="margin-bottom: 5px;">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h4>
            <h5 class="text-uppercase" style="margin-top: 0; margin-bottom: 0px;">পত্র প্রাপ্তি রেজিস্টার</h5>
            <p style="margin-top: 0; margin-bottom: 0;"> দপ্তর : <?php echo $office_title; ?> </p>
            <?php if (isset($_POST['submit'])) { ?>
                <p class=" text-center m-0" style="margin-top: 0; margin-bottom: 0;">
                    তারিখ : <?php echo englishToBanglaNumber(date('d-m-Y', strtotime($_POST['date1']))); ?>
                
                    থেকে: <?php echo englishToBanglaNumber(date('d-m-Y', strtotime($_POST['date2']))); ?>
                </p>
            <?php } ?>
        </div>
    </div>
    `;
    // var title = '<h1 class="text-center">পত্র প্রাপ্তি রেজিস্টার</h1>';
    // Create a container for the content to be printed
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
        <head>
            <title>পত্র প্রাপ্তি রেজিস্টার</title>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                @font-face {
                    font-family: 'Nikosh', Times, serif;
                    src: url(Nikosh.ttf);
                }

                .imgcontainer {
                    text-align: center;
                    margin: 5px 0 12px 0;
                    position: relative;
                }

                img.avatar {
                    width: 25%;
                    border-radius: 50%;
                }

                * {
                    font-family: 'Open Sans', sans-serif;
                    font-family: 'Tiro Bangla', serif;
                    font-family: 'Nikosh', sans-serif;
                }
            </style>
        </head>
        <body>
            ${title}
            ${printContents}
        </body>
        </html>
    `;

    // Trigger the print dialog
    window.print();
    // Restore the original contents of the page after printing
    document.body.innerHTML = originalContents;    
    // Reload the page to reflect the original content and avoid any loss of functionality
    window.location.reload();
});
</script>
<?php
include('footer.php');
?>
