<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>-->
  <title>BCIC PMIS</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Latest compiled and minified CSS -->

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
thead th {
    background-color: #f0f2f5;
    color: black;
	border-right: 2px solid #7d21bf !important;
	border-bottom: 1px solid black !important; 
	
}
 body { 
    background-color:white;
 }

</style>

</head>
<body>	

<?php //include('../includes/header.php');?>

 <div class="container p-2 my-2 border shadow flex-container" >

  <div class="row">
    <div class="col-sm-4"> <span class=" mx-auto d-block text-left pt-1"><a href="home.html">
	<i class="fa fa-arrow-left" style="font-size:36px;color:#7d21bf"></i></a></span>
	
	</div>
	<!-- Start Main Content-->
	<div class="col-sm-4"> 
	<img src="images/img.png" class="rounded-circle img-thumbnail border shadow border-warning mx-auto d-block" alt="Cinque Terre" width="160" height="130">
	<div>
	<span class=" mx-auto d-block text-center pt-1 pb-1 d-flex justify-content-around">
	
	<!-- <a href="tel:ফোন (অফিস)#Telephone: 02-48315085#মোবাইল#Mobile:  01713062404, Tele: 02-48315085 (Office), 02-48315084(Bungalow);  Fax: 49349999 (Office), 8322255(Bungalow)#ফোন (বাসা)#Bungalow: 02-48315084"> -->
	<!-- <i class="fa fa-phone-square" style="font-size:36px;color:#691066"></i></a> -->
	<!-- <a href="sms:Mobile:  01713062404, Tele: 02-48315085 (Office), 02-48315084(Bungalow);  Fax: 49349999 (Office), 8322255(Bungalow)"> -->
	<!-- <i class="fa fa-pencil-square" style="font-size:36px;color:#691066"></i></a> -->
	<!-- <a href="mailto:emrantareq@yahoo.com"> -->
	<!-- <i class="fa fa-envelope-square" style="font-size:36px;color:#691066"></i></a> -->
	<!-- <i class="fa fa-save" style="font-size:36px;color:#691066"></i> -->
	
	
	<a href="tel:ফোন (অফিস)#Telephone: 02-48315085#মোবাইল#Mobile:  01713062404, Tele: 02-48315085 (Office), 02-48315084(Bungalow);  Fax: 49349999 (Office), 8322255(Bungalow)#ফোন (বাসা)#Bungalow: 02-48315084">
	<i class="fa fa-phone-square" style="font-size:36px;color:#691066"></i></a>
	<a href="sms:Mobile:  01713062404, Tele: 02-48315085 (Office), 02-48315084(Bungalow);  Fax: 49349999 (Office), 8322255(Bungalow)">
	<i class="fa fa-pencil-square" style="font-size:36px;color:#691066"></i></a>
	<a href="mailto:emrantareq@yahoo.com">
	<i class="fa fa-envelope-square" style="font-size:36px;color:#691066"></i></a>
	<i class="fa fa-save" style="font-size:36px;color:#691066"></i>
	
	<!--
	<i class="fa fa-home-square" style="font-size:48px;color:red"></i>
	<i class="fa fa-home" style="font-size:36px;color:red"></i>
	<i class="fa fa-reorder-square" style="font-size:48px;color:red"></i>
	<i class="fa fa-arrow-circle-o-left" style="font-size:48px;color:#691066"></i>-->
	</span></div>
	</div>
	<div class="col-sm-4"> <span class=" mx-auto d-block text-right pt-1">
	<a href="home.html">
	<i class="fa fa-home" style="font-size:36px;color:#7d21bf"></i></a></span></div>
	<!--End Main content-->
 
  


 
 </div>
 
<table class="table table-bordered table-hover table-striped p-1 my-1">
<tbody>
  <?php
	include('db/db.php');
		
		$sql="SELECT name,designation,division_name,section_name,phone_office,phone_home,intercom,mobile,email
		FROM tel_tbl where designation='Chairman'";
		
    			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			  // output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
			?>
<tr ><thead>
<th class="col-sm-2">নাম</th>
<td class="col-sm-10 text-uppercase"><?php echo $row['name']; ?></td>
</tr>
<tr><th >পদবি</th>
<td ><?php echo $row['designation']; ?></td>
</tr>
<tr><th>দপ্তর</th>
<td><?php echo $row['division_name']; ?> </td>
</tr>
</thead>
  <?php
			  }
			}
			else {
				echo "<p class='btn btn-danger'> Record Not Found!!!</p>";
			}
            //}?>
</tbody>
</table>

<table class="table table-bordered table-hover table-striped p-2 my-2">
<tbody>
<tr><thead>
<th class="col-sm-2">ই-মেইল</th>
<td class="col-sm-10">divcomdhaka@mopa.gov.bd</td>
</tr>
<tr><th>ফোন (অফিস)</th>
<td>Telephone: 02-48315085</td>
</tr>
<tr><th>ফোন (বাসা)</th><td>	Bungalow: 02-48315084 </td></tr>
<tr><th>মোবাইল</th><td>Mobile: 01713062404, Tele: 02-48315085 (Office) </td></tr>
<tr><th>ফ্যাক্স</th><td>Fax: 49349999 (Office) Fax: 8322255(Bungalow)</td></tr>
</thead>
</tbody></table>
</div>



</body>
</html>