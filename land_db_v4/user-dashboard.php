<?php
session_start();
$code = $_SESSION['code'];  
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";

      $result= $usercredentials->runBaseQuery($query);

      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
      ?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- basic -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- mobile metas -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<!-- site metas -->
<title>BCIC Land Database</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content=""> 
<!-- bootstrap css -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<!-- style css -->
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- Responsive-->
<link rel="stylesheet" href="css/responsive.css">
<!-- fevicon -->
<link rel="icon" href="images/fevicon.png" type="image/gif" />
<!-- Scrollbar Custom CSS -->
<link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
<!-- Tweaks for older IEs-->
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<!-- owl stylesheets --> 
<link rel="stylesheet" href="css/owl.carousel.min.css">
<link rel="stylesheet" href="css/owl.theme.default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Icon Font Stylesheet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik+Gemstones&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nova+Slim&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Nova+Slim&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Nova+Slim&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&family=Pacifico&display=swap" rel="stylesheet">
  <style>

    * {
      font-family: 'Open Sans', sans-serif;

        font-family: 'Tiro Bangla', serif;
    /*  font-family: 'Noto Sans Bengali', sans-serif;*/

        font-family: 'Nikosh', sans-serif;

        font-family: 'Nikosh', serif;
    }
    .pacifico-regular {
      font-family: "Pacifico", cursive;
      font-weight: 400;
      font-style: normal;
    }

    .ga-maamli-regular {
      font-family: "Ga Maamli", sans-serif;
      font-weight: 500;
      font-style: normal;
    }

    .rubik-gemstones-regular {
        font-family: "Rubik Gemstones", system-ui;
        font-weight: 400;
        font-style: normal;
      }
      .nova-slim-regular {
        font-family: "Nova Slim", system-ui;
        font-weight: 400;
        font-style: normal;
      }
      .nosifer-regular {
        font-family: "Nosifer", sans-serif;
        font-weight: 400;
        font-style: normal;
      }

      .sedgwick-ave-display-regular {
        font-family: "Sedgwick Ave Display", cursive;
        font-weight: 400;
        font-style: normal;
      }

    .dropdown-menu-columns {
          columns: 2;
          column-gap: 1rem;
      }
  </style>
<link rel="icon" type="image/gif/png" href="images/BCIC_logo.jpg">
</head>
<body>
<?php include_once"includes/topbar.php";?>
  <!--banner section start -->
  <div class="banner_section layout_padding">
    <div class="container">
      <h1 class="banner_taital text-uppercase"> Land Database</h1>
      <h1 class="text-center text-uppercase text-white rubik-gemstones-regular " style="font-size: 36px;">Bangladesh Chemical Industries Corporation</h1>

           <!--  <div class="read_bt">
              <div class="read_text"><a href="#">EMD</a></div>
            </div> -->

    <div class="read_bt my-0">
        <a href="#">
            <img src="images/1.png" height="100" width="100">
            <span class="read_text my-0 nova-slim-regular" style="color: white; font-size: 32px;"> (EMD)</span>
        </a>
    </div>


    </div>
  </div>
  <!--banner section end -->
  <!--bg_main section start -->
  <div class="container">
    <div class="bg_main"><img src="images/bg-main.png"></div>
  </div>
  <!--bg_main section end -->
  <!--about section start -->
  <div class="layout_padding" id="about_us">
    <div class="container" >
      <h1 class="about_text ">about BCIC</h1><center><img src="images/about bcic.jpeg" class="img-fluid shadow-lg rounded img-thumbnail " alt="Sample image" width="500" height="400" align="middle"></center>
      
      <p class="text-justify" >Bangladesh Chemical Industries Corporation (BCIC), fully owned by the Government, was established in July, 1976 by a presidential Order. The Corporation is now managing 13 large and medium size industrial enterprises engaged in producing a wide range of products like Urea, TSP, DAP, Paper, Cement, Glass sheet, Hardboard, Sanitary ware & Insulator etc
      Bangladesh Chemical Industries Corporation (BCIC) housed in its own 20 storied building at 30-31, Dilkusha C/A, Dhaka is one of the largest conglomerates of the country, fully owned by the Govt. came into being on 1st July 1976 through merger of three erstwhile corporations viz Bangladesh Fertilizer, Chemical and Pharmaceutical Corporation (BFCPC), Bangladesh Paper and Board Corporation (BPBC) and Bangladesh Tanneries Corporation (BTC). Bangladesh Chemical Industries Corporation is entrusted with the task of supervision, co-ordination & control of the enterprises under its management & for developing new industries in the Chemical & allied Sector.
      BCIC started functioning in 1976 with 88 nationalized enterprises under its administrative control. Since then as per Govt. policy a large number of small & medium size enterprises have been either been sold out to the private entrepreneurs transfered to their former owners. At present BCIC is managing 13 large & medium size industrial enterprises- Eight fertilizer factories, one Paper mill & Four other chemical & allied industrial units producing Urea,TSP, DAP, Paper, Cement, Glass sheet, Hardboard, Sulphuric Acid, Sanitary ware, Insulator, Tiles & Fire bricks etc. </p>
      <!-- <div class="about_bg"><img src="images/about-bg.png"></div>
      <p class="lorem_text">orem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit</p>
      <div class="read_bt_main">
              <div class="read_text_2"><a href="#">Read More</a></div>
            </div> -->
    </div>
  </div>
  <!--about section end -->
  <!--service section start -->
  <div class="service_section layout_padding" id="developer_info">
    <div class="container">
      <h1 class="about_text text-uppercase text-info">Developer Information</h1>
      <!-- <p class="ipsum_text"></p> -->
      <div class="service_section_2">
        <div class="row">
            <div class="col-sm-12 col-lg-4 text-center">
              <div class="icon_1"><img class="img-fluid w-100" style="height: 300px; width: 50px;" src="images/002.jpg" alt=""></div>
              <h1 class="website_text">MD.TAREQ EMARN</h1>
                <span class="card-text text-center">PROGRAMMER, </span>
                  <!-- <span class="card-text">ICT DIVISION </span> -->
                  <span class="card-text text-center">01671583637 emrantareq09@gmail.com</span>
            </div>
            <div class="col-sm-12 col-lg-4">
              <div class="icon_1"><img src="images/icon-2.png"></div>
              <h1 class="website_text">Ict Division</h1>
              <p class="dolor_text">BCIC Head Office, 30-31 Dilkusha C/A, Motijheel, Dhaka</p>
            </div>
            <div class="col-sm-12 col-lg-4 text-center">
              <div class="icon_1"><img class="img-fluid w-100" style="height: 300px; width: 50px;" src="images/001.jpg" alt=""></div>
              <h1 class="website_text">SHANEWORN BHADRA</h1>
              <span class="card-text text-center">ASSISTANT PROGRAMMER, </span>
                <!-- <span class="card-text">ICT DIVISION, </span> -->
                <span class="card-text text-center">01878072812 shanewornbhadra@gmail.com</span>
            </div>
          </div>
      </div>
    </div>
  </div>
  <!--service section end -->
  <!--contact section start -->
  <div class="contact_section layout_padding" id="statistics">
    <div class="container mt-3">

