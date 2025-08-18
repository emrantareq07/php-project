<?php
session_start();
ob_start(); // Start output buffering
$role = $_SESSION['role'];
?>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<div class="container-fluid p-0 my-2 bg-white">
    <div class="d-flex justify-content-end mb-3">
        <button onclick="window.print()" class="btn btn-danger" id="printButton"> 
            <i class="fa fa-print" style="font-size:16px"></i> Print
        </button>

        <?php 
        if ($role == 'user') {
        ?>
            <button onclick="window.location.href='user-dashboard.php'" class="btn btn-primary ms-2" id="backButton"> 
                <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page
            </button>
        <?php
        } else {
        ?>
            <button onclick="window.location.href='admin-dashboard.php'" class="btn btn-primary ms-2" id="backButton"> 
                <i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page
            </button>
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
$k = 0;
if (isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
    include_once(ROOT_PATH . '/libs/function.php');
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

    foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
    }

    if (isset($_GET['val'])) {
        $val = $_GET['val'];         
    }

    if ($val == 'PTAREQ!%b@IWORNE**7') {
        $val = 'এসএফসিএল';
    } elseif ($val == '@!TAREQ!%b@IWORNE**7') {
        $val = 'জেএফসিএল';
    } elseif ($val == '1*22SHANETAREQ3') {
        $val = 'জিপিএফপিএলসি';
    } elseif ($val == 'PBCICH.O.!%b@IWORNE**7') {
        $val = 'বিসিআইসি প্রধান কার্যালয়';
    } elseif ($val == '3*22SKNMLQ*%3') {
        $val = 'কেএনএমএল';
    } elseif ($val == '5*22$&S$%#KHBMLQ$*3') {
        $val = 'কেএইচবিএম';
    } elseif ($val == '7*22S@@CCCQ*3') {
        $val = 'সিসিসি';
    } elseif ($val == '1*22$*%^CCCLLQ$*3') {
        $val = 'সিসিসিএল';
    } elseif ($val == '1*22SAFCCLRQ%3') {
        $val = 'এএফসিসিএল';
    } elseif ($val == '5*22SH***TSPCLREQ$3') {
        $val = 'টিএসপিসিএল';
    } elseif ($val == '8*22SH$$$CUFLEQ*3') {
        $val = 'সিইউএফএল';
    } elseif ($val == '1*22S@DAPFCLEQ%3') {
        $val = 'ডিএপিএফসিএল';
    } elseif ($val == '3*22SHDLCLQ*3') {
        $val = 'ডিএলসিএল';
    } elseif ($val == '0*22@@@SKPMLAREQ3') {
        $val = 'কেপিএমএল';
    } elseif ($val == '6*22SHA^^UGSFLAEQ)3') {
        $val = 'ইউজিএসএফএল';
    } elseif ($val == '1*22SHA!TICIEQ@3') {
        $val = 'টিআইসিআই';
    }
    elseif ($val == '6*22S@@CCCQ*5') {
        $val = 'বিআইএসএফএল';
    }
    elseif ($val == '8*22SHANETAREQ') {
        $val = '123';
    } else {
        session_destroy();
        header("Location: index.php");
        ob_end_flush(); // Flush output buffer and turn off output buffering
        exit(); // Terminate the script immediately after redirection
    }
}

if($val=='123'){
    $query_msg = "SELECT * FROM land_tbl";
}
else{
    $query_msg = "SELECT * FROM land_tbl WHERE org_name='$val'";
}

// $query_msg = "SELECT * FROM land_tbl WHERE org_name='$val'";
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

