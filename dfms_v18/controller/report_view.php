<?php
include('header.php');
?>
 
<div class="container">
  
 <div class="row">
  
    <div class="col-sm-3">
      <h2></h2>
      
    </div>
	<div class="col-sm-6">
	<h2 class="text-success text-center">Urea Production Report</h2>
  <p></p> 
	<div class="row">
      	<div class="col-sm-6">
	<h2 class="text-success text-center"> Factory Name</h2>
	<hr>
			 <div class="dropdown" align="center">
		<button type="button" class="btn btn-primary dropdown-toggle pull-right" data-toggle="dropdown">
		  Select Product..
		</button>
		<div class="dropdown-menu">
		  <a class="dropdown-item" href="sfcl_serach_by_date.php">SFCL</a>
		  <div class="dropdown-divider"></div>
		  <a class="dropdown-item" href="jfcl_urea_form.php">JFCL</a>
		</div>
	  </div>
    </div>
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
	<p></p> 
	<h4 class="text-center"><a href="index.php">Back to Main Page</a></h4>
	</div>
	
    <div class="col-sm-3">
      <h3><a href="report_view.php">View Report</a></h3>
    </div>
  </div>
 
</div>
<?php
include('footer.php');
?>