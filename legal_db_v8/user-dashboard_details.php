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


//       $sql1 = "SELECT count(*) AS A FROM legal_tbl ";
// $query_run1 = mysqli_query($conn, $sql1);
// $row1 = mysqli_fetch_assoc($query_run1);
//     $data= $row1['A'];

    $query1 = "SELECT count(*) AS user_count FROM legal_tbl where hearing_result='পক্ষে' ";
$result1 = mysqli_query($conn, $query1);
$row1= mysqli_fetch_assoc($result1);
     $count1 = $row1['user_count'];

     $query2 = "SELECT count(*) AS user_count FROM legal_tbl where hearing_result like '%বিপক্ষে%'";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
     $count2 = $row2['user_count'];

     $query3 = "SELECT count(*) AS user_count FROM legal_tbl where hearing_result like '%বিচারাধীন%'";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
     $count3 = $row3['user_count'];

     $query4 = "SELECT count(*) AS user_count FROM legal_tbl";
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
     $count4 = $row4['user_count'];


$query5 = "SELECT distinct court_type FROM legal_tbl";
$result5 = mysqli_query($conn, $query5);
while ($row5 = mysqli_fetch_assoc($result5)) {

    $court_type = $row5['court_type'];

    if($court_type =='উচ্চ আদালত'){

      $sql5="SELECT hearing_result FROM legal_tbl where court_type='$court_type'";
      $result7 = mysqli_query($conn, $sql5);
       // $total_highcourt=0;
      $infavour_highcourt=0;
      $against_highcourt=0;
      $inprogress_highcourt=0;
      while ($row7 = mysqli_fetch_assoc($result7)) {
        $hearing_result = $row7['hearing_result'];
        if($hearing_result=='পক্ষে'){
          // $total_highcourt += 1;
      $infavour_highcourt += 1;

        }
        elseif($hearing_result=='বিপক্ষে'){
          // $total_highcourt += 1;
      $against_highcourt += 1;

        }
        elseif($hearing_result=='বিচারাধীন'){
         // $total_highcourt += 1;
      $inprogress_highcourt += 1;
          
        }       
      }      
    }
    if($court_type =='নিম্ন আদালত'){

      $sql6="SELECT hearing_result FROM legal_tbl where court_type='$court_type'";
      $result6 = mysqli_query($conn, $sql6);
       // $total_lowcourt=0;
      $infavour_lowcourt=0;
      $against_lowcourt=0;
      $inprogress_lowcourt=0;
      while ($row6 = mysqli_fetch_assoc($result6)) {
        $hearing_result = $row6['hearing_result'];
        if($hearing_result=='পক্ষে'){
          // $total_lowcourt += 1;
      $infavour_lowcourt += 1;

        }
        elseif($hearing_result=='বিপক্ষে'){
          // $total_lowcourt += 1;
        $against_lowcourt += 1;

        }
        elseif($hearing_result=='বিচারাধীন'){
         // $total_lowcourt += 1;
        $inprogress_lowcourt += 1;
          
        }
       
      }

    }
} 
// include_once"includes/header.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="bn">
<head>
    <meta charset="utf-8">
     <title>BCIC LEGAL</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="images/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/gif/png" href="images/BCIC_logo.jpg">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
      <style>
 
        * {
          font-family: 'Open Sans', sans-serif;

            font-family: 'Tiro Bangla', serif;
        /*  font-family: 'Noto Sans Bengali', sans-serif;*/

            font-family: 'Nikosh', sans-serif;

            font-family: 'Nikosh', serif;
        }
      </style>
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->
    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
             <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i> BCIC Bhaban, 30-31, Dilkusha C/A,Dhaka-1000.</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>02-223355410</small>
                    <!-- <small class="text-light"><i class="fa fa-envelope-open me-2"></i>bcic.law2024@gmail.com</small> -->
                    <small class="text-light">
                        <i class="fa fa-envelope-open me-2"></i>
                        <a href="mailto:bcic.law2024@gmail.com" class="text-light">bcic.law2024@gmail.com</a>
                    </small>

                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-twitter fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i class="fab fa-youtube fw-normal"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
    <a href="user-dashboard_details.php" class="navbar-brand p-0">
        <h2 class="m-0 text-white"><i class="fas fa-balance-scale me-2"></i> BCIC LEGAL AFFAIRS</h2>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
            <a href="#about_us" class="nav-item nav-link active">ABOUT</a>
            <a href="case_details.php" class="nav-item nav-link text-uppercase">CASE INFO Details</a>
            <div class="btn-group ">
             <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    REPORT
                </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="all_info_case_report.php?val=PTAREQ!%b@IWORNE**7">বিচারাধীন</a></li>
              <li><a class="dropdown-item" href="all_info_case_report.php?val=@!TAREQ!%b@IWORNE**7">পক্ষে</a></li>
              <li><a class="dropdown-item" href="all_info_case_report.php?val=1**2SHANETAREQ">বিপক্ষে</a></li>
               <li><a class="dropdown-item" href="all_info_case_report.php?val=1*5*2SHANE*TAREQ">সকল মামলা</a></li>
              <li><a href="user-dashboard.php?val=STAREK!%b@IWORNE**4" class="dropdown-item ">খুজুন</a></li>
             <!--  <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Separated link</a></li> -->
            </ul>
          </div>
            <a href="#developer" class="nav-item nav-link ">DEVELOPER</a>
            <!-- <a href="logout.php" class="nav-item nav-link ">LOGOUT</a> -->
            <a href="goout.php?code=<?php echo $code; ?>" class="nav-link nav-link">LOGOUT</a> 
        </div>
        <!-- <form id="downloadForm" action="dawnload_database.php" method="post">
            <button class="btn btn-primary py-2 px-4 ms-3" type="submit" name="submit">Download Database</button>
        </form> -->
    </div>