if ($val == '123') {
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
<!-- Continue with your HTML and PHP code here -->
<div class="bs-example bg-white" style="background-color: white">
<div class="container-fluid p-0 my-0 bg-white">
    <table class="table table-bordered  table-hover table-striped shadow-lg" style="width: 100%; overflow-y: auto;" >
       <?php if($k != 1) { ?>
        <div class="page-break"></div>
        <?php } ?>
        <div style="text-align:center;" class="div2">
            <h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3>
            <h5 class="text-decoration-none">প্রতিষ্ঠানের নাম: <?php echo $org_name?></h5>
            <h6>অবস্থান: <?php echo $address?></h6>
            <h6 class="mb-0">প্রতিষ্ঠাকাল: <?php echo $establish_year?></h6>
            <h5 class="text-decoration-none mb-0"  style="margin-bottom:0px;">[--<?php echo $land_type?>--]</h5>
        </div>            
        <thead class="table-success text-center">
            <tr>             
                <th rowspan="2" class="sl-fixed-width small-text " style="">ক্র. নং</th>
                <th rowspan="2" class="half-fixed-width small-text">বিভাগ</th>
                <th rowspan="2" class="half-fixed-width small-text">জেলা</th>
                <th rowspan="2" class="half-fixed-width small-text">উপজেলা/থানা</th>
                <th rowspan="2" class="half-fixed-width small-text"> জেএল নং</th>
                <th rowspan="2" class="owner-fixed-width small-text">মৌজা</th>
                <th rowspan="2" class="owner-fixed-width small-text">মালিকের নাম</th>
                <th colspan="4" class="fixed-width small-text">খতিয়ান নং</th>
                <th colspan="4" class="fixed-width small-text">দাগ নং</th>
                <th rowspan="2" class=" small-text">জমির পরিমান(একর)</th>
                <th rowspan="2" class=" small-text">মালিকানা সূত্র(এল এ কেস নং/দলিল নং..)</th>
                <th rowspan="2" class=" small-text">নামজারি কেস নং ও তাং এবং রেকর্ড সংশোধিত হয়েছে কিনা? </th>
                <th rowspan="2" class="small-text">ভূমি উন্নয়ন কর পরিশোধের বিবরণ</th>
                <th rowspan="2" class=" small-text">প্রাচীর/বেড়া</th>
            </tr>
            <tr>
                <th class="fixed-width small-text">সিএস</th>
                <th class="fixed-width small-text">আরএস</th>
                <th class="fixed-width small-text">এএস</th>
                <th class="fixed-width small-text">বিএস</th>
                <th class="fixed-width small-text">সিএস</th>
                <th class="fixed-width small-text">আরএস</th>
                <th class="fixed-width small-text">এএস</th>
                <th class="fixed-width small-text">বিএস</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row6 = mysqli_fetch_assoc($result1)) { ?>
            <tr>
                <td class="sl-fixed-width small-text"><?= englishToBanglaNumber($i); ?></td>
                <td class="half-fixed-width small-text"><?= $row6['division']; ?></td>
                <td class="half-fixed-width small-text"><?= $row6['district']; ?></td>
                <td class="half-fixed-width small-text"><?= $row6['upazilla_thana']; ?></td>
                <td class="half-fixed-width small-text"><?= $row6['jl_no']; ?></td>
                <td class="owner-fixed-width small-text"><?= $row6['mouza']; ?></td>
                <td class="owner-fixed-width small-text"><?= $row6['org_name']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_cs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_rs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_as']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_bs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_cs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_rs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_as']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_bs']; ?></td>
                <td class="landsize-fixed-width small-text"><?= $row6['land_size']; ?></td>
                <td class="lg-fixed-width small-text"><?= $row6['proprietary_source']; ?></td>
                <td class="namjaree-fixed-width small-text"><?= $row6['namjaree_caseno_date']; ?></td>
                <td class="lg-fixed-width small-text"><?= $row6['land_development_taxpayment']; ?></td>
                <td class="boundary-fixed-width small-text"><?= $row6['boundary_wall']; ?></td>
            </tr>
            <?php $i++; ?>
        <?php } ?>
        <?php $k++; }  } ?>
        </tbody>
    </table>
