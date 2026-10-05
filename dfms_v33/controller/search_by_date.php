<?php
include('header.php');
?>
 <?php
include('db/db.php');
?>
  
<div class="container">
  
 <div class="row">
  
  	<div class="col-sm-9">
	 <div class="row">
	<h2 class="text-success">Search by Date</h2>
	 <div class="col-sm-2">
      <h3></h3>
    </div>
	
      <div class="col-sm-4">
      <form action="search_by_date.php" method="post" class="needs-validation" novalidate>	
			<div class="form-group">
			  <label for="date">Date:</label>
			  <input type="date" class="form-control" id="date" placeholder="Enter Date" name="date" required>
			  <div class="valid-feedback">Valid.</div>
			  <div class="invalid-feedback">Please fill out this field.</div>
			</div>		
			<button type="submit" name="submit" class="btn btn-primary pull-middle">Search</button>
	  </form>
    
    </div>
	<div class="col-sm-3">
	<h3></h3>
	</div>
	
	 </div>	
	  <label for=""></label>
	  
	  <table class="table table-bordered table-striped text-center">
    <thead class="thead-dark text-center" >
        <tr>
		<th>Factory Name</th>
			<th>Date</th>
            <th>Daily</th>
            <th>Monthly Cumulative(MT)</th>
            <th>Yearly Cumulative(MT)</th>
			<th>Plant Load(%)</th>
            <th>Production Target(MT)</th>
            <th>Due</th>
            <th>Stock on date</th>
      </tr>
    </thead>
    <tbody>
  <?php
	include('db/db.php');
	if(isset($_POST['submit'])){

	$date=$_POST['date'];
	
	
	 //$sql =  "SELECT * FROM sfcl WHERE `date` LIKE '{$date}%'";
	$sql ="select s.factory_name, s.daily,s.monthly, s.yearly from sfcl s JOIN jfcl j ON s.date=j.date";
        
            //while($row = mysqli_fetch_array($result)){

         // $sql = "SELECT e.fullname, e.address, e.contact_no, e.email, d.dept_name, des.des_name 
		 // FROM employee e 
		 // JOIN department d 
		 // ON e.dept_id=d.dept_id 
		 // JOIN designation des 
		 // ON e.des_id=des.des_id ORDER BY e.fullname;";
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			  // output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
	
	
        //$result = mysql_query("SELECT * FROM report_urea WHERE `date` LIKE '{$date}%'");
        
            //while($row = mysql_fetch_array($result)){  ?>
            <tr>
			  <td><?php echo $row['factory_name']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['daily']; ?></td>
                <td><?php echo $row['monthly']; ?></td>
                <td><?php echo $row['yearly']; ?></td>
				<td><?php echo $row['plant_load']; ?></td>
                <td><?php echo $row['production_target']; ?></td>
                <td><?php echo $row['due']; ?></td>
                <td><?php echo $row['stock_on_date']; ?></td>
                
            </tr>
            <?php
            }
			}
		 /* if (!mysql_query($result,$con))
		 {
		 die('Error: ' . mysql_error());
		 } */
		 mysql_close($con)?>
    </tbody>
</table>
<?php
 // include('db.php');
 // if(isset($_POST['submit'])){

 // $date=$_POST['date'];
 
 // $result = mysql_query("SELECT * FROM report_urea WHERE `date` LIKE '{$date}%'");
 
  // echo "<table border='1' style='position:center'>
 // <thead>
 // <tr>
 // <th>Daily </th>
 // <th>Monthly</th>
 // <th>Yearly</th>

 // <th>Date</th>
 // </tr>
 // </thead>";
 
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
   
 // }
 // /* if (!mysql_query($result,$con))
 // {
 // die('Error: ' . mysql_error());
 // } */
 // mysql_close($con)
 
 // ?> 
 <h4 class="text-center"><a href="index.php">Back to Main Page</a></h4>
    </div>
    <div class="col-sm-3">
      <h3><a href="urea_report.php">View Urea Report</a></h3>
    </div>
  </div>
 
</div>

<?php
include('footer.php');
?>
 