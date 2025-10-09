<?php include_once('include/header.php');?>
<style>
 body {
  font-family: Arial, Helvetica, sans-serif;
}

[data-href] {
    cursor: pointer;
	cursor: hand;
}
</style>
 <script>
//$('#main').hide();
$(document).ready(function(){

//$("#splash").fadeOut(4000);
$( "#main" ).fadeIn();
});


</script> 
<!-- <div class="container"> -->
  <!-- <div class="row justify-content-center"> -->
    <!-- <div class="col-sm-4 "></div> -->
    <!-- <div class="col-sm-4 " id="splash" ><h3 class="pt-4">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h3> -->
	<!-- <img class="mx-auto d-block " src="images/bdlogo2.png" width="170" height="170"/> -->
	<!-- <h3>শিল্প মন্ত্রণালয় </h3> -->
	<!-- <img class="mx-auto d-block" src="images/bcic_logo.png" width="170" height="170"/> -->
	<!-- <h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3> -->
	<!-- <h4>(বিসিআইসি ই-ডিরেক্টরি)</h4> -->
	<!-- </div> -->
    <!-- <div class="col-sm-4 "></div> -->
  <!-- </div> -->
<!-- </div> margin-left-5 margin-right-5-->
 
 <div class="container mt-3 p-2 shadow-lg bg-white border" id ="main">
  <div class="row">
  <div class="col-sm-10">
  <div class="card bg-warning text-white text-center" style="height:50px">
    <div class="card-body"><h4 class="text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4></div>
  </div>
  <!--<h4 class="bg-danger text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4>-->
  </div>

    <div class="col-sm-2">
  <div class="card bg-light text-white text-center" style="height:50px">
    <div class="card-body">
      <a href="show_all_contact.php" class="btn btn-success p-1" style="text-decoration: none; margin-top: -12px; font-size: 14px">Show All Contact</a></div>
  </div>
  <!--<h4 class="bg-danger text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4>-->
  </div>
 
<div class="description-container mt-3">
<!--Start Chairman Section-->
<div class="accordion">
    <h2 class="section-header text-muted">চেয়ারম্যান সচিবালয়</h2>
    <div class="panel">
	<h3 class="text-muted text-center">চেয়ারম্যান সচিবালয় <hr></h3>
	
	<table class="table table-bordered table-hover border-muted table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
   
    <tbody>
	<?php
	include('db/db.php');
		
		$sql="SELECT id,name,designation,division_name,section_name,phone_office,phone_home,intercom,mobile,email,image
		FROM tel_tbl where division_name='Chairman Secretariat'";
		
    		$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			  // output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  $image=$row['image']
			?>
	<!--<tr class='clickable-row' data-href='chairman.php?id=<?php //echo $rows["id"]; ?>'>-->
      <tr class='clickable-row ' data-href='chairman_sec.php?id=<?php echo $row["id"]; ?>' >
	  <td width="30" ><?php echo $row['id']; ?></td>
        <td width="90"><img src="upload/<?php echo $row['image']; ?>" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" 
		width="90" height="50"></td>
		<td><h4 ><?php echo $row['name']; ?></h4>
		<p class="text-muted"><?php echo $row['designation']; ?></p></td>
		 </tr>
	    </tbody>
		<?php
			  }
			}
			else {
				echo "<p class='btn btn-danger'> Record Not Found!!!</p>";
			}
            //}?>
  </table>
      <p class="text-muted"> </p>
    </div>
  </div>
  
  <!--Start Chairman Section-->
<div class="accordion">
    <h2 class="section-header text-muted">উর্ধ্বতন মহাব্যবস্থাপক</h2>
    <div class="panel">
	<h3 class="text-muted text-center">উর্ধ্বতন মহাব্যবস্থাপক <hr></h3>
	
	<table class="table table-bordered table-hover border-muted table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
   
    <tbody>
	<?php
	include('db/db.php');
		
		$sql="SELECT id,name,designation,division_name,section_name,phone_office,phone_home,intercom,mobile,email,image
		FROM tel_tbl where designation='Sr. GM(Admin)'";
		
    		$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
			  // output data of each row
			  while($row = mysqli_fetch_assoc($result)) {
				  $image=$row['image']
			?>
	<!--<tr class='clickable-row' data-href='chairman.php?id=<?php //echo $rows["id"]; ?>'>-->
      <tr class='clickable-row ' data-href='sr_gm_sec.php?id=<?php echo $row["id"]; ?>' >
	  <td width="30" ><?php echo $row['id']; ?></td>
        <td width="90"><img src="upload/<?php echo $row['image']; ?>" class="rounded-circle img-thumbnail border shadow border-warning " alt="Cinque Terre" 
		width="90" height="50"></td>
		<td><h4 ><?php echo $row['name']; ?></h4>
		<p class="text-muted"><?php echo $row['designation']; ?></p></td>
		 </tr>
	    </tbody>
		<?php
			  }
			}
			else {
				echo "<p class='btn btn-danger'> Record Not Found!!!</p>";
			}
            //}?>
  </table>
      <p class="text-muted"> </p>
    </div>
  </div>
<!--End Board of Directors
 
  <!--Start Sr. GM Admin-->
