<?php
// Start the session
session_start();
$_SESSION['emp_id']=$_GET["emp_id"];

$_SESSION['emp_id']=$_SESSION['emp_id'];
//$_GET['emp_id'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BCIC PMIS</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> -->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">	
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
</style>	
	
</head>
<body>	
<?php //include('../includes/header.php');?>

 <div class="container p-2 my-2 border shadow bg-light rounded">
    	<center>
       <h2 class="page-header text-success m-1 text-center badge bg-dark bg-outline-info text-wrap" style="width: 70rem; font-size: 30px;">View Information of <b class="text-primary">[--<?php echo "Employee ID: ".$_SESSION['emp_id'];?>--]</b>
    
    </h2> 
      </center>
		 
		<h4><?php //echo $_SESSION['emp_id']; ?></h4>
    <?php
      if(@$_GET['submitted'])
      {?>
      <div class="alert alert-success" role="alert">
        Data Inserted Successfully
      </div>
    <?php }?>		
 <div class="row">
		
<!-- <div class="col-sm|md|lg|xl|xxl-12 ">-->
<div class="col-sm-12 ">		

<?php
include('../db/db.php');
 
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
 <thead class='thead-dark text-center'>
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
 print "<center>&nbsp;<input class='rounded-circle shadow-lg border border-warning' type=\"image\" name=\"imageField\" 
 width=\"130\" height=\"130\" src=\"".'../images/'.$row['image']."\">&nbsp; </center>";
  }
 echo "</table>";
  mysqli_close($conn);
 ?>

</div>

</div>
<!--end first row-->

<div class="row "> <!--Start 2nd row-->
<div class="col-sm-1 "></div>
<div class="col-sm-8 text-center my-0">
 <h2 class="my-0 mb-2 text-success text-uppercase" > <b>Update Your Profile </b></h2>
 <hr class="my-0 mb-2 bg-danger border-2 border-top border-success">
 <h4>
  <!-- <a class="btn btn-primary" href="add_personal_data.php?edit=<?php echo $emp_id;?>"> Add Personal info. </a>
|| -->
  <a class="btn btn-primary" href="../controller/edit_personal_info.php?edit=<?php echo $emp_id;?>"> Update Personal Information </a>
  ||
  <a class="btn btn-primary" href="../edit_child_info.php?edit=<?php echo $emp_id;?>"> Children Information </a>
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
 <!--  <a class="btn btn-warning" href="add_training_info.php?edit=<?php echo $emp_id;?>"> Add Training Information</a>
 || -->
 <!-- <a class="btn btn-warning" href="edit_training_info_4.php?edit=<?php echo $emp_id;?>"> Update Training Information </a> -->
 <!-- ||
 <a class="btn btn-warning"  href="view_training_info.php?edit=<?php echo $emp_id;?>"> View </a> -->
 </h4>
 <h4>
<!--  <a class="btn btn-info" href="../controller/add_membership_info_details.php?edit=<?php echo $emp_id;?>"> Add Prof./ Membership Information </a>
 || -->
 <!-- <a class="btn btn-info" href="../controller/membership_info_details.php?edit=<?php echo $emp_id;?>"> Update Prof./ Membership Info. </a> -->
 <!-- ||
 <a class="btn btn-info"  href="../view/view_membership_info.php?edit=<?php echo $emp_id;?>"> View</a> -->
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
 ||
 <a class="btn btn-warning" href="../view/view_promotion_info.php?edit=<?php echo $emp_id;?>"> Promotion Info. </a>
 ||
 <a class="btn btn-primary" href="../controller/award_and_penalty_info.php?edit=<?php echo $emp_id;?>"> Disciplinary Action  </a>
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
 <!-- <a class="btn btn-warning" href="add_award_info.php?edit=<?php echo $emp_id;?>"> View Increment Info. </a>
 || -->
 <!-- <a class="btn btn-warning" href="view_promotion_info.php?edit=<?php echo $emp_id;?>"> Promotion Information</a> -->
 <!-- || -->
 <!-- <a class="btn btn-warning"  href="../view/transfer_info.php?edit=<?php echo $emp_id;?>"> View Transfer Information </a> -->
 </h4>

 <!-- <h4>
 <a class="btn btn-primary" href="add_award_info.php?edit=<?php echo $emp_id;?>"> Add Promotion Info. </a>
 ||
 <a class="btn btn-primary" href="edit_award_info.php?edit=<?php echo $emp_id;?>"> Update Promotion Info.</a>
 ||
 <a class="btn btn-primary"  href="view_award_info.php?edit=<?php echo $emp_id;?>"> View </a>
 </h4> -->
   <h4>
 <!-- <a class="btn btn-success" href="publication_info.php?edit=<?php echo $emp_id;?>"> Add Publication </a>
 || -->
 <!-- <a class="btn btn-success"  href="edit_award_info.php?edit=<?php echo $emp_id;?>"> Update Publication</a>
 || -->
 <!-- <a class="btn btn-success"  href="view_publication_info.php?edit=<?php echo $emp_id;?>"> View </a> -->
 </h4>

  <h4>
