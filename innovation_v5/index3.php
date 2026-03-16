<?php
include('db/db.php');
$res=mysqli_query($conn,"SELECT * FROM innovation ORDER BY id desc");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dynamic Datatable</title>
 <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="datatables.css" rel="stylesheet">
  <!-- Latest compiled and minified CSS 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  
 
   <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  
</head>
<body>
	<div class="container p-3 my-3 border shadow" style="margin-top:50px;width:100%;">
	  <table id="example" class="table table-striped pt-2">
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
				  <th class='text-center pb-4'>উদ্ভাবনের শিরোনাম</th>
				 <th>উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
				 <th class='text-center pb-4'> উদ্ভাবনের বর্নণা </th>
				 <th class='text-center pb-4'> বাস্তাবয়নের অবস্থা</th>
				  <th class='text-center pb-4'>রেপ্লিকেট যোগ্যতা</th>
			</tr>
		</thead>
		<tbody>
			<?php while($row=mysqli_fetch_assoc($res)){?>
			<tr>
				<td><?php echo $row['fiscal_year']?></td>
				<td><?php echo $row['title_of_invention']?></td>
				<td><?php echo $row['inventors_name']."<br>".$row['inventors_designation']."<br>".$row['inventors_emp_id']."<br>".$row['proposed_workplace'] ?></td>
				
				
				<td><?php echo $row['des_of_invention']?></td>
				<td><?php echo $row['imple_status']?></td>
				<td><?php echo $row['replicate_eligibility']?></td>
				
				<!--<td style="display:none;"><?php //echo $row['address']?></td>-->
				
			</tr>
			<?php } ?>
		</thead>
	  </table>
   </div>
  


</body>


<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="datatables.js"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script> 
   <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
 <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script> 
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
 
 <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js "></script> 


  <script>
  $(document).ready(function() {
    var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
    } );
 
    table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
} );
  
  
 // $(document).ready( function () {
		//$('.table').DataTable();
  //});
  </script>
</html>