</div>
</div>
<?php 
}
else {
    $k = 1;
    $query_landtype = "SELECT DISTINCT land_type FROM land_tbl WHERE org_name='$val'";
    $result_landtype = mysqli_query($conn, $query_landtype);

    while ($row_landtype = mysqli_fetch_assoc($result_landtype)) {
        $land_type = $row_landtype['land_type'];
        $i = 0;
        $query1 = "SELECT * FROM land_tbl, org_tbl WHERE land_tbl.org_name=org_tbl.org_name AND land_tbl.org_name='$val' AND land_type='$land_type'";
        $result1 = mysqli_query($conn, $query1);

        $query_all = "SELECT * FROM land_tbl, org_tbl WHERE land_tbl.org_name=org_tbl.org_name AND land_tbl.org_name='$val' AND land_type='$land_type'";
        $result_all = mysqli_query($conn, $query_all);
        $row_all = mysqli_fetch_assoc($result_all);
        $org_name = $row_all['org_name'];
        $land_type = $row_all['land_type'];
        $address = $row_all['address'];
        $establish_year = $row_all['establish_year'];

        $i = 1;
?>
<div class="bs-example bg-white" style="background-color: white">
<div class="container-fluid p-0 my-0 bg-white">
    <table class="table table-bordered table-hover table-striped " style="width: 100%;  overflow-y: auto;">
        <?php if($k != 1) { ?> 
        <div class="page-break"></div>
        <?php } ?>
        <div style="text-align:center;" class="div2">
            <h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3>
            <h5 class="text-decoration-none">প্রতিষ্ঠানের নাম: <?php echo $org_name?></h5>
            <h6>অবস্থান: <?php echo $address?></h6>
            <h6 class="mb-0">প্রতিষ্ঠাকাল: <?php echo $establish_year?></h6>
            <h5 class="text-decoration-none mb-0 text-center"  style="margin-bottom: 0">[--<?php echo $land_type?>--]</h5>
        </div>
            
        <thead class="table-success text-center mb-0" id="thead2">
            <tr>             
                <th rowspan="2" class="sl-fixed-width small-text " style="">ক্র. নং</th>
                <th rowspan="2" class="half-fixed-width small-text">বিভাগ</th>
                <th rowspan="2" class="half-fixed-width small-text">জেলা</th>
                <th rowspan="2" class="half-fixed-width small-text">উপজেলা/থানা</th>
                <th rowspan="2" class="half-fixed-width small-text"> জেএল নং</th>
                <th rowspan="2" class="owner-fixed-width small-text">মৌজা</th>
                <th rowspan="2" class="owner-fixed-width small-text">মালিকের নাম</th>
                <th colspan="4" class="fixed-width small-text">খতিয়ান নং</th>
                <th colspan="4" class="fixed-width small-text">দাগ নং</th>
                <th rowspan="2" class=" small-text">জমির পরিমান(একর)</th>
                <th rowspan="2" class=" small-text">মালিকানা সূত্র(এল এ কেস নং/দলিল নং..)</th>
                <th rowspan="2" class=" small-text">নামজারি কেস নং ও তাং এবং রেকর্ড সংশোধিত হয়েছে কিনা? </th>
                <th rowspan="2" class="small-text">ভূমি উন্নয়ন কর পরিশোধের বিবরণ</th>
                <th rowspan="2" class=" small-text">প্রাচীর/বেড়া</th>
            </tr>
            <tr>
                <th class="fixed-width small-text">সিএস</th>
                <th class="fixed-width small-text">আরএস</th>
                <th class="fixed-width small-text">এএস</th>
                <th class="fixed-width small-text">বিএস</th>
                <th class="fixed-width small-text">সিএস</th>
                <th class="fixed-width small-text">আরএস</th>
                <th class="fixed-width small-text">এএস</th>
                <th class="fixed-width small-text">বিএস</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row6 = mysqli_fetch_assoc($result1)) { ?>
            <tr>
                <td class="sl-fixed-width small-text"><?= englishToBanglaNumber($i); ?></td>
                <td class="half-fixed-width small-text"><?= $row6['division']; ?></td>
                <td class="half-fixed-width small-text"><?= $row6['district']; ?></td>
                <td class="half-fixed-width small-text"><?= $row6['upazilla_thana']; ?></td>
                <td class="half-fixed-width small-text"><?= $row6['jl_no']; ?></td>
                <td class="owner-fixed-width small-text"><?= $row6['mouza']; ?></td>
                <td class="owner-fixed-width small-text"><?= $row6['org_name']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_cs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_rs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_as']; ?></td>
                <td class="fixed-width small-text"><?= $row6['khatian_bs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_cs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_rs']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_as']; ?></td>
                <td class="fixed-width small-text"><?= $row6['dag_bs']; ?></td>
                <td class="landsize-fixed-width small-text"><?= $row6['land_size']; ?></td>
                <td class="lg-fixed-width small-text"><?= $row6['proprietary_source']; ?></td>
                <td class="namjaree-fixed-width small-text"><?= $row6['namjaree_caseno_date']; ?></td>
                <td class="lg-fixed-width small-text"><?= $row6['land_development_taxpayment']; ?></td>
                <td class="boundary-fixed-width small-text"><?= $row6['boundary_wall']; ?></td>
            </tr>
            <?php $i++; ?>
        <?php } ?>
        <?php $k++; } $k = 1; } 
            // }
        ?>
        <?php