</nav>


        <div id="header-carousel" class="carousel slide carousel-fade my-0" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100"  src="images/kk3.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">                         
                      <!--   <h2 class="display-6 text-white animated slideInDown">Bangladseh Chemical Industries Corporation </h2> -->   
                         <h2 class="display-8 text-white animated slideInDown my-0">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h2>                            
                         
<!-- First block -->
            <div class="container-fluid facts py-5 pt-lg-5 my-1 rounded">
              <div class="container py-5 pt-lg-0 ">
                  <div class="row gx-0 ">

                  <div class="col-lg-4 wow zoomIn" data-wow-delay="0.1s">
                    <div class="bg-primary shadow d-flex align-items-center justify-content-center p-4" style="height: 150px;">
                        <div class="bg-white d-flex align-items-center justify-content-center rounded mb-2" style="width: 60px; height: 60px;">
                            <i class="fa fa-users text-primary"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="text-white mb-0">বিচারাধীন মামলার সংখ্যা</h5>
                            <h1 class="text-white mb-0" ><?php echo $count3 ?></h1>
                        </div>
                    </div>
                </div>

                 <!-- <div class="col-lg-3 wow zoomIn" data-wow-delay="0.1s">
                    <div class="bg-primary shadow d-flex align-items-center justify-content-center p-4" style="height: 150px;">
                        <div class="bg-white d-flex align-items-center justify-content-center rounded mb-2" style="width: 60px; height: 60px;">
                            <i class="fa fa-users text-primary"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="text-white mb-0">মামলার সংখ্যা</h5>
                            <h1 class="text-white mb-0" ><?php echo $count4 ?></h1>
                        </div>
                    </div>
                </div> -->

                  <div class="col-lg-4 wow zoomIn" data-wow-delay="0.3s">
                    <div class="bg-light shadow d-flex align-items-center justify-content-center p-4" style="height: 150px;">
                        <div class="bg-primary d-flex align-items-center justify-content-center rounded mb-2" style="width: 60px; height: 60px;">
                            <i class="fa fa-check text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">পক্ষে</h5>
                            <h1 class="mb-0" da><?php echo $count1 ?></h1>
                        </div>
                    </div>
                </div>


                 <div class="col-lg-4 wow zoomIn" data-wow-delay="0.1s">
                    <div class="bg-primary shadow d-flex align-items-center justify-content-center p-4" style="height: 150px;">
                        <div class="bg-white d-flex align-items-center justify-content-center rounded mb-2" style="width: 60px; height: 60px;">
                            <i class="fa fa-users text-primary"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="text-white mb-0">বিপক্ষে</h5>
                            <h1 class="text-white mb-0" ><?php echo $count2 ?></h1>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Second block -->
     <div class="container-fluid facts py-1 pt-lg-1 ">
              <div class="container py-1 pt-lg-0">
                  <div class="row gx-0">

                 <div class="col-lg-12 wow zoomIn" data-wow-delay="0.1s">
                    <div class="bg-primary shadow d-flex align-items-center justify-content-center p-4 rounded border border-secondary shadow" style="height: 150px; width: 100%">
                        <!-- <div class="bg-white d-flex align-items-center justify-content-center rounded mb-2" style="width: 60px; height: 60px;">
                            <i class="fa fa-users text-primary"></i>
                        </div> -->
                        <!-- <div class="ps-4"> -->

                           <table class="table text-white fw-bold" width="100%">
                                    <thead>
                                      <tr class="rounded-pill  rounded">
                                        <!-- <th class="rounded-pill bg-dark">আদালত</th>
                                        <th class="rounded-pill bg-dark">মোট</th>
                                        <th class="rounded-pill bg-dark">পক্ষে</th>
                                        <th class="rounded-pill bg-dark">বিপক্ষে</th>
                                        <th class="rounded-pill bg-dark">বিচারাধীন</th> -->

                                        <th class="rounded-pill fs-4" >আদালত</th>
                                        <th class="rounded-pill fs-4">বিচারাধীন</th>
                                        <th class="rounded-pill fs-4">পক্ষে</th>
                                        <th class="rounded-pill fs-4">বিপক্ষে</th>
                                        <!-- <th >বিচারাধীন</th> -->
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>

                                        <td >উচ্চ আদালত</td>
                                        <td><?php echo $inprogress_highcourt; ?></td>
                                        <td><?php echo $infavour_highcourt; ?></td>
                                        <td><?php echo $against_highcourt; ?></td>
                                        <!-- <td><?php echo $inprogress_highcourt; ?></td> -->
                                        
                                      </tr>
                                      <tr>
                                        <td>নিম্ন আদালত</td>
                                         <td><?php echo $inprogress_lowcourt; ?></td>
                                        <td><?php echo $infavour_lowcourt; ?></td>
                                        <td><?php echo $against_lowcourt; ?></td>
                                        <!-- <td><?php echo $inprogress_lowcourt; ?></td> -->
                                        
                                      </tr>
                                      
                                    </tbody>
                                  </table>


                                                
                                        </div>
                                    </div>

                                     
                                </div>
                            </div>
                        </div>

                            
                        </div>
                    </div>
                </div>
          
            </div>
           
        
        </div>
    </div>
    

    <!-- About Section -->
