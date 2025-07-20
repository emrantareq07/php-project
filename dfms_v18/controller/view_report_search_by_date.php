<?php
session_name('dfms');
include('include/header.php');
?>
<style type="text/css">
        .box {
            color: black;
            display: none;
            margin-top: 20px;
        }
</style>
			
  <script type="text/javascript">
/*  $(document).ready(function(){
	$("#table_content").hide();
  $("#submit").click(function(){
     $("#table_content").show();
	 return true;
  });
});  */

/*  $(document).ready(function() {
            $('input[type="submit"]').click(function() {
                var inputValue = $(this).attr("value");
                $("." + inputValue).toggle();
            });
        }); */
		
/* function showTable() {
   document.getElementById('welcomeDiv').style.display = "block";
} */		
</script>			


<!--<div id="welcomeDiv"  style="display:none;" class="answer_list" > WELCOME</div>
<input type="button" name="answer" value="Show Div" onclick="showDiv()" />-->
			
<div class="container">
  
 <div class="row">
  
    <div class="col-sm-2">
      
      
    </div>
	<div class="col-sm-6">
	<h2 class="text-success text-center my-1">Urea Production Report --[All Factory]-- </h2>
	</div>
  
   <div class="col-sm-4">
     
      <span class="text-center float-end">
	  <a href="report_search_by_date.php" class="btn btn-primary">Back to Previous Page</a>
	  <a href="index.php" class="btn btn-primary">Back to Main Page</a></span>
    </div>
 </div>	
 <?php error_reporting (E_ALL ^ E_NOTICE); ?>
<!--	
<div class="col-sm-3 ">
<fieldset class="border p-2 my-2 rounded">
                <legend class="float-none w-auto p-2">Select Date:</legend>
      <form action="view_report_search_by_date.php" id="search_form" method="post" class="needs-validation" >	
			<div class="form-group">
			 
			  <div class="form-group">
			  <label for="date"></label>
			  <input type="date" class="form-control" id="date" placeholder="Enter Date" name="date" required>
			  <div class="valid-feedback">Valid.</div>
			  <div class="invalid-feedback">Please fill out this field.</div>
			</div>		
			<center><button type="submit" name="submit" id="submit" class="btn btn-primary pull-middle">Search</button></center>
			
			</div>

	  </form>
	  </fieldset>

    
  </div>-->
  
