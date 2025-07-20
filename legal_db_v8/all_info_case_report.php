<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<?php
session_start();
ob_start(); // Start output buffering
$role = $_SESSION['role'];

function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
}

require_once("config/config.php");
require_once("db/db.php");
include_once"includes/header.php";

$val = 0;
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
    include_once(ROOT_PATH.'/libs/function.php');
    $usercredentials = new DB_con();

    //fetching username from either session or cookies condition
    $uname = $uun = $uup = "";
    if (isset($_SESSION["uname"])) {
        $uname  = $_SESSION['uname'];
    }
    if (isset($_COOKIE['user_login'])) {
        $uname  = $_COOKIE['user_login'];
    } 

    $query = "SELECT * FROM tblusers WHERE Username='$uname'";
    $result = $usercredentials->runBaseQuery($query);

    foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
    }

    if(isset($_GET['val'])){
        $val = $_GET['val']; 
    }
    
    ob_start(); // Start output buffering
    include_once "includes/header.php";

    if($val == 'PTAREQ!%b@IWORNE**7'){
        $val = 'বিচারাধীন';
    }
    elseif($val == '@!TAREQ!%b@IWORNE**7'){
        $val = 'পক্ষে';
    }
    elseif($val == '1**2SHANETAREQ'){
        $val = 'বিপক্ষে';
    }
        elseif($val == '1*5*2SHANE*TAREQ'){
        $val = '123';
    }
    else{
        session_destroy();
        ob_end_flush(); // Flush output buffer and turn off output buffering
        header("Location: index.php");
        exit(); // Terminate the script immediately after redirection
    }
}

if( $val == '123'){
    $val = 'মোট';
    $query1 = "SELECT * FROM legal_tbl GROUP BY id, hearing_result ORDER BY FIELD(hearing_result, 'বিচারাধীন', 'পক্ষে', 'বিপক্ষে')";
    $result1 = mysqli_query($conn, $query1);
}
else{
    $query1 = "SELECT * FROM legal_tbl WHERE hearing_result='$val' ORDER BY case_date DESC";
    $result1 = mysqli_query($conn, $query1);
}

$i = 1;
// $query_total_inprogress= "SELECT count(*) total_inprogress_cases FROM legal_tbl WHERE hearing_result='$val'";
// $result_total_inprogress = mysqli_query($conn, $query_total_inprogress);
$row_total_inprogress=mysqli_num_rows($result1);

//$total_inprogress_cases=$row_total_inprogress['total_inprogress_cases'];
$total_inprogress_cases_bn=englishToBanglaNumber($row_total_inprogress);

if (mysqli_num_rows($result1) == 0) {
    $redirectPage = ($role == 'user') ? 'user-dashboard_details.php' : 'admin-dashboard.php';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script type="text/javascript">';
    echo 'Swal.fire({
            title: "NO Record Found!!!",            
            icon: "warning"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "' . $redirectPage . '";
            }
        });';
    echo '</script>';
}

 // if (mysqli_num_rows($result1) == 0) {
 //          echo '<script type="text/javascript">';
 //          echo 'alert("NO Record Found!!!");';
 //          // echo 'window.location.href="add_edu_info.php"';
 //          echo 'window.location.href="user-dashboard_details.php"';
 //          echo '</script>';
 //        } 
else {
?>

<div class="bs-example bg-white" style="background-color: white">
<div class="container-fluid p-2 my-3 bg-white">
    <div class="d-flex justify-content-end mb-3">
        <button onclick="window.print()" class="btn btn-danger" id="printButton"> <i class="fa fa-print" style="font-size:16px"></i> Print</button>

        <!-- <button onclick="window.location.href='user-dashboard_details.php'" class="btn btn-primary" id="backButton">  <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</button> -->

         <?php 
        if ($role == 'user') {
            ?>
            <button onclick="window.location.href='user-dashboard_details.php'" class="btn btn-primary" id="backButton"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</button>
        <?php
        } else {
            ?>
            <button onclick="window.location.href='admin-dashboard.php'" class="btn btn-primary" id="backButton"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</button>
        <?php
        }
        ?>    

    </div>
    <table class="table table-bordered table-striped table-hover" style="width: 100%; max-height: 400px; overflow-y: auto;">
        <thead class="table-success">
            <tr>
              <div style="text-align:center;margin-bottom:10px;"><h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3><h6>৩০-৩১ দিলকুশা, বা/এ ঢাকা-১০০০।</h6><h5 class="text-decoration-underline">বিভিন্ন আদালতে চলমান মামলার তথ্যাবলী</h5><h5 class="text-decoration-none">[--বিসিআইসি মামলার ডাটাবেস--]</h5>
                <h5 class="text-decoration-none"><?php echo $val?> মামলার সংখ্যা : <?php echo $total_inprogress_cases_bn?></h5>
              </div>

                <th>ক্রঃ নং</th>
                <th>প্রতিষ্ঠানের নাম</th>
                <th>মামলা নম্বর</th>
                <th>বাদী</th>
                <th>বিবাদী</th>
                <th>মামলার বিষয়বস্তু</th>
                <th>বিচারাধীন মহামান্য আদালত</th>
                <th>নিয়োজিত বিজ্ঞ আইনজীবীর নাম ও ঠিকানা</th>
                <th>মন্ত্রণালয়/দপ্তর /সংস্থা কর্তৃক গৃহীত কার্যক্রম</th>
                <th>মামলার বর্তমান অবস্থা</th> 
                <th>ধার্য্য তারিখ</th>
            </tr>
        </thead>
        <tbody>
        <?php        

        while ($row6 = mysqli_fetch_assoc($result1)) : 
            $next_hearing_result_date_db=$row6['next_hearing_result_date'];
            $next_hearing_result_date_bn=englishToBanglaNumber($next_hearing_result_date_db);

            ?>
            <tr>   
                <td><?= englishToBanglaNumber($i); ?></td>
                <td><?= $row6['org_name']; ?></td>  
                <td><?= $row6['concate3col']; ?></td>
                <td><?= $row6['plaintiff_name']; ?></td>  
                <td><?= $row6['defendant_name']; ?></td>
                <td><?= $row6['reason_of_case']; ?></td>  
                <td><?= $row6['court_type']; ?></td>
                <td><?= $row6['lower_name_address']; ?></td>     
                <td><?= $row6['case_filing_accept_steps']; ?></td>
                <td><?= $row6['hearing_result']; ?></td> 
                <td><?= $next_hearing_result_date_bn; ?></td> 
            </tr>
            <?php $i++; ?>
        <?php endwhile; }?>
        </tbody>
    </table>
</div>
</div>
<style>
    @media print {
        #printButton {
            display: none;
        }
 #backButton {
            display: none;
        }

    }
</style>