<div id="about_us" class="container-fluid py-0 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-20">
                <div class="section-title position-relative pb-3 mb-5">
                    <h5 class="fw-bold text-primary text-uppercase">About Us</h5>
                    <h1 class="mb-0">Bangladesh Chemical Industries Corporation</h1>
                </div>
                <p class="mb-4" style="text-align: justify;">Bangladesh Chemical Industries Corporation (BCIC), fully owned by the Government, was established in July, 1976 by a presidential Order. The Corporation is now managing 13 large and medium size industrial enterprises engaged in producing a wide range of products like Urea, TSP, DAP, Paper, Cement, Glass sheet, Hardboard, Sanitary ware & Insulator etc<br>
                Bangladesh Chemical Industries Corporation (BCIC) housed in its own 20 storied building at 30-31, Dilkusha C/A, Dhaka is one of the largest conglomerates of the country, fully owned by the Govt. came into being on 1st July  1976 through merger of three erstwhile  corporations viz Bangladesh Fertilizer, Chemical and Pharmaceutical Corporation (BFCPC), Bangladesh Paper and Board Corporation (BPBC) and Bangladesh Tanneries Corporation (BTC). Bangladesh Chemical Industries Corporation is entrusted with the task of supervision, co-ordination & control of the enterprises under its management & for developing new industries in the Chemical & allied Sector.<br>
                BCIC started functioning in 1976 with 88 nationalized enterprises under its administrative control. Since then as per Govt. policy a large number of small & medium size enterprises have been either been sold out to the private entrepreneurs transfered to their former owners. At present BCIC is managing 13 large & medium size industrial enterprises- Eight fertilizer factories, one Paper mill & Four other chemical & allied industrial units producing Urea,TSP, DAP, Paper, Cement, Glass sheet, Hardboard, Sulphuric Acid, Sanitary ware, Insulator, Tiles & Fire bricks etc.
                </p>
            </div>
            <!-- Additional content -->
        </div>
    </div>
