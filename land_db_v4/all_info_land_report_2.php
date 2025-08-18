<?php
session_start();
$role=$_SESSION['role'];
?>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<div class="container-fluid p-0 my-2 bg-white">
    <div class="d-flex justify-content-end mb-3">
        <button onclick="window.print()" class="btn btn-danger" id="printButton"> <i class="fa fa-print" style="font-size:16px"></i> Print</button>

        <?php 
        if($role=='user'){
            ?>
            <button onclick="window.location.href='user-dashboard.php'" class="btn btn-primary" id="backButton"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</button>
        <?php
        }else{
            ?>
        <button onclick="window.location.href='admin-dashboard.php'" class="btn btn-primary" id="backButton"> <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</button>
        <?php
        }
        ?>        
    </div>
</div>

<?php

function englishToBanglaNumber($number) {
    $englishNumbers = range(0, 9);
    $banglaNumbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    return mb_convert_encoding($number, 'UTF-8', 'ASCII') ? str_replace($englishNumbers, $banglaNumbers, $number) : $number;
}

require_once("config/config.php");
require_once("db/db.php");
include_once"includes/header.php";

$val = 0;
$k=0;
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
        $val = 'এসএফসিএল';
    }
    elseif($val == '@!TAREQ!%b@IWORNE**7'){
        $val = 'জেএফসিএল';
    }
    elseif($val == '1*22SHANETAREQ3'){
        $val = 'জিপিএফপিএলসি';
    }
    elseif($val == 'PBCICH.O.!%b@IWORNE**7'){
        $val = 'বিসিআইসি প্রধান কার্যালয়';
    }elseif($val == '3*22$&SKNMLQ$*&%3'){
        $val = 'কেএনএমএল';
    }elseif($val == '5*22$&S$%#KHBMLQ$*3'){
        $val = 'কেএইচবিএম';
    }elseif($val == '7*22$&S$%CCCQ$*3'){
        $val = 'সিসিসি';
    }elseif($val == '1*22$*%^CCCLLQ$*3'){
        $val = 'সিসিসিএল';
    }elseif($val == '1*22SHAN##AFCCLRQ%3'){
        $val = 'এএফসিসিএল';
    }elseif($val == '5*22SH***TSPCLREQ$3'){
        $val = 'টিএসপিসিএল';
    }elseif($val == '8*22SH$$$CUFLEQ*3'){
        $val = 'সিইউএফএল';
    }elseif($val == '1*22S&&&DAPFCLEQ%3'){
        $val = 'ডিএপিএফসিএল';
    }elseif($val == '3*22$&SHDLCLQ$*3'){
        $val = 'ডিএলসিএল';
    }elseif($val == '0*22@@@SKPMLAREQ#3'){
        $val = 'কেপিএমএল';
    }
    elseif($val == '6*22SHA^^UGSFLAEQ)3'){
        $val = 'ইউজিএসএফএল';
    }
    elseif($val == '1*22SHANETAREQ'){
        $val = '123';
    }
    else{
        session_destroy();
        ob_end_flush(); // Flush output buffer and turn off output buffering
        header("Location: index.php");
        exit(); // Terminate the script immediately after redirection
    }
}

$query_msg = "SELECT * FROM land_tbl WHERE org_name='$val'";
$resultquery_msg = mysqli_query($conn, $query_msg);

if (mysqli_num_rows($resultquery_msg) == 0) {
    $redirectPage = ($role == 'user') ? 'user-dashboard.php' : 'admin-dashboard.php';
    
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


if( $val == '123'){
// $query1 = "SELECT * FROM land_tbl";
// $result1 = mysqli_query($conn, $query1);

    $query13 = "SELECT DISTINCT org_name FROM land_tbl";
    $result13 = mysqli_query($conn, $query13);
    $k = 1;
    while ($row13 = mysqli_fetch_assoc($result13)) {
        $org_name = $row13['org_name'];
        
    $query_landtype = "SELECT DISTINCT land_type FROM land_tbl WHERE org_name='$org_name'";
    $result_landtype = mysqli_query($conn, $query_landtype);

    while ($row_landtype = mysqli_fetch_assoc($result_landtype)) {
        $land_type = $row_landtype['land_type'];
        $i = 0;
        $query1 = "SELECT * FROM land_tbl, org_tbl WHERE land_tbl.org_name=org_tbl.org_name AND land_tbl.org_name='$org_name' AND land_type='$land_type'";
        $result1 = mysqli_query($conn, $query1);

        $query_all = "SELECT * FROM land_tbl, org_tbl WHERE land_tbl.org_name=org_tbl.org_name AND land_tbl.org_name='$org_name' AND land_type='$land_type'";
        $result_all = mysqli_query($conn, $query_all);
        $row_all = mysqli_fetch_assoc($result_all);
        $org_name = $row_all['org_name'];
        $land_type = $row_all['land_type'];
        $address = $row_all['address'];
        $establish_year = $row_all['establish_year'];

        $i = 1;


?>