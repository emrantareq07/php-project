<?php
include('db/db.php');
$res=mysqli_query($conn,"SELECT * FROM innovation_tbl ORDER BY id DESC");
//$res=mysqli_query($conn,"SELECT id,fiscal_year,title_of_invention,inventors_name,inventors_designation,
 //inventors_emp_id,proposed_workplace,des_of_invention, imple_status,replicate_eligibility FROM innovation order by id desc");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dynamic Datatable</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="datatables.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
<!--
 
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"> 
  <link href="//db.onlinewebfonts.com/c/aec382221b330b8581963c1bcc7c61d9?family=Nikosh" rel="stylesheet" type="text/css"/> 
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">-->

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
.bs-example{
            margin: 10px;
        }

  </style>
 
</head>
<body>
  <div class="bs-example">
	<div class="container-fluid border shadow" style="width:100%;">
	
	<div class="row">    
	
	<div class="col-sm-8"> <h2 align="right" class="my-3 text-center text-dark bg-warning rounded">বাস্তবায়িত উদ্ভাবনী ধারণা, সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ </h2></div>
	<div class="col-sm-4">
                            
         <span class="float-end"><a href="statistics_report.php" class="btn btn-success text-white my-3"> Statistics Report </a>
		 	  <a class="btn btn-danger my-3" href="create_pdf.php" target="_blank"> Print</a> </span>
			  
    </div>
	</div>
	  <table class="table table-striped table-hover pt-2">
		<thead class="table-success">
			<tr>
				<!--<th>অর্থবছর</th>
				<th>Name</th>
				<th>Title</th>
				<th>Company Name</th>
				<th>Name</th>
				<th>Title</th>
				<th style="display:none;">Address</th>
				<th>City</th>-->
				
				<th class='text-center pb-4'>#</th>
				 <th class='text-center pb-4'>অর্থবছর</th>
				  <th class='text-center pb-4'>সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</th>
				 <th>উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
				 <th class='text-center pb-4'> সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা </th>
				 <th class='text-center pb-4'> বাস্তাবয়নের অবস্থা</th>
				  <th class='text-center pb-4'>রেপ্লিকেট যোগ্যতা</th>
				  <th class='text-center pb-4'> ফলাফল </th>
				  <th class='text-center pb-4'>সেবার লিংক</th>
				  <th class='text-center pb-4'>মন্তব্য</th>
			</tr>
		</thead>
		<tbody>
		
		
			<?php 
			//if (mysqli_num_rows($res) > 0) {
			
			while($row=mysqli_fetch_array($res)){?>
			<tr>
			<td><?php echo $row['id']?></td>
				<td><?php echo $row['fiscal_year']?></td>
				<td><?php echo $row['title_of_invention']?></td>
				<td><?php echo $row['inventors_name']."<br>".$row['inventors_designation']."<br>".$row['inventors_emp_id']."<br>".$row['proposed_workplace'] ?></td>
				
				
				<td><?php echo $row['des_of_invention']?></td>
				<td><?php echo $row['imple_status']?></td>
				<td><?php echo $row['replicate_eligibility']?></td>
				<td><?php echo $row['feedback']?></td>
				<td><?php echo $row['service_link']?></td>
				<td><?php echo $row['remarks']?></td>
				
				<!--<td style="display:none;"><?php //echo $row['address']?></td>-->
				
			</tr>
			<?php //}
			} 
			//mysqli_close($conn);
			?>
		</thead>
	  </table>

   </div>
   </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="datatables.js"></script>
  <script>
  $(document).ready( function () {
		$('.table').DataTable({
			 "order": [[1, "desc"]], //"2" is my date array position
			 lengthMenu: [
            [5,10, 25, 50, -1],
            [5,10, 25, 50, 'All'],
        ],
		});
  });
  </script>
</body>
</html>