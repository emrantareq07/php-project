<?php
include('db/db.php');
$res=mysqli_query($conn,"SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl WHERE fiscal_year='২০১৭-২০১৮'
	UNION
SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl WHERE fiscal_year='২০১৮-২০১৯'
UNION
SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl WHERE fiscal_year='২০১৯-২০২০'
UNION
SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl WHERE fiscal_year='২০২০-২০২১'
UNION
SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl WHERE fiscal_year='২০২১-২০২২'

UNION
SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl WHERE fiscal_year='২০২২-২০২৩'");


// $res=mysqli_query($conn,"SELECT fiscal_year, no_of_award, no_of_officer, SUM(no_of_award) As Total_award, SUM(no_of_officer) As Total_officer FROM( 
// (SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation WHERE fiscal_year='২০১৮-১৯') 
// UNION  
// (SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation WHERE fiscal_year='২০১৯-২০') )innovation;");

//$res=mysqli_query($conn,"SELECT id,fiscal_year,title_of_invention,inventors_name,inventors_designation,
 //inventors_emp_id,proposed_workplace,des_of_invention, imple_status,replicate_eligibility FROM innovation order by id desc");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Innovation Database - BCIC.</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
  
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="bootstrap/css/bootstrap-theme.css" rel="stylesheet">
  <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
  
  <link href="DataTables/jquery.dataTables.min.css" rel="stylesheet">
  
   <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  
  
  <link href="DataTables/datatables.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <style>
 
* {
	font-family: 'Open Sans', sans-serif;

    font-family: 'Tiro Bangla', serif;
	<!--font-family: 'Noto Sans Bengali', sans-serif;

    font-family: 'Nikosh', sans-serif;

    font-family: 'Nikosh', serif;-->
}

  </style>
 <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
  
</head>
<body>
	<div class="container p-3 my-3 border shadow" style="margin-top:50px;width:100%;">
	
	<div class="row">    
	<div class="col col-sm-6">    </div>
	<div class="col col-sm-3">    </div>
	<div class="col col-sm-3">
                            
          <span class="float-end"><a href="index.php" class="btn btn-success text-white my-2"><i class='fas fa-home'></i> Home Page </a>
		 	  <a class="btn btn-danger my-2" href="statistics_report_pdf.php" target="_blank"><i class="fa fa-print"></i> Print</a> </span>
    </div>
	</div>
	  <table class="table table-striped pt-2">
		<thead class="table-dark">
			<tr>
				<!--<th>অর্থবছর</th>
				<th>Name</th>
				<th>Title</th>
				<th>Company Name</th>
				<th>Name</th>
				<th>Title</th>
				<th style="display:none;">Address</th>
				<th>City</th>-->
				
				
				 <th class='text-center pb-4'>অর্থবছর</th>
				  <th class='text-center pb-4'>পুরুস্কার সংখ্যা</th>
				
				 <th class='text-center pb-4'> কর্মকর্তার সংখ্যা</th>
				
				 
			</tr>
		</thead>
		<tbody>
		
		
			<?php 
			//if (mysqli_num_rows($res) > 0) {
			
			while($row=mysqli_fetch_array($res)){
				// $total1=array($row['fiscal_year']);
			//$total2=count($row['no_of_officer']);
				
				?>
			<tr>
			
				<td class='text-center pb-1'><?php echo $row['fiscal_year']?></td>
				<td class='text-center pb-1'><?php echo $row['no_of_award']?></td>
			
				<td class='text-center pb-1'><?php echo $row['no_of_officer']?></td>
				
		
				<!--<td style="display:none;"><?php //echo $row['address']?></td>-->
				
			</tr>
		
	
			<?php //}
			} 
			//mysqli_close($conn);
			?>

		</tbody>
		
		<tr class="end"><!--Total tr-->
		<?php 
		$sql=mysqli_query($conn,"SELECT fiscal_year, count(fiscal_year) no_of_award, COUNT(inventors_emp_id) no_of_officer FROM innovation_tbl");

			while($row=mysqli_fetch_array($sql)){
			
			?>
		
		<td class='text-center pb-1'>Total</td>
		
		<td class='text-center pb-1'><?php echo $row['no_of_award']?></td>
		<td class='text-center pb-1'><?php echo $row['no_of_officer']?></td>
		</tr>
		<?php //}
			} 
			//mysqli_close($conn);
			?>
		
	  </table>
	  
   </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
  <script src="DataTables/jQuery v3.6.0.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="DataTables/datatables.js"></script>
  <script src="bootstrap/js/bootstrap.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="bootstrap/js/npm.js"></script>
  <script>
  $(document).ready( function () {
		$('.table').DataTable({
			 "order": [[1, "desc"]] //"2" is my date array position
		});
  });
  </script>
</body>
</html>