</div>

    <!-- Testimonial Start -->
    <div class="container-fluid py-0 wow fadeInUp " data-wow-delay="0.1s">
        <div class="container py-0">
            <div class="section-title text-center position-relative pb-3 mb-4 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">Law IS Equal For All</h5>
                <h1 class="mb-0">Meet our Lawyers</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.6s">

                <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">Mr.Ahsan Quddus Kuntal</h5>
                            <small class="text-uppercase">General Manager (Legal),BCIC</small>

                        </div>
                    </div>
                    <div class="pt-2 pb-3 px-3">
                          <small class="">Email: ahsanqudduskuntal@gmail.com</small> 
                          <br>                          
                          <small class="">Mobile No.: 01749-931284</small> 
                          <br>
                           <br>
                           
                    </div>
                </div>

                  <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">Mr.Ahsan Quddus Kuntal</h5>
                            <small class="text-uppercase">General Manager (Legal),BCIC</small>

                        </div>
                    </div>
                    <div class="pt-2 pb-3 px-3">
                          <small class="">Email: ahsanqudduskuntal@gmail.com</small> 
                          <br>                          
                          <small class="">Mobile No.: 01749-931284</small> 
                          <br>
                           <br>
                           
                    </div>
                </div>

                  <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">Mr.Ahsan Quddus Kuntal</h5>
                            <small class="text-uppercase">General Manager (Legal),BCIC</small>

                        </div>
                    </div>
                    <div class="pt-2 pb-3 px-3">
                          <small class="">Email: ahsanqudduskuntal@gmail.com</small> 
                          <br>                          
                          <small class="">Mobile No.: 01749-931284</small> 
                          <br>
                           <br>
                           
                    </div>
                </div>

                <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h4 class="text-primary mb-0">Client Name</h4>
                            <small class="text-uppercase">Profession</small>
                        </div>
                    </div>
                    <div class="pt-4 pb-5 px-5">
                        Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam
                    </div>
                </div>


                <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h4 class="text-primary mb-0">Client Name</h4>
                            <small class="text-uppercase">Profession</small>
                        </div>
                    </div>
                    <div class="pt-4 pb-5 px-5">
                        Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam
                    </div>
                </div>


                <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h4 class="text-primary mb-0">Client Name</h4>
                            <small class="text-uppercase">Profession</small>
                        </div>
                    </div>
                    <div class="pt-4 pb-5 px-5">
                        Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- Testimonial End -->

        <!-- Contact Us -->

    <div class="container-fluid py-0 wow fadeInUp my-5" data-wow-delay="0.1s">
        <div class="container py-0">
            <div class="section-title text-center position-relative pb-3 mb-4 mx-auto" style="max-width: 600px;">
                <!-- <h5 class="fw-bold text-primary text-uppercase">Law IS Equal For All</h5> -->
                <h1 class="mb-0"> Contact Us</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.6s">

                <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">Mr.Ahsan Quddus Kuntal</h5>
                            <small class="text-uppercase">General Manager (Legal),BCIC</small>

                        </div>
                    </div>
                    <div class="pt-2 pb-3 px-3">
                          <small class="">Email: ahsanqudduskuntal@gmail.com</small> 
                          <br>                          
                          <small class="">Mobile No.: 01749-931284</small> 
                          <br>
                           <br>
                           
                    </div>
                </div>

                  <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/kuntol.jpg" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">Mr.Ahsan Quddus Kuntal</h5>
                            <small class="text-uppercase">General Manager (Legal),BCIC</small>

                        </div>
                    </div>
                    <div class="pt-2 pb-3 px-3">
                          <small class="">Email: ahsanqudduskuntal@gmail.com</small> 
                          <br>                          
                          <small class="">Mobile No.: 01749-931284</small> 
                          <br>
                           <br>
                           
                    </div>
                </div>

                  <div class="testimonial-item bg-light my-4">
                    <div class="d-flex align-items-center border-bottom pt-5 pb-4 px-5">
                        <img class="img-fluid rounded" src="images/Untitled.png" style="width: 60px; height: 60px;" >
                        <div class="ps-4">
                            <h5 class="text-primary mb-0">Mr. Apurba Kirtonia</h5>
                            <small class="text-uppercase">......,BCIC</small>

                        </div>
                    </div>
                    <div class="pt-2 pb-3 px-3">
                          <small class="">Email: ....</small> 
                          <br>                          
                          <small class="">Mobile No.: 01726-028397</small> 
                          <br>
                           <br>
                           <br>
                           
                    </div>
                </div>

                

            </div>
        </div>
    </div>

   <!-- Team Section -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s" id="developer">
    <div class="container py-5">
        <div class="row justify-content-center"> <!-- Centering the content -->
            <div class="col-lg-10">
                <div class="section-title text-center position-relative pb-3 mb-5">
                    <h5 class="fw-bold text-primary text-uppercase">Team Members</h5>
                    <h1 class="mb-0">Developer's Team</h1>
                </div>
                <div class="row g-5">
                    <div class="col-lg-6 wow slideInUp" data-wow-delay="0.3s">
                        <div class="team-item bg-light rounded overflow-hidden">
                            <div class="team-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" style="height: 300px;" src="images/002.jpg" alt="">
                                <div class="team-social">
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-twitter fw-normal"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-instagram fw-normal"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <h4 class="text-primary">MD.TAREQ EMARN</h4>
                                <p class="text-uppercase m-0">PROGRAMMER (ICT Div.)</p>
                                <p class="text-uppercase m-0">01671583637</p>
                                <p class="m-0">emrantareq09@gmail.com</p>
                                <p class="liw">Bangladesh Chemical Industries Corporation</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 wow slideInUp" data-wow-delay="0.6s">
                        <div class="team-item bg-light rounded overflow-hidden">
                            <div class="team-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" style="height: 300px;" src="images/001.jpg" alt="">
                                <div class="team-social">
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-twitter fw-normal"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-facebook-f fw-normal"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-instagram fw-normal"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded" href=""><i class="fab fa-linkedin-in fw-normal"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <h4 class="text-primary">SHANEWORN BHADRA</h4>
                                <p class="text-uppercase m-0">ASSISTANT PROGRAMMER (ICT Div.)</p>
                                <p class="text-uppercase m-0">01878072812</p>
                                <p class="m-0">Shanewornbhadra@gmail.com</p>
                                <p class="liw">Bangladesh Chemical Industries Corporation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    
    <!-- Footer Start -->
<div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-12">
                <div class="container-fluid text-white text-center" style="background: #061429;">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                                    <p class="mb-0">Design & Developed By ICT Division (BCIC).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Footer End -->
<style>
    /* Style for back-to-top button */
    .back-to-top {
        position: fixed;
        right: 45px;
        bottom: 45px;
        z-index: 99;
        display: none; /* Initially hide the button */
    }

    .back-to-top i {
        font-size: 24px;
    }

    /* Hover effect */
    .back-to-top:hover {
        transform: scale(1.1);
    }
</style>

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top" onclick="scrollToTop()">
    <i class="bi bi-arrow-up"></i>
</a>

<script>
    // Function to scroll to top of the page
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Smooth scrolling
        });
    }

    // Function to handle scroll event
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        // If user has scrolled down 20% of the page height, show the button
        if (document.body.scrollTop > (document.body.scrollHeight * 0.2) || document.documentElement.scrollTop > (document.documentElement.scrollHeight * 0.2)) {
            document.querySelector('.back-to-top').style.display = "block";
        } else {
            document.querySelector('.back-to-top').style.display = "none";
        }
    }
</script>



    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>



<?php } else{
  // header('location:login/logout.php');
   header('location:login.php');
  } 
?>