//         }
//     }
// }
ob_end_flush(); // Flush output buffer and turn off output buffering
?>
        </tbody>

    </table>  
</div>
</div>

<style>
    @media print {
    #printButton, #backButton {
        display: none;
    }
    body {
        margin-top: 0px;
        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
    }
    table {
        width: 100%;
        font-size: 10px;
        border-spacing: 0;
        border-collapse: collapse;
        color: black; 
    }
    thead {
        display: table-header-group; 
    }
  /*  thead::before {
        content: '';
        display: block;
        margin-top: 10mm; 
    }*/
    th, td {
        padding: 0px; 
        text-align: center;
    }
/* Default to A4 portrait size */
  /*  @page {
        size: A4;
        margin: 7mm 2mm 10mm 2mm; /* Adjust margin as needed */
    }*/

    /* Uncomment the following section to use A4 landscape size */
    
    @page {
        size: A4 landscape;
        margin: 7mm 2mm 10mm 2mm;
    }
    

    /* Uncomment the following section to use Letter portrait size */
    
   /* @page {
        size: Letter;
        margin: 7mm 2mm 10mm 2mm;
    }*/    

    /* Uncomment the following section to use Letter landscape size */
    
    @page {
        size: Letter landscape;
        margin: 7mm 2mm 10mm 2mm;
    } 
    .page-break {
    clear: both;
    page-break-before: always;
    margin-bottom:  40px;
    
    }  
}
    table {
        width: 100%;
    }
    th, td {
        text-align: center;
        word-break: break-all;
        padding: 0px;
        border-spacing: 0;
    }
    .fixed-width {
        width: 80px;
    }
    .owner-fixed-width {
        width: 100px;
    }
    .half-fixed-width {
        width: 50px;
    }
    .sl-fixed-width {
        width: 30px;
    }
    .lg-fixed-width {
        width: 120px;
    }
    .namjaree-fixed-width {
        width: 100px;
    }
    .landsize-fixed-width {
        width: 80px;
    }
    .boundary-fixed-width {
        width: 40px;
    }
    .small-text {
        font-size: 11px;
    }
    </style>
<!-- <script>
function applyPageLastRowClass() {
    const rows = document.querySelectorAll('tbody tr');
    const rowsPerPage = 20; // Adjust based on your specific layout

    rows.forEach((row, index) => {
        if ((index + 1) % rowsPerPage === 0) {
            row.classList.add('page-last-row');
        }
    });
}

window.onload = applyPageLastRowClass;
</script> -->