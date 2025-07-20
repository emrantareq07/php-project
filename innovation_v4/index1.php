<?php
// Start the session
//session_start();
?>
<?php include('includes/header.php');?>


 <div class="container p-3 my-3 border shadow">
    	<h2 class="page-header text-muted text-uppercase text-center">Innovation Database<?php //echo " Employee ID ".$_SESSION['emp_id']; ?></h2>
		<h4><?php //echo $_SESSION['emp_id']; ?></h4>
    	<div class="row">
		
    		<div class="col-sm-12 ">

<?php
include('db/db.php');

 
 //if(isset($_SESSION['emp_id'])){
	 
	 //$emp_id=$_SESSION['emp_id'];
	 //$sql="SELECT * FROM innovation where emp_id='$emp_id'";
	 $sql="SELECT * FROM innovation ORDER BY id desc";
 //}
 $result = mysqli_query($conn,$sql);
 
 echo "<table id='example' class='table table-bordered table-striped '>
 <thead class='thead-dark'>
 <tr>
 
 <th> # </th>
 <th class='text-center p-4'>অর্থবছর</th>
  <th class='text-center p-4'>উদ্ভাবনের শিরোনাম</th>
 <th class='text-justify'>উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
 <th class='text-center p-4'> উদ্ভাবনের বর্নণা </th>
 <th class='text-center'> বাস্তাবয়নের অবস্থা</th>
  <th class='text-center'>রেপ্লিকেট যোগ্যতা</th>
 
 </tr>
  </thead>";
 
 while($row = mysqli_fetch_array($result)){
 $id=$row[0];
 $fiscal_year=$row[1];
 $title_of_invention=$row[2];

 $inventors_name=$row[3];
 $inventors_designation=$row[4];
 $inventors_emp_id=$row[5];
 $proposed_workplace=$row[6];
 
 $des_of_invention=$row[7];
 $imple_status=$row[8];
 $replicate_eligibility=$row[9];
  
  
 echo "<tr>";
 echo "<td>" .  $id. "</td>";
  echo "<td>" .  $fiscal_year. "</td>";
 echo "<td class='text-justify'>" .  $title_of_invention. "</td>";
 echo "<td class='text-justify'>" . $inventors_name. "<br> " . $inventors_designation. "<br> " . $inventors_emp_id. "<br>" . $proposed_workplace."</td>";
 //echo "<td>" . $row['inventors_name']. "".$row['inventors_designation']."</td>";
 //echo "<td>" .  $inventors_designation. "</td>";
 // echo "<td>" .  $inventors_emp_id. "</td>";
 //echo "<td>" .  $proposed_workplace. "</td>";
 echo "<td class='text-justify'>" . $des_of_invention. "</td>";
 echo "<td>" .  $imple_status. "</td>";
 echo "<td>" .  $replicate_eligibility. "</td>";
  
 echo "</tr>";
 }
 echo "</table>";

 
 mysqli_close($conn);
 
?>


  
</div>


</div>

<div class="row">
		
    		<div class="col-sm-4 ">
			
			</div>
			<div class="col-sm-4">
			<h4 class="text-center btn btn-p"><a  href="view_profile_details.php"> Back Previous Page </a>
			
			||
			<a  class="text-center btn btn-danger" href="create_pdf.php" target="_blank"> Print PDF </a>
			</h4>
			
			</div>
			<div class="col-sm-4 ">
			
			</div>
</div>
<!--
<div class="row "> 
<div class="col-sm-4 "></div>
<div class="col-sm-4 "><h4 class="text-center"><a href="logout.php" class="btn btn-danger"> Logout </a></h4></div>
<div class="col-sm-4 "></div>

</div>-->

   <script>
   $(document).ready(function () {
    $('#example').DataTable();
});


   </script>
   <script src=" https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
</div>
<?php include('includes/footer.php');?>