<!--  <a class="btn btn-danger" href="../controller/add_disciplinary_action.php?edit=<?php echo $emp_id;?>"> Add Disciplinary Action </a>
 || -->
<!--  <a class="btn btn-success" href="edit_award_info.php?edit=<?php echo $emp_id;?>"> Update Disciplinary Action</a>
 || -->
 <!-- <a class="btn btn-danger"  href="view_disciplinary_action.php?edit=<?php echo $emp_id;?>"> View </a> -->
 </h4>

<!--   <h4>
 <a class="btn btn-primary" href="add_award_info.php?edit=<?php echo $emp_id;?>"> Add Transfer Information </a>
 ||
 <a class="btn btn-primary" href="edit_award_info.php?edit=<?php echo $emp_id;?>"> Update Transfer Information</a>
 ||
 <a class="btn btn-primary"  href="view_award_info.php?edit=<?php echo $emp_id;?>"> View </a>
 </h4> -->

   <h4>
 <a class="btn btn-primary" href="../controller/nominees_info_details.php?edit=<?php echo $emp_id;?>"> Nominees Information</a>
 ||
 <!-- <a class="btn btn-primary" href="nominees_info.php?edit=<?php echo $emp_id;?>"> Group Insurance Info.</a>
 ||
 <a class="btn btn-primary"  href="view_award_info.php?edit=<?php echo $emp_id;?>"> Pension Information </a>
|| -->
 <a class="btn btn-primary"  href="../controller/welfare_info_details.php?edit=<?php echo $emp_id;?>"> Welfare Information </a>

 </h4>

</div>

<div class="col-sm-2 "><h4 class="text-center"><a href="view_users.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></h4>
 <h4 class="text-center"><a href="../dashboard.php" class="btn btn-success"><span class="glyphicon glyphicon-th" aria-hidden="true"></span>  Dashboard</a></h4>
 <!-- <h4 class="text-center"><a href="view_bio_data_at_a_glance.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>  At a Glance</a></h4> -->

 <hr>
 <h4>
<form method="POST" action="../increment/create_increment_letter_by_emp_id.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" target="_blank">  
 <center>
<!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
<button type="submit" name="create_pdf" class="btn btn-danger text-center"><i class="fa fa-print"></i> Print Increment Letter </button>
 </center>
          
  </form>
   </h4>
      

    <form method="POST" action="../bio-data/create_pdf_bio_data_by_emp_id_L.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" target="_blank">  
      <center>
        <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
  <!-- <button type="submit" name="create_pdf" class="btn btn-warning text-center"><i class="fa fa-print"></i> Print Bio Data (L+PDF) </button> -->
      </center>
          
  </form>
  <br>
      <form method="POST" action="../bio-data/create_pdf_bio_data_by_emp_id_p.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" target="_blank">  
      <center>
        <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
  <!-- <button type="submit" name="create_pdf" class="btn btn-warning text-center"><i class="fa fa-print"></i> Print Bio Data (P+PDF) </button> -->
      </center>
          
  </form>
 <h4 class="text-center"><a href="../bio-data/bio_data_indetails.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Bio Data (Long)</a></h4>
 <h4 class="text-center"><a href="../bio-data/bio_data_shortly.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-success"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Bio Data (Short)</a></h4>
 <!-- <h4 class="text-center"><a href="view_bio_data.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>  Bio Data </a></h4> -->
 <hr>
 <h4 class="text-center"><a href="logout.php" class="btn btn-danger"> <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout </a></h4></div>
</div>

<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><!--<h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4>--></div>
<div class="col-sm-4 "></div>

</div>
</div>


</body>
</html>

<?php //include('../includes/footer.php');?>