<div class="accordion">
    <h2 class="section-header text-muted">উর্ধ্বতন মহাব্যবস্থাপক</h2>
    <div class="panel">
	
	<h3 class="text-muted text-center">উর্ধ্বতন মহাব্যবস্থাপক<hr></h3>
	<table class="table table-bordered table-hover border-success table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
    <thead class="bg-warning">
      <tr >
        <th>নাম</th>
        <th>পদবি</th>
		<th>শাখা ও ডিভিশন/অফিস</th>
        <th class="text-center">ফোন (অফিস)</th>
		<th class="text-center">ফোন (বাসা)</th>
		<th class="text-center">ইন্টারকম</th>
		<th class="text-center">মোবাইল</th>
		<th>ই-মেইল</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>জনাব মোহাম্মদ সেলিম</td>
        <td>	ঊর্ধ্বতন মহাব্যবস্থাপক (প্রশাসন) (চলতি দায়িত্ব)</td>
        <td>প্রশাসন বিভাগ, বিসিআইসি প্রধান কার্যালয়</td>
		 <td>০২-২২৩৩৮২০৮৯</td>
        <td></td>
        <td></td>
		  <td>০১৭২৬-০৪৬৮৮২</td>
		  <td>corporate.dir@bcic.gov.bd</td>
      </tr>
      
    </tbody>
  </table>
      <p class="text-muted">
	  
	  
	  
      </p>
    </div>
  </div>
 
 
  <div class="accordion">
    <h2 class="section-header text-muted">Chairman Section</h2>
    <div class="panel">
	
	<p class="text-muted text-center">চেয়ারম্যান সচিবালয় <hr></p>
	<table class="table table-bordered table-hover border-primary table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
    <thead>
      <tr >
        <th>Name</th>
        <th>Designation</th>
		<th>Section & Division/Office</th>
        <th class="text-center">Telephone No.(Off.)</th>
		<th class="text-center">Telephone No.(Res.)</th>
		<th class="text-center">Mobile No.</th>
		<th>Email ID.</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Chairman (Grade-1)</td>
        <td>BCIC</td>
		 <td>123456</td>
        <td>123456</td>
        <td>123456</td>
		  <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
		 <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		  <td>john@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
		 <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		  <td>john@example.com</td>
      </tr>
    </tbody>
  </table>
      <p class="text-muted">
	  
	  
	  
      </p>
    </div>
  </div>
  <div class="accordion">
    <h2 class="section-header text-muted">Chairman Section</h2>
    <div class="panel">
	
	<p class="text-muted text-center">চেয়ারম্যান সচিবালয় <hr></p>
	<table class="table table-bordered table-hover border-primary table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
    <thead>
      <tr >
        <th>Name</th>
        <th>Designation</th>
		<th>Section & Division/Office</th>
        <th class="text-center">Telephone No.(Off.)</th>
		<th class="text-center">Telephone No.(Res.)</th>
		<th class="text-center">Mobile No.</th>
		<th>Email ID.</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Chairman (Grade-1)</td>
        <td>BCIC</td>
		 <td>123456</td>
        <td>123456</td>
        <td>123456</td>
		  <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
		 <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		  <td>john@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
		 <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		  <td>john@example.com</td>
      </tr>
    </tbody>
  </table>
      <p class="text-muted">
	  
	  
	  
      </p>
    </div>
  </div>
  <div class="accordion">
    <h2 class="section-header text-muted">Chairman Section</h2>
    <div class="panel">
	
	<p class="text-muted text-center">চেয়ারম্যান সচিবালয় <hr></p>
	<table class="table table-bordered table-hover border-primary table-responsive{-sm|-md|-lg|-xl|-xxl} shadow-sm bg-white">
    <thead>
      <tr >
        <th>Name</th>
        <th>Designation</th>
		<th>Section & Division/Office</th>
        <th class="text-center">Telephone No.(Off.)</th>
		<th class="text-center">Telephone No.(Res.)</th>
		<th class="text-center">Mobile No.</th>
		<th>Email ID.</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Chairman (Grade-1)</td>
        <td>BCIC</td>
		 <td>123456</td>
        <td>123456</td>
        <td>123456</td>
		  <td>john@example.com</td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td>mary@example.com</td>
		 <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		  <td>john@example.com</td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td>july@example.com</td>
		 <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
		  <td>john@example.com</td>
      </tr>
    </tbody>
  </table>
      <p class="text-muted">
	  
	  
	  
      </p>
    </div>
  </div>
  
  <div class="col-sm-12 mt-2">
  <div class="card bg-dark text-white text-center " style="height:50px;">
    <div class="card-title"><h6 class="text-center float-end mt-2">Design & Developed By Md. Tareq Emran, Programmer, BCIC.</h6></div>
  </div>
  
  <!--<h4 class="bg-danger text-center" style="color:#7d21bf;">বিসিআইসি ই-ডিরেক্টরি</h4>-->
  </div>
</div>
</div>

</div>
<script>
  $(".section-header").addClass("closed");

$('.section-header').click(function() {
  let $this = $(this);
  let $others = $(".section-header").not($this);
  
  $others.addClass("closed").removeClass('active');;
  $others.siblings().slideUp();

  $this.toggleClass("closed active");
  $this.siblings().slideToggle();

  return false;
});

//For Clickable row
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
  </script>
<?php include_once('include/footer.php');?>