<?php
function convertBengaliToArabic($number) {
    $bengali_digits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $arabic_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($bengali_digits, $arabic_digits, $number);
}
function convertArabicToBengali($number) {
    $arabic_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bengali_digits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($arabic_digits, $bengali_digits, $number);
}
?>
<!-- <div class="container-fluid "> -->
  <h1 class="about_text">Land Statistics</h1>
    <table class="table table-bordered  shadow rounded " width="100%">
        <thead class="table-dark">
            <tr class="text-center">
                <th>প্রতিষ্ঠান </th>
                <th>মোট জমির পরিমান (একর)</th>
                <th>ব্যক্তি মালিকানা (একর)</th>
                <th>খাস জমি (একর)</th>
                <th>লীজকৃত জমি (একর)</th>
                <th>মালিকাধীন (একর)</th>
                <th>ক্রয়কৃত জমি (একর)</th>
                <th>অধিগ্রহনকৃত জমি (একর)</th>
            </tr>
        </thead>
        <tbody class="table-info fw-bold  fs-2 text ipsum_text ">
            <?php
            $query = "SELECT org_name, land_type, land_size FROM land_tbl";
            $result = mysqli_query($conn, $query);

            $land_data = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $org_name = $row['org_name'];
                $land_type = $row['land_type'];
                $land_size = floatval(convertBengaliToArabic($row['land_size']));

                if (!isset($land_data[$org_name])) {
                    $land_data[$org_name] = [
                        'ব্যক্তি মালিকানা' => 0,
                        'খাস জমি' => 0,
                        'লীজকৃত জমি' => 0,
                        'মালিকাধীন' => 0,
                        'ক্রয়কৃত জমি' => 0,
                        'অধিগ্রহনকৃত জমি' => 0,
                    ];
                }

                $land_data[$org_name][$land_type] += $land_size;
            }

            foreach ($land_data as $org_name => $land_sizes) {
                $total_land = array_sum($land_sizes);
                echo "<tr>
                    <td class='fw-bold'>{$org_name}</td>
                    <td>" . convertArabicToBengali((string)$total_land) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['ব্যক্তি মালিকানা']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['খাস জমি']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['লীজকৃত জমি']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['মালিকাধীন']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['ক্রয়কৃত জমি']) . "</td>
                    <td>" . convertArabicToBengali((string)$land_sizes['অধিগ্রহনকৃত জমি']) . "</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
<!-- </div> -->
    
</div>
</div>
 
  <!--footer section start -->
  <div class="footer_section layout_padding">
    <div class="container">
      <div class="footer_main">
        <div class="input-group mb-3 text-center">
          <input type="text" class="form-control text-center" placeholder="Contact information:- Email: emd@bcic.gov.bd, Phone No.-" aria-label="Recipient's username" aria-describedby="basic-addon2">
          <div class="input-group-append">
            <!-- <span class="input-group-text" id="basic-addon2">subscribe Now</span> -->
          </div>
      </div>

      <h1 class="year_text"><?php echo date("Y"); ?></h1>
      <h1 class="landing_text">Design & Developed by ICT Division, BCIC. </h1>
      </div>
      <div class="social_icon">
        <ul>
          <li><a href="#"><img src="images/fb-icon.png"></a></li>
          <li><a href="#"><img src="images/twitter-icon.png"></a></li>
          <li><a href="#"><img src="images/linkdin-icon.png"></a></li>
          <li><a href="#"><img src="images/instagram-icon.png"></a></li>
          <li><a href="#"><img src="images/youtub-icon.png"></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!--footer section end -->
  <!--copyright section start -->
  <!-- <div class="copyright_section">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">

          <p class="copyright_text">Design & Developed by ICT Division, BCIC.</p>
        </div>
      </div>
    </div>
  </div> -->
  <!--copyright section end -->
   <!-- Javascript files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script src="js/plugin.js"></script>
    <!-- sidebar -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- javascript --> 
    <script src="js/owl.carousel.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script>
    $(document).ready(function(){
    $(".fancybox").fancybox({
        openEffect: "none",
        closeEffect: "none"
        });
    </script>
</body>
</html>
<?php } else{
  // header('location:login/logout.php');
   header('location:index.php');
  } 
?>