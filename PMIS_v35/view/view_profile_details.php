<?php
// Start the session
session_start();
include('../db/db.php');
// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: ../login/index.php");
  exit();
}
// Check the user role
$role = $_SESSION['role'];

$_SESSION['role']=$role ;
echo $role;

$_SESSION['emp_id']=$_GET["emp_id"];
 
// Display different content based on the user role
if ($role === 'admin'||$role=== 'user') {
 
 if(isset($_SESSION['emp_id'])){
  
   $emp_id=$_SESSION['emp_id'];

   $sql="SELECT * FROM basic_info where emp_id='$emp_id'";
    
   $sql1="SELECT dob FROM job_desc where emp_id='$emp_id'";
    $result1 = mysqli_query($conn,$sql1);
    $row1=mysqli_fetch_array($result1);
    
    if(isset($row1['dob'])==''){
      $dob='';
    }else{
      $dob=$row1['dob'];
    }
 }

  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BCIC PMIS</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"> 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
<style>
fieldset {
  background-color: #eeeeee;
}
legend {
  background-color: ;
  color: white;
  padding: 10px 10px;
}
input {
  margin: 0px;
}
fieldset.scheduler-border {
  border: 1px groove #ddd !important;
  padding: 0px 1px 10px 10px !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow:  0px 0px 0px 0px #000;
  box-shadow:  0px 0px 0px 0px #000;
}
  legend.scheduler-border {
  width:inherit; /* Or auto */
  padding:0px 5px 0px 5px; /* To give a bit of padding on the left and right */
  border-bottom:none;
  margin-bottom:10px;
  margin-top:-10px;  
}

@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");:root{--header-height: 3rem;--nav-width: 68px;--first-color: #4723D9;--first-color-light: #AFA5D9;--white-color: #F7F6FB;--body-font: 'Nunito', sans-serif;--normal-font-size: 1rem;--z-fixed: 100}*,::before,::after{box-sizing: border-box}body{position: relative;margin: var(--header-height) 0 0 0;padding: 0 1rem;font-family: var(--body-font);font-size: var(--normal-font-size);transition: .5s}a{text-decoration: none}.header{width: 100%;height: var(--header-height);position: fixed;top: 0;left: 0;display: flex;align-items: center;justify-content: space-between;padding: 0 1rem;background-color: var(--white-color);z-index: var(--z-fixed);transition: .5s}.header_toggle{color: var(--first-color);font-size: 1.5rem;cursor: pointer}.header_img{width: 35px;height: 35px;display: flex;justify-content: center;border-radius: 50%;overflow: hidden}.header_img img{width: 40px}.l-navbar{position: fixed;top: 0;left: -30%;width: var(--nav-width);height: 100vh;background-color: var(--first-color);padding: .5rem 1rem 0 0;transition: .5s;z-index: var(--z-fixed)}.nav{height: 100%;display: flex;flex-direction: column;justify-content: space-between;overflow: hidden}.nav_logo, .nav_link{display: grid;grid-template-columns: max-content max-content;align-items: center;column-gap: 1rem;padding: .5rem 0 .5rem 1.5rem}.nav_logo{margin-bottom: 2rem}.nav_logo-icon{font-size: 1.25rem;color: var(--white-color)}.nav_logo-name{color: var(--white-color);font-weight: 700}.nav_link{position: relative;color: var(--first-color-light);margin-bottom: 1.5rem;transition: .3s}.nav_link:hover{color: var(--white-color)}.nav_icon{font-size: 1.25rem}.show{left: 0}.body-pd{padding-left: calc(var(--nav-width) + 1rem)}.active{color: var(--white-color)}.active::before{content: '';position: absolute;left: 0;width: 2px;height: 32px;background-color: var(--white-color)}.height-100{height:100vh}@media screen and (min-width: 768px){body{margin: calc(var(--header-height) + 1rem) 0 0 0;padding-left: calc(var(--nav-width) + 2rem)}.header{height: calc(var(--header-height) + 1rem);padding: 0 2rem 0 calc(var(--nav-width) + 2rem)}.header_img{width: 40px;height: 40px}.header_img img{width: 45px}.l-navbar{left: 0;padding: 1rem 1rem 0 0}.show{width: calc(var(--nav-width) + 156px)}.body-pd{padding-left: calc(var(--nav-width) + 188px)}}
</style>  

  <script type="text/javascript">
   document.addEventListener("DOMContentLoaded", function(event) {   
    const showNavbar = (toggleId, navId, bodyId, headerId) =>{
    const toggle = document.getElementById(toggleId),
    nav = document.getElementById(navId),
    bodypd = document.getElementById(bodyId),
    headerpd = document.getElementById(headerId)

    // Validate that all variables exist
    if(toggle && nav && bodypd && headerpd){
    toggle.addEventListener('click', ()=>{
    // show navbar
    nav.classList.toggle('show')
    // change icon
    toggle.classList.toggle('bx-x')
    // add padding to body
    bodypd.classList.toggle('body-pd')
    // add padding to header
    headerpd.classList.toggle('body-pd')
        })
      }
    }
    showNavbar('header-toggle','nav-bar','body-pd','header')
    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')
    function colorLink(){
    if(linkColor){
    linkColor.forEach(l=> l.classList.remove('active'))
    this.classList.add('active')
      }
    }
    linkColor.forEach(l=> l.addEventListener('click', colorLink))
 // Your code to run since DOM is loaded and ready
}); 
  </script>
<link rel="icon" type="image/gif/png" href="../images/bcic_logo.png">
</head>
  <!-- for sidebar -->
  <body id="body-pd">
     <header class="header bg-warning" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i>  </div>
        <h2 class="text-center text-success fw-bold position-static">
            View Information of <b class="text-primary">(--<?php echo "Employee ID: " . $_SESSION['emp_id']; ?>--)</b>
        </h2>
     
      <div class="row">        
        <div class="col text-end">
          <div class="dropdown">
            <a href="#" class="d-block text-decoration-none dropdown-toggle text-white" id="profileDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">           

              <?php 
                  if ($row['image'] != '' && file_exists('' . $row['image'])) {
                echo "<img class='rounded-circle border border-muted' src='" . $row['image'] . "' width='30' height='30'";
                } else {
                echo "<img class='rounded-circle border border-muted' src='../images/fd.png' width='30' height='30'>";
                }?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
          </div>
          <span class="ms-2">
              <span class="text-success me-1 fw-bold">Online</span>
          </span>
        </div>
      </div>
    </header> 

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name"> <?php echo $_SESSION['role']?> Dashboard</span> </a>
                <div class="nav_list"> <a href="../dashboard.php" class="nav_link active"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Dashboard</span> </a> <a href="#" class="nav_link"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Users</span> </a> <a href="#" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name">Messages</span> </a> <a href="#" class="nav_link"> <i class='bx bx-dock-top nav_icon'></i> <span class="nav_name">Increment Letter</span> </a> <a href="#" class="nav_link"> <i class='bx bx-book-open nav_icon'></i> <span class="nav_name">Bio-Data (Long)</span> </a> 
                  <a href="#" class="nav_link"> <i class='bx bx-book nav_icon'></i> <span class="nav_name">Bio-Data (Short)</span> </a>
                  <a href="view_users.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="nav_link"> <i class='bx bx-arrow-back nav_icon'></i> <span class="nav_name">Previous Page</span> </a>  
                </div>
            </div> <a href="../logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
        </nav>
    </div> 
    <!--Container Main start-->
<div class="container-fluid">    <!-- Your content goes here -->
<div class="row">    
<!-- <div class="col-sm|md|lg|xl|xxl-12 ">-->
<div class="col-sm-12 ">
<?php
// include('../db/db.php'); 
 if(isset($_SESSION['emp_id'])){
   //$emp_id=$_GET['emp_id'];
   $emp_id=$_SESSION['emp_id'];
   $sql="SELECT * FROM basic_info where emp_id='$emp_id'";
    // $sql="SELECT DISTINCT * from basic_info u INNER JOIN job_desc j ON u.emp_id=j.emp_id  where u.emp_id='$emp_id' LIMIT 1 ";

   $sql1="SELECT dob FROM job_desc where emp_id='$emp_id'";
    $result1 = mysqli_query($conn,$sql1);
    $row1=mysqli_fetch_array($result1);
    
    if(isset($row1['dob'])==''){
      $dob='';
    }else{
      $dob=$row1['dob'];
    }
 }
 $result = mysqli_query($conn,$sql);
 echo "<table class='table table-bordered table-striped text-center rounded'>
 <thead class='table-dark text-center'>
 <tr > 
  <th class=' text-center'> Name</th>
  <th class=' text-center'>Designation</th>
  <th class=' text-center'> Division/Office </th>
  <th class=' text-center'> Section </th>  
  <th class=' text-center'> DOB </th>
  <th class=' text-center'> Mobile Number </th>
  <th class=' text-center'>Email</th> 
 </tr>
  </thead>";
  while($row = mysqli_fetch_array($result)) {
   $user_id=$row[0];
   $_SESSION['id']=$user_id;
   $emp_id=$row['emp_id'];
   $name=$row['name'];
   $designation=$row['designation'];
   $division=$row['division'];
   $section=$row['section'];
  // $place_of_posting=$row['place_of_posting'];<th class=' text-center'> Place of Posting </th>

  // $dob=$row['dob'];
  // $test1=$row['dob'];
  // $newDob= date('d-m-Y',strtotime($test1));
  $sub_cadre_header = $row['sub_cadre_header'];
     // $sub_cadre_header = '';
     if($sub_cadre_header!= '') {
      $designation_header=$designation.' ('.$sub_cadre_header.')';
       }
       else {
        $designation_header = $designation;
       }

  $mobile_no=$row['mobile_no'];
  $email=$row['email'];
    echo "<tr>";
    echo "<td>" .  $name. "</td>";
    echo "<td>" .  $designation_header. "</td>";
    echo "<td>" .  $division. "</td>";
    echo "<td>" .  $section. "</td>";
   // echo "<td>" .  $place_of_posting. "</td>";
    echo "<td>" . $dob. "</td>";
    echo "<td>" .  $mobile_no. "</td>"; 
   echo "<td>" .  $email. "</td>";
   echo "</tr>";
    
  if ($row['image'] != '' && file_exists('' . $row['image'])) {
   print "<center>&nbsp;<input class='rounded-circle shadow-lg border border-warning' type=\"image\" name=\"imageField\" 
   width=\"130\" height=\"130\" src=\"".'../images/'.$row['image']."\">&nbsp; </center>";
  } else {
  // echo "<img class='rounded-circle border border-muted' src='images/avatar.png' width='130' height='130'>";
   echo "<center>&nbsp;<input class='rounded-circle shadow-lg border border-primary my-2' type=\"image\" name=\"imageField\" 
   width=\"130\" height=\"130\" src=\"" . '../images/avater.png' . "\">&nbsp; </center>";
      }
    }
   echo "</table>";
    mysqli_close($conn);
 ?>
</div>
</div>
<!--end first row-->
<div class="row "> <!--Start 2nd row-->
<div class="col-sm-0 "></div>
<div class="col-sm-10 text-center my-0">
 <h2 class="my-0 mb-2 text-success text-uppercase" > <b>Update Profile </b></h2>
 <hr class="my-0 mb-2 bg-danger border-2 border-top border-success">
 <h4>
  <!-- <a class="btn btn-primary" href="add_personal_data.php?edit=<?php echo $emp_id;?>"> Add Personal info. </a>
|| -->
  <a class="btn btn-primary" href="../controller/edit_personal_info.php?edit=<?php echo $emp_id;?>"> Update Personal Information </a>
  ||
  <a class="btn btn-primary" href="../edit_child_info.php?edit=<?php echo $emp_id; ?>&role=<?php echo $role;?>"> Children Information </a>
 ||
 <a class="btn btn-primary"  href="view_personal_info.php?edit=<?php echo $emp_id;?>"> View</a>
 </h4>
 <h4><a class="btn btn-primary"  href="../controller/add_edu_info.php?edit=<?php echo $emp_id;?>"> Add Educational Information </a>
 ||
 <a class="btn btn-primary"  href="../controller/edit_edu_info.php?edit=<?php echo $emp_id;?>"> Update Educational Information </a>
 ||
  <a class="btn btn-primary"  href="../controller/other_qualification_details.php?edit=<?php echo $emp_id;?>"> Others Qualification </a>
 ||
 <a class="btn btn-primary"  href="view_edu_info.php?edit=<?php echo $emp_id;?>"> View</a>
 </h4>
 <h4>
  <a class="btn btn-warning" href="../controller/add_job_desc.php?edit=<?php echo $emp_id;?>"> Add Service Information </a> 
 ||
 <a class="btn btn-warning" href="../controller/edit_job_desc.php?edit=<?php echo $emp_id;?>"> Update Service Information </a>
 ||
 <a class="btn btn-warning"  href="../view/view_job_desc.php?edit=<?php echo $user_id;?>"> View  </a>
 </h4>
 <h4>
 <!-- <a class="btn btn-success" href="add_service_history.php?emp_id=<?php echo $emp_id;?>"> Add Service History </a>
 || -->
 <a class="btn btn-success" href="../controller/service_history_details.php?edit=<?php echo $emp_id;?>"> Service History Details</a>
||
<a class="btn btn-warning" href="../controller/training_info_details.php?edit=<?php echo $emp_id;?>"> Training Information Details </a>
<a class="btn btn-info" href="../controller/membership_info_details.php?edit=<?php echo $emp_id;?>"> Prof./ Membership Info. </a>
 <!-- <a class="btn btn-success"  href="view_service_history.php?edit=<?php echo $user_id;?>"> View  </a>  -->
 </h4>
<h4>
 <a class="btn btn-warning" href="../controller/add_prof_qualification_info.php?edit=<?php echo $emp_id;?>"> Add Prof. Qualification Info. </a>
 ||
 <a class="btn btn-warning" href="../controller/edit_prof_qualification_info.php?edit=<?php echo $emp_id;?>"> Update Prof. Qualification Info. </a>
 ||
 <a class="btn btn-warning"  href="../view/view_prof_qualification_info.php?edit=<?php echo $emp_id;?>"> View</a>
 </h4>

 <h4>
<!--  <a class="btn btn-success" href="#"> Add Diploma Course Info. </a>
 ||
 <a class="btn btn-success" href="#"> Update Diploma Course Info. </a>
 || -->
 <a class="btn btn-success"  href="../controller/diploma_course_info_details.php?edit=<?php echo $emp_id;?>"> Diploma Course Info. </a>
 <!-- ||
 <a class="btn btn-warning" href="../view/view_promotion_info.php?edit=<?php echo $emp_id;?>"> Promotion Info. </a> -->
 ||
 <a class="btn btn-primary" href="../controller/award_and_penalty_info.php?edit=<?php echo $emp_id;?>"> Award/ Punishment Info.  </a>
 ||
 <a class="btn btn-primary" href="../controller/loan_info_details.php?edit=<?php echo $emp_id;?>"> Loan Information</a>
 </h4>

 <h4>
 <a class="btn btn-primary" href="../controller/add_bank_info.php?edit=<?php echo $emp_id;?>"> Add EMP Bank Info. </a>
 ||
 <a class="btn btn-primary" href="../controller/edit_bank_info.php?edit=<?php echo $emp_id;?>"> Update EMP Bank Info</a>
 ||
 <a class="btn btn-primary"  href="../view/view_bank_info.php?edit=<?php echo $emp_id;?>"> View </a>
 </h4>

 <h4>
 <a class="btn btn-primary" href="../controller/nominees_info_details.php?edit=<?php echo $emp_id;?>"> Nominees Information</a>
 ||
 <a class="btn btn-primary"  href="../controller/welfare_info_details.php?edit=<?php echo $emp_id;?>"> Welfare Information </a>
 ||
 <a class="btn btn-primary"  href="../controller/leave_mgtm_info_details.php?edit=<?php echo $emp_id;?>"> Leave Management </a>
 </h4>
</div>
<?php 
  if($role=='admin'){ 
?>
<div class="col-sm-2 "><h4 class="text-center"><a href="view_users.php" class="btn btn-success"> <i class="fa fa-arrow-left"></i> Previous Page </a></h4>
 <h4 class="text-center"><a href="../dashboard.php" class="btn btn-success"><i class="fa fa-gear"></i>  Dashboard</a></h4>
 <!-- <h4 class="text-center"><a href="view_bio_data_at_a_glance.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>  At a Glance</a></h4> -->

 <hr>
 <h4>
  <form method="POST" action="../increment/create_increment_letter_by_emp_id.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" target="_blank">  
   <center>
  <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
  <button type="submit" name="create_pdf" class="btn btn-danger text-center"><i class="fa fa-print"></i>  Increment Letter </button>
   </center>            
    </form>
  </h4>

 <h4 class="text-center"><a href="../bio-data/bio_data_indetails.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"><i class="fa fa-book"></i> Bio Data (Long)</a></h4>
 <h4 class="text-center"><a href="../bio-data/bio_data_shortly.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"><i class="fa fa-book"></i> Bio Data (Short)</a></h4>
 <!-- <h4 class="text-center"><a href="view_bio_data.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>  Bio Data </a></h4> -->
 <hr>
 <h4 class="text-center"><a href="logout.php" class="btn btn-danger"> <i class="fa fa-sign-out"></i> Logout </a></h4>
  </div>
  <?php
    } 
    else{
      ?>   
  <div class="col-sm-2 "><h4 class="text-center"><a href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"> <i class="fa fa-arrow-left"></i>  Previous Page </a></h4></div>
  <?php
    }
  ?>
</div>

<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><!--<h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4>--></div>
<div class="col-sm-4 "></div>

</div>
</div>
</div>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- jQuery (minified) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-kJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<!-- Bootstrap JS (minified) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-cCiSp3WRjv6etWDIkHbhl4Upjw6OLHegAX6a2McdV2CyDHcNtk9cblu96L5c88x1" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php //include('../includes/footer.php');?>

<?php
} else {
  echo "<h1>Welcome, User!</h1>";
  // Display user-specific content
  header("Location: user_dashboard.php");
}
?>