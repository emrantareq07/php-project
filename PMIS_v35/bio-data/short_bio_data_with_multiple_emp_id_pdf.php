<?php
include('../db/database_2.php');
include('../controller/function.php');
// include('includes/header.php');
include('../db/db.php');
if(isset($_POST['submit'])){

foreach ($_POST['emp_id'] as $selectedOption){
  
$emp_id= $selectedOption;
$last_update_at;         
$sql="SELECT * from basic_info where emp_id='$emp_id'";
    
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BCIC PMIS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
    integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 

    <link rel="icon" type="image/gif/png" href="../images/bcic_logo.png">
    <link rel="stylesheet" type="text/css" href="../css/print.css" media="print">
    <!-- <link rel="stylesheet" type="text/css" href="css/ab.css" > -->

</head>

<body class="">

<!-- <div class="container-fluid p-2 my-1 border border-primary shadow-lg  bg-white rounded"> -->
<div class="container-fluid p-3 my-1 bg-white " >
  
    
<!-- <hr class="bg-warning" style="color:#1b4fa8;"> -->
    <div class="row "> 
    <!--   <div class="col-sm-1">1st column

     
      </div>  -->
  <div class="col-sm-10">
    <h5 class="text-center text-muted text-uppercase "><b>BANGLADESH CHEMICAL INDUSTRIES CORPORATION</b></h5>
        

    <h6 class=" text-center text-muted text-uppercase "> BCIC Employees BIO-DATA SHEET(short) </h6>
 <?php 
    
      echo "<center class='text-muted fst-italic'>&nbsp;<b>Name: ".$row['name']."</b><b>(".$row['designation'].")</b>&nbsp;</center>";
      echo "<center class='text-muted fst-italic'>&nbsp;<b>Employee ID: ".$row['emp_id'].";"." Seniority No : ". $row['seniority_no'] .";"." Division/Office : ".$row['division']."</b>&nbsp; </center>";

       ?>

  </div>
      
      <div class="col-sm-2 "><!--2nd column-->
<center>
   
<?php
if ($row['image'] != '' && file_exists('../images/' . $row['image'])) {
    echo "<input class='rounded-circle border border-muted' type='image' name='imageField' width='100' height='100' src='../images/" . $row['image'] . "'>";
} else {
    // echo "<input class='rounded-circle border border-muted' type='image' name='imageField' width='100' height='100' src='../images/avatar.png'>";
  echo '<input type="image" name="imageField" img src="../images/gdgvbc.png" alt="Italian Trulli" width="100" height="100" class="rounded-circle border border-muted">';
}
?>
         
        <button onclick="location.href='short_bio_data_with_multiple_emp_id.php'" type="button" class="btn btn-info  text-center text-white" id="print-btn"><i class="fa fa-arrow-left" style="font-size:20px;color:white"></i>  </button>

         <button onclick="window.print();return false;" class="btn btn-danger float-end  mt-2 text-center " id="print-btn"><i class="fa fa-print"></i> Print</button>
        </center>

      </div>
     
  </div>


<hr class="text-muted mb-1">
    <!-- Example Code -->
 <div class="row ">   

      <?php
    
       if(isset($_POST['emp_id'])){
         
         // $emp_id=$_SESSION['emp_id'];
    
      $sql="SELECT * from job_desc j INNER JOIN basic_info b ON j.emp_id=b.emp_id where j.emp_id='$emp_id'"; 
      
       }
       $result = mysqli_query($conn,$sql);

      if ($result->num_rows > 0) {
       
       while($row = mysqli_fetch_array($result)) {
       $_SESSION['seniority_no']=$row['seniority_no'];
       $name=$row['name'];
       //$fathersName=$row['fathersName'];
       //$mothersName=$row['mothersName'];
       $nid=$row['nid'];
       $mobile_no=$row['mobile_no'];
       
       $dob=$row['dob'];
       $name_bn=$row['name_bn'];
       $home_dist=$row['home_dist'];
       $doj=$row['doj'];
       $join_designation=$row['join_designation'];
       //$blood_group=$row['blood_group'];
       $sub_cadre_header=$row['sub_cadre_header'];
      $last_update_at=$row['last_update_at'];
       // $designation_ext=$doj.' , '.$join_designation.'('.$sub_cadre_header.')';
       
       if($sub_cadre_header==''){
          $designation_ext=$doj.' , '.$join_designation;
          }
          else{
                $designation_ext=$doj.' ,  '.$join_designation.' ('.$sub_cadre_header.')';
          }
       
       //$email=$row['email'];
       //$quota=$row['quota'];
       //$maritial_status=$row['maritial_status'];
       //$spouse_name=$row['spouse_name'];
       //$spouse_occupation=$row['spouse_occupation'];
       
       //$present_add=$row['present_add'];
       $permanent_add=$row['permanent_add'];
        $last_promo_date_db =$row['last_promo_date'];

       $doj1 = new DateTime($row['doj']);
       $today1= date('Y-m-d');
       $today=new DateTime($today1);
      $total_service_period =$doj1->diff($today);

      $last_promo_date1 = new DateTime($row['last_promo_date']);

      if($last_promo_date_db=='0000-00-00'){
          $last_promo_date =$doj1->diff($today);
      }
      else{
          $last_promo_date =$last_promo_date1->diff($today);
      }                          

       ?>

       <div class="container-fluid">
          <div class="accordion-item ">
              <h5 class="accordion-header text-uppercase text-center">
                
                 <b class=""> General Information</b>
                
              </h5>
            <div  class="" >
          <div class="accordion-body fs-6">

       <?php
       
        echo "<table class='table table-bordered border table-hovered table-striped text-muted ' style='line-height: 1;' class='p-1'>";
            // echo  '<thead >';
            echo '<tbody>';

            echo '<tr>';
            echo '<tr>';
            echo '<td  class="table-success fw-bold"> Name (BN)</td>';
            echo '<td>' . $row['name_bn'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">National ID</th>';
            echo '<td>' . $row['nid'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Mobile Number</th>';
            echo '<td>' . $row['mobile_no'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Date of Birth</th>';
            echo '<td>' . $row['dob'] . '</td>';
            echo '</tr>';

            // echo '<tr>';
            // echo '<th class="table-success">Email</th>';
            // echo '<td>' . $row['email'] . '</td>';
            // echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Joining Date & Designation</th>';
            echo '<td>' . $designation_ext . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">PRL Date</th>';
            echo '<td>' . $row['prl'] . '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<th class="table-success">Place of Posting</th>';
            echo '<td>' . $row['place_of_posting'] . '</td>';
            echo '</tr>';
                       echo '<tr>';
            echo '<th class="table-success">Date of Last Promotion</th>';
            echo '<td>' . $row['last_promo_date'] . '</td>';
            echo '</tr>';
            
            echo '<tr>';
            echo '<th class="table-success">Date of Next Promotion</th>';
            echo '<td>' . $row['next_promo_date'] . '</td>';
            echo '</tr>';
            
            echo '<th class="table-success" width="50%" style="height:10px;">Service Period (Current Designation)</th>';
            echo '<td>' . $last_promo_date->y . ' years, ' . $last_promo_date->m . ' months, ' . $last_promo_date->d . ' days </td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th class="table-success">Total Service Period</th>';
            echo '<td>' . $total_service_period->y . ' years, ' . $total_service_period->m . ' months, ' . $total_service_period->d . ' days </td>'; 
            echo '</tr>';;

        echo "</tbody>";
        echo "</table>";

       
       
        }
      }
      else {
        // echo "<p class='btn btn-danger btn-xs text-white'> No Record Found!!! </p>";
      }

      //$_SESSION['seniority_no']=$row['seniority_no'];
      // mysqli_close($conn);
       
      ?>
      </div>
    </div>
</div>
     
 <?php
  
 if(isset($_POST['emp_id'])){
   
   //$emp_id=$_SESSION['emp_id'];
   $sql="SELECT * from edu_info where emp_id='$emp_id'";
 
 }
  $result = mysqli_query($conn,$sql);

 if (mysqli_num_rows($result) > 0) {
       ?>
    <div class="">
        <h5 class="accordion-header text-uppercase text-center">
          
            <b class="">Educational Qualification</b>
          
        </h5>
        <div class="">
          <div class="accordion-body">
   <?php 
  // echo "<div class='table-responsive-lg'>";
echo "<table class='table table-bordered table-striped text-center' style='line-height: 1;' class='p-1'>";
    echo  '<thead class="table-success ">';
      echo  '<tr>';
        echo  '<th>Examination</th>';
        echo  '<th>Subject/Group</th>';
        echo  '<th>Institute</th>';
        echo  '<th>Board/Univ. </th>';
        // echo  '<th>CGPA/Division/Class</th>';
        echo  '<th>Result</th>';
        echo  '<th>Year</th>';
        echo  '<th>Duration</th>';

      echo  '</tr>';
      echo  '</thead>';
 
  while($row = mysqli_fetch_array($result)) {

  $ssc_exam=$row['ssc_exam'];
   $ssc_group_name=$row['ssc_group_name'];
   $ssc_inst_name=$row['ssc_inst_name'];
   $ssc_board_or_univ=$row['ssc_board_or_univ'];
   $ssc_div_or_cgpa=$row['ssc_div_or_cgpa'];
   
   $ssc_passing_year=$row['ssc_passing_year'];

   $hsc_exam=$row['hsc_exam'];
   $hsc_group_name=$row['hsc_group_name'];
   $hsc_inst_name=$row['hsc_inst_name'];
   $hsc_board_or_univ=$row['hsc_board_or_univ'];
   $hsc_div_or_cgpa=$row['hsc_div_or_cgpa'];
   $hsc_passing_year=$row['hsc_passing_year'];
   
   $honors_exam=$row['honors_exam'];  
   $honors_group_name=$row['honors_group_name'];
   $honors_inst_name=$row['honors_inst_name'];
   $honors_board_or_univ=$row['honors_board_or_univ'];
   $honors_div_or_cgpa=$row['honors_div_or_cgpa'];  
   $honors_passing_year=$row['honors_passing_year'];
   $honors_course_duration=$row['honors_course_duration'];

   $masters=$row['masters'];
   $ms_group_name=$row['ms_group_name'];
   $ms_inst_name=$row['ms_inst_name'];  
   $ms_board_or_univ=$row['ms_board_or_univ'];
   $ms_div_or_cgpa=$row['ms_div_or_cgpa'];
   $ms_passing_year=$row['ms_passing_year'];
   $ms_course_duration=$row['ms_course_duration'];

  
 echo  '<tbody>';
      echo  '<tr>';
          echo  '<td>'.  $ssc_exam.'</td>';
          echo  '<td>'.  $ssc_group_name.'</td>';
          //echo  '<td>'.  $dolp.'</td>';
          echo  '<td>'.  $ssc_inst_name.'</td>';
          echo  '<td>'.  $ssc_board_or_univ.'</td>';
          echo  '<td>'.  $ssc_div_or_cgpa.'</td>';
          
          echo  '<td>'.  $ssc_passing_year.'</td>';
          echo  '<td>'. '' .'</td>';
        echo  '</tr>';

        if($hsc_exam!=Null && $hsc_group_name!=Null && $hsc_inst_name!=Null && $hsc_board_or_univ!=Null && $hsc_div_or_cgpa!=Null && $hsc_passing_year!=Null){
          echo  '<tr>';
          echo  '<td>'.  $hsc_exam.'</td>';
          echo  '<td>'.  $hsc_group_name.'</td>';
          //echo  '<td>'.  $dolp.'</td>';
          echo  '<td>'.  $hsc_inst_name.'</td>';
          echo  '<td>'.  $hsc_board_or_univ.'</td>';
          echo  '<td>'.  $hsc_div_or_cgpa.'</td>';
          
          echo  '<td>'.  $hsc_passing_year.'</td>';
          echo  '<td>'. '' .'</td>';
        echo  '</tr>';

        }

        if($honors_exam !=Null && $honors_group_name!=Null && $honors_inst_name!=Null && $honors_board_or_univ!=Null && $honors_div_or_cgpa!=Null && $honors_passing_year!=Null && $honors_course_duration!=Null){

        echo  '<tr>';
          echo  '<td>'.  $honors_exam.'</td>';
          echo  '<td>'.  $honors_group_name.'</td>';
          //echo  '<td>'.  $dolp.'</td>';
          echo  '<td>'.  $honors_inst_name.'</td>';
          echo  '<td>'.  $honors_board_or_univ.'</td>';
          echo  '<td>'.  $honors_div_or_cgpa.'</td>';
          
          echo  '<td>'.  $honors_passing_year.'</td>';
          echo  '<td>'.  $honors_course_duration.'</td>';
        echo  '</tr>';
      }

        if($masters !=Null && $ms_group_name!=Null && $ms_inst_name!=Null && $ms_board_or_univ!=Null && $ms_div_or_cgpa!=Null && $ms_passing_year!=Null && $ms_course_duration!=Null){

          echo  '<tr>';
            echo  '<td>'.  $masters.'</td>';
            echo  '<td>'.  $ms_group_name.'</td>';
            //echo  '<td>'.  $dolp.'</td>';
            echo  '<td>'.  $ms_inst_name.'</td>';
            echo  '<td>'.  $ms_board_or_univ.'</td>';
            echo  '<td>'.  $ms_div_or_cgpa.'</td>';
            
            echo  '<td>'.  $ms_passing_year.'</td>';
            echo  '<td>'.  $ms_course_duration.'</td>';
        echo  '</tr>';
  
        }
  
    }

 }
 else {
        // echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
      }
 echo  '</tbody>';
echo  '</table>';
 
?>
      </div>
    </div>
</div>
        
<?php
        
  if(isset($_POST['emp_id'])){
         
         //$emp_id=$_SESSION['emp_id'];
    $sql="SELECT * from training_info where emp_id='$emp_id'";
         //$sql="SELECT * FROM users where emp_id='$emp_id'";
       }
     $result = mysqli_query($conn,$sql);
    if (mysqli_num_rows($result) > 0) {

      ?>
      <div class="accordion-item">
          <h5 class="accordion-header text-center text-uppercase" >
           
             <b class=""> Training Information</b>
            
          </h5>
          <div class="" >
        <div class="accordion-body">
      <?php
      echo "<table class='table table-bordered table-hovered table-striped text-center' style='line-height: 1;' class='p-1'>";
          echo  '<thead class="table-success">';
            echo  '<tr>';
              echo  '<th>Training Type</th>';
              echo  '<th>Title</th>';
          
              echo  '<th>Institute</th>';
              echo  '<th>Country</th>';
              echo  '<th>Period</th>';
              echo  '<th>Month/ Year</th>'; 
            echo  '</tr>';
          echo  '</thead>';
       
       while($row = mysqli_fetch_array($result)) {
        
         $type=$row['type'];
         $title=$row['title'];
         $institute=$row['institute'];
         
         $country=$row['country'];
          
         $period=$row['period'];
         $month_year=$row['month_year'];

       echo  '<tbody>';
            echo  '<tr>';
          
              echo  '<td>'.  $type.'</td>';
              echo  '<td>'.  $title.'</td>';
              echo  '<td>'.  $institute.'</td>';
              echo  '<td>'.  $country.'</td>';
              echo  '<td>'.  $period.'</td>';
              echo  '<td>'.  $month_year.'</td>';
          
            echo  '</tr>';
              
         }
       }
       else {
              // echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
            }
        echo  '</tbody>';
        echo  '</table>';

?>
          </div>
        </div>
      </div>
 <?php
           
     if(isset($_POST['emp_id'])){
     
      $sql="SELECT * from service_history where emp_id='$emp_id'";
         //$sql="SELECT * FROM users where emp_id='$emp_id'";
       }
       $result = mysqli_query($conn,$sql);
       if (mysqli_num_rows($result) > 0) {

        ?>

        <div class="">
            <h5 class="accordion-header text-uppercase text-center">
              
                <b class="text-center">Service History </b>
             
            </h5>
            <div class="">
          <div class="accordion-body">
        <?php
      echo "<table class='table table-bordered table-hovered table-striped text-center' style='line-height: 1;' class='p-1'>";
          echo  '<thead class="table-success">';
          echo  '<tr>';
          echo  '<th>Organization</th>';
          echo  '<th>From Date</th>';
          
          echo  '<th>To Date</th>';
          echo  '<th>Job Location</th>';
          echo  '<th>Designation</th>';
          echo  '<th>Scale</th>'; 
          echo  '</tr>';
          echo  '</thead>';
       
       while($row = mysqli_fetch_array($result)) {
        $org_name=$row['org_name'];
         $from_date=$row['from_date'];
         
         $to_date=$row['to_date'];
         
         $location=$row['location'];
         $designation=$row['designation'];
         $scale=$row['scale'];
       echo  '<tbody>';
          echo  '<tr>';
          echo  '<td>'.  $org_name.'</td>';
          echo  '<td>'.  $from_date.'</td>';
              
          echo  '<td>'.  $to_date.'</td>';
          echo  '<td>'.  $location.'</td>';
          echo  '<td>'.  $designation.'</td>';
          echo  '<td>'.  $scale.'</td>';
          
          echo  '</tr>';
              
        }
       }
       else {
              // echo "<p class='btn btn-danger btn-block text-left'> Record Not Found!!!</p>";
            }
        echo  '</tbody>';
        echo  '</table>';
 
      ?>
          </div>
      </div>
    </div>


    <div class="accordion-item">
        <h6 class="accordion-header float-end text-center mt-5" >
          
           <b> Signature & Seal of Employee<br>
            Whose Particulars has been Printed. </b>
          
        </h6>
        <br><br><br>
        <hr class="text-muted">
          <h6 class="text-center" >
           <b> Print date: <?php echo date('d-m-Y');?></b><br>
           <b> Last Update Date & Time: <?php echo $last_update_at;?></b>
          
          </h6>       
       
      </div>
    </div>
  </div>
</div>
</body>
</html>
<div style="break-after:page"></div>

<?php
  }
}
mysqli_close($conn);
?>