<div class="row">
  <div class="col-sm-12">
  <div class="card">
  <div class="card-header"> </div>
  <div class="card-body">
  <table class="table table-bordered table-striped" id="table_content">
    <thead>
        <tr>
			<th>Factory Name</th>
			<th>Date</th>
            <th>Daily</th>
            <th>Monthly</th>
            <th>Yearly</th>
			 <th>Production Target</th>
			  <th>Due</th>
			   <th>Plant Load(%)</th>
			   <th>Causes of Shutdown</th>
      </tr>
    </thead>
    <tbody>
	
	
	
	
  <?php
	include('db/db.php');
	if(isset($_POST['submit'])){

	$date=$_POST['date1'];
	
        //$sql =  "SELECT * FROM sfcl AND jfcl WHERE `date` LIKE '{$date}%'";
		// $sql="SELECT sfcl.*,jfcl.*
		// FROM sfcl, jfcl 
		// WHERE `date` like '{$date1}%'";

		// $sql="SELECT s.date,j.date,a.date from sfcl s, jfcl j, afccl a where `date` like '{$date1}'";
		
		$sql="SELECT * FROM sfcl 
		WHERE `date` LIKE '{$date}%'
		union
		
		SELECT * FROM afccl 
		WHERE `date` LIKE '{$date}%'
		union
		
		SELECT * FROM jfcl 
		WHERE `date` LIKE '{$date}%' ORDER BY date desc";
		
       // $sql="SELECT s.date, s.factory_name,j.factory_name, s.daily, s.monthly, s.yearly FROM sfcl s JOIN jfcl j WHERE `date` LIKE '{$date}%'"; 
            //while($row = mysqli_fetch_array($result)){

         // $sql = "SELECT e.fullname, e.address, e.contact_no, e.email, d.dept_name, des.des_name 
		 // FROM employee e 
		 // JOIN department d 
		 // ON e.dept_id=d.dept_id 
		 // JOIN designation des 
		 // ON e.des_id=des.des_id ORDER BY e.fullname;";
			 $result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			  //output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  
			?>
             <tr>
			<td class="text-uppercase"><?php echo $row['factory_name']; ?></td>
                <td ><?php echo $row['date']; ?></td>
                <td><?php echo $row['daily']; ?></td>
                <td><?php echo $row['monthly']; ?></td>
                <td><?php echo $row['yearly']; ?></td>
				<td><?php echo $row['production_target']; ?></td>
                <td><?php echo $row['due']; ?></td>
                <td><?php echo $row['plant_load']; ?></td>
				<td><?php echo $row['remarks']; ?></td>
                
            </tr> 
		
            <?php
			   }
			  $sfcl_sql="SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'";
			  			$result = mysqli_query($conn, $sfcl_sql);

			  //output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  $sfcl_daily=$row['daily'];
				  $sfcl_monthly=$row['monthly'];
				  $sfcl_yearly=$row['yearly'];
				  $sfcl_prod_tar=$row['production_target'];
				  $sfcl_due=$row['due'];
				  
			  }
			  
			  $jfcl_sql="SELECT * FROM jfcl WHERE `date` LIKE '{$date}%'";
			  	$result = mysqli_query($conn, $jfcl_sql);

			  //output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  $jfcl_daily=$row['daily'];
				  $jfcl_monthly=$row['monthly'];
				  $jfcl_yearly=$row['yearly'];
				  $jfcl_prod_tar=$row['production_target'];
				  $jfcl_due=$row['due'];
			  }

			  
			  
			  $afccl_sql="SELECT * FROM afccl WHERE `date` LIKE '{$date}%'";
			  	$result = mysqli_query($conn, $afccl_sql);

			  //output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  $afccl_daily=$row['daily'];
				  $afccl_monthly=$row['monthly'];
				  $afccl_yearly=$row['yearly'];
				  $afccl_prod_tar=$row['production_target'];
				  $afccl_due=$row['due'];
			  }
			  
			  @$total_prod_daily=(int)$sfcl_daily+(int)$jfcl_daily+(int)$afccl_daily;
			  @$total_prod_monthly=(int)$sfcl_monthly+(int)$jfcl_monthly+(int)$afccl_monthly;
			  @$total_prod_yearly=(int)$sfcl_yearly+(int)$jfcl_yearly+(int)$afccl_yearly;
			  @$total_prod_tar = (int)$sfcl_prod_tar + (int)$jfcl_prod_tar + (int)$afccl_prod_tar;
			  @$total_due = (int)$sfcl_due + (int)$jfcl_due + (int)$afccl_due;
			  
			  	echo '<tr>
			<td ></td>
			<td >Total Production:</td>
			<td >'. $total_prod_daily .'</td>
			<td >'. $total_prod_monthly .'</td>
			<td >'. $total_prod_yearly .'</td>
			<td >'. $total_prod_tar .'</td>
			<td >'. $total_due .'</td>
			</tr>';
			}
			else {

				echo "<p class='btn btn-danger'> Record Not Found!!!</p>";
				echo "<br/>";
			}
             }?>
    </tbody>
</table>
  </div>
  <div class="card-footer text-right"><i>Design & Developed By Md. Tareq Emran</i></div>
</div>
  
  
  

<?php
// include('db.php');
 // $result = mysql_query("SELECT * FROM report_urea");
 
 // echo "<table border='1' style='float:rignt'>
 // <thead>
 // <tr>
 // <th>Daily </th>
 // <th>Monthly</th>
 // <th>Yearly</th>

 // <th>Date</th>
 // </tr>
 // <thead>";
 
 // while($row = mysql_fetch_array($result))
 // {
 // echo "<tr>";
 // echo "<td>" . $row['daily'] . "</td>";
 // echo "<td>" . $row['monthly'] . "</td>";
 // echo "<td>" . $row['yearly'] . "</td>";
 
 // echo "<td>" . $row['date'] . "</td>";
 // echo "</tr>";
 // }
 // echo "</table>";
 
// ?>

	
	</div>
	
    <div class="col-sm-0">
      <!--<h3><a href="report_view.php">View Report</a></h3>-->
    </div>
  </div>
 
</div>

<?php
include('include/footer.php');
?>
