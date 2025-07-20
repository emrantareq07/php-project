<?php
// Start the session
session_start();

$_SESSION['emp_id']=$_SESSION['emp_id'];

?>
<?php include('../includes/header.php');?>
 <div class="container text-center p-4 my-2 bg-light border shadow rounded">
          <center>
       <h2 class="page-header text-success m-1 text-center badge bg-dark bg-outline-info text-wrap" style="width: 70rem; font-size: 30px;">View Information of <b class="text-white">[--<?php echo "Employee ID: ".$_SESSION['emp_id'];?>--]</b>
    
    </h2> 
      </center>
    <h4><?php //echo $_SESSION['emp_id']; ?></h4>
      <div class="row">
    
        <div class="col-sm-12 ">

<?php
include('../db/db.php');
 
 if(isset($_SESSION['emp_id'])){
   
   $emp_id=$_SESSION['emp_id'];
   //$sql="SELECT * FROM users u, childs_info c where u.emp_id=c.emp_id AND emp_id='$emp_id'";
   //$sql="SELECT * from users u,  childs_info c where u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
   $sql="SELECT * FROM basic_info where emp_id='$emp_id'";
   //$sql="SELECT * FROM users u INNER JOIN childs_info c ON u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
    $sql1="SELECT dob FROM job_desc where emp_id='$emp_id'";
    $result1 = mysqli_query($conn,$sql1);
    $row1=mysqli_fetch_array($result1);
    $dob=$row1['dob'];

    if(isset($row1['dob'])==''){
      $dob='';
    }else{
      $dob=$row1['dob'];
    }
 }
 $result = mysqli_query($conn,$sql);
 
 while($row = mysqli_fetch_array($result)) {

 $fathersName=$row['fathersName'];
 $mothersName=$row['mothersName'];

 $gender=$row['gender'];
 $blood_group=$row['blood_group'];
 $religion=$row['religion'];
 $nid=$row['nid'];
 $mobile_no=$row['mobile_no'];
 $nationality=$row['nationality'];
 $home_dist=$row['home_dist'];
 $email=$row['email'];
 $quota=$row['quota'];
 $maritial_status=$row['maritial_status'];
 $spouse_name=$row['spouse_name'];
  $spouse_home_dist=$row['spouse_home_dist'];
  $spouse_occupation=$row['spouse_occupation'];
  $spo_present_add=$row['spo_present_add'];
  $spo_parmanent_add=$row['spo_parmanent_add'];
 
 $present_add=$row['present_add'];
 $permanent_add=$row['permanent_add'];
 
 echo "<table class='table table-bordered table-striped text-center'>
 <thead class='thead-dark'>
 <tr style='background-color:#ffffff;color:black; border:1px solid #e0dcdc'> <td class='text-left' colspan='8'><b>General Information</b></td> </tr>
 <tr>
 
 
 <th>Fathers Name</th>
  <th >Mothers Name</th>
 <th>Date of Birth</th>
 <th> Gender </th>
 <th> Blood Group </th>
  <th>Religion</th>
 <th> National ID </th>
 <th> Mobile Number </th>
 </tr>
  </thead>";
  echo "<tbody>";
  echo "<tr>";
  echo "<tr>";
  echo "</tr>";
  echo "<td>" .  $fathersName. "</td>";
  echo "<td>" .  $mothersName. "</td>";
  echo "<td>" . $dob. "</td>";
 echo "<td>" .  $gender. "</td>";
 echo "<td>" .  $blood_group. "</td>";
 echo "<td>" . $religion. "</td>";
 echo "<td>" .  $nid. "</td>";
 echo "<td>" .  $mobile_no. "</td>";
  
 echo "</tr>";
 
  echo "</tbody>";
 echo "</table>";
 
 echo "<table class='table table-bordered table-striped text-center'>
 <thead class='thead-dark'>
 <tr>
  
 <th >Nationality</th>
  <th >Home District</th>
  <th>Email</th>
  <th> Quotas </th>
  <th>Maritial Status</th>

  <th>Present Address</th>
  <th> Permanent Address </th>
 
 </tr>
  </thead>";
  echo "<tbody>";
  echo "<tr>";
 
echo "<td>" .  $nationality. "</td>";
 echo "<td>" .  $home_dist. "</td>";
 echo "<td>" .  $email. "</td>";
 echo "<td>" . $quota. "</td>";
 echo "<td>" .  $maritial_status. "</td>";

 echo "<td>" .  $present_add. "</td>";
 echo "<td>" .  $permanent_add. "</td>";
  
 echo "</tr>";
 echo "</tbody>";
 echo "</table>";

 echo "<table class='table table-bordered table-striped text-center'>
  <thead class='thead-dark'>
  <tr style='background-color:#5f228a;color:white;'> 
    <td class='text-left' colspan='5'><b>Spouse Information</b></td> 
  </tr>
  <tr>
    <th class='text-center'>Name</th>
    <th class='text-center'> Home District </th>
    <th class='text-center'> Occupation </th>
    <th class='text-center'>Present Address</th>
    <th class='text-center'> Permanent Address </th>
  </tr>
  </thead>";
  echo "<tbody class='text-center'>";

  echo "<tr>";
  echo "<td>" .  $spouse_name. "</td>";
  echo "<td>" .  $spouse_home_dist. "</td>";
  echo "<td>" . $spouse_occupation. "</td>";
  echo "<td>" .  $spo_present_add. "</td>";
  echo "<td>" . $spo_parmanent_add. "</td>";
 echo "</tr>";
 echo "</tbody>";
 echo "</table>";

}

$sql="SELECT * FROM childs_info where emp_id='$emp_id'";
 //$sql="SELECT * FROM users u INNER JOIN childs_info c ON u.emp_id=c.emp_id AND u.emp_id='$emp_id'";
 
 $result = mysqli_query($conn,$sql);
 echo "<table class='table table-bordered table-striped text-center'>
  <thead class='thead-dark'>
  <tr style='background-color:#5f228a;color:white;'> <td class='text-left' colspan='3'><b>Children Information</b></td> </tr>
  <tr>
  <th class='text-center'>Name</th>
  <th class='text-center'>Date of Birth</th>
  <th class='text-center'> Gender </th>
  </tr>
  </thead>";
 
 while($row = mysqli_fetch_array($result)) {
  $name=$row['name'];
  $dob=$row['dob'];
  $gender=$row['gender'];

  echo "<tbody class='text-center'>";
  echo "<tr>";
  echo "</tr>";
  echo "<tr>";
  echo "<td>" .  $name. "</td>";
  echo "<td>" .  $dob. "</td>";
  echo "<td>" . $gender. "</td>";
 echo "</tr>";
 }
 echo "</tbody>";
 echo "</table>";

 
 mysqli_close($conn);
 
?>


  <h4><a class="btn btn-success" href="view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous Page </a></h4>
</div>
</div>
<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4></div>
<div class="col-sm-4 "></div>

</div>
</div>
<?php include('../includes/footer.php